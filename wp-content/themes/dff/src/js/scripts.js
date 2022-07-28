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
import './ajax/ajax-continue-course';

console.log('I am testing!!!');
const $ = jQuery.noConflict();
// const chart = null;
(function ($) {
    const phpParams = php_params;
    const modal = $('.modal-frame');
    const overlay = $('.modal-overlay');

    /* Need this to clear out the keyframe classes so they dont clash with each other between ener/leave. Cheers. */
    modal.bind('webkitAnimationEnd oanimationend msAnimationEnd animationend', function (e) {
        if (modal.hasClass('state-leave')) {
            $modal.removeClass('state-leave');
        }
    });

    $('.close').on('click', function () {
        overlay.removeClass('state-show');
        modal.removeClass('state-appear').addClass('state-leave');
    });

    $('.open').on('click', function () {
        overlay.addClass('state-show');
        modal.removeClass('state-leave').addClass('state-appear');
    });
    /**
     * Add a new course to the user.
     * ajax.
     */
    $('.go-to-courses').on('click', function (e) {
        e.preventDefault();
        const courseId = $(this).attr('course_id');
        const courseIdLang = $(this).attr('course_id_lang');
        const slug = $(this).attr('slug');
        const data = {
            action: "add_lesson_to_user_ajax",
            course_id: courseId,
            course_id_lang: courseIdLang,
        };
        console.log(data);
        $.ajax({
            type: "POST",
            url: courses_ajax.url,
            data: data,
            dataType: 'JSON',
        }).done(function (response) {
            console.log(response);
            if (response.success) {
                console.log(response.success);
                window.location.replace(phpParams.site_url + '/my-courses/' + slug);
            }
        }).fail(function (response) {
            console.log(response);
        });
    });

    /**
    * Ajax to delete a course from user profile.
    * 
    */
    $('.leave-course-popup .buttons .leave-course').on('click', function (e) {
        e.preventDefault();
        const courseId = $(this).attr('course-id');
        const courseIdLang = $(this).attr('course_id_lang');
        const data = {
            action: "leave_course_ajax",
            course_id: courseId,
            course_id_lang: courseIdLang,
        };
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

    $('.close-popup').on('click', function (e) {
        e.preventDefault();
        $('.single-course-modal .modal').removeClass('is-visible');
        $('html').css('overflow', 'auto');
        location.reload();
    });


    // // Shows the open - auth popup
    // $('.open-auth-popup').on('click', function (e) {
    //     e.preventDefault();
    //     console.log('open-auth-popup')
    //     const isVisible = $('.register-login-module .modal').toggleClass('is-visible');
    //     if (isVisible.hasClass('is-visible')) {
    //         $('html').css('overflow', 'hidden');
    //     } else {
    //         $('html').css('overflow', 'auto');
    //     }
    // });

    // Shows the open - auth popup
    $('.leave-course-popup .modal-toggle').on('click', function (e) {
        e.preventDefault();
        const isVisible = $('.leave-course-popup .modal').toggleClass('is-visible');
        if (isVisible.hasClass('is-visible')) {
            $('html').css('overflow', 'hidden');
        } else {
            $('html').css('overflow', 'auto');
        }
    });

    // Register the login tab
    $('.register-login-module .register-login-tab .register-login-tabs-nav a').on('click', function () {
        // Check for active
        $('.register-login-module .register-login-tab .register-login-tabs-nav li').removeClass('active');
        $(this).parent().addClass('active');

        // Display active tab
        const currentTab = $(this).attr('href');
        $('.register-login-module .register-login-tab .register-login-tabs-content .register-login-tab-wrapper').hide();
        $(currentTab).show();
        return false;
    });



    function checkInputs(e, step, init = false) {
        var allSelect = false;
        var allInput = false;
        var select_count = 0;

        if (!init) {
            if (isStepValid(e, step)) {
                $('a[href="#finish"], a[href="#next"]').removeClass('failed');
            } else {
                $('a[href="#finish"], a[href="#next"]').addClass('failed');
            }
        } else {
            $('input[type="text"]').each(function () {
                $(this).on('change paste keyup', function (event) {
                    let step = event.target.closest('.course-quiz__step').getAttribute('data-step')
                    if (isStepValid(event, step, false, true)) {
                        $('a[href="#finish"], a[href="#next"]').removeClass('failed');
                    } else {
                        $('a[href="#finish"], a[href="#next"]').addClass('failed');
                    }
                })
            });

            $('input[type="checkbox"], select, input[type="radio"]').on('change', function (event) {
                $('.nice-select').niceSelect('reset')
                let step = event.target.closest('.course-quiz__step').getAttribute('data-step')
                if (isStepValid(event, step, false, true)) {
                    $('a[href="#finish"], a[href="#next"]').removeClass('failed');
                } else {
                    $('a[href="#finish"], a[href="#next"]').addClass('failed');
                }
            });
        }

    }


    function isStepValid(e, step, init = false, isEvent = false) {
        let isValid = true
        let quize = e.target.closest('.course-quiz')
        // console.log(step);
        if (!isEvent) {
            step = step + 1
        }
        // console.log(step);
        let stepWrap = quize.querySelectorAll(`.course-quiz__step`)

        for (const wr of stepWrap) {

            if (wr.getAttribute('data-step') === (step).toString()) {
                // console.log(wr.getAttribute('data-step'));
                // console.log((step).toString());
                stepWrap = Element
                stepWrap = wr
                // console.log(stepWrap);
            }
        }




        if (!stepWrap.length && stepWrap) {
            let inputsRad = stepWrap.querySelectorAll('[type=radio]')
            let res = {}
            if (inputsRad && inputsRad.length) {
                res.isRadValid = false
                for (const rad of inputsRad) {

                    if (rad.checked) {
                        res.isRadValid = true
                    }
                }
            }
            let inputsCheck = stepWrap.querySelectorAll('[type=checkbox]')
            if (inputsCheck && inputsCheck.length) {
                res.isCheckValid = false
                for (const ch of inputsCheck) {
                    if (ch.checked) {
                        res.isCheckValid = true
                    }
                }
            }
            let inputsText = stepWrap.querySelectorAll('[type=text], textarea')
            if (inputsText && inputsText.length) {
                res.isTextValid = false
                for (const tx of inputsText) {
                    if (tx.value.length) {
                        res.isTextValid = true
                    }
                }
            }

            let inputsSelects = stepWrap.querySelectorAll('select')
            if (inputsSelects && inputsSelects.length) {
                res.isslValid = false
                let subRes = true
                for (const sl of inputsSelects) {
                    let option = sl.querySelector('option')
                    console.log(option.value);
                    if (sl.value === option.value) {
                        subRes = false

                    }
                }
                if (subRes) {
                    res.isslValid = true
                }

            }
            for (const iterator of Object.values(res)) {
                if (!iterator) {
                    isValid = false
                }
            }
        } else {
            return false
        }
        return isValid
    }

    $(document).ajaxComplete(function () {
        $('.course-quiz select').niceSelect();


        var form = $("#quiz");
        form.validate({
            errorPlacement: function (error, element) {
                if (element.attr("type") == "checkbox") {
                    error.insertBefore(element.parent());
                } else {
                    error.insertBefore(element);
                }

                $('a[href="#next"]').addClass('failed');
            },
        });
        $("#quiz").steps({
            headerTag: ".course-quiz__step-title",
            bodyTag: ".course-quiz__step",
            labels: {
                finish: $('.phrase_done').text(),
                next: $('.phrase_next').text(),
                previous: $('.phrase_back').text(),
            },
            onInit: function (e, step) {
                $('a[href="#next"]').addClass('failed');
                checkInputs(e, step, true);
            },
            onStepChanging: function (event, currentIndex, newIndex) {
                checkInputs(event, newIndex);
                if (currentIndex > newIndex) {
                    return true;
                }

                form.validate().settings.ignore = ":disabled,:hidden";

                return form.valid();
            },
            onFinishing: function (event, currentIndex, newIndex) {
                form.validate().settings.ignore = ":disabled";
                return form.valid();
            },

            onFinished: function (event, currentIndex) {

                var quizData = {};
                $.each($(this).serializeArray(), function (index, value) {
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
                const moduleId = $(this).data('module-id');
                const type = $(this).data('type');                
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
                        var result = response.percentage;
                        $('.course-quiz__progress-result span').text(result + '%');
                        $('.course-quiz__steps').text($('.phrase_result').text());
                        $('.course-quiz__content').hide();
                        $('.course-quiz__progress').removeClass('active');

                        if (result >= 80) {    
                            // console.log('I am exam!!!!');                       
                            $(".module_" + moduleId + " .accordion-content ul li.module-lesson-test").add($(".module_" + moduleId + " .accordion-head")).addClass('complete');                               
                            if(type == 'exam'){                             
                                $(".exam-tab-item").addClass('complete');                                                                             
                            }
                            $('.course-quiz__progress[data-success="thanks"]').addClass('active');
                            $('.course-quiz__progress[data-success="success"]').addClass('active');
                        } else {
                            $('.course-quiz__progress[data-success="fail"]').addClass('active');
                            $(".module_" + moduleId + " .accordion-content ul li.module-lesson-test").add($(".module_" + moduleId + " .accordion-head")).removeClass('complete');                            
                            if(type == 'exam'){                                
                                $(".exam-tab-item").removeClass('complete');                                                                             
                            }
                        }

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

        $('input[type="checkbox"]').on('keypress', function (event) {
            if (event.which === 13) {
                this.checked = !this.checked;
            }
        });

        if ($(window).width() < 768) {
            showLess();

            $('.accordion-content li, .my-progres-modules li').click(function () {
                $('html, body').animate({
                    scrollTop: $(".main-content").offset().top - 71
                }, 500);
            })
        }
    });

    $('.accordion .open-module .accordion-head').add('.single-course .accordion .accordion-head').on('click', function () {
        if ($(this).hasClass('active')) {
            $(this).siblings('.accordion-content').slideUp();
            $(this).removeClass('active');
        } else {
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


    if ($(window).width() < 768) {
        showLess();
    }

    function showLess() {
        $('.show-more').show();
        $('.show-more').click(function () {
            $(this).closest('.desc').children().slideDown(300);
            $('.show-more').hide();
            $('.show-less').show();
        })
        $('.show-less').click(function () {

            $(this).closest('.desc').children().not(":first-child").slideUp(300);
            $('html, body').animate({
                scrollTop: $(".main-content").offset().top - 71
            }, 500);
            $('.show-less').hide();
            $('.show-more').show();
        })
    }
    $(".course-header-wrapper.course-header-my-tabs .hero-side-course").on('click', function () {
        $('html, body').animate({
            scrollTop: $("#tabs-content").offset().top - 60
        }, 1000);
    });

    $(document).on('click', '.lesson-complete input[type=checkbox][name=lesson-complete]', function (e) {
        const moduleIndex = $(this).attr('module-index');
        const lessonIndex = $(this).attr('lesson-index');
        const courseId = $(this).attr('course-id');
        const courseIdLang = $(this).attr('course-id-lang');
        const state = ($(this).is(':checked')) ? '1' : '0';
        const data = {
            action: "state_lesson_ajax",
            course_id: courseId,
            lesson_index: lessonIndex,
            module_index: moduleIndex,
            course_id_lang: courseIdLang,
            checked_box: state,
            // course_id_lang: courseIdLang,
        };
        $.ajax({
            type: "POST",
            url: courses_ajax.url,
            data: data,
            dataType: 'JSON',
        }).done(function (response) {
            if (response.success) {
                // console.log(response.success);
                // window.location.replace(phpParams.site_url + '/my-courses/' + slug);
            }
        }).fail(function (response) {
            console.log(response);
        });

        $(".module_" + moduleIndex + " .accordion-content ul li.tab-item[lesson-index='" + lessonIndex + "']").toggleClass('complete');
    });
    
   
})(jQuery);