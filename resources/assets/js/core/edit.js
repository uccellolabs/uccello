import {EntityField} from './entity_field'

export class Edit {
    constructor() {
        this.initListeners()
    }

    initListeners() {
        this.initDeleteCurrentFileListener()
        this.initSaveAndNewListener()
        this.initEntityFields()
        this.initEntityModalButtonsListener()
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
        let entityField = new EntityField()
    }

    initEntityModalButtonsListener() {
        $('a.create-related-record').click(event => {
            let modal = $(event.currentTarget).parents('.modal:first')

            $('div.create-related-record', modal).show()
            $('div.search-related-record', modal).hide()

            $('a.create-related-record', modal).hide()
            $('a.search-related-record', modal).show()
        })

        $('a.search-related-record').click(event => {
            let modal = $(event.currentTarget).parents('.modal:first')

            $('div.search-related-record', modal).show()
            $('div.create-related-record', modal).hide()

            $('a.search-related-record', modal).hide()
            $('a.create-related-record', modal).show()
        })
    }
}