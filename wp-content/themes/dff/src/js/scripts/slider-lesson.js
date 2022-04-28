import Swiper, { Navigation } from 'swiper';
const dffSliderToLesson = () => {

    Swiper.use([Navigation]);
    jQuery(".gallery-slider").each(function () {
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
    /**
    * Updates the number of switch - numeric pagination
    * to slider
    */
    function updSwiperNumericPagination() {
        this.el.querySelector(".swiper-counter").innerHTML = '<span class="count">' + (this.realIndex + 1) + '</span>/<span class="total">' + this.el.slidesQuantity + "</span>";
    }

}

export default dffSliderToLesson;