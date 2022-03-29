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
        const locationHref = location.href;
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
})(jQuery);

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