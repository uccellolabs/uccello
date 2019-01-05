import 'datatables.net-bs';
import 'datatables.net-buttons-bs';
import 'datatables.net-buttons/js/buttons.colVis';
// import 'datatables.net-colreorder';
import 'datatables.net-fixedcolumns';
import 'datatables.net-responsive-bs';
import 'datatables.net-select';
import { sprintf } from 'sprintf-js'
import { Link } from './link'

export class Datatable {
    /**
     * Init Datatable configuration
     * @param {Element} element
     */
    init(element) {
        let linkManager = new Link(false)

        let table = $(element).DataTable({
            dom: 'Brtp',
            autoWidth: false, // Else the width is not refreshed on window resize
            responsive: true,
            colReorder: true,
            serverSide: true,
            ajax: {
                url: this.url,
                type: "POST"
            },
            pageLength: 15, // Default length
            order: [[1, 'asc']],
            columnDefs: this.getDatatableColumnDefs(element),
            createdRow: (row, data, index) => {
                // Go to detail view when you click on a row
                $('td:gt(0):lt(-1)', row).click(() => {
                    document.location.href = sprintf(this.rowUrl, data.id);
                })

                // Init click listener on delete button
                linkManager.initClickListener(row)
            },
            buttons: [
                {
                    extend: 'colvis',
                    columns: ':gt(0):lt(-1)'
                }
            ],
            language: {
                paginate: {
                    previous: '<',
                    next: '>'
                }
            }
        });

        // Config buttons
        this.configButtons(table)

        // Init search
        this.initDatatableColumnSearch(table)
    }

    /**
     * Make datatable columns from filter.
     * @param {Element} element
     */
    getDatatableColumnDefs(element) {
        let selector = new UccelloUitypeSelector.UitypeSelector() // UccelloUitypeSelector is replaced automaticaly by webpack. See webpack.mix.js

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
                createdCell: (td, cellData, rowData, row, col) => {
                    selector.get(column.uitype).createdCell(column, td, cellData, rowData, row, col)
                },
                visible: column.visible
            });
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
     * @param {Datatable} table
     */
    configButtons(table) {
        // Get buttons container
        var buttonsContainer = table.buttons().container()

        // Retrieve container
        var dataTableContainer = buttonsContainer.parents('.dataTable-container:first');

        // Remove old buttons if datatable was initialized before (e.g. in related list selection modal)
        $('.action-buttons .buttons-colvis', dataTableContainer).remove()

        // Display mini buttons (related lists)
        if (dataTableContainer.data('button-size') === 'mini') {

            $('button', buttonsContainer).each((index, element) => {
                // Get content and use it as a title
                var title = $('span', element).html()

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
                $(element).prependTo($('.action-buttons', dataTableContainer))
            })
        }
        // Display classic buttons (list)
        else {
            // Move buttons
            buttonsContainer.appendTo($('.action-buttons', dataTableContainer));

            $('button', buttonsContainer).each((index, element) => {
                // Replace <span>...</span> by its content
                $(element).html($('span', element).html())

                // Add icon and effect
                $(element).addClass('icon-right waves-effect bg-primary')
                $(element).removeClass('btn-default')
                $(element).append('<i class="material-icons">keyboard_arrow_down</i>')
            })

            // Move to the right
            $('.action-buttons .btn-group', dataTableContainer).addClass('pull-right')
        }

        // Change records number
        $('ul#items-number a').on('click', (event) => {
            let recordsNumber = $(event.target).data('number')
            $('strong.records-number').text(recordsNumber)
            table.page.len(recordsNumber).draw();
        })

        $(".dataTables_paginate", dataTableContainer).appendTo($('.paginator', dataTableContainer))
    }

    /**
     * Config column search.
     * @param {Datatable} table
     */
    initDatatableColumnSearch(table)
    {
        let timer = 0

        // Config each column
        table.columns().every(function (index) {
            let column = table.column(index)

            // Event listener to launch search
            $('input, select', this.header()).on('keyup change', function() {
                let value = $(this).val()

                if (value !== '') {
                    $('.clear-search').show()
                }

                if (column.search() !== value) {
                    clearTimeout(timer)
                    timer = setTimeout(() => {
                        column.search(value)
                        table.draw()
                    }, 500)
                }
            })
        })

        // Add clear search button listener
        this.addClearSearchButtonListener(table)
    }

    /**
     * Clear datatable search
     * @param {Datatable} table
     */
    addClearSearchButtonListener(table)
    {
        $('.actions-column .clear-search').on('click', (event) => {
            // Clear all search fields
            $('.dataTable thead input, .dataTable thead select').val(null).change()

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