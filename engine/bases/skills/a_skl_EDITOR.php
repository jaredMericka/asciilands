<?php

/**
 * This abstract class just exists to that as I modify skill stuff, the editor
 * skills don't have to keep being updated to comply with the base-classes.
 */
abstract class a_skl_EDITOR extends Skill
{
	public function onChangeLevel() { }
	public function getRelatedSkills() { return []; }
}