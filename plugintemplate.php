<?php
/*
 * @package plugintemplate
 * @version 1.0-alpha
 *
 * Plugin Name: plugintemplate
 * Description: 
 * Author: Jonathan Kissam
 * Text Domain: plugintemplate
 * Domain Path: /languages
 * Version: 1.0-alpha
 * Author URI: http://jonathankissam.com
 */

/**
 * Includes
 */
if (!defined('JONATHANKISSAM_BRANDING_VERSION')) {
	require_once( plugin_dir_path( __FILE__ ) . 'jk_wp_branding/jk_branding.inc.php' );
}


/**
 * Set up options
 */

/**
 * Installation, database setup
 */
global $plugintemplate_version;
$plugintemplate_version = '1.0-alpha';

/*
global $plugintemplate_db_version;
$plugintemplate_db_version = '1.0';
*/

function plugintemplate_install() {

	global $wpdb;
	global $plugintemplate_version;
	// global $plugintemplate_db_version;
	$installed_version = get_option( 'plugintemplate_version' );
	// $installed_db_version = get_option( 'plugintemplate_db_version' );

	$notices = get_option('plugintemplate_deferred_admin_notices', array());

	if ($installed_version != $plugintemplate_version) {

		// test for particular updates here

		// on first installation
		if (!$installed_version) {
			$install_notice = jk_branding( 'plugintemplate', 'https://github.com/jkissam/plugintemplate' , true, false);
			// jk_branding will not return the notice if plugin was deleted and then re-installed
			if ($install_notice) {
				$notices[] = $install_notice;
			}
		}

		update_option( 'plugintemplate_version', $plugintemplate_version );
	}

/*
	if ($installed_db_version != $plugintemplate_db_version) {

		$table_name = $wpdb->prefix . 'plugintemplate';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			title varchar (255) DEFAULT '' NOT NULL,
			text text NOT NULL,
			boolean tinyint(1) DEFAULT 0 NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		update_option( 'plugintemplate_db_version', $plugintemplate_db_version );

	}
*/

	update_option('plugintemplate_deferred_admin_notices', $notices);

}
register_activation_hook( __FILE__, 'plugintemplate_install' );

function plugintemplate_update_version_check() {
	global $plugintemplate_version;
	// global $plugintemplate_db_version;
	$installed_version = get_option( 'plugintemplate_version' );
	// $installed_db_version = get_option( 'plugintemplate_db_version' );
	if ( ($installed_version != $plugintemplate_version) /* || ($installed_db_version != $plugintemplate_db_version) */ ) {
		plugintemplate_install();
	}
}
add_action( 'plugins_loaded', 'plugintemplate_update_version_check' );

/**
 * Uninstall
 */
function plugintemplate_uninstall() {

	global $wpdb;

	// remove options
	$plugintemplate_options = array(
		'plugintemplate_version',
		'plugintemplate_db_version',
		'plugintemplate_deferred_admin_notices',
	);
	foreach ($plugintemplate_options as $option) {
		delete_option( $option );
	}

/*
	// remove database table
	$table_name = $wpdb->prefix . 'plugintemplate';
	$wpdb->query("DROP TABLE IF EXISTS $table_name");
*/


}
register_uninstall_hook( __FILE__, 'plugintemplate_uninstall' );

/**
 * Administrative notices
 */
function plugintemplate_admin_notices() {
	if ($notices = get_option( 'plugintemplate_deferred_admin_notices' ) ) {
		foreach ($notices as $notice) {
			echo "<div class=\"updated notice is-dismissible\"><p>$notice</p></div>";
		}
		delete_option( 'plugintemplate_deferred_admin_notices' );
	}
}
add_action( 'admin_notices', 'plugintemplate_admin_notices' );



/**
 * Set up admin menu structure
 * https://developer.wordpress.org/reference/functions/add_menu_page/
 */
function plugintemplate_admin_menu() {
	/*

	// Create
	$plugintemplate_admin_menu_hook = add_menu_page( __('Administer plugintemplate', 'plugintemplate'), 'Action Network', 'manage_options', 'plugintemplate', 'plugintemplate_admin_page', 'dashicons-admin-generic', 61);
	add_action( 'load-' . $plugintemplate_admin_menu_hook, 'plugintemplate_admin_add_help' );

	// customize the first sub-menu link
	$plugintemplate_admin_menu_hook = add_submenu_page( __('Administer plugintemplate'), __('Administer'), 'manage_options', 'plugintemplate-menu', 'plugintemplate_admin_page');
	add_action( 'load-' . $plugintemplate_admin_menu_hook, 'plugintemplate_admin_add_help' );

	*/
}
add_action( 'admin_menu', 'plugintemplate_admin_menu' );

/**
 * Handle administrative actions
 */
function plugintemplate_admin_handle_actions(){
	
	if ( !isset($_REQUEST['plugintemplate_admin_action']) || !check_admin_referer(
		'plugintemplate_'.$_REQUEST['plugintemplate_admin_action'], 'plugintemplate_nonce_field'
		) ) {
			return false;
	}
	
	switch ($_REQUEST['plugintemplate_admin_action']) {
	
/*
		case 'update_option':
		$plugintemplate_option = $_REQUEST['plugintemplate_option'];
		if (get_option('plugintemplate_option', null) !== $plugintemplate_option) {
			update_option('plugintemplate_api_key', $plugintemplate_option);
			$return['notices']['updated'][] = __('Option has been updated', 'plugintemplate');
		}
		break;
*/	
		
	}
	
	return $return;
}

/**
 * Administrative page
 */
function plugintemplate_admin_page() {

	// load scripts and stylesheets
	wp_enqueue_style('plugintemplate-admin-css', plugins_url('admin.css', __FILE__));
	wp_enqueue_script('plugintemplate-admin-js', plugins_url('admin.js', __FILE__));
	
	// This checks which tab we should display
	$tab = isset($_REQUEST['plugintemplate_tab']) ? $_REQUEST['plugintemplate_tab'] : 'actions';
	
	// This handles form submissions and prints any relevant notices from them
	$notices_html = '';
	if (isset($_REQUEST['plugintemplate_admin_action'])) {
		$action_returns = plugintemplate_admin_handle_actions();
		if (isset($action_returns['notices'])) {
			foreach ($action_returns['notices']['error'] as $notice) {
				$notices_html .= '<div class="error notice is-dismissible"><p>'.$notice.'</p></div>';
			}
			foreach ($action_returns['notices']['updated'] as $notice) {
				$notices_html .= '<div class="updated notice is-dismissible"><p>'.$notice.'</p></div>';
			}

		}
		if (isset($action_returns['tab'])) { $tab = $action_returns['tab']; }
	}

	?>
	
	<div class='wrap'>
		
		<h1>plugintemplate <a href="#plugintemplate-add" class="page-title-action"><?php _e('Add New', 'plugintemplate'); ?></a></h1>

		<?php if ($notices_html) { echo $notices_html; } ?>
		
		<div class="wrap-inner">
			
			<h2 class="nav-tab-wrapper">
				<a href="#plugintemplate-content" class="nav-tab<?php echo ($tab == 'content') ? ' nav-tab-active' : ''; ?>">
					<?php _e('Content', 'plugintemplate'); ?>
				</a>
				<a href="#plugintemplate-add" class="nav-tab<?php echo ($tab == 'add') ? ' nav-tab-active' : ''; ?>">
					<?php _e('Add New', 'plugintemplate'); ?>
				</a>
				<a href="#plugintemplate-settings" class="nav-tab<?php echo ($tab == 'settings') ? ' nav-tab-active' : ''; ?>">
					<?php _e('Settings', 'plugintemplate'); ?>
				</a>
			</h2>
			
			<?php /* list content */ ?>
			<div class="plugintemplate-admin-tab<?php echo ($tab == 'content') ? ' plugintemplate-admin-tab-active' : ''; ?>" id="plugintemplate-content">
				<h2><?php _e('Content', 'plugintemplate'); ?></h2>
			</div>
		
			<?php /* add something */ ?>
			<div class="plugintemplate-admin-tab<?php echo ($tab == 'add') ? ' plugintemplate-admin-tab-active' : ''; ?>" id="plugintemplate-add">
				<h2><?php _e('Add new', 'plugintemplate'); ?></h2>
			</div>
			
			<?php /* options settings */ ?>
			<div class="plugintemplate-admin-tab<?php echo ($tab == 'settings') ? ' plugintemplate-admin-tab-active' : ''; ?>" id="plugintemplate-settings">
				<h2><?php _e('Plugin Settings', 'plugintemplate'); ?></h2>
			</div>
		
		</div> <!-- /.wrap-inner -->

		<?php jk_branding( 'Action Network', 'https://github.com/jkissam/plugintemplate' ); ?>

	</div> <!-- /.wrap -->
	<?php
}

/**
 * Help for administrative page
 */
function plugintemplate_admin_add_help() {
	$screen = get_current_screen();
	
	$screen->add_help_tab( array(
		'id'       => 'plugintemplate-help-1',
		'title'    => __( 'Help Tab 1', 'plugintemplate' ),
		'content'  => __('
<p>Placeholder for documentation</p>
		', 'plugintemplate'),
	));
}


