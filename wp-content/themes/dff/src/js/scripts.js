import "@fancyapps/fancybox";
import Swiper, { Navigation } from 'swiper';

console.log('I am testing!!!');
const $ = jQuery.noConflict();
(function ($) {
    // dffSliderToLesson();
    // dffGalleryFancybox();
    dffAccordion();

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


    $(document).on('click', '.course-sidebar .accordion-content .tab-item', function (e) {
        e.preventDefault();
        const tabItem = $('.accordion-content ul li');
        // const selectID = $(this).attr('id');
        const moduleIndex = $(this).attr('module-index');
        const lessonIndex = $(this).attr('lesson-index');
        const courseId = $(".modules-course").find(".course-sidebar").attr('course-id');
        tabItem.removeClass('active');
        $(this).addClass('active');
        const data = {
            action: "upload_lesson_ajax",
            module_index: moduleIndex,
            lesson_index: lessonIndex,
            course_id: courseId,
        };
        // console.log(data);
        $.ajax({
            type: "POST",
            url: courses_ajax.url,
            data: data,
            beforeSend: function () {
                $('#loader').show().parent().parent().addClass('loader-wrap');
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
            $('#loader').hide().parent().parent().removeClass('loader-wrap');
            // console.log(response);
        })
        .fail(function (response) {
            console.log(response);
        });

        return true;
    });

    $(".my-courses-tabs .tabs-nav-my-courses").on("click", "a", function (e) {
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


})(jQuery);

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

// swiper.on('slideChangeTransitionEnd', function() {
//     console.log('slideChangeTransitionEnd');
//   });

//   swiper.on('slideChangeTransitionStart', function() {
//     console.log('slideChangeTransitionStart');
//   });

function updSwiperNumericPagination() {
    this.el.querySelector(".swiper-counter").innerHTML = '<span class="count">' + (this.realIndex + 1) + '</span>/<span class="total">' + this.el.slidesQuantity + "</span>";
}


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
function dffAccordion() {
    /**
    * Accordion for modules
    */
    $('.accordion .accordion-item:nth-child(1) .accordion-head').addClass('active');
    $('.accordion .accordion-item:nth-child(1) .accordion-content').slideDown();
    
    $('.accordion-head').on('click', function () {
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

