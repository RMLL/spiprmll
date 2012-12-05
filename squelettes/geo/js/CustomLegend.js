

CustomLegend = OpenLayers.Class(OpenLayers.Control,{
	CLASS_NAME: "CustomLegend",

	map:null,

	contentDiv:null,
	initialize: function(map, options) {
		this.map=map;
		this.options =  options || {};

		OpenLayers.Control.prototype.initialize.apply(this, [options]);
    		this.loadLegend();

    		
		

    },


/**
     * Method: draw
     * Render the control in the browser.
     */    
    draw: function() {
        OpenLayers.Control.prototype.draw.apply(this, arguments);
      

        // create overview map DOM elements
        this.element = document.createElement('div');
	this.element.id="customlegend";
        this.element.className = this.displayClass + 'Element';
	//this.element.style.display = 'none';
	this.div.appendChild(this.element);

	this.titleDiv = document.createElement("div");
	this.titleDiv.id="CLegendTitle";
	Element.addClassName(this.titleDiv,'boxHead');
	Element.addClassName(this.titleDiv,'LegendTitle');
	this.titleDiv.innerHTML="Légende";
	this.element.appendChild(this.titleDiv);
	this.contentDiv = document.createElement("div");
	Element.addClassName(this.contentDiv,'legendBody');
	

	
	this.closeDiv = document.createElement("div");
	Element.addClassName(this.closeDiv,'legend_close');
	this.closeDiv.innerHTML="-";
	this.closeDiv.onclick=function(){
		if(this.closeDiv.innerHTML=="-")
		{
			this.contentDiv.style.display="none";
			this.closeDiv.innerHTML="+";
		}
		else
		{
			this.contentDiv.style.display="block";
			this.closeDiv.innerHTML="-";
		}
	}.bind(this);
	
	this.element.appendChild(this.closeDiv);
	this.element.appendChild(this.contentDiv);
	this.map.events.register('moveend', this, this.update);
	return this.div;
    },
    
    loadLegend: function() {
    	//this.spinner('start');  
    	//this.getFrameBody().setAttribute('id','div_legend');   	 
  	//this.spinner('stop');
	
  		    	    	
    },
    changeLayerVisbility: function(){
     this.setVisibility(!this.getVisibility());
    
    },
    update: function(){
    
   	this.contentDiv.innerHTML="";
	    	for(var index=0; index<this.map.layers.size() ; index++) {
	    		
	    		
	        	var layer=this.map.layers[index];	
			    //if(layer.getVisibility())
			   // {
				       
					
					if(layer.legendurl && layer.inRange)
					{
						
						var divlegendImg = document.createElement("div");
				       	Element.addClassName(divlegendImg,'LegendImg');
				    
				        var legendTxt = document.createElement("div");
				        Element.addClassName(legendTxt,'LegendTxt');
						legendTxt.innerHTML=layer.name;
						
						var checkboxElmt = document.createElement("input");
						checkboxElmt.type="checkbox";
						checkboxElmt.checked=layer.getVisibility();
						Element.addClassName(checkboxElmt,'legend_chkbox');
						
						OpenLayers.Event.observe(checkboxElmt, "click", 
                   	    this.changeLayerVisbility.bindAsEventListener(layer));
                   			
						
							
						
						var legendImg = document.createElement("img");
						Element.addClassName(legendImg,'legend_img');
						divlegendImg.appendChild(checkboxElmt);
				    	divlegendImg.appendChild(legendImg);
				    	divlegendImg.appendChild(legendTxt);
						legendImg.src=layer.legendurl;
						legendImg.width=30;
						legendImg.height=30;
						legendImg.style.position="relative";
						legendImg.style.top="3px";
						this.contentDiv.appendChild(divlegendImg);
					}

			     //}
		}
    }
}); 