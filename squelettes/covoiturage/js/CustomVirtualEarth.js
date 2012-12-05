/**
 * @requires OpenLayers/Layer/VirtualEarth.js
 */


CustomVirtualEarth = OpenLayers.Class(
    OpenLayers.Layer.VirtualEarth, {
    

    initialize: function(name, options) {
        OpenLayers.Layer.VirtualEarth.prototype.initialize.apply(this, arguments);
       
    },
    
    /**
     * Method: loadMapObject
     */
    loadMapObject:function() {

        // create div and set to same size as map
        var veDiv = OpenLayers.Util.createDiv(this.name);
        var sz = this.map.getSize();
        veDiv.style.width = sz.w;
        veDiv.style.height = sz.h;
        this.div.appendChild(veDiv);

        try { // crash prevention
            this.mapObject = new VEMap(this.name);
        } catch (e) { }

        if (this.mapObject != null) {
            try { // this is to catch a Mozilla bug without falling apart

                // The fourth argument is whether the map is 'fixed' -- not 
                // draggable. See: 
                // http://blogs.msdn.com/virtualearth/archive/2007/09/28/locking-a-virtual-earth-map.aspx
                //
                this.mapObject.LoadMap(null, null, this.type, true);
                this.mapObject.AttachEvent("onmousedown", function() {return true; });
		this.mapObject.AttachEvent("onendzoom", (function() {this.events.triggerEvent("loadend"); }).bind(this));

            } catch (e) { }
            this.mapObject.HideDashboard();
        }

        //can we do smooth panning? this is an unpublished method, so we need 
        // to be careful
        if ( !this.mapObject ||
             !this.mapObject.vemapcontrol ||
             !this.mapObject.vemapcontrol.PanMap ||
             (typeof this.mapObject.vemapcontrol.PanMap != "function")) {

            this.dragPanMapObject = null;
        }

    },

    CLASS_NAME: "CustomVirtualEarth"
});
