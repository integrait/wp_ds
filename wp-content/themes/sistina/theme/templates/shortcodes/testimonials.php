<?php
	wp_reset_query();
    
    $args = array(
        'post_type' => 'testimonial'
    );

	$args['posts_per_page'] = (isset($items) && $items != '') ? $items : -1;
	
	if ( isset( $cat ) && ! empty( $cat ) ) {
	    $cat = array_map( 'trim', explode( ',', $cat ) );
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'category-testimonial',
                'field' => 'id',
                'terms' => $cat
            )
        );
    }
    
    $tests = new WP_Query( $args );   
    
    if( !$tests->have_posts() ) return false ?>

        <?php $i = 0; while( $tests->have_posts() ) : $tests->the_post();
            if ( ($i % 2) == 0 ) echo '<div class="testimonial-page-full row-fluid">';

            $fulltext = '';
            $text = ( yit_get_option('text-type-testimonials') == 'content' ) ? get_the_content() : get_the_excerpt();
            
            $title = (yit_get_option('link-testimonials')) ? the_title( '<a href="' . get_permalink() . '" class="name">', '</a>', false ) : the_title('<p class="name">', '</p>',false);
            $label = yit_get_post_meta( get_the_ID(), '_site-label' );
            $siteurl = yit_get_post_meta( get_the_ID(), '_site-url' );
            $smallquote = yit_get_post_meta( get_the_ID(), '_small-quote' );
            $website = '';
            if ($siteurl != ''):
                if ($label != ''):
                    $website = '<a class="website" href="' . esc_url($siteurl) . '">' . $label . '</a>';
                else:
                    $website = '<a class="website" href="' . esc_url($siteurl) . '">' . $siteurl . '</a>';
                endif;
            else:
                $website = '<span class="website">' . $label . '</span>';	
            endif;
            ?>
            <div class="span6">

               <div class="row-fluid">
                   <?php if (yit_get_option('thumbnail-testimonials') && has_post_thumbnail()) :  ?>

                        <div class="span4">
                           <div class="thumbnail">
                               <div class="thumbnail-quote"></div>
                                <?php yit_image( "size=thumb-testimonial-quote&alt=testimage" );//echo get_the_post_thumbnail( null, 'thumb-testimonial-quote' ); ?>
                           </div>
                        </div>
                    <?php endif ?>

                    <div class="span8 testimonial clearfix">
                        <?php if (isset($smallquote) && $smallquote != '') : ?>
                            <blockquote><?php echo $smallquote ?></blockquote>
                        <?php endif ?>
                        <div class="testimonial-text"><?php echo wpautop( $text ); ?></div>
                        <div class="testimonial-signature"><?php echo the_title('<p class="signature">', '</p>',false) ?></div>
                        <div class="testimonial-name <?php if (!yit_get_option('thumbnail-testimonials') || !get_the_post_thumbnail( null, 'thumb-testimonial-quote' )) :  ?>nothumb<?php endif ?>">
                            <?php echo $title . $website; ?>
                        </div>
                    </div>
               </div>
            </div>
		<?php
            if ( ($i % 2) != 0 ) echo '</div>';
            $i++;
        endwhile;
if ( ($i % 2) != 0 ) echo '</div>';