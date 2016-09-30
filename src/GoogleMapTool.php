<?php
namespace Xpressengine\Plugins\GoogleMapTool;
use App\Facades\XeFrontend;
use Illuminate\Contracts\Auth\Access\Gate;
use Xpressengine\Editor\AbstractTool;
use Xpressengine\Permission\Instance;
class GoogleMapTool extends AbstractTool
{
    protected $gate;
    public function __construct(Gate $gate, $instanceId)
    {
        parent::__construct($instanceId);
        $this->gate = $gate;
    }
    public function initAssets()
    {
        XeFrontend::html('google_map_tool.load_url')->content("
        <script>
            (function() {
            
                var _url = {
                    popup: '".route('google_map_tool::popup')."'                
                };
            
                var URL = {
                    get: function (type) {
                        return _url[type];                 
                    }
                };
                
                window.googleToolURL = URL;
            })();
        </script>
        ")->load();
        XeFrontend::js(asset($this->getAssetsPath() . '/googleMapTool.js'))->load();
    }
    public function getIcon()
    {
        return asset($this->getAssetsPath() . '/icon.png');
    }

    public static function getInstanceSettingURI($instanceId)
    {
        return route('settings.plugin.google_map_tool.setting', $instanceId);
    }
    public static function getKey($instanceId)
    {
        return static::getId() .  '.' . $instanceId;
    }
    public function compile($content)
    {

        //TODO:: api key
        XeFrontend::js('http://maps.googleapis.com/maps/api/js?key=AIzaSyBYz-hHmnLkZszDc-DeKoFplyBSrjrEsao')->load();
        
    }
    private function getAssetsPath()
    {
        return str_replace(base_path(), '', realpath(__DIR__ . '/../assets'));
    }
}