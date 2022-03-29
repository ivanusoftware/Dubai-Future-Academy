class HTMLMapMarkerAdmin extends google.maps.OverlayView {
	constructor( myLatlng ) {
		super();
		this.latlng = myLatlng.latlng;
		this.html = myLatlng.html;
		this.marker = myLatlng.marker;
		this.setMap( myLatlng.map );
	}

	createDiv() {
	this.div = document.createElement('div');
	this.div.classList.add("animated-dot");
	this.div.style.position = 'absolute';
	if (this.html) {
		this.div.innerHTML = this.html;
	}
	/*google.maps.event.addDomListener(this.div, 'click', function(event) {
		google.maps.event.trigger(this, 'click');
	});*/
	/*google.maps.event.addDomListener(this.div, 'mouseover', function(event) {
		google.maps.event.trigger(this, 'mouseover');
	});*/
	}

	appendDivToOverlay() {
	const panes = this.getPanes();
	panes.overlayMouseTarget.appendChild(this.div);
	var me = this;
	google.maps.event.addDomListener(this.div, 'mouseover', function(event) {
		google.maps.event.trigger(me, 'mouseover');
	});
	}

	positionDiv() {
	const point = this.getProjection().fromLatLngToDivPixel(this.latlng);
	if (point) {
		this.div.style.left = `${point.x}px`;
		this.div.style.top = `${point.y}px`;
	}
	}

	draw() {
	if (!this.div) {
		this.createDiv();
		this.appendDivToOverlay();
	}
	this.positionDiv();
	}

	remove() {
	if (this.div) {
		this.div.parentNode.removeChild(this.div);
		this.div = null;
	}
	}

	getPosition() {
	return this.latlng;
	}

	getDraggable() {
	return false;
	}
}

var map;
var markersArray = [];
var projectPartners;
var projectTechnologies;
var markerCluster;
var terms;
var exhibitionMode;
googleMapUpdated = function( attributes ){
    setTimeout(function( attributes ){ 
        if( document.getElementById("google-map-admin") !== null && 0 !== document.getElementById("google-map-admin").length ) {
            console.log(attributes);
			terms = attributes.terms;
			exhibitionMode = attributes.exhibitionMode;
			
			var defaultLat;
			var defaultLong;
			var defaultZoom;
			if( null !== attributes.MapLatitude && "" !== attributes.MapLatitude ) {
				defaultLat = parseFloat( attributes.MapLatitude );
			} else {
				defaultLat = 25.2048493;
			}

			if( null !== attributes.MapLongitude && "" !== attributes.MapLongitude ) {
				defaultLong = parseFloat( attributes.MapLongitude );
			} else {
				defaultLong = 55.2707828;
			}

			if( null !== attributes.MapZoom && "" !== attributes.MapZoom ) {
				defaultZoom = parseInt( attributes.MapZoom );
			} else {
				defaultZoom = 12;
			}
			
			
            map = new google.maps.Map(document.getElementById("google-map-admin"), {
                center: { lat: defaultLat, lng: defaultLong },
                zoom: defaultZoom,
                panControl: false,
                zoomControl: attributes.showZoom,
                mapTypeControl: false,
                streetViewControl: false,
                styles: [{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#333333"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#FFFFFF"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#FEFEFE"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#FEFEFE"},{"lightness":17},{"weight":1.2}]},{"featureType":"administrative.locality","elementType":"geometry","stylers":[{"saturation":"34"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#F7F8F8"},{"lightness":20}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#F5F5F5"},{"lightness":21}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#ECEEED"},{"lightness":21}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"color":"#F1F4F2"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#FFFFFF"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"lightness":29},{"weight":0.2},{"color":"#F1F4F2"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry.stroke","stylers":[{"color":"#F1F4F2"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#FFFFFF"},{"lightness":18}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#F1F4F2"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#FFFFFF"},{"lightness":16}]},{"featureType":"road.local","elementType":"geometry.stroke","stylers":[{"color":"#F1F4F2"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#F2F2F2"},{"lightness":19}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#CBD2D3"},{"lightness":17}]}]
            });

            google.maps.event.addListener(map, 'bounds_changed', function() {
                getProjectMarkers();
                
                // remove listener as its only needed on pageload
                google.maps.event.clearListeners(map, 'bounds_changed');
            });

        } else { console.log("Map Div not found!!"); }
    }, 4000, attributes);
}

function getProjectMarkers() {
   
    var data_obj = { 
        action: 'impactmap_get_project_markers',
        project_terms: terms
    }
    jQuery.ajax({
        type : 'POST',
        url : impactMap.ajaxurl,
		data: data_obj,
		dataType: "json",
        success : function( xhr ) {
            var data = xhr.data;
            if (markersArray.length > 0){
                clearProjectMarkers();
            }
            setProjectMarkers( map, data.project_markers );
            
        },
		error: function(objAJAXRequest, strError) {
				alert('error function executed: '+strError);				
		},
		statusCode: {
			404: function() {
				alert( "page not found" );
			}
		},
    });
}

function setProjectMarkers( map, markers ) {
    for ( var i = 0; i < markers.length; i++ ) {
        var marker_info = markers[i];
		var LatLng = new google.maps.LatLng( marker_info['latitude'], marker_info['longitude'] );
		var marker = new HTMLMapMarkerAdmin({
			latlng: LatLng,
            map: map,
            html: `<div class="middle-dot"></div><div class="signal"></div><div class="signal2"></div>`	
        });
        
        //Push markers in array.
        markersArray.push( marker );

    }

    //Setting up the clustering.
	if( false === exhibitionMode ) {
		setMarkerCluster();
	}
}
function clearProjectMarkers() {
	for (var i = 0; i < markersArray.length; i++ ) {
		markersArray[i].setMap(null);
	}
	
	markersArray = [];
}

function setMarkerCluster() {
    var clusterOptions = {
        imagePath:  "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m",
  	};
    var markerCluster = new MarkerClusterer(map,markersArray,clusterOptions);
}

//Customize the cluster div
ClusterIcon.prototype.onAdd = function() {
	this.div_ = document.createElement('DIV');
	var clusterSizeClass = '';
	if (this.cluster_.getMarkers().length < 3) { clusterSizeClass = 'cluster-small'; }
	if (this.cluster_.getMarkers().length > 2 && this.cluster_.getMarkers().length < 6) { clusterSizeClass = 'cluster-medium'; }
	if (this.cluster_.getMarkers().length > 5) { clusterSizeClass = 'cluster-large'; }
	
    if (this.visible_) {
      var pos = this.getPosFromLatLng_(this.center_);
      this.div_.style.cssText = this.createCss(pos);
	  this.div_.innerHTML = this.sums_.text;
	  this.div_.classList.add("gmap-custom-cluster");
	  this.div_.classList.add( clusterSizeClass );
    }
  
    var panes = this.getPanes();
    panes.overlayMouseTarget.appendChild(this.div_);
  
    var that = this;
    google.maps.event.addDomListener(this.div_, 'click', function() {
      that.triggerClusterClick();
    });
};