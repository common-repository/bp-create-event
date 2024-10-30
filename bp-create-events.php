<?php
/*
Plugin Name: BP Create Events
Description: An Events plugin for BuddyPress
Version: 1.0.1
Author: Vivacity InfoTech Pvt. Ltd.
Author URI: http://business.thevivapower.com/
*/

if ( !defined( 'ABSPATH' ) ) exit;


function bp_create_event_bp_check() {
	if ( !class_exists('BuddyPress') ) {
		add_action( 'admin_notices', 'bp_create_event_install_buddypress_notice' );
	}
}
add_action('plugins_loaded', 'bp_create_event_bp_check', 999);

function bp_create_event_install_buddypress_notice() {
	echo '<div id="message" class="error fade"><p style="line-height: 150%">';
	_e('<strong>BuddyPress Create Events</strong></a> requires the BuddyPress plugin. Please <a href="http://buddypress.org/download">install BuddyPress</a> first, or <a href="plugins.php">deactivate BuddyPress Creatge Events</a>.', 'bp-create-events');
	echo '</p></div>';
}

function bp_create_event_init() {

	$vcheck = bp_create_event_version_check();

	if( $vcheck ) {

		define( 'BP_EVENTS_DIR', dirname( __FILE__ ) );

		load_plugin_textdomain( 'bp-create-events', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		require( dirname( __FILE__ ) . '/inc/bp-events-core.php' );

	}

}
add_action( 'bp_include', 'bp_create_event_init' );

function bp_create_event_scripts_styles() {
wp_enqueue_script('thumbnail-slider', plugin_dir_url(__FILE__) . 'thumbnail-slider.js', array('jquery'));	
  
   wp_enqueue_style( 'thumbs2',  plugin_dir_url(__FILE__) . 'thumbs2.css' );
wp_enqueue_style( 'thumbnail-sliders',  plugin_dir_url(__FILE__) . 'thumbnail-slider.css' );	

	
    
   
}

add_action( 'wp_enqueue_scripts', 'bp_create_event_scripts_styles' );

function bp_create_event_activation() {

	$vcheck = bp_create_event_version_check();

	if( $vcheck ) {

		bp_create_event_add_event_caps();

		bp_create_event_post_type_event();

		bp_create_events_page();

		bp_create_events_options();

		flush_rewrite_rules();
	}
}
register_activation_hook(__FILE__, 'bp_create_event_activation');


function bp_create_event_deactivation () {
	bp_create_event_remove_event_caps();

}
register_deactivation_hook(__FILE__, 'bp_create_event_deactivation');


function bp_create_events_uninstall () {
	delete_option( 'pp_events_tab_position' );
	delete_option( 'pp_events_required' );
}
register_uninstall_hook( __FILE__, 'bp_create_events_uninstall');


function bp_events_version_check() {

	if ( ! defined( 'BP_VERSION' ) )
		return false;

	if( version_compare( BP_VERSION, '2.2', '>=' ) )
		return true;
	else {
		echo '<div id="message" class="error">BuddyPress Create Events requires at least version 2.2 of BuddyPress.</div>';
		return false;
	}
}


function bp_create_events_options() {

	// tab position on profile pages
	add_option( 'pp_events_tab_position', '201', '', 'no' );

	//default required fields
	add_option( 'pp_events_required', array(), '', 'no' );
}


function bp_create_events_page() {

    $page = get_page_by_path('events');

    if( ! $page ){
		$events_page = array(
		  'post_title'    => 'Events',
		  'post_name'     => 'events',
		  'post_status'   => 'publish',
		  'post_author'   => get_current_user_id(),
		  'post_type'     => 'page'
		);

		$post_id = wp_insert_post( $events_page, true );
    }

}

add_action('init', 'bp_create_event_portfolio_register');



 function bp_create_event_request() {
 //$data=array();
 $ids='';
    // The $_REQUEST contains all the data sent via ajax
    
     
        $id = $_REQUEST['id'];
        //echo $id;
        global $wpdb;
      
	if($wpdb->delete( 'wp_posts', array( 'ID' => $id ), array( '%d' ) ))
{
	//$data['result'] = 'success' ;
	echo $id;
	
	}       //  echo $fruit;


       // echo 'Deleted post';
         
       }
 
add_action( 'wp_ajax_request', 'bp_create_event_request' );
//add_action( 'wp_ajax_nopriv_example_ajax_request', 'example_ajax_request' );	



	

function bp_create_event_portfolio_register() {
 
	$labels = array(
		'name' => __('My Portfolio', 'post type general name'),
		'singular_name' => __('Portfolio Item', 'post type singular name'),
		'add_new' => __('Add New', 'portfolio item'),
		'add_new_item' => __('Add New Portfolio Item'),
		'edit_item' => __('Edit Portfolio Item'),
		'new_item' => __('New Portfolio Item'),
		'view_item' => __('View Portfolio Item'),
		'search_items' => __('Search Portfolio'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
 
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'menu_icon' => get_stylesheet_directory_uri() . '/article16.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','thumbnail')
	  ); 
 
	register_post_type( 'portfolio' , $args );
}


function bp_create_event_post_type_event() {

	if ( ! defined( 'BP_VERSION' ) )
		return;

	register_post_type( 'event',
		array(
		  'labels' => array(
			'name' => __( 'Events' ),
			'singular_name' => __( 'Event' ),
			'add_new' => __( 'Add New' ),
			'add_new_item' => __( 'Add New Event' ),
			'edit' => __( 'Edit' ),
			'edit_item' => __( 'Edit Event' ),
			'new_item' => __( 'New Event' ),
			'view' => __( 'View Events' ),
			'view_item' => __( 'View Event' ),
			'search_items' => __( 'Search Events' ),
			'not_found' => __( 'No Events found' ),
			'not_found_in_trash' => __( 'No Events found in Trash' ),
            'bp_activity_admin_filter' => __( 'Events', 'bp-create-events' ),
            'bp_activity_front_filter' => __( 'Events', 'bp-create-events' ),
            'bp_activity_new_post'     => __( '%1$s created a new <a href="%2$s">Event</a>', 'bp-create-events' ),
            'bp_activity_new_post_ms'  => __( '%1$s created a new <a href="%2$s">Event</a>, on the site %3$s', 'bp-create-events' ),

		),
		'public' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'event' ),
		'capability_type' => array('event', 'events'),
		'exclude_from_search' => false,
		'has_archive' => true,
		'map_meta_cap' => true,
		'hierarchical' => false,
		"supports"	=> array("title", "editor", "thumbnail", "author", "comments", "trackbacks", "buddypress-activity"),
        'bp_activity' => array(
            'component_id'          => buddypress()->activity->id,
            'action_id'             => 'new_event',
            'contexts'              => array( 'activity', 'member', 'groups', 'member-groups' ),
            'position'              => 70,
        ),
		'taxonomies' => array('category'),
		)
	);
	register_taxonomy_for_object_type('category', 'event');

}
add_action( 'init', 'bp_create_event_post_type_event' );


function bp_create_event_add_event_caps() {

	$role = get_role( 'administrator' );
	$role->add_cap( 'delete_published_events' );
	$role->add_cap( 'delete_others_events' );
	$role->add_cap( 'delete_events' );
	$role->add_cap( 'edit_others_events' );
	$role->add_cap( 'edit_published_events' );
	$role->add_cap( 'edit_events' );
	$role->add_cap( 'publish_events' );

}

function bp_create_event_remove_event_caps() {
	global $wp_roles;

	$all_roles = $wp_roles->roles;

	foreach( $all_roles as $key => $value ){

		$role = get_role( $key );
      $role->remove_cap( 'delete_published_events' );
		$role->remove_cap( 'delete_others_events' );
		$role->remove_cap( 'delete_events' );
		$role->remove_cap( 'edit_others_events' );
		$role->remove_cap( 'edit_published_events' );
		$role->remove_cap( 'edit_events' );
		$role->remove_cap( 'publish_events' );

	}
}


 
// Create the shortcode
add_shortcode( 'display-event', 'bp_create_event_display_posts_shortcode' );
function bp_create_event_display_posts_shortcode( $atts ) {

	// Original Attributes, for filters
	$original_atts = $atts;

	// Pull in shortcode attributes and set defaults
	$atts = shortcode_atts( array(
		'title'              => '',
		'author'              => '',
		'category'            => '',
		'category_display'    => '',
		'category_label'      => 'Posted in: ',
		'date_format'         => '(n/j/Y)',
		'date'                => '',
		'date_column'         => 'post_date',
		'date_compare'        => '=',
		'date_query_before'   => '',
		'date_query_after'    => '',
		'date_query_column'   => '',
		'date_query_compare'  => '',
		'display_posts_off'   => false,
		'exclude_current'     => false,
		'id'                  => false,
		'ignore_sticky_posts' => false,
		'image_size'          => false,
		'include_title'       => true,
		'include_author'      => false,
		'include_content'     => false,
		'include_date'        => false,
		'include_excerpt'     => false,
		'meta_key'            => '',
		'meta_value'          => '',
		'no_posts_message'    => '',
		'offset'              => 0,
		'order'               => 'DESC',
		'orderby'             => 'date',
		'post_parent'         => false,
		'post_status'         => 'publish',
		'post_type'           => 'event',
		'posts_per_page'      => '10',
		'tag'                 => '',
		'tax_operator'        => 'IN',
		'tax_term'            => false,
		'taxonomy'            => false,
		'time'                => '',
		'wrapper'             => 'ul',
		'wrapper_class'       => 'display-posts-listing',
		'wrapper_id'          => false,
	), $atts, 'display-posts' );
	
	// End early if shortcode should be turned off
	if( $atts['display_posts_off'] )
		return;

	$shortcode_title     = sanitize_text_field( $atts['title'] );
	$author              = sanitize_text_field( $atts['author'] );
	$category            = sanitize_text_field( $atts['category'] );
	$category_display    = 'true' == $atts['category_display'] ? 'category' : sanitize_text_field( $atts['category_display'] );
	$category_label      = sanitize_text_field( $atts['category_label'] );
	$date_format         = sanitize_text_field( $atts['date_format'] );
	$date                = sanitize_text_field( $atts['date'] );
	$date_column         = sanitize_text_field( $atts['date_column'] );
	$date_compare        = sanitize_text_field( $atts['date_compare'] );
	$date_query_before   = sanitize_text_field( $atts['date_query_before'] );
	$date_query_after    = sanitize_text_field( $atts['date_query_after'] );
	$date_query_column   = sanitize_text_field( $atts['date_query_column'] );
	$date_query_compare  = sanitize_text_field( $atts['date_query_compare'] );
	$exclude_current     = filter_var( $atts['exclude_current'], FILTER_VALIDATE_BOOLEAN );
	$id                  = $atts['id']; // Sanitized later as an array of integers
	$ignore_sticky_posts = filter_var( $atts['ignore_sticky_posts'], FILTER_VALIDATE_BOOLEAN );
	$image_size          = sanitize_key( $atts['image_size'] );
	$include_title       = filter_var( $atts['include_title'], FILTER_VALIDATE_BOOLEAN );
	$include_author      = filter_var( $atts['include_author'], FILTER_VALIDATE_BOOLEAN );
	$include_content     = filter_var( $atts['include_content'], FILTER_VALIDATE_BOOLEAN );
	$include_date        = filter_var( $atts['include_date'], FILTER_VALIDATE_BOOLEAN );
	$include_excerpt     = filter_var( $atts['include_excerpt'], FILTER_VALIDATE_BOOLEAN );
	$meta_key            = sanitize_text_field( $atts['meta_key'] );
	$meta_value          = sanitize_text_field( $atts['meta_value'] );
	$no_posts_message    = sanitize_text_field( $atts['no_posts_message'] );
	$offset              = intval( $atts['offset'] );
	$order               = sanitize_key( $atts['order'] );
	$orderby             = sanitize_key( $atts['orderby'] );
	$post_parent         = $atts['post_parent']; // Validated later, after check for 'current'
	$post_status         = $atts['post_status']; // Validated later as one of a few values
	$post_type           = sanitize_text_field( $atts['post_type'] );
	$posts_per_page      = intval( $atts['posts_per_page'] );
	$tag                 = sanitize_text_field( $atts['tag'] );
	$tax_operator        = $atts['tax_operator']; // Validated later as one of a few values
	$tax_term            = sanitize_text_field( $atts['tax_term'] );
	$taxonomy            = sanitize_key( $atts['taxonomy'] );
	$time                = sanitize_text_field( $atts['time'] );
	$wrapper             = sanitize_text_field( $atts['wrapper'] );
	$wrapper_class       = sanitize_html_class( $atts['wrapper_class'] );

	if( !empty( $wrapper_class ) )
		$wrapper_class = ' class="' . $wrapper_class . '"';
	$wrapper_id = sanitize_html_class( $atts['wrapper_id'] );
	if( !empty( $wrapper_id ) )
		$wrapper_id = ' id="' . $wrapper_id . '"';
		
	// Set up initial query for post
	$args = array(
		'category_name'       => $category,
		
		'post_type'           => explode( ',', $post_type ),
		'posts_per_page'      => $posts_per_page,
		
	);



		
	// If Post IDs
	if( $id ) {
		$posts_in = array_map( 'intval', explode( ',', $id ) );
		$args['post__in'] = $posts_in;
	}
	
	// If Exclude Current
	if( is_singular() && $exclude_current )
		$args['post__not_in'] = array( get_the_ID() );
	
	
	
	// Set up html elements used to wrap the posts. 
	// Default is ul/li, but can also be ol/li and div/div
	$wrapper_options = array( 'ul', 'ol', 'div' );
	if( ! in_array( $wrapper, $wrapper_options ) )
		$wrapper = 'ul';
	$inner_wrapper = 'div' == $wrapper ? 'div' : 'li';

	
	$listing = new WP_Query( apply_filters( 'display_posts_shortcode_args', $args, $original_atts ) );
	if ( ! $listing->have_posts() ) {
		
		
	?>
	<table id="current-events" cellspacing="10" cellpadding="10">
<thead>

</thead>
<tbody>
	<tr>

<td>

No Result Found 
</td>
</tr>
</table>
	<?php
		return apply_filters( 'display_posts_shortcode_no_results', wpautop( $no_posts_message ) );
	}
		
	$inner = '';?>
	
		<table id="current-events" cellspacing="10" cellpadding="10">
<thead>
<tr>
<th id="event-description" width="*">Event</th>
<th id="event-time" width="150">Date/Time</th>

</tr>
</thead>
<tbody>
<?php
while ( $listing->have_posts() ): $listing->the_post(); //global $post;
		//echo "swati";
		$image = $date = $author = $excerpt = $content = '';
global $post;
setup_postdata( $post );
$id= get_the_ID();
//$meta = get_post_meta($post );
 $meta = get_post_meta($post->ID );?>
<tr>

<td>
<div style="float:left; margin:0px 10px 0px 0px;">
	<?php 
				
				if( ! empty( $meta['event-myfile'][0] ) )
				{?>
					

				<img class="attachment-100x100 size-100x100 wp-post-image" width="100" height="100" alt="Pierce the Veil" src="<?php echo $meta['event-myfile'][0];?>">
			<?php }
			
			else{
				?>
				<img class="attachment-100x100 size-100x100 wp-post-image" width="100" height="100" alt="Pierce the Veil" src="<?php  echo plugin_dir_url(__FILE__); ?>/fff.png">
				
		<?php		}	?>

</div>
<a title="Pierce the Veil" href="<?php the_permalink() ?>"><?php the_title(); ?></a>
<br>
<i><?php the_excerpt(); ?></i>
</td>
<td>
<?php
if( ! empty( $meta['event-date'][0] ) )
				echo __( 'Date', 'bp-create-events' ) . ':&nbsp;' . $meta['event-date'][0];
?> 
<br>
<?php
	if( ! empty( $meta['event-time'][0] ) )
				echo '<br/>' . __( 'Time', 'bp-create-events' ) . ':&nbsp;' . $meta['event-time'][0];
?> 

<?php 
		
		if( $include_author )
			
			$author = apply_filters( 'display_posts_shortcode_author', ' <span class="author">by ' . get_the_author() . '</span>' );
		
		if ( $include_excerpt ) 
			$excerpt = ' <span class="excerpt-dash">-</span> <span class="excerpt">' . get_the_excerpt() . '</span>';
			
		if( $include_content ) {
			add_filter( 'shortcode_atts_display-posts', 'bp_create_event_display_posts_off', 10, 3 );
			/** This filter is documented in wp-includes/post-template.php */
			$content = '<div class="content">' . apply_filters( 'the_content', get_the_content() ) . '</div>';
			remove_filter( 'shortcode_atts_display-posts', 'bp_create_event_display_posts_off', 10, 3 );
		}
		
		// Display categories the post is in
		$category_display_text = '';
		if( $category_display && is_object_in_taxonomy( get_post_type(), $category_display ) ) {
			$terms = get_the_terms( get_the_ID(), $category_display );
			$term_output = array();
			foreach( $terms as $term )
				$term_output[] = '<a href="' . get_term_link( $term, $category_display ) . '">' . $term->name . '</a>';
			$category_display_text = ' <span class="category-display"><span class="category-display-label">' . $category_label . '</span> ' . implode( ', ', $term_output ) . '</span>';

			
			$category_display_text = apply_filters( 'display_posts_shortcode_category_display', $category_display_text );
		
		// If they pass a taxonomy that doesn't exist on this post type	
		}elseif( $category_display ) {
			$category_display = '';
		}
		
		$class = array( 'listing-item' );

		
		$class = array_map( 'sanitize_html_class', apply_filters( 'display_posts_shortcode_post_class', $class, $post, $listing, $original_atts ) );
		$output = '<' . $inner_wrapper . ' class="' . implode( ' ', $class ) . '">' . $image . $date . $author . $category_display_text . $excerpt . $content . '</' . $inner_wrapper . '>';
		
		// If post is set to private, only show to logged in users
		if( 'private' == get_post_status( get_the_ID() ) && !current_user_can( 'read_private_posts' ) )
			$output = '';

		
		$inner .= apply_filters( 'display_posts_shortcode_output', $output, $original_atts, $image,  $date, $excerpt, $inner_wrapper, $content, $class );
		
	endwhile; wp_reset_postdata();?>
</td>
</tr>
</table> 
<?php
	
	$open = apply_filters( 'display_posts_shortcode_wrapper_open', '<' . $wrapper . $wrapper_class . $wrapper_id . '>', $original_atts );

	
	$close = apply_filters( 'display_posts_shortcode_wrapper_close', '</' . $wrapper . '>', $original_atts );
	
	$return = $open;

	if( $shortcode_title ) {

		
		$title_tag = apply_filters( 'display_posts_shortcode_title_tag', 'h2', $original_atts );

		$return .= '<' . $title_tag . ' class="display-posts-title">' . $shortcode_title . '</' . $title_tag . '>' . "\n";
	}

	$return .= $inner . $close;

	return $return;
}



function bp_create_event_display_posts_off( $out, $pairs, $atts ) {

	$out['display_posts_off'] = apply_filters( 'display_posts_shortcode_inception_override', true );
	return $out;
}

