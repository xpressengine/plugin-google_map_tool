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



<!--
 기본 좌표, 줌 레벨
 -->