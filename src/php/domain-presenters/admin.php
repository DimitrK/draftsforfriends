<?php
namespace DFF\Domain_Presenters;

require_once( DFF_PHP_PATH . '/formatters/epoch-time-formatter.php');
require_once( DFF_PHP_PATH . '/drafts/shared-manager.php');

use DFF\Formatters\Epoch_Time_Formatter as Epoch_Formatter;
use DFF\Drafts\Shared_Manager;

class Admin {
	
	function __construct() {
		
		global $current_user;
		
		$doing_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;
		
		if( ( $doing_ajax || is_admin() ) && current_user_can('manage_options') ) {	
			
			$this->admin_page_init();
			
			$this->manager = new Shared_Manager();

		}
	}
	
	function admin_page_init() {
		add_action('admin_menu', array($this, 'add_admin_pages'));
		
		include_once( DFF_PHP_PATH . '/admin-ajax.php');
		
		
		$this->add_page_scripts();
		
	}
	
	function add_page_scripts() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'vendor-bundle-script', DFF_URL . '/js/dist/vendor.bundle.js', array(), DFF_PLUGIN_VERSION_NUM, TRUE );
		wp_enqueue_script( 'bundle-script', DFF_URL . '/js/dist/bundle.js', array('vendor-bundle-script'), DFF_PLUGIN_VERSION_NUM, TRUE );
		
		//Another way to pass the settings and translations to your app. I was curious to see how an async load would work though so that was an experiment.
//		$dff = array(
//			'translations' 	=> array(
//				'drafts_title' 		=> __('Drafts for Friends', 'draftsforfriends'),
//				'curently_shared' => __('Currently shared drafts', 'draftsforfriends'),
//				'id_column'	 			=> __('ID', 'draftsforfriends'),
//				'title_column' 		=> __('Title', 'draftsforfriends'),
//				'link_column' 		=> __('Link', 'draftsforfriends'),
//				'expires_column' 	=> __('Expires', 'draftsforfriends'),
//				'actions_column' 	=> __('Actions', 'draftsforfriends'),
//				'extend_button' 	=> __('Extend', 'draftsforfriends'),
//				'cancel_button' 	=> __('Cancel', 'draftsforfriends'),
//				'delete_button' 	=> __('Delete', 'draftsforfriends'),
//				'no_drafts' 			=> __('No shared drafts!', 'draftsforfriends'),
//				'shareit_button'	=> __('Share it', 'draftsforfriends'),
//				'for'							=> __('for', 'draftsforfriends'),
//			)
//		);
//		wp_localize_script( 'bundle-script', 'dff', $dff );

	}
	
	
	function add_admin_pages() {
		add_submenu_page("edit.php", __('Drafts for Friends', 'draftsforfriends'), __('Drafts for Friends', 'draftsforfriends'),
			'manage_options', 'draftsforfriends', array($this, 'output_client_app_root'));
	}
	

	function output_client_app_root(){
?>
	<div id="dff-client-root"></div>
<?php
	}
}
