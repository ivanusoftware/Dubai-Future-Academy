/**
    * Add class active to sidebar tab item 
    * when clicking on next or back buttons.
    * @param {*} moduleIndex 
    * @param {*} lessonIndex 
    */
export default function dffNextBackButtonsActive(moduleIndex, lessonIndex) {
    const $ = jQuery.noConflict();
    const tabAccordionItem = $('.accordion-content ul li');
    console.log();
    tabAccordionItem.each(function () {
        const accordionModuleIndex = $(this).attr('module-index');
        const accordionLessonIndex = $(this).attr('lesson-index');
        if (accordionModuleIndex === moduleIndex && accordionLessonIndex === lessonIndex) {
            console.log('Tetsts');
            $(this).addClass('active');
        } else {
            $(this).removeClass('active');
        }
    });
}




