import {Datatable} from './datatable'

export class List {
    constructor() {
        this.initDatatable()
        this.initListeners()
    }

    /**
     * Init datatable
     */
    initDatatable() {
        if ($('table[data-filter-type="list"]').length == 0) {
            return
        }

        this.datatable = new Datatable()
        this.datatable.init($('table[data-filter-type="list"]'))
        this.datatable.makeQuery()
    }


    /**
     * Init listeners
     */
    initListeners() {
        this.initSaveFilterListener()
        this.initDeleteFilterListener()
        this.initExportListener()
    }

    /**
     * Save a filter
     */
    initSaveFilterListener() {
        $('#addFilterModal a.save').on('click', (event) => {

            let filterName = $('#add_filter_filter_name').val()

            // Mandatory field
            if (filterName === '') {
                $('#add_filter_filter_name').parent('.form-line').addClass('focused error')
                return
            }

            if ($(`ul#filters-list a[data-name='${filterName}']`).length > 0) {

                swal({
                    title: uctrans.trans('uccello::default.filter.exists.title'),
                    text: uctrans.trans('uccello::default.filter.exists.message'),
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    buttons: [
                        uctrans.trans('uccello::default.button.no'),
                        uctrans.trans('uccello::default.button.yes')
                    ],
                })
                .then((response) => {
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

    /**
     * Delete a filter
     */
    initDeleteFilterListener() {
        const table = $('table[data-filter-type="list"]')

        $('a.delete-filter').on('click', () => {
            let selectedFilterId = $(table).attr('data-filter-id')

            if(selectedFilterId) {
                swal({
                    title: uctrans.trans('uccello::default.dialog.confirm.title'),
                    text: uctrans.trans('uccello::default.filter.delete.message'),
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    buttons: [
                        uctrans.trans('uccello::default.button.no'),
                        uctrans.trans('uccello::default.button.yes')
                    ],
                })
                .then((response) => {
                    if (response === true) {
                        this.deleteFilter(selectedFilterId)
                    }
                })
            }
        })
    }

    /**
     * Export records
     */
    initExportListener() {
        $('#exportModal .export').on('click', (event) => {
            const table = $('table[data-filter-type="list"]')
            const modal = $('#exportModal')

            // Export config
            let data = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                extension: $('#export_format', modal).val(),
                columns: this.getVisibleColumns(table),
                conditions: this.getSearchConditions(table),
                order: $(table).attr('data-order') ? JSON.parse($(table).attr('data-order')) : null,
                with_hidden_columns: $('#with_hidden_columns', modal).is(':checked') ? 1 : 0,
                with_id: $('#export_with_id', modal).is(':checked') ? 1 : 0,
                with_conditions: $('#export_keep_conditions', modal).is(':checked') ? 1 : 0,
                with_order: $('#export_keep_order', modal).is(':checked') ? 1 : 0,
                with_timestamps: $('#export_with_timestamps', modal).is(':checked') ? 1 : 0,
            }

            // URL
            const url = table.data('export-url')

            // Make a fake form to be able to download the file
            let fakeFormHtml = this.getFakeFormHtml(data, url)

            // Add the fake form into the page
            let fakeFormDom = $(fakeFormHtml);
            $("body").append(fakeFormDom);

            // Submit fake form to download the file
            fakeFormDom.submit();

            // Remove the fake form from the page
            fakeFormDom.remove()
        })
    }

    /**
     * Get datatable visible columns
     * @param {Datatable} table
     * @return {array}
     */
    getVisibleColumns(table) {
        let visibleColumns = []

        $('th[data-field]', table).each(function() {
            let fieldName = $(this).data('field')

            if ($(this).css('display') !== 'none') {
                visibleColumns.push(fieldName)
            }
        })

        return visibleColumns
    }

    /**
     * Get search conditions
     * @param {Datatable} table
     * @return {Object}
     */
    getSearchConditions(table) {
        let conditions = {}

        $('th[data-column]', table).each((index, el) => {
            let fieldName = $(el).data('field')
            if (this.datatable.columns[fieldName].search) {
                conditions[fieldName] = this.datatable.columns[fieldName].search
            }
        })

        return conditions
    }

    /**
     * Get order with field colunm instead of column index
     * @param {Datatable} table
     * @return {Object}
     */
    getOrderWithFieldColumn(table) {
        const datatableColumns = this.datatable.columns

        let order = {}
        for (let sortOrder of table.order()) {
            let index = sortOrder[0]
            order[datatableColumns[index-1].db_column] = sortOrder[1]
        }

        return order
    }

    /**
     * Save filter into database
     */
    saveFilter() {
        const table = $('table[data-filter-type="list"]')
        const modal = $('#addFilterModal')

        // Save filter
        let data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            name: $('#add_filter_filter_name', modal).val(),
            type: 'list',
            save_order: $('#add_filter_save_order', modal).is(':checked') ? 1 : 0,
            save_page_length: $('#add_filter_save_page_length', modal).is(':checked') ? 1 : 0,
            columns: this.getVisibleColumns(table),
            order: $(table).attr('data-order') ? JSON.parse($(table).attr('data-order')) : null,
            page_length: $(table).attr('data-length'),
            public: $('#add_filter_is_public', modal).is(':checked') ? 1 : 0,
            default: $('#add_filter_is_default', modal).is(':checked') ? 1 : 0,
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

        $.ajax({
                url: table.data('save-filter-url'),
                method: 'post',
                data: data,
                contentType: "application/x-www-form-urlencoded"
            })
            .then((response) => {
                let filterToAdd = {
                    id: response.id,
                    name: response.name
                }

                // Set filter name into the list
                $('a[data-target="filters-list"] span').text(filterToAdd.name)

                // Set current filter id
                $(table).attr('data-filter-id', filterToAdd.id)

                // Remove disabled on delete button
                $('a.delete-filter').parents('li:first').show()
            })
            .fail((error) => {
                swal(uctrans.trans('uccello::default.dialog.error.title'), error.message, 'error')
            })
    }

    /**
     * Retrieve a filter by its id and delete it
     * @param {integer} id
     */
    deleteFilter(id) {
        const table = $('table[data-filter-type="list"]')

        let data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id: id
        }

        let url = $(table).data('delete-filter-url')

        $.post(url, data)
            .then((response) => {
                if (response.success) {
                    // Refresh list without filter
                    document.location.href = $(table).data('list-url')
                } else {
                    swal(uctrans.trans('uccello::default.dialog.error.title'), response.message, 'error')
                }
            })
            .fail((error) => {
                swal(uctrans.trans('uccello::default.dialog.error.title'), error.message, 'error')
            })
    }

    /**
     * Make a fake form with data as hidden inputs
     *
     * @param {Object} data
     * @param {String} url
     */
    getFakeFormHtml(data, url) {
        let form = "<form style='display: none;' method='POST' action='"+url+"'>";

        _.each(data, function(postValue, postKey){
            // Convert into JSON if it is a complex data
            var escapedValue = typeof postValue === 'object' ? JSON.stringify(postValue) : postValue;

            // Escape string (not for numbers)
            if (typeof escapedValue === 'string') {
                escapedValue = escapedValue.replace("\\", "\\\\").replace("'", "\'")
            }

            // Add data to the fake form
            form += "<input type='hidden' name='"+postKey+"' value='"+escapedValue+"'>";
        });

        form += "</form>";

        return form
    }
}