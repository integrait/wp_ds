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

if( !class_exists( 'recent_posts' ) ) :
class recent_posts extends WP_Widget {
    function recent_posts() {
    	unregister_widget( 'WP_Widget_Recent_Posts' );
		
        $widget_ops = array( 
            'classname' => 'recent-posts', 
            'description' => __('The latest posts, with a preview thumb.', 'yit') 
        );

        $control_ops = array( 'id_base' => 'recent-posts' );

        $this->WP_Widget( 'recent-posts', __('Recent Posts', 'yit'), $widget_ops, $control_ops );
    }
    
    function widget( $args, $instance ) {
        extract( $args );

        /* User-selected settings. */
        if( !isset( $instance['title'] ) )
            $instance['title'] = '';
            
        $title = apply_filters('widget_title', $instance['title'] );

        $items = isset( $instance['items']) ? $instance['items'] : '';
        $more_text = isset( $instance['more_text']) ? $instance['more_text'] : '';  
        $show = isset( $instance['show'] ) ? $instance['show'] : 'nothing';
        $excerpt = isset( $instance['excerpt']) ? $instance['excerpt'] : 'no';
        $excerpt_length = isset( $instance['excerpt_length'] ) ? $instance['excerpt_length'] : 10;
        $show_comments = isset( $instance ['show_comments'] ) ? $instance['show_comments'] : 'no';

        echo $before_widget;
        
        if ( $title ) echo $before_title . $title . $after_title;

        $args = array(
           'posts_per_page' => $items,
           'orderby' => 'date',
           'ignore_sticky_posts' => 1
        );                            
        
        $args['order'] = 'DESC'; 
        
        $myposts = new WP_Query( $args );
    	
        $html = "\n";       
        $html .= '<div class="recent-post group">'."\n";
        
        if( $myposts->have_posts() ) : while( $myposts->have_posts() ) { $myposts->the_post();
            
            $img = '';
            if(has_post_thumbnail())
                { $img = yit_image( "size=blog_thumb&alt=blog_thumb", false ); }
			
            else
                { $img = '<img src="'.get_template_directory_uri().'/images/no_image_recentposts.jpg" alt="No Image" />'; }
			
    		    
            $html .= '<div class="hentry-post group">'."\n";	
				
            if ( $show == 'thumb' ) {
                $html .= '<div class="thumb-img">' . $img . '</div>';
                $html .= '<div class="text">';
            } elseif ( $show == 'date' ) {
                $html .= '<div class="thumb-date"><span class="month">' . get_the_date('M') . '</span><span class="day">' . get_the_date('d') . '</span></div>';
                $html .= '<div class="text">';
            } else {
                $html .= '<div class="text without-thumbnail">';
            }
            
            $html .= the_title( '<a href="'.get_permalink().'" title="'.get_the_title().'" class="title">', '</a>', false );
            
            if ( $excerpt == 'yes' ) {
            	if( strpos( $more_text, "href='#'" ) ) {
	                $post_readmore = str_replace( "href='#'", "href='" . get_permalink() . "'", str_replace( '"', "'", do_shortcode( $more_text ) ) );
	            } else {
	            	$post_readmore = $more_text;
	            }
                $html .= yit_content( 'excerpt', $excerpt_length, $post_readmore );
            }

            if ( $show_comments == 'yes' ) {
                $html .= '<p class="post-comments">';
                $number_comments = get_comments_number();
                if ( $number_comments == 0 ) {
                    $html .= __('0 comments', 'yit');
                } elseif ( $number_comments == 1 ) {
                    $html .= __('1 comment', 'yit');
                } else {
                    $html .= $number_comments . __(' comments', 'yit');
                }
                $html .= '</p>';
            }
			
            $html .= '</div>'."\n";
    		$html .= '</div>'."\n";
        
        } endif;
        
        wp_reset_query();
        $html .= '</div>';
        
        echo $html;
        
        add_filter( 'the_content_more_link', 'yit_sc_more_link', 10, 3 );  //shortcode in more links
        
        echo $after_widget;
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['title'] = strip_tags( $new_instance['title'] );

        $instance['items'] = $new_instance['items'];

        $instance['show'] = $new_instance['show'];

        $instance['excerpt'] = $new_instance['excerpt'];

        $instance['excerpt_length'] = $new_instance['excerpt_length'];

        $instance['more_text'] = str_replace( '"', "'", $new_instance['more_text'] );
        
        $instance['show_comments'] = $new_instance['show_comments'];

        return $instance;
    }

    function form( $instance ) {   
        /* Impostazioni di default del widget */
        $defaults = array( 
            'title' => __('Recent Posts', 'yit'), 
            'items' => 3,
            'show' => 'thumb',
            'excerpt' => 'no',
            'excerpt_length' => '10',
            'more_text' => '|| ' . __( 'Read More', 'yit' ),
            'show_comments' => 'no'
        );
        
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>
        
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'yit' ) ?>:
                 <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
            </label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'items' ); ?>"><?php _e( 'Items', 'yit' ) ?>:
                <input type="text" id="<?php echo $this->get_field_id( 'items' ); ?>" name="<?php echo $this->get_field_name( 'items' ); ?>" value="<?php echo $instance['items']; ?>" size="3" />
            </label>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id( 'show' ); ?>"><?php _e( 'Show', 'yit' ) ?>:
                 <select id="<?php echo $this->get_field_id( 'show' ); ?>" name="<?php echo $this->get_field_name( 'show' ); ?>">
                    <option value="nothing" <?php selected( $instance['show'], 'nothing' ) ?>><?php _e( 'Nothing', 'yit' ) ?></option>
                     <option value="thumb" <?php selected( $instance['show'], 'thumb' ) ?>><?php _e( 'Thumbnails', 'yit' ) ?></option>
                     <option value="date" <?php selected( $instance['show'], 'date' ) ?>><?php _e( 'Date', 'yit' ) ?></option>
                 </select>
            </label>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id( 'excerpt' ); ?>"><?php _e( 'Show Excerpt', 'yit' ) ?>:
                 <select id="<?php echo $this->get_field_id( 'excerpt' ); ?>" name="<?php echo $this->get_field_name( 'excerpt' ); ?>">
                    <option value="yes" <?php selected( $instance['excerpt'], 'yes' ) ?>><?php _e( 'Yes', 'yit' ) ?></option>
                    <option value="no" <?php selected( $instance['excerpt'], 'no' ) ?>><?php _e( 'No', 'yit' ) ?></option>
                 </select>
            </label>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id( 'excerpt_length' ); ?>"><?php _e( 'Excerpt Lenght', 'yit' ) ?>:
                 <input type="text" id="<?php echo $this->get_field_id( 'excerpt_length' ); ?>" name="<?php echo $this->get_field_name( 'excerpt_length' ); ?>" value="<?php echo $instance['excerpt_length']; ?>"  size="3" />
            </label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'more_text' ); ?>"><?php _e( 'More Text', 'yit' ) ?>:
                <input type="text" id="<?php echo $this->get_field_id( 'more_text' ); ?>" name="<?php echo $this->get_field_name( 'more_text' ); ?>" value="<?php echo $instance['more_text']; ?>" class="widefat" />
            </label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'show_comments' ); ?>"><?php _e( 'Show Comments', 'yit' ) ?>:
                <select id="<?php echo $this->get_field_id( 'show_comments' ); ?>" name="<?php echo $this->get_field_name( 'show_comments' ); ?>">
                    <option value="yes" <?php selected( $instance['show_comments'], 'yes' ) ?>><?php _e( 'Yes', 'yit' ) ?></option>
                    <option value="no" <?php selected( $instance['show_comments'], 'no' ) ?>><?php _e( 'No', 'yit' ) ?></option>
                </select>
            </label>
        </p>
    <?php
    }
}
endif;