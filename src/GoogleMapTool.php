<?php

namespace Xpressengine\Plugins\GoogleMapTool;
use App\Facades\XeFrontend;
use Illuminate\Contracts\Auth\Access\Gate;
use Xpressengine\Config\ConfigManager;
use Xpressengine\Editor\AbstractTool;
use Xpressengine\Permission\Instance;

class GoogleMapTool extends AbstractTool
{
    protected $configs;

    protected $gate;

    public function __construct(ConfigManager $configs, Gate $gate, $instanceId)
    {
        parent::__construct($instanceId);

        $this->configs = $configs;
        $this->gate = $gate;
    }

    public function initAssets()
    {
        $config = $this->configs->getOrNew('google_map_tool');

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
        XeFrontend::js(asset($this->getAssetsPath() . '/googleMapTool.js?key=' . $config->get('key')))->load();
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
        $config = $this->configs->getOrNew('google_map_tool');

        //TODO:: api key
        XeFrontend::js('http://maps.googleapis.com/maps/api/js?key=' . $config->get('key'))->load();
        XeFrontend::html('google_map_tool.render')->content("
        <script>
            function googleMapRender(dom)
            {
                var lat = $(dom).data('lat');
                var lng = $(dom).data('lng');
                var text = $(dom).data('text');
            
                var map = new google.maps.Map(dom, {
                    center: new google.maps.LatLng(lat, lng),
                    zoom: 10,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });
            
                var myLatLng = new google.maps.LatLng(lat, lng);
                var marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map
                });
            
                var infowindow = new google.maps.InfoWindow({
                    content: text
                });
            
                infowindow.open(map, marker);
            }
            
            
            $(function() {
                $('[data-googlemap]').each(function () {
                    googleMapRender(this);
                });
            });
        </script>
        ")->load();

        return $content;
        
    }
    private function getAssetsPath()
    {
        return str_replace(base_path(), '', realpath(__DIR__ . '/../assets'));
    }
}