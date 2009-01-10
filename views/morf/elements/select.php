<dl>
	<?if (isset($error) OR isset($label)):?>
	<dt>
	<?if (isset($error)) : ?><?= $error; ?> <?endif;?><?if (isset($label)) :?><?= $label; ?><?endif;?>
	</dt>
	<?endif;?>
	<dd>
	<?= $element; ?>
	</dd>
</dl>