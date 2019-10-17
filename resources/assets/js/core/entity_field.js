import {Datatable} from './datatable'

export class EntityField {
    constructor() {
        this.initEntityFields()
        this.initListener()
        this.relatedModule = '';
    }

    /**
     * Initalize datatable for all entity fields
     */
    initEntityFields() {
        if ($('table[data-filter-type="related-list"]').length == 0) {
            return
        }

        $('table[data-filter-type="related-list"]').each((index, el) => {
            // Click callback
            let rowClickCallback = (event, datatable, recordId, recordLabel) => {
                this.selectRelatedModule(datatable, recordId, recordLabel)
            }

            let datatable = new Datatable()
            datatable.init(el, rowClickCallback)
            datatable.makeQuery()
        })

        $('.delete-related-record').on('click', (event) => {
            const modal = $(event.currentTarget).parents('.modal:first');
            const fieldName = modal.attr('data-field')

            $(`[name='${fieldName}']`).val('')
            $(`#${fieldName}_display`).val('')
            $(`#${fieldName}_display`).parent().find('label').removeClass('active')

            $(modal).modal('close')
        })
    }

    selectRelatedModule(datatable, recordId, recordLabel) {
        const modal = $(datatable.table).parents('.modal:first');
        const fieldName = modal.attr('data-field')

        $(`[name='${fieldName}']`).val(recordId).trigger('keyup')
        $(`#${fieldName}_display`).val(recordLabel)
        $(`#${fieldName}_display`).parent().find('label').addClass('active')

        $(modal).modal('close')
    }

    initListener() {
        $('a.entity-modal').on('click', function() {
            let tableId = $(this).attr('data-table')
            this.relatedModule = tableId.replace('datatable_', '');
            
            let event = new CustomEvent('uccello.list.refresh', { detail: tableId })
            dispatchEvent(event);   

            let form = $('#form_popup_'+this.relatedModule)

            $(form).submit(function(){
                $.ajax(
                    {
                        url: $(form).attr('action'), // or whatever
                        type: 'POST',
                        data : $(form).serialize(),
                        success : function (response) {
                            const modal = $(form).parents('.modal:first');
                            const fieldName = modal.attr('data-field');


                            $(`[name='${fieldName}']`).val(response.id).trigger('keyup')
                            $(`#${fieldName}_display`).val(response.display_name)
                            $(`#${fieldName}_display`).parent().find('label').addClass('active')

                            $(modal).modal('close')
                        }
                    }
                );
                return false;
            });

            // let popup_save_url = $('meta[name="popup_url_'+this.relatedModule+'"]').attr('content');
            // $.get(popup_save_url).then((data) => {
            //     $('#tabCreateAjax').html(data);

            // });
        })

        
    }
}