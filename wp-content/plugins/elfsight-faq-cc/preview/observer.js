(function(window){"use strict";/*!
 * 
 * 	elfsight.com
 * 
 * 	Copyright (c) 2020 Elfsight, LLC. ALL RIGHTS RESERVED
 * 
 */
!function(t){var e={};function o(n){if(e[n])return e[n].exports;var r=e[n]={i:n,l:!1,exports:{}};return t[n].call(r.exports,r,r.exports,o),r.l=!0,r.exports}o.m=t,o.c=e,o.d=function(t,e,n){o.o(t,e)||Object.defineProperty(t,e,{configurable:!1,enumerable:!0,get:n})},o.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return o.d(e,"a",e),e},o.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},o.p="",o(o.s=0)}([function(t,e){(window.eapps=window.eapps||{}).observer=function(t,e,o){var n,r=t.setPropertyVisibility;t.$watch("widget.data.layout",function(t){r("accordionIcon","accordion"===t),r("openFirstQuestionByDefault","accordion"===t),r("multipleActiveQuestions","accordion"===t)}),t.$watch("widget.data.displayCategoriesNames",function(t){r("categoryTextColor",t)}),t.$watch("widget.data.template",function(e){"clean"===e?(n=t.widget.data.itemBackgroundColor,t.widget.data.itemBackgroundColor=""):n&&(t.widget.data.itemBackgroundColor=n),t.setPropertyVisibility("itemBackgroundColor",!("clean"===e))})}}]);})(window)