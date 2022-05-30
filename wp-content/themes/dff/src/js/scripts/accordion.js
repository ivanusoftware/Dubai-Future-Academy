/**
 * Accordion for modules
 */

const dffAccordion = () => {
    const $ = jQuery.noConflict();
    $('.accordion .open-module .accordion-head').add('.single-course .accordion .accordion-head').on('click', function () {
        if ($(this).hasClass('active')) {
            $(this).siblings('.accordion-content').slideUp();
            $(this).removeClass('active');
            // $(this).siblings('.accordion-content').slideToggle();
            // $(this).toggleClass('active');
            console.log('active1');
        }
        else {
            console.log('active2');
            $('.accordion-content').slideUp();
            $('.accordion-head').removeClass('active');
            $(this).siblings('.accordion-content').slideToggle();
            $(this).toggleClass('active');
        }
    });
}

export default dffAccordion;
