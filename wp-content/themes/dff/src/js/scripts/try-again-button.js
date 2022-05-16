/**
    * Add class active to sidebar tab item 
    * when clicking on next or back buttons.
    * @param {*} moduleIndex 
    * @param {*} lessonIndex 
    */
export default function dffTryAgaineButton(moduleIndex, lessonIndex) {
    const $ = jQuery.noConflict();
    const tabAccordionItem = $('.accordion-item.module_' + moduleIndex + ' .accordion-content ul li');
    const accordionModuleIndex = $('.accordion-item.module_' + moduleIndex).attr('module-i');
    const lessonModileIndex = [];
    tabAccordionItem.each(function () {
        lessonModileIndex.push($(this).attr('lesson-index'));
    });

    if (accordionModuleIndex === moduleIndex && $.inArray(lessonIndex, lessonModileIndex)) {
        $(".accordion-item.module_" + moduleIndex + " .accordion-content ul li[lesson-index*=" + lessonIndex + "]").addClass('active');
    }
    if ($(".accordion-item.module_1 .accordion-content ul li[lesson-index*=1]").hasClass('active')) {
        $(".accordion-item.module_1 .accordion-content ul li[lesson-index*=1]").removeClass('active');
    }
}