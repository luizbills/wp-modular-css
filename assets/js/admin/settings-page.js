window.jQuery( document.body ).on('better-wp-admin-api-code-editor-loaded', function (evt, editor) {
	if ( 'wp_modular_css_config_json' !== editor.container.dataset.codeEditorFor ) return;

	var $ = window.jQuery.noConflict();
	var config = window.wp_modular_css_settings;

	createSaveCommand(editor)
	setupCursorSaveLoad(editor)

	var $form = $(editor.container).parents('form');

	// prevent save with invalid JSON
	$form.addClass('validate-json');
	$form.on('submit', function (evt) {
		if ($form.hasClass('validate-json')) {
			var json_text = editor.getValue();
			try {
				var json = JSON.parse(json_text);

				if (json['include-normalize']) {
					alert('The "include-normalize" option is deprecated since version 2.0. To stop showing this message, erase this option from "Tachyons values" field and set the "CSS Reset" field.');
				}
			} catch (exception) {
				evt.preventDefault();
				console.log(exception);
				alert(config.messages.error_config_json_syntax)
			}
		}
	})

	// reset button
	$('#reset-tachyons').on('click', function (evt) {
		if ( confirm( "Are your sure? Your configuration will be lost if you confirm!" ) ) {
			$('textarea#wp_modular_css_config_json').val('reset');
			$form.removeClass('validate-json');
			$('#wp-modular-css-settings > form #submit').trigger('click');
		}
	});
});

window.jQuery( document.body ).on('better-wp-admin-api-code-editor-loaded', function (evt, editor) {
	if ( 'wp_modular_css_custom_css' !== editor.container.dataset.codeEditorFor ) return;

	editor.getSession().setUseWorker(false);

	createSaveCommand(editor)
	setupCursorSaveLoad(editor)
});

function createSaveCommand (editor) {
	var $ = window.jQuery.noConflict();
	editor.commands.addCommand({
		name: 'save',
		bindKey: {win: 'Ctrl-S',  mac: 'Command-S'},
		exec: function(editor) {
			var $form = $(editor.container).parents('form');
			$form.find('#submit').trigger('click');
		},
		readOnly: false
	});
}

function setupCursorSaveLoad (editor) {
	var $ = window.jQuery.noConflict();
	var editor_id = editor.container.dataset.codeEditorFor;
	var cookie_cursor = editor_id + '_cursor';

	// save the editor cursor position on save
	$('#wp-modular-css-settings > form').on('submit', function (evt) {
		setCookie( cookie_cursor, JSON.stringify(editor.selection.getCursor()) );
		return true;
	});

	// get session line and column
	var cursor_position = getCookie(cookie_cursor);

	if ( cursor_position !== null ) {
		cursor_position = JSON.parse(cursor_position);
		editor.gotoLine(cursor_position.row + 1, cursor_position.column);
	}
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