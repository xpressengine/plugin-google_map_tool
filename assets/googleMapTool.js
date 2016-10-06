XEeditor.tools.define({
    id : 'editortool/googlemap@googlemap',
    events: {
        iconClick: function(targetEditor, cbAppendToolContent) {

            var cWindow = window.open(googleToolURL.get('popup'), 'createPopup', "width=750,height=930,directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no");

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
                $(targetEditor.document.$.querySelectorAll('[data-googlemap]')).renderer({
                    win: editorWindow,
                    callback: function (el) {
                        $(el).prepend('<button type="button" class="btnEditMap" style="position:absolute;z-index:1;left:0;top:0">Edit</button>');
                    }
                });

                $(targetEditor.document.$.querySelectorAll('[data-googlemap]')).on('click', '.btnEditMap', function() {
                    var cWindow = window.open(googleToolURL.get('edit_popup'), 'editPopup', "width=750,height=930,directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no");
                    var $this = $(this);

                    $(cWindow).on('load', function() {
                        cWindow.targetEditor = targetEditor;
                        cWindow.$targetDom = $this.parents("[data-googlemap]");
                    });
                });

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