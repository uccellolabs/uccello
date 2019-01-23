export class Edit {
    constructor() {
        this.initListeners()
    }

    initListeners() {
        this.initDeleteCurrentFileListener()
        this.initSaveAndNewListener()
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
}