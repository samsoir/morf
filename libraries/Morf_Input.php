<?php defined('SYSPATH') or die('No direct script access.');

class Morf_Input_Core extends Morf_Element
{
	public function render(& $render_variables, $errors = array())
	{
		// Load base template and attributes
		$result = parent::render($render_variables, $errors);

		// Discover the type
		switch ($this->type)
		{
			case 'input'	:
				$result['template']->element = form::input($result['attributes'], $this->value);
				break;
			case 'submit'	:
				$result['template']->element = form::submit($result['attributes'], $this->value);
				$render_variables['submit'] = TRUE;
				break;
			case 'radio'	:
				$result['template']->element = form::radio($result['attributes'], $this->value);
				break;
			case 'checkbox'	:
				$result['template']->element = form::checkbox($result['attributes'], $this->value);
				break;
			case 'hidden'	:
				$result['template']->element = form::hidden($result['attributes'], $this->value);
				break;
			case 'file'		:
				$result['template']->element = form::upload($result['attributes'], $this->value);
				$render_variables['enctype'] = 'multipart/form-data';
				break;
		}

		// Return the resulting output
		return (string) $result['template']->render();
	}

} // End