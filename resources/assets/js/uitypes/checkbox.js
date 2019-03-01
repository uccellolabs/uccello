export class UitypeCheckbox
{
    createdCell(columnData, td, cellData, rowData, row, col) {
        let value = cellData
        let color = 'red'

        if (value === uctrans('yes')) {
            color = 'green'
        }

        let html = `<div class="align-center">
            <i class="material-icons col-${color}" style="font-size: 18px" title="${value}">lens</i>
        </div>`

        $(td).html(html)

    }
}