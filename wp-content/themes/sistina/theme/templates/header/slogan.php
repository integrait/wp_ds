<?php
/**
 * Your Inspiration Themes
 * 
 * @package WordPress
 * @subpackage Your Inspiration Themes
 * @author Your Inspiration Themes Team <info@yithemes.com>
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
global $post;
    
$post_id = yit_post_id();

$slogan          = yit_get_post_meta( $post_id, '_slogan' );
$sub_slogan      = yit_get_post_meta( $post_id, '_sub-slogan' );
$icon_slogan      = yit_get_post_meta( $post_id, '_slogan-icon-image' );

if(yit_get_option('show-header-search'))
    $slogan_class ="slogan";
else
    $slogan_class = "slogan no_search";

if( $slogan ) : 
    $tag_slogan     = apply_filters( 'yit_page_slogan_tag', 'h2' );  
    $tag_sub_slogan = apply_filters( 'yit_page_sub_slogan_tag', 'h3' );
    if($icon_slogan){
        $tag_icon_slogan = apply_filters( 'yit_page_sub_slogan_tag', 'img' );
    }

?>
    <!-- SLOGAN -->
    <div class="slogan">
    <h2 class=""><img src="http://drugstoc.biz/wp-content/uploads/2013/07/cart1.png" class="yit-image">Secure, Simple, Reliable &amp; Efficient</h2>
    </div>
<?php endif; ?>  