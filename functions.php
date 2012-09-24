<?php
// If BuddyPress is not activated, switch back to the default WP theme
if ( !defined( 'BP_VERSION' ) )
	switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
	
// Set up theme. Taken mostly from bp-default theme. 
function bp_dtheme_setup() {
	global $bp;

	// Load the AJAX functions for the theme
	require( TEMPLATEPATH . '/_inc/ajax.php' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'buddypress' ),
	) );
	
	if ( !is_admin() ) {
		// Register buttons for the relevant component templates
		// Friends button
		if ( bp_is_active( 'friends' ) )
			add_action( 'bp_member_header_actions',    'bp_add_friend_button' );

		// Activity button
		if ( bp_is_active( 'activity' ) )
			add_action( 'bp_member_header_actions',    'bp_send_public_message_button' );

		// Messages button
		if ( bp_is_active( 'messages' ) )
			add_action( 'bp_member_header_actions',    'bp_send_private_message_button' );

		// Group buttons
		if ( bp_is_active( 'groups' ) ) {
			add_action( 'bp_group_header_actions',     'bp_group_join_button' );
			add_action( 'bp_group_header_actions',     'bp_group_new_topic_button' );
			add_action( 'bp_directory_groups_actions', 'bp_group_join_button' );
		}

		// Blog button
		if ( bp_is_active( 'blogs' ) )
			add_action( 'bp_directory_blogs_actions',  'bp_blogs_visit_blog_button' );
	}
}
add_action( 'after_setup_theme', 'bp_dtheme_setup' );

// Add main CSS and Google Font CSS	
function bp_dtheme_enqueue_styles() {
	// Bump this when changes are made to bust cache
	$version = '20120921';
	// Register our main stylesheet
    wp_enqueue_style( 'bp-default-main', get_template_directory_uri() . '/_inc/css/default.css', array(), $version );
	// Main CSS
    wp_enqueue_style( 'bpflash-main', get_stylesheet_directory_uri() . '/style.css', array(), $version );
}
add_action( 'wp_print_styles', 'bp_dtheme_enqueue_styles' );

// Load up functions-custom.php, if the user has selected that option in theme options.
add_action( 'after_setup_theme', 'bpflash_add_custom_functions' );
function bpflash_add_custom_functions() {
	$options = get_option('bpflash_theme_options');
	
	if ( $options['customphp'] == 1 ) {
		get_template_part('functions-custom');
	}
}

// Add site credits by filtering exising text in footer.php from bp-default.
add_filter('gettext', 'bpflash_sitecredits', 20, 3);
/**
 * Edit the default credits to add BP Flash link. Remove it if you'd like or modify it to display whatever you want. 
 *
 * @link http://codex.wordpress.org/Plugin_API/Filter_Reference/gettext
 */
function bpflash_sitecredits( $translated_text, $untranslated_text, $domain ) {
    $custom_field_text = 'Proudly powered by <a href="%1$s">WordPress</a> and <a href="%2$s">BuddyPress</a>.';
    if ( $untranslated_text === $custom_field_text ) {
        return 'Copyright &copy; 2012, <a href="http://flash.org/">FLASH</a>.';
    }
    return $translated_text;
}

?>
