<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leaflet Map Generator / by mikeott</title>
    <link rel="stylesheet" href="css/style.css?v=1.0" type="text/css" media="all" />
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
        'google'                => 'Map data &copy; ' . date('Y') . '&nbsp; Google &nbsp; <a href="https://www.google.com/intl/en-GB_US/help/terms_maps.html" target="_blank" rel="noopener">Terms of use</a>',
        'openstreetmap'         => '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        'openstreetmap_bw'      => '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        'opentopmap'            => 'Map data: &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>',
        'hydda_base'            => 'Tiles courtesy of <a href="http://openstreetmap.se/" target="_blank">OpenStreetMap Sweden</a> &mdash; Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        'watercolor'            => 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        'esri'                  => 'Tiles &copy; Esri &mdash; Source: Esri, DeLorme, NAVTEQ, USGS, Intermap, iPC, NRCAN, Esri Japan, METI, Esri China (Hong Kong), Esri (Thailand), TomTom, ' . date('Y'),
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
        $attribution = 'Map data &copy; ' .  date('Y') . '&nbsp; Google &nbsp; <a href="https://www.google.com/intl/en-GB_US/help/terms_maps.html" target="_blank" rel="noopener">Terms of use</a>';
    }

    $lat = $_POST['latitude'];
    $lng = $_POST['longitude'];

    $zoom_level = isset($_POST['zoom_level']);
    if($zoom_level) {
        $zoom_level = $_POST['zoom_level'];
    } else {
        $zoom_level = 14;
    }

    $full_screen = isset($_POST['full_screen']);
    if($full_screen) {
        $full_screen_css = "\r<!--/ Start Fullscreen Addon: https://github.com/brunob/leaflet.fullscreen /-->\r<style>.leaflet-control-fullscreen-button { background: #fff url('https://leaflet.github.io/Leaflet.fullscreen/dist/fullscreen.png'); background-position: 50% 2px !important; } .leaflet-fullscreen-on .leaflet-control-fullscreen-button { background-position: 50% -24px !important; }</style>";
        $full_screen_js = '<script src="https://leaflet.github.io/Leaflet.fullscreen/dist/Leaflet.fullscreen.min.js"></script>';
        $full_screen_init = "/* Full Screen: https://github.com/brunob/leaflet.fullscreen */\r";
        $full_screen_init .= "  fullscreenControl: true,\r";
        $full_screen_init .= "  fullscreenControlOptions: {\r";
        $full_screen_init .= "  position: 'bottomright'\r";
        $full_screen_init .= "},";
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

    $satellite_view = isset($_POST['satellite_view']);	
    if($satellite_view) {
        $satellite_view = "\r\r/* Satellite view */\r";
        $satellite_view .= "var default_view = L.tileLayer('" . $theme . "')\r";
        $satellite_view .= "satellite_view = L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}')\r";

        $satellite_view .= "var baseMaps = {\r";
        $satellite_view .= "    'Default': default_view,\r";
        $satellite_view .= "    'Satellite': satellite_view\r";
        $satellite_view .= "};\r";
        $satellite_view .= "var overlays =  {\r";
        $satellite_view .= "};\r";
        $satellite_view .= "L.control.layers(baseMaps,overlays, {position: 'bottomleft'}).addTo(map);\r";
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
    } else {
        $marker_image = '';
        $marker_default = '';
    }
?>
<textarea id="the_code" spellcheck="false">
<?php echo htmlspecialchars('<!--/ Start Leaflet Map /-->'); ?>

<?php echo htmlspecialchars('<div id="leaflet" style="width:' . $width . 'px; height:' . $height . 'px"></div><!--/ Remove inline width and height, declare in your stylesheet instead. /-->'); ?>

<?php echo htmlspecialchars('<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" />'); ?>
    
<?php echo htmlspecialchars('<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"></script>'); ?>

<?php if($marker) {
    echo '<style>.leaflet-shadow-pane img { display: none !important; } .leaflet-marker-pane img:first-child { display: none !important; } </style>';
} ?>

<?php echo htmlspecialchars($full_screen_css); ?>

<?php echo htmlspecialchars($full_screen_js); ?>

<?php echo htmlspecialchars('<script>'); ?>

<?php echo htmlspecialchars('jQuery( document ).ready(function() {'); ?>

    <?php echo htmlspecialchars("var map = new L.Map('leaflet', {
        center: [" . $lat . ", " . $lng . "],
        zoom: " . $zoom_level . ",
        scrollWheelZoom: " . $scrollwheel . ",
        zoomControl: false,
        keyboard: true,
        dragging: true,
        zoomControl: false,
        tap: false,
        touchZoom: true,
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

    <?php echo htmlspecialchars($popup); ?>

    <?php echo htmlspecialchars($marker_image); ?>

    <?php echo htmlspecialchars($satellite_view); ?>

    <?php echo htmlspecialchars('});'); ?>

    <?php if($satellite_view) {
        echo htmlspecialchars("\r<!--/ Auto check first radio button /-->\rjQuery( document ).ready(function() { \r     jQuery('.leaflet-control-layers-base label:nth-child(1) .leaflet-control-layers-selector').prop('checked', true); \r});");
    }
    ?>

    <?php echo htmlspecialchars('</script>'); ?>

<?php echo htmlspecialchars('<!--/ End Leaflet Map /-->'); ?>
</textarea>
<span class="copy">Copy</span>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="mask"></a>
<?php } ?>

    
    <form action="" method="POST">
        <h1>
            Leaflet Map Generator
            <span style="display:block; font-size:.4em;">jQuery Required</span>
        </h1>

        <div class="form-content">
            <p>
                <label>Latitude</label>
                <input type="text" name="latitude" value="-31.945864953664223" required />
            </p>
            <p>
                <label>Longitude</label>
                <input type="text" name="longitude" value="115.86781662580758" required />
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
                <input type="number" name="zoom_level" min="1" max="18" step="1" value="14" />
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
                    <input type="checkbox" name="satellite_view" id="satellite_view" />
                    <label for="satellite_view">Satellite view control</label>
                </span>
            </p>
            <p>
                <span class="valign">
                    <input type="checkbox" name="scrollwheel" id="scrollwheel" />
                    <label for="scrollwheel">Enable scrollwheel</label>
                </span>
            </p>
            <p>
                <span class="valign">
                    <input type="checkbox" name="popup" id="popup" />
                    <label for="popup">Include marker popup</label>
                </span>
            </p>
            <p>
                <span class="valign">
                    <input type="checkbox" name="marker" id="marker" />
                    <label for="marker">Custom marker image</label>
                </span>
            </p>
            <p class="full-width hidden image-path">
                <label>Custom marker image path (absolute or relative)</label>
                <input type="text" name="marker_image" id="marker_image" />
            </p>
            <p class="full-width hidden popup-text">
                <label>Popup text (HTML allowed)</label>
                <textarea name="popup_text" id="popup_text"></textarea>
            </p>
        </div>

        <input type="submit" name="submit" class="submit" value="Generate Code" />

        <div class="theme-image">
            <img src="images/google.png" id="theme_image" />
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
            $('#theme_image').attr('src', 'images/'+$('#theme').val() + '.png');
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

            /* Work around the Chrome problem */
            textBox.onmouseup = function() {
                /* Prevent further mouseup intervention */
                textBox.onmouseup = null;
                return false;
            };
        };
    </script>

</body>
</html>