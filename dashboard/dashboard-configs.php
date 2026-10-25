<?php
/**
 * Admin dashboard for Elementor Widgets.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'ELEMENTOR_WIDGETS_DASHBOARD_PAGE' ) ) {
	define( 'ELEMENTOR_WIDGETS_DASHBOARD_PAGE', 'elementor-widgets' );
}

if ( ! defined( 'ELEMENTOR_WIDGETS_SETTINGS_GROUP' ) ) {
	define( 'ELEMENTOR_WIDGETS_SETTINGS_GROUP', 'elementor_widgets_settings' );
}

require_once __DIR__ . '/dash-configs/widget-visibility.php';
require_once __DIR__ . '/dash-configs/config-visibility.php';

/**
 * Register the plugin dashboard page.
 */
function elementor_widgets_register_dashboard_menu(): void {
	add_menu_page(
		esc_html__( 'Elementor Widgets', 'custom-elementor-widgets' ),
		esc_html__( 'Elementor Widgets', 'custom-elementor-widgets' ),
		'manage_options',
		ELEMENTOR_WIDGETS_DASHBOARD_PAGE,
		'elementor_widgets_render_dashboard_page',
		'dashicons-screenoptions'
	);
}
add_action( 'admin_menu', 'elementor_widgets_register_dashboard_menu' );

/**
 * Load styles only on the plugin dashboard page.
 */
function elementor_widgets_enqueue_dashboard_assets( string $hook_suffix ): void {
	if ( 'toplevel_page_' . ELEMENTOR_WIDGETS_DASHBOARD_PAGE !== $hook_suffix ) {
		return;
	}

	$dependencies = [];
	$stylesheet   = __DIR__ . '/assets/css/dashboard.css';

	if ( wp_style_is( 'elementor-icons', 'registered' ) ) {
		wp_enqueue_style( 'elementor-icons' );
		$dependencies[] = 'elementor-icons';
	}

	wp_enqueue_style(
		'elementor-widgets-dashboard',
		plugins_url( 'assets/css/dashboard.css', __FILE__ ),
		$dependencies,
		file_exists( $stylesheet ) ? filemtime( $stylesheet ) : false
	);
}
add_action( 'admin_enqueue_scripts', 'elementor_widgets_enqueue_dashboard_assets', 20 );

/**
 * Render the plugin dashboard page.
 */
function elementor_widgets_render_dashboard_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap elementor-widgets-dashboard">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<p><?php esc_html_e( 'Manage the widgets and Elementor-wide features included with this plugin.', 'custom-elementor-widgets' ); ?></p>

		<?php settings_errors(); ?>

		<form action="options.php" method="post">
			<?php
			settings_fields( ELEMENTOR_WIDGETS_SETTINGS_GROUP );
			do_settings_sections( ELEMENTOR_WIDGETS_DASHBOARD_PAGE );
			submit_button( esc_html__( 'Save changes', 'custom-elementor-widgets' ) );
			?>
		</form>
	</div>
	<?php
}
