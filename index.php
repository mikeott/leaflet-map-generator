<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Leaflet Map Generator</title>
    <link rel="stylesheet" href="style.css?v=1.1" type="text/css" media="all" />
</head>
<body>

<?php if(isset($_POST['submit'])) { 

    $map_theme = array(
        'google'                => 'https://mt0.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',
        'openstreetmap'         => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        'openstreetmap_bw'      => 'http://{s}.tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png',
        'opentopmap'            => 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png',
        'hydda_base'            => 'https://{s}.tile.openstreetmap.se/hydda/base/{z}/{x}/{y}.png',
        'watercolor'            => 'https://stamen-tiles-{s}.a.ssl.fastly.net/watercolor/{z}/{x}/{y}.png',
        'esri'                  => 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}',
        'positron'              => 'https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}{r}.png',
        'voyager'               => 'https://cartodb-basemaps-{s}.global.ssl.fastly.net/rastertiles/voyager/{z}/{x}/{y}{r}.png',
        'voyager_no_labels'     => 'https://cartodb-basemaps-{s}.global.ssl.fastly.net/rastertiles/voyager_nolabels/{z}/{x}/{y}{r}.png',
        'voyager_labels_under'  => 'https://cartodb-basemaps-{s}.global.ssl.fastly.net/rastertiles/voyager_labels_under/{z}/{x}/{y}{r}.png'
    );

    $attribution = array(
        'google'                => 'Map data &copy;2018 Google &nbsp;&nbsp; <a href="https://www.google.com/intl/en-GB_US/help/terms_maps.html" target="_blank" rel="noopener">Terms of use</a>',
        'openstreetmap'         => '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        'openstreetmap_bw'      => '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        'opentopmap'            => 'Map data: &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>',
        'hydda_base'            => 'Tiles courtesy of <a href="http://openstreetmap.se/" target="_blank">OpenStreetMap Sweden</a> &mdash; Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        'watercolor'            => 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        'esri'                  => 'Tiles &copy; Esri &mdash; Source: Esri, DeLorme, NAVTEQ, USGS, Intermap, iPC, NRCAN, Esri Japan, METI, Esri China (Hong Kong), Esri (Thailand), TomTom, 2012',
        'positron'              => '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="http://cartodb.com/attributions">CartoDB</a>',
        'voyager'               => '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="http://cartodb.com/attributions">CartoDB</a>',
        'voyager_no_labels'     => '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="http://cartodb.com/attributions">CartoDB</a>',
        'voyager_labels_under'  => '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="http://cartodb.com/attributions">CartoDB</a>'
    );

    $width = $_POST['width'];	
    $height = $_POST['height'];	

    $theme = isset($_POST['theme']);	
    if($theme) {
        $theme = $map_theme[$_POST['theme']];
        $attribution = $attribution[$_POST['theme']];
    } else {
        $theme = 'https://mt0.google.com/vt/lyrs=m&x={x}&y={y}&z={z}';
        $attribution = 'Map data &copy;2018 Google &nbsp;&nbsp; <a href="https://www.google.com/intl/en-GB_US/help/terms_maps.html" target="_blank" rel="noopener">Terms of use</a>';
    }

    $lat = $_POST['latitude'];
    $lng = $_POST['longitude'];

    $zoom_level = isset($_POST['zoom_level']);
    if($zoom_level) {
        $zoom_level = $_POST['zoom_level'];
    } else {
        $zoom_level = 9;
    }

    $full_screen = isset($_POST['full_screen']);
    if($full_screen) {
        $full_screen_css = "<style>.leaflet-control-zoom-fullscreen { background-image: url('https://raw.githubusercontent.com/brunob/leaflet.fullscreen/master/icon-fullscreen.png');}</style>";
        $full_screen_js = '<script src="https://unpkg.com/leaflet.fullscreen@1.4.5/Control.FullScreen.js"></script>';
        $full_screen_init = "/* Full Screen: https://github.com/brunob/leaflet.fullscreen */\r";
        $full_screen_init .= "        fullscreenControl: true,\r";
        $full_screen_init .= "        fullscreenControlOptions: {\r";
        $full_screen_init .= "            position: 'bottomright'\r";
        $full_screen_init .= "        },";
    } else {
        $full_screen_css = '';
        $full_screen_js = '';
        $full_screen_init = '';
    }

    $scrollwheel = isset($_POST['scrollwheel']);	
    if ($scrollwheel) {
        $scrollwheel = 'true';
    } else {
        $scrollwheel = 'false';
    }

    $popup = isset($_POST['popup']);	
    $popup_text = $_POST['popup_text'];
    if($popup_text) {
        $popup_text = $popup_text;
    } else {
        $popup_text = '';
    }
    if($popup) {
        $popup = "\r\r/* Popup */\r";
        $popup .= "L.marker([" . $lat . ", " . $lng . "])\r";
        $popup .= ".addTo(map)\r";
        $popup .= ".bindPopup('" . $popup_text . "')\r";
        $popup .= ".openPopup();\r";
    } else {
        $popup = '';
    }

    $marker = isset($_POST['marker']);
    $marker_image_url = $_POST['marker_image'];
    if($marker) {
        $marker_image = "\r\r/* Custom map marker */\r";
        $marker_image .= "var mapIcon = L.Icon.extend({\r";
        $marker_image .= "  options: {\r";
        $marker_image .= "  iconSize: [50, 67],\r";
        $marker_image .= "      iconAnchor: [22, 64]\r";
        $marker_image .= "  }\r";
        $marker_image .= "});\r";
        $marker_image .= "var orangeIcon = new mapIcon({iconUrl: '" . $marker_image_url . "'});\r";
        $marker_image .= "L.marker([" . $lat . ", " . $lng . "], {icon: orangeIcon}).addTo(map);\r";
        $marker_default = "/* Remove default marker */\r";
        $marker_default .= "    map.removeLayer(marker);";
    } else {
        $marker_image = '';
        $marker_default = '';
    }
?>
<textarea id="the_code" spellcheck="false">
<?php echo htmlspecialchars('<!--/ Start Leaflet Map /-->'); ?>

<?php echo htmlspecialchars('<div id="leaflet" style="width:' . $width . 'px; height:' . $height . 'px"></div><!--/ Remove inline width and height, declare in your stylesheet instead. /-->'); ?>

<?php echo htmlspecialchars('<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" />'); ?>
    
<?php echo htmlspecialchars('<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"></script>');  ?>

<?php echo htmlspecialchars($full_screen_css);  ?>

<?php echo htmlspecialchars($full_screen_js);  ?>

<?php echo htmlspecialchars('<script>');  ?>

<?php echo htmlspecialchars('$( document ).ready(function() {');  ?>

    <?php echo htmlspecialchars("var map = new L.Map('leaflet', {
        center: [" . $lat . ", " . $lng . "],
        zoom: " . $zoom_level . ",
        scrollWheelZoom: " . $scrollwheel . ",
        zoomControl: false,
        keyboard: true,
        " . $full_screen_init . "
        layers: [
            new L.TileLayer('" . $theme . "', {
                'attribution': '" . $attribution . "'
            })
        ],
    });

    " . $marker_default . "

    /* Map marker position */
    L.marker([" . $lat . ", " . $lng . "]).addTo(map);

    /* Show scale ruler */
    L.control.scale().addTo(map);

    /* Zoom control position */
    L.control.zoom({
        position:'bottomright'
    }).addTo(map);"); ?>

    <?php echo htmlspecialchars($popup);  ?>

    <?php echo htmlspecialchars($marker_image);  ?>

<?php echo htmlspecialchars('});');  ?>

<?php echo htmlspecialchars('</script>');  ?>

<?php echo htmlspecialchars('<!--/ End Leaflet Map /-->'); ?>
</textarea>
<span class="copy">Copy</span>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="mask"></a>
<?php } ?>

    
    <form action="" method="POST">
        <h1>Leaflet Map Generator</h1>

        <div class="form-content">
            <p>
                <label>Latitude</label>
                <input type="text" name="latitude" value="0-31.945816" required />
            </p>
            <p>
                <label>Longitude</label>
                <input type="text" name="longitude" value="115.867818" required />
            </p>
            <p>
                <label>Width</label>
                <input type="number" name="width" value="800" required />
            </p>
            <p>
                <label>Height</label>
                <input type="number" name="height" value="600" required />
            </p>
            <p>
                <label>Zoom level</label>
                <input type="number" name="zoom_level" min="1" max="18" step="1" value="9" />
            </p>
            <p>
                <label>Theme</label>
                <select name="theme" id="theme">
                    <option value="google">Google</option>
                    <option value="openstreetmap">Open Street Map</option>
                    <option value="opentopmap">Open to Map</option>
                    <option value="hydda_base">Hydra Base</option>
                    <option value="watercolor">Water Colour</option>
                    <option value="esri">Esri</option>
                    <option value="positron">Positron</option>
                    <option value="voyager">Voyager</option>
                    <option value="voyager_no_labels">Voyager (no labels)</option>
                </select>
            </p>
            <p>
                <span class="valign">
                    <input type="checkbox" name="full_screen" id="full_screen" />
                    <label for="full_screen">Full screen control</label>
                </span>
            </p>
            <p>
                <span class="valign">
                    <input type="checkbox" name="scrollwheel" id="scrollwheel" />
                    <label for="scrollwheel">Scrollwheel</label>
                </span>
            </p>
            <p>
                <span class="valign">
                    <input type="checkbox" name="popup" id="popup" />
                    <label for="popup">Popup</label>
                </span>
            </p>
            <p>
                <span class="valign">
                    <input type="checkbox" name="marker" id="marker" />
                    <label for="marker">Custom marker</label>
                </span>
            </p>
            <p class="full-width hidden image-path">
                <label>Custom marker image path</label>
                <input type="text" name="marker_image" id="marker_image" />
            </p>
            <p class="full-width hidden popup-text">
                <label>Popup text</label>
                <input type="text" name="popup_text" id="popup_text" />
            </p>
        </div>

        <input type="submit" name="submit" class="submit" value="Generate Code" />

        <div class="theme-image">
            <img src="google.png" id="theme_image" />
        </div>

    </form>

    <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js'></script>
    <script>
        $('#marker').change(function(){
            if ($(this).is(':checked')) {
                $('.image-path').fadeIn();
                $('#marker_image').attr('required', 'required');

            } else {
                $('.image-path').fadeOut();
                $('#marker_image').removeAttr('required');
            }
        });

        $('#popup').change(function(){
            if ($(this).is(':checked')) {
                $('.popup-text').fadeIn();
                $('#popup_text').attr('required', 'required');

            } else {
                $('.popup-text').fadeOut();
                $('#popup_text').removeAttr('required');
            }
        });

        $('#theme').change(function(){
            $('#theme_image').attr('src', $('#theme').val() + '.png');
        });

        $('.copy').click(function() {
            $('#the_code').select();
            $(this).html('Copied!' + ' &#10004');
            $(this).addClass('copied');
            document.execCommand('copy');
        });
        
        var textBox = document.getElementById('the_code');
        textBox.onfocus = function() {
            textBox.select();

            // Work around Chrome's little problem
            textBox.onmouseup = function() {
                // Prevent further mouseup intervention
                textBox.onmouseup = null;
                return false;
            };
        };
    </script>

</body>
</html>