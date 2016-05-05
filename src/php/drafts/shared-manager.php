<?php

namespace DFF\Drafts;

class Shared_Manager {
	
	private $admin_options ;
	private $user_options ;
	
	function __construct() {
		
		global $current_user;
			
		$this->admin_options = $this->get_admin_options();
		
		
		if( isset($this->admin_options[$current_user->ID]) && !empty($this->admin_options[$current_user->ID]) ) {
			$this->user_options = $this->admin_options[$current_user->ID];
		} else {
			$this->user_options = array( DFF_SHARED_OPTION => array() );
		}
		
		$this->save_admin_options();
	}
	
	
	public function create_shared_draft($draft_post_id, $expiration_value, $expiration_metric) {
		
		$password_length = 8;
		
		$include_special_chars = FALSE;
		
		$draft_post = $this->validate_draft_post($draft_post_id);
		
		if( is_wp_error($draft_post) ) {
			
			return $draft_post;
		}
		
		$new_shared_draft = array(
			'id' => $draft_post_id,
			'expires' => $this->calc_expiration_time(time(), $expiration_value, $expiration_metric),
			'key' => 'baba_' . wp_generate_password( $password_length, $include_special_chars ),
		);
			
		$this->user_options[DFF_SHARED_OPTION][] = $new_shared_draft;
		
		if( $this->save_admin_options() ) {
			
			return $new_shared_draft;		
		}
		
		return false;

	}
	
	
	public function extend_shared_draft($shared_key, $extend_value, $extend_metric) {
		
		global $current_user;
		
		$edited_shared_draft = $this->validate_shared_draft($shared_key, $current_user->ID);
		
		if( is_wp_error($edited_shared_draft) ) {
		
			return $edited_shared_draft;
		}
		
		$edited_shared_draft['expires'] = $this->calc_expiration_time( $edited_shared_draft['expires'], $extend_value, $extend_metric );
		
		$sharedArray = array();
		
		foreach( $this->user_options[DFF_SHARED_OPTION] as $share ) {
			
			if ( $share['key'] == $shared_key ) {
			
				$sharedArray[] = $edited_shared_draft;
			} else {
				
				$sharedArray[] = $share;
			}
			
		}
		
		$this->user_options[DFF_SHARED_OPTION] = $sharedArray;

		if( $this->save_admin_options() ) {
			
			return $edited_shared_draft;		
		}
		
		return false;
	}
	
	
	public function destroy_shared_draft($shared_key) {
		
		$shared = array();
		
		foreach( $this->user_options[DFF_SHARED_OPTION] as $share ) {
			
			if ( $share['key'] == $shared_key ) {
			
				continue;
			}
			
			$shared[] = $share;
		}
		
		$this->user_options[DFF_SHARED_OPTION] = $shared;
		
		if( $this->save_admin_options() ) {
			
			return $shared_key;		
		}
		
		return false;
	}
	
	
	public function validate_shared_draft($shared_key, $user_id) {
		
		if ( isset( $user_id ) && $user_id > 0 ) {
			
			$shared_posts = $this->admin_options[$user_id][DFF_SHARED_OPTION];			
		} else {
			
			$all_shared_per_user = array_map(
				function($key) {
			
					return $key[DFF_SHARED_OPTION];
				},
				array_values($this->admin_options)
			);
			
			$shared_posts = array_reduce( 
				array_filter( 
					array_values( $all_shared_per_user ) 
				),
				'array_merge', 
				array() 
			); // join all shared posts from all user options into an array
		}

		$shared_posts_for_key = array_filter(
			$shared_posts,
			function( $shared ) use ( $shared_key ) {
				
				return $shared['key'] == $shared_key;
			}
		);
		
		// There should be exactly one shared post in the array since the key is unique.
		if ( count( $shared_posts_for_key ) == 1 ) {
			$first_shared_post = array_values($shared_posts_for_key)[0];

			$darft_post = $this->validate_draft_post($first_shared_post['id']);

			if ( NULL != $user_id  && is_wp_error( $draft_post ) ) {
				
				return $draft_post;	
			} else {
				
				return $first_shared_post;
			}
		} else {
			
			return new \WP_Error(
				'invalid_shared_draft',
				__( 'The specific shared post is invalid' ),
				array( 'shared_key' => $shared_key )
			);
		}
	}
	
	
	public function validate_draft_post($draft_post_id) {
		
		$valid_post_statuses = array(
			'pending',
			'draft',
			'auto-draft',
			'future',
			'inherit',		
		);
		
		$draft = get_post($draft_post_id);
		
		if( empty($draft) ) {
			
			return new \WP_Error(
				'invalid_draft_not_found',
				__( 'Requested post does not exist' ),
				array( 'draft_id' => $draft_post_id )
			);
		}
		
		if( in_array( get_post_status($draft) , $valid_post_statuses ) == FALSE ) {
			
			return new \WP_Error(
				'invalid_draft_bad_status',
				__( 'The status of this post is invalid' ),
				array( 'draft_id' => $draft_post_id )
			);
		}
		
		return $draft;
	}
	
	
	private function calc_expiration_time($base_time, $expiration_value, $expiration_metric) {
		$metrics = array(
			'd' => 'days', 
			'h' => 'hours', 
			'm' => 'minutes', 
			's' => 'seconds',
		);
		
		$error = new \WP_Error();
		
		if ( empty( $base_time ) ) {
			$base_time = time(); //now
		}
		
		if ( intval( $base_time ) != $base_time ) {
			$error->add('invalid_arguments', __( 'The base time should be an integer number.' ) );
		}		
		
		if ( empty( $metrics[$expiration_metric] ) ) {
			$error->add('invalid_arguments', __( 'The expiration metric is not correctly set.' ) );
		}
		
		if ( intval( $expiration_value ) != $expiration_value ) {
			$error->add('invalid_arguments', __( 'Expiration value should be an integer number.' ) );
		}
		
		$error_messages = $error->get_error_messages('invalid_arguments');
		
		if ( empty( $error_messages ) ) {
			
			return strtotime( '+'. $expiration_value .' '. $metrics[$expiration_metric], $base_time );
		} else {
			
			throw new \InvalidArgumentException( implode(', ', $error_messages) );
		}		
	}
	
	
	public function get_user_drafts($user) {
		
		$user_id = is_object($user) ? $user->ID : $user;
		
		$my_drafts = get_users_drafts($user_id);
		
		$my_scheduled = $this->get_users_future($user_id);
		
		$pending = $this->get_others_pending($user_id);
		
		$user_drafts_by_type = array(
			__('Your Drafts:', 'draftsforfriends') => $my_drafts,
			__('Your Scheduled Posts:', 'draftsforfriends') => $my_scheduled,
			__('Pending Review:', 'draftsforfriends') => $pending,
		);
		
		return $user_drafts_by_type;
	}
	
	public function get_shared() {
		
		return $this->user_options[DFF_SHARED_OPTION];
	}
	
	private function get_admin_options() {
		
		$saved_options = get_option(DFF_SHARED_OPTION);
		return is_array( $saved_options ) ? $saved_options : array();
	}
	
	private function save_admin_options() {
		
		global $current_user;
		
		if ( $current_user->ID > 0 && current_user_can('manage_options') ) {
			$this->admin_options[$current_user->ID] = $this->user_options;
			return update_option(DFF_SHARED_OPTION, $this->admin_options);
		} else {
			return false;
		}
	}
	
	private function get_others_pending($exclude_user_id) {
		
		$others_pending_args = array(
			'posts_per_page'   => -1,
			'orderby'          => 'post_modified',
			'order'            => 'DESC',
			'post_type'        => 'post',
			'author__not_in'	 => array( $exclude_user_id ),
			'post_status'      => array( 'pending', 'draft' ),
			'suppress_filters' => true 
		);
		
		$others_pending_posts = get_posts( $others_pending_args );
		
		return $others_pending_posts;
	}
	
	private function get_users_future($user_id) {

		return get_posts( array(
			'posts_per_page'   => -1,
			'post_type'   => 'post',
			'post_status' => 'future',
			'post_author' => $user_id
		) );
	}
}