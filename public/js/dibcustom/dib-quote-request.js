/// <reference path="../Typescript/directives/jquery.d.ts" />
/// <reference path="../Typescript/directives/underscore.d.ts" />
/// <reference path="../Typescript/directives/jqueryui.d.ts" />
var DibQuoterequest = /** @class */ (function () {
    function DibQuoterequest(options) {
        this.defaultSettings = {
            requestFormUrl: '',
        };
        this.settings = $.extend(true, {}, this.defaultSettings, options);
    }
    DibQuoterequest.prototype.initialSetting = function () {
        this.requestFormgenerate();
        this.editFormGenerate();
        this.overviewGenerate();
        this.checkboxEvent();
        this.editStatusForm();
        this.generateMainBrokenSlipPage();
        CKEDITOR.config.readOnly = false;
        this.deleteBrokingSlip();
        this.sendBrokingSlip();
        this.uploadQuoteForm();
        this.sendIssuanceDocumentForm();
        this.changeNotificationFlag();
    };
    DibQuoterequest.prototype.requestFormgenerate = function () {
        var _that = this;
        $(document).on('click', '.dpib_quote_request_add', function () {
            $.ajax({
                url: _that.settings.requestFormUrl,
                type: "GET"
            }).done(function (data) {
                $("#db_quote_request_popup").remove();
                $('body').append('<div id="db_quote_request_popup" title="Add a  request" style="display:none" >' + data.content + '</div>');
                var dialogElement = $("#db_quote_request_popup");
                dialogElement.dialog({
                    width: 900,
                    modal: true,
                    buttons: {
                        "Save": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text: 'Save',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                                var isValid = true;
                                var errorMessage = "";
                                var i = 0;
                                $(".required:visible").each(function () {
                                    if ($(this).val() == '' || $(this).val() == null) {
                                        isValid = false;
                                        $(this).addClass('form-control-danger');
                                        $(this).parent('.element').addClass('has-danger');
                                        if (i == 0) {
                                            errorMessage += "<b>The following errors occurred while validating data:" + "</b><br/>";
                                            i++;
                                        }
                                        errorMessage += "<b>" + $(this).attr('error-message') + "</b><br/>";
                                    }
                                    else {
                                        $(this).removeClass('error');
                                        $(this).removeClass('form-control-danger');
                                        $(this).parent('.element').removeClass('has-danger');
                                    }
                                });
                                if (isValid) {
                                    $("form#form_quote_request_add").submit();
                                }
                                else {
                                    DIB.alert(errorMessage, 'Error!!!!');
                                }
                            }
                        },
                        "cancel": {
                            class: "btn waves-effect waves-light btn-rounded btn-danger",
                            text: 'Cancel',
                            click: function () {
                                dialogElement.dialog('close');
                                dialogElement.remove();
                            }
                        }
                    },
                    open: function (event, ui) {
                        $('#crm_type').val(0).trigger('change');
                        FORM.setDatePicker('#prop_repeat_date');
                    }
                });
                DIB.centerDialog();
            });
        });
    };
    DibQuoterequest.prototype.editFormGenerate = function () {
        var _that = this;
        $(document).on('click', '.dpib_request_edit', function () {
            $.ajax({
                url: $(this).attr('editUrl'),
                type: "GET"
            }).done(function (data) {
                $("#db_quote_request_editpopup").remove();
                $('body').append('<div id="db_quote_request_editpopup" title="Update request" style="display:none" >' + data.content + '</div>');
                var dialogElement = $("#db_quote_request_editpopup");
                dialogElement.dialog({
                    width: 900,
                    modal: true,
                    buttons: {
                        "Update": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text: 'Update',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
                                var isValid = true;
                                var errorMessage = "";
                                var i = 0;
                                $(".required:visible").each(function () {
                                    if ($(this).val() == '' || $(this).val() == null) {
                                        isValid = false;
                                        $(this).addClass('form-control-danger');
                                        $(this).parent('.element').addClass('has-danger');
                                        if (i == 0) {
                                            errorMessage += "<b>The following errors occurred while validating data:" + "</b><br/>";
                                            i++;
                                        }
                                        errorMessage += "<b>" + $(this).attr('error-message') + "</b><br/>";
                                    }
                                    else {
                                        $(this).removeClass('error');
                                        $(this).removeClass('form-control-danger');
                                        $(this).parent('.element').removeClass('has-danger');
                                    }
                                });
                                if (isValid) {
                                    $("form#form_quote_request_edit").submit();
                                }
                                else {
                                    DIB.alert(errorMessage, 'Error!!!!');
                                }
                            }
                        },
                        "cancel": {
                            class: "btn waves-effect waves-light btn-rounded btn-danger",
                            text: 'Cancel',
                            click: function () {
                                dialogElement.dialog('close');
                                dialogElement.remove();
                            }
                        }
                    },
                    open: function (event, ui) {
                        $('#crm_type').trigger('change');
                        $('#request_status').trigger('change');
                        FORM.setDatePicker('#prop_repeat_date');
                    }
                });
                DIB.centerDialog();
            });
        });
    };
    DibQuoterequest.prototype.overviewGenerate = function () {
        $(document).on('click', '.dp_quote_request_overview', function () {
            $.ajax({
                url: $(this).attr('openUrl'),
                type: "GET"
            }).done(function (data) {
                $("#db_quote_request_overviewpopup").remove();
                $('body').append('<div id="db_quote_request_overviewpopup" title="Quote request" style="display:none" >' + data.content + '</div>');
                var dialogElement = $("#db_quote_request_overviewpopup");
                dialogElement.dialog({
                    width: 900,
                    resizable: false,
                    bgiframe: true,
                    modal: true,
                    buttons: {
                        "OK": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text: 'Ok',
                            click: function () {
                                dialogElement.dialog('close');
                                dialogElement.remove();
                            }
                        },
                    },
                    close: function (event, ui) { }
                });
                DIB.centerDialog();
            });
        });
    };
    DibQuoterequest.prototype.checkboxEvent = function () {
        $(document).off('click', '#prop_repeat_flag');
        $(document).on('click', '#prop_repeat_flag', function () {
            if ($(this).val() == 0) {
                $(this).val(1);
            }
            else {
                $(this).val(0);
            }
        });
        $(document).off('click', '#prop_reminder');
        $(document).on('click', '#prop_reminder', function () {
            if ($(this).val() == 0) {
                $(this).val(1);
            }
            else {
                $(this).val(0);
            }
        });
    };
    DibQuoterequest.prototype.editStatusForm = function () {
        var _that = this;
        $(document).on('click', '.dpib_request_status_edit', function () {
            $.ajax({
                url: $(this).attr('editUrl'),
                type: "GET"
            }).done(function (data) {
                $("#db_quote_request_editpopup").remove();
                $('body').append('<div id="db_quote_request_editpopup" title="Update request status" style="display:none" >' + data.content + '</div>');
                var dialogElement = $("#db_quote_request_editpopup");
                dialogElement.dialog({
                    width: 900,
                    modal: true,
                    buttons: {
                        "Update": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text: 'Update',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
                                $("form#form_crm_status_edit").submit();
                                // $.post($("form#form_crm_status_edit").attr('action'), $("form#form_crm_status_edit").serialize(), function(json) {
                                //    if(json.status) {
                                //         dialogElement.dialog('close');
                                //         dialogElement.remove();
                                //    }
                                //   }, 'json');
                                //   return false;
                                // $("form#form_crm_status_edit").submit(function(){
                                //     console.log("this is",$(this).attr('action'))
                                // });
                            }
                        },
                        "cancel": {
                            class: "btn waves-effect waves-light btn-rounded btn-danger",
                            text: 'Cancel',
                            click: function () {
                                dialogElement.dialog('close');
                                dialogElement.remove();
                            }
                        }
                    }
                });
                DIB.centerDialog();
            });
        });
    };
    DibQuoterequest.prototype.quoteUploadForm = function (data) {
        $("#db_policy_add_popup").remove();
        $('body').append('<div id="db_policy_add_popup" title="Add Policy" style="display:none" >' + data.content + '</div>');
        var dialogElement = $("#db_policy_add_popup");
        dialogElement.dialog({
            width: 900,
            modal: true,
            buttons: {
                "Update": {
                    class: "btn waves-effect waves-light btn-rounded btn-success",
                    text: 'Update',
                    click: function () {
                        DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
                        $.post($("form#form_crm_status_edit").attr('action'), $("form#form_crm_status_edit").serialize(), function (json) {
                            if (json.status) {
                                dialogElement.dialog('close');
                                dialogElement.remove();
                                _that.quoteUploadForm();
                            }
                        }, 'json');
                        return false;
                    }
                },
                "cancel": {
                    class: "btn waves-effect waves-light btn-rounded btn-danger",
                    text: 'Cancel',
                    click: function () {
                        dialogElement.dialog('close');
                        dialogElement.remove();
                    }
                }
            }
        });
        DIB.centerDialog();
    };
    DibQuoterequest.prototype.generateBrokenSlipForm = function () {
        $(document).off('change', '#insurance_product');
        $(document).on('change', '#insurance_product', function () {
            $.ajax({
                url: $(this).attr('openUrl'),
                method: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product: $(this).val()
                }
            }).done(function (data) {
                $("#brokenslip_creation_table tr").not(".dp_main_tr").remove();
                $('#brokenslip_creation_table tr:last').after(data.content);
                FORM.setDatePicker('#start_date');
                FORM.setDatePicker('#end_date');
                for (name in CKEDITOR.instances) {
                    CKEDITOR.instances[name].destroy(true);
                }
                $("#form_brokingslip_generation textarea").each(function () {
                    $(this).ckeditor();
                    
                    
                });
                // let editorObj = $('textarea.editor').ckeditor();
                DIB.centerDialog();
            });
        });
    };
    DibQuoterequest.prototype.generateMainBrokenSlipPage = function () {
        var _that = this;
        $(document).off('click', '#dp_generate_broken_slip');
        $(document).on('click', '#dp_generate_broken_slip', function () {
            $.ajax({
                url: $(this).attr('openUrl'),
                type: "GET"
            }).done(function (data) {
                $("#db_brokenslip_popup").remove();
                $('body').append('<div id="db_brokenslip_popup" title="Create broking slip" style="display:none" >' + data.content + '</div>');
                var dialogElement = $("#db_brokenslip_popup");
                dialogElement.dialog({
                    width: 900,
                    modal: true,
                    buttons: {
                        "Generate": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text: 'Generate',
                            click: function () {
                                // dialogElement.dialog('close');
                                //   dialogElement.remove();  
                                var isValid = true;
                                var errorMessage = "";
                                var i = 0;
                                $("#form_brokingslip_generation .required:visible").each(function () {
                                    if ($(this).val() == '' || $(this).val() == null) {
                                        isValid = false;
                                        $(this).addClass('error');
                                        if (i == 0) {
                                            errorMessage += "<b>The following errors occurred while validating data:" + "</b><br/>";
                                            i++;
                                        }
                                        errorMessage += "<b>" + $(this).attr('error-message') + "</b><br/>";
                                    }
                                    else {
                                        $(this).removeClass('error');
                                    }
                                });
                                if (isValid) {
                                    $("form#form_brokingslip_generation").submit();
                                }
                                else {
                                    DIB.alert(errorMessage, 'Error!!!!');
                                }
                            }
                        },
                    },
                    open: function (event, ui) {
                        _that.generateBrokenSlipForm();
                    }
                });
                DIB.centerDialog();
            });
        });
    };
    DibQuoterequest.prototype.deleteBrokingSlip = function () {
        var _that = this;
        $(document).off('click', '.dpib_delete_brokingslip');
        $(document).on('click', '.dpib_delete_brokingslip', function () {
            var ajaxUrl = $(this).attr('actionUrl');
            $("#db_brokenslip_delete_popup").remove();
            $('body').append('<div id="db_brokenslip_delete_popup" title="Delete broking slip" style="display:none" >Do you really want to delete?</div>');
            var dialogElement = $("#db_brokenslip_delete_popup");
            dialogElement.dialog({
                width: 400,
                modal: true,
                buttons: {
                    "Delete": {
                        class: "btn waves-effect waves-light btn-rounded btn-success",
                        text: 'Delete',
                        click: function () {
                            dialogElement.dialog('close');
                            $.ajax({
                                url: ajaxUrl,
                                type: "GET"
                            }).done(function (data) {
                                location.reload(true);
                            });
                        }
                    },
                    "cancel": {
                        class: "btn waves-effect waves-light btn-rounded btn-danger",
                        text: 'Cancel',
                        click: function () {
                            dialogElement.dialog('close');
                            dialogElement.remove();
                        }
                    }
                },
                open: function (event, ui) {
                }
            });
            DIB.centerDialog();
        });
    };
    DibQuoterequest.prototype.sendBrokingSlip = function () {
        var _that = this;
        $(document).off('click', '.dpib_brokingslip_sendmail');
        $(document).on('click', '.dpib_brokingslip_sendmail', function () {
            var title = $(this).attr('data-title');
            $.ajax({
                url: $(this).attr('openUrl'),
                type: "GET"
            }).done(function (data) {
                $("#db_brokenslip_send_popup").remove();
                $('body').append('<div id="db_brokenslip_send_popup" title="' + title + '" style="display:none" >' + data.content + '</div>');
                var dialogElement = $("#db_brokenslip_send_popup");
                dialogElement.dialog({
                    width: 900,
                    modal: true,
                    buttons: {
                        "Send": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text: 'Send',
                            click: function () {
                                // dialogElement.dialog('close');
                                //   dialogElement.remove();  
                                var isValid = true;
                                var errorMessage = "";
                                var i = 0;
                                $("#form_brokingslip_sendmail .required:visible").each(function () {
                                    if ($(this).val() == '' || $(this).val() == null) {
                                        isValid = false;
                                        $(this).addClass('error');
                                        if (i == 0) {
                                            errorMessage += "<b>The following errors occurred while validating data:" + "</b><br/>";
                                            i++;
                                        }
                                        errorMessage += "<b>" + $(this).attr('error-message') + "</b><br/>";
                                    }
                                    else if ($(this).attr('type') == 'email' && (/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/.test($(this).val()) == false)) {
                                        isValid = false;
                                        $(this).addClass('error');
                                        if (i == 0) {
                                            errorMessage += "<b>The following errors occurred while validating data:" + "</b><br/>";
                                            i++;
                                        }
                                        errorMessage += "<b>Please enter valid email address</b><br/>";
                                    }
                                    else {
                                        $(this).removeClass('error');
                                    }
                                });
                                if (isValid) {
                                    $("form#form_brokingslip_sendmail").submit();
                                }
                                else {
                                    DIB.alert(errorMessage, 'Error!!!!');
                                }
                            }
                        },
                    }
                });
                DIB.centerDialog();
            });
            DIB.centerDialog();
        });
    };
    DibQuoterequest.prototype.uploadQuoteForm = function () {
        var _that = this;
        $(document).on('click', '.dpib_upload_quote', function () {
            $.ajax({
                url: $(this).attr('actionUrl'),
                type: "GET"
            }).done(function (data) {
                $("#db_quote_upload_form").remove();
                $('body').append('<div id="db_quote_upload_form" title="Quote upload" style="display:none" >' + data.content + '</div>');
                var dialogElement = $("#db_quote_upload_form");
                dialogElement.dialog({
                    width: 900,
                    modal: true,
                    buttons: {
                        "Update": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text: 'Update',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
                                $("form#form_quote_add").submit();
                            }
                        },
                        "cancel": {
                            class: "btn waves-effect waves-light btn-rounded btn-danger",
                            text: 'Cancel',
                            click: function () {
                                dialogElement.dialog('close');
                                dialogElement.remove();
                            }
                        }
                    }
                });
                DIB.centerDialog();
            });
        });
    };
    DibQuoterequest.prototype.sendIssuanceDocumentForm = function () {
        var _that = this;
        $(document).on('click', '.dpib_send_issuance_document', function () {
            $.ajax({
                url: $(this).attr('openurl'),
                type: "GET"
            }).done(function (data) {
                $("#dpib_send_issuance_document_form").remove();
                $('body').append('<div id="dpib_send_issuance_document_form" title="Send issuance document" style="display:none" >' + data.content + '</div>');
                var dialogElement = $("#dpib_send_issuance_document_form");
                dialogElement.dialog({
                    width: 900,
                    modal: true,
                    buttons: {
                        "Send": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text: 'Send',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
                                $("form#form_issuance_doc_sendmail").submit();
                            }
                        },
                        "cancel": {
                            class: "btn waves-effect waves-light btn-rounded btn-danger",
                            text: 'Cancel',
                            click: function () {
                                dialogElement.dialog('close');
                                dialogElement.remove();
                            }
                        }
                    }
                });
                DIB.centerDialog();
            });
        });
    };
    DibQuoterequest.prototype.changeNotificationFlag = function () {
        $(document).off('click', '.dpib_request_flag_change');
        $(document).on('click', '.dpib_request_flag_change', function () {
            $.ajax({
                url: $(this).attr('data-url'),
                type: "GET"
            }).done(function (data) {
                location.reload(true);
            });
        });
    };
    return DibQuoterequest;
}());
