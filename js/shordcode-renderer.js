//(function() {
	tinymce.create('tinymce.plugins.icitspots', {

		init : function(ed, url) {
			var _t = this;

			//replace shortcode before editor content set
			ed.onBeforeSetContent.add(function(ed, o) {
				o.content = _t._do_spot(o.content,_t);
			});
			
			//replace shortcode as its inserted into editor (which uses the exec command)
			ed.onExecCommand.add(function(ed, cmd) {
			    if (cmd ==='mceInsertContent'){
					tinyMCE.activeEditor.setContent( _t._do_spot(tinyMCE.activeEditor.getContent()) );
				}
			});
			//replace the image back to shortcode on save
			ed.onPostProcess.add(function(ed, o) {
				if (o.get)
					o.content = _t._get_spot(o.content);
			});
		},

		_do_spot : function(co,_t) {
			var _res = co.replace(/(\[([\w_]+)[^\]]*\]([^\[]*\[\/\2])?)/g, function(a, b, c){
				code_serial_number++;
				return '<div class="shortcode_render" contenteditable=false data-index=\''+code_serial_number+'\' data-repr=\''+tinymce.DOM.encode(a.slice(1, -1))+'\' >'+c+'<div class="shortcode_end"><div class="edit">edit</div><div class="delete">delete</div></div></div>';
			});
			return _res;

		},

		_get_spot : function(co) {
			_res = co.replace(/<div class="shortcode_render" contenteditable="false" data-index="(\d+)" data-repr="([^"]*)(?:(?!<div class="shortcode_end">).)*(<div class="shortcode_end">)(?:(?!<\/div><\/div>).)*<\/div><\/div><\/div>/g, function(a, b, c){
				return '['+tinymce.DOM.decode(c)+']';
			});
			return _res;
		}
	});

	var tiny_element_click = function(e){

		try {
			var code_data = e.target.closest('.shortcode_render').getAttribute('data-repr');
			var code_index = e.target.closest('.shortcode_render').getAttribute('data-index');
			if(e.target.classList[0] == 'edit'){
				edit_code(code_data,e);

			}
			if(e.target.classList[0] == 'delete'){
				e.target.closest('.shortcode_render').remove();
			}
		}
		catch(err) {}

	}

	tinymce.PluginManager.add('icitspots', tinymce.plugins.icitspots);
//})();



