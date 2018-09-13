export class Edit {
    constructor() {
        this.initListeners()
    }

    initListeners() {
        this.initDeleteCurrentFileListener()
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
}