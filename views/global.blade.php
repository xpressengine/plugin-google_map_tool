{{
XeFrontend::js([
    'http://maps.googleapis.com/maps/api/js?key=' . $config->get('key'),
    asset('plugins/google_map_tool/assets/googleMapRenderer.js?key=' . $config->get('key'))
])->load()
}}

@section('page_title')
    <h2>Editor 구글맵 설정</h2>
@endsection

@section('page_description')
    <small>구글맵 설정페이지 입니다.</small>
@endsection

@section('content_bread_crumbs')

@endsection

<div class="panel-group" role="tablist" aria-multiselectable="true">
    <div class="panel">
        <div class="panel-heading">
            <div class="pull-left">
                <h3 class="panel-title">전역 설정</h3>
            </div>
        </div>
        <div class="panel-collapse collapse in">
            <form method="post" action="{{ route('settings.plugin.google_map_tool.global') }}">
                {{ csrf_field() }}
                <div class="panel-body">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="clearfix">
                                    <label>API Key</label>
                                </div>
                                <input type="text" class="form-control" name="key" value="{{ $config->get('key') }}">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="clearfix">
                                    <label>
                                        기본지도 설정
                                        <small>지도가 보여질 최초 좌표와 줌 레벨을 설정합니다.</small>
                                    </label>
                                </div>
                                <div id="map-wrapper" style="height: 400px"></div>
                                <input type="hidden" name="lat" value="{{ $config->get('lat', '37.566535') }}">
                                <input type="hidden" name="lng" value="{{ $config->get('lng', '126.97796919999996') }}">
                                <input type="hidden" name="zoom" value="{{ $config->get('zoom', 10) }}">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="panel-footer">
                    <div class="pull-right">
                        <button type="submit" class="btn btn-primary">저장</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        var lat = $('input[name="lat"]').val(),
            lng = $('input[name="lng"]').val(),
            myLatLng = new google.maps.LatLng(lat, lng);

        map = new google.maps.Map(document.getElementById('map-wrapper'), {
            center: myLatLng,
            zoom: parseInt($('input[name="zoom"]').val()),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var marker = new google.maps.Marker({
            position: myLatLng,
            title: 'Move!',
            map: map
        });

        google.maps.event.addListener(map, 'center_changed', function (event) {
            marker.setPosition(map.getCenter());

            $('input[name="lat"]').val(map.getCenter().lat());
            $('input[name="lng"]').val(map.getCenter().lng());
            $('input[name="zoom"]').val(map.getZoom());
        });
    });
</script>

<!--
 기본 좌표, 줌 레벨
 -->