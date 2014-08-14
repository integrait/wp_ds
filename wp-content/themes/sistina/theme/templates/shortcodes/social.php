<?php
    if( !isset($icon_type) || $icon_type == '' )  $icon_type = 'default';
    if( $size != '' ) $size = '-' . $size;

	//if( $icon_type != '' ) $icon_type = '-' . $icon_type;
    
    if( is_null($title) || $title == '' ) $title = ucfirst($type);

	$target = (isset($target) && $target != '') ? 'target="' . $target . '"' : '';
    
?>
<?php /** icon social fade **/ ?>
<?php if( $icon_type == 'round' ) : ?>
<div class="fade-socials<?php echo $size . ' ' . $type . $size; ?>">
<a href="<?php echo $href; ?>" class="fade-socials<?php echo $size . ' ' . $type . $size; ?>" title="<?php echo $title; ?>" <?php echo $target; ?>></a>
</div>
<?php /** icon social rounded **/ ?>
<?php elseif( $icon_type == 'default' ) : ?>
<div class="socials-default<?php echo $size .' ' . $type . $size . ' ' . $icon_type; ?>">
<a href="<?php echo $href; ?>" class="socials-default<?php echo $size . ' ' . $icon_type .' ' . $type; ?>" <?php echo $target; ?>><?php echo $type; ?></a>
</div>
<?php endif ?>