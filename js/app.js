/**
 * @namespace OCA.SendtoKindle
 */
OCA.SendtoKindle = {};

OCA.SendtoKindle.App = {
	_initialized: false,

	initialize: function($el) {
		if (this._initialized) {
			return;
		}
		this._initialized = true;
		$('#file_action_panel').after('<div id="kindle-alert" style="display:none;"></div>');
		this._createFileActions();
	},

	_createFileActions: function() {
		var fileActions = OCA.Files.fileActions;
		fileActions.register('dir', 'Open', OC.PERMISSION_READ, '', function (filename, context) {
				var dir = context.$file.attr('data-path') || context.fileList.getCurrentDirectory();
				context.fileList.changeDirectory(OC.joinPaths(dir, filename), true, false, parseInt(context.$file.attr('data-id'), 10));
			});
			
		fileActions.setDefault('dir', 'Open');
		//console.log(fileActions.getCurrentMimeType());

		fileActions.registerAction({
			name: 'Kindle',
			displayName: t('files', 'Send to Kindle'),
			mime: 'all',
			permissions: OC.PERMISSION_READ,
			iconClass: 'icon-share',
			actionHandler: function(filename, context) {
				var fileList = context.fileList;
				
				var url = OC.filePath('sendtokindle', 'ajax', 'mail.php');
				$('#kindle-alert').addClass('sending').text('Sending').show();
				//var url = '/test.php';
				$.post(url, {
						file: filename,
						dir: fileList.getCurrentDirectory()
					},
					
				).done(function(data){
					$('#kindle-alert').removeClass('sending');
						if (data.status == 'success') {
							$('#kindle-alert').addClass('success');
						}else {
							$('#kindle-alert').addClass('error');
						}
						OC.msg.finishedAction('#kindle-alert',data);

					  });
			}
		});
	}
};

$(document).ready(function() {
	$('#app-content-files').one('show', function() {
		var App = OCA.SendtoKindle.App;
		App.initialize($('#app-content-files'));
		// force breadcrumb init
		// App.fileList.changeDirectory(App.fileList.getCurrentDirectory(), false, true);
	});
});
