<?php
/**
 * The core class for loading adding the 'Scheduled' submenu item to the 'Posts'
 * menu.
 *
 * @package Scheduled_Post_Shortcut
 * @since   1.0.0
 */

/**
 * Adds the 'Scheduled' submenu item to the 'Posts' menu. This will only works
 * for the roles who have the ability to create, schedule, and edit posts.
 *
 * Specifically, this will only work ford
 *
 * @package Scheduled_Post_Shortcut
 * @since   1.0.0
 */
class Scheduled_Post_Shortcut {

	/**
	 * The current version of the plugin.
	 *
	 * @access private
	 * @var    string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Maintains a reference to the query that retrieves the scheduled posts.
	 *
	 * @access private
	 * @var    WP_Query    $scheduled_posts_query    A reference to the post query.
	 */
	private $scheduled_posts_query;

	/**
	 * Initializes the properties of the class.
	 */
	public function __construct() {

		$this->version = '1.4.0';
		$this->scheduled_posts_query = null;
	}

	/**
	 * Hooks the submenu page functionality into the admin_menu hook of WordPress
	 * so we can customize it for Scheduled posts.
	 */
	public function init() {

		$plugin_path = plugin_basename( dirname( dirname( dirname( __FILE__ ) ) ) );
		$plugin_path .= '/languages';

		load_plugin_textdomain(
			'scheduled-posts-shortcuts',
			false,
			$plugin_path
		);

		add_action(
			'admin_menu',
			array( $this, 'add_submenu_page' )
		);

		add_action(
			'admin_enqueue_scripts',
			array( $this, 'admin_enqueue_styles' )
		);

		add_filter(
			'parent_file',
			array( $this, 'highlight_scheduled_submenu' )
		);
	}

	/**
	 * Highlights the 'Scheduled' submenu item using WordPress native styles.
	 *
	 * @param string $parent_file The filename of the parent menu.
	 *
	 * @return string $parent_file The filename of the parent menu.
	 */
	public function highlight_scheduled_submenu( $parent_file ) {

		global $submenu_file;

		// Only update the submenu file value if we're on the Scheduled page.
		if ( 'future' === get_query_var( 'post_status' ) ) {

			// Support future posts for every post type.
			$args = array(
				'post_status' => 'future',
				'post_type'   => get_post_type(),
			);

			/**
			 * We have to use the full URL so get the admin URL and concatenate
			 * it with the parent file.
			 */
			$admin_url = trailingslashit( get_admin_url() );
			$url = sprintf( '%s%s', $admin_url, $parent_file );

			// @codingStandardsIgnoreStart
			$submenu_file = add_query_arg( $args, $url );
			// @codingStandardsIgnoreEnd
		}

		return $parent_file;
	}

	/**
	 * Adds a shortcuted to the 'Scheduled' posts page in the 'Posts' menu.
	 *
	 * @return string The submenu item. Primarily returned for unit testing.
	 */
	public function add_submenu_page() {

		$url = $this->create_menu_url();
		$scheduled_submenu_item = $this->create_submenu_item( $url );
		$this->rebuild_posts_menu( $scheduled_submenu_item );

		// Return this string that composes the menu to improve testability.
		return $scheduled_submenu_item;
	}

	/**
	 * Adds the CSS responsible for properly adjusting the location of the
	 * scheduled post icon on the submenu item.
	 */
	public function admin_enqueue_styles() {

		wp_enqueue_style(
			'scheduled-post-shortcut',
			plugin_dir_url( dirname( __FILE__ ) ) . 'css/admin.css',
			array(),
			$this->version,
			'all'
		);
	}

	/**
	 * Creates the key/value pair query string to be appended to the URL of the
	 * 'Scheduled' submenu item.
	 *
	 * @access private
	 *
	 * @return array The array of query string parameters for the 'Scheduled' URL.
	 */
	private function create_menu_url() {

		return add_query_arg(
			array(
				'post_status' => 'future',
				'post_type'   => 'post',
			),
			admin_url( 'edit.php' )
		);
	}

	/**
	 * Set the array that will be used drive the menu.
	 *
	 * This is based on code from WordPress core:
	 * https://github.com/WordPress/WordPress/blob/d23cd0aa5002e0749555ae355a04fa17e87db5e4/wp-admin/menu.php#L90
	 *
	 * @access private
	 *
	 * @param  string $url The URL to which the key/value pair will be appended.
	 * @return array      The array that will be added to the 'Posts' menu.
	 */
	private function create_submenu_item( $url ) {

		$menu_name  = __( 'Scheduled', 'scheduled-posts-shortcuts' );

		$post_count = $this->get_scheduled_posts_count();
		$menu_name .= '<span id="scheduled-post-count" class="update-plugins count-' . $post_count . '">';
			$menu_name .= '<span class="update-count">';
				$menu_name .= $post_count;
			$menu_name .= '</span>';
		$menu_name .= '</span>';

		return array(
			array(
				$menu_name,
				'publish_posts',
				$url,
			),
		);
	}

	/**
	 * Rebuilds the 'Posts' menu by placing the 'Scheduled' menu item as the
	 * third item in the list.
	 *
	 * @access private
	 *
	 * @param array $scheduled_submenu_item The array that will be added to the 'Posts' menu.
	 */
	private function rebuild_posts_menu( $scheduled_submenu_item ) {

		// Get access to the submenu used to drive the WordPress menu.
		global $submenu;

		// Separate the menu were we want to place the 'Scheduled' menu item.
		$first_submenu  = array_slice( $submenu['edit.php'], 0, 2, true );
		$second_submenu = array_slice( $submenu['edit.php'], 2, count( $submenu ) - 1 );

		// Now combine the menu items placing the 'Scheduled' menu item second.
		$submenu['edit.php'] = array_merge(
			$first_submenu,
			$scheduled_submenu_item,
			$second_submenu
		);

	}

	/**
	 * Retrieves the number of scheduled posts.
	 *
	 * @access private
	 *
	 * @return string A string representation of the number of scheduled posts.
	 */
	private function get_scheduled_posts_count() {

		// Create the scheduled post query if it's not been set up.
		if ( null === $this->scheduled_posts_query ) {
			$this->scheduled_posts_query = $this->get_scheduled_posts_query();
		}

		// Define the number of updated posts.
		$post_count = 0;
		if ( 0 !== $this->scheduled_posts_query->post_count ) {
			$post_count = $this->scheduled_posts_query->post_count;
		}

		return (string) $post_count;
	}

	/**
	 * Returns a reference to the query that retrieves the number of scheduled
	 * posts.
	 *
	 * @access private
	 *
	 * @return WP_Query A reference to the query for scheduled posts.
	 */
	private function get_scheduled_posts_query() {

		$args = array(
			'post_status' => 'future',
			// @codingStandardsIgnoreStart
			'posts_per_page' => -1,
			// @codingStandardsIgnoreEnd
		);
		$scheduled_posts_query = new WP_Query( $args );

		return $scheduled_posts_query;
	}
}
