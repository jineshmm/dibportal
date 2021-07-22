$(function ()
{
    /**
     * Active icon placement
     */

    $('#main-menu li[class="active"]').each(function ()
    {
        if ($(this).find('.btn-group').length)
        {
            $(this).find('.btn-group button').append('<span class="icon-active" style="margin-left:-' + ($(this).width() / 2 + 7) + 'px;margin-top:44px;"></span>');
        } else
        {
            $(this).append('<span class="icon-active" style="margin-left:' + ($(this).width() / 2 - 18) + 'px;"></span>');
        }
    });

    $(document).on('click', 'input[type=checkbox]', function ()
    {

        if ($(this).attr('checked'))
            $(this).parent().find('.icon-check-empty').addClass('icon-check');
        else
            $(this).parent().find('.icon-check-empty').removeClass('icon-check');
    });

    $('.nav-tabs li[class="active"] a').each(function ()
    {
        $(this).append('<span class="icon-active" style="margin-left:-' + ($(this).width() / 2 + 25) + 'px"></span>');
    });

    $('.show-extended-filters').on('click', function ()
    {
        $(this).closest('.extended-filters').find('.filterasd').slideDown();
    });

    // Set default jQuery ajax options
    $.ajaxSetup(
            {
                type: "post",
                dataType: "json"
            });



    // Init
    DIB.initTooltips();
    DIB.initPopover();

    // Fix up fields
    DIB.fixupElements();

    // Dropdown menus
    DIB.initDropdowns();

    // Datepicker
    FORM.setDatePicker();
    FORM.setAutoComplete();
    FORM.setMask();
    DIB.setScrollable();
    DIB.initSummernote();

    // Defaultfocus
    if ($(".defaultfocus").length)
    {
        $(".defaultfocus")[0].focus();
    }

    LIST.initBackToTopButton();

    // Tab switch
    var URL = document.location.toString();
    if (URL.match(/#/))
    {
        var anchor = URL.split('#')[1];
        if (anchor != '_=_' && $("#content_" + anchor).length)
        {
            TAB.select(anchor);
        }
    }

    // We can remove that
    $('noscript').remove();
});

$(window).resize(function ()
{
    DIB.setScrollable();
});

//
//  Helper funcs
//

function str_repeat(i, m) {
    for (var o = []; m > 0; o[--m] = i)
        ;
    return o.join('');
}

function sprintf() {
    var i = 0, a, f = arguments[i++], o = [], m, p, c, x, s = '';
    while (f) {
        if (m = /^[^\x25]+/.exec(f)) {
            o.push(m[0]);
        } else if (m = /^\x25{2}/.exec(f)) {
            o.push('%');
        } else if (m = /^\x25(?:(\d+)\$)?(\+)?(0|'[^$])?(-)?(\d+)?(?:\.(\d+))?([b-fosuxX])/.exec(f)) {
            if (((a = arguments[m[1] || i++]) == null) || (a == undefined)) {
                throw('Too few arguments.');
            }
            if (/[^s]/.test(m[7]) && (typeof (a) != 'number')) {
                throw('Expecting number but found ' + typeof (a));
            }
            switch (m[7]) {
                case 'b':
                    a = a.toString(2);
                    break;
                case 'c':
                    a = String.fromCharCode(a);
                    break;
                case 'd':
                    a = parseInt(a);
                    break;
                case 'e':
                    a = m[6] ? a.toExponential(m[6]) : a.toExponential();
                    break;
                case 'f':
                    a = m[6] ? parseFloat(a).toFixed(m[6]) : parseFloat(a);
                    break;
                case 'o':
                    a = a.toString(8);
                    break;
                case 's':
                    a = ((a = String(a)) && m[6] ? a.substring(0, m[6]) : a);
                    break;
                case 'u':
                    a = Math.abs(a);
                    break;
                case 'x':
                    a = a.toString(16);
                    break;
                case 'X':
                    a = a.toString(16).toUpperCase();
                    break;
            }
            a = (/[def]/.test(m[7]) && m[2] && a >= 0 ? '+' + a : a);
            c = m[3] ? m[3] == '0' ? '0' : m[3].charAt(1) : ' ';
            x = m[5] - String(a).length - s.length;
            p = m[5] ? str_repeat(c, x) : '';
            o.push(s + (m[4] ? a + p : p + a));
        } else {
            throw('Huh ?!');
        }
        f = f.substring(m[0].length);
    }
    return o.join('');
}

function sortObject(object)
{
    var newArr = new Array();
    var last = "";
    for (n in object)
    {
        last = "";
        for (i in object)
        {
            if ((last == '' || object[i] < last) && !newArr[i])
            {
                last = i;
            }
        }
        newArr[last] = object[last];
    }
    return newArr;
}

function var_dump(obj) {
    var out = typeof (obj) + ": \n";
    for (var i in obj) {
        out += i + ": " + obj[i] + "\n";
    }
    return out;
}

function readOnlyObserver(observable) {
    return ko.dependentObservable({
        read: observable,
        write: function () { }
    });
}


//
//  JavaScript hacks & additions
//

Math.stdRound = Math.round;
Math.round = function (number, precision)
{
    if (isNaN(number))
        return 0;
    if (precision != null)
    {
        precision = Math.abs(parseInt(precision)) || 0;
        var coefficient = Math.pow(10, precision);
        return Math.stdRound(number * coefficient) / coefficient;
    } else
    {
        return Math.stdRound(number);
    }
}

Object.elementCount = function (obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key))
            size++;
    }
    return size;
};

ko.bindingHandlers.autoNumeric = {
    init: function (el, valueAccessor, bindingsAccessor, viewModel) {
        var $el = $(el),
                bindings = bindingsAccessor(),
                settings = bindings.settings,
                value = valueAccessor();

        $el.autoNumeric(settings);
        $el.autoNumeric('set', parseFloat(ko.utils.unwrapObservable(value()), 10));
        $el.change(function () {
            value(parseFloat(FORM.getAutoNumericValue($el)));
        });
    },
    update: function (el, valueAccessor, bindingsAccessor, viewModel) {
        var $el = $(el),
                newValue = parseFloat(ko.utils.unwrapObservable(valueAccessor()), 10),
                elementValue = parseFloat(FORM.getAutoNumericValue($el)),
                valueHasChanged = (newValue !== elementValue);

        if ((newValue === 0) && (elementValue !== 0) && (elementValue !== "0")) {
            valueHasChanged = true;
        }

        if (valueHasChanged) {
            FORM.setAutoNumericValue($el, newValue);
            setTimeout(function () {
                $el.change()
            }, 0);
        }
    }
};

ko.bindingHandlers.readonly = {
    update: function (el, valueAccessor, bindingsAccessor, viewModel) {
        var newValue = ko.utils.unwrapObservable(valueAccessor());
        $(el).attr('readonly', newValue ? 'readonly' : null);
    }
};

ko.bindingHandlers.clean = {
    update: function (el, valueAccessor, allBindings) {
        var $el = $(el),
                shouldClean = ko.utils.unwrapObservable(valueAccessor()),
                value = allBindings.get('cleanValue') || '';

        if (shouldClean) {
            if ($el.hasClass('autonumeric')) {
                $el.autoNumeric('init').autoNumeric('set', value);
            } else {
                $el.val(value);
            }

            setTimeout(function () {
                $el.change()
            }, 0);
        }
    }
};

ko.bindingHandlers.tooltip = {
    init: function (element, valueAccessor) {
        var local = ko.utils.unwrapObservable(valueAccessor()),
                options = {};

        ko.utils.extend(options, ko.bindingHandlers.tooltip.options);
        ko.utils.extend(options, local);
        $(element).tooltip(options);

        ko.utils.domNodeDisposal.addDisposeCallback(element, function () {
            $(element).tooltip("destroy");
        });
    },
    options: {
        placement: "auto",
        trigger: "hover"
    }
};

//
//  DIB objekt - üldised funktsioonid
//

var DIB = {};

//Must be defined before DIB.* functions, because DIB.* is dependant of this AUTONUMERIC object
DIB.AUTONUMERIC = {
    formObject: null,
    /**
     * Cleans up FORM inputs that have autonumeric formatting, so that POST has clean values
     * @param form_object
     */
    beforeSubmit: function (form_object)
    {
        this.formObject = form_object;
        //clean up after autonumeric formatting
        var fields = form_object.find(".autonumeric").filter('input[type=text], input[type=hidden], input[type=tel], input:not([type])');
        $.each(fields, function (k, el) {
            var val;
            try {
                val = $(el).autoNumeric('get');
                $(el).autoNumeric('destroy');
                $(el).val(val);
            } catch (err) {

            }
        });
    },
    /**
     * Restored autonumeric formatting for inputs that have .autonumeric class
     * @param form_object
     */
    afterSubmit: function ()
    {
        //clean up after autonumeric formatting
        var fields = this.formObject.find(".autonumeric").filter('input[type=text], input[type=hidden], input[type=tel], input:not([type])');
        $.each(fields, function (k, el) {
            var $field = $(el);
            try {
                $field.autoNumeric('set', $field.val());
            } catch (err) {
                var val = $field.val();
                $field.autoNumeric('init');

                if ($.isNumeric(val)) {
                    $field.autoNumeric('set', val);
                }
            }
        });
    },
    getValue: function (field) {
        try {
            return $(field).autoNumeric('get');
        } catch (e) {
            return $(field).val();
        }
    },
    init: function (field) {
        var $field = field instanceof jQuery ? field : $(field);
        return $field.filter('input[type=text], input[type=hidden], input[type=tel], input:not([type])')
                .autoNumeric('init');
    }
};

DIB.changeTextCustomField = function (formFieldPrefix)
{
    if ($("#" + formFieldPrefix + "_type").val() == 'number' || $("#" + formFieldPrefix + "_type").val() == 'price')
    {
        $("#field_" + formFieldPrefix + "_min td div.label span.title").text(LOCALE.get('DIB.SYSTEMSETTINGS.Field.MinValue'));
        $("#field_" + formFieldPrefix + "_max td div.label span.title").text(LOCALE.get('DIB.SYSTEMSETTINGS.Field.MaxValue'));
        $("#field_" + formFieldPrefix + "_min td div.label span.field-info").attr('data-content', LOCALE.get('DIB.SYSTEMSETTINGS.Field.Info.MinValue'));
        $("#field_" + formFieldPrefix + "_max td div.label span.field-info").attr('data-content', LOCALE.get('DIB.SYSTEMSETTINGS.Field.Info.MaxValue'));
    } else if ($("#" + formFieldPrefix + "_type").val() == 'text')
    {
        $("#field_" + formFieldPrefix + "_min td div.label span.title").text(LOCALE.get('DIB.SYSTEMSETTINGS.Field.MinLength'));
        $("#field_" + formFieldPrefix + "_max td div.label span.title").text(LOCALE.get('DIB.SYSTEMSETTINGS.Field.MaxLength'));
        $("#field_" + formFieldPrefix + "_min td div.label span.field-info").attr('data-content', LOCALE.get('DIB.SYSTEMSETTINGS.Field.Info.MinLength'));
        $("#field_" + formFieldPrefix + "_max td div.label span.field-info").attr('data-content', LOCALE.get('DIB.SYSTEMSETTINGS.Field.Info.MaxLength'));
    } else if ($("#" + formFieldPrefix + "_type").val() == 'textarea')
    {
        $("#field_" + formFieldPrefix + "_max td div.label span.title").text(LOCALE.get('DIB.SYSTEMSETTINGS.Field.MaxLength'));
        $("#field_" + formFieldPrefix + "_max td div.label span.field-info").attr('data-content', LOCALE.get('DIB.SYSTEMSETTINGS.Field.Info.MaxLength'));
    }
};

DIB.fixupElements = function (dom_base, binding)
{
    FORM.fixupTextFields(dom_base);
    FORM.fixupRadioButtons();
    FORM.fixupCheckBoxes();
    FORM.fixupLabels();
    DIB.fixupStripes();
    DIB.fixupPopovers();
    DIB.initDropdowns();
    DIB.fixupAutocomplete();
    DIB.initSummernote();
    if (binding !== false) {
        DIB.refreshBindings();
    }
};

DIB.refreshBindings = function () {
    DIB.AUTONUMERIC.beforeSubmit($("#DIB-form"));
    $('.hasKOBinding').each(function () {
        ko.cleanNode(this);
        ko.applyBindings(ViewModel, this);
    });
    DIB.AUTONUMERIC.afterSubmit();
};

DIB.fixupAutocomplete = function ()
{
    $('.autocomplete').each(function ()
    {
        $(this).siblings('.ui-combobox').find('.ui-autocomplete-input').val($(this).find("option:selected").text());
    });
}

DIB.fixupStripes = function ()
{
    $('.table-striped').each(function ()
    {
        $(this).find("tbody").find("tr").removeClass("table-striped-row-dark").removeClass("table-striped-row-light");
        $(this).find("tbody").find("tr:visible:even").addClass("table-striped-row-light");
        $(this).find("tbody").find("tr:visible:odd").addClass("table-striped-row-dark");
    });
}

DIB.fixupPopovers = function ()
{
    // Using namespaced events not to bind same events again.
    $('[data-toggle="popover"]').off('.fixupPopoversEnter').
            on('mouseenter.fixupPopoversEnter', function ()
            {
                var style = $(this).attr('data-style');
                $(this).popover('show');

                if (typeof (style) !== 'undefined')
                {
                    $('.popover').addClass(style);
                }
            }).
            off('.fixupPopoversLeave').
            on('mouseleave.fixupPopoversLeave', function ()
            {
                $(this).popover('hide');
            });
};

DIB.scrollToElement = function (element_id)
{
    if ($('#' + element_id).length)
    {
        $('html, body').animate({
            scrollTop: $('#' + element_id).offset().top - 100
        }, 200);
    }
};

DIB.setScrollable = function ()
{
    var $windowWidth = $(window).width();
    var $listTableContainer = $('.auto-scroll');
    var $listTable = $listTableContainer.find('table');
    if ($listTable.width() + 42 - $windowWidth > 0) // $listTable.length>0 && 
    {
        $listTableContainer.addClass('scrollable');
    } else
    {
        $listTableContainer.removeClass('scrollable');
    }
};

DIB.redirect = function (url)
{
    window.location.href = url;
};

DIB.reload = function ()
{
    window.location.reload(true);
};

DIB.showMenu = function (element)
{
    $(element).closest('.btn-group').addClass('open');
};

DIB.hideMenu = function (element)
{
    $(element).closest('.btn-group').removeClass('open');
};

/**
 * Display message dialog.
 *
 * @param message
 * @param title
 * @param reloadOnClose
 * @param redirectOnClose
 * @param width
 */
DIB.showMessage = function (message, title, reloadOnClose, redirectOnClose, width, divId)
{
    title = title || LOCALE.get('DIB.SystemTitle');
    width = width || 500;

    if (divId) {
        $("#" + divId).remove();

    } else {
        $("#DIB_alert").remove();
        divId = "DIB_alert";
    }


    $('body').append('<div id="' + divId + '" title="' + title + '" style="display:none" >' + message + '</div>');
    var dialogElement = $("#" + divId);

    dialogElement.dialog({
        width: width,
        resizable: false,
        bgiframe: true,
        modal: true,
        classes: {
            "ui-dialog": "highlight"
        },
        buttons: {
            OK: {
                buttonClass: "primary",
                buttonAction: function () {
                    dialogElement.dialog('close');
                    dialogElement.remove();
                }
            }
        },
        close: function (event, ui) {
            if (reloadOnClose) {
                DIB.progressDialog(LOCALE.get('DIB.FORM.Msg.Saving'));
                DIB.reload();
            }

            if (redirectOnClose) {
                DIB.progressDialog(LOCALE.get('DIB.FORM.Msg.Saving'));
                DIB.redirect(redirectOnClose);
            }
        }
    });

    dialogElement.dialog('open');
    return true;
};

/**
 * Display message dialog on unsuccessful action
 *
 * @param message
 * @param title
 * @param reloadOnClose
 * @param redirectOnClose
 * @param width
 */
DIB.alert = function (message, title, reloadOnClose, redirectOnClose, width, divId)
{
    DIB.showMessage(message, title, reloadOnClose, redirectOnClose, width, divId);
};

/**
 * Display message dialog on successful action
 *
 * @param message
 * @param title
 * @param reloadOnClose
 * @param redirectOnClose
 * @param width
 */
DIB.success = function (message, title, reloadOnClose, redirectOnClose, width)
{
    DIB.showMessage(message, title, reloadOnClose, redirectOnClose, width);
};

DIB.confirmDialog = function (message, title, buttons)
{
    $("#DIB_confirmdialog").remove();
    $('body').append('<div id="DIB_confirmdialog" title="' + title + '" style="display:none">' + message + '</div>');
    $("#DIB_confirmdialog").dialog({
        width: '500px',
        resizable: false,
        bgiframe: true,
        modal: true,
        buttons: buttons
    });
    $("#DIB_confirmdialog").dialog('open');
    return true;
};

DIB.closeConfirmDialog = function ()
{
    if ($("#DIB_confirmdialog").hasClass('ui-dialog-content'))
    {
        $("#DIB_confirmdialog").dialog('close');
    }
    $("#DIB_confirmdialog").remove();
};

DIB.displayErrors = function (message, title, errors)
{
    $('.error').removeClass('error');

    var fieldErrors = false;
    var firstField = null;
    if (typeof errors !== 'undefined')
    {
        fieldErrors = true;
        for (var formElementID in errors)
        {
            if (firstField == null) {
                firstField = formElementID;
            }

            var $element = $('#' + formElementID);
            if (typeof $element != 'undefined') {
                $element.addClass('error');
                $element.closest('tbody').css('display', 'table-row-group');
            }

            var $fieldElement = $('#field_' + formElementID);
            var $radioListElement = $fieldElement.find('div.element.radiolist');
            if ($fieldElement.length && $radioListElement.length) {
                $radioListElement.addClass('error');
                $fieldElement.find('label.radio-button').addClass('error');
            }
        }
    }

    if (title == null || title == '')
    {
        title = LOCALE.get('DIB.SystemTitle');
    }
    $("#DIB-alert-errors").remove();
    $('body').append('<div id="DIB-alert-errors" title="' + title + '" style="display:none" class="alert alert-important">' + message + '</div>');
    $("#DIB-alert-errors").dialog({
        resizable: false,
        bgiframe: true,
        modal: true,
        width: '500px',
        buttons: {
            'OK': {
                buttonAction: function ()
                {
                    $("#DIB-alert-errors").remove();
                    if (fieldErrors && $("#" + firstField).parents().find(".dialogform").length == 0)
                        DIB.scrollToElement(firstField);
                    $("#DIB-alert-errors").dialog('close');

                },
                buttonClass: "primary"
            }
        }
    });
    $("#DIB-alert-errors").dialog('open');
};

DIB.openDialog = function (content_url, content_data, dialog_width)
{
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
    var dialog_tag = Math.floor(Math.random() * 100000 + 1);
    if (dialog_width == null)
        dialog_width = 600;
    if (content_data == null)
    {
        content_data = {'tag': dialog_tag};
    } else
    {
        content_data.tag = dialog_tag;
    }
    $.ajax({
        url: content_url,
        data: content_data,
        success: function (data)
        {
            DIB.closeProgressDialog();
            if (data != null && data.status == '1')
            {
                $('body').append('<div id="dialog_' + dialog_tag + '" style="display:none">' + data.content + '</div>');
                FORM.setDatePicker("#form_" + dialog_tag + " input:text.datefield");
                FORM.setMask("#form_" + dialog_tag);
                FORM.setAutoComplete("#form_" + dialog_tag + " select.autocomplete");
                var buttons = {};
                buttons[LOCALE.get('DIB.DIALOG.CloseWindow')] = {
                    buttonAction: function ()
                    {
                        $("#dialog_" + dialog_tag).dialog('close');
                    }
                };
                $("#dialog_" + dialog_tag).dialog({
                    title: (data.title != null ? data.title : LOCALE.get('DIB.SystemTitle')),
                    resizable: false,
                    bgiframe: true,
                    modal: true,
                    width: dialog_width + 'px',
                    buttons: buttons,
                    close: function (event, ui)
                    {
                        $("#dialog_" + dialog_tag).remove();
                    }
                });
                $("#dialog_" + dialog_tag).dialog('open');
                if (data.displaytrigger != null && data.displaytrigger != "")
                {
                    eval(data.displaytrigger);
                }
                DIB.centerDialog();
            } else
            {
                if (data != null && data.error != null)
                {
                    DIB.displayErrors(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                } else
                {
                    DIB.alert(LOCALE.get('DIB.FORM.ERROR.ErrorOpeningDialog'), LOCALE.get('DIB.COMMON.Whoops'));
                }
                return;
            }
        },
        error: function ()
        {
            DIB.closeProgressDialog();
            DIB.alert(LOCALE.get('DIB.FORM.ERROR.ErrorOpeningDialog'), LOCALE.get('DIB.COMMON.Whoops'));
            return;
        }
    });
};

DIB.openEditDialog = function (content_url, content_data, dialog_width, extra_buttons, no_standard_save, dialog_height)
{
    var dialog_height_value = dialog_height ? (dialog_height + 'px') : 'auto';
    var dialog_tag = Math.floor(Math.random() * 100000 + 1);
    if (dialog_width == null)
        dialog_width = 750;
    if (content_data == null)
    {
        content_data = {'tag': dialog_tag};
    } else
    {
        content_data.tag = dialog_tag;
    }

    DIB.progressDialog();

    $.ajax({
        url: content_url,
        data: content_data,
        type: "GET",
        success: function (data)
        {
            DIB.closeProgressDialog();
            if (data != null && data.status == '1')
            {
                $('body').append('<div id="dialog_' + dialog_tag + '" style="display:none">' + data.content + '</div>');
                var $dialogElement = $('#dialog_' + dialog_tag);

                var submitbuttontitle = LOCALE.get('DIB.FORM.Btn.Save');
                if (data.submit != null && data.submit != "")
                {
                    submitbuttontitle = data.submit;
                }
                var progressmessage = LOCALE.get('DIB.FORM.Msg.Saving');
                if (data.progressmessage != null && data.progressmessage != "")
                {
                    progressmessage = data.progressmessage;
                }
                var submithandler = null;
                if (data.submithandler != null && data.submithandler != "")
                {
                    submithandler = data.submithandler;
                }
                FORM.setDatePicker("#form_" + dialog_tag + " input:text.datefield");
                FORM.setMask("#form_" + dialog_tag);
                FORM.setAutoComplete("#form_" + dialog_tag + " select.autocomplete");
                DIB.fixupElements("#form_" + dialog_tag + " input");

                var buttons = {};
                if (no_standard_save == null || no_standard_save == false)
                {
                    buttons[submitbuttontitle] = {
                        buttonAction: function ()
                        {
                            if ($("#form_" + dialog_tag)[0].action == null || $("#form_" + dialog_tag)[0].action == "")
                            {
                                DIB.alert(LOCALE.get('DIB.FORM.Error.CouldNotFindForm'), LOCALE.get('DIB.COMMON.Whoops'));
                                return;
                            }
                            $("#form_" + dialog_tag + " td.label.error").removeClass('error');

                            // Custom submithandler?
                            if (submithandler != null)
                            {
                                eval(submithandler + '(\'' + dialog_tag + '\', \'' + progressmessage + '\')');
                            }

                            // Submit
                            else
                            {
                                // Progress
                                if (progressmessage != '')
                                {
                                    DIB.progressDialog(progressmessage);
                                }

                                // Do submit
                                $("#form_" + dialog_tag).ajaxSubmit({
                                    type: "post",
                                    data: {ajaxsubmit: '1'},
                                    beforeSerialize: function (form) {
                                        DIB.AUTONUMERIC.beforeSubmit(form);
                                        if (data.beforeSerializeHandler) {
                                            (new Function('form', data.beforeSerializeHandler))(form);
                                        }
                                    },
                                    success: function (data)
                                    {
                                        if (data != null && data.status == '1')
                                        {
                                            if (data.message != null && data.message != '')
                                            {
                                                DIB.closeProgressDialog();
                                                $dialogElement.dialog('close');

                                                if (data.redirect != null && data.redirect != '')
                                                {
                                                    DIB.alert(data.message, null, null, data.redirect);
                                                } else if (data.reload != null && data.reload == '1')
                                                {
                                                    DIB.alert(data.message, null, true);
                                                }
                                            } else if (data.redirect != null && data.redirect != '')
                                            {
                                                DIB.closeProgressDialog();
                                                $dialogElement.dialog('close');
                                                DIB.redirect(data.redirect);
                                            } else if (data.reload != null && data.reload == '1')
                                            {
                                                $dialogElement.dialog('close');
                                                DIB.reload();
                                            } else
                                            {
                                                DIB.closeProgressDialog();
                                                if (data.submitsuccessaction)
                                                {
                                                    eval(data.submitsuccessaction);
                                                }

                                                $dialogElement.dialog('close');
                                            }

                                        } else
                                        {
                                            DIB.closeProgressDialog();
                                            var errors = '<b>' + LOCALE.get('DIB.FORM.Error.Errors') + ':</b>';
                                            if (data != null && data.error != null)
                                            {
                                                if (typeof (data.error) == 'object')
                                                {
                                                    for (var fld_tag in data.error)
                                                    {
                                                        if (data.error.hasOwnProperty(fld_tag))
                                                        {
                                                            if (typeof (data.error[fld_tag]) == 'object') {
                                                                for (var message in data.error[fld_tag]) {
                                                                    errors = errors + '<br/> &#0149; ' + data.error[fld_tag][message];
                                                                }
                                                            } else {
                                                                errors = errors + '<br/> &#0149; ' + data.error[fld_tag];
                                                            }
                                                        }
                                                    }
                                                    DIB.displayErrors(errors, LOCALE.get('DIB.COMMON.Whoops'), data.error);
                                                } else
                                                {
                                                    reload = false;
                                                    if (data.reload != null && data.reload == '1')
                                                        reload = true;
                                                    DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'), reload);
                                                }
                                            } else
                                            {
                                                DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData') + ': 1', LOCALE.get('DIB.COMMON.Whoops'));
                                            }
                                            return;
                                        }
                                    },
                                    error: function (xhr, ajaxOptions, thrownError)
                                    {
                                        DIB.closeProgressDialog();
                                        DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData') + ': ' + thrownError + ' / ' + var_dump(xhr), LOCALE.get('DIB.COMMON.Whoops'));
                                        return;
                                    },
                                    complete: function () {
                                        DIB.AUTONUMERIC.afterSubmit();
                                    }
                                });
                            }
                        },
                        buttonClass: "primary"
                    };
                }
                if (extra_buttons != null)
                {
                    for (var k in extra_buttons)
                    {
                        buttons[k] = extra_buttons[k];
                    }
                }
                var cancelbuttontitle = LOCALE.get('DIB.FORM.Btn.Cancel');
                buttons[cancelbuttontitle] = {
                    buttonAction: function ()
                    {
                        $dialogElement.dialog('close');
                    }
                };

                $dialogElement.dialog({
                    title: (data.title != null ? data.title : LOCALE.get('DIB.SystemTitle')),
                    resizable: false,
                    bgiframe: true,
                    modal: true,
                    width: dialog_width + 'px',
                    height: dialog_height_value,
                    buttons: buttons,
                    close: function (event, ui)
                    {
                        $dialogElement.remove();
                    }
                });

                $dialogElement.dialog('open');
                if (data.displaytrigger != null && data.displaytrigger != "")
                {
                    eval(data.displaytrigger);
                }
                DIB.fixupElements();
                DIB.initPopover();
                DIB.initTooltips();
                DIB.centerDialog();
                if ($('.ui-dialog-buttonpane button.primary').length == 1)
                {
                    $('#form_' + dialog_tag + ' input,#form_' + dialog_tag + ' select').keypress(function (e)
                    {
                        if (e.which == 13) {
                            $('.ui-dialog-buttonpane button.primary').trigger('click');
                        }
                    });
                }
                if ($("#form_" + dialog_tag + " .defaultfocus").length)
                {
                    $("#form_" + dialog_tag + " .defaultfocus")[0].focus();
                } else
                {
                    if ($("#form_" + dialog_tag + " input").length)
                        $("#form_" + dialog_tag + " input")[0].focus();
                }

                $dialogElement.css('height', dialog_height_value);
            } else
            {
                if (data != null && data.error != null)
                {
                    if (typeof (data.error) == 'object')
                    {
                        DIB.displayErrors(data.error, LOCALE.get('DIB.COMMON.Whoops'), data.error);
                    } else
                    {
                        DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                    }
                } else
                {
                    DIB.alert(LOCALE.get('DIB.FORM.ERROR.ErrorOpeningDialog'), LOCALE.get('DIB.COMMON.Whoops'));
                }
                return;
            }
        },
        error: function ()
        {
            DIB.alert(LOCALE.get('DIB.FORM.ERROR.ErrorOpeningDialog'), LOCALE.get('DIB.COMMON.Whoops'));
            return;
        }
    });
};

DIB.progressDialog = function (message)
{
    $("#DIALOG-progress-content").text(message);

    $("#DIALOG-progress").dialog({
        dialogClass: 'progressdialog',
        closeOnEscape: false,
        resizable: false,
        bgiframe: true,
        modal: true,
        width: 550
    });
};

DIB.closeProgressDialog = function ()
{
    if ($("#DIALOG-progress").hasClass('ui-dialog-content'))
    {
        $("#DIALOG-progress").dialog('close');
    }
};

DIB.centerDialog = function ()
{
    $(":ui-dialog").each(function () {
        $(this).dialog('option', 'position', 'center');
    });
};

DIB.parseDate = function (txtDate)
{
    var objDate,  // date object initialized from the txtDate string
            mSeconds, // txtDate in milliseconds
            day,      // day
            month,    // month
            year;     // year
    // date length should be 10 characters (no more no less)
    if (txtDate.length !== 10) {
        return false;
    }
    // third and sixth character should be '.'
    if (txtDate.substring(2, 3) !== '.' || txtDate.substring(5, 6) !== '.') {
        return false;
    }
    // extract month, day and year from the txtDate (expected format is dd.mm.yyyy)
    // subtraction will cast variables to integer implicitly (needed
    // for !== comparing)
    day = txtDate.substring(0, 2) - 0;
    month = txtDate.substring(3, 5) - 1; // because months in JS start from 0
    year = txtDate.substring(6, 10) - 0;
    // test year range
    if (year < 1000 || year > 3000) {
        return false;
    }
    // convert txtDate to milliseconds
    mSeconds = (new Date(year, month, day)).getTime();
    // initialize Date() object from calculated milliseconds
    objDate = new Date();
    objDate.setTime(mSeconds);
    // compare input date and parts from Date() object
    // if difference exists then date isn't valid
    if (objDate.getFullYear() !== year || objDate.getMonth() !== month || objDate.getDate() !== day) {
        return false;
    }
    // otherwise return true
    return objDate;
};

DIB.doPostSubmit = function (form_url, form_data, confirm_message)
{
    if (confirm_message != null)
    {
        if (!confirm(confirm_message))
            return;
    }
    var form_tag = Math.floor(Math.random() * 100000 + 1);
    $('body').append('<form id="form_' + form_tag + '" method="post" action="' + form_url + '"></form>');
    for (var k in form_data)
    {
        $('#form_' + form_tag).append('<input type="hidden" name="' + k + '" value="' + form_data[k] + '" />');
    }
    window.localStorage.setItem('previousUrl', window.location.href);
    $('#form_' + form_tag)[0].submit();
};

DIB.doPostSubmitWithOptions = function (form_url, form_data, title, message, option_key, options, enable_cancel)
{
    if (title == null || title == '')
    {
        title = LOCALE.get('DIB.SystemTitle');
    }
    if (form_data == null)
    {
        form_data = {};
    }
    var buttons = {};
    $.each(options, function (o, button_title) {
        buttons[button_title] = {
            buttonAction: function ()
            {
                $("#DIB_alert").dialog('close');
                $("#DIB_alert").remove();
                form_data[option_key] = o;
                DIB.doPostSubmit(form_url, form_data);
            },
            buttonClass: "primary"
        }
    });
    if (enable_cancel != null && enable_cancel == true)
    {
        buttons[LOCALE.get('DIB.FORM.BTN.Cancel')] = {
            buttonACtion: function ()
            {
                $("#DIB_alert").dialog('close');
                $("#DIB_alert").remove();
            }
        }
    }
    $("#DIB_alert").remove();
    $('body').append('<div id="DIB_alert" title="' + title + '" style="display:none">' + message + '</div>');
    $("#DIB_alert").dialog({
        width: '500px',
        resizable: false,
        bgiframe: true,
        modal: true,
        buttons: buttons
    });
};

/**
 * 
 * @param action_url
 * @param action_data
 * @param confirm_message
 * @param reload_on_success
 * @param progressmessage
 * @param reload_on_close
 */
DIB.doAjaxAction = function (action_url, action_data, confirm_message, reload_on_success, progressmessage, reload_on_close)
{
    if (confirm_message != null)
    {
        if (!confirm(confirm_message))
            return;
    }
    if (progressmessage == null)
    {
        var progressmessage = LOCALE.get('DIB.FORM.MSG.Saving');
    }
    DIB.progressDialog(progressmessage);
    $.ajax(
            {
                url: action_url,
                data: action_data,
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        if (data.redirect != null)
                        {
                            DIB.redirect(data.redirect);
                        } else if ((data.reload != null && data.reload == '1') || reload_on_success == null || reload_on_success == true)
                        {
                            DIB.reload();
                        } else
                        {
                            DIB.closeProgressDialog();
                        }
                    } else
                    {
                        DIB.closeProgressDialog();
                        if (data != null && data.error != null)
                        {
                            if (reload_on_close == true)
                            {
                                DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'), true);
                            } else
                            {
                                DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                            }
                        } else
                        {
                            if (reload_on_close == true)
                            {
                                DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'), true);
                            } else
                            {
                                DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                            }

                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.closeProgressDialog();
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
};

DIB.doAjaxActionWithOptions = function (action_url, action_data, title, message, option_key, options, enable_cancel, reload_on_success, progressmessage)
{
    if (title == null || title == '')
    {
        title = LOCALE.get('DIB.SystemTitle');
    }
    if (action_data == null)
    {
        action_data = {};
    }
    var buttons = {};
    $.each(options, function (o, button_title) {
        buttons[button_title] = {
            buttonAction: function ()
            {
                $("#DIB_alert").dialog('close');
                $("#DIB_alert").remove();
                action_data[option_key] = o;
                DIB.doAjaxAction(action_url, action_data, null, reload_on_success, progressmessage);
            },
            buttonClass: "primary"
        }
    });
    if (enable_cancel != null && enable_cancel == true)
    {
        buttons[LOCALE.get('DIB.FORM.BTN.Cancel')] = {
            buttonAction: function ()
            {
                $("#DIB_alert").dialog('close');
                $("#DIB_alert").remove();
            }
        }
    }
    $("#DIB_alert").remove();
    $('body').append('<div id="DIB_alert" title="' + title + '" style="display:none">' + message + '</div>');
    $("#DIB_alert").dialog({
        width: '500px',
        resizable: false,
        bgiframe: true,
        modal: true,
        buttons: buttons
    });
};

DIB.initTooltips = function ()
{
    var $tooltips = $('[data-toggle="tooltip"]');
    if (!$tooltips.length) {
        return;
    }
    $tooltips.tooltip(
            {
                delay:
                        {
                            show: 500,
                            hide: 100
                        },
                placement: 'bottom'
            });
};

DIB.initPopover = function ()
{
    var $popovers = $('[data-toggle="popover"]');
    if (!$popovers.length) {
        return;
    }
    $popovers.popover({
        container: 'body',
        html: true
    });
};

DIB.initDropdowns = function ( )
{
    var theTimer = 0;
    var theElement = null;
    var theLastPosition = {x: 0, y: 0};

    $('[data-toggle]').closest('div').filter(function () {
        return $(this).parents('.summernote, .note-editor').length <= 0;
    }).off('.initDropdownsEnter').
            on('mouseenter.initDropdownsEnter', function (inEvent)
            {
                if (theElement)
                    theElement.removeClass('open');
                window.clearTimeout(theTimer);
                theElement = $(this);
                theTimer = window.setTimeout(function ()
                {
                    theElement.addClass('open');
                }, 50);
            }).off('.initDropdownsMove').
            on('mousemove.initDropdownsMove', function (inEvent)
            {
                theElement = $(this);
                if (Math.abs(theLastPosition.x - inEvent.ScreenX) > 4 || Math.abs(theLastPosition.y - inEvent.ScreenY) > 4)
                {
                    theLastPosition.x = inEvent.ScreenX;
                    theLastPosition.y = inEvent.ScreenY;
                    return;
                }

                if (theElement.hasClass('open'))
                    return;

                window.clearTimeout(theTimer);
                theTimer = window.setTimeout(function ()
                {
                    theElement.addClass('open');
                }, 100);
            }).off('.initDropdownsLeave').
            on('mouseleave.initDropdownsLeave', function (inEvent)
            {
                window.clearTimeout(theTimer);
                theElement = $(this);
                theTimer = window.setTimeout(function ()
                {
                    theElement.removeClass('open');
                }, 50);
            });

    // Open dropdowns right away
    $('.btn-group').filter(function () {
        return $(this).parents('.summernote, .note-editor').length <= 0;
    }).off('.initDropdownsBtnGroupsEnter').
            on('mouseenter.initDropdownsBtnGroupsEnter', function ()
            {
                $(this).closest('.btn-group').addClass('open');
            }).off('.initDropdownsBtnGroupsLeave').
            on('mouseleave.initDropdownsBtnGroupsLeave', function ()
            {
                $(this).closest('.btn-group').removeClass('open');
            });
};
DIB.initSummernote = function () {
    if ($('.summernote').length > 0) {
        $('.summernote').summernote({
            height: 220,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link']],
                ['misc', ['codeview']]
            ]
        });
    }
};


DIB.videoPlayer = null;
DIB.videoOverlayLeft = null;

DIB.showVideo = function (source, title)
{
    var title = title || '';

    if ($('#DIB_help_video').length <= 0) {
        $('body').append('<div id="DIB_help_video" title="' + title + '" style="display:none"><video id="DIB-tutorial-video" class="video-js vjs-DIB-skin" controls'
                + ' preload="auto" width="848px" style="width:848px;">'
                + '<p class="vjs-no-js">'
                + 'To view this video please enable JavaScript, and consider upgrading to a web browser'
                + ' that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>'
                + '</p>'
                + '</video></div>');
        //Mixpanel tracking
    } else {
        $("#DIB_help_video").dialog('open');
        DIB.videoPlayer.play();
        return;
    }

    videojs('DIB-tutorial-video').src(source);
    $('#DIB-video-modal-title').html(title);

    // When the video is loaded
    videojs('DIB-tutorial-video').ready(function ()
    {
        DIB.videoPlayer = this;
        DIB.videoOverlayLeft = '50%';
        $(document).keyup(function (e)
        {
            if (e.keyCode == 27) {
                player.pause();
            }
        });

        // Initialize resizeVideoJS()
        DIB.resizeVideo();

        // On resize call resizeVideo()
        window.onresize = DIB.resizeVideo;

        $("#DIB_help_video").dialog({
            width: '900px',
            resizable: false,
            bgiframe: true,
            modal: true,
            close: function (event, ui) {
                DIB.videoPlayer.pause();
            },
            open: function (event, ui) {
                DIB.videoPlayer.play();
                mixpanel.track('Video - ' + title);
            }
        });
    });
};

DIB.resizeVideo = function ()
{
    // Window size calculations
    var screenWidth = 0;
    if ($('#DIB-video-modal > .modal-body').length > 0) {
        screenWidth = $('#DIB-video-modal > .modal-body').width() - 40;
    } else {
        screenWidth = $(document).width() - 40;
    }
    if (screenWidth > 900)
        screenWidth = 850;
    var videoWidth = 800;
    var videoHeight = 449;
    var videoHeightCalculated = videoHeight / videoWidth * screenWidth;
    var videoWidthCalculated = videoWidth / videoHeight * videoHeightCalculated;

    // calculate new video overlay
    DIB.videoOverlayLeft = ((videoHeightCalculated / videoWidthCalculated * 100) + 2) + '%';
    $('#video-overlay').css('left', DIB.videoOverlayLeft);

    $('.video-content').width(videoWidthCalculated).height(videoHeightCalculated);
    DIB.videoPlayer.width(videoWidthCalculated).height(videoHeightCalculated);

    // Modal
    $('#DIB-video-modal .modal-dialog').attr('style', 'display:block;width: ' + videoWidthCalculated + 'px; height: ' + videoHeightCalculated + 'px;');
};


var ViewModel = {};

//
//  FORM objekt - vormihaldus
//

var FORM = {};

FORM.toggleInstallmentRows = function (inst_tag)
{
    var rows = document.getElementsByClassName("subinstallment_" + inst_tag);
    for (var i = 0; i < rows.length; i++) {
        if (rows[i].style.display == 'none') {
            rows[i].style.display = '';
            document.getElementById("icon_arrow_" + inst_tag).className = "icon-arrow-down";
        } else
        {
            rows[i].style.display = 'none';
            document.getElementById("icon_arrow_" + inst_tag).className = "icon-arrow-closed2";
        }
    }
}

FORM.copyValue = function (fromfield, tofield)
{
    document.getElementById(tofield).value = document.getElementById(fromfield).value;

}
FORM.toggle = function (tag, toggle_hide, toggle_show, primary_name, secondary_name)
{
    if ($(toggle_show).is(":visible"))
    {
        $(toggle_hide).hide();
        $("#field_" + tag).find("button").text(primary_name);
    } else
    {
        $(toggle_show).show();
        $("#field_" + tag).find("button").text(secondary_name);
    }
}

FORM.checkPastDate = function (date_issue_field)
{
    var date_issue = $(date_issue_field).datepicker('getDate');
    var today = new Date();

    if (date_issue < today)
    {
        if (!(date_issue.getDate() == today.getDate() && date_issue.getMonth() == today.getMonth() && date_issue.getFullYear() == today.getFullYear()))
        {

            $(date_issue_field + "_comment").html('<span class="text-warning icon-alert" data-style="warning">' + LOCALE.get('DIB.POLICY.warning.PastDate') + '</span>');
        } else
        {
            $(date_issue_field + "_comment").html('');
        }
    } else
    {
        $(date_issue_field + "_comment").html('');
    }
}

FORM.putRequired = function (field_required, field_check, true_values, label)
{
    var value = null;
    if (field_check != null)
    {
        if ($(field_check).attr('type') == 'checkbox')
        {
            value = ($(field_check).is(':checked') ? '1' : '0');
        } else if ($(field_check).attr('type') == 'radio')
        {
            value = $(field_check + ':checked').val();
        } else
        {
            value = $(field_check).val();
        }
    }
    if ($.inArray(value, true_values) > -1)
    {
        $(field_required).parents("tr").find(".label").html("<span class='text-danger icon-asterix'></span>" + label)
    } else
    {
        $(field_required).parents("tr").find(".label").html(label)
    }
};

FORM.submit = function (form_action, form_id, success_callback, progressmessage, extraData, actionUrl)
{
    // Init
    if (form_action == null)
    {
        form_action = 'save';
    }
    if (form_id == null)
    {
        form_id = 'DIB-form';
    }

    if (extraData === null || typeof extraData !== 'object') {
        extraData = {};
    }

    // Check
    if ($("#" + form_id)[0].action == null || $("#" + form_id)[0].action == "")
    {
        DIB.alert(LOCALE.get('DIB.FORM.Error.CouldNotFindForm'), LOCALE.get('DIB.COMMON.Whoops'));
        return;
    }

    // Clear
    $("#" + form_id + " .errors").remove();
    $("#" + form_id + " td.label.error").removeClass('error');

    // Progress
    if (progressmessage == null)
    {
        progressmessage = LOCALE.get('DIB.COMMON.Progress.Save');
    }
    if (progressmessage != '')
    {
        DIB.progressDialog(progressmessage);
    }

    var ajaxOptions = {
        data: $.extend({ajaxsubmit: '1', action: form_action}, extraData),
        beforeSerialize: function (form) {
            DIB.AUTONUMERIC.beforeSubmit(form)
        },
        success: function (data)
        {
            if (data != null && data.status == '1')
            {
                window.localStorage.removeItem('previousUrl');
                if (success_callback != null)
                {
                    DIB.closeProgressDialog();
                    success_callback(data);
                } else if (data.reload != null && data.reload == '1')
                {
                    DIB.reload();
                } else if (data.redirect != null && data.redirect.length > 0)
                {
                    DIB.redirect(data.redirect);
                } else
                {
                    DIB.closeProgressDialog();
                }
            } else
            {
                DIB.closeProgressDialog();
                var errors = '<b>' + LOCALE.get('DIB.FORM.Error.Errors') + ':</b>';
                if (data != null && data.error != null)
                {
                    if (typeof (data.error) == 'object')
                    {
                        for (var fld_tag in data.error)
                        {
                            if (data.error.hasOwnProperty(fld_tag))
                            {
                                errors = errors + '<br/> &#0149; ' + data.error[fld_tag];
                            }
                        }
                        DIB.displayErrors(errors, LOCALE.get('DIB.COMMON.Whoops'), data.error);
                    } else
                    {
                        DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                    }
                } else
                {
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                }

                return;
            }
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
            DIB.closeProgressDialog();
            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData') + ' - ' + thrownError + ' - ' + xhr.responseText, LOCALE.get('DIB.COMMON.Whoops'));
            return;
        },
        complete: function () {
            DIB.AUTONUMERIC.afterSubmit();
        }
    };

    if (actionUrl) {
        ajaxOptions.url = actionUrl;
    }

    $("#" + form_id).ajaxSubmit(ajaxOptions);

    return $("#" + form_id).data('jqxhr');

    // Prevent default
    /*
     if (jQuery.browser.msie) event.returnValue = false;
     if(event.preventDefault) event.preventDefault();
     if(event.stopPropagation) event.stopPropagation();
     */
};

FORM.submitOfferFields = function (fields, offerOid) {
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Saving'));
    $("#DIB-form").ajaxSubmit({
        url: '/offer/submitofferfields',
        data: {
            fields: fields,
            offer_oid: offerOid
        },
        success: function (data) {
            DIB.closeProgressDialog();
            if (data.status != 1) {
                var error = data.error ? data.error : LOCALE.get('DIB.COMMON.ErrorSavingData');
                DIB.alert(error, LOCALE.get('DIB.common.error'));
            }
        },
        error: function () {
            DIB.closeProgressDialog();
            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.common.error'));
        }
    });
}

FORM.cancel = function ()
{
    var previousUrl = window.localStorage.getItem('previousUrl');
    if (previousUrl != '' && previousUrl != null)
    {
        window.localStorage.removeItem('previousUrl');
        window.location.href = previousUrl;
    } else
        window.history.go(-1);
};

FORM.hideFields = function (hide_fields)
{
    if (hide_fields != '')
    {
        $(hide_fields).hide();
    }
};

/**
 *
 * @param hide_fields - field class to hide
 * @param show_fields - dynamic class with concatenated value-trigger , default - field class to show (example: claimyes)
 * @param field - field from where it takes the value that will be used in second parameter to determine when to show/hide
 */
FORM.showFields = function (hide_fields, show_fields, field)
{
    if (field != null && $(field).length == 0)
        return;
    if (hide_fields != '')
    {
        $(hide_fields).hide();
    }
    if (field != null)
    {
        if ($(field).attr('type') == 'checkbox')
        {
            show_fields = show_fields.replace('$val', ($(field).is(':checked') ? '1' : '0'));
            show_fields = show_fields.replace('$empty', ($(field).is(':checked') ? 'notempty' : 'empty'));
        } else if ($(field).attr('type') == 'radio')
        {
            show_fields = show_fields.replace('$val', $(field + ':checked').val());
            show_fields = show_fields.replace('$empty', ($(field + ':checked').length > 0 ? 'notempty' : 'empty'));
        } else
        {
            show_fields = show_fields.replace('$val', $(field).val());
            show_fields = show_fields.replace('$empty', (($(field).val().length == 0 || ($(field).get(0).tagName == 'SELECT' && $(field).val() == '0')) ? 'empty' : 'notempty'));
        }
    }
    if (show_fields != '') {
        $(show_fields).show(0);
    }
    if (hide_fields != '')
    {
        $(hide_fields + " input[type=text]").each(function () {
            if ($(this).closest("tr").css('display') == 'none' && $(this).attr('data-keephiddenvalue') != '1')
            {
                $(this).val($(this).attr('data-emptyformat'));
            }
        });
        $(hide_fields + " textarea").each(function () {
            if ($(this).closest("tr").css('display') == 'none' && $(this).attr('data-keephiddenvalue') != '1')
            {
                $(this).val($(this).attr('data-emptyformat'));
            }
        });
        $(hide_fields + " select").each(function () {
            if ($(this).closest("tr").css('display') == 'none' && $(this).attr('data-keephiddenvalue') != '1')
            {
                if ($(this).attr('data-emptyformat') != null && $(this).attr('data-emptyformat') != '')
                {
                    $(this).val($(this).attr('data-emptyformat'));
                } else if ($(this).attr('data-default-value')) {
                    $(this).val($(this).attr('data-default-value'));
                } else
                {
                    $(this).val($('option:first', this).attr('value'));
                }
            }
        });
        $(hide_fields + " input[type=checkbox]").each(function () {
            if ($(this).closest("tr").css('display') == 'none' && $(this).attr('data-keephiddenvalue') != '1')
            {
                $(this).removeAttr('checked');
            }
        });
        $(hide_fields + " .radiolist input[type=radio]").each(function () {
            if ($(this).closest("tr").css('display') == 'none' && $(this).attr('data-keephiddenvalue') != '1') {
                if ($(this).val() == $(this).attr('data-default-value')) {
                    $(this).prop('checked', true);
                }
            }
        });
    }
    DIB.centerDialog();
};

// FORM.showErrors = function ( errors )
// {
// 	for (var k in errors)
// 	{
// 		// console.log(k);
// 		$('#'+k).addClass('error');
// 	}
// 	// DEBUG
// 	console.dir(errors);
// };

FORM.changeDropDownWithOther = function (field_tag, other_field_tag, other_field_value)
{
    var field = $("#" + field_tag);
    if (field.val() == other_field_value)
    {
        $("#" + other_field_tag).removeAttr('disabled');
        if (!field.hasClass('autocomplete')) {
            $("#" + other_field_tag)[0].focus();
        }
    } else
    {
        $("#" + other_field_tag).attr('disabled', 'disabled');
        $("#" + other_field_tag).val('');
    }
};

FORM.updateCurrency = function (field_tag)
{
    $(".currencylabel").text($("#" + field_tag).val());
}
FORM.setMask = function (form_filter)
{
    $((form_filter != null ? form_filter + " " : "") + " input[data-mask!='']").each(function () {
        $(this).mask($(this).attr('data-mask'));
    });
}

FORM.setDatePicker = function (date_fields_filter , defaultSetting ={})
{


    if (date_fields_filter == null)
    {
        date_fields_filter = 'input:text.datefield:not(.startThisYear):not([readonly=readonly])';
        date_fields_thisYear_filter = 'input:text.datefield.startThisYear:not([readonly=readonly])';
    }

    date_fields = $(date_fields_filter);
    date_fields_thisYear = $(date_fields_thisYear_filter);

    if (date_fields.size())
    {
        var currentDate = new Date();
        var defaultRange = "1900:" + (currentDate.getFullYear() + 10);

        date_fields.datepicker($.extend(
                {}, null, {
            showOn: "button",
            showAnim: "fadeIn",
            duration: "fast",
            buttonImage: "../public/Images/icon-calendarpicker.png",
            buttonImageOnly: true,
            showButtonPanel: false,
            changeMonth: true,
            changeYear: true,
            yearRange: defaultRange
        },defaultSetting)).attr("maxlength", "10");


        // 2.0 EE date quickentry
        if (LOCALE.datepicker != null && LOCALE.datepicker == 'dd.mm.yy')
        {
            date_fields.keyup(function () {
                var Date = this.value;
                if (Date.match(/^[0-9]+$/) && Date.length == 6)
                {
                    if ((Date.charAt(4) + Date.charAt(5)) < 50)
                    {
                        var Century = 20;
                    } else
                    {
                        var Century = 19;
                    }
                    this.value = (Date.charAt(0) + Date.charAt(1) + "." + Date.charAt(2) + Date.charAt(3) + "." + Century + Date.charAt(4) + Date.charAt(5));
                }
            });
        }
    }
    date_fields.mask(LOCALE.maskedinput);
};

FORM.setAutoComplete = function (select_fields_filter)
{
    if (select_fields_filter == null)
    {
        select_fields_filter = 'select.autocomplete:not([readonly=readonly])';
    }
    if (!$(select_fields_filter).length) {
        return;
    }
    $(select_fields_filter).css('display', 'none');
    $(select_fields_filter).combobox();
};

FORM.loadOptions = function (select_name, data, selected_option)
{
    var option = '';
    for (var key in data)
    {
        option += '<option value="' + ((key.indexOf("#_") > -1) ? key.replace("#_", "") : key) + '"' + (key == selected_option ? ' selected="selected"' : '') + '>' + data[key] + '</option>';
    }
    $(select_name + ' option').remove();
    $(select_name).html(option);
};

FORM.setValues = function (values, base_field)
{
    for (var k in values)
    {
        if (base_field != null && base_field != '')
        {
            if (base_field == 'prop' && k.match(/^prop_/))
            {
                var fld = k;
            } else
            {
                var fld = base_field + "_" + k;
            }
        } else
        {
            var fld = k;
        }
        if ($("select[name=" + fld + "]").length)
        {
            if (values[k] != null && typeof (values[k]) == 'object')
            {
                FORM.loadOptions("select[name=" + fld + "]", sortObject(values[k]), $("select[name=" + fld + "]").val());
            } else
            {
                $("#" + fld).val(values[k]);
            }
        } else if ($("input[name=" + fld + "]").attr('type') == 'radio')
        {
            //$("#"+fld+"_"+values[k]).attr('checked','checked');
            $("#" + fld + "_" + values[k]).trigger("click");
        } else if ($("input[name=" + fld + "]").attr('type') == 'checkbox')
        {
            if (values[k] == '1')
            {
                $("#" + fld).attr('checked', 'checked');
            } else
            {
                $("#" + fld).removeAttr('checked');
            }
        } else if ($("input[name='" + fld + "[]']").attr('type') == 'checkbox')
        {
            if (values[k] != null && values[k] != '')
            {
                var itens = values[k].split("	");

                for (var i in itens)
                {
                    if ($("#" + fld + "_" + itens[i]).val() == itens[i])
                    {
                        $("#" + fld + "_" + itens[i]).attr('checked', 'checked');
                    } else
                    {
                        $("#" + fld + "_" + itens[i]).removeAttr('checked');
                    }
                }
            }
        } else
        {
            $("#" + fld).val(values[k]);
        }
    }

    DIB.fixupElements();
};

FORM.toggleFieldGroup = function (fieldgroup)
{
    if ($("#fieldgroup_" + fieldgroup).is(':visible'))
    {
        $("#fieldgroup_" + fieldgroup).hide();
        $("#header_" + fieldgroup).parent().find('.icon-fieldgroup').removeClass('icon-fieldgroup-open');
        $("#header_" + fieldgroup).parent().find('.icon-fieldgroup').addClass('icon-fieldgroup-closed');
        var hidden = '1';
    } else
    {
        $("#fieldgroup_" + fieldgroup).show();
        $("#header_" + fieldgroup).parent().find('.icon-fieldgroup').removeClass('icon-fieldgroup-closed');
        $("#header_" + fieldgroup).parent().find('.icon-fieldgroup').addClass('icon-fieldgroup-open');
        var hidden = '0';
    }
    $.ajax({
        url: "/ajax/dashboard/module",
        data: "module=fieldgroup_" + encodeURIComponent(fieldgroup) + "&hidden=" + encodeURIComponent(hidden),
        success: function (data) {},
        error: function (data) {}
    });
};

FORM.toggleShowAll = function ()
{
    if ($("#broker_person_oid_showall").is(':checked'))
    {
        $("#broker_person_oid").html($("#broker_person_oid_fulllist").html());
    } else
    {
        $("#broker_person_oid").html($("#broker_person_oid_shortlist").html());
    }
};

FORM.toUpperCase = function (field_tag)
{
    $(field_tag).val($(field_tag).val().toUpperCase());
};

FORM.toLowerCase = function (field_tag)
{
    $(field_tag).val($(field_tag).val().toLowerCase());
};

FORM.toNameCase = function (field_tag)
{
    var name = $(field_tag).val();

    // Convert "Name Name"
    var pieces = name.split(" ");
    for (var i = 0; i < pieces.length; i++)
    {
        var j = pieces[i].charAt(0).toUpperCase();
        pieces[i] = j + pieces[i].substr(1);
    }
    name = pieces.join(" ");

    // Convert "Name-Name"
    var pieces = name.split("-");
    for (var i = 0; i < pieces.length; i++)
    {
        var j = pieces[i].charAt(0).toUpperCase();
        pieces[i] = j + pieces[i].substr(1);
    }
    name = pieces.join("-");

    $(field_tag).val(name);
};

FORM.fixupTextFields = function (dom_base)
{
    if (typeof dom_base == 'undefined') {
        dom_base = '';
    }
    // Using namespaced events not to bind same event again.
    $(dom_base + ":text").off('.fixupTextFields').
            on('change.fixupTextFields', function () {
                // Trim
                this.value = $.trim(this.value);

                // Numbriväljad patchime ära
                if ($(this).hasClass('numberfield') && !$(this).hasClass('autonumeric'))
                {
                    this.value = this.value.replace(' ', '');
                    this.value = this.value.replace(',', '.');
                }
            });
};

FORM.fixupRadioButtons = function ()
{
    $('input[type=radio]').each(function () {

        if ($(this).is(":checked"))
        {
            $(this).parent().find('span').removeClass('icon-radio-empty').addClass('icon-radio');
        } else
        {
            $(this).parent().find('span').removeClass('icon-radio').addClass('icon-radio-empty');
        }
    });

    // Using namespaced events not to bind same event again.
    $('.radio-button').off('.fixupRadioButtons').
            on('click.fixupRadioButtons', function ()
            {
                $("input[name=" + $(this).find("input:not([readonly])").attr("name") + "]").each(function () {
                    if ($(this).is(":checked"))
                    {
                        $(this).parent().find('span').removeClass('icon-radio-empty').addClass('icon-radio');
                    } else
                    {
                        $(this).parent().find('span').removeClass('icon-radio').addClass('icon-radio-empty');

                    }
                });
            });
};

FORM.fixupCheckBoxes = function ()
{
    $('input[type=checkbox]').each(function () {
        if ($(this).attr("checked"))
        {
            $(this).parent().find('.icon-check-empty').addClass('icon-check');
        } else
        {
            $(this).parent().find('.icon-check-empty').removeClass('icon-check');
        }

        if ($(this).attr("disabled"))
        {
            $(this).parent().hover().css("cursor", "not-allowed");
            //text color
            $(this).parent().css("color", "#BBBBBB");
            //icon color hack
            $(this).parent().find('.icon-check-empty').addClass("icon-check-disabled");
        } else
        {
            $(this).parent().hover().css("cursor", "pointer");
            $(this).parent().css("color", "#575757 !important");
            $(this).parent().find('.icon-check-empty').removeClass("icon-check-disabled");


        }

    });


};

FORM.fixupLabels = function () {
    $('.full-height').each(function () {
        var $element = $(this);
        $element.height(
                $element.height('auto').parent().height() - 8
                );
    });
};

FORM.checklistSelectAll = function (field_tag)
{
    if ($('input[name="' + field_tag + '[]"]:checked').length < $('input[name="' + field_tag + '[]"]').length)
    {
        $('input[name="' + field_tag + '[]"]').attr('checked', 'checked').parent().find('span.icon-check-empty').addClass("icon-check");
    } else
    {
        $('input[name="' + field_tag + '[]"]').removeAttr('checked').parent().find('span.icon-check-empty').removeClass("icon-check");
    }
};


FORM.setDataBinding = function (selector, action, binding) {
    if ($(selector).length === 0) {
        return;
    }
    binding = _.replace(binding, /\r?\n|\r/g, '\\n');
    $(selector).each(function (id, item) {
        var bindings = [], $item = $(item);

        if ($item.attr('data-bind')) {
            bindings = ko.expressionRewriting.parseObjectLiteral($item.attr('data-bind'));
        }
        bindings.push({'key': action, 'value': binding});
        bindings = _.reverse(_.uniqBy(_.reverse(_.filter(bindings, function (v) {
            return v.key !== "_ko_property_writers"
        })), 'key'));
        $item.attr('data-bind', ko.expressionRewriting.preProcessBindings(bindings)).addClass('hasKOBinding');
    });
};

FORM.setViewModelObserver = function (key, observer) {
    if (!_.has(ViewModel, key))
    {
        _.set(ViewModel, key, observer);
    }
};

FORM.getValue = function (query) {
    var $elem = $(query);

    if (!$elem.length) {
        return null;
    }

    try {
        return FORM.getAutoNumericValue($elem);
    } catch (e) {
        switch ($elem.attr('type')) {
            case 'radio':
                return $elem.filter(':checked').val();
            default:
                return $elem.val();
        }
    }
};

FORM.getAutoNumericValue = function ($element) {
    if (typeof anumeric !== 'undefined' && anumeric.isManagedByAutoNumeric($element[0])) {
        return anumeric.getAutoNumericElement($element[0]).getNumber();
    }

    if (typeof $element.autoNumeric !== 'undefined') {
        return $element.autoNumeric('get');
    }

    throw new Error('not autonumeric field');
};

FORM.setAutoNumericValue = function ($element, value) {
    if (typeof anumeric !== 'undefined' && anumeric.isManagedByAutoNumeric($element[0])) {
        var aNumericElement = anumeric.getAutoNumericElement($element[0]);
        aNumericElement.set(value);
    }

    if (typeof $element.autoNumeric !== 'undefined') {
        $element.autoNumeric('set', value);
    }
};

FORM.validateFile = function (input, maxSize, maxSizeExeededMessage) {
    //validate against each file (multi-select)
    var flag = true;
    for (var i = 0; i < input[0].files.length; i++) {
        //If file is too large
        if (input[0].files[i].size / 1000000 > maxSize) {
            $(input).clearFields();

            DIB.displayErrors(maxSizeExeededMessage, LOCALE.get('DIB.COMMON.whoops'));
            flag = false;
            if (!flag) {
                return false;
            }

        }
    }
    return flag;
};

//
//  Tabbed content
//

var TAB = {};

TAB.loaded = new Array();

/**
 * @param {String} tab_id
 * @param {String|NULL} content_url
 * @param {Integer} isContentCached
 */
TAB.select = function (tab_id, content_url, isContentCached)
{
    isContentCached = 'undefined' === typeof isContentCached ? 1 : isContentCached;

    // Show tab
    $('.tabcontent').hide();
    $('#content_' + tab_id).show();
    $(".nav-tabs li").removeClass('active');
    $('a .icon-active').remove();
    $('#tab_' + tab_id + ' a').append('<span class="icon-active" style="margin-left:-' + ($('#tab_' + tab_id).width() / 2 + 10) + 'px;"></span>');
    $('#tab_' + tab_id).addClass('active');

    // URL
    var URL = document.location.toString();
    if (URL.match('#'))
    {
        var URLbase = URL.split('#')[0];
        document.location.href = URLbase + '#' + tab_id;
    } else
    {
        document.location.href = URL + '#' + tab_id;
    }

    // Load content
    if (content_url == null && $("#content_" + tab_id).attr('rel') != undefined && $("#content_" + tab_id).attr('rel') != '')
    {
        content_url = $("#content_" + tab_id).attr('rel');
    } else if (content_url == null && $("#content_" + tab_id).attr('data-redirect') != undefined && $("#content_" + tab_id).attr('data-redirect') != '')
    {
        DIB.redirect($("#content_" + tab_id).attr('data-redirect'));
        return;
    }
    if (content_url != null)
    {
        // Already loaded
        if (1 === isContentCached && TAB.loaded[tab_id] != null && TAB.loaded[tab_id] == 1) {
            DIB.fixupStripes();
            return;
        }

        // Loading
        $("#content_" + tab_id).html('<div class="loading"><span class="size-3 loader"></span></div>');

        // Get content
        $.ajax(
                {
                    url: content_url,
                    data: "",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data)
                    {
                        if (data != null && data.status == '1')
                        {
                            $("#content_" + tab_id).hide().html(data.content).fadeIn(200);
                            TAB.loaded[tab_id] = 1;
                            DIB.initPopover();
                            DIB.initTooltips();
                            DIB.initDropdowns();
                            DIB.fixupElements();
                            //Log datatable is redraw
                            if (tab_id == 'log') {
                                setTimeout(function () {
                                    customerLogTable.columns.adjust().draw();
                                }, 400);
                            } else if (tab_id == 'document') {
                                setTimeout(function () {
                                    customerdocumentTable.columns.adjust().draw();
                                }, 400);
                            } else if (tab_id == 'brokingslip') {
                                setTimeout(function () {
                                    customerbrokinglistTable.columns.adjust().draw();
                                }, 400);
                            } else if (tab_id == 'quotes') {
                                setTimeout(function () {
                                    customerQuoteTable.columns.adjust().draw();
                                }, 400);
                            } else if (tab_id == 'crm') {
                                setTimeout(function () {
                                    quoterequestTable.columns.adjust().draw();
                                }, 400);
                            } else if (tab_id == 'endorsement') {
                                setTimeout(function () {
                                    endorsementlistTable.columns.adjust().draw();
                                }, 400);
                            } else if (tab_id == 'policy') {
                                setTimeout(function () {
                                    policyTable.columns.adjust().draw();
                                }, 400);
                            }
                            else if (tab_id == 'claims') {
                                setTimeout(function () {
                                    claimTable.columns.adjust().draw();
                                }, 400);
                            }
                                else if (tab_id == 'payment') {
                                setTimeout(function () {
                                    paymentTable.columns.adjust().draw();
                                }, 400);
                            }

                        } else
                        {
                            if (data.error != null && data.error != '')
                            {
                                $("#content_" + tab_id).html('<div class="alert alert-danger">' + data.error + '</div>');
                            } else
                            {
                                $("#content_" + tab_id).html('<div class="alert alert-danger">' + LOCALE.get('dib.common.ErrorReadingData') + '</div>');
                            }
                            return;
                        }
                    }
                    ,
                    error: function ()
                    {
                        $("#content_" + tab_id).html('<div class="alert alert-danger">' + LOCALE.get('dib.common.ErrorReadingData') + '</div>');
                        return;
                    }
                });
    }
};

TAB.reload = function (tabId, url) {
    this.loaded = 0;
    this.select(tabId, url);
};

//
//	Log
//

var LOG = {};

LOG.toggleData = function (id)
{
    if (id != null)
    {
        var $icon = $('#' + id).prev().find('span.icon-arrow-closed,span.icon-arrow-open');
        if ($icon.attr('class') == 'icon-arrow-closed')
        {
            $icon.attr('class', 'icon-arrow-open');
        } else
        {
            $icon.attr('class', 'icon-arrow-closed');
        }
        $('#' + id).toggle();
    } else
    {
        $('.logrow').show();
        $('#content_log table span').attr('class', 'icon-arrow-open');
    }

};


//
//  Block module
//

BLOCK = {};

BLOCK.toggle = function (element)
{
    var $panel = $(element).closest('.panel');
    $panel.find('.panel-body').fadeToggle(200);
    $panel.toggleClass('open').toggleClass('closed');
    var module_id = $panel.find('.panel-body').attr('id');
    var hidden = ($panel.hasClass('open') ? '1' : '0');
    if (hidden)
        DIB.fixupStripes();

    $.ajax({
        url: "/helper/toggleblock",
        data: "module=" + encodeURIComponent(module_id) + "&hidden=" + encodeURIComponent(hidden),
        success: function (data) {},
        error: function (data) {}
    });
};


//
//List module
//

LIST = {};

LIST.toggle = function (element, clicked)
{
    var panel = $("#" + element);
    panel.fadeToggle(200);
    panel.toggleClass('open').toggleClass('closed');
    var module_id = panel.attr('id');
    var hidden = (panel.hasClass('open') ? '1' : '0');
    if (hidden)
        DIB.fixupStripes();

    $('#' + clicked).css('background-image', 'url(../Images/blockaction-' + (hidden == '1' ? 'open' : 'close') + '.png)');

    $.ajax({
        url: "/helper/toggleblock",
        data: "module=" + encodeURIComponent(module_id) + "&hidden=" + encodeURIComponent(hidden),
        success: function (data) {},
        error: function (data) {}
    });
};

/**
 * Find and initialize events for "back to top" button.
 * See DIB_LIST::setShowBackToTopButton().
 */
LIST.initBackToTopButton = function ()
{
    if (!$("#back-to-top-button").length) {
        return;
    }

    // Attach scroll listener
    $(window).scroll(function () {
        if ($(window).scrollTop() > $(window).height() - 400) {
            $("#back-to-top-button:hidden").fadeIn("medium");
        } else {
            $("#back-to-top-button:visible").fadeOut("medium");
        }
    });

    // Attach click event
    $("#back-to-top-button").on("click", function (e) {
        $("html, body").animate({scrollTop: 0}, 400);
        e.preventDefault();
    });
};

LIST.toggleMassSelect = function (checkbox)
{
    if (checkbox.is(':checked')) {
        $('.select_row_item').attr('checked', 'checked');
    } else {
        $('.select_row_item').removeAttr('checked');
    }
    DIB.fixupElements();
};

//
//	Filter
//

FILTER = {};

FILTER.toggle = function (filter_id)
{
    var $filter = $('#filter-' + filter_id);
    var extendedfilters = $filter.attr('data-extendedfilters');
    $filter.slideToggle('fast', function ()
    {
        $filter.closest('.panel').find('.icon-filter').toggleClass('open');
        $filter.toggleClass('open');
        $('#filter-' + filter_id + ' input[autofocus]').focus();

        var hidden = ($filter.hasClass('open') ? '0' : '1');

        $.ajax({
            url: "/helper/toggleblock",
            data: "module=" + encodeURIComponent('filter_' + filter_id) + "&hidden=" + encodeURIComponent(hidden),
            success: function (data) {},
            error: function (data) {}
        });
    });
};

FILTER.toggleExtended = function (filter_id)
{
    var $filter = $('#filter-' + filter_id);
    var hidden = ($filter.find('.extend-filter').hasClass('hidden') ? 1 : 0);
    $filter.find('.extend-filter').toggleClass('hidden');

    var linkTitle = LOCALE.get('DIB.LIST.Filter.ShowAll');
    if (hidden == 1)
        linkTitle = LOCALE.get('DIB.LIST.Filter.HideExtended');
    $('.show-extended-filters a').text(linkTitle);

    $.ajax({
        url: "/helper/togglefilter",
        data: "filter=" + encodeURIComponent(filter_id) + "&visibility=" + encodeURIComponent(hidden),
        success: function (data) {},
        error: function (data) {}
    });
};




//
//  Passwords
//

PASSWORD = {};

PASSWORD.suggestPassword = function (field_tag)
{
    // Generate password
    var password = PASSWORD.generatePassword();
    while (!PASSWORD.isSecure(password))
    {
        password = PASSWORD.generatePassword();
    }

    // Set
    $("#" + field_tag).val(password);
    $("#" + field_tag + "_repeat").val(password);

    // Show
    DIB.alert(LOCALE.get('DIB.FORM.Password.SuggestPassword.Result.1') + ': <b>' + password + '</b><br/>' + LOCALE.get('DIB.FORM.Password.SuggestPassword.Result.2'));
};

PASSWORD.generatePassword = function ()
{
    var password = '';
    var symbols = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz987654321";
    for (var b = 0; b < 4; b++)
    {
        if (b > 0)
            password += '-';
        for (var i = 0; i < 3; i++)
        {
            var symbol = symbols[(Math.floor(Math.random() * symbols.length))];
            password += symbol;
        }
    }
    return password;
};

PASSWORD.isSecure = function (password)
{
    var StrongPass = /^(?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9])\S{5,}$/;
    var exp = new RegExp(StrongPass);
    return exp.test(password);
};

//
//  Range filter
//

FILTERRANGE = {};
FILTERRANGE.update = function (tag) {
    $('input[name="filter_' + tag + '"]').val($('#filter_' + tag + '_from').val() + '||' + $('#filter_' + tag + '_to').val());
};

//
//  Checklist filter
//

FILTERCHECKLIST = {};

FILTERCHECKLIST.openFilter = function (field_tag, title, options, inverseFilter)
{
    // Olemasolevad väärtused
    var selected = $("input[name=filter_" + field_tag + "]").val().split(',');

    // Teeme HTMLi
    var filtercontent = '<div id="maakler_filter" style="overflow-x:hidden;overflow-y:auto;" class="panel-body"><table style="background: #EEEEEE; width: 100%" class="blocklist">';
    filtercontent = filtercontent + '<tr>';
    var c = 1;
    for (var o in options)
    {
        c++;
        if (c == 2)
        {
            filtercontent = filtercontent + '</tr>';
            filtercontent = filtercontent + '<tr>';
            c = 0;
        }
        filtercontent = filtercontent + '<td style="width: 50%; padding-left: 7px;"><label for="filteroption_' + o + '"><input type="checkbox" style="margin-right: 7px" id="filteroption_' + o + '" rel="filteroption_' + field_tag + '" value="' + o + '"' + ($.inArray(o, selected) != -1 ? 'checked="checked"' : '') + '><span class="icon-check-empty' + ($.inArray(o, selected) != -1 ? ' icon-check' : '') + '"></span>' + options[o] + '</label></td>';
    }
    filtercontent = filtercontent + '</tr>';
    filtercontent = filtercontent + '</table></div>';

    // Buttons
    var buttons = {};
    buttons[LOCALE.get('DIB.LIST.Filter.ApplySelection')] = {
        buttonAction: function ()
        {
            var filterval = '';
            var filtertext = '';
            if ($("input[rel=filteroption_" + field_tag + "]:checked").length < $("input[rel=filteroption_" + field_tag + "]").length || inverseFilter)
            {
                $("input[rel=filteroption_" + field_tag + "]:checked").each(function () {
                    if (filterval != '')
                    {
                        filterval = filterval + ',';
                        filtertext = filtertext + ', ';
                    }
                    filterval = filterval + $(this).attr('value');
                    filtertext = filtertext + options[$(this).attr('value')];
                });
            }
            if (filterval == '')
            {
                filtertext = inverseFilter ? LOCALE.get('DIB.COMMON.NoneSelected') : LOCALE.get('DIB.COMMON.All');
                filtertext = '--- ' + filtertext + ' ---'
            }
            $("input[name=filter_" + field_tag + "]").val(filterval).change();
            $("input[name=filter_" + field_tag + "_disp]").val(filtertext);
            $(this).dialog('close');
            $("#maakler_filter").remove();
        },
        buttonClass: "primary"
    };
    buttons[LOCALE.get('DIB.LIST.Filter.ToggleAll')] = {
        buttonAction: function ()
        {
            if ($("input[rel=filteroption_" + field_tag + "]:checked").length == $("input[rel=filteroption_" + field_tag + "]").length)
            {
                $("input[rel=filteroption_" + field_tag + "]").removeAttr('checked');
            } else
            {
                $("input[rel=filteroption_" + field_tag + "]").attr('checked', 'checked');
            }
            DIB.fixupElements();
        }
    };

    // Dialog
    $("#maakler_filter").remove();
    $('body').append(filtercontent);
    $("#maakler_filter").dialog({
        width: '800px',
        title: title,
        resizable: false,
        bgiframe: true,
        modal: true,
        buttons: buttons,
        close: function (event, ui) {
            $("#maakler_filter").remove();
        }
    });
    $("#maakler_filter").dialog('open');
    $('#maakler_filter').css('max-height', $(window).height() - 260);
    DIB.centerDialog();
};


//
//  The Chooser
//

CHOOSER = {};

CHOOSER.url = new String();
CHOOSER.optional_params = {};
CHOOSER.optional_input_html = new String();

CHOOSER.openDialog = function (url, window_title, searchfor_title, width)
{
    this.url = url;
    if (width == null)
    {
        width = 750;
    }
    $("#DIALOG-chooser").dialog({
        title: window_title,
        resizable: false,
        bgiframe: true,
        modal: true,
        width: width
    });
    $("#chooser_search").val('');


    $("#DIALOG-chooser-result").empty();
    $("#DIALOG-chooser").dialog('open');
    if (searchfor_title != null)
    {
        $("#DIALOG-chooser-searchfor").html('<div class="label">' + searchfor_title + ':</div>');
    } else
    {
        $("#DIALOG-chooser-searchfor").html('<div class="label">' + searchfor_title + ':</div>');
    }
    if (CHOOSER.optional_input_html.length > 1)
    {
        //Remove previously set rows, in case dialog is opened twice in a single request.
        $(".quicksearchtable-row-custom").remove();

        $(".quicksearchtable-row:last").after(CHOOSER.optional_input_html);
    }



    $("#chooser_search")[0].focus();
};

CHOOSER.search = function ()
{
    var searchval = $("#chooser_search").val();
    var paymentoidval = $("#payment_oid").val();

    //Create params object and extend it with optional parameters
    var params = {};
    params.search = searchval;
    params.payment_oid = paymentoidval;
    $.extend(params, CHOOSER.optional_params);

    //Add ability to define custom variables in outside of the chooser's main scope. For reference, see CHOOSEINVOICE.openDialog
    if ($('.quicksearchtable-row-custom').length > 0)
    {
        var customparams = {};
        $('.quicksearchtable-row-custom input').each(function ()
        {
            var inputname = $(this).attr('name');
            if ($(this).attr('type') == 'checkbox')
            {
                var inputvalue = $(this).is(':checked');
            } else
            {
                var inputvalue = $(this).val();
            }
            customparams[inputname] = inputvalue;
        });

        $.extend(params, customparams);
    }

    if (searchval == "")
        return;

    $("#DIALOG-chooser-result").html('<span class="loader spin"></span>');
    DIB.centerDialog();
    $.ajax(
            {
                url: this.url,
                data: params,
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        $("#DIALOG-chooser-result").html(data.content);
                        DIB.centerDialog();
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            $("#DIALOG-chooser-result").empty();
                            DIB.alert(data.error);
                        } else
                        {
                            $("#DIALOG-chooser-result").empty();
                            DIB.alert(LOCALE.get('DIB.QUICKSEARCH.Error'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    $("#DIALOG-chooser-result").empty();
                    DIB.alert(LOCALE.get('DIB.QUICKSEARCH.Error'));
                    return;
                }
            });
};

CHOOSER.searchKeyPress = function (evt)
{
    if ((jQuery.browser.msie && event.keyCode == 13) || evt.which == 13)
    {
        CHOOSER.search();
        if (jQuery.browser.msie)
            evt.returnValue = false;
        else
            evt.preventDefault();
        return;
    }
};

CHOOSER.closeDialog = function ()
{
    $("#DIALOG-chooser").dialog('close');
};


//
//  INLINE search objekt
//

INLINESEARCH = {};

INLINESEARCH.searchKeypress = function (searchField, searchType, searchSubtype, baseField, evt, params)
{
    if ((jQuery.browser.msie && event.keyCode == 13) || evt.which == 13) {
        INLINESEARCH.search(searchField, searchType, searchSubtype, baseField, params);

        if (jQuery.browser.msie) {
            evt.returnValue = false;
        } else {
            evt.preventDefault();
        }

        return;
    }
};

INLINESEARCH.search = function (searchField, searchType, searchSubtype, baseField, params)
{
    var $searchFieldElement = $("#" + searchField);
    var searchValue = $searchFieldElement.val();
    params = params || '{}';

    params = JSON.parse(params);
    if (!params.hasOwnProperty('minimum_length')) {
        params.minimum_length = 3;
    }

    if (searchValue == "") {
        return;
    }

    if (searchValue.length < params.minimum_length) {
        DIB.alert(LOCALE.get('DIB.INLINESEARCH.Error.MinLength').replace('%d', params.minimum_length));
        return;
    }

    $searchFieldElement.val('');

    if ($("input[name$='customer_oid']").length) {
        var customer_oid = $("input[name$='customer_oid']").val();
    } else {
        var customer_oid = '0';
    }

    // Search dialog
    $('body').append('<div id="DIB_inlinesearch" style="padding: 20px 15px 10px 15px; display:none"><span class="loader spin"></span></div>');
    var buttons = {};
    buttons[LOCALE.get('DIB.FORM.BTN.Cancel')] = {
        buttonAction: function () {
            $("#DIB_inlinesearch").dialog('close');
        },
        buttonClass: "primary"
    };
    $("#DIB_inlinesearch").dialog({
        title: LOCALE.get('DIB.QUICKSEARCH.Search'),
        resizable: false,
        bgiframe: true,
        modal: true,
        width: 800,
        buttons: buttons,
        close: function (event, ui) {
            $("#DIB_inlinesearch").remove();
        }
    });

    $.ajax({
        url: '/helper/inlinesearch',
        data: "type=" + searchType + "&search_field=" + searchField + "&subtype=" + encodeURIComponent(searchSubtype) + "&search=" + encodeURIComponent(searchValue) + "&customer_oid=" + encodeURIComponent(customer_oid) + "&base_field=" + encodeURIComponent(baseField) + "&minimum_length=" + encodeURIComponent(params.minimum_length) + (params.load_function ? "&load_function=" + encodeURIComponent(params.load_function) : ''),
        success: function (data) {
            if (data != null && data.status == '1') {
                $("#DIB_inlinesearch").html(data.content);
                DIB.centerDialog();
            } else {
                if (data != null && data.error != null) {
                    $("#DIB_inlinesearch").html('<div class="alert alert-danger" style="margin-top: 10px">' + data.error + '</div>');
                    DIB.centerDialog();
                } else {
                    $("#DIB_inlinesearch").html('<div class="alert alert-danger" style="margin-top: 10px">' + LOCALE.get('DIB.QUICKSEARCH.Error') + '</div>');
                    DIB.centerDialog();
                }

                return;
            }
        },
        error: function () {
            $("#DIB_inlinesearch").html('<div class="alert alert-danger" style="margin-top: 10px">' + LOCALE.get('DIB.QUICKSEARCH.Error') + '</div>');
            DIB.centerDialog();

            return;
        }
    });
};

INLINESEARCH.close = function ()
{
    if ($("#DIB_inlinesearch").length)
    {
        $("#DIB_inlinesearch").dialog('close');
    }
};


//
//  UI language selection
//

// TODO: põhimõtteliselt pole vaja enam
LANGUAGE = {};

LANGUAGE.openDialog = function ()
{
    $("#DIALOG-langsel").dialog({
        title: LOCALE.get('DIB.LANGUAGE.SelectLanguage'),
        resizable: false,
        bgiframe: true,
        modal: true,
        width: '350px'
    });
    $("#DIALOG-langsel").dialog('open');
};

LANGUAGE.switchLanguage = function (lang)
{
    DIB.doAjaxAction('/helper/switchlang', {lang: lang}, null, true);
};

//
//	COVERHOLDER JS
//

COVERHOLDER = {};

COVERHOLDER.showGrossPremiumEdit = function (product_tags)
{
    $.each(product_tags, function (index, product_tag) {
        $("#sync_prop_coverholder_addition_" + product_tag).bind('keyup', function () {
            COVERHOLDER.showGrossPremiumPercentageSum(product_tag);
        });

        if ($("#prop_coverholder_premium_edited").is(':checked')) {
            //Show original gross premium and reset percentage calculation
            $("#prop_legal_pmt_lloyds_1_after_" + product_tag).val(parseFloat($("#prop_legal_pmt_lloyds_1_orig_" + product_tag).val()));
            $(".premium_edit").show();
        } else {
            //If checkbox was clicked to hide percentage, reset value to empty
            $("#sync_prop_coverholder_addition_" + product_tag).val('');
            $(".premium_edit").hide();
        }
    });
};

COVERHOLDER.showGrossPremiumPercentageSum = function (product_tag)
{
    var grosspremium = parseFloat($("#prop_" + product_tag + "_pmt_lloyds_1_orig").val());
    var percentage = parseFloat($("#sync_prop_coverholder_addition_" + product_tag).val());

    var sum = grosspremium + (grosspremium * (percentage / 100));

    if (isNaN(sum))
    {
        sum = grosspremium;
    }

    $("#prop_" + product_tag + "_pmt_lloyds_1_after").val(sum.toFixed(2));
};

//
//  Reports
//

REPORT = new Object();

REPORT.toggleBrokerSelection = function (field_tag)
{
    if ($("#" + field_tag).val() == 'list')
    {
        $("#" + field_tag + "_list").show();
        DIB.fixupElements();
    } else
    {
        $("#" + field_tag + "_list").hide();
        DIB.fixupElements();
    }
}


//
//  Märkused
//

NOTES = {};

NOTES.addNote = function (ref_oid, note_type)
{
    var type = (typeof (note_type) === 'undefined') ? '1' : note_type,
            title = LOCALE.get('DIB.NOTES.DIALOG.Title'),
            popup = $('#add_note_template_' + type).clone(true, true);

    $("#DIB_addnote").remove();

    $('body').append($(popup).addClass('hasKOBinding').attr('title', title).attr('id', 'DIB_addnote'));

    ViewModel.multiFileData = ko.observable({
        fileArray: ko.observableArray(),
    });
    ViewModel.onClear = function (fileData) {
        if (confirm('Are you sure?')) {
            fileData.clear && fileData.clear();
        }
    };
    ViewModel.noteAddEntry = ko.observable('');
    DIB.refreshBindings();

    var buttons = {};

    buttons[LOCALE.get('DIB.NOTES.Action.Add')] = {
        buttonAction: function ()
        {
            if (ViewModel.noteAddEntry() === '' && ViewModel.multiFileData().fileArray().length === 0) {
                DIB.displayErrors(LOCALE.get('DIB.NOTES.Error.Empty'), LOCALE.get('DIB.COMMON.Whoops'));
                return;
            }

            var formData = new FormData(),
                    files = ViewModel.multiFileData().fileArray(),
                    noteEntry = ViewModel.noteAddEntry();

            for (var x = 0; x < files.length; x++) {
                formData.append('attachment[]', files[x]);
            }

            formData.append('note_entry', noteEntry);
            formData.append('ref_oid', ref_oid);
            formData.append('note_type', type);
            formData.append('files', files.length);

            DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Save'));

            $.ajax(
                    {
                        url: '/helper/addnote',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data)
                        {
                            DIB.closeProgressDialog();
                            popup.dialog('close').remove();

                            if (data != null && data.status == '1') {
                                data.documents = $.map(data.documents, function (value, index) {
                                    return [value];
                                });

                                window.notes.unshift(data);
                            } else {
                                if (data != null && data.error != null)
                                {
                                    DIB.displayErrors(data.error, LOCALE.get('DIB.COMMON.Whoops'), data.error);
                                } else
                                {
                                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                                }
                                return;
                            }
                        }
                        ,
                        error: function ()
                        {
                            DIB.closeProgressDialog();
                            DIB.alert(
                                    LOCALE.get('DIB.COMMON.ErrorSavingData'),
                                    LOCALE.get('DIB.COMMON.Whoops')
                                    );

                            return;
                        }
                    });
        },
        buttonClass: "primary"
    };
    buttons[LOCALE.get('DIB.FORM.BTN.Cancel')] = {
        buttonAction: function ()
        {
            $("#DIB_addnote").dialog('close');
            $("#DIB_addnote").remove();
        }
    };
    $("#DIB_addnote").dialog({
        width: '500px',
        resizable: false,
        bgiframe: true,
        modal: true,
        buttons: buttons
    });
    $("#DIB_addnote").dialog('open');
};

NOTES.editNote = function (note_oid) {
    $("#DIB_editnote").remove();
    var noteText = $('li[data-note-id="' + note_oid + '"]').find('div.note-content').text();
    $('body').append('<div id="DIB_editnote" title="' + LOCALE.get('DIB.NOTES.Action.Edit') + '" style="display:none;"><textarea class="note_add_entry full-width" id="note_add_entry" name="note_add_entry" rows="10" wrap="soft">' + noteText + '</textarea></div>');
    var buttons = {};
    buttons[LOCALE.get('DIB.NOTES.Action.Edit')] = {
        buttonAction: function () {
            if ($("#note_add_entry").val() == "") {
                DIB.displayErrors(LOCALE.get('DIB.NOTES.Error.Empty'), LOCALE.get('DIB.COMMON.Whoops'));
                return;
            }
            $.ajax(
                    {
                        url: '/helper/editnote',
                        data: "note_oid=" + encodeURIComponent(note_oid) + "&note_entry=" + encodeURIComponent($("#note_add_entry").val()),
                        success: function (data) {
                            if (data != null && data.status == '1') {
                                DIB.reload();
                            } else {
                                if (data != null && data.error != null) {
                                    DIB.displayErrors(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                                } else {
                                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                                }
                                return;
                            }
                        },
                        error: function () {
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                            return;
                        }
                    });
        },
        buttonClass: "primary"
    };
    buttons[LOCALE.get('DIB.FORM.BTN.Cancel')] = {
        buttonAction: function () {
            $("#DIB_editnote").dialog('close').remove();
        }
    };
    $("#DIB_editnote").dialog({
        width: '500px',
        resizable: false,
        bgiframe: true,
        modal: true,
        buttons: buttons
    }).dialog('open');
};

NOTES.deleteNote = function (note_oid)
{
    if (!confirm(LOCALE.get('DIB.NOTES.Action.Delete.Confirm')))
        return;
    $.ajax(
            {
                url: '/helper/deletenote',
                data: "note_oid=" + encodeURIComponent(note_oid),
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        $('.note[data-note-id="' + note_oid + '"]').slideUp();
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.displayErrors(data.error, LOCALE.get('DIB.COMMON.Whoops'), data.error);
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
};

//
// DIB COOKIES jummy
//

DIB.COOKIE = {};

DIB.COOKIE.create = function (name, value, days)
{
    var expires;

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
}

DIB.COOKIE.read = function (name)
{
    var nameEQ = encodeURIComponent(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ')
            c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0)
            return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
};

DIB.COOKIE.delete = function (name)
{
    DIB.COOKIE.create(name, "", -1);
};

var SESSION = {};
SESSION.storage = null;
SESSION.fail = false;
SESSION.uid = null;
try {
    SESSION.uid = new Date;
    (SESSION.storage = window.sessionStorage).setItem(SESSION.uid, SESSION.uid);
    SESSION.fail = SESSION.storage.getItem(SESSION.uid) != SESSION.uid;
    SESSION.storage.removeItem(SESSION.uid);
    SESSION.fail && (SESSION.storage = false);
} catch (exception) {
    SESSION.fail = true;
}

SESSION.get = function (name) {
    if (SESSION.fail === true)
        return null;
    return SESSION.storage.getItem(name);
};

SESSION.set = function (name, value) {
    if (SESSION.fail === true)
        return null;
    return SESSION.storage.setItem(name, value);
};

SESSION.remove = function (name) {
    if (SESSION.fail === true)
        return null;
    return SESSION.storage.removeItem(name);
};

SESSION.clear = function () {
    if (SESSION.fail === true)
        return null;
    return SESSION.storage.clear();
};
DIB.SUMMERNOTE = {};
DIB.SUMMERNOTE.toField = function (fieldName, fieldClass) {
    var summerNoteClass = '.summernote';
    if (fieldClass) {
        summerNoteClass += '.' + fieldClass;
    }
    var code = $(summerNoteClass).code();
    var field = $('input[name="' + fieldName + '"]');
    if (field.length > 0) {
        field.val(code);
    }

    return true;
};

DIB.SUMMERNOTE.initForm = function (fieldName, fieldClass) {
    var summerNoteClass = '.summernote';
    if (fieldClass) {
        summerNoteClass += '.' + fieldClass;
    }
    var field = $('input[name="' + fieldName + '"]');
    var summer = $(summerNoteClass);
    if (field.length > 0 && summer.length > 0)
        summer.code(field.val());
};
DIB.SUMMERNOTE.submitWidget = function (dialog_tag, progressmessage) {
    DIB.SUMMERNOTE.toField('mail_content');
    if (progressmessage != '')
    {
        DIB.progressDialog(progressmessage);
    }
    // Do submit
    $("#form_" + dialog_tag).ajaxSubmit({
        type: "post",
        data: {ajaxsubmit: '1'},
        success: function (data)
        {
            if (data != null && data.status == '1')
            {
                if (data.message != null && data.message != '')
                {
                    DIB.closeProgressDialog();
                    $("#dialog_" + dialog_tag).dialog('close');
                    if (data.redirect != null && data.redirect != '')
                    {
                        DIB.alert(data.message, null, null, data.redirect);
                    } else if (data.reload != null && data.reload == '1')
                    {
                        DIB.alert(data.message, null, true);
                    }
                } else if (data.redirect != null && data.redirect != '')
                {
                    $("#dialog_" + dialog_tag).dialog('close');
                    DIB.redirect(data.redirect);
                } else if (data.reload != null && data.reload == '1')
                {
                    $("#dialog_" + dialog_tag).dialog('close');
                    DIB.reload();
                } else
                {
                    DIB.closeProgressDialog();
                    if (data.submitsuccessaction)
                    {
                        eval(data.submitsuccessaction);
                    }
                    $("#dialog_" + dialog_tag).dialog('close');
                }

            } else
            {
                DIB.closeProgressDialog();
                var errors = '<b>' + LOCALE.get('DIB.FORM.Error.Errors') + ':</b>';
                if (data != null && data.error != null)
                {
                    if (typeof (data.error) == 'object')
                    {
                        for (var fld_tag in data.error)
                        {
                            if (data.error.hasOwnProperty(fld_tag))
                            {
                                errors = errors + '<br/> &#0149; ' + data.error[fld_tag];
                            }
                        }
                        DIB.displayErrors(errors, LOCALE.get('DIB.COMMON.Whoops'), data.error);
                    } else
                    {
                        reload = false;
                        if (data.reload != null && data.reload == '1')
                            reload = true;
                        DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'), reload);
                    }
                } else
                {
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData') + ': 1', LOCALE.get('DIB.COMMON.Whoops'));
                }
                return;
            }
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
            DIB.closeProgressDialog();
            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData') + ': ' + thrownError + ' / ' + var_dump(xhr), LOCALE.get('DIB.COMMON.Whoops'));
            return;
        }
    });
};

/**
 * @see \DIB\Form\Field::setConditionalField() method
 *
 * @param field
 * @param showFieldTag
 * @param showValue
 */
window.FORM.simpleShowField = function (field, showFieldTag, showValue) {

    var $affectedField = $('#' + 'field_' + showFieldTag);
    var toggle;

    switch (field.type) {
        case 'checkbox':
            var values = $(field)
                    .closest('.checklist')
                    .find('input[type=checkbox]:checked')
                    .map(function (_, el) {
                        return $(el).val();
                    })
                    .get();

            toggle = values !== null && values.indexOf(showValue) !== -1;

            break;
        case 'select-multiple':
            if ($(field).hasClass('chosen')) {
                var values = $(field).chosen().val();
                toggle = values !== null && values.indexOf(showValue) !== -1;
            }
            break;
        default:
            toggle = field.value === showValue;
    }

    $affectedField.toggle(toggle);
};

window.FORM.queueReportSuccess = function () {
    TAB.select('files', null, null);
};

!function (a) {
    "use strict";
    function b(a) {
        var b = {};
        if (void 0 === a.selectionStart) {
            a.focus();
            var c = document.selection.createRange();
            b.length = c.text.length, c.moveStart("character", -a.value.length), b.end = c.text.length, b.start = b.end - b.length
        } else
            b.start = a.selectionStart, b.end = a.selectionEnd, b.length = b.end - b.start;
        return b
    }
    function c(a, b, c) {
        if (void 0 === a.selectionStart) {
            a.focus();
            var d = a.createTextRange();
            d.collapse(!0), d.moveEnd("character", c), d.moveStart("character", b), d.select()
        } else
            a.selectionStart = b, a.selectionEnd = c
    }
    function d(b, c) {
        a.each(c, function (a, d) {
            "function" == typeof d ? c[a] = d(b, c, a) : "function" == typeof b.autoNumeric[d] && (c[a] = b.autoNumeric[d](b, c, a))
        })
    }
    function e(a, b) {
        "string" == typeof a[b] && (a[b] *= 1)
    }
    function f(a, b) {
        d(a, b), b.tagList = ["b", "caption", "cite", "code", "dd", "del", "div", "dfn", "dt", "em", "h1", "h2", "h3", "h4", "h5", "h6", "ins", "kdb", "label", "li", "output", "p", "q", "s", "sample", "span", "strong", "td", "th", "u", "var"];
        var c = b.vMax.toString().split("."), f = b.vMin || 0 === b.vMin ? b.vMin.toString().split(".") : [];
        if (e(b, "vMax"), e(b, "vMin"), e(b, "mDec"), b.mDec = "CHF" === b.mRound ? "2" : b.mDec, b.allowLeading = !0, b.aNeg = b.vMin < 0 ? "-" : "", c[0] = c[0].replace("-", ""), f[0] = f[0].replace("-", ""), b.mInt = Math.max(c[0].length, f[0].length, 1), null === b.mDec) {
            var g = 0, h = 0;
            c[1] && (g = c[1].length), f[1] && (h = f[1].length), b.mDec = Math.max(g, h)
        }
        null === b.altDec && b.mDec > 0 && ("." === b.aDec && "," !== b.aSep ? b.altDec = "," : "," === b.aDec && "." !== b.aSep && (b.altDec = "."));
        var i = b.aNeg ? "([-\\" + b.aNeg + "]?)" : "(-?)";
        b.aNegRegAutoStrip = i, b.skipFirstAutoStrip = new RegExp(i + "[^-" + (b.aNeg ? "\\" + b.aNeg : "") + "\\" + b.aDec + "\\d].*?(\\d|\\" + b.aDec + "\\d)"), b.skipLastAutoStrip = new RegExp("(\\d\\" + b.aDec + "?)[^\\" + b.aDec + "\\d]\\D*$");
        var j = "-" + b.aNum + "\\" + b.aDec;
        return b.allowedAutoStrip = new RegExp("[^" + j + "]", "gi"), b.numRegAutoStrip = new RegExp(i + "(?:\\" + b.aDec + "?(\\d+\\" + b.aDec + "\\d+)|(\\d*(?:\\" + b.aDec + "\\d*)?))"), b
    }
    function g(a, b, c) {
        if (b.aSign)
            for (; a.indexOf(b.aSign) > - 1; )
                a = a.replace(b.aSign, "");
        a = a.replace(b.skipFirstAutoStrip, "$1$2"), a = a.replace(b.skipLastAutoStrip, "$1"), a = a.replace(b.allowedAutoStrip, ""), b.altDec && (a = a.replace(b.altDec, b.aDec));
        var d = a.match(b.numRegAutoStrip);
        if (a = d ? [d[1], d[2], d[3]].join("") : "", ("allow" === b.lZero || "keep" === b.lZero) && "strip" !== c) {
            var e = [], f = "";
            e = a.split(b.aDec), -1 !== e[0].indexOf("-") && (f = "-", e[0] = e[0].replace("-", "")), e[0].length > b.mInt && "0" === e[0].charAt(0) && (e[0] = e[0].slice(1)), a = f + e.join(b.aDec)
        }
        if (c && "deny" === b.lZero || c && "allow" === b.lZero && b.allowLeading === !1) {
            var g = "^" + b.aNegRegAutoStrip + "0*(\\d" + ("leading" === c ? ")" : "|$)");
            g = new RegExp(g), a = a.replace(g, "$1$2")
        }
        return a
    }
    function h(a, b) {
        if ("p" === b.pSign) {
            var c = b.nBracket.split(",");
            b.hasFocus || b.removeBrackets ? (b.hasFocus && a.charAt(0) === c[0] || b.removeBrackets && a.charAt(0) === c[0]) && (a = a.replace(c[0], b.aNeg), a = a.replace(c[1], "")) : (a = a.replace(b.aNeg, ""), a = c[0] + a + c[1])
        }
        return a
    }
    function i(a, b) {
        if (a) {
            var c = +a;
            if (1e-6 > c && c > -1)
                a = +a, 1e-6 > a && a > 0 && (a = (a + 10).toString(), a = a.substring(1)), 0 > a && a > -1 && (a = (a - 10).toString(), a = "-" + a.substring(2)), a = a.toString();
            else {
                var d = a.split(".");
                void 0 !== d[1] && (0 === +d[1] ? a = d[0] : (d[1] = d[1].replace(/0*$/, ""), a = d.join(".")))
            }
        }
        return"keep" === b.lZero ? a : a.replace(/^0*(\d)/, "$1")
    }
    function j(a, b, c) {
        return b && "." !== b && (a = a.replace(b, ".")), c && "-" !== c && (a = a.replace(c, "-")), a.match(/\d/) || (a += "0"), a
    }
    function k(a, b, c) {
        return c && "-" !== c && (a = a.replace("-", c)), b && "." !== b && (a = a.replace(".", b)), a
    }
    function l(a, b, c) {
        return"" === a || a === b.aNeg ? "zero" === b.wEmpty ? a + "0" : "sign" === b.wEmpty || c ? a + b.aSign : a : null
    }
    function m(a, b) {
        a = g(a, b);
        var c = a.replace(",", "."), d = l(a, b, !0);
        if (null !== d)
            return d;
        var e = "";
        e = 2 === b.dGroup ? /(\d)((\d)(\d{2}?)+)$/ : 4 === b.dGroup ? /(\d)((\d{4}?)+)$/ : /(\d)((\d{3}?)+)$/;
        var f = a.split(b.aDec);
        b.altDec && 1 === f.length && (f = a.split(b.altDec));
        var i = f[0];
        if (b.aSep)
            for (; e.test(i); )
                i = i.replace(e, "$1" + b.aSep + "$2");
        if (0 !== b.mDec && f.length > 1 ? (f[1].length > b.mDec && (f[1] = f[1].substring(0, b.mDec)), a = i + b.aDec + f[1]) : a = i, b.aSign) {
            var j = -1 !== a.indexOf(b.aNeg);
            a = a.replace(b.aNeg, ""), a = "p" === b.pSign ? b.aSign + a : a + b.aSign, j && (a = b.aNeg + a)
        }
        return 0 > c && null !== b.nBracket && (a = h(a, b)), a
    }
    function n(a, b) {
        a = "" === a ? "0" : a.toString(), e(b, "mDec"), "CHF" === b.mRound && (a = (Math.round(20 * a) / 20).toString());
        var c = "", d = 0, f = "", g = "boolean" == typeof b.aPad || null === b.aPad ? b.aPad ? b.mDec : 0 : +b.aPad, h = function (a) {
            var b = 0 === g ? /(\.(?:\d*[1-9])?)0*$/ : 1 === g ? /(\.\d(?:\d*[1-9])?)0*$/ : new RegExp("(\\.\\d{" + g + "}(?:\\d*[1-9])?)0*$");
            return a = a.replace(b, "$1"), 0 === g && (a = a.replace(/\.$/, "")), a
        };
        "-" === a.charAt(0) && (f = "-", a = a.replace("-", "")), a.match(/^\d/) || (a = "0" + a), "-" === f && 0 === +a && (f = ""), (+a > 0 && "keep" !== b.lZero || a.length > 0 && "allow" === b.lZero) && (a = a.replace(/^0*(\d)/, "$1"));
        var i = a.lastIndexOf("."), j = -1 === i ? a.length - 1 : i, k = a.length - 1 - j;
        if (k <= b.mDec) {
            if (c = a, g > k) {
                -1 === i && (c += ".");
                for (var l = "000000"; g > k; )
                    l = l.substring(0, g - k), c += l, k += l.length
            } else
                k > g ? c = h(c) : 0 === k && 0 === g && (c = c.replace(/\.$/, ""));
            if ("CHF" !== b.mRound)
                return 0 === +c ? c : f + c;
            "CHF" === b.mRound && (i = c.lastIndexOf("."), a = c)
        }
        var m = i + b.mDec, n = +a.charAt(m + 1), o = a.substring(0, m + 1).split(""), p = "." === a.charAt(m) ? a.charAt(m - 1) % 2 : a.charAt(m) % 2, q = !0;
        if (1 !== p && (p = 0 === p && a.substring(m + 2, a.length) > 0 ? 1 : 0), n > 4 && "S" === b.mRound || n > 4 && "A" === b.mRound && "" === f || n > 5 && "A" === b.mRound && "-" === f || n > 5 && "s" === b.mRound || n > 5 && "a" === b.mRound && "" === f || n > 4 && "a" === b.mRound && "-" === f || n > 5 && "B" === b.mRound || 5 === n && "B" === b.mRound && 1 === p || n > 0 && "C" === b.mRound && "" === f || n > 0 && "F" === b.mRound && "-" === f || n > 0 && "U" === b.mRound || "CHF" === b.mRound)
            for (d = o.length - 1; d >= 0; d -= 1)
                if ("." !== o[d]) {
                    if ("CHF" === b.mRound && o[d] <= 2 && q) {
                        o[d] = 0, q = !1;
                        break
                    }
                    if ("CHF" === b.mRound && o[d] <= 7 && q) {
                        o[d] = 5, q = !1;
                        break
                    }
                    if ("CHF" === b.mRound && q ? (o[d] = 10, q = !1) : o[d] = +o[d] + 1, o[d] < 10)
                        break;
                    d > 0 && (o[d] = "0")
                }
        return o = o.slice(0, m + 1), c = h(o.join("")), 0 === +c ? c : f + c
    }
    function o(a, b, c) {
        var d = b.aDec, e = b.mDec;
        if (a = "paste" === c ? n(a, b) : a, d && e) {
            var f = a.split(d);
            f[1] && f[1].length > e && (e > 0 ? (f[1] = f[1].substring(0, e), a = f.join(d)) : a = f[0])
        }
        return a
    }
    function p(a, b) {
        a = g(a, b), a = o(a, b), a = j(a, b.aDec, b.aNeg);
        var c = +a;
        return c >= b.vMin && c <= b.vMax
    }
    function q(b, c) {
        this.settings = c, this.that = b, this.$that = a(b), this.formatted = !1, this.settingsClone = f(this.$that, this.settings), this.value = b.value
    }
    function r(b) {
        return"string" == typeof b && (b = b.replace(/\[/g, "\\[").replace(/\]/g, "\\]"), b = "#" + b.replace(/(:|\.)/g, "\\$1")), a(b)
    }
    function s(a, b, c) {
        var d = a.data("autoNumeric");
        d || (d = {}, a.data("autoNumeric", d));
        var e = d.holder;
        return(void 0 === e && b || c) && (e = new q(a.get(0), b), d.holder = e), e
    }
    q.prototype = {init: function (a) {
            this.value = this.that.value, this.settingsClone = f(this.$that, this.settings), this.ctrlKey = a.ctrlKey, this.cmdKey = a.metaKey, this.shiftKey = a.shiftKey, this.selection = b(this.that), ("keydown" === a.type || "keyup" === a.type) && (this.kdCode = a.keyCode), this.which = a.which, this.processed = !1, this.formatted = !1
        }, setSelection: function (a, b, d) {
            a = Math.max(a, 0), b = Math.min(b, this.that.value.length), this.selection = {start: a, end: b, length: b - a}, (void 0 === d || d) && c(this.that, a, b)
        }, setPosition: function (a, b) {
            this.setSelection(a, a, b)
        }, getBeforeAfter: function () {
            var a = this.value, b = a.substring(0, this.selection.start), c = a.substring(this.selection.end, a.length);
            return[b, c]
        }, getBeforeAfterStriped: function () {
            var a = this.getBeforeAfter();
            return a[0] = g(a[0], this.settingsClone), a[1] = g(a[1], this.settingsClone), a
        }, normalizeParts: function (a, b) {
            var c = this.settingsClone;
            b = g(b, c);
            var d = b.match(/^\d/) ? !0 : "leading";
            a = g(a, c, d), "" !== a && a !== c.aNeg || "deny" !== c.lZero || b > "" && (b = b.replace(/^0*(\d)/, "$1"));
            var e = a + b;
            if (c.aDec) {
                var f = e.match(new RegExp("^" + c.aNegRegAutoStrip + "\\" + c.aDec));
                f && (a = a.replace(f[1], f[1] + "0"), e = a + b)
            }
            return"zero" !== c.wEmpty || e !== c.aNeg && "" !== e || (a += "0"), [a, b]
        }, setValueParts: function (a, b, c) {
            var d = this.settingsClone, e = this.normalizeParts(a, b), f = e.join(""), g = e[0].length;
            return p(f, d) ? (f = o(f, d, c), g > f.length && (g = f.length), this.value = f, this.setPosition(g, !1), !0) : !1
        }, signPosition: function () {
            var a = this.settingsClone, b = a.aSign, c = this.that;
            if (b) {
                var d = b.length;
                if ("p" === a.pSign) {
                    var e = a.aNeg && c.value && c.value.charAt(0) === a.aNeg;
                    return e ? [1, d + 1] : [0, d]
                }
                var f = c.value.length;
                return[f - d, f]
            }
            return[1e3, -1]
        }, expandSelectionOnSign: function (a) {
            var b = this.signPosition(), c = this.selection;
            c.start < b[1] && c.end > b[0] && ((c.start < b[0] || c.end > b[1]) && this.value.substring(Math.max(c.start, b[0]), Math.min(c.end, b[1])).match(/^\s*$/) ? c.start < b[0] ? this.setSelection(c.start, b[0], a) : this.setSelection(b[1], c.end, a) : this.setSelection(Math.min(c.start, b[0]), Math.max(c.end, b[1]), a))
        }, checkPaste: function () {
            if (void 0 !== this.valuePartsBeforePaste) {
                var a = this.getBeforeAfter(), b = this.valuePartsBeforePaste;
                delete this.valuePartsBeforePaste, a[0] = a[0].substr(0, b[0].length) + g(a[0].substr(b[0].length), this.settingsClone), this.setValueParts(a[0], a[1], "paste") || (this.value = b.join(""), this.setPosition(b[0].length, !1))
            }
        }, skipAllways: function (a) {
            var b = this.kdCode, c = this.which, d = this.ctrlKey, e = this.cmdKey, f = this.shiftKey;
            if ((d || e) && "keyup" === a.type && void 0 !== this.valuePartsBeforePaste || f && 45 === b)
                return this.checkPaste(), !1;
            if (b >= 112 && 123 >= b || b >= 91 && 93 >= b || b >= 9 && 31 >= b || 8 > b && (0 === c || c === b) || 144 === b || 145 === b || 45 === b || 224 === b)
                return!0;
            if ((d || e) && 65 === b)
                return!0;
            if ((d || e) && (67 === b || 86 === b || 88 === b))
                return"keydown" === a.type && this.expandSelectionOnSign(), (86 === b || 45 === b) && ("keydown" === a.type || "keypress" === a.type ? void 0 === this.valuePartsBeforePaste && (this.valuePartsBeforePaste = this.getBeforeAfter()) : this.checkPaste()), "keydown" === a.type || "keypress" === a.type || 67 === b;
            if (d || e)
                return!0;
            if (37 === b || 39 === b) {
                var g = this.settingsClone.aSep, h = this.selection.start, i = this.that.value;
                return"keydown" === a.type && g && !this.shiftKey && (37 === b && i.charAt(h - 2) === g ? this.setPosition(h - 1) : 39 === b && i.charAt(h + 1) === g && this.setPosition(h + 1)), !0
            }
            return b >= 34 && 40 >= b ? !0 : !1
        }, processAllways: function () {
            var a;
            return 8 === this.kdCode || 46 === this.kdCode ? (this.selection.length ? (this.expandSelectionOnSign(!1), a = this.getBeforeAfterStriped(), this.setValueParts(a[0], a[1])) : (a = this.getBeforeAfterStriped(), 8 === this.kdCode ? a[0] = a[0].substring(0, a[0].length - 1) : a[1] = a[1].substring(1, a[1].length), this.setValueParts(a[0], a[1])), !0) : !1
        }, processKeypress: function () {
            var a = this.settingsClone, b = String.fromCharCode(this.which), c = this.getBeforeAfterStriped(), d = c[0], e = c[1];
            return b === a.aDec || a.altDec && b === a.altDec || ("." === b || "," === b) && 110 === this.kdCode ? a.mDec && a.aDec ? a.aNeg && e.indexOf(a.aNeg) > -1 ? !0 : d.indexOf(a.aDec) > -1 ? !0 : e.indexOf(a.aDec) > 0 ? !0 : (0 === e.indexOf(a.aDec) && (e = e.substr(1)), this.setValueParts(d + a.aDec, e), !0) : !0 : "-" === b || "+" === b ? a.aNeg ? ("" === d && e.indexOf(a.aNeg) > -1 && (d = a.aNeg, e = e.substring(1, e.length)), d = d.charAt(0) === a.aNeg ? d.substring(1, d.length) : "-" === b ? a.aNeg + d : d, this.setValueParts(d, e), !0) : !0 : b >= "0" && "9" >= b ? (a.aNeg && "" === d && e.indexOf(a.aNeg) > -1 && (d = a.aNeg, e = e.substring(1, e.length)), a.vMax <= 0 && a.vMin < a.vMax && -1 === this.value.indexOf(a.aNeg) && "0" !== b && (d = a.aNeg + d), this.setValueParts(d + b, e), !0) : !0
        }, formatQuick: function () {
            var a = this.settingsClone, b = this.getBeforeAfterStriped(), c = this.value;
            if (("" === a.aSep || "" !== a.aSep && -1 === c.indexOf(a.aSep)) && ("" === a.aSign || "" !== a.aSign && -1 === c.indexOf(a.aSign))) {
                var d = [], e = "";
                d = c.split(a.aDec), d[0].indexOf("-") > -1 && (e = "-", d[0] = d[0].replace("-", ""), b[0] = b[0].replace("-", "")), d[0].length > a.mInt && "0" === b[0].charAt(0) && (b[0] = b[0].slice(1)), b[0] = e + b[0]
            }
            var f = m(this.value, this.settingsClone), g = f.length;
            if (f) {
                var h = b[0].split(""), i = 0;
                for (i; i < h.length; i += 1)
                    h[i].match("\\d") || (h[i] = "\\" + h[i]);
                var j = new RegExp("^.*?" + h.join(".*?")), k = f.match(j);
                k ? (g = k[0].length, (0 === g && f.charAt(0) !== a.aNeg || 1 === g && f.charAt(0) === a.aNeg) && a.aSign && "p" === a.pSign && (g = this.settingsClone.aSign.length + ("-" === f.charAt(0) ? 1 : 0))) : a.aSign && "s" === a.pSign && (g -= a.aSign.length)
            }
            this.that.value = f, this.setPosition(g), this.formatted = !0
        }};
    var t = {init: function (b) {
            return this.each(function () {
                var d = a(this), e = d.data("autoNumeric"), f = d.data(), i = d.is("input[type=text], input[type=hidden], input[type=tel], input:not([type])");
                if ("object" == typeof e)
                    return this;
                e = a.extend({}, a.fn.autoNumeric.defaults, f, b, {aNum: "0123456789", hasFocus: !1, removeBrackets: !1, runOnce: !1, tagList: ["b", "caption", "cite", "code", "dd", "del", "div", "dfn", "dt", "em", "h1", "h2", "h3", "h4", "h5", "h6", "ins", "kdb", "label", "li", "output", "p", "q", "s", "sample", "span", "strong", "td", "th", "u", "var"]}), e.aDec === e.aSep && a.error("autoNumeric will not function properly when the decimal character aDec: '" + e.aDec + "' and thousand separator aSep: '" + e.aSep + "' are the same character"), d.data("autoNumeric", e);
                var o = s(d, e);
                if (i || "input" !== d.prop("tagName").toLowerCase() || a.error('The input type "' + d.prop("type") + '" is not supported by autoNumeric()'), -1 === a.inArray(d.prop("tagName").toLowerCase(), e.tagList) && "input" !== d.prop("tagName").toLowerCase() && a.error("The <" + d.prop("tagName").toLowerCase() + "> is not supported by autoNumeric()"), e.runOnce === !1 && e.aForm) {
                    if (i) {
                        var q = !0;
                        "" === d[0].value && "empty" === e.wEmpty && (d[0].value = "", q = !1), "" === d[0].value && "sign" === e.wEmpty && (d[0].value = e.aSign, q = !1), q && "" !== d.val() && (null === e.anDefault && d[0].value === d.prop("defaultValue") || null !== e.anDefault && e.anDefault.toString() === d.val()) && d.autoNumeric("set", d.val())
                    }
                    -1 !== a.inArray(d.prop("tagName").toLowerCase(), e.tagList) && "" !== d.text() && d.autoNumeric("set", d.text())
                }
                e.runOnce = !0, d.is("input[type=text], input[type=hidden], input[type=tel], input:not([type])") && (d.on("keydown.autoNumeric", function (b) {
                    return o = s(d), o.settings.aDec === o.settings.aSep && a.error("autoNumeric will not function properly when the decimal character aDec: '" + o.settings.aDec + "' and thousand separator aSep: '" + o.settings.aSep + "' are the same character"), o.that.readOnly ? (o.processed = !0, !0) : (o.init(b), o.skipAllways(b) ? (o.processed = !0, !0) : o.processAllways() ? (o.processed = !0, o.formatQuick(), b.preventDefault(), !1) : (o.formatted = !1, !0))
                }), d.on("keypress.autoNumeric", function (a) {
                    o = s(d);
                    var b = o.processed;
                    return o.init(a), o.skipAllways(a) ? !0 : b ? (a.preventDefault(), !1) : o.processAllways() || o.processKeypress() ? (o.formatQuick(), a.preventDefault(), !1) : void(o.formatted = !1)
                }), d.on("keyup.autoNumeric", function (a) {
                    o = s(d), o.init(a);
                    var b = o.skipAllways(a);
                    return o.kdCode = 0, delete o.valuePartsBeforePaste, d[0].value === o.settings.aSign && ("s" === o.settings.pSign ? c(this, 0, 0) : c(this, o.settings.aSign.length, o.settings.aSign.length)), b ? !0 : "" === this.value ? !0 : void(o.formatted || o.formatQuick())
                }), d.on("focusin.autoNumeric", function () {
                    o = s(d);
                    var a = o.settingsClone;
                    if (a.hasFocus = !0, null !== a.nBracket) {
                        var b = d.val();
                        d.val(h(b, a))
                    }
                    o.inVal = d.val();
                    var c = l(o.inVal, a, !0);
                    null !== c && "" !== c && d.val(c)
                }), d.on("focusout.autoNumeric", function () {
                    o = s(d);
                    var a = o.settingsClone, b = d.val(), c = b;
                    a.hasFocus = !1;
                    var e = "";
                    "allow" === a.lZero && (a.allowLeading = !1, e = "leading"), "" !== b && (b = g(b, a, e), null === l(b, a) && p(b, a, d[0]) ? (b = j(b, a.aDec, a.aNeg), b = n(b, a), b = k(b, a.aDec, a.aNeg)) : b = "");
                    var f = l(b, a, !1);
                    null === f && (f = m(b, a)), (f !== o.inVal || f !== c) && (d.val(f), d.change(), delete o.inVal)
                }))
            })
        }, destroy: function () {
            return a(this).each(function () {
                var b = a(this);
                b.off(".autoNumeric"), b.removeData("autoNumeric")
            })
        }, update: function (b) {
            return a(this).each(function () {
                var c = r(a(this)), d = c.data("autoNumeric");
                "object" != typeof d && a.error("You must initialize autoNumeric('init', {options}) prior to calling the 'update' method");
                var e = c.autoNumeric("get");
                return d = a.extend(d, b), s(c, d, !0), d.aDec === d.aSep && a.error("autoNumeric will not function properly when the decimal character aDec: '" + d.aDec + "' and thousand separator aSep: '" + d.aSep + "' are the same character"), c.data("autoNumeric", d), "" !== c.val() || "" !== c.text() ? c.autoNumeric("set", e) : void 0
            })
        }, set: function (b) {
            return null !== b ? a(this).each(function () {
                var c = r(a(this)), d = c.data("autoNumeric"), e = b.toString(), f = b.toString(), g = c.is("input[type=text], input[type=hidden], input[type=tel], input:not([type])");
                return"object" != typeof d && a.error("You must initialize autoNumeric('init', {options}) prior to calling the 'set' method"), f !== c.attr("value") && f !== c.text() || d.runOnce !== !1 || (e = e.replace(",", ".")), a.isNumeric(+e) || a.error("The value (" + e + ") being 'set' is not numeric and has caused a error to be thrown"), e = i(e, d), d.setEvent = !0, e.toString(), "" !== e && (e = n(e, d)), e = k(e, d.aDec, d.aNeg), p(e, d) || (e = n("", d)), e = m(e, d), g ? c.val(e) : -1 !== a.inArray(c.prop("tagName").toLowerCase(), d.tagList) ? c.text(e) : !1
            }) : void 0
        }, get: function () {
            var b = r(a(this)), c = b.data("autoNumeric");
            "object" != typeof c && a.error("You must initialize autoNumeric('init', {options}) prior to calling the 'get' method");
            var d = "";
            return b.is("input[type=text], input[type=hidden], input[type=tel], input:not([type])") ? d = b.eq(0).val() : -1 !== a.inArray(b.prop("tagName").toLowerCase(), c.tagList) ? d = b.eq(0).text() : a.error("The <" + b.prop("tagName").toLowerCase() + "> is not supported by autoNumeric()"), "" === d && "empty" === c.wEmpty || d === c.aSign && ("sign" === c.wEmpty || "empty" === c.wEmpty) ? "" : ("" !== d && null !== c.nBracket && (c.removeBrackets = !0, d = h(d, c), c.removeBrackets = !1), (c.runOnce || c.aForm === !1) && (d = g(d, c)), d = j(d, c.aDec, c.aNeg), 0 === +d && "keep" !== c.lZero && (d = "0"), "keep" === c.lZero ? d : d = i(d, c))
        }, getString: function () {
            var b = !1, c = r(a(this)), d = c.serialize(), e = d.split("&"), f = a("form").index(c), g = a("form:eq(" + f + ")"), h = [], i = [], j = /^(?:submit|button|image|reset|file)$/i, k = /^(?:input|select|textarea|keygen)/i, l = /^(?:checkbox|radio)$/i, m = /^(?:button|checkbox|color|date|datetime|datetime-local|email|file|image|month|number|password|radio|range|reset|search|submit|time|url|week)/i, n = 0;
            return a.each(g[0], function (a, b) {
                "" === b.name || !k.test(b.localName) || j.test(b.type) || b.disabled || !b.checked && l.test(b.type) ? i.push(-1) : (i.push(n), n += 1)
            }), n = 0, a.each(g[0], function (a, b) {
                "input" !== b.localName || "" !== b.type && "text" !== b.type && "hidden" !== b.type && "tel" !== b.type ? (h.push(-1), "input" === b.localName && m.test(b.type) && (n += 1)) : (h.push(n), n += 1)
            }), a.each(e, function (c, d) {
                d = e[c].split("=");
                var g = a.inArray(c, i);
                if (g > -1 && h[g] > -1) {
                    var j = a("form:eq(" + f + ") input:eq(" + h[g] + ")"), k = j.data("autoNumeric");
                    "object" == typeof k && null !== d[1] && (d[1] = a("form:eq(" + f + ") input:eq(" + h[g] + ")").autoNumeric("get").toString(), e[c] = d.join("="), b = !0)
                }
            }), b || a.error("You must initialize autoNumeric('init', {options}) prior to calling the 'getString' method"), e.join("&")
        }, getArray: function () {
            var b = !1, c = r(a(this)), d = c.serializeArray(), e = a("form").index(c), f = a("form:eq(" + e + ")"), g = [], h = [], i = /^(?:submit|button|image|reset|file)$/i, j = /^(?:input|select|textarea|keygen)/i, k = /^(?:checkbox|radio)$/i, l = /^(?:button|checkbox|color|date|datetime|datetime-local|email|file|image|month|number|password|radio|range|reset|search|submit|time|url|week)/i, m = 0;
            return a.each(f[0], function (a, b) {
                "" === b.name || !j.test(b.localName) || i.test(b.type) || b.disabled || !b.checked && k.test(b.type) ? h.push(-1) : (h.push(m), m += 1)
            }), m = 0, a.each(f[0], function (a, b) {
                "input" !== b.localName || "" !== b.type && "text" !== b.type && "hidden" !== b.type && "tel" !== b.type ? (g.push(-1), "input" === b.localName && l.test(b.type) && (m += 1)) : (g.push(m), m += 1)
            }), a.each(d, function (c, d) {
                var f = a.inArray(c, h);
                if (f > -1 && g[f] > -1) {
                    var i = a("form:eq(" + e + ") input:eq(" + g[f] + ")"), j = i.data("autoNumeric");
                    "object" == typeof j && (d.value = a("form:eq(" + e + ") input:eq(" + g[f] + ")").autoNumeric("get").toString(), b = !0)
                }
            }), b || a.error("None of the successful form inputs are initialized by autoNumeric."), d
        }, getSettings: function () {
            var b = r(a(this));
            return b.eq(0).data("autoNumeric")
        }};
    a.fn.autoNumeric = function (b) {
        return t[b] ? t[b].apply(this, Array.prototype.slice.call(arguments, 1)) : "object" != typeof b && b ? void a.error('Method "' + b + '" is not supported by autoNumeric()') : t.init.apply(this, arguments)
    }, a.fn.autoNumeric.defaults = {aSep: ",", dGroup: "3", aDec: ".", altDec: null, aSign: "", pSign: "p", vMax: "9999999999999.99", vMin: "-9999999999999.99", mDec: null, mRound: "S", aPad: !0, nBracket: null, wEmpty: "empty", lZero: "allow", sNumber: !0, aForm: !0, anDefault: null}
}(jQuery);

/*
 * Gritter for jQuery
 * http://www.boedesign.com/
 *
 * Copyright (c) 2012 Jordan Boesch
 * Dual licensed under the MIT and GPL licenses.
 *
 * Date: February 24, 2012
 * Version: 1.7.4
 */

(function ($) {

    /**
     * Set it up as an object under the jQuery namespace
     */
    $.gritter = {};

    /**
     * Set up global options that the user can over-ride
     */
    $.gritter.options = {
        position: '',
        class_name: '', // could be set to 'gritter-light' to use white notifications
        fade_in_speed: 'medium', // how fast notifications fade in
        fade_out_speed: 1000, // how fast the notices fade out
        time: 6000 // hang on the screen for...
    }

    /**
     * Add a gritter notification to the screen
     * @see Gritter#add();
     */
    $.gritter.add = function (params) {

        try {
            return Gritter.add(params || {});
        } catch (e) {

            var err = 'Gritter Error: ' + e;
            (typeof (console) != 'undefined' && console.error) ?
                    console.error(err, params) :
                    alert(err);

        }

    }

    /**
     * Remove a gritter notification from the screen
     * @see Gritter#removeSpecific();
     */
    $.gritter.remove = function (id, params) {
        Gritter.removeSpecific(id, params || {});
    }

    /**
     * Remove all notifications
     * @see Gritter#stop();
     */
    $.gritter.removeAll = function (params) {
        Gritter.stop(params || {});
    }

    /**
     * Big fat Gritter object
     * @constructor (not really since its object literal)
     */
    var Gritter = {

        // Public - options to over-ride with $.gritter.options in "add"
        position: '',
        fade_in_speed: '',
        fade_out_speed: '',
        time: '',

        // Private - no touchy the private parts
        _custom_timer: 0,
        _item_count: 0,
        _is_setup: 0,
        _tpl_close: '<a class="gritter-close" href="#" tabindex="1">Close Notification</a>',
        _tpl_title: '<span class="gritter-title">[[title]]</span>',
        _tpl_item: '<div id="gritter-item-[[number]]" class="gritter-item-wrapper [[item_class]]" style="display:none" role="alert"><div class="gritter-top"></div><div class="gritter-item">[[close]][[image]]<div class="[[class_name]]">[[title]]<p>[[text]]</p></div><div style="clear:both"></div></div><div class="gritter-bottom"></div></div>',
        _tpl_wrap: '<div id="gritter-notice-wrapper"></div>',

        /**
         * Add a gritter notification to the screen
         * @param {Object} params The object that contains all the options for drawing the notification
         * @return {Integer} The specific numeric id to that gritter notification
         */
        add: function (params) {
            // Handle straight text
            if (typeof (params) == 'string') {
                params = {text: params};
            }

            // We might have some issues if we don't have a title or text!
            if (params.text === null) {
                throw 'You must supply "text" parameter.';
            }

            // Check the options and set them once
            if (!this._is_setup) {
                this._runSetup();
            }

            // Basics
            var title = params.title,
                    text = params.text,
                    image = params.image || '',
                    sticky = params.sticky || false,
                    item_class = params.class_name || $.gritter.options.class_name,
                    position = $.gritter.options.position,
                    close = params.close;
            time_alive = params.time || '';

            this._verifyWrapper();

            this._item_count++;
            var number = this._item_count,
                    tmp = this._tpl_item;

            // Assign callbacks
            $(['before_open', 'after_open', 'before_close', 'after_close']).each(function (i, val) {
                Gritter['_' + val + '_' + number] = ($.isFunction(params[val])) ? params[val] : function () {}
            });

            // Reset
            this._custom_timer = 0;

            // A custom fade time set
            if (time_alive) {
                this._custom_timer = time_alive;
            }

            var image_str = (image != '') ? '<img src="' + image + '" class="gritter-image" />' : '',
                    class_name = (image != '') ? 'gritter-with-image' : 'gritter-without-image';

            // String replacements on the template
            if (title) {
                title = this._str_replace('[[title]]', title, this._tpl_title);
            } else {
                title = '';
            }

            tmp = this._str_replace(
                    ['[[title]]', '[[text]]', '[[close]]', '[[image]]', '[[number]]', '[[class_name]]', '[[item_class]]'],
                    [title, text, close, image_str, this._item_count, class_name, item_class], tmp
                    );

            // If it's false, don't show another gritter message
            if (this['_before_open_' + number]() === false) {
                return false;
            }

            $('#gritter-notice-wrapper').addClass(position).append(tmp);

            var item = $('#gritter-item-' + this._item_count);

            item.fadeIn(this.fade_in_speed, function () {
                Gritter['_after_open_' + number]($(this));
            });

            if (!sticky) {
                this._setFadeTimer(item, number);
            }

            // Bind the hover/unhover states
            $(item).bind('mouseenter mouseleave', function (event) {
                if (event.type == 'mouseenter') {
                    if (!sticky) {
                        Gritter._restoreItemIfFading($(this), number);
                    }
                } else {
                    if (!sticky) {
                        Gritter._setFadeTimer($(this), number);
                    }
                }
                Gritter._hoverState($(this), event.type);
            });

            // Clicking (X) makes the perdy thing close
            $(item).find('.gritter-close').click(function () {
                Gritter.removeSpecific(number, {}, null, true);
                return false;
            });

            return number;

        },

        /**
         * If we don't have any more gritter notifications, get rid of the wrapper using this check
         * @private
         * @param {Integer} unique_id The ID of the element that was just deleted, use it for a callback
         * @param {Object} e The jQuery element that we're going to perform the remove() action on
         * @param {Boolean} manual_close Did we close the gritter dialog with the (X) button
         */
        _countRemoveWrapper: function (unique_id, e, manual_close) {

            // Remove it then run the callback function
            e.remove();
            this['_after_close_' + unique_id](e, manual_close);

            // Check if the wrapper is empty, if it is.. remove the wrapper
            if ($('.gritter-item-wrapper').length == 0) {
                $('#gritter-notice-wrapper').remove();
            }

        },

        /**
         * Fade out an element after it's been on the screen for x amount of time
         * @private
         * @param {Object} e The jQuery element to get rid of
         * @param {Integer} unique_id The id of the element to remove
         * @param {Object} params An optional list of params to set fade speeds etc.
         * @param {Boolean} unbind_events Unbind the mouseenter/mouseleave events if they click (X)
         */
        _fade: function (e, unique_id, params, unbind_events) {

            var params = params || {},
                    fade = (typeof (params.fade) != 'undefined') ? params.fade : true,
                    fade_out_speed = params.speed || this.fade_out_speed,
                    manual_close = unbind_events;

            this['_before_close_' + unique_id](e, manual_close);

            // If this is true, then we are coming from clicking the (X)
            if (unbind_events) {
                e.unbind('mouseenter mouseleave');
            }

            // Fade it out or remove it
            if (fade) {

                e.animate({
                    opacity: 0
                }, fade_out_speed, function () {
                    e.animate({height: 0}, 300, function () {
                        Gritter._countRemoveWrapper(unique_id, e, manual_close);
                    })
                })

            } else {

                this._countRemoveWrapper(unique_id, e);

            }

        },

        /**
         * Perform actions based on the type of bind (mouseenter, mouseleave)
         * @private
         * @param {Object} e The jQuery element
         * @param {String} type The type of action we're performing: mouseenter or mouseleave
         */
        _hoverState: function (e, type) {

            // Change the border styles and add the (X) close button when you hover
            if (type == 'mouseenter') {

                e.addClass('hover');

                // Show close button
                e.find('.gritter-close').show();

            }
            // Remove the border styles and hide (X) close button when you mouse out
            else {

                e.removeClass('hover');

                // Hide close button
                e.find('.gritter-close').hide();

            }

        },

        /**
         * Remove a specific notification based on an ID
         * @param {Integer} unique_id The ID used to delete a specific notification
         * @param {Object} params A set of options passed in to determine how to get rid of it
         * @param {Object} e The jQuery element that we're "fading" then removing
         * @param {Boolean} unbind_events If we clicked on the (X) we set this to true to unbind mouseenter/mouseleave
         */
        removeSpecific: function (unique_id, params, e, unbind_events) {

            if (!e) {
                var e = $('#gritter-item-' + unique_id);
            }

            // We set the fourth param to let the _fade function know to
            // unbind the "mouseleave" event.  Once you click (X) there's no going back!
            this._fade(e, unique_id, params || {}, unbind_events);

        },

        /**
         * If the item is fading out and we hover over it, restore it!
         * @private
         * @param {Object} e The HTML element to remove
         * @param {Integer} unique_id The ID of the element
         */
        _restoreItemIfFading: function (e, unique_id) {

            clearTimeout(this['_int_id_' + unique_id]);
            e.stop().css({opacity: '', height: ''});

        },

        /**
         * Setup the global options - only once
         * @private
         */
        _runSetup: function () {

            for (opt in $.gritter.options) {
                this[opt] = $.gritter.options[opt];
            }
            this._is_setup = 1;

        },

        /**
         * Set the notification to fade out after a certain amount of time
         * @private
         * @param {Object} item The HTML element we're dealing with
         * @param {Integer} unique_id The ID of the element
         */
        _setFadeTimer: function (e, unique_id) {

            var timer_str = (this._custom_timer) ? this._custom_timer : this.time;
            this['_int_id_' + unique_id] = setTimeout(function () {
                Gritter._fade(e, unique_id);
            }, timer_str);

        },

        /**
         * Bring everything to a halt
         * @param {Object} params A list of callback functions to pass when all notifications are removed
         */
        stop: function (params) {

            // callbacks (if passed)
            var before_close = ($.isFunction(params.before_close)) ? params.before_close : function () {};
            var after_close = ($.isFunction(params.after_close)) ? params.after_close : function () {};

            var wrap = $('#gritter-notice-wrapper');
            before_close(wrap);
            wrap.fadeOut(function () {
                $(this).remove();
                after_close();
            });

        },

        /**
         * An extremely handy PHP function ported to JS, works well for templating
         * @private
         * @param {String/Array} search A list of things to search for
         * @param {String/Array} replace A list of things to replace the searches with
         * @return {String} sa The output
         */
        _str_replace: function (search, replace, subject, count) {

            var i = 0, j = 0, temp = '', repl = '', sl = 0, fl = 0,
                    f = [].concat(search),
                    r = [].concat(replace),
                    s = subject,
                    ra = r instanceof Array, sa = s instanceof Array;
            s = [].concat(s);

            if (count) {
                this.window[count] = 0;
            }

            for (i = 0, sl = s.length; i < sl; i++) {

                if (s[i] === '') {
                    continue;
                }

                for (j = 0, fl = f.length; j < fl; j++) {

                    temp = s[i] + '';
                    repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
                    s[i] = (temp).split(f[j]).join(repl);

                    if (count && s[i] !== temp) {
                        this.window[count] += (temp.length - s[i].length) / f[j].length;
                    }

                }
            }

            return sa ? s : s[0];

        },

        /**
         * A check to make sure we have something to wrap our notices with
         * @private
         */
        _verifyWrapper: function () {

            if ($('#gritter-notice-wrapper').length == 0) {
                $('body').append(this._tpl_wrap);
            }

        }

    }

})(jQuery);

/* global LOCALE */

AFFINITY = {};

AFFINITY.openDialog = function ()
{
    $("#DIALOG-selectaffinity").dialog({
        title: LOCALE.get('DIB.SYSTEMSETTINGS.SALESCHANNEL.Action.SelectSalesChannel'),
        resizable: false,
        bgiframe: true,
        modal: true,
        width: '500px'
    });
    $("#DIALOG-selectaffinity").dialog('open');
};

AFFINITY.selectAffinity = function (saleschannel_oid)
{
    DIB.doAjaxAction('/helper/switchsaleschannel', {saleschannel_oid: saleschannel_oid}, null, true);
};


//
//  QUICKSEARCH objekt
//

QUICKSEARCH = {};

QUICKSEARCH.openSearch = function ()
{
    $("#DIALOG-quicksearch").dialog({
        title: LOCALE.get('DIB.QUICKSEARCH.Title'),
        resizable: false,
        bgiframe: true,
        modal: true,
        width: 850
    });
    $('#DIALOG-quicksearch').removeClass('hidden');
    $(".quicksearchtable-row").show();
    $(".quicksearchtable-row input").val('');
    $("#DIALOG-quicksearch-result").html('');
    $("#DIALOG-quicksearch").dialog('open');
    $("#quicksearch_customer")[0].focus();
};

QUICKSEARCH.submitOfferSearch = function ()
{
    var searchVal = $(".quote-landing-refno-input").val();
    if (searchVal == "")
        return;

    $("#DIALOG-quicksearch").dialog({
        title: LOCALE.get('DIB.QUICKSEARCH.Title'),
        resizable: false,
        bgiframe: true,
        modal: true,
        width: 850
    });

    $('#DIALOG-quicksearch').removeClass('hidden');
    $(".quicksearchtable-row").hide();
    $("#quicksearchtable-row-offer").show();
    $("#DIALOG-quicksearch").dialog('open');

    $("#quicksearch_offer").val(searchVal.toString());

    $("#DIALOG-quicksearch-result").html('<div class="loading"><span class="loader"></span></div>');
    DIB.centerDialog();
    $.ajax(
            {
                url: '/helper/quicksearch',
                data: "type=" + "offer" + "&search=" + encodeURIComponent(searchVal),
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        $("#DIALOG-quicksearch-result").html(data.content);
                        DIB.centerDialog();
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            $("#DIALOG-quicksearch-result").html('<div class="alert alert-danger" style="margin-top: 10px">' + data.error + '</div>');
                            DIB.centerDialog();
                        } else
                        {
                            $("#DIALOG-quicksearch-result").html('<div class="alert alert-danger" style="margin-top: 10px">' + LOCALE.get('DIB.QUICKSEARCH.Error') + '</div>');
                            DIB.centerDialog();
                        }
                        return;
                    }
                },
                error: function ()
                {
                    $("#DIALOG-quicksearch-result").html('<div class="alert alert-danger" style="margin-top: 10px">' + LOCALE.get('DIB.QUICKSEARCH.Error') + '</div>');
                    DIB.centerDialog();
                    return;
                }
            });
};

QUICKSEARCH.keypress = function (search_type, evt)
{
    if ((jQuery.browser.msie && event.keyCode == 13) || evt.which == 13)
    {
        QUICKSEARCH.submit(search_type);
        if (jQuery.browser.msie)
            evt.returnValue = false;
        else
            evt.preventDefault();
        return;
    }
};

QUICKSEARCH.submit = function (search_type)
{
    var searchVal = $("#quicksearch_" + search_type).val();
    if (searchVal == "")
        return;
    $(".quicksearchtable-row").hide();
    $("#quicksearchtable-row-" + search_type).show();
    $("#DIALOG-quicksearch-result").html('<div class="loading"><span class="loader"></span></div>');
    DIB.centerDialog();
    $.ajax(
            {
                url: '/helper/quicksearch',
                data: "type=" + search_type + "&search=" + encodeURIComponent(searchVal),
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        $("#DIALOG-quicksearch-result").html(data.content);
                        DIB.centerDialog();
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            $("#DIALOG-quicksearch-result").html('<div class="alert alert-danger" style="margin-top: 10px">' + data.error + '</div>');
                            DIB.centerDialog();
                        } else
                        {
                            $("#DIALOG-quicksearch-result").html('<div class="alert alert-danger" style="margin-top: 10px">' + LOCALE.get('DIB.QUICKSEARCH.Error') + '</div>');
                            DIB.centerDialog();
                        }
                        return;
                    }
                },
                error: function ()
                {
                    $("#DIALOG-quicksearch-result").html('<div class="alert alert-danger" style="margin-top: 10px">' + LOCALE.get('DIB.QUICKSEARCH.Error') + '</div>');
                    DIB.centerDialog();
                    return;
                }
            });
};

DOCUMENTIMPORT = {};

DOCUMENTIMPORT.keypress = function (search_type, evt)
{
    if ((jQuery.browser.msie && event.keyCode == 13) || evt.which == 13)
    {
        DOCUMENTIMPORT.submit(search_type);
        if (jQuery.browser.msie)
            evt.returnValue = false;
        else
            evt.preventDefault();
        return;
    }
};

DOCUMENTIMPORT.map = function (import_oid)
{
    // List
    var documentList = '';
    $("input.documentimport_oid:checked").each(function (i, val)
    {
        if ($(val).val() != import_oid)
            documentList = documentList + (documentList != '' ? ',' : '') + $(val).val();
    });
    DIB.openEditDialog('/documentimport/edit', {'documentimport_oid': import_oid, 'extraimport_oid': documentList}, 1000);
}

DOCUMENTIMPORT.submit = function ()
{
    var searchVal = $("#documentsearch_reference").val();
    var search_type = $("#documentsearch_reference_type").val();
    if (searchVal == "")
        return;
    $("#DIALOG-documentsearch-result").html('<span style="background-repeat: no-repeat; background-image:url(../Images/icon-progress.gif); padding-left: 20px">' + LOCALE.get('DIB.QUICKSEARCH.Progress') + '</span>');
    DIB.centerDialog();
    $.ajax(
            {
                url: '/helper/documentsearch',
                data: "type=" + search_type + "&search=" + encodeURIComponent(searchVal),
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        $("#DIALOG-documentsearch-result").html(data.content);
                        DIB.fixupElements();
                        DIB.centerDialog();
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            $("#DIALOG-documentsearch-result").html('<div class="alert alert-danger" style="margin-top: 10px">' + data.error + '</div>');
                            DIB.centerDialog();
                        } else
                        {
                            $("#DIALOG-documentsearch-result").html('<div class="alert alert-danger" style="margin-top: 10px">' + LOCALE.get('DIB.QUICKSEARCH.Error') + '</div>');
                            DIB.centerDialog();
                        }
                        return;
                    }
                },
                error: function ()
                {
                    $("#DIALOG-documentsearch-result").html('<div class="alert alert-danger" style="margin-top: 10px">' + LOCALE.get('DIB.QUICKSEARCH.Error') + '</div>');
                    DIB.centerDialog();
                    return;
                }
            });
};


//
//  Mail
//

MAIL = {};

MAIL.mailToPopup = function (mailto, ref_type, ref_oid)
{
    if (ref_type == undefined)
        ref_type = '';
    if (ref_oid == undefined)
        ref_oid = '';
    var buttons = {};
    buttons[LOCALE.get('DIB.DIALOG.CloseWindow')] = {
        buttonAction: function ()
        {
            MAIL.closePopup();
        }
    };
    html = '<div id="dialog_mailtopopup" style="display:none">';
    html += '<div class="mailtodialog">';
    html += '<table style="width: 100%">';
    html += '<tr class="field">';
    html += '<td style="width: 10px"><div class="label" style="width: 5px">&nbsp;</div></td><td style="padding-right: 0px"><div class="element" style="padding-top: 6px"><a class="stealth selection" onclick="MAIL.mailDialog(\'' + mailto + '\',\'' + ref_type + '\',\'' + ref_oid + '\')">' + LOCALE.get('DIB.MAILTO.Action.InDIB') + '</a></div></td>';
    html += '</tr>';
    html += '<tr class="field">';
    html += '<td style="width: 10px"><div class="label" style="width: 5px">&nbsp;</div></td><td style="padding-right: 0px"><div class="element" style="padding-top: 6px"><a class="stealth selection" href="mailto:' + mailto + '" onclick="MAIL.closePopup()">' + LOCALE.get('DIB.MAILTO.Action.MailClient') + '</a></div></td>';
    html += '</tr>';
    html += '</table></div></div>';
    $('body').append(html);
    $("#dialog_mailtopopup").dialog({
        title: LOCALE.get('DIB.MAILTO.Title'),
        resizable: false,
        bgiframe: true,
        modal: true,
        width: '400px',
        buttons: buttons
    });
};

MAIL.closePopup = function ()
{
    $("#dialog_mailtopopup").dialog('close');
    $("#dialog_mailtopopup").remove();
};

MAIL.mailDialog = function (mailto, ref_type, ref_oid)
{
    MAIL.closePopup();
    DIB.openEditDialog('/helper/sendmail', {'mailto': mailto, 'ref_type': ref_type, 'ref_oid': ref_oid}, 900);
};



//
//  Varaga seotud asjad
//

OBJECT = {};

OBJECT.addObjectSubmit = function (customer_oid)
{
    var submitData = {
        'customer_oid': customer_oid,
        'object_type': $("#object_type").val()
    };
    DIB.doPostSubmit('/object/edit', submitData);
};



/**
 * Adds new object via JS ajax
 * @param actionUrl
 * @param objectType
 * @param beforeField
 * @param objectProduct
 * @param parentId
 */

OBJECT.addObjectRow = function (actionUrl, objectType, beforeField, objectProduct, parentId)
{
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));

    return new Promise(
            function (resolve, reject) {
                $.ajax(
                        {
                            url: actionUrl,
                            data:
                                    {
                                        offer_oid: $('#offer_oid').val(),
                                        object_type: objectType,
                                        object_product: objectProduct,
                                        parent_oid: parentId,
                                        offer_sourcetype: $('#offer_sourcetype').val(),
                                        mta_begindate: $('#prop_mta_begindate').val(),
                                        mta_enddate: $('#prop_mta_enddate').val(),
                                        offer_is_mta: $('#prop_mta_enddate').length ? 1 : 0,
                                        mta_policy_oid: $('#prop_mta_policy_oid').val(),
                                        begindate: $('#prop_' + OFFER.currentOfferType + '_begindate').val()
                                    },
                            success: function (data)
                            {
                                DIB.closeProgressDialog();
                                if (data.status != 2)
                                {
                                    var contents = data.content;

                                    if (typeof parentId === 'undefined' || !parentId) {
                                        $(beforeField).parent().before(contents.form);
                                    } else {
                                        $('#fieldgroup_object_' + parentId).parent().after(contents.form);
                                    }

                                    DIB.fixupElements();

                                    // Initialize the date fields
                                    FORM.setDatePicker();

                                    // Add scripts from formobject
                                    eval(data.javascript);
                                    resolve(data);
                                } else if (data.error != '' && data.error != undefined)
                                {
                                    DIB.alert(data.error, LOCALE.get('DIB.common.error'));
                                    reject(data);
                                } else
                                {
                                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.common.error'));
                                    reject(LOCALE.get('DIB.common.error'));
                                }
                            },
                            error: function () {
                                DIB.closeProgressDialog();
                                DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.common.error'));
                                reject(LOCALE.get('DIB.common.error'));
                                return;
                            }
                        }
                );
            }
    ).then(function (ajaxResponse) {
        OBJECT.addObjectRowOnSuccess(
                actionUrl,
                objectType,
                beforeField,
                objectProduct,
                ajaxResponse
                )
    })
};

/**
 * Called after addObjectRow ajax call was successful
 *
 * @param actionUrl
 * @param objectType
 * @param beforeField
 * @param objectProduct
 * @param ajaxResponse
 */
OBJECT.addObjectRowOnSuccess = function (
        actionUrl,
        objectType,
        beforeField,
        objectProduct,
        ajaxResponse
        ) {}

/**
 * Removes object via JS ajax
 *
 * @param objectType
 * @param objectOid
 * @param onSuccess function
 */
OBJECT.removeObjectRow = function (objectType, objectOid, onSuccess, url)
{
    var buttons = {};

    if (typeof url === 'undefined') {
        url = '/product/removeobjectfields';
    }

    function removeObject(objectType, objectId) {
        var $objectPanel = $("#fieldgroup_" + objectType + '_' + objectId).parent('.panel');

        if ($objectPanel.length) {
            $objectPanel.fadeOut(500, function () {
                $(this).remove();
            });
        }
    }

    buttons[LOCALE.get('DIB.COMMON.Yes')] = {
        buttonAction: function ()
        {
            DIB.fixupElements();
            DIB.closeConfirmDialog();
            DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
            $.ajax(
                    {
                        url: url,
                        data: {
                            offer_oid: $('#offer_oid').val(),
                            offer_object_oid: objectOid,
                            object_type: objectType
                        },
                        success: function (data)
                        {
                            DIB.closeProgressDialog();
                            if (data.status != 2) {
                                var $fieldGroupObject = $("#fieldgroup_" + objectType + '_' + objectOid),
                                        fieldGroupId = $fieldGroupObject.closest('.panel-body').first().attr('id');
                                // Remove the tile body and rows body
                                $("#fieldgroup_title_" + objectType + '_' + objectOid).fadeOut(500, function () {
                                    $(this).remove();

                                    if (typeof onSuccess == 'function') {
                                        onSuccess(fieldGroupId);
                                    }
                                });

                                for (var i in data.child) {
                                    removeObject('object', data.child[i]);
                                }

                                $fieldGroupObject.fadeOut(500, function () {
                                    $(this).remove();
                                });

                                removeObject(objectType, objectOid);
                                eval(data.javascript);
                            } else if (data.error != '' && data.error != undefined) {
                                DIB.alert(data.error, LOCALE.get('DIB.common.error'));
                            } else {
                                DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.common.error'));
                            }
                        },
                        error: function ()
                        {
                            DIB.closeProgressDialog();
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.common.error'));
                            return;
                        }
                    }
            );

        },
        buttonClass: "primary"
    };

    buttons[LOCALE.get('DIB.FORM.BTN.Cancel')] = {
        buttonAction: function ()
        {
            DIB.closeConfirmDialog();
        }
    };

    DIB.confirmDialog(LOCALE.get('DIB.OBJECT.Action.Delete.Confirm'), LOCALE.get('DIB.COMMON.Delete'), buttons);
};



OBJECT.showInsuredValueHistory = function (object_oid) {

    $.ajax(
            {
                url: '/object/insuredvaluehistory',
                data: "object_oid=" + encodeURIComponent(object_oid),
                success: function (data)
                {
                    $('body').append('<div id="DIALOG-insuredvaluehistory" title="Insured value history" style="display:none"></div>');

                    var $dialog = $('#DIALOG-insuredvaluehistory');

                    $dialog.html(data.list);
                    $dialog.dialog({
                        title: LOCALE.get('DIB.INSUREDVALUE.Action.Title.InsuredValueHistory'),
                        closeOnEscape: true,
                        resizable: false,
                        bgiframe: true,
                        modal: true,
                        width: 800
                    });
                },
                error: function ()
                {
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
};

OBJECT.loadObject = function (object_oid, base_field)
{

    INLINESEARCH.close();
    var customer_base_field = CUSTOMER.getBaseField();
    if (object_oid == null || object_oid == '0')
        return;
    if (base_field != null && base_field != '')
    {
        var field_prefix = base_field + '_';
    } else
    {
        var field_prefix = '';
    }
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
    $.ajax(
            {
                url: '/object/getobjectdata',
                data: "object_oid=" + encodeURIComponent(object_oid) + "&base_field=" + encodeURIComponent(base_field),
                success: function (data)
                {
                    DIB.closeProgressDialog();

                    if (data != null && data.status == '1')
                    {
                        $("#object_oid").val(object_oid);
                        $("#object_reload_oid").val(object_oid);
                        $("#object_reload_oid").trigger("blur");
                        $("#" + field_prefix + "object_oid").val(object_oid);

                        // Object data
                        if (data.objectdata.prop_vehicle_make != null)
                        {
                            VEHICLE.changeMake(field_prefix + "prop_vehicle_make", field_prefix + "prop_vehicle_model", field_prefix + "prop_vehicle_modification", data.objectdata.prop_vehicle_make, data.objectdata.prop_vehicle_model, data.objectdata.prop_vehicle_modification);
                        }
                        if (data.objectdata.prop_vehicle_category != null)
                        {
                            $("#" + field_prefix + "prop_vehicle_category").val(data.objectdata.prop_vehicle_category);
                            VEHICLE.changeCategory(field_prefix + "prop_vehicle_category");
                        }
                        FORM.setValues(data.objectdata, base_field);

                        // Customer data
                        if ($("#customer_oid").val() == '0' || $("#customer_oid").val() == '')
                        {
                            CUSTOMER.resetCustomer();
                            $("#customer_oid").val(data.customer_oid);
                            FORM.setValues(data.customerdata, customer_base_field);
                            CUSTOMER.customerLoaded(customer_base_field);
                        }
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData') + ": " + data.error, LOCALE.get('DIB.COMMON.Whoops'));
                            DIB.centerDialog();
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
                            DIB.centerDialog();
                        }

                        return;
                    }
                },
                error: function ()
                {
                    DIB.closeProgressDialog();
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
};

OBJECT.addObject = function (offer_oid, object_displayname, object_type, object_oid, offer_product)
{
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));

    if (object_displayname != '')
    {
        $.ajax({
            url: '/object/addobject',
            data: "offer_oid=" + encodeURIComponent(offer_oid) +
                    "&object_displayname=" + encodeURIComponent(object_displayname) +
                    "&object_type=" + encodeURIComponent(object_type) +
                    "&object_oid=" + encodeURIComponent(object_oid) +
                    "&offer_type=" + encodeURIComponent(offer_product),
            success: function (data)
            {
                if (data.status != 1 && data.error != null) {
                    DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                    return false;
                }

                var html = '<tr class="datarow">';
                html += '<td style="width:96%">' + object_displayname + '</td>';
                html += '<td style="width:2%" class="comment nowrap">' + '<span class="icon-edit" style="cursor: pointer" id="edit_offer_object_oid_' + data.offer_object_oid + '" onclick="DIB.openEditDialog(\'/object/quoteedit\',{offer_object_oid: \'' + data.offer_object_oid + '\', object_oid: \'' + data.object_oid + '\', offer_oid: \'' + data.offer_oid + '\'});" title="' + LOCALE.get('DIB.POLICY.Object.EditObject') + '"></span>' + '</td>';
                html += '<td style="width:2%" class="comment nowrap">' + '<span class="icon-delete" style="cursor: pointer" id="delete_offer_object_oid_' + data.offer_object_oid + '" onclick="return OBJECT.removeObject(\'' + data.offer_object_oid + '\', \'' + LOCALE.get('DIB.OFFER.OTHER.OBJECTS.Delete') + '\', this);" title="' + LOCALE.get('DIB.OFFER.OTHER.OBJECTS.Delete') + '"></span>' + '</td>';
                html += '</tr>';

                $('#objects_table tr:last').after(html);
                $('#no_data').hide();
                $('#allowed_type').val(object_type);
                $('#obj_info_table').hide();
                $('#contains_object').val('1');
                $('#prop_object_info').val('');

                DIB.fixupStripes();
                return true;
            }
        });
    }


    DIB.closeProgressDialog();
};

OBJECT.addExistingObject = function (object_oid, offer_oid)
{
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));

    if ($('#object_reload_oid').val() != '')
    {
        $.ajax({
            url: '/object/getobjectdata',
            data: "object_oid=" + encodeURIComponent(object_oid),
            success: function (data)
            {
                if ($('#allowed_type').val() == data.object_type || $('#allowed_type').val() == '')
                {
                    OBJECT.addObject(offer_oid, data.object_displayname, data.object_type, data.object_oid, $('#prop_offer_product').val());
                } else
                {
                    DIB.alert(LOCALE.get('DIB.OFFER.OTHER.OBJECTS.TypeError'));
                }
            }
        });
        $('#object_reload_oid').val('');
    }

    DIB.closeProgressDialog();
};

OBJECT.removeObject = function (offer_object_oid, confirm_message, element)
{
    if (confirm(confirm_message + '?'))
    {
        DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
        $.ajax({
            url: '/object/objectofferdelete',
            data: "offer_object_oid=" + encodeURIComponent(offer_object_oid),
            success: function (data)
            {
                $('#' + element.id).parent().parent().remove();
                if ($('#objects_table tr').length == 2)
                {
                    $('#no_data').show();
                    $('#obj_info_table').show();
                    $('#contains_object').val('0');
                    $('#prop_object_info').val('');
                    $('#allowed_type').val('');
                }

                DIB.fixupStripes();
            }
        });
        DIB.closeProgressDialog();
    }
    return false;
};

OBJECT.getOtherQuoteObjects = function (offer_oid)
{
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));

    $.ajax({
        url: '/object/quotegetobjects',
        data: "offer_oid=" + offer_oid,
        success: function (data)
        {
            $('#objects_table').html(data.objects);
            DIB.fixupStripes();
        }
    });

    DIB.closeProgressDialog();
};


//
// Price Object
//

PRICE = {};

PRICE.addPrice = function ()
{
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));

    if ($('#price_insurer').val() != '')
    {
        var insurer_to_add = $('#price_insurer').find(":selected").text();
        var input_name = 'prop_other_';
        var input_class = 'text-center';
        var insurers = new Array();

        // EE specific brokerfee logic
        var useEEBrokerFeeLogic = $('#matrix_row_brokerfee').length > 0;

        if ($("#insurer_row th:contains('" + insurer_to_add + "')").length == 1)
        {
            $('#insurer_row').append('<th class="element text-center" style="text-align: center; width: 10%;"><b>' + insurer_to_add + '</b></td>');
            $('#prices_group_title').attr('colspan', $('#insurer_row').children('td').length);
            $('#insurer_row').append('<input type="hidden" name="prop_other_insurers[]" value="' + $('#price_insurer').val() + '"/>');

            $('.matrix_row').each(function () {
                var period = $(this).attr('id').split("_");
                var readOnly = '';
                var fieldSum = period[period.length - 2];
                var name =
                        input_name +
                        fieldSum +
                        '_' + $('#price_insurer').val() +
                        '_' + period[period.length - 1];

                if (fieldSum == 'tax' || fieldSum == 'premium') {
                    readOnly = ' readonly="readonly"';
                }

                if (useEEBrokerFeeLogic) {
                    var onChangeEventStr = 'OFFER.updateManualPrices(this, \'prop_other\', \'' + $('#price_insurer').val() + '\')';
                    $(this).find(".empty_td").before(
                            '<td style="text-align: center">' +
                            '<div class="element text-center padding-0-10">' +
                            '<input type="text" id="' + name + '" name="' + name + '" class="' + input_class + '" value="" autocomplete="off" onchange="' + onChangeEventStr + '"/>' +
                            '<input type="hidden" id="' + name + '_brokerfee" name="' + name + '_brokerfee" value="" />' +
                            '<input type="hidden" id="' + name + '_insurer" name="' + name + '_insurer" value="" />' +
                            '</div>' +
                            '</td>'
                            );
                } else {
                    $(this).find(".empty_td").before(
                            '<td style="text-align: center">' +
                            '<div class="element text-center padding-0-10">' +
                            '<input type="text" id="' + name + '" name="' + name + '" class="' + input_class + '" value="" autocomplete="off"' + readOnly + ' />' +
                            '</div>' +
                            '</td>'
                            );
                }
            });

            $('#matrix_row_show_on_offer').find(".empty_td").before('<td class="calc"><div class="element text-center"><label class="label-without-margins"><input type="checkbox" id="prop_other_show_' + $('#price_insurer').val() + '" name="prop_other_show_' + $('#price_insurer').val() + '" value="1"><span class="icon-check-empty"></span></label></div></td>');

            $('#matrix_row_recommend_on_offer').find(".empty_td").before('<td class="calc"><div class="element text-center"><label class="radio-button" for="prop_other_recommendation_' + $('#price_insurer').val() + '"><input type="radio" id="prop_other_recommendation_' + $('#price_insurer').val() + '" name="prop_other_recommendation" value="' + $('#price_insurer').val() + '"><span class="icon-radio-empty" style="margin-left: -7px"></span></label></div></td>');


            if (useEEBrokerFeeLogic) {
                var fieldTag = 'prop_other_calcmode_' + $('#price_insurer').val() + '_broker_fee';
                var onChangeEventStr = 'OFFER.updatePrices(\'prop_other\', \'' + $('#price_insurer').val() + '\')';
                $('#matrix_row_brokerfee').find(".empty_td").before(
                        '<td class="calc">' +
                        '<div class="element text-center padding-0-10 input-group">' +
                        '<input type="text" autocomplete="off" id="' + fieldTag + '" name="' + fieldTag + '" onchange="' + onChangeEventStr + '" class="form-control rounded-left text-center numberfield" value="" />' +
                        '<span class="input-group-addon">%</span>' +
                        '</div>' +
                        '</td>'
                        );
            }

            $("#price_insurer option[value='" + $('#price_insurer').val() + "']").remove();
            //$(".empty_td").hide();
            DIB.fixupElements();
        }
    } else
    {
        DIB.alert(LOCALE.get('DIB.OFFER.OTHER.PRICES.NoInsurerSelected'));
    }

    DIB.closeProgressDialog();
};

PRICE.toggleTaxRowToMatrix = function (period, paymentOption)
{
    var selectedTaxOption = $('.tax_option').val();
    var matrixRow = $('.matrix_row_' + period + '_tax');

    if (paymentOption.checked && selectedTaxOption && selectedTaxOption != "") {
        matrixRow.toggle('slow');
    } else {
        matrixRow.hide();
    }
};

PRICE.toggleTaxButtonToMatrix = function (productTag)
{
    var matrixRow = $('.matrix_row_tax');
    var buttonRow = $('#tr_calc_button');
    var selectedTax = false;

    $('.matrix_row_tax :input').each(function () {
        $(this).val('');
    });

    $('.tax_option').each(function () {
        if ($(this).val() != "") {
            selectedTax = true;
        }
    });

    if (selectedTax) {
        buttonRow.show();
        $('.matrix_row').each(function () {
            var period = $(this).attr('id').split("_");
            period = period[period.length - 1];
            if ($('#prop_other_showpmt_' + period).attr('checked') || $('#prop_' + productTag + '_showpmt_' + period).attr('type') == "hidden") {
                $('.matrix_row_' + period + '_tax').show();
            }
        });
    } else {
        buttonRow.hide();
        matrixRow.hide();
    }
};

//
//  Liising-/laenulepingutega seotud asjad
//

LCONTRACT = {};

LCONTRACT.addLContractSubmit = function (customer_oid)
{
    var submitData = {
        'customer_oid': customer_oid,
        'lcontract_type': $("#lcontract_type").val()
    };
    DIB.doPostSubmit('/lcontract/edit', submitData);
};

LCONTRACT.setEndDate = function (months)
{
    function pad(n) {
        return n < 10 ? "0" + n.toString() : n
    }
    function format(str) {
        if (str.match(/^0/))
            str = str.replace(/^0/, "");
        return parseInt(str);
    }
    if ($("#lcontract_date_start").val() == '')
    {
        $("#lcontract_date_end").val('');
        return;
    }
    var endDate = $.datepicker.parseDate(LOCALE.datepicker, $("#lcontract_date_start").val());
    endDate.setMonth(endDate.getMonth() + months);
    endDate.setDate(endDate.getDate() - 1);
    $("#lcontract_date_end").val($.datepicker.formatDate(LOCALE.datepicker, endDate));
};

LCONTRACT.changeObject = function (object_oid)
{
    var buttons = {};
    buttons[LOCALE.get('DIB.COMMON.Yes')] = {
        buttonAction: function ()
        {
            $("#object_oid_" + object_oid).attr('checked', 'checked');
            DIB.closeConfirmDialog();
        },
        buttonClass: "primary"
    };
    buttons[LOCALE.get('DIB.COMMON.No')] = {
        buttonAction: function ()
        {
            DIB.closeConfirmDialog();
        }
    };
    DIB.confirmDialog(LOCALE.get('DIB.LCONTRACT.Warning.ObjectChangeWillUntiePolicies'), LOCALE.get('DIB.COMMON.Warning'), buttons);
};



//
//  Quote
//

CHOOSEOFFER = {};

CHOOSEOFFER.field = new String();

CHOOSEOFFER.openDialog = function (field_tag)
{
    this.field = field_tag;
    CHOOSER.openDialog('/helper/chooseoffer', LOCALE.get('DIB.FORMFIELD.CHOOSEOFFER.Action.Choose'), LOCALE.get('DIB.FORMFIELD.CHOOSEOFFER.SearchFor'), 750);
};

//
//  Installment
//

CHOOSEINSTALLMENT = {};

CHOOSEINSTALLMENT.field = new String();

CHOOSEINSTALLMENT.openDialog = function (field_tag)
{
    this.field = field_tag;
    CHOOSER.openDialog('/helper/chooseinstallment', LOCALE.get('DIB.FORMFIELD.CHOOSEINSTALLMENT.Action.Choose'), LOCALE.get('DIB.FORMFIELD.CHOOSEINSTALLMENT.SearchFor'), 750);
};



//
//  Kliendi valiku field
//

CHOOSECUSTOMER = {};

CHOOSECUSTOMER.field = new String();

CHOOSECUSTOMER.openDialog = function (field_tag)
{
    this.field = field_tag;
    CHOOSER.openDialog('/helper/choosecustomer', LOCALE.get('DIB.FORMFIELD.CHOOSECUSTOMER.Action.Choose'), LOCALE.get('DIB.FORMFIELD.CHOOSECUSTOMER.SearchFor'), 750);
};

CHOOSECUSTOMER.reset = function (field_tag, empty_title)
{
    $("#" + field_tag).val('0');
    $("#" + field_tag + "_display").html('<i>' + empty_title + '</i>');
    FORM.showFields('.choosecustomer', '.choosecustomerempty', null);
};

CHOOSECUSTOMER.getByPolicyOid = function (policyOid) {
    return $.ajax({
        url: '/customer/getbypolicyoid',
        data: {policy_oid: policyOid}
    });
}


CHOOSECUSTOMER.select = function (customer_oid, customer_name)
{
    CHOOSER.closeDialog();
    $("#" + this.field).val(customer_oid);
    $("#" + this.field + "_display").text(customer_name);
    FORM.showFields('.choosecustomer', '.choosecustomernotempty', null);
};

//
// Choose customer for the new policy add
//

CHOOSECUSTOMER_QUICK = {};

CHOOSECUSTOMER_QUICK.field = '';

CHOOSECUSTOMER_QUICK.openDialog = function (field_tag)
{
    this.field = field_tag;
    CHOOSER.openDialog('/helper/choosecustomerquick', LOCALE.get('DIB.FORMFIELD.CHOOSECUSTOMER.Action.Choose'), LOCALE.get('DIB.FORMFIELD.CHOOSECUSTOMER.SearchFor'), 600);
};

CHOOSECUSTOMER_QUICK.reset = function (field_tag, empty_title)
{
    $("#" + field_tag).val('0');
    $("#" + field_tag + "_name").val('').removeAttr('readonly');
};

CHOOSECUSTOMER_QUICK.select = function (customer_oid, customer_name)
{
    CHOOSER.closeDialog();
    $("#" + this.field).val(customer_oid);
    $("#" + this.field + "_name").val(customer_name).attr('readonly', 'readonly');
};

//
//  Choose policy field
//

CHOOSEPOLICY = {};

CHOOSEPOLICY.field = new String();
CHOOSEPOLICY.updateCustomer = false;

CHOOSEPOLICY.openDialog = function (field_tag, searchForTitle)
{
    if (typeof searchForTitle == 'undefined') {
        searchForTitle = 'DIB.FORMFIELD.CHOOSEPOLICY.SearchFor';
    }

    this.field = field_tag;
    CHOOSER.openDialog('/helper/choosepolicy', LOCALE.get('DIB.FORMFIELD.CHOOSEPOLICY.Action.Choose'), LOCALE.get(searchForTitle), 600);
};

CHOOSEPOLICY.reset = function (field_tag, empty_title)
{
    $("#" + field_tag).val('0');
    $("#" + field_tag + "_display").html('<i>' + empty_title + '</i>');
    FORM.showFields('.choosepolicy', '.choosepolicyempty', null);
    CHOOSEPOLICY.updateRenewalCheck(field_tag);

    if (this.updateCustomer) {
        CHOOSECUSTOMER.select('', '');
    }
};

CHOOSEPOLICY.select = function (policy_oid, policy_no)
{
    CHOOSER.closeDialog();
    $("#" + this.field).val(policy_oid);
    $("#" + this.field).trigger('change');
    $("#" + this.field + "_display").text(policy_no);
    FORM.showFields('.choosepolicy', '.choosepolicynotempty', null);
    CHOOSEPOLICY.updateRenewalCheck(this.field);

    if (this.updateCustomer) {
        CHOOSECUSTOMER.getByPolicyOid(policy_oid).done(function (response) {
            CHOOSECUSTOMER.select(response.customer_oid, response.customer_name);
        });
    }

    CHOOSEPOLICY.afterChoose(this.field);
};

/**
 * Extend this from broker custom JS
 * @param field_tag
 */
CHOOSEPOLICY.afterChoose = function (field_tag)
{
    return;
};

CHOOSEPOLICY.updateRenewalCheck = function (field_tag)
{
    if ($("#" + field_tag + "_updaterenewal").length > 0)
    {
        var buttons = {};
        buttons[LOCALE.get('DIB.COMMON.Yes')] = {
            buttonAction: function ()
            {
                $("#" + field_tag + "_updaterenewal").val('1');
                DIB.closeConfirmDialog();
            },
            buttonClass: "primary"
        };
        buttons[LOCALE.get('DIB.COMMON.No')] = {
            buttonAction: function ()
            {
                $("#" + field_tag + "_updaterenewal").val('0');
                DIB.closeConfirmDialog();
            }
        };
        DIB.confirmDialog(LOCALE.get('DIB.POLICY.Renewal.Confirm.UpdateRenewal'), LOCALE.get('DIB.POLICY.FldGroup.Renewal'), buttons);
    }
};

CHOOSEPOLICY.selectProduct = function (field_id) {

    if ($('#' + field_id).val() != '') {
        FORM.showFields('', '.chooseproductnotempty', null);
    } else {
        FORM.showFields('.chooseproduct', '', null);
    }
};

CHOOSEPOLICY.getProducts = function (policy_oid, product_id_selector, object_id_selector) {
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
    $('#' + product_id_selector).find('option').not(':first').remove();
    $('#' + object_id_selector).find('option').not(':first').remove();
    $.ajax(
            {
                url: '/policy/getpolicyproducts',
                data: 'policy_oid=' + encodeURIComponent(policy_oid),
                success: function (data) {
                    if (data != null && data.status == '1') {
                        $.each(data.products, function (i, item) {
                            $('#' + product_id_selector).append($('<option>', {
                                value: i,
                                text: item
                            }));
                        });
                        $.each(data.objects, function (i, item) {
                            $('#' + object_id_selector).append($('<option>', {
                                value: i,
                                text: item
                            }));
                        });
                    }
                    DIB.closeProgressDialog();
                },
                error: function () {
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
                }
            });
};

COLORPICKER = {};
COLORPICKER.chooseColor = function (fieldTag) {
    var currentColor = $('#' + fieldTag).val();
    var buttons = {};
    buttons[LOCALE.get('DIB.LIST.Filter.ApplySelection')] = {
        buttonAction: function () {
            var selectedColor = $("#colorpicker_selected").val();
            COLORPICKER.setColor(fieldTag, selectedColor);
            $("#dialog_colorpicker").dialog('close');
            $("#dialog_colorpicker").remove();
        },
        buttonClass: "primary"
    };
    buttons[LOCALE.get('DIB.DIALOG.CloseWindow')] = {
        buttonAction: function () {
            $("#dialog_colorpicker").dialog('close');
            $("#dialog_colorpicker").remove();
        }
    };

    $('body').append('<div id="dialog_colorpicker" style="display:none"><input type="hidden" id="colorpicker_selected" value="' + currentColor + '"><div id="design_colorpicker"></div></div>');
    $("#dialog_colorpicker").dialog({
        title: LOCALE.get('DIB.FORMFIELD.COLORPICKER.Action.Choose'),
        resizable: false,
        bgiframe: true,
        modal: true,
        width: '380px',
        buttons: buttons
    });
    $('#design_colorpicker').colpick({
        flat: true,
        color: currentColor,
        submit: 0,
        onChange: function (hsb, hex, rgb, el, bySetColor) {
            $("#colorpicker_selected").val(hex);
        }
    });
}

COLORPICKER.setColor = function (fieldTag, color) {
    $('#' + fieldTag).val(color);
    $('#' + fieldTag + '-hitbox').css('background-color', '#' + color);
    $('#' + fieldTag + '-display').html('#' + color);
}

//
//  Arve valiku field
//

CHOOSEINVOICE = {};

CHOOSEINVOICE.field = new String();

CHOOSEINVOICE.openDialog = function (field_tag, payment_oid)
{
    DIB.closeProgressDialog();
    this.field = field_tag;
    CHOOSER.optional_input_html = '<tr class="quicksearchtable-row-custom"><td class="quicksearch-row-label"><div class="label"><span class="title">' + LOCALE.get("DIB.PAYMENT.LinkedInvoices.ShowPaid") + '</span></div></td><td class="quicksearch-row-input"><div class="element"><label for="show_paid" class="label-without-margins" style="cursor: pointer; color: rgb(87, 87, 87);"><input type="checkbox" id="show_paid" name="show_paid" value="1"><span class="icon-check-empty"></span>' + LOCALE.get("DIB.COMMON.Yes") + '</label></div></td></tr>'
            + '<tr class="quicksearchtable-row-custom"><td class="quicksearch-row-label"><div class="label"><span class="title">' + LOCALE.get("DIB.PAYMENT.LinkedInvoices.ShowCancelled") + '</span></div></td><td class="quicksearch-row-input"><div class="element"><label for="show_cancelled" class="label-without-margins" style="cursor: pointer; color: rgb(87, 87, 87);"><input type="checkbox" id="show_cancelled" name="show_cancelled" value="1"><span class="icon-check-empty"></span>' + LOCALE.get("DIB.COMMON.Yes") + '</label></div></td></tr>';
    CHOOSER.openDialog('/helper/chooseinvoice/' + payment_oid, LOCALE.get('DIB.FORMFIELD.CHOOSEINVOICE.Action.Choose'), LOCALE.get('DIB.FORMFIELD.CHOOSEINVOICE.SearchFor'), 900);
};

CHOOSEINVOICE.loadInvoiceRows = function ( )
{
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
    $.ajax(
            {
                url: '/helper/chooseinvoiceloadrows',
                type: 'post',
                data: {
                    search: $('#chooser_search').val(),
                    show_paid: $('#show_paid').parent('label').find('.icon-check-empty').hasClass('icon-check'),
                    show_cancelled: $('#show_cancelled').parent('label').find('.icon-check-empty').hasClass('icon-check'),
                    invoice_offset: $('#invoices_offset').val(),
                    payment_oid: $('#payment_oid').val()
                },
                success: function (data)
                {
                    DIB.closeProgressDialog();
                    if (data != null && data.status == '1')
                    {
                        var $loadMoreRow = $('#row-load-more');
                        $loadMoreRow.before(data.content);
                        var $invoicesOffset = $('#invoices_offset');
                        $invoicesOffset.val(parseInt($invoicesOffset.val()) + 10);
                        if (data.hide_add_button)
                        {
                            $loadMoreRow.remove();
                        }
                        DIB.fixupElements();
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData') + ": " + data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        DIB.centerDialog();
                        return;
                    }
                },
                error: function ()
                {
                    DIB.closeProgressDialog();
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
};
/*
 CHOOSEINVOICE.select = function ( field_tag )
 {
 this.field = field_tag;
 CHOOSER.openDialog('/helper/chooseinvoice',LOCALE.get('DIB.FORMFIELD.CHOOSEINVOICE.Action.Choose'),LOCALE.get('DIB.FORMFIELD.CHOOSEINVOICE.SearchFor'),700);
 }*/


CHOOSEINVOICE.updateTotals = function ()
{
    var total = 0.00;
    var payment_sum = $('#payment_sum').val();
    var payment_type = Number($('#payment_type').val());

    $("input.invoice_oid:checked").each(function () {
        var invoice_oid = $(this).val();
        if ($('#invoice_paid_sum_' + invoice_oid).length)
        {
            var invoicePaid = $('#invoice_paid_sum_' + invoice_oid).val();
            total += Number(invoicePaid.replace(',', '.')); //Replace commas with dot for float numbers to work
        }
    });

    if (total > payment_sum && payment_type != 21)
    {
        $('input#invoice_multi_total_sum').addClass('error');
    } else
    {
        $('input#invoice_multi_total_sum').removeClass('error');
    }

    //update total input value
    $('input#invoice_multi_total_sum').val(total.toFixed(2));
};

CHOOSEINVOICE.linkInvoicesToPayment = function (payment_oid)
{
    var $checkedInvoiceOids = $("input.invoice_oid:checked");
    if (!$checkedInvoiceOids.length)
    {
        DIB.alert(LOCALE.get('DIB.COMMON.Error.NothingSelected'), LOCALE.get('DIB.COMMON.Whoops'));
        return;
    }

    var invoices = [];
    var i = 0;
    $checkedInvoiceOids.each(function () {
        var invoiceOid = $(this).val();
        invoices[i] = {oid: invoiceOid, sum: $('#invoice_paid_sum_' + invoiceOid).val()};
        i++;
    });

    DIB.doAjaxAction('/payment/linkinvoicesmulti', {payment_oid: payment_oid, invoices: invoices}, null, true, LOCALE.get('DIB.COMMON.Progress'));
};


CHOOSEINVOICE.select = function (invoice_oid, invoice_name, invoice_date, invoice_date_due, invoice_currency, invoice_sum, invoice_sum_paid, invoice_sum_unpaid)
{
    /*CHOOSER.closeDialog();*/

    $("#" + this.field).val(invoice_oid);
    $("#" + this.field + "_display").text(invoice_name);
    FORM.showFields('.chooseinvoice', '.chooseinvoicenotempty', null);
    if ($("input[rel=" + this.field + "]").length)
    {
        if (parseFloat($("#sum_total").attr('data-sum')) != 'NaN' && parseFloat($("#sum_total").attr('rel')) != 'NaN')
        {
            $("input[rel=" + this.field + "]").val('');
            PAYMENT.LINKEDINVOICES.recalcTotal();
            var available_sum = Math.round(parseFloat($("#sum_total").attr('rel')) - parseFloat($("#sum_total").attr('data-sum')));
            if (available_sum >= invoice_sum_unpaid)
            {
                $("input[rel=" + this.field + "]").val(invoice_sum_unpaid);
            } else
            {
                $("input[rel=" + this.field + "]").val(sprintf("%.02f", available_sum));
            }
        }
        PAYMENT.LINKEDINVOICES.recalcTotal();
    }
};


//
//  Seltsi arve valiku field
//

CHOOSEINSURERINVOICE = {};

CHOOSEINSURERINVOICE.field = new String();

CHOOSEINSURERINVOICE.openDialog = function (field_tag)
{
    DIB.closeProgressDialog();
    this.field = field_tag;
    CHOOSER.openDialog('/helper/chooseinsurerinvoice', LOCALE.get('DIB.FORMFIELD.CHOOSEINSURERINVOICE.Action.Choose'), LOCALE.get('DIB.FORMFIELD.CHOOSEINSURERINVOICE.SearchFor'), 700);
};

CHOOSEINSURERINVOICE.select = function (insurerinvoice_oid, insurerinvoice_name)
{
    CHOOSER.closeDialog();
    $("#" + this.field).val(insurerinvoice_oid);
    $("#" + this.field + "_display").text(insurerinvoice_name);
    FORM.showFields('.chooseinsurerinvoice', '.chooseinsurerinvoicenotempty', null);
    if ($("input[rel=" + this.field + "]").length)
    {
        PAYMENT.LINKEDINSURERINVOICES.recalcTotal();
    }
};


//
//  Seltsi aruande valiku field
//

CHOOSEINSURERREPORT = {};

CHOOSEINSURERREPORT.field = new String();

CHOOSEINSURERREPORT.openDialog = function (field_tag)
{
    DIB.closeProgressDialog();
    this.field = field_tag;
    CHOOSER.openDialog('/helper/chooseinsurerreport', LOCALE.get('DIB.FORMFIELD.CHOOSEINSURERREPORT.Action.Choose'), LOCALE.get('DIB.FORMFIELD.CHOOSEINSURERREPORT.SearchFor'), 700);
};

CHOOSEINSURERREPORT.select = function (insurerreport_oid, insurerreport_name)
{
    CHOOSER.closeDialog();
    $("#" + this.field).val(insurerreport_oid);
    $("#" + this.field + "_display").text(insurerreport_name);
    FORM.showFields('.chooseinsurerreport', '.chooseinsurerreportnotempty', null);
    if ($("input[rel=" + this.field + "]").length)
    {
        PAYMENT.LINKEDINSURERREPORTS.recalcTotal();
    }
};


//
//  Kliendi valiku field
//

CHOOSELCONTRACT = {};

CHOOSELCONTRACT.field = new String();

CHOOSELCONTRACT.openDialog = function (field_tag, object_type, customer_oid)
{
    this.field = field_tag;
    CHOOSER.url = '/helper/chooselcontract/' + object_type;
    $("#DIALOG-chooser").dialog({
        title: LOCALE.get('DIB.FORMFIELD.CHOOSELCONTRACT.Action.Choose'),
        resizable: false,
        bgiframe: true,
        modal: true,
        width: 800
    });
    $("#chooser_search").val('');
    $("#DIALOG-chooser-result").empty();
    $("#DIALOG-chooser").dialog('open');
    $("#DIALOG-chooser-searchfor").text(LOCALE.get('DIB.FORMFIELD.CHOOSELCONTRACT.SearchFor') + ':');
    $("#chooser_search")[0].focus();

    if (customer_oid != null && object_type != null)
    {
        $("#DIALOG-chooser-result").html('<span class="withicon" style="background-image:url(../Images/icon-progress.gif)">' + LOCALE.get('DIB.QUICKSEARCH.Progress') + '</span>');
        DIB.centerDialog();
        $.ajax(
                {
                    url: '/helper/chooselcontract/' + object_type,
                    data: "customer_oid=" + encodeURIComponent(customer_oid),
                    success: function (data)
                    {
                        if (data != null && data.status == '1')
                        {
                            $("#DIALOG-chooser-result").html(data.content);
                            DIB.centerDialog();
                        } else
                        {
                            if (data != null && data.error != null)
                            {
                                $("#DIALOG-chooser-result").empty();
                                DIB.alert(data.error);
                            } else
                            {
                                $("#DIALOG-chooser-result").empty();
                                DIB.alert(LOCALE.get('DIB.QUICKSEARCH.Error'));
                            }
                            return;
                        }
                    },
                    error: function ()
                    {
                        $("#DIALOG-chooser-result").empty();
                        DIB.alert(LOCALE.get('DIB.QUICKSEARCH.Error'));
                        return;
                    }
                });
    }
};

CHOOSELCONTRACT.reset = function (field_tag, empty_title)
{
    $("#" + field_tag).val('0');
    $("#" + field_tag + "_display").html('<i>' + empty_title + '</i>');
};

CHOOSELCONTRACT.select = function (customer_oid, customer_name)
{
    CHOOSER.closeDialog();
    $("#" + this.field).val(customer_oid);
    $("#" + this.field + "_display").text(customer_name);
};


//
//  Klient
//

CUSTOMER = {};

CUSTOMER.FIELD = {
    field_id: null,
    cache: {},
    init: function (field_prefix) {
        field_prefix = field_prefix + '_';
        var fieldSet = {};

        fieldSet[field_prefix + 'customer_type'] = $('input[name=' + field_prefix + 'customer_type]');
        $('input[id*=' + field_prefix + ']:not([type=radio]), select[id*=' + field_prefix + ']').each(function () {
            fieldSet[$(this).attr('name')] = $(this);
        });

        this.field_id = this.fetchId(field_prefix);  // set object unique ID
        this.cache[this.field_id] = fieldSet;        // store cached jQuery selectors

        return fieldSet;
    },

    /**
     * Set customer type correctly
     * @param field_prefix
     * @param selector
     */
    setType: function (field_prefix, selector) {
        var field = this.getFieldSet(field_prefix);
        if (selector == null) {
            selector = $('input[name=' + field_prefix + '_customer_type][checked=checked]');
        }
        var active_type = $(selector).val();
        $.each(field[field_prefix + '_customer_type'], function (k, v) {
            var type = $(v);
            if (typeof active_type == 'undefined') {
                active_type = type.attr('value');
            }
            if (active_type == type.attr('value')) {
                type.attr('checked', 'checked');
            } else {
                type.removeAttr('checked');
            }
        });
        var ctype = (active_type == 11) ? 1 : 2;
        FORM.showFields('.object_' + this.field_id + '_customertype', '.object_' + this.field_id + '_customertype' + ctype, null);
    },

    /**
     * Empty all the inputs and show/hide
     * fields
     * @param field_prefix
     */
    reset: function (field_prefix) {
        var fieldSet = this.getFieldSet(field_prefix);
        var self = this;
        fieldSet[field_prefix + '_customer_type'].removeAttr('disabled');
        fieldSet[field_prefix + '_customer_name'].show();
        if (fieldSet[field_prefix + '_customer_name_first'])
            fieldSet[field_prefix + '_customer_name_first'].closest('tr').show();
        if (fieldSet[field_prefix + '_customer_name_middle'])
            fieldSet[field_prefix + '_customer_name_middle'].closest('tr').show();
        if (fieldSet[field_prefix + '_customer_name_last'])
            fieldSet[field_prefix + '_customer_name_last'].closest('tr').show();
        fieldSet[field_prefix + '_customer_idcode'].show();

        $('#' + field_prefix + '_customer_name_disp').remove();
        $('#' + field_prefix + '_customer_name_btn').remove();
        $('#' + field_prefix + '_customer_idcode_disp').remove();
        $.each(this.cache[this.field_id], function (key, field) {
            switch (field[0].nodeName) {
                case 'SELECT':
                    self.setValue(key, field.data('default-value'));
                    break;
                default:
                    if (key != field_prefix + '_customer_type')
                        self.setValue(key, '');
                    break;
            }
        });
        this.setType(field_prefix, null);
    },

    /**
     * Set one form field (input|select) value
     * @param key
     * @param value
     */
    setValue: function (key, value) {
        var fieldSet = this.cache[this.field_id];
        if (fieldSet[key] != typeof undefined) {
            if (fieldSet[key]) {
                fieldSet[key].val(value);
            }
        }
    },

    /**
     * Fetch object id
     * @param field_prefix
     * @returns {*}
     */
    fetchId: function (field_prefix) {
        var match = field_prefix.match('^object_([0-9]*)_');
        if (match) {
            return match[1];
        } else {
            return 'main';
        }
    },

    /**
     * Get all
     * @param field_prefix
     * @returns {*}
     */
    getFieldSet: function (field_prefix) {
        this.field_id = this.fetchId(field_prefix);
        if (!this.cache[this.field_id]) {
            return this.init(field_prefix);
        } else {
            return this.cache[this.field_id];
        }
    },

    /**
     * Inserts customer data to corresponding
     * input fields
     * @param field_prefix
     */
    loaded: function (field_prefix) {
        var fieldSet = this.getFieldSet(field_prefix);
        var prefix = (field_prefix != null || field_prefix) ? field_prefix + '_' : '';
        var customer_oid = fieldSet[field_prefix + '_customer_oid'].val();

        if (customer_oid == null || customer_oid == '' || customer_oid == '0') {
            this.reset(field_prefix);
        } else {
            this.setType(field_prefix, null);
            fieldSet[prefix + 'customer_type'].attr('disabled', 'disabled');
            fieldSet[field_prefix + '_customer_name'].closest('tr').show();
            fieldSet[field_prefix + '_customer_name'].hide();
            if (fieldSet[field_prefix + '_customer_name_first'])
                fieldSet[prefix + 'customer_name_first'].closest('tr').hide();
            if (fieldSet[field_prefix + '_customer_name_middle'])
                fieldSet[prefix + 'customer_name_middle'].closest('tr').hide();
            if (fieldSet[field_prefix + '_customer_name_last'])
                fieldSet[prefix + 'customer_name_last'].closest('tr').hide();
            fieldSet[prefix + 'customer_name'].parent('.element').prepend(
                    '<span class="dispfield withbtn" style="width: 60%" id="' + prefix + 'customer_name_disp">' +
                    '<b>' +
                    '<a target="_blank" href="/customer/view/customer=' + customer_oid + '/">' + fieldSet[prefix + 'customer_name'].val() + '</a>' +
                    '</b>' +
                    '</span>' +
                    '<button id="' + prefix + 'customer_name_btn" type="button" class="fieldbtn" onclick="CUSTOMER.FIELD.reset(\'' + field_prefix + '\')">' + LOCALE.get('DIB.OFFER.Action.ResetCustomer') + '</button>'
                    );
            fieldSet[prefix + 'customer_idcode'].hide();
            $("#field_" + prefix + "customer_idcode td div.element").prepend('<span class="dispfield" id="' + prefix + 'customer_idcode_disp">' + $("#" + prefix + "customer_idcode").val() + '</div>');
            $("#" + prefix + "customer_idcode_getname").hide();
        }
    },

    /**
     * Load customer data via AJAX
     * @param customer_oid
     * @param field_prefix
     * @param type
     */
    loadCustomer: function (customer_oid, field_prefix, type) {
        INLINESEARCH.close();
        var self = this;
        if (customer_oid == null || customer_oid == '0')
            return;
        DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
        $.ajax({
            url: '/customer/getcustomerdata',
            data: 'customer_oid=' + encodeURIComponent(customer_oid) + '&nopropprefix=1',
            success: function (data)
            {
                var fieldSet = self.getFieldSet(field_prefix);
                DIB.closeProgressDialog();
                if (data != null && data.status == '1')
                {
                    self.reset(field_prefix);
                    $.each(data.customerdata, function (k, v) {
                        if (k != 'customer_type') {
                            self.setValue(field_prefix + '_' + k, v);
                        }
                    });
                    $.each(data.addressdata, function (k, v) {
                        if (k != 'customer_type') {
                            self.setValue(field_prefix + '_' + k, v);
                        }
                    });
                    $.each(fieldSet[field_prefix + '_customer_type'], function () {
                        if (data.customerdata.customer_type == $(this).val()) {
                            $(this).attr('checked', 'checked');
                        } else {
                            $(this).removeAttr('checked');
                        }
                    });
                    if (data.addressdata != null)
                    {
                        FORM.setValues(data.addressdata, field_prefix);
                    }
                    self.loaded(field_prefix);
                } else
                {
                    if (data != null && data.error != null)
                    {
                        DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData') + ": " + data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        DIB.centerDialog();
                    } else
                    {
                        DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        DIB.centerDialog();
                    }
                }
            },
            error: function ()
            {
                DIB.closeProgressDialog();
                DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
            }
        });
    },

    /**
     * Delete customer entry from table
     * @param actionUrl
     * @param field_prefix
     */
    delete: function (actionUrl, field_prefix) {
        var fieldSet = this.getFieldSet(field_prefix);
        DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
        $.ajax(
                {
                    url: actionUrl,
                    data: {
                        object_oid: this.field_id
                    },
                    success: function (data) {
                        DIB.closeProgressDialog();
                        if (data.status != 2)
                        {
                            $("#field_" + field_prefix + "_customer_search").remove();
                            $.each(fieldSet, function (name, $element) {
                                $element.closest('tr').remove();
                            });
                            $('tr[id^="field_' + field_prefix + '"]').remove();
                            DIB.fixupElements();
                        } else if (data.error != '' && data.error != undefined)
                        {
                            DIB.alert(data.error, LOCALE.get('DIB.common.error'));
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.common.error'));
                        }
                    },
                    error: function () {
                        DIB.closeProgressDialog();
                        DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.common.error'));
                        return;
                    }
                }
        );
    }
};

CUSTOMER.getBaseField = function ()
{
    if ($("#customer_idcode").length > 0 || $("#customer_name").length > 0)
    {
        return '';
    } else
    {
        return 'prop';
    }
};

CUSTOMER.loadCustomer = function (customer_oid, field_prefix, type)
{
    INLINESEARCH.close();
    if (customer_oid == null || customer_oid == '0')
        return;
    var base_field = CUSTOMER.getBaseField();
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
    $.ajax(
            {
                url: '/customer/getcustomerdata',
                data: "customer_oid=" + encodeURIComponent(customer_oid),
                success: function (data)
                {
                    DIB.closeProgressDialog();
                    if (data != null && data.status == '1')
                    {
                        CUSTOMER.resetCustomer();
                        CUSTOMER.updateQuoteCustomer($('#offer_oid').val(), data.customer_oid);
                        $("#customer_oid").val(data.customer_oid);
                        $("#saleschannel_oid").val(data.saleschannel_oid);
                        FORM.setValues(data.customerdata, base_field);
                        if (data.addressdata != null)
                        {
                            FORM.setValues(data.addressdata, base_field);
                        }
                        if (data.contactdata != null)
                        {
                            CUSTOMER.loadContacts(data.contactdata, base_field);
                        }
                        CUSTOMER.customerLoaded(base_field);
                        $(document).trigger("onCustomerChange", [data.customerdata]);
                        DIB.fixupElements();
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData') + ": " + data.error, LOCALE.get('DIB.COMMON.Whoops'));
                            DIB.centerDialog();
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
                            DIB.centerDialog();
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.closeProgressDialog();
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
};

CUSTOMER.updateQuoteCustomer = function (quoteOid, customerOid)
{
    $.ajax(
            {
                url: '/quotecustomerupdate',
                data: {
                    "quote_oid": encodeURIComponent(quoteOid),
                    "customer_oid": encodeURIComponent(customerOid)
                },
                success: function (data) {
                    DIB.closeProgressDialog();
                    return;
                },
                error: function (data) {
                    var responseData = JSON.parse(data.responseText);
                    DIB.closeProgressDialog();

                    if (responseData != null && responseData.error != null) {
                        DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData') + ": " + responseData.error, LOCALE.get('DIB.COMMON.Whoops'));
                        DIB.centerDialog();

                        return;
                    }
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    DIB.centerDialog();

                    return;
                }
            });
}

CUSTOMER.resetCustomer = function ()
{
    var base_field = CUSTOMER.getBaseField();
    if (base_field != '')
    {
        var fieldprefix = base_field + '_';
    } else
    {
        fieldprefix = '';
    }
    $("#customer_oid").val('');
    $("#saleschannel_oid").val($("#saleschannel_default").val());
    $("input[name^='" + fieldprefix + "customer_']:not([type='radio']):not([name^='" + fieldprefix + "customer_claims_history_list'])").val('');
    $("select[name^='" + fieldprefix + "customer_']").each(function () {
        $(this).val($(this).attr("data-default-value"));
    });
//	$("input[name='"+fieldprefix+"customer_type']:eq(0)").attr('checked','checked');

    $("#object_oid").val('');
    // $("#"+base_field+"_person_oid").html('<option value="add">--- '+LOCALE.get('DIB.CUSTOMER.ContactPerson.Action.Add').toLowerCase()+' ---</option>');
    $(document).trigger("onCustomerChange", [0]);

    CUSTOMER.customerLoaded(base_field);
};

CUSTOMER.defaultSalesChannel = function ()
{
    DIB.doAjaxAction('/offertext/changesaleschannel', {type: salesChannel}, null, true, LOCALE.get('DIB.COMMON.Progress'));
};

CUSTOMER.customerLoaded = function (base_field)
{
    var customer_oid = $("#customer_oid").val();
    if (base_field != null && base_field != '')
    {
        var fieldprefix = base_field + "_";
    } else
    {
        fieldprefix = '';
    }
    var customerName = $("#" + fieldprefix + "customer_name").val();
    if (customer_oid == null || customer_oid == '' || customer_oid == '0' || customerName == '')
    {
        $("input[name='" + fieldprefix + "customer_type']").removeAttr('disabled');
        $("#" + fieldprefix + "customer_name_disp").remove();
        $("#" + fieldprefix + "customer_name_btn").remove();
        $("#" + fieldprefix + "customer_name").show();
        $("#" + fieldprefix + "customer_idcode_disp").remove();
        $("#" + fieldprefix + "customer_idcode").show();
        $("#" + fieldprefix + "customer_idcode_getname").show();
    } else
    {
        $("input[name='" + fieldprefix + "customer_type']").attr('disabled', 'disabled');
        $("#" + fieldprefix + "customer_name").hide();
        $("#field_" + fieldprefix + "customer_name td div.element").prepend('<span class="dispfield withbtn" style="width: 60%" id="' + fieldprefix + 'customer_name_disp"><b><a target="_blank" href="/customer/view/customer=' + customer_oid + '/">' + $("#" + fieldprefix + "customer_name").val() + '</a></b></span><button id="' + fieldprefix + 'customer_name_btn" type="button" class="fieldbtn" onclick="CUSTOMER.resetCustomer()">' + LOCALE.get('DIB.OFFER.Action.ResetCustomer') + '</button>');
        $("#" + fieldprefix + "customer_idcode_getname").hide();

        if ($("#" + fieldprefix + "customer_idcode").val() !== "") {
            $("#" + fieldprefix + "customer_idcode").hide();
            $("#field_" + fieldprefix + "customer_idcode td div.element").prepend('<span class="dispfield" id="' + fieldprefix + 'customer_idcode_disp">' + $("#" + fieldprefix + "customer_idcode").val() + '</div>');
        }
    }
    CUSTOMER.changeCustomerType(base_field);
};

CUSTOMER.loadContacts = function (data, base_field)
{
    var contacts = '<option value="">--- ' + LOCALE.get('DIB.FORM.Select') + ' ---</option>';
    for (var k in data)
    {
        contacts = contacts + '<option'
                + ' value="' + k + '"'
                + ' data-contact="1"'
                + ' data-email="' + (data[k].customer_email != null ? data[k].customer_email : '') + '"'
                + ' data-phone="' + (data[k].customer_phone != null ? data[k].customer_phone : '') + '"'
                + ' data-mobile="' + (data[k].customer_mobile != null ? data[k].customer_mobile : '') + '"'
                + ' data-title="' + (data[k].customer_name_title != null ? data[k].customer_name_title : '') + '"'
                + ' data-birthdate="' + (data[k].customer_birthdate != null ? data[k].customer_birthdate : '') + '"'
                + ' data-birthplace="' + (data[k].prop_customer_birthplace != null ? data[k].prop_customer_birthplace : '') + '"'
                + '>' + data[k].customer_name
                + '</option>';
    }
    contacts = contacts + '<option value="add">+ ' + LOCALE.get('DIB.CUSTOMER.ContactPerson.Action.Add').toLowerCase() + '</option>';
    $("#prop_customer_contact_oid").html(contacts);
    CUSTOMER.changeContactPerson(base_field);
};

/**
 * Polish customer fields after customer load.
 *
 * @param base_field
 * @param birthday_type		1 - optional, 2 - required (see $FLD_birthdate_dynamic and customer settings)
 * @param name_required		Default true
 */
CUSTOMER.changeCustomerType = function (base_field, birthday_type, name_required)
{
    if (base_field != null && base_field != '') {
        var fieldprefix = base_field + '_';
    } else {
        var fieldprefix = '';
    }

    if (typeof name_required == 'undefined' || name_required == null) {
        name_required = true
    }

    var customer_type = Math.floor($("input[name=" + fieldprefix + "customer_type]:checked").val() / 10);
    if (!customer_type) {
        var $customer_contact = $('div#prop_customer_contact_oid');
        $("#display_prop_customer_contactperson_email").text($customer_contact.data('email'));
        $("#prop_customer_contactperson_name").next().text($customer_contact.text());
        $("#prop_customer_contactperson_phone").next().text($customer_contact.data('phone'));
        $("#prop_customer_contactperson_mobile").next().text($customer_contact.data('mobile'));
        return;
    }

    FORM.showFields('.customertype', '.customertype' + customer_type, null);

    var customer_name_span = $("#field_" + fieldprefix + "customer_name .label span");
    var customer_idcode_span = $("#field_" + fieldprefix + "customer_idcode .label span");

    $("#field_" + fieldprefix + "customer_name .label").html(
            (name_required
                    ? "<span class='text-danger icon-asterix'></span>"
                    : ''
                    ) + LOCALE.get('DIB.CUSTOMER.Fld.Name.' + customer_type)
            );

    if (
            $("#field_" + fieldprefix + "customer_idcode .label").text() == LOCALE.get('DIB.CUSTOMER.Fld.IDCode') ||
            $("#field_" + fieldprefix + "customer_idcode .label").text() == LOCALE.get('DIB.CUSTOMER.Fld.IDCode.1') ||
            $("#field_" + fieldprefix + "customer_idcode .label").text() == LOCALE.get('DIB.CUSTOMER.Fld.IDCode.2')
            ) {
        $("#field_" + fieldprefix + "customer_idcode .label").text(LOCALE.get('DIB.CUSTOMER.Fld.IDCode.' + customer_type));
        $("#field_" + fieldprefix + "customer_idcode .label").html(customer_idcode_span);
    }

    if ($("#offer_oid").length) {
        var customer_oid = $("#customer_oid").val();

        if (customer_oid != null && customer_oid != '' && customer_oid != '0') {
            $("tr[id^=field_" + fieldprefix + "customer_name]").hide();
            $("#field_" + fieldprefix + "customer_name").show();
        }

        CUSTOMER.changeContactPerson(base_field);
    }

    if (birthday_type == 2) {
        $("#field_" + fieldprefix + "customer_birthdate").find(".label").html(
                "<span class='text-danger icon-asterix'></span>" + LOCALE.get('DIB.CUSTOMER.Fld.BirthDate')
                );
    }
};

CUSTOMER.changeContactPerson = function (base_field)
{
    if (base_field != null && base_field != '')
    {
        var fieldprefix = base_field + '_';
    } else
    {
        var fieldprefix = '';
    }
    var customer_type = Math.floor($("input[name=" + fieldprefix + "customer_type]:checked").val() / 10);
    $(".contactinfofield input:not(#prop_customer_contactperson_birthdate)").removeAttr('readonly').removeClass('noborderonreadonly');
    if (customer_type == 1 && $("#prop_customer_contact_oid").val() == 'add')
    {
        $("#field_prop_customer_contactperson_name").show();
        $(".contactinfofield").show();
        $(".contactinfofield input").val('');
    } else
    {
        $("#field_prop_customer_contactperson_name").hide();
        $(".contactinfofield").hide();
    }
    var customer_contact = $("#prop_customer_contact_oid option:selected");
    if (customer_type == 1 && customer_contact.attr('data-contact') == '1')
    {
        $(".contactinfofield").show();
        $(".contactinfofield input:not(#prop_customer_contactperson_birthdate)").attr('readonly', 'readonly').addClass('noborderonreadonly');
        $("#prop_customer_contactperson_email").val(customer_contact.attr('data-email'));
        $("#prop_customer_contactperson_phone").val(customer_contact.attr('data-phone'));
        $("#prop_customer_contactperson_mobile").val(customer_contact.attr('data-mobile'));
        $("#prop_customer_contactperson_name_title").val(customer_contact.attr('data-title'));
        var birthdate = customer_contact.attr('data-birthdate');
        if (birthdate != undefined)
        {
            $("#prop_customer_contactperson_birthdate").val($.datepicker.formatDate(LOCALE.datepicker, new Date(birthdate)));
        }
        $("#prop_customer_contactperson_birthplace").val(customer_contact.attr('data-birthplace'));
    }
}

CUSTOMER.getCustomerName = function (field)
{
    var customer_idcode = $("#" + field).val();
    if (customer_idcode == '')
    {
        DIB.alert(LOCALE.get('DIB.CUSTOMER.GetCustomerName.Error.IDCodeEmpty'), LOCALE.get('DIB.COMMON.Whoops'));
        return;
    }

    // Field prefix
    var base_field = CUSTOMER.getBaseField();
    if (base_field != null && base_field != '')
    {
        var field_prefix = base_field + '_';
    } else
    {
        var field_prefix = '';
    }

    // Customer_type
    var customer_type = $("input[name=" + field.replace(/_idcode$/, '_type') + "]:checked").val();

    // Query
    DIB.progressDialog(LOCALE.get('DIB.FORM.MSG.Querying'));
    $.ajax(
            {
                url: "/customer/getname",
                data: "customer_idcode=" + encodeURIComponent(customer_idcode) + "&customer_type=" + encodeURIComponent(customer_type) + "&field_prefix=" + encodeURIComponent(field_prefix),
                success: function (data)
                {
                    DIB.closeProgressDialog();
                    if (data != null && data.status == '1')
                    {
                        FORM.setValues(data.customerdata);
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'), data.error);
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.closeProgressDialog();
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}

CUSTOMER.searchForCustomerPerson = function ()
{
    // Reset these in any case
    $("#add_customer_relation").val('0');
    $("#add_customer_relation_oid").val('0');
    $("#add_customer_relation_type").val('0');

    // Checks
    if ($("#add_customer_relation_done").val() == '1')
        return;
    if ($("#customer_name").val().length < 3)
        return;

    // Query
    $.ajax(
            {
                url: "/customer/searchperson",
                data: "customer_name=" + encodeURIComponent($("#customer_name").val()),
                success: function (data)
                {
                    if (data != null && data.status == '1' && data.found == '1')
                    {
                        var buttons = {};
                        buttons[LOCALE.get('DIB.FORM.BTN.OK')] = {
                            buttonAction: function ()
                            {
                                FORM.setValues(data.customerdata);
                                if ($("#search_addrelation").val() == '1')
                                {
                                    $("#add_customer_relation").val('1');
                                    $("#add_customer_relation_oid").val(data.customer_oid);
                                    $("#add_customer_relation_type").val($("#search_addrelation_type").val());
                                }
                                $("#add_customer_relation_done").val('1');
                                $("#DIB_customer_searchcustomer").dialog('close');
                                $("#DIB_customer_searchcustomer").remove();
                            },
                            buttonClass: "primary"
                        };
                        buttons[LOCALE.get('DIB.FORM.BTN.Cancel')] = {
                            buttonAction: function ()
                            {
                                $("#add_customer_relation_done").val('1');
                                $("#DIB_customer_searchcustomer").dialog('close');
                                $("#DIB_customer_searchcustomer").remove();
                            }
                        };
                        $("#DIB_customer_searchcustomer").remove();
                        $('body').append('<div id="DIB_customer_searchcustomer" title="' + LOCALE.get('DIB.SystemTitle') + '" style="display:none">' + data.content + '</div>');
                        $("#DIB_customer_searchcustomer").dialog({
                            width: '680px',
                            resizable: false,
                            bgiframe: true,
                            modal: true,
                            buttons: buttons
                        });
                        $("#DIB_customer_searchcustomer").bind("dialogclose", function (event, ui) {
                            $("#DIB_customer_searchcustomer").dialog('close');
                            $("#DIB_customer_searchcustomer").remove();
                        });
                        $("#DIB_customer_searchcustomer").dialog('open');
                    } else
                    {
                        return;
                    }
                },
                error: function ()
                {
                    return;
                }
            });
}

//
//  Poliis
//

POLICY = {};

POLICY.setEndDate = function (months, policy_duration_adjustment, date_start_field, date_end_field)
{
    function pad(n) {
        return n < 10 ? "0" + n.toString() : n
    }
    function format(str) {
        if (str.match(/^0/))
            str = str.replace(/^0/, "");
        return parseInt(str);
    }
    if (date_start_field == null)
    {
        date_start_field = '#policy_date_start';
    }
    if (date_end_field == null)
    {
        date_end_field = '#policy_date_end';
    }
    if ($(date_start_field).val() == '')
    {
        $("#policy_date_end").val('');
        return;
    }
    var endDate = $.datepicker.parseDate(LOCALE.datepicker, $(date_start_field).val());
    endDate.setMonth(endDate.getMonth() + months);
    endDate.setDate(endDate.getDate() + policy_duration_adjustment);

    $(date_end_field).val($.datepicker.formatDate(LOCALE.datepicker, endDate));
}

POLICY.editChangeInsurer = function ()
{
    var insurer = $("#policy_insurer").val();
    if (insurer != "")
    {
        DIB.progressDialog(LOCALE.get('DIB.FORM.MSG.Querying'));
        $.ajax(
                {
                    url: "/policy/editchangeinsurer",
                    data: "policy_insurer=" + encodeURIComponent(insurer),
                    success: function (data)
                    {
                        DIB.closeProgressDialog();
                        if (data != null && data.status == '1')
                        {
                            FORM.setValues(data.insurerdata);
                        } else
                        {
                            if (data != null && data.error != null)
                            {
                                DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                            } else
                            {
                                DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                            }
                            return;
                        }
                    },
                    error: function ()
                    {
                        DIB.closeProgressDialog();
                        DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        return;
                    }
                });
    } else
    {
        $("#insurercontract_oid").html('<option value="0" selected="selected">--- ' + LOCALE.get('DIB.POLICY.Fld.InsurerContract.SelectInsurer') + ' ---</option>');
    }
}

POLICY.editInitNewPolicy = function ()
{
}

POLICY.checkForDuplicate = function ()
{
    $("#policy_no_comment").html('');
    var customer_oid = $("#customer_oid").val();
    var policy_insurer = $("#policy_insurer").val();
    var policy_date_start = $("#policy_date_start").val();
    if ($("#policy_no_series").length > 0)
    {
        var policy_no = '';
        var policy_no_series = $("#policy_no_series").val();
        var policy_no_number = $("#policy_no_number").val();
        var policy_no_annex = $("#policy_no_annex").val();
    } else
    {
        var policy_no = $("#policy_no").val();
        var policy_no_series = '';
        var policy_no_number = '';
        var policy_no_annex = '';
    }
    if (customer_oid != '' && customer_oid != '0' && policy_insurer != '' && policy_date_start != '' && (policy_no != '' || policy_no_number != '' || policy_no_annex != ''))
    {
        $("#policy_no_comment").html('<img src="../Images/icon-progress.gif" />');
        $.ajax(
                {
                    url: "/policy/checkforduplicate",
                    data: "customer_oid=" + encodeURIComponent(customer_oid) + "&policy_insurer=" + encodeURIComponent(policy_insurer) + "&policy_date_start=" + encodeURIComponent(policy_date_start) + "&policy_no=" + encodeURIComponent(policy_no) + "&policy_no_series=" + encodeURIComponent(policy_no_series) + "&policy_no_number=" + encodeURIComponent(policy_no_number) + "&policy_no_annex=" + encodeURIComponent(policy_no_annex),
                    success: function (data)
                    {
                        $("#policy_no_comment").html('');
                        if (data != null && data.status == '1')
                        {
                            if (data.duplicate != '')
                            {
                                $("#policy_no_comment").html('<span class="text-warning icon-alert" data-style="warning">' + data.duplicate + '</span>');
                                DIB.initPopover();
                            }
                        }
                    },
                    error: function ()
                    {
                        $("#policy_no_comment").val('');
                        return;
                    }
                });
    }
}

POLICY.toggleShowAllAddProducts = function ()
{
    if ($("#add_product_showall").is(':checked'))
    {
        $("#add_product_tag").html($("#add_product_fulllist").html());
    } else
    {
        $("#add_product_tag").html($("#add_product_shortlist").html());
    }
}

POLICY.addProduct = function ()
{
    if ($("#add_product_tag").val() == "")
    {
        DIB.alert(LOCALE.get('DIB.POLICY.Product.Error.NoProductSelected'), LOCALE.get('DIB.COMMON.Whoops'));
        return;
    }
    DIB.doPostSubmit('/policy/editproduct', {'policy_oid': $("#policy_oid").val(), 'policy_product_product': $("#add_product_tag").val()});
}

POLICY.updatePaymentInfoDiscountType = function ()
{
    var discount_type = $("#policy_discount_type").val();
    if (discount_type != "1")
    {
        $("#field_policy_discount td.label").html(LOCALE.get('DIB.POLICY.Fld.Discount') + ' (' + $("#policy_premium_currency").val() + '):');
    } else
    {
        $("#field_policy_discount td.label").html(LOCALE.get('DIB.POLICY.Fld.Discount') + ' (%):');
    }
    if (discount_type == "0")
    {
        $("#policy_discount").val('');
    }
    FORM.showFields('.discount', '.discount\$empty', '#policy_discount_type');
}

POLICY.updateInstallmentDiscountType = function ()
{
    var discount_type = $("#policy_installment_discount_type").val();
    if (discount_type == "12")
    {
        $("#field_policy_installment_discount td small").html($("#policy_installment_currency").val());
    } else
    {
        $("#field_policy_installment_discount td small").html('%');
    }

    var commission_type = $("#policy_installment_commission_type").val();

    if (commission_type == 1)
    {
        $("#field_policy_installment_commission td small").html('%');
    } else if (commission_type == 2)
    {
        $("#field_policy_installment_commission_sum td small").html($("#policy_installment_currency").val());
    } else
    {
        $("#field_policy_installment_commission td small").html();
    }

}

POLICY.recalculateSplittedSum = function (totalSum, enteredValue, sourceId)
{
    if (totalSum < 0)
    {
        totalSum = -totalSum;
        if (enteredValue < 0)
            enteredValue = -enteredValue;
        if (enteredValue > totalSum)
        {
            var reversedTotalSum = -totalSum;
            $('#split_sum_' + sourceId).val(reversedTotalSum.toFixed(2));
            enteredValue = totalSum;
        }
        if (sourceId == 1)
            targetId = 2;
        else
            targetId = 1;
        subtractedSum = -(totalSum - enteredValue);
        $('#split_sum_' + targetId).val(subtractedSum.toFixed(2));
    } else
    {
        if (enteredValue > totalSum)
        {
            $('#split_sum_' + sourceId).val(totalSum.toFixed(2));
            enteredValue = totalSum;
        }
        if (sourceId == 1)
            targetId = 2;
        else
            targetId = 1;
        subtractedSum = totalSum - enteredValue;
        $('#split_sum_' + targetId).val(subtractedSum.toFixed(2));
    }
}

POLICY.ADD = {};

POLICY.ADD.checkInstallmentSum = function ()
{
    var TotalInstallments = 0;
    var requiredTotal = parseFloat($("#policy_premium_sum").val());
    $('.installmentschedulerow .installment_sum').each(function () {
        TotalInstallments += parseFloat(this.value);
    });
    TotalInstallments = TotalInstallments.toFixed(2);
    $('#installmentTotalSum').html(TotalInstallments);
    if (requiredTotal != TotalInstallments)
    {
        $('#installmentTotalSum').css({"color": "#aa0000", 'font-size': "18px"});
        $('#installment_error').show();
    } else
    {
        $('#installmentTotalSum').css({"color": "#000000", 'font-size': "13px"});
        $('#installment_error').hide();
    }
}

POLICY.ADD.initStepCustomer = function ()
{
    if ($("#customer_oid").val() == '')
    {
        var $customerIdCode = $("#customer_idcode");

        if ($customerIdCode.length && $customerIdCode.val() != '') {
            $(".choosecustomer").show();
        } else {
            $("#customer_oid_display").html('<a class="stealth" onclick="CHOOSECUSTOMER.reset(\'customer_oid\',\'' + LOCALE.get('DIB.POLICY.Customer.NewCustomer') + '\')"><i>' + LOCALE.get('DIB.POLICY.Edit.NewPolicyCustomer') + '</i></a>');
        }
    }
}

POLICY.ADD.submit = function (submit_btn)
{
    // Init
    var form_id = 'DIB-form';

    // Clear
    $("#" + form_id + " .errors").remove();
    $("#" + form_id + " td.label.error").removeClass('error');

    // Progress
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Save'));

    $('#' + form_id).ajaxSubmit(
            {
                data: {ajaxsubmit: '1', policy_add_id: $("#policy_add_id").val(), dir: submit_btn},
                beforeSerialize: function (form) {
                    DIB.AUTONUMERIC.beforeSubmit(form);
                },
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        if (data.reload != null && data.reload == '1')
                        {
                            DIB.reload();
                        } else if (data.redirect != null && data.redirect.length > 0)
                        {
                            DIB.redirect(data.redirect);
                        } else
                        {
                            DIB.closeProgressDialog();
                            if (data.content != null)
                            {
                                $("#policy_add_form").html(data.content);
                                if (data.help != null)
                                    $('.panel-help .panel-information-content .text').text(data.help);
                                window.scrollTo(0, 0);
                                if (data.displaytrigger != null && data.displaytrigger != "")
                                {
                                    eval(data.displaytrigger);
                                }
                                FORM.setDatePicker("#DIB-form input:text.datefield");
                                FORM.setAutoComplete("#DIB-form select.autocomplete");
                                DIB.fixupElements();
                            }
                        }
                    } else
                    {
                        DIB.closeProgressDialog();
                        var errors = '<b>' + LOCALE.get('DIB.FORM.Error.Errors') + ':</b>';
                        if (data != null && data.error != null)
                        {
                            if (typeof (data.error) == 'object')
                            {
                                for (var fld_tag in data.error)
                                {
                                    if (data.error.hasOwnProperty(fld_tag))
                                    {
                                        errors = errors + '<br/> &#0149; ' + data.error[fld_tag];
                                    }
                                }
                                DIB.displayErrors(errors, LOCALE.get('DIB.COMMON.Whoops'), data.error);
                            } else
                            {
                                DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                            }
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                complete: function () {
                    DIB.AUTONUMERIC.afterSubmit();
                },
                error: function (xhr, ajaxOptions, thrownError)
                {
                    DIB.closeProgressDialog();
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData') + ' - ' + thrownError + ' - ' + xhr.responseText, LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}

POLICY.ADD.addProduct = function ()
{
    if ($("#add_product_tag").val() == "")
    {
        DIB.alert(LOCALE.get('DIB.POLICY.Product.Error.NoProductSelected'), LOCALE.get('DIB.COMMON.Whoops'));
        return;
    }
    if ($("div.productformblock").length > 0)
    {
        if (!confirm(LOCALE.get('DIB.POLICY.Product.AddProduct.ConfirmClear')))
            return;
    }
    var policy_add_id = $("#policy_add_id").val();
    var product_tag = $("#add_product_tag").val();
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
    $.ajax(
            {
                url: "/policy/getproductform",
                data: "policy_product_product=" + encodeURIComponent(product_tag) + "&policy_add_id=" + encodeURIComponent(policy_add_id),
                success: function (data)
                {
                    DIB.closeProgressDialog();
                    if (data != null && data.status == '1')
                    {
                        $("input[type=hidden][id^=product_]").remove();
                        $("input[type=hidden][id^=object_]").remove();
                        $("div.productformblock").remove();
                        $("#policy_add_form div.panel:eq(0)").after(data.content);
                        if (data.id)
                        {
                            $.each(data.id, function (index, value) {
                                FORM.setDatePicker("#fieldgroup_" + value + " input:text.datefield");
                                FORM.setAutoComplete("#fieldgroup_" + value + " select.autocomplete");
                                DIB.fixupElements("#fieldgroup_" + value + " input");
                            });
                        }
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.displayErrors(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.closeProgressDialog();
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}

POLICY.ADD.generateInstallmentSchedule = function ()
{
    // Check
    if ($("tr.installmentschedulerow").length > 0)
    {
        if (!confirm(LOCALE.get('DIB.POLICY.Action.GenerateSchedule.Confirm')))
            return;
    }

    // Generate schedule
    DIB.progressDialog(LOCALE.get('DIB.POLICY.Action.GenerateSchedule.Progress'));
    $.ajax(
            {
                url: "/policy/addgenerateschedule",
                data: "policy_add_id=" + encodeURIComponent($("#policy_add_id").val()) + "&policy_installments=" + encodeURIComponent($("#policy_installments").val()) + "&policy_premium_sum=" + encodeURIComponent($("#policy_premium_sum").val()) + "&policy_premium_currency=" + encodeURIComponent($("#policy_premium_currency").val()),
                success: function (data)
                {
                    DIB.closeProgressDialog();
                    if (data != null && data.status == '1')
                    {
                        $("tr.installmentschedulerow").remove();
                        $("#field_policy_installments").after(data.content);
                        FORM.setDatePicker("tr.installmentschedulerow input:text.datefield");
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.displayErrors(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.closeProgressDialog();
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}

POLICY.COINSURANCE = {};
POLICY.COINSURANCE.SETTINGS = {};

POLICY.COINSURANCE.checkInsurer = function (coinsurance_rel)
{
    if ($("#coinsurance_insurer_" + coinsurance_rel).val() == "")
    {
        $("#perc_" + coinsurance_rel).val("0.00");
        $("#perc_" + coinsurance_rel).attr("disabled", "disabled");
    } else
    {
        $("#perc_" + coinsurance_rel).removeAttr("disabled");
    }
}

POLICY.COINSURANCE.recalcNumeration = function ()
{
    count = 1;
    $(".coinsurance-cnt").each(function () {
        if ($(this).is(":visible"))
        {
            $(this).html(count);
            count = count + 1;
        }
    });
}

POLICY.COINSURANCE.addInsurer = function ()
{
    if ($("#policy_oid").val() == undefined || $("#policy_oid").val() == '')
    {
        DIB.alert("Policy OID missing!");
        return;
    } else if ($("select#add_insurer").val() == undefined || $("select#add_insurer").val() == '')
    {
        DIB.alert("Select an insurer from the dropdown!");
        return;
    }

    var insurerTag = $("select#add_insurer").val();

    $('#add_insurer option[value="' + $("select#add_insurer").val() + '"').remove();

    if ($('#add_insurer option').length < 2) //Disregard select from here option
    {
        $('#add_insurer').attr('disabled', 'disabled');
        $('#add_insurer_btn').attr('disabled', 'disabled');
    }

    $.ajax(
            {
                data: {policy_oid: $("#policy_oid").val(), insurer_tag: insurerTag},
                url: '/policy/getcoinsuranceinsurer',
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        $("tr.insurerrow th.head-insurer:last").before(data.insurerrow);
                        $("tr.commissionrow th.head-share:last").before(data.commissionrow);

                        $(data.datarow).each(function () {
                            var productRow = $('table.coinsurance tr[data-product="' + $(this).data('product') + '"');
                            $(productRow).find('.total-share').before($(this));
                        });
                        POLICY.COINSURANCE.recalcShare();
                    } else
                    {
                        if (data.error != null)
                            DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        else
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorCheckingData'), LOCALE.get('DIB.COMMON.Whoops'));

                        //Put the insurer back to the list in case of error.
                        POLICY.COINSURANCE.deleteInsurer(insurerTag);
                        return;
                    }
                },
                error: function ()
                {
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorCheckingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });

}

/**
 * Add insurer function, this is pretty much teh same as standard addinsurer, but we don't have policy object
 * requirement here
 */
POLICY.COINSURANCE.SETTINGS.addInsurer = function ()
{
    //Currently default settings can only be applied from binder view. In the next step we should not have this requirement
    if ($("#binder_oid").val() == undefined || $("#binder_oid").val() == '')
    {
        DIB.alert("Binder OID missing!");
        return;
    }
    if ($("select#add_insurer").val() == undefined || $("select#add_insurer").val() == '')
    {
        DIB.alert("Select an insurer from the dropdown!");
        return;
    }

    var insurerTag = $("select#add_insurer").val();

    $('#add_insurer option[value="' + $("select#add_insurer").val() + '"').remove();

    if ($('#add_insurer option').length < 2) //Disregard select from here option
    {
        $('#add_insurer').attr('disabled', 'disabled');
        $('#add_insurer_btn').attr('disabled', 'disabled');
    }

    $.ajax(
            {
                data: {binder_oid: $("#binder_oid").val(), insurer_tag: insurerTag, type: 'settings'},
                url: '/policy/getcoinsuranceinsurer',
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        $("tr.insurerrow th.head-insurer:last").before(data.insurerrow);
                        $("tr.commissionrow th.head-share:last").before(data.commissionrow);

                        $(data.datarow).each(function () {
                            var productRow = $('table.coinsurance tr[data-product="' + $(this).data('product') + '"');
                            $(productRow).find('.total-share').before($(this));
                        });
                        POLICY.COINSURANCE.recalcShare();
                    } else
                    {
                        if (data.error != null)
                            DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        else
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorCheckingData'), LOCALE.get('DIB.COMMON.Whoops'));

                        //Put the insurer back to the list in case of error.
                        POLICY.COINSURANCE.deleteInsurer(insurerTag);
                        return;
                    }
                    //POLICY.COINSURANCE.recalcNumeration();
                },
                error: function ()
                {
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorCheckingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            }
    );
}

POLICY.COINSURANCE.deleteInsurer = function (insurerTag)
{

    $('[data-insurer="' + insurerTag + '"]').each(function () {
        $(this).remove();
    });

    $('.coinsurance_insurers_list[value="' + insurerTag + '"]').remove();
    $('#add_insurer')
            .append($("<option></option>")
                    .attr("value", insurerTag)
                    .text(insurerTag));//this will be insurer tag, fyi

    $('#add_insurer').removeAttr('disabled');
    $('#add_insurer_btn').removeAttr('disabled');


    POLICY.COINSURANCE.recalcNumeration();
    POLICY.COINSURANCE.recalcShare();
}

POLICY.COINSURANCE.recalcShare = function ()
{
    $('tr.product-row td').each(function () {
        var product = $(this).data('product');
        var totalShare = 0;
        var totalCommission = 0;
        $("input[data-share='" + product + "']").each(function () {
            if ($(this).is(":visible"))
                totalShare = totalShare + parseFloat($(this).val());

        });
        $("input[data-commission='" + product + "']").each(function () {
            if ($(this).is(":visible"))
                totalCommission = totalCommission + parseFloat($(this).val());

        });

        // Grand total
        if (totalShare != 100)
        {
            $("#total_" + product + "_share input").css('color', '#FF484F');
            $("#total_" + product + "_share input").css('font-weight', 'bold');
        } else
        {
            $("#total_" + product + "_share input").css('color', '#27690F');
            $("#total_" + product + "_share input").css('font-weight', 'bold');
        }

        if (totalCommission != 100)
        {
            $("#total_" + product + "_commission input").css('color', '#FF484F');
            $("#total_" + product + "_commission input").css('font-weight', 'bold');
        } else
        {
            $("#total_" + product + "_commission input").css('color', '#27690F');
            $("#total_" + product + "_commission input").css('font-weight', 'bold');
        }
        $("#total_" + product + "_share input").val(sprintf("%.02f", totalShare));
        $("#total_" + product + "_commission input").val(sprintf("%.02f", totalCommission));
    });
};

POLICY.COINSURANCE.checkSplit = function ()
{

    //Check totals
    var errors = false;

    $('.total-share input').each(function () {
        //Allow share to be 0 - in this case it is not covered. Must be handled on specific calculator separately.
        if (parseFloat($(this).val()) != 100 && parseFloat($(this).val()) != 0)
        {
            DIB.alert(LOCALE.get('DIB.POLICY.Coinsurance.Error.ProductTotalShareSplit'), LOCALE.get('DIB.COMMON.Whoops'));
            errors = true;
        }
    });

    if (errors === false)
    {
        FORM.submit();
    }
};

POLICY.COMMISSIONSPLIT = {};

POLICY.COMMISSIONSPLIT.changePerson = function (role)
{
    if ($("#person_" + role).val() != "")
    {
        $("#type_" + role).removeAttr('disabled');
        $("#perc_" + role).removeAttr('disabled');
        $("#sum_" + role).removeAttr('disabled');
        if (parseFloat($("#person_" + role + " option:selected").attr('data-commission')) != 0)
        {
            $("#type_" + role).val($("#person_" + role + " option:selected").attr('data-commission-type'));
            if ($("#person_" + role + " option:selected").attr('data-commission-type') == '2')
            {
                $("#sum_" + role).val($("#person_" + role + " option:selected").attr('data-commission'));
            } else
            {
                var personDefault = $("#person_" + role + " option:selected").attr('data-commission');
                if (personDefault == '') {
                    $("#perc_" + role).val($("#perc_" + role).attr('data-role-default'))
                } else {
                    $("#perc_" + role).val(personDefault);
                }
            }
        }
        POLICY.COMMISSIONSPLIT.changeType(role);
    } else
    {
        $("#type_" + role).val('0');
        $("#perc_" + role).val('');
        $("#sum_" + role).val('');
        $("#type_" + role).attr('disabled', 'disabled');
        $("#perc_" + role).attr('disabled', 'disabled');
        $("#sum_" + role).attr('disabled', 'disabled');
    }

    // Recalc
    POLICY.COMMISSIONSPLIT.recalcSplit();
}
POLICY.COMMISSIONSPLIT.disableFields = function (currentRowNum)
{
    if ($('.personrow_' + currentRowNum).val() != "") {

        for (var rowNo = 1; rowNo < 10; rowNo++) {
            if (rowNo == currentRowNum)
                continue;
            $('.personrow_' + rowNo).attr('disabled', 'disabled');
            $('.percrow_' + rowNo).attr('disabled', 'disabled');
            $('.typerow_' + rowNo).attr('disabled', 'disabled');
            $('.sumrow_' + rowNo).attr('disabled', 'disabled');
            $('.personrow_' + rowNo).val('0');
            $('.percrow_' + rowNo).val('');
            $('.typerow_' + rowNo).val('');
            $('.sumrow_' + rowNo).val('');
        }
    } else
    {
        for (var rowNo = 1; rowNo < 10; rowNo++) {
            if (rowNo == currentRowNum)
            {
                $('.percrow_' + rowNo).attr('disabled', 'disabled');
                $('.typerow_' + rowNo).attr('disabled', 'disabled');
                $('.sumrow_' + rowNo).attr('disabled', 'disabled');
            } else
            {
                $('.personrow_' + rowNo).removeAttr('disabled');
            }
        }
    }
}

POLICY.COMMISSIONSPLIT.changeType = function (role)
{
  console.log($("#type_" + role).val());
    if (parseInt($("#type_" + role).val()) == 1)
    {
        $("#perc_" + role).attr('disabled', 'disabled');
        $("#perc_" + role).css('border-color', '');
        $("#sum_" + role).removeAttr('disabled');
        $("#sum_" + role)[0].focus();
        $("#perc_" + role).val('');
    } else
    {
        $("#perc_" + role).removeAttr('disabled');
        $("#sum_" + role).attr('disabled', 'disabled');
        $("#sum_" + role).css('border-color', '');
        $("#sum_" + role).val('');
        $("#perc_" + role)[0].focus();
    }

    // Recalc
    POLICY.COMMISSIONSPLIT.recalcSplit();
}

POLICY.COMMISSIONSPLIT.recalcSplit = function () {
    var currency = $("#policy_premium_currency").val();
    var total_payment_sum = parseFloat($("#policy_premium_sum").val());
    var total_commission = parseFloat($("#broker_commission").val());

    var salesperson_commission = parseFloat($("#perc_sales_person").val());
    var salesperson_commission_amount = parseFloat($("#sum_sales_person").val());
    var commission_type = $("#type_sales_person").val();
    var total_company_commission = 0;
    var total_sales_person_commission = 0;
    var total_commission = parseFloat((total_payment_sum * total_commission) / 100);
    
    if (commission_type == 0 && $("#perc_sales_person").val() != '' && $("#person_sales_person").val()!='') {
        total_company_commission = parseFloat((total_commission) - ((total_commission * salesperson_commission) / 100));        
        total_sales_person_commission = parseFloat(total_commission - total_company_commission);
    } else if (commission_type == 1) {
        total_sales_person_commission = parseFloat($("#sum_sales_person").val());
        total_company_commission = parseFloat(total_commission - total_sales_person_commission);
    }

    $("#sum_sales_person").val(total_sales_person_commission);
    $("#commission_sales_person").val(total_sales_person_commission);
    
    $("#total_sum_broker").html(total_company_commission);
    $("#total_sum").html(total_commission);

}






POLICY.COMMISSIONSPLIT.recalcSplit123 = function ()
{
    // Init
    var currency = $("#policy_premium_currency").val();
    var policy_payment_sum = parseFloat($("#policy_payment_sum").val());
    var total_payment_sum = parseFloat($("#policy_payment_sum").val());
    var total_commission_sum = parseFloat($("#policy_commission_sum").val());
    var policy_commission_sum = parseFloat($("#policy_commission_sum").val());
    var total_fee_sum = parseFloat($("#policy_fee_sum").val());
    var total_commission_external_sum = 0;
    var total_commission_internal_sum = 0;
    var external_split_simple = true;
    var internal_split_simple = true;
    commission_split_data_error = false;

    if (isNaN(total_fee_sum)) {
        total_fee_sum = 0;
    }

    // Do not consider fee
    total_payment_sum = total_payment_sum - total_fee_sum;




    // Init internal
    var total_commission_internal = 0;
    var show_perc_internal = true;
    $("#total_perc_internal").css('color', '');
    $("#total_sum_internal").css('color', '');

    // Traverse internal fields
    $("select[data-intext='internal']").each(function () {
        if ($(this).val() != '' || $(this).attr('data-policyperson') == '1')
        {
            var type = $("#type_" + $(this).attr('data-role')).val();
            if (type == 2)
            {
                show_perc_internal = false;
                internal_split_simple = false;
                if ($("#sum_" + $(this).attr('data-role')).length > 0 && $("#sum_" + $(this).attr('data-role')).val() != '')
                {
                    var val = parseFloat($("#sum_" + $(this).attr('data-role')).val());
                    if (sprintf("%.02f", val) == 'NaN')
                    {
                        $("#sum_" + $(this).attr('data-role')).css('border-color', '#FF484F');
                        commission_split_data_error = true;
                    } else
                    {
                        $("#sum_" + $(this).attr('data-role')).css('border-color', '');
                        $("#sum_" + $(this).attr('data-role')).val(sprintf("%.02f", val));
                        total_commission_internal_sum += val;
                    }
                } else
                {
                    $("#sum_" + $(this).attr('data-role')).css('border-color', '');
                    $("#perc_" + $(this).attr('data-role')).val('');
                }
            } else
            {
                if (type == 1)
                {
                    internal_split_simple = false;
                }
                if ($("#perc_" + $(this).attr('data-role')).length > 0 && $("#perc_" + $(this).attr('data-role')).val() != '')
                {
                    var val = parseFloat($("#perc_" + $(this).attr('data-role')).val());
                    if (sprintf("%.02f", val) == 'NaN' || val > 100)
                    {
                        $("#perc_" + $(this).attr('data-role')).css('border-color', '#FF484F');
                        commission_split_data_error = true;
                    } else
                    {
                        $("#perc_" + $(this).attr('data-role')).css('border-color', '');
                        $("#perc_" + $(this).attr('data-role')).val(sprintf("%.02f", val));
                        if (type == 1)
                        {
                            var sum = Math.round(total_payment_sum * (val / 100), 2);
                            show_perc_internal = false;
                        } else
                        {
                            var sum = Math.round(total_commission_sum * (val / 100), 2);
                            total_commission_internal += val;
                        }
                        $("#sum_" + $(this).attr('data-role')).val(sprintf("%.02f", sum));
                        total_commission_internal_sum += sum;
                    }
                } else
                {
                    $("#perc_" + $(this).attr('data-role')).css('border-color', '');
                    $("#sum_" + $(this).attr('data-role')).val('');
                }
            }
        }
    });

    // Remainder
    if (total_commission_internal == 100)
    {
        var remainder = policy_commission_sum - total_commission_external_sum - total_commission_internal_sum;
        if ($("#policy_roundingremainder").val() != undefined && remainder != 0)
        {
            var remainder_destiny = $("#policy_roundingremainder").val().replace('prop_settings_policy_roundingremainder_', '');
            if (remainder_destiny != '')
            {
                $('#sum_' + remainder_destiny + '_person').val(Math.round(parseFloat($('#sum_' + remainder_destiny + '_person').val()) + Math.round(remainder, 2), 2));
                total_commission_internal_sum = total_commission_internal_sum + Math.round(remainder, 2);
            }
        }
    }



    // Palju maaklerile jääb?
    $("#total_perc_broker").css('color', '');
    $("#total_sum_broker").css('color', '');
    if (!commission_split_data_error)
    {
        var broker_sum = Math.round(policy_commission_sum - (total_commission_external_sum + total_commission_internal_sum), 2);
        $("#total_sum_broker").text(sprintf("%.02f", broker_sum));
        if ($("select[data-intext='external']").length == 0)
        {
            if (internal_split_simple)
            {
                if (total_commission_internal > 100)
                {
                    var broker_perc = 0;
                } else
                {
                    var broker_perc = Math.round((100 - total_commission_internal), 2);
                }
            } else
            {
                var broker_perc = Math.round((broker_sum / policy_commission_sum) * 100, 2);
            }
            $("#total_perc_broker").text(sprintf("%.02f", broker_perc));
        } else
        {
            $("#total_perc_broker").text('');
        }
        if (broker_sum < 0)
        {
            $("#total_perc_broker").css('color', '#FF484F');
            $("#total_sum_broker").css('color', '#FF484F');
        }
    }

    // Grand total
    if (commission_split_data_error)
    {
        $("#total_perc").css('color', '#FF484F');
        $("#total_sum").css('color', '#FF484F');
        $("#total_perc").text(LOCALE.get('DIB.COMMON.Error'));
        $("#total_sum").text(LOCALE.get('DIB.COMMON.Error'));
    } else
    {
        $("#total_perc").css('color', '');
        $("#total_sum").css('color', '');
        var total_sum = Math.round(total_commission_external_sum + total_commission_internal_sum, 2);
        $("#total_sum").text(sprintf("%.02f", total_sum));
        if (total_sum > policy_commission_sum)
        {
            $("#total_sum").css('color', '#FF484F');
        }
        if ($("select[data-intext='external']").length == 0 && internal_split_simple)
        {
            var total_perc = Math.round(total_commission_internal, 2);
            if (total_perc > 100)
            {
                commission_split_data_error = true;
            }
        } else
        {
            var total_perc = Math.round(((total_commission_external_sum + total_commission_internal_sum) / policy_commission_sum) * 100, 2);
        }
        $("#total_perc").text(sprintf("%.02f", total_perc));
        if (total_perc > 100)
        {
            $("#total_perc").css('color', '#FF484F');
        }
    }
}

POLICY.COMMISSIONSPLIT.checkSplit = function ()
{
    if (typeof (commission_split_data_error) != 'undefined' && commission_split_data_error == true)
    {
        DIB.alert(LOCALE.get('DIB.POLICY.CommissionSplit.Error.Values'), LOCALE.get('DIB.COMMON.Whoops'));
        return false;
    }
    if ($("#person_issue_person").val() == '')
    {
        DIB.alert(LOCALE.get('DIB.POLICY.CommissionSplit.Error.PolicyIssuerEmpty'), LOCALE.get('DIB.COMMON.Whoops'));
        return false;
    }
    FORM.submit();
}

POLICY.EDITPRODUCT = {};

POLICY.EDITPRODUCT.addObject = function (object_type)
{
    $.ajax(
            {
                url: "/policy/getobjectform",
                data: "object_type=" + encodeURIComponent(object_type) + "&policy_oid=" + encodeURIComponent($("#policy_oid").val()),
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        $("#form_addobjects").before(data.content);
                        FORM.setDatePicker("#fieldgroup_addobject_" + data.id + " input:text.datefield, #form_" + data.id + " input:text.datefield");
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.displayErrors(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}

POLICY.EDITPRODUCT.removeObject = function (object_tag)
{
    if (!confirm(LOCALE.get('DIB.POLICY.Object.RemoveObject.Confirm')))
        return;
    $("#panel-form_" + object_tag).remove();
}

POLICY.ENDORSEMENT = {};

POLICY.ENDORSEMENT.recalcSum = function (changed_fld)
{
    // Check current value
    if ($("#" + changed_fld).val() != '')
    {
        if (isNaN(parseFloat($("#" + changed_fld).val())))
        {
            DIB.alert(LOCALE.get('DIB.FORM.ERROR.Filter'), LOCALE.get('DIB.COMMON.Whoops'));
            $("#" + changed_fld)[0].focus();
            return;
        }
        $("#" + changed_fld).val(sprintf("%.02f", parseFloat($("#" + changed_fld).val())));
    }

    var endorsement_premium = 0;
    if ($("#installmentschedule_add_sum").val() != '')
    {
        endorsement_premium += parseFloat($("#installmentschedule_add_sum").val());
    }
    $("[id^=installmentschedule_sum_]").each(function () {
        if ($(this).val() != '')
        {
            endorsement_premium += parseFloat($(this).val());
        }
    });
    $("#policy_endorsement_premium").val(sprintf("%.02f", Math.round(endorsement_premium, 2)));
}

POLICY.ENDORSEMENT.splitSum = function ()
{
    // Check
    if ($("#policy_endorsement_premium").val() == '')
        return;
    if ($("[id^=installmentschedule_sum_]").length == 0)
        return;
    if (isNaN(parseFloat($("#policy_endorsement_premium").val())))
    {
        DIB.alert(LOCALE.get('DIB.POLICY.Endorsement.Fld.Premium') + ': ' + LOCALE.get('DIB.COMMON.Error.NotNumeric'), LOCALE.get('DIB.COMMON.Whoops'));
        $("#policy_endorsement_premium")[0].focus();
        return;
    }
    if (parseFloat($("#policy_endorsement_premium").val()) == 0)
    {
        DIB.alert(LOCALE.get('DIB.POLICY.Endorsement.Error.SplitPremium.PremiumEmpty'), LOCALE.get('DIB.COMMON.Whoops'));
        $("#policy_endorsement_premium")[0].focus();
        return;
    }

    // Teeme dialoogi
    dialog = '<div id="split_add_form" style="display: none"><div class="dialogform">';
    dialog += '<table class="DIB_dialogform">';
    dialog += '<tr class="field"><td><div class="label">' + LOCALE.get('DIB.POLICY.Endorsement.Fld.Premium') + '</div></td><td><div class="element element-text">' + sprintf("%.02f", parseFloat($("#policy_endorsement_premium").val())) + ' ' + $("#policy_endorsement_currency").val() + '</div></td></tr>';
    dialog += '<tr class="field"><td><div class="label">' + LOCALE.get('DIB.POLICY.Endorsement.Schedule.OneTimeAddl') + '</div></td><td class="element"><input type="text" id="split_add_sum" name="split_add_sum" value="' + $("#installmentschedule_add_sum").val() + '" autocomplete="off" style="width: 70% !important"> ' + $("#policy_endorsement_currency").val() + '</td></tr>';
    dialog += '<tr><td colspan="2" class="field"><div class="element" style="padding: 5px">' + LOCALE.get('DIB.POLICY.Endorsement.Info.SplitPremium') + '</div></td></tr>';

    $('body').append(dialog);

    var buttons = {};
    buttons[LOCALE.get('DIB.POLICY.Endorsement.Action.SplitPremium')] = {
        buttonAction: function ()
        {

            // Check
            if ($("#split_add_sum").val() != '')
            {
                if (isNaN(parseFloat($("#split_add_sum").val())))
                {
                    DIB.alert(LOCALE.get('DIB.POLICY.Endorsement.Schedule.OneTimeAddl') + ': ' + LOCALE.get('DIB.COMMON.Error.NotNumeric'), LOCALE.get('DIB.COMMON.Whoops'));
                    $("#split_add_sum")[0].focus();
                    return;
                }
            }

            // Set one-time additional
            $("#installmentschedule_add_sum").val(sprintf("%.02f", parseFloat($("#split_add_sum").val())));

            // Calc split
            var endorsement_premium = parseFloat($("#policy_endorsement_premium").val());
            if ($("#installmentschedule_add_sum").val() != '')
            {
                endorsement_premium -= parseFloat($("#installmentschedule_add_sum").val());
            }
            var endorsement_premium_split = Math.round(endorsement_premium / $("[id^=installmentschedule_sum_]").length, 2);
            var endorsement_premium_remainder = Math.round(endorsement_premium - (endorsement_premium_split * $("[id^=installmentschedule_sum_]").length), 2);

            // Split
            $("[id^=installmentschedule_sum_]").each(function () {
                $(this).val(sprintf("%.02f", endorsement_premium_split));
            });

            // Add remainder
            $("[id^=installmentschedule_sum_]:first").val(sprintf("%.02f", Math.round(endorsement_premium_split + endorsement_premium_remainder, 2)));

            // Close dialog
            $("#split_add_form").dialog('close');
        },
        buttonClass: "primary"

    };
    buttons[LOCALE.get('DIB.FORM.BTN.Cancel')] = {
        buttonAction: function ()
        {
            $("#split_add_form").dialog('close');
        }
    };
    $("#split_add_form").dialog({
        title: LOCALE.get('DIB.POLICY.Endorsement.Action.SplitPremium'),
        resizable: false,
        bgiframe: true,
        modal: true,
        width: '550px',
        buttons: buttons,
        close: function (event, ui)
        {
            $("#split_add_form").remove();
        }
    });
    $("#split_add_form").dialog('open');
}

POLICY.ENDORSEMENT.changeCurrency = function ()
{
    $("span.currency").text($("#policy_endorsement_currency").val());
    if ($("#policy_endorsement_currency").val() != $("#policy_premium_currency").val())
    {
        DIB.alert('<b>' + LOCALE.get('DIB.COMMON.Note') + ':</b> ' + LOCALE.get('DIB.POLICY.Endorsement.Info.CurrencyMismatch'));
    }
}


/**
 * Load policy coverage
 * @param policyOid
 */

POLICY.loadCover = function (policyOid, mtaOid)
{
    // Mta tabs
    $("#policy_mta.nav-tabs li").removeClass('active');
    $('#policy_mta a .icon-active').remove();
    $('#tab_' + mtaOid + ' a').append('<span class="icon-active" style="margin-left:-' + ($('#tab_' + mtaOid).width() / 2 + 10) + 'px; margin-bottom: -8px;"></span>');
    $('#tab_' + mtaOid).addClass('active');

    var URL = document.location.toString();
    var URLbase = URL.split('#')[0];
    var content_url = URLbase + '/get=coverage/';
    var coverageDiv = "#products_all";
    var policy_cover_version = mtaOid;

    // Clear products
    $(coverageDiv).html('');
    // Loading
    $(coverageDiv).html('<div class="loading"><span class="size-3 loader"></span></div>');

    // Get content
    $.ajax(
            {
                url: content_url,
                data: {
                    policy_cover_version: policy_cover_version,
                },
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        $(coverageDiv).hide().html(data.content).fadeIn(200);
                        DIB.initPopover();
                        DIB.initTooltips();
                        DIB.initDropdowns();
                        DIB.fixupElements();
                    } else
                    {
                        if (data.error != null && data.error != '')
                        {
                            $(coverageDiv).html('<div class="alert alert-danger">' + data.error + '</div>');
                        } else
                        {
                            $(coverageDiv).html('<div class="alert alert-danger">' + LOCALE.get('DIB.COMMON.ErrorReadingData') + '</div>');
                        }
                        return;
                    }
                }
                ,
                error: function ()
                {
                    $(coverageDiv).html('<div class="alert alert-danger">' + LOCALE.get('DIB.COMMON.ErrorReadingData') + '</div>');
                    return;
                }
            });

}


//
//  Arveldus seltsidega
//

INSURERREPORT = {};

INSURERREPORT.editChangeInsurer = function ()
{
    var policy_insurer = $("#insurerreport_insurer").val();
    if (policy_insurer != "")
    {
        $.ajax(
                {
                    url: "/insurerreport/getinsurercontract",
                    data: "policy_insurer=" + encodeURIComponent(policy_insurer),
                    success: function (data)
                    {
                        if (data != null && data.status == '1')
                        {
                            FORM.loadOptions('#insurercontract_oid', data.insurercontract_oid);
                            if (data.insurerreport_product)
                            {
                                FORM.loadOptions('#prop_insurerreport_product', data.insurerreport_product)
                            }
                        } else
                        {
                            if (data != null && data.error != null)
                            {
                                DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                            } else
                            {
                                DIB.alert(LOCALE.get('DIB.common.errorsavingdata'), LOCALE.get('DIB.COMMON.Whoops'));
                            }
                            return;
                        }
                    },
                    error: function ()
                    {
                        DIB.alert(LOCALE.get('DIB.common.errorsavingdata'), LOCALE.get('DIB.COMMON.Whoops'));
                        return;
                    }
                });
    } else
    {
        $("#insurercontract_oid").html('<option value="0" selected="selected">--- ' + LOCALE.get('DIB.COMMON.NotSet') + ' ---</option>');
        $("#prop_insurerreport_product").html('<option value="0" selected="selected">--- ' + LOCALE.get('DIB.COMMON.NotSet') + ' ---</option>');
    }
}

INSURERREPORT.editInsurerInvoice = function (dialog_tag)
{
    DIB.progressDialog(LOCALE.get('DIB.FORM.MSG.Saving'));
    $("#form_" + dialog_tag + " input, #form_" + dialog_tag + " select, #form_" + dialog_tag + " textarea").ajaxFileUpload(
            {
                url: "/insurerreport/editinsurerinvoice",
                data: {ajaxsubmit: '1', ajaxresponse: 'xml'},
                dataType: "xml",
                timeout: 600,
                success: function (data)
                {
                    var status = $('status', data).text();
                    if (status == '1')
                    {
                        DIB.reload();
                    } else
                    {
                        DIB.closeProgressDialog();
                        if (data != null)
                        {
                            errors = $('error', data).text();
                            DIB.displayErrors(errors, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.closeProgressDialog();
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}

INSURERREPORT.bankAccountFilter = function ()
{
    if ($('#DIB_report_insurerreport_paymentlines_payment_type').val() != '1')
    {
        $('#DIB_report_insurerreport_paymentlines_bank_account').closest('tr').hide();
    } else
    {
        $('#DIB_report_insurerreport_paymentlines_bank_account').closest('tr').show();
    }
}

INSURERREPORT.removeInstallments = function ()
{
    // Check
    if ($("input.policy_installment_oid:checked").length == 0)
    {
        DIB.alert(LOCALE.get('DIB.COMMON.Error.NothingSelected'), LOCALE.get('DIB.COMMON.Whoops'));
        return;
    }

    if (!confirm(LOCALE.get('DIB.INSURERREPORT.Action.RemoveInstallments.Confirm')))
        return;

    // List
    var total = $("input.policy_installment_oid:checked").length;
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress'));
    var installments = new Array();
    var i = 0;
    $("input.policy_installment_oid:checked").each(function () {
        installments[i] = $(this).val();
        i++;
    });
    $.ajax(
            {
                method: "POST",
                url: "/insurerreport/removeinstallment",
                data: {installments: installments},
                success: function (data)
                {
                    DIB.closeProgressDialog();
                    DIB.reload();
                },
                error: function ()
                {
                    DIB.closeProgressDialog();
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    //DIB.reload();
                }
            });
}

INSURERREPORT.exportXls = function (insurerReportOid)
{
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress'));
    var installments = new Array();
    var i = 0;

    $("input.policy_installment_oid:checked").each(function () {
        installments[i++] = $(this).val();
    });

    DIB.doPostSubmit('/insurerreport/exportxls', {
        insurerreport_oid: insurerReportOid,
        installments: installments
    });

    DIB.closeProgressDialog();
}

INSURERREPORT.ADDPAYMENT = {};

INSURERREPORT.ADDPAYMENT.toggleAll = function ()
{
    if ($("#selectall:checked").length > 0)
    {
        $("input.policy_installment_oid").attr('checked', 'checked');
        $("#selectall").attr('checked', 'checked');
    } else
    {
        $("input.policy_installment_oid").removeAttr('checked');
        $("#selectall").removeAttr('checked');
    }
    DIB.fixupElements();
}

INSURERREPORT.ADDPAYMENT.calculateTotals = function ()
{
    var total_count = $("input.policy_installment_oid:checked").length;
    var total_sum = new Array();
    var total_netsum = new Array();
    var total_commission = new Array();
    $("input.policy_installment_oid:checked").each(function ()
    {
        if (typeof (total_count[$("#sum_" + $(this).val()).attr('rel')]) == 'undefined')
            total_count[$("#sum_" + $(this).val()).attr('rel')] = 0;
        if (typeof (total_sum[$("#sum_" + $(this).val()).attr('rel')]) == 'undefined')
            total_sum[$("#sum_" + $(this).val()).attr('rel')] = 0;
        if (typeof (total_netsum[$("#sum_" + $(this).val()).attr('rel')]) == 'undefined')
            total_netsum[$("#sum_" + $(this).val()).attr('rel')] = 0;
        if (typeof (total_commission[$("#sum_" + $(this).val()).attr('rel')]) == 'undefined')
            total_commission[$("#sum_" + $(this).val()).attr('rel')] = 0;
        total_sum[$("#sum_" + $(this).val()).attr('rel')] += parseFloat($("#sum_" + $(this).val()).attr('class'));
        total_netsum[$("#netsum_" + $(this).val()).attr('rel')] += parseFloat($("#netsum_" + $(this).val()).attr('class'));
        total_commission[$("#commission_" + $(this).val()).attr('rel')] += parseFloat($("#commission_" + $(this).val()).attr('class'));
    });
    $("#sum_total").html('');
    for (var c in total_sum)
    {
        $("#sum_total").html($("#sum_total").html() + '<div>' + sprintf("%.02f", total_sum[c]) + ' ' + c + '</div>');
    }
    if ($("#sum_total").html() == '')
    {
        $("#sum_total").html('0.00');
    }
    $("#netsum_total").html('');
    for (var c in total_netsum)
    {
        $("#netsum_total").html($("#netsum_total").html() + '<div>' + sprintf("%.02f", total_netsum[c]) + ' ' + c + '</div>');
    }
    if ($("#netsum_total").html() == '')
    {
        $("#netsum_total").html('0.00');
    }
    $("#commission_total").html('');
    for (var c in total_commission)
    {
        $("#commission_total").html($("#commission_total").html() + '<div>' + sprintf("%.02f", total_commission[c]) + ' ' + c + '</div>');
    }
    if ($("#commission_total").html() == '')
    {
        $("#commission_total").html('0.00');
    }

    $("#total_count").text(total_count);
}

//
//  Arveldus agentidega
//

AGENTREPORT = {};

AGENTREPORT.editChangeInsurer = function ()
{
    var policy_insurer = $("#agentreport_insurer").val();
    if (policy_insurer != "")
    {
        $.ajax(
                {
                    url: "/agentreport/getinsurercontract",
                    data: "policy_insurer=" + encodeURIComponent(policy_insurer),
                    success: function (data)
                    {
                        if (data != null && data.status == '1')
                        {
                            FORM.loadOptions('#insurercontract_oid', data.insurercontract_oid);
                        } else
                        {
                            if (data != null && data.error != null)
                            {
                                DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                            } else
                            {
                                DIB.alert(LOCALE.get('DIB.common.errorsavingdata'), LOCALE.get('DIB.COMMON.Whoops'));
                            }
                            return;
                        }
                    },
                    error: function ()
                    {
                        DIB.alert(LOCALE.get('DIB.common.errorsavingdata'), LOCALE.get('DIB.COMMON.Whoops'));
                        return;
                    }
                });
    } else
    {
        $("#insurercontract_oid").html('<option value="0" selected="selected">--- ' + LOCALE.get('DIB.COMMON.NotSet') + ' ---</option>');
    }
}

AGENTREPORT.editInsurerInvoice = function (dialog_tag)
{
    DIB.progressDialog(LOCALE.get('DIB.FORM.MSG.Saving'));
    $("#form_" + dialog_tag + " input, #form_" + dialog_tag + " select, #form_" + dialog_tag + " textarea").ajaxFileUpload(
            {
                url: "/agentreport/editinsurerinvoice",
                data: {ajaxsubmit: '1', ajaxresponse: 'xml'},
                dataType: "xml",
                timeout: 600,
                success: function (data)
                {
                    var status = $('status', data).text();
                    if (status == '1')
                    {
                        DIB.reload();
                    } else
                    {
                        DIB.closeProgressDialog();
                        if (data != null)
                        {
                            errors = $('error', data).text();
                            DIB.displayErrors(errors, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.closeProgressDialog();
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}

AGENTREPORT.bankAccountFilter = function ()
{
    if ($('#DIB_report_agentreport_paymentlines_payment_type').val() != '1')
    {
        $('#DIB_report_agentreport_paymentlines_bank_account').closest('tr').hide();
    } else
    {
        $('#DIB_report_agentreport_paymentlines_bank_account').closest('tr').show();
    }
}

AGENTREPORT.removeInstallments = function ()
{
    // Check
    if ($("input.policy_installment_oid:checked").length == 0)
    {
        DIB.alert(LOCALE.get('DIB.COMMON.Error.NothingSelected'), LOCALE.get('DIB.COMMON.Whoops'));
        return;
    }

    //if (!confirm(LOCALE.get('DIB.INSURERREPORT.Action.RemoveInstallments.Confirm'))) return;

    // List
    var total = $("input.policy_installment_oid:checked").length;
    var count = 0;
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress'));
    $("input.policy_installment_oid:checked").each(function (i, val) {
        $.ajax(
                {
                    url: "/agentreport/removeinstallment",
                    data: "agentreport_installment_oid=" + this.value,
                    success: function (data)
                    {
                        count++;
                        DIB.closeProgressDialog();
                    },
                    error: function ()
                    {
                        DIB.closeProgressDialog();
                        DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    },
                    complete: function ()
                    {
                        if (count == total)
                            DIB.reload();
                    }
                });
    });
}

AGENTREPORT.removeAllInstallments = function () {
    var checked = $("input.policy_installment_oid:checked");

    if (checked.length === 0) {
        DIB.alert(LOCALE.get('DIB.COMMON.Error.NothingSelected'), LOCALE.get('DIB.COMMON.Whoops'));
        return;
    }

    var total = checked.length;
    var count = 0;

    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress'));
    checked.each(function (i, val) {
        $.ajax(
                {
                    url: "/agentreporting/removeinstallment",
                    data: "agentreport_installment_oid=" + this.value,
                    success: function (data) {
                        count++;
                        DIB.closeProgressDialog();
                    },
                    error: function () {
                        DIB.closeProgressDialog();
                        DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    },
                    complete: function () {
                        if (count == total)
                            DIB.reload();
                    }
                });
    });
};

AGENTREPORT.ADDPAYMENT = {};

AGENTREPORT.ADDPAYMENT.toggleAll = function ()
{
    if ($("#selectall:checked").length > 0)
    {
        $("input.policy_installment_oid").attr('checked', 'checked');
        $("#selectall").attr('checked', 'checked');
    } else
    {
        $("input.policy_installment_oid").removeAttr('checked');
        $("#selectall").removeAttr('checked');
    }
    DIB.fixupElements();
}

AGENTREPORT.ADDPAYMENT.calculateTotals = function ()
{
    var total_count = $("input.policy_installment_oid:checked").length;
    var total_sum = new Array();
    var total_netsum = new Array();
    var total_commission = new Array();
    $("input.policy_installment_oid:checked").each(function ()
    {
        if (typeof (total_count[$("#sum_" + $(this).val()).attr('rel')]) == 'undefined')
            total_count[$("#sum_" + $(this).val()).attr('rel')] = 0;
        if (typeof (total_sum[$("#sum_" + $(this).val()).attr('rel')]) == 'undefined')
            total_sum[$("#sum_" + $(this).val()).attr('rel')] = 0;
        if (typeof (total_netsum[$("#sum_" + $(this).val()).attr('rel')]) == 'undefined')
            total_netsum[$("#sum_" + $(this).val()).attr('rel')] = 0;
        if (typeof (total_commission[$("#sum_" + $(this).val()).attr('rel')]) == 'undefined')
            total_commission[$("#sum_" + $(this).val()).attr('rel')] = 0;
        total_sum[$("#sum_" + $(this).val()).attr('rel')] += parseFloat($("#sum_" + $(this).val()).attr('class'));
        total_netsum[$("#netsum_" + $(this).val()).attr('rel')] += parseFloat($("#netsum_" + $(this).val()).attr('class'));
        total_commission[$("#commission_" + $(this).val()).attr('rel')] += parseFloat($("#commission_" + $(this).val()).attr('class'));
    });
    $("#sum_total").html('');
    for (var c in total_sum)
    {
        $("#sum_total").html($("#sum_total").html() + '<div>' + sprintf("%.02f", total_sum[c]) + ' ' + c + '</div>');
    }
    if ($("#sum_total").html() == '')
    {
        $("#sum_total").html('0.00');
    }
    $("#netsum_total").html('');
    for (var c in total_netsum)
    {
        $("#netsum_total").html($("#netsum_total").html() + '<div>' + sprintf("%.02f", total_netsum[c]) + ' ' + c + '</div>');
    }
    if ($("#netsum_total").html() == '')
    {
        $("#netsum_total").html('0.00');
    }
    $("#commission_total").html('');
    for (var c in total_commission)
    {
        $("#commission_total").html($("#commission_total").html() + '<div>' + sprintf("%.02f", total_commission[c]) + ' ' + c + '</div>');
    }
    if ($("#commission_total").html() == '')
    {
        $("#commission_total").html('0.00');
    }

    $("#total_count").text(total_count);
}

AGENTREPORT.selectAll = function ()
{
    if ($("#policy_installment_oid_checkall").is(':checked'))
    {
        $("input.policy_installment_oid").attr('checked', 'checked');
    } else
    {
        $("input.policy_installment_oid").removeAttr('checked');
    }

    DIB.fixupElements();
}

AGENTREPORT.addInstallments = function (agentreport)
{
    // Check
    if ($("input.policy_installment_oid:checked").length == 0)
    {
        DIB.alert(LOCALE.get('DIB.GENERATEINVOICE.Error.NothingSelected'), LOCALE.get('DIB.COMMON.Whoops'));
        return;
    }

    // List
    var installmentlist = '';
    $("input.policy_installment_oid:checked").each(function (i, val)
    {
        installmentlist = installmentlist + (installmentlist != '' ? ',' : '') + $(val).val();
    });

    // Req
    DIB.progressDialog();
    $.ajax(
            {
                url: agentreport.url,
                data: "policy_installment_oid=" + encodeURIComponent(installmentlist) + "&consolidate=" + encodeURIComponent($("#consolidate").val()) + "&agentreport_oid=" + agentreport.oid,
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        DIB.redirect(agentreport.redirect);
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.closeProgressDialog();
                            DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.closeProgressDialog();
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        }

                        return;
                    }
                },
                error: function ()
                {
                    DIB.closeProgressDialog();
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}



//
//  Arved
//

INVOICE = {};

INVOICE.updateInvoice = function (invoice_oid, confirm_message, progress_message)
{
    var invoice_template = $("#invoice_template :selected").val();
    DIB.doAjaxAction("/invoice/updateinvoice", {invoice_oid: invoice_oid, invoice_template: invoice_template}, confirm_message, 1, progress_message);
}

INVOICE.ADDROW = {};

INVOICE.ADDROW.search = function ()
{
    var invoice_oid = $("#invoice_oid").val();
    var searchval = $("#invoice_addrow_search").val();
    if (searchval == "")
    {
        alert(LOCALE.get('DIB.QUICKSEARCH.Error.Empty'));
        return;
    }
    $("#invoice_addrow_results").empty();
    $.ajax(
            {
                url: "/invoice/addrowsearch",
                data: "invoice_oid=" + encodeURIComponent(invoice_oid) + "&search=" + encodeURIComponent(searchval),
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        $("#invoice_addrow_results").html(data.content);
                        DIB.centerDialog();
                        DIB.initPopover();
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            alert(data.error);
                        } else
                        {
                            alert(LOCALE.get('DIB.QUICKSEARCH.Error'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    alert(LOCALE.get('DIB.QUICKSEARCH.Error'));
                    return;
                }
            });
}

INVOICE.ADDROW.searchKeyPress = function (evt)
{
    if ((jQuery.browser.msie && event.keyCode == 13) || evt.which == 13)
    {
        INVOICE.ADDROW.search();
        if (jQuery.browser.msie)
            evt.returnValue = false;
        else
            evt.preventDefault();
        return;
    }
}

//
//  Arvete genereerimine
//

GENERATEINVOICE = {};

GENERATEINVOICE.selectAll = function ()
{
    if ($("#policy_installment_oid_checkall").is(':checked'))
    {
        $("input.policy_installment_oid").attr('checked', 'checked');
    } else
    {
        $("input.policy_installment_oid").removeAttr('checked');
    }

    DIB.fixupElements();
}

GENERATEINVOICE.generateInvoices = function ()
{
    // Check
    if ($("input.policy_installment_oid:checked").length == 0)
    {
        DIB.alert(LOCALE.get('DIB.GENERATEINVOICE.Error.NothingSelected'), LOCALE.get('DIB.COMMON.Whoops'));
        return;
    }

    // List
    var installmentlist = '';
    $("input.policy_installment_oid:checked").each(function (i, val)
    {
        installmentlist = installmentlist + (installmentlist != '' ? ',' : '') + $(val).val();
    });

    // Req
    DIB.progressDialog(LOCALE.get('DIB.GENERATEINVOICE.Action.Generate.Progress'));
    $.ajax(
            {
                url: "/generateinvoice/generate",
                data: "policy_installment_oid=" + encodeURIComponent(installmentlist) + "&consolidate=" + encodeURIComponent($("#consolidate").val()),
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        DIB.closeProgressDialog();
                        DIB.alert(sprintf(LOCALE.get('DIB.GENERATEINVOICE.Success'), data.invoicecnt), LOCALE.get('DIB.GENERATEINVOICE.Title'), true);
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.closeProgressDialog();
                            DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.closeProgressDialog();
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.closeProgressDialog();
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}


//
//  Arvete saatmine
//

SENDINVOICE = {};

SENDINVOICE.selectAll = function ()
{
    if ($("#invoice_oid_checkall").is(':checked'))
    {
        $("div.check_invoice:visible input.invoice_oid").attr('checked', 'checked');
    } else
    {
        $("input.invoice_oid").removeAttr('checked');
    }
    DIB.fixupElements();
}

SENDINVOICE.toggleSnail = function (invoice_oid)
{
    if ($("#check_" + invoice_oid).is(':visible'))
    {
        $("#check_" + invoice_oid).hide();
        $("#snail_" + invoice_oid).show();
    } else
    {
        $("#snail_" + invoice_oid).hide();
        $("#check_" + invoice_oid).show();
        $("#invoice_oid_" + invoice_oid).attr('checked', 'checked');
    }
}

SENDINVOICE.togglePhone = function (invoice_oid)
{
    var checkInvoice = $("#check_" + invoice_oid);
    var phoneInvoice = $("#phone_" + invoice_oid);

    if (checkInvoice.is(':visible')) {
        checkInvoice.hide();
        phoneInvoice.show();
    } else {
        checkInvoice.show();
        phoneInvoice.hide();
        $("#invoice_oid_" + invoice_oid).attr('checked', 'checked');
    }
}

SENDINVOICE.changeType = function ()
{
    var sendinvoice_type = $("#sendinvoice_type").val();
    DIB.doAjaxAction('/sendinvoice/changetype', {type: sendinvoice_type}, null, true, LOCALE.get('DIB.COMMON.Progress'));
}

SENDINVOICE.mailInvoices = function ()
{
    // Check
    if ($("input.invoice_oid:checked").length == 0)
    {
        DIB.alert(LOCALE.get('DIB.SENDINVOICE.Error.NothingSelected'), LOCALE.get('DIB.COMMON.Whoops'));
        return;
    }

    // List
    var invoicelist = '';
    $("input.invoice_oid:checked").each(function (i, val) {
        invoicelist = invoicelist + (invoicelist != '' ? ',' : '') + $(val).val();
    });

    // Req
    DIB.progressDialog(LOCALE.get('DIB.SENDINVOICE.Action.Email.Progress'));
    $.ajax(
            {
                url: "/sendinvoice/email",
                data: "invoice_oid=" + encodeURIComponent(invoicelist) + "&template=" + encodeURIComponent($("#template").val()),
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        DIB.closeProgressDialog();
                        DIB.alert(data.content, LOCALE.get('DIB.SENDINVOICE.Title'), true);
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.closeProgressDialog();
                            DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.closeProgressDialog();
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.closeProgressDialog();
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}

SENDINVOICE.printInvoices = function ()
{
    // Check
    if ($("input.invoice_oid:checked").length == 0)
    {
        DIB.alert(LOCALE.get('DIB.SENDINVOICE.Error.NothingSelected'), LOCALE.get('DIB.COMMON.Whoops'));
        return;
    }

    // List
    var invoicelist = '';
    $("input.invoice_oid:checked").each(function (i, val) {
        invoicelist = invoicelist + (invoicelist != '' ? ',' : '') + $(val).val();
    });

    // Submit
    $("#print_invoice").val(invoicelist);
    $("#print_form")[0].submit();
}


//
//  Võlamenetlus
//

DEBTMANAGEMENT = {};

DEBTMANAGEMENT.selectAll = function ()
{
    if ($("#notice_oid_checkall").is(':checked'))
    {
        $("input.notice_oid").attr('checked', 'checked');
    } else
    {
        $("input.notice_oid").removeAttr('checked');
    }
    DEBTMANAGEMENT.calculateTotals();
    DIB.fixupElements();
}

DEBTMANAGEMENT.toggleSnail = function (notice_oid)
{
    if ($("#check_" + notice_oid).is(':visible'))
    {
        $("#check_" + notice_oid).hide();
        $("#snail_" + notice_oid).show();
    } else
    {
        $("#snail_" + notice_oid).hide();
        $("#check_" + notice_oid).show();
        $("#notice_oid_" + notice_oid).attr('checked', 'checked');
    }
}

DEBTMANAGEMENT.togglePhone = function (notice_oid)
{
    var checkNotice = $("#check_" + notice_oid);
    var phoneNotice = $("#phone_" + notice_oid);

    if (checkNotice.is(':visible')) {
        checkNotice.hide();
        phoneNotice.show();
    } else {
        checkNotice.show();
        phoneNotice.hide();
        $("#notice_oid_" + notice_oid).attr('checked', 'checked');
    }
}

DEBTMANAGEMENT.calculateTotals = function ()
{
    var total_sum = new Array();
    $("input.notice_oid:checked").each(function ()
    {
        if (!total_sum[$(this).attr("data-currency")])
            total_sum[$(this).attr("data-currency")] = 0;
        if ($(this).attr('data-sum'))
            total_sum[$(this).attr("data-currency")] += parseFloat($(this).attr('data-sum'));
    });
    $("#sum_total").html('');
    for (var c in total_sum)
    {
        $("#sum_total").html($("#sum_total").html() + '<div>' + sprintf("%.02f", total_sum[c]) + ' ' + c + '</div>');
    }
    if ($("#sum_total").html() == '')
    {
        $("#sum_total").html('0.00');
    }
}

DEBTMANAGEMENT.changeType = function ()
{
    var debtmanagement_type = $("#debtmanagement_type").val();
    DIB.doAjaxAction('/debtmanagement/changetype', {type: debtmanagement_type}, null, true, LOCALE.get('DIB.COMMON.Progress'));
}

DEBTMANAGEMENT.mailNotices = function ()
{
    // Check
    if ($("input.notice_oid:checked").length == 0)
    {
        DIB.alert(LOCALE.get('DIB.DEBTMANAGEMENT.Error.NothingSelected'), LOCALE.get('DIB.COMMON.Whoops'));
        return;
    }

    // List
    var noticelist = '';
    $("input.notice_oid:checked").each(function (i, val) {
        noticelist = noticelist + (noticelist != '' ? ',' : '') + $(val).val();
    });

    // Req
    DIB.progressDialog(LOCALE.get('DIB.DEBTMANAGEMENT.Action.Email.Progress'));
    $.ajax(
            {
                url: "/debtmanagement/email",
                data: "type=" + encodeURIComponent($("#type").val()) + "&template=" + encodeURIComponent($("#template").val()) + "&notice_oid=" + encodeURIComponent(noticelist),
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        DIB.closeProgressDialog();
                        DIB.alert(data.content, LOCALE.get('DIB.DEBTMANAGEMENT.Title'), true);
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.closeProgressDialog();
                            DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.closeProgressDialog();
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.closeProgressDialog();
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}

DEBTMANAGEMENT.printNotices = function ()
{
    // Check
    if ($("input.notice_oid:checked").length == 0)
    {
        DIB.alert(LOCALE.get('DIB.DEBTMANAGEMENT.Error.NothingSelected'), LOCALE.get('DIB.COMMON.Whoops'));
        return;
    }

    // List
    var noticelist = '';
    $("input.notice_oid:checked").each(function (i, val) {
        noticelist = noticelist + (noticelist != '' ? ',' : '') + $(val).val();
    });

    // Submit
    $("#print_notice").val(noticelist);
    $("#print_form")[0].submit();
}


//
//  Payments functionality
//

PAYMENT = {};

PAYMENT.LINKEDINVOICES = {};

PAYMENT.LINKEDINVOICES.addInvoice = function ()
{
    $("#noinvoices").hide();
    var uniqid = Math.floor(Math.random() * 100000 + 1);
    var row = '<tr class="invoicerow" id="invoice_' + uniqid + '">';
    row += '<td style="width: 700px; padding: 10px 20px 10px 0px">';
    row += '<div style="display: block; overflow: hidden">';
    row += '<input type="hidden" id="invoice_oid_' + uniqid + '" name="invoice_oid[' + uniqid + ']" value="" />';
    row += '<div id="invoice_oid_' + uniqid + '_display" style="display: block; float: left; padding: 6px 0px 6px 28px; background: url(../Images/icon-invoice.png) left center no-repeat">&nbsp;</div>';
    row += '<div style="float: right"><button type="button" onclick="CHOOSEINVOICE.openDialog(\'invoice_oid_' + uniqid + '\')">' + LOCALE.get('DIB.COMMON.Choose') + '</button></div>';
    row += '</div>';
    row += '</td>';
    row += '<td style="width: 130px; text-align: center" ><input class="totalfld" id="payment_invoice_sum_' + uniqid + '" name="payment_invoice_sum[' + uniqid + ']" rel="invoice_oid_' + uniqid + '" type="text" style="width: 125px; text-align: center" value="" onchange="PAYMENT.LINKEDINVOICES.recalcTotal()" /></td>';
    row += '<td style="width: 30px; padding: 10px 0px 10px 10px"><a onclick="PAYMENT.LINKEDINVOICES.removeInvoice(\'' + uniqid + '\')"><img src="../Images/icon-delete.png" title="' + LOCALE.get('DIB.PAYMENT.LinkedInvoices.RemoveInvoice') + '" /></a></td>';
    row += '</tr>';
    $('#pmt_total').before(row);
    if ($("tr.invoicerow").length == 1)
    {
        $('#payment_invoice_sum_' + uniqid).val($("#sum_total").attr('rel'));
    }
    DIB.centerDialog();
}

PAYMENT.LINKEDINVOICES.removeInvoice = function (payment_linkedinvoice_oid, payment_oid)
{
    if (!confirm(LOCALE.get('DIB.PAYMENT.Action.Unlink.Invoice.Confirm')))
        return;

    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));

    $.ajax(
            {
                url: "/payment/deletelinkedinvoice",
                data: "payment_linkedinvoice_oid=" + encodeURIComponent(payment_linkedinvoice_oid) + "&payment_oid=" + payment_oid,
                success: function (data)
                {
                    DIB.closeProgressDialog();
                    if (data != null && data.status == '1')
                    {
                        DIB.reload();
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.common.errorsavingdata'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.alert(LOCALE.get('DIB.common.errorsavingdata'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}

PAYMENT.LINKEDINVOICES.recalcTotal = function ()
{

    var total_sum = 0;

    $("input.totalfld").each(function ()
    {
        total_sum += parseFloat($(this).val());
    });
    total_sum = Math.round(total_sum, 2);
    if (sprintf("%.02f", total_sum) == 'NaN')
    {
        $("#sum_total").attr('data-sum', '');
        $("#sum_total").text(LOCALE.get('DIB.COMMON.Error'));
        $("#sum_total").css('color', '#FF484F');
    } else
    {
        $("#sum_total").attr('data-sum', sprintf("%.02f", total_sum));
        $("#sum_total").text(sprintf("%.02f", total_sum) + ' ' + $("#sum_total").attr('class'));
        if (total_sum <= $("#sum_total").attr('rel'))
        {
            $("#sum_total").css('color', '#009200');
        } else
        {
            $("#sum_total").css('color', '#FF484F');
        }
    }
}

PAYMENT.LINKEDINVOICES.recalcInvoiceTotalSum = function ()
{

    var total_sum = 0;

    $("input.totalfld_row").each(function ()
    {
        total_sum += parseFloat($(this).val());
    });
    total_sum = Math.round(total_sum, 2);
    if (sprintf("%.02f", total_sum) == 'NaN')
    {
        $("#sum_total").attr('data-sum', '');
        $("#sum_total").text(LOCALE.get('DIB.COMMON.Error'));
        $("#sum_total").css('color', '#FF484F');
    } else
    {
        $("#payment_invoice_sum").val(sprintf("%.02f", total_sum));
        $("#sum_total").attr('data-sum', sprintf("%.02f", total_sum));
        $("#sum_total").text(sprintf("%.02f", total_sum) + ' ' + $("#sum_total").attr('class'));
        if (total_sum <= $("#sum_total").attr('rel'))
        {
            $("#sum_total").css('color', '#009200');
        } else
        {
            $("#sum_total").css('color', '#FF484F');
        }
    }
}

PAYMENT.LINKEDCUSTOMERS = {};
/*
 PAYMENT.LINKEDCUSTOMERS.addCustomer = function()
 {
 $("#nocustomers").hide();
 var uniqid=Math.floor(Math.random()*100000+1);
 var row='<tr class="customerrow" id="customer_'+uniqid+'">';
 row+='<td style="width: 700px; padding: 10px 20px 10px 0px">';
 row+='<div style="display: block; overflow: hidden">';
 row+='<input type="hidden" id="customer_oid_'+uniqid+'" name="customer_oid['+uniqid+']" value="" />';
 row+='<div id="customer_oid_'+uniqid+'_display" style="display: block; float: left; padding: 6px 0px 6px 28px; background: url(../Images/icon-customer.png) left center no-repeat">&nbsp;</div>';
 row+='<div style="float: right"><button type="button" onclick="CHOOSECUSTOMER.openDialog(\'customer_oid_'+uniqid+'\')">'+LOCALE.get('DIB.COMMON.Choose')+'</button></div>';
 row+='</div>';
 row+='</td>';
 row+='<td style="width: 130px; text-align: center" ><input class="totalfld" id="payment_customer_sum_'+uniqid+'" name="payment_customer_sum['+uniqid+']" type="text" style="width: 125px; text-align: center" value="" onchange="PAYMENT.LINKEDCUSTOMERS.recalcTotal()" /></td>';
 row+='<td style="width: 30px; padding: 10px 0px 10px 10px"><a onclick="PAYMENT.LINKEDCUSTOMERS.removeCustomer(\''+uniqid+'\')"><img src="../Images/icon-delete.png" title="'+LOCALE.get('DIB.PAYMENT.LinkedCustomers.RemoveCustomer')+'" /></a></td>';
 row+='</tr>';
 $('#pmt_total').before(row);
 if ($("tr.customerrow").length==1)
 {
 $('#payment_customer_sum_'+uniqid).val($("#sum_total").attr('rel'));
 }
 PAYMENT.LINKEDCUSTOMERS.recalcTotal();
 DIB.centerDialog();
 }*/
PAYMENT.LINKEDCUSTOMERS.addCustomer = function (payment_oid, customer_oid)
{
    $.ajax(
            {
                url: "/payment/linkcustomers",
                data: "payment_oid=" + payment_oid + "&customer_oid=" + customer_oid,
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        //DIB.reload();
                    } else
                    {

                        if (data != null && data.error != null)
                        {
                            DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.common.errorsavingdata'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.alert(LOCALE.get('DIB.common.errorsavingdata'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
    PAYMENT.LINKEDCUSTOMERS.recalcTotal();
    DIB.centerDialog();
}




/*
 PAYMENT.LINKEDCUSTOMERS.removeCustomer = function( id )
 {
 if (!confirm(LOCALE.get('DIB.LIST.Confirm2'))) return;
 $("#customer_"+id).remove();
 if ($("tr.customerrow").length==0)
 {
 $("#nocustomers").show();
 }
 DIB.centerDialog();
 PAYMENT.LINKEDCUSTOMERS.recalcTotal();
 }*/

PAYMENT.LINKEDCUSTOMERS.removeCustomer = function (customer_oid, payment_oid)
{
    if (!confirm(LOCALE.get('DIB.LIST.Confirm2')))
        return;
    $.ajax(
            {
                url: "/payment/deletelinkedcustomer",
                data: "customer_oid=" + encodeURIComponent(customer_oid) + "&payment_oid=" + payment_oid,
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        DIB.reload();
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.common.errorsavingdata'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.alert(LOCALE.get('DIB.common.errorsavingdata'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}


PAYMENT.LINKEDCUSTOMERS.recalcTotal = function ()
{
    var total_sum = 0;
    $("input.totalfld").each(function ()
    {
        if ($(this).val() != '')
        {
            total_sum += parseFloat($(this).val());
        }
    });
    total_sum = Math.round(total_sum, 2);
    if (sprintf("%.02f", total_sum) == 'NaN')
    {
        $("#sum_total").text(LOCALE.get('DIB.COMMON.Error'));
        $("#sum_total").css('color', '#FF484F');
    } else
    {
        $("#sum_total").text(sprintf("%.02f", total_sum) + ' ' + $("#sum_total").attr('class'));
        if (total_sum <= $("#sum_total").attr('rel'))
        {
            $("#sum_total").css('color', '#009200');
        } else
        {
            $("#sum_total").css('color', '#FF484F');
        }
    }
}


PAYMENT.LINKEDOFFERS = {};

PAYMENT.LINKEDOFFERS.removeOffer = function (offer_oid, payment_oid)
{
    if (!confirm(LOCALE.get('DIB.LIST.Confirm2')))
        return;
    $.ajax(
            {
                url: "/payment/deletelinkoffers",
                data: "offer_oid=" + encodeURIComponent(offer_oid) + "&payment_oid=" + payment_oid,
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        DIB.reload();
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.common.errorsavingdata'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.alert(LOCALE.get('DIB.common.errorsavingdata'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}

PAYMENT.LINKEDINSTALLMENTS = {};

PAYMENT.LINKEDINSTALLMENTS.removeInstallment = function (policy_installment_oid, payment_oid)
{
    if (!confirm(LOCALE.get('DIB.LIST.Confirm2')))
        return;
    $.ajax(
            {
                url: "/payment/deletelinkedinstallment",
                data: "policy_installment_oid=" + encodeURIComponent(policy_installment_oid) + "&payment_oid=" + payment_oid,
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        DIB.reload();
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.common.errorsavingdata'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.alert(LOCALE.get('DIB.common.errorsavingdata'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}


PAYMENT.LINKEDINSURERINVOICES = {};

PAYMENT.LINKEDINSURERINVOICES.addInvoice = function ()
{
    $("#noinsurerinvoices").hide();
    var uniqid = Math.floor(Math.random() * 100000 + 1);
    var row = '<tr class="insurerinvoicerow" id="insurerinvoice_' + uniqid + '">';
    row += '<td style="width: 700px; padding: 10px 20px 10px 0px">';
    row += '<div style="display: block; overflow: hidden">';
    row += '<input type="hidden" id="insurerinvoice_oid_' + uniqid + '" name="insurerinvoice_oid[' + uniqid + ']" value="" />';
    row += '<div id="insurerinvoice_oid_' + uniqid + '_display" style="display: block; float: left; padding: 6px 0px 6px 28px; background: url(../Images/icon-invoice.png) left center no-repeat">&nbsp;</div>';
    row += '<div style="float: right"><button type="button" onclick="CHOOSEINSURERINVOICE.openDialog(\'insurerinvoice_oid_' + uniqid + '\')">' + LOCALE.get('DIB.COMMON.Choose') + '</button></div>';
    row += '</div>';
    row += '</td>';
    row += '<td style="width: 130px; text-align: center" ><input class="totalfld" id="payment_insurerinvoice_sum_' + uniqid + '" name="payment_insurerinvoice_sum[' + uniqid + ']" rel="insurerinvoice_oid_' + uniqid + '" type="text" style="width: 125px; text-align: center" value="" onchange="PAYMENT.LINKEDINSURERINVOICES.recalcTotal()" /></td>';
    row += '<td style="width: 30px; padding: 10px 0px 10px 10px"><a onclick="PAYMENT.LINKEDINSURERINVOICES.removeInvoice(\'' + uniqid + '\')"><img src="../Images/icon-delete.png" title="' + LOCALE.get('DIB.PAYMENT.LinkedInvoices.RemoveInvoice') + '" /></a></td>';
    row += '</tr>';
    $('#pmt_total').before(row);
    if ($("tr.insurerinvoicerow").length == 1)
    {
        $('#payment_insurerinvoice_sum_' + uniqid).val($("#sum_total").attr('rel'));
    }
    DIB.centerDialog();
}
/*
 PAYMENT.LINKEDINSURERINVOICES.removeInvoice = function( id )
 {
 if (!confirm(LOCALE.get('DIB.LIST.Confirm2'))) return;
 $("#insurerinvoice_"+id).remove();
 if ($("tr.insurerinvoicerow").length==0)
 {
 $("#noinsurerinvoices").show();
 }
 DIB.centerDialog();
 PAYMENT.LINKEDINSURERINVOICES.recalcTotal();
 }*/

PAYMENT.LINKEDINSURERINVOICES.removeInvoice = function (payment_insurerinvoice_oid, payment_oid)
{
    if (!confirm(LOCALE.get('DIB.LIST.Confirm2')))
        return;
    $.ajax(
            {
                url: "/payment/deletelinkedinsurerinvoice",
                data: "payment_insurerinvoice_oid=" + encodeURIComponent(payment_insurerinvoice_oid) + "&payment_oid=" + payment_oid,
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        DIB.reload();
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.common.errorsavingdata'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.alert(LOCALE.get('DIB.common.errorsavingdata'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}

PAYMENT.LINKEDINSURERINVOICES.addInvoice = function (payment_oid)
{
    if (!confirm(LOCALE.get('DIB.LIST.Confirm2')))
        return;
    $.ajax(
            {
                url: "/payment/payment",
                data: "payment_oid=" + payment_oid,
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        DIB.reload();
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.common.errorsavingdata'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.alert(LOCALE.get('DIB.common.errorsavingdata'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}


PAYMENT.LINKEDINSURERINVOICES.recalcTotal = function ()
{
    var total_sum = 0;
    $("input.totalfld").each(function ()
    {
        if ($(this).val() != '')
        {
            total_sum += parseFloat($(this).val());
        }
    });
    total_sum = Math.round(total_sum, 2);
    if (sprintf("%.02f", total_sum) == 'NaN')
    {
        $("#sum_total").text(LOCALE.get('DIB.COMMON.Error'));
        $("#sum_total").css('color', '#FF484F');
    } else
    {
        $("#sum_total").text(sprintf("%.02f", total_sum) + ' ' + $("#sum_total").attr('class'));
        if (total_sum <= $("#sum_total").attr('rel'))
        {
            $("#sum_total").css('color', '#009200');
        } else
        {
            $("#sum_total").css('color', '#FF484F');
        }
    }
}


PAYMENT.LINKEDINSURERREPORTS = {};

PAYMENT.LINKEDINSURERREPORTS.removeReport = function (payment_insurerreport_oid, payment_oid)
{
    if (!confirm(LOCALE.get('DIB.LIST.Confirm2')))
        return;
    $.ajax(
            {
                url: "/payment/deletelinkedinsurerreport",
                data: "payment_insurerreport_oid=" + encodeURIComponent(payment_insurerreport_oid) + "&payment_oid=" + payment_oid,
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        DIB.reload();
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.common.errorsavingdata'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.alert(LOCALE.get('DIB.common.errorsavingdata'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}

PAYMENT.LINKEDINSURERREPORTS.recalcTotal = function ()
{
    var total_sum = 0;
    $("input.totalfld").each(function ()
    {
        if ($(this).val() != '')
        {
            total_sum += parseFloat($(this).val());
        }
    });
    total_sum = Math.round(total_sum, 2);
    if (sprintf("%.02f", total_sum) == 'NaN')
    {
        $("#sum_total").text(LOCALE.get('DIB.COMMON.Error'));
        $("#sum_total").css('color', '#FF484F');
    } else
    {
        $("#sum_total").text(sprintf("%.02f", total_sum) + ' ' + $("#sum_total").attr('class'));
        if (total_sum <= $("#sum_total").attr('rel'))
        {
            $("#sum_total").css('color', '#009200');
        } else
        {
            $("#sum_total").css('color', '#FF484F');
        }
    }
}


PAYMENTIMPORT = {};

PAYMENTIMPORT.changeSource = function ()
{
    var paymentimport_source = $("#paymentimport_source").val();
    if (paymentimport_source != "")
    {
        DIB.progressDialog(LOCALE.get('DIB.FORM.MSG.Querying'));
        $.ajax(
                {
                    url: "/paymentimport/getbankaccountlist",
                    data: "paymentimport_source=" + encodeURIComponent(paymentimport_source),
                    success: function (data)
                    {
                        DIB.closeProgressDialog();
                        if (data != null && data.status == '1')
                        {
                            FORM.setValues(data.bankaccountdata);
                        } else
                        {
                            if (data != null && data.error != null)
                            {
                                DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                            } else
                            {
                                DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                            }
                            return;
                        }
                    },
                    error: function ()
                    {
                        DIB.closeProgressDialog();
                        DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        return;
                    }
                });
    } else
    {
        $("#bankaccount_oid").html('<option value="0" selected="selected">--- ' + LOCALE.get('DIB.PAYMENTIMPORT.Fld.BankAccount.ChooseSource') + ' ---</option>');
    }
}


//
//  Sularahakviitungid
//

CASHRECEIPT = {};

/**
 * Calculate and update total cash receipts sum.
 *
 * @param elem
 */
CASHRECEIPT.updateCashReceiptsSum = function (elem) {
    var totals = {};

    var cashReceiptsSumElement = $('.total.js_cash_receipts_sum div').html();

    var checkedCashReceiptParts = $(elem).parent().parent().nextAll().find('.js_cash_receipt_sum').text().split(' ');

    if ('undefined' !== typeof cashReceiptsSumElement) {
        $.each(cashReceiptsSumElement.split('<br>'), function (key, value) {
            var parts = value.split(' ');

            totals[parts[1]] = parseFloat(parts[0]);
        });
    }

    if ('undefined' !== typeof totals[checkedCashReceiptParts[1]]) {
        if ($(elem).is(':checked')) {
            totals[checkedCashReceiptParts[1]] += parseFloat(checkedCashReceiptParts[0]);
        } else {
            totals[checkedCashReceiptParts[1]] -= parseFloat(checkedCashReceiptParts[0]);
        }
    } else {
        totals[checkedCashReceiptParts[1]] = parseFloat(checkedCashReceiptParts[0]);
    }

    this.displayCashReceiptsSumHtml(this.getCashReceiptTotalSumHtml(totals));
};

/**
 * Returns total sum of cash receipts.
 *
 * @returns {string}
 */
CASHRECEIPT.getCashReceiptTotalSum = function () {
    var totals = {};

    $('.js_cash_receipt_sum').each(function () {
        var parts = $(this).text().split(' ');

        totals[parts[1]] = (totals[parts[1]] || 0) + parseFloat(parts[0]);
    });

    return this.getCashReceiptTotalSumHtml(totals);
};

/**
 * Converts object to html.
 *
 * @param {Object} totals
 *
 * @returns {String}
 */
CASHRECEIPT.getCashReceiptTotalSumHtml = function (totals) {
    var html = '';

    $.each(totals, function (key, value) {
        if ('undefined' === key || 'undefined' === value) {
            return;
        }

        html += value + ' ' + key + '<br>';
    });

    return html.slice(0, -4);
};

/**
 * Displays html string to the DOM element having .total & .js_cash_receipts_sum classes.
 *
 * @param html
 */
CASHRECEIPT.displayCashReceiptsSumHtml = function (html) {
    $('.total.js_cash_receipts_sum').html('<div style="font-weight: bold;">' + html + '</div>');
};

CASHRECEIPT.selectAll = function ()
{
    if ($("#cashreceipt_oid_checkall").is(':checked'))
    {
        this.displayCashReceiptsSumHtml(this.getCashReceiptTotalSum());
        $("input.cashreceipt_oid").attr('checked', 'checked');
    } else
    {
        this.displayCashReceiptsSumHtml('');
        $("input.cashreceipt_oid").removeAttr('checked');
    }

    DIB.fixupElements();
}

CASHRECEIPT.markAsTransferredToBank = function ()
{
    // Check
    if ($("input.cashreceipt_oid:checked").length == 0)
    {
        DIB.alert(LOCALE.get('DIB.COMMON.Error.NothingSelected'), LOCALE.get('DIB.COMMON.Whoops'));
        return;
    }

    // List
    var cashreceiptlist = [];
    $("input.cashreceipt_oid:checked").each(function (i, val)
    {
        cashreceiptlist.push($(val).val());
    });


    // Req
    if (!confirm(LOCALE.get('DIB.CASHRECEIPT.Confirm.MarkAsTransferredToBank')))
        return;
    DIB.progressDialog(LOCALE.get('DIB.CASHRECEIPT.Action.Generate.Progress'));
    $.ajax(
            {
                url: "/cashreceipt/markastransferred",
                data: {
                    cashreceipt_oids: cashreceiptlist,
                    cash_receipt_status: $('#cash_receipt_status_set').val()
                },
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        DIB.reload();

                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.closeProgressDialog();
                            DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.closeProgressDialog();
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.closeProgressDialog();
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}


CASHRECEIPT.cancelReceipt = function (cashreceipt_oid)
{
    var buttons = {};
    buttons[LOCALE.get('DIB.FORM.Btn.Cancel')] = {
        buttonAction: function ()
        {
            $("#DIB_cashreceipt_cancel").dialog('close');
            $("#DIB_cashreceipt_cancel").remove();
        },
        buttonClass: "primary"
    };
    buttons[LOCALE.get('DIB.No')] = {
        buttonAction: function ()
        {
            CASHRECEIPT.cancelReceiptSubmit(cashreceipt_oid, '0');
        }
    }
    buttons[LOCALE.get('DIB.Yes')] = {
        buttonAction: function ()
        {
            CASHRECEIPT.cancelReceiptSubmit(cashreceipt_oid, '1');
        }
    }
    $("#DIB_cashreceipt_cancel").remove();
    $('body').append('<div id="DIB_cashreceipt_cancel" title="' + LOCALE.get('DIB.CASHRECEIPT.Action.Cancel') + '" style="padding: 20px 10px; display:none">' + LOCALE.get('DIB.CASHRECEIPT.Cancel.RemoveAccrual') + '</div>');
    $("#DIB_cashreceipt_cancel").dialog({
        width: '500px',
        resizable: false,
        bgiframe: true,
        modal: true,
        buttons: buttons
    });
}

CASHRECEIPT.edit = function (cashreceipt_oid)
{
    if ($("#cashreceipt_status").val() == 21)
    {
        DIB.alert(LOCALE.get('DIB.CASHRECEIPT.Confirm.MarkAsCancel'), LOCALE.get('DIB.CASHRECEIPT.Action.Cancel'));
    }
}

CASHRECEIPT.cancelReceiptSubmit = function (cashreceipt_oid, remove_accrual)
{
    $.ajax(
            {
                url: "/cashreceipt/cancel",
                data: "cashreceipt_oid=" + encodeURIComponent(cashreceipt_oid) + "&remove_accrual=" + encodeURIComponent(remove_accrual),
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        DIB.reload();
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.common.errorsavingdata'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.alert(LOCALE.get('DIB.common.errorsavingdata'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}

PAYMENTEDIT = {};
PAYMENTEDIT.changeType = function ()
{
    if ($('#payment_type').val() == 2)
    {
        $('#create_cashreceipt').prop('checked', true);
    } else
    {
        $('#create_cashreceipt').prop('checked', false);
    }
    DIB.fixupElements();
};

PAYMENTEDIT.changeCashReceipt = function ()
{
    if (!$('#create_cashreceipt').is(':checked'))
    {
        var buttons = {};
        buttons[LOCALE.get('DIB.FORM.BTN.Ok')] = {
            buttonAction: function ()
            {
                $("#DIB_cashreceipt_cancel_notification").dialog('close').remove();
                DIB.fixupElements();
            }
        };

        $('body').append('<div id="DIB_cashreceipt_cancel_notification" title="' + LOCALE.get('DIB.CASHRECEIPT.Title.Cashreceipt') + '" style="padding: 20px 10px; display:none">' + LOCALE.get('DIB.CASHRECEIPT.Confirm.DontCreate') + '</div>');
        $("#DIB_cashreceipt_cancel_notification").dialog({
            width: '500px',
            resizable: false,
            bgiframe: true,
            modal: true,
            buttons: buttons,
            close: function ()
            {
                // häkk
                $('#create_cashreceipt').prop('checked', false);
            }
        })
    }
    DIB.fixupElements();
};


//
//  Export
//

EXPORT = {};

EXPORT.INVOICE = {};

EXPORT.INVOICE.selectAll = function ()
{
    if ($("#invoice_oid_checkall").is(':checked'))
    {
        $("input.invoice_oid").attr('checked', 'checked');
    } else
    {
        $("input.invoice_oid").removeAttr('checked');
    }
    DIB.fixupElements();
}

EXPORT.INVOICE.exportInvoices = function ()
{
    // Check
    if ($("input.invoice_oid:checked").length == 0)
    {
        DIB.alert(LOCALE.get('DIB.SENDINVOICE.Error.NothingSelected'), LOCALE.get('DIB.COMMON.Whoops'));
        return;
    }

    // List
    var invoicelist = '';
    $("input.invoice_oid:checked").each(function (i, val) {
        invoicelist = invoicelist + (invoicelist != '' ? ',' : '') + $(val).val();
    });

    // Submit
    $("#export_invoice").val(invoicelist);
    $("#export_form")[0].submit();
}


//
//  Pakkumised
//

OFFER = {};

OFFER.renewalOffer = function (offer_type, renewal_policy_oid, renewal_product, mark_status)
{
    DIB.closeConfirmDialog();
    DIB.doPostSubmit('/offer/new/' + offer_type, {renewal_policy_oid: renewal_policy_oid, renewal_product: renewal_product, mark_policy_renewal: mark_status, policy_end_date_to_offer: 0});
};

OFFER.createRenewalOffer = function (offer_type, renewal_policy_oid, renewal_product)
{
    var buttons = {};
    buttons[LOCALE.get('DIB.COMMON.Yes')] = {
        buttonAction: function () {
            OFFER.renewalOffer(offer_type, renewal_policy_oid, renewal_product, 1);
        },
        buttonClass: "primary"
    };
    buttons[LOCALE.get('DIB.COMMON.No')] = {
        buttonAction: function () {
            OFFER.renewalOffer(offer_type, renewal_policy_oid, renewal_product, 0);
        }
    };
    DIB.confirmDialog(LOCALE.get('DIB.OFFER.Action.CreateRenewalOffer.MarkPolicyInRenewal'), LOCALE.get('DIB.OFFER.Action.CreateRenewalOffer'), buttons);
}

OFFER.sendToReferral = function (operation, content_url, dialog_width)
{
    var offer_oid = $("#offer_oid").val();
    var chtag_binder_oid = $("#prop_product_binder").val();
    $("#DIB-form").ajaxSubmit({
        data: {ajaxsubmit: '1', operation: 'prevalidate'},
        success: function (data)
        {
            if (data != null && data.status == '1')
            {
                DIB.openEditDialog(content_url, {offer_oid: offer_oid, chtag_binder_oid: chtag_binder_oid}, dialog_width);
            } else
            {
                if (data.error != null && data.error != '')
                {
                    if (typeof (data.error) == 'object')
                    {
                        var errors = '<b>' + LOCALE.get('DIB.OFFER.Calc.ErrorCheckingData') + ':</b>';
                        for (var k in data.error)
                        {
                            errors = errors + '<br/> &#0149; ' + data.error[k];
                        }
                        DIB.displayErrors(errors, LOCALE.get('DIB.COMMON.Whoops'));
                    } else
                    {
                        DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                    }
                } else
                {
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorCheckingData'), LOCALE.get('DIB.COMMON.Whoops'));
                }
            }
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData') + ' - ' + thrownError + ' - ' + xhr.responseText, LOCALE.get('DIB.COMMON.Whoops'));
            return;
        }
    });
}

OFFER.openEditDialog = function (operation, content_url, dialog_width, extra_data, extra_buttons, no_standard_save)
{
    var offer_oid = $("#offer_oid").val();
    $("#DIB-form").ajaxSubmit({
        data: {ajaxsubmit: '1', action: 'popupsave', operation: operation},
        beforeSerialize: function (form) {
            DIB.AUTONUMERIC.beforeSubmit(form);
        },
        success: function (data)
        {
            if (data != null && data.status == '1')
            {
                DIB.openEditDialog(content_url, {offer_oid: offer_oid, extra_data: extra_data}, dialog_width, extra_buttons, no_standard_save);
            } else
            {
                if (data.error != null && data.error != '')
                {
                    if (typeof (data.error) == 'object')
                    {
                        var errors = '<b>' + LOCALE.get('DIB.OFFER.Calc.ErrorCheckingData') + ':</b>';
                        for (var k in data.error)
                        {
                            errors = errors + '<br/> &#0149; ' + data.error[k];
                        }
                        DIB.displayErrors(errors, LOCALE.get('DIB.COMMON.Whoops'), data.error);
                    } else
                    {
                        DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                    }
                } else
                {
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorCheckingData'), LOCALE.get('DIB.COMMON.Whoops'));
                }
            }
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData') + ' - ' + thrownError + ' - ' + xhr.responseText, LOCALE.get('DIB.COMMON.Whoops'));
            return;
        },
        complete: function () {
            DIB.AUTONUMERIC.afterSubmit();
        }
    });
}

OFFER.showRequest = function (insurerrequest_oid)
{
    if ($(".info_" + insurerrequest_oid + ":visible").length)
    {
        $("tr.info_" + insurerrequest_oid).hide();
        $("#img_" + insurerrequest_oid).attr('class', 'icon-arrow-closed2');
    } else
    {
        $("tr.info_" + insurerrequest_oid).show();
        $("#img_" + insurerrequest_oid).attr('class', 'icon-arrow-open');
    }
}

OFFER.copyOffer = function (action_url, action_data, copyWithPricesOption) {
    copyWithPricesOption = copyWithPricesOption === undefined ? true : copyWithPricesOption;

    var copyWithoutPricesObject = {
        buttonAction: function () {
            action_data.copyprices = 0;
            DIB.doAjaxAction(action_url, action_data);
        },
        buttonClass: "primary"
    };

    var buttons = {};
    if (copyWithPricesOption) {
        buttons[LOCALE.get('DIB.OFFER.Action.Copy.ConfirmPricesNo')] = copyWithoutPricesObject;

        buttons[LOCALE.get('DIB.OFFER.Action.Copy.ConfirmPricesYes')] = {
            buttonAction: function () {
                action_data.copyprices = 1;
                DIB.doAjaxAction(action_url, action_data);
            }
        };
    } else {
        buttons[LOCALE.get('DIB.COMMON.Yes')] = copyWithoutPricesObject;
    }

    buttons[LOCALE.get('DIB.FORM.BTN.Cancel')] = {
        buttonAction: function () {
            DIB.closeConfirmDialog();
        }
    };

    var dialogMessage = LOCALE.get('DIB.OFFER.Action.Copy.ConfirmPrices');
    if (!copyWithPricesOption) {
        dialogMessage = LOCALE.get('DIB.OFFER.Action.Copy.Sure');
    }

    DIB.confirmDialog(dialogMessage, LOCALE.get('DIB.OFFER.Action.Copy'), buttons);
}

OFFER.disableButtons = function () {
    $('#page-offer button').each(function () {
        $(this).addClass('disabled');
        $(this).attr('disabled', true);
        ;
    });
}

OFFER.enableButtons = function () {
    var found = false;
    $("input[name*='_status_']").each(function () {
        if ($(this).parent().find('span.loader').length != 0) {
            found = true;
            return false;
        }
    });

    if (found == false) {
        $('#page-offer button').each(function () {
            $(this).removeClass('disabled');
            $(this).attr('disabled', false);
            ;
        });
    }

}

OFFER.calculateDiscount = function (data) {

    var offer_oid = $("#offer_oid").val();
    var insurer = data.data.insurer;

    // Check calcmode
    var calcmode = $("#prop_casco_calcmode_" + insurer).val();
    if (calcmode != '0')
        return;

    // Check calc ID
    var prev_calculationid = $('#prop_casco_calculationid_' + insurer).val();
    if (prev_calculationid == null || prev_calculationid == "undefined" || prev_calculationid == "" && insurer == "insta")
    {
        DIB.alert(LOCALE.get('DIB.CASCO.Error.ReCalc'), LOCALE.get('DIB.COMMON.Whoops'));
        return;
    }

    // Do calc
    $("#DIB-form").ajaxSubmit({
        url: '/offer/calc/vehicle',
        data: {ajaxsubmit: '1', action: 'discount', product: 'casco', insurer: insurer},
        success: function (data)
        {
            $(".casco_statusfield img").remove();
            $(".casco_statusfield input").css('display', '');

            if (data != null && data.status == '1')
            {
                var insurerData;

                insurerData = data;

                // OK
                if (insurerData.status == '1')
                {
                    $("#prop_casco_status_" + insurer).html('OK');

                    // kui hinnad tulid, siis selekteerime esimese kahe optioni kohta kaks makseperioodi
                    $("input[name='prop_casco_showpmt_1']").attr('checked', true);
                    $("input[name='prop_casco_showpmt_2']").attr('checked', true);
                    $("input[name='prop_casco_showpmt_4']").attr('checked', true);
                    $("input[name='prop_casco_showpmt_12']").attr('checked', true);

                    // Maksed
                    if (insurerData['pmt_1'] != null)
                        $("#prop_casco_pmt_" + insurer + "_1").val(insurerData['pmt_1']);
                    if (insurerData['pmt_2'] != null)
                        $("#prop_casco_pmt_" + insurer + "_2").val(insurerData['pmt_2']);
                    if (insurerData['pmt_4'] != null)
                        $("#prop_casco_pmt_" + insurer + "_4").val(insurerData['pmt_4']);
                    if (insurerData['pmt_12'] != null)
                        $("#prop_casco_pmt_" + insurer + "_12").val(insurerData['pmt_12']);

                    // Check
                    if (insurerData['pmt_1'] != null)
                        $("#prop_casco_show_" + insurer).removeAttr('disabled').attr('checked', 'checked');

                    // Warnings/messages
                    if (insurerData.warning != null && insurerData.warning != "")
                        $("#prop_casco_msg_" + insurer).html('<b>' + insurer.toUpperCase() + '</b>: ' + data['prop_casco_msg_' + insurer]).show();

                    // Clauses
                    if (insurerData.req != null && insurerData.req != "")
                    {
                        $("#prop_casco_req_" + insurer).val('<b>' + insurer.toUpperCase() + '</b>: ' + insurerData.req);
                        $("#prop_casco_req_" + insurer + "_disp").html('<b>' + insurer.toUpperCase() + '</b>: ' + insurerData.req).show();
                        //$("#prop_casco_req_"+insurer+"_disp").removeClass('hidden');
                    }

                    // Quote texts
                    for (var t in insurerData.offertext)
                        $('#' + t).val(insurerData.offertext[t]);

                    // Params
                    for (var p in insurerData.param)
                        $("#" + p).val(insurerData.param[p]);

                    if (data.asked != null && data.asked != "")
                        DIB.alert(data.asked, 'Info');
                }
                // not OK
                else
                {
                    $("#prop_casco_status_" + insurer).val(LOCALE.get('DIB.COMMON.Error'));
                    if (insurerData.error != null && insurerData.error != '')
                    {
                        $("#prop_casco_msg_" + insurer).val(insurerData.error);
                        $("#prop_casco_msg_" + insurer + "_disp").html('<b>' + insurer.toUpperCase() + ':</b> ' + insurerData.error);
                        $("#prop_casco_msg_" + insurer + "_disp").show();
                    }
                }

                $('#prop_casco_calculationid').val(data.prop_casco_calculationid);
                $('#prop_casco_calculationid_disp').html(data.prop_casco_calculationid);
            } else
            {
                if (data.error != null && data.error != '')
                {
                    DIB.alert(LOCALE.get('DIB.OFFER.Calc.ErrorCalculatingPrices') + ': ' + data.error, LOCALE.get('DIB.COMMON.Whoops'));
                } else
                {
                    DIB.alert(LOCALE.get('DIB.OFFER.Calc.ErrorCalculatingPrices'), LOCALE.get('DIB.COMMON.Whoops'));
                }
            }
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
            $(".casco_statusfield img").remove();
            $(".casco_statusfield input").css('display', '');
            DIB.alert(LOCALE.get('DIB.OFFER.Calc.ErrorCalculatingPrices') + ' - ' + thrownError + ' - ' + xhr.responseText, LOCALE.get('DIB.COMMON.Whoops'));
            return;
        }
    });
}

OFFER.customerResponse = function (field)
{
    if (field.value != '')
    {
        FORM.showFields('.accept', '.accept\$val', field);

        $('select[id*="accept_"]').each(function (index) {
            if ($('#' + this.id + ' option').size() == '3' || $('#' + this.id + ' option').size() == '2')
            {
                $('#' + this.id + ' option:eq(1)').attr('selected', 'selected');
            }
        });
    } else
    {
        FORM.hideFields('.accept', '.accept\$val', field);
    }
}

OFFER.MAIL = {};

OFFER.MAIL.changeType = function ()
{
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
    $.ajax({
        url: '/offer/mailtemplate',
        data: "what=type&offer_oid=" + encodeURIComponent($("#offer_oid").val()) + "&mail_type=" + encodeURIComponent($("#mail_type").val()),
        success: function (data)
        {
            DIB.closeProgressDialog();
            if (data != null && data.status == '1')
            {
                if (data.mail_template != null)
                {
                    FORM.loadOptions('#mail_template', data.mail_template);
                    if (Object.elementCount(data.mail_template) > 1)
                    {
                        $("#field_send_template").show();
                    } else
                    {
                        $("#field_send_template").hide();
                    }
                }
                $("#mail_subject").val(data.mail_subject);
                $("#mail_content").val(data.mail_content);
                $('#field_send_content div.summernote').code(data.mail_content);
            } else
            {
                if (data != null && data.error != null)
                {
                    DIB.displayErrors(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                } else
                {
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                }
                return;
            }
        },
        error: function ()
        {
            DIB.closeProgressDialog();
            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
            return;
        }
    });
}

OFFER.MAIL.changeTemplate = function ()
{
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
    $.ajax({
        url: '/offer/mailtemplate',
        data: "what=template&offer_oid=" + encodeURIComponent($("#offer_oid").val()) + "&mail_template=" + encodeURIComponent($("#mail_template").val()),
        success: function (data)
        {
            DIB.closeProgressDialog();
            if (data != null && data.status == '1')
            {
                $("#mail_subject").val(data.mail_subject);
                $("#mail_content").val(data.mail_content);
            } else
            {
                if (data != null && data.error != null)
                {
                    DIB.displayErrors(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                } else
                {
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                }
                return;
            }
        },
        error: function ()
        {
            DIB.closeProgressDialog();
            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
            return;
        }
    });
}

OFFER.changeSalesChannel = function (field)
{
    if (!confirm(LOCALE.get('DIB.OFFER.Action.ChangeSalesChannel.Confirm')))
    {
        $(field).val($(field).attr('data-previous'));
        return;
    }

    FORM.submit();
    $(field).val($(field).attr('data-previous'));
}

OFFER.setPreviousSalesChannel = function (field)
{
    $(field).attr('data-previous', $(field + " :selected").val());
}


/**
 * Calculate MTA total days based on the MTA dates supplied.
 */

OFFER.updateMTATotalDays = function ()
{
    $.ajax({
        url: '/helper/calcmtatotaldays',

        data: {
            mta_begindate: $('#prop_mta_begindate').val(),
            mta_enddate: $('#prop_mta_enddate').val()
        },

        success: function (data)
        {
            if (data != null && data.status == '1')
            {
                $("#prop_mta_totaldays").val(data.totaldays);
            }
        },

        error: function ()
        {
            return;
        }

    });
};

OFFER.changeMTAType = function ()
{
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
    $.ajax({
        url: '/helper/changeMTAType',
        data: "offer_oid=" + encodeURIComponent($("#offer_oid").val()) + "&prop_mta_type=" + encodeURIComponent($("#prop_mta_type").val()),
        success: function (data)
        {
            if (data != null && data.status == '1') {
                DIB.reload();
            }
            DIB.closeProgressDialog();
        },
        error: function ()
        {
            DIB.closeProgressDialog();
            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
            return;
        }
    });
};

OFFER.OTHER = {};

OFFER.OTHER.offer_oid = new String();

OFFER.OTHER.addDocument = function (offer_oid)
{
    OFFER.OTHER.offer_oid = offer_oid;
    FORM.submit(null, null, function (data) {
        DIB.openEditDialog('/document/edit', {ref_oid: OFFER.OTHER.offer_oid, type: 'offer'});
    });
}

OFFER.OTHER.changeProduct = function ()
{
    if ($("tr[id^=field_prop_productfield_]").length > 0)
    {
        if (!confirm('Change product? This will delete the current product fields along with its content.'))
        {
            $("#prop_offer_product").val($("#prop_offer_product").attr('data-currentvalue'));
            return;
        }
    }
    $("#prop_offer_product").attr('data-currentvalue', $("#prop_offer_product").val());
    $("tr[id^=field_prop_productfield_]").remove();
    $("tr[id^=row_prop_home_]").remove();
    $(".matrix_row_tax").hide();

    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
    $.ajax({
        url: '/offer/getproductfields',
        data: "offer_oid=" + encodeURIComponent($("#offer_oid").val()) + "&product=" + encodeURIComponent($("#prop_offer_product").val()),
        success: function (data)
        {
            DIB.closeProgressDialog();
            if (data != null && data.status == '1')
            {
                $("#prop_offer_product").attr('data-currentvalue', $("#prop_offer_product").val());
                $("tr[id^=field_prop_productfield_]").remove();
                $("#field_prop_offer_product").after(data.content);
                $('#other_quote_add_object').attr(
                        'onclick',
                        "OFFER.OTHER.addObject('" + $("#offer_oid").val() + "', '" + $("#prop_offer_product").val() + "');"
                        );

                if (data.object_type != null && data.object_type != "") {
                    $('#fieldgroup_title_objects').parent().show();
                    $('#object_search').attr(
                            'onkeydown',
                            "INLINESEARCH.searchKeypress('object_search', 'object', '" + data.object_type + "', 'object' , event, '{\"minimum_length\":3,\"load_function\":\"OBJECT.loadObject\"}');"
                            ).next('button').attr(
                            'onclick',
                            "INLINESEARCH.search('object_search', 'object', '" + data.object_type + "', 'object', '{\"minimum_length\":3,\"load_function\":\"OBJECT.loadObject\"}');"
                            );
                    $('#allowed_type').val(data.object_type);
                    $('#objects_table tr.datarow').hide();
                    $('#no_data').show();
                } else {
                    $('#fieldgroup_title_objects').parent().hide();
                }

                FORM.setDatePicker("input:text.datefield[id^=prop_productfield_]");
                FORM.setAutoComplete("select.autocomplete[id^=prop_productfield_]");
                DIB.fixupElements();
            } else
            {
                $("#prop_offer_product").val($("#prop_offer_product").attr('data-currentvalue'));
                $('#fieldgroup_title_objects').parent().hide();
                if (data != null && data.error != null)
                {
                    DIB.displayErrors(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                } else
                {
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                }
                return;
            }
        },
        error: function ()
        {
            DIB.closeProgressDialog();
            $("#prop_offer_product").val($("#prop_offer_product").attr('data-currentvalue'));
            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
            return;
        }
    });
};

OFFER.OTHER.calcPrices = function ()
{
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
    $(".offercalc button").prop('disabled', true);

    $("#DIB-form").ajaxSubmit({
        url: '/calculator/check',
        data: {
            ajaxsubmit: '1',
            action: 'check',
            product: 'other'
        },
        success: function (data)
        {
            if (data != null && data.status == '1') {
                $("input[name^='prop_other_show_']").each(function () {
                    OFFER.OTHER.calcInsurer($(this).attr('id').replace(/prop_other_show_/, ''));
                });
            } else if (data.error != null && typeof (data.error) == 'object') {
                var errors = "";
                for (var fld_tag in data.error) {
                    if (data.error.hasOwnProperty(fld_tag)) {
                        errors = errors + '<br/> &#0149; ' + data.error[fld_tag];
                    }
                }
                DIB.displayErrors(errors, LOCALE.get('DIB.COMMON.Whoops'), data.error);
            } else {
                DIB.alert(LOCALE.get('DIB.COMMON.ErrorCheckingData'), LOCALE.get('DIB.COMMON.Whoops'));
            }
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
            DIB.alert(
                    LOCALE.get('DIB.COMMON.ErrorSavingData') +
                    ' - ' +
                    thrownError +
                    ' - ' +
                    xhr.responseText,
                    LOCALE.get('DIB.COMMON.Whoops')
                    );
            return;
        }
    });

    $(document).ajaxStop(function () {
        $(".offercalc button").prop('disabled', false);
        DIB.closeProgressDialog();
    });
};

OFFER.OTHER.calcInsurer = function (insurer)
{
    $("#DIB-form").ajaxSubmit({
        url: '/calculator/calculate',
        data: {
            ajaxsubmit: '1',
            action: 'calc',
            product: 'other',
            insurer: insurer
        },
        success: function (data)
        {
            if (data != null && data.status == '1' && data.param != null) {
                for (var k in data.param) {
                    if (data.param[k] != null && $("input#" + k).length > 0) {
                        $("#" + k).val(data.param[k]);
                    }
                }
                DIB.fixupElements();
            } else if (data.error != null && data.error != '') {
                DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
            } else {
                DIB.alert(LOCALE.get('DIB.COMMON.ErrorCheckingData'), LOCALE.get('DIB.COMMON.Whoops'));
            }
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
            DIB.alert(
                    LOCALE.get('DIB.COMMON.ErrorSavingData') +
                    ' - ' +
                    thrownError +
                    ' - ' +
                    xhr.responseText,
                    LOCALE.get('DIB.COMMON.Whoops')
                    );
            return;
        }
    });
};

OFFER.OTHER.addObject = function (offer_oid, offer_product)
{
    DIB.openEditDialog('/object/quoteedit', {offer_oid: offer_oid, offer_product: offer_product});
};


//
//  CRM
//

CRM = {};

CRM.TASK = {};

CRM.TASK.checkReminderDate = function (idCheckField, idDateField, dateValue)
{
    var checked = $('#' + idCheckField).attr('checked');

    $('#field_snoozereminder').hide();
    $('#field_snoozeto').hide();
    $('#field_reject_reason').hide();

    $('#crm_entry_reminder_tstamp_minute').hide();
    $('#crm_entry_reminder_tstamp_minute').next().hide();

    $('#crm_entry_reminder_tstamp_hour').hide();
    $('#crm_entry_reminder_tstamp_hour').next().hide();

    if (checked)
    {
        $('#' + idDateField).show();
        $('#' + idDateField).next().show();

        $('#crm_entry_reminder_tstamp_hour').show();
        $('#crm_entry_reminder_tstamp_hour').next().show();

        $('#crm_entry_reminder_tstamp_minute').show();
        $('#crm_entry_reminder_tstamp_minute').next().show();
    } else if (dateValue == '')
    {
        $('#' + idDateField).hide();
        $('#' + idDateField).next().hide();
    }
}

CRM.TASK.filterTasks = function (param, oid)
{
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));

    $.ajax({
        url: '/crm/filtertasks',
        data: "status=" + encodeURIComponent($('#filter_status').val()) + "&type=" + encodeURIComponent($('#filter_type').val()) +
                "&param=" + encodeURIComponent(param) + "&oid=" + encodeURIComponent(oid),
        success: function (data)
        {
            $('#panel-entries-block .panel-heading .badge').html(data.total);
            $('#entries-block .panel-heading .badge').html(data.total);
            $('#entries-list').replaceWith(data.items);

            DIB.closeProgressDialog();
        },
        error: function ()
        {
            DIB.closeProgressDialog();
            DIB.alert(LOCALE.get('DIB.FORM.ERROR.ErrorOpeningDialog'), LOCALE.get('DIB.COMMON.Whoops'));
            return;
        }
    });
}

CRM.TASK.clearFilters = function ()
{
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));

    $('#filter_status').val('1');
    $('#filter_type').val('');

    DIB.closeProgressDialog();
};


CRM.TASK.openEditDialog = function (content_url, content_data, dialog_width, buttonsetName)
{
    var dialog_tag = Math.floor(Math.random() * 100000 + 1);
    if (dialog_width == null)
        dialog_width = 600;
    if (content_data == null)
    {
        content_data = {'tag': dialog_tag};
    } else
    {
        content_data.tag = dialog_tag;
    }

    var getButtonByTitle = function (buttonName) {
        return $('.ui-dialog-buttonpane button:contains("' + buttonName + '")').button();
    };

    $.ajax({
        url: content_url,
        data: content_data,
        success: function (data)
        {
            if (data != null && data.status == '1')
            {
                $('body').append('<div id="dialog_' + dialog_tag + '" style="display:none">' + data.content + '</div>');
                var submitbuttontitle = LOCALE.get('DIB.FORM.Btn.Save');
                if (data.submit != null && data.submit != "")
                {
                    submitbuttontitle = data.submit;
                }
                var progressmessage = LOCALE.get('DIB.FORM.Msg.Saving');
                if (data.progressmessage != null && data.progressmessage != "")
                {
                    progressmessage = data.progressmessage;
                }
                var submithandler = null;
                if (data.submithandler != null && data.submithandler != "")
                {
                    submithandler = data.submithandler;
                }
                FORM.setDatePicker("#form_" + dialog_tag + " input:text.datefield");
                FORM.setAutoComplete("#form_" + dialog_tag + " select.autocomplete");
                DIB.fixupElements("#form_" + dialog_tag + " input");

                var buttons = {};
                if (buttonsetName == 'complete')
                {
                    buttons[LOCALE.get('DIB.CRM.Action.CloseReminderNew')] = {
                        buttonAction: function ()
                        {
                            CRM.TASK.closeReminderWithNewTaskPopUp(dialog_tag);
                        },
                        buttonClass: 'primary'
                    };
                    buttons[LOCALE.get('DIB.CRM.Action.CloseReminder')] = {
                        buttonAction: function ()
                        {
                            CRM.TASK.closeReminderWithPageReload(dialog_tag)
                        },
                        buttonClass: 'primary'
                    };

                    buttons[LOCALE.get('DIB.CRM.Action.SnoozeReminder')] = {
                        buttonAction: function ()
                        {
                            if ($("#reminder_operation").val() == 'snooze')
                            {
                                DIB.progressDialog(LOCALE.get('DIB.FORM.Msg.Saving'));
                                $.ajax({
                                    url: '/crm/snoozetask',
                                    data: "crm_entry_oid=" + encodeURIComponent($('#crm_entry_oid').val()) + "&snoozeto=" + encodeURIComponent($('#snoozeto').val()),
                                    success: function (data)
                                    {
                                        if (data != null && data.status == '1')
                                        {
                                            $("#dialog_" + dialog_tag).dialog('close').remove();
                                            DIB.reload();
                                        } else
                                        {
                                            DIB.closeProgressDialog();
                                            if (data != null && data.error != null)
                                            {
                                                DIB.displayErrors(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                                            } else
                                            {
                                                DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                                            }
                                            return;
                                        }
                                    },
                                    error: function ()
                                    {
                                        DIB.closeProgressDialog();
                                        DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                                        return;
                                    }
                                });
                            } else
                            {
                                var hideButtons = [
                                    LOCALE.get('DIB.CRM.Action.CloseReminderNew'),
                                    LOCALE.get('DIB.CRM.Action.CloseReminder'),
                                    LOCALE.get('DIB.CRM.Action.SnoozeReminder'),
                                    LOCALE.get('DIB.CRM.Action.DeleteTask'),
                                    LOCALE.get('DIB.CRM.Action.RejectReminder'),
                                ];

                                hideButtons.forEach(function (element) {
                                    getButtonByTitle(element).hide();
                                });

                                $('#field_snoozereminder').show();
                                $('#field_snoozeto').show();

                                $("#reminder_operation").val('snooze');
                            }
                        }
                    };
                }
                if (buttonsetName == 'complete' ||
                        buttonsetName == 'save' ||
                        buttonsetName == undefined)
                {
                    buttons[LOCALE.get('DIB.CRM.Action.DeleteTask')] = {
                        buttonAction: function ()
                        {
                            CRM.TASK.deleteButtonAction(dialog_tag);
                        }
                    };
                    buttons[submitbuttontitle] = {
                        buttonAction: function () {
                            CRM.TASK.saveButtonAction(dialog_tag, progressmessage, submithandler)
                        },
                        buttonClass: 'primary'
                    }
                }
                if (buttonsetName == 'save') {
                    buttons[LOCALE.get('DIB.CRM.Action.Reopen')] = {
                        buttonAction: function () {
                            CRM.TASK.reopenButtonAction()
                        },
                        buttonClass: 'primary'
                    }
                }

                if (buttonsetName === 'complete') {
                    var rejectButtonTitle = LOCALE.get('DIB.CRM.Action.RejectReminder');
                    buttons[rejectButtonTitle] = {
                        buttonAction: function () {
                            var $reminderOperation = $("#reminder_operation");
                            var rejectOperation = 'reject';
                            if ($reminderOperation.val() === rejectOperation) {
                                $reminderOperation.val('');
                                CRM.TASK.closeReminder(
                                        dialog_tag,
                                        12,
                                        {
                                            "crm_entry_reject_reason": $('#reject_reason').val()
                                        }
                                ).done(function (data) {
                                    DIB.reload();
                                });
                            } else {
                                $('#field_reject_reason').show().focus();
                                $('.ui-dialog-buttonset button').hide();
                                getButtonByTitle(rejectButtonTitle).show();
                                getButtonByTitle(LOCALE.get('DIB.FORM.Btn.Cancel')).show();

                                $reminderOperation.val(rejectOperation);
                            }
                        },
                        buttonClass: 'primary'
                    }
                }

                //can only review
                if (buttonsetName === 'reject') {
                    buttons = {};
                }

                buttons[LOCALE.get('DIB.FORM.Btn.Cancel')] = {
                    buttonAction: function () {
                        CRM.TASK.cancelButtonAction(dialog_tag)
                    }
                };
                $("#dialog_" + dialog_tag).dialog({
                    title: (data.title != null ? data.title : LOCALE.get('DIB.SystemTitle')),
                    resizable: false,
                    bgiframe: true,
                    modal: true,
                    width: dialog_width + 'px',
                    buttons: buttons,
                    close: function (event, ui)
                    {
                        $("#dialog_" + dialog_tag).remove();
                    }
                });
                $("#dialog_" + dialog_tag).dialog('open');
                if (data.displaytrigger != null && data.displaytrigger != "")
                {
                    eval(data.displaytrigger);
                }
                DIB.centerDialog();
                DIB.initPopover();
                DIB.fixupElements();
                if ($("#form_" + dialog_tag + " .defaultfocus").length)
                {
                    $("#form_" + dialog_tag + " .defaultfocus")[0].focus();
                } else
                {
                    if ($("#form_" + dialog_tag + " input").length)
                        $("#form_" + dialog_tag + " input")[0].focus();
                }

                $('button:contains(' + LOCALE.get('DIB.CRM.Action.DeleteTask') + ')').after('<br>');

                if (content_data != undefined && content_data.crm_entry_oid != undefined)
                {
                    $('#date_' + content_data.crm_entry_oid).removeClass('unread');
                    $('#title_' + content_data.crm_entry_oid).removeClass('unread');
                }
            } else
            {
                if (data != null && data.error != null)
                {
                    if (typeof (data.error) == 'object')
                    {
                        DIB.displayErrors(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                    } else
                    {
                        DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                    }
                } else
                {
                    DIB.alert(LOCALE.get('DIB.FORM.ERROR.ErrorOpeningDialog'), LOCALE.get('DIB.COMMON.Whoops'));
                }
                return;
            }
        },
        error: function ()
        {
            DIB.alert(LOCALE.get('DIB.FORM.ERROR.ErrorOpeningDialog'), LOCALE.get('DIB.COMMON.Whoops'));
            return;
        }
    });
};

CRM.TASK.deleteButtonAction = function (dialog_tag)
{
    if (!confirm(LOCALE.get('DIB.CRM.Action.DeleteTask.Confirm')))
        return;

    DIB.progressDialog(LOCALE.get('DIB.FORM.Msg.Saving'));
    $.ajax({
        url: '/crm/deletetask',
        data: "crm_entry_oid=" + encodeURIComponent($('#crm_entry_oid').val()),
        success: function (data)
        {
            if (data != null && data.status == '1')
            {
                $("#dialog_" + dialog_tag).dialog('close').remove();
                DIB.reload();
            } else
            {
                DIB.closeProgressDialog();
                if (data != null && data.error != null)
                {
                    DIB.displayErrors(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                } else
                {
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                }
                return;
            }
        },
        error: function ()
        {
            DIB.closeProgressDialog();
            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
            return;
        }
    });
};

CRM.TASK.cancelButtonAction = function (dialog_tag)
{
    $('#dialog_' + dialog_tag).dialog('close');
};

CRM.TASK.closeReminderWithNewTaskPopUp = function (dialog_tag) {
    CRM.TASK.closeReminder(dialog_tag, 11, {})
            .done(function (data) {
                DIB.openEditDialog('/crm/addtask', {
                    'customer_oid': data.customer_oid,
                    'customer_name': data.customer_name,
                    'offer_oid': data.offer_oid,
                    'policy_oid': data.policy_oid,
                    'claim_oid': data.claim_oid,
                    'crm_project_oid': data.crm_project_oid
                }, 800);
            });
};

CRM.TASK.closeReminderWithPageReload = function (dialog_tag) {
    CRM.TASK.closeReminder(dialog_tag, 11, {})
            .done(function (data) {
                DIB.reload();
            });
};

CRM.TASK.closeReminder = function (dialog_tag, closeStatus, additionalData) {
    DIB.progressDialog(LOCALE.get('DIB.FORM.Msg.Saving'));

    additionalData = typeof additionalData !== 'undefined' ? additionalData : {};

    var defer = $.Deferred();

    $.ajax({
        url: '/crm/closetask',
        data: Object.assign(
                {
                    "crm_entry_oid": encodeURIComponent($('#crm_entry_oid').val()),
                    "crm_entry_status": closeStatus
                },
                additionalData
                ),
        success: function (data) {
            if (data != null && data.status == '1') {
                $("#dialog_" + dialog_tag).dialog('close').remove();
                DIB.closeProgressDialog();

                defer.resolve(data);
            } else {
                DIB.closeProgressDialog();
                if (data != null && data.error != null) {
                    DIB.displayErrors(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                } else {
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                }
                defer.reject();
                return;
            }
        },
        error: function () {
            DIB.closeProgressDialog();
            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
            defer.reject();
            return;
        }
    });

    return defer.promise();
};

CRM.TASK.reopenButtonAction = function () {
    DIB.doAjaxAction('/crm/reopentask', {crm_entry_oid: encodeURIComponent($('#crm_entry_oid').val())}, null, true, LOCALE.get('DIB.COMMON.Progress'));
};

CRM.TASK.saveButtonAction = function (dialog_tag, progressmessage, submithandler)
{
    var $dialogSelector = $("#dialog_" + dialog_tag);
    var $dialogForm = $("#form_" + dialog_tag);
    if ($dialogForm[0].action == null || $dialogForm[0].action == "")
    {
        DIB.alert(LOCALE.get('DIB.FORM.Error.CouldNotFindForm'), LOCALE.get('DIB.COMMON.Whoops'));
        return;
    }
    $dialogForm.find("td.label.error").removeClass('error');

    // Custom submithandler?
    if (submithandler != null)
    {
        eval(submithandler + '(\'' + dialog_tag + '\')');
    } else
    {
        // Progress
        if (progressmessage != '')
        {
            DIB.progressDialog(progressmessage);
        }

        $dialogForm.ajaxSubmit({
            type: "post",
            data: {ajaxsubmit: '1'},
            success: function (data)
            {
                if (data != null && data.status == '1')
                {
                    if (data.message != null && data.message != '')
                    {
                        DIB.closeProgressDialog();
                        $dialogSelector.dialog('close');
                        if (data.redirect != null && data.redirect != '')
                        {
                            DIB.alert(data.message, null, null, data.redirect);
                        } else if (data.reload != null && data.reload == '1')
                        {
                            DIB.alert(data.message, null, true);
                        }
                    } else if (data.redirect != null && data.redirect != '')
                    {
                        $dialogSelector.dialog('close');
                        DIB.redirect(data.redirect);
                    } else if (data.reload != null && data.reload == '1')
                    {
                        $dialogSelector.dialog('close');
                        DIB.reload();
                    } else
                    {
                        DIB.closeProgressDialog();
                        if (data.submitsuccessaction)
                        {
                            eval(data.submitsuccessaction);
                        }
                        $dialogSelector.dialog('close');
                    }
                } else
                {
                    DIB.closeProgressDialog();
                    var errors = '<b>' + LOCALE.get('DIB.FORM.Error.Errors') + ':</b>';
                    if (data != null && data.error != null)
                    {
                        if (typeof (data.error) == 'object')
                        {
                            for (var fld_tag in data.error)
                            {
                                if (data.error.hasOwnProperty(fld_tag))
                                {
                                    $("#field_" + fld_tag + " td.label").addClass('error');
                                    errors = errors + '<br/> &#0149; ' + data.error[fld_tag];
                                }
                            }
                            DIB.displayErrors(errors, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        }
                    } else
                    {
                        DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData') + ': 1', LOCALE.get('DIB.COMMON.Whoops'));
                    }
                    return;
                }
            },
            error: function (xhr, ajaxOptions, thrownError)
            {
                DIB.closeProgressDialog();
                DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData') + ': ' + thrownError + ' / ' + var_dump(xhr), LOCALE.get('DIB.COMMON.Whoops'));
                return;
            }
        });
    }
};

CRM.PROJECT = {};

CRM.PROJECT.filterProjects = function (id)
{
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));

    $.ajax({
        url: '/project/filter/',
        data: id + '=' + encodeURIComponent($('#' + id).val()),
        success: function (data)
        {
            $('#panel-projects_tab .panel-heading .badge').html(data.total);
            $('#panel-projects_tab .table').replaceWith(data.items);

            DIB.closeProgressDialog();
        },
        error: function ()
        {
            DIB.closeProgressDialog();
            DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
            return;
        }
    });


}

CRM.PROJECT.clearFilters = function ()
{
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));

    $('#crm_project_status').val('1');

    DIB.closeProgressDialog();
}


//
//  Statistika / graafikud
//

STATS = {};

STATS.callChart = function (chart)
{
    $.ajax({
        url: "/report/getchart/" + chart,
        dataType: 'json',
        success: function (result) {
            $("#div_" + chart).html(result.chart);
        },
        error: function (xhr, textStatus, err)
        {
            $("#div_" + chart).replaceWith(xhr.responseText);
        }
    });
}

STATS.showProgressMessage = function (chart) {
    $("#div_" + chart).html(LOCALE.get('DIB.COMMON.Progress.Loading'));
    $("#div_" + chart).css("font-size", "15px");
    $("#div_" + chart).css("text-align", "center");
    $("#div_" + chart).css("vertical-align", "middle");
}

STATS.chart = function (divname, series, options) {
    try
    {
        var plot3 = $.jqplot(divname, series, options);
    } catch (err)
    {
        $("#" + divname).html(LOCALE.get('DIB.CHARTS.Error.ErrorGeneratingChart'));
    }
    return plot3;
}


//
//	POLICYIMPORT
//

POLICYIMPORT = {};

POLICYIMPORT.mappingInsertedAction = function (row_id)
{
    $('#' + row_id).remove();
}

POLICYIMPORT.setBrokerValueOptions = function (classifier_value)
{
    var classifier;
    var filter = false;
    var optionHTML = '';

    if (classifier_value == null)
        classifier = $('#policyimport_mapping_classifier').val();
    else
    {
        classifier = classifier_value;
        filter = true;
    }

    // millal seda vaja on?
    if (classifier == 'model' && $('#make_list').val() == null)
    {
        $('#policyimport_mapping_brokervalue').attr('disabled', 'disabled');
        $('#policyimport_mapping_brokervalue').html('<option>--- ' + LOCALE.get('DIB.VEHICLE.SelectMakeFirst') + ' ---</option>');

        $.ajax(
                {
                    url: '/policyimport/brokermappingvalues',
                    data: 'classifier=make',
                    success: function (data)
                    {
                        if (data != null && data.status == '1')
                        {
                            $('#field_policyimport_mapping_insurervalue').after('<tr id="make_selection" class="field"><td class="label">' + LOCALE.get('DIB.OBJECTTYPE.VEHICLE.Fld.Make') + ':</td><td class="element"><select id="make_list" name="make_list" onchange="VEHICLE.changeMake(\'make_list\', \'policyimport_mapping_brokervalue\')"></select></td></tr>');
                            FORM.loadOptions('#make_list', data.broker_values);
                            $('#policyimport_mapping_brokervalue').removeAttr('disabled');
                        } else
                        {
                            if (data != null && data.error != null)
                            {
                                alert(data.error);
                            } else
                            {
                                alert(LOCALE.get('DIB.VEHICLE.Error.LoadingModelList'));
                            }
                            return;
                        }
                    },
                    error: function ()
                    {
                        DIB.closeProgressDialog();
                        DIB.alert(LOCALE.get('DIB.COMMON.ErrorCheckingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        return;
                    }
                });
    } else
    {
        $('#make_selection').remove();
        DIB.progressDialog(LOCALE.get('DIB.POLICYIMPORT.Mapping.Msg.LoadingBrokerValues'));

        $.ajax(
                {
                    url: '/policyimport/brokermappingvalues',
                    data: 'classifier=' + encodeURIComponent(classifier),
                    success: function (data)
                    {
                        if (data != null && data.status == '1')
                        {
                            if (!filter)
                                optionHTML += '<option value="">--- ' + LOCALE.get('DIB.COMMON.NotSet') + ' ---</option>';
                            else
                            {
                                $('select[name="filter_policyimport_mapping_brokervalue"]').html('').removeAttr('disabled');
                                optionHTML += '<option value="">--- ' + LOCALE.get('DIB.COMMON.All') + ' ---</option>';
                            }

                            for (value in data.broker_values)
                            {
                                optionHTML += '<option value="' + value + '">' + data.broker_values[value] + '</option>';
                            }

                            // SORTIMINE ON PERSSES
                            if (!filter)
                            {
                                $('#policyimport_mapping_brokervalue').html('').removeAttr('disabled');
                                $('#policyimport_mapping_brokervalue').append(optionHTML);
                            } else
                                $('select[id="filter_policyimport_mapping_brokervalue"]').append(optionHTML);
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorCheckingData'), LOCALE.get('DIB.COMMON.Whoops'));
                            return;
                        }
                        DIB.closeProgressDialog();
                    },
                    error: function ()
                    {
                        DIB.closeProgressDialog();
                        DIB.alert(LOCALE.get('DIB.COMMON.ErrorCheckingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        return;
                    }
                });
    }
}

POLICYIMPORT.showErrorData = function (policyimport_oid)
{
    var $dialog = $('#DIALOG-searchpopup');

    $.ajax(
            {
                url: '/policyimport/geterrors',
                data: "policyimport=" + encodeURIComponent(policyimport_oid),
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        if (data.results > 0)
                        {
                            $dialog.html(data.content);
                            $dialog.dialog({
                                title: LOCALE.get('DIB.POLICYIMPORT.Errors.Title'),
                                closeOnEscape: true,
                                resizable: false,
                                bgiframe: true,
                                modal: true,
                                width: 800
                            });
                        } else
                        {
                            DIB.alert(LOCALE.get('maakler.quicksearch.nothing'), LOCALE.get('DIB.FORMFIELD.CUSTOMERSEARCH.Search'));
                            return;
                        }
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}

//
//  Help
//

HELP = {};

HELP.openHelp = function ()
{
    // URL
    var context = document.location.toString();

    // Open help dialog
    $("#DIALOG-help").dialog({
        title: LOCALE.get('DIB.HELP.Title'),
        resizable: false,
        bgiframe: true,
        modal: true,
        width: 820
    });
    $("#DIALOG-help-content").html('<div class="loading"><span class="size-4 loader"></span></div>');
    $("#DIALOG-help").dialog('open');
    DIB.centerDialog();

    // Load content
    HELP.getHelp(context);
};

HELP.getHelp = function (context)
{
    $.ajax(
            {
                url: "/helper/gethelp",
                data: "context=" + encodeURIComponent(context),
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        $("#DIALOG-help-content").html(data.content);
                        DIB.centerDialog();
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            $("#DIALOG-help-content").html('<div class="alert alert-danger">' + LOCALE.get('DIB.COMMON.Error') + ': ' + data.error + '</div>');
                        } else
                        {
                            $("#DIALOG-help-content").html('<div class="alert alert-danger">' + LOCALE.get('DIB.HELP.Error.Loading') + '</div>');
                        }
                        DIB.centerDialog();
                        return;
                    }
                },
                error: function ()
                {
                    $("#DIALOG-help-content").html('<div class="alert alert-danger">' + LOCALE.get('DIB.HELP.Error.Loading') + '</div>');
                    DIB.centerDialog();
                    return;
                }
            });
};


//
//	Notifications
//

NOTIFICATION = {};
var existingTicketIDs = new Array();

NOTIFICATION.init = function ()
{
    $.extend($.gritter.options, {
        position: 'bottom-right',
        fade_in_speed: 150,
        fade_out_speed: 150,
        time: 100
    });
    setInterval(NOTIFICATION.check, 30000);
};

NOTIFICATION.check = function ()
{
    $.get('/supportticket/getactivity', function (data)
    {
        if (data != null && data.tickets != null)
        {
            $.each(data.tickets, function (i, ticket)
            {
                if ($.inArray(ticket.supportticket_oid, existingTicketIDs) == -1)
                {
                    existingTicketIDs.push(ticket.supportticket_oid);
                    $.gritter.add({
                        close: '<a class="gritter-close" href="#" tabindex="1" onclick="NOTIFICATION.setRead(\'' + ticket.supportticket_oid + '\')">' + LOCALE.get('DIB.SUPPORTTICKET.Btn.Close') + '</a>',
                        title: LOCALE.get('DIB.SUPPORTTICKET.Notification.General'),
                        text: '<a style="color:white;" href="/supportticket/view/supportticket=' + ticket.supportticket_oid + '" onclick="DIB.progressDialog(\'' + LOCALE.get('DIB.FORM.MSG.Querying') + '\')">' + ticket.supportticket_name + '</a>',
                        image: '../Images/help_48.png',
                        sticky: true,
                        time: '',
                        class_name: 'supportticket-notification-' + ticket.supportticket_oid
                    });
                }
            });
        }
    }, 'json');
};

NOTIFICATION.setRead = function (ticket_oid)
{
    $.ajax({
        url: '/supportticket/setnotificationread',
        data: "ticket=" + encodeURIComponent(ticket_oid),
        success: function (data) {},
        error: function (data) {}
    });
};


//
//  Admin functionality
//

ADMIN = {};

ADMIN.CUSTOMERPERSON = {};

ADMIN.CUSTOMERPERSON.changePerson = function (person_no)
{
    if ($("#person" + person_no + "_enabled").val() == '1')
    {
        $("#person" + person_no + "_name").removeAttr('disabled');
        $("#person" + person_no + "_name")[0].focus();
    } else
    {
        if ($("#person" + person_no + "_enabled").attr('data-confirm') == '1' && !confirm(LOCALE.get('DIB.SYSTEMSETTINGS.CUSTOMERPERSON.DisableConfirm')))
        {
            $("#person" + person_no + "_enabled").val('1');
            return;
        }
        $("#person" + person_no + "_name").val('');
        $("#person" + person_no + "_name").attr('disabled', 'disabled');
    }
}

ADMIN.CUSTOMERPERSON.checkPersons = function ()
{
    for (var i = 1; i <= 5; ++i)
    {
        if ($("#person" + i + "_enabled").val() == '1' && $("#person" + i + "_name").val() == '')
        {
            DIB.alert(sprintf(LOCALE.get('DIB.SYSTEMSETTINGS.CUSTOMERPERSON.Error.RoleNameEmpty'), i), LOCALE.get('DIB.COMMON.Whoops'));
            return false;
        }
    }
    return true;
}

ADMIN.COLLECTION = {};

ADMIN.COLLECTION.selectInsurer = function (insurer_tag)
{
    $("tr.insurer").hide();
    $("tr.insurer_" + insurer_tag).show();
    $('html, body').animate({
        scrollTop: $("#header_" + insurer_tag).offset().top - 5
    }, 100);
}

ADMIN.COLLECTION.changeAll = function (insurer, type)
{
    if ($("#" + type + "_" + insurer).val() == '-1')
        return;
    $("select[data-insurer=" + insurer + "][data-type=" + type + "]").val($("#" + type + "_" + insurer).val());
    $("#" + type + "_" + insurer).val('-1');
}

ADMIN.COMMISSION = {};

ADMIN.COMMISSION.select = function (tag)
{
    if ($("#table_" + tag + ":visible").length)
    {
        $("#table_" + tag).hide();
        $("#arrow_" + tag).attr('class', 'icon-arrow-closed2');
    } else
    {
        $("#table_" + tag).show();
        $("#arrow_" + tag).attr('class', 'icon-arrow-open');
        $('html, body').animate({
            scrollTop: $("#item_" + tag).offset().top - 5
        }, 100);
    }
    // This makes tables zebras.
    DIB.fixupStripes();
}

ADMIN.PRODUCTTYPE = {};

ADMIN.PRODUCTTYPE.init = function ()
{
    if ($("#documenttype_product_all").is(":checked"))
    {
        ADMIN.PRODUCTTYPE.selectAll();
    } else
    {
        $("input.productselect").each(function () {
            ADMIN.PRODUCTTYPE.select($(this).attr('value'));
        });
    }
}

ADMIN.PRODUCTTYPE.selectAll = function ()
{
    if ($("#documenttype_product_all").is(":checked"))
    {
        $(".productselect").attr('checked', 'checked');
        $(".productselect").attr('disabled', 'disabled');
        $(".productrequiredselect").attr('disabled', 'disabled');
        $(".productrequiredselect").val($("#documenttype_product_required_all").val());
    } else
    {
        $(".productselect").removeAttr('checked');
        $(".productselect").removeAttr('disabled');
        $(".productrequiredselect").removeAttr('disabled');
    }
    DIB.fixupElements();

}

ADMIN.PRODUCTTYPE.requiredAll = function ()
{
    if ($("#documenttype_product_all").is(":checked"))
    {
        $(".productrequiredselect").val($("#documenttype_product_required_all").val());
    }
}

ADMIN.PRODUCTTYPE.select = function (product)
{
    if ($("#documenttype_product_" + product).is(":checked"))
    {
        $("#documenttype_product_required_" + product).removeAttr('disabled');
    } else
    {
        $("#documenttype_product_required_" + product).attr('disabled', 'disabled');
    }
}

ADMIN.TEMPLATE = {};

ADMIN.TEMPLATE.changeTemplate = function ()
{
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
    $.ajax(
            {
                url: "/admin/templateparam",
                data: "template_type=" + encodeURIComponent($("#template_type").val()) + "&template_tag=" + encodeURIComponent($("#template_tag").val()),
                success: function (data)
                {
                    DIB.closeProgressDialog();
                    if (data != null && data.status == '1')
                    {
                        $("#fieldgroup_param").html(data.content);
                        DIB.fixupElements();
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.displayErrors(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.closeProgressDialog();
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}

ADMIN.LANG = {};

ADMIN.LANG.updateDefaultLanguageList = function ()
{
    var current_default_lang = $("#prop_settings_defaultlang").val();
    if ($("input[name^=prop_settings_availablelang]:checked").length > 0)
    {
        var default_lang_list = '';
        $("input[name^=prop_settings_availablelang]:checked").each(function () {
            default_lang_list = default_lang_list + '<option value="' + $(this).attr('value') + '">' + $(this).parent().text().trim() + '</option>';
        });
        $("#prop_settings_defaultlang").html(default_lang_list);
        if ($("#prop_settings_defaultlang option[value=" + current_default_lang + "]").length > 0)
        {
            $("#prop_settings_defaultlang").val(current_default_lang);
        }
    } else
    {
        $("#prop_settings_defaultlang").html('<option value="">--- '.LOCALE.get('DIB.COMMON.None') + ' ---</option>');
    }
}

ADMIN.DELETECASHRECEIPTSERIES = function (cashreceipt_series_oid)
{
    if (!confirm(LOCALE.get('DIB.SYSTEMSETTINGS.CASHRECEIPTSERIES.Action.Delete.Confirm')))
        return;
    $.ajax(
            {
                url: "/admin/cashreceiptseries_delete",
                data: "cashreceipt_series_oid=" + encodeURIComponent(cashreceipt_series_oid),
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        DIB.reload();
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.common.errorsavingdata'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.alert(LOCALE.get('DIB.common.errorsavingdata'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}

ADMIN.USER = {};

ADMIN.USER.copyRoles = function ()
{
    var copy_from = $("#copy_roles_from").val();
    if (copy_from == "")
    {
        DIB.alert(LOCALE.get('DIB.SYSTEMSETTINGS.USER.Fld.Roles.CopyRoles.Error.EmptyVal'), LOCALE.get('DIB.COMMON.Whoops'));
        return;
    }

    DIB.progressDialog(LOCALE.get('DIB.FORM.MSG.Querying'));
    $.ajax(
            {
                url: "/admin/usergetroles",
                data: "broker_person_oid=" + encodeURIComponent(copy_from),
                success: function (data)
                {
                    DIB.closeProgressDialog();
                    if (data != null && data.status == '1' && data.roledata != null)
                    {
                        $("input[id^=roleset_]").removeAttr('checked');
                        $("input[id^=roleset_]").parent().find('span').removeClass('icon-check');
                        for (var i in data.roledata)
                        {
                            $("#roleset_" + data.roledata[i]).attr('checked', 'checked');
                            $("#roleset_" + data.roledata[i]).parent().find('span').addClass('icon-check');
                        }
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.closeProgressDialog();
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}

ADMIN.OFFERTEXT = {};

ADMIN.OFFERTEXT.changeSalesChannel = function ()
{
    var salesChannel = $("#salesChannel").val();

    DIB.doAjaxAction('/offertext/changesaleschannel', {type: salesChannel}, null, true, LOCALE.get('DIB.COMMON.Progress'));
}

ADMIN.DESIGN = {};

ADMIN.DESIGN.openColorPicker = function (tag, color)
{
    var buttons = {};
    buttons[LOCALE.get('DIB.FORM.BTN.Save')] = {
        buttonAction: function ()
        {
            var selected_color = $("#colorpicker_selected").val();

            var invoicetemplate = 0;
            var pathname = $('#admin-content').attr('data-link');
            if (pathname.indexOf("invoicetemplate") > -1)
                invoicetemplate = 1;

            DIB.doAjaxAction('/admin/editcolor', {tag: tag, color: selected_color, invoice_template: invoicetemplate}, null, true);
        },
        buttonClass: "primary"
    };
    buttons[LOCALE.get('DIB.DIALOG.CloseWindow')] = {
        buttonAction: function ()
        {
            ADMIN.DESIGN.closeColorPicker();
        }
    };
    $('body').append('<div id="dialog_colorpicker" style="display:none"><input type="hidden" id="colorpicker_selected" value="' + color + '"><div id="design_colorpicker"></div></div>');
    $("#dialog_colorpicker").dialog({
        title: LOCALE.get('DIB.SYSTEMSETTINGS.DESIGN.Fld.Color.Action.Choose'),
        resizable: false,
        bgiframe: true,
        modal: true,
        width: '380px',
        buttons: buttons
    });
    $('#design_colorpicker').colpick({
        flat: true,
        color: color,
        submit: 0,
        onChange: function (hsb, hex, rgb, el, bySetColor)
        {
            $("#colorpicker_selected").val(hex);
        }
    });
}

ADMIN.DESIGN.closeColorPicker = function ()
{
    $("#dialog_colorpicker").dialog('close');
    $("#dialog_colorpicker").remove();
}

DOCUMENTREPORT = {};

DOCUMENTREPORT.selectAll = function ()
{
    if ($("#document_oid_checkall").is(':checked'))
    {
        $("div.documentreport_check:visible input.document_oid").attr('checked', 'checked');
    } else
    {
        $("input.document_oid").removeAttr('checked');
    }
    DIB.fixupElements();
}

DOCUMENTREPORT.connectDocuments = function (documentreport_oid)
{
    // Check
    if ($("input.document_oid:checked").length == 0)
    {
        DIB.alert(LOCALE.get('DIB.SENDINVOICE.Error.NothingSelected'), LOCALE.get('DIB.COMMON.Whoops'));
        return;
    }

    // List
    var documentlist = '';
    $("input.document_oid:checked").each(function (i, val) {
        documentlist = documentlist + (documentlist != '' ? ',' : '') + $(val).val();
    });

    // Req
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Save'));
    $.ajax(
            {
                url: "/documentreport/connectdocuments",
                data: "documentreport_oid=" + documentreport_oid + "&document_oid=" + encodeURIComponent(documentlist),
                success: function (data)
                {
                    DIB.closeProgressDialog();
                    if (data != null && data.status == '1')
                    {
                        DIB.redirect("/documentreport/view/documentreport_oid=" + documentreport_oid);
                    } else
                    {
                        DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        return;
                    }
                },
                error: function ()
                {
                    DIB.closeProgressDialog();
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}

DOCUMENTREPORT.confirm = function (documentreport_oid, confirm)
{
    // Req
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Save'));
    $.ajax(
            {
                url: "/documentreport/confirm",
                data: "documentreport_oid=" + documentreport_oid + "&confirm=" + confirm,
                success: function (data)
                {
                    DIB.closeProgressDialog();
                    if (data != null && data.status == '1')
                    {
                        DIB.reload();
                    } else
                    {
                        DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        return;
                    }
                },
                error: function ()
                {
                    DIB.closeProgressDialog();
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
}

WIZARD = {};

WIZARD.firstLogin = function ()
{
    var dialog_tag = Math.floor(Math.random() * 100000 + 1);
    var dialog_width = 850;
    $("#system-warning").hide();
    DIB.progressDialog(LOCALE.get('DIB.WIZARD.FirstLogin.Progress'));
    $.ajax({
        url: '/wizard/firstlogin',
        success: function (data)
        {
            DIB.closeProgressDialog();
            if (data != null && data.status == '1')
            {
                $('body').append('<div id="dialog_' + dialog_tag + '" style="display:none">' + data.content + '</div>');
                $("#dialog_" + dialog_tag).dialog({
                    title: LOCALE.get('DIB.WIZARD.FirstLogin.DialogTitle'),
                    resizable: false,
                    bgiframe: true,
                    modal: true,
                    width: '850px',
                    close: function (event, ui)
                    {
                        $("#dialog_" + dialog_tag).remove();
                        $("#system-warning").show();
                        WIZARD.showDemoButtonHint();
                    }
                });
                $("#dialog_" + dialog_tag).dialog('open');
                DIB.fixupElements();
                DIB.initPopover();
                DIB.initTooltips();
                DIB.centerDialog();
            } else
            {
                if (data != null && data.error != null)
                {
                    if (typeof (data.error) == 'object')
                    {
                        DIB.displayErrors(data.error, LOCALE.get('DIB.COMMON.Whoops'), data.error);
                    } else
                    {
                        DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                    }
                } else
                {
                    DIB.alert(LOCALE.get('DIB.FORM.ERROR.ErrorOpeningDialog'), LOCALE.get('DIB.COMMON.Whoops'));
                }
                return;
            }
        },
        error: function ()
        {
            DIB.closeProgressDialog();
            $("#system-warning").show();
            DIB.alert(LOCALE.get('DIB.FORM.ERROR.ErrorOpeningDialog'), LOCALE.get('DIB.COMMON.Whoops'));
            return;
        }
    });
}

WIZARD.showDemoButtonHint = function ()
{
    $('#sidemenu-demo').popover({
        'placement': 'bottom',
        'content': '<div style="max-width: 320px"><p>' + LOCALE.get('DIB.DEMO.DemoButtonHint') + '</p><p><b><a onclick="WIZARD.hideDemoButtonHint()"><span class="icon-cancel" style="font-size: 13px">' + LOCALE.get('DIB.DEMO.DemoButtonHintClose') + '</span></a></b></p></div>',
        html: true
    }).popover('show');
}

WIZARD.hideDemoButtonHint = function ()
{
    $('#sidemenu-demo').popover('hide');
}

WIZARD.next = function ()
{
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Save'));
    if ($("#DIB-form").length > 0)
    {
        $("#DIB-form").ajaxSubmit({
            url: '/wizard/validatestep',
            type: 'post',
            data: {ajaxsubmit: '1'},
            success: function (data)
            {
                if (data != null && data.status == '1')
                {
                    DIB.redirect('/wizard/setstep');
                } else
                {
                    DIB.closeProgressDialog();
                    DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            },
            error: function ()
            {
                DIB.closeProgressDialog();
                DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                return;
            }
        });
    } else
    {
        $.ajax(
                {
                    url: '/wizard/validatestep',
                    success: function (data)
                    {
                        if (data != null && data.status == '1')
                        {
                            DIB.redirect('/wizard/setstep');
                        } else
                        {
                            DIB.closeProgressDialog();
                            DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                            return;
                        }
                    },
                    error: function ()
                    {
                        DIB.closeProgressDialog();
                        DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        return;
                    }
                });
    }
};

WIZARD.ADDPRODUCT = {};

WIZARD.ADDPRODUCT.toggleInsurer = function (insurer)
{
    if ($("#insurer_" + insurer).is(':checked'))
    {
        $("#collection_" + insurer).removeAttr('disabled');
        $("#collectionwithinsurer_" + insurer).removeAttr('disabled');
        $("#commission_" + insurer).removeAttr('disabled');
    } else
    {
        $("#collection_" + insurer).attr('disabled', 'disabled');
        $("#collectionwithinsurer_" + insurer).attr('disabled', 'disabled');
        $("#commission_" + insurer).attr('disabled', 'disabled');
        $("#collection_" + insurer).val('1');
        $("#collectionwithinsurer_" + insurer).val('1');
        $("#commission_" + insurer).val('');
    }
}

WIZARD.ADDPRODUCT.editProductField = function (row_id)
{
    if (row_id != null)
    {
        var postdata = {};
        postdata.edit = 1;
        postdata.row_id = row_id;
        postdata.product_field_name = $("#product_field_name_" + row_id).val();
        postdata.product_field_type = $("#product_field_type_" + row_id).val();
        postdata.product_field_data = $("#product_field_data_" + row_id).val();
        postdata.product_field_min = $("#product_field_min_" + row_id).val();
        postdata.product_field_max = $("#product_field_max_" + row_id).val();
        postdata.product_field_precision = $("#product_field_precision_" + row_id).val();
        postdata.product_field_allownegative = $("#product_field_allownegative_" + row_id).val();
        postdata.product_field_filter = $("#product_field_filter_" + row_id).val();
        postdata.product_field_required = $("#product_field_required_" + row_id).val();
        postdata.product_field_description = $("#product_field_description_" + row_id).val();
    }
    DIB.openEditDialog('/wizard/editproductfield', postdata, '700');
}

WIZARD.ADDPRODUCT.updateProductField = function (data)
{
    if (data.data != null && data.data.content != null)
    {
        $("#productfieldstable tr.noresults").remove();
        if (data.data.row_id != null && $("#productfieldrow_" + data.data.row_id).length > 0)
        {
            $("#productfieldrow_" + data.data.row_id).replaceWith(data.data.content);
        } else
        {
            $("#productfieldstable").append(data.data.content);
        }
    }
}

WIZARD.ADDPRODUCT.deleteProductField = function (row_id)
{
    if (!confirm('Delete this field?'))
        return;
    $("#productfieldrow_" + row_id).remove();
    if ($(".productfieldrow").length == 0)
    {
        $("#productfieldstable").append('<tr class="noresults"><td colspan="5">No fields defined yet. Please add at least one product field.</td></tr>');
    }
}

WIZARD.ADDPRODUCT.editObjectField = function (row_id)
{
    if (row_id != null)
    {
        var postdata = {};
        postdata.edit = 1;
        postdata.row_id = row_id;
        postdata.object_field_name = $("#object_field_name_" + row_id).val();
        postdata.object_field_type = $("#object_field_type_" + row_id).val();
        postdata.object_field_data = $("#object_field_data_" + row_id).val();
        postdata.object_field_min = $("#object_field_min_" + row_id).val();
        postdata.object_field_max = $("#object_field_max_" + row_id).val();
        postdata.object_field_precision = $("#object_field_precision_" + row_id).val();
        postdata.object_field_allownegative = $("#object_field_allownegative_" + row_id).val();
        postdata.object_field_filter = $("#object_field_filter_" + row_id).val();
        postdata.object_field_required = $("#object_field_required_" + row_id).val();
        postdata.object_field_description = $("#object_field_description_" + row_id).val();
    }
    DIB.openEditDialog('/wizard/editobjectfield', postdata, '700');
}

WIZARD.ADDPRODUCT.updateObjectField = function (data)
{
    if (data.data != null && data.data.content != null)
    {
        $("#objectfieldstable tr.noresults").remove();
        if (data.data.row_id != null && $("#objectfieldrow_" + data.data.row_id).length > 0)
        {
            $("#objectfieldrow_" + data.data.row_id).replaceWith(data.data.content);
        } else
        {
            $("#objectfieldstable").append(data.data.content);
        }
    }
}

WIZARD.ADDPRODUCT.deleteObjectField = function (row_id)
{
    if (!confirm('Delete this field?'))
        return;
    $("#objectfieldrow_" + row_id).remove();
    if ($(".objectfieldrow").length == 0)
    {
        $("#objectfieldstable").append('<tr class="noresults"><td colspan="5">No fields defined yet. Please add at least one object field.</td></tr>');
    }
}

WIZARD.COMMISSION = {};

WIZARD.COMMISSION.toggleInsurer = function (insurer, product)
{
    if ($("#product_" + insurer + "_" + product).is(':checked'))
    {
        $("#commission_" + insurer + "_" + product).removeAttr('disabled');
        $("#commission_" + insurer + "_" + product)[0].focus();
    } else
    {
        $("#commission_" + insurer + "_" + product).attr('disabled', 'disabled');
        $("#commission_" + insurer + "_" + product).val('');
    }
}

WIZARD.OFFER = {};

WIZARD.OFFER.createOffer = function ()
{
    $.ajax({
        url: '/wizard/createoffer',
        success: function (data)
        {
            if (data != null && data.status == '1')
            {
                DIB.redirect(data.redirect);
            } else
            {
                DIB.closeProgressDialog();
                DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                return;
            }
        },
        error: function ()
        {
            DIB.closeProgressDialog();
            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
            return;
        }
    });

};

CREDITCARD = {};
CREDITCARD.openDialog = function (content_url, content_data, dialog_width)
{
    var dialog_tag = Math.floor(Math.random() * 100000 + 1);
    if (dialog_width == null)
        dialog_width = 600;
    if (content_data == null)
    {
        content_data = {'tag': dialog_tag};
    } else
    {
        content_data.tag = dialog_tag;
    }
    $.ajax({
        url: content_url,
        data: content_data,
        success: function (data)
        {
            if (data != null && data.status == '1')
            {
                $('body').append('<div id="dialog_' + dialog_tag + '" style="display:none">' + data.content + '</div>');
                FORM.setDatePicker("#form_" + dialog_tag + " input:text.datefield");
                FORM.setAutoComplete("#form_" + dialog_tag + " select.autocomplete");
                var buttons = {};


                buttons[LOCALE.get('DIB.FORM.BTN.Save')] = {
                    buttonAction: function ()
                    {
                        document.getElementById('form_checkout').dispatchEvent(new Event('submit'));

                    },
                    buttonClass: "primary"
                };
                $("#dialog_" + dialog_tag).dialog({
                    title: (data.title != null ? data.title : LOCALE.get('DIB.SystemTitle')),
                    resizable: false,
                    bgiframe: true,
                    modal: true,
                    width: dialog_width + 'px',
                    buttons: buttons,
                    close: function (event, ui)
                    {
                        $("#dialog_" + dialog_tag).remove();
                    }
                });
                $("#dialog_" + dialog_tag).dialog('open');
                if (data.displaytrigger != null && data.displaytrigger != "")
                {
                    eval(data.displaytrigger);
                }
                DIB.centerDialog();
            } else
            {
                if (data != null && data.error != null)
                {
                    DIB.displayErrors(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                } else
                {
                    DIB.alert(LOCALE.get('DIB.FORM.ERROR.ErrorOpeningDialog'), LOCALE.get('DIB.COMMON.Whoops'));
                }
                return;
            }
        },
        error: function ()
        {
            DIB.alert(LOCALE.get('DIB.FORM.ERROR.ErrorOpeningDialog'), LOCALE.get('DIB.COMMON.Whoops'));
            return;
        }
    });
};

DATAIMPORT = {};

DATAIMPORT.checkProgress = function ()
{
    if (DATAIMPORT.oid == 0)
        return;
    $.ajax(
            {
                type: 'post',
                url: '/dataimport/ping',
                data: 'dataimport_oid=' + DATAIMPORT.oid,
                success: function (data) {
                    if (data != null && data.status == '1') {
                        if (data.reload != null && data.reload == '1') {
                            DIB.reload();
                        } else {
                            if (data.progress != null) {
                                $('.progress-bar').css('width', data.progress + '%').attr('aria-valuenow', data.progress).text(data.progress + '%');
                            }
                        }
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData') + ' - ' + thrownError + ' - ' + xhr.responseText, LOCALE.get('DIB.COMMON.Whoops'));
                }
            });
};

DATAIMPORT.change = function (url, data, element)
{
    if ($('#' + element).val() == 'muu')
        return;

    return DIB.doAjaxAction(url, data);
};

DATAIMPORT.init = function (url, oid)
{
    var elements = ['dataimport_delimiter'];
    $.each(elements, function (idx) {
        $('#' + elements[idx] + '_other').on('change', function () {
            if (!$(this).is(':disabled')) {
                var d = {dataimport_oid: oid};
                d[elements[idx] + '_other'] = $(this).val();
                d[elements[idx]] = 'muu';
                DATAIMPORT.change(url, d, elements[idx] + '_other');
            }
        });
    });

    if ($('#dataimport_headers').is(':checked'))
    {
        $('#fieldgroup_mapping > table > tbody > tr').each(function () {
            var row = $(this);
            var title = row.find('td').eq(0).text();
            var select = row.find('select');

            select.find('optgroup > option').each(function () {
                if ($(this).val() == title)
                {
                    select.val(title);
                    return false;
                }
            });
        });
    }
};

DATAIMPORT.next = function ()
{
    if (DATAIMPORT.validateMapping())
    {
        $.ajax(
                {
                    url: '/dataimport',
                    data: 'ajaxsubmit=1&' + $('.mapping-row, #dataimport_step, #dataimport_oid').filter(function () {
                        return $(this).val() != 'nothing';
                    }).serialize(),
                    success: function (data) {
                        if (data != null && data.status == '1') {
                            if (data.reload != null && data.reload == '1') {
                                DIB.reload();
                            } else if (data.redirect != null && data.redirect.length > 0) {
                                DIB.redirect(data.redirect);
                            } else {
                                DIB.closeProgressDialog();
                                if (data.content != null) {
                                    $(".DIB-form").html(data.content);
                                    $('.mapping-table').remove();
                                    window.scrollTo(0, 0);
                                    DIB.fixupElements();
                                }
                            }
                        } else {
                            DIB.closeProgressDialog();
                            var errors = '<b>' + LOCALE.get('DIB.FORM.Error.Errors') + ':</b>';
                            if (data != null && data.error != null) {
                                if (typeof (data.error) == 'object') {
                                    for (var fld_tag in data.error) {
                                        if (data.error.hasOwnProperty(fld_tag)) {
                                            errors = errors + '<br/> &#0149; ' + data.error[fld_tag];
                                        }
                                    }
                                    DIB.displayErrors(errors, LOCALE.get('DIB.COMMON.Whoops'), data.error);
                                } else {
                                    DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                                }
                            } else {
                                DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                            }
                            return;
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        DIB.closeProgressDialog();
                        DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData') + ' - ' + thrownError + ' - ' + xhr.responseText, LOCALE.get('DIB.COMMON.Whoops'));
                        return;
                    }
                });
    }
};

DATAIMPORT.validateMapping = function ()
{
    var options = $('#mapping-row-0 option');
    options.each(function () {
        var req = $(this).attr('data-required');
        if (typeof req != 'undefined' && req != null && req == 'true') {
            var tag = $('.mapping-row option[value="' + $(this).val() + '"]:selected');
            if (tag.length <= 0) {
                DIB.alert('Required field needs mapping - ' + $(this).text(), LOCALE.get('DIB.COMMON.Whoops'));
                return false;
            } else if (tag.length > 1) {
                DIB.alert('Every field should be mapped only once - ' + $(this).text(), LOCALE.get('DIB.COMMON.Whoops'));
                return false;
            }
        }
    });
    return true;
};

DATAIMPORT.mappingChange = function ()
{
    var option = $(event.target).val();
    var old = $(event.target).attr('data-old');
    if (option == 'nothing') {
        $(event.target).css('border-color', '');
        $(event.target).attr('data-old', option);
        DATAIMPORT.mappingChangeCheck(old);
        return;
    }
    $(event.target).attr('data-old', option);
    //Current value
    DATAIMPORT.mappingChangeCheck(option);
    DATAIMPORT.mappingChangeCheck(old);
};

DATAIMPORT.mappingChangeCheck = function (value)
{
    if (typeof value != 'undefined' && value != null) {
        if (value == 'nothing')
            return;
        var tag = $('.mapping-row option[value="' + value + '"]:selected');
        if (tag.length > 1) {
            tag.closest('select').css('border-color', 'red');
        } else {
            tag.closest('select').css('border-color', '');
        }
    }
};

var HELP = {};

$(document).ready(function () {
    HELP.canShow();
});

HELP.canShow = function ()
{
    var cookie = DIB.COOKIE.read('DIB_help');
    if (cookie != null)
    {
        var hidden = cookie.split(';');
        if (hidden.indexOf(HELP.getCurrentPage()) !== -1)
        {
            $('.panel-help, .panel-information-video').hide();
            $('.help-show').show();
        }
    }
};

HELP.show = function ()
{
    var cookie = DIB.COOKIE.read('DIB_help');
    var hidden = [];
    if (cookie != null)
    {
        hidden = cookie.split(';');
        var idx = hidden.indexOf(HELP.getCurrentPage());
        if (idx > -1) {
            hidden.splice(idx, 1);
        }
        if (hidden.length > 0)
        {
            DIB.COOKIE.create('DIB_help', hidden.join(';'), 365);
        } else {
            DIB.COOKIE.delete('DIB_help');
        }
    }
    $('.panel-help, .panel-information-video').show();
    $('.help-show').hide();
};

HELP.hide = function ()
{
    var cookie = DIB.COOKIE.read('DIB_help');
    var hidden = [];
    if (cookie != null)
    {
        hidden = cookie.split(';');
    }
    hidden.push(HELP.getCurrentPage());
    DIB.COOKIE.create('DIB_help', hidden.join(';'), 365);
    $('.panel-help, .panel-information-video').hide();
    $('.help-show').show();
};

HELP.getCurrentPage = function ()
{
    return HELP.capitalize(window.location.pathname.split('/').join(' ')).split(' ').join('');
};

HELP.capitalize = function (s)
{
    return s.toLowerCase().replace(/\b./g, function (a) {
        return a.toUpperCase();
    });
};

var FEEDBACK = {};



$(document).ready(function ()
{
    $('#give-us-feedback').on('click', function (e)
    {

        e.preventDefault();
    });
});

var CLAIM = {};
CLAIM.ADD = {};

CLAIM.ADD.initStepPolicy = function ()
{
    if ($("#policy_oid").val() == '')
    {
        if ($("#customer_oid").val() != '')
        {
            $(".choosepolicy").show();
        } else
        {
            $("#policy_oid_display").html('<a class="stealth" onclick="CHOOSEPOLICY.reset(\'policy_oid\',\'' + LOCALE.get('DIB.COMMON.None') + '\')"><i>' + LOCALE.get('DIB.CLAIM.Info.ChoosePolicyOrAddNew') + '</i></a>');
        }
    }
};
CLAIM.ADD.addClaimant = function ()
{
    $.ajax(
            {
                url: "/claim/getclaimantform",
                data: "claimanttype=" + encodeURIComponent($('#claimanttype').val()),
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        $("#field_add_claimants").before(data.content);
                        FORM.setDatePicker("#form_" + data.id + " input:text.datefield");
                    } else
                    {
                        if (data != null && data.error != null)
                        {
                            DIB.displayErrors(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function ()
                {
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
};
CLAIM.ADD.removeClaimant = function (objectID)
{
    if (!confirm(LOCALE.get('DIB.POLICY.Object.RemoveObject.Confirm')))
        return;
    $("#panel-form_" + objectID).remove();
};
CLAIM.ADD.submit = function (submit_btn)
{
    // Init
    var form_id = 'DIB-form';

    // Clear
    $("#" + form_id + " .errors").remove();
    $("#" + form_id + " td.label.error").removeClass('error');

    // Progress
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Save'));

    // Submit
    $("#" + form_id).ajaxSubmit(
            {
                data: {ajaxsubmit: '1', dir: submit_btn},
                success: function (data)
                {
                    if (data != null && data.status == '1')
                    {
                        if (data.reload != null && data.reload == '1')
                        {
                            DIB.reload();
                        } else if (data.redirect != null && data.redirect.length > 0)
                        {
                            DIB.redirect(data.redirect);
                        } else
                        {
                            DIB.closeProgressDialog();
                            if (data.content != null)
                            {
                                $("#claim_add_form").html(data.content);
                                window.scrollTo(0, 0);
                                if (data.displaytrigger != null && data.displaytrigger != "")
                                {
                                    eval(data.displaytrigger);
                                }
                                FORM.setDatePicker("#DIB-form input:text.datefield");
                                FORM.setAutoComplete("#DIB-form select.autocomplete");
                                DIB.fixupElements();
                            }
                        }
                    } else
                    {
                        DIB.closeProgressDialog();
                        var errors = '<b>' + LOCALE.get('DIB.FORM.Error.Errors') + ':</b>';
                        if (data != null && data.error != null)
                        {
                            if (typeof (data.error) == 'object')
                            {
                                for (var fld_tag in data.error)
                                {
                                    if (data.error.hasOwnProperty(fld_tag))
                                    {
                                        errors = errors + '<br/> &#0149; ' + data.error[fld_tag];
                                    }
                                }
                                DIB.displayErrors(errors, LOCALE.get('DIB.COMMON.Whoops'), data.error);
                            } else
                            {
                                DIB.alert(data.error, LOCALE.get('DIB.COMMON.Whoops'));
                            }
                        } else
                        {
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Whoops'));
                        }
                        return;
                    }
                },
                error: function (xhr, ajaxOptions, thrownError)
                {
                    DIB.closeProgressDialog();
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData') + ' - ' + thrownError + ' - ' + xhr.responseText, LOCALE.get('DIB.COMMON.Whoops'));
                    return;
                }
            });
};
TAX = {};

TAX.TAXSCHEME = {};

TAX.TAXSCHEME.getOptions = function (tax, option)
{
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));

    tax_oid = $(tax).val();

    if (tax_oid != '')
    {
        $.ajax({
            url: '/admin/taxschemegetoptions',
            data: "tax_oid=" + encodeURIComponent(tax_oid),
            success: function (data)
            {
                if (data != null && data.status == '1')
                {
                    var options = '<option value="">--- ' + LOCALE.get('DIB.FORM.Select') + ' ---</option>';

                    $.each(data.options, function (index, value) {
                        options += '<option value="' + index + '">' + value + '</option>';
                    });

                    $(option).html(options);
                }
                DIB.closeProgressDialog();
                return true;
            }
        });
    } else
    {
        var options = '<option value="">--- ' + LOCALE.get('DIB.SYSTEMSETTINGS.TAXSCHEME.Option.ChooseTax') + ' ---</option>';
        $(option).html(options);
        DIB.closeProgressDialog();
    }
};

TAX.TAXSCHEME.getFeeTaxes = function (feetype, policy_installment_oid)
{
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));

    var feetype_oid = $(feetype).val();

    if (feetype_oid != undefined && feetype_oid.substring(0, 1) == '3')
    {
        feetype_oid = feetype_oid.substr(2);
        $.ajax({
            url: '/policy/getfeeinstallmenttaxes',
            data: 'feetype_oid=' + encodeURIComponent(feetype_oid) + '&policy_installment_oid=' + encodeURIComponent(policy_installment_oid),
            success: function (data)
            {
                if (data != null && data.status == '1')
                {
                    var count = 1;

                    $('#taxscheme_oid').val(data.taxscheme_oid);

                    if (data.taxes)
                    {
                        $('#field_taxation').show();
                        $('#field_taxation').removeClass('hide');

                        $.each(data.taxes, function (index, value) {

                            $('#field_tax' + count + '_option_oid .title').html(index);
                            if (!$('#field_tax' + count + '_option_oid .label .text-danger').length)
                            {
                                $('#field_tax' + count + '_option_oid .label').prepend('<span class="text-danger icon-asterix"></span>');
                            }

                            $('#field_tax' + count + '_option_oid').removeClass('hide');
                            $('#field_tax' + count + '_option_oid').show();

                            var options = '<option value="">--- ' + LOCALE.get('DIB.FORM.Select') + ' ---</option>';

                            $.each(value, function (optionIndex, optionValue) {

                                var selected = '';

                                if (data.default_option[count] != null && data.default_option[count] == optionIndex && policy_installment_oid == '')
                                {
                                    selected = ' selected="selected"';
                                } else if (data.installment_option[count] != null && data.installment_option[count] == optionIndex)
                                {
                                    selected = ' selected="selected"';
                                }

                                options += '<option value="' + optionIndex + '"' + selected + '>' + optionValue + '</option>';
                            });

                            $('#tax' + count + '_option_oid').html(options);

                            count++;
                        });
                    } else
                    {
                        $('#field_taxation').hide();

                        for (var count = 1; count <= 3; count++)
                        {
                            $('#field_tax' + count + '_option_oid').hide();
                            $('#field_tax' + count + '_option_oid .label .text-danger').remove();
                            $('#tax' + count + '_option_oid').val('');
                        }
                    }
                }
                DIB.closeProgressDialog();
                return true;
            }
        });
    } else
    {
        $('#field_taxation').hide();

        for (var count = 1; count <= 3; count++)
        {
            $('#tax' + count + '_option_oid').val('');
            $('#field_tax' + count + '_option_oid').hide();
            $('#field_tax' + count + '_option_oid .label .text-danger').remove();
        }

        DIB.closeProgressDialog();
    }
};

// Checkbox with hidden date

CHECKDATE = {};

CHECKDATE.showDate = function (idCheckField, idDateField, dateValue)
{
    var checked = $('#' + idCheckField).attr('checked');
    if (checked)
    {
        $('#' + idDateField).show();
        $('#' + idDateField).next().show();
    } else if (dateValue == '')
    {
        $('#' + idDateField).hide();
        $('#' + idDateField).next().hide();
    }
}

var FORMSTEP = {};
FORMSTEP.submit = function (submit_btn)
{
    // Init
    var form_id = 'DIB-form';

    // Clear
    $("#" + form_id + " .errors").remove();
    $("#" + form_id + " td.label.error").removeClass('error');

    // Progress
    DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Save'));

    // Submit
    $("#" + form_id).ajaxSubmit(
            {
                data: {
                    ajaxsubmit: '1', dir: submit_btn
                },
                beforeSerialize: function (form) {
                    DIB.AUTONUMERIC.beforeSubmit(form)
                },
                success: function (data)
                {
                    if (data != null && data.status == '1') {
                        if (data.reload != null && data.reload == '1') {
                            DIB.reload();
                        } else if (data.redirect != null && data.redirect.length > 0) {
                            DIB.redirect(data.redirect);
                        } else {
                            DIB.closeProgressDialog();
                            if (data.content != null) {
                                $("#" + form_id).html(data.content);
                                window.scrollTo(0, 0);
                                if (data.displaytrigger != null && data.displaytrigger != "") {
                                    eval(data.displaytrigger);
                                }
                                FORM.setDatePicker("#DIB-form input:text.datefield");
                                FORM.setAutoComplete("#DIB-form select.autocomplete");
                                DIB.fixupElements()
                            }
                        }
                    } else {
                        DIB.closeProgressDialog();
                        var errors = '<b>' + LOCALE.get('DIB.FORM.Error.Errors') + ':</b>';
                        if (data != null && data.error != null) {
                            if (typeof (data.error) == 'object') {
                                for (var fld_tag in data.error) {
                                    if (data.error.hasOwnProperty(fld_tag)) {
                                        errors = errors + '<br/> &#0149; ' + data.error[fld_tag];
                                    }
                                }
                                DIB.displayErrors(errors, LOCALE.get('DIB.COMMON.Error'), data.error);
                            } else {
                                DIB.alert(data.error, LOCALE.get('DIB.COMMON.Error'));
                            }
                        } else {
                            DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData'), LOCALE.get('DIB.COMMON.Error'));
                        }
                        return;
                    }
                },
                error: function (xhr, ajaxOptions, thrownError)
                {
                    DIB.closeProgressDialog();
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorSavingData') + ' - ' + thrownError + ' - ' + xhr.responseText, LOCALE.get('DIB.COMMON.Error'));
                    return;
                }
            }
    );
};

var SEARCH = {};
SEARCH.data = [];
SEARCH.highlightDisabled = true;

/**
 * @param {Object} [queryData]    JSON object to pass along with the loadContent()
 */
SEARCH.init = function (queryData) {

    queryData = queryData || {};

    $('#page-admin').css({
        display: 'table',
        width: '100%',
        "padding-left": 0,
        "padding-right": 0
    });

    $('.navbar').css({
        "margin-bottom": 0
    });

    $('.sidebar').perfectScrollbar({suppressScrollX: true});
    var $sidebarContainer = $('.sidebar-container');
    SEARCH.updateHeight();
    $('.toggle-sidebar').css('left', $sidebarContainer.width() + 17);
    SEARCH.originaLeft = $sidebarContainer.width() + 17;

    if (SESSION.get('SEARCH.lastLink') !== null) {
        SEARCH.loadContent(SESSION.get('SEARCH.lastLink'), queryData);
    }
    if (SESSION.get('SEARCH.highlight') !== null) {
        SEARCH.disableHighlight();
    }
    SEARCH.fixForms();
};

SEARCH.search = function (val)
{
    if (val.length > 0 && $('.sidebar-container .icon-close').is(':hidden'))
    {
        $('.sidebar-container .icon-close').toggleClass('hidden');
        $('.sidebar li > h4 > span.panel-action-open').hide();
        $('.sidebar li.closed').find('ul').fadeToggle(200);
    } else if (val.length <= 0 && $('.sidebar-container .icon-close').is(':visible'))
    {
        $('.sidebar-container .icon-close').toggleClass('hidden');
        $('.sidebar li > h4 > span.panel-action-open').show();
        $('.sidebar li.closed').find('ul').fadeToggle(200);
    }
    val = val.toLowerCase().replace(/\s+/g, '');
    var lists = $('div.sidebar li ul');
    lists.parent().show();
    if (val.length) {
        $('div.sidebar li a').each(function () {
            if ($(this).text().toLowerCase().indexOf(val) < 0) {
                $(this).hide()
            } else {
                $(this).show()
            }
        });
        lists.each(function () {
            if ($(this).find('li > a:visible').length == 0) {
                $(this).parent().hide();
            } else {
                $(this).parent().show();
            }
        });
    } else {
        $('div.sidebar li a').show();
    }

    $('.sidebar').perfectScrollbar('update');
};

/**
 * Load admin page content
 *
 * @param {string} link
 * @param {Object} [data]   Optional JSON object to pass as the data on AJAX call.
 */
SEARCH.loadContent = function (link, data)
{
    var queryData = data ? data : {};
    queryData.type = 'link';
    queryData.link = link;

    $.ajax({
        url: '/admin/getdata',
        data: queryData,
        async: true,
        success: function (data) {
            if (data.status == '1') {
                $('.admin-content').attr('data-link', link);
                SESSION.set('SEARCH.lastLink', link);
                $('.admin-content').html(data.content);

                SEARCH.setActiveLink(link);

                DIB.fixupElements();
                DIB.initPopover();
                DIB.initTooltips();

                SEARCH.updateHeight();
                SEARCH.fixForms();
                if (!SEARCH.highlightDisabled)
                    SEARCH.initHighlight();
            }
        }
    });
};
SEARCH.setActiveLink = function (link)
{
    $('.sidebar ul > li.active').each(function () {
        $(this).find('span.icon-left-arrow').remove();
        $(this).removeClass('active');
    });

    $('.sidebar ul > li a[href="' + link + '"]').closest('li').addClass('active').append('<span class="icon-left-arrow"></span>');
};
SEARCH.disableHighlight = function ()
{
    SEARCH.highlightDisabled = true;
    SESSION.set('SEARCH.highlight', false);
    var spans = document.getElementsByClassName('found');
    while (spans.length) {
        var p = spans[0].parentNode;
        p.innerHTML = p.textContent || p.innerText;
    }
};
SEARCH.initHighlight = function ()
{
    if (SEARCH.highlightDisabled)
        return;
    var div = document.getElementById('page-admin');

    var spans = document.getElementsByClassName('found');
    while (spans.length) {
        var p = spans[0].parentNode;
        p.innerHTML = p.textContent || p.innerText;
    }
    if ($('#search').val().length > 0) {
        SEARCH.highlight(div, new RegExp('(' + $('#search').val() + ')', 'gi'));
    }

    if ($('#search').val().length > 0) {
        var search = $('#search').val().toLowerCase().replace(/\s+/g, '');
        $('.admin-content .panel-action-add[data-toggle="tooltip"]').each(function ()
        {
            $(this).removeClass('circleAction');
            var title = $(this).attr('data-original-title').toLowerCase().replace(/\s+/g, '');
            if (title.indexOf(search) > -1) {
                $(this).addClass('circleAction');
            }
        });
    } else {
        $('.admin-content .panel-action-add[data-toggle="tooltip"]').removeClass('circleAction');
    }
};

SEARCH.highlight = function (n, r, f)
{
    f = n.childNodes;
    for (c in f)
        SEARCH.highlight(f[c], r);
    if (n.data) {
        f = document.createElement('span');
        f.innerHTML = n.data.replace(r, '<span class=found>$1</span>');
        n.parentNode.insertBefore(f, n);
        n.parentNode.removeChild(n);
    }
};

SEARCH.collapse = function (self)
{
    var $panel = $(self).closest('li');
    $panel.find('ul').fadeToggle(200);
    $panel.toggleClass('open').toggleClass('closed');
    $('.sidebar').perfectScrollbar('update');
};

SEARCH.clear = function ()
{
    $('#search').val('');
    SEARCH.search('');
    if (!SEARCH.highlightDisabled)
        SEARCH.initHighlight();
};

SEARCH.originaLeft = 0;
SEARCH.toggle = function ()
{
    if ($('.sidebar-container').is(':visible')) {
        $('.sidebar-container').hide("slide", {direction: "left"}, 400);
        $('.toggle-sidebar').animate({left: "-6px"}, 400);
        $('.admin-content').animate({"padding-left": 20}, 400);
    } else {
        $('.sidebar-container').show("slide", {direction: "left"}, 400);
        $('.toggle-sidebar').animate({left: SEARCH.originaLeft}, 400);
        $('.admin-content').animate({"padding-left": 340}, 400);
    }
};

/**
 * Adds action to forms that want to submit to their current url.
 */
SEARCH.fixForms = function ()
{
    $('.admin-content').find('form').each(function () {
        if ($(this).attr('action').length <= 0)
            $(this).attr('action', '?link=' + $('.admin-content').attr('data-link'));
    });
};

SEARCH.startSearch = function () {
    SEARCH.search($('#search').val());

    //Time search, when too slow disable highlighting.
    var time = new Date().getTime();
    if (!SEARCH.highlightDisabled)
        SEARCH.initHighlight();
    var timeEnd = new Date().getTime();
    if (!SEARCH.highlightDisabled && timeEnd - time >= 100)
        SEARCH.disableHighlight();
};

SEARCH.updateHeight = function ()
{
    //Reset sidebar height so it won't change other heights.
    var sidebar = $('.sidebar-container');
    sidebar.css('height', 100);

    //Set values needed for calulcations.
    var maxScroll = $(document).height() - $(window).height();
    var currentScroll = $(document).scrollTop();
    var headerHeight = 100;
    var extraHeaderHeight = 0;
    if ($('body > #system-warning').length > 0)
        $('body > #system-warning').each(function () {
            extraHeaderHeight += $(this).outerHeight();
        });

    //Some basic stuff to figure out how much we should have top/bottom margins.
    var top = (headerHeight + extraHeaderHeight) - currentScroll;
    if (top < 0)
        top = 0;
    var bottom = maxScroll - currentScroll;
    if (bottom > 70)
        bottom = 0;
    else
        bottom = 70 - (maxScroll - currentScroll);

    //Set sidebar height and if no scrolling possibilities set bottom value.
    var sidebarHeight = $(window).height() - top - bottom;
    if (maxScroll == 0)
    {
        sidebarHeight = $('#main').height() - top + extraHeaderHeight;
        bottom = $(window).height() - sidebarHeight;
    }
    //Set stuff for real.
    $('.toggle-sidebar').css('top', top + 10);
    sidebar.css({top: top, bottom: bottom, height: sidebarHeight});

    $('.sidebar').height(sidebarHeight - 100);
    $('.sidebar').perfectScrollbar('update');
};

$(document).on('click', '#page-admin > .sidebar-container > .sidebar a', function (e) {
    e.preventDefault();
    e.stopPropagation();

    SEARCH.loadContent($(this).attr('href'));

    return false;
});

$(window).on('scroll', function () {
    if ($('#page-admin').length > 0) {
        SEARCH.updateHeight();
    }
});

$(window).on('resize ready', function () {
    if (SEARCH.data.length > 0) {
        $('.toggle-sidebar').css('left', $('.sidebar-container').width() + 17);
        SEARCH.originaLeft = $('.sidebar-container').width() + 17;
    }
});

$(document).ready(function () {
    var $mainMenu = $('#main-menu');
    var menuWidth = $mainMenu.outerWidth() + $('#side-menu').width() + 10;

    function onResize() {
        var wWidth = $(window).width();

        $mainMenu.toggleClass('small-icons', menuWidth > wWidth);
    }

    $(window).resize(onResize);

    onResize();
});

OFFER.showDiffFieldsFromOriginal = function (diffFields) {

    for (var type in diffFields) {
        for (var prop in diffFields[type]) {
            var val = diffFields[type][prop];
            var name = prop;

            if (type == 'added') {
                name = diffFields[type][prop];
                val = '';
            }

            var $element = $('[name="' + name + '"]');

            if ($element.attr('type') == 'checkbox') {
                $element = $element.closest('label');
            } else if ($element.attr('type') == 'radio') {
                $element = $element.filter(':checked').closest('label');
            } else {

                if (type != 'added') {
                    if ($element.is('select')) {
                        val = $element.find('option[value="' + val + '"]').text();
                    } else if ($element.data('datepicker')) {
                        var previousDate = new Date(val);

                        if (previousDate != 'Invalid Date') {
                            var currentDate = $element.datepicker('getDate');
                            val = $element.datepicker('setDate', previousDate).val();
                            $element.datepicker('setDate', currentDate);
                        }
                    }

                    $element.attr('title', val).tooltip();
                }
            }

            $element.addClass('diff-highlighted');

            if (type == 'added') {
                $element.addClass('new');
            } else if (type == 'deleted') {
                $element.addClass('del');
            }
        }
    }
};

CREDITLIMIT = {};

CREDITLIMIT.toggleData = function (id)
{
    if (id != null)
    {
        var $idElement = $('#' + id);
        var $icon = $idElement.prev().find('span.icon-arrow-closed,span.icon-arrow-open');
        if ($icon.attr('class') == 'icon-arrow-closed')
        {
            $idElement.parent().parent().removeClass('table-hovered');
            $icon.attr('class', 'icon-arrow-open');
        } else
        {
            $idElement.parent().parent().addClass('table-hovered');
            $icon.attr('class', 'icon-arrow-closed');
        }
        $idElement.toggle();
    }
};
CLAIM.SETTINGS = {
    toggleObjectOptions: function () {
        var $product_option = $("input[name=prop_settings_claim_link_products]:checked").val();
        var $object_option = $("input[name=prop_settings_claim_link_product_objects]:checked").val();
        var trigger = false;
        $($("input[name=prop_settings_claim_link_product_objects]").get().reverse()).each(function (k, v) {
            var $option = $(v);
            if ($option.val() > $product_option) {
                $option.attr("checked", false).attr("disabled", true);
                if ($option.val() == $object_option) {
                    trigger = true;
                }
            }
            if ($option.val() <= $product_option) {
                $option.attr("disabled", false);
                if (trigger) {
                    $option.attr("checked", true).trigger('click');
                    trigger = false;
                }
            }
        });
    }
};

var FILEBIN = {};
FILEBIN.groupChange = function () {
    var documentTypeField = $('#field_documenttype_oid');
    documentTypeField.addClass('hidden');
    $.ajax(
            {
                url: 'helper/filebindocumenttypes',
                data: {
                    file_bin_oid: $('#ref_oid').val()
                },
                success: function (data) {
                    if (data != null && data.status == '1') {
                        if (data.content != null) {
                            documentTypeField.removeClass('hidden');
                            FORM.loadOptions('#documenttype_oid', data.content);
                        }
                    } else {
                        DIB.alert(data.error, LOCALE.get('DIB.common.error'));
                    }
                }
                ,
                error: function () {
                    DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.common.error'));
                }
            });
};

var ViewModel = {};

function readOnlyObserver(observable) {
    return ko.dependentObservable({
        read: observable,
        write: function () { }
    });
}

/**
 * Address helper
 * @type {{}}
 */
window.OFFER.ADDRESS_IO = {};

/**
 * Get address by ZIP code
 * @param base_element
 */
window.OFFER.ADDRESS_IO.getAddressByZip = function (base_element)
{
    var $baseElement = $(base_element);

    var postcode = $baseElement.parent().find('[rel="_zip"]').val();
    var $addressDropdown = $baseElement.parents().eq(2).find('[rel="_select"]');
    var elem_address_select_label = $addressDropdown.parent().find('label');
    var $addressCountryDropdown = $baseElement.parents().eq(2).find('[rel="_country"]');

    if ($addressCountryDropdown.find('option:selected').val() !== 'GB') {
        DIB.alert("Zip code search works only for United Kingdom country!");
    }

    $addressDropdown.show();
    elem_address_select_label.show();

    if (!OFFER.ADDRESS_IO.validatePostcodeFormat(postcode)) {
        return DIB.alert("Invalid postcode \"" + postcode + "\" entered");
    }

    $addressDropdown.html('<option value="">--- LOADING ---</option>');

    $.get('/service/uk/lookup/address/' + window.encodeURI(postcode))
            .done(function (response) {
                if (Array.isArray(response.Addresses)) {
                    var output = ['<option value="">--- choose ---</option>'];

                    response.Addresses.forEach(function (val, key) {
                        var addressInfo = val.split(',');
                        var cleanAddressInfo = [];

                        addressInfo.forEach(function (val) {
                            if (val.trim().length !== 0) {
                                cleanAddressInfo.push(val);
                            }
                        });

                        return output.push('<option value="' + key + '">' + cleanAddressInfo.join(',') + '</option>');
                    });

                    $addressDropdown.html(output.join(''));
                }
            })
            .fail(function (xhr) {
                DIB.alert(xhr.responseText);
            });
};

/**
 * @param base_element
 */
window.OFFER.ADDRESS_IO.insertAddressFields = function (base_element)
{
    var $baseElement = $(base_element);
    //Split address value by commas
    var address_values = $baseElement.find('option:selected').text().split(',');
    var address_values_length = address_values.length;

    var address_street = [];
    var address_city_county = [];

    for (var i = 0; i < address_values.length; i++) {
        //Last 2 elements must be city & county values
        if ((address_values_length - i) == 1 || (address_values_length - i) == 2) {
            address_city_county.push(address_values[i]);
        } else {
            address_street.push(address_values[i]);
        }
    }
    address_street = (address_street.join(',').trim());
    address_city_county = (address_city_county.join(',').trim());

    //Update form fields
    $baseElement.parents().eq(2).find('[rel=""]').val(address_street).trigger('change'); //Street
    $baseElement.parents().eq(2).find('[rel="_city"]').val(address_city_county).trigger('change'); //County
};

/**
 * Validate correct postcode format
 * @param postcode
 * @returns {boolean}
 */
OFFER.ADDRESS_IO.validatePostcodeFormat = function (postcode) {
    return !!postcode.match(/^[a-zA-Z0-9]{1,4}\s?\d[a-zA-Z]{2}$/);
};

/**
 * Validate zip code format
 * @param postcode
 * @param id
 * @returns {boolean}
 */
window.OFFER.ADDRESS_IO.validateZipFormat = function (postcode, id) {

    // transform zip code to upperCase
    $(id).val($(id).val().toUpperCase());

    // allowed postcode formats
    var postcodeFormats = [
        /^[a-zA-Z][0-9]\s?[0-9][a-zA-Z]{2}$/, //  AN NAA
        /^[a-zA-Z]\d{2}\s?[0-9][a-zA-Z]{2}$/, //  ANN NAA
        /^[a-zA-Z]((\d[a-zA-Z])|([a-zA-Z]\d))\s?\d[a-zA-Z]{2}$/, //  AAN NAA / ANA NAA
        /^[a-zA-Z]{2}\d([a-zA-Z]|\d)\s?\d[a-zA-Z]{2}$/, //  AANA NAA / AANN NAA
    ];

    $(id).removeClass('error');
    for (regex in postcodeFormats) {
        if (postcode.match(postcodeFormats[regex]))
        {
            return true;
        }
    }
    $(id).addClass('error');
};



$(function () {

    OFFER.ENDORSEMENT = {
        endorsements: []
    };

    /**
     * Load Endorsements list based on selected library
     */

    OFFER.ENDORSEMENT.loadEndorsementsList = function () {
        $.ajax({
            url: '/endorsement/getendorsements',
            data: {
                libraryId: $('#dialog_endorsement_library').val()
            },
            success: function (data) {
                if (data.content != null) {
                    OFFER.ENDORSEMENT.endorsements = data.content;
                    var options = ['--- ' + LOCALE.get('DIB.FORM.Select') + ' ---'];
                    $.each(data.content, function (index, value) {
                        options[index] = value.title
                    });
                    FORM.loadOptions('#dialog_endorsement_conditions', options);
                }
                DIB.fixupElements();
            }
        });
    };

    /**
     * Attach events to dialog inputs
     */

    OFFER.ENDORSEMENT.dialogTriggers = function () {
        $('#dialog_endorsement_library').on('change', function () {
            OFFER.ENDORSEMENT.loadEndorsementsList();
            if (!$("#dialog_endorsement_library").val()) {
                $('#dialog_endorsement_title').val('');
                $('#dialog_endorsement_description').val('');
            }
        });

        $('#dialog_endorsement_conditions').on('change', function () {

            var selectedCondition = OFFER.ENDORSEMENT.endorsements[$('#dialog_endorsement_conditions').val()];
            var title = $('#dialog_endorsement_title');
            var description = $('#dialog_endorsement_description');

            if (selectedCondition) {
                title.val(selectedCondition.title);
                description.val(selectedCondition.description);
            } else {
                title.val('');
                description.val('');
            }
        });
    };

    /**
     * Add endorsement
     */

    OFFER.ENDORSEMENT.add = function () {
        $.ajax({
            url: '/endorsement/getdialog',
            success: function (data) {
                if (data.content != null) {
                    var buttons = {};

                    buttons[LOCALE.get('DIB.COMMON.Add')] = {
                        buttonAction: function () {
                            var title = $('#dialog_endorsement_title');
                            var description = $('#dialog_endorsement_description');
                            var condition = $('#dialog_endorsement_conditions');

                            if (title.val() == '') {
                                return DIB.alert(LOCALE.get('DIB.OFFER.ENDORSEMENTS.InfoMissing'), LOCALE.get('DIB.COMMON.Whoops'));
                            }

                            OFFER.ENDORSEMENT.addRow(title.val(), description.val(), condition.val());

                            title.val('');
                            description.val('');
                            condition.val('');
                        }
                    };
                    buttons[LOCALE.get('DIB.DIALOG.CloseWindow')] = {
                        buttonAction: function () {
                            $("#product_endorsement").dialog('close');
                        }
                    };

                    OFFER.ENDORSEMENT.drawDialogWindow(data, buttons);
                    DIB.fixupElements();
                    OFFER.ENDORSEMENT.dialogTriggers();
                }
            }
        });
    };


    /**
     * Adding new endorsement
     *
     * @param endorsement_title
     * @param endorsement_description
     * @param endorsement_id
     */

    OFFER.ENDORSEMENT.addRow = function (endorsement_title, endorsement_description, endorsement_id) {
        var sendData = JSON.stringify({
            offer_oid: $('#offer_oid').val(),
            endorsement_title: endorsement_title,
            endorsement_description: endorsement_description,
            endorsement_id: endorsement_id
        });
        $.ajax(
                {
                    url: '/endorsement/addendorsement',
                    data: JSON.parse(sendData),
                    dataType: 'json',
                    success: function (data) {
                        $("#field_endorsement_add").before(data.content);

                    },
                    error: function () {
                        return DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    }
                }
        );
    };

    /**
     * Editing selected endorsement
     *
     * @param endorsement_title
     * @param endorsement_description
     * @param offerEndorsementId
     * @param offer_oid
     */

    OFFER.ENDORSEMENT.editRow = function (endorsement_title, endorsement_description, offerEndorsementId, offer_oid) {
        var sendData = JSON.stringify({
            endorsement_title: endorsement_title,
            endorsement_description: endorsement_description,
            offer_endorsement_id: offerEndorsementId,
            offer_oid: offer_oid
        });
        $.ajax(
                {
                    url: '/endorsement/saveendorsement',
                    data: JSON.parse(sendData),
                    dataType: 'json',
                    success: function (data) {
                        $("#product_endorsement").dialog('close');
                        $("#endorsement_title_" + offerEndorsementId + "_row_disp").html(data.content.endorsement_title);
                        $("#endorsement_description_" + offerEndorsementId + "_row_disp").html(data.content.endorsement_description);

                    },
                    error: function () {
                        return DIB.alert(LOCALE.get('DIB.COMMON.ErrorReadingData'), LOCALE.get('DIB.COMMON.Whoops'));
                    }
                }
        );
    };

    /**
     * Delete endorsement row
     * @param offer_oid
     * @param offerEndorsementId
     */

    OFFER.ENDORSEMENT.delete = function (offer_oid, offerEndorsementId) {
        $.ajax({
            url: '/endorsement/deleteendorsement',
            data: {
                offer_oid: offer_oid,
                offer_endorsement_id: offerEndorsementId
            },
            success: function (data) {
                if (data.status == 1) {
                    $("#field_endorsement_" + offerEndorsementId + "_row").remove();
                }
            }
        });
    };

    /**
     * Edit endorsement
     * @param offer_oid
     * @param offerEndorsementId
     */

    OFFER.ENDORSEMENT.edit = function (offer_oid, offerEndorsementId) {
        $.ajax({
            url: '/endorsement/editendorsement',
            data: {
                offer_endorsement_id: offerEndorsementId
            },
            success: function (data) {
                if (data.content != null) {
                    var buttons = {};
                    buttons[LOCALE.get('DIB.COMMON.Edit')] = {
                        buttonAction: function () {
                            var title = $('#dialog_endorsement_title').val();
                            var description = $('#dialog_endorsement_description').val();

                            OFFER.ENDORSEMENT.editRow(title, description, offerEndorsementId, offer_oid);
                        }
                    };
                    buttons[LOCALE.get('DIB.FORM.BTN.Cancel')] = {
                        buttonAction: function () {
                            $("#product_endorsement").dialog('close');
                        }
                    };

                    OFFER.ENDORSEMENT.drawDialogWindow(data, buttons);
                    DIB.fixupElements();
                }
            }
        });
    };

    /**
     * Pop-up dialog window
     * @param data
     * @param buttons
     */

    OFFER.ENDORSEMENT.drawDialogWindow = function (data, buttons) {
        $('body').append('<div id="product_endorsement" title="' + data.title + '" style="padding: 20px 10px; display:none">' + data.content + '<div style="clear:both;"></div></div>');
        $("#product_endorsement").dialog({
            width: '1200px',
            resizable: false,
            bgiframe: true,
            modal: true,
            buttons: buttons,
            close: function () {
                $('#product_endorsement').remove();
            }
        });
    };

    /**
     * Show/Hide logic for endorsement description
     * @param endorsementId
     */

    OFFER.ENDORSEMENT.toggleDescription = function (endorsementId) {
        var endorsementRow = $("#endorsement_description_" + endorsementId + "_row_disp");
        if (endorsementRow.hasClass('hidden')) {
            endorsementRow.removeClass('hidden');
        } else {
            endorsementRow.addClass('hidden');
        }
    }
});



