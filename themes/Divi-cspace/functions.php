<?php

define('WP_DEBUG', true);

// add_filter( 'pods_shortcode', function( $tags )  {
// 	$tags[ 'shortcodes' ] = true;
// 	return $tags;
// });

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

};

function load_scripts() {
   wp_register_script('main-js', get_stylesheet_directory_uri() . '/main.min.js', array('jquery'),'1.1', true);
   wp_enqueue_script('main-js');
} 
   add_action( 'wp_enqueue_scripts', 'load_scripts' );  

/**
 *	This will hide the Divi "Project" post type.
 */
add_filter( 'et_project_posttype_args', 'mytheme_et_project_posttype_args', 10, 1 );
function mytheme_et_project_posttype_args( $args ) {
	return array_merge( $args, array(
		'public'              => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => false,
		'show_in_nav_menus'   => false,
		'show_ui'             => false
	));
};


//for use within Divi
function autoLayoutShortcode($atts) {

	if($atts){
		$filterPageID = $atts['page'];// this should always be posted so ACF knows what page fields to pull in
		$filterCatID = $atts['cat'];
	}

	ob_start();
	//get_template_part('my_form_template');
	include( locate_template( $templatePath . 'templates/dynamic-post-layout-shortcode.php', false,false ));
	return ob_get_clean();

};
add_shortcode( 'auto-layout', 'autoLayoutShortcode' );


//filter auto-layout shortcode
function filter_posts(){
	echo '<h3>FILTERED BY CATEGORY: "' . get_cat_name( $_POST['categoryfilter']) . '"</h3>';

	echo do_shortcode( '[auto-layout page="' . $_POST['pageID'] . '" cat="' . $_POST['categoryfilter'] . '"]' );
	die(); 
}

add_action('wp_ajax_filterPosts', 'filter_posts'); 
add_action('wp_ajax_nopriv_filterPosts', 'filter_posts');


//hubspot form
function hubSpotShortcode($atts){

	$portal_id 	= $atts['portal_id'];
	$form_id 	= $atts['form_id'];
	ob_start();
	?>

	<!--[if lte IE 8]>
	<script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2-legacy.js"></script>
	<![endif]-->
	<script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2.js"></script>
		<script>
		hbspt.forms.create({
			portalId: "<?php echo $portal_id; ?>",
			formId: "<?php echo $form_id; ?>"
		});
	</script>

	<?php
	return ob_get_clean();
};
add_shortcode( 'hubspot-form', 'hubSpotShortcode' );

function geenhouseJobs($atts){

	if($atts['for']){

		$forTerm = $atts['for'];

		ob_start();
		?>
	
		<div id="grnhse_app"></div>
		<script src="//boards.greenhouse.io/embed/job_board/js?for=<?php echo $forTerm;?>"></script>
	
		<?php
		return ob_get_clean();

	}

}
add_shortcode( 'greenhouse-jobs', 'geenhouseJobs' );

