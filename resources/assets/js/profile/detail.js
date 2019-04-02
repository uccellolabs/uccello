export class Detail {
    constructor() {
        this.autoDisplayApiCapabilities()
    }

    autoDisplayApiCapabilities() {
        let globalApiTotalCount = $("#permissions-table td.for-api").length
        let globalApiCheckedCount = $("#permissions-table td.for-api[data-checked='true']").length

        if (globalApiCheckedCount > 0 && globalApiTotalCount !== globalApiCheckedCount) {
            $("#permissions-table .for-api").removeClass('hide')
        }
    }
}