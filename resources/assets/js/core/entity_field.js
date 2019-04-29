import {Datatable} from './datatable'

export class EntityField {
    constructor() {
        this.initEntityFields()
        this.initListener()
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
            let event = new CustomEvent('uccello.list.refresh', { detail: tableId })
            dispatchEvent(event)
        })
    }
}