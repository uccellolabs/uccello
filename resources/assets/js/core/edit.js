import {EntityField} from './entity_field'

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
            $(event.currentTarget).parents('.file-field:first').find('.btn').removeClass('hide')
            $(event.currentTarget).parents('.file-field:first').find('.file-path-wrapper').removeClass('hide')

            // Remove current file
            $(event.currentTarget).parents('.file-field:first').find('.delete-file-field').val(1)
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
}