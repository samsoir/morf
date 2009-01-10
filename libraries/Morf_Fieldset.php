<?php defined('SYSPATH') or die('No direct script access.');

class Morf_Fieldset_Core extends Morf
{
	// The elements
	protected $elements;

	// The legend
	protected $legend;

	// Element attributes
	protected $attributes = array
	(
		'id' => NULL,
		'class' => NULL
	);
	
	public function factory($config = array())
	{
		return new Morf_Fieldset($config);
	}
	
	public function __construct($config = array())
	{
		$this->_init($config);
	}
	
	protected function _init($config)
	{
		$this->elements = new ArrayObject;

		$this->post = $config['post'];

		foreach ($config as $key => $val)
		{
			if (array_key_exists($key, $this->attributes))
			{
				$this->attributes[$key] = $val;
			}
			elseif (property_exists($this, $key))
			{
				$this->$key = $val;
			}
		}
	}

	public function legend($text, $attributes = array())
	{
		$this->legend = form::legend($text, $attributes);
		return $this;
	}
	
	public function render(& $render_variables, $errors = array())
	{
		// Create output buffer
		$output_buffer = form::open_fieldset($this->_format_attributes());

		// Set the legend
		if (isset($this->legend))
			$output_buffer .= $this->legend;
			
		// Iterate through children
		foreach ($this->elements as $name => $element)
		{
			$output_buffer .= $element->render($render_variables, $errors);
		}

		// Create output buffer
		$output_buffer .= form::close_fieldset();

		// Load the view
		$result = new View('morf/'.Kohana::config('morf.elements.fieldset.template'));

		// Apply content to template
		$result->elements = $output_buffer;

		// Return the resulting output
		return (string) $result->render();
	}
	
} // End Morf_Fieldset