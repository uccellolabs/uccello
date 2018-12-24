const _ = require('lodash');

export class MenuManager {
    constructor() {
        this.initMenu()
        this.initListeners()
    }

    initMenu() {

        let menuStructure = JSON.parse($("meta[name='menu-structure']").attr('content'))

        if (typeof menuStructure === 'object') {
            let menuHtml = ''
            for(let item of menuStructure) {
                menuHtml += this.buildItem(item)
            }

            $('.menu-manager ol.dd-list:first').html(menuHtml)
        }

        $('.menu-manager').nestable({
            maxDepth: 3
        })

        // For initialization
        this.menuToJson()

        $('.menu-manager').on('change', (event) => {
            // Save change
            this.menuToJson()
        });
    }

    initListeners() {
        this.initSaveListener()
        this.initAddGroupListener()
        this.initAddRouteLinkListener()
        this.initAddLinkListener()
    }

    initSaveListener() {
        $('a.save-menu').on('click', (event) => {
            event.preventDefault()

            let url = $(event.currentTarget).attr('href')

            $.ajax({
                url: url,
                method: "post",
                data: {
                    _token: $("meta[name='csrf-token']").attr('content'),
                    structure: this.menuStructure
                }
            }).then((response) => {
                swal("Success", response.message, "success")
            }).fail((error) => {
                swal("Error", error.message, "error")
            })
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
        this.menuStructure = JSON.stringify($('.menu-manager').nestable('serialize'))
    }

    addGroup(label, icon) {
        let itemHtml = this.buildItem({
            type: 'group',
            label: label,
            icon: icon,
            color: 'green'
        })

        $('.menu-manager .dd-list:first').append(itemHtml);
        this.menuToJson()
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

        $('.menu-manager .dd-list:first').append(itemHtml);
        this.menuToJson()
    }

    addLink(label, icon, url) {
        let itemHtml = this.buildItem({
            type: 'link',
            label: label,
            icon: icon,
            color: 'blue',
            url: url
        })

        $('.menu-manager .dd-list:first').append(itemHtml);
        this.menuToJson()
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

        let html = `<li class="dd-item ${noChildrenClass}" ${dataModule} ${dataRoute} ${dataUrl} data-type="${item.type}" data-label="${item.label}" data-icon="${item.icon}" data-nochildren="${item.noChildren ? true : false}" data-color="${item.color}">
            <div class="dd-handle">
                <i class="material-icons">${item.icon}</i>
                <span class="icon-label">${item.label}</span>
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