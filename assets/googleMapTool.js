XEeditor.tools.define({
    id : 'editortool/googlemap@googlemap',
    events: {
        iconClick: function(targetEditor, cbAppendToolContent) {

            var cWindow = window.open(googleToolURL.get('popup'), 'test', "width=750,height=930,directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no");

            $(cWindow).on('load', function() {
                cWindow.targetEditor = targetEditor;
                cWindow.appendToolContent = cbAppendToolContent;
            });
        },
        elementDoubleClick: function() {
            
        },
        beforeSubmit: function(targetEditor) {
            $(targetEditor.document.$.querySelectorAll('[data-googlemap]')).empty();
        },
        editorLoaded: function(targetEditor) {
            var editorWindow = targetEditor.window.$;

            if($(targetEditor.document.$.querySelectorAll('[data-googlemap]')).length > 0) {
                $(targetEditor.document.$.querySelectorAll('[data-googlemap]')).renderer({win: editorWindow});
            }
        }
    },
    props: {
        name: 'GoogleMap',
        options: {
            label: 'Google Map',
            command: 'openGoogleMap'
        },
        addEvent: {
            doubleClick: false
        }
    }
});