<?php
	$icon = (isset($icon) && $icon != '') ? esc_url($icon) : '';
	$text = (isset($text) && $text != '') ? $text : '';
	$number = (isset($number) && $number != '') ? $number : '';
	$last = (isset($last) && $last != '' && $last == 'yes') ? 'last' : '';

?>

<div class="one-third <?php echo $last; ?>">
	<div class="random-numbers">
		<img src="<?php echo $icon; ?>" alt="" width="52" height="52" />
        <span class="number"><?php echo $number; ?></span>
        <?php echo yit_addp($text); ?>
	</div>
</div>