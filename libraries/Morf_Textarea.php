<?php defined('SYSPATH') or die('No direct script access.');

class Morf_Textarea_Core extends Morf_Element
{
	public function render(& $upload, $errors = array())
	{
		// Load base template and attributes
		$result = parent::render($render_variables, $errors);

		$result['template']->element = form::textarea($result['attributes'], $this->value);

		// Return the resulting output
		return (string) $result['template']->render();
	}
} // End Morf_Textarea_Core