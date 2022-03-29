var $ = jQuery;

jQuery(document).ready(function () {


  $('body').on('click', '.popup_mobile_background_image', function(e){
    e.preventDefault();

    var button = $(this),
    aw_uploader = wp.media({
        title: 'Custom image',
        library : {
            uploadedTo : wp.media.view.settings.post.id,
            type : 'image'
        },
        button: {
            text: 'Use this image'
        },
        multiple: false
    }).on('select', function() {
        var attachment = aw_uploader.state().get('selection').first().toJSON();
        $('#popup_mobile_background_image').val(attachment.url);
        $('.background_mobile_image_view').html( '<img class="term-image" src="'+attachment.url+'" alt="popup_mobile_background_image">' );
    })
    .open();
  });

 // $('#project_pilot_date').datepicker();
  $('#pilot_year_picker').datepicker({
    changeYear: true,
    showButtonPanel: true,
    dateFormat: 'yy',
    onClose: function(dateText, inst) { 
        var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
        $(this).datepicker('setDate', new Date(year, 1));
    }
  });

  $(".date-picker-year").focus(function () {
    $(".ui-datepicker-month").hide();
  });

  $('#completion_year_picker').datepicker({
    changeYear: true,
    showButtonPanel: true,
    dateFormat: 'yy',
    onClose: function(dateText, inst) { 
        var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
        $(this).datepicker('setDate', new Date(year, 1));
    }
  });




  /**
   * Remove enter keypress from location input.
   */

  jQuery('input[name="project_search_location"]').keydown(function(event){
    return event.key != "Enter";
  });

  /**
   * Load project_completion_date logically.
   */
  var project_status = $('.project_status').find(":selected").text();
  
  if( 'Completed' === project_status ) {
    jQuery('#project_completion_date').show();
  } else {
    jQuery('#project_completion_date').hide();
  }
  
  $('.project_status').on('change', function (e) {
    var optionSelected = $("option:selected", this).text();
    
    if( 'Completed' === optionSelected ) {
      jQuery('#project_completion_date').show();
    } else {
      jQuery('#project_completion_date').hide();
    }
    
  });

	var switchStatus = false;
	jQuery("#myonoffswitch").on('change', function() {
		if ( jQuery(this).is(':checked') ) {
			switchStatus = $(this).is(':checked');
			jQuery('.project_hide_show').val('hide');
		}
		else {
			switchStatus = jQuery(this).is(':checked');
			jQuery('.project_hide_show').val('show');
		}
	});

	/**
	 * Project Image slider.
	 */
	jQuery('.upload_gallery_button').click(function(event){
        var current_gallery = jQuery( this ).closest( 'label' );
        console.log(current_gallery);


        if ( event.currentTarget.id === 'clear-gallery' ) {
            //remove value from input
            current_gallery.find( '.gallery_values' ).val( '' ).trigger( 'change' );

            //remove preview images
            current_gallery.find( '.gallery-screenshot' ).html( '' );
            return;
        }

        // Make sure the media gallery API exists
        if ( typeof wp === 'undefined' || !wp.media || !wp.media.gallery ) {
            return;
        }
        event.preventDefault();

        // Activate the media editor
        var val = current_gallery.find( '.gallery_values' ).val();
        var final;

        if ( !val ) {
            final = '[gallery ids="0"]';
        } else {
            final = '[gallery ids="' + val + '"]';
        }
        var frame = wp.media.gallery.edit( final );
        console.log(wp);
        console.log(wp.media);
        console.log(wp.media.gallery);
        console.log(frame + " -- frame");


        frame.state( 'gallery-edit' ).on('update', function( selection ) {
            console.log('coming');

                //clear screenshot div so we can append new selected images
                current_gallery.find( '.gallery-screenshot' ).html( '' );

                var element, preview_html = '', preview_img;
                var ids = selection.models.map(
                    function( e ) {
                        element = e.toJSON();
                        preview_img = typeof element.sizes.thumbnail !== 'undefined' ? element.sizes.thumbnail.url : element.url;
                        preview_html = "<div class='screen-thumb'><img src='" + preview_img + "'/></div>";
                        current_gallery.find( '.gallery-screenshot' ).append( preview_html );
                        return e.id;
                    }
                );

                current_gallery.find( '.gallery_values' ).val( ids.join( ',' ) ).trigger( 'change' );
            }
        );
        return false;
    });
    
    $('body').on('click', '.popup_background_image', function(e){
        e.preventDefault();
  
        var button = $(this),
        aw_uploader = wp.media({
            title: 'Custom image',
            library : {
                uploadedTo : wp.media.view.settings.post.id,
                type : 'image'
            },
            button: {
                text: 'Use this image'
            },
            multiple: false
        }).on('select', function() {
            var attachment = aw_uploader.state().get('selection').first().toJSON();
            $('#popup_background_image').val(attachment.url);
            $('.background_image_view').html( '<img class="term-image" src="'+attachment.url+'" alt="popup_background_image">' );
        })
        .open();
    });


    $('body').on('click', '.popup_logo_image', function(e){
        e.preventDefault();
  
        var button = $(this),
        aw_uploader = wp.media({
            title: 'Custom image',
            library : {
                uploadedTo : wp.media.view.settings.post.id,
                type : 'image'
            },
            button: {
                text: 'Use this image'
            },
            multiple: false
        }).on('select', function() {
            var attachment = aw_uploader.state().get('selection').first().toJSON();
            $('#popup_logo_image').val(attachment.url);
            $('.logo_image_view').html( '<img class="term-image" src="'+attachment.url+'" alt="popup_background_image">' );
        })
        .open();
    });

    /**
     * Validate add project fields.
     */

    $(document).on('click', '.submitbox #publish, #save-action', function (e) {
      e.stopPropagation();

      $('.for_project_map').remove();

      errorMsgs = '';

      

      if (0 !== $('body.post-type-dff-project-map').length) {
        
          //alert( selected );

          if ( '' === $('input[name="post_title"]').val() ) {
              errorMsgs += '<p>Please set Project Title.</p>';
          }

          if ( '' === jQuery('#dff_project_description').val() ) {
              errorMsgs += '<p>Please set Project Description.</p>';
          }

          if ( '' === jQuery('#dff_short_description').val() ) {
            errorMsgs += '<p>Please set Short Description.</p>';
          }

          // if ( '' === jQuery('input[name="project_pilot_date"]').val() ) {
          //   errorMsgs += '<p>Please select Project Pilot Date.</p>';
          // }

          if ( '' === jQuery('.project_status :selected').val() ) {
            errorMsgs += '<p>Please select Project Status.</p>';
          }

          if ( '' === jQuery('.project_primary_technologies :selected').val() ) {
            errorMsgs += '<p>Please select Project Primary Technologies.</p>';
          }
          
          if ( '' === jQuery('input[name="project_search_location"]').val() ) {
            errorMsgs += '<p>Please select Project Location.</p>';
          }

          if ( '' === jQuery('input[name="project_latitude"]').val() ) {
            errorMsgs += '<p>Please select Project Latitude.</p>';
          }

          if ( '' === jQuery('input[name="project_longitude"]').val() ) {
            errorMsgs += '<p>Please select Project Longitude.</p>';
          }


          var project_technologieschecklist = [];
          $('#project_technologieschecklist input:checked').each(function() {
            project_technologieschecklist.push($(this).attr('name'));
          });
          
          if ( 0 === project_technologieschecklist.length ) {
            errorMsgs += '<p>Please select Project Technologies.</p>';
          }

          var project_partnerschecklist = [];
          $('#project_partnerschecklist input:checked').each(function() {
            project_partnerschecklist.push($(this).attr('name'));
          });

          if ( 0 === project_partnerschecklist.length ) {
            errorMsgs += '<p>Please select Project partners.</p>';
          }
          
      }

      if( '' !==  errorMsgs) {
          $('<div class="error notice for_project_map">'+errorMsgs+'</div>').insertAfter('.wp-header-end');
          return false;
      }
      var currentId = $(this).attr("id");
      $(this).toggleClass('saving');
      if ($(this).hasClass('saving')) {
          saveNextFunction(currentId);
      } else {
          return false;
      }

  });

});

function saveNextFunction(updateButton) {
  
  if('publish' === updateButton ){
    $('#publish').trigger('click');
  }else if( 'save-post' === updateButton  ){
    $('#save-post').trigger('click');
  }

}

function openSettingsTab(evt, cityName) {
	// Declare all variables
	var i, tabcontent, tablinks;
  
	// Get all elements with class="tabcontent" and hide them
	tabcontent = document.getElementsByClassName("tabcontent");
	for (i = 0; i < tabcontent.length; i++) {
	  tabcontent[i].style.display = "none";
	}
  
	// Get all elements with class="tablinks" and remove the class "active"
	tablinks = document.getElementsByClassName("tablinks");
	for (i = 0; i < tablinks.length; i++) {
	  tablinks[i].className = tablinks[i].className.replace(" active", "");
	}
  
	// Show the current tab, and add an "active" class to the button that opened the tab
	document.getElementById(cityName).style.display = "block";
	evt.currentTarget.className += " active";
  }

  /**
   * Map rendor function with auto complete search API in Add/Edit project
   */
  function initProjectMap() {

    //Check map for the specific page.
    if( document.getElementById("map") === null || 0 === document.getElementById("map").length ) {
      return false;
    }

    var project_latitude = parseFloat( document.getElementById('project_latitude').value );
    var project_longitude = parseFloat( document.getElementById('project_longitude').value );
    var project_search_location = document.getElementById('pac-input').value;
    var zoom = 0;

    var project_latitude_nan = isNaN(project_latitude);
    var project_longitude_nan = isNaN(project_longitude);

    if( false === project_latitude_nan && false === project_longitude_nan ) {
      
      project_latitude = project_latitude;
      project_longitude = project_longitude;
      zoom = 15;

    } else {
      
      project_latitude = 25.2048493;
      project_longitude = 55.2707828;
      zoom = 12;
    }

    const map = new google.maps.Map(document.getElementById("map"), {
      center: { lat: project_latitude, lng: project_longitude },
      zoom: zoom,
      panControl: false,
      zoomControl: false,
      mapTypeControl: false,
      streetViewControl: false,
      styles: [{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#333333"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#FFFFFF"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#FEFEFE"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#FEFEFE"},{"lightness":17},{"weight":1.2}]},{"featureType":"administrative.locality","elementType":"geometry","stylers":[{"saturation":"34"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#F7F8F8"},{"lightness":20}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#F5F5F5"},{"lightness":21}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#ECEEED"},{"lightness":21}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"color":"#F1F4F2"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#FFFFFF"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"lightness":29},{"weight":0.2},{"color":"#F1F4F2"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry.stroke","stylers":[{"color":"#F1F4F2"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#FFFFFF"},{"lightness":18}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#F1F4F2"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#FFFFFF"},{"lightness":16}]},{"featureType":"road.local","elementType":"geometry.stroke","stylers":[{"color":"#F1F4F2"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#F2F2F2"},{"lightness":19}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#CBD2D3"},{"lightness":17}]}]
    });

    if( false === project_latitude_nan && false === project_longitude_nan ) {
        var myLatlng = new google.maps.LatLng(project_latitude, project_longitude);
        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            title: project_search_location,
        });
    }

    // Create the search box and link it to the UI element.
    const input = document.getElementById("pac-input");
    const searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    // Bias the SearchBox results towards current map's viewport.
    map.addListener("bounds_changed", () => {
      searchBox.setBounds(map.getBounds());
    });
    let markers = [];
    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener("places_changed", () => {
      const places = searchBox.getPlaces();
  
      if (places.length == 0) {
        return;
      }
      // Clear out the old markers.
      markers.forEach((marker) => {
        marker.setMap(null);
      });
      markers = [];
      // For each place, get the icon, name and location.
      const bounds = new google.maps.LatLngBounds();
      places.forEach((place) => {
        if (!place.geometry) {
          console.log("Returned place contains no geometry");
          return;
        }
        const icon = {
          url: place.icon,
          size: new google.maps.Size(71, 71),
          origin: new google.maps.Point(0, 0),
          anchor: new google.maps.Point(17, 34),
          scaledSize: new google.maps.Size(25, 25),
        };
        // Create a marker for each place.
        markers.push(
          new google.maps.Marker({
            map,
            icon,
            title: place.name,
            position: place.geometry.location,
          })
        );
  
        if (place.geometry.viewport) {
          // Only geocodes have viewport.
          bounds.union(place.geometry.viewport);
        } else {
          bounds.extend(place.geometry.location);
        }

        document.getElementById('project_latitude').value = place.geometry.location.lat();
        document.getElementById('project_longitude').value = place.geometry.location.lng();
      });
      map.fitBounds(bounds);
    });
  }

  google.maps.event.addDomListener(window, 'load', initProjectMap);

  function openSettingsTab(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;
    
    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
    
    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    
    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
    }