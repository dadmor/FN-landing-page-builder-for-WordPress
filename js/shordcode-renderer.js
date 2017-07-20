//(function() {
	tinymce.create('tinymce.plugins.icitspots', {

		init : function(ed, url) {
			var _t = this;
			//t.url = url;

			//replace shortcode before editor content set
			ed.onBeforeSetContent.add(function(ed, o) {
				o.content = _t._do_spot(o.content);
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

		_do_spot : function(co) {
			//console.log(co);
			/*return co.replace(/\[contact_form([^\]]*)\]/g, function(a,b){
				return '<div style="border:2px dashed #333" onclick="alert(\'asdas\')">'+tinymce.DOM.encode(b)+'</div>';
			});*/

			//co.replace()
			

			var res = co.replace(/\[_contact_form([^\]]*)\]/g, '<div class="shordcode_render_representation  contact_form" style="background:#c7c7c7; text-align:center; padding:1em" contenteditable=false>Contact Form</div>');
			return res;

		},

		_get_spot : function(co) {

			function getAttr(s, n) {
				n = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s);
				return n ? tinymce.DOM.decode(n[1]) : '';
			};

			return co.replace(/(?:<p[^>]*>)*(<img[^>]+>)(?:<\/p>)*/g, function(a,im) {
				var cls = getAttr(im, 'class');

				if ( cls.indexOf('wpSpot') != -1 )
					return '<p>['+tinymce.trim(getAttr(im, 'title'))+']</p>';

				return a;
			});
		},

		/*getInfo : function() {
			return {
				longname : 'Spots shortcode replace',
				author : 'Simon Dunton',
				authorurl : 'http://www.wpsites.co.uk',
				infourl : '',
				version : "1.0"
			};
		}*/
	});

	tinymce.PluginManager.add('icitspots', tinymce.plugins.icitspots);
//})();
