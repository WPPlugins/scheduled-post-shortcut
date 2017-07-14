<?php
/**
 * The primary test suite for Scheduled Posts Shortcuts
 * As of 1.0.0, this is only a single file, but if the plugin grows beyond a
 * single class then there will be multiple tests to add to the suite and the
 * bootstrap file.
 *
 * @package Scheduled_Post_Shortcut/tests
 * @since   1.1.0
 */

/**
 * Provides a suite of tests to verify that Scheduled Posts Shortcuts'
 * server-side functionality is running as expected.
 *
 * @package Scheduled_Post_Shortcut/tests
 * @since   1.0.0
 */
class Test_Class_Scheduled_Post_Shortcut extends WP_UnitTestCase {

	/**
	 * A reference to an instance of the plugin. This is set during
	 * each execution of the setUp() method.
	 *
	 * @access private
	 * @var    Scheduled_Post_Shortcut
	 */
	private $plugin;

	/**
	 * Prepares an instance of the plugin to be used for each individual test.
	 */
	public function setUp() {
		parent::setUp();

		$this->mock_submenu();

		// Create an administrator and navigate to the post screen.
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		$user = wp_set_current_user( $user_id );
		set_current_screen( 'edit' );

		// Initialize the plugin.
		$this->plugin = new Scheduled_Post_Shortcut();
		$this->plugin->init();

	}

	/**
	 * Mocks the global $submenu variable defined in WordPress so that we can
	 * test the 'Scheduled' submenu item creation.
	 *
	 * @access private
	 */
	private function mock_submenu() {

		// @codingStandardsIgnoreStart
		$GLOBALS['submenu'] = array();
		$GLOBALS['submenu']['edit.php'] = array();
		$GLOBALS['submenu']['edit.php'][2] = array();
		// @codingStandardsIgnoreStop

	}

	/**
	 * Verify that the plugin was property instantiated.
	 */
	public function test_instantiation() {
		$this->assertNotNull( $this->plugin );
	}

	/**
	 * Verifies that the 'Scheduled' post submenu item exists by testing for
	 * the presence of the proper hooks and the values returned to be set
	 * into the menu.
	 */
	public function test_has_submenu() {

		// These assertions work because of init() being called in setUp.
		$this->assertInternalType(
			'integer',
			has_action(
				'admin_menu',
				array( $this->plugin, 'add_submenu_page' )
			)
		);

		$this->assertInternalType(
			'integer',
			has_action(
				'parent_file',
				array( $this->plugin, 'highlight_scheduled_submenu' )
			)
		);

		// Manually trigger the method to insert the menu item into $submenu.
		global $submenu;
		$menu = $this->plugin->add_submenu_page();
		$menu = $menu[0];

		$this->assertSame(
			'Scheduled<span class="update-plugins count-0"><span class="update-count">0</span></span>',
			$menu[0]
		);

		$this->assertSame(
			'publish_posts',
			$menu[1]
		);

		$this->assertSame(
			'http://example.org/wp-admin/edit.php?post_status=future&post_type=post',
			$menu[2]
		);

	}
}
