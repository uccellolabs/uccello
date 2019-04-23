import {Datatable} from './datatable'

export class Edit {
    constructor() {
        this.initListeners()
    }

    initListeners() {
        this.initDeleteCurrentFileListener()
        this.initSaveAndNewListener()
        this.initEntityFields()
    }

    initDeleteCurrentFileListener() {
        $('.current-file .delete-file a').on('click', (event) => {
            event.preventDefault();

            // Display file field
            $(event.currentTarget).parents('.form-group:first').find('.file-field').removeClass('hide')

            // Remove current file
            $(event.currentTarget).parents('.form-group:first').find('.delete-file-field').val(1)
            $(event.currentTarget).parents('.current-file:first').remove()
        })
    }

    initSaveAndNewListener() {
        $('.btn-save-new').on('click', () => {
            // Set we want to create a new record after save
            $("input[name='save_new_hdn']").val(1);

            // Submit form
            $('form.edit-form').submit();
        })
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
        
        $(`[name='${fieldName}']`).val(recordId)
        $(`#${fieldName}_display`).val(recordLabel)
        $(`#${fieldName}_display`).parent().find('label').addClass('active')

        $(modal).modal('close')
    }
}