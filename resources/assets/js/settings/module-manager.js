export class ModuleManager {
    constructor() {
        this.initCheckboxListener()
    }

    initCheckboxListener() {
        $("input[type='checkbox'].module-activation").on('click', (event) => {
            let element = event.currentTarget
            let url = $("meta[name='module-activation-url']").attr('content')

            $.post(url, {
                _token: $("meta[name='csrf-token']").attr('content'),
                src_module: $(element).data('module'),
                active: $(element).is(':checked') === true ? '1' : '0'
            }).fail((error) => {
                swal(uctrans.trans('default:dialog.error.title'), uctrans.trans('settings:error.save'), 'error')
            })
        })
    }
}