import "@fancyapps/fancybox";
import 'jquery-nice-select/js/jquery.nice-select.js';
import 'jquery-steps/build/jquery.steps.js';

import Swiper, { Navigation } from 'swiper';
import './ajax/ajax-categories';
import './ajax/custom';

console.log('I am testing!!!');
const $ = jQuery.noConflict();
(function ($) {
    dffAccordion();

    /**
     * Ajax switches between modules and 
     * lessons on the course page in the my account 
     */
    $(document).on('click', '.course-sidebar .accordion-content .tab-item', function (e) {
        e.preventDefault();
        const tabItem = $('.accordion-content ul li');
        const moduleIndex = $(this).attr('module-index');
        const lessonIndex = $(this).attr('lesson-index');
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
        }).done(function (response) {
            $(".main-content .content .lesson-container").html('<div class="lesson-wrapper">' + response + '</div>');
            dffSliderToLesson();
            dffGalleryFancybox();
            // dffAccordion();
            $(window.wp.mediaelement.initialize);
            $('#loader').hide();
            // console.log(response);
        }).fail(function (response) {
            console.log(response);
        });
    });



    $(document).on('click', '.lesson-header .back', '', function (e) {
        e.preventDefault();
        const moduleIndex = $(this).attr('module-index');
        const lessonIndex = $(this).attr('lesson-index');
        const lessonTestId = $(".course-sidebar").find(".accordion-item.module_" + moduleIndex + " .module-lesson-test").attr('lesson-test-id');
        const courseId = $(".modules-course").find(".course-sidebar").attr('course-id');
        $(".course-sidebar").find(".accordion-head.active").parent().addClass('active-btn');
        dffNextBackButtonsActive(moduleIndex, lessonIndex);
        dffAjaxLessons(moduleIndex, lessonIndex, lessonTestId, courseId);
    });

    $(document).on('click', '.lesson-header .next', function (e) {
        e.preventDefault();
        const moduleIndex = $(this).attr('module-index');
        const lessonIndex = $(this).attr('lesson-index');
        const countLessonRow = $(".modules-course").find(".accordion-head.active").attr('count-lesson-row');
        const lessonTestId = $(".course-sidebar").find(".accordion-item.module_" + moduleIndex + " .module-lesson-test").attr('lesson-test-id');
        const courseId = $(".modules-course").find(".course-sidebar").attr('course-id');
        $(".course-sidebar").find(".accordion-head.active").parent().addClass('active-btn');
        dffNextBackButtonsActive(moduleIndex, lessonIndex);
        dffAjaxLessons(moduleIndex, lessonIndex, lessonTestId, courseId, countLessonRow)
    });

    /**
     * Add class active to sidebar tab item
     * when click to next or back buttons
     * @param {*} moduleIndex 
     * @param {*} lessonIndex 
     */
    function dffNextBackButtonsActive(moduleIndex, lessonIndex) {
        const tabAccordionItem = $('.accordion-content ul li');
        tabAccordionItem.each(function () {
            const accordionModuleIndex = $(this).attr('module-index');
            const accordionLessonIndex = $(this).attr('lesson-index');
            if (accordionModuleIndex === moduleIndex && accordionLessonIndex === lessonIndex) {
                $(this).addClass('active');
            } else {
                $(this).removeClass('active');
            }
        });
    }

    /**
    * Ajax switches between tabs on the course page
    * in the my account
    */
    $(document).on('click', '.my-courses-tabs .tabs-nav-my-courses a', function (e) {
        e.preventDefault();
        $('.tabs-nav li').removeClass('active');
        $(this).parent().addClass('active');
        const courseId = $("#content").find(".my-courses-tabs").attr('course-id');
        console.log(courseId);
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
        }).done(function (response) {
            $(".my-courses-tabs-content .tab-wrapper ").html(response);
            dffAccordion();
            dffSliderToLesson();
            dffGalleryFancybox();
            window.wp.mediaelement.initialize();
        }).fail(function (response) {
            console.log(response);
        });
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


    // Upload a lesson ajax
    /**
     * 
     * @param {*} moduleIndex 
     * @param {*} lessonIndex 
     * @param {*} lessonTestId 
     * @param {*} courseId 
     * @param {*} countLessonRow 
     */
    function dffAjaxLessons(moduleIndex, lessonIndex, lessonTestId, courseId, countLessonRow) {
        const data = {
            action: "upload_lesson_ajax",
            module_index: moduleIndex,
            lesson_index: lessonIndex,
            course_id: courseId,
            lesson_test_id: lessonTestId,
            count_lesson_row: countLessonRow,
        };
        $.ajax({
            type: "POST",
            url: courses_ajax.url,
            data: data,
            beforeSend: function () {
                $('#loader').show();
            }
        }).done(function (response) {
            $(".main-content .content .lesson-container").html('<div class="lesson-wrapper">' + response + '</div>');
            dffSliderToLesson();
            dffGalleryFancybox();
            $(window.wp.mediaelement.initialize);
            $('#loader').hide();
        }).fail(function (response) {
            console.log(response);
        });
    }

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

})(jQuery);
