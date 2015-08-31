<?php

if (!isset($style) || $style == '') $style = 'accordion' ;
echo do_shortcode('[accordion name="' . $name . '" style="' . $style . '"]');

?>