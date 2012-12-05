/* OpenLayers configuration script */

function initOL()
{
   // Configuration option -----------------------------------------------------
   OpenLayers.IMAGE_RELOAD_ATTEMPTS = 3;

   // Default values -----------------------------------------------------------
   /*
   var hostName = "http://192.168.0.102/~gba" ;
   var urlTC = [hostName + "/cgi-wmsc/tilecache.cgi?"];
   var urlMS = [hostName + "/cgi-bin/netherlands.cgi?"];
   */
   var urlTC = ["http://sig1.demo.makina-corpus.com/cgi-wmsc/tilecache.cgi?",
                "http://sig2.demo.makina-corpus.com/cgi-wmsc/tilecache.cgi?",
                "http://sig3.demo.makina-corpus.com/cgi-wmsc/tilecache.cgi?",
                "http://sig4.demo.makina-corpus.com/cgi-wmsc/tilecache.cgi?"];
   var urlMS = ["http://sig.demo.makina-corpus.com/cgi-bin/mapserv?map=/var/www/sig.demo.makina-corpus.com/www/ol_and/netherlands.map&"];
   var layersTC = {main: "ANDtransportation", overview: "ANDoverviewmap"};
   var layersMS = {main: "ANDbackground,ANDroadgroup,ANDlocation", overview: "ANDbackground,ANDroadgroup"};

   // Map options --------------------------------------------------------------
   var optionsSet = {
      maxExtent: new OpenLayers.Bounds(314.29, 297853.59, 278089.68, 634281.67),
      resolutions: [1152, 576, 288, 144, 72, 36, 18, 9, 6, 3, 1.5, 0.75],
      units: "m",
      tileSize: new OpenLayers.Size(256, 256),
      projection: "EPSG:28992",
      theme: "./include/customOL.css",
      controls: []
   }
   var overviewOptionsSet = {
      maxExtent: new OpenLayers.Bounds(314.29, 297853.59, 278089.68, 634281.67),
      resolutions: [9216, 4608, 2304, 1152, 576, 288, 144, 72, 36, 18, 9, 6],
      units: "m",
      tileSize: new OpenLayers.Size(64, 64),
      projection: "EPSG:28992",
      theme: "./include/customOL.css",
      controls: []
   }

   // Map definition -----------------------------------------------------------
   var map = new OpenLayers.Map($("map"), optionsSet);

   // Layers definition ----------------------------------------------------
   var cachedLayers = new OpenLayers.Layer.WMS("Netherlands road map", urlTC, {layers: layersTC.main, format: "image/png"}, {isBaseLayer: true, reproject: false});
   var overviewLayer = new OpenLayers.Layer.WMS("Overview map", urlTC, {layers: layersTC.overview, format: "image/png"}, {isBaseLayer: true, reproject: false});

   // Map-Layers binding -------------------------------------------------------
   map.addLayer(cachedLayers);

   // UI controls --------------------------------------------------------------
   map.addControl( new OpenLayers.Control.MouseDefaults() );
   map.addControl( new OpenLayers.Control.PanZoomBar() );
   map.addControl( new OpenLayers.Control.MousePosition() );
   map.addControl( new OpenLayers.Control.Scale() );
   map.addControl( new OpenLayers.Control.OverviewMap({ div: $("overviewBody"), layers: [overviewLayer], mapOptions: overviewOptionsSet }) );

   // Prepare map for output ---------------------------------------------------
   map.setCenter( new OpenLayers.LonLat(122000, 488000), 4, false, false );

   return true;
}
