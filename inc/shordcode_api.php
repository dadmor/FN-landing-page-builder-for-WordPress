<?php 
/*Add this code to your functions.php file of current theme OR plugin file if you're making a plugin*/
//add the button to the tinymce editor
add_action('media_buttons_context','add_my_tinymce_media_button');
function add_my_tinymce_media_button($context){

	return $context.='<a href="#FN-editor-modal" onclick="dot_content(shortcode_data.shortcodes,template.shortcodes,target_tpl.popup)" class="button"><span class="dashicons dashicons-screenoptions"></span>Add component</a>';
}

//javascript code needed to make shortcode appear in TinyMCE edtor
add_action('wp_footer','my_shortcode_add_shortcode_to_editor');
add_action('admin_footer','my_shortcode_add_shortcode_to_editor');
function my_shortcode_add_shortcode_to_editor(){?>

<script>
	var code_serial_number = 0;
	var code_selected_index = false;
	var target_tpl = {
		"popup" : document.getElementById('modal-body'),

	}

	var shortcode_data = {
		'shortcodes':[
			{
			"shortcode":"autoresponder",
			"name":"Autoresponder",
			"ico":"megaphone",
			"options":true,
			},
			{
			"shortcode":"contact_form",	
			"name":"Contact form",
			"ico":"email-alt",
			"options":false,
			},
			{
			"shortcode":"ico",
			"name":"Icons",
			"ico":"awards",
			"options":true,
			},
			{
			"shortcode":"gallery",
			"name":"Gallery",
			"ico":"format-gallery",
			"options":null,
			},
			{
			"shortcode":"boutique_banner",
			"name":"boutique_banner",
			"ico":"paperclip",
			"options":false,
			}
		],
		'autoresponder':{
			"title":"Autoresponder Form Options",
			"description":"Select active fields on autoresponder form",
			"shortcode":"autoresponder",
			"edit_mode":false,
			"form":[
					{
						"title":"User E-mail field",
						"check_name":"useremail_active",
						"check_value":"checked",
						"field_name":"useremail",
						"field_value":"Insert email",
						"field_type":"text"
					},
					{
						"title":"User first name field",
						"check_name":"userfirstname_active",
						"check_value":"checked",						
						"field_name":"userfirstname",
						"field_value":"Insert first name",
						"field_type":"text"
					},
					{
						"title":"User last name field",
						"check_name":"userlastname_active",
						"check_value":"checked",						
						"field_name":"userlastname",
						"field_value":"Insert last name",
						"field_type":"text"
					},
					{
						"title":"User phone field",
						"check_name":"userphone_active",
						"check_value":"checked",
						"field_name":"userphone",
						"field_value":"Insert phone",
						"field_type":"text"
					},
					{
						"title":"User extra field 1",
						"check_name":"userextra1_active",
						"check_value":"checked",
						"field_name":"userextra1",
						"field_value":"Insert your eyes colour",
						"field_type":"text"
					},
					{
						"title":"User extra field 2",
						"check_name":"userextra2_active",
						"check_value":"checked",
						"field_name":"userextra2",
						"field_value":"Insert your age",
						"field_type":"text"
					},
					{
						"title":"User extra field 3",
						"check_name":"userextra3_active",
						"check_value":"checked",
						"field_name":"userextra3",
						"field_value":"Insert your TAX ID",
						"field_type":"text"
					},
					{
						"title":"Agreement",
						"check_name":"useragreement_active",
						"check_value":"checked",
						"field_name":"useragreement",
						"field_value":"I agree for procesing my data",
						"field_type":"checkbox"
					},
				],
			},
		"autoresponder-options":{
			"fields":[
				{
					"name":"text",
					"type":"text",
					"value":"10"
				}

			]
		},
		'ico':{
			"title":"Icons",
			"description":"Choose icon",
			"shortcode":"ico",
			"edit_mode":false,
			"form":[
					{
						"title":"Icon name",
						"field_name":"name",
						"field_value":"home",
					},
					{
						"title":"Icon size",						
						"field_name":"class",
						"field_value":"big",
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
					<div data-index="{{=index}}" onclick="dot_content(shortcode_data.{{=value.shortcode}},template.{{=value.shortcode}},target_tpl.popup)">
				{{??}}
					<div data-index="{{=index}}" onclick="insert_my_shortcode('{{=value.shortcode}}')">
				{{?}}	
					<div class="dashicons dashicons-{{=value.ico}}"></div>
					<div class="description">{{=value.name}}</div>
				</div>
				{{~}}
			</div>
		`,
		'autoresponder':
		`
			<div class="tabs">
				<div class="active" data-target="tab-1" >
					Autoresponder form options
				</div>
				<div data-target="tab-1" onclick="load_data('action=arigatomanager&op=lists',template.autoresponder_options,document.querySelector('.code-form-wrap'))">
					Autoresponder mailing options
				</div>
			</div>

			<div class="code-form-wrap" data-tab="tab-1">
				<h2>{{=it.title}}</h2>
				<p>{{=it.description}}</p>
				<form id="{{=it.shortcode}}-edit">
					{{~it.form:value:index}}
						<div class="form-row">
							<input class="row_checkbox" name="{{=value.check_name}}" checked="{{=value.check_value}}" type="checkbox" onclick="checkbox_toggle_row(this)"/>
							<label>{{=value.title}}</label>
							<input class="input" name="{{=value.field_name}}" value="{{=value.field_value}}"/>
						</div>
					{{~}}
				</form>
				{{? it.edit_mode }}
					<button class="submit" onclick="insert_my_shortcode('{{=it.shortcode}}','{{=it.shortcode}}-edit',{{=it.edit_mode}})">Edit component</button>
				{{??}}
					<button class="submit" onclick="insert_my_shortcode('{{=it.shortcode}}','{{=it.shortcode}}-edit',{{=it.edit_mode}})">Insert component</button>
				{{?}}	
			</div>

			<div class="code-form-wrap" data-tab="tab-2">
				
			</div>
		`,
		'autoresponder_options':`
			<pre>{{=it}}</pre>
		`,
		'ico':
		`
			

			<div class="code-ico-wrap">
				<h2>{{=it.title}}</h2>
				<p>{{=it.description}}</p>
	
				<form id="{{=it.shortcode}}-edit">
					{{~it.form:value:index}}
						<div class="form-row">							
							<label>{{=value.title}}</label>
							<input class="input" name="{{=value.field_name}}" value="{{=value.field_value}}"/>
						</div>
					{{~}}
				</form>
				{{? it.edit_mode }}
				<button class="submit" onclick="insert_my_shortcode('{{=it.shortcode}}','{{=it.shortcode}}-edit',{{=it.edit_mode}})">Edit component</button>
				{{??}}
					<button class="submit" onclick="insert_my_shortcode('{{=it.shortcode}}','{{=it.shortcode}}-edit',{{=it.edit_mode}})">Insert component</button>
				{{?}}	
				<br>
			</div>

		`
	}

	function insert_my_shortcode(_name,_form = null,_edit=false){
		
		if(_form){
			var data = serializeForm_toArray(document.getElementById(_form));
			var params = array_to_shortcode(data);
		}else{
			var params = '';
		}
		
		if(_edit){

			code_selected_index.target.closest('.shortcode_render').setAttribute('data-repr',_name+' '+params);

		}else{
			
			tinyMCE.execCommand('mceInsertContent', false, '['+_name+' '+params+']');
		}

		
		window.location.href = "#close";
	}

	function remove_shordcode_by_id(data_index){

		tinyMCE.activeEditor.dom.remove(tinyMCE.activeEditor.dom.select('div.shortcode_render[data-index='+data_index+']'));
	}

	var edit_code = function(params,_e){

		code_selected_index = _e;
		var code = params.split(' ')[0];
		params = params.replace(/"/g,'","');
		params = params.replace(/=",/g,'":"');
		params = params.replace(/"," /g,'","');
		params = params.replace(/""/g,'"');
		params = params.replace(code+' ','');
		params = params.slice(0,-2);
		params = '{"'+params+'}';
		params = JSON.parse(params);
		for (var i = 0; i < shortcode_data.shortcodes.length; i++) {
			if( shortcode_data.shortcodes[i]['shortcode'] == code ){
				if(shortcode_data.shortcodes[i].options){
					window.location.href = "#FN-editor-modal";
					shortcode_data[code].edit_mode = true;
					dot_content(shortcode_data[code],template[code],target_tpl.popup);
					shortcode_data[code].edit_mode = false;
				}
			}
		}
		/* merge options with existed data */
		var check_inputs = target_tpl.popup.querySelectorAll('input');
		for (var i = 0; i < check_inputs.length; i++) {
			var get_val = params[check_inputs[i].name]
			check_inputs[i].value = get_val;
			if(check_inputs[i].type == 'checkbox'){
				
				if( check_inputs[i].value == 'on'){
					console.log('check_inputs[i].checked true');
					check_inputs[i].checked = true;
				}else{
					console.log('check_inputs[i].checked false');
					check_inputs[i].checked = false;
					//TODO - hidde is row
				}
				
			}
	    }
	}

	var load_data = function(params,template,target){
		var xmlhttp=new XMLHttpRequest();
		var _t = target;
		var _tpl = template;
		
		var data = params;
		xmlhttp.open("POST",window.FN_editor_data.url+"/wp-admin/admin-ajax.php",true);
		xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xmlhttp.send(data);
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				console.log(xmlhttp);
				dot_content(JSON.parse(xmlhttp.response),_tpl,_t);
			}
		}

	}
	
	console.log('load data');

	var dot_content = function(_data,_template,_target){
		var tempFn = doT.template(_template);
		var resultText = tempFn(_data);
		_target.innerHTML = resultText;
	}

	var close_modal = function(){
		window.location.href = "#close";
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

	function array_to_shortcode(_array){
		var url = "";
		_array.forEach(function(e){
			url += e.name + '="' + e.value + '" ';
		});
		return url;
	}

	/* open modal by query string */
	var query_modal_data = getQueryParam('modal-start');
	if(query_modal_data){
		dot_content(shortcode_data[query_modal_data],template[query_modal_data],target_tpl.popup);
	}else{
		/* open modal with first load mesage */
		window.location.href = "#FN-editor-modal";
	}

	window.editor_completed = function(){
		if(!query_modal_data){
			window.location.href = "#close";
		}
	}

	/* modal effects */

	var checkbox_toggle_row = function(_t){
		if(_t.checked){
			_t.closest('.form-row').classList.remove('inactive');
		}else{
			_t.closest('.form-row').classList.add('inactive');
		}
		
		
	}

</script>
<?php
}