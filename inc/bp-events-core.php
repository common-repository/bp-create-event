<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class PP_Events_Component extends BP_Component {

	function __construct() {
		global $bp;
		parent::start('events',	__('Events', 'bp-create-events'), BP_EVENTS_DIR);
		$this->includes();
		$bp->active_components[$this->id] = '1';
	}

	function includes( $includes = array() ) {

		if( ! is_admin() ) {

			$includes = array(
				'inc/bp-events-functions.php',
				'inc/bp-events-templates.php',
				'inc/bp-events-screens.php',
				'inc/bp-events-widget.php'
			);

		}
		else {

			$includes = array(
		        'inc/admin/bp-events-admin.php',
				'inc/admin/bp-events-admin-settings.php',
				'inc/bp-events-functions.php',
				'inc/bp-events-widget.php'
			);

		}

		parent::includes( $includes );

	}

	function setup_globals( $args = array() ) {

		$bp = buddypress();

		if ( !defined( 'PP_EVENTS_SLUG' ) )
			define( 'PP_EVENTS_SLUG', $this->id );

		$globals = array(
			'slug'                  => PP_EVENTS_SLUG,
			'root_slug'             => isset( $bp->pages->{$this->id}->slug ) ? $bp->pages->{$this->id}->slug : PP_EVENTS_SLUG,
			'has_directory'         => true,
			'directory_title'       => __( 'Events', 'bp-create-events' ),
			'search_string'         => sprintf(__( 'Search %s...', 'bp_create_events' ),__('Events','bp_create_events')),
		);

		parent::setup_globals( $globals );

	}

	function setup_nav( $main_nav = array(), $sub_nav = array() ) {
		
		if ( bp_displayed_user_domain() ) {
			$user_domain = bp_displayed_user_domain();
		} elseif ( bp_loggedin_user_domain() ) {
			$user_domain = bp_loggedin_user_domain();
		} else {
			return;
		}
		
		$user_has_access = false;
		if( bp_is_my_profile() || is_super_admin() )
			$user_has_access = true;

		$tab_position = get_option( 'pp_events_tab_position' );
		$count        = bp_events_count_profile();
		$class        = ( 0 === $count ) ? 'no-count' : 'count';


		bp_core_new_nav_item( array(
			'name'                => sprintf( __( 'Events <span class="%s">%s</span>', 'bp-create-events' ), esc_attr( $class ), number_format_i18n( $count ) ),
			'slug'                => 'events',
			'position'            => $tab_position,
			'screen_function'     => 'bp_events_profile',
			'default_subnav_slug' => 'upcoming',
			'item_css_id'         => 'member-events'
		) );

		bp_core_new_subnav_item( array(
			'name'              => 'Upcoming',
			'slug'              => 'upcoming',
			'parent_url'        => trailingslashit( $user_domain . 'events' ),
			'parent_slug'       => 'events',
			'screen_function'   => 'bp_events_profile',
			'position'          => 20,
			'item_css_id'       => 'member-events-upcoming'
			//'user_has_access'   => $user_has_access
			)
		);


		bp_core_new_subnav_item( array(
			'name'              => 'Archive',
			'slug'              => 'archive',
			'parent_url'        => trailingslashit( $user_domain . 'events' ),
			'parent_slug'       => 'events',
			'screen_function'   => 'bp_events_profile_archive',
			'position'          => 25,
			'item_css_id'       => 'member-events-archive'
			//'user_has_access'   => $user_has_access
			)
		);

		if ( current_user_can('publish_events') ) {
		
			bp_core_new_subnav_item( array(
				'name'              => 'Create Event',
				'slug'              => 'create',
				'parent_url'        => trailingslashit( $user_domain . 'events' ),
				'parent_slug'       => 'events',
				'screen_function'   => 'bp_events_profile_create',
				'position'          => 30,
				'item_css_id'       => 'member-events-create',
				'user_has_access'   => $user_has_access
				)
			);
			
		}
		
		parent::setup_nav( $main_nav, $sub_nav );

	}

	function setup_admin_bar( $wp_admin_nav = array() ) {
		$bp = buddypress();

		if ( ! current_user_can('publish_events') )
			return;

		if ( is_user_logged_in() ) {
			$user_domain = bp_loggedin_user_domain();
			$item_link = trailingslashit( $user_domain . 'events' );

			$wp_admin_nav[] = array(
				'parent' => $bp->my_account_menu_id,
				'id'     => 'my-account-events',
				'title'  => __( 'Events',  'bp-create-events' ),
				'href'   => trailingslashit( $item_link ),
				'meta'   => array( 'class' => 'menupop' )
			);

			// submenu
			$wp_admin_nav[] = array(
				'parent' => 'my-account-events',
				'id'     => 'my-account-events-upcoming',
				'title'  => __( 'Upcoming', 'bp-create-events' ),
				'href'   => trailingslashit( $item_link ) . 'upcoming'
			);

			// submenu
			$wp_admin_nav[] = array(
				'parent' => 'my-account-events',
				'id'     => 'my-account-events-archive',
				'title'  => __( 'Archive', 'bp-create-events' ),
				'href'   => trailingslashit( $item_link ) . 'archive'
			);

			// submenu
			$wp_admin_nav[] = array(
				'parent' => 'my-account-events',
				'id'     => 'my-account-events-create',
				'title'  => __( 'Create', 'bp-create-events' ),
				'href'   => trailingslashit( $item_link ) . 'create'
			);

		}

		parent::setup_admin_bar( $wp_admin_nav );
	}

}
function bp_events_load_core_component() {
	global $bp;
	$bp->events = new PP_Events_Component();
}
add_action( 'bp_loaded', 'bp_events_load_core_component' );