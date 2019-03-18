import 'datatables.net-bs4'
import 'datatables.net-buttons-bs4'
import 'datatables.net-buttons/js/buttons.colVis'
// import 'datatables.net-colreorder'
import 'datatables.net-fixedcolumns-bs4'
import 'datatables.net-responsive-bs4'
import 'datatables.net-select-bs4'
import { sprintf } from 'sprintf-js'
import { Link } from './link'

export class Datatable {
    /**
     * Init Datatable configuration
     * @param {Element} element
     */
    async init(element) {
        let linkManager = new Link(false)

        $(element).hide()

        await this.getDatatableConfig(element)

        this.table = $(element).DataTable({
            dom: 'Brtp',
            autoWidth: false, // Else the width is not refreshed on window resize
            responsive: true,
            colReorder: false,
            serverSide: true,
            ajax: {
                url: this.url,
                type: "POST"
            },
            pageLength: this.getInitialPageLength(),
            order: this.getInitialOrder(),
            columnDefs: this.getDatatableColumnDefs(element),
            createdRow: (row, data, index) => {
                // Go to detail view when you click on a row
                $('td:gt(0):lt(-1)', row).click(() => {
                    document.location.href = sprintf(this.rowUrl, data.id);
                })

                // Init click listener on delete button
                linkManager.initClickListener(row)

                // Init click listener on the row if a callback is defined
                if (typeof this.rowClickCallback !== 'undefined') {
                    $(row).on('click', (event) => {
                        this.rowClickCallback(event, this.table, data)
                    })
                }
            },
            buttons: [
                {
                    extend: 'colvis',
                    columns: ':gt(0):lt(-1)',
                    columnText: ( dt, idx, title ) => {
                        let column = this.columns[idx-1]
                        return $(`th[data-name="${column.name}"]`).data('label')
                    }
                }
            ],
            language: {
                paginate: {
                    previous: '<',
                    next: '>'
                },
                buttons: {
                    colvis: uctrans('button.columns')
                }
            },
            aoSearchCols: this.getInitialSearch(),

        });

        // Config buttons
        this.configButtons()

        // Init search
        this.initDatatableColumnSearch()

        // Hidde loader
        $(element).parents('.table-responsive:first').find('.loader').hide()

        // Show datatable
        $(element).show()
    }

    /**
     * Async method to get Datatable configuration: columns and selected filter definition.
     * @param {Element} element
     */
    async getDatatableConfig(element) {
        let filterId = $(element).data('filter-id')
        if (typeof filterId === 'undefined') {
            filterId = ''
        }

        let url = laroute.route('uccello.datatable.config', {
            domain: this.domainSlug,
            module: this.moduleName,
            filter: filterId,
            filter_type: $(element).data('filter-type')
        })

        let response = await $.get(url)

        this.columns = response.columns
        this.selectedFilter = response.filter
    }

    /**
     * Make datatable columns from filter.
     * @param {Element} element
     * @return {array}
     */
    getDatatableColumnDefs(element) {
        // let selector = new UccelloUitypeSelector.UitypeSelector() // UccelloUitypeSelector is replaced automaticaly by webpack. See webpack.mix.js

        let datatableColumns = [];

        // Add first column
        datatableColumns.push({
            targets: 0,
            data: null,
            defaultContent: '',
            orderable: false,
            searchable: false
        })

        // Add all filter columns
        for (let i in this.columns) {
            let column = this.columns[i]
            datatableColumns.push({
                targets: parseInt(i) + 1, // Force integer
                data: column.name,
                orderable: true,
                // createdCell: (td, cellData, rowData, row, col) => {
                //     selector.get(column.uitype).createdCell(column, td, cellData, rowData, row, col)
                // },
                visible: column.visible
            })
        }

        // Add last column (action buttons)
        datatableColumns.push({
            targets: this.columns.length + 1,
            data: null,
            defaultContent: '',
            orderable: false,
            searchable: false,
            createdCell: this.getActionsColumnCreatedCell(element)
        })

        return datatableColumns;
    }

    /**
     * Initialize initial columns search, according to selected filter conditions
     * @return {array}
     */
    getInitialSearch() {
        let search = []

        if (this.selectedFilter && this.selectedFilter.conditions && this.selectedFilter.conditions.search) {
            // First column
            search.push(null)

            for (let i in this.columns) {
                let value = null

                let column =  this.columns[i]
                value = typeof this.selectedFilter.conditions.search[column.name] !== 'undefined' ? this.selectedFilter.conditions.search[column.name] : null

                if (value) {
                    search.push({sSearch: value})
                } else {
                    search.push(null)
                }
            }

            // Last column
            search.push(null)
        }

        return search
    }

    /**
     * Get initial order
     * @return {array}
     */
    getInitialOrder() {
        let order = [[1, 'asc']] // Default
        if (this.selectedFilter && this.selectedFilter.data && this.selectedFilter.data.order) {
            order = this.selectedFilter.data.order
        }

        return order
    }

    /**
     * Get initial page length
     * @return {integer}
     */
    getInitialPageLength() {
        let length = 15 // Default
        if (this.selectedFilter && this.selectedFilter.data && this.selectedFilter.data.length) {
            length = this.selectedFilter.data.length
        }

        return length
    }

    /**
     * Make datatable action column.
     * @param {Element} element
     */
    getActionsColumnCreatedCell(element) {
        return (td, cellData, rowData, row, col) => {
            var dataTableContainer = $(element).parents('.dataTable-container:first');

            // Copy buttons from template
            let editButton = $(".template .edit-btn", dataTableContainer).clone().tooltip().appendTo($(td))
            let deleteButton = $(".template .delete-btn", dataTableContainer).clone().tooltip().appendTo($(td))

            // Config edit link url
            if (editButton.attr('href')) {
                let editLink = editButton.attr('href').replace('RECORD_ID', rowData.id)
                editButton.attr('href', editLink)
            }

            // Config delete link url
            if (deleteButton.attr('href')) {
                let deleteLink = deleteButton.attr('href').replace('RECORD_ID', rowData.id)

                // if relation_id is defined, replace RELATION_ID
                if (rowData.relation_id) {
                    deleteLink = deleteLink.replace('RELATION_ID', rowData.relation_id)
                }

                deleteButton.attr('href', deleteLink)
            }
        }
    }

    /**
     * Config buttons to display them correctly
     */
    configButtons() {
        let table = this.table

        // Get buttons container
        let buttonsContainer = table.buttons().container()

        // Retrieve container
        let dataTableContainer = buttonsContainer.parents('.dataTable-container:first')

        // Remove old buttons if datatable was initialized before (e.g. in related list selection modal)
        $('.action-buttons .buttons-colvis', dataTableContainer).remove()

        // Display mini buttons (related lists)
        if (dataTableContainer.data('button-size') === 'mini') {

            $('button', buttonsContainer).each((index, element) => {
                // Get content and use it as a title
                let title = $('span', element).html()

                // Add icon and effect
                $(element)
                    .addClass('btn-circle waves-effect waves-circle waves-float bg-primary')
                    .removeClass('btn-default')
                    .html('<i class="material-icons">view_column</i>')
                    .attr('title', title)
                    .tooltip({
                        placement: 'top'
                    })

                // Move button
                $(element).prependTo($('.action-buttons'))
            })
        }
        // Display classic buttons (list)
        else {
            // Move buttons
            $('button.buttons-colvis', buttonsContainer)
                .attr('class', 'btn-small waves-effect primary buttons-colvis')
                .appendTo($('.action-buttons'))

            // $(buttonsContainer).remove()

            // $('button', buttonsContainer).each((index, element) => {
            //     // Replace <span>...</span> by its content
            //     $(element).html($('span', element).html())

            //     // Add icon and effect
            //     $(element).addClass('icon-right waves-effect bg-primary')
            //     $(element).removeClass('btn-default')
            //     $(element).append('<i class="material-icons">keyboard_arrow_down</i>')
            // })

            // Move to the right
            $('.action-buttons .btn-group', dataTableContainer).addClass('pull-right')
        }

        // Change records number
        $('ul#items-number a').on('click', (event) => {
            let recordsNumber = $(event.target).data('number')
            $('strong.records-number').text(recordsNumber)
            table.page.len(recordsNumber).draw()
        })

        $(".dataTables_paginate", dataTableContainer).appendTo($('.paginator', dataTableContainer))
    }

    /**
     * Config column search.
     */
    initDatatableColumnSearch()
    {
        let table = this.table
        this.timer = 0
        let that = this

        // Config each column
        table.columns().every(function (index) {
            let column = table.column(index)

            // Event listener to launch search
            $('input', this.header()).on('keyup apply.daterangepicker cancel.daterangepicker', function() {
                that.launchSearch(column, $(this).val())
            })

            $('select', this.header()).on('change', function() {
                that.launchSearch(column, $(this).val())
            })
        })

        // Add clear search button listener
        this.addClearSearchButtonListener()
    }

    /**
     * Launch search
     * @param {Object} column
     * @param {String} q
     */
    launchSearch(column, q)
    {
        if (q !== '') {
            $('.clear-search').show()
        }

        if (column.search() !== q) {
            clearTimeout(this.timer)
            this.timer = setTimeout(() => {
                column.search(q)
                this.table.draw()
            }, 500)
        }
    }

    /**
     * Clear datatable search
     */
    addClearSearchButtonListener()
    {
        let table = this.table

        $('.actions-column .clear-search').on('click', (event) => {
            // Clear all search fields
            $('.dataTable thead select').selectpicker('deselectAll')
            $('.dataTable thead input').val('')

            // Update columns
            table.columns().every(function (index) {
                let column = table.column(index)
                column.search('')
            })

            // Disable clear search button
            $(event.currentTarget).hide()

            // Update data
            table.draw()
        })
    }
}