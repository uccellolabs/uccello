export class Index {
    constructor() {
        this.initCheckboxListener()
    }

    initCheckboxListener() {
        const domainSlug = $('meta[name="domain"]').attr('content')

        $("input[type='checkbox'].module-activation").on('click', (event) => {
            let element = event.currentTarget
            let moduleName = $(element).data('module')
            let active = $(element).is(':checked') === true ? '1' : '0'

            document.location.href = laroute.route('uccello.module.activation', { domain: domainSlug, src_module: moduleName, active: active })
        })
    }
}