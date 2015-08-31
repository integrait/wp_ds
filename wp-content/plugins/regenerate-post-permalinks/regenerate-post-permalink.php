<?php
/*
  Plugin Name: Regenerate post permalinks
  Plugin URI: http://www.sandorkovacs.ro/blog/simple-post-meta-manager-wordpress-plugin/
  Description: This plugin can help you to regenerate all your permalinks based on the post titles.
  Author: Sandor Kovacs
  Version: 1.0.1
  Author URI: http://sandorkovacs.ro/en/
 */


add_action('admin_menu', 'register_simple_rpp_submenu_page');

function register_simple_rpp_submenu_page() {
    add_submenu_page(
            'options-general.php', __('Permalink regeneration'), __('Permalink regeneration'), 'manage_options', 'regenerate-post-permalink', 'rpp_callback');
}

// Regenerate post permalink
function regenerate_post_permalink($post_type = 'post') {
    global $wpdb;

    $myrows = $wpdb->get_results("SELECT id, post_title FROM $wpdb->posts WHERE post_status = 'publish' AND post_type='$post_type' ");

    $counter = 0;
    foreach ($myrows as $pid) :
        $guid = home_url() . '/' . sanitize_title_with_dashes($pid->post_title);
        $sql = "UPDATE $wpdb->posts 
                     SET post_name = '" . sanitize_title_with_dashes($pid->post_title) . "',
                         guid = '" . $guid . "'
               WHERE ID = $pid->id";
        $wpdb->query($sql);
        $counter++;
    endforeach;

    return $counter;
}

/** POST META MANAGER PLUGIN PAGE * */
function rpp_callback() {
    ?>
    <div class="wrap" id='simple-sf'>
        <div class="icon32" id="icon-options-general"><br></div><h2><?php _e('Permalinks regeneration'); ?></h2>
        <?php _e('<p>Simply select the post type and click on the Regenerate permalinks button.It will regenerate all the permalinks based on title.  </p>
        <p>Eg. <strong>"This is my article title"</strong> will have the following permalink:  <em>"this-is-my-article-title"</em></p>') ?>

    <?php
    if (isset($_POST['submit-regenerate-permalinks'])) :
        $counter = regenerate_post_permalink($_POST['rpp-post-type']);
        ?>
            
      <p>  <?php printf( __( '%1$s permalinks have been regenerated for all posts having type: <strong>%2$s</strong>' ), $counter, $_POST['rpp-post-type'] ); ?></p>
        
            <?php else:
            ?>

            <br/>
            <form action="" method="post" name="rpp">

                <table class="form-table">

                    <tr>
                        <th scope="row"><strong><?php _e('Select post type'); ?></strong></th>
                        <td>      
                            <select name="rpp-post-type">
                            <?php
                            $post_types = get_post_types(array('public' => true), 'names');

                            foreach ($post_types as $post_type) {

                                echo '<option value="' . $post_type . '">' . $post_type . '</option>';
                            }
                            ?>                
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">&nbsp;</th>
                        <td>      
                            <input type='submit' 
                                   onclick='if (!window.confirm("<?php _e('Are you really sure?') ?>")) return false'
                                   name='submit-regenerate-permalinks' value='<?php _e('Regenerate permalinks') ?>' />
                        </td>
                    </tr>

                </table>

            </form>

    <?php endif; ?>
    </div>

    <?php
}
