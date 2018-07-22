export class UitypeEntity
{
    createdCell(columnData, td, cellData, rowData, row, col) {
        const domainSlug = $('meta[name="domain"]').attr('content')
        const moduleName = columnData.data.module;
        const recordId = rowData[columnData.db_column]

        let link = `<a href="/${domainSlug}/${moduleName}/detail?id=${recordId}">${cellData}</a>`;

        $(td).html(link)
    }
}