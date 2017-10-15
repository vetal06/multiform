
var MultiForm = {
    rowTemplate: '',
    jsRowTemplate: '',
    widgetId: '',
    rowSelector: '.js-multiform-row',
    deleteSelector: '.js-delete-row',
    addSelector: '.js-add-button',
    init: function () {
        this.setDeleteEvent();
        this.setAddEvent();
    },

    /**
     * Событие удаления строки
     */
    setDeleteEvent: function () {
        var _t = this;
        $('body').on('click', _t.deleteSelector, function () {
            var button = $(this);
            button.closest(_t.rowSelector).remove();
        });
    },

    setAddEvent: function () {
        var _t = this;
        $('body').on('click', _t.addSelector, function () {
            var button = $(this);
            var newRowId = _t.getNewRowId(button);
            var rowData = $(_t.rowTemplate);
            var rowId = parseInt(rowData.data('id-key'));
            rowData.attr('data-id-key', newRowId);
            $.each(rowData.find('input'), function (k, v) {
                var name = $(v).attr('name');
                if (!!name) {
                    name = name.replace(rowId, newRowId);
                    $(v).attr('name', name);

                }
                var id = $(v).attr('id');
                if (!!id) {
                    id = id.replace(rowId, newRowId);
                    $(v).attr('id', id);

                }

            });
            button.closest(_t.widgetId).append(rowData);
            _t.registerJs(rowData, newRowId);
        });
    },

    registerJs: function(row, rowId) {
        var js = this.jsRowTemplate,
            inputs = row.find('input');
        $.each(inputs, function (k, input) {
            var id = $(input).attr('id'),
                name = $(input).attr('name');
            if (!!id){
                var firstId = id.replace(rowId, '1');
                js = js.replace(new RegExp(firstId, 'g'), id);
            }
            if (!!name){
                var firstname = name.replace(rowId, '1');
                js = js.replace(new RegExp(firstname, 'g'), name);
            }
        });
        window.eval(js);
    },

    getNewRowId: function (button) {
        var rowData = button.closest(this.widgetId).find(this.rowSelector).last();
        return parseInt(rowData.attr('data-id-key')) + 1;
    }
};