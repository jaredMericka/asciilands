<?php

class Quest
{
	public $owner;			// Dude doing the quest
	public $name;			// Name of the quest
	public $id;
	public $description;	// Description of the quest

	/** This should be a nested array with a structure as follows:

	$tasks =
	[
		$taskInSeries,
		$taskInSeries,
		[
			$taskInParallel,
			$taskInParallel,
			$taskInParallel,
		],
		[
			$taskInParallel,
			$taskInParallel,
		],
		$taskInSeries,
		$taskInSeries,
		[
			$taskInParallel,
			$taskInParallel,
		],
		...ETC
	]

	This way, quests can be arranged with a number of tasks that can be
	completed in any order and other tasks that must be completed in order.
	*/
	public $tasks;

	public $reward;
	public $penalty;

	public $complete = false;
	public $failed = false;

	public $hidden = false;
	public $taskNumber = 0;

	public $retryDelayHours = 1;

	public function __construct($name, $description, $tasks, $reward = null, $penalty = null)
	{
		$this->name			= $name;
		$this->id			= id();
		$this->description	= $description;

		foreach ($tasks as &$task)
		{
			if (!is_array($task))
			{
				$task = [$task];
			}

			foreach ($task as &$t)
			{
				$t->quest = $this;
				$t->number = $this->taskNumber++;
			}
		}

		if ($reward)	$this->reward	= is_array($reward)		? $reward	: [$reward];
		if ($penalty)	$this->penalty	= is_array($penalty)	? $penalty	: [$penalty];

		$this->tasks = $tasks;
	}

	public function getAjaxObject()
	{
		$ajaxObj = new stdClass();

		$ajaxObj->name	= $this->name;
		$ajaxObj->desc	= $this->description;
		$ajaxObj->id	= $this->id;

		if ($this->reward)
		{
			$ajaxObj->rwd	= '';

			foreach ($this->reward as $reward)
			{
				if (!$reward->hidden) $ajaxObj->rwd .= "{$reward->description} ";
			}
		}

		if ($this->penalty)
		{
			$ajaxObj->pnlt	= '';

			foreach ($this->penalty as $penalty)
			{
				if (!$penalty->hidden) $ajaxObj->pnlt .= $penalty->description;
			}
		}

		if ($this->complete)	$ajaxObj->complete	= true;
		if ($this->failed)		$ajaxObj->failed	= true;

		$ajaxObj->tasks	= [];

		foreach ($this->tasks as $phase)
		{
			foreach ($phase as $task)
			{
				if (!$task->hidden) $ajaxObj->tasks[] = $task->getAjaxObject();
			}
		}

		return $ajaxObj;
	}

	public function getActiveTasks()
	{
		if ($this->complete || $this->failed) return [];

		$activeTasks = [];
		foreach ($this->tasks as $phase)
		{
			$allFailing = true;

			foreach ($phase as $task)
			{
				if ($task->complete)
				{
					if ($task->failureCondition)
					{
						$this->fail();
						return [];
					}
					continue;
				}

				if (!isset($activeTasks[$task->EOI])) $activeTasks[$task->EOI] = [];
				if (!$task->active) $task->onActivate();

				if ($task->complete) continue; // We have to check this a second time in case the quest was complete by the onActivate() function.

				$task->active = true;

				console_echo("Activating a task with the description: {$task->description}", '#fff');

				if ($allFailing && !$task->failureCondition) $allFailing = false;

				$activeTasks[$task->EOI][] = $task;
			}
			// Put something in here to make sure there aren't ONLY failing conditions left.
			if ($allFailing)
			{
				foreach ($activeTasks as $phase)
				{
					foreach ($phase as $task)
					{
						console_echo("Deactivating task \"{$task->description}\"");
						$task->active = false;
					}
				}

				$activeTasks = [];
			}

			if (!empty($activeTasks)) break;
		}

		if (empty($activeTasks))
		{
			$this->complete();
		}

		return $activeTasks;
	}

	public function complete()
	{
		$this->complete = true;
		if ($this->reward)
		{
			foreach ($this->reward as $reward)
			{
				$reward->deliver($this->owner);
			}
		}
//		$this->owner->completedQuests[$this->name] = $_SERVER['REQUEST_TIME_FLOAT'];
		$this->owner->completedQuests = [$this->name => $_SERVER['REQUEST_TIME_FLOAT']] + $this->owner->completedQuests;
	}

	public function fail()
	{
		foreach ($this->tasks as $phase)
			foreach ($phase as $task)
			{
				$task->active = false;
			}
		$this->failed = true;
		if ($this->penalty)
		{
			foreach ($this->penalty as $penalty)
			{
				$penalty->deliver($this->owner);
			}
		}

		$retryDelaySeconds = $this->retryDelayHours * 3600;

		$this->owner->failedQuests[$this->name] = $_SERVER['REQUEST_TIME_FLOAT'] + $retryDelaySeconds;
	}
}

trait QuestCapability
{
	// TODO: Implement quest abandoning. Abandoned quests should deliver their
	// penalties unless abandoned in a town but delay time for re-attempt should
	// be halved or abolished (if too short).

	// Array of current quests. Format should be {name} => {quest object}.
	public $quests = [];

	public $activeTasks = [];

	// Array of completed quests. Format should be {name of quest} => {completion timestamp}.
	public $completedQuests = [];

	// Array of failed/abandoned quests. Format should be {name of quest} => {timestamp when re-attempt is allowed}.
	public $failedQuests = [];
	public $abandonedQuests = [];

	/**
	 *
	 * @param Quest $quest - The qust to be added to the quest list of the dude.
	 * @return string - Returns a QS variable that describes the status of the quest passed in.
	 */
	public function addQuest(Quest $quest)
	{
		if (isset($this->completedQuests[$quest->name])) return QS_COMPLETE;

		if (isset($this->failedQuests[$quest->name]))
		{
			if ($this->failedQuests[$quest->name] > $_SERVER['REQUEST_TIME_FLOAT']) return QS_FAILED;
		}

		if (isset($this->abandonedQuests[$quest->name]))
		{
			if ($this->abandonedQuests[$quest->name] > $_SERVER['REQUEST_TIME_FLOAT']) return QS_ABANDONED;
		}

		if (isset($this->quests[$quest->name]))
		{
			if ($this->quests[$quest->name]->failed)
			{
				console_echo("Overwriting failed quest \"<<#faa>>{$quest->name}<>\".", '#ffa');
				unset($this->quests[$quest->name]);
			}
			else
			{
				console_echo("Attempting to add quest \"<<#ffa>>{$quest->name}<>\" to someone who already has that quest.", '#faa');
				return QS_IN_PROGRESS;
			}
		}
		$quest->owner = $this;

		$this->quests[$quest->name] = $quest;

		update_quests([$quest]);

		return QS_NOT_STARTED;
	}

	public function getQS($quest)
	{
		if (!is_string($quest)) $quest = $quest->name;

		if (isset($this->quests[$quest]))			return QS_IN_PROGRESS;
		if (isset($this->completedQuests[$quest]))	return QS_COMPLETE;
		if (isset($this->failedQuests[$quest]))		return QS_FAILED;
		if (isset($this->abandonedQuests[$quest]))	return QS_ABANDONED;

		return QS_NOT_STARTED;
	}

	public function getActiveTasks()
	{
		$activeTasks = [];

		foreach ($this->quests as $quest)
		{
			$questActiveTasks = $quest->getActiveTasks();
			foreach ($questActiveTasks as $EOI => $tasks)
			{
				if (isset($activeTasks[$EOI]))
				{
					$activeTasks[$EOI] = array_merge($activeTasks[$EOI], $tasks);
				}
				else
				{
					$activeTasks[$EOI] = $tasks;
				}
			}
		}
		$this->activeTasks = $activeTasks;

		return $activeTasks;
	}

	public function handleEventOfInterest($EOI)
	{
		if (isset($this->activeTasks[$EOI])) console_echo('Handling event of interest: ' . $EOI . ' count: '.count($this->activeTasks[$EOI]), '#faf');

		$completedTasks = [];

		if (!isset($this->activeTasks[$EOI])) return;

		$args = func_get_args();
		array_shift($args);				// Gets rid of the $EOI value.
		$args = array_values($args);	// Rebase the array at 0.

		$questsForRefresh = [];
		foreach ($this->activeTasks[$EOI] as $task)
		{
			$task->check($args);

			if ($task->complete)
			{
				$questsForRefresh[] = $task->quest;
				$completedTasks[] = $task;
			}
		}

		if ($questsForRefresh) update_quests($questsForRefresh);

		return $completedTasks;
	}
}

abstract class QuestResult
{
	public $description;
	public $hidden		 = false;

	public function __construct($description)
	{
		$this->description = $description;
	}

	abstract function deliver (Player $recipient);
}