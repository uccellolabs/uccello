import { Datatable } from './datatable'

export class Detail {
    constructor() {
        this.initRelatedLists()
        this.initRelatedListSelectionModalListener()
    }

    /**
     * Initalize datatable for all related lists
     */
    initRelatedLists() {
        $('.relatedlist .dataTable').each((index, element) => {
            this.initDatatable(element)
        })
    }

    /**
     * Show a selection modal and initialize datatable in it
     */
    initRelatedListSelectionModalListener() {
        $('.btn-relatedlist-select').on('click', (event) => {
            const element = event.currentTarget

            const relatedListId = $(element).data('relatedlist')
            const modalTitle = $(element).data('modal-title')
            const modalIcon = $(element).data('modal-icon')
            const modalBody = $(`.selection-modal-content[data-relatedlist='${relatedListId}']`).html()

            // Get modal
            const modal = $('#relatedListSelectionModal')

            // Change modal title
            $('.modal-title span', modal).text(modalTitle)

            // Change modal icon
            $('.modal-title i', modal).text(modalIcon)

            // Change modal body
            $('.modal-body', modal).html(modalBody)

            // Init datatable
            this.initDatatable($('.dataTable', modal), true)

            // Show modal
            modal.modal('show')
        })
    }

    /**
     * Initialise datatable for a specific element
     * @param {Element} element
     * @param {boolean} forSelection
     */
    initDatatable(element, forSelection) {
        const csrfToken = $('meta[name="csrf-token"]').attr('content')
        const domainSlug = $('meta[name="domain"]').attr('content')
        const recordId = $('meta[name="record"]').attr('content')

        const relatedListId = $(element).data('relatedlist')
        const relatedModuleName = $(element).data('related-module')
        const datatableUrl = $(element).data('url')
        const datatableColumns = $(element).data('columns') // Json automaticaly parsed

        let rowUrl = ''
        let url = `${datatableUrl}?id=${recordId}&relatedlist=${relatedListId}`

        // Specify if it is for selection only
        if (forSelection === true) {
            url += '&action=select'
            rowUrl = 'javascript:void(0)' // No link
        } else {
            rowUrl = laroute.route('uccello.detail', { id: '%s', domain: domainSlug, module: relatedModuleName })
        }

        let datatable = new Datatable()
        datatable.url = `${url}&_token=${csrfToken}`
        datatable.domainSlug = domainSlug
        datatable.moduleName = relatedModuleName
        datatable.columns = datatableColumns
        datatable.rowUrl = rowUrl
        datatable.init(element)
    }
}