export class UitypeText
{
    createdCell(columnData, td, cellData, rowData, row, col) {
        if(typeof cellData === 'object') {
            $(td).html(cellData[columnData.name])
        }
    }
}