const _ = require('lodash');

export class MenuManager {
    constructor() {
        this.initMenus()
        this.initListeners()
    }

    initMenus() {

        // Init HTML
        this.initMenuHtml('main-menu-structure', 'menu-main')
        this.initMenuHtml('admin-menu-structure', 'menu-admin')


        $('.menu-manager').nestable({
            maxDepth: 3
        })

        // For initialization
        this.menuToJson()

        $('.menu-manager').on('change', (event) => {
            // Save change
            this.menuToJson()
            this.save()
        });
    }

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

    initListeners() {
        this.initSaveListener()
        this.initAddGroupListener()
        this.initAddRouteLinkListener()
        this.initAddLinkListener()
        this.initMenuSwitcherListener()
    }

    initMenuSwitcherListener() {
        $('input#menu-switcher').on('change', (event) => {
            let showAdminMenu = $(event.currentTarget).is(':checked')

            if (showAdminMenu) {
                $('.menu-manager.menu-admin').show()
                $('.menu-manager.menu-main').hide()
            } else {
                $('.menu-manager.menu-admin').hide()
                $('.menu-manager.menu-main').show()
            }

            // Save active menu structure
            this.menuToJson()
        })
    }

    initSaveListener() {
        $('a.save-menu').on('click', (event) => {
            event.preventDefault()

            this.save()
        })
    }

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
            // swal("Success", response.message, "success")
        }).fail((error) => {
            swal("Error", error.message, "error")
        })
    }

    initAddGroupListener() {
        $('#add-group').on('click', (event) => {
            let labelElement = $("#addGroupModal input[name='label']")
            let iconElement = $("#addGroupModal input[name='icon']")

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
            this.addGroup(label, icon)

            // Empty fields
            labelElement.val('')
            iconElement.val('')

            // Hide modal
            $('#addGroupModal').modal('hide')
        })
    }

    initAddRouteLinkListener() {
        $('#add-route-link').on('click', (event) => {
            let labelElement = $("#addRouteLinkModal input[name='label']")
            let iconElement = $("#addRouteLinkModal input[name='icon']")
            let moduleElement = $("#addRouteLinkModal input[name='module']")
            let routeElement = $("#addRouteLinkModal input[name='route']")

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
            this.addRouteLink(label, icon, moduleName, route)

            // Empty fields
            labelElement.val('')
            iconElement.val('')
            moduleElement.val('')
            routeElement.val('')

            // Hide modal
            $('#addRouteLinkModal').modal('hide')
        })
    }

    initAddLinkListener() {
        $('#add-link').on('click', (event) => {
            let labelElement = $("#addLinkModal input[name='label']")
            let iconElement = $("#addLinkModal input[name='icon']")
            let urlElement = $("#addLinkModal input[name='url']")

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
            this.addLink(label, icon, url)

            // Empty fields
            labelElement.val('')
            iconElement.val('')
            urlElement.val('')

            // Hide modal
            $('#addLinkModal').modal('hide')
        })
    }

    menuToJson() {
        this.menuStructure = JSON.stringify($('.menu-manager:visible').nestable('serialize'))
    }

    addGroup(label, icon) {
        let itemHtml = this.buildItem({
            type: 'group',
            label: label,
            icon: icon,
            color: 'green'
        })

        $('.menu-manager:visible .dd-list:first').append(itemHtml);
        this.menuToJson()
        this.save()
    }

    addRouteLink(label, icon, moduleName, route) {
        let itemHtml = this.buildItem({
            type: 'route',
            module: moduleName,
            label: label,
            icon: icon,
            color: 'red',
            route: route
        })

        $('.menu-manager:visible .dd-list:first').append(itemHtml);
        this.menuToJson()
        this.save()
    }

    addLink(label, icon, url) {
        let itemHtml = this.buildItem({
            type: 'link',
            label: label,
            icon: icon,
            color: 'blue',
            url: url
        })

        $('.menu-manager:visible .dd-list:first').append(itemHtml);
        this.menuToJson()
        this.save()
    }

    buildItem(item) {
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

        let html = `<li class="dd-item ${noChildrenClass}" ${dataModule} ${dataRoute} ${dataUrl} data-type="${item.type}" data-label="${item.label}" ${dataTranslation} data-icon="${item.icon}" data-nochildren="${item.noChildren ? true : false}" data-color="${item.color}">
            <div class="dd-handle">
                <i class="material-icons">${item.icon}</i>
                <span class="icon-label">${translation}</span>
                <span class="pull-right col-${item.color}">${_.capitalize(item.type)}</span>
            </div>`

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