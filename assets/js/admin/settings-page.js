window.jQuery( document.body ).on('better-wp-admin-api-code-editor-loaded', function (evt, editor) {
	var $ = window.jQuery;
	var json_editor = editor;
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

	// JSON validation
	$('#wp-modular-css-settings > form').on('submit', function (evt) {
		var submit = true;

		// save the editor cursor position
		setCookie( cookie_cursor, JSON.stringify(editor.selection.getCursor()) );

		return submit;
	});

	// get session line and column
	var cursor = getCookie(cookie_cursor);

	if ( cursor !== null ) {
		cursor = JSON.parse(cursor);
		editor.gotoLine(cursor.row + 1, cursor.column);
	}

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
});