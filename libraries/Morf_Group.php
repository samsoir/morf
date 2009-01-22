<?php defined('SYSPATH') or die('No direct script access.');

abstract class Morf_Group_Core extends Morf_Element
{
	// Select list options
	protected $options;

	// Force selection
	protected $force_selection = FALSE;

	public function factory($config = array(), $options = array(), $selected = NULL, $post = FALSE)
	{
		return new Morf_Select($config, $options, $selected, $post);
	}

	public function __construct($config = array(), $options = array(), $selected = NULL, $post = FALSE)
	{
		$this->options = $options;

		$this->_init($config, $selected, $post);
	}

	public function render(& $render_variables, $errors = array())
	{
		$result = parent::render($render_variables, $errors);
		
		// If $force_selection is TRUE, add 'Please select...' as first item
		if ($this->force_selection)
			$this->options = array('_NO_SELECTION_' => ucfirst(Kohana::lang('morf.pleaseselect'))) + $this->options;

		return $result;
	}

} // End Morf_Select_Core