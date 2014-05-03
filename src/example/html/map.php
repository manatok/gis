<!DOCTYPE html>
<html>
  <head>
    <title>GIS MapTile example</title>
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script>
/**
 * Edit this to your domain
 */
var basePath = 'http://gis.dev/src/example/index.php';


/**
 * @constructor
 * @implements {google.maps.MapType}
 */
function CoordMapType() {}
CoordMapType.prototype.tileSize = new google.maps.Size(256,256);
CoordMapType.prototype.maxZoom = 19;

CoordMapType.prototype.getTile = function(coord, zoom, ownerDocument) {
  var oImg=ownerDocument.createElement("img");
  oImg.setAttribute('src', basePath+'?action=draw&w='+this.tileSize.width+'&h='+this.tileSize.height+'&x='+coord.x+'&y='+coord.y+'&z='+zoom);
  oImg.setAttribute('height', this.tileSize.height+'px');
  oImg.setAttribute('width', this.tileSize.width+'px');
  return oImg;
};

var map;
var centre = new google.maps.LatLng(80,-80);
var coordinateMapType = new CoordMapType();

function initialize() {
  var mapOptions = {
    zoom: 3,
    center: centre,
    streetViewControl: false,
    mapTypeId: 'coordinate',
    mapTypeControlOptions: {
      mapTypeIds: ['coordinate', google.maps.MapTypeId.ROADMAP],
      style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
    }
  };
  map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);

  // Now attach the coordinate map type to the map's registry
  map.mapTypes.set('coordinate', coordinateMapType);
}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>
  </head>
  <body>
    <div id="map-canvas"></div>
  </body>
</html>