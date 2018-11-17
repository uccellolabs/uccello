import { Datatable } from './datatable'

export class Detail {
    constructor() {
        this.initDatatable()
    }

    initDatatable() {
        const csrfToken = $('meta[name="csrf-token"]').attr('content')
        const domainSlug = $('meta[name="domain"]').attr('content')
        const moduleName = $('meta[name="module"]').attr('content')
        const recordId = $('meta[name="record"]').attr('content')

        $('.dataTable').each((index, element) => {
            var relatedListId = $(element).data('relatedlist')
            var relatedModuleName = $(element).data('related-module')
            var datatableUrl = $(element).data('url')
            var datatableColumns = $(element).data('columns') // Json automaticaly parsed

            let datatable = new Datatable()
            datatable.url = `${datatableUrl}?id=${recordId}&relatedlist=${relatedListId}&datatable=1&_token=${csrfToken}`
            datatable.domainSlug = domainSlug
            datatable.moduleName = relatedModuleName
            datatable.columns = datatableColumns
            datatable.rowUrl = laroute.route('uccello.detail', { id: '%s', domain: domainSlug, module: relatedModuleName })
            datatable.init(element)
        })
    }
}