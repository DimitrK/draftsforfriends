<?php
namespace DFF;


require_once( 'domain-presenters/admin.php' );
require_once( 'domain-presenters/reader.php' );

use DFF\Domain_Presenters\Admin as Admin_Presenter;
use DFF\Domain_Presenters\Reader as Reader_Presenter;

class Plugin	{

	function __construct(){		
    add_action('init', array(&$this, 'init'));
	}

	function init() {
		add_action( 'plugins_loaded', array( $this, 'check_for_upgrade') );
		
//		add_action( 'load_textdomain',  array( $this, 'log_load_translation_file') );
		
		$this->load_plugin_textdomain();
		
		$this->load_presentation_domain();		
	}
	
	function check_for_update() {
		$old_version = get_option(DFF_PLUGIN_VERSION_OPTION);

		if ( empty($old_version) ) {
			//vanilla install, no upgrade needed.
			add_option(DFF_PLUGIN_VERSION_OPTION, DFF_PLUGIN_VERSION_NUM);
		} 

		if ( $old_version < DFF_PLUGIN_VERSION_NUM ) {
			update_option(DFF_PLUGIN_VERSION_OPTION, DFF_PLUGIN_VERSION_NUM);
		}
	}
	
	public function load_plugin_textdomain() {
		//http://geertdedeckere.be/article/loading-wordpress-language-files-the-right-way
		$domain = 'draftsforfriends';
		$locale = apply_filters('plugin_locale', get_locale(), $domain);
		
		load_textdomain($domain, WP_LANG_DIR .'/'. DFF_ROOT_DIRNAME .'/'. $domain.'-'.$locale.'.mo');
		

		load_plugin_textdomain($domain, FALSE, DFF_ROOT_DIRNAME .'/languages');
	}
	
	function log_load_translation_file($domain, $translation_file){
    echo 'loading file "' . $translation_file . '" on domain "' . $domain . '"';
	}
	
	function load_presentation_domain() {
		if (is_admin()) {
			new Admin_Presenter();
		} else {
			new Reader_Presenter();
		}
	}

}
