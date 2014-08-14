<?php

class portfolio_gallery extends WP_Widget

{

    function portfolio_gallery()

    {

        $widget_ops = array( 

            'classname' => 'portfolio-gallery',

            'description' => __('Get images from portfolio created on portfolio custom type.', 'yit')

        );



        $control_ops = array( 'id_base' => 'portfolio-gallery' );



        $this->WP_Widget( 'portfolio-gallery', 'Portfolio Gallery', $widget_ops, $control_ops );

    }

    

    function form( $instance )

    {

        /* Impostazioni di default del widget */

        $defaults = array( 

            'title' => 'Portfolio Gallery',
            'project_post_type' => 0,
            'items' => 6

        );

        

        $instance = wp_parse_args( (array) $instance, $defaults ); ?>

        <p><label>

            <strong><?php _e( 'Widget Title', 'yit' ) ?>:</strong><br />

            <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />

        </label></p>                     

        <p>

            <label for="<?php echo $this->get_field_id( 'project_post_type' ); ?>">Portfolio:

                <select id="<?php echo $this->get_field_id( 'project_post_type' ); ?>" name="<?php echo $this->get_field_name( 'project_post_type' ); ?>">
                    <?php $portfolios = yit_portfolios(); ?>
                    <?php foreach( $portfolios as $portfolio ): ?>
                        <option value="<?php echo $portfolio->ID ?>"<?php if($portfolio->ID == $instance['project_post_type']): ?> selected="selected"<?php endif ?>><?php echo $portfolio->post_title ? $portfolio->post_title : 'Portfolio ID: ' . $portfolio->ID ?></option>
                    <?php endforeach ?>
                </select>

            </label>

        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'items' ); ?>">Items:
                <input type="text" id="<?php echo $this->get_field_id( 'items' ); ?>" name="<?php echo $this->get_field_name( 'items' ); ?>" value="<?php echo $instance['items']; ?>" />
            </label>
        </p>

        <p><?php _e( 'Get images from portfolio created on portfolio custom type.', 'yit' ) ?></p>

        <?php

    }

    

    function widget( $args, $instance )

    {

        $yit_portfolio = yit_portfolios();

        extract( $args );

        $project_n_items = isset( $instance['items']) ? $instance['items'] : 6;
        $project_post_types = isset( $instance['project_post_type']) ? $instance['project_post_type'] : false;

        /* User-selected settings. */
        $title = apply_filters('widget_title', $instance['title'] );

        global $more;
        $more = 0;


        if( $project_post_types ) {
            foreach( $yit_portfolio as $portfolio ) {
                if($portfolio->ID == $project_post_types) {
                    echo $before_widget;

                    if ( $title ) echo $before_title . $title . $after_title;

                    $portfolios = yit_portfolio_get_setting( 'items', $portfolio->ID );

                    echo '<div class="portfolio-gallery-widget">';

                    echo '<ul>';

                    //loop
                    yit_set_portfolio_loop( $portfolio->ID );
                    $i = 0;
                    foreach( $portfolios as $item_id => $item ) {

                        $post_permalink = yit_work_permalink( $item_id );

                        echo '<li class="work-item group">';
                        echo '<a class="work-thumb" href="' . $post_permalink . '">';
                        yit_image( "id=$item_id&size=portfolio_gallery_thumb" );
                        echo '</a>';

                        echo '</li>';

                        if( ++$i == $project_n_items ) break;
                    }

                    echo '</ul>';

                    echo '</div>';

                    echo $after_widget;

                    break;
                }
            }
        }

    }                     



    function update( $new_instance, $old_instance ) 

    {

        $instance = $old_instance;



        $instance['title'] = strip_tags( $new_instance['title'] );

        $instance['project_post_type'] = $new_instance['project_post_type'];

        $instance['items'] = $new_instance['items'];

        return $instance;

    }

    

}     

?>