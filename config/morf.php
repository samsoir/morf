<?php

// If no submit button has been included, add one if 'autosubmit' is not FALSE
$config['autosubmit']	= array
(
	'value'				=> 'Submit',
	'config'			=> array('type' => 'submit')
);

// If set to TRUE, morf will automatically apply the POST variables back to the form
$config['post']			= FALSE;

$config['errors']		= array
(
	'class'				=> 'error',
	'file'				=> NULL
);

// Element settings
$config['elements'] 	= array
(
	'input'				=> array
		(
			'type' 			=> 'input',
			'template'		=> 'elements/input',
		),
	'submit'			=> 	array
		(
			'type' 			=> 'input',
			'template'		=> 'unstructured/submit',
		),
	'radio'				=> 	array
		(
			'type' 			=> 'input',
			'template'		=> 'elements/radio',
		),
	'radiogroup'		=> 	array
		(
			'type' 			=> 'radiogroup',
			'template'		=> 'elements/radiogroup',
		),
	'checkbox'			=> array
		(
			'type' 			=> 'input',
			'template'		=> 'elements/input',
		),
	'password'			=> array
		(
			'type' 			=> 'input',
			'template'		=> 'elements/input',
		),
	'hidden'			=> array
		(
			'type' 			=> 'input',
			'template'		=> 'unstructured/hidden',
		),
	'file'				=> array
		(
			'type' 			=> 'input',
			'template'		=> 'elements/input',
		),
	'textarea'			=> array
		(
			'type' 			=> 'textarea',
			'template'		=> 'elements/input',
		),
	'select'			=> array
		(
			'type' 			=> 'select',
			'template'		=> 'elements/select',
		),
	'multiselect'		=> array
		(
			'type' 			=> 'select',
			'template'		=> 'elements/select',
		),
	'button'			=> array
		(
			'type' 			=> 'button',
			'template'		=> 'unstructured/button',
		),
	'fieldset'			=> array
		(
			'type' 			=> 'fieldset',
			'template'		=> 'groups/fieldset',
		),
	'plaintext'			=> array
		(
			'type' 			=> 'plaintext',
			'template'		=> 'unstructured/plaintext',
		)
);