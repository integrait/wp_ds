<?php

$id = ( isset($id) ) ? (int) $id : 0;
$show_avatar = ( isset($show_avatar) && $show_avatar == 'yes' ) ? 'yes' : 'no';
$show_rating = ( isset($show_rating) && $show_rating == 'yes' ) ? 'yes' : 'no';
$items = ( isset($items) && $items != '' ) ? $items : 10;


$args = array(
    'status' => 'approve',
    'post_status' => 'publish',
    'order' => 'DESC',
    'number' => $items
);

if( $id != 0 ) {
    $args['post_id'] = $id;
} else {
    $args['post_type'] = 'product';
}

$reviews = get_comments($args);
?>
<div class="sc-review">
    <?php if( !empty( $title ) ) {
        if( !empty($review_icon_title)) { yit_image("src=$review_icon_title"); }
        yit_string( '<h3 class="title">', yit_decode_title($title), '</h3>' );
    }

    if( !empty( $description ) ) { yit_string( '<p class="desc">', $description, '</p>' ); }

    ?>
</div>
<div class="comment_container comment-flexslider">
    <ul class="slides">
        <?php foreach ( $reviews as $review ) : ?>
            <li>
                <?php if ( $show_avatar == 'yes' ) :
                    echo get_avatar( $review->comment_author, $size='70' );
                endif ?>

                <div class="comment-text woocommerce <?php if ( $show_avatar == 'no' ) : ?>no-avatar<?php endif ?>">

                    <div itemprop="description" class="description"><?php echo $review->comment_content ?></div>

                    <p class="meta">
                        <span itemprop="author"><?php echo $review->comment_author; _e( ' on ', 'yit' ) ?> <a href="<?php echo get_permalink( $review->ID ) ?>"><?php echo $review->post_title; ?></a></span>
                        <?php if ( $show_rating == 'yes' ) :
                            $rating = esc_attr( get_comment_meta( $review->comment_ID, 'rating', true ) ) ?>
                            <span class="star-rating" title="<?php echo sprintf(__( 'Rated %d out of 5', 'woocommerce' ), $rating) ?>">
                                <span style="width:<?php echo ( intval( get_comment_meta( $review->comment_ID, 'rating', true ) ) / 5 ) * 100; ?>%"><strong itemprop="ratingValue"><?php echo intval( get_comment_meta( $review->comment_ID, 'rating', true ) ); ?></strong> <?php _e( 'out of 5', 'woocommerce' ); ?></span>
                            </span>
                        <?php endif; ?>
                    </p>

                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<script type="text/javascript">
    jQuery(function($){
        var anim = $.browser.msie || $.browser.opera ? 'fade' : 'slide';
        $('.comment-flexslider').flexslider({
            animation : anim,
            slideshowSpeed: <?php echo (isset($timeout)) ? $timeout : 3000 ?>,
            animationSpeed: <?php echo (isset($speed)) ? $speed : 400 ?>,
            touch: false,
            controlNav: false,
            directionNav: true
        });
    });
</script>
