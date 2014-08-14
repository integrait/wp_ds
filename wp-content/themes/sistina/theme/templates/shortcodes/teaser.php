<?php

$title = ( isset($title) ) ? $title : '';
$image = ( isset($image) ) ? esc_url($image) : '';
$link = ( isset($link) ) ? esc_url($link) : '';
?>

<div class="teaser">
    <div class="image">
        <img src="<?php echo $image ?>" alt="<?php echo $title ?>" />
       <?php if ( $link != '') { ?>
            <p class="title"><a href="<?php echo $link ?>"><?php echo $title ?></a></p>
        <?php } else { ?>
            <p class="title"><?php echo $title ?></p>
        <?php } ?>
    </div>
</div>