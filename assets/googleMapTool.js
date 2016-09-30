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
            var editorDoc = targetEditor.document.$;

            var _jsLoad = function(targetDoc, src, load, error) {
                var el = targetDoc.createElement( 'script' );

                el.src = src;
                el.async = true;

                if(load) {
                    el.onload = load;
                }

                if(error) {
                    el.onerror = error;
                }

                targetDoc.head.appendChild(el);
            };

            if($(targetEditor.document.$.querySelectorAll('[data-googlemap]')).length > 0) {

                var loadCallback = function() {

                    $(targetEditor.document.$.querySelectorAll('[data-googlemap]')).each(function() {
                        var $this = $(this);
                        var id = $this[0].id;
                        var lat = $this.data('lat');
                        var lng = $this.data('lng');
                        var text = $this.data('text');

                        var map = new editorWindow.google.maps.Map(editorDoc.getElementById(id), {
                            center: new editorWindow.google.maps.LatLng(lat, lng),
                            zoom: 10,
                            mapTypeId: editorWindow.google.maps.MapTypeId.ROADMAP
                        });

                        var myLatLng = new editorWindow.google.maps.LatLng(lat, lng);
                        var marker = new editorWindow.google.maps.Marker({
                            position: myLatLng,
                            map: map
                        });

                        editorWindow.infowindow = new editorWindow.google.maps.InfoWindow({
                            content: text
                        });

                        editorWindow.infowindow.open(map, marker);
                    });
                };

                if(editorWindow.google) {
                    loadCallback();
                }else {
                    _jsLoad(editorDoc, 'http://maps.googleapis.com/maps/api/js?key=AIzaSyBYz-hHmnLkZszDc-DeKoFplyBSrjrEsao', loadCallback);
                }
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