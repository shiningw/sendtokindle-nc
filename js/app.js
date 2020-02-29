/**
 * @namespace OCA.SendtoKindle
 */
OCA.SendtoKindle = {};

OCA.SendtoKindle.App = {
    _initialized: false,
    initialize: function ($el) {
        if (this._initialized) {
            return;
        }
        this._initialized = true;
        $('#file_action_panel').after('<div id="kindle-alert" style="display:none;"></div>');
        //console.log(OCA.Files.FileList);
        this._createFileActions();
    },
    _createFileActions: function () {
        var fileActions = OCA.Files.fileActions;
        var mimes = ['application/x-mobipocket-ebook', 'application/pdf', 'application/vnd.amazon.ebook', 'AZW/Mobi', 'application/epub+zip', 'text', 'application/x-ms-reader','Application/x-rocketbook','application/x-newton-compatible-pkg','PRC'];
        fileActions.setDefault('dir', 'Open');
        //console.log(fileActions.getCurrentMimeType());
        var actionHandler = function (filename, context) {
            var fileList = context.fileList;
            var url = OC.filePath('sendtokindle', 'kindle', 'send.php');
            $('#kindle-alert').addClass('sending').text('Sending...Please give it a few seconds').show();
            $.post(url, {
                file: filename,
                dir: fileList.getCurrentDirectory()
            },
                    ).done(function (data) {
                $('#kindle-alert').removeClass('sending');
                if (data.status == 'success') {
                    $('#kindle-alert').addClass('success');
                } else {
                    $('#kindle-alert').addClass('error');
                }
                OC.msg.finishedAction('#kindle-alert', data);
            });
        };
        var params = {
            name: 'Kindle',
            displayName: t('files', 'Send to Kindle'),
            permissions: OC.PERMISSION_READ,
            //mime: 'application/x-mobipocket-ebook',
            iconClass: 'icon-share',
            actionHandler: actionHandler,
        };
        for (i = 0; mime = mimes[i]; i++) {
            params.mime = mime;
            fileActions.registerAction(params);
        }
    }
};

$(document).ready(function () {
    $('#app-content-files').one('show', function () {
        var App = OCA.SendtoKindle.App;
        App.initialize($('#app-content-files'));
        // force breadcrumb init
        // App.fileList.changeDirectory(App.fileList.getCurrentDirectory(), false, true);
    });
});
