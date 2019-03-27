const _ = require('lodash');

export class MenuManager {
    constructor() {
        this.initMenus()
        this.initListeners()
    }

    /**
     * Initialize main and admin menus
     */
    initMenus() {

        this.itemsNumber = 0

        // Init HTML
        this.initMenuHtml('main-menu-structure', 'menu-main')
        this.initMenuHtml('admin-menu-structure', 'menu-admin')

        // Initialize nestable library
        $('.menu-manager').nestable({
            maxDepth: 3
        })

        // For initialization
        this.menuToJson()

        // Save on change
        $('.menu-manager').on('change', (event) => {
            // Save change
            this.menuToJson()
            this.save()
        });
    }

    /**
     * Build menu html
     * @param {string} metaName
     * @param {string} listClass
     */
    initMenuHtml(metaName, listClass) {
        let menuStructure = JSON.parse($(`meta[name='${metaName}']`).attr('content'))

        if (typeof menuStructure === 'object') {
            let menuHtml = ''
            for(let item of menuStructure) {
                menuHtml += this.buildItem(item)
            }

            $(`.menu-manager.${listClass} ol.dd-list:first`).html(menuHtml)
        }
    }

    /**
     * Init event listeners
     */
    initListeners() {
        this.initSaveGroupButtonListener()
        this.initSaveRouteLinkButtonListener()
        this.initSaveLinkButtonListener()
        this.initMenuSwitcherListener()
        this.initEditButtonListener()
        this.initRemoveButtonListener()
        this.initActionsButtonsListener()
    }

    /**
     * Init menu switcher listener to switch between main and admin menus
     */
    initMenuSwitcherListener() {
        $('input#menu-switcher').on('change', (event) => {
            let showAdminMenu = $(event.currentTarget).is(':checked')

            // Get reset button configuration
            let resetButtonConfig = JSON.parse($('a.btn-reset').attr('data-config'))

            if (showAdminMenu) {
                $('.menu-manager.menu-admin').show()
                $('.menu-manager.menu-main').hide()

                // Change menu type for reset button
                resetButtonConfig.ajax.params = {type: 'admin'}
            } else {
                $('.menu-manager.menu-admin').hide()
                $('.menu-manager.menu-main').show()

                // Change menu type for reset button
                resetButtonConfig.ajax.params = {type: 'main'}
            }

            // Update reset button configuration
            $('a.btn-reset').attr('data-config', JSON.stringify(resetButtonConfig))

            // Save active menu structure
            this.menuToJson()
        })
    }

    /**
     * Convert menu into JSON
     */
    menuToJson() {
        this.menuStructure = JSON.stringify($('.menu-manager:visible').nestable('serialize'))
    }

    /**
     * Save current menu
     */
    save() {
        let url = $("meta[name='save-url']").attr('content')

        $.ajax({
            url: url,
            method: "post",
            data: {
                _token: $("meta[name='csrf-token']").attr('content'),
                structure: this.menuStructure,
                type: $(".menu-manager:visible").data('type')
            }
        }).then((response) => {
            $('span.saved').fadeIn().delay(1000).fadeOut()
        }).fail((error) => {
            swal(uctrans('dialog.error.title', 'settings'), uctrans('error.save', 'settings'), "error")
        })
    }

    /**
     * Init save group button listener
     */
    initSaveGroupButtonListener() {
        $('#save-group').on('click', (event) => {
            let labelElement = $("#groupModal input[name='label']")
            let iconElement = $("#groupModal input[name='icon']")

            let label = labelElement.val()
            let icon = iconElement.val()

            if (!label) {
                labelElement.parent('.form-line').addClass(['focused', 'error'])
                return
            }

            if (!icon) {
                iconElement.parent('.form-line').addClass(['focused', 'error'])
                return
            }

            // Create group
            this.saveGroup(label, icon)

            // Hide modal
            $('#groupModal').modal('hide')
        })
    }

    /**
     * Init save route link button listener
     */
    initSaveRouteLinkButtonListener() {
        $('#save-route-link').on('click', (event) => {
            let labelElement = $("#routeLinkModal input[name='label']")
            let iconElement = $("#routeLinkModal input[name='icon']")
            let moduleElement = $("#routeLinkModal input[name='module']")
            let routeElement = $("#routeLinkModal input[name='route']")

            let label = labelElement.val()
            let icon = iconElement.val()
            let moduleName = moduleElement.val()
            let route = routeElement.val()

            if (!label) {
                labelElement.parent('.form-line').addClass(['focused', 'error'])
                return
            }

            if (!icon) {
                iconElement.parent('.form-line').addClass(['focused', 'error'])
                return
            }

            if (!moduleName) {
                moduleElement.parent('.form-line').addClass(['focused', 'error'])
                return
            }

            if (!route) {
                routeElement.parent('.form-line').addClass(['focused', 'error'])
                return
            }

            // Create link
            this.saveRouteLink(label, icon, moduleName, route)

            // Hide modal
            $('#routeLinkModal').modal('hide')
        })
    }

    /**
     * Init save link button listener
     */
    initSaveLinkButtonListener() {
        $('#save-link').on('click', (event) => {
            let labelElement = $("#linkModal input[name='label']")
            let iconElement = $("#linkModal input[name='icon']")
            let urlElement = $("#linkModal input[name='url']")

            let label = labelElement.val()
            let icon = iconElement.val()
            let url = urlElement.val()

            if (!label) {
                labelElement.parent('.form-line').addClass(['focused', 'error'])
                return
            }

            if (!icon) {
                iconElement.parent('.form-line').addClass(['focused', 'error'])
                return
            }

            if (!url) {
                urlElement.parent('.form-line').addClass(['focused', 'error'])
                return
            }

            // Create link
            this.saveLink(label, icon, url)

            // Hide modal
            $('#linkModal').modal('hide')
        })
    }

    /**
     * Init actions buttons listener.
     * When an action button is clicked, empty modal input field and remove 'focused' class
     */
    initActionsButtonsListener() {
        $('.btn-actions').on('click', () => {
            this.currentItem = null
            this.currentItemJson = null

            // Empty fields
            $('.modal input').val('')
            $('.modal .form-line').removeClass('focused')
        })
    }

    /**
     * Init edit button listener
     */
    initEditButtonListener() {
        $('.btn-edit').off('click') // Remove event listener first because this function can be called several times (e.g. after group creation)

        $('.btn-edit').on('click', (event) => {
            let item = $(event.currentTarget).parents('li:first')
            let itemId = $(item).data('id')
            this.currentItem = item

            // Retrieve item JSON
            this.retrieveCurrentItemJsonById(itemId, JSON.parse(this.menuStructure))

            let modal
            switch (item.data('type')) {
                // Group
                case 'group':
                        modal = $('#groupModal').modal()
                        $("input[name='label']", modal).val(item.data('label')).parent().addClass('focused')
                        $("input[name='icon']", modal).val(item.data('icon')).parent().addClass('focused')
                    break

                // Link
                case 'link':
                        modal = $('#linkModal').modal()
                        $("input[name='label']", modal).val(item.data('label')).parent().addClass('focused')
                        $("input[name='icon']", modal).val(item.data('icon')).parent().addClass('focused')
                        $("input[name='url']", modal).val(item.data('url')).parent().addClass('focused')
                    break

                // Route link
                case 'route':
                        modal = $('#routelinkModal').modal()
                        $("input[name='label']", modal).val(item.data('label')).parent().addClass('focused')
                        $("input[name='icon']", modal).val(item.data('icon')).parent().addClass('focused')
                        $("input[name='module']", modal).val(item.data('module')).parent().addClass('focused')
                        $("input[name='route']", modal).val(item.data('route')).parent().addClass('focused')
                    break
            }
        })
    }

    /**
     * Init remove item button listener.
     * Only items without children can be removed.
     */
    initRemoveButtonListener() {
        $('.btn-remove').off('click') // Remove event listener first because this function can be called several times (e.g. after group creation)

        $('.btn-remove').on('click', (event) => {
            let item = $(event.currentTarget).parents('li:first')
            let itemId = item.data('id')

            // Retrieve item JSON
            this.retrieveCurrentItemJsonById(itemId, JSON.parse(this.menuStructure))

            if (this.currentItemJson && this.currentItemJson.children) { // There are children
                swal(uctrans('menu.error.not_empty.title', 'settings'), uctrans('menu.error.not_empty.description', 'settings'), 'error')
            } else {
                $('.menu-manager:visible').nestable('remove', itemId)
                this.menuToJson()
                this.save()
            }
        })
    }

    /**
     * Add or edit group
     * @param {string} label
     * @param {string} icon
     */
    saveGroup(label, icon) {
        if (this.currentItem) {

            if (this.currentItemJson !== null) {
                this.currentItemJson.label = label
                this.currentItemJson.icon = icon

                // Edit data
                $(this.currentItem).attr('data-label', label).attr('data-icon', icon)

                // Change label
                $('.icon-label:first', this.currentItem).text(label)

                // Change icon
                $('.material-icons:first', this.currentItem).text(icon)

                // Replace JSON
                $('.menu-manager:visible').nestable('replace', this.currentItemJson)

                // Replace HTML
                let itemId = $(this.currentItem).data('id')
                $(`[data-id='${itemId}']`).replaceWith(this.currentItem)
            }

        } else {
            let itemHtml = this.buildItem({
                type: 'group',
                label: label,
                icon: icon,
                color: 'green'
            })

            // Add html
            $('.menu-manager:visible .dd-list:first').append(itemHtml);
        }

        // Trigger change to save
        $('.menu-manager:visible').trigger('change')

        // Init listeners (they are remove when the html code is replace by update)
        this.initEditButtonListener()
        this.initRemoveButtonListener()
    }

    /**
     * Add or edit route link
     * @param {string} label
     * @param {string} icon
     * @param {string} moduleName
     * @param {string} route
     */
    saveRouteLink(label, icon, moduleName, route) {
        if (this.currentItem) {

            if (this.currentItemJson !== null) {
                this.currentItemJson.label = label
                this.currentItemJson.icon = icon
                this.currentItemJson.module = moduleName
                this.currentItemJson.route = route

                // Edit data
                $(this.currentItem)
                    .attr('data-label', label)
                    .attr('data-icon', icon)
                    .attr('data-module', moduleName)
                    .attr('data-route', route)

                // Change label
                $('.icon-label:first', this.currentItem).text(label)

                // Change icon
                $('.material-icons:first', this.currentItem).text(icon)

                // Replace JSON
                $('.menu-manager:visible').nestable('replace', this.currentItemJson)

                // Replace HTML
                let itemId = $(this.currentItem).data('id')
                $(`[data-id='${itemId}']`).replaceWith(this.currentItem)
            }

        } else {
            let itemHtml = this.buildItem({
                type: 'route',
                module: moduleName,
                label: label,
                icon: icon,
                color: 'red',
                route: route
            })

            // Add html
            $('.menu-manager:visible .dd-list:first').append(itemHtml);
        }

        // Trigger change to save
        $('.menu-manager:visible').trigger('change')

        // Init listeners (they are remove when the html code is replace by update)
        this.initEditButtonListener()
        this.initRemoveButtonListener()
    }

    /**
     * Add or edit link
     * @param {string} label
     * @param {string} icon
     * @param {string} url
     */
    saveLink(label, icon, url) {
        if (this.currentItem) {

            if (this.currentItemJson !== null) {
                this.currentItemJson.label = label
                this.currentItemJson.icon = icon
                this.currentItemJson.url = url

                // Edit data
                $(this.currentItem)
                    .attr('data-label', label)
                    .attr('data-icon', icon)
                    .attr('data-url', url)

                // Change label
                $('.icon-label:first', this.currentItem).text(label)

                // Change icon
                $('.material-icons:first', this.currentItem).text(icon)

                // Replace JSON
                $('.menu-manager:visible').nestable('replace', this.currentItemJson)

                // Replace HTML
                let itemId = $(this.currentItem).data('id')
                $(`[data-id='${itemId}']`).replaceWith(this.currentItem)
            }

        } else {
            let itemHtml = this.buildItem({
                type: 'link',
                label: label,
                icon: icon,
                color: 'blue',
                url: url
            })

            // Add html
            $('.menu-manager:visible .dd-list:first').append(itemHtml);
        }

        // Trigger change to save
        $('.menu-manager:visible').trigger('change')

        // Init listeners (they are remove when the html code is replace by update)
        this.initEditButtonListener()
        this.initRemoveButtonListener()
    }

    /**
     * Recursive function to find an item by id. Returns JSON
     * @param {string} id
     * @param {string} structure
     * @param {string} parentItem
     */
    retrieveCurrentItemJsonById(id, structure, parentItem) {
        if (typeof structure == 'object') {
            for (let item of structure) {
                if (item.id == id) {
                    this.currentItemJson = item
                    this.currentItemParentJson = parentItem
                    break
                }

                if (item.children) {
                    this.retrieveCurrentItemJsonById(id, item.children, item)
                }
            }
        }
    }

    /**
     * Build item in html format
     * @param {object} item
     */
    buildItem(item) {

        this.itemsNumber++

        let noChildrenClass = ''
        if (item.type !== 'group') {
            noChildrenClass = 'dd-nochildren'
        }

        let dataModule = ''
        if (item.module) {
            dataModule = `data-module="${item.module}"`
        }

        let dataUrl = ''
        if (item.url) {
            dataUrl = `data-url="${item.url}"`
        }

        let dataRoute = ''
        if (item.route) {
            dataRoute = `data-route="${item.route}"`
        }

        let dataTranslation = ''
        let translation = item.label
        if (item.translation) {
            dataTranslation = `data-translation="${item.translation}"`
            translation = item.translation
        }

        let actions = ''
        if (item.type !== 'module') {
            actions = `<div class="dd3-actions">
                        <i class="material-icons btn-edit">edit</i>
                        <i class="material-icons btn-remove">delete</i>
                    </div>`
        }

        let html = `<li class="dd-item dd3-item ${noChildrenClass}" data-id="${this.itemsNumber}" ${dataModule} ${dataRoute} ${dataUrl} data-type="${item.type}" data-label="${item.label}" ${dataTranslation} data-icon="${item.icon}" data-nochildren="${item.noChildren ? true : false}" data-color="${item.color}">
                <div class="dd-handle dd3-content">
                    <i class="material-icons">${item.icon}</i>
                    <span class="icon-label">${translation}</span>
                    <span class="pull-right col-${item.color}">${_.capitalize(item.type)}</span>
                </div>
                ${actions}`

        if (item.children) {
            html += `<ol class="dd-list">`
            $.each(item.children, (index, sub) => {
                html += this.buildItem(sub);
            });
            html += `</ol>`

        }

        html += `</li>`

        return html;
    }
}