(function ($) {
    const phpParams = php_params;
   /**
     * Ajax upload Exam url 
     */
    $(document).on('click', '.accordion-head.exam-tab-item', function (e) {
        e.preventDefault();
        const examPostId = $(this).attr('exam-post-id');
        const moduleType = $(this).attr('module-type');
        const courseId = $(".modules-course").find(".course-sidebar").attr('course-id');
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
        }).done(function (response) {
            if (response.success) {
                window.location.replace(phpParams.site_url + '/my-courses/');
            }
        }).fail(function (response) {
            console.log(response);
        });
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