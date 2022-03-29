var $ = jQuery;

jQuery( document ).ready(function() {
  $('body').on('click', '.enter_oauth_token_button', function(){
    var entered_token = $(".enter_oauth_token").val();
    var current_site_url = $( ".current_site_url" ).val();

    if( entered_token !== "" ) {

      jQuery.ajax({
        url: ajax_object.ajaxurl,
        type: 'POST',
        data: {
          action: 'entered_token_validation',
          entered_token: entered_token,
          current_site_url: current_site_url,
        },
        success: function (data) {

          if( data == "false" ) {
            $('.error_message').show();
            $('#incorrect_token').show();
            $('.enter_oauth_token').css( "border", "1px solid red" );
            $('.import_settings').hide();
          } else {
            $('.error_message').hide();
            $('#incorrect_token').hide();
            $('.enter_oauth_token').css( "border", "1px solid #7e8993" );
            $('#correct_token').show();
            $('.success_message').show();
            $('.import_settings').show();
            $('#edit-token').show();
            $('.enter_oauth_token').attr('readonly');
          }

        },
        beforeSend: function() {
          $('.error_message').hide();
          $('.enter_oauth_token').css( "border", "1px solid #7e8993" );
          $('#incorrect_token').hide();
          $('.image_status').show();

          $('#correct_token').hide();
          $('.success_message').hide();
        },
        complete: function(){
          $('.image_status').hide();
        },

      });

    }

  });

  // Show validate button on editing the saved token.
  $('#edit-token').click(function () {
    $(this).hide();
    $('.success_message').hide();
    $('.enter_oauth_token_button').show();
    $('.enter_oauth_token').removeAttr('readonly');
  });

  $('body').on('click', '#sync_now_button', function(){

    var language = $("input[name='language']").val();
    var current_site_url = $(".current_site_url").val();
    var enter_oauth_token = $(".enter_oauth_token").val();
    var cat = [];
    var tags = [];

    if( language=== "en" ) {

      $.each($("input[name='emp_english_category[]']:checked"), function(){
        cat.push($(this).val());
      });
      $.each($("input[name='emp_english_tags[]']:checked"), function(){
        tags.push($(this).val());
      });
    } else {
      $.each($("input[name='emp_arabic_category[]']:checked"), function(){
        cat.push($(this).val());
      });
      $.each($("input[name='emp_arabic_tags[]']:checked"), function(){
        tags.push($(this).val());
      });
    }

    jQuery.ajax({
      url: ajax_object.ajaxurl,
      type: 'POST',
      data: {
        action: 'sync_manually_event_ajax',
        entered_token: enter_oauth_token,
        current_site_url: current_site_url,
        language: language,
        cat: cat,
        sync_type: "Manual",
        tags: tags,
      },
      success: function (data) {
        var syncNowDynamicValue = document.getElementsByClassName("sync_now_dynamic_value")[0];
        if( syncNowDynamicValue ) {
          syncNowDynamicValue.innerHTML = data;
          $('.sync_now_dynamic_value').show();
        }

      },
      beforeSend: function () {
        $('.image_status').show();
        $('#wizard-main').css( "opacity", "0.5" );
      },
      complete: function () {
        $('.image_status').hide();
        $('#wizard-main').css( "opacity", "1" );
      },
    });

  });

  $('body').on('click', '#update_cat_tags_settings', function(){

    var cat = [];
    var tags = [];

    $.each($("input[name='emp_english_category[]']:checked"), function(){
      cat.push($(this).val());
    });
    $.each($("input[name='emp_english_tags[]']:checked"), function(){
      tags.push($(this).val());
    });

    $.each($("input[name='emp_arabic_category[]']:checked"), function(){
      cat.push($(this).val());
    });
    $.each($("input[name='emp_arabic_tags[]']:checked"), function(){
      tags.push($(this).val());
    });

    jQuery.ajax({
      url: ajax_object.ajaxurl,
      type: 'POST',
      data: {
        action: 'dff_update_cat_tags',
        cat: cat,
        tags: tags,
      },
      success: function (data) {
        window.location.href = "/wp-admin/admin.php?page=cep-settings&update=yes";
      },
      beforeSend: function () {
        $('.image_status').show();
        $('#wizard-main').css( "opacity", "0.5" );
      },
      complete: function () {
        $('.image_status').hide();
        $('#wizard-main').css( "opacity", "1" );
      },
    });

  });


  $('body').on('click', '#reset_plugin_button', function(){
    $("#dff_reset_model").show();
  });

  $('body').on('click', '.dff-modal-close', function(){
    $("#dff_reset_model").hide();
  });

  $('body').on('click', '#reset_plugin_confirmed', function(){
    var reset_plugin = $(".reset_plugin").val();
    var site_url = $(".site_url").val();

    if( reset_plugin === "reset" ) {
      $(".reset_plugin").css( "border", "1px solid #7e8993" );
      jQuery.ajax({
        url: ajax_object.ajaxurl,
        type: 'POST',
        data: {
          action: 'dff_reset_plugin',
        },
        success: function (data) {
          window.location.href = site_url+"/wp-admin/admin.php?page=event_setup_wizard&reset=yes";
        },
        beforeSend: function () {
          $('.image_status').show();
          $('#wizard-main').css( "opacity", "0.5" );
        },
        complete: function () {
          $('.image_status').hide();
          $('#wizard-main').css( "opacity", "1" );
        },
      });
    } else {
      $(".reset_plugin").css( "border", "1px solid red" );
    }

  });

  var ajax_data = $( ".hidden_history_data" ).val();

  jQuery('#event_history').DataTable( {
    deferRender:    true,
    "lengthChange": false,
    "searching":    false,
    "autoWidth":    false,
    "processing": true,
    "ordering": false,
    "serverSide": true,
    "ajax":{
      "url": ajax_object.ajaxurl+'?action=event_history_table_pagination',
      type: "post",
    },
    "columns": [
      { "width": "10%" },
      { "width": "30%" },
      { "width": "20%" },
      { "width": "20%" },
      { "width": "20%" },
    ]
  } );

    setTimeout(function() {

      $(".notice-success").fadeOut("slow", function(){
        $(this).hide();
      });

    }, 2000);


});

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
