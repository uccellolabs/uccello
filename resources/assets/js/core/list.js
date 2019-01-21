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
        const selectedFilter = $('meta[name="selected-filter"]').attr('content')

        let datatable = new Datatable()
        datatable.url = `${datatableUrl}?_token=${csrfToken}`
        datatable.domainSlug = domainSlug
        datatable.moduleName = moduleName
        datatable.columns = JSON.parse(datatableColumns)
        datatable.selectedFilter = JSON.parse(selectedFilter)
        datatable.rowUrl = laroute.route('uccello.detail', { id: '%s', domain: domainSlug, module: moduleName })
        datatable.init('.dataTable')
    }

    /**
     * Init listeners
     */
    initListeners() {
        this.initSelectFilterListener()
        this.initSaveFilterListener()
        this.initDeleteFilterListener()
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

            let filterName = $('#add_filter_filter_name').val()

            // Mandatory field
            if (filterName === '') {
                $('#add_filter_filter_name').parent('.form-line').addClass('focused error')
                return
            }

            if ($(`select.filter option:contains(${filterName})`).length > 0) {

                swal({
                    title: 'A filter already exists with the same name', //TODO: translate
                    text: 'Do you want to update it?', //TODO: translate
                    type: "warning",
                    showCancelButton: true,
                    closeOnConfirm: true,
                    confirmButtonColor: '#DD6B55'
                },
                (response) => {
                    if (response === true) {
                        // Save filter
                        this.saveFilter()
                    }
                })
            } else {
                // Save filter
                this.saveFilter()
            }
        })
    }

    initDeleteFilterListener() {
        $('button.delete-filter').on('click', () => {
            let selectedFilterId = $('select.filter').val()

            if(selectedFilterId) {
                swal({
                    title: 'Are you sure?', //TODO: translate
                    text: 'Do you want to delete this filter?', //TODO: translate
                    type: "warning",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true,
                    confirmButtonColor: '#DD6B55'
                },
                (response) => {
                    if (response === true) {
                        this.deleteFilter(selectedFilterId)
                    }
                })
            }
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

    getSearchConditions(table) {
        const datatableColumns = JSON.parse($('meta[name="datatable-columns"]').attr('content'))

        let conditions = {}
        table.columns().every((index) => {
            if (index > 0 && index < table.columns().visible().length - 1) { // Ignore firt and last column
                if (table.column(index).search()) {
                    conditions[datatableColumns[index-1].name] = table.column(index).search()
                }
            }
        })

        return conditions
    }

    /**
     * Save filter into database
     */
    saveFilter() {
        const domainSlug = $('meta[name="domain"]').attr('content')
        const moduleName = $('meta[name="module"]').attr('content')
        const table = $('.listview .dataTable').DataTable()

        // Save filter
        let data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            name: $('#add_filter_filter_name').val(),
            type: 'list',
            save_order: $('#add_filter_save_order').is(':checked') ? 1 : 0,
            save_page_length: $('#add_filter_save_page_length').is(':checked') ? 1 : 0,
            columns: this.getVisibleColumns(table),
            order: table.order(),
            page_length: parseInt($('button .records-number').text()),
            public: $('#add_filter_is_public').is(':checked') ? 1 : 0,
            default: $('#add_filter_is_default').is(':checked') ? 1 : 0,
        }

        // Add search conditions if defined
        let searchConditions = this.getSearchConditions(table)
        if (searchConditions !== {}) {
            data['conditions'] = {
                search: searchConditions
            }
        } else {
            data['conditions'] = null
        }

        const url = laroute.route('uccello.list.filter.save', {domain: domainSlug, module: moduleName})
        $.ajax({
                url: url,
                method: 'post',
                data: data,
                contentType: "application/x-www-form-urlencoded"
            })
            .then((response) => {
                // Hide modal
                $('#addFilterModal').modal('hide')

                let filterToAdd = {
                    id: response.id,
                    name: response.name
                }

                // Add option if necessary
                if ($(`select.filter option[value='${filterToAdd.id}']`).length === 0) {
                    $('select.filter').append(`<option value="${filterToAdd.id}">${filterToAdd.name}</option>`)
                }

                // Select option
                $('select.filter').val(filterToAdd.id).selectpicker('refresh')

                // Remove disabled on delete button
                $('button.delete-filter').removeAttr('disabled')
            })
            .fail((error) => {
                swal('Error', error.message, 'error') //TODO: Translate
            })
    }

    /**
     * Retrieve a filter by its id and delete it
     * @param {integer} id
     */
    deleteFilter(id) {
        const domainSlug = $('meta[name="domain"]').attr('content')
        const moduleName = $('meta[name="module"]').attr('content')

        let url = laroute.route('uccello.list.filter.delete', { domain: domainSlug, module: moduleName, id: id })
        $.get(url)
            .then((response) => {
                if (response.success) {
                    // Refresh list without filter
                    document.location.href = laroute.route('uccello.list', { domain: domainSlug, module: moduleName })
                } else {
                    swal('Error', response.message, 'error') //TODO: Translate
                }
            })
            .fail((error) => {
                swal('Error', error.message, 'error') //TODO: Translate
            })
    }
}