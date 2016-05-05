<?php

require_once( DFF_PHP_PATH . '/drafts/shared-manager.php');
require_once( DFF_PHP_PATH . '/formatters/epoch-time-formatter.php');

use DFF\Drafts\Shared_Manager;
use DFF\Formatters\Epoch_Time_Formatter;

class Ajax {
	
	private $shared_manager;
	
	function __construct() {
		$this->shared_manager = new Shared_Manager();
		
		$this->init();
	}
	
	public function init() {
	
		add_action( 'wp_ajax_LIST_SETTINGS', array( $this, 'list_settings_callback' ) );
		add_action( 'wp_ajax_LIST_SHARED', array( $this, 'list_shared_callback' ) );
		add_action( 'wp_ajax_LIST_DRAFTS', array( $this, 'list_drafts_callback' ) );
		add_action( 'wp_ajax_CREATE_SHARED', array( $this, 'create_shared_callback' ) );
		add_action( 'wp_ajax_EXTEND_SHARED', array( $this, 'extend_shared_callback' ) );
		add_action( 'wp_ajax_DESTROY_SHARED', array( $this, 'destroy_shared_callback' ) );
		add_action( 'wp_ajax_GET_SHARED', array( $this, 'get_shared_callback' ) );
	}
	
	public function get_shared_callback() {
		$shared_key = $_POST['shared'];
		$result = $this->shared_manager->validate_shared_draft($shared_key);
		if ( is_wp_error($result) ) {
			$this->respond_error($result);	
		} else {
			$this->respond_success(  $result  );	
		}
	}
	
	public function create_shared_callback() {
		$post_id = intval($_POST['post']);
		
		$expiration_value = intval($_POST['expires']);
		
		$expiration_metric = $_POST['metric'];
		
		$result = $this->shared_manager->create_shared_draft($post_id, $expiration_value, $expiration_metric);
		
		if ( is_wp_error($result) ) {
			$this->respond_error($result);	
		} else {
			$this->respond_success( $this->transform_shared( $result ) );	
		}
	}
	
	public function extend_shared_callback() {
		$shared_key = $_POST['shared'];
		
		$extend_value = intval($_POST['expires']);
		
		$extend_metric = $_POST['metric'];
	
		$result = $this->shared_manager->extend_shared_draft($shared_key, $extend_value, $extend_metric);
		
		if ( is_wp_error($result) ) {
			$this->respond_error($result);	
		} else {
			$this->respond_success( $this->transform_shared( $result ) );	
		}
	}
	
	public function destroy_shared_callback() {
		
		$shared_key = $_POST['shared'];
		
		$result = $this->shared_manager->destroy_shared_draft($shared_key);
		
		if ( is_wp_error($result) ) {
			$this->respond_error($result);	
		} else {
			$this->respond_success($result);	
		}
	}
	
	public function list_settings_callback() {
		//TODO: That is getting unmaintainable ppl!!!
		$settings = array(
			'translations' 	=> array(
					'drafts_title' 		=> __('Drafts for Friends', 'draftsforfriends'),
				 	'choose_draft'		=> __('Choose a draft', 'draftsforfriends'),
					'curently_shared' => __('Currently shared drafts', 'draftsforfriends'),
					'id_column'	 			=> __('ID', 'draftsforfriends'),
					'title_column' 		=> __('Title', 'draftsforfriends'),
					'link_column' 		=> __('Link', 'draftsforfriends'),
					'expires_column' 	=> __('Expires', 'draftsforfriends'),
					'actions_column' 	=> __('Actions', 'draftsforfriends'),
					'extend_button' 	=> __('Extend', 'draftsforfriends'),
					'cancel_button' 	=> __('Cancel', 'draftsforfriends'),
					'delete_button' 	=> __('Delete', 'draftsforfriends'),
					'no_drafts' 			=> __('No shared drafts!', 'draftsforfriends'),
					'shareit_button'	=> __('Share it', 'draftsforfriends'),
					'text_for'				=> __('for', 'draftsforfriends'),
					'text_by'					=> __('by', 'draftsforfriends'),
					'loading'					=> __('Loading...', 'draftsforfriends'),
					'seconds' 				=> __('seconds', 'draftsforfriends'),
					'minutes' 				=> __('minutes', 'draftsforfriends'),
					'hours'						=> __('hours', 'draftsforfriends'),
					'days' 						=> __('days', 'draftsforfriends'),
				)
		);

		$this->respond_success( $settings );
	}

	
	public function list_drafts_callback() {
		global $current_user;
		
		$grouped_drafts = $this->shared_manager->get_user_drafts($current_user);
		
		$this->respond_success( $grouped_drafts );
	}

	public function list_shared_callback() {
		
		$shared = $this->shared_manager->get_shared();
		
		$processed_shared = array();
		
		foreach( $shared as $share ) {
			
			$processed_shared[] = $this->transform_shared($share);
		}

		$this->respond_success( $processed_shared );
	}
	
	
	private function transform_shared( $shared ) {
		
		$epoch_formatter = new Epoch_Time_Formatter( $shared[ 'expires' ] );
			
		$post = get_post( $shared[ 'id' ] );
		
		return array(
				'post_id' 	 		 => $post->ID,
				'post_title' 		 => $post->post_title,
				'shared_key' 		 => $shared['key'],
				'shared_expires' => $epoch_formatter->to_human_readable(),
			);
	}
	
	private function respond_success( $response_data ) {
		$response = array(
			'status'   => 'ok',
			'data'		 =>	$response_data
		);

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { 
			wp_send_json($response) ;
		}
	}
	
	private function respond_error( $wp_error ) {
		$response = array(
			'status'   => 'error',
			'data'		 =>	implode( ', ', $wp_error->get_error_messages())
		);

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { 
			wp_send_json($response) ;
		}
	}
}

new Ajax();