<?php 
/*
Plugin Name: Page Comments Off Please
Version: 2.0.2
License: GPL2
Plugin URI: http://techism.com/2010/dev/wp-plugins/page-comments-off-please/
Author: Joe Melberg
Author URI: http://techism.com
Donate URI: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8355SHYKY5CFS
Description: Manage page and post comments (and their defaults) separately.  Perfect for CMS users. Uncheck the comments and trackbacks checkbox for new Pages by default while leaving them checked for Posts.

* 
    Copyright 2010  TECHISM LLC  (email : melberg@techism.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////

///////////////////////
////////////////////     ////////   ///        ///   /////////  ///// //////
/////      //          ////        ///              ///        /// ///  ///
/////     ////////   ////         ///////    ///    ////////  /// ///  ///
/////    //          ////        ///   ///  ///         ///  ///      ///
///////  /////////   /////////  ///   ///  ///   /////////  ///      ///

////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
// Donate : http://bit.ly/MDMHO8
// Website : http://www.techism.com/ 
// Plugin : http://techism.com/page-comments-off-please/
////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////// 
// Thanks : http://www.yaconiello.com/blog/how-to-write-wordpress-plugin/
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
if(!class_exists('Page_Comments_Off_Please'))
{
    class Page_Comments_Off_Please
    {
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
			$this->_hook = 'page-comments-off-please';
			$this->_file = plugin_basename( __FILE__ );
			$this->_accessLevel = 'manage_options';
			$this->_slug = 'page-comments-off-please';
			$this->_handle = '';
            // register options and menus
            add_action('admin_init', array(&$this, 'admin_init'));
			add_action('admin_menu', array(&$this, 'add_menu'));
			
			// // register multisite functions
			// Coming Soon! 
			//add_action('wpmu_new_blog', array(&$this, 'new_blog'));   
			//add_action('network_admin_menu', 'init_mu_settings');
			
			// Add normal filter method
            add_filter('pre_option_default_comment_status', array(&$this, 'disable_page_comments_default'));
			add_filter('pre_option_default_ping_status', array(&$this, 'disable_page_comments_default'));
			// Add Legacy Mode Hoook
			add_action('admin_footer', array(&$this, 'disable_page_comments_legacy'));
			
			// load the admin js only on our admin page please.
			add_action( "admin_print_scripts-settings_page_page-comments-off-please", array(&$this, 'register_admin_scripts'));
						

	    } // END public function __construct
	/////////////////////////////////////////////////////////////////////		
	/////////////////////////////////////////////////////////////////////
        /**
         * Activate the plugin
         */
        public static function activate()
        {
			// Do nothing
        } // END public static function activate
	/////////////////////////////////////////////////////////////////////		
	/////////////////////////////////////////////////////////////////////
        /**
         * Deactivate the plugin
         */     
        public static function deactivate()
        {
            // Do nothing
        } // END public static function deactivate
	/////////////////////////////////////////////////////////////////////		
	/////////////////////////////////////////////////////////////////////   	
        /**
         * Register Scripts
         */     
        public static function register_admin_scripts()
        {
            // register  javascripts
			    wp_enqueue_script('page-comments-off-please-js', plugins_url( 'js/page_comments_off_please.js' , __FILE__ ), array('jquery'));
            
        } // END public static function deactivate
	/////////////////////////////////////////////////////////////////////		
	/////////////////////////////////////////////////////////////////////	
		/**
		 * hook into WP's admin_init action hook
		 */
		public function admin_init()
		{
		    // Set up the settings for this plugin
		    $this->init_settings();
			// Handle admin menu actions.
			$this->handle_actions();
		    // Possibly do additional admin_init tasks
		} // END public static function admin_init
	/////////////////////////////////////////////////////////////////////		
	/////////////////////////////////////////////////////////////////////	
		/**
		 * Initialize some custom settings
		 */     
		public function init_settings()
		{
		    // register the settings for this plugin
		    register_setting('page_comments_off_please-settings', 'disable_page_comments');
		    register_setting('page_comments_off_please-settings', 'disable_post_comments');
			register_setting('page_comments_off_please-settings', 'legacy_mode');
			// set default (untested!?)
			add_option( 'disable_page_comments', '1', '', 'yes' );
			
		} // END public function init_settings	
		/////////////////////////////////////////////////////////////////////		
		/////////////////////////////////////////////////////////////////////	
			/**
			 * Initialize some custom settings
			 */     
			public function init_mu_settings()
					{
					    add_submenu_page(
					       'settings.php',
					       'Co za asy',
					       'Co za asy',
					       'manage_network_options',
					       'my-netw-settings',
					       'your_form'
					  );

			} // END public function init_settings	/////////////////////////////////////////////////////////////////////		
	/////////////////////////////////////////////////////////////////////	
		/**
		 * Evaluation plugin actions sent by settings page.
		 */     
		public function handle_messages()
		{
			if ( ! empty( $_GET['message'] ) && 'toggle-pages' == $_GET['message'] ) {
						if ( empty( $_GET['pages-toggled'] ) || 0 == $_GET['pages-toggled'] )
							$msg = __( 'There were no pages with comments or pings turned on (#^.^#)');
						else
							$msg = sprintf( _n( 'Successfully toggled %d page.( ^.^ )', 'Successfully toggled %d pages. ( ^.^ )', $_GET['pages-toggled'], $this->_slug ), $_GET['pages-toggled'] );
						echo "<div class='updated'><p>" . esc_html( $msg ) . '</p></div>';
					}
		
		} // END public function handle_messages
	/////////////////////////////////////////////////////////////////////		
	/////////////////////////////////////////////////////////////////////	
		/**
		 * Evaluation plugin actions sent by settings page.
		 */     
		public function handle_actions()
		{
			
			if ( empty( $_GET['action'] ) || empty( $_GET['page'] ) || $_GET['page'] != $this->_hook )			
				return;
				
		    if ( 'toggle-pages' == $_GET['action'] ) {
				// Toggling Page Comments.
				// First: Check referrer for security purposes
				check_admin_referer( 'toggle-pages' );
				$redirect_args = array( 'message' => strtolower( $_GET['action'] ) );
				
				// Run the query
				/////////////////////////////////////////////////////////
				$redirect_args['pages-toggled'] = $this->toggle_page_comments_please();
				// Return them home, and we are done.
				wp_safe_redirect( add_query_arg( $redirect_args, remove_query_arg( array( 'action', '_wpnonce' ) ) ) );
				exit;
			}
		} // END public function handle_actions
	/////////////////////////////////////////////////////////////////////		
	/////////////////////////////////////////////////////////////////////	
		/**
		 * add the settings menu item
		 */             
		public function add_menu()
		{
		    $this->_handle = add_options_page('Page Comments Off Please Settings', 'Page Comments Off Please', 'manage_options', $this->_hook, array(&$this, 'plugin_settings_page'));
			
		
		
		} // END public function add_menu
	/////////////////////////////////////////////////////////////////////		
	/////////////////////////////////////////////////////////////////////
		/**
		 * Menu Callback
		 */     
		public function plugin_settings_page()
		{
		    if(!current_user_can('manage_options'))
		    {
		        wp_die(__('You do not have sufficient permissions to access this page.'));
		    }
		    // Render the settings template
		    include(sprintf("%s/includes/pcop_options.php", dirname(__FILE__)));
		} // END public function plugin_settings_page
	/////////////////////////////////////////////////////////////////////		
	/////////////////////////////////////////////////////////////////////		
		 /**
		 * Do the work - uncheck comments as a filter method.
		 */
		public function disable_page_comments_default($default) {
			// triggered by: pre_option_default_comment_status via filter.
			// don't run if legacy mode.
			$legacy = get_option( 'legacy_mode' );
			if (isset($legacy) && ($legacy=="1")) {
				// Legacy mode bipasses this filter,
				// so just pass it unchanged and go on.
				return "open";
			}
			// evaluate if this post-type should allow comments:
			 	$posts = get_option( 'disable_post_comments' );
				$pages = get_option( 'disable_page_comments' );
				
				if (isset($_REQUEST['post_type'])) {
					if ($_REQUEST['post_type'] == "page") {
						if ($pages == "1") {
							return "closed";
						} else {
							return "open";
						}
					}
				}
				elseif ($posts == "1") {
						return "closed";
				} 
			    else {
						return "open";
				}	
		} // END public function disable_page_comments_default
	/////////////////////////////////////////////////////////////////////		
	/////////////////////////////////////////////////////////////////////		
		/**
		 * Do the Work 2 - Uncheck comments as a javascript method.
		 */
		function disable_page_comments_legacy() {
			// This function checks if you are creating a new page and turns off the allow comments / allow pingbacks check boxes.  IT must be a different function from the other method because the other method is a filter where this is an action.
			// Check if legacy is the mode:
				$legacy = get_option( 'legacy_mode' );
				$posts = get_option( 'disable_post_comments' );
				$pages = get_option( 'disable_page_comments' );
				if (isset($legacy) && ($legacy=="1")) {
					// Load the post object, if any
					global $post;
					if ($post->ID) {
						// do nothing - this post is being edited and is not new.  Respect existing settings.
					} else {
						if(isset($_REQUEST['post_type'])) {
							// A crude circumstantial work-around was to use the fact that wordpress sets the request var post_type = page on new page creation.  						
							if ($_REQUEST['post_type'] == "page" && $pages=="1") {
									// A simple javascript unchecks the boxes for you.
									$this->legacy_javascript_fix();
							}
						} else {
							// this is a post...OR WE ARE EDITING!!!
							// ...Check settings
							if ($posts=="1") {
								// A simple javascript unchecks the boxes for you.
								// Only fire if this is a new post view.
								if(get_post_type($id)) {
									// this post exists					
								}
							}	
						}
					}
				}
		}// END public function disable_page_comments_legacy
	/////////////////////////////////////////////////////////////////////		
	/////////////////////////////////////////////////////////////////////				
		/**
		* Output the Javascript fix | meat of the legacy code
		*/
		public function legacy_javascript_fix(){
				$fixit = <<<ENDIT
					<script>
						if (document.post) {
							var the_comment = document.post.comment_status;
							var the_ping = document.post.ping_status;
							if (the_comment && the_ping) {
								the_comment.checked = false;
								the_ping.checked = false;
							}
						}
					</script>"
ENDIT;
					echo $fixit;
		}// END public function legacy_javascript_fix
		/////////////////////////////////////////////////////////////////////		
	
		/////////////////////////////////////////////////////////////////////		
		/////////////////////////////////////////////////////////////////////
		public function toggle_page_comments_please () {
		// this will toggle the comments on all pages to 'closed'.
		global $wpdb;
		$table = $wpdb->base_prefix . 'posts';
		$result=$wpdb->query(
			"
			UPDATE $table  
			SET comment_status = 'closed', ping_status = 'closed'
			WHERE post_type = 'page' 
			"
		);	
		// Returns number of objects that matched querry.	
		return $result;	

		}// END public function disable_page_comments_please
		/////////////////////////////////////////////////////////////////////		
	
		/////////////////////////////////////////////////////////////////////		
		/////////////////////////////////////////////////////////////////////
		public function new_blog () {
		// this will toggle the comments off for new blogs.
			// check prefs...these options need to be on the multisite superadmin side...?
			global $wpdb;
			$table = $wpdb->base_prefix . 'posts';
			$result=$wpdb->query(
				"
					UPDATE $table  
					SET comment_status = 'closed', ping_status = 'closed'
					WHERE post_type = 'page' 
					"
			); // END query	
		// Return number of objects that matched querry.	
		return $result;	
		}// END public function disable_page_comments_please

	} // END class Page_Comments_Off_Please
} // END if(!class_exists('Page_Comments_Off_Please'))
// 
///////     //      //    //////
//         // //   //   //     //
///////    //   ////   //      //
//        //     //   //     //
///////   //     //  /////////
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////		
/////////////////////////////////////////////////////////////////////
// Run an instance of the plugin class:
if(class_exists('Page_Comments_Off_Please'))
{
    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('Page_Comments_Off_Please', 'activate'));
    register_deactivation_hook(__FILE__, array('Page_Comments_Off_Please', 'deactivate'));

    // GO!!! -- instantiate the plugin class
    $page_comments_off_please = new Page_Comments_Off_Please();

	// Add a link to the settings page onto the plugin page
	if(isset($page_comments_off_please))
	{
	    // Add the settings link to the plugins page
	    function plugin_settings_link($links)
	    { 
	        $settings_link = '<a href="options-general.php?page=page-comments-off-please">Settings</a>'; 
	        array_unshift($links, $settings_link); 
	        return $links; 
	    }

	    add_filter("plugin_action_links_$page_comments_off_please->_file", 'plugin_settings_link');
	}
}

?>
