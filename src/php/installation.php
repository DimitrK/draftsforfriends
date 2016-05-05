<?php
namespace DFF;


class Installation	{
	
	public static $MIN_WP_VERSION = '4.5';
	public static $MIN_PHP_VERSION = '7.0.5';
	
	private $current_wp_version = '';
	private $current_php_version = '';

	function __construct($wp_version, $php_version) {
		
    	if ( !isset($wp_version) || !isset($php_version) ) {
				throw new \InvalidArgumentException('Current WP and/or PHP version did not passed as argument on installation instance');
			}
	}
	
	/**
	 * Simply because I did not test this plugin on other platforms I will assume that the minimum
	 * platform requirements are the one I am currently running it on. In a real case scenario I would
	 * have to check which invoked functions could not be supported and adjust either them or the 
	 * version accordingly.
	 * 
	 * @author Dimitrk
	 */
	public function meets_requirements() {
		
		$meets_minimum_wp_version = self::$MIN_WP_VERSION > $this->current_wp_version;
		$meets_minimum_php_version = version_compare( self::$MIN_PHP_VERSION, $this->current_php_version, '>' );
		
		return $meets_minimum_wp_version && $meets_minimum_php_version;
	}
	
}
