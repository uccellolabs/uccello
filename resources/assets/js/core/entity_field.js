import {Datatable} from './datatable'

export class EntityField {
    constructor() {
        this.initEntityFields()
        this.initListener()
        this.fieldName = '';
    }

    /**
     * Initalize datatable for all entity fields
     */
    initEntityFields() {
        if ($('table[data-filter-type="related-list"]').length == 0) {
            return
        }

        $('.delete-related-record').on('click', (event) => {
            const modal = $(event.currentTarget).parents('.modal:first');
            const fieldName = modal.attr('data-field')

            dispatchEvent(new CustomEvent('uccello.uitype.entity.deleted', {
                detail: {
                    id: $(`[name='${fieldName}']`).val(),
                    fieldName: fieldName
                }
            }));

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

        dispatchEvent(new CustomEvent('uccello.uitype.entity.selected', {
            detail: {
                id: recordId,
                fieldName: fieldName
            }
        }));

        $(modal).modal('close')
    }


    setUpForm(html, modal) {
        $('.create-ajax', modal).html(html);

        this.initMaterializeOnItems();

        let form = $('#form_popup_'+this.fieldName, modal)

        form.submit(_ => {
            //Submit form with AJAX
            $.ajax(
                {
                    url: form.attr('action'),
                    type: 'POST',
                    data : form.serialize(),
                    success : response => {
                        if (response.id && response.recordLabel) {
                            const fieldName = modal.attr('data-field');

                            $(`[name='${fieldName}']`).val(response.id).trigger('keyup')
                            $(`#${fieldName}_display`).val(response.recordLabel)
                            $(`#${fieldName}_display`).parent().find('label').addClass('active')

                            dispatchEvent(new CustomEvent('uccello.uitype.entity.created', {
                                detail: {
                                    id: response.id,
                                    fieldName: fieldName
                                }
                            }));

                            $(modal).modal('close')

                            // Display search list and refresh the list
                            $('a.search-related-record', modal).click()
                            let event = new CustomEvent('uccello.list.refresh', {detail: $('.search-related-record table', modal).attr('id')})
                            dispatchEvent(event)
                        }
                        else {
                            this.setUpForm(response, modal);

                            // Scroll to first error
                            var scrollTo = $('.invalid:first');
                            modal.animate({scrollTop: scrollTo.offset().top - modal.offset().top + modal.scrollTop() - 30, scrollLeft: 0},300);
                        }
                    },
                }
            );
            return false;
        });
    }

    initMaterializeOnItems()
    {
        $('select').each((index, el) => {
            $(el).formSelect({
                dropdownOptions: {
                    alignment: $(el).data('alignment') ? $(el).data('alignment') : 'left',
                    constrainWidth: $(el).data('constrain-width') === false ? false : true,
                    container: $(el).data('container') ? $($(el).data('container')) : null,
                    coverTrigger: $(el).data('cover-trigger') === true ? true : false,
                    closeOnClick: $(el).data('close-on-click') === false ? false : true,
                    hover: $(el).data('hover') === true ? true : false,
                }
            })
        })

        $('[data-tooltip]').tooltip({
            transitionMovement: 0,
        })

        $('.collapsible').collapsible()

        M.updateTextFields();
    }

    initListener() {
        $('a.entity-modal').on('click', event => {
            let element = $(event.currentTarget);
            let tableId = element.attr('data-table')

            let modalId = $(element).attr('href')
            let modal = $(modalId);

            this.fieldName = tableId.replace('datatable_', '');


            // Click callback
            let rowClickCallback = (event, datatable, recordId, recordLabel) => {
                this.selectRelatedModule(datatable, recordId, recordLabel)
            }

            let el = $('table#'+tableId)
            let datatable = new Datatable()
            datatable.init(el, rowClickCallback)
            datatable.makeQuery()

            if (!$("meta[name='entity-new-tab']").attr('content')) {
                let edit_view_popup_url = $('meta[name="popup_url_'+this.fieldName+'"]').attr('content');
                $.get(edit_view_popup_url, {field: this.fieldName}).then((data) => {
                    this.setUpForm(data, modal);
                });
            }
        })
    }
}