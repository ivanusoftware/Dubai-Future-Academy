class HTMLMapMarker extends google.maps.OverlayView {
  constructor(myLatlng) {
    super();
    this.latlng = myLatlng.latlng;
    this.html = myLatlng.html;
    this.marker = myLatlng.marker;
    this.setMap(myLatlng.map);
  }

  createDiv() {
    this.div = document.createElement("div");
    this.div.classList.add("animated-dot");
    this.div.style.position = "absolute";
    if (this.html) {
      this.div.innerHTML = this.html;
    }
  }

  appendDivToOverlay() {
    const panes = this.getPanes();
    panes.overlayMouseTarget.appendChild(this.div);
    var me = this;
    google.maps.event.addDomListener(this.div, "mouseover", function (event) {
      google.maps.event.trigger(me, "mouseover");
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

(function ($) {
  "use strict";

  var map;
  var mapSettings;
  var mapDivId = "front-block-map";
  var mapDivWrapper = "map-wrapper-front-block-map";
  var infoWindow;
  var markersArray = [];
  var selectedTaxonomy;
  var completeProject;
  var zoomInButton;
  var zoomOutButton;
  var markerCluster;
  var interval;
  var mapDefaultZoom;
  var mapDefaultLat;
  var mapDefaultLong;
  var defaultFullscreenControl;

  var initFrontMap = function () {
    mapSettings = initilizeMapSettings();

    if ( document.getElementById(mapDivId) !== null && 0 !== document.getElementById(mapDivId).length ) {
      //console.log(mapSettings);
      
      if( null !== mapSettings.defaultLat && "" !== mapSettings.defaultLat ) {
        mapDefaultLat = parseFloat( mapSettings.defaultLat );
      } else {
        mapDefaultLat = 25.2048493;
      }

      if( null !== mapSettings.defaultLong && "" !== mapSettings.defaultLong ) {
        mapDefaultLong = parseFloat( mapSettings.defaultLong );
      } else {
        mapDefaultLong = 55.2707828;
      }

      if( null !== mapSettings.defaultZoom && "" !== mapSettings.defaultZoom ) {
        mapDefaultZoom = parseInt( mapSettings.defaultZoom );
      } else {
        mapDefaultZoom = 12;
      }

      if( 1 != mapSettings.exhibitionMode ) {
        defaultFullscreenControl = false;
      } else {
        defaultFullscreenControl = true;
      }

      //Initialize Map.
      map = new google.maps.Map(document.getElementById(mapDivId), {
        center: { lat: mapDefaultLat, lng: mapDefaultLong },
        zoom: mapDefaultZoom,
        panControl: false,
        zoomControl: false,
        fullscreenControl: defaultFullscreenControl,
        mapTypeControl: false,
        streetViewControl: false,
        clickableIcons: false,
        styles: [
          {
            featureType: "all",
            elementType: "labels.text.fill",
            stylers: [
              { saturation: 36 },
              { color: "#333333" },
              { lightness: 40 },
            ],
          },
          {
            featureType: "all",
            elementType: "labels.text.stroke",
            stylers: [
              { visibility: "on" },
              { color: "#FFFFFF" },
              { lightness: 16 },
            ],
          },
          {
            featureType: "all",
            elementType: "labels.icon",
            stylers: [{ visibility: "off" }],
          },

          {
            featureType: "all",
            elementType: "labels.text",
            stylers: [{ visibility: "on" }],
          },
          {
            featureType: "administrative",
            elementType: "geometry.fill",
            stylers: [{ color: "#FEFEFE" }, { lightness: 20 }],
          },
          {
            featureType: "administrative",
            elementType: "geometry.stroke",
            stylers: [{ color: "#FEFEFE" }, { lightness: 17 }, { weight: 1.2 }],
          },
          {
            featureType: "administrative.locality",
            elementType: "geometry",
            stylers: [{ saturation: "34" }],
          },
          {
            featureType: "landscape",
            elementType: "geometry",
            stylers: [{ color: "#F7F8F8" }, { lightness: 20 }],
          },
          {
            featureType: "poi",
            elementType: "geometry",
            stylers: [{ color: "#F5F5F5" }, { lightness: 21 }],
          },
          {
            featureType: "poi.park",
            elementType: "geometry",
            stylers: [{ color: "#ECEEED" }, { lightness: 21 }],
          },
          {
            featureType: "road",
            elementType: "geometry.stroke",
            stylers: [{ color: "#F1F4F2" }],
          },
          {
            featureType: "road.highway",
            elementType: "geometry.fill",
            stylers: [{ color: "#FFFFFF" }, { lightness: 17 }],
          },
          {
            featureType: "road.highway",
            elementType: "geometry.stroke",
            stylers: [{ lightness: 29 }, { weight: 0.2 }, { color: "#F1F4F2" }],
          },
          {
            featureType: "road.highway.controlled_access",
            elementType: "geometry.stroke",
            stylers: [{ color: "#F1F4F2" }],
          },
          {
            featureType: "road.arterial",
            elementType: "geometry",
            stylers: [{ color: "#FFFFFF" }, { lightness: 18 }],
          },
          {
            featureType: "road.arterial",
            elementType: "geometry.stroke",
            stylers: [{ color: "#F1F4F2" }],
          },
          {
            featureType: "road.local",
            elementType: "geometry",
            stylers: [{ color: "#FFFFFF" }, { lightness: 16 }],
          },
          {
            featureType: "road.local",
            elementType: "geometry.stroke",
            stylers: [{ color: "#F1F4F2" }],
          },
          {
            featureType: "transit",
            elementType: "geometry",
            stylers: [{ color: "#F2F2F2" }, { lightness: 19 }],
          },
          {
            featureType: "water",
            elementType: "geometry",
            stylers: [{ color: "#CBD2D3" }, { lightness: 17 }],
          },

        ],
      });
      
      google.maps.event.addListener(map, "bounds_changed", function () {
        getProjectMarkers();
        

        // remove listener as its only needed on pageload
        google.maps.event.clearListeners(map, "bounds_changed");
	  });
	  
	  google.maps.event.addListener(map, "click", function(event) {
		infoWindow.close();
	});

      // Setup the click event listener - zoomIn
      if (null !== zoomInButton) {
        google.maps.event.addDomListener(zoomInButton, "click", function () {
          map.setZoom(map.getZoom() + 1);
        });
      }

      // Setup the click event listener - zoomOut
      if (null !== zoomOutButton) {
        google.maps.event.addDomListener(zoomOutButton, "click", function () {
          map.setZoom(map.getZoom() - 1);
        });
      }

    } else {
      console.log("Map div not found!!");
    }
  };
  google.maps.event.addDomListener(window, "load", initFrontMap);

  var initilizeMapSettings = function () {
    var settingDiv = jQuery("#" + mapDivWrapper);
    var mapSettings = {
      zoomControl: settingDiv.find("#map-zoom-control").val(),
      exhibitionMode: settingDiv.find("#map-exhibition_mode").val(),
      exhibitionTime: settingDiv.find("#map-exhibition_time").val(),
      defaultZoom: settingDiv.find("#map-defaultZoom").val(),
      defaultLat: settingDiv.find("#map-defaultLatitude").val(),
      defaultLong: settingDiv.find("#map-defaultLongitude").val()
    };
    zoomInButton = document.getElementById("map-custom-zoomin");
    zoomOutButton = document.getElementById("map-custom-zoomout");
    return mapSettings;
  };

  function getProjectMarkers() {
    var exhibition_mode = jQuery("#map-exhibition_mode").val();

    var SharingOptions = jQuery("#map-SharingOptions").val();
    var map_terms = jQuery("#map-terms").val();
    var pButton = jQuery("#map-pButton").val();
    var description = jQuery("#map-description").val();

    var PrimaryTechnology = jQuery("#map-PrimaryTechnology").val();

    var project_technologies_array = [];
    jQuery(".project_technologies_filter li .selected").each(function () {
      project_technologies_array.push(jQuery(this).attr("data-slug"));
    });

    var project_partners_array = [];
    jQuery(".project_partners_filter li .selected").each(function () {
      project_partners_array.push(jQuery(this).attr("data-slug"));
    });

    var term_array = [
      { project_technologies: project_technologies_array },
      { project_partners: project_partners_array },
    ];
    selectedTaxonomy = JSON.stringify(term_array);

    var data_obj = {
      action: "impactmap_front_get_project_markers",
      project_terms: selectedTaxonomy,
      complete_project: completeProject,
      exhibition_mode: exhibition_mode,
      SharingOptions: SharingOptions,
      pButton: pButton,
      PrimaryTechnology: PrimaryTechnology,
      map_terms: map_terms,
      share_project: impactMap.shareProject,
	    description: description,
	    page_url: impactMap.mapPage,
    };
    jQuery.ajax({
      type: "POST",
      url: impactMap.ajaxurl,
      data: data_obj,
      dataType: "json",
      success: function (xhr) {
        var data = xhr.data;
        //console.log(markersArray.length);
        
        if (markersArray.length > 0) {
          //console.log('Clear');
          clearProjectMarkers();
        }
        setProjectMarkers(map, data.project_markers, data.is_share);
        //initViewPort();
        //alert( mapSettings.defaultZoom );

        /**
         * Set zoom size after click on filter button.
         */
        map.setZoom( parseInt( mapSettings.defaultZoom ) );
        map.setCenter({lat: mapDefaultLat, lng: mapDefaultLong});

      },
      error: function (objAJAXRequest, strError) {
        alert("error function executed: " + strError);
      },

      statusCode: {
        404: function () {
          alert("page not found");
        },
      },
    });
  }

  function setProjectMarkers(map, markers, isShare = false) {
    infoWindow = new google.maps.InfoWindow({
      maxWidth: 324,
      minWidth: 324,
	  pixelOffset: new google.maps.Size(180, 0),
	  disableAutoPan: true,
	  });
    var marker;
    

    for (var i = 0; i < markers.length; i++) {
      var marker_info = markers[i];
      var LatLng = new google.maps.LatLng(
        marker_info["latitude"],
        marker_info["longitude"]
      );

      marker = new HTMLMapMarker({
        latlng: LatLng,
        map: map,
        html: `<div class="middle-dot"></div><div class="signal"></div><div class="signal2"></div>`,
        data_id: marker_info["project_id"],
      });

      //push marker in array.
      markersArray.push(marker);

      (function (marker, marker_info) {
        google.maps.event.addListener(marker, "mouseover", function (e) {
          //Create and open InfoWindow.
          //Wrap the content inside an HTML DIV in order to set height and width of InfoWindow.
          infoWindow.setContent(marker_info["content"]);
          infoWindow.setPosition(marker.position);
          infoWindow.open(map, marker);

            var scrollOffsetY, scrollOffsetX, arabicSideBarWidth, englishSideBarWidth; 
            var popupHeight = 250;
            var popupWidth = 250;
            setTimeout(
              function( popupHeight, popupWidth, scrollOffsetX, scrollOffsetY, arabicSideBarWidth, englishSideBarWidth ) {
                  popupHeight = jQuery(".map-popup-outer").outerHeight() / 2;
                  popupHeight = popupHeight + 20;
                  popupWidth = jQuery(".map-popup-outer").outerWidth();
                  var mapHeight = ( jQuery("#map-wrapper-front-block-map").outerHeight() / 2 );
                  var mapWidth = ( jQuery("#map-wrapper-front-block-map").outerWidth() / 2 );
                  var mapBottom = mapHeight - marker.div.offsetTop;
                  var mapTop = mapHeight + marker.div.offsetTop;
                  var mapLeft = marker.div.offsetLeft;

                  //Drag from Bottom
                  if( mapBottom < popupHeight ){
                    scrollOffsetY = ( popupHeight - mapBottom ) - 20;
                    map.panBy(0, scrollOffsetY);
                    //map.panTo(marker.getPosition());
                  }

                  //Drag from Right                  
                  if(jQuery('#impact-maparabic-css').length == 0 && !jQuery(".sidebar-right").hasClass("collapsed") ){
                    englishSideBarWidth = jQuery(".sidebar-right:not(.collapsed)").outerWidth();
                  }else{
                    englishSideBarWidth = 0;
                  }
                  if(jQuery('#impact-maparabic-css').length == 1 && !jQuery(".sidebar-left").hasClass("collapsed") ){
                    arabicSideBarWidth = jQuery(".sidebar-left:not(.collapsed)").outerWidth();
                  }else{
                    arabicSideBarWidth = 0;
                  }
                  if( mapWidth < (mapLeft + popupWidth + arabicSideBarWidth + englishSideBarWidth) ){ 
                    scrollOffsetX = ((mapLeft + popupWidth + arabicSideBarWidth + englishSideBarWidth) - mapWidth) + 50;
                    map.panBy(scrollOffsetX, 0);
                  }

                  //Drag from Top
                  if( mapTop < popupHeight ){
                    scrollOffsetY = (( popupHeight - mapTop ) + 20) * -1;
                    map.panBy(0, scrollOffsetY);
                  }

              }, 100, popupHeight, popupWidth, scrollOffsetX, scrollOffsetY, arabicSideBarWidth, englishSideBarWidth );
        });
      })(marker, marker_info);
    }
    if( true === isShare ) {
      map.setCenter(LatLng);
      google.maps.event.trigger(marker, "mouseover");
    }

    //Setting up the clustering.
    if ("1" !== mapSettings.exhibitionMode) {
      setMarkerCluster();
    } else {
      setTimeout( mapExhibitionModeOn(), 2000 );
    }
  }
  var panPath = [];   // An array of points the current panning action will use
var panQueue = [];  // An array of subsequent panTo actions to take
var STEPS = 50;     // The number of steps that each panTo action will undergo

function panTo(newLat, newLng) {
  if (panPath.length > 0) {
    // We are already panning...queue this up for next move
    panQueue.push([newLat, newLng]);
  } else {
    // Lets compute the points we'll use
    panPath.push("LAZY SYNCRONIZED LOCK");  // make length non-zero - 'release' this before calling setTimeout
    var curLat = map.getCenter().lat();
    var curLng = map.getCenter().lng();
   
    var dLat = (newLat - curLat)/STEPS;
    var dLng = (newLng - curLng)/STEPS;
    console.log( "Diff lat "+dLat );
    console.log( "Diff long "+dLng );
    console.log( dLat.toFixed(5) );
    if( dLat >= 0.003126244492637653 && dLng >= 0.0007510185241699219 ) { 
    for (var i=0; i < STEPS; i++) {
      panPath.push([curLat + dLat * i, curLng + dLng * i]);
    }
    panPath.push([newLat, newLng]);
    panPath.shift();      // LAZY SYNCRONIZED LOCK
    setTimeout(doPan, 20);
  }
  }
}

function doPan() {
  var next = panPath.shift();
  if (next != null) {
    // Continue our current pan action
    map.panTo( new google.maps.LatLng(next[0], next[1]));
    setTimeout(doPan, 20 );
  } else {
    // We are finished with this pan - check if there are any queue'd up locations to pan to 
    var queued = panQueue.shift();
    if (queued != null) {
      panTo(queued[0], queued[1]);
    }
  }
}

  function mapExhibitionModeOn() {
    //convert time to milliseconds.
    var changeTime = parseInt(mapSettings.exhibitionTime) * 1000;
	var counter = 1
    var delayTime;
    for (var i = 0; i < markersArray.length; i++) {
      delayTime = changeTime * counter;
      //console.log("Delay Time" + delayTime);
      setTimeout(
        function (marker) {
          google.maps.event.trigger(marker, "mouseover");
        },
        delayTime,
        markersArray[i]
	  );
	  counter++;
    }

	if( !interval ) {
    	interval = setInterval(mapExhibitionModeOn, delayTime);
	}
  }

  function clearProjectMarkers() {
    for (var i = 0; i < markersArray.length; i++) {
      markersArray[i].setMap(null);
    }

	markersArray = [];
	
	markerCluster.clearMarkers();
  }

  function setMarkerCluster() {
    var clusterOptions = {
      imagePath:
        "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m",
    };
    markerCluster = new MarkerClusterer(map, markersArray, clusterOptions);
  }

  //Customize the cluster div
  ClusterIcon.prototype.onAdd = function () {
    this.div_ = document.createElement("DIV");
    var clusterSizeClass = "";
    if (this.cluster_.getMarkers().length < 3) {
      clusterSizeClass = "cluster-small";
    }
    if (
      this.cluster_.getMarkers().length > 2 &&
      this.cluster_.getMarkers().length < 6
    ) {
      clusterSizeClass = "cluster-medium";
    }
    if (this.cluster_.getMarkers().length > 5) {
      clusterSizeClass = "cluster-large";
    }

    if (this.visible_) {
      var pos = this.getPosFromLatLng_(this.center_);
      this.div_.style.cssText = this.createCss(pos);
      this.div_.innerHTML = this.sums_.text;
      this.div_.classList.add("gmap-custom-cluster");
      this.div_.classList.add(clusterSizeClass);
    }

    var panes = this.getPanes();
    panes.overlayMouseTarget.appendChild(this.div_);

    var that = this;
    google.maps.event.addDomListener(this.div_, "click", function () {
      that.triggerClusterClick();
    });
  };

  jQuery(document).ready(function () {

    $("#map-wrapper-front-block-map").addClass("map-loading-data");

    $(".sidebar-close, .sidebar-tabs ul li a").click(function (e) {
      e.preventDefault();
      $(this)
        .parents(".sidebar")
        .toggleClass("collapsed")
        .find(".sidebar-tabs ul li")
        .toggleClass("active");
    });

    setTimeout(function () {
      $("#impactmap_popup_onload").show();
      $("#map-wrapper-front-block-map").removeClass("map-loading-data");
    }, 6000);

    $(document).on("click", ".impactmap_popup_onload-close-btn, .explore-more-btn", function () {
      $("#impactmap_popup_onload, .front-block-map-overlay").hide();
    });

    $(document).on("click", ".close-map-popup", function () {
      $(".gm-style-iw-a").hide();
    });
    $(document).on("click", ".map-wrapper-front-block-map .project-share .export-link", function (e) {
      e.preventDefault();
      $(this).next().toggle();
    });

    jQuery(".filter-icon").on("click", function () {
      
      jQuery(".gm-style-iw-a").hide(); 
      jQuery(".gmap_sidebar_left .sidebar-left").addClass("collapsed");

    });

    jQuery(".map_full_screen_controller button").on("click", function () {
      jQuery("#map-wrapper-front-block-map").toggleClass( 'mapfullscreen', 'slow' );
      jQuery('body').toggleClass( 'map_full_mode' );

      if( jQuery("#map-wrapper-front-block-map").hasClass('mapfullscreen') ) {
        jQuery(".map_full_screen_controller button").html( '<img src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2018%2018%22%3E%0A%20%20%3Cpath%20fill%3D%22%23333%22%20d%3D%22M4%2C4H0v2h6V0H4V4z%20M14%2C4V0h-2v6h6V4H14z%20M12%2C18h2v-4h4v-2h-6V18z%20M0%2C14h4v4h2v-6H0V14z%22%2F%3E%0A%3C%2Fsvg%3E%0A" alt="" style="height: 18px; width: 18px;">' );
      } else {
        jQuery(".map_full_screen_controller button").html( '<img src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2018%2018%22%3E%0A%20%20%3Cpath%20fill%3D%22%23666%22%20d%3D%22M0%2C0v2v4h2V2h4V0H2H0z%20M16%2C0h-4v2h4v4h2V2V0H16z%20M16%2C16h-4v2h4h2v-2v-4h-2V16z%20M2%2C12H0v4v2h2h4v-2H2V12z%22%2F%3E%0A%3C%2Fsvg%3E%0A" alt="" style="height: 18px; width: 18px;">' );
      }

      
    });

    /**
     * project_technologies_filter on click.
     *
     * Filter settings start
     */
    jQuery(".project_technologies_filter li a ").on("click", function () {
      jQuery(this).toggleClass("selected");

      var project_technologies_array = [];
      jQuery(".project_technologies_filter li .selected").each(function () {
        project_technologies_array.push(jQuery(this).attr("data-slug"));
      });

      var project_partners_array = [];
      jQuery(".project_partners_filter li .selected").each(function () {
        project_partners_array.push(jQuery(this).attr("data-slug"));
      });

      var term_array = [
        { project_technologies: project_technologies_array },
        { project_partners: project_partners_array },
      ];
      selectedTaxonomy = JSON.stringify(term_array);
      //selectedTaxonomy = selectedTaxonomy.substring(1, selectedTaxonomy.length-1);

      //console.log( selectedTaxonomy );
      getProjectMarkers();
    });

    /**
     * reset button on click.
     *
     */
    jQuery("#reset-map").on("click", function () {
      
      jQuery( '.sidebar-left' ).addClass( 'collapsed' );
      jQuery(".project_technologies_filter li a").removeClass("selected");
      jQuery(".project_partners_filter li a").removeClass("selected");
      jQuery( '.sidebar-left .sidebar-tabs' ).hide();
      jQuery('.map_filter_complete_project').prop('checked', false).trigger("change");
      initFrontMap();

    });

    jQuery("#reset-share-map").on("click", function () {

      var uri = window.location.toString();
      uri = uri.split('?')[0];
      window.location.replace( uri );

    });

    /**
     * project_partners_filter on click.
     */
    jQuery(".project_partners_filter li a ").on("click", function () {
      jQuery(this).toggleClass("selected");

      var project_partners_array = [];
      jQuery(".project_partners_filter li .selected").each(function () {
        project_partners_array.push(jQuery(this).attr("data-slug"));
      });

      var project_technologies_array = [];
      jQuery(".project_technologies_filter li .selected").each(function () {
        project_technologies_array.push(jQuery(this).attr("data-slug"));
      });

      var term_array = [
        { project_technologies: project_technologies_array },
        { project_partners: project_partners_array },
      ];

      //console.log( term_array );

      selectedTaxonomy = JSON.stringify(term_array);
      //selectedTaxonomy = selectedTaxonomy.substring(1, selectedTaxonomy.length-1);

      //console.log( selectedTaxonomy );
      getProjectMarkers();
    });
  });

  /**
   * Clear project_technologies_filter on click.
   */
  $(document).on("click", ".project_technologies_clear", function () {
    jQuery(".project_technologies_filter li .selected").each(function () {
      jQuery(this).removeClass("selected");
    });

    var project_technologies_array = [];
    jQuery(".project_technologies_filter li .selected").each(function () {
      project_technologies_array.push(jQuery(this).attr("data-slug"));
    });

    var project_partners_array = [];
    jQuery(".project_partners_filter li .selected").each(function () {
      project_partners_array.push(jQuery(this).attr("data-slug"));
    });

    var term_array = [
      { project_technologies: project_technologies_array },
      { project_partners: project_partners_array },
    ];

    if ($(".map_filter_complete_project").is(":checked")) {
      completeProject = 1;
    } else {
      completeProject = 0;
    }

    selectedTaxonomy = JSON.stringify(term_array);
    //selectedTaxonomy = selectedTaxonomy.substring(1, selectedTaxonomy.length-1);

    //console.log( selectedTaxonomy );
    getProjectMarkers();
  });

  /**
   * Clear project_partners_filter on click.
   */
  $(document).on("click", ".project_partners_clear", function () {
    jQuery(".project_partners_filter li .selected").each(function () {
      jQuery(this).removeClass("selected");
    });

    var project_partners_array = [];
    jQuery(".project_partners_filter li .selected").each(function () {
      project_partners_array.push(jQuery(this).attr("data-slug"));
    });

    var project_technologies_array = [];
    jQuery(".project_technologies_filter li .selected").each(function () {
      project_technologies_array.push(jQuery(this).attr("data-slug"));
    });

    var term_array = [
      { project_technologies: project_technologies_array },
      { project_partners: project_partners_array },
    ];

    //console.log( term_array );

    if ($(".map_filter_complete_project").is(":checked")) {
      completeProject = 1;
    } else {
      completeProject = 0;
    }

    selectedTaxonomy = JSON.stringify(term_array);
    //selectedTaxonomy = selectedTaxonomy.substring(1, selectedTaxonomy.length-1);

    //console.log( selectedTaxonomy );
    getProjectMarkers();
  });

  $(document).on("change", ".map_filter_complete_project", function () {
    var project_partners_array = [];
    jQuery(".project_partners_filter li .selected").each(function () {
      project_partners_array.push(jQuery(this).attr("data-slug"));
    });

    var project_technologies_array = [];
    jQuery(".project_technologies_filter li .selected").each(function () {
      project_technologies_array.push(jQuery(this).attr("data-slug"));
    });

    var term_array = [
      { project_technologies: project_technologies_array },
      { project_partners: project_partners_array },
    ];

    selectedTaxonomy = JSON.stringify(term_array);

    if ($(this).is(":checked")) {
      completeProject = 1;
    } else {
      completeProject = 0;
    }

    getProjectMarkers();
  });

  /**
   * Filter settings end.
   */

  /**
   * Explore More button click.
   */

  $(document).on("click", ".more-button", function () {
    var data_project_id = jQuery(this).attr("data-project_id");

    var pdImageSlider = jQuery("#map-pdImageSlider").val();
    var pdChangeImageafter = jQuery("#map-pdChangeImageafter").val();
    var pdProjectTechnologies = jQuery("#map-pdProjectTechnologies").val();
    var pdProjectPartners = jQuery("#map-pdProjectPartners").val();
    var pdProjectStatus = jQuery("#map-pdProjectStatus").val();
    var pdProjectDescriptions = jQuery("#map-pdProjectDescriptions").val();
    var pdProjectAddress = jQuery("#map-pdProjectAddress").val();
    var pdSharingOptions = jQuery("#map-pdSharingOptions").val();
    var pdShowVIdeo = jQuery("#map-pdShowVIdeo").val();

    pdChangeImageafter = pdChangeImageafter * 1000;

    var data_obj = {
      action: "impactmap_explore_more_button",
      data_project_id: data_project_id,
      pdImageSlider: pdImageSlider,
      pdChangeImageafter: pdChangeImageafter,
      pdProjectTechnologies: pdProjectTechnologies,
      pdProjectPartners: pdProjectPartners,
      pdProjectStatus: pdProjectStatus,
      pdProjectDescriptions: pdProjectDescriptions,
      pdProjectAddress: pdProjectAddress,
      pdSharingOptions: pdSharingOptions,
      pdShowVIdeo: pdShowVIdeo,
      page_url: impactMap.mapPage,
    };
    jQuery.ajax({
      type: "POST",
      url: impactMap.ajaxurl,
      data: data_obj,
      dataType: "html",
      success: function (data) {
        jQuery(".gmap_sidebar_left .sidebar-content").html(data);
        jQuery(".gmap_sidebar_left .sidebar-tabs").show();
        jQuery(".gmap_sidebar_left .sidebar-left").removeClass("collapsed");

        jQuery(
          ".map-wrapper-front-block-map .left_sidebar_gallery .gallery"
        ).bxSlider({
          adaptiveHeight: true,
          auto: true,
          pager: true,
          // AUTO
          pause: pdChangeImageafter,
          autoStart: true,
          autoDirection: "next",
          autoHover: false,
          autoDelay: 0,
          autoSlideForOnePage: false,
        });

        $(".map-youtube-video").magnificPopup({
          disableOn: 700,
          type: "iframe",
          iframe: {
            patterns: {
              youtube: {
                index: "youtube.com/",
                id: function (url) {
                  return url;
                },
                src: "%id%",
              },
            },
          },
          mainClass: "mfp-fade",
          removalDelay: 160,
          preloader: false,
          fixedContentPos: false,
        });

        /**
         * Hide project popup.
         */
        jQuery(".gm-style-iw-a").hide(); 

      },
      error: function (objAJAXRequest, strError) {
        alert("error function executed: " + strError);
      },
      beforeSend: function () {
        $("#map-wrapper-front-block-map").addClass("map-loading-data");
      },
      complete: function () {
        $("#map-wrapper-front-block-map").removeClass("map-loading-data");
      },
    });
  });
})(jQuery);
