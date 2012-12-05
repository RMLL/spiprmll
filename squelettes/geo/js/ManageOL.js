/* Copyright (c) 2007 Makina Corpus, published under a modified BSD license.
 * Sylvain Beorchia - sylvain.beorchia@makina-corpus.net
 * 3.10.2007
 */

/**
 * Base class to manage OpenLayers
 *
 * @class
 */

var ManageOL = OpenLayers.Class({
 
    /**
    * Initial longitude if no extent is given
    */
    initLon: -0.497818,
    /**
    * Initial latitude if no extent is given
    */
    initLat: 43.892697,
    /**
    * Initial zoom if no extent is given
    */
    initZoom: 0,
    /**
    * OpenLayers Map Object
    */
    map: null,
    /**
    * Layer for polygons coming from the GeoRSS
    */
    georssVectorLayer: null,
    markers:null,
    cineLayer:null,
    aftercineLayer:null,
    popup:null,
    gdir:null,
    /**
    *
    */
    //flux: null,

    /**
    * @constructor
    *
    * @param {String} mode : edit or view
    */
    initialize: function(extent) {

        OpenLayers.IMAGE_RELOAD_ATTEMPTS = 3;

        var options = {numZoomLevels: 18, 
		controls: []
		};
        this.map = new OpenLayers.Map('map', options);


        // Add Google base layers to the map
        this.addBaseLayers();
	
	this.markersTGV = new OpenLayers.Layer.Markers( "Gares" , {legendurl:"./img/icon_gare.png",maxScale:800000,minScale:10000000,visibility:false});
    this.markersAvion = new OpenLayers.Layer.Markers( "Aéroport" , {legendurl:"./img/icon_aeroport.png",maxScale:800000,minScale:10000000,visibility:false});

	var size = new OpenLayers.Size(30,30);
    var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
    var iconTGV = new OpenLayers.Icon('img/icon_gare.png',size,offset);
	var sizeAvion = new OpenLayers.Size(30,30);
    var offsetAvion = new OpenLayers.Pixel(-(sizeAvion.w/2), -sizeAvion.h);
	var iconAvion = new OpenLayers.Icon('img/icon_aeroport.png',sizeAvion,offsetAvion);


	
	var sizeMDM = new OpenLayers.Size(150,51);
    var offsetMDM = new OpenLayers.Pixel(-((sizeMDM.w/2)-17), -sizeMDM.h);
	var iconMDM = new OpenLayers.Icon('img/MontMarsan5.png',sizeMDM,offsetMDM);

	
	
	
	marker=new OpenLayers.Marker(new OpenLayers.LonLat(-1.06018,43.62),iconTGV);
	var params={
	marker:marker,
	context:this,
	lieu:"Place de la gare,Dax"
	,txtContent:"<span class='title_popup'>DAX : Gare</span><span class='content_popup'>Dax - Mont de Marsan : 54 km<br> (compter 50min)<br></span><span class='footer_popup'>cliquer sur l'icone pour visualiser le trajet</span>"};
	marker.events.register("mouseover", params, this.markerover);
	marker.events.register("mouseout", params, this.markerout);
    marker.events.register("mousedown", params, this.setDirections);
	this.markersTGV.addMarker(marker);
	
	
	marker=new OpenLayers.Marker(new OpenLayers.LonLat(-0.6018,44.72),iconTGV.clone());
	var params={
	marker:marker,
	context:this,
	lieu:"Gare,Bordeaux"
	,txtContent:"<span class='title_popup'>Bordeaux: Gare</span><span class='content_popup'>Bordeaux - Mont de Marsan : 130 km <br>(compter 1H20)<br></span><span class='footer_popup'>cliquer sur l'icone pour visualiser le trajet</span>"};
	marker.events.register("mouseover", params, this.markerover);
	marker.events.register("mouseout", params, this.markerout);
    marker.events.register("mousedown", params, this.setDirections);
	this.markersTGV.addMarker(marker);
	
	
	

	marker=new OpenLayers.Marker(new OpenLayers.LonLat(-1.36,43.45),iconTGV.clone())
	var params={
	marker:marker,
	context:this,
	lieu:"France,Bayonne,Gare"
	,txtContent:"<span class='title_popup'>Bayonne: Gare</span><span class='content_popup'>Bayonne - Mont de Marsan : 105 km<br> (compter 1H10)<br></span><span id='footer_p_Bayonne' class='footer_popup'>cliquer sur l'icone pour visualiser le trajet</span>"};
	marker.events.register("mouseover", params, this.markerover);
	marker.events.register("mouseout", params, this.markerout);
    marker.events.register("mousedown", params, this.setDirections);
	this.markersTGV.addMarker(marker);
	
	marker=new OpenLayers.Marker(new OpenLayers.LonLat(1.48,43.53),iconTGV.clone())
	var params={
	marker:marker,
	context:this,
	lieu:"Gare,Toulouse"
	,txtContent:"<span class='title_popup'>Toulouse: Gare</span><span class='content_popup'>Toulouse - Mont de Marsan : 224 km<br> (compter 2H40)<br></span><span id='footer_p_Toulouse' class='footer_popup'>cliquer sur l'icone pour visualiser le trajet</span>"};
	marker.events.register("mouseover", params, this.markerover);
	marker.events.register("mouseout", params, this.markerout);
    marker.events.register("mousedown", params, this.setDirections);
	this.markersTGV.addMarker(marker);
	
	marker=new OpenLayers.Marker(new OpenLayers.LonLat(-1.48,43.39),iconAvion);
	var params={
	marker:marker,
	context:this,
	lieu:"Aéroport de Biarritz, Biarritz"
	,txtContent:"<span class='title_popup'>Aéroport de Biarritz</span><span class='content_popup'><br><span id='footer_p_Biarritz' class='footer_popup'>cliquer sur l'icone pour visualiser le trajet</span>"};
	marker.events.register("mouseover", params, this.markerover);
	marker.events.register("mouseout", params, this.markerout);
    marker.events.register("mousedown", params, this.setDirections);
	this.markersAvion.addMarker(marker);
	
	
	marker=new OpenLayers.Marker(new OpenLayers.LonLat(-0.48,44.87),iconAvion.clone());
	var params={
	marker:marker,
	context:this,
	lieu:"Aéroport de bordeaux"
	,txtContent:"<span class='title_popup'>Aéroport de Bordeaux</span><br><span class='footer_popup'>cliquer sur l'icone pour visualiser le trajet</span>"};
	marker.events.register("mouseover", params, this.markerover);
	marker.events.register("mouseout", params, this.markerout);
    marker.events.register("mousedown", params, this.setDirections);
	this.markersAvion.addMarker(marker);
	
	
	marker=new OpenLayers.Marker(new OpenLayers.LonLat(-0.38,43.23),iconAvion.clone());
	var params={
	marker:marker,
	context:this,
	lieu:"Aéroport de Pau-Pyrénées, Pau"
	,txtContent:"<span class='title_popup'>Aéroport de Pau</span><br><span class='footer_popup'>cliquer sur l'icone pour visualiser le trajet</span>"};
	marker.events.register("mouseover", params, this.markerover);
	marker.events.register("mouseout", params, this.markerout);
    marker.events.register("mousedown", params, this.setDirections);
	this.markersAvion.addMarker(marker);
	
	
	marker=new OpenLayers.Marker(new OpenLayers.LonLat(1.32,43.60),iconAvion.clone());
	var params={
	marker:marker,
	context:this,
	lieu:"Aéroport de Toulouse, Toulouse"
	,txtContent:"<span class='title_popup'>Aéroport de Toulouse</span><br><span class='footer_popup'>cliquer sur l'icone pour visualiser le trajet</span>"};
	marker.events.register("mouseover", params, this.markerover);
	marker.events.register("mouseout", params, this.markerout);
    marker.events.register("mousedown", params, this.setDirections);
	this.markersAvion.addMarker(marker);
	

	


	this.markersMDM = new OpenLayers.Layer.Markers( "MDM" , {maxScale:800000});
	this.markersMDM.addMarker(new OpenLayers.Marker(new OpenLayers.LonLat(-0.50,43.88),iconMDM.clone()));
	
	


	var yelp = new OpenLayers.Icon("./img/icon_rmll.png", new OpenLayers.Size(30,30));
	var newl = new OpenLayers.Layer.GeoRSS( 'Sites des RMLL', 'rmll_OL.xml', {legendurl:"./img/icon_rmll.png",'icon':yelp,minScale:100000});
	newl.markerClick=function(evt) {
        var sameMarkerClicked = (this == this.layer.selectedFeature);
        this.layer.selectedFeature = (!sameMarkerClicked) ? this : null;
        for(var i=0; i < this.layer.map.popups.length; i++) {
            this.layer.map.removePopup(this.layer.map.popups[i]);
        }
        if (!sameMarkerClicked) {
            this.popupClass=OpenLayers.Popup;
            this.data.popupSize= new OpenLayers.Size(252, 128);
            var popup = this.createPopup(false);
            popup.closeDiv.style.display="none"; 
            Element.addClassName(popup.div,"LocalPopup");
            
            OpenLayers.Event.observe(popup.div, "click",
                OpenLayers.Function.bind(function() { 
                    for(var i=0	; i < this.layer.map.popups.length; i++) { 
                        this.layer.map.removePopup(this.layer.map.popups[i]); 
                    }
                }, this)
            );
	    this.layer.map.panTo(this.lonlat);
        this.layer.map.addPopup(popup); 
        }
        OpenLayers.Event.stop(evt);
   	 };

	
	
	var iconRestau = new OpenLayers.Icon("./img/icon_hotel_rmll.png", new OpenLayers.Size(30,30));
	var RestauRMLL = new OpenLayers.Layer.GeoRSS( 'Hébergement RMLL', 'Heb_Resto_RMLL_OL.xml', {legendurl:"./img/icon_hotel_rmll.png",'icon':iconRestau,minScale:100000});
	RestauRMLL.markerClick=newl.markerClick;
	
	
	var iconwifi = new OpenLayers.Icon("./img/icon_wifi.png", new OpenLayers.Size(30,30));
	var wifiRMLL = new OpenLayers.Layer.GeoRSS( 'Hotspots Wifi', 'wifi_OL.xml', {legendurl:"./img/icon_wifi.png",'icon':iconwifi,minScale:100000});
	wifiRMLL.markerClick=newl.markerClick;
	
	
	var iconHotels = new OpenLayers.Icon("./img/icon_hotel.png", new OpenLayers.Size(30,30));
	var hotelslayer = new OpenLayers.Layer.GeoRSS( 'Hôtels', 'hotels_OL.xml', {legendurl:"./img/icon_hotel.png",'icon':iconHotels,popupSize:new OpenLayers.Size(300,150),minScale:100000});
	hotelslayer.markerClick=newl.markerClick;


	//Ajout des couches
	
	this.map.addLayer(hotelslayer);
	this.map.addLayer(wifiRMLL);
	this.map.addLayer(RestauRMLL);
	this.map.addLayer(this.markersMDM);
	this.map.addLayer(this.markersAvion);
	this.map.addLayer(this.markersTGV);
	this.map.addLayer(newl);
	
    // Map settings


     this.map.setCenter(new OpenLayers.LonLat(this.initLon, this.initLat), this.initZoom);

	 var mouseNav = new OpenLayers.Control.Navigation({zoomWheelEnabled:false});
     this.map.addControl( mouseNav );
        
        
    //    // Coords on map
        var mousePosition = new OpenLayers.Control.MousePosition();
    //    this.map.addControl( mousePosition );

    },


markerout: function(evt) {
		 if (this.context.popup != null) {
			this.context.map.removePopup(this.context.popup);
                this.context.popup.destroy();
                this.context.popup = null;
		}
	  
	},
	
	
	
	
markerover: function(evt) {
             // check to see if the popup was hidden by the close box
             // if so, then destroy it before continuing
	    
            if (this.context.popup != null) {
                if (!this.context.popup.visible()) {
                    this.context.map.removePopup(this.context.popup);
                    this.context.popup.destroy();
                    this.context.popup = null;
                }
            }
            if (this.context.popup == null) {
                this.context.popup = new OpenLayers.Popup("olPopup",
                   this.marker.lonlat,
                   new OpenLayers.Size(200,86),
                   "example popup",
                   false);
                this.context.popup.setContentHTML(this.txtContent);
                this.context.popup.setOpacity(1);
                this.context.map.addPopup(this.context.popup);
            } else {
                this.context.map.removePopup(this.context.popup);
                this.context.popup.destroy();
                this.context.popup = null;
            }
            OpenLayers.Event.stop(evt);
        }
,
 

/*----------------------------------------------------------------- 
*  Fonction pour charger un objet GDirections de l'API Gmap 
*  afin d'afficher un trajet sur la carte
*----------------------------------------------------------------- */
directions:function(){
 if (GBrowserIsCompatible()) {      
        map2 = this.map.baseLayer.mapObject;
        this.gdir = new GDirections(map2);
        GEvent.addListener(this.gdir, "load", this.onGDirectionsLoad);
        GEvent.addListener(this.gdir, "error", this.handleErrors);
	
      }
	},
/*----------------------------------------------------------------- 
*  Affichage d'un trajet depuis un lieu vers Mont de marsan
*  En cas de succès la fonction onGDirectionsLoad est appelée
*----------------------------------------------------------------- */
 setDirections:function(fromAddress, toAddress, locale) {
 
	if(this.lieu)
	 	this.context.gdir.load("from: "+this.lieu + " to: Mont de marsan",{ "locale": "fr",preserveViewport:true });
	else
      	this.gdir.load("from: " + fromAddress + " to: " + toAddress,{ "locale": locale,preserveViewport:true });
    },

/*----------------------------------------------------------------- 
*  Fonction de rappel lorsqu'un itinéraire est chargé
*----------------------------------------------------------------- */
 onGDirectionsLoad:function(){
 	show($('drivetime'));
 	$("drivetimeContent").innerHTML=this.getSummaryHtml();
},

/*----------------------------------------------------------------- 
*  Fonction de rappel lorsqu'une erreur arrive lors d'un calcul d'itinéraire
*----------------------------------------------------------------- */
handleErrors:function(){
	   if (this.getStatus().code == G_GEO_UNKNOWN_ADDRESS)
	     alert("No corresponding geographic location could be found for one of the specified addresses. This may be due to the fact that the address is relatively new, or it may be incorrect.\nError code: " + this.gdir.getStatus().code);
	   else if (this.getStatus().code == G_GEO_SERVER_ERROR)
	     alert("A geocoding or directions request could not be successfully processed, yet the exact reason for the failure is not known.\n Error code: " + this.gdir.getStatus().code);
	   
	   else if (this.gdir.getStatus().code == G_GEO_MISSING_QUERY)
	     alert("The HTTP q parameter was either missing or had no value. For geocoder requests, this means that an empty address was specified as input. For directions requests, this means that no query was specified in the input.\n Error code: " + this.gdir.getStatus().code);

	//   else if (gdir.getStatus().code == G_UNAVAILABLE_ADDRESS)  <--- Doc bug... this is either not defined, or Doc is wrong
	//     alert("The geocode for the given address or the route for the given directions query cannot be returned due to legal or contractual reasons.\n Error code: " + gdir.getStatus().code);
	     
	   else if (this.gdir.getStatus().code == G_GEO_BAD_KEY)
	     alert("The given key is either invalid or does not match the domain for which it was given. \n Error code: " + this.gdir.getStatus().code);

	   else if (this.gdir.getStatus().code == G_GEO_BAD_REQUEST)
	     alert("A directions request could not be successfully parsed.\n Error code: " + this.gdir.getStatus().code);
	    
	   else alert("An unknown error occurred.");
	   
},

    /**
     *
     */
destroy: function() {
    },

    /**
    * Add base layers
    **/
addBaseLayers: function() {  
    this.aftercineLayer = new OpenLayers.Layer.Google( "Carte" , {type: G_NORMAL_MAP} );
    this.aftercineLayer.addOptions({isBaseLayer: true, buffer: 1});   
	this.map.addLayer(this.aftercineLayer);
	
    },

cinematique: function() {
	
	currentZoom=this.map.zoom+1;
	if(currentZoom<7)
		{
		setTimeout("manageOL.cinematiqueStep()",900);
		}
	else{
		setTimeout("manageOL.cinematiqueStop()",2000);
	}
		
},
	
cinematiqueStop: function() {
	
	this.map.setBaseLayer(this.aftercineLayer);
	//show($('title'));
	show($('title2'));
	
	var scale = new OpenLayers.Control.Scale();
	this.map.addControl(scale);
	// Navigation tools
    var panzoombar = new OpenLayers.Control.PanZoomBar();
    panzoombar.position = new OpenLayers.Pixel(5, 5);
    this.map.addControl(panzoombar);

	var legend=new CustomLegend(this.map);
	this.map.addControl( legend );
	
	dndMgr.registerDraggable( new Rico.Draggable('Custom2',legend.id, 'CLegendTitle') );
	
	this.markersTGV.setVisibility(true);
	this.markersAvion.setVisibility(true);

	var baseLayerSwitcher = new OpenLayers.Control.LayerSwitcher();
	this.directions();

	//this.map.addControl( baseLayerSwitcher );
		
		
    },



cinematiqueStep: function() {
	
	currentZoom=this.map.zoom+1;
	this.map.setCenter(new OpenLayers.LonLat(this.initLon, this.initLat), currentZoom);
		
		
},

    /**
    * Zoom the map to extent given in parameters
    **/
    zoomTo: function(extent) {
        if(extent != "" && extent != "None")
        {
            var tabExtent = extent.split(" ");
            if(tabExtent[0] == tabExtent[2] && tabExtent[1] == tabExtent[3])
            {
                this.map.setCenter(new OpenLayers.LonLat(tabExtent[0], tabExtent[1]), this.initZoom);
            }
            else
            {
                bounds = new OpenLayers.Bounds(tabExtent[0],tabExtent[1],tabExtent[2],tabExtent[3]);
                this.map.zoomToExtent(bounds);
            }
        }
    }


});
