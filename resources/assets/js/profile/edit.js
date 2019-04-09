export class Edit {
    constructor() {
        this.intiSwitchListener()
        this.initCheckboxListener()
        this.autoCheck()
        this.autoDisplayApiCapabilities()
    }

    intiSwitchListener() {
        $('#manage-api-capabilities').on('change', (event) => {
            let element = $(event.currentTarget)

            if (element.is(':checked')) {
                $("#permissions-table .for-api").removeClass('hide')
            } else {
                $("#permissions-table .for-api").addClass('hide')
            }
        })
    }

    initCheckboxListener() {
        // Select all checkbox
        $('#permissions-table input.select-all').on('change', (event) => {
            let element = $(event.currentTarget)

            if (element.is(':checked')) {
                $("#permissions-table input[type='checkbox']").prop('checked', true)
            } else {
                $("#permissions-table input[type='checkbox']").prop('checked', false)
            }

            this.autoCheck()
        })

        // Select row checkboxes
        $('#permissions-table input.select-row').on('change', (event) => {
            let element = $(event.currentTarget)
            let parentElement = $(element).parents('tr:first')

            if (element.is(':checked')) {
                $(".select-item", parentElement).prop('checked', true)
            } else {
                $(".select-item", parentElement).prop('checked', false)
            }

            this.autoCheck()
        })

        // Select item checkboxes
        $('#permissions-table input.select-item').on('change', (event) => {
            let element = $(event.currentTarget)
            let parentElement = $(element).parents('tr:first')

            this.autoCheck()
        })
    }

    autoCheck() {
        // All modules
        let globalCheckedCount = $("#permissions-table .select-item:checked").length
        let globalTotalCount = $("#permissions-table .select-item").length
        $("#permissions-table .select-all").prop('checked', globalCheckedCount === globalTotalCount)

        // Each row
        $("#permissions-table tbody tr").each((index, el) => {
            let rowElement = $(el)
            let rowCheckedCount = $(".select-item:checked", rowElement).length
            let rowTotalCount = $(".select-item", rowElement).length
            $(".select-row", rowElement).prop('checked', rowCheckedCount === rowTotalCount)
        })
    }

    autoDisplayApiCapabilities() {
        let globalApiTotalCount = $("#permissions-table td.for-api input.select-item").length
        let globalApiCheckedCount = $("#permissions-table td.for-api input.select-item:checked").length

        if (globalApiCheckedCount > 0 && globalApiTotalCount !== globalApiCheckedCount) {
            $('#manage-api-capabilities').prop('checked', true).change()
        }
    }
}