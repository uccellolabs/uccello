import { Datatable } from './datatable'

export class Edit {
    constructor() {
        this.initListeners()
    }

    /**
     * Init listeners
     */
    initListeners() {
        this.initDeleteCurrentFileListener()
        this.initSaveAndNewListener()
        this.initEntityModalListener()
    }

    /**
     * Init "Delete" button on file uitype
     */
    initDeleteCurrentFileListener() {
        $('.current-file .delete-file a').on('click', (event) => {
            event.preventDefault();

            // Display file field
            $(event.currentTarget).parents('.form-group:first').find('.file-field').removeClass('hide')

            // Remove current file
            $(event.currentTarget).parents('.form-group:first').find('.delete-file-field').val(1)
            $(event.currentTarget).parents('.current-file:first').remove()
        })
    }

    /**
     * Init "Save and New" button listener
     */
    initSaveAndNewListener() {
        $('.btn-save-new').on('click', () => {
            // Set we want to create a new record after save
            $("input[name='save_new_hdn']").val(1);

            // Submit form
            $('form.edit-form').submit();
        })
    }

    /**
     * Show a selection modal and initialize datatable in it
     */
    initEntityModalListener() {
        $('.entity input[type="text"], .entity .input-group-addon a').on('click', (event) => {
            const element = event.currentTarget

            const fieldElement = $(element).parents('.entity:first')
            const field = $(fieldElement).data('field')
            // const relatedModule = $(`#${field}_module`, fieldElement).val()
            const modalTitle = $(fieldElement).data('modal-title')
            const modalIcon = $(fieldElement).data('modal-icon')
            const modalBody = $(`.selection-modal-content`, fieldElement).html()

            // Get modal
            const modal = $('#entityModal')

            // Change modal title
            $('.modal-title span', modal).text(modalTitle)

            // Change modal icon
            $('.modal-title i', modal).text(modalIcon)

            // Change modal body
            $('.modal-body', modal).html(modalBody)

            // Init datatable
            this.initDatatable($('.dataTable', modal), field)

            // Show modal
            modal.modal('show')
        })
    }

    /**
     * Initialise datatable for a specific element
     * @param {Element} element
     */
    initDatatable(element, field) {
        const csrfToken = $('meta[name="csrf-token"]').attr('content')
        const domainSlug = $('meta[name="domain"]').attr('content')
        const recordId = $('meta[name="record"]').attr('content')

        const relatedModuleName = $(element).data('related-module')
        const datatableUrl = $(element).data('url')
        const datatableColumns = $(element).data('columns') // Json automaticaly parsed

        let rowClickCallBack = (event, table, data) => {
            this.entityRowClickCallback(event, table, data, field)
        }

        let datatable = new Datatable()
        datatable.url = `${datatableUrl}?id=${recordId}&action=select&_token=${csrfToken}`
        datatable.domainSlug = domainSlug
        datatable.moduleName = relatedModuleName
        datatable.columns = datatableColumns
        datatable.rowUrl = 'javascript:void(0)' // No link
        datatable.rowClickCallback = rowClickCallBack
        datatable.init(element)
    }

    /**
     * Callback to call when a row is clicked in a datatable for a N-N related list
     * @param {Event} event
     * @param {Datatable} table
     * @param {object} data Selected row data
     */
    entityRowClickCallback(event, table, data, field) {
        const domainSlug = $('meta[name="domain"]').attr('content')
        const moduleName = $('meta[name="module"]').attr('content')

        $(`[name='${field}']`).val(data.id).change()
        $(`#${field}_display`).val(data.recordLabel)
        $(`#${field}_display`).parents('.form-line:first').addClass("focused")

        // Hide modal
        $('#entityModal').modal('hide')
    }
}