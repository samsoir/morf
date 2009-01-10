<dl>
	<?if (isset($error) OR isset($label)):?>
	<dt>
	<?if (isset($error)) : ?><?= $error; ?> <?endif;?><?if (isset($label)) :?><?= $label; ?><?endif;?>
	</dt>
	<?endif;?>
	<dd>
	<?if (isset($element)) : ?>
	<?foreach ($element as $text => $radio) :?>
		<p><?= $radio; ?> <?= inflector::humanize($text); ?></p>
	<?endforeach;?>
	<?endif;?>
	</dd>
</dl>