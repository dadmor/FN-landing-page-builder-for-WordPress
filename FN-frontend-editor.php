<?php
/*
Plugin Name: FN frontend editor
Description: Another frontend editor (created by FutureNet.club)
Version:     0.0.1
Author:      dadmor@gmail.com

Copyright © 2017-2017 Grzegorz Durtan

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*/

function FN_frontend_editor_enqueue_script()
{   
 	/* init dot.js templates */
 	wp_enqueue_script( 'dotjs', plugin_dir_url( __FILE__ ) . 'js/dotjs.min.js' );
}
add_action('wp_enqueue_scripts', 'FN_frontend_editor_enqueue_script');


/* insert frontend editor scripts */
function editor_scripts() {
	//global $post;

	$dir_js = __DIR__.'/js/';
	$dir_css = __DIR__.'/css/';

	echo "<script>\n";
	
	
/*	echo "window.FN_editor_data = {";
	echo "'url':'". get_home_url()."',";
	echo "'post_id':".$post->ID.",";
	echo "'post_slug':'".$post->post_name."',";
	echo "'contentpart':null,";
	echo "'content':null,";
	echo "'area_index':null";
	echo "};\n";*/

	echo file_get_contents(str_replace(array("\r", "\n"), '', $dir_js.'mce-frontend-editor-events.js'));

	echo "\n</script>";

	echo "<style>\n";
	echo file_get_contents($dir_css.'editor-style.css');
	echo "\n</style>";
}


/* Show init screen at start (tinyMCE init) */
function bp_docs_add_idle_function_to_tinymce( $initArray ) {
	$initArray['setup'] = 'function(ed) {
			ed.onInit.add(
				function(ed) {
					window.editor_completed();
				}
			);
		}';
	return $initArray;
}

function add_tcustom_tinymce_plugin($plugin_array) {
	$plugin_array['icitspots'] = plugin_dir_url( __FILE__ ) . 'js/shordcode-renderer.js';
	return $plugin_array;
}

/* run scripts on frontend_edit */
if(!is_admin()){
	if($_GET['editor']=='1'){
		add_action( 'wp_footer', 'editor_scripts' );
		add_filter( 'tiny_mce_before_init', 'bp_docs_add_idle_function_to_tinymce' );
		include __DIR__.'/inc/shordcode_api.php';
		add_filter('mce_external_plugins', 'add_tcustom_tinymce_plugin');
	}
}

/* add save button */
add_action('media_buttons_context','add_tinymce_save_post_button');
function add_tinymce_save_post_button($context){
	return $context.='<button id="save_content_button" class="save-button">Save content</button>';
}

/* add wp_editor inside edit post link */
add_filter( 'edit_post_link', 'my_edit_post_link',10,3 );
function my_edit_post_link( $edit, $before, $after) {

	global $post;

	$content = '';
	$editor_id = 'FN_frontend_editor';
	$settings =   array(
		'quicktags' => false,
		'wpautop' => true,
		/*'teeny' => false,
    	'dfw' => false,
    	'tinymce' => false, */
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
			$edit .= 'Load editor. Please wait...';
		$edit .= '</div>';
	$edit .= '</div>';



    return $edit;
}





/* add tab to media uploader */
function wpse_76980_add_upload_tab( $tabs ) {
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
add_action( 'media_upload_tab_slug', 'wpse_76980_media_upload' );







