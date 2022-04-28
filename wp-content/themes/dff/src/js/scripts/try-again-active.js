/**
    * Clicking the button "try again" adds an active class 
    * to the module accordion (module 1, module 2 etc).
    * @param {*} moduleIndex 
    */
 export default function dffTryAgainActive(moduleIndex) {
    const tabAccordionItem = $('.accordion .accordion-item .accordion-head');
    tabAccordionItem.each(function () {
        const accordionModuleI = $(this).parent().attr('module-i');
        if (accordionModuleI === moduleIndex) {
            $(this).siblings('.accordion-content').slideToggle();
            $(this).toggleClass('active');
        }
    });
}