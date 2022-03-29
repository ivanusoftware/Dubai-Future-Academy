/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 4);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {

/**
 * Checks if `value` is the
 * [language type](http://www.ecma-international.org/ecma-262/7.0/#sec-ecmascript-language-types)
 * of `Object`. (e.g. arrays, functions, objects, regexes, `new Number(0)`, and `new String('')`)
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is an object, else `false`.
 * @example
 *
 * _.isObject({});
 * // => true
 *
 * _.isObject([1, 2, 3]);
 * // => true
 *
 * _.isObject(_.noop);
 * // => true
 *
 * _.isObject(null);
 * // => false
 */
function isObject(value) {
  var type = typeof value;
  return value != null && (type == 'object' || type == 'function');
}

module.exports = isObject;


/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

var Symbol = __webpack_require__(2),
    getRawTag = __webpack_require__(19),
    objectToString = __webpack_require__(20);

/** `Object#toString` result references. */
var nullTag = '[object Null]',
    undefinedTag = '[object Undefined]';

/** Built-in value references. */
var symToStringTag = Symbol ? Symbol.toStringTag : undefined;

/**
 * The base implementation of `getTag` without fallbacks for buggy environments.
 *
 * @private
 * @param {*} value The value to query.
 * @returns {string} Returns the `toStringTag`.
 */
function baseGetTag(value) {
  if (value == null) {
    return value === undefined ? undefinedTag : nullTag;
  }
  return (symToStringTag && symToStringTag in Object(value))
    ? getRawTag(value)
    : objectToString(value);
}

module.exports = baseGetTag;


/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

var root = __webpack_require__(16);

/** Built-in value references. */
var Symbol = root.Symbol;

module.exports = Symbol;


/***/ }),
/* 3 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var attributes = {
  title: {
    type: 'string',
    default: ''
  },
  showZoom: {
    type: 'boolean',
    default: true
  },
  showFilter: {
    type: 'boolean',
    default: true
  },
  showProjectDetail: {
    type: 'boolean',
    default: true
  },

  exhibitionMode: {
    type: 'boolean',
    default: false
  },

  changeProjectafter: {
    type: 'string',
    default: '5'
  },

  MapLatitude: {
    type: 'string',
    default: '25.2048493'
  },

  MapLongitude: {
    type: 'string',
    default: '55.2707828'
  },

  MapZoom: {
    type: 'string',
    default: '12'
  },

  PrimaryTechnology: {
    type: 'boolean',
    default: true
  },

  Description: {
    type: 'boolean',
    default: true
  },

  SharingOptions: {
    type: 'boolean',
    default: true
  },
  pButton: {
    type: 'boolean',
    default: true
  },

  CompletedRadioButton: {
    type: 'boolean',
    default: true
  },
  ProjectTechnologies: {
    type: 'boolean',
    default: true
  },
  ProjectPartners: {
    type: 'boolean',
    default: true
  },

  pdImageSlider: {
    type: 'boolean',
    default: true
  },
  pdChangeImageafter: {
    type: 'string',
    default: '20'
  },

  pdProjectTechnologies: {
    type: 'boolean',
    default: true
  },

  pdProjectPartners: {
    type: 'boolean',
    default: true
  },

  pdProjectStatus: {
    type: 'boolean',
    default: true
  },

  pdProjectDescriptions: {
    type: 'boolean',
    default: true
  },

  pdProjectAddress: {
    type: 'boolean',
    default: true
  },
  pdSharingOptions: {
    type: 'boolean',
    default: true
  },
  pdShowVIdeo: {
    type: 'boolean',
    default: true
  },

  terms: {
    type: 'string',
    default: {}
  },
  taxonomies: {
    type: 'array',
    default: []
  }
};
/* harmony default export */ __webpack_exports__["a"] = (attributes);

/***/ }),
/* 4 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__impactmap__ = __webpack_require__(5);
/**
 * Import files
 */



/***/ }),
/* 5 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__edit__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__map_attributes__ = __webpack_require__(3);



(function (blocks, i18n, element, editor, components) {
    var __ = wp.i18n.__;
    var registerBlockType = wp.blocks.registerBlockType;
    var _wp$editor = wp.editor,
        MediaUpload = _wp$editor.MediaUpload,
        AlignmentToolbar = _wp$editor.AlignmentToolbar,
        InspectorControls = _wp$editor.InspectorControls,
        InnerBlocks = _wp$editor.InnerBlocks,
        PanelColorSettings = _wp$editor.PanelColorSettings,
        BlockAlignmentToolbar = _wp$editor.BlockAlignmentToolbar,
        RichText = _wp$editor.RichText;
    var _wp$components = wp.components,
        PanelBody = _wp$components.PanelBody,
        TextControl = _wp$components.TextControl,
        Button = _wp$components.Button,
        SelectControl = _wp$components.SelectControl,
        RangeControl = _wp$components.RangeControl,
        ToggleControl = _wp$components.ToggleControl,
        ServerSideRender = _wp$components.ServerSideRender;
    var Fragment = wp.element.Fragment;


    registerBlockType('imap/imap', {
        title: __('Project Map'),
        icon: 'location-alt',
        category: 'widgets',
        keywords: [__('row'), __('project'), __('map')],
        supports: {
            anchor: true
        },
        attributes: __WEBPACK_IMPORTED_MODULE_1__map_attributes__["a" /* default */],
        edit: __WEBPACK_IMPORTED_MODULE_0__edit__["a" /* default */],
        save: function save() {
            return null;
        }
    });
})(window.wp.blocks, window.wp.i18n, window.wp.element, window.wp.editor, window.wp.components);

/***/ }),
/* 6 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_classnames__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_classnames___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_classnames__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__map_settings__ = __webpack_require__(8);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__map_attributes__ = __webpack_require__(3);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * BLOCK: rightSideBar Row / Layout
 */

/**
 * Import External
 */
//import map from 'lodash/map';




var _wp$element = wp.element,
    Component = _wp$element.Component,
    Fragment = _wp$element.Fragment;
var _wp$editor = wp.editor,
    InnerBlocks = _wp$editor.InnerBlocks,
    InspectorControls = _wp$editor.InspectorControls,
    RichText = _wp$editor.RichText;
var _wp$components = wp.components,
    PanelBody = _wp$components.PanelBody,
    Button = _wp$components.Button,
    ButtonGroup = _wp$components.ButtonGroup,
    TextControl = _wp$components.TextControl,
    CheckboxControl = _wp$components.CheckboxControl,
    ServerSideRender = _wp$components.ServerSideRender;
var __ = wp.i18n.__;


var TwoColumnTemplate = [['core/columns', { columns: 2 }]];

//import { Map, GoogleApiWrapper } from 'google-maps-react';


var sizeTypes = [{ key: 'h1', name: __('H1') }, { key: 'h2', name: __('H2') }, { key: 'h3', name: __('H3') }, { key: 'h4', name: __('H4') }, { key: 'h5', name: __('H5') }, { key: 'h6', name: __('H6') }];

/**
 * Build the row edit
 */

var rightSideBarRowLayout = function (_Component) {
    _inherits(rightSideBarRowLayout, _Component);

    function rightSideBarRowLayout() {
        _classCallCheck(this, rightSideBarRowLayout);

        var _this = _possibleConstructorReturn(this, (rightSideBarRowLayout.__proto__ || Object.getPrototypeOf(rightSideBarRowLayout)).apply(this, arguments));

        _this.state = {
            taxonomies: [],
            taxonomies_name: [],
            termsObj: {},
            filterTermsObj: {},
            showingInfoWindow: false,
            activeMarker: {},
            selectedPlace: {}

        };

        return _this;
    }

    _createClass(rightSideBarRowLayout, [{
        key: 'componentDidMount',
        value: function componentDidMount() {
            var _this2 = this;

            wp.apiFetch({ path: '/wp/v1/all-terms' }).then(function (terms) {
                console.log(terms);
                _this2.setState({
                    termsObj: terms,
                    filterTermsObj: terms,
                    taxonomies: ['project_partners', 'project_technologies'],
                    taxonomies_name: ['Project Partners', 'Project Technologies']
                });
            });
        }
    }, {
        key: 'isEmpty',
        value: function isEmpty(obj) {
            var key = void 0;
            for (key in obj) {
                if (obj.hasOwnProperty(key)) {
                    return false;
                }
            }
            return true;
        }
    }, {
        key: 'filterTerms',
        value: function filterTerms(value, taxonomy) {
            var _this3 = this;

            var filterTerms = {};
            this.state.taxonomies.map(function (tax) {
                if (taxonomy === tax) {
                    filterTerms[tax] = _this3.state.termsObj[tax].filter(function (term) {
                        return -1 < term.name.toLowerCase().indexOf(value.toLowerCase());
                    });
                } else {
                    filterTerms[tax] = _this3.state.termsObj[tax];
                }
            });
            this.setState({ filterTermsObj: filterTerms });
        }
    }, {
        key: 'getPosts',
        value: function getPosts(value) {
            var idsStr = this.state.ids;
            var idsArray = idsStr.split(',');
            if (-1 !== idsArray.indexOf(value.toString())) {
                idsArray.splice(idsArray.indexOf(value.toString()), 1);
            } else {
                idsArray.push(value.toString());
            }
            var resultIds = idsArray.join();
            this.setState({ ids: resultIds });
        }
    }, {
        key: 'render',
        value: function render() {
            var _this4 = this;

            var _props = this.props,
                _props$attributes = _props.attributes,
                title = _props$attributes.title,
                showZoom = _props$attributes.showZoom,
                showFilter = _props$attributes.showFilter,
                showProjectDetail = _props$attributes.showProjectDetail,
                exhibitionMode = _props$attributes.exhibitionMode,
                changeProjectafter = _props$attributes.changeProjectafter,
                MapLatitude = _props$attributes.MapLatitude,
                MapLongitude = _props$attributes.MapLongitude,
                MapZoom = _props$attributes.MapZoom,
                PrimaryTechnology = _props$attributes.PrimaryTechnology,
                Description = _props$attributes.Description,
                SharingOptions = _props$attributes.SharingOptions,
                pButton = _props$attributes.pButton,
                CompletedRadioButton = _props$attributes.CompletedRadioButton,
                ProjectTechnologies = _props$attributes.ProjectTechnologies,
                ProjectPartners = _props$attributes.ProjectPartners,
                pdImageSlider = _props$attributes.pdImageSlider,
                pdChangeImageafter = _props$attributes.pdChangeImageafter,
                pdProjectTechnologies = _props$attributes.pdProjectTechnologies,
                pdProjectPartners = _props$attributes.pdProjectPartners,
                pdProjectStatus = _props$attributes.pdProjectStatus,
                pdProjectDescriptions = _props$attributes.pdProjectDescriptions,
                pdProjectAddress = _props$attributes.pdProjectAddress,
                pdSharingOptions = _props$attributes.pdSharingOptions,
                pdShowVIdeo = _props$attributes.pdShowVIdeo,
                terms = _props$attributes.terms,
                taxonomies = _props$attributes.taxonomies,
                tempTerms = _props$attributes.tempTerms,
                autoBackgroundImage = _props$attributes.autoBackgroundImage,
                autoBackgroundOverlayColor = _props$attributes.autoBackgroundOverlayColor,
                autoBackgroundOverlayOpacity = _props$attributes.autoBackgroundOverlayOpacity,
                autoHeaderTitleTextColor = _props$attributes.autoHeaderTitleTextColor,
                autoHeaderSubTitleTextColor = _props$attributes.autoHeaderSubTitleTextColor,
                setAttributes = _props.setAttributes,
                clientId = _props.clientId;


            var isCheckedTerms = {};
            if (!this.isEmpty(terms) && terms.constructor !== Object) {
                isCheckedTerms = JSON.parse(terms);
            }
            console.log(this.props.attributes);
            var backgroundStyle = {};
            autoBackgroundImage && (backgroundStyle.backgroundImage = 'url(' + autoBackgroundImage + ')');

            var headerTitleStyle = {};
            autoHeaderTitleTextColor && (headerTitleStyle.color = autoHeaderTitleTextColor);

            var headerSubTitleStyle = {};
            autoHeaderSubTitleTextColor && (headerSubTitleStyle.color = autoHeaderSubTitleTextColor);

            var setInsurer = function setInsurer(event) {
                var selected = event.target.querySelector('option:checked');
                setAttributes({ currentlyInsurer: selected.value });
                event.preventDefault();
            };

            var onSelectOwnHome = function onSelectOwnHome(event) {
                var selectedHome = event.target.value;
                var selectedClass = event.target.className;
                'components-button rectBtn own-home active' === selectedClass && setAttributes({ ownHome: '' });
                'components-button rectBtn own-home active' !== selectedClass && setAttributes({ ownHome: selectedHome ? selectedHome : '' });
            };

            var onSelectCreditScore = function onSelectCreditScore(event) {
                var selectedCreditScore = event.target.value;
                var selectedClass = event.target.className;
                'components-button rectBtn credit-score active' === selectedClass && setAttributes({ creditScore: '' });
                'components-button rectBtn credit-score active' !== selectedClass && setAttributes({ creditScore: selectedCreditScore ? selectedCreditScore : '' });
            };

            var onSelectAccidents = function onSelectAccidents(event) {
                var selectedAccident = event.target.value;
                var selectedClass = event.target.className;
                'components-button rectBtn accident-ticket active' === selectedClass && setAttributes({ accident: '' });
                'components-button rectBtn accident-ticket active' !== selectedClass && setAttributes({ accident: selectedAccident ? selectedAccident : '' });
            };

            var onSelectSr22 = function onSelectSr22(event) {
                var selectedSr22 = event.target.value;
                var selectedClass = event.target.className;
                'components-button rectBtn sr22 active' === selectedClass && setAttributes({ sr22: '' });
                'components-button rectBtn sr22 active' !== selectedClass && setAttributes({ sr22: selectedSr22 ? selectedSr22 : '' });
            };

            var onSelectDui = function onSelectDui(event) {
                var selectedDui = event.target.value;
                var selectedClass = event.target.className;
                'components-button rectBtn dui active' === selectedClass && setAttributes({ dui: '' });
                'components-button rectBtn dui active' !== selectedClass && setAttributes({ dui: selectedDui ? selectedDui : '' });
            };
            var onMarkerClick = function onMarkerClick(props, marker, e) {
                return _this4.setState({
                    selectedPlace: props,
                    activeMarker: marker,
                    showingInfoWindow: true
                });
            };

            var onMapClicked = function onMapClicked(props) {

                if (_this4.state.showingInfoWindow) {
                    _this4.setState({
                        showingInfoWindow: false,
                        activeMarker: null
                    });
                }
            };

            return wp.element.createElement(
                Fragment,
                null,
                wp.element.createElement(
                    InspectorControls,
                    null,
                    wp.element.createElement(__WEBPACK_IMPORTED_MODULE_1__map_settings__["a" /* default */], { AttributesData: this.props.attributes, setAttributes: setAttributes }),
                    wp.element.createElement(
                        'div',
                        { className: 'tag-category-wrapper' },
                        0 < this.state.taxonomies.length && wp.element.createElement(
                            Fragment,
                            null,
                            this.state.taxonomies.map(function (taxonomy, index) {
                                return undefined !== _this4.state.filterTermsObj[taxonomy] && wp.element.createElement(
                                    'div',
                                    { className: 'faq-tag' },
                                    wp.element.createElement(
                                        'div',
                                        { className: 'tag-wrapper' },
                                        wp.element.createElement(
                                            PanelBody,
                                            { title: __(_this4.state.taxonomies_name[index]), initialOpen: false, className: 'hide-panel' },
                                            wp.element.createElement(
                                                'ul',
                                                { className: 'dropdown-list tag-dropdown-list' },
                                                wp.element.createElement(
                                                    'li',
                                                    { role: 'option', className: 'dropdown-item' },
                                                    wp.element.createElement(
                                                        'span',
                                                        { className: 'filterOption' },
                                                        _this4.state.filterTermsObj[taxonomy].map(function (term, index) {
                                                            return wp.element.createElement(
                                                                Fragment,
                                                                { key: index },
                                                                wp.element.createElement(CheckboxControl, {
                                                                    checked: isCheckedTerms[taxonomy] !== undefined && -1 < isCheckedTerms[taxonomy].indexOf(term.slug),
                                                                    label: term.name,
                                                                    name: taxonomy + '[]',
                                                                    value: term.slug,
                                                                    className: 'checkbox-input',
                                                                    onChange: function onChange(isChecked) {
                                                                        var index = void 0,
                                                                            tempTerms = terms;
                                                                        if (!_this4.isEmpty(tempTerms)) {
                                                                            tempTerms = JSON.parse(tempTerms);
                                                                        }
                                                                        if (isChecked) {
                                                                            if (tempTerms[taxonomy] === undefined) {
                                                                                tempTerms[taxonomy] = [term.slug];
                                                                            } else {
                                                                                tempTerms[taxonomy].push(term.slug);
                                                                            }
                                                                        } else {
                                                                            index = tempTerms[taxonomy].indexOf(term.slug);
                                                                            tempTerms[taxonomy].splice(index, 1);
                                                                        }
                                                                        tempTerms = JSON.stringify(tempTerms);
                                                                        _this4.props.setAttributes({
                                                                            terms: tempTerms
                                                                        });
                                                                        _this4.getPosts(term.term_id);
                                                                    }
                                                                })
                                                            );
                                                        })
                                                    )
                                                )
                                            )
                                        )
                                    )
                                );
                            })
                        )
                    )
                ),
                wp.element.createElement(
                    'div',
                    { className: 'map-block', style: backgroundStyle, id: 'map-block_' + clientId },
                    wp.element.createElement(ServerSideRender, {
                        block: 'imap/imap',
                        attributes: this.props.attributes,
                        onChange: googleMapUpdated(this.props.attributes)
                    })
                )
            );
        }
    }]);

    return rightSideBarRowLayout;
}(Component);
//rightSideBarRowLayout.defaultProps = googleMapStyles;


/* harmony default export */ __webpack_exports__["a"] = (rightSideBarRowLayout);

/***/ }),
/* 7 */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
  Copyright (c) 2017 Jed Watson.
  Licensed under the MIT License (MIT), see
  http://jedwatson.github.io/classnames
*/
/* global define */

(function () {
	'use strict';

	var hasOwn = {}.hasOwnProperty;

	function classNames () {
		var classes = [];

		for (var i = 0; i < arguments.length; i++) {
			var arg = arguments[i];
			if (!arg) continue;

			var argType = typeof arg;

			if (argType === 'string' || argType === 'number') {
				classes.push(arg);
			} else if (Array.isArray(arg) && arg.length) {
				var inner = classNames.apply(null, arg);
				if (inner) {
					classes.push(inner);
				}
			} else if (argType === 'object') {
				for (var key in arg) {
					if (hasOwn.call(arg, key) && arg[key]) {
						classes.push(key);
					}
				}
			}
		}

		return classes.join(' ');
	}

	if (typeof module !== 'undefined' && module.exports) {
		classNames.default = classNames;
		module.exports = classNames;
	} else if (true) {
		// register as 'classnames', consistent with npm package name
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function () {
			return classNames;
		}).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {
		window.classNames = classNames;
	}
}());


/***/ }),
/* 8 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_lodash_range__ = __webpack_require__(9);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_lodash_range___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_lodash_range__);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }


var __ = wp.i18n.__;
var _wp$data = wp.data,
    withSelect = _wp$data.withSelect,
    withDispatch = _wp$data.withDispatch;
var _wp$element = wp.element,
    Component = _wp$element.Component,
    Fragment = _wp$element.Fragment;
var compose = wp.compose.compose;
var _wp$editor = wp.editor,
    InnerBlocks = _wp$editor.InnerBlocks,
    InspectorControls = _wp$editor.InspectorControls,
    RichText = _wp$editor.RichText,
    BlockControls = _wp$editor.BlockControls,
    ColorPalette = _wp$editor.ColorPalette,
    AlignmentToolbar = _wp$editor.AlignmentToolbar,
    BlockAlignmentToolbar = _wp$editor.BlockAlignmentToolbar,
    PanelColorSettings = _wp$editor.PanelColorSettings;
var _wp$components = wp.components,
    Button = _wp$components.Button,
    ButtonGroup = _wp$components.ButtonGroup,
    Tooltip = _wp$components.Tooltip,
    TabPanel = _wp$components.TabPanel,
    CheckboxControl = _wp$components.CheckboxControl,
    Dashicon = _wp$components.Dashicon,
    PanelBody = _wp$components.PanelBody,
    TextControl = _wp$components.TextControl,
    RangeControl = _wp$components.RangeControl,
    ToggleControl = _wp$components.ToggleControl,
    SelectControl = _wp$components.SelectControl,
    ServerSideRender = _wp$components.ServerSideRender;

var MapSettings = function (_Component) {
    _inherits(MapSettings, _Component);

    function MapSettings() {
        _classCallCheck(this, MapSettings);

        return _possibleConstructorReturn(this, (MapSettings.__proto__ || Object.getPrototypeOf(MapSettings)).apply(this, arguments));
    }

    _createClass(MapSettings, [{
        key: 'render',
        value: function render() {
            var _props$AttributesData = this.props.AttributesData,
                showZoom = _props$AttributesData.showZoom,
                showFilter = _props$AttributesData.showFilter,
                showProjectDetail = _props$AttributesData.showProjectDetail,
                pdImageSlider = _props$AttributesData.pdImageSlider,
                pdChangeImageafter = _props$AttributesData.pdChangeImageafter,
                pdProjectTechnologies = _props$AttributesData.pdProjectTechnologies,
                pdProjectPartners = _props$AttributesData.pdProjectPartners,
                pdProjectStatus = _props$AttributesData.pdProjectStatus,
                pdProjectDescriptions = _props$AttributesData.pdProjectDescriptions,
                pdProjectAddress = _props$AttributesData.pdProjectAddress,
                pdSharingOptions = _props$AttributesData.pdSharingOptions,
                MapLatitude = _props$AttributesData.MapLatitude,
                MapLongitude = _props$AttributesData.MapLongitude,
                MapZoom = _props$AttributesData.MapZoom,
                pdShowVIdeo = _props$AttributesData.pdShowVIdeo,
                exhibitionMode = _props$AttributesData.exhibitionMode,
                changeProjectafter = _props$AttributesData.changeProjectafter,
                PrimaryTechnology = _props$AttributesData.PrimaryTechnology,
                Description = _props$AttributesData.Description,
                SharingOptions = _props$AttributesData.SharingOptions,
                pButton = _props$AttributesData.pButton,
                CompletedRadioButton = _props$AttributesData.CompletedRadioButton,
                ProjectTechnologies = _props$AttributesData.ProjectTechnologies,
                ProjectPartners = _props$AttributesData.ProjectPartners,
                autoBackgroundImage = _props$AttributesData.autoBackgroundImage,
                autoBackgroundOverlayColor = _props$AttributesData.autoBackgroundOverlayColor,
                autoBackgroundOverlayOpacity = _props$AttributesData.autoBackgroundOverlayOpacity,
                autoHeaderTitleTextColor = _props$AttributesData.autoHeaderTitleTextColor,
                autoHeaderSubTitleTextColor = _props$AttributesData.autoHeaderSubTitleTextColor;

            var setAttributes = this.props.setAttributes;

            var HouseholdAmts = ['0', '25000', '35000', '45000', '55000', '65000', '75000', '85000', '95000', '105000', '115000', '130000', '145000', '160000', '175000', '200000', '225000', '250000+'];
            var HoueholdOptions = HouseholdAmts.map(function (item, index) {
                return { value: item, label: item };
            });

            var changeHouseholdAmount = function changeHouseholdAmount(value) {
                var index = HouseholdAmts.indexOf(value);

                setAttributes({ annualHouseholdIncome: value });

                setAttributes({ annualHouseholdKey: index });
            };

            return wp.element.createElement(
                Fragment,
                null,
                wp.element.createElement(
                    PanelBody,
                    { title: __('Map General Settings'), initialOpen: false, className: 'gmap-setting-panel hide-panel' },
                    !exhibitionMode && wp.element.createElement(ToggleControl, {
                        label: wp.element.createElement(
                            'p',
                            null,
                            wp.element.createElement(
                                'strong',
                                null,
                                __('Show Zoom in/ Zoom out')
                            )
                        ),
                        checked: showZoom,
                        onChange: function onChange() {
                            return setAttributes({ showZoom: !showZoom });
                        }
                    }),
                    !exhibitionMode && wp.element.createElement(ToggleControl, {
                        label: wp.element.createElement(
                            'p',
                            null,
                            wp.element.createElement(
                                'strong',
                                null,
                                __('Show Filter')
                            )
                        ),
                        checked: showFilter,
                        onChange: function onChange() {
                            return setAttributes({ showFilter: !showFilter });
                        }
                    }),
                    wp.element.createElement(
                        'div',
                        { className: 'change-lat-long-wrapper' },
                        wp.element.createElement(
                            'strong',
                            null,
                            __('Map Latitude')
                        ),
                        wp.element.createElement(TextControl, {
                            type: 'text',
                            name: 'test_name[]',
                            value: MapLatitude,
                            placeholder: __('Map Latitude'),
                            onChange: function onChange(value) {
                                return setAttributes({ MapLatitude: value });
                            }
                        })
                    ),
                    wp.element.createElement(
                        'div',
                        { className: 'change-lat-long-wrapper' },
                        wp.element.createElement(
                            'strong',
                            null,
                            __('Map Longitude')
                        ),
                        wp.element.createElement(TextControl, {
                            type: 'text',
                            name: 'test_name[]',
                            value: MapLongitude,
                            placeholder: __('Map Longitude'),
                            onChange: function onChange(value) {
                                return setAttributes({ MapLongitude: value });
                            }
                        })
                    ),
                    wp.element.createElement(
                        'div',
                        { className: 'change-lat-long-wrapper' },
                        wp.element.createElement(
                            'strong',
                            null,
                            __('Map Zoom')
                        ),
                        wp.element.createElement(TextControl, {
                            type: 'number',
                            name: 'test_name[]',
                            value: MapZoom,
                            placeholder: __('Map Zoom'),
                            onChange: function onChange(value) {
                                return setAttributes({ MapZoom: value });
                            }
                        })
                    )
                ),
                wp.element.createElement(
                    PanelBody,
                    { title: __("Exhibition Settings"), initialOpen: false, className: 'gmap-setting-panel hide-panel' },
                    wp.element.createElement(ToggleControl, {
                        label: wp.element.createElement(
                            'p',
                            null,
                            wp.element.createElement(
                                'strong',
                                null,
                                __('Exhibition Mode')
                            )
                        ),
                        checked: exhibitionMode,
                        onChange: function onChange() {
                            return setAttributes({ exhibitionMode: !exhibitionMode });
                        }
                    }),
                    wp.element.createElement(
                        'div',
                        { className: 'exhibition-wrapper' },
                        wp.element.createElement(TextControl, {
                            type: 'text',
                            name: 'test_name[]',
                            value: changeProjectafter,
                            placeholder: __('Change Project after'),
                            onChange: function onChange(value) {
                                return setAttributes({ changeProjectafter: value });
                            }
                        }),
                        wp.element.createElement(
                            'span',
                            null,
                            __('Seconds')
                        )
                    )
                ),
                wp.element.createElement(
                    PanelBody,
                    { title: __("Project Popup Setting"), initialOpen: false, className: 'gmap-setting-panel hide-panel' },
                    wp.element.createElement(ToggleControl, {
                        label: wp.element.createElement(
                            'p',
                            null,
                            wp.element.createElement(
                                'strong',
                                null,
                                __('Primary Technology')
                            )
                        ),
                        checked: PrimaryTechnology,
                        onChange: function onChange() {
                            return setAttributes({ PrimaryTechnology: !PrimaryTechnology });
                        }
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: wp.element.createElement(
                            'p',
                            null,
                            wp.element.createElement(
                                'strong',
                                null,
                                __('Description')
                            )
                        ),
                        checked: Description,
                        onChange: function onChange() {
                            return setAttributes({ Description: !Description });
                        }
                    }),
                    !exhibitionMode && wp.element.createElement(ToggleControl, {
                        label: wp.element.createElement(
                            'p',
                            null,
                            wp.element.createElement(
                                'strong',
                                null,
                                __('Sharing Options')
                            )
                        ),
                        checked: SharingOptions,
                        onChange: function onChange() {
                            return setAttributes({ SharingOptions: !SharingOptions });
                        }
                    }),
                    !exhibitionMode && wp.element.createElement(ToggleControl, {
                        label: wp.element.createElement(
                            'p',
                            null,
                            wp.element.createElement(
                                'strong',
                                null,
                                __('Button to Hide "Explore More" button')
                            )
                        ),
                        checked: pButton,
                        onChange: function onChange() {
                            return setAttributes({ pButton: !pButton });
                        }
                    })
                ),
                !exhibitionMode && wp.element.createElement(
                    PanelBody,
                    { title: __("Filter Setting Options"), initialOpen: false, className: 'gmap-setting-panel hide-panel' },
                    wp.element.createElement(ToggleControl, {
                        label: wp.element.createElement(
                            'p',
                            null,
                            wp.element.createElement(
                                'strong',
                                null,
                                __('Completed RadioButton')
                            )
                        ),
                        checked: CompletedRadioButton,
                        onChange: function onChange() {
                            return setAttributes({ CompletedRadioButton: !CompletedRadioButton });
                        }
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: wp.element.createElement(
                            'p',
                            null,
                            wp.element.createElement(
                                'strong',
                                null,
                                __('Project Technologies')
                            )
                        ),
                        checked: ProjectTechnologies,
                        onChange: function onChange() {
                            return setAttributes({ ProjectTechnologies: !ProjectTechnologies });
                        }
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: wp.element.createElement(
                            'p',
                            null,
                            wp.element.createElement(
                                'strong',
                                null,
                                __('Project Partners')
                            )
                        ),
                        checked: ProjectPartners,
                        onChange: function onChange() {
                            return setAttributes({ ProjectPartners: !ProjectPartners });
                        }
                    })
                ),
                !exhibitionMode && pButton && wp.element.createElement(
                    PanelBody,
                    { title: __("Project Detail Setting"), initialOpen: false, className: 'gmap-setting-panel hide-panel' },
                    wp.element.createElement(ToggleControl, {
                        label: wp.element.createElement(
                            'p',
                            null,
                            wp.element.createElement(
                                'strong',
                                null,
                                __('Image Slider')
                            )
                        ),
                        checked: pdImageSlider,
                        onChange: function onChange() {
                            return setAttributes({ pdImageSlider: !pdImageSlider });
                        }
                    }),
                    wp.element.createElement(
                        'div',
                        { className: 'change-image-wrapper' },
                        wp.element.createElement(TextControl, {
                            type: 'text',
                            name: 'test_name[]',
                            value: pdChangeImageafter,
                            placeholder: __('ChangeImage after'),
                            onChange: function onChange(value) {
                                return setAttributes({ pdChangeImageafter: value });
                            }
                        }),
                        wp.element.createElement(
                            'span',
                            null,
                            __('Seconds')
                        )
                    ),
                    wp.element.createElement(ToggleControl, {
                        label: wp.element.createElement(
                            'p',
                            null,
                            wp.element.createElement(
                                'strong',
                                null,
                                __('Project Technologies')
                            )
                        ),
                        checked: pdProjectTechnologies,
                        onChange: function onChange() {
                            return setAttributes({ pdProjectTechnologies: !pdProjectTechnologies });
                        }
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: wp.element.createElement(
                            'p',
                            null,
                            wp.element.createElement(
                                'strong',
                                null,
                                __('Project Partners')
                            )
                        ),
                        checked: pdProjectPartners,
                        onChange: function onChange() {
                            return setAttributes({ pdProjectPartners: !pdProjectPartners });
                        }
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: wp.element.createElement(
                            'p',
                            null,
                            wp.element.createElement(
                                'strong',
                                null,
                                __('Project Status')
                            )
                        ),
                        checked: pdProjectStatus,
                        onChange: function onChange() {
                            return setAttributes({ pdProjectStatus: !pdProjectStatus });
                        }
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: wp.element.createElement(
                            'p',
                            null,
                            wp.element.createElement(
                                'strong',
                                null,
                                __('Project Descriptions')
                            )
                        ),
                        checked: pdProjectDescriptions,
                        onChange: function onChange() {
                            return setAttributes({ pdProjectDescriptions: !pdProjectDescriptions });
                        }
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: wp.element.createElement(
                            'p',
                            null,
                            wp.element.createElement(
                                'strong',
                                null,
                                __('Project Address')
                            )
                        ),
                        checked: pdProjectAddress,
                        onChange: function onChange() {
                            return setAttributes({ pdProjectAddress: !pdProjectAddress });
                        }
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: wp.element.createElement(
                            'p',
                            null,
                            wp.element.createElement(
                                'strong',
                                null,
                                __('Sharing Options')
                            )
                        ),
                        checked: pdSharingOptions,
                        onChange: function onChange() {
                            return setAttributes({ pdSharingOptions: !pdSharingOptions });
                        }
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: wp.element.createElement(
                            'p',
                            null,
                            wp.element.createElement(
                                'strong',
                                null,
                                __('Show Video')
                            )
                        ),
                        checked: pdShowVIdeo,
                        onChange: function onChange() {
                            return setAttributes({ pdShowVIdeo: !pdShowVIdeo });
                        }
                    })
                )
            );
        }
    }]);

    return MapSettings;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (MapSettings);

/***/ }),
/* 9 */
/***/ (function(module, exports, __webpack_require__) {

var createRange = __webpack_require__(10);

/**
 * Creates an array of numbers (positive and/or negative) progressing from
 * `start` up to, but not including, `end`. A step of `-1` is used if a negative
 * `start` is specified without an `end` or `step`. If `end` is not specified,
 * it's set to `start` with `start` then set to `0`.
 *
 * **Note:** JavaScript follows the IEEE-754 standard for resolving
 * floating-point values which can produce unexpected results.
 *
 * @static
 * @since 0.1.0
 * @memberOf _
 * @category Util
 * @param {number} [start=0] The start of the range.
 * @param {number} end The end of the range.
 * @param {number} [step=1] The value to increment or decrement by.
 * @returns {Array} Returns the range of numbers.
 * @see _.inRange, _.rangeRight
 * @example
 *
 * _.range(4);
 * // => [0, 1, 2, 3]
 *
 * _.range(-4);
 * // => [0, -1, -2, -3]
 *
 * _.range(1, 5);
 * // => [1, 2, 3, 4]
 *
 * _.range(0, 20, 5);
 * // => [0, 5, 10, 15]
 *
 * _.range(0, -4, -1);
 * // => [0, -1, -2, -3]
 *
 * _.range(1, 4, 0);
 * // => [1, 1, 1]
 *
 * _.range(0);
 * // => []
 */
var range = createRange();

module.exports = range;


/***/ }),
/* 10 */
/***/ (function(module, exports, __webpack_require__) {

var baseRange = __webpack_require__(11),
    isIterateeCall = __webpack_require__(12),
    toFinite = __webpack_require__(23);

/**
 * Creates a `_.range` or `_.rangeRight` function.
 *
 * @private
 * @param {boolean} [fromRight] Specify iterating from right to left.
 * @returns {Function} Returns the new range function.
 */
function createRange(fromRight) {
  return function(start, end, step) {
    if (step && typeof step != 'number' && isIterateeCall(start, end, step)) {
      end = step = undefined;
    }
    // Ensure the sign of `-0` is preserved.
    start = toFinite(start);
    if (end === undefined) {
      end = start;
      start = 0;
    } else {
      end = toFinite(end);
    }
    step = step === undefined ? (start < end ? 1 : -1) : toFinite(step);
    return baseRange(start, end, step, fromRight);
  };
}

module.exports = createRange;


/***/ }),
/* 11 */
/***/ (function(module, exports) {

/* Built-in method references for those with the same name as other `lodash` methods. */
var nativeCeil = Math.ceil,
    nativeMax = Math.max;

/**
 * The base implementation of `_.range` and `_.rangeRight` which doesn't
 * coerce arguments.
 *
 * @private
 * @param {number} start The start of the range.
 * @param {number} end The end of the range.
 * @param {number} step The value to increment or decrement by.
 * @param {boolean} [fromRight] Specify iterating from right to left.
 * @returns {Array} Returns the range of numbers.
 */
function baseRange(start, end, step, fromRight) {
  var index = -1,
      length = nativeMax(nativeCeil((end - start) / (step || 1)), 0),
      result = Array(length);

  while (length--) {
    result[fromRight ? length : ++index] = start;
    start += step;
  }
  return result;
}

module.exports = baseRange;


/***/ }),
/* 12 */
/***/ (function(module, exports, __webpack_require__) {

var eq = __webpack_require__(13),
    isArrayLike = __webpack_require__(14),
    isIndex = __webpack_require__(22),
    isObject = __webpack_require__(0);

/**
 * Checks if the given arguments are from an iteratee call.
 *
 * @private
 * @param {*} value The potential iteratee value argument.
 * @param {*} index The potential iteratee index or key argument.
 * @param {*} object The potential iteratee object argument.
 * @returns {boolean} Returns `true` if the arguments are from an iteratee call,
 *  else `false`.
 */
function isIterateeCall(value, index, object) {
  if (!isObject(object)) {
    return false;
  }
  var type = typeof index;
  if (type == 'number'
        ? (isArrayLike(object) && isIndex(index, object.length))
        : (type == 'string' && index in object)
      ) {
    return eq(object[index], value);
  }
  return false;
}

module.exports = isIterateeCall;


/***/ }),
/* 13 */
/***/ (function(module, exports) {

/**
 * Performs a
 * [`SameValueZero`](http://ecma-international.org/ecma-262/7.0/#sec-samevaluezero)
 * comparison between two values to determine if they are equivalent.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to compare.
 * @param {*} other The other value to compare.
 * @returns {boolean} Returns `true` if the values are equivalent, else `false`.
 * @example
 *
 * var object = { 'a': 1 };
 * var other = { 'a': 1 };
 *
 * _.eq(object, object);
 * // => true
 *
 * _.eq(object, other);
 * // => false
 *
 * _.eq('a', 'a');
 * // => true
 *
 * _.eq('a', Object('a'));
 * // => false
 *
 * _.eq(NaN, NaN);
 * // => true
 */
function eq(value, other) {
  return value === other || (value !== value && other !== other);
}

module.exports = eq;


/***/ }),
/* 14 */
/***/ (function(module, exports, __webpack_require__) {

var isFunction = __webpack_require__(15),
    isLength = __webpack_require__(21);

/**
 * Checks if `value` is array-like. A value is considered array-like if it's
 * not a function and has a `value.length` that's an integer greater than or
 * equal to `0` and less than or equal to `Number.MAX_SAFE_INTEGER`.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is array-like, else `false`.
 * @example
 *
 * _.isArrayLike([1, 2, 3]);
 * // => true
 *
 * _.isArrayLike(document.body.children);
 * // => true
 *
 * _.isArrayLike('abc');
 * // => true
 *
 * _.isArrayLike(_.noop);
 * // => false
 */
function isArrayLike(value) {
  return value != null && isLength(value.length) && !isFunction(value);
}

module.exports = isArrayLike;


/***/ }),
/* 15 */
/***/ (function(module, exports, __webpack_require__) {

var baseGetTag = __webpack_require__(1),
    isObject = __webpack_require__(0);

/** `Object#toString` result references. */
var asyncTag = '[object AsyncFunction]',
    funcTag = '[object Function]',
    genTag = '[object GeneratorFunction]',
    proxyTag = '[object Proxy]';

/**
 * Checks if `value` is classified as a `Function` object.
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a function, else `false`.
 * @example
 *
 * _.isFunction(_);
 * // => true
 *
 * _.isFunction(/abc/);
 * // => false
 */
function isFunction(value) {
  if (!isObject(value)) {
    return false;
  }
  // The use of `Object#toString` avoids issues with the `typeof` operator
  // in Safari 9 which returns 'object' for typed arrays and other constructors.
  var tag = baseGetTag(value);
  return tag == funcTag || tag == genTag || tag == asyncTag || tag == proxyTag;
}

module.exports = isFunction;


/***/ }),
/* 16 */
/***/ (function(module, exports, __webpack_require__) {

var freeGlobal = __webpack_require__(17);

/** Detect free variable `self`. */
var freeSelf = typeof self == 'object' && self && self.Object === Object && self;

/** Used as a reference to the global object. */
var root = freeGlobal || freeSelf || Function('return this')();

module.exports = root;


/***/ }),
/* 17 */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(global) {/** Detect free variable `global` from Node.js. */
var freeGlobal = typeof global == 'object' && global && global.Object === Object && global;

module.exports = freeGlobal;

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(18)))

/***/ }),
/* 18 */
/***/ (function(module, exports) {

var g;

// This works in non-strict mode
g = (function() {
	return this;
})();

try {
	// This works if eval is allowed (see CSP)
	g = g || Function("return this")() || (1,eval)("this");
} catch(e) {
	// This works if the window reference is available
	if(typeof window === "object")
		g = window;
}

// g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}

module.exports = g;


/***/ }),
/* 19 */
/***/ (function(module, exports, __webpack_require__) {

var Symbol = __webpack_require__(2);

/** Used for built-in method references. */
var objectProto = Object.prototype;

/** Used to check objects for own properties. */
var hasOwnProperty = objectProto.hasOwnProperty;

/**
 * Used to resolve the
 * [`toStringTag`](http://ecma-international.org/ecma-262/7.0/#sec-object.prototype.tostring)
 * of values.
 */
var nativeObjectToString = objectProto.toString;

/** Built-in value references. */
var symToStringTag = Symbol ? Symbol.toStringTag : undefined;

/**
 * A specialized version of `baseGetTag` which ignores `Symbol.toStringTag` values.
 *
 * @private
 * @param {*} value The value to query.
 * @returns {string} Returns the raw `toStringTag`.
 */
function getRawTag(value) {
  var isOwn = hasOwnProperty.call(value, symToStringTag),
      tag = value[symToStringTag];

  try {
    value[symToStringTag] = undefined;
    var unmasked = true;
  } catch (e) {}

  var result = nativeObjectToString.call(value);
  if (unmasked) {
    if (isOwn) {
      value[symToStringTag] = tag;
    } else {
      delete value[symToStringTag];
    }
  }
  return result;
}

module.exports = getRawTag;


/***/ }),
/* 20 */
/***/ (function(module, exports) {

/** Used for built-in method references. */
var objectProto = Object.prototype;

/**
 * Used to resolve the
 * [`toStringTag`](http://ecma-international.org/ecma-262/7.0/#sec-object.prototype.tostring)
 * of values.
 */
var nativeObjectToString = objectProto.toString;

/**
 * Converts `value` to a string using `Object.prototype.toString`.
 *
 * @private
 * @param {*} value The value to convert.
 * @returns {string} Returns the converted string.
 */
function objectToString(value) {
  return nativeObjectToString.call(value);
}

module.exports = objectToString;


/***/ }),
/* 21 */
/***/ (function(module, exports) {

/** Used as references for various `Number` constants. */
var MAX_SAFE_INTEGER = 9007199254740991;

/**
 * Checks if `value` is a valid array-like length.
 *
 * **Note:** This method is loosely based on
 * [`ToLength`](http://ecma-international.org/ecma-262/7.0/#sec-tolength).
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a valid length, else `false`.
 * @example
 *
 * _.isLength(3);
 * // => true
 *
 * _.isLength(Number.MIN_VALUE);
 * // => false
 *
 * _.isLength(Infinity);
 * // => false
 *
 * _.isLength('3');
 * // => false
 */
function isLength(value) {
  return typeof value == 'number' &&
    value > -1 && value % 1 == 0 && value <= MAX_SAFE_INTEGER;
}

module.exports = isLength;


/***/ }),
/* 22 */
/***/ (function(module, exports) {

/** Used as references for various `Number` constants. */
var MAX_SAFE_INTEGER = 9007199254740991;

/** Used to detect unsigned integer values. */
var reIsUint = /^(?:0|[1-9]\d*)$/;

/**
 * Checks if `value` is a valid array-like index.
 *
 * @private
 * @param {*} value The value to check.
 * @param {number} [length=MAX_SAFE_INTEGER] The upper bounds of a valid index.
 * @returns {boolean} Returns `true` if `value` is a valid index, else `false`.
 */
function isIndex(value, length) {
  var type = typeof value;
  length = length == null ? MAX_SAFE_INTEGER : length;

  return !!length &&
    (type == 'number' ||
      (type != 'symbol' && reIsUint.test(value))) &&
        (value > -1 && value % 1 == 0 && value < length);
}

module.exports = isIndex;


/***/ }),
/* 23 */
/***/ (function(module, exports, __webpack_require__) {

var toNumber = __webpack_require__(24);

/** Used as references for various `Number` constants. */
var INFINITY = 1 / 0,
    MAX_INTEGER = 1.7976931348623157e+308;

/**
 * Converts `value` to a finite number.
 *
 * @static
 * @memberOf _
 * @since 4.12.0
 * @category Lang
 * @param {*} value The value to convert.
 * @returns {number} Returns the converted number.
 * @example
 *
 * _.toFinite(3.2);
 * // => 3.2
 *
 * _.toFinite(Number.MIN_VALUE);
 * // => 5e-324
 *
 * _.toFinite(Infinity);
 * // => 1.7976931348623157e+308
 *
 * _.toFinite('3.2');
 * // => 3.2
 */
function toFinite(value) {
  if (!value) {
    return value === 0 ? value : 0;
  }
  value = toNumber(value);
  if (value === INFINITY || value === -INFINITY) {
    var sign = (value < 0 ? -1 : 1);
    return sign * MAX_INTEGER;
  }
  return value === value ? value : 0;
}

module.exports = toFinite;


/***/ }),
/* 24 */
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__(0),
    isSymbol = __webpack_require__(25);

/** Used as references for various `Number` constants. */
var NAN = 0 / 0;

/** Used to match leading and trailing whitespace. */
var reTrim = /^\s+|\s+$/g;

/** Used to detect bad signed hexadecimal string values. */
var reIsBadHex = /^[-+]0x[0-9a-f]+$/i;

/** Used to detect binary string values. */
var reIsBinary = /^0b[01]+$/i;

/** Used to detect octal string values. */
var reIsOctal = /^0o[0-7]+$/i;

/** Built-in method references without a dependency on `root`. */
var freeParseInt = parseInt;

/**
 * Converts `value` to a number.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to process.
 * @returns {number} Returns the number.
 * @example
 *
 * _.toNumber(3.2);
 * // => 3.2
 *
 * _.toNumber(Number.MIN_VALUE);
 * // => 5e-324
 *
 * _.toNumber(Infinity);
 * // => Infinity
 *
 * _.toNumber('3.2');
 * // => 3.2
 */
function toNumber(value) {
  if (typeof value == 'number') {
    return value;
  }
  if (isSymbol(value)) {
    return NAN;
  }
  if (isObject(value)) {
    var other = typeof value.valueOf == 'function' ? value.valueOf() : value;
    value = isObject(other) ? (other + '') : other;
  }
  if (typeof value != 'string') {
    return value === 0 ? value : +value;
  }
  value = value.replace(reTrim, '');
  var isBinary = reIsBinary.test(value);
  return (isBinary || reIsOctal.test(value))
    ? freeParseInt(value.slice(2), isBinary ? 2 : 8)
    : (reIsBadHex.test(value) ? NAN : +value);
}

module.exports = toNumber;


/***/ }),
/* 25 */
/***/ (function(module, exports, __webpack_require__) {

var baseGetTag = __webpack_require__(1),
    isObjectLike = __webpack_require__(26);

/** `Object#toString` result references. */
var symbolTag = '[object Symbol]';

/**
 * Checks if `value` is classified as a `Symbol` primitive or object.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a symbol, else `false`.
 * @example
 *
 * _.isSymbol(Symbol.iterator);
 * // => true
 *
 * _.isSymbol('abc');
 * // => false
 */
function isSymbol(value) {
  return typeof value == 'symbol' ||
    (isObjectLike(value) && baseGetTag(value) == symbolTag);
}

module.exports = isSymbol;


/***/ }),
/* 26 */
/***/ (function(module, exports) {

/**
 * Checks if `value` is object-like. A value is object-like if it's not `null`
 * and has a `typeof` result of "object".
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is object-like, else `false`.
 * @example
 *
 * _.isObjectLike({});
 * // => true
 *
 * _.isObjectLike([1, 2, 3]);
 * // => true
 *
 * _.isObjectLike(_.noop);
 * // => false
 *
 * _.isObjectLike(null);
 * // => false
 */
function isObjectLike(value) {
  return value != null && typeof value == 'object';
}

module.exports = isObjectLike;


/***/ })
/******/ ]);