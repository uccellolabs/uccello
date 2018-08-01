import 'datatables.net-bs';
import 'datatables.net-buttons-bs';
import 'datatables.net-buttons/js/buttons.colVis';
import 'datatables.net-colreorder';
import 'datatables.net-fixedcolumns';
import 'datatables.net-responsive-bs';
import 'datatables.net-select';
import { sprintf } from 'sprintf-js'

export class Datatable {
    /**
     * Init Datatable configuration
     * @param {Element} selector
     */
    init(selector) {
        let table = $(selector).DataTable({
            dom: 'Blrtip',
            autoWidth: false, // Else the width is not refreshed on window resize
            responsive: true,
            colReorder: true,
            serverSide: true,
            ajax: {
                url: this.url,
                type: "POST"
            },
            columnDefs: this.getDatatableColumnDefs(),
            createdRow: (row, data, index) => {
                // Go to detail view when you click on a row
                $('td', row).click(() => {
                    document.location.href = sprintf(this.rowUrl, data.id);
                })
            },
            buttons: [
                {
                    extend: "colvis",
                    //columns: ':gt(0):lt(-1)'
                }
            ]
        });

        // Config buttons
        this.configButtons(table)
    }

    /**
     * Make datatable columns from filter.
     */
    getDatatableColumnDefs() {
        let selector = new UccelloUitypeSelector.UitypeSelector() // UccelloUitypeSelector is replaced automaticaly by webpack. See webpack.mix.js

        let datatableColumns = [];
        for (let i in this.columns) {
            let column = this.columns[i]
            datatableColumns.push({
                targets: parseInt(i), // Force integer
                data: column.name,
                createdCell: (td, cellData, rowData, row, col) => {
                    selector.get(column.uitype).createdCell(column, td, cellData, rowData, row, col)
                },
                visible: column.visible
            });
        }
        return datatableColumns;
    }

    /**
     * Config buttons to display them correctly
     * @param {Datatable} table
     */
    configButtons(table) {
        // Get buttons container
        var buttonsContainer = table.buttons().container()

        // Move buttons
        buttonsContainer.appendTo('#action-buttons');

        $('button', buttonsContainer).each((index, element) => {
            // Replace <span>...</span> by its content
            $(element).html($('span', element).html())

            // Add icon and effect
            $(element).addClass('icon-right waves-effect')
            $(element).append('<i class="material-icons">keyboard_arrow_down</i>')
        })

        // Move to the right
        $('#action-buttons .btn-group').addClass('pull-right')
    }
}