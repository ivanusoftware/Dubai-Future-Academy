jQuery(function ($) { // use jQuery code inside this to avoid "$ is not defined" error
    $('.futureId li.logout a').on('click', function (e) {
        e.preventDefault();

        console.log('logout');
        // if (localStorage.length > 0 ) {
        //     console.log(localStorage);
        //     localStorage.clear();
        // }
        // // $.removeCookie("token");
        // // $.removeCookie("user");
        // Cookies.get('token');
        // Cookies.get('user');
        // location.reload();

        // const courseId = $(this).attr('course_id');
        // const courseIdLang = $(this).attr('course_id_lang');
        const data = {
            action: "dff_logout_user_ajax",
            // course_id: courseId,
            // course_id_lang: courseIdLang,
        };
        console.log(data);
        $.ajax({
            type: "POST",
            url: dff_ajax_data.url,
            data: data,
            dataType: 'JSON',
        }).done(function (response) {
            console.log(response);
            if (localStorage.length > 0) {
                // console.log(localStorage);
                localStorage.clear();
            }
            location.reload();
            window.location.reload();
        }).fail(function (response) {
            console.log(response);
        });
    });


});