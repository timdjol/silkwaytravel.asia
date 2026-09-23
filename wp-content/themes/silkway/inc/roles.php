<?php
/**
 * Content manager role.
 *
 * @package silkway
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Capability map for the manager role (content only, no site settings).
 *
 * @return array<string, bool>
 */
function silkway_manager_capabilities() {
	$caps = array(
		'read'                   => true,
		'upload_files'           => true,

		// Posts / blog.
		'edit_posts'             => true,
		'edit_others_posts'      => true,
		'edit_published_posts'   => true,
		'edit_private_posts'     => true,
		'publish_posts'          => true,
		'read_private_posts'     => true,
		'delete_posts'           => true,
		'delete_others_posts'    => true,
		'delete_published_posts' => true,
		'delete_private_posts'   => true,

		// Pages.
		'edit_pages'             => true,
		'edit_others_pages'      => true,
		'edit_published_pages'   => true,
		'edit_private_pages'     => true,
		'publish_pages'          => true,
		'read_private_pages'     => true,
		'delete_pages'           => true,
		'delete_others_pages'    => true,
		'delete_published_pages' => true,
		'delete_private_pages'   => true,

		// Taxonomies / menus-lite.
		'manage_categories'      => true,
		'moderate_comments'      => true,

		// Unfiltered HTML for classic blocks / embeds (same as Editor).
		'unfiltered_html'        => true,
	);

	/**
	 * Allow menus without full Appearance access when possible.
	 * edit_theme_options also unlocks Widgets/Customizer — kept off by default.
	 */
	$caps['edit_theme_options'] = false;

	return $caps;
}

/**
 * Register or refresh the Manager role.
 */
function silkway_register_manager_role() {
	$version = '1.1';
	$role_key = 'silkway_manager';
	$caps     = silkway_manager_capabilities();

	if ( get_option( 'silkway_manager_role_version' ) === $version && get_role( $role_key ) ) {
		return;
	}

	$existing = get_role( $role_key );
	if ( $existing ) {
		foreach ( array_keys( $existing->capabilities ) as $cap ) {
			$existing->remove_cap( $cap );
		}
		foreach ( $caps as $cap => $grant ) {
			if ( $grant ) {
				$existing->add_cap( $cap );
			}
		}
	} else {
		add_role( $role_key, __( 'Manager', 'silkway' ), $caps );
	}

	update_option( 'silkway_manager_role_version', $version, false );
}
add_action( 'init', 'silkway_register_manager_role', 5 );
add_action( 'after_switch_theme', 'silkway_register_manager_role' );

/**
 * CPT screens use post capabilities by default — keep admin menu tidy for managers.
 */
add_action( 'admin_menu', function () {
	if ( ! silkway_user_is_manager() ) {
		return;
	}

	remove_menu_page( 'tools.php' );
	remove_menu_page( 'options-general.php' );
	remove_menu_page( 'themes.php' );
	remove_menu_page( 'plugins.php' );
	remove_menu_page( 'users.php' );
}, 999 );

/**
 * @param WP_User|int|null $user User object, ID, or current user.
 * @return bool
 */
function silkway_user_is_manager( $user = null ) {
	if ( null === $user ) {
		$user = wp_get_current_user();
	} elseif ( ! $user instanceof WP_User ) {
		$user = new WP_User( $user );
	}
	if ( ! $user || ! $user->exists() ) {
		return false;
	}
	return in_array( 'silkway_manager', (array) $user->roles, true );
}

/**
 * Soften admin bar for managers.
 */
add_action( 'wp_before_admin_bar_render', function () {
	if ( ! silkway_user_is_manager() ) {
		return;
	}
	global $wp_admin_bar;
	if ( ! $wp_admin_bar ) {
		return;
	}
	$wp_admin_bar->remove_node( 'themes' );
	$wp_admin_bar->remove_node( 'customize' );
	$wp_admin_bar->remove_node( 'updates' );
} );
