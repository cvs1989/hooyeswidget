<?php
 $mapx=$_GET["x"];
 $mapy=$_GET["y"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--IE下用静态icon-->
<link rel="icon" href="favicon.ico" type="image/x-icon" />
<!--FF下可用gif动态icon有-->
<link rel="icon" href="favicon.gif" type="image/gif" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link rel="bookmark" href="favicon.ico" type="image/x-icon" />
<title>map</title>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=ABQIAAAA6Jp07s_8CvfZulmIh1H7-BTuXyOUnH9ME_Vx0Y9eV8az_KJ8OBRQi9YKM0lPHlDKNbam2O4YhISYCA" type="text/javascript"></script>
<script type="text/javascript">
    //<!--

    function initialize() {

        if (GBrowserIsCompatible()) {
            var map = new GMap2(document.getElementById("map_canvas"));
            map.setMapType(G_HYBRID_MAP);
            map.setUIToDefault();

            var center = new GLatLng(<?php echo $mapx.",".$mapy ?>);
            //map.setCenter(new GLatLng(37.339085, -121.8914807), 18);
            var marker = new GMarker(center, { draggable: false });
            var topRight = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(10, 10));
            map.setCenter(center, 17);
            map.addControl(new GLargeMapControl(), topRight);
            map.addControl(new GOverviewMapControl());
            map.enableRotation();

            map.addOverlay(marker);
            GEvent.addListener(marker, "click", function () {
                marker.openInfoWindowHtml("hi hooyes");
            });
            marker.openInfoWindowHtml("hi hooyes");
        }
    }

    window.onload = function () {
        initialize();
    };
    //-->
</script>
</head>
<body>
<div id="map_canvas" style="height:400px; width:900px;">loading..</div>
</body>
</html>
