<?php defined('SYSPATH') or die('No direct script access.');

class Morf_Plaintext_Core extends Morf_Element
{
	// Element attributes
	protected $attributes = array
	(
		'type' => NULL,
		'id' => NULL,
		'class' => NULL,
	);

	public function render(& $render_variables, $errors = array())
	{
		// Load base template and attributes
		$result = parent::render($render_variables, $errors);

		$result['template']->element = $this->value;

		$result['attributes'] = $this->_filter_attributes(array('name'), $result['attributes']);

		if ($result['attributes'])
			$result['template']->attributes = form::attributes($result['attributes']);

		// Return the resulting output
		return (string) $result['template']->render();
	}
} // End Morf_Plaintext_Core