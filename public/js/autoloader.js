(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/autoloader"],{

/***/ "./resources/assets/js/core/autoloader.js":
/*!************************************************!*\
  !*** ./resources/assets/js/core/autoloader.js ***!
  \************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _list__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./list */ "./resources/assets/js/core/list.js");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./edit */ "./resources/assets/js/core/edit.js");
/* harmony import */ var _detail__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./detail */ "./resources/assets/js/core/detail.js");
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
        case 'list':
          new _list__WEBPACK_IMPORTED_MODULE_0__["List"]();
          break;

        case 'edit':
          new _edit__WEBPACK_IMPORTED_MODULE_1__["Edit"]();
          break;

        case 'detail':
          new _detail__WEBPACK_IMPORTED_MODULE_2__["Detail"]();
          break;
      }
    }
  }]);

  return Autoloader;
}();

new Autoloader();

/***/ }),

/***/ "./resources/assets/js/core/datatable.js":
/*!***********************************************!*\
  !*** ./resources/assets/js/core/datatable.js ***!
  \***********************************************/
/*! exports provided: Datatable */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Datatable", function() { return Datatable; });
/* harmony import */ var _link__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./link */ "./resources/assets/js/core/link.js");
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }


var Datatable =
/*#__PURE__*/
function () {
  function Datatable() {
    _classCallCheck(this, Datatable);
  }

  _createClass(Datatable, [{
    key: "init",

    /**
     * Init Datatable configuration
     * @param {Element} element
     */
    value: function init(element, rowClickCallback) {
      this.table = $(element);
      this.linkManager = new _link__WEBPACK_IMPORTED_MODULE_0__["Link"](false);
      this.rowClickCallback = rowClickCallback;
      this.initColumns();
      this.initColumnsSortListener();
      this.initColumnsVisibilityListener();
      this.initRecordsNumberListener();
      this.initColumnsSearchListener();
      this.initRefreshContentEventListener();
    }
  }, {
    key: "initColumns",
    value: function initColumns() {
      var _this = this;

      this.columns = {};

      if (!this.table) {
        return;
      }

      $('th[data-field]', this.table).each(function (index, el) {
        var element = $(el);
        var fieldName = element.data('field');

        if (typeof fieldName !== 'undefined') {
          _this.columns[fieldName] = {
            columnName: element.data('column'),
            search: $('.field-search', element).val()
          };
        }
      });
    }
  }, {
    key: "makeQuery",
    value: function makeQuery(page) {
      var _this2 = this;

      if (!this.table) {
        return;
      } // Get query URL


      var url = $(this.table).attr('data-content-url'); // Delete old records
      // $('tbody tr.record', this.table).remove()
      // Hide no_result row

      $('tbody tr.no-results', this.table).hide(); // Hide pagination

      $(".pagination[data-table=\"".concat(this.table.attr('id'), "\"]")).hide(); // Show loader

      $(".loader[data-table=\"".concat(this.table.attr('id'), "\"]")).show(); // Query data

      var data = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        id: $('meta[name="record"]').attr('content'),
        columns: this.columns,
        order: $(this.table).attr('data-order') ? JSON.parse($(this.table).attr('data-order')) : null,
        relatedlist: $(this.table).attr('data-relatedlist') ? $(this.table).attr('data-relatedlist') : null,
        length: $(this.table).attr('data-length')
      };

      if (typeof page !== 'undefined') {
        data.page = page;
      } // Make query


      $.post(url, data).then(function (response) {
        _this2.displayResults(response);

        _this2.displayPagination(response); // Hide loader


        $(".loader[data-table=\"".concat(_this2.table.attr('id'), "\"]")).hide();
      })["catch"](function (error) {
        // Hide loader
        $(".loader[data-table=\"".concat(_this2.table.attr('id'), "\"]")).addClass('hide'); // Show error

        swal(uctrans.trans('uccello::default.dialog.error.title'), uctrans.trans('uccello::default.dialog.error.message'), 'error');
      });
    }
  }, {
    key: "displayResults",
    value: function displayResults(response) {
      if (!this.table || !response.data) {
        return;
      } // Delete old records


      $('tbody tr.record', this.table).remove();

      if (response.data.length === 0) {
        // No result
        $('tbody tr.no-results', this.table).show();
      } else {
        // Add a row by record
        var _iteratorNormalCompletion = true;
        var _didIteratorError = false;
        var _iteratorError = undefined;

        try {
          for (var _iterator = response.data[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
            var record = _step.value;
            this.addRowToTable(record);
          }
        } catch (err) {
          _didIteratorError = true;
          _iteratorError = err;
        } finally {
          try {
            if (!_iteratorNormalCompletion && _iterator["return"] != null) {
              _iterator["return"]();
            }
          } finally {
            if (_didIteratorError) {
              throw _iteratorError;
            }
          }
        }
      }
    }
  }, {
    key: "displayPagination",
    value: function displayPagination(response) {
      var _this3 = this;

      if (!this.table || !response.data) {
        return;
      }

      var currentPage = response.current_page || 1;
      var pageRange = 2;
      var totalPage = response.last_page;
      var rangeStart = currentPage - pageRange;
      var rangeEnd = currentPage + pageRange;

      if (rangeEnd > totalPage) {
        rangeEnd = totalPage;
        rangeStart = totalPage - pageRange * 2;
        rangeStart = rangeStart < 1 ? 1 : rangeStart;
      }

      if (rangeStart <= 1) {
        rangeStart = 1;
        rangeEnd = Math.min(pageRange * 2 + 1, totalPage);
      } // Add a link to the previous page


      var previousLinkClass = response.prev_page_url === null ? 'disabled' : 'waves-effect';
      var previousDataPage = response.prev_page_url ? "data-page=\"".concat(response.current_page - 1, "\"") : '';
      var paginationHtml = "<li class=\"".concat(previousLinkClass, "\"><a href=\"javascript:void(0);\" ").concat(previousDataPage, "><i class=\"material-icons\">chevron_left</i></a></li>");
      var i;

      if (rangeStart <= pageRange + 1) {
        // Add 1 to 5
        for (i = 1; i < rangeStart; i++) {
          if (i == currentPage) {
            paginationHtml += "<li class=\"active primary\"><a href=\"javascript:void(0);\">".concat(i, "</a></li>");
          } else {
            paginationHtml += "<li class=\"waves-effect\"><a href=\"javascript:void(0);\" data-page=\"".concat(i, "\">").concat(i, "</a></li>");
          }
        }
      } else {
        // Add 1 ... at the beginning
        paginationHtml += "<li class=\"waves-effect\"><a href=\"javascript:void(0);\" data-page=\"1\">1</a></li> <li>...</li>";
      } // Add 2 pages before and after the current page


      for (i = rangeStart; i <= rangeEnd; i++) {
        if (i === currentPage) {
          paginationHtml += "<li class=\"active primary\"><a href=\"javascript:void(0);\">".concat(i, "</a></li>");
        } else {
          paginationHtml += "<li class=\"waves-effect\"><a href=\"javascript:void(0);\" data-page=\"".concat(i, "\">").concat(i, "</a></li>");
        }
      } // Add ... lastPage


      if (rangeEnd < totalPage) {
        paginationHtml += "<li>...</li> <li class=\"waves-effect\"><a href=\"javascript:void(0);\" data-page=\"".concat(totalPage, "\">").concat(totalPage, "</a></li>");
      } // Add a link to the next page


      var nextLinkClass = response.next_page_url === null ? 'disabled' : 'waves-effect';
      var nextDataPage = response.next_page_url ? "data-page=\"".concat(response.current_page + 1, "\"") : '';
      paginationHtml += "<li class=\"".concat(nextLinkClass, "\"><a href=\"javascript:void(0);\" ").concat(nextDataPage, "><i class=\"material-icons\">chevron_right</i></a></li>");
      var paginationElement = $(".pagination[data-table=\"".concat(this.table.attr('id'), "\"]"));
      paginationElement.html(paginationHtml);
      paginationElement.show(); // Init click listener

      $('a[data-page]', paginationElement).on('click', function (el) {
        var page = $(el.currentTarget).attr('data-page');

        _this3.makeQuery(page);
      });
    }
  }, {
    key: "addRowToTable",
    value: function addRowToTable(record) {
      if (!this.table || !record) {
        return;
      }

      var that = this; // Clone row template

      var tr = $('tbody tr.template', this.table).clone(); // Create each cell according to all headers

      $('th[data-field]', this.table).each(function () {
        var fieldName = $(this).data('field'); // let fieldColumn = $(this).data('column')
        // Add content to the cell

        var td = $("td[data-field=\"".concat(fieldName, "\"] "), tr);
        td.html(record[fieldName + '_html']); // Get html content
        // Hide if necessary

        if ($(this).css('display') === 'none') {
          td.hide();
        }
      }); // Replace RECORD_ID by the record's id in all links

      $('a', tr).each(function () {
        var href = $(this).attr('href');
        href = href.replace('RECORD_ID', record.__primaryKey);
        $(this).attr('href', href);

        if ($(this).attr('data-tooltip')) {
          $(this).tooltip();
        }
      }); // Replace RECORD_ID by the record's primary key in the row url

      var rowUrl = $(tr).attr('data-row-url');
      rowUrl = rowUrl.replace('RECORD_ID', record.__primaryKey);
      $(tr).attr('data-row-url', rowUrl);
      $(tr).attr('data-record-id', record.__primaryKey);
      $(tr).attr('data-record-label', record.recordLabel); // Add click listener

      $(tr).on('click', function (event) {
        if (typeof that.rowClickCallback !== 'undefined') {
          that.rowClickCallback(event, that, $(this).attr('data-record-id'), $(this).attr('data-record-label'));
        } else {
          document.location.href = $(this).attr('data-row-url');
        }
      }); // Dispatch event

      var event = new CustomEvent('uccello.list.row_added', {
        detail: {
          element: tr,
          record: record
        }
      });
      dispatchEvent(event); // Init click listener on delete button

      this.linkManager.initClickListener(tr); // Add the record to tbody

      tr.removeClass('hide').removeClass('template').addClass('record').appendTo("#".concat(this.table.attr('id'), " tbody")); // We use the id else it append not always into the good table (if there are several)
    }
  }, {
    key: "initColumnsVisibilityListener",
    value: function initColumnsVisibilityListener() {
      $("ul.columns[data-table=\"".concat(this.table.attr('id'), "\"] li a")).on('click', function (el) {
        var element = $(el.currentTarget);
        var fieldName = $(element).data('field'); // Select or unselect item in dropdown

        var liElement = $(element).parents('li:first');
        liElement.toggleClass('active'); // Show or hide column

        if (liElement.hasClass('active')) {
          $("th[data-field=\"".concat(fieldName, "\"]")).show(); // Label

          $("td[data-field=\"".concat(fieldName, "\"]")).show(); // Content
        } else {
          $("th[data-field=\"".concat(fieldName, "\"]")).hide(); // Label

          $("td[data-field=\"".concat(fieldName, "\"]")).hide(); // Content
        }
      });
    }
  }, {
    key: "initRecordsNumberListener",
    value: function initRecordsNumberListener() {
      var _this4 = this;

      if (!this.table) {
        return;
      }

      $("ul.records-number[data-table=\"".concat(this.table.attr('id'), "\"] li a")).on('click', function (el) {
        var element = $(el.currentTarget);
        var number = $(element).data('number'); // Select or unselect item in dropdown

        var ulId = $(element).parents('ul:first').attr('id');
        $("a[data-target=\"".concat(ulId, "\"] strong.records-number")).text(number);
        $(_this4.table).attr('data-length', number);

        _this4.makeQuery();
      });
    }
  }, {
    key: "initColumnsSearchListener",
    value: function initColumnsSearchListener() {
      if (!this.table) {
        return;
      }

      this.timer = 0;
      var that = this; // Config each column

      $('th[data-field]', this.table).each(function (index, el) {
        var element = $(el);
        var fieldName = element.data('field');
        $('input:not(.nosearch)', element).on('keyup apply.daterangepicker cancel.daterangepicker', function () {
          that.launchSearch(fieldName, $(this).val());
        });
        $('select:not(.nosearch)', element).on('change', function () {
          that.launchSearch(fieldName, $(this).val());
        });
      }); // Add clear search button listener

      this.addClearSearchButtonListener();
    }
    /* Refresh content */

  }, {
    key: "initRefreshContentEventListener",
    value: function initRefreshContentEventListener() {
      var _this5 = this;

      if (!this.table) {
        return;
      }

      addEventListener('uccello.list.refresh', function (event) {
        if (event.detail === $(_this5.table).attr('id')) {
          _this5.makeQuery();
        }
      });
    }
    /**
     * Launch search
     * @param {String} fieldName
     * @param {String} q
     */

  }, {
    key: "launchSearch",
    value: function launchSearch(fieldName, q) {
      var _this6 = this;

      if (q !== '') {
        $('.clear-search').show();
      }

      if (this.columns[fieldName].search !== q) {
        this.columns[fieldName].search = q;
        clearTimeout(this.timer);
        this.timer = setTimeout(function () {
          _this6.makeQuery();
        }, 700);
      }
    }
    /**
     * Clear datatable search
     */

  }, {
    key: "addClearSearchButtonListener",
    value: function addClearSearchButtonListener() {
      var _this7 = this;

      if (!this.table) {
        return;
      }

      $('.actions-column .clear-search').on('click', function (event) {
        // Clear all search fields
        $('thead select', _this7.table).val('');
        $('thead input', _this7.table).val(''); // Update columns

        _this7.initColumns(); // Disable clear search button


        $(event.currentTarget).hide(); // Update data

        _this7.makeQuery();
      });
    }
  }, {
    key: "initColumnsSortListener",
    value: function initColumnsSortListener() {
      var _this8 = this;

      if (!this.table) {
        return;
      }

      $('th[data-field].sortable', this.table).each(function (index, el) {
        var element = $(el);
        var fieldColumn = element.data('column');
        $('a.column-label', element).on('click', function (event) {
          // Get current sort order
          var order = _this8.table.attr('data-order') ? JSON.parse(_this8.table.attr('data-order')) : null; // Hide all sort icons

          $('a.column-label i').hide(); // Adapt icon according to sort order

          if (order !== null && order[fieldColumn] === 'asc') {
            order[fieldColumn] = 'desc';
            $('a.column-label i', element).removeClass('fa-sort-amount-up').addClass('fa-sort-amount-down');
          } else {
            order = {};
            order[fieldColumn] = 'asc';
            $('a.column-label i', element).removeClass('fa-sort-amount-down').addClass('fa-sort-amount-up');
          } // Show column's sort icon


          $('a.column-label i', element).show(); // Update sort order in the datatable

          _this8.table.attr('data-order', JSON.stringify(order)); // Make query


          _this8.makeQuery();
        });
      });
    }
  }]);

  return Datatable;
}();

/***/ }),

/***/ "./resources/assets/js/core/detail.js":
/*!********************************************!*\
  !*** ./resources/assets/js/core/detail.js ***!
  \********************************************/
/*! exports provided: Detail */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Detail", function() { return Detail; });
/* harmony import */ var _datatable__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./datatable */ "./resources/assets/js/core/datatable.js");
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }


var Detail =
/*#__PURE__*/
function () {
  function Detail() {
    _classCallCheck(this, Detail);

    this.initRelatedLists();
    this.initRelatedListSelectionModalListener();
  }
  /**
   * Initalize datatable for all related lists
   */


  _createClass(Detail, [{
    key: "initRelatedLists",
    value: function initRelatedLists() {
      var _this = this;

      if ($('table[data-filter-type="related-list"]').length == 0) {
        return;
      }

      this.relatedListDatatables = {};
      $('table[data-filter-type="related-list"]').each(function (index, el) {
        var datatable = new _datatable__WEBPACK_IMPORTED_MODULE_0__["Datatable"]();
        datatable.init(el);
        datatable.makeQuery();
        _this.relatedListDatatables[$(el).attr('id')] = datatable;
      });
    }
    /**
     * Show a selection modal and initialize datatable in it
     */

  }, {
    key: "initRelatedListSelectionModalListener",
    value: function initRelatedListSelectionModalListener() {
      var _this2 = this;

      $('.btn-relatedlist-select').on('click', function (event) {
        var element = event.currentTarget;
        var relatedListId = $(element).data('relatedlist');
        var modalTitle = $(element).data('modal-title');
        var modalIcon = $(element).data('modal-icon');
        var modalBody = $(".selection-modal-content[data-relatedlist='".concat(relatedListId, "']")).html();
        var relatedListTable = $(element).data('table'); // Get modal

        var modal = $('#relatedListSelectionModal'); // Change modal title

        $('h4 span', modal).text(modalTitle); // Change modal icon

        $('h4 i', modal).text(modalIcon); // Change modal body

        $('.modal-body', modal).html(modalBody); // Init datatable

        $('table tbody tr.record', modal).remove(); // Display search fields

        $('table thead .search', modal).removeClass('hide'); // Click callback

        var rowClickCallback = function rowClickCallback(event, datatable, recordId) {
          _this2.relatedListNNRowClickCallback(relatedListId, relatedListTable, datatable, recordId);
        }; // Init datatable for selection


        var datatable = new _datatable__WEBPACK_IMPORTED_MODULE_0__["Datatable"]();
        datatable.init($('table', modal), rowClickCallback);
        datatable.makeQuery();
      });
    }
    /**
     * Callback to call when a row is clicked in a datatable for a N-N related list
     * @param {integer} relatedListId
     * @param {Element} relatedListTable
     * @param {Object} modalDatatableInstance
     * @param {integer} relatedRecordId
     */

  }, {
    key: "relatedListNNRowClickCallback",
    value: function relatedListNNRowClickCallback(relatedListId, relatedListTable, modalDatatableInstance, relatedRecordId) {
      var _this3 = this;

      var url = $(modalDatatableInstance.table).data('add-relation-url');
      var data = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        id: $('meta[name="record"]').attr('content'),
        relatedlist: relatedListId,
        related_id: relatedRecordId // Ajax call to make a relation between two records

      };
      $.post(url, data).then(function (response) {
        // Display an alert if an error occured
        if (response.success === false) {
          swal(uctrans.trans('uccello::default.dialog.error.title'), response.message, 'error');
        } else {
          // Refresh relatedlist datatable
          var relatedListDatatable = _this3.relatedListDatatables[relatedListTable];

          if (relatedListDatatable) {
            relatedListDatatable.makeQuery();
          } // Hide modal


          $('#relatedListSelectionModal').modal('close');
        }
      });
    }
  }]);

  return Detail;
}();

/***/ }),

/***/ "./resources/assets/js/core/edit.js":
/*!******************************************!*\
  !*** ./resources/assets/js/core/edit.js ***!
  \******************************************/
/*! exports provided: Edit */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Edit", function() { return Edit; });
/* harmony import */ var _entity_field__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./entity_field */ "./resources/assets/js/core/entity_field.js");
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }


var Edit =
/*#__PURE__*/
function () {
  function Edit() {
    _classCallCheck(this, Edit);

    this.initListeners();
  }

  _createClass(Edit, [{
    key: "initListeners",
    value: function initListeners() {
      this.initDeleteCurrentFileListener();
      this.initSaveAndNewListener();
      this.initEntityFields();
    }
  }, {
    key: "initDeleteCurrentFileListener",
    value: function initDeleteCurrentFileListener() {
      $('.current-file .delete-file a').on('click', function (event) {
        event.preventDefault(); // Display file field

        $(event.currentTarget).parents('.form-group:first').find('.file-field').removeClass('hide'); // Remove current file

        $(event.currentTarget).parents('.form-group:first').find('.delete-file-field').val(1);
        $(event.currentTarget).parents('.current-file:first').remove();
      });
    }
  }, {
    key: "initSaveAndNewListener",
    value: function initSaveAndNewListener() {
      $('.btn-save-new').on('click', function () {
        // Set we want to create a new record after save
        $("input[name='save_new_hdn']").val(1);
        console.log('done' + $("input[name='save_new_hdn']").val()); // Submit form

        $('form.edit-form').submit();
      });
    }
    /**
     * Initalize datatable for all entity fields
     */

  }, {
    key: "initEntityFields",
    value: function initEntityFields() {
      var entityField = new _entity_field__WEBPACK_IMPORTED_MODULE_0__["EntityField"]();
    }
  }]);

  return Edit;
}();

/***/ }),

/***/ "./resources/assets/js/core/entity_field.js":
/*!**************************************************!*\
  !*** ./resources/assets/js/core/entity_field.js ***!
  \**************************************************/
/*! exports provided: EntityField */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "EntityField", function() { return EntityField; });
/* harmony import */ var _datatable__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./datatable */ "./resources/assets/js/core/datatable.js");
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }


var EntityField =
/*#__PURE__*/
function () {
  function EntityField() {
    _classCallCheck(this, EntityField);

    this.initEntityFields();
    this.initListener();
    this.relatedModule = '';
  }
  /**
   * Initalize datatable for all entity fields
   */


  _createClass(EntityField, [{
    key: "initEntityFields",
    value: function initEntityFields() {
      var _this = this;

      if ($('table[data-filter-type="related-list"]').length == 0) {
        return;
      }

      $('table[data-filter-type="related-list"]').each(function (index, el) {
        // Click callback
        var rowClickCallback = function rowClickCallback(event, datatable, recordId, recordLabel) {
          _this.selectRelatedModule(datatable, recordId, recordLabel);
        };

        var datatable = new _datatable__WEBPACK_IMPORTED_MODULE_0__["Datatable"]();
        datatable.init(el, rowClickCallback);
        datatable.makeQuery();
      });
      $('.delete-related-record').on('click', function (event) {
        var modal = $(event.currentTarget).parents('.modal:first');
        var fieldName = modal.attr('data-field');
        $("[name='".concat(fieldName, "']")).val('');
        $("#".concat(fieldName, "_display")).val('');
        $("#".concat(fieldName, "_display")).parent().find('label').removeClass('active');
        $(modal).modal('close');
      });
    }
  }, {
    key: "selectRelatedModule",
    value: function selectRelatedModule(datatable, recordId, recordLabel) {
      var modal = $(datatable.table).parents('.modal:first');
      var fieldName = modal.attr('data-field');
      $("[name='".concat(fieldName, "']")).val(recordId).trigger('keyup');
      $("#".concat(fieldName, "_display")).val(recordLabel);
      $("#".concat(fieldName, "_display")).parent().find('label').addClass('active');
      $(modal).modal('close');
    }
  }, {
    key: "initListener",
    value: function initListener() {
      $('a.entity-modal').on('click', function () {
        var tableId = $(this).attr('data-table');
        this.relatedModule = tableId.replace('datatable_', '');
        var event = new CustomEvent('uccello.list.refresh', {
          detail: tableId
        });
        dispatchEvent(event);
        var form = $('#form_popup_' + this.relatedModule);
        $(form).submit(function () {
          $.ajax({
            url: $(form).attr('action'),
            // or whatever
            type: 'POST',
            data: $(form).serialize(),
            success: function success(response) {
              var modal = $(form).parents('.modal:first');
              var fieldName = modal.attr('data-field');
              $("[name='".concat(fieldName, "']")).val(response.id).trigger('keyup');
              $("#".concat(fieldName, "_display")).val(response.display_name);
              $("#".concat(fieldName, "_display")).parent().find('label').addClass('active');
              $(modal).modal('close');
            }
          });
          return false;
        }); // let popup_save_url = $('meta[name="popup_url_'+this.relatedModule+'"]').attr('content');
        // $.get(popup_save_url).then((data) => {
        //     $('#tabCreateAjax').html(data);
        // });
      });
    }
  }]);

  return EntityField;
}();

/***/ }),

/***/ "./resources/assets/js/core/link.js":
/*!******************************************!*\
  !*** ./resources/assets/js/core/link.js ***!
  \******************************************/
/*! exports provided: Link */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Link", function() { return Link; });
function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Link =
/*#__PURE__*/
function () {
  function Link(initListeners) {
    _classCallCheck(this, Link);

    if (initListeners !== false) {
      this.initListeners();
    }
  }

  _createClass(Link, [{
    key: "initListeners",
    value: function initListeners() {
      this.initClickListener();
    }
  }, {
    key: "initClickListener",
    value: function initClickListener(parentElement) {
      var _this = this;

      if (typeof parentElement === 'undefined') {
        parentElement = null;
      }

      $("a[data-config], button[data-config]", parentElement).on('click', function (event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        _this.element = $(event.currentTarget);
        _this.config = _this.element.data('config'); // Remove focus

        _this.element.blur();

        if (_this.config === null) {
          _this.config = {};
        } // Check if we need a confirmation or if we laucnh action directly


        if (_this.config.confirm === true) {
          _this.confirm();
        } else {
          _this.launchAction();
        }
      });
    }
    /**
     * Launch action according to its type
     */

  }, {
    key: "launchAction",
    value: function launchAction() {
      switch (this.config.actionType) {
        // Simple link
        case 'link':
          this.gotoUrl();
          break;
        // Ajax call

        case 'ajax':
          this.callUrl();
          break;
      }
    }
    /**
     * Go to URL according to the target if it is defined
     */

  }, {
    key: "gotoUrl",
    value: function gotoUrl() {
      // Get target if defined, or use default one
      var target = this.config.target ? this.config.target : '_self'; // Go to the link

      window.open(this.element.attr('href'), target);
    }
    /**
     * Call URL by Ajax
     */

  }, {
    key: "callUrl",
    value: function callUrl() {
      // Get URL
      var url = this.element.attr('href'); // Get Ajax config

      var ajaxConfig = _typeof(this.config.ajax) === 'object' ? this.config.ajax : {}; // Method

      var method = "get";

      if (ajaxConfig.method) {
        method = ajaxConfig.method;
      } // Params


      var params = {};

      if (ajaxConfig.params) {
        params = ajaxConfig.params;
      } // If POST is used, add CSRF token to parameters


      if (method === 'post') {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        params['_token'] = csrfToken;
      } // Element to update with the call response


      var elementToUpdate = null;

      if (ajaxConfig.elementToUpdate) {
        elementToUpdate = ajaxConfig.elementToUpdate;
      } // Call


      $.ajax({
        url: url,
        method: method,
        data: params
      }).then(function (response) {
        // If we want to update an element in the page, do it
        if (elementToUpdate) {
          $(elementToUpdate).html(response.content);
        } // Display dialog displaying success or error
        else {
            if (response.success === false) {
              swal(uctrans.trans('uccello::default.dialog.error.title'), response.message, "error");
            } else {
              swal(uctrans.trans('uccello::default.dialog.success.title'), response.message, "success"); // Reload page if needed

              if (ajaxConfig.refresh === true) {
                document.location.reload();
              }
            }
          }
      }) // Impossible to reach the URL. Display error
      ["catch"](function (error) {
        swal(uctrans.trans('uccello::default.dialog.error.title'), error.message, "error");
      });
    }
    /**
     * Show confirm dialog
     */

  }, {
    key: "confirm",
    value: function confirm() {
      var _this2 = this;

      // Get config
      var title, text, confirmButtonText, confirmButtonColor, closeOnConfirm;

      if (_typeof(this.config.dialog) === 'object') {
        title = this.config.dialog.title;
        text = this.config.dialog.text;
        confirmButtonText = this.config.dialog.confirmButtonText;
        confirmButtonColor = this.config.dialog.confirmButtonColor;
        closeOnConfirm = this.config.dialog.closeOnConfirm;
      } // Default config


      if (!title) {
        title = uctrans.trans('uccello::default.confirm.dialog.title');
      }

      if (!confirmButtonText) {
        confirmButtonText = uctrans.trans('uccello::default.button.yes');
      }

      if (!confirmButtonColor) {
        confirmButtonColor = '#DD6B55';
      }

      if (!closeOnConfirm) {
        closeOnConfirm = true;
      } // If it is an AJAX call, show loader


      var showLoaderOnConfirm = false;

      if (this.config.actionType === 'ajax') {
        showLoaderOnConfirm = true;

        if (!this.config.ajax || !this.config.ajax.elementToUpdate) {
          closeOnConfirm = false;
        }
      } // Show confirm dialog


      swal({
        title: title,
        text: text,
        icon: "warning",
        buttons: {
          cancel: {
            text: uctrans.trans('uccello::default.button.no'),
            value: null,
            visible: true
          },
          confirm: {
            text: confirmButtonText,
            value: true,
            className: "red",
            closeModal: closeOnConfirm
          }
        }
      }).then(function (response) {
        if (response === true) {
          _this2.launchAction();
        }
      });
    }
  }]);

  return Link;
}();

/***/ }),

/***/ "./resources/assets/js/core/list.js":
/*!******************************************!*\
  !*** ./resources/assets/js/core/list.js ***!
  \******************************************/
/*! exports provided: List */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "List", function() { return List; });
/* harmony import */ var _datatable__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./datatable */ "./resources/assets/js/core/datatable.js");
/* harmony import */ var _entity_field__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./entity_field */ "./resources/assets/js/core/entity_field.js");
function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }



var List =
/*#__PURE__*/
function () {
  function List() {
    _classCallCheck(this, List);

    this.initDatatable();
    this.initListeners();
    this.initEntityFields();
    this.content_url = $('#datatable').attr('data-content-url');
  }
  /**
   * Init datatable
   */


  _createClass(List, [{
    key: "initDatatable",
    value: function initDatatable() {
      if ($('table[data-filter-type="list"]').length == 0) {
        return;
      }

      this.datatable = new _datatable__WEBPACK_IMPORTED_MODULE_0__["Datatable"]();
      this.datatable.init($('table[data-filter-type="list"]'));
      this.datatable.makeQuery();
    }
    /**
     * Init listeners
     */

  }, {
    key: "initListeners",
    value: function initListeners() {
      this.initSaveFilterListener();
      this.initDeleteFilterListener();
      this.initExportListener();
      this.initDescendantsRecordsFilterEventListener();
    }
    /**
     * Save a filter
     */

  }, {
    key: "initSaveFilterListener",
    value: function initSaveFilterListener() {
      var _this = this;

      $('#addFilterModal a.save').on('click', function (event) {
        var filterName = $('#add_filter_filter_name').val(); // Mandatory field

        if (filterName === '') {
          $('#add_filter_filter_name').parent('.form-line').addClass('focused error');
          return;
        }

        if ($("ul#filters-list a[data-name='".concat(filterName, "']")).length > 0) {
          swal(_defineProperty({
            title: uctrans.trans('uccello::default.filter.exists.title'),
            text: uctrans.trans('uccello::default.filter.exists.message'),
            icon: "warning",
            buttons: true,
            dangerMode: true
          }, "buttons", [uctrans.trans('uccello::default.button.no'), uctrans.trans('uccello::default.button.yes')])).then(function (response) {
            if (response === true) {
              // Save filter
              _this.saveFilter();
            }
          });
        } else {
          // Save filter
          _this.saveFilter();
        }
      });
    }
    /**
     * Delete a filter
     */

  }, {
    key: "initDeleteFilterListener",
    value: function initDeleteFilterListener() {
      var _this2 = this;

      var table = $('table[data-filter-type="list"]');
      $('a.delete-filter').on('click', function () {
        var selectedFilterId = $(table).attr('data-filter-id');

        if (selectedFilterId) {
          swal(_defineProperty({
            title: uctrans.trans('uccello::default.confirm.dialog.title'),
            text: uctrans.trans('uccello::default.filter.delete.message'),
            icon: "warning",
            buttons: true,
            dangerMode: true
          }, "buttons", [uctrans.trans('uccello::default.button.no'), uctrans.trans('uccello::default.button.yes')])).then(function (response) {
            if (response === true) {
              _this2.deleteFilter(selectedFilterId);
            }
          });
        }
      });
    }
    /**
     * Export records
     */

  }, {
    key: "initExportListener",
    value: function initExportListener() {
      var _this3 = this;

      $('#exportModal .export').on('click', function (event) {
        var table = $('table[data-filter-type="list"]');
        var modal = $('#exportModal'); // Export config

        var data = {
          _token: $('meta[name="csrf-token"]').attr('content'),
          extension: $('#export_format', modal).val(),
          columns: _this3.getVisibleColumns(table),
          conditions: _this3.getSearchConditions(table),
          order: $(table).attr('data-order') ? JSON.parse($(table).attr('data-order')) : null,
          with_hidden_columns: $('#with_hidden_columns', modal).is(':checked') ? 1 : 0,
          with_id: $('#export_with_id', modal).is(':checked') ? 1 : 0,
          with_conditions: $('#export_keep_conditions', modal).is(':checked') ? 1 : 0,
          with_order: $('#export_keep_order', modal).is(':checked') ? 1 : 0,
          with_timestamps: $('#export_with_timestamps', modal).is(':checked') ? 1 : 0,
          with_descendants: $('#export_with_descendants', modal).val() // URL

        };
        var url = table.data('export-url'); // Make a fake form to be able to download the file

        var fakeFormHtml = _this3.getFakeFormHtml(data, url); // Add the fake form into the page


        var fakeFormDom = $(fakeFormHtml);
        $("body").append(fakeFormDom); // Submit fake form to download the file

        fakeFormDom.submit(); // Remove the fake form from the page

        fakeFormDom.remove();
      });
    }
    /**
     * Get datatable visible columns
     * @param {Datatable} table
     * @return {array}
     */

  }, {
    key: "getVisibleColumns",
    value: function getVisibleColumns(table) {
      var visibleColumns = [];
      $('th[data-field]', table).each(function () {
        var fieldName = $(this).data('field');

        if ($(this).css('display') !== 'none') {
          visibleColumns.push(fieldName);
        }
      });
      return visibleColumns;
    }
    /**
     * Get search conditions
     * @param {Datatable} table
     * @return {Object}
     */

  }, {
    key: "getSearchConditions",
    value: function getSearchConditions(table) {
      var _this4 = this;

      var conditions = {};
      $('th[data-column]', table).each(function (index, el) {
        var fieldName = $(el).data('field');

        if (_this4.datatable.columns[fieldName].search) {
          conditions[fieldName] = _this4.datatable.columns[fieldName].search;
        }
      });
      return conditions;
    }
    /**
     * Get order with field colunm instead of column index
     * @param {Datatable} table
     * @return {Object}
     */

  }, {
    key: "getOrderWithFieldColumn",
    value: function getOrderWithFieldColumn(table) {
      var datatableColumns = this.datatable.columns;
      var order = {};
      var _iteratorNormalCompletion = true;
      var _didIteratorError = false;
      var _iteratorError = undefined;

      try {
        for (var _iterator = table.order()[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
          var sortOrder = _step.value;
          var index = sortOrder[0];
          order[datatableColumns[index - 1].db_column] = sortOrder[1];
        }
      } catch (err) {
        _didIteratorError = true;
        _iteratorError = err;
      } finally {
        try {
          if (!_iteratorNormalCompletion && _iterator["return"] != null) {
            _iterator["return"]();
          }
        } finally {
          if (_didIteratorError) {
            throw _iteratorError;
          }
        }
      }

      return order;
    }
    /**
     * Save filter into database
     */

  }, {
    key: "saveFilter",
    value: function saveFilter() {
      var table = $('table[data-filter-type="list"]');
      var modal = $('#addFilterModal'); // Save filter

      var data = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        name: $('#add_filter_filter_name', modal).val(),
        type: 'list',
        save_order: $('#add_filter_save_order', modal).is(':checked') ? 1 : 0,
        save_page_length: $('#add_filter_save_page_length', modal).is(':checked') ? 1 : 0,
        columns: this.getVisibleColumns(table),
        order: $(table).attr('data-order') ? JSON.parse($(table).attr('data-order')) : null,
        page_length: $(table).attr('data-length'),
        "public": $('#add_filter_is_public', modal).is(':checked') ? 1 : 0,
        "default": $('#add_filter_is_default', modal).is(':checked') ? 1 : 0 // Add search conditions if defined

      };
      var searchConditions = this.getSearchConditions(table);

      if (searchConditions !== {}) {
        data['conditions'] = {
          search: searchConditions
        };
      } else {
        data['conditions'] = null;
      }

      $.ajax({
        url: table.data('save-filter-url'),
        method: 'post',
        data: data,
        contentType: "application/x-www-form-urlencoded"
      }).then(function (response) {
        var filterToAdd = {
          id: response.id,
          name: response.name // Set filter name into the list

        };
        $('a[data-target="filters-list"] span').text(filterToAdd.name); // Set current filter id

        $(table).attr('data-filter-id', filterToAdd.id); // Remove disabled on delete button

        $('a.delete-filter').parents('li:first').show();
      }).fail(function (error) {
        swal(uctrans.trans('uccello::default.dialog.error.title'), error.message, 'error');
      });
    }
    /**
     * Retrieve a filter by its id and delete it
     * @param {integer} id
     */

  }, {
    key: "deleteFilter",
    value: function deleteFilter(id) {
      var table = $('table[data-filter-type="list"]');
      var data = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        id: id
      };
      var url = $(table).data('delete-filter-url');
      $.post(url, data).then(function (response) {
        if (response.success) {
          // Refresh list without filter
          document.location.href = $(table).data('list-url');
        } else {
          swal(uctrans.trans('uccello::default.dialog.error.title'), response.message, 'error');
        }
      }).fail(function (error) {
        swal(uctrans.trans('uccello::default.dialog.error.title'), error.message, 'error');
      });
    }
    /**
     * Make a fake form with data as hidden inputs
     *
     * @param {Object} data
     * @param {String} url
     */

  }, {
    key: "getFakeFormHtml",
    value: function getFakeFormHtml(data, url) {
      var form = "<form style='display: none;' method='POST' action='" + url + "'>";

      _.each(data, function (postValue, postKey) {
        // Convert into JSON if it is a complex data
        var escapedValue = _typeof(postValue) === 'object' ? JSON.stringify(postValue) : postValue; // Escape string (not for numbers)

        if (typeof escapedValue === 'string') {
          escapedValue = escapedValue.replace("\\", "\\\\").replace("'", "\'");
        } // Add data to the fake form


        form += "<input type='hidden' name='" + postKey + "' value='" + escapedValue + "'>";
      });

      form += "</form>";
      return form;
    }
    /**
     * Initalize datatable for all entity fields
     */

  }, {
    key: "initEntityFields",
    value: function initEntityFields() {
      var entityField = new _entity_field__WEBPACK_IMPORTED_MODULE_1__["EntityField"]();
    }
  }, {
    key: "initDescendantsRecordsFilterEventListener",
    value: function initDescendantsRecordsFilterEventListener() {
      var that = this;
      $('[data-descendants-records-filter]').on('click', function (e) {
        $('#dropdown-descendants-records').find('li').each(function () {
          $(this).removeClass('active');
        });
        $(this).parent().addClass('active');
        var seeDescendantsRecords = $(this).data('descendants-records-filter');
        var table = $(this).parents('ul:first').data('table'); // Show / Hide domain column

        var domainColumnOption = $('#dropdown-columns li a[data-field="domain"]');

        if (seeDescendantsRecords == 1) {
          $('#export_with_descendants').val(1);

          if (domainColumnOption.length > 0) {
            $(domainColumnOption).parents('li:first').addClass('active');
            $('th[data-field="domain"]').show();
          }
        } else {
          $('#export_with_descendants').val(0);

          if (domainColumnOption.length > 0) {
            $(domainColumnOption).parents('li:first').removeClass('active');
            $('th[data-field="domain"]').hide();
          }
        } // Add filter to content URL


        var $datatable = $('#' + table);
        $datatable.attr('data-content-url', that.content_url + '?descendants=' + seeDescendantsRecords);
        that.dispatchCustomEvent(table);
      });
    }
  }, {
    key: "dispatchCustomEvent",
    value: function dispatchCustomEvent(table_id) {
      // Refresh datatable
      var event = new CustomEvent('uccello.list.refresh', {
        detail: table_id
      });
      dispatchEvent(event);
    }
  }]);

  return List;
}();

/***/ }),

/***/ "./resources/assets/sass/app.scss":
/*!****************************************!*\
  !*** ./resources/assets/sass/app.scss ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/assets/sass/materialize.scss":
/*!************************************************!*\
  !*** ./resources/assets/sass/materialize.scss ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!********************************************************************************************************************************!*\
  !*** multi ./resources/assets/js/core/autoloader.js ./resources/assets/sass/materialize.scss ./resources/assets/sass/app.scss ***!
  \********************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! f:\VMs\Partage\ginkgo\packages\jerome-savin\uccello\resources\assets\js\core\autoloader.js */"./resources/assets/js/core/autoloader.js");
__webpack_require__(/*! f:\VMs\Partage\ginkgo\packages\jerome-savin\uccello\resources\assets\sass\materialize.scss */"./resources/assets/sass/materialize.scss");
module.exports = __webpack_require__(/*! f:\VMs\Partage\ginkgo\packages\jerome-savin\uccello\resources\assets\sass\app.scss */"./resources/assets/sass/app.scss");


/***/ })

},[[0,"/js/manifest"]]]);