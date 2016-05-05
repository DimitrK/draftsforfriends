<?php
namespace DFF\Domain_Presenters;

require_once( DFF_PHP_PATH . '/drafts/shared-manager.php');

use DFF\Drafts\Shared_Manager;

class Reader {
	
	private $shared_post = NULL;
	
	function __construct() {
		
		$this->manager = new Shared_Manager();
		
		add_filter('the_posts', array($this, 'the_posts_intercept'));
		add_filter('posts_results', array($this, 'posts_results_intercept'));
	}
	
	private function can_view($pid) {
		$now = time();
		
		$shared_draft = $this->manager->validate_shared_draft($_GET['draftsforfriends']);
		
		if ( is_wp_error($shared_draft) ) {
			
			return FALSE;
		} else {
			
			return $shared_draft['expires'] >= $now;
		}
	}

	function posts_results_intercept($pp) {
		if (1 != count($pp)) return $pp;
		$p = $pp[0];
		$status = get_post_status($p);
		if ('publish' != $status && $this->can_view($p->ID)) {
			$this->shared_post = $p;
		}
		return $pp;
	}

	function the_posts_intercept($pp){
		if (empty($pp) && !is_null($this->shared_post)) {
			return array($this->shared_post);
		} else {
			$this->shared_post = null;
			return $pp;
		}
	}
}
