(window.webpackJsonp=window.webpackJsonp||[]).push([[3],{"+lRy":function(t,e){},0:function(t,e,a){a("F/og"),a("XZv6"),t.exports=a("+lRy")},"F/og":function(t,e,a){"use strict";a.r(e);var n=a("ipVs");function i(t,e){for(var a=0;a<e.length;a++){var n=e[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}var r=function(){function t(){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t)}var e,a,r;return e=t,(a=[{key:"init",value:function(t,e){this.table=$(t),this.linkManager=new n.a(!1),this.rowClickCallback=e,this.initColumns(),this.initColumnsSortListener(),this.initColumnsVisibilityListener(),this.initRecordsNumberListener(),this.initColumnsSearchListener(),this.initRefreshContentEventListener()}},{key:"initColumns",value:function(){var t=this;this.columns={},this.table&&$("th[data-field]",this.table).each((function(e,a){var n=$(a),i=n.data("field");void 0!==i&&(t.columns[i]={columnName:n.data("column"),search:$(".field-search",n).val()})}))}},{key:"makeQuery",value:function(t){var e=this;if(this.table){var a=$(this.table).attr("data-content-url");$("tbody tr.no-results",this.table).hide(),$('.pagination[data-table="'.concat(this.table.attr("id"),'"]')).hide(),$('.loader[data-table="'.concat(this.table.attr("id"),'"]')).show();var n={_token:$('meta[name="csrf-token"]').attr("content"),id:$('meta[name="record"]').attr("content"),columns:this.columns,order:$(this.table).attr("data-order")?JSON.parse($(this.table).attr("data-order")):null,relatedlist:$(this.table).attr("data-relatedlist")?$(this.table).attr("data-relatedlist"):null,length:$(this.table).attr("data-length")};void 0!==t&&(n.page=t),$.post(a,n).then((function(t){e.displayResults(t),e.displayPagination(t),$('.loader[data-table="'.concat(e.table.attr("id"),'"]')).hide()})).catch((function(t){$('.loader[data-table="'.concat(e.table.attr("id"),'"]')).addClass("hide"),swal(uctrans.trans("uccello::default.dialog.error.title"),uctrans.trans("uccello::default.dialog.error.message"),"error")})),this.dispatchQueryEvent(this.table.attr("id"),n)}}},{key:"dispatchQueryEvent",value:function(t,e){var a=new CustomEvent("uccello.datatable.query",{detail:{tableId:t,columns:e.columns}});dispatchEvent(a)}},{key:"displayResults",value:function(t){if(this.table&&t.data)if($("tbody tr.record",this.table).remove(),0===t.data.length)$("tbody tr.no-results",this.table).show();else{var e=!0,a=!1,n=void 0;try{for(var i,r=t.data[Symbol.iterator]();!(e=(i=r.next()).done);e=!0){var l=i.value;this.addRowToTable(l)}}catch(t){a=!0,n=t}finally{try{e||null==r.return||r.return()}finally{if(a)throw n}}}}},{key:"displayPagination",value:function(t){var e=this;if(this.table&&t.data){var a=t.current_page||1,n=t.last_page,i=a-2,r=a+2;r>n&&(r=n,i=(i=n-4)<1?1:i),i<=1&&(i=1,r=Math.min(5,n));var l,o=null===t.prev_page_url?"disabled":"waves-effect",s=t.prev_page_url?'data-page="'.concat(t.current_page-1,'"'):"",c='<li class="'.concat(o,'"><a href="javascript:void(0);" ').concat(s,'><i class="material-icons">chevron_left</i></a></li>');if(i<=3)for(l=1;l<i;l++)c+=l==a?'<li class="active primary"><a href="javascript:void(0);">'.concat(l,"</a></li>"):'<li class="waves-effect"><a href="javascript:void(0);" data-page="'.concat(l,'">').concat(l,"</a></li>");else c+='<li class="waves-effect"><a href="javascript:void(0);" data-page="1">1</a></li> <li>...</li>';for(l=i;l<=r;l++)c+=l===a?'<li class="active primary"><a href="javascript:void(0);">'.concat(l,"</a></li>"):'<li class="waves-effect"><a href="javascript:void(0);" data-page="'.concat(l,'">').concat(l,"</a></li>");r<n&&(c+='<li>...</li> <li class="waves-effect"><a href="javascript:void(0);" data-page="'.concat(n,'">').concat(n,"</a></li>"));var d=null===t.next_page_url?"disabled":"waves-effect",u=t.next_page_url?'data-page="'.concat(t.current_page+1,'"'):"";c+='<li class="'.concat(d,'"><a href="javascript:void(0);" ').concat(u,'><i class="material-icons">chevron_right</i></a></li>');var f=$('.pagination[data-table="'.concat(this.table.attr("id"),'"]'));f.html(c),f.show(),$("a[data-page]",f).on("click",(function(t){var a=$(t.currentTarget).attr("data-page");e.makeQuery(a)}))}}},{key:"addRowToTable",value:function(t){if(this.table&&t){var e=this,a=$("tbody tr.template",this.table).clone();$("th[data-field]",this.table).each((function(){var e=$(this).data("field"),n=$('td[data-field="'.concat(e,'"] '),a);n.html(t[e+"_html"]),"none"===$(this).css("display")&&n.hide()})),$("a",a).each((function(){var e=$(this).attr("href");e=e.replace("RECORD_ID",t.__primaryKey),$(this).attr("href",e),$(this).attr("data-tooltip")&&$(this).tooltip()}));var n=$(a).attr("data-row-url");n=n.replace("RECORD_ID",t.__primaryKey),$(a).attr("data-row-url",n),$(a).attr("data-record-id",t.__primaryKey),$(a).attr("data-record-label",t.recordLabel),$(a).on("click",(function(t){void 0!==e.rowClickCallback?e.rowClickCallback(t,e,$(this).attr("data-record-id"),$(this).attr("data-record-label")):document.location.href=$(this).attr("data-row-url")}));var i=new CustomEvent("uccello.list.row_added",{detail:{element:a,record:t}});dispatchEvent(i),this.linkManager.initClickListener(a),a.removeClass("hide").removeClass("template").addClass("record").appendTo("#".concat(this.table.attr("id")," tbody"))}}},{key:"initColumnsVisibilityListener",value:function(){$('ul.columns[data-table="'.concat(this.table.attr("id"),'"] li a')).on("click",(function(t){var e=$(t.currentTarget),a=$(e).data("field"),n=$(e).parents("li:first");n.toggleClass("active"),n.hasClass("active")?($('th[data-field="'.concat(a,'"]')).show(),$('td[data-field="'.concat(a,'"]')).show()):($('th[data-field="'.concat(a,'"]')).hide(),$('td[data-field="'.concat(a,'"]')).hide())}))}},{key:"initRecordsNumberListener",value:function(){var t=this;this.table&&$('ul.records-number[data-table="'.concat(this.table.attr("id"),'"] li a')).on("click",(function(e){var a=$(e.currentTarget),n=$(a).data("number"),i=$(a).parents("ul:first").attr("id");$('a[data-target="'.concat(i,'"] strong.records-number')).text(n),$(t.table).attr("data-length",n),t.makeQuery()}))}},{key:"initColumnsSearchListener",value:function(){if(this.table){this.timer=0;var t=this;$("th[data-field]",this.table).each((function(e,a){var n=$(a),i=n.data("field");$("input:not(.nosearch)",n).on("keyup apply.daterangepicker cancel.daterangepicker",(function(){t.launchSearch(i,$(this).val())})),$("select:not(.nosearch)",n).on("change",(function(){t.launchSearch(i,$(this).val())}))})),this.addClearSearchButtonListener()}}},{key:"initRefreshContentEventListener",value:function(){var t=this;this.table&&addEventListener("uccello.list.refresh",(function(e){e.detail===$(t.table).attr("id")&&t.makeQuery()}))}},{key:"launchSearch",value:function(t,e){var a=this;""!==e&&$(".clear-search").show(),this.columns[t].search!==e&&(this.columns[t].search=e,clearTimeout(this.timer),this.timer=setTimeout((function(){a.makeQuery()}),700))}},{key:"addClearSearchButtonListener",value:function(){var t=this;this.table&&$(".actions-column .clear-search").on("click",(function(e){$("thead select",t.table).val(""),$("thead input",t.table).val(""),t.initColumns(),$(e.currentTarget).hide(),t.makeQuery()}))}},{key:"initColumnsSortListener",value:function(){var t=this;this.table&&$("th[data-field].sortable",this.table).each((function(e,a){var n=$(a),i=n.data("column");$("a.column-label",n).on("click",(function(e){var a=t.table.attr("data-order")?JSON.parse(t.table.attr("data-order")):null;$("a.column-label i").hide(),null!==a&&"asc"===a[i]?(a[i]="desc",$("a.column-label i",n).removeClass("fa-sort-amount-up").addClass("fa-sort-amount-down")):((a={})[i]="asc",$("a.column-label i",n).removeClass("fa-sort-amount-down").addClass("fa-sort-amount-up")),$("a.column-label i",n).show(),t.table.attr("data-order",JSON.stringify(a)),t.makeQuery()}))}))}}])&&i(e.prototype,a),r&&i(e,r),t}();function l(t,e){for(var a=0;a<e.length;a++){var n=e[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}var o=function(){function t(){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.initEntityFields(),this.initListener()}var e,a,n;return e=t,(a=[{key:"initEntityFields",value:function(){var t=this;0!=$('table[data-filter-type="related-list"]').length&&($('table[data-filter-type="related-list"]').each((function(e,a){var n=new r;n.init(a,(function(e,a,n,i){t.selectRelatedModule(a,n,i)})),n.makeQuery()})),$(".delete-related-record").on("click",(function(t){var e=$(t.currentTarget).parents(".modal:first"),a=e.attr("data-field");$("[name='".concat(a,"']")).val(""),$("#".concat(a,"_display")).val(""),$("#".concat(a,"_display")).parent().find("label").removeClass("active"),$(e).modal("close")})))}},{key:"selectRelatedModule",value:function(t,e,a){var n=$(t.table).parents(".modal:first"),i=n.attr("data-field");$("[name='".concat(i,"']")).val(e).trigger("keyup"),$("#".concat(i,"_display")).val(a),$("#".concat(i,"_display")).parent().find("label").addClass("active"),$(n).modal("close")}},{key:"initListener",value:function(){$("a.entity-modal").on("click",(function(){var t=$(this).attr("data-table"),e=new CustomEvent("uccello.list.refresh",{detail:t});dispatchEvent(e)}))}}])&&l(e.prototype,a),n&&l(e,n),t}();function s(t){return(s="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function c(t,e,a){return e in t?Object.defineProperty(t,e,{value:a,enumerable:!0,configurable:!0,writable:!0}):t[e]=a,t}function d(t,e){for(var a=0;a<e.length;a++){var n=e[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}var u=function(){function t(){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.initDatatable(),this.initListeners(),this.initEntityFields(),this.content_url=$("#datatable").attr("data-content-url")}var e,a,n;return e=t,(a=[{key:"initDatatable",value:function(){0!=$('table[data-filter-type="list"]').length&&(this.datatable=new r,this.datatable.init($('table[data-filter-type="list"]')),this.datatable.makeQuery())}},{key:"initListeners",value:function(){this.initSaveFilterListener(),this.initDeleteFilterListener(),this.initExportListener(),this.initDescendantsRecordsFilterEventListener()}},{key:"initSaveFilterListener",value:function(){var t=this;$("#addFilterModal a.save").on("click",(function(e){var a=$("#add_filter_filter_name").val();""!==a?$("ul#filters-list a[data-name='".concat(a,"']")).length>0?swal(c({title:uctrans.trans("uccello::default.filter.exists.title"),text:uctrans.trans("uccello::default.filter.exists.message"),icon:"warning",buttons:!0,dangerMode:!0},"buttons",[uctrans.trans("uccello::default.button.no"),uctrans.trans("uccello::default.button.yes")])).then((function(e){!0===e&&t.saveFilter()})):t.saveFilter():$("#add_filter_filter_name").parent(".form-line").addClass("focused error")}))}},{key:"initDeleteFilterListener",value:function(){var t=this,e=$('table[data-filter-type="list"]');$("a.delete-filter").on("click",(function(){var a=$(e).attr("data-filter-id");a&&swal(c({title:uctrans.trans("uccello::default.confirm.dialog.title"),text:uctrans.trans("uccello::default.filter.delete.message"),icon:"warning",buttons:!0,dangerMode:!0},"buttons",[uctrans.trans("uccello::default.button.no"),uctrans.trans("uccello::default.button.yes")])).then((function(e){!0===e&&t.deleteFilter(a)}))}))}},{key:"initExportListener",value:function(){var t=this;$("#exportModal .export").on("click",(function(e){var a=$('table[data-filter-type="list"]'),n=$("#exportModal"),i={_token:$('meta[name="csrf-token"]').attr("content"),extension:$("#export_format",n).val(),columns:t.getVisibleColumns(a),conditions:t.getSearchConditions(a),order:$(a).attr("data-order")?JSON.parse($(a).attr("data-order")):null,with_hidden_columns:$("#with_hidden_columns",n).is(":checked")?1:0,with_id:$("#export_with_id",n).is(":checked")?1:0,with_conditions:$("#export_keep_conditions",n).is(":checked")?1:0,with_order:$("#export_keep_order",n).is(":checked")?1:0,with_timestamps:$("#export_with_timestamps",n).is(":checked")?1:0},r=a.data("export-url"),l=t.getFakeFormHtml(i,r),o=$(l);$("body").append(o),o.submit(),o.remove()}))}},{key:"getVisibleColumns",value:function(t){var e=[];return $("th[data-field]",t).each((function(){var t=$(this).data("field");"none"!==$(this).css("display")&&e.push(t)})),e}},{key:"getSearchConditions",value:function(t){var e=this,a={};return $("th[data-column]",t).each((function(t,n){var i=$(n).data("field");e.datatable.columns[i].search&&(a[i]=e.datatable.columns[i].search)})),a}},{key:"getOrderWithFieldColumn",value:function(t){var e=this.datatable.columns,a={},n=!0,i=!1,r=void 0;try{for(var l,o=t.order()[Symbol.iterator]();!(n=(l=o.next()).done);n=!0){var s=l.value;a[e[s[0]-1].db_column]=s[1]}}catch(t){i=!0,r=t}finally{try{n||null==o.return||o.return()}finally{if(i)throw r}}return a}},{key:"saveFilter",value:function(){var t=$('table[data-filter-type="list"]'),e=$("#addFilterModal"),a={_token:$('meta[name="csrf-token"]').attr("content"),name:$("#add_filter_filter_name",e).val(),type:"list",save_order:$("#add_filter_save_order",e).is(":checked")?1:0,save_page_length:$("#add_filter_save_page_length",e).is(":checked")?1:0,columns:this.getVisibleColumns(t),order:$(t).attr("data-order")?JSON.parse($(t).attr("data-order")):null,page_length:$(t).attr("data-length"),public:$("#add_filter_is_public",e).is(":checked")?1:0,default:$("#add_filter_is_default",e).is(":checked")?1:0},n=this.getSearchConditions(t);a.conditions=n!=={}?{search:n}:null,$.ajax({url:t.data("save-filter-url"),method:"post",data:a,contentType:"application/x-www-form-urlencoded"}).then((function(e){var a={id:e.id,name:e.name};$('a[data-target="filters-list"] span').text(a.name),$(t).attr("data-filter-id",a.id),$("a.delete-filter").parents("li:first").show()})).fail((function(t){swal(uctrans.trans("uccello::default.dialog.error.title"),t.message,"error")}))}},{key:"deleteFilter",value:function(t){var e=$('table[data-filter-type="list"]'),a={_token:$('meta[name="csrf-token"]').attr("content"),id:t},n=$(e).data("delete-filter-url");$.post(n,a).then((function(t){t.success?document.location.href=$(e).data("list-url"):swal(uctrans.trans("uccello::default.dialog.error.title"),t.message,"error")})).fail((function(t){swal(uctrans.trans("uccello::default.dialog.error.title"),t.message,"error")}))}},{key:"getFakeFormHtml",value:function(t,e){var a="<form style='display: none;' method='POST' action='"+e+"'>";return _.each(t,(function(t,e){var n="object"===s(t)?JSON.stringify(t):t;"string"==typeof n&&(n=n.replace("\\","\\\\").replace("'","'")),a+="<input type='hidden' name='"+e+"' value='"+n+"'>"})),a+="</form>"}},{key:"initEntityFields",value:function(){new o}},{key:"initDescendantsRecordsFilterEventListener",value:function(){var t=this;$("[data-descendants-records-filter]").on("click",(function(e){$("#dropdown-descendants-records").find("li").each((function(){$(this).removeClass("active")})),$(this).parent().addClass("active");var a=$(this).data("descendants-records-filter"),n=$(this).parents("ul:first").data("table"),i=$('#dropdown-columns li a[data-field="domain"]');1==a?i.length>0&&($(i).parents("li:first").addClass("active"),$('th[data-field="domain"]').show()):i.length>0&&($(i).parents("li:first").removeClass("active"),$('th[data-field="domain"]').hide()),$("#"+n).attr("data-content-url",t.content_url+"?descendants="+a),t.dispatchCustomEvent(n)}))}},{key:"dispatchCustomEvent",value:function(t){var e=new CustomEvent("uccello.list.refresh",{detail:t});dispatchEvent(e)}}])&&d(e.prototype,a),n&&d(e,n),t}();function f(t,e){for(var a=0;a<e.length;a++){var n=e[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}var h=function(){function t(){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.initListeners()}var e,a,n;return e=t,(a=[{key:"initListeners",value:function(){this.initDeleteCurrentFileListener(),this.initSaveAndNewListener(),this.initEntityFields()}},{key:"initDeleteCurrentFileListener",value:function(){$(".current-file .delete-file a").on("click",(function(t){t.preventDefault(),$(t.currentTarget).parents(".form-group:first").find(".file-field").removeClass("hide"),$(t.currentTarget).parents(".form-group:first").find(".delete-file-field").val(1),$(t.currentTarget).parents(".current-file:first").remove()}))}},{key:"initSaveAndNewListener",value:function(){$(".btn-save-new").on("click",(function(){$("input[name='save_new_hdn']").val(1),$("form.edit-form").submit()}))}},{key:"initEntityFields",value:function(){new o}}])&&f(e.prototype,a),n&&f(e,n),t}();function v(t,e){for(var a=0;a<e.length;a++){var n=e[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}var m=function(){function t(){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.initRelatedLists(),this.initRelatedListSelectionModalListener()}var e,a,n;return e=t,(a=[{key:"initRelatedLists",value:function(){var t=this;0!=$('table[data-filter-type="related-list"]').length&&(this.relatedListDatatables={},$('table[data-filter-type="related-list"]').each((function(e,a){var n=new r;n.init(a),n.makeQuery(),t.relatedListDatatables[$(a).attr("id")]=n})))}},{key:"initRelatedListSelectionModalListener",value:function(){var t=this;$(".btn-relatedlist-select").on("click",(function(e){var a=e.currentTarget,n=$(a).data("relatedlist"),i=$(a).data("modal-title"),l=$(a).data("modal-icon"),o=$(".selection-modal-content[data-relatedlist='".concat(n,"']")).html(),s=$(a).data("table"),c=$("#relatedListSelectionModal");$("h4 span",c).text(i),$("h4 i",c).text(l),$(".modal-body",c).html(o),$("table tbody tr.record",c).remove(),$("table thead .search",c).removeClass("hide");var d=new r;d.init($("table",c),(function(e,a,i){t.relatedListNNRowClickCallback(n,s,a,i)})),d.makeQuery()}))}},{key:"relatedListNNRowClickCallback",value:function(t,e,a,n){var i=this,r=$(a.table).data("add-relation-url"),l={_token:$('meta[name="csrf-token"]').attr("content"),id:$('meta[name="record"]').attr("content"),relatedlist:t,related_id:n};$.post(r,l).then((function(t){if(!1===t.success)swal(uctrans.trans("uccello::default.dialog.error.title"),t.message,"error");else{var a=i.relatedListDatatables[e];a&&a.makeQuery(),$("#relatedListSelectionModal").modal("close")}}))}}])&&v(e.prototype,a),n&&v(e,n),t}();function p(t,e){for(var a=0;a<e.length;a++){var n=e[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}new(function(){function t(){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.lazyLoad()}var e,a,n;return e=t,(a=[{key:"lazyLoad",value:function(){switch($('meta[name="page"]').attr("content")){case"list":new u;break;case"edit":new h;break;case"detail":new m}}}])&&p(e.prototype,a),n&&p(e,n),t}())},XZv6:function(t,e){},ipVs:function(t,e,a){"use strict";function n(t){return(n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function i(t,e){for(var a=0;a<e.length;a++){var n=e[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}a.d(e,"a",(function(){return r}));var r=function(){function t(e){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),!1!==e&&this.initListeners()}var e,a,r;return e=t,(a=[{key:"initListeners",value:function(){this.initClickListener()}},{key:"initClickListener",value:function(t){var e=this;void 0===t&&(t=null),$("a[data-config], button[data-config]",t).on("click",(function(t){t.preventDefault(),t.stopImmediatePropagation(),e.element=$(t.currentTarget),e.config=e.element.data("config"),e.element.blur(),null===e.config&&(e.config={}),!0===e.config.confirm?e.confirm():e.launchAction()}))}},{key:"launchAction",value:function(){switch(this.config.actionType){case"link":this.gotoUrl();break;case"ajax":this.callUrl()}}},{key:"gotoUrl",value:function(){var t=this.config.target?this.config.target:"_self";window.open(this.element.attr("href"),t)}},{key:"callUrl",value:function(){var t=this.element.attr("href"),e="object"===n(this.config.ajax)?this.config.ajax:{},a="get";e.method&&(a=e.method);var i={};if(e.params&&(i=e.params),"post"===a){var r=$('meta[name="csrf-token"]').attr("content");i._token=r}var l=null;e.elementToUpdate&&(l=e.elementToUpdate),$.ajax({url:t,method:a,data:i}).then((function(t){l?$(l).html(t.content):!1===t.success?swal(uctrans.trans("uccello::default.dialog.error.title"),t.message,"error"):(swal(uctrans.trans("uccello::default.dialog.success.title"),t.message,"success"),!0===e.refresh&&document.location.reload())})).catch((function(t){swal(uctrans.trans("uccello::default.dialog.error.title"),t.message,"error")}))}},{key:"confirm",value:function(){var t,e,a,i,r,l=this;"object"===n(this.config.dialog)&&(t=this.config.dialog.title,e=this.config.dialog.text,a=this.config.dialog.confirmButtonText,i=this.config.dialog.confirmButtonColor,r=this.config.dialog.closeOnConfirm),t||(t=uctrans.trans("uccello::default.confirm.dialog.title")),a||(a=uctrans.trans("uccello::default.button.yes")),i||(i="#DD6B55"),r||(r=!0),"ajax"===this.config.actionType&&(this.config.ajax&&this.config.ajax.elementToUpdate||(r=!1)),swal({title:t,text:e,icon:"warning",buttons:{cancel:{text:uctrans.trans("uccello::default.button.no"),value:null,visible:!0},confirm:{text:a,value:!0,className:"red",closeModal:r}}}).then((function(t){!0===t&&l.launchAction()}))}}])&&i(e.prototype,a),r&&i(e,r),t}()}},[[0,0]]]);