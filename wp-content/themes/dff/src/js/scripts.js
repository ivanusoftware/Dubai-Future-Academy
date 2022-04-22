import "@fancyapps/fancybox";
import 'jquery-nice-select/js/jquery.nice-select.js';
import 'jquery-steps/build/jquery.steps.js';

import Swiper, { Navigation } from 'swiper';

console.log('I am testing!!!');
const $ = jQuery.noConflict();
(function ($) {
    const phpParams = php_params;
    console.log( phpParams);
    // dffSliderToLesson();
    // dffGalleryFancybox();
    // dffAccordion();
    /** 
    * Ajax filter by taxonomy on the archive page
    */
    $(".archive-courses .courses-categories #tabs-nav .courses-cat a").on("click", function (e) {
        e.preventDefault();
        $(".archive-courses .courses-categories #tabs-nav .courses-cat a").parent().removeClass("active");
        let active = $(this).parent();
        active.addClass("active");

        const taxId = $(this).attr("tax_id");
        const data = {
            action: "courses_tax_ajax",
            tax_id: taxId,
        };
        // console.log(taxId);
        $.ajax({
            type: "POST",
            url: courses_ajax.url,
            data: data,
        })
            .done(function (response) {
                $(".archive-courses .archive-courses-list").html(response);
            })
            .fail(function (response) {
                console.log(response);
            });
    });

    /**
     * Ajax switches between modules and 
     * lessons on the course page in the my account 
     */
    $(document).on('click', '.course-sidebar .accordion-content .tab-item', '', function (e) {
        e.preventDefault();
        const tabItem = $('.accordion-content ul li'); 
        const moduleIndex  = $(this).attr('module-index');
        const lessonIndex  = $(this).attr('lesson-index');        
        const lessonTestId = $(this).attr('lesson-test-id');        
        const courseId = $(".modules-course").find(".course-sidebar").attr('course-id');
        tabItem.removeClass('active');
        $(this).addClass('active');
        const data = {
            action: "upload_lesson_ajax",
            module_index: moduleIndex,
            lesson_index: lessonIndex,
            course_id: courseId,
            lesson_test_id: lessonTestId,
        };
        // console.log(data);
        $.ajax({
            type: "POST",
            url: courses_ajax.url,
            data: data,
            beforeSend: function () {
                // $('#loader').show().parent().parent().addClass('loader-wrap');
                $('#loader').show();
            },
            // complete: function() {

            // }             
        })
            .done(function (response) {
                $(".main-content .content .lesson-container").html('<div class="lesson-wrapper">' + response + '</div>');
                dffSliderToLesson();
                dffGalleryFancybox();
                // dffAccordion();
                $(window.wp.mediaelement.initialize);
                $('#loader').hide();
                // console.log(response);
            }) 
            .fail(function (response) {
                console.log(response);
            });

        return true;
    });
    
    /**
     * Ajax upload Exam url 
     */
    $(document).on('click', '.accordion-head.exam-tab-item', function (e) {
        e.preventDefault();
        const examPostId = $(this).attr('exam-post-id');        
        const moduleType = $(this).attr('module-type');   
        const courseId   = $(".modules-course").find(".course-sidebar").attr('course-id');            
        const data = {
            action: "upload_exam_ajax",
            exam_post_id: examPostId,
            course_id: courseId,
            module_type: moduleType,
        };        
        $.ajax({
            type: "POST",
            url: courses_ajax.url,
            data: data,
            beforeSend: function () {
                $('#loader').show();
            },               
        })
            .done(function (response) {
                $(".main-content .content .lesson-container").html('<div class="lesson-wrapper">' + response + '</div>');               
                $('#loader').hide();                
            })
            .fail(function (response) {
                console.log(response);
            });

        return true;
    });

    /**
    * Ajax switches between tabs on the course page
    * in the my account
    */
    $(document).on('click', '.my-courses-tabs .tabs-nav-my-courses a', function (e) {
        e.preventDefault();
        $('.tabs-nav li').removeClass('active');
        $(this).parent().addClass('active');
        const courseId = $("#content").find(".my-courses-tabs").attr('course-id');
        const tabId = $(this).attr('tab-id');
        const data = {
            action: "tabs_lesson_ajax",
            main_tab_id: tabId,
            course_id: courseId,
        };
        // console.log(data);
        $.ajax({
            type: "POST",
            url: courses_ajax.url,
            data: data,
        })
            .done(function (response) {
                $(".my-courses-tabs-content .tab-wrapper ").html(response);
                dffAccordion();
                dffSliderToLesson();
                dffGalleryFancybox();
                window.wp.mediaelement.initialize();
            })
            .fail(function (response) {
                console.log(response);
            });

        return true;
    });
    
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
        })
            .done(function (response) {                
                if(response.success){                    
                    window.location.replace(phpParams.site_url + '/my-courses/');
                }
            })
            .fail(function (response) {
                console.log(response);
            });
 
        return true;
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

    // $('.gallery-slider .gallery-fancybox img').each(function() {
    //     console.log($(this).width());
    //     console.log('----------------------');
    //     console.log($(this).height());
    //     if($(this).width() > $(this).height()) {
    //           $(this).addClass("horizontal"); 
    //         // $(this).attr("title", "horizontal");
    //     } else {
    //         $(this).addClass("vertical");
    //         // $(this).attr("title", "vertical");
    //     }        
    // });

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


        $("#quiz").steps({
            headerTag: ".course-quiz__step-title",
            bodyTag: ".course-quiz__step",
        });

        let lastStepId = $('.course-quiz__step:last-child').attr("data-step");
        $('.lastStepId').html(lastStepId);

        $('.actions a').click(function () {
            let currentStepId = $('.course-quiz__step-title.current').text();
            $('.currentStepId').html(currentStepId);
        })


    });




})(jQuery);

/**
 * Slider to Lesson
 */
function dffSliderToLesson() {
    Swiper.use([Navigation]);
    $(".gallery-slider").each(function () {
        // Getting slides quantity before slider clones them
        this.slidesQuantity = this.querySelectorAll(".swiper-slide").length;

        // Swiper initialization
        const swiper = new Swiper(this, {
            speed: 1000,
            slidesPerView: 'auto',
            // loop: false,
            //   pagination: {
            //     el: this.querySelector(".swiper-pagination")
            //   },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            on: {
                init: updSwiperNumericPagination,
                slideChange: updSwiperNumericPagination
            },
            // on('slideChangeTransitionEnd', function() {
            //     console.log('slideChangeTransitionEnd');
            // });
        });
    });

}

/**
 * Updates the number of switch - numeric pagination
 * to slider
 */
function updSwiperNumericPagination() {
    this.el.querySelector(".swiper-counter").innerHTML = '<span class="count">' + (this.realIndex + 1) + '</span>/<span class="total">' + this.el.slidesQuantity + "</span>";
}


// Fancybox for gallery
function dffGalleryFancybox() {
    $('.gallery-fancybox').fancybox({
        buttons: [
            "zoom",
            "slideShow",
            "fullScreen",
            "thumbs",
            "close"
        ]
    });
}
/**
 * dff adapter for accordion
 */
function dffAccordion() {
    /**
    * Accordion for modules
    */
    $('.accordion .accordion-item:nth-child(1) .accordion-head').addClass('active');
    $('.accordion .accordion-item:nth-child(1) .accordion-content').slideDown();
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
}

