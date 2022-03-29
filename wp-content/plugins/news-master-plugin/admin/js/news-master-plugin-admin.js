var $ = jQuery;
jQuery( document ).ready(function() {

  jQuery( ".add_sites_button" ).on( "click", function() {
    var add_sites_field = jQuery(".add_sites_field").val();

    if( add_sites_field === "" ){
      jQuery( ".add_sites_field" ).css( "border", "1px solid red" );
    }

    if( add_sites_field !== "" ) {
      jQuery.ajax({
        url: ajax_object.ajaxurl,
        type: 'POST',
        data:{
          action: 'add_sites_action',
          add_sites_field: add_sites_field,
        },
        success: function( data ){
            var addSiteTable = document.getElementsByClassName("add_site_table")[0];
          addSiteTable.innerHTML += data;
          jQuery( ".add_sites_field" ).val("").css( "border", "1px solid #7e8993" );

        }
      });
    }

  });

  jQuery(document).on("click", '.delete_site_button', function(event) {

    var retVal = confirm("Are you sure you wish to delete this site?");

    if( retVal == true ) {

      var delete_site_button = jQuery(this).parent().siblings('.siteurl').text();
      var tr = jQuery(this).closest('tr');

      jQuery.ajax({
        url: ajax_object.ajaxurl,
        type: 'POST',
        data:{
          action: 'delete_sites_action',
          delete_site_button: delete_site_button,
        },
        success: function( data ){
          tr.fadeOut("normal", function() {
            $(this).remove();
          });
        }
      });

    } else {
      return false;
    }

  });

  jQuery('.sub_category input').change(function() {

    if(this.checked) {
      $(this).parents('ul').prev('li').find('input').prop( "checked", true );
    } else {
      $(this).parents('ul').prev('li').find('input').prop( "checked", false );
    }

  });

});

function openSetting(evt, cityName) {
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
