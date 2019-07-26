<?php

const CNS_SYSTEM		= -1;

const CNS_DEFAULT		= 1;
const CNS_ERRORS		= 2;
const CNS_UPDATES		= 3;

const CNS_MAP_INIT		= 10;
const CNS_BEHAVIOUR		= 11;
const CNS_SPRITE		= 12;
const CNS_ATTACK		= 13;
const CNS_ITEMS			= 14;
const CNS_DSs			= 15;
const CNS_SRZ			= 16;

$consoleStreams = [
	CNS_DEFAULT			=> 'Default',
	CNS_ERRORS			=> 'Error Info',
	CNS_UPDATES			=> 'Update Content',
	CNS_MAP_INIT		=> 'Map Initialisation',
	CNS_BEHAVIOUR		=> 'Behaviour',
	CNS_SPRITE			=> 'Sprite Manipulation',
	CNS_ATTACK			=> 'Attack Sata',
	CNS_ITEMS			=> 'Items',
	CNS_DSs				=> 'Dude Stats',
	CNS_SRZ				=> 'Micro-serialization',
];

$consoleStreams_offDefault = [
	CNS_DSs,
	CNS_SRZ,
];

if (!isset($_GET['console']))
{
	$consoleTimeStamp = microtime(true);

	function console_echo ($string, $colour = null, $CNS = null)
	{
		global $consoleTimeStamp;

		$CNS = $CNS ? $CNS : CNS_DEFAULT;

		if (isset($CNS)
			&& isset($_SESSION['console']['streams'])
			&& isset($_SESSION['console']['streams'][$CNS])
			&& !$_SESSION['console']['streams'][$CNS]
		) return;

		if (!DEV_MODE) return;

//		if ($STREAM) $string = "[{$STREAM}] {$string}";

		if (isset($colour))
        {
            for($i = 0; $i < strlen($colour); $i++)
            {
				if (is_numeric($colour[$i])) $colour[$i] = 'a';
			}
		}

		$string = str_replace(
		[
			'<<',
			'>>',
			'<>'
		],
		[
			'<span style="color:',
			';">',
			'</span>',
		],
		$string);

		$string = ($colour ? "<span style=\"color:{$colour};\">{$string}</span>" : $string);

		if (isset($_SESSION['console'][$consoleTimeStamp]['data']))
			$_SESSION['console'][$consoleTimeStamp]['data'] .= "<br>$string";
		else
			$_SESSION['console'][$consoleTimeStamp]['data'] = $string;
	}

	function console_var_dump($var, $colour = null, $CNS = null)
	{
		if (!DEV_MODE) return;

		if (!$colour) $colour = '#aaf';

		ob_start();
		var_dump($var);
		$string = ob_get_clean();
		$string = "<pre>{$string}</pre>";
		console_echo($string, $colour, $CNS);
	}

	function console_stack_trace($colour = '#afa')
	{
		ob_start();
		debug_print_backtrace();
		$trace = ob_get_clean();

		$trace = str_replace("\n", '<br>', $trace);

		console_echo($trace, $colour);
	}

	function console_class_list($array, $colour = '#aff')
	{
		console_var_dump(buildClassList($array), $colour);
	}

	function buildClassList($array)
	{
		$classNames = [];

		foreach ($array as $key => $thing)
		{
			if (is_array($thing))
			{
				$classNames[$key] = buildClassList($thing);
			}
			elseif (is_scalar($thing))
			{
				$thing = (string)($thing);
				if (strlen($thing) > 100) $thing = substr($thing, 0, 100) . '...';
				$classNames[$key] = (string)($thing);

			}
			else
			{
				$classNames[$key] = get_class($thing);
			}
		}

		return $classNames;
	}

	function console_forceScroll()
	{
		global $consoleTimeStamp;

		$_SESSION['console'][$consoleTimeStamp]['FS'] = true;
	}

	function console_swatch($colour)
	{
		return "<span style=\"background-color:{$colour}\">&nbsp;&nbsp;</span> {$colour}";
	}

	function console_sprite(Sprite $sprite)
	{
		$string = '';

		foreach ($sprite->frames as $frame)
		{
			$string .= '<div class="consoleSprite">';
			for ($e = 0; $e < 6; $e++)
			{
				if (isset($frame[$e]))
				{
					$element = $frame[$e];
//					$char = htmlentities($element->char);

					$string .= "<span style=\"color:{$element->fg};";
					$string .= (isset($element->bg) ? "background-color:{$element->bg};" : '');
//					$string .= "\">{$char}</span>";
					$string .= "\">{$element->char}</span>";
				}
				else
				{
					$string .= '&nbsp;';
				}

				if ($e === 2) $string .= '<br>';
			}
			$string .= '</div>';
		}



		return $string;
	}

    function console_errorHander($errno, $errstr, $errfile, $errline)
	{
		global $consoleTimeStamp;

		$errfile = basename($errfile);
		$errmess = "<<#faa>>{$errfile}<> - [<<#fff>>{$errline}<>]:<br>{$errstr}\n";

		foreach(debug_backtrace(null, 10) as $level => $trace)
		{
			if (!$level) continue;

			$function	= isset($trace['function'])	? $trace['function']			: '';
			$line		= isset($trace['line'])		? "[<<#fff>>{$trace['line']}<>]"	: '';
			$file		= '';
			$class		= isset($trace['class'])	? $trace['class']				: '';
			$object		= isset($trace['object'])	? get_class($trace['object'])	: '';
			$type		= isset($trace['type'])		? "<<#faa>>{$trace['type']}<>"	: '';
			$args		= '';

			if (isset($trace['args']))
			{
				foreach ($trace['args'] as $arg)
				{
					if (is_object($arg)) $args .= get_class($arg) . ', ';
					elseif (is_array($arg)) $args .= 'array, ';
					else $args .= $arg . ', ';
				}
				$args = "({$args})";
			}

			if (isset($trace['file']))
			{
//				$file = $trace['file'];
				$file = explode('asciilands', $trace['file'])[1];
				$file = trim($file, '\\/');
				$file = "<<#faa>>{$file}<>";
			}

			$errmess .= "[{$level}]\t{$file}\t{$line}:\t{$object} {$class}{$type}{$function}{$args};\n";


		}

		console_echo($errmess, '#f55', CNS_ERRORS);
		$_SESSION['console'][$consoleTimeStamp]['error'] = true;
		return true;
	}

	function console_toogleStream ($STREAM, $forceState = null)
	{
		if (!$_SESSION['console']['streams'])
		{
			global $consoleStreams;
			global $consoleStreams_offDefault;

			$streams = [];

			foreach ($consoleStreams as $CNS => $val)
			{
				$streams[$CNS] = !in_array($CNS, $consoleStreams_offDefault);
			}

			$_SESSION['console']['streams'] = $streams;
			console_var_dump($streams);
		}

		if ($forceState === null)
		{
			$_SESSION['console']['streams'][$STREAM] = !$_SESSION['console']['streams'][$STREAM];
		}
		else
		{
			$_SESSION['console']['streams'][$STREAM] = $forceState;
		}

		$_SESSION['console']['updateStreams'] = true;
	}

	function console_setFrameTime()
	{
		global $consoleTimeStamp;

		$_SESSION['console'][$consoleTimeStamp]['frameTime'] = round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 4);
	}

	function console_setWakeTime()
	{
		global $consoleTimeStamp;

		$_SESSION['console'][$consoleTimeStamp]['wakeTime'] = round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 4);
	}

	function error_eater($errno, $errstr, $errfile, $errline) { }

    function console_update_location()
    {
        if (!DEV_MODE) return;

        global $player;
        $_SESSION['console']['location'] = "{$player->n_offset}:{$player->w_offset}";
    }

	function console_warning ($message)
	{
		trigger_error($message);
	}

	if (DEV_MODE)
	{
		set_error_handler('console_errorHander', -1);
	}
	else
	{
		set_error_handler('error_eater', -1);
	}
}
else
{
	$console = $_GET['console'];

	switch ($console)
	{
		case 'request':
			session_start();

			if (!isset($_SESSION['console'])) EXIT();

			$consoleData = new stdClass();

			if (isset($_SESSION['console']['location']))
			{
				$consoleData->location = $_SESSION['console']['location'];
				unset($_SESSION['console']['location']);
			}
			else
			{
				$consoleData->location = null;
			}

			if (isset($_SESSION['console']['updateStreams']))
			{
				$consoleData->streams = $_SESSION['console']['streams'];
				unset($_SESSION['console']['updateStreams']);
			}

			$consoleData->frameData = $_SESSION['console'];
			unset($consoleData->frameData['streams']);


			echo json_encode($consoleData);

			$streams = isset($_SESSION['console']['streams']) ? $_SESSION['console']['streams'] : [];

			$_SESSION['console'] = [
				'streams' => $streams
			];

			EXIT();
		break;

		case 'window':
			require '../core/constants.php';
			require '../core/config.cfg';
?>

<html>
	<head>
		<style type="text/css">
			*
			{
                padding:0px;
                margin:0px;
				font-family:lucida console, monospace;
				font-size:10px;
			}

			body
			{
				background-color:#000;
			}

			#content
			{
                display:block;
				overflow-y:auto;
				overflow-x:hidden;
                background-color:#000;
				color:#555;
				margin-bottom:<?php echo CHAR_HEIGHT; ?>px;
			}

			.panel:hover
			{
				background-color:#444;
			}

			.consoleSprite
			{
				display:inline-block;
			}

            #header
            {
                background-color:#000;
                color:#fff;
                position:fixed;
                top:0px;
                left:0px;
                right:0px;
            }

			#pinned
			{
				display:none;
				position:fixed;
				top:<?php echo CHAR_HEIGHT; ?>px;
				left:0px;
				right:0px;
				height:auto;
				max-height:<?php echo CHAR_HEIGHT * 3; ?>px;
				color:#668;
				background-color:#223;
				overflow:hidden;
				opacity:0.5;
				transition:max-height 0.3s, opacity 0.3s;
			}

			#pinned:hover
			{
				max-height:80%;
				opacity:0.95;
				overflow:auto;
				z-index:999;
			}

			#iframeContainer
			{
				position:fixed;
				top:<?php echo CHAR_HEIGHT; ?>px;
				right:0px;
				bottom:<?php echo CHAR_HEIGHT; ?>px;
				width:<?php echo CHAR_WIDTH * 10; ?>px;
				opacity:0.5;
				transition:width 0.3s, opacity 0.3s;
			}

			#iframeContainer:hover
			{
				opacity:0.9;
				width:<?php echo CHAR_WIDTH * 95; ?>px;
				z-index:999;
			}

			iframe
			{
				width:100%;
				height:100%;
				border:none;
			}

			#input
			{
				position:fixed;
				bottom:0px;
				left:0px;
				right:0px;
				top:auto;
				height:<?php echo CHAR_HEIGHT; ?>px;
				width:100%;
				border:none;
				background-color:#030;
				color:#afa;
				resize:none;
			}

			#graph
			{
				position:fixed;
				bottom:<?php echo CHAR_HEIGHT; ?>px;
				max-width:<?php echo CHAR_WIDTH * 10; ?>px;
				height:400px;
				overflow:hidden;
				opacity:0.2;
				transition:max-width 0.3s, opacity 0.3s;
			}

			#graph:hover
			{
				max-width:100%;
				opacity:0.8;
				padding-right:<?php echo CHAR_WIDTH * 4; ?>px;
			}

			.graphBar
			{
				border-top-style:solid;
				border-top-width:2px;

				background-color:#fff;
				width:4px;
				float:left;
				bottom:0px;
			}

			.graphBar .wakePoint
			{
				position:absolute;
				height:2px;
				width:4px;
				background-color:#000;
			}

			.graphBar.error { background-color:#f66 !important; }
			.graphBar.pin { background-color:#66f !important; }

			#graph:hover>.graphBar { background-color:#aaa; }
			.graphBar.pin:hover { background-color:#88f !important; }
			.graphBar.error:hover { background-color:#f88 !important; }

			.graphBar:hover
			{
				background-color:#fff !important;
				border-top-width:30px;
			}

			.graphBar.t1 { border-color: #0f0; }
			.graphBar.t2 { border-color: #ff0; }
			.graphBar.t3 { border-color: #f80; }
			.graphBar.t4 { border-color: #f00; }

			#pressedKeyCode
			{
				position:absolute;
				right:0px;
			}

			#mouseButtonCode
			{
				position:absolute;
				right:100px;
			}

			#streams
			{
				position:fixed;
				max-height:<?php echo CHAR_HEIGHT; ?>;
				background-color:#266;
				color:#9dd;
				bottom:<?php echo CHAR_HEIGHT; ?>px;
				right:<?php echo CHAR_WIDTH * 10; ?>px;
				width:<?php echo CHAR_WIDTH * 20; ?>px;
				overflow:hidden;
				opacity:0.5;
				transition:max-height 0.3s, opacity 0.3s;
			}

			#streams:hover
			{
				opacity:0.9;
				max-height:50%;
			}

			.stream
			{
				padding:5px;
			}

			.stream:hover
			{
				color:#fff;
				background-color:#488;
				cursor:pointer;
			}

			.stream.on
			{
				color:#afa;
			}

			.stream.off
			{
				color:#f77;
			}

			#streams span
			{
				display:block;
				background-color:#144;
				padding:3px;
				color:#fff;
			}

			#allStreamsContainer { text-align:center; }

			.allStreams
			{
				color:#266;
				display:inline-block;
				padding:6px;
				margin:6px;
				width: 40px;
				border-radius:6px;
				font-weight:bold;
			}

			.allStreams.on	{ background-color:#afa; }
			.allStreams.off	{ background-color:#f77; }

			.allStreams.on:hover	{ background-color:#dfd; }
			.allStreams.off:hover	{ background-color:#faa; }

		</style>
		<title id="title">Asciilands console</title>
	</head>
	<body>
        <pre id="header"><!--
			--><span id="location"></span><!--
			--><span id="pressedKeyCode"></span><!--
			--><span id="mouseButtonCode"></span><!--
		--></pre>
		<pre id="content">

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2584;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;
&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;&nbsp;&nbsp;&#x2588;&nbsp;&nbsp;&nbsp;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;&nbsp;&nbsp;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;&nbsp;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;&nbsp;&nbsp;&nbsp;&nbsp;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;&nbsp;&nbsp;&#x2588;
&nbsp;&nbsp;&nbsp;&nbsp;&#x2584;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2584;&nbsp;&nbsp;&#x2588;&#x2588;&#x2580;&nbsp;&#x258c;&#x2580;&#x2588;&#x2588;&#x2588;&#x2580;&nbsp;&nbsp;&#x2590;&#x2588;&#x2580;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2584;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2584;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2584;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2590;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2584;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&#x2580;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2580;&nbsp;&#x258c;&#x2580;&#x2588;&#x2588;&#x2588;&#x2580;
&nbsp;&nbsp;&#x2584;&#x2588;&#x2580;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2584;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2584;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2584;&#x2588;&#x2580;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2590;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2584;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&nbsp;&#x258c;
&nbsp;&#x2590;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&#x2588;&#x2588;&#x2584;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2590;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&#x2588;&#x2588;&#x2584;&nbsp;&#x258c;
&nbsp;&#x2588;&#x2588;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;&nbsp;&nbsp;&nbsp;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2590;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;
&#x2590;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x258c;&nbsp;&#x2580;&#x2588;&#x2588;&#x2584;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2584;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2590;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x258c;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x258c;&nbsp;&#x2580;&#x2588;&#x2588;&#x2584;
&#x2588;&#x2588;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x258c;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2590;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x258c;
&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&#x2584;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2590;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x258c;&nbsp;&nbsp;&#x2584;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;
&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&#x2588;&#x2580;&nbsp;&#x2590;&#x2588;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x258c;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2590;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&nbsp;&#x2588;&#x2580;&nbsp;&#x2590;&#x2588;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;
&#x2588;&#x2588;&#x2588;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&#x2588;&#x2584;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&#x2588;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&nbsp;&#x2590;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2588;&#x2584;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&nbsp;&nbsp;&#x2588;&#x2588;&#x2588;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2590;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&nbsp;&nbsp;&nbsp;&#x2588;&#x2584;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&#x2588;&#x2588;&#x2588;&#x258c;
&#x2590;&#x2588;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2584;&#x258c;&#x2584;&#x2588;&#x2588;&#x2588;&#x2580;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2588;&#x2584;&#x2584;&#x2584;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&#x2590;&#x2588;&#x2580;&#x2584;&#x2584;&#x2588;&#x2584;&#x2584;&#x2584;&#x2584;&#x2584;&#x2584;&#x2588;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2590;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2580;&nbsp;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2584;&#x258c;&#x2584;&#x2588;&#x2588;&#x2588;&#x2580;
&nbsp;&#x2588;&#x2588;&#x2588;&#x2584;&#x258c;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2580;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;&nbsp;&nbsp;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;&nbsp;&nbsp;&#x2580;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2588;&#x2584;&#x258c;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;&nbsp;&nbsp;&nbsp;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x258c;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;
&nbsp;&#x2590;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;
&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2588;&#x258c;
&nbsp;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2580;
		</pre>

		<pre id="pinned"></pre>

		<input id="input" type="text"></input>

		<?php if(!isset($_GET['build']) || $_GET['build'] !== 'false'){ ?>
		<div id="iframeContainer">
			<iframe src="http://localhost:<?php echo $_SERVER['SERVER_PORT']; ?>/tools/build.php?debug"></iframe>
		</div>
		<?php } ?>

		<div id="graph">

		</div>

		<div id="streams">
			<span>STREAMS</span>
			<div id="allStreamsContainer">
				<div class="allStreams on" onclick="toggleStream('allOn');">ON</div>
				<div class="allStreams off" onclick="toggleStream('allOff');">OFF</div>
			</div>
			<?php
				foreach ($consoleStreams as $STREAM => $name)
				{
					echo "<div id=\"stream_{$STREAM}\" onclick=\"toggleStream({$STREAM})\" class=\"stream on\">{$name}</div>";
				}
			?>
		</div>

	</body>
</html>

<script type="text/javascript" src="../../engine/javascript/jvs.notifyServer.php"></script>
<script type="text/javascript">

	var consoleWait = false;
	var content = document.getElementById('content');
	var graph = document.getElementById('graph');
	var title = document.getElementById('title');
    var playerLocation = document.getElementById('location');
    var pressedKeyCode = document.getElementById('pressedKeyCode');
	var autoScroll = false;

	var frameId = 0;

	var panelBg = '#000';
	var locationVals = '';

	var pinnedBar = null;

	var errorInTitle = false;
	var errorInTitleTimer;

	setInterval('getConsoleStream()', 800);

	function getConsoleStream()
	{
		if (consoleWait) return;
		consoleWait = true;

		consoleRequest = new XMLHttpRequest();

		consoleRequest.open("POST","console.php?console=request","true");
		consoleRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		consoleRequest.send();

		consoleRequest.onreadystatechange = function()
		{
			if (consoleRequest.readyState === 4 && consoleRequest.status === 200 && consoleRequest.responseText !== '')
			{
				var consoleData = JSON.parse(consoleRequest.responseText);

				if (consoleData.location !== null) locationVals = consoleData.location;
				playerLocation.innerHTML = '<span style="color:#aaf;">Location:</span> ' + locationVals;

				var frameTime;

				for (var index in consoleData.frameData)
				{
					var data = consoleData.frameData[index];
					var consolePanel = document.createElement('div');
					consolePanel.className = 'panel';

					consolePanel.id = ++frameId + '_p';
					consolePanel.name = frameId + '_p';

					if (data.error === undefined)
					{
						panelBg = (panelBg === '#000' ? '#111' : '#000');
						consolePanel.style.backgroundColor = panelBg;
					}
					else
					{
						consolePanel.style.backgroundColor = '#500';
						errorInTitle = true;
						errorInTitleTimer = setTimeout('errorInTitle = false;', 2000);
					}

					consolePanel.innerHTML = data.data;

					consolePanel.oncontextmenu = function ()
					{
						if (pinnedBar !== null) pinnedBar.className = pinnedBar.className.replace('pin', '');

						var pinnedDiv = document.getElementById('pinned');
						pinnedBar = document.getElementById(this.id.replace('p', 'b'));
						pinnedBar.className = pinnedBar.className + ' pin';

						pinnedDiv.innerHTML = this.innerHTML;
						pinnedDiv.style.display = 'block';

						return false;
					};

					content.appendChild(consolePanel);
					if (content.childNodes.length > 200 && autoScroll)
					{
						while (content.childNodes.length > 200)
						{
							content.removeChild(content.firstChild);
							graph.removeChild(graph.firstChild);
						}
					}

					frameTime = data.frameTime ? data.frameTime : '???';
					wakeTime = data.wakeTime ? data.wakeTime : '???';

					var graphBar = document.createElement('a');
					graphBar.id = frameId + '_b';
					graphBar.title = frameTime + ' secs';
					graphBar.className = 'graphBar' + (data.error === undefined ? '' : ' error');
					graphBar.href = '#'+frameId+'_p';

					if (data.frameTime)
					{
						graphBar.style.height = Math.round(data.frameTime * 2000) + 'px';
					}
					else
					{
						graphBar.style.height = '50px';
						graphBar.style.backgroundColor = '#ff0';
						graphBar.style.borderColor = '#fff';
					}

					graphBar.oncontextmenu = function ()
					{
						document.getElementById(this.id.replace('b', 'p')).oncontextmenu();
						return false;
					};

					if (frameTime < 0.05) { graphBar.className = graphBar.className + ' t1'; }
					else if (frameTime < 0.08) { graphBar.className = graphBar.className + ' t2'; }
					else if (frameTime < 0.1) { graphBar.className = graphBar.className + ' t3'; }
					else { graphBar.className = graphBar.className + ' t4'; }

					graph.appendChild(graphBar);
					graphBar.style.marginTop = (graph.offsetHeight - graphBar.offsetHeight) + 'px';

					var wakePoint = document.createElement('div');

					wakePoint.className = 'wakePoint';

					graphBar.appendChild(wakePoint);

					wakePoint.style.bottom = (wakeTime * 2000) + 'px';

					if (data.FS !== undefined) document.body.scrollTop = document.body.scrollHeight;
				}

				title.innerHTML = locationVals + ' - ' + frameTime + (errorInTitle ? ' ERROR!' : '');

				if (autoScroll) document.body.scrollTop = document.body.scrollHeight;

				if (consoleData.streams !== undefined)
				{
					for (var i in consoleData.streams)
					{
						document.getElementById('stream_' + i).className = consoleData.streams[i] ? 'stream on' : 'stream off';
					}
				}
			}
			consoleWait = false;
		};
	}

	function toggleStream(STREAM)
	{
		notifyServer('console', 'STREAM', STREAM);
	}

	window.onfocus = function() { autoScroll = false; };
	window.onblur = function() { autoScroll = true; };

	document.onkeydown = function(e)
	{
		pressedKeyCode.innerHTML = e.keyCode;

		document.getElementById('input').focus();
		switch (e.keyCode)
		{
//			case 67:
//				content.innerHTML = '';
//				break;

			case 40:
				document.body.scrollTop = document.body.scrollHeight;
				autoScroll = true;
				break;

			case 38:
				autoScroll = false;
				break;

			case 13:
				var input = document.getElementById('input');

				if (input.text !== '')
				{
					notifyServer('console', '<?php echo UIN_TEXT; ?>', input.value);
				}
				break;
		}

	};

	document.onmousedown = function (e)
	{
		document.getElementById('mouseButtonCode').innerHTML = e.button;
	};

	notifyWait = false;

	function notifyServer(key, type, content)
	{
		if (notifyWait) return 0;
		notifyWait = true;

		var rightClick = (rightClick !== undefined && rightClick ? 1 : 0);

		UIEventRequest = new XMLHttpRequest();

		UIEventRequest.open("POST","../../ajax/notifyServer.php","true");
		UIEventRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		UIEventRequest.send('k='+key+'&c='+content+'&t='+type);

		UIEventRequest.onreadystatechange = function()
		{
			notifyWait = false;
			//autoScroll = false;
		};
		return false;
	}

</script>

<?php break; } }
