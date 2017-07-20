<?php 
/*Add this code to your functions.php file of current theme OR plugin file if you're making a plugin*/
//add the button to the tinymce editor
add_action('media_buttons_context','add_my_tinymce_media_button');
function add_my_tinymce_media_button($context){

	return $context.='<a href="#FN-editor-modal" onclick="dot_content(data.shortcodes,template.shortcodes,target_tpl.popup)" class="button"><span class="dashicons dashicons-screenoptions"></span>Add component</a>';
}

//javascript code needed to make shortcode appear in TinyMCE edtor
add_action('wp_footer','my_shortcode_add_shortcode_to_editor');
add_action('admin_footer','my_shortcode_add_shortcode_to_editor');
function my_shortcode_add_shortcode_to_editor(){?>

<script>

	var target_tpl = {
		"popup" : document.getElementById('modal-body')
	}

	var data = {
		'shortcodes':[
			{
			"shordcode":"autoresponder",
			"name":"Autoresponder",
			"ico":"megaphone",
			"options":{
					"data":"data.contact_form",
					"template":"template.contact_form",
					"target":"target_tpl.popup"
				}
			},
			{
			"shordcode":"contact-form",	
			"name":"Contact form",
			"ico":"email-alt",
			"options":{
					"data":"data.contact_form",
					"template":"template.contact_form",
					"target":"target_tpl.popup"
				}
			},
			{
			"shordcode":"ico",
			"name":"Icons",
			"ico":"awards",
			"options":{
					"data":"data.contact_form",
					"template":"template.contact_form",
					"target":"target_tpl.popup"
				}
			},
			{
			"shordcode":"gallery",
			"name":"Gallery",
			"ico":"format-gallery",
			"options":null
			},
			{
				"shordcode":"boutique_banner",
			"name":"boutique_banner",
			"ico":"paperclip",
			"options":null
			}
		],
		'contact_form':{
			"title":"Contact Form Options",
			"description":"Select active fields on contanct form",
			"form":[
					{
						"title":"User name field",
						"check_name":"username_active",
						"check_value":"checked",						
						"field_name":"username",
						"field_value":"Insert name",
					},
					{
						"title":"User E-mail field",
						"check_name":"useremail_active",
						"check_value":"checked",
						"field_name":"useremail",
						"field_value":"Insert email",
					},
					{
						"title":"User phone field",
						"check_name":"useremail_active",
						"check_value":"checked",
						"field_name":"userphone",
						"field_value":"Insert phone",
					},
				]
		}

	};
	var template = {
		'shortcodes':
		`
			<div class="codes-wrap">
				{{~it:value:index}}
				{{? value.options }}
					<div data-index="{{=index}}" onclick="dot_content({{=value.options.data}},{{=value.options.template}},{{=value.options.target}})">
				{{??}}
					<div data-index="{{=index}}" onclick="insert_my_shordcode('{{=value.shordcode}}')">
				{{?}}	
					<div class="dashicons dashicons-{{=value.ico}}"></div>
					<div class="description">{{=value.name}}</div>
				</div>
				{{~}}
			</div>
		`,
		'contact_form':
		`
			<div class="form-wrap">
				<h2>{{=it.title}}</h2>
				<p>{{=it.description}}</p>
				<form id="contact-form-edit">
					{{~it.form:value:index}}
						<div class="form-row">
							<input class="row_prefix" name="{{=value.check_name}}" checked="{{=value.check_valuee}}" type="checkbox" />
							<label>{{=value.title}}</label>
							<input class="input" name="{{=value.field_name}}" value="{{=value.field_value}}"/>
						</div>
					{{~}}
				</form>
				<button class="submit" onclick="insert_my_shordcode('contact_form','contact-form-edit')">Insert component</button>
				<br>
			</div>
		`
	}

	function insert_my_shordcode(_name,_form = null){
		if(_form){
			var data = serializeForm_toArray(document.getElementById(_form));
			var params = array_to_shordcode(data);
		}else{
			var params = '';
		}
		tinyMCE.execCommand('mceInsertContent', false, '['+_name+' '+params+']');
		window.location.href = "#";
	}

	function dot_content(_data,_template,_target){
		var tempFn = doT.template(_template);
		var resultText = tempFn(_data);
		_target.innerHTML = resultText;
	}

	function getQueryParam(name) {
		url = window.location.href;
		name = name.replace(/[\[\]]/g, "\\$&");
		var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
			results = regex.exec(url);
		if (!results) return null;
		if (!results[2]) return '';
		return decodeURIComponent(results[2].replace(/\+/g, " "));
	}

	function serializeForm_toArray(form) {
	    var field, l, s = [];
	    if (typeof form == 'object' && form.nodeName == "FORM") {
	        var len = form.elements.length;
	        for (var i=0; i<len; i++) {
	            field = form.elements[i];
	            if (field.name && !field.disabled && field.type != 'file' && field.type != 'reset' && field.type != 'submit' && field.type != 'button') {
	                if (field.type == 'select-multiple') {
	                    l = form.elements[i].options.length; 
	                    for (j=0; j<l; j++) {
	                        if(field.options[j].selected)
	                            s[s.length] = { name: field.name, value: field.options[j].value };
	                    }
	                } else if ((field.type != 'checkbox' && field.type != 'radio') || field.checked) {
	                    s[s.length] = { name: field.name, value: field.value };
	                }
	            }
	        }
	    }
	    return s;
	}

	function array_to_shordcode(_array){
		var url = "";
		_array.forEach(function(e){
			url += e.name + '="' + e.value + '" ';
		});
		return url;
	}

	/* open modal by query string */
	var query_modal_data = getQueryParam('modal-start');
	if(query_modal_data){
		dot_content(data[query_modal_data],template[query_modal_data],target_tpl.popup);
	}else{
		/* open modal with first load mesage */
		window.location.href = "#FN-editor-modal";
	}

	window.editor_completed = function(){
		if(!query_modal_data){
			window.location.href = "#";
		}
	}




	var media = wp.media, shortcode_string = 'boutique_banner';
	wp.mce = wp.mce || {};
	wp.mce.boutique_banner = {
		shortcode_data: {},
		template: media.template( 'editor-boutique-banner' ),
		getContent: function() {
			var options = this.shortcode.attrs.named;
			options.innercontent = this.shortcode.content;
			return this.template(options);
		},
		View: { // before WP 4.2:
			template: media.template( 'editor-boutique-banner' ),
			postID: '123',
			initialize: function( options ) {
				this.shortcode = options.shortcode;
				wp.mce.boutique_banner.shortcode_data = this.shortcode;
			},
			getHtml: function() {
				var options = this.shortcode.attrs.named;
				options.innercontent = this.shortcode.content;
				return this.template(options);
			}
		},
		edit: function( data ) {
			var shortcode_data = wp.shortcode.next(shortcode_string, data);
			var values = shortcode_data.shortcode.attrs.named;
			values.innercontent = shortcode_data.shortcode.content;
			wp.mce.boutique_banner.popupwindow(tinyMCE.activeEditor, values);
		},
		// this is called from our tinymce plugin, also can call from our "edit" function above
		// wp.mce.boutique_banner.popupwindow(tinyMCE.activeEditor, "bird");
		popupwindow: function(editor, values, onsubmit_callback){
			values = values || [];
			if(typeof onsubmit_callback !== 'function'){
				onsubmit_callback = function( e ) {
					// Insert content when the window form is submitted (this also replaces during edit, handy!)
					var args = {
							tag     : shortcode_string,
							type    : e.data.innercontent.length ? 'closed' : 'single',
							content : e.data.innercontent,
							attrs : {
								title    : e.data.title,
								link     : e.data.link,
								linkhref : e.data.linkhref
							}
						};
					editor.insertContent( wp.shortcode.string( args ) );
				};
			}
			editor.windowManager.open( {
				title: 'Banner',
				body: [
					{
						type: 'textbox',
						name: 'title',
						label: 'Title',
						value: values.title
					},
					{
						type: 'textbox',
						name: 'link',
						label: 'Button Text',
						value: values.link
					},
					{
						type: 'textbox',
						subtype: 'url',
						name: 'linkhref',
						label: 'Button URL',
						value: values.linkhref
					},
					{
						type: 'textbox',
						name: 'innercontent',
						label: 'Content',
						value: values.innercontent
					}
				],
				onsubmit: onsubmit_callback
			} );
		}
	};
	wp.mce.views.register( shortcode_string, wp.mce.boutique_banner );



	tinymce.PluginManager.add( 'dtbaker_mce_banner', function( editor ) {
		editor.addButton( 'dtbaker_mce_banner_button', {
			text: 'Banner',
			icon: false,
			onclick: function() {
				wp.mce.boutique_banner.popupwindow(editor);
			}
		});
});

</script>
<?php
}