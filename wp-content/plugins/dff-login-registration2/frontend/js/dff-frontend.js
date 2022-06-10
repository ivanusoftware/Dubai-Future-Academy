jQuery(function ($) { // use jQuery code inside this to avoid "$ is not defined" error
	 // Shows the open - auth popup
     $('.open-auth-popup').on('click', function (e) {
        e.preventDefault();
        console.log('open-auth-popup');
        const isVisible = $('.open-auth-popup .modal').toggleClass('is-visible');
        if (isVisible.hasClass('is-visible')) {
            $('html').css('overflow', 'hidden');
        } else {
            $('html').css('overflow', 'auto');
        }
    });
});