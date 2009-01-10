<?php defined('SYSPATH') or die('No direct script access.');

class Morf_Button_Core extends Morf_Element
{
	public function render(& $render_variables, $errors = array())
	{
		// Load base template and attributes
		$result = parent::render($render_variables, $errors);
		
		if (is_array($result) AND $result['type'] == 'submit')
			$render_variables['submit'] = TRUE;

		$result['template']->element = form::button($result['attributes'], $this->value);

		// Return the resulting output
		return (string) $result['template']->render();
	}
} // End