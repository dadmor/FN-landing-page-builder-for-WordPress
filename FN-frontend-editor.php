<?php
/*
Plugin Name: FN frontend editor
Description: Template system based on native tiny edytor and shortcodes 
Version:     0.0.1
Author:      dadmor@gmail.com

Copyright © 2017-2017 FutureNet.club

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*/

/* init dot.js templates */
function FN_frontend_editor_enqueue_script()
{   
 	wp_enqueue_script( 'dotjs', plugin_dir_url( __FILE__ ) . 'js/dotjs.min.js' );
}
add_action('wp_enqueue_scripts', 'FN_frontend_editor_enqueue_script');


/* insert frontend editor scripts */
function editor_scripts() {
	$dir_js = __DIR__.'/js/';
	$dir_css = __DIR__.'/css/';
	echo "<script>\n";
	echo file_get_contents(str_replace(array("\r", "\n"), '', $dir_js.'mce-frontend-editor-events.js'));
	echo "\n</script>";
	echo "<style>\n";
	echo "/* active editor style */";
	echo file_get_contents($dir_css.'editor-style.css');
	echo "\n</style>";
}


/* Show init screen at start (tinyMCE init) */
function bp_docs_add_idle_function_to_tinymce( $initArray ) {
	$code = 'function(ed) {
			ed.onInit.add(
				function(ed) {
					window.editor_completed();
				}
			);
      		ed.onClick.add(
      			function(ed, e) {
      				tiny_element_click(e);
      			}
      		);

		}';
	$initArray['setup'] = str_replace(array("\n","\r"),'',$code);
	$code = str_replace(array("\n","\r"),'',file_get_contents(__DIR__.'/css/'.'mce-editor-style.css'));
	$initArray['content_style'] = $code;
	return $initArray;
}


/* replace inline shortcode to graphics representation into editor */
function add_tcustom_tinymce_plugin($plugin_array) {
	$plugin_array['icitspots'] = plugin_dir_url( __FILE__ ) . 'js/shordcode-renderer.js';
	return $plugin_array;
}


/* add wp_editor inside edit post link */
function my_edit_post_link( $edit, $before, $after) {
	global $post;
	echo "<script>";
	echo "window.FN_editor_data = {";
	echo "'url':'". get_home_url()."',";
	echo "'post_id':".$post->ID.",";
	echo "'post_slug':'".$post->post_name."',";
	echo "'contentpart':null,";
	echo "'content':null,";
	echo "'area_index':null";
	echo "};\n";
	echo "</script>";
	$content = '';
	$editor_id = 'FN_frontend_editor';
	$settings =  array(
		'quicktags' => false,
		'wpautop' => true,
	);
	$edit .= '<div id="FN-editor-wrapper" class="hide">';
		ob_start();	
		wp_editor( $content, $editor_id, $settings ); 
		$edit .= ob_get_clean();
	$edit .= '</div>';
	$edit .= '<div id="FN_frontend_editor"></div>';
	$edit .= '<div id="FN_content_before_filtering">';
	$edit .= wpautop( $post->post_content );
	$edit .= '</div> ';
	$edit .= '<div id="FN-editor-modal">';
		$edit .= '<div id="modal-body">';
			$edit .= __( 'Load editor. Please wait...', 'FN-frontend-editor' );
		$edit .= '</div>';
		$edit .= '<div id="FN-close-modal" onclick="close_modal()">x</div>';
	$edit .= '</div>';
    return $edit;
}


/* run scripts on frontend_edit */
if(!is_admin()){
	if(@$_GET['editor']=='1'){
		add_action( 'wp_footer', 'editor_scripts' );
		add_filter( 'tiny_mce_before_init', 'bp_docs_add_idle_function_to_tinymce' );
		include __DIR__.'/inc/shordcode_api.php';
		add_filter(	'mce_external_plugins', 'add_tcustom_tinymce_plugin');
		add_filter( 'edit_post_link', 'my_edit_post_link',10,3 );
	}
}


/* add save button with tinny */
if(!is_admin()){
	add_action('media_buttons_context','add_tinymce_save_post_button');
}
function add_tinymce_save_post_button($context){
	return $context.='<button id="save_content_button" class="save-button">Save content</button>';
}


/* add top bar link to edit */
if(!is_admin()){
	add_action( 'admin_bar_menu', function( \WP_Admin_Bar $bar )
	{
		$bar->add_menu( array(
	        'id'     => 'FN-editor-start',
	        'parent' => null,
	        'group'  => null,
	        'title'  => '<span class="dashicons-before dashicons-edit" style="display: inline-block; vertical-align: 15px; color: #9ca1a6; margin-right: 10px;"></span>'.__( 'FN edit', 'FN-frontend-editor' ).'',
	        'href'   => get_permalink().'?editor=1',
	        'meta'   => array(
	            'target'   => '_self',
	            'title'    => __( 'FN edit', 'FN-frontend-editor' ),
	            //'class'    => 'edit',
	            //'rel'      => 'friend',
	            'tabindex' => PHP_INT_MAX,
	        ),
	    ) );
	} );
}

/* add tab to media uploader */
/*function wpse_76980_add_upload_tab( $tabs ) {
    $newtab = array( 'tab_slug' => 'Tab Name' );
    return array_merge( $tabs, $newtab );
}
add_filter( 'media_upload_tabs', 'wpse_76980_add_upload_tab' );

function wpse_76980_media_upload() {
   ?>
	Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum

	<div class="wrap">
      <div>
        <h2>Insert My Shortcode</h2>
        <div class="my_shortcode_add">
          <input type="text" id="id_of_textbox_user_typed_in"><button class="button-primary" id="id_of_button_clicked">Add Shortcode</button>
        </div>
      </div>
    </div>

    <button type="button" class="media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text">Zamknij media panel</span></span></button>

   <?php
}
add_action( 'media_upload_tab_slug', 'wpse_76980_media_upload' );*/







