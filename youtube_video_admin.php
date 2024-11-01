<?php
add_action('admin_menu', 'youtube');
function youtube() {
    add_options_page('Youtube-Settings', 'Youtube-Settings', 8, 'Youtube-Settings', 'youtube_admin');
}

function youtube_admin() {

?>
	<h2>Youtube Video Fetcher admin options</h2>
	<i>For getting latest videos for each post enter the number of videos to be displayed</i></br></br>
	
	<form method='post' action='options.php' style='margin:0 20px;'>
		<table>
		<?php wp_nonce_field('update-options'); ?>
		<tr>
			<td>No Of Videos:</td>
			<td><input type="text" name="novideos"  value="<?php echo get_option('novideos');?>" <?php echo get_option('novideos'); ?> /></td>
		</tr>
		<tr>
			<td>
				<p class='submit'>
				<input type='submit' name='Submit' value='Update Options &raquo;'/>
		 		</p>
	 		</td>
 		</tr>
		</table>
		<input type='hidden' name='action' value='update'/>
		<input type='hidden' name='page_options' value='novideos'/>
		
	</form>
	
	<p style="font-weight:bold; margin-left:20px;">Buy me a pizzzzzza</p>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_donations">
		<input type="hidden" name="business" value="nirajmchauhan@gmail.com">
		<input type="hidden" name="lc" value="US">
		<input type="hidden" name="item_name" value="WP Plugin">
		<input type="hidden" name="item_number" value="3">
		<input type="hidden" name="no_note" value="0">
		<input type="hidden" name="currency_code" value="USD">
		<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>
	
	
<?php
}

add_action( 'add_meta_boxes', 'cd_meta_box_add' );
function cd_meta_box_add()
{
	add_meta_box( 'youtube_fetcher_id', 'Youtube Video Fetcher', 'youtube_fetcher_cb', 'post', 'normal', 'high' );
}

function youtube_fetcher_cb( $post )
{
	$values = get_post_custom( $post->ID );
	$check = isset( $values['youtube_check'] ) ? esc_attr( $values['youtube_check'][0] ) : 'on';
	wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
	?>

	<p>
		
		<input type="checkbox" name="youtube_check" id="youtube_check" <?php checked( $check, 'on' ); ?> />&nbsp;&nbsp;
		<label for="youtube_check">Show Video</label>
	</p>
	<?php
}


add_action( 'save_post', 'cd_meta_box_save' );
function cd_meta_box_save( $post_id )
{
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

	if( !current_user_can( 'edit_post' ) ) return;

	$allowed = array(
		'a' => array( // on allow a tags
			'href' => array() // and those anchords can only have href attribute
	)
	);

	$chk = ( isset( $_POST['youtube_check'] ) && $_POST['youtube_check'] ) ? 'on' : 'off';
	update_post_meta( $post_id, 'youtube_check', $chk );
}
?>