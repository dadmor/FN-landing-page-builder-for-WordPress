var editableClass = 'FN_edit';
var area_index = null;

var classname = document.getElementsByClassName(editableClass);
for (var i = 0; i < classname.length; i++) {
	classname[i].addEventListener('click', selectEditableArea, false);
	classname[i].setAttribute('data-index',i);
}
//_D_mask.addEventListener('click', outEditableArea, false);
var _D_editor = document.getElementById('wp-FN_frontend_editor-wrap');
_D_editor.addEventListener('click', editor_wrapper, false);

var _D_save = document.getElementById('save_content_button');
_D_save.addEventListener('click', save_content, false);

/* <button type="button" id="insert-media-button" class="button insert-media add_media" data-editor="FN_frontend_editor"><span class="wp-media-buttons-icon"></span> Dodaj medium</button> */

function selectEditableArea(e){

	var _D_mask = document.getElementById('FN-editor-wrapper');
	var _D_editor = document.getElementById('wp-FN_frontend_editor-wrap');
	var _D_body = document.getElementsByTagName('body')[0];
	var _D_content = document.getElementById('FN_content_before_filtering');
	var _D_frame = document.getElementById('FN_frontend_editor_ifr');

	e.cancelBubble = true
	area_index = this.getAttribute('data-index');

	_D_mask.classList.remove('hide');
	_D_mask.style.height = _D_body.offsetHeight+50;

	var out = _D_content.getElementsByClassName(editableClass)[area_index].innerHTML;
	/*var out = this.innerHTML;*/
	tinymce.activeEditor.setContent(out);

	_D_editor.style.width = this.offsetWidth;	
	_D_editor.style.left = this.offsetLeft;
	_D_editor.style.top = this.offsetTop - 45;
	_D_frame.style.height = this.offsetHeight - 45;

}

function save_content(e){
	var _D_mask = document.getElementById('FN-editor-wrapper');
	_D_mask.classList.add('hide');
	update_content_technical_container(tinymce.activeEditor.getContent());
}
function editor_wrapper(e){
	//e.preventDefault();
	//e.cancelBubble = true
}

var _URL = window.FN_editor_data.url;
function update_content_technical_container(content){
	var _D_content = document.getElementById('FN_content_before_filtering');
	_D_content.getElementsByClassName(editableClass)[area_index].innerHTML = content;
	document.getElementById('entry-content').getElementsByClassName(editableClass)[area_index].innerHTML = content;
    window.FN_editor_data.contentpart = btoa(content);
    window.FN_editor_data.content = btoa(_D_content.innerHTML);
    window.FN_editor_data.area_index = area_index;
    
	//console.log(_D_content.innerHTML);
	loadFile(function(res) {		
		var out = JSON.parse(res);
        console.log(out);
        document.getElementById('entry-content').getElementsByClassName(editableClass)[out.area_index].innerHTML = out.contentpart;

	}, _URL+"/wp-content/plugins/FN-frontend-editor/ajax/post.php", window.FN_editor_data, function(res) {console.log(res)}, "post"); 
}

var loadFile = function (callback, file, post_data, error, method) {

    var xobj = new XMLHttpRequest();
    xobj.overrideMimeType("application/json");
    xobj.open(method, file, true);
	xobj.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    //xobj.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
   // xobj.setRequestHeader("Access-Control-Allow-Origin", _URL);
    //xobj.setRequestHeader("Access-Control-Allow-Headers","origin");
   // xobj.setRequestHeader("Access-Control-Request-Headers","access-control-allow-origin");
    //xobj.setRequestHeader("Host", _URL); 
    xobj.onreadystatechange = function () {
        if (xobj.readyState == 4 && xobj.status == "200") {
            callback(xobj.responseText);
            return true;
        }
        if (xobj.readyState == 4 && xobj.status == "201") {
            callback(xobj.responseText);
            return true;
        }
        if(xobj.readyState == 4 && xobj.status == "403") {          
            var msg = JSON.parse(xobj.responseText);
            msg.error = 'loadFile:error 403';
            if(msg.message){
                console.log(msg);
            }
            error(msg);
            return true;
        }
        if(xobj.status == "404") {  
            var msg ={};
            msg.error = 'FATAL ERROR\n'+file+' \nDOESNT EXIST';
            error(msg);
            return true;            
        }
    };

    xobj.send(btoa(JSON.stringify(post_data)));  
}

