import "@fancyapps/fancybox";
import 'jquery-nice-select/js/jquery.nice-select.js';
import 'jquery-steps/build/jquery.steps.js';
//import './scripts/jquery.validate.js';
import './ajax/ajax-categories';
import './ajax/tab-module';
import './ajax/sidebar-tab-item';
import './ajax/test-try-again';
import './ajax/test-try-again-exam';
import './ajax/back-next-buttons';

console.log('I am testing!!!');
const $ = jQuery.noConflict();
// const chart = null;
(function ($) {
    const phpParams = php_params;

    /**
     * Add a new course to the user.
     * ajax.
     */
    $('.single-course-modal .buttons a.go-to-courses').on('click', function (e) {
        e.preventDefault();
        const courseId = $(this).attr('course_id');
        const data = {
            action: "add_lesson_to_user_ajax",
            course_id: courseId,
        };
        // console.log(data);
        $.ajax({
            type: "POST",
            url: courses_ajax.url,
            data: data,
            dataType: 'JSON',
        }).done(function (response) {
            if (response.success) {
                window.location.replace(phpParams.site_url + '/my-courses/');
            }
        }).fail(function (response) {
            console.log(response);
        });
    });

    /**
    * Open modal window.
    * toggle the visibility of the course modal.
    */
    $('.modal-toggle').on('click', function (e) {
        e.preventDefault();
        const isVisible = $('.single-course-modal .modal').toggleClass('is-visible');
        if (isVisible.hasClass('is-visible')) {
            $('html').css('overflow', 'hidden');
        } else {
            $('html').css('overflow', 'auto');
        }
    });



    $(document).ajaxComplete(function () {
        $('.course-quiz select').niceSelect();

        var form = $("#quiz");
        form.validate({
            errorPlacement: function errorPlacement(error, element) { element.before(error); },
        });


        $("#quiz").steps({
            headerTag: ".course-quiz__step-title",
            bodyTag: ".course-quiz__step",
            //onStepChanging: function (event, currentIndex, newIndex) { return true; },
            //onStepChanged: function (event, currentIndex, priorIndex) { }, 
            //onCanceled: function (event) { },
            //onFinishing: function (event, currentIndex) { return true; }, 

            onStepChanging: function (event, currentIndex, newIndex)
            {
                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            },
            onFinishing: function (event, currentIndex)
            {
                form.validate().settings.ignore = ":disabled";
                return form.valid();
            },

            onFinished: function (event, currentIndex) {
                var quizData = {};
                $.each($(this).serializeArray(), function(index, value) {
                  if (value['name'].endsWith('[]')) {
                      var name = value['name'];
                      name = name.substring(0, name.length - 2);
                      if (!(name in quizData)) {
                          quizData[name] = [];
                      }
                      quizData[name].push(value['value']);
                  } else {
                      quizData[value['name']] = value['value'];
                  }
                });
                var data = new FormData();
                data.append('action', 'quiz_answers');
                data.append('type', $(this).data('type'));
                data.append('quiz_id', $(this).data('quiz-id'));
                data.append('module_id', $(this).data('module-id'));
                data.append('course_id', $(this).data('course-id'));
                data.append('user_id', $(this).data('user-id'));
                data.append('form', JSON.stringify(quizData));
                $.ajax({
                    type: "POST",
                    enctype: 'multipart/form-data',
                    url: courses_ajax.url,
                    data: data,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        $('#loader').show();
                    }
                }).done(function (response) {
                    $('#loader').hide();
                    if (response.result) {
                      alert('Your result: ' + response.percentage + '%');
                    } else {
                      console.log(response);
                    }
                }).fail(function (response) {
                    console.log(response);
                });
            },
        });

        let lastStepId = $('.course-quiz__step:last-child').attr("data-step");
        $('.lastStepId').html(lastStepId);

        $('.actions a').click(function () {
            let currentStepId = $('.course-quiz__step-title.current').text();
            $('.currentStepId').html(currentStepId);
        })
    });

    $('.accordion .open-module .accordion-head').add('.single-course .accordion .accordion-head').on('click', function () {
        if ($(this).hasClass('active')) {
            $(this).siblings('.accordion-content').slideUp();
            $(this).removeClass('active');
        }
        else {
            $('.accordion-content').slideUp();
            $('.accordion-head').removeClass('active');
            $(this).siblings('.accordion-content').slideToggle();
            $(this).toggleClass('active');
        }
    });

    // Shows the active tab. 
    $('.my-course-tab .tabs-nav a').on('click', function () {
        // Check for active
        $('.my-course-tab .tabs-nav li').removeClass('active');
        $(this).parent().addClass('active');

        // Display active tab
        const currentTab = $(this).attr('href');
        $('.my-course-tab .tabs-content .tab-wrapper').hide();
        $(currentTab).show();
        return false;
    });

    // Creates a progress tab on the my course page 
    $(document).on('click', '.my-progres-modules li a', function (e) {
        e.preventDefault();
        // Check for active
        $('.my-progress-content .my-progres-modules li').removeClass('active');
        $(this).parent().addClass('active');
        // Display active tab
        const currentTab = $(this).attr('href');
        $('.progress-container .tabs-content .progress-wrapper').hide();
        $(currentTab).show();
        return false;
    });


})(jQuery);
