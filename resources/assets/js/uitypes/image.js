export class UitypeImage
{
    createdCell(columnData, td, cellData, rowData, row, col) {

        if (cellData)
        {
            let html = `<div class="align-center"><img src="${cellData}" style="object-fit: cover; border-radius: 50%; width: 40px; height: 40px"></div>`

            $(td).html(html)
        }
    }
}