<?php defined('SYSPATH') or die('No direct script access.');

class Morf_Radiogroup_Core extends Morf_Group
{
	
	public function render(& $render_variables, $errors = array())
	{
		$result = parent::render($render_variables, $errors);

		// Clean attributes
		$id = $this->_clean_attributes($result['attributes'], 'id');

		$buffer = array();
		
		foreach ($this->options as $value => $title)
		{
			if ($this->value == $value)
				$buffer[inflector::underscore($title)] = form::radio($result['attributes'], $value, TRUE);
			else
				$buffer[inflector::underscore($title)] = form::radio($result['attributes'], $value);
		}
		$result['template']->element = $buffer;

		return (string) $result['template']->render();
	}
}