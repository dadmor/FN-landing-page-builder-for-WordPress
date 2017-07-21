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
			//console.log(co);
			/*return co.replace(/\[contact_form([^\]]*)\]/g, function(a,b){
				return '<div style="border:2px dashed #333" onclick="alert(\'asdas\')">'+tinymce.DOM.encode(b)+'</div>';
			});*/

			var _res = co.replace(/(\[([\w_]+)[^\]]*\]([^\[]*\[\/\2])?)/g, function(a, b, c){
				return '<div class="shortcode_render" contenteditable=false data-repr=\''+tinymce.DOM.encode(a.slice(1, -1))+'\'>'+c+'<div class="shortcode_end">end</div></div>';
			});
			return _res;

		},

		_get_spot : function(co) {
			_res = co.replace(/<div class="shortcode_render" contenteditable="false" data-repr="([^"]*)(?:(?!<div class="shortcode_end">).)*(<div class="shortcode_end">)(?:(?!<\/div><\/div>).)*<\/div><\/div>/g, function(a, b){
				return '['+tinymce.DOM.decode(b)+']';
			});
			return _res;
		}
	});

	var tiny_element_click = function(e){

		try {
    		var code_data = e.target.closest('.shortcode_render').getAttribute('data-repr');
    		edit_code(code_data);
		}
		catch(err) {}

	}

	tinymce.PluginManager.add('icitspots', tinymce.plugins.icitspots);
//})();



