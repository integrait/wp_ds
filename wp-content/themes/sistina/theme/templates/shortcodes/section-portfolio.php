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
global $yit_portfolio_index;
if ( ! isset( $yit_portfolio_index )  ) $yit_portfolio_index = 0;

$var['posts_per_page'] = (!is_null( $items )) ? $items : -1;

yit_get_model( 'portfolio' )->shortcode_atts = $var;
yit_set_portfolio_loop( $portfolio );

$sidebar_layout = yit_get_sidebar_layout();
?>
<div id="section-portfolio-<?php echo $yit_portfolio_index ?>" class="section portfolio<?php if( $sidebar_layout != 'sidebar-no' ): ?> section-portfolio-with-sidebar<?php endif ?>"><!-- section blog wrapper -->
	<?php if( ! yit_is_portfolio_empty() ): ?>
        <?php

        $portfolio_length = yit_get_portfolio_lenght();
        $portfolio_groups = array();
        $classes = " work yit_item";

        $i = $j = 0;
        while ( yit_have_works() ) {
            $portfolio_groups[$i][] = array(
                'title' => yit_work_get('title'),
                'terms' => yit_work_get('terms'),
                'permalink' => yit_work_permalink( yit_work_get( 'item_id' ) ),
                'image_id' => yit_work_get( 'item_id' ),
                'image_url' => yit_work_get( 'image_url' )
            );

            if( ++$j % 3 == 0 ) $i++;
        }

        if( !empty( $title ) ) {
            if( !empty($portfolio_icon_title)) { yit_image("src=$portfolio_icon_title"); }
            yit_string( '<h3 class="title">', yit_decode_title($title), '</h3>' );
        }

        if( !empty( $description ) ) { yit_string( '<p class="desc">', $description, '</p>' ); }
        ?>
        <div class="portfolio-projects group">
        <?php foreach( $portfolio_groups as $k => $group ): ?>
            <div class="section_portfolio_group<?php if( $k % 3 == 2 ): ?> last_group<?php endif ?>">
            <?php foreach( $group as $index=>$work ): ?>
                <?php
                    $class = "";
                    if( $k % 2 == 1 ) {
                        $class = ( $index % 3 == 0 ) ? 'large' : 'half';
                    } else {
                        $class = ( $index % 3 == 2 ) ? 'large' : 'half';
                    }
                ?>
                <div <?php yit_work_class( $class . $classes ) ?>>
                    <?php yit_image( "id={$work['image_id']}&size=" . ( $class == 'large' ? 'section_portfolio_large' : 'section_portfolio' ) ); ?>

                    <?php if( $show_lightbox_hover == 'yes' ): ?>
                        <div class="description">
                            <div class="description-container">
                                <?php if( $work['title'] ): ?>
                                    <!-- title -->
                                    <h2><?php echo $work['title'] ?></h2>
                                <?php endif ?>

                                <?php if( $work['terms'] ): ?>
                                    <!-- categories -->
                                    <span class="categories"><img src="<?php echo get_template_directory_uri() ?>/theme/templates/portfolios/pinterest/images/terms.png" alt="" /><?php echo implode( ', ', $work['terms'] ) ?></span>
                                <?php endif ?>
                            </div>
                        </div>
                    <?php endif ?>

                    <span class="detail"></span>
                    <a href="<?php echo $work['permalink'] ?>"></a>
                </div>
            <?php endforeach ?>
            </div>
        <?php endforeach ?>


    	</div>
	<?php endif ?>
</div><!-- end section portfolio wrapper -->
<div class="clear"></div>
<?php $yit_portfolio_index++ ?>