/******/ (() => { // webpackBootstrap
/******/ 	// The require scope
/******/ 	var __webpack_require__ = {};
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
/*!***************************!*\
  !*** ./src/js/scripts.js ***!
  \***************************/
console.log('I am testing!!!');
(function ($) {
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
            success: function (response) {
                $(".archive-courses .archive-courses-list").html(response);
            },
            error: function (response) {
                console.log(response);
            },
        });
        return true;
    });
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

    $('.tabs-nav a').on('click', function () {
        console.log('tab click');
        // Check for active
        $('.tabs-nav li').removeClass('active');
        $(this).parent().addClass('active');

        // Display active tab
        const currentTab = $(this).attr('href');

        $('.tabs-content .tab-wrapper').hide();
        $(currentTab).show();

        return false;
    });

    $('.course-sidebar .accordion-content ul li').click(function () {
        const selectID = $(this).attr('id');
        console.log(selectID);
        $('.accordion-content ul li').removeClass('active');
        $(this).addClass('active');
        $('div.box').removeClass('selected');
        $('.' + selectID + '-box').addClass('selected');
    });

    $(window).resize(function () {
        if ($('.vertical-tabs').innerWidth() > 608) {
            if ($('div.selected').length) {
            } else {
                $('div.box:first').addClass('selected');
            }
        }
    });

})(jQuery);

// function vert_tabs() {
//     $('ul.checklist-select li').click(function () {
//         var selectID = $(this).attr('id');
//         $('ul.checklist-select li').removeClass('active');
//         $(this).addClass('active');
//         $('div.box').removeClass('selected');
//         $('.' + selectID + '-box').addClass('selected');
//     });
// }
})();

// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
/*!*****************************!*\
  !*** ./src/scss/style.scss ***!
  \*****************************/
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin

})();

/******/ })()
;
//# sourceMappingURL=main.js.map