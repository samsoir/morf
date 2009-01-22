<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Morf_Element
 * Yet another form library
 *
 * This abstract class provides the base framework for almost all other Morf form elements.
 *
 * @package default
 * @author Sam Clark
 * @license GNU Public License v3 http://www.gnu.org/licenses/gpl-3.0.html
 */
abstract class Morf_Element_Core
{
	// Configuration
	protected $config;

	// Element attributes
	protected $attributes = array
	(
		'type' => NULL,
		'name' => NULL,
		'label' => TRUE,
		'id' => NULL,
		'class' => NULL,
		'checked' => NULL,
		'multiple' => NULL
	);
	
	// Error status
	protected $error;
	
	// Field value
	protected $value;
		
	// Template
	protected $template;

	// Post value
	protected $post;

	/**
	 * Factory method for generating Morf_Element derivatives
	 *
	 * @param string $element The name of the element (use one of the predifined element types from config/morf)
	 * @param array $config The configuration for this element as an associative array
	 * @param mixed $value The value to assign to the element
	 * @param string $selected Will be depreciated soon (ignore)
	 * @param array $post The post array of elements to use the post values rather than the predefined values
	 * @return Morf_Element object
	 * @author Sam Clark
	 */
	public function factory($element, $config = array(), $value = NULL, $selected = NULL, $post = FALSE)
	{
		$model = 'Morf_'.ucfirst($element);

		switch ($config['type'])
		{
			case 'select'		:
			case 'multiselect'	:
			case 'radiogroup'	:
				$obj = new $model($config, $value, $selected, $post);
				break;
			default				:
				$obj = new $model($config, $value, $post);
				break;
		}
		return $obj;
	}

	/**
	 * The Morf_Element base contructor
	 *
	 * @param array $config Configuration array of keys => values
	 * @param mixed $value The value of this element
	 * @param boolean $post if TRUE, this element will load it's value from the post element of the same name (if it exists)
	 * @param array $post If this elements name is a key within the $post array, the value will be taken from that key
	 * @return void
	 * @author Sam Clark
	 */
	public function __construct($config = array(), $value = NULL, $post = FALSE)
	{
		$this->_init($config, $value, $post);
		return;
	}

	/**
	 * Initialises this Morf_Element
	 *
	 * @param array $config Configuration array of keys => values
	 * @param string $value The value of this element
	 * @param boolean $post if TRUE, this element will load it's value from the post element of the same name (if it exists)
	 * @param array $post If this elements name is a key within the $post array, the value will be taken from that key
	 * @return void
	 * @author Sam Clark
	 */
	protected function _init($config, $value, $post)
	{
		$this->post = $post;
		
		foreach ($config as $key => $val)
		{
			if (array_key_exists($key, $this->attributes) OR preg_match('/(data-[\w-]+)/', $key))
			{
				$this->attributes[$key] = $val;
			}
			elseif (property_exists($this, $key))
			{
				$this->$key = $val;
			}
		}
		
		$this->value = $value;
		return;
	}

	/**
	 * Overloads the __get() method to allow access to this elements attributes and value
	 *
	 * @param string $key The attribute name or value to retrieve
	 * @return mixed $value The resulting value
	 * @author Sam Clark
	 */
	public function __get($key)
	{
		$value = FALSE;

		if (array_key_exists($key, $this->attributes))
		{
			$value = $this->attributes[$key];
		}
		elseif ($key === 'value')
		{
			$value = $this->value;
		}

		return $value;
	}

	/**
	 * Overloads the __set() method to allow setting of classes, attributes or the value
	 *
	 * @param string $key The property to set
	 * @param mixed $value The value
	 * @return void
	 * @author Sam Clark
	 */
	public function __set($key, $value)
	{
		if ($key === 'class')
		{
			$this->add_class($value);
		}
		if (array_key_exists($key, $this->attributes))
		{
			$this->attributes[$key] = $value;
		}
		elseif (in_array($key, array('value', 'template')))
		{
			$this->$key = $value;
		}
	}

	/**
	 * Overload the __isset() method to allow checking for values
	 *
	 * @param string      key The key to check
	 * @return boolean        TRUE if value is set, FALSE otherwise
	 * @author Sam Clark
	 */
	public function __isset($key)
	{
		$result = FALSE;

		if (array_key_exists($key, $this->attributes))
			$result = isset($this->attributes[$key]);
		elseif (property_exists($this, $key))
			$result = isset($this->$key);
		
		return $result;
	}

	/**
	 * Set a class name to the class attribute. This method automatically handles multiple class names
	 *
	 * @param string $value The class name to add to the class attribute
	 * @return Morf_Element $this
	 * @author Sam Clark
	 */
	public function add_class($value = FALSE)
	{
		// If there is a real value set
		if ( ! empty($value))
		{
			// Trim whitespace from the value
			$value = trim( $value );
			
			// If there is already a class value set
			if ( ! empty($this->attributes['class']))
			{
				// If the class value is an array
				if (is_array($this->attributes['class']))
				{
					// Add this value to the array
					$this->attributes['class'][] = $value;
				}
				else
				{
					// Otherwise create an array with the existing value and the new value
					$this->attributes['class'] = array($this->attributes['class'], $value);
				}
			}
			else
			{
				// Otherwise set this as the first value outside of an array
				$this->attributes['class'] = $value;
			}
		}
		elseif ($value === NULL)
		{	// If the value supplied is NULL
			// Return the value back to null
			$this->attributes['class'] = NULL;
		}
		// Return this object
		return $this;
	}

	public function __toString()
	{
		return $this->render();
	}

	protected function _class_to_string()
	{
		$result = '';
		if ( ! empty($this->attributes['class']) AND is_array($this->attributes['class']))
			$result = implode(' ', $this->attributes['class']);
		elseif ( ! empty($this->attributes['class']))
			$result = $this->attributes['class'];

		return $result;
	}

	protected function _format_attributes($set_name = TRUE)
	{
		// Create attributes array
		$result = array();

		foreach ($this->attributes as $key => $value)
		{
			if ($key === 'class')
				$value = $this->_class_to_string();

			if ( ! empty($value))
				$result[$key] = $value;
		}

		if ( $set_name AND ! array_key_exists('name', $result) AND isset($this->attributes['id']))
			$result['name'] = $result['id'];

		$result = $this->_filter_attributes(array('type', 'label'), $result);

		return $result;
	}

	protected function _filter_attributes($filter = array(), $attributes = array())
	{
		$result = array();
		foreach ($attributes as $key => $value)
		{
			if ( ! in_array($key, $filter))
				$result[$key] = $value;
		}
		return $result;
	}

	protected function _clean_attributes(& $attributes, $return = NULL)
	{
		$result = '';
		$cleaned = array();
		
		foreach ($attributes as $attribute => $value )
		{
			if ($attribute === $return)
				$result = ' '.$return.'="'.$value.'"';
			else
				$cleaned[$attribute] = $value;
		}
		
		$attributes = $cleaned;
		
		return $result;
	}

	protected function _process_post()
	{
		if ((is_array($this->post) AND in_array($this->name, $this->post)) OR $this->post === TRUE)
			$this->value = Input::instance()->post($this->name, $this->value);

		return $this->value;
	}

	/**
	 * Render this element, this should be overloaded by each extention type
	 *
	 * @param string $content 
	 * @param string $print 
	 * @return string rendered content
	 * @author Sam Clark
	 */
	public function render(& $render_variables, $errors = array())
	{
		if ( ! in_array($this->type, array('submit', 'hidden')))
		{
			// Override the template name for this element if locally modified
			if (isset($this->template))
				$template_name = $this->template;
			else
				$template_name = Kohana::config('morf.elements.'.$this->type.'.template');

			// Create output buffer
			$result = array('template' => new View('morf/'.$template_name));

			// Process post
			if ($this->post)
				$this->_process_post();

			// Discover if there is an error
			if (isset($this->attributes['name']) AND $errors AND array_key_exists($this->name, $errors))
			{
				$this->add_class(Kohana::config('morf.errors.class'));
				$result['template']->error = $errors[$this->name];
			}

			if ($this->label === TRUE)
			{
				if (isset($this->attributes['name']))
					$name = $this->name;
				elseif (isset($this->attributes['id']))
					$name = $this->id;
				else
					$name = FALSE;

				if ($name)
					$result['template']->label = form::label($this->name, ucwords(inflector::humanize($name)));
			}
			elseif (is_string($this->label))
					$result['template']->label = form::label($this->name, $this->label);
		
		}
		else
		{
			$result = array( 'template' => new View('morf/unstructured/'.$this->type));
		}
		if ($this->type === 'file')
			$upload = TRUE;

		// Attributes
		$result['attributes'] = $this->_format_attributes();
		
		return $result;
	}
} // End Morf_Element