export class UitypeCheckbox
{
    createdCell(columnData, td, cellData, rowData, row, col) {
        let value = cellData
        let color = 'red'

        if (value === uctrans('yes')) {
            color = 'green'
        }

        let html = `<div>
            <i class="material-icons col-${color}" style="font-size: 18px" title="${value}">lens</i>
            <span class="icon-label" style="top: -3px; left: 2px">${value}</span>
        </div>`

        $(td).html(html)

    }
}