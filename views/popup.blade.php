<style>
    #container {margin: 0 auto; width: 500px; margin-top:15px;}
    #mapWrapper { width: 500px; height: 500px;}
    #content {width: 100%; height: 80px;}
</style>
<!-- TODO:: api key 백엔드에서 받아야함 -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-2.2.4.min.js"   integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="   crossorigin="anonymous"></script>
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBYz-hHmnLkZszDc-DeKoFplyBSrjrEsao"></script>

<div id="container">
    <div id="mapWrapper"></div>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">
                <label for="content">Marker 내용 <small class="text-primary">(HTML사용 가능)</small></label>
                <textarea id="content" placeholder="" ></textarea>
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
    <div>
        <input type="hidden" id="markerText" />
        <input type="hidden" id="latitude" />
        <input type="hidden" id="longitude" />
    </div>
</div>


<script type="text/javascript">

    var defaultText = '입력하세요';
    var text = '';
    var map = new google.maps.Map(document.getElementById('mapWrapper'), {
        center: new google.maps.LatLng('37.566535', '126.97796919999996'),
        zoom: 10,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var myLatLng = new google.maps.LatLng('37.566535', '126.97796919999996');
    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map
    });

    var infowindow = new google.maps.InfoWindow({
        content: defaultText
    });

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

    infowindow.open(map, marker);

    google.maps.event.addListener(map, 'click', function(event) {
        marker.setPosition(event.latLng);
        map.setCenter(event.latLng);
    });

    google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(map, marker);
    });

    $('#content').on('keyup', function(e) {
        var $this = $(this);

        text = $this.val();

        if($this.val() === '') {
            infowindow.setContent(defaultText);
        }else {
            infowindow.setContent(text);
        }
    });

    $(window).on('load', function() {
        if(!self.appendToolContent) {
            alert('팝업을 재실행 하세요.');
            self.close();
        }
    });

    $('#btnAppendToEditor').on('click', function() {
        var lat = marker.getPosition().lat();
        var lng = marker.getPosition().lng();

        var editorDoc = self.targetEditor.document.$;
        var editorWindow = self.targetEditor.window.$;
        var uuid = _generateUUID();

        appendToolContent('<div id="googlemap_' + uuid + '" contenteditable="true" data-googlemap data-text="' + text + '" data-lat="' + lat + '" data-lng="' + lng + '" style="width:100%;height:300px;"></div>', function() {

            var loadCallback = function() {

                var map = new editorWindow.google.maps.Map(editorDoc.getElementById('googlemap_' + uuid), {
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

                self.close();

            };

            if(editorWindow.google) {
                loadCallback();
            }else {
                _jsLoad(editorDoc, 'http://maps.googleapis.com/maps/api/js?key=AIzaSyBYz-hHmnLkZszDc-DeKoFplyBSrjrEsao', loadCallback);
            }
        });
    });

</script>