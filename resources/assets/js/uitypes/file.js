export class UitypeFile
{
    createdCell(columnData, td, cellData, rowData, row, col) {
        let valueParts = cellData.split(';')

        if (valueParts.length === 2)
        {
            let fileName = valueParts[0]

            let domainSlug = $('meta[name="domain"]').attr('content')
            let moduleName = $('meta[name="module"]').attr('content')

            let url = laroute.route('uccello.download', {
                domain: domainSlug,
                module: moduleName,
                id: rowData.id,
                field: columnData.db_column
            })

            let html = `<a href="${url}" data-toggle="tooltip" title="${uctrans('button.download_file')}">${fileName}</a>`

            $(td).html(html)
        }
    }
}