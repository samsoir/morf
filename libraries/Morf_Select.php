<?php defined('SYSPATH') or die('No direct script access.');

class Morf_Select_Core extends Morf_Group
{
	public function render(& $render_variables, $errors = array())
	{
		$result = parent::render($render_variables, $errors);
		
		$result['template']->element = form::dropdown($result['attributes'], $this->options, $this->value);

		return (string) $result['template']->render();
	}

} // End Morf_Select_Core