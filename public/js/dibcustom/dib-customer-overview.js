/// <reference path="../Typescript/directives/jquery.d.ts" />
/// <reference path="../Typescript/directives/underscore.d.ts" />
/// <reference path="../Typescript/directives/jqueryui.d.ts" />
var Customeroverview = /** @class */ (function () {
    function Customeroverview(options) {
        this.defaultSettings = {
            parent: '',
            template: '#teamSettingPageTemplate',
            savepath: '',
            teamImage: '',
            uploadPath: '',
            contactpersonAddUrl: '',
        };
        this.settings = $.extend(true, {}, this.defaultSettings, options);
    }
    Customeroverview.prototype.initialSettings = function () {
        this.noteAdd();
        /*****    ADD MORE CONTACT PERSON   **/
        this.contactpersonAdd();
        /*****    EDIT CONTACT PERSON DETAILS  **/
        this.contactpersonEdit();
        /*****    DELETE CONTACT PERSON DETAILS  **/
        this.contactpersonDelete();
        /*****  ADD ADDRESS AREA   *****/
        this.addressAdd();
        /**** DELETE ADDRESS    *****/
        this.addressDelete();
        /***** EDIT ADDRESS   ****/
        this.addressEdit();
        this.connectCustomer();
        this.editCustomerConnection();
        this.deleteConnection();
    };
    Customeroverview.prototype.noteAdd = function () {
        $(document).on('click', '.dp_customer_note_add', function () {
            $("#dp_note_add").remove();
            $('body').append('<div id="dp_note_add" title="Add note" style="display:none" >' + $("#add_note_template_1").html() + '</div>');
            var dialogElement = $("#dp_note_add");
            var ajaxUrl = $('#dp_note_add .note_add_entry').attr('default_setting');
            var overviewUrl = $('#dp_note_add .note_add_entry').attr('overview_url');
            dialogElement.dialog({
                width: 500,
                modal: true,
                buttons: {
                    "Add note": {
                        class: "btn waves-effect waves-light btn-rounded btn-success dp_add_note",
                        text: 'Add note',
                        click: function () {
                            DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
                            if ($.trim($('#dp_note_add .note_add_entry').val()) != '') {
                                $.ajax({
                                    url: ajaxUrl,
                                    method: "post",
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: { note: $('#dp_note_add .note_add_entry').val() },
                                    success: function (data) {
                                        DIB.closeProgressDialog();
                                        if (data.status == 'success') {
                                            window.location.href = overviewUrl;
                                        }
                                        else {
                                        }
                                    },
                                    error: function () {
                                        DIB.closeProgressDialog();
                                    }
                                });
                            }
                            else {
                                DIB.closeProgressDialog();
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
                close: function (event, ui) {
                }
            });
            DIB.centerDialog();
        });
    };
    Customeroverview.prototype.contactpersonAdd = function () {
        var that = this;
        $(document).on('click', '.dpib_add_contact_person_more', function () {
            $("#dp_contact_person_add").remove();
            $.ajax({
                url: that.settings.contactpersonAddUrl,
                type: "GET"
            }).done(function (data) {
                $('body').append('<div id="dp_contact_person_add" title="Add a contact person" style="display:none" >' + data.content + '</div>');
                var dialogElement = $("#dp_contact_person_add");
                dialogElement.dialog({
                    width: 900,
                    modal: true,
                    buttons: {
                        "Save": {
                            class: "btn waves-effect waves-light btn-rounded btn-success dp_add_new_contact",
                            text: 'Save',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
                                var isValid = true;
                                var errorMessage = "";
                                var i = 0;
                                $("#form_contact_person_add .required:visible").each(function () {
                                    if ($(this).val() == '') {
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
                                    $("#form_contact_person_add").submit();
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
                    close: function (event, ui) {
                    }
                });
            });
            DIB.centerDialog();
        });
    };
    Customeroverview.prototype.contactpersonEdit = function () {
        $(document).on('click', '.dpib_editcontactperson', function () {
            $("#dp_contact_person_edit").remove();
            $.ajax({
                url: $(this).attr('data-url'),
                type: "GET"
            }).done(function (data) {
                $('body').append('<div id="dp_contact_person_edit" title="Edit a contact person" style="display:none" >' + data.content + '</div>');
                var dialogElement = $("#dp_contact_person_edit");
                dialogElement.dialog({
                    width: 900,
                    modal: true,
                    buttons: {
                        "Save": {
                            class: "btn waves-effect waves-light btn-rounded btn-success dp_add_new_contact",
                            text: 'Save',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
                                var isValid = true;
                                var errorMessage = "";
                                var i = 0;
                                $("#form_contact_person_add .required:visible").each(function () {
                                    if ($(this).val() == '') {
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
                                    $("#form_contact_person_add").submit();
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
                    close: function (event, ui) {
                    }
                });
            });
            DIB.centerDialog();
        });
    };
    Customeroverview.prototype.contactpersonDelete = function () {
        $(document).on('click', '.dpib_deletecontactperson', function () {
            $("#dp_contact_person_delete").remove();
            $.ajax({
                url: $(this).attr('data-url'),
                type: "GET"
            }).done(function (data) {
                $('body').append('<div id="dp_contact_person_delete" title="Delete a contact person" style="display:none" >' + data.content + '</div>');
                var dialogElement = $("#dp_contact_person_delete");
                dialogElement.dialog({
                    width: 500,
                    modal: true,
                    buttons: {
                        "OK": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text: 'Ok',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
                                $("#form_contact_person_delete").submit();
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
                    close: function (event, ui) {
                    }
                });
            });
            DIB.centerDialog();
        });
    };
    Customeroverview.prototype.addressAdd = function () {
        var that = this;
        $(document).on('click', '.dpib_add_contact_address_more', function () {
            $("#dp_contact_address_add").remove();
            $.ajax({
                url: $(this).attr('data-url'),
                type: "GET"
            }).done(function (data) {
                $('body').append('<div id="dp_contact_address_add" title="Add national address" style="display:none" >' + data.content + '</div>');
                var dialogElement = $("#dp_contact_address_add");
                dialogElement.dialog({
                    width: 900,
                    modal: true,
                    buttons: {
                        "Save": {
                            class: "btn waves-effect waves-light btn-rounded btn-success dp_add_new_contact",
                            text: 'Save',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
                                $("#form_contact_address_add").submit();
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
                    close: function (event, ui) {
                    }
                });
            });
            DIB.centerDialog();
        });
    };
    Customeroverview.prototype.addressDelete = function () {
        $(document).on('click', '.dpib_deletecontactaddress', function () {
            $("#dp_contact_address_delete").remove();
            $.ajax({
                url: $(this).attr('data-url'),
                type: "GET"
            }).done(function (data) {
                $('body').append('<div id="dp_contact_address_delete" title="Delete national address " style="display:none" >' + data.content + '</div>');
                var dialogElement = $("#dp_contact_address_delete");
                dialogElement.dialog({
                    width: 500,
                    modal: true,
                    buttons: {
                        "OK": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text: 'Ok',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
                                $("#form_contact_address_delete").submit();
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
                    close: function (event, ui) {
                    }
                });
            });
            DIB.centerDialog();
        });
    };
    Customeroverview.prototype.addressEdit = function () {
        var that = this;
        $(document).on('click', '.dpib_editcontactaddress', function () {
            $("#dp_contact_address_add").remove();
            $.ajax({
                url: $(this).attr('data-url'),
                type: "GET"
            }).done(function (data) {
                $('body').append('<div id="dp_contact_address_add" title="Edit national address" style="display:none" >' + data.content + '</div>');
                var dialogElement = $("#dp_contact_address_add");
                dialogElement.dialog({
                    width: 900,
                    modal: true,
                    buttons: {
                        "Save": {
                            class: "btn waves-effect waves-light btn-rounded btn-success dp_add_new_contact",
                            text: 'Save',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
                                $("#form_contact_address_add").submit();
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
                    close: function (event, ui) {
                    }
                });
            });
            DIB.centerDialog();
        });
    };
    Customeroverview.prototype.connectCustomer = function () {
        var that = this;
        $(document).on('click', '.dpib_connect_customers', function () {
            $("#dp_customer_connection_add").remove();
            $.ajax({
                url: $(this).attr('data-url'),
                type: "GET"
            }).done(function (data) {
                $('body').append('<div id="dp_customer_connection_add" title="Add related customer" style="display:none" >' + data.content + '</div>');
                var dialogElement = $("#dp_customer_connection_add");
                dialogElement.dialog({
                    width: 900,
                    modal: true,
                    buttons: {
                        "Save": {
                            class: "btn waves-effect waves-light btn-rounded btn-success dp_add_new_contact",
                            text: 'Save',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
                                $("#form_customer_connection_add").submit();
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
                    close: function (event, ui) {
                    }
                });
            });
            DIB.centerDialog();
        });
    };
    Customeroverview.prototype.editCustomerConnection = function () {
        var that = this;
        $(document).on('click', '.dpib_connect_customers_edit', function () {
            $("#dp_customer_connection_add").remove();
            $.ajax({
                url: $(this).attr('data-url'),
                type: "GET"
            }).done(function (data) {
                $('body').append('<div id="dp_customer_connection_add" title=Edit related customer" style="display:none" >' + data.content + '</div>');
                var dialogElement = $("#dp_customer_connection_add");
                dialogElement.dialog({
                    width: 900,
                    modal: true,
                    buttons: {
                        "Update": {
                            class: "btn waves-effect waves-light btn-rounded btn-success dp_add_new_contact",
                            text: 'Update',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
                                $("#form_customer_connection_add").submit();
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
                    close: function (event, ui) {
                    }
                });
            });
            DIB.centerDialog();
        });
    };
    Customeroverview.prototype.deleteConnection = function () {
        $(document).on('click', '.dpib_deletecustomerconnection', function () {
            if (!confirm(LOCALE.get('dib.object.action.delete.confirm'))) {
                return;
            }
            DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
            $.ajax({
                url: $(this).attr('data-url'),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            }).done(function (data) {
                if (data.success) {
                    location.reload(true);
                }
            });
        });
    };
    return Customeroverview;
}());
