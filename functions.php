<?php

// Enqueue scripts
function bpn_custom_scripts() {
	wp_enqueue_script(
		'bpn-custom-script',
		get_template_directory_uri() . '/assets/js/custom.js',
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);

	wp_localize_script( 'bpn-custom-script', 'bpn_ajax_obj',
		array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'bpn_custom_scripts' );

/*Blog Page Shortcode*/
function bpn_blog( $options ){
	// Setup defaults
	$array_defaults = array( 
		'posts_per_page' => isset( $options['posts_per_page'] ) ? (int) $options['posts_per_page'] : 5,
	); 

	ob_start();

	// Get Blog content
	get_template_part( 'inc/blog-content', NULL, $array_defaults );

	$blog_html = ob_get_contents();
	ob_end_clean();

	return $blog_html;
}
add_shortcode( 'blog_section', 'bpn_blog' );

// Category Ajax Filter
function bpn_filter(){
	ob_start();
	// Get Blog content
	get_template_part( 'inc/blog-filter' );
	$blog_html = ob_get_contents();
	ob_end_clean();
	$return = [
		'html' => $blog_html,
        'message' => 'success'
    ];
    return wp_send_json( $return );
	wp_die();
}
add_action( 'wp_ajax_bpn_filter', 'bpn_filter' );
add_action( 'wp_ajax_nopriv_bpn_filter', 'bpn_filter' );