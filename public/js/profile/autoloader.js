(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/profile/autoloader"],{

/***/ "./resources/assets/js/profile/autoloader.js":
/*!***************************************************!*\
  !*** ./resources/assets/js/profile/autoloader.js ***!
  \***************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _detail__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./detail */ "./resources/assets/js/profile/detail.js");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./edit */ "./resources/assets/js/profile/edit.js");
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }




var Autoloader =
/*#__PURE__*/
function () {
  function Autoloader() {
    _classCallCheck(this, Autoloader);

    this.lazyLoad();
  }

  _createClass(Autoloader, [{
    key: "lazyLoad",
    value: function lazyLoad() {
      var page = $('meta[name="page"]').attr('content');

      switch (page) {
        case 'detail':
          new _detail__WEBPACK_IMPORTED_MODULE_0__["Detail"]();
          break;

        case 'edit':
          new _edit__WEBPACK_IMPORTED_MODULE_1__["Edit"]();
          break;
      }
    }
  }]);

  return Autoloader;
}();

new Autoloader();

/***/ }),

/***/ "./resources/assets/js/profile/detail.js":
/*!***********************************************!*\
  !*** ./resources/assets/js/profile/detail.js ***!
  \***********************************************/
/*! exports provided: Detail */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Detail", function() { return Detail; });
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Detail =
/*#__PURE__*/
function () {
  function Detail() {
    _classCallCheck(this, Detail);

    this.autoDisplayApiCapabilities();
  }

  _createClass(Detail, [{
    key: "autoDisplayApiCapabilities",
    value: function autoDisplayApiCapabilities() {
      var globalApiTotalCount = $("#permissions-table td.for-api").length;
      var globalApiCheckedCount = $("#permissions-table td.for-api[data-checked='true']").length;

      if (globalApiCheckedCount > 0 && globalApiTotalCount !== globalApiCheckedCount) {
        $("#permissions-table .for-api").removeClass('hide');
      }
    }
  }]);

  return Detail;
}();

/***/ }),

/***/ "./resources/assets/js/profile/edit.js":
/*!*********************************************!*\
  !*** ./resources/assets/js/profile/edit.js ***!
  \*********************************************/
/*! exports provided: Edit */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Edit", function() { return Edit; });
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Edit =
/*#__PURE__*/
function () {
  function Edit() {
    _classCallCheck(this, Edit);

    this.intiSwitchListener();
    this.initCheckboxListener();
    this.autoCheck();
    this.autoDisplayApiCapabilities();
    this.autoDisplaySwitch();
  }

  _createClass(Edit, [{
    key: "intiSwitchListener",
    value: function intiSwitchListener() {
      $('#manage-api-capabilities').on('change', function (event) {
        var element = $(event.currentTarget);

        if (element.is(':checked')) {
          $("#permissions-table .for-api").removeClass('hide');
        } else {
          $("#permissions-table .for-api").addClass('hide');
        }
      });
    }
  }, {
    key: "initCheckboxListener",
    value: function initCheckboxListener() {
      var _this = this;

      // Select all checkbox
      $('#permissions-table input.select-all').on('change', function (event) {
        var element = $(event.currentTarget);

        if (element.is(':checked')) {
          $("#permissions-table input[type='checkbox']").prop('checked', true);
        } else {
          $("#permissions-table input[type='checkbox']").prop('checked', false);
        }

        _this.autoCheck();
      }); // Select row checkboxes

      $('#permissions-table input.select-row').on('change', function (event) {
        var element = $(event.currentTarget);
        var parentElement = $(element).parents('tr:first');

        if (element.is(':checked')) {
          $(".select-item", parentElement).prop('checked', true);
        } else {
          $(".select-item", parentElement).prop('checked', false);
        }

        _this.autoCheck();
      }); // Select item checkboxes

      $('#permissions-table input.select-item').on('change', function (event) {
        var element = $(event.currentTarget);

        _this.autoCheck();
      });
    }
  }, {
    key: "autoCheck",
    value: function autoCheck() {
      // All modules
      var globalCheckedCount = $("#permissions-table .select-item:checked").length;
      var globalTotalCount = $("#permissions-table .select-item").length;
      $("#permissions-table .select-all").prop('checked', globalCheckedCount === globalTotalCount); // Each row

      $("#permissions-table tbody tr").each(function (index, el) {
        var rowElement = $(el);
        var rowCheckedCount = $(".select-item:checked", rowElement).length;
        var rowTotalCount = $(".select-item", rowElement).length;
        $(".select-row", rowElement).prop('checked', rowCheckedCount === rowTotalCount);
      });
    }
  }, {
    key: "autoDisplayApiCapabilities",
    value: function autoDisplayApiCapabilities() {
      var globalApiTotalCount = $("#permissions-table td.for-api input.select-item").length;
      var globalApiCheckedCount = $("#permissions-table td.for-api input.select-item:checked").length;

      if (globalApiCheckedCount > 0 && globalApiTotalCount !== globalApiCheckedCount) {
        $('#manage-api-capabilities').prop('checked', true).change();
      }
    }
  }, {
    key: "autoDisplaySwitch",
    value: function autoDisplaySwitch() {
      var globalApiTotalCount = $("#permissions-table td.for-api input.select-item").length;

      if (globalApiTotalCount > 0) {
        $(".api-switch").removeClass('hide');
      }
    }
  }]);

  return Edit;
}();

/***/ }),

/***/ 2:
/*!*********************************************************!*\
  !*** multi ./resources/assets/js/profile/autoloader.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! f:\VMs\Partage\ginkgo\packages\jerome-savin\uccello\resources\assets\js\profile\autoloader.js */"./resources/assets/js/profile/autoloader.js");


/***/ })

},[[2,"/js/manifest"]]]);