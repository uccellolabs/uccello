require('datatables.net-bs');
require('datatables.net-buttons-bs');
require('datatables.net-colreorder');
require('datatables.net-fixedcolumns');
require('datatables.net-responsive-bs');
require('datatables.net-select');

$(function () {

    $('.dataTable').DataTable({
        dom: 'lrtip',
        responsive: true,
        colReorder: true,
        serverSide: true,
        ajax: {
            url: `/api/${domainSlug}/${moduleName}/list?datatable=1&_token=${csrfToken}`,
            type: "POST"
        },
        columns: getDatatableColumns()
    });

    /**
     * Make datatable columns from filter.
     * filterColumns variable is defined in the blade view.
     */
    function getDatatableColumns() {
        var datatableColumns = [];
        for (var column of filterColumns) {
            datatableColumns.push({data: column});
        }

        return datatableColumns;
    }
});