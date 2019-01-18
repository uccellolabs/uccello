import { Datatable } from './datatable'

export class List {
    constructor() {
        this.initDatatable()
        this.initListeners()
    }

    /**
     * Init Datatable
     */
    initDatatable() {
        const csrfToken = $('meta[name="csrf-token"]').attr('content')
        const domainSlug = $('meta[name="domain"]').attr('content')
        const moduleName = $('meta[name="module"]').attr('content')
        const datatableUrl = $('meta[name="datatable-url"]').attr('content')
        const datatableColumns = $('meta[name="datatable-columns"]').attr('content')

        let datatable = new Datatable()
        datatable.url = `${datatableUrl}?_token=${csrfToken}`
        datatable.domainSlug = domainSlug
        datatable.moduleName = moduleName
        datatable.columns = JSON.parse(datatableColumns)
        datatable.rowUrl = laroute.route('uccello.detail', { id: '%s', domain: domainSlug, module: moduleName })
        datatable.init('.dataTable')
    }

    /**
     * Init listeners
     */
    initListeners() {
        this.initSelectFilterListener()
        this.initSaveFilterListener()
    }

    /**
     * Select a filter
     */
    initSelectFilterListener() {
        const domainSlug = $('meta[name="domain"]').attr('content')
        const moduleName = $('meta[name="module"]').attr('content')

        $('select.filter').on('change', (event) => {
            let element = event.currentTarget

            // Get selected filter id
            let filterId = $(element).val()

            // Refresh the page with selected filter
            document.location.href = laroute.route('uccello.list', {domain: domainSlug, module: moduleName, filter: filterId})
        })
    }

    /**
     * Save a filter
     */
    initSaveFilterListener() {
        $('#addFilterModal button.save').on('click', (event) => {
            const table = $('.listview .dataTable').DataTable()

            // Save filter
            let data = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                name: $('#add_filter_filter_name').val(),
                type: 'list',
                save_order: $('#add_filter_save_order').is(':checked') ? 1 : 0,
                save_rows_number: $('#add_filter_save_rows_number').is(':checked') ? 1 : 0,
                columns: this.getVisibleColumns(table),
                order: table.order(),
                rows_number: parseInt($('button .records-number').text()),
                public: $('#add_filter_is_public').is(':checked') ? 1 : 0,
                default: $('#add_filter_is_default').is(':checked') ? 1 : 0,
            }

            // Save filter
            this.saveFilter(data)
        })
    }

    /**
     * Get datatable visible columns
     * @param {Datatable} table
     * @return {array}
     */
    getVisibleColumns(table) {
        const datatableColumns = JSON.parse($('meta[name="datatable-columns"]').attr('content'))

        let visibleColumns = []
        table.columns().every((index) => {
            if (index > 0 && index < table.columns().visible().length - 1) { // Ignore firt and last column
                if (table.column(index).visible()) {
                    visibleColumns.push(datatableColumns[index-1].name) // The first column is not in datatableColumns, so we use -1
                }
            }
        })

        return visibleColumns
    }

    /**
     * Save filter into database
     * @param {object} data
     */
    saveFilter(data) {
        const domainSlug = $('meta[name="domain"]').attr('content')
        const moduleName = $('meta[name="module"]').attr('content')

        const url = laroute.route('uccello.list.filter.save', {domain: domainSlug, module: moduleName})
        $.ajax({
                url: url,
                method: 'post',
                data: data,
                contentType: "application/x-www-form-urlencoded"
            })
            .then((response) => {
                $('#addFilterModal').modal('hide')

                // TODO: Add and select filter in the list
            })
            .fail((error) => {
                swal('Error', error.message, 'error') //TODO: Translate
            })
    }
}