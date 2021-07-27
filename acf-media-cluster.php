<?php

/*
Plugin Name: ACF Media Cluster
Description: An extension for Advance Custom Fields which provides the ability to add multiple media into a post.
Version: 1.0.0
Author: Navneil Naicker
Author URI: http://www.navz.me/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_media_cluster') ) :

class acf_media_cluster {
	
	// vars
	var $settings;
	
	
	/*
	*  __construct
	*
	*  This function will setup the class functionality
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	void
	*  @return	void
	*/
	
	function __construct() {
		
		add_action('admin_init', array($this, 'scripts'));
		add_filter('acf/media-cluster-edit-fields', array($this, 'edit_fields'), 10, 3);

		// settings
		// - these will be passed into the field class.
		$this->settings = array(
			'version'	=> '1.0.0',
			'url'		=> plugin_dir_url( __FILE__ ),
			'path'		=> plugin_dir_path( __FILE__ )
		);	
		
		// include field
		add_action('acf/include_field_types', 	array($this, 'include_field')); // v5
		add_action('acf/register_fields', 		array($this, 'include_field')); // v4
	}

	function scripts() {
		$settings = $this->settings;
		$version = $settings['version'];
		wp_register_style('css-acf-media-cluster', plugins_url('/assets/css/acf-media-cluster.css', __FILE__), null, $version, null);
		wp_enqueue_style('css-acf-media-cluster');
		wp_register_script( 'js-acf-media-cluster', plugins_url('/assets/js/acf-media-cluster.js', __FILE__ ), 'jquery', $version, true);
		wp_enqueue_script('js-acf-media-cluster');
	}
	
	/*
	*  include_field
	*
	*  This function will include the field type class
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	$version (int) major ACF version. Defaults to false
	*  @return	void
	*/
	
	function include_field( $version = false ) {
		
		// support empty $version
		if( !$version ) $version = 5;		
		
		// include
		include_once('fields/class-acf-media-cluster-v5.php');
	}

	function edit_fields( $post, $args ){
		return $args;
	}
	
}

// initialize
new acf_media_cluster();

// class_exists check
endif;

function acf_mc_save_fields( $post_id ) {
	$fields = (!empty($_POST['acf-mc-fields']))?$_POST['acf-mc-fields']:null;
	if( !empty($fields) and count($fields) ){
		update_post_meta( $post_id, '_' . sanitize_text_field($_POST['acf-mc-field-name']), sanitize_text_field($_POST['acf-mc-field-key']));
		foreach($fields as $field_name => $ids){
			if( count($ids) === 1 and $ids[0] < 1){
				delete_post_meta( $post_id, $field_name );
			} else if( count($ids) > 1 ){
				$idx = array();
				foreach($ids as $id){
					if( $id > 0 ){
						$idx[] = preg_replace('/\D/', '', $id);
					}
				}
				update_post_meta( $post_id, $field_name, implode(',', $idx));
			}
		}	
	}
	add_action( 'save_post', 'acf_mc_save_fields' );
}
add_action( 'save_post', 'acf_mc_save_fields' );

function acf_mc_cluster_field_group($noajax = false, $attachment_id = 0, $fname = "", $pkey = "", $url = "", $title = "", $showEditDel = false, $showAdd = true, $groupIndex = 0){
	include( dirname(__FILE__) . '/includes/acf_mc_cluster_field_group.php' );
}
add_action('wp_ajax_acf_mc_cluster_field_group', 'acf_mc_cluster_field_group');

function acf_mc_cluster_edit_fields(){
	include( dirname(__FILE__) . '/includes/acf_mc_cluster_edit_fields.php' );
	die();
}
add_action('wp_ajax_acf_mc_cluster_edit_fields', 'acf_mc_cluster_edit_fields');

function acf_mc_cluster_edit_save_field(){
	if( $_POST['action'] == 'acf_mc_cluster_edit_save_field' ){
		global $wpdb;
		$t = array();
		$tables = json_decode(stripslashes($_POST['tables']), true);
		$post_id = preg_replace('/\D/', '', $_POST['post_id']);
		$acf_mc_key = preg_replace('/[^a-z0-9_]/', '', $_POST['acf-mc-field-key']);
		foreach($tables as $a => $b){
			if( !empty($t[$b]) ){
				array_push($t[sanitize_text_field($b)], sanitize_text_field($a));
			} else {
				$t[sanitize_text_field($b)] = array($a);
			}
		}
		if( !empty($t) ){
			foreach($t as $a => $b){
				if( $a == "postmeta" ){
					foreach($b as $c){
						update_post_meta( $post_id, $c, sanitize_text_field($_POST[$c]));
					}
				}
				if( $a == "posts" ){
					$u = array();
					$u['ID'] = $post_id;
					foreach( $b as $c){
						if( in_array($c, array('post_content', 'post_title', 'post_excerpt')) ){
							if( in_array($c, array('post_content')) ){
								$u[$c] = sanitize_textarea_field($_POST[$c]);
							} else {
								$u[$c] = sanitize_text_field($_POST[$c]);
							}
						}
					}
					if( !empty($u) ){
						wp_update_post( $u );
					}
				}
			}
			update_option('acf_mc_key_' . $acf_mc_key, wp_json_encode($t) );
		}
	}
	die();
}
add_action('wp_ajax_acf_mc_cluster_edit_save_field', 'acf_mc_cluster_edit_save_field');

function acf_media_cluster($post_id, $field_name){
	global $wpdb;
	$field_key = get_field('_' . sanitize_text_field($field_name));
	$option = json_decode(get_option('acf_mc_key_' . $field_key));
	$meta_attachment_ids = array_filter(explode(',', get_field($field_name, preg_replace('/\D/', '', $post_id))));
	$posts = get_posts(array(
		'post__in' => sanitize_text_field($meta_attachment_ids),
		'post_type' => 'attachment',
		'orderby' => 'post__in',
		'order' => 'ASC',
	));
	$data = array();
	if( !empty($option) ){
		$post_tmp = array();
		$postmeta_tmp = array();
		foreach( $option as $a => $b ){
			if( $a == "posts" ){
				foreach( $posts as $a ){
					$post_tmp[$a->ID] = (object) array(
						'ID' => $a->ID,
						'post_media_url' => wp_get_attachment_url($a->ID),
						'post_content' => $a->post_content, 
						'post_title' => $a->post_title,
						'post_excerpt' => $a->post_excerpt,
						'post_mime_type' => $a->post_mime_type,
						'post_date' => $a->post_date
					);
				}
			} else if( $a == "postmeta" ){
				foreach($meta_attachment_ids as $d){
					foreach($b as $f => $g){
						$postmeta_tmp[sanitize_text_field($d)] = array($g => get_field(sanitize_text_field($g), sanitize_text_field($d)));
					}
				}
			}
		}
		$i = 0;
		foreach($post_tmp as $a => $b){
			array_push($data, $b);
			foreach($postmeta_tmp[$a] as $c => $d){
				$data[$i]->$c = sanitize_text_field($d);
			}
			$i++;
		}
		return $data;
	}
}