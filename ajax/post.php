<?php 
/* TODO https://wordpress.stackexchange.com/questions/60776/is-there-an-easy-way-to-ajax-ify-saving-of-post */
/* get ajax data */
$data = json_decode(base64_decode ( key($_POST)), true);
include '../../../../wp-load.php';

if( current_user_can('edit_others_pages') ) { 
	
	$post = get_post($data['post_id']);
	if($post->post_name == $data['post_slug']){

		if(($post->post_type=='post')||($post->post_type=='page')){
			$data['content'] = base64_decode ($data['content']); 
			$data['contentpart'] = base64_decode ($data['contentpart']); 

			// Update post 37
			$my_post = array(
				'ID' => $post->ID,
				//'post_title'   => $post->ID,
				'post_content' => $data['content'],
			);
			wp_update_post( $my_post );


			$data['content'] = apply_filters('the_content', $data['content']);
			$data['contentpart'] = apply_filters('the_content', $data['contentpart']);
			
			
			echo json_encode($data);
		}

	}

} 



