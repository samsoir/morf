<?php defined('SYSPATH') or die('No direct script access.');

class Morf_Rating_Core extends Morf_Element
{
	// The rating range to allow; lowest, highest
	protected $range;
	
	// The scale to implement the range by (leave as 1 unless you want .5 increments)
	protected $scale;
	
	public function _init($config, $value, $post)
	{
		$config += array('range' => Kohana::config('morf.elements.rating.range'), 'scale' => Kohana::config('morf.elements.rating.scale'));
		parent::_init($config, $value, $post);
	}
	

	protected function _create_rating($attributes = array())
	{
		$result = array();
		
		if ($this->scale == 0)
			throw new Kohana_Exception('Morf::_create_rating() scale of zero is not allowed', $this->scale);

		$i = $this->range[0];
		$limit = $this->range[1];

		for ($i; $i < ($limit + $this->scale); $i+= $this->scale)
		{
			$result[(string)$i] = form::radio($attributes, $i, ($this->value == $i) ? TRUE : FALSE);
		}
		
		return $result;
	}
	
	public function render(& $render_variables, $errors = array())
	{
		$result = parent::render($render_variables, $errors);

		// Clean attributes
		$id = $this->_clean_attributes($result['attributes'], 'id');

		$buffer = $this->_create_rating($result['attributes']);
		
		$result['template']->element = $buffer;
		$result['template']->attributes = form::attributes($result['attributes']);

		return (string) $result['template']->render();
	}
} // End Morf_Rating_Core