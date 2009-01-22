<div<? if (isset($attributes)) echo $attributes; ?>>
<? if (isset($element)) : ?>
<? foreach ($element as $key => $value) : ?>
	<div>
		<span class='rating_radio'><?= $value; ?></span>
		<span class='rating_label'><?= $key; ?></span>
	</div>
<? endforeach; ?>
<? endif; ?>
</div>