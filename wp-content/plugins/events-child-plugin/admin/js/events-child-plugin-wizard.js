var $ = jQuery;

jQuery( document ).ready(function() {

  //Initialize tooltips
  $('.nav-tabs > li a[title]').tooltip();

  $('body').on('click', '#wizard_validate_token', function(e){

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

          if (data == "false") {
            $(".enter_oauth_token").css( "border", "1px solid red" );
            $(".error_token_message").show();
          } else {
            $(".enter_oauth_token").css( "border", "1px solid #7e8993" );
            $(".error_token_message").hide();
            var $active = $('.wizard .nav-tabs li.active');
            $active.next().removeClass('disabled');
            nextTab($active);
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

    }

  });

  $('body').on('click', '#wizard_language_select', function(e) {

    var entered_token = $(".enter_oauth_token").val();
    var current_site_url = $(".current_site_url").val();
    var language = $("input[name='language']:checked").val();

    if( $("input[name='language']").is(':checked')) {
      jQuery.ajax({
        url: ajax_object.ajaxurl,
        type: 'POST',
        data: {
          action: 'entered_language_ajax',
          entered_token: entered_token,
          current_site_url: current_site_url,
          language: language,
        },
        success: function (data) {
          var selectEventCategoriesTags = document.getElementsByClassName("select_event_categories_tags")[0];
          if( selectEventCategoriesTags ) {
            selectEventCategoriesTags.innerHTML = data;
          }
          var $active = $('.wizard .nav-tabs li.active');
          $active.next().removeClass('disabled');
          nextTab($active);
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
    }

  });

  $('body').on('click', '#wizard_select_cat_tag', function(e) {

    var language = $("input[name='language']:checked").val();
    var cat = [];
    var tags = [];

    if( language === "arabic" ) {

      $.each($("input[name='emp_arabic_category[]']:checked"), function(){
        cat.push($(this).val());
      });
      $.each($("input[name='emp_arabic_tags[]']:checked"), function(){
        tags.push($(this).val());
      });

    } else {

      $.each($("input[name='emp_english_category[]']:checked"), function(){
        cat.push($(this).val());
      });
      $.each($("input[name='emp_english_tags[]']:checked"), function(){
        tags.push($(this).val());
      });

    }

    if( cat == "" && tags == "" ) {
      return false;
    } else {
      var $active = $('.wizard .nav-tabs li.active');
      $active.next().removeClass('disabled');
      nextTab($active);
    }

  });

  $('body').on('click', '#wizard_sync_button', function(e){

    var entered_token = $(".enter_oauth_token").val();
    var current_site_url = $(".current_site_url").val();
    var language = $("input[name='language']:checked").val();

    var cat = [];
    var tags = [];

    if( language === "arabic" ) {

      $.each($("input[name='emp_arabic_category[]']:checked"), function(){
        cat.push($(this).val());
      });
      $.each($("input[name='emp_arabic_tags[]']:checked"), function(){
        tags.push($(this).val());
      });

    } else {

      $.each($("input[name='emp_english_category[]']:checked"), function(){
        cat.push($(this).val());
      });
      $.each($("input[name='emp_english_tags[]']:checked"), function(){
        tags.push($(this).val());
      });

    }

    var number_of_events = "";

    if( $("input[name='all_events']").is(':checked')) {
      number_of_events = "0";
    } else {
      number_of_events = $(".event_number").val();
    }

    if( cat == "" && tags == "" ) {
      return false;
    } else {

      jQuery.ajax({
        url: ajax_object.ajaxurl,
        type: 'POST',
        data: {
          action: 'wizard_sync_event_ajax',
          entered_token: entered_token,
          current_site_url: current_site_url,
          number_of_events: number_of_events,
          language: language,
          cat: cat,
          tags: tags,
        },
        success: function (data) {
          var wizardDynamicCount = document.getElementsByClassName("wizard_dynamic_count")[0];
          if( wizardDynamicCount ) { wizardDynamicCount.innerHTML = data; }

          $('.wizard-thank-you').show();
          $('#wizard-main').hide();
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

    }

  });

  $('.all_events').change(function() {
    if(this.checked) {
      $(".event_number").attr("disabled", true);
    } else {
      $(".event_number").attr("disabled", false);
    }

  });

  //Wizard
  $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

    var $target = $(e.target);

    if ($target.parent().hasClass('disabled')) {
      return false;
    }
  });

  $(".prev-step").click(function (e) {
    var $active = $('.wizard .nav-tabs li.active');
    prevTab($active);
  });

  jQuery("body").on("click", "#select-all-eng-cat", function(event){
      jQuery(".emp_english_category").prop("checked", $(this).prop("checked"));
  });

  jQuery("body").on("click", ".emp_english_category", function(event){
    if (!$(this).prop("checked")) {
      jQuery("#select-all-eng-cat").prop("checked", false);
    }
  });

  jQuery("body").on("click", "#select-all-eng-tags", function(event){
    jQuery(".emp_english_tags").prop("checked", $(this).prop("checked"));
  });

  jQuery("body").on("click", ".emp_english_tags", function(event){
    if (!$(this).prop("checked")) {
      jQuery("#select-all-eng-tags").prop("checked", false);
    }
  });

  jQuery("body").on("click", "#select-all-ar-cats", function(event){
    jQuery(".emp_arabic_category").prop("checked", $(this).prop("checked"));
  });

  jQuery("body").on("click", ".emp_arabic_category", function(event){
    if (!$(this).prop("checked")) {
      jQuery("#select-all-ar-cats").prop("checked", false);
    }
  });

  jQuery("body").on("click", "#select-all-ar-tags", function(event){
    jQuery(".emp_arabic_tags").prop("checked", $(this).prop("checked"));
  });

  jQuery("body").on("click", ".emp_arabic_tags", function(event){
    if (!$(this).prop("checked")) {
      jQuery("#select-all-ar-tags").prop("checked", false);
    }
  });

});

function nextTab(elem) {
  $(elem).next().find('a[data-toggle="tab"]').click();
}

function prevTab(elem) {
  $(elem).prev().find('a[data-toggle="tab"]').click();
}
