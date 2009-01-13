<?php
/**
 * Morf
 * Yet another form library
 *
 * @package default
 * @author Sam Clark
 * @license GNU Public License v3 http://www.gnu.org/licenses/gpl-3.0.html
 */
class Morf_Core
{
	// Configuration
	protected $config;
	
	// Attributes
	protected $attributes = array
	(
		'action' => NULL,
		'method' => 'post',
		'id' => NULL,
		'class' => NULL,
		'charset' => 'utf-8',
		'enctype' => 'application/x-www-form-urlencoded',
	);
	
	// Form elements
	protected $elements;
	
	// Errors
	protected $errors = array();

	// Post
	protected $post = FALSE;

	/**
	 * Factory method for creating a new Morf object. Allows chaining
	 *
	 * @param array $config array of key => value pairs for configuration, merges with config file
	 * @param array $hidden_fields array of key => value pairs for hidden fields to be automatically included
	 * @return Morf object
	 * @author Sam Clark
	 */
	public function factory($config = array(), $hidden_fields = array())
	{
		return new Morf($config, $hidden_fields);
	}

	/**
	 * Constructor. Takes configuration as an associative array. Merges with the config/morf.php pre-defined config file
	 *
	 * @param array $config array of key => value pairs for configuration, merges with config file
	 * @param array $hidden_fields array of key => value pairs for hidden fields to be automatically included
	 * @return void
	 * @author Sam Clark
	 */
	public function __construct($config = array(), $hidden_fields = array())
	{
		// Setup configuration
		$config += Kohana::config('morf');
		$this->config = $config;

		$this->_init($this->config['post']);
		
		if ($hidden_fields)
			$this->_hidden_fields($hidden_fields);
		
		return;
	}

	/**
	 * Object initialisation. Sets up the element array, post fields and form action
	 *
	 * @param boolean $post if TRUE, all elements will take values from post, else they will use initial values
	 * @param array $post using an associative array, control which elements load their values from post
	 * @return void
	 * @author Sam Clark
	 */
	protected function _init($post)
	{
		// Setup elements as an array object
		$this->elements = new ArrayObject();
		
		// Manage post
		$this->post = $post;
			
		// Create url
		$this->attributes['action'] = $this->_set_action();
		
		// Return
		return;
	}

	protected function _hidden_fields($hidden_fields = array())
	{
		foreach ($hidden_fields as $name => $value)
			$this->hidden($name, $value);
		
		return;
	}

	/**
	 * Overload the __get() method to allow access to attributes and elements
	 *
	 * @param string $key the name of the attribute or form field name
	 * @return mixed $value the result of the get request
	 * @author Sam Clark
	 */
	public function __get($key)
	{
		$value = NULL;

		if (array_key_exists($key, $this->attributes))
			$value = $this->attributes[$key];
		elseif ($this->elements->offsetExists($key))
			$value = $this->elements->offsetGet($key);

		return $value;
	}

	/**
	 * Overload the __set() method to allow access to attributes
	 *
	 * @param string $key The attribute name
	 * @param string $value the value to assign to the attribute
	 * @param integer $value the value to assign to the attribute
	 * @param float $value the value to assign to the attribute
	 * @return void
	 * @author Sam Clark
	 */
	public function __set($key, $value)
	{
		if (array_key_exists($key, $this->attributes))
		{
			$this->attributes[$key] = $value;
		}
	}

	/**
	 * Overload the __call() method to give access to new element/fieldset creation, also allows access to attributes to allow chaining
	 *
	 * @param string $method The name of the element to create
	 * @param string $method The name of the attribute to augment
	 * @param array $args All of the supplied arguments will be in this array
	 * @return Morf $this returns this object to allow chaining
	 * @author Sam Clark
	 */
	public function __call($method, $args)
	{		
		// Setup result
		$result = $this;

		// Elements (this should be always loaded from the config file, not local config)
		$elements =  Kohana::config('morf.elements');
		// Refactor method
		$method = strtolower($method);

		// Count the number of arguments
		$num_args = count($args);

		// If method is an existing key
		if (array_key_exists($method,$elements))
		{
			// If the call of selection group type
			if (in_array($elements[$method]['type'], array('select', 'checkgroup', 'radiogroup')))
			{
				// Configuration
				$config = $this->_make_config($method, $args[0]);

				// Options
				$value = isset($args[1]) ? $args[1] : array();

				// Config
				$selected = isset($args[2]) ? $args[2] : NULL;
			}
			// Otherwise this is a standard call
			else
			{
				// Name/id
				$config = $this->_make_config($method, $args[0]);

				// Value
				$value = isset($args[1]) ? $args[1] : NULL;
			}

			// Setup the uid
			if (array_key_exists('name', $config))
				$uid = $config['name'];
			elseif (array_key_exists('id', $config))
				$uid = $config['id'];
			else
				$uid = $this->_generate_name();

			switch ($num_args)
			{
				case 0 :
					throw new Kohana_Exception('Morf::__call() no arguments supplied for method : '.$method, $method);
					break;
				case 1 :
					$this->_add_element(Morf_Element::factory($elements[$method]['type'], $config), $uid);
					break;
				case 2 :
					$this->_add_element(Morf_Element::factory($elements[$method]['type'], $config, $value), $uid);
					break;
				case 3 :
					$this->_add_element(Morf_Element::factory($elements[$method]['type'], $config, $value), $uid);
					break;
				default :
					throw new Kohana_Exception('Morf::__call() too many arguments supplied for method : '.$method, $method);
			}
		}
		elseif (array_key_exists($method, $this->attributes))
		{
			if ($num_args == 1)
			{
				$this->attributes[$method] = $args[0];
			}
			else
			{
				throw new Kohana_Exception('Morf::__call() to many arguments provided for '.$method, $method);
			}
		}
		else
		{
			throw new Kohana_Exception('Morf::__call() undefined method called : '.$method, $method);
		}

		return $result;
	}

	/**
	 * Create a new fieldset
	 *
	 * @param string $id the id of the fieldset. Used to access the fieldset later
	 * @param array $attributes an array of attributes ('class', 'id') to apply to the fieldset
	 * @return Morf $this returns the parent Morf object for chaining
	 * @author Sam Clark
	 */
	public function fieldset($id, $attributes = array())
	{
		$attributes += array('id' => $id, 'post' => $this->config['post']);

		$fieldset = new Morf_Fieldset($attributes);
		$this->_add_element($fieldset, $id);
		return $this;
	}

	/**
	 * Add an errors array (returned from the Validation library)
	 *
	 * @param string $error The field to apply the error to
	 * @param array $error an associative array of field names and error messages to apply to this Morf object
	 * @param Validation $error A validation object
	 * @param string $msg [Optional] Required only if $error is a string
	 * @return this
	 * @author Sam Clark
	 */
	public function add_error($error, $msg = NULL)
	{
		if (is_array($error))
			$this->errors += $error;
		elseif ($error instanceof Validation_Core)
			$this->errors += $error->errors($this->config['errors']['file']);
		else
			$this->errors[$error] = $msg;

		return $this;
	}

	/**
	 * Adds a new element to Morf
	 *
	 * @param array $element An array of Morf_Elements, if the array isn't associative a name will be autogenerated
	 * @param Morf_Element $element A single Morf_Element derivative
	 * @return Morf $this returns the parent Morf object for method chaining
	 * @author Sam Clark
	 */
	public function add($element)
	{
		if (is_array($element))
		{
			foreach ($element as $key => $value)
			{
				if (ctype_digit($key))
					$this->_add_element($element, $this->_generate_name());
				else
				{
					if (isset($element->name))
						$this->_add_element($element, $element->name);
					else
						$this->_add_element($element, $this->_generate_name());
				}
			}
		}
		elseif (is_object($element))
		{
			if (isset($element->name))
				$this->_add_element($element, $element->name);
			else
				$this->_add_element($element, $this->_generate_name());
		}
		
		return $this;
	}

	/**
	 * Formats the configuration array ready for passing to Morf_Element
	 *
	 * @param string $method The name of the element to format
	 * @param array $config the configuration array to uses
	 * @return array $config the parsed configuration array
	 * @author Sam Clark
	 */
	protected function _make_config($method, $config)
	{
		if (in_array($method, array('submit', 'button')))
		{
			if ( ! is_array($config))
				$config = array('type' => $method, 'id' => $config);
			else
				$config['type'] = $method;
		}
		else
		{
			if ( ! is_array($config))
				$config = array('type' => $method, 'name' => $config, 'id' => $config);
			else
				$config['type'] = $method;

			if ( ! array_key_exists('name', $config) AND array_key_exists('id', $config))
			{
				$config['name'] = $config['id'];
			}
			elseif ( ! array_key_exists('id', $config) AND array_key_exists('name', $config))
			{
				$config['id'] = $config['name'];
			}

			if (empty($config['name']))
				$config['name'] = $this->_generate_name();
		}

		$config['post'] = $this->post;

		return $config;
	}

	/**
	 * Generates a new id for form elements. Only used if id or name are missing.
	 * It is highly recommended that you never have to use this method.
	 *
	 * @param string $prefix the prefix to use
	 * @return string The resulting new name
	 * @author Sam Clark
	 */
	protected function _generate_name($prefix = 'item_')
	{
		return $prefix.$this->elements->count();
	}

	/**
	 * Adds a new element to the element array
	 *
	 * @param Morf_Element $element The element to add
	 * @param Morf_Fieldset $element The fieldset to add
	 * @param string $name The identifier name for the element
	 * @return void
	 * @author Sam Clark
	 */
	protected function _add_element($element, $name)
	{
		$this->elements->offsetSet($name, $element);
		return;
	}

	/**
	 * Automatically sets the form action if no action is required
	 *
	 * @return string $result The resulting formatted form action url
	 * @author Sam Clark
	 */
	protected function _set_action()
	{
		$result = url::current();
		
		if ( ! empty($this->attributes['action']))
		{
			if(preg_match('/(http:\/\/|https:\/\/)/', $this->attributes['action']))
				$result = $this->attributes['action'];
			else
				$result = url::site($this->attributes['action'], request::protocol());
		}
		
		return $result;
	}

	/**
	 * Gets the post variables from the post settings.
	 *
	 * @param boolean $post TRUE will load all posted variables, FALSE will load none
	 * @param array $post an associative array of post variables will load the listed post vars
	 * @return array Resulting array of $_POST variables
	 * @author Sam Clark
	 */
	protected function _get_post_vars($post = FALSE)
	{
		$result = array();
		
		if ($post === TRUE)
		{
			$result = Input::instance()->post();
		}
		elseif (is_array($post))
		{
			foreach ($post as $key)
			{
				$result[$key] = Input::instance()->post($key, NULL);
			}
		}

		return $result;
	}

	/**
	 * Formats attributes for the form
	 *
	 * @return array the resulting attributes array
	 * @author Sam Clark
	 */
	protected function _format_attributes()
	{
		// Create attributes array
		$result = array();

		foreach ($this->attributes as $key => $value)
		{
			if (isset($value) AND $key !== 'action')
				$result[$key] = $value;
		}
		return $result;
	}

	/**
	 * Handles the objects transformation to string
	 *
	 * @return string the rendered result of this form
	 * @author Sam Clark
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 * Renders the Morf object into an HTML form
	 *
	 * @param boolean $print If TRUE render will automatically print the form
	 * @return string HTML form representation of the Morf object
	 * @author Sam Clark
	 */
	public function render($print = FALSE)
	{
		// Load correct view
		$view = new View('morf/groups/form');

		$element_cache = '';

		$render_variables = array
		(
			'enctype'		=>	'application/x-www-form-urlencoded',
			'submit'		=> FALSE
		);

		// Foreach element
		foreach ($this->elements as $name => $element)
		{
			$element_cache .= $element->render($render_variables, $this->errors);
		}

		$this->enctype = $render_variables['enctype'];

		if ( ! $render_variables['submit'] AND $this->config['autosubmit'])
			$element_cache .= Morf_Element::factory('input', $this->config['autosubmit']['config'], $this->config['autosubmit']['value'])->render($render_variables, $this->errors);

		// Create output buffer
		$result = form::open($this->action, $this->_format_attributes());

		// Apply the rendered elements to the view
		$view->elements = $element_cache;

		// Render the resulting view
		$result .= $view->render();

		// Close form
		$result .= form::close();

		// If print is true, print the output
		if ($print)
			echo $result;

		// Return the resulting output
		return $result;
	}
} // End Morf_Core