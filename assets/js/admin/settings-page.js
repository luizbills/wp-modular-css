window.jQuery( document.body ).on('better-wp-admin-api-code-editor-loaded', function (evt, editor) {
	if ( 'wp_modular_css_config_json' !== editor.container.dataset.codeEditorFor ) return;
	
	var $ = window.jQuery.noConflict();
	var cookie_cursor = 'wp_modular_css_config_json_cursor';

	// command "save"
	editor.commands.addCommand({
		name: 'save',
		bindKey: {win: 'Ctrl-S',  mac: 'Command-S'},
		exec: function(editor) {
			var $form = $(editor.container).parents('form');
			$form.find('#submit').trigger('click');
		},
		readOnly: false
	});

	// save the editor cursor position
	$('#wp-modular-css-settings > form').on('submit', function (evt) {
		setCookie( cookie_cursor, JSON.stringify(editor.selection.getCursor()) );
		return true;
	});

	// get session line and column
	var cursor = getCookie(cookie_cursor);

	if ( cursor !== null ) {
		cursor = JSON.parse(cursor);
		editor.gotoLine(cursor.row + 1, cursor.column);
	}
});

window.jQuery( document.body ).on('better-wp-admin-api-code-editor-loaded', function (evt, editor) {
	if ( 'wp_modular_css_custom_css' !== editor.container.dataset.codeEditorFor ) return;
	
	editor.getSession().setUseWorker(false);
	
	var $ = window.jQuery.noConflict();
	var cookie_cursor = 'wp_modular_css_custom_css_cursor';

	// command "save"
	editor.commands.addCommand({
		name: 'save',
		bindKey: {win: 'Ctrl-S',  mac: 'Command-S'},
		exec: function(editor) {
			var $form = $(editor.container).parents('form');
			$form.find('#submit').trigger('click');
		},
		readOnly: false
	});

	// save the editor cursor position
	$('#wp-modular-css-settings > form').on('submit', function (evt) {
		setCookie( cookie_cursor, JSON.stringify(editor.selection.getCursor()) );
		return true;
	});

	// get session line and column
	var cursor = getCookie(cookie_cursor);

	if ( cursor !== null ) {
		cursor = JSON.parse(cursor);
		editor.gotoLine(cursor.row + 1, cursor.column);
	}
});

window.jQuery(function ($) {
	// reset button
	$('#reset-tachyons').on('click', function (evt) {
		if ( confirm( "Are your sure? Your configuration will be lost if you confirm!" ) ) {
			$('textarea#wp_modular_css_config_json').val('reset');
			$('#wp-modular-css-settings > form #submit').trigger('click');
		}
	});
})

// Cookie library
// https://stackoverflow.com/a/24103596
function setCookie (name, value, days) {
	var expires = "";
	if (days) {
		var date = new Date();
		date.setTime(date.getTime() + (days*24*60*60*1000));
		expires = "; expires=" + date.toUTCString();
	}
	document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

function getCookie (name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}