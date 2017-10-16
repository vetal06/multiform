
(function( $ ){
    var methods = {
        init: function (options) {
            var _t = {
                rowSelector: '.js-multiform-row',
                deleteSelector: '.js-delete-row',
                addSelector: '.js-add-button',
                /**
                 * Событие удаления строки
                 */
                setDeleteEvent: function (element) {
                    var _t = this;
                    element.on('click', _t.deleteSelector, function () {
                        var button = $(this);
                        button.closest(_t.rowSelector).remove();
                    });
                },

                setAddEvent: function (element) {
                    var _t = this;
                    element.on('click', _t.addSelector, function () {
                        var button = $(this);
                        var newRowId = _t.getNewRowId(element);
                        var rowData = $(options.rowTemplate);
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
                        element.append(rowData);
                        _t.registerJs(rowData, newRowId);
                    });
                },

                registerJs: function(row, rowId) {
                    var js = options.jsRowTemplate,
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

                getNewRowId: function (element) {
                    var rowData = element.find(this.rowSelector).last();
                    return parseInt(rowData.attr('data-id-key')) + 1;
                }
            };
            _t.setDeleteEvent(this);
            _t.setAddEvent(this);
        },
    };

    $.fn.MultiForm = function( method ) {

        if ( methods[method] ) {
            return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Метод с именем ' +  method + ' не существует для jQuery.tooltip' );
        }

    };

})( jQuery );
