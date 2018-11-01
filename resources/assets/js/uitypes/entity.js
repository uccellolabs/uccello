export class UitypeEntity
{
    createdCell(columnData, td, cellData, rowData, row, col) {
        const domainSlug = $('meta[name="domain"]').attr('content')
        const moduleName = columnData.data.module;
        const recordId = rowData[columnData.db_column]

        let route = laroute.route('uccello.detail', { id: recordId, domain: domainSlug, module: moduleName })

        let link = `<a href="${route}">${cellData}</a>`;

        $(td).html(link)
    }
}