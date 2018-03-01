<?php

add_action( 'admin_init', 'wp04_theme_options_init' );
add_action( 'admin_menu', 'wp04_theme_options_add_page' );

/**
 * Prep the necessary scripts for image uploads.
 *
 * @since WP-ZeroFour 1.0
 */
function wp04_options_enqueue_scripts() {
	if ( 'appearance_page_wp04_theme_options' == get_current_screen() -> id ) :
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');
		wp_enqueue_script('media-upload');
	endif;
}
add_action( 'admin_enqueue_scripts', 'wp04_options_enqueue_scripts' );


/**
 * Init plugin options to white list our options
 */
function wp04_theme_options_init(){
	register_setting( 'wp04_options', 'wp04_theme_options', 'wp04_theme_options_validate' );
}

/**
 * Load up the menu page
 */
function wp04_theme_options_add_page() {
	add_theme_page( 'WP-ZeroFour Options', 'WP-ZeroFour Options', 'manage_options', 'wp04_theme_options', 'wp04_theme_options_do_page' );
}

/**
 * Create tab navigation for settings
 *
 * @since WP-ZeroFour 1.0
 */
function wp04_admin_tabs( $current = 'general' ) {
	$tabs = array( 'general' => 'General',  'homepage' => 'Home Settings', 'media' => 'Media Section', 'contact' => 'Contact' );
	echo '<div id="icon-themes" class="icon32"><br></div>';
	echo '<h2 class="nav-tab-wrapper">';
	foreach( $tabs as $tab => $name ){
		$class = ( $tab == $current ) ? ' nav-tab-active' : '';
		echo "<a class='nav-tab$class' href='?page=wp04_theme_options&tab=$tab'>$name</a>";
	}
	echo '</h2>';
}

/**
 * Create the options page
 */
function wp04_theme_options_do_page() {
	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;

	?>
	<div class="wrap">
		<?php screen_icon(); echo "<h2>" . __( 'WP-ZeroFour Theme Settings', 'wpzerofour' ) . "</h2>"; ?>

		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved', 'wpzerofour' ); ?></strong></p></div>
		<?php endif; ?>

		<?php if ( isset ( $_GET['tab'] ) ) wp04_admin_tabs($_GET['tab']); else wp04_admin_tabs('general'); ?>

		<form method="post" action="options.php" enctype="multipart/form-data">
			<?php settings_fields( 'wp04_options' ); ?>
			<?php $options = get_option( 'wp04_theme_options' ); ?>
	<?php 
	$dispGeneral = "none";
	$dispHomePage = "none";
	$dispMediaSection = "none";
	$dispContact = "none";
	
	if ( isset ( $_GET['tab'] ) ) 
		$tab = $_GET['tab']; 
	else 
		$tab = 'general'; 

	switch ( $tab ) :
		case 'general' : 
			$dispGeneral = "block";
			break; 
		case 'homepage' : 
			$dispHomePage = "block";
			break; 
		case 'media' : 
			$dispMediaSection = "block";
			break; 
		case 'contact' : 
			$dispContact = "block";
			break; 
	endswitch; 

	?>
		<div id = "dispGeneral"  style="display: <?= $dispGeneral;?>">	
			<h3 class="title"><?php _e( 'Layout Options', 'wpzerofour' ); ?></h3>

			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label class="description" for="wp04_theme_options[header_img]"><?php _e( 'Header Background Image', 'wpzerofour' ); ?></label></th>
						<td>
							<input id="wp04_theme_options[header_img]" class="regular-text" type="text" name="wp04_theme_options[header_img]" value="<?php echo esc_url( $options['header_img'] ); ?>" /> 
							<input id="upload_header_img_button" type="button" class="button" value="<?php _e( 'Upload Image', 'wpzerofour' ); ?>" />
							<span class="description"><?php _e('Ideal size is 1400x651.', 'wpzerofour' ); ?></span>
						</td>
					</tr>
				</tbody>
			</table>

			<h3 class="title"><?php _e( 'Analytics and Tracking', 'wpzerofour' ); ?></h3>

			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label class="description" for="wp04_theme_options[gaID]"><?php _e( 'Google Analytics Profile ID', 'wpzerofour' ); ?></label></th>
						<td><input id="wp04_theme_options[gaID]" class="regular-text" type="text" name="wp04_theme_options[gaID]" value="<?php esc_attr_e( $options['gaID'] ); ?>" placeholder="e.g. UA-12345678-1" /></td>
					</tr>
					<tr>
						<th scope="row"><label class="description" for="wp04_theme_options[tracking]"><?php _e( 'Other Tracking Code', 'wpzerofour' ); ?></label></th>
						<td><textarea id="wp04_theme_options[tracking]" class="large-text" cols="30" rows="8" name="wp04_theme_options[tracking]"><?php echo esc_textarea( $options['tracking'] ); ?></textarea></td>
					</tr>
				</tbody>
			</table>

			<script>
			jQuery(document).ready(function($) {
				$('#upload_header_img_button').click(function() {
					tb_show('Upload a header image', 'media-upload.php?TB_iframe=true', false);

					window.send_to_editor = function(html) {
						var image_url = $('img',html).attr('src');
						$('#upload_header_img_button').prev('input').val(image_url);
						tb_remove();
					}

					return false;
				});
			});
			</script>
		</div>  <!-- END div id = "dispGeneral" -->	

		<div id = "dispHomePage"  style="display: <?= $dispHomePage;?>">	

			<h3 class="title"><?php _e( 'Centerpiece Settings', 'wpzerofour' ); ?></h3>

			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label class="description" for="wp04_theme_options[centerpiece_headline]"><?php _e( 'Main Headline', 'wpzerofour' ); ?></label></th>
						<td><input id="wp04_theme_options[centerpiece_headline]" class="regular-text" type="text" name="wp04_theme_options[centerpiece_headline]" value="<?php esc_attr_e( $options['centerpiece_headline'] ); ?>" /></td>
					</tr>
					<tr>
						<th scope="row"><label class="description" for="wp04_theme_options[centerpiece_subheading]"><?php _e( 'Subheading', 'wpzerofour' ); ?></label></th>
						<td><input id="wp04_theme_options[centerpiece_subheading]" class="regular-text" type="text" name="wp04_theme_options[centerpiece_subheading]" value="<?php esc_attr_e( $options['centerpiece_subheading'] ); ?>" /></td>
					</tr>
					<tr>
						<th scope="row"><label class="description" for="wp04_theme_options[centerpiece_button_label]"><?php _e( 'Button Label', 'wpzerofour' ); ?></label></th>
						<td>
							<input id="wp04_theme_options[centerpiece_button_label]" class="regular-text" type="text" name="wp04_theme_options[centerpiece_button_label]" value="<?php esc_attr_e( $options['centerpiece_button_label'] ); ?>" />
							<span class="description"><?php _e('Leave blank to exclude button.', 'wpzerofour' ); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label class="description" for="wp04_theme_options[centerpiece_button_link]"><?php _e( 'Button Link', 'wpzerofour' ); ?></label></th>
						<td><input id="wp04_theme_options[centerpiece_button_link]" class="regular-text" type="text" name="wp04_theme_options[centerpiece_button_link]" value="<?php echo esc_url( $options['centerpiece_button_link'] ); ?>" /></td>
					</tr>
					<tr>
						<th scope="row"><label class="description" for="wp04_theme_options[centerpiece_button_icon]"><?php echo _e( 'Button Icon', 'wpzerofour' ); ?></label></th>
						<td>
<?php
			//OK 	check-circle
			//OK 	info-circle
			//REP 	minus-circle Minus Sign /// WAS  ?? 
			//REP 	exclamation-circle Exclamation /// WAS cog Cog
			//REP 	play-circle Play Button /// WAS arrow-o Arrow
			//REP 	plus-circle Plus Sign /// WAS file File
			//REP	times-circle Times Sign /// WAS user User
			//NEW 	question-circle Question Mark /// WAS chart Chart
			//NO 	dot-circle // didn't work
			//NO	pause-circle // didn't work
			//NO 	stop-circle // didn't work
			//ALSO	removed file-text Text
?>
							<select id="wp04_theme_options[centerpiece_button_icon]" name="wp04_theme_options[centerpiece_button_icon]">
								<option value="">-<?php echo _e( 'None', 'wpzerofour' ); ?>-</option>

								<option value="check"<?php if( $options['centerpiece_button_icon'] == 'check' ) : ?> selected<?php endif; ?>>Checkmark</option>
								<option value="info"<?php if( $options['centerpiece_button_icon'] == 'info' ) : ?> selected<?php endif; ?>>Info</option>
								<option value="play"<?php if( $options['centerpiece_button_icon'] == 'play' ) : ?> selected<?php endif; ?>>Play Button</option>
								<option value="plus"<?php if( $options['centerpiece_button_icon'] == 'plus' ) : ?> selected<?php endif; ?>>Plus Sign</option>
								<option value="minus"<?php if( $options['centerpiece_button_icon'] == 'minus' ) : ?> selected<?php endif; ?>>Minus Sign</option>
								<option value="user"<?php if( $options['centerpiece_button_icon'] == 'times' ) : ?> selected<?php endif; ?>>Times Sign</option>
								<option value="question"<?php if( $options['centerpiece_button_icon'] == 'question' ) : ?> selected<?php endif; ?>>Question Mark</option>
								<option value="exclamation"<?php if( $options['centerpiece_button_icon'] == 'exclamation' ) : ?> selected<?php endif; ?>>Exclamation</option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label class="description" for="wp04_theme_options[centerpiece_button_type]"><?php echo _e( 'Button Type', 'wpzerofour' ); ?></label></th>
						<td>
							<select id="wp04_theme_options[centerpiece_button_type]" name="wp04_theme_options[centerpiece_button_type]">
								<option value="primary"<?php if( $options['centerpiece_button_type'] == 'primary' ) : ?> selected<?php endif; ?>>Primary</option>
								<option value="secondary"<?php if( $options['centerpiece_button_type'] == 'secondary' ) : ?> selected<?php endif; ?>>Secondary</option>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
		</div>  <!-- END div id = "dispHomePage" -->	

		<div id = "dispMediaSection"  style="display: <?= $dispMediaSection;?>">	

			<h3 class="title"><?php _e( 'Media Section', 'wpzerofour' ); ?></h3>

			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label class="description" for="wp04_theme_options[site_logo]"><?php _e( 'Site Logo Image', 'wpzerofour' ); ?></label></th>
						<td>
							<input id="wp04_theme_options[site_logo]" class="regular-text" type="text" name="wp04_theme_options[site_logo]" value="<?php echo esc_url( $options['site_logo'] ); ?>" /> 
							<input id="upload_site_logo_img_button" type="button" class="button" value="<?php _e( 'Upload Logo', 'wpzerofour' ); ?>" />
							<span class="description"><?php _e('Ideal size is about 250x50 px.', 'wpzerofour' ); ?></span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>  <!-- END div id = "dispMediaSection" -->	

		<style> .regular-text32{width: 99%;} </style> <!-- Gotta Do What You Gotta Do -->

		<div id = "dispContact"  style="display: <?= $dispContact;?>">	
			<h3 class="title">Contact Information</h3>
			
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label class="description" for="wp04_theme_options[email]"><?php _e( 'Email', 'wpzerofour' ); ?></label></th>
						<td><input id="wp04_theme_options[email-label]" class="regular-text32" type="text" name="wp04_theme_options[email-label]" value="<?php esc_attr_e( $options['email-label'] ); ?>" placeholder="(Type label here to show email, e.g., Email)" /></td>
						<td><input id="wp04_theme_options[email-disp]" class="regular-text32" type="text" name="wp04_theme_options[email-disp]" value="<?php esc_attr_e( $options['email-disp'] ); ?>" placeholder="(Displayed address. Blank uses actual address.)" /></td>
						<td><input id="wp04_theme_options[email]" class="regular-text32" type="text" name="wp04_theme_options[email]" value="<?php esc_attr_e( $options['email'] ); ?>" placeholder="(Email address here will be linked)" /></td>
					</tr>
					<tr>
						<th scope="row"><label class="description" for="wp04_theme_options[url]"><?php _e( 'URL', 'wpzerofour' ); ?></label></th>
						<td><input id="wp04_theme_options[url-label]" class="regular-text32" type="text" name="wp04_theme_options[url-label]" value="<?php echo esc_attr_e( $options['url-label'] ); ?>" placeholder="(Type label here to show URL, e.g., WWW)" /></td>
						<td><input id="wp04_theme_options[url-disp]" class="regular-text32" type="text" name="wp04_theme_options[url-disp]" value="<?php echo esc_attr_e( $options['url-disp'] ); ?>" placeholder="(Displayed URL. Blank uses actual URL.)" /></td>
						<td><input id="wp04_theme_options[url]" class="regular-text32" type="text" name="wp04_theme_options[url]" value="<?php echo esc_attr_e( $options['url'] ); ?>" placeholder="(URL here will be linked)" /></td>
					</tr>
					<tr>
						<th scope="row"><label class="description" for="wp04_theme_options[phone]"><?php _e( 'Phone', 'wpzerofour' ); ?></label></th>
						<td><input id="wp04_theme_options[phone-label]" class="regular-text32" type="text" name="wp04_theme_options[phone-label]" value="<?php echo esc_textarea( $options['phone-label'] ); ?>" placeholder="(Type phone number label here, e.g., Phone)" /></td>
						<td><input id="wp04_theme_options[phone-disp]" class="regular-text32" type="text" name="wp04_theme_options[phone-disp]" value="<?php echo esc_textarea( $options['phone-disp'] ); ?>" placeholder="(Displayed phone. Blank hides phone field.)" /></td>
						<td><input id="wp04_theme_options[phone]" class="regular-text32" type="text" name="wp04_theme_options[phone]" value="<?php echo esc_textarea( $options['phone'] ); ?>" placeholder="(Number here will be linked)" /></td>
					</tr>
					<tr>
						<th scope="row"><label class="description" for="wp04_theme_options[address]"><?php _e( 'Address', 'wpzerofour' ); ?></label></th>
						<td colspan="3"><textarea id="wp04_theme_options[address]" class="large-text" cols="3" rows="8" name="wp04_theme_options[address]"><?php esc_attr_e( $options['address'] ); ?></textarea></td>
					</tr>
					<tr>
						<th scope="row"><label class="description" for="wp04_theme_options[social-link-1-label]"><?php _e( 'Social Link 1', 'wpzerofour' ); ?></label></th>
						<td><input id="wp04_theme_options[social-link-1-label]" class="regular-text-32" type="text" name="wp04_theme_options[social-link-1-label]" value="<?php esc_attr_e( $options['social-link-1-label'] ); ?>" placeholder="<?php _e( 'link label (e.g., Twitter)', 'wpzerofour' ); ?>" /></td>
						<td><input id="wp04_theme_options[social-link-1-name]" class="regular-text-32" type="text" name="wp04_theme_options[social-link-1-name]" value="<?php esc_attr_e( $options['social-link-1-name'] ); ?>" placeholder="<?php _e( 'link name (e.g., @example)', 'wpzerofour' ); ?>" /></td>
						<td><input id="wp04_theme_options[social-link-1-href]" class="regular-text-32" type="text" name="wp04_theme_options[social-link-1-href]" value="<?php esc_attr_e( $options['social-link-1-href'] ); ?>" placeholder="<?php _e( 'link url (e.g., https://twitter.com/example)', 'wpzerofour' ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row"><label class="description" for="wp04_theme_options[social-link-2-label]"><?php _e( 'Social Link 2', 'wpzerofour' ); ?></label></th>
						<td><input id="wp04_theme_options[social-link-2-label]" class="regular-text-32" type="text" name="wp04_theme_options[social-link-2-label]" value="<?php esc_attr_e( $options['social-link-2-label'] ); ?>" placeholder="<?php _e( 'link label', 'wpzerofour' ); ?>" /></td>
						<td><input id="wp04_theme_options[social-link-2-name]" class="regular-text-32" type="text" name="wp04_theme_options[social-link-2-name]" value="<?php esc_attr_e( $options['social-link-2-name'] ); ?>" placeholder="<?php _e( 'link name', 'wpzerofour' ); ?>" /></td>
						<td><input id="wp04_theme_options[social-link-2-href]" class="regular-text-32" type="text" name="wp04_theme_options[social-link-2-href]" value="<?php esc_attr_e( $options['social-link-2-href'] ); ?>" placeholder="<?php _e( 'link url', 'wpzerofour' ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row"><label class="description" for="wp04_theme_options[social-link-1-label]"><?php _e( 'Social Link 3', 'wpzerofour' ); ?></label></th>
						<td><input id="wp04_theme_options[social-link-3-label]" class="regular-text-32" type="text" name="wp04_theme_options[social-link-3-label]" value="<?php esc_attr_e( $options['social-link-3-label'] ); ?>" placeholder="<?php _e( 'link label', 'wpzerofour' ); ?>" /></td>
						<td><input id="wp04_theme_options[social-link-3-name]" class="regular-text-32" type="text" name="wp04_theme_options[social-link-3-name]" value="<?php esc_attr_e( $options['social-link-3-name'] ); ?>" placeholder="<?php _e( 'link name', 'wpzerofour' ); ?>" /></td>
						<td><input id="wp04_theme_options[social-link-3-href]" class="regular-text-32" type="text" name="wp04_theme_options[social-link-3-href]" value="<?php esc_attr_e( $options['social-link-3-href'] ); ?>" placeholder="<?php _e( 'link url', 'wpzerofour' ); ?>" />
						</td>
					</tr>
				</tbody>
			</table>
		</div>  <!-- END div id = "dispContact" -->	
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'wpzerofour' ); ?>" />
			</p>
		</form>
	<?php
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function wp04_theme_options_validate( $input ) {
	global $select_options, $radio_options;

	// Our checkbox value is either 0 or 1
	if ( ! isset( $input['option1'] ) )
		$input['option1'] = null;
	$input['option1'] = ( $input['option1'] == 1 ? 1 : 0 );

	// Say our text option must be safe text with no HTML tags
	$input['sometext'] = wp_filter_nohtml_kses( $input['sometext'] );

	// Our select option must actually be in our array of select options
	if ( ! array_key_exists( $input['selectinput'], $select_options ) )
		$input['selectinput'] = null;

	// Our radio option must actually be in our array of radio options
	if ( ! isset( $input['radioinput'] ) )
		$input['radioinput'] = null;
	if ( ! array_key_exists( $input['radioinput'], $radio_options ) )
		$input['radioinput'] = null;

	// Say our textarea option must be safe text with the allowed tags for posts
	$input['sometextarea'] = wp_filter_post_kses( $input['sometextarea'] );

	return $input;
}

// adapted from http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/