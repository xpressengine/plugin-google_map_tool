<style>
    #container {margin: 0 auto; width: 500px; margin-top:15px;}
    #mapWrapper { width: 500px; height: 500px;}
    #content {width: 100%; height: 80px;}
</style>

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />

<div id="container">
    <div id="mapWrapper"></div>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">
                <label for="content">Marker 내용 <small class="text-primary">(HTML사용 가능)</small></label>
                <textarea id="content" placeholder="마커에 표시할 내용을 입력하세요." ></textarea>
            </div>
            <div class="form-group">
                <label for="useFullHorizontal">가로 넓이</label>
                <input type="number" id="hSize" pattern="[0-2000]" min="0" max="10000" />&nbsp;<small class="text-measure">px</small>
                |
                <label for="useFullHorizontal"><small class="text-success">가로 넓이 100% 유지</small></label>
                <input type="checkbox" id="checkFullWidth" />
            </div>
            <div class="form-group">
                <label for="vSize">세로 넓이</label>
                <input type="number" id="vSize" pattern="[0-2000]" min="0" max="10000" />&nbsp;<small class="text-measure">px</small>
            </div>
        </div>
        <div class="panel-footer clearfix">
            <div class="pull-left">
                <span style="color:#6d6d6d;line-height:30px">지도를 클릭하면 마커 위치변경이 가능합니다.</span>
            </div>
            <div class="pull-right">
                <button type="button" id="btnAppendToEditor" class="btn btn-primary">에디터에 넣기</button>
            </div>

        </div>
    </div>
</div>


<script type="text/javascript">

    (function() {
        var defaultText = '입력하세요';
        var text = '', mapWidth = 0, mapHeight = 0;
        var selfObj;
        var map, marker, infowindow;

        var _generateUUID = function() {
            var d = new Date().getTime();
            if(window.performance && typeof window.performance.now === "function"){
                d += performance.now(); //use high-precision timer if available
            }
            var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                var r = (d + Math.random()*16)%16 | 0;
                d = Math.floor(d/16);
                return (c=='x' ? r : (r&0x3|0x8)).toString(16);
            });
            return uuid;
        }

        return {
            init: function() {
                selfObj = this;

                var myLatLng = new google.maps.LatLng('{{ $config->get('lat') }}', '{{ $config->get('lng') }}');

                map = new google.maps.Map(document.getElementById('mapWrapper'), {
                    center: myLatLng,
                    zoom: parseInt('{{ $config->get('zoom') }}'),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });

                marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map
                });

                infowindow = new google.maps.InfoWindow({
                    content: defaultText
                });

                infowindow.open(map, marker);

                selfObj.bindEvent();

                return this;
            },
            bindEvent: function() {
                google.maps.event.addListener(map, 'click', function(event) {
                    marker.setPosition(event.latLng);
//                    map.setCenter(event.latLng);
                });

                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.open(map, marker);
                });

                $(window).on('load', selfObj.preventReloading);
                $('#content').on('keyup', selfObj.setContent);
                $('#checkFullWidth').on('change', selfObj.checkFullWidth);
                $('#btnAppendToEditor').on('click', function() {
                    if(selfObj.isValid()) {
                        selfObj.appendToEditor();
                    }
                });
            },
            preventReloading: function() {
                if(!self.appendToolContent) {
                    alert('팝업을 재실행 하세요.');
                    self.close();
                }
            },
            setContent: function(e) {
                var $this = $(this);

                text = $this.val();

                if($this.val() === '') {
                    infowindow.setContent(defaultText);
                }else {
                    infowindow.setContent(text);
                }
            },
            appendToEditor: function() {
                var lat = marker.getPosition().lat();
                var lng = marker.getPosition().lng();

                var editorDoc = self.targetEditor.document.$;
                var editorWindow = self.targetEditor.window.$;
                var uuid = _generateUUID();
                var width = $('#hSize').val() + $('#hSize').parent().find('.text-measure').text();
                var height = $('#vSize').val() + $('#vSize').parent().find('.text-measure').text();
                var zoom = map.getZoom();

                appendToolContent('<div xe-tool-id="editortool/googlemap@googlemap" id="googlemap_' + uuid + '" contenteditable="true" data-googlemap data-text="' + text + '" data-lat="' + lat + '" data-lng="' + lng + '" data-zoom="' + zoom + '" style="width:' + width + ';height:' + height + '"></div>', function() {
                    $(editorDoc.getElementById('googlemap_' + uuid)).renderer({win: editorWindow});
                    self.close();
                });
            },
            checkFullWidth: function() {
                var $this = $(this);

                if($this.prop('checked')) {
                    $('#hSize').prop('disabled', true);
                    $('#hSize').val(100);
                    $('#hSize').parent().find('.text-measure').text('%');
                }else {
                    $('#hSize').prop('disabled', false);
                    $('#hSize').val('');
                    $('#hSize').parent().find('.text-measure').text('px');
                }
            },
            isValid: function() {
                if($('#content').val() === '') {
                    alert('Marker 표시 내용을 입력하세요.');
                    return false;
                }else if($('#hSize').val() === '') {
                    alert('가로 넓이를 입력하세요.');
                    return false;
                }else if($('#vSize').val() === '') {
                    alert('세로 넓이를 입력하세요.');
                    return false;
                }

                return true;
            }
        }
    })().init();

</script>