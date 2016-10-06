(function ($) {
    var scripts = document.getElementsByTagName('script');
    var lastScript = scripts[scripts.length-1];
    var scriptName = lastScript.src;

    var _jsLoad = function(targetDoc, src, load, error) {
        var el = targetDoc.createElement('script');

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

    var getParam = function (name) {
        var qs = scriptName.replace(/^[^\?]+\??/, '');

        return (function (query) {
            var params = {};

            if (!query) {
                return name ? null : {};
            }

            var pairs = query.split(/[;&]/);
            for (var i = 0; i < pairs.length; i++) {
                var KeyVal = pairs[i].split('=');
                if (!KeyVal || KeyVal.length != 2) {
                    continue;
                }
                var key = unescape(KeyVal[0]);
                var val = unescape(KeyVal[1]);
                val = val.replace(/\+/g, ' ');
                params[key] = val;
            }
            return name ? params[name] : params;
        })(qs);
    };
    
    $.fn['renderer'] = function (options) {
        var options = options || {},
            win = options.win || window,
            callback = options.callback || function () {},
            $tar = this instanceof jQuery ? this : $(this);

        var render = function (tar, win, callback) {
            var lat = $(tar).data('lat');
            var lng = $(tar).data('lng');
            var text = $(tar).data('text').toString();
            var zoom = $(tar).data('zoom') || 10;

            var map = new win.google.maps.Map(tar, {
                center: new win.google.maps.LatLng(lat, lng),
                zoom: zoom,
                mapTypeId: win.google.maps.MapTypeId.ROADMAP
            });

            var myLatLng = new win.google.maps.LatLng(lat, lng);
            var marker = new win.google.maps.Marker({
                position: myLatLng,
                map: map
            });

            var infowindow = new win.google.maps.InfoWindow({
                content: text
            });

            infowindow.open(map, marker);

            callback(tar);
        };

        var act = function () {
            $tar.each(function () {
                render(this, win, callback);
            });
        };

        if(win.google) {
            act();
        } else {
            _jsLoad(win.document, 'http://maps.googleapis.com/maps/api/js?key=' + getParam('key'), act);
        }
    }
})(jQuery);
