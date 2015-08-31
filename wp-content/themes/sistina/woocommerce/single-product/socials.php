<?php
/**
 * Single Product Share
 *
 * Sharing plugins can hook into here or you can add your own code directly.
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! yit_get_option('shop-single-show-socials') ) return;

global $product;

$title = urlencode( get_the_title() );
$permalink = urlencode( get_permalink() );
$excerpt = urlencode( get_the_excerpt() );
$src = wp_get_attachment_image_src(get_post_thumbnail_id( isset( $product->id ) ? $product->id : 0 ), 'full');

$socials = array(

    'facebook' => array(
        'label' => __( 'share on | [Facebook]', 'yit' ),
        'url' =>  apply_filters( 'yit_share_facebook', 'https://www.facebook.com/sharer.php?u=' . $permalink . '&t=' . $title . '' )
    ),

    'pinterest' => array(
        'label' => __( 'pin this item | [on Pinterest]', 'yit' ),
        'url' =>  apply_filters( 'yit_share_pinterest', 'http://pinterest.com/pin/create/button/?url=' . $permalink . '&media=' . $src[0] . '&description=' . $excerpt )
    ),

    'twitter' => array(
        'label' => __( 'share it on | [Twitter]', 'yit' ),
        'url' =>  apply_filters( 'yit_share_twitter', 'https://twitter.com/share?url=' . $permalink . '&text=' . $title . '' )
    ),

    'email' => array(
        'label' => __( 'Send to a | [friend]', 'yit' ),
        'url' =>  apply_filters( 'yit_share_email', 'mailto:?subject=' . $title . '&amp;body=' . sprintf( __( 'Check this awesome %s at %s', 'yit' ), $title, $permalink ) , $permalink, $title )
    ),

);

$first = true;

// remove the socials if the options is disabled
foreach( $socials as $social => $the_ ) if ( ! yit_get_option('shop-single-show-social-'.$social) ) unset( $socials[$social] );
?>

<div class="socials-box n<?php echo count( $socials ); ?>">
    <?php foreach( $socials as $social => $the_ ) : ?><a href="<?php echo $the_['url']; ?>" class="social <?php echo $social; ?><?php echo $first ? ' first' : ''; ?>" target="_blank"><i></i><?php echo yit_decode_title( $the_['label'] ); ?></a><?php $first = false; endforeach; ?>
</div>