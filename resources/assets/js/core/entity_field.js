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


    setUpForm(html)
    {
        let that = this;
        $('#tabCreateAjax').html(html);

        this.initMaterializeOnItems();

        let form = $('#form_popup_'+this.relatedModule)
        
        $(form).submit(function(){
            //Submit form with AJAX
            $.ajax(
                {
                    url: $(form).attr('action'),
                    type: 'POST',
                    data : $(form).serialize(),
                    success : function (response) {
                        if(response.id && response.recordLabel)
                        {
                            const modal = $(form).parents('.modal:first');
                            const fieldName = modal.attr('data-field');

                            $(`[name='${fieldName}']`).val(response.id).trigger('keyup')
                            $(`#${fieldName}_display`).val(response.recordLabel)
                            $(`#${fieldName}_display`).parent().find('label').addClass('active')

                            $(modal).modal('close')
                        }
                        else
                        {
                            that.setUpForm(response);
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
        let that = this;
        $('a.entity-modal').on('click', function() {
            let tableId = $(this).attr('data-table')
            that.relatedModule = tableId.replace('datatable_', '');

            let edit_view_popup_url = $('meta[name="popup_url_'+that.relatedModule+'"]').attr('content');
            $.get(edit_view_popup_url).then((data) => {
                that.setUpForm(data);
            });
        })
    }
}