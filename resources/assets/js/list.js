require('datatables.net-bs');
require('datatables.net-buttons-bs');
require('datatables.net-colreorder');
require('datatables.net-fixedcolumns');
require('datatables.net-responsive-bs');
require('datatables.net-select');

$(function () {

    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    var domainSlug = $('meta[name="domain"]').attr('content');
    var moduleName = $('meta[name="module"]').attr('content');
    var datatableUrl = $('meta[name="datatable-url"]').attr('content');

    $('.dataTable').DataTable({
        dom: 'lrtip',
        autoWidth: false, // Else the width is not refreshed on window resize
        responsive: true,
        colReorder: true,
        serverSide: true,
        ajax: {
            url: `${datatableUrl}?datatable=1&_token=${csrfToken}`,
            type: "POST"
        },
        columns: getDatatableColumns(),
        createdRow: function ( row, data, index ) {
            // Go to detail view when you click on a row
            $('td', row).click(function() {
                document.location.href = `/${domainSlug}/${moduleName}/detail?id=${data.id}`;
            })
        }
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