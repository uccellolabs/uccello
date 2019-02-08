webpackJsonp([3],{

/***/ "./resources/assets/js/settings/autoloader.js":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* WEBPACK VAR INJECTION */(function($) {/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__module_manager__ = __webpack_require__("./resources/assets/js/settings/module-manager.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__menu_manager__ = __webpack_require__("./resources/assets/js/settings/menu-manager.js");
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }




var Autoloader = function () {
    function Autoloader() {
        _classCallCheck(this, Autoloader);

        this.lazyLoad();
    }

    _createClass(Autoloader, [{
        key: 'lazyLoad',
        value: function lazyLoad() {
            var page = $('meta[name="page"]').attr('content');

            switch (page) {
                case 'module-manager':
                    new __WEBPACK_IMPORTED_MODULE_0__module_manager__["a" /* ModuleManager */]();
                    break;

                case 'menu-manager':
                    new __WEBPACK_IMPORTED_MODULE_1__menu_manager__["a" /* MenuManager */]();
                    break;
            }
        }
    }]);

    return Autoloader;
}();

new Autoloader();
/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__("./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/settings/menu-manager.js":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function($) {/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return MenuManager; });
var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var _ = __webpack_require__("./node_modules/lodash/lodash.js");

var MenuManager = function () {
    function MenuManager() {
        _classCallCheck(this, MenuManager);

        this.initMenus();
        this.initListeners();
    }

    /**
     * Initialize main and admin menus
     */


    _createClass(MenuManager, [{
        key: 'initMenus',
        value: function initMenus() {
            var _this = this;

            this.itemsNumber = 0;

            // Init HTML
            this.initMenuHtml('main-menu-structure', 'menu-main');
            this.initMenuHtml('admin-menu-structure', 'menu-admin');

            // Initialize nestable library
            $('.menu-manager').nestable({
                maxDepth: 3
            });

            // For initialization
            this.menuToJson();

            // Save on change
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
        key: 'initMenuHtml',
        value: function initMenuHtml(metaName, listClass) {
            var menuStructure = JSON.parse($('meta[name=\'' + metaName + '\']').attr('content'));

            if ((typeof menuStructure === 'undefined' ? 'undefined' : _typeof(menuStructure)) === 'object') {
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
                        if (!_iteratorNormalCompletion && _iterator.return) {
                            _iterator.return();
                        }
                    } finally {
                        if (_didIteratorError) {
                            throw _iteratorError;
                        }
                    }
                }

                $('.menu-manager.' + listClass + ' ol.dd-list:first').html(menuHtml);
            }
        }

        /**
         * Init event listeners
         */

    }, {
        key: 'initListeners',
        value: function initListeners() {
            this.initSaveGroupButtonListener();
            this.initSaveRouteLinkButtonListener();
            this.initSaveLinkButtonListener();
            this.initMenuSwitcherListener();
            this.initEditButtonListener();
            this.initRemoveButtonListener();
            this.initActionsButtonsListener();
        }

        /**
         * Init menu switcher listener to switch between main and admin menus
         */

    }, {
        key: 'initMenuSwitcherListener',
        value: function initMenuSwitcherListener() {
            var _this2 = this;

            $('input#menu-switcher').on('change', function (event) {
                var showAdminMenu = $(event.currentTarget).is(':checked');

                // Get reset button configuration
                var resetButtonConfig = JSON.parse($('a.btn-reset').attr('data-config'));

                if (showAdminMenu) {
                    $('.menu-manager.menu-admin').show();
                    $('.menu-manager.menu-main').hide();

                    // Change menu type for reset button
                    resetButtonConfig.ajax.params = { type: 'admin' };
                } else {
                    $('.menu-manager.menu-admin').hide();
                    $('.menu-manager.menu-main').show();

                    // Change menu type for reset button
                    resetButtonConfig.ajax.params = { type: 'main' };
                }

                // Update reset button configuration
                $('a.btn-reset').attr('data-config', JSON.stringify(resetButtonConfig));

                // Save active menu structure
                _this2.menuToJson();
            });
        }

        /**
         * Convert menu into JSON
         */

    }, {
        key: 'menuToJson',
        value: function menuToJson() {
            this.menuStructure = JSON.stringify($('.menu-manager:visible').nestable('serialize'));
        }

        /**
         * Save current menu
         */

    }, {
        key: 'save',
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
                swal(uctrans('dialog.error.title', 'settings'), uctrans('error.save', 'settings'), "error");
            });
        }

        /**
         * Init save group button listener
         */

    }, {
        key: 'initSaveGroupButtonListener',
        value: function initSaveGroupButtonListener() {
            var _this3 = this;

            $('#save-group').on('click', function (event) {
                var labelElement = $("#groupModal input[name='label']");
                var iconElement = $("#groupModal input[name='icon']");

                var label = labelElement.val();
                var icon = iconElement.val();

                if (!label) {
                    labelElement.parent('.form-line').addClass(['focused', 'error']);
                    return;
                }

                if (!icon) {
                    iconElement.parent('.form-line').addClass(['focused', 'error']);
                    return;
                }

                // Create group
                _this3.saveGroup(label, icon);

                // Hide modal
                $('#groupModal').modal('hide');
            });
        }

        /**
         * Init save route link button listener
         */

    }, {
        key: 'initSaveRouteLinkButtonListener',
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
                    labelElement.parent('.form-line').addClass(['focused', 'error']);
                    return;
                }

                if (!icon) {
                    iconElement.parent('.form-line').addClass(['focused', 'error']);
                    return;
                }

                if (!moduleName) {
                    moduleElement.parent('.form-line').addClass(['focused', 'error']);
                    return;
                }

                if (!route) {
                    routeElement.parent('.form-line').addClass(['focused', 'error']);
                    return;
                }

                // Create link
                _this4.saveRouteLink(label, icon, moduleName, route);

                // Hide modal
                $('#routeLinkModal').modal('hide');
            });
        }

        /**
         * Init save link button listener
         */

    }, {
        key: 'initSaveLinkButtonListener',
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
                    labelElement.parent('.form-line').addClass(['focused', 'error']);
                    return;
                }

                if (!icon) {
                    iconElement.parent('.form-line').addClass(['focused', 'error']);
                    return;
                }

                if (!url) {
                    urlElement.parent('.form-line').addClass(['focused', 'error']);
                    return;
                }

                // Create link
                _this5.saveLink(label, icon, url);

                // Hide modal
                $('#linkModal').modal('hide');
            });
        }

        /**
         * Init actions buttons listener.
         * When an action button is clicked, empty modal input field and remove 'focused' class
         */

    }, {
        key: 'initActionsButtonsListener',
        value: function initActionsButtonsListener() {
            var _this6 = this;

            $('.btn-actions').on('click', function () {
                _this6.currentItem = null;
                _this6.currentItemJson = null;

                // Empty fields
                $('.modal input').val('');
                $('.modal .form-line').removeClass('focused');
            });
        }

        /**
         * Init edit button listener
         */

    }, {
        key: 'initEditButtonListener',
        value: function initEditButtonListener() {
            var _this7 = this;

            $('.btn-edit').off('click'); // Remove event listener first because this function can be called several times (e.g. after group creation)

            $('.btn-edit').on('click', function (event) {
                var item = $(event.currentTarget).parents('li:first');
                var itemId = $(item).data('id');
                _this7.currentItem = item;

                // Retrieve item JSON
                _this7.retrieveCurrentItemJsonById(itemId, JSON.parse(_this7.menuStructure));

                var modal = void 0;
                switch (item.data('type')) {
                    // Group
                    case 'group':
                        modal = $('#groupModal').modal('show');
                        $("input[name='label']", modal).val(item.data('label')).parent().addClass('focused');
                        $("input[name='icon']", modal).val(item.data('icon')).parent().addClass('focused');
                        break;

                    // Link
                    case 'link':
                        modal = $('#linkModal').modal('show');
                        $("input[name='label']", modal).val(item.data('label')).parent().addClass('focused');
                        $("input[name='icon']", modal).val(item.data('icon')).parent().addClass('focused');
                        $("input[name='url']", modal).val(item.data('url')).parent().addClass('focused');
                        break;

                    // Route link
                    case 'route':
                        modal = $('#routelinkModal').modal('show');
                        $("input[name='label']", modal).val(item.data('label')).parent().addClass('focused');
                        $("input[name='icon']", modal).val(item.data('icon')).parent().addClass('focused');
                        $("input[name='module']", modal).val(item.data('module')).parent().addClass('focused');
                        $("input[name='route']", modal).val(item.data('route')).parent().addClass('focused');
                        break;
                }
            });
        }

        /**
         * Init remove item button listener.
         * Only items without children can be removed.
         */

    }, {
        key: 'initRemoveButtonListener',
        value: function initRemoveButtonListener() {
            var _this8 = this;

            $('.btn-remove').off('click'); // Remove event listener first because this function can be called several times (e.g. after group creation)

            $('.btn-remove').on('click', function (event) {
                var item = $(event.currentTarget).parents('li:first');
                var itemId = item.data('id');

                // Retrieve item JSON
                _this8.retrieveCurrentItemJsonById(itemId, JSON.parse(_this8.menuStructure));

                if (_this8.currentItemJson && _this8.currentItemJson.children) {
                    // There are children
                    swal(uctrans('menu.error.not_empty.title', 'settings'), uctrans('menu.error.not_empty.description', 'settings'), 'error');
                } else {
                    $('.menu-manager:visible').nestable('remove', itemId);
                    _this8.menuToJson();
                    _this8.save();
                }
            });
        }

        /**
         * Add or edit group
         * @param {string} label
         * @param {string} icon
         */

    }, {
        key: 'saveGroup',
        value: function saveGroup(label, icon) {
            if (this.currentItem) {

                if (this.currentItemJson !== null) {
                    this.currentItemJson.label = label;
                    this.currentItemJson.icon = icon;

                    // Edit data
                    $(this.currentItem).attr('data-label', label).attr('data-icon', icon);

                    // Change label
                    $('.icon-label:first', this.currentItem).text(label);

                    // Change icon
                    $('.material-icons:first', this.currentItem).text(icon);

                    // Replace JSON
                    $('.menu-manager:visible').nestable('replace', this.currentItemJson);

                    // Replace HTML
                    var itemId = $(this.currentItem).data('id');
                    $('[data-id=\'' + itemId + '\']').replaceWith(this.currentItem);
                }
            } else {
                var itemHtml = this.buildItem({
                    type: 'group',
                    label: label,
                    icon: icon,
                    color: 'green'
                });

                // Add html
                $('.menu-manager:visible .dd-list:first').append(itemHtml);
            }

            // Trigger change to save
            $('.menu-manager:visible').trigger('change');

            // Init listeners (they are remove when the html code is replace by update)
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
        key: 'saveRouteLink',
        value: function saveRouteLink(label, icon, moduleName, route) {
            if (this.currentItem) {

                if (this.currentItemJson !== null) {
                    this.currentItemJson.label = label;
                    this.currentItemJson.icon = icon;
                    this.currentItemJson.module = moduleName;
                    this.currentItemJson.route = route;

                    // Edit data
                    $(this.currentItem).attr('data-label', label).attr('data-icon', icon).attr('data-module', moduleName).attr('data-route', route);

                    // Change label
                    $('.icon-label:first', this.currentItem).text(label);

                    // Change icon
                    $('.material-icons:first', this.currentItem).text(icon);

                    // Replace JSON
                    $('.menu-manager:visible').nestable('replace', this.currentItemJson);

                    // Replace HTML
                    var itemId = $(this.currentItem).data('id');
                    $('[data-id=\'' + itemId + '\']').replaceWith(this.currentItem);
                }
            } else {
                var itemHtml = this.buildItem({
                    type: 'route',
                    module: moduleName,
                    label: label,
                    icon: icon,
                    color: 'red',
                    route: route
                });

                // Add html
                $('.menu-manager:visible .dd-list:first').append(itemHtml);
            }

            // Trigger change to save
            $('.menu-manager:visible').trigger('change');

            // Init listeners (they are remove when the html code is replace by update)
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
        key: 'saveLink',
        value: function saveLink(label, icon, url) {
            if (this.currentItem) {

                if (this.currentItemJson !== null) {
                    this.currentItemJson.label = label;
                    this.currentItemJson.icon = icon;
                    this.currentItemJson.url = url;

                    // Edit data
                    $(this.currentItem).attr('data-label', label).attr('data-icon', icon).attr('data-url', url);

                    // Change label
                    $('.icon-label:first', this.currentItem).text(label);

                    // Change icon
                    $('.material-icons:first', this.currentItem).text(icon);

                    // Replace JSON
                    $('.menu-manager:visible').nestable('replace', this.currentItemJson);

                    // Replace HTML
                    var itemId = $(this.currentItem).data('id');
                    $('[data-id=\'' + itemId + '\']').replaceWith(this.currentItem);
                }
            } else {
                var itemHtml = this.buildItem({
                    type: 'link',
                    label: label,
                    icon: icon,
                    color: 'blue',
                    url: url
                });

                // Add html
                $('.menu-manager:visible .dd-list:first').append(itemHtml);
            }

            // Trigger change to save
            $('.menu-manager:visible').trigger('change');

            // Init listeners (they are remove when the html code is replace by update)
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
        key: 'retrieveCurrentItemJsonById',
        value: function retrieveCurrentItemJsonById(id, structure, parentItem) {
            if ((typeof structure === 'undefined' ? 'undefined' : _typeof(structure)) == 'object') {
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
                        if (!_iteratorNormalCompletion2 && _iterator2.return) {
                            _iterator2.return();
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
        key: 'buildItem',
        value: function buildItem(item) {
            var _this9 = this;

            this.itemsNumber++;

            var noChildrenClass = '';
            if (item.type !== 'group') {
                noChildrenClass = 'dd-nochildren';
            }

            var dataModule = '';
            if (item.module) {
                dataModule = 'data-module="' + item.module + '"';
            }

            var dataUrl = '';
            if (item.url) {
                dataUrl = 'data-url="' + item.url + '"';
            }

            var dataRoute = '';
            if (item.route) {
                dataRoute = 'data-route="' + item.route + '"';
            }

            var dataTranslation = '';
            var translation = item.label;
            if (item.translation) {
                dataTranslation = 'data-translation="' + item.translation + '"';
                translation = item.translation;
            }

            var actions = '';
            if (item.type !== 'module') {
                actions = '<div class="dd3-actions">\n                        <i class="material-icons btn-edit">edit</i>\n                        <i class="material-icons btn-remove">delete</i>\n                    </div>';
            }

            var html = '<li class="dd-item dd3-item ' + noChildrenClass + '" data-id="' + this.itemsNumber + '" ' + dataModule + ' ' + dataRoute + ' ' + dataUrl + ' data-type="' + item.type + '" data-label="' + item.label + '" ' + dataTranslation + ' data-icon="' + item.icon + '" data-nochildren="' + (item.noChildren ? true : false) + '" data-color="' + item.color + '">\n                <div class="dd-handle dd3-content">\n                    <i class="material-icons">' + item.icon + '</i>\n                    <span class="icon-label">' + translation + '</span>\n                    <span class="pull-right col-' + item.color + '">' + _.capitalize(item.type) + '</span>\n                </div>\n                ' + actions;

            if (item.children) {
                html += '<ol class="dd-list">';
                $.each(item.children, function (index, sub) {
                    html += _this9.buildItem(sub);
                });
                html += '</ol>';
            }

            html += '</li>';

            return html;
        }
    }]);

    return MenuManager;
}();
/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__("./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./resources/assets/js/settings/module-manager.js":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function($) {/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ModuleManager; });
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var ModuleManager = function () {
    function ModuleManager() {
        _classCallCheck(this, ModuleManager);

        this.initCheckboxListener();
    }

    _createClass(ModuleManager, [{
        key: 'initCheckboxListener',
        value: function initCheckboxListener() {
            var domainSlug = $('meta[name="domain"]').attr('content');

            $("input[type='checkbox'].module-activation").on('click', function (event) {
                var element = event.currentTarget;
                var url = laroute.route('uccello.settings.module.activation', { domain: domainSlug });

                $.post(url, {
                    _token: $("meta[name='csrf-token']").attr('content'),
                    src_module: $(element).data('module'),
                    active: $(element).is(':checked') === true ? '1' : '0'
                }).fail(function (error) {
                    swal(uctrans('dialog.error.title'), uctrans('error.save', 'settings'), 'error');
                });
            });
        }
    }]);

    return ModuleManager;
}();
/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__("./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ 2:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__("./resources/assets/js/settings/autoloader.js");


/***/ })

},[2]);