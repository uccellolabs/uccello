(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/settings/autoloader"],{

/***/ "./node_modules/webpack/buildin/global.js":
/*!***********************************!*\
  !*** (webpack)/buildin/global.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

var g;

// This works in non-strict mode
g = (function() {
	return this;
})();

try {
	// This works if eval is allowed (see CSP)
	g = g || new Function("return this")();
} catch (e) {
	// This works if the window reference is available
	if (typeof window === "object") g = window;
}

// g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}

module.exports = g;


/***/ }),

/***/ "./node_modules/webpack/buildin/module.js":
/*!***********************************!*\
  !*** (webpack)/buildin/module.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = function(module) {
	if (!module.webpackPolyfill) {
		module.deprecate = function() {};
		module.paths = [];
		// module.parent = undefined by default
		if (!module.children) module.children = [];
		Object.defineProperty(module, "loaded", {
			enumerable: true,
			get: function() {
				return module.l;
			}
		});
		Object.defineProperty(module, "id", {
			enumerable: true,
			get: function() {
				return module.i;
			}
		});
		module.webpackPolyfill = 1;
	}
	return module;
};


/***/ }),

/***/ "./resources/assets/js/settings/autoloader.js":
/*!****************************************************!*\
  !*** ./resources/assets/js/settings/autoloader.js ***!
  \****************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _module_manager__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./module-manager */ "./resources/assets/js/settings/module-manager.js");
/* harmony import */ var _menu_manager__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./menu-manager */ "./resources/assets/js/settings/menu-manager.js");
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
        case 'module-manager':
          new _module_manager__WEBPACK_IMPORTED_MODULE_0__["ModuleManager"]();
          break;

        case 'menu-manager':
          new _menu_manager__WEBPACK_IMPORTED_MODULE_1__["MenuManager"]();
          break;
      }
    }
  }]);

  return Autoloader;
}();

new Autoloader();

/***/ }),

/***/ "./resources/assets/js/settings/menu-manager.js":
/*!******************************************************!*\
  !*** ./resources/assets/js/settings/menu-manager.js ***!
  \******************************************************/
/*! exports provided: MenuManager */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "MenuManager", function() { return MenuManager; });
function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var _ = __webpack_require__(/*! lodash */ "./node_modules/lodash/lodash.js");

var MenuManager =
/*#__PURE__*/
function () {
  function MenuManager() {
    _classCallCheck(this, MenuManager);

    this.initMenus();
    this.initListeners();
  }
  /**
   * Initialize main and admin menus
   */


  _createClass(MenuManager, [{
    key: "initMenus",
    value: function initMenus() {
      var _this = this;

      this.itemsNumber = 0; // Init HTML

      this.initMenuHtml('main-menu-structure', 'menu-main');
      this.initMenuHtml('admin-menu-structure', 'menu-admin'); // Initialize nestable library

      $('.menu-manager').nestable({
        maxDepth: 3
      }); // For initialization

      this.menuToJson(); // Save on change

      $('.menu-manager').on('change', function (event) {
        // Save change
        _this.menuToJson();

        _this.save();
      });
    }
    /**
     * Build menu html
     * @param {string} metaName
     * @param {string} listClass
     */

  }, {
    key: "initMenuHtml",
    value: function initMenuHtml(metaName, listClass) {
      var menuStructure = JSON.parse($("meta[name='".concat(metaName, "']")).attr('content'));

      if (_typeof(menuStructure) === 'object') {
        var menuHtml = '';
        var _iteratorNormalCompletion = true;
        var _didIteratorError = false;
        var _iteratorError = undefined;

        try {
          for (var _iterator = menuStructure[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
            var item = _step.value;
            menuHtml += this.buildItem(item);
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

        $(".menu-manager.".concat(listClass, " ol.dd-list:first")).html(menuHtml);
      }
    }
    /**
     * Init event listeners
     */

  }, {
    key: "initListeners",
    value: function initListeners() {
      this.initSaveGroupButtonListener();
      this.initSaveRouteLinkButtonListener();
      this.initSaveLinkButtonListener();
      this.initMenuSwitcherListener();
      this.initEditButtonListener();
      this.initRemoveButtonListener();
      this.initActionsButtonsListener();
      this.initResetButtonListener();
    }
    /**
     * Init menu switcher listener to switch between main and admin menus
     */

  }, {
    key: "initMenuSwitcherListener",
    value: function initMenuSwitcherListener() {
      var _this2 = this;

      $('input#menu-switcher').on('change', function (event) {
        var showAdminMenu = $(event.currentTarget).is(':checked');

        if (showAdminMenu) {
          $('.menu-manager.menu-admin').show();
          $('.menu-manager.menu-main').hide(); // Change menu type for reset button

          $('input#selected-menu').val('admin');
        } else {
          $('.menu-manager.menu-admin').hide();
          $('.menu-manager.menu-main').show(); // Change menu type for reset button

          $('input#selected-menu').val('main');
        } // Save active menu structure


        _this2.menuToJson();
      });
    }
    /**
     * Convert menu into JSON
     */

  }, {
    key: "menuToJson",
    value: function menuToJson() {
      this.menuStructure = JSON.stringify($('.menu-manager:visible').nestable('serialize'));
    }
    /**
     * Save current menu
     */

  }, {
    key: "save",
    value: function save() {
      var url = $("meta[name='save-url']").attr('content');
      $.ajax({
        url: url,
        method: "post",
        data: {
          _token: $("meta[name='csrf-token']").attr('content'),
          structure: this.menuStructure,
          type: $(".menu-manager:visible").data('type')
        }
      }).then(function (response) {
        $('span.saved').fadeIn().delay(1000).fadeOut();
      }).fail(function (error) {
        swal(uctrans.trans('uccello::default.dialog.error.title'), uctrans.trans('uccello::settings.menu_manager.error.save'), "error");
      });
    }
    /**
     * Init save group button listener
     */

  }, {
    key: "initSaveGroupButtonListener",
    value: function initSaveGroupButtonListener() {
      var _this3 = this;

      $('#save-group').on('click', function (event) {
        var labelElement = $("#groupModal input[name='label']");
        var iconElement = $("#groupModal input[name='icon']");
        var label = labelElement.val();
        var icon = iconElement.val();

        if (!label) {
          labelElement.addClass(['invalid']);
        }

        if (!icon) {
          iconElement.addClass(['invalid']);
        }

        if (!label || !icon) {
          return;
        } // Create group


        _this3.saveGroup(label, icon); // Close modal


        $('#groupModal').modal('close');
      });
    }
    /**
     * Init save route link button listener
     */

  }, {
    key: "initSaveRouteLinkButtonListener",
    value: function initSaveRouteLinkButtonListener() {
      var _this4 = this;

      $('#save-route-link').on('click', function (event) {
        var labelElement = $("#routeLinkModal input[name='label']");
        var iconElement = $("#routeLinkModal input[name='icon']");
        var moduleElement = $("#routeLinkModal input[name='module']");
        var routeElement = $("#routeLinkModal input[name='route']");
        var label = labelElement.val();
        var icon = iconElement.val();
        var moduleName = moduleElement.val();
        var route = routeElement.val();

        if (!label) {
          labelElement.addClass(['invalid']);
        }

        if (!icon) {
          iconElement.addClass(['invalid']);
        }

        if (!moduleName) {
          moduleElement.addClass(['invalid']);
        }

        if (!route) {
          routeElement.addClass(['invalid']);
        }

        if (!label || !icon || !moduleName || !route) {
          return;
        } // Create link


        _this4.saveRouteLink(label, icon, moduleName, route); // Close modal


        $('#routeLinkModal').modal('close');
      });
    }
    /**
     * Init save link button listener
     */

  }, {
    key: "initSaveLinkButtonListener",
    value: function initSaveLinkButtonListener() {
      var _this5 = this;

      $('#save-link').on('click', function (event) {
        var labelElement = $("#linkModal input[name='label']");
        var iconElement = $("#linkModal input[name='icon']");
        var urlElement = $("#linkModal input[name='url']");
        var label = labelElement.val();
        var icon = iconElement.val();
        var url = urlElement.val();

        if (!label) {
          labelElement.addClass(['invalid']);
        }

        if (!icon) {
          iconElement.addClass(['invalid']);
        }

        if (!url) {
          urlElement.addClass(['invalid']);
        }

        if (!label || !icon || !url) {
          return;
        } // Create link


        _this5.saveLink(label, icon, url); // Close modal


        $('#linkModal').modal('close');
      });
    }
    /**
     * Init actions buttons listener.
     * When an action button is clicked, empty modal input field and remove 'focused' class
     */

  }, {
    key: "initActionsButtonsListener",
    value: function initActionsButtonsListener() {
      var _this6 = this;

      $('.btn-actions').on('click', function () {
        _this6.currentItem = null;
        _this6.currentItemJson = null; // Empty fields

        $('.modal input').val('');
        $('.modal .form-line').removeClass('focused');
      });
    }
    /**
     * Init edit button listener
     */

  }, {
    key: "initEditButtonListener",
    value: function initEditButtonListener() {
      var _this7 = this;

      $('.btn-edit').off('click'); // Remove event listener first because this function can be called several times (e.g. after group creation)

      $('.btn-edit').on('click', function (event) {
        var item = $(event.currentTarget).parents('li:first');
        var itemId = $(item).data('id');
        _this7.currentItem = item; // Retrieve item JSON

        _this7.retrieveCurrentItemJsonById(itemId, JSON.parse(_this7.menuStructure));

        var modal;

        switch (item.data('type')) {
          // Group
          case 'group':
            modal = $('#groupModal').modal('open');
            $("input[name='label']", modal).val(item.data('label')).next('label').addClass('active');
            $("input[name='icon']", modal).val(item.data('icon')).next('label').addClass('active');
            break;
          // Link

          case 'link':
            modal = $('#linkModal').modal('open');
            $("input[name='label']", modal).val(item.data('label')).next('label').addClass('active');
            $("input[name='icon']", modal).val(item.data('icon')).next('label').addClass('active');
            $("input[name='url']", modal).val(item.data('url')).next('label').addClass('active');
            break;
          // Route link

          case 'route':
            modal = $('#routeLinkModal').modal('open');
            $("input[name='label']", modal).val(item.data('label')).next('label').addClass('active');
            $("input[name='icon']", modal).val(item.data('icon')).next('label').addClass('active');
            $("input[name='module']", modal).val(item.data('module')).next('label').addClass('active');
            $("input[name='route']", modal).val(item.data('route')).next('label').addClass('active');
            break;
        }
      });
    }
    /**
     * Init remove item button listener.
     * Only items without children can be removed.
     */

  }, {
    key: "initRemoveButtonListener",
    value: function initRemoveButtonListener() {
      var _this8 = this;

      $('.btn-remove').off('click'); // Remove event listener first because this function can be called several times (e.g. after group creation)

      $('.btn-remove').on('click', function (event) {
        var item = $(event.currentTarget).parents('li:first');
        var itemId = item.data('id'); // Retrieve item JSON

        _this8.retrieveCurrentItemJsonById(itemId, JSON.parse(_this8.menuStructure));

        if (_this8.currentItemJson && _this8.currentItemJson.children) {
          // There are children
          swal(uctrans.trans('uccello::settings.menu_manager.menu.error.not_empty.title'), uctrans.trans('uccello::settings.menu_manager.menu.error.not_empty.description'), 'error');
        } else {
          $('.menu-manager:visible').nestable('remove', itemId);

          _this8.menuToJson();

          _this8.save();
        }
      });
    }
  }, {
    key: "initResetButtonListener",
    value: function initResetButtonListener() {
      $('a#btn-reset-menu').on('click', function (event) {
        event.preventDefault();
        var element = $(event.currentTarget);
        swal({
          title: uctrans.trans('uccello::settings.menu_manager.menu.reset.title'),
          text: uctrans.trans('uccello::settings.menu_manager.menu.reset.text'),
          icon: 'warning',
          buttons: {
            cancel: {
              text: uctrans.trans('uccello::default.button.no'),
              value: null,
              visible: true
            },
            confirm: {
              text: uctrans.trans('uccello::default.yes'),
              value: true,
              className: "red"
            }
          }
        }).then(function (response) {
          if (response !== true) {
            return;
          }

          var data = {
            _token: $("meta[name='csrf-token']").attr('content'),
            type: $("input#selected-menu").val()
          };
          $.post(element.attr('href'), data).then(function () {
            document.location.reload();
          });
        });
      });
    }
    /**
     * Add or edit group
     * @param {string} label
     * @param {string} icon
     */

  }, {
    key: "saveGroup",
    value: function saveGroup(label, icon) {
      if (this.currentItem) {
        if (this.currentItemJson !== null) {
          this.currentItemJson.label = label;
          this.currentItemJson.icon = icon; // Edit data

          $(this.currentItem).attr('data-label', label).attr('data-icon', icon); // Change label

          $('.icon-label:first', this.currentItem).text(label); // Change icon

          $('.material-icons:first', this.currentItem).text(icon); // Replace JSON

          $('.menu-manager:visible').nestable('replace', this.currentItemJson); // Replace HTML

          var itemId = $(this.currentItem).data('id');
          $("[data-id='".concat(itemId, "']")).replaceWith(this.currentItem);
        }
      } else {
        var itemHtml = this.buildItem({
          type: 'group',
          label: label,
          icon: icon,
          color: 'green'
        }); // Add html

        $('.menu-manager:visible .dd-list:first').append(itemHtml);
      } // Trigger change to save


      $('.menu-manager:visible').trigger('change'); // Init listeners (they are remove when the html code is replace by update)

      this.initEditButtonListener();
      this.initRemoveButtonListener();
    }
    /**
     * Add or edit route link
     * @param {string} label
     * @param {string} icon
     * @param {string} moduleName
     * @param {string} route
     */

  }, {
    key: "saveRouteLink",
    value: function saveRouteLink(label, icon, moduleName, route) {
      if (this.currentItem) {
        if (this.currentItemJson !== null) {
          this.currentItemJson.label = label;
          this.currentItemJson.icon = icon;
          this.currentItemJson.module = moduleName;
          this.currentItemJson.route = route; // Edit data

          $(this.currentItem).attr('data-label', label).attr('data-icon', icon).attr('data-module', moduleName).attr('data-route', route); // Change label

          $('.icon-label:first', this.currentItem).text(label); // Change icon

          $('.material-icons:first', this.currentItem).text(icon); // Replace JSON

          $('.menu-manager:visible').nestable('replace', this.currentItemJson); // Replace HTML

          var itemId = $(this.currentItem).data('id');
          $("[data-id='".concat(itemId, "']")).replaceWith(this.currentItem);
        }
      } else {
        var itemHtml = this.buildItem({
          type: 'route',
          module: moduleName,
          label: label,
          icon: icon,
          color: 'red',
          route: route
        }); // Add html

        $('.menu-manager:visible .dd-list:first').append(itemHtml);
      } // Trigger change to save


      $('.menu-manager:visible').trigger('change'); // Init listeners (they are remove when the html code is replace by update)

      this.initEditButtonListener();
      this.initRemoveButtonListener();
    }
    /**
     * Add or edit link
     * @param {string} label
     * @param {string} icon
     * @param {string} url
     */

  }, {
    key: "saveLink",
    value: function saveLink(label, icon, url) {
      if (this.currentItem) {
        if (this.currentItemJson !== null) {
          this.currentItemJson.label = label;
          this.currentItemJson.icon = icon;
          this.currentItemJson.url = url; // Edit data

          $(this.currentItem).attr('data-label', label).attr('data-icon', icon).attr('data-url', url); // Change label

          $('.icon-label:first', this.currentItem).text(label); // Change icon

          $('.material-icons:first', this.currentItem).text(icon); // Replace JSON

          $('.menu-manager:visible').nestable('replace', this.currentItemJson); // Replace HTML

          var itemId = $(this.currentItem).data('id');
          $("[data-id='".concat(itemId, "']")).replaceWith(this.currentItem);
        }
      } else {
        var itemHtml = this.buildItem({
          type: 'link',
          label: label,
          icon: icon,
          color: 'blue',
          url: url
        }); // Add html

        $('.menu-manager:visible .dd-list:first').append(itemHtml);
      } // Trigger change to save


      $('.menu-manager:visible').trigger('change'); // Init listeners (they are remove when the html code is replace by update)

      this.initEditButtonListener();
      this.initRemoveButtonListener();
    }
    /**
     * Recursive function to find an item by id. Returns JSON
     * @param {string} id
     * @param {string} structure
     * @param {string} parentItem
     */

  }, {
    key: "retrieveCurrentItemJsonById",
    value: function retrieveCurrentItemJsonById(id, structure, parentItem) {
      if (_typeof(structure) == 'object') {
        var _iteratorNormalCompletion2 = true;
        var _didIteratorError2 = false;
        var _iteratorError2 = undefined;

        try {
          for (var _iterator2 = structure[Symbol.iterator](), _step2; !(_iteratorNormalCompletion2 = (_step2 = _iterator2.next()).done); _iteratorNormalCompletion2 = true) {
            var item = _step2.value;

            if (item.id == id) {
              this.currentItemJson = item;
              this.currentItemParentJson = parentItem;
              break;
            }

            if (item.children) {
              this.retrieveCurrentItemJsonById(id, item.children, item);
            }
          }
        } catch (err) {
          _didIteratorError2 = true;
          _iteratorError2 = err;
        } finally {
          try {
            if (!_iteratorNormalCompletion2 && _iterator2["return"] != null) {
              _iterator2["return"]();
            }
          } finally {
            if (_didIteratorError2) {
              throw _iteratorError2;
            }
          }
        }
      }
    }
    /**
     * Build item in html format
     * @param {object} item
     */

  }, {
    key: "buildItem",
    value: function buildItem(item) {
      var _this9 = this;

      this.itemsNumber++;
      var noChildrenClass = '';

      if (item.type !== 'group') {
        noChildrenClass = 'dd-nochildren';
      }

      var dataModule = '';

      if (item.module) {
        dataModule = "data-module=\"".concat(item.module, "\"");
      }

      var dataUrl = '';

      if (item.url) {
        dataUrl = "data-url=\"".concat(item.url, "\"");
      }

      var dataRoute = '';

      if (item.route) {
        dataRoute = "data-route=\"".concat(item.route, "\"");
      }

      var dataTranslation = '';
      var translation = item.label;

      if (item.translation) {
        dataTranslation = "data-translation=\"".concat(item.translation, "\"");
        translation = item.translation;
      }

      var actions = '';

      if (item.type !== 'module') {
        actions = "<div class=\"dd3-actions\">\n                        <a href=\"javascript:void(0)\" class=\"btn-edit primary-text\"><i class=\"material-icons\">edit</i></a>\n                        <a href=\"javascript:void(0)\" class=\"btn-remove primary-text\"><i class=\"material-icons\">delete</i></a>\n                    </div>";
      }

      var html = "<li class=\"dd-item dd3-item ".concat(noChildrenClass, "\" data-id=\"").concat(this.itemsNumber, "\" ").concat(dataModule, " ").concat(dataRoute, " ").concat(dataUrl, " data-type=\"").concat(item.type, "\" data-label=\"").concat(item.label, "\" ").concat(dataTranslation, " data-icon=\"").concat(item.icon, "\" data-nochildren=\"").concat(item.noChildren ? true : false, "\" data-color=\"").concat(item.color, "\">\n                    <div class=\"dd-handle dd3-content\">\n                        <i class=\"material-icons left\">").concat(item.icon, "</i>\n                        ").concat(translation, "\n                        <span class=\"right ").concat(item.color, "-text\">").concat(_.capitalize(item.type), "</span>\n                        </div>\n                    ").concat(actions);

      if (item.children) {
        html += "<ol class=\"dd-list\">";
        $.each(item.children, function (index, sub) {
          html += _this9.buildItem(sub);
        });
        html += "</ol>";
      }

      html += "</li>";
      return html;
    }
  }]);

  return MenuManager;
}();

/***/ }),

/***/ "./resources/assets/js/settings/module-manager.js":
/*!********************************************************!*\
  !*** ./resources/assets/js/settings/module-manager.js ***!
  \********************************************************/
/*! exports provided: ModuleManager */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ModuleManager", function() { return ModuleManager; });
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var ModuleManager =
/*#__PURE__*/
function () {
  function ModuleManager() {
    _classCallCheck(this, ModuleManager);

    this.initCheckboxListener();
  }

  _createClass(ModuleManager, [{
    key: "initCheckboxListener",
    value: function initCheckboxListener() {
      $("input[type='checkbox'].module-activation").on('click', function (event) {
        var element = event.currentTarget;
        var url = $("meta[name='module-activation-url']").attr('content');
        $.post(url, {
          _token: $("meta[name='csrf-token']").attr('content'),
          src_module: $(element).data('module'),
          active: $(element).is(':checked') === true ? '1' : '0'
        }).then(function () {
          var text = $(element).is(':checked') === true ? uctrans.trans('uccello::settings.module_manager.notification.module_activated') : uctrans.trans('uccello::settings.module_manager.notification.module_deactivated');
          M.toast({
            html: text
          });
        }).fail(function (error) {
          swal(uctrans.trans('uccello::default.dialog.error.title'), uctrans.trans('uccello::settings.module_manager.error.save'), 'error');
        });
      });
    }
  }]);

  return ModuleManager;
}();

/***/ }),

/***/ 1:
/*!**********************************************************!*\
  !*** multi ./resources/assets/js/settings/autoloader.js ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! f:\VMs\Partage\ginkgo\packages\jerome-savin\uccello\resources\assets\js\settings\autoloader.js */"./resources/assets/js/settings/autoloader.js");


/***/ })

},[[1,"/js/manifest","/js/vendor"]]]);