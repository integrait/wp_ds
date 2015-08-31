<style type="text/css">
	<?php include "pcop_settings.css"; ?>
</style>
<div id="pcop_options_wrap" class="wrap">
<h2>Page Comments Off Please 2.0</h2>
<?php $this->handle_messages(); ?>
<div class="colset">
	<div class="column c_left">
		<p>This plugin lets you control the default behavior of page and post comments individually.  By default, this plugin will change the default comment setting for pages to 'disabled'.</p>
		<h3>NOTE:</h3>
		<p>This plugin does NOT disable comments on existing pages by default. That would be startling to some users. However we have provided a button below to let you do this with one-click.  Thanks!</p>


		<form method="post" action="options.php"> 
		        <?php @settings_fields('page_comments_off_please-settings'); ?>
		        <?php @do_settings_fields('page_comments_off_please-settings'); ?>

		        <table class="form-table">  
		            <tr valign="top">
		                <th scope="row"><label for="disable_page_comments">Disable Page Comments Please</label></th>
		                <td><input name="disable_page_comments" type="checkbox" value="1" <?php checked( '1', get_option( 'disable_page_comments' ) ); ?> /></td>
		            </tr>
		           	 <tr valign="top">
			                <th scope="row"><label for="disable_post_comments">Disable Post Comments Please</label></th>
			                <td><input name="disable_post_comments" type="checkbox" value="1" <?php checked( '1', get_option( 'disable_post_comments' ) ); ?> /></td>
			            </tr>
						 <tr valign="top">
				                <td colspan="2"><hr /></td>
				            </tr>
						 <tr valign="top">
				                <th scope="row"><label for="legacy_mode">Legacy Mode Please</label></th>
				                <td><input name="legacy_mode" type="checkbox" value="1" <?php checked( '1', get_option( 'legacy_mode' ) ); ?> /></td>
				            </tr>
						 <tr valign="top">
				                <td colspan="2"><small>( Forces version 1.0 operating-mode for backward compatibility )</small></td>
				            </tr>
						 <tr valign="top">
				                <td colspan="2"><hr /></td>
				            </tr>
		        </table>
		<?php
		$toggle_pages_url = wp_nonce_url( add_query_arg( array( 'action' => 'toggle-pages' ) ), 'toggle-pages' );
		?>
				<p><a class="toggle" href="<?php echo esc_url( $toggle_pages_url ); ?>"><?php _e( 'Toggle Page Comments Off Please', 'page-comments-off-please' ); ?></a><br />
											<small><?php _e( "This link will disable your page comments on your existing pages if you want."); ?></small>
					
					</p>
		        <?php @submit_button(); ?>
		    </form>
	</div><!-- .column .c_right -->
	<div class="column c_left">
		<h3>I'm a victim of my awesome.</h3>
		<p>On the real, thank you.  I'm so flattered to have 10,000 downloads and a 5-star rating from you as of 7-2012. If you are using this for work that is getting YOU paid, then maybe you can float a little something my way?  I'd appreciate it.  Thanks for using my plugin! You have already made me feel like a winner. -Joe</p>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="8355SHYKY5CFS">
		<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>	
	</div><!-- .column .c_right -->
</div><!-- .colset -->
</div><!-- #pcop_options_wrap -->

