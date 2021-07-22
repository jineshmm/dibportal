var nrequire;
/// <reference path="../Typescript/directives/jquery.d.ts" />
/// <reference path="../Typescript/directives/underscore.d.ts" />
/// <reference path="../Typescript/directives/jqueryui.d.ts" />
var DibPolicyAdd = /** @class */ (function () {
    function DibPolicyAdd(options) {
        this.defaultSettings = {
            requestFormUrl: '',
        };
        this.settings = $.extend(true, {}, this.defaultSettings, options);
    }
    DibPolicyAdd.prototype.initialSetting = function () {
        this.savePolicyDetails();
        this.generateProductFields();
        this.generateInstallmentSchedules();
        this.generatepersonObjectContent();
        this.removeExtraAddedObject();
        this.productobjectEdit();
        this.productObjectDelete();
        this.createObject();
        this.regenerateInstallment();
        this.editPolicyPremium();
        this.addPreviousPolicy();
        this.installmentCheckbox();
        this.installmentEdit();
        this.changePolicyStatus();
        this.generateInvoice();
    };
    DibPolicyAdd.prototype.savePolicyDetails = function () {
        var _that = this;
        $(document).off('click', '.submit_policy');
        $(document).on('click', '.submit_policy', function () {
            var type = $(this).attr('step-type');
            var step = $(this).attr('step');
            $("#policy_step").val(step);
            $("#step_type").val(type);
            var form = $('form#savepolicyForm');
            var url = form.attr('action');
            if (type == 'back') {
                _that.backwardButtonAction(step);
                return;
            }
            if (_that.validatePolicyData(step, type)) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form.serialize(),
                    success: function (data) {
                        $('#step-backward').hide();
                        if (data.status) {
                            $('#step-continue').attr('step', data.step);
                        }
                        if (data.backButton) {
                            $('#step-backward').show();
                            $('#step-backward').attr('step', data.step - 1);
                        }
                        $('.tabs-vertical').find('a.active').removeClass('active');
                        if (step == 1) {
                            $(".customvtab").tabs("enable", 1);
                            $("#policy_step2_section").empty();
                            $("#policy_step2_section").html(data.content);
                            $("#policy_step2_section").show();
                            $("#policy_id").val(data.policyId);
                            $("#step1").addClass('done');
                            $(".customvtab").tabs('option', 'active', 1);
                            $("#step2").find('.nav-link').addClass('active');
                        }
                        else if (step == 2) {
                            $(".customvtab").tabs("enable", 2);
                            $("#policy_step3_section").empty();
                            $("#policy_step3_section").html(data.content);
                            $("#policy_step3_section").show();
                            $("#step2").addClass('done');
                            $(".customvtab").tabs('option', 'active', 2);
                            $("#step3").find('.nav-link').addClass('active');
                        }
                        else if (step == 3) {
                            $(".customvtab").tabs("enable", 3);
                            $("#policy_step4_section").empty();
                            $("#policy_step4_section").html(data.content);
                            $("#policy_step4_section").show();
                            $("#step3").addClass('done');
                            $(".customvtab").tabs('option', 'active', 3);
                            $("#step4").find('.nav-link').addClass('active');
                        }
                        else {
                            window.location.href = data.returnUrl;
                        }
                    }
                });
            }
        });
    };
    DibPolicyAdd.prototype.generateProductFields = function () {
        var _that = this;
        $(document).off('change', '#policy_product');
        $(document).on('change', '#policy_product', function () {
            var productId = $(this).val();
            $.ajax({
                url: $(this).attr('openUrl'),
                method: "post",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'product': $(this).val(),
                    'product-title': $("#policy_product option:selected").text()
                }
            }).done(function (data) {
                $("#form_product_field_area").empty();
                $("#form_product_field_area").html(data.content);
                $("#form_product_field_area").show();
                $("#form_object").hide();
                $("#form_addobjects").hide();
                if (productId == 38) {
                    $("#form_addobjects").show();
                    $(".dipib_add_extra_policy_obj").attr('object-type', 'person');
                }
                else if (productId == 27) {
                    $("#form_addobjects").show();
                    $(".dipib_add_extra_policy_obj").attr('object-type', 'vehicle');
                }
                if (data.objectFlag) {
                    $("#form_object").empty();
                    $("#form_object").html(data.objectHtml);
                    $("#form_object").show();
                }
                FORM.setDatePicker("#savepolicyForm input:text.datefield");
            });
        });
    };
    DibPolicyAdd.prototype.generateInstallmentSchedules = function () {
        var _that = this;
        $(document).off('click', '#dip_installment_generate');
        $(document).on('click', '#dip_installment_generate', function () {
            if ($("tr.installmentschedulerow").length > 0) {
                if (!confirm(LOCALE.get('DIB.POLICY.Action.GenerateSchedule.Confirm')))
                    return;
            }
            if ($("#policy_installments").val() > 0) {
                $.ajax({
                    url: $(this).attr('openUrl'),
                    method: "post",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'premium_amount': $("#policy_premium_sum").val(),
                        'installment_number': $("#policy_installments").val(),
                        'policyId': $("#policy_id").val(),
                        'additional_amount': $("#policy_additional_amount").val()
                    }
                }).done(function (data) {
                    $("#fieldgroup_installmentschedule").empty();
                    $("#fieldgroup_installmentschedule").html(data.content);
                    $("#fieldgroup_installmentschedule").show();
                    FORM.setDatePicker("#savepolicyForm input:text.datefield");
                });
            }
            else {
                $("#dpib_installment_table").find("tr:gt(0)").remove();
            }
        });
    };
    DibPolicyAdd.prototype.generatepersonObjectContent = function () {
        $(document).off('click', '.dipib_add_extra_policy_obj');
        $(document).on('click', '.dipib_add_extra_policy_obj', function () {
            var extraobjectCurrentCount = $('#extra_object_count').val();
            var nextCount = parseInt(extraobjectCurrentCount) + 1;
            if ($(this).attr('object-type') == 'person') {
                var data = {
                    count: nextCount,
                    title: 'Person'
                };
                var template = _.template($("#person_object_template").html());
            }
            else {
                var data = {
                    count: nextCount,
                    title: 'Vehicle'
                };
                var template = _.template($("#vehicle_object_template").html());
            }
            var result = template(data);
            $("#extra_object_append_area").append(result);
            $('#extra_object_count').val(nextCount);
            FORM.setDatePicker("#savepolicyForm input:text.datefield");
        });
    };
    DibPolicyAdd.prototype.removeExtraAddedObject = function () {
        $(document).off('click', '.dpib_delete_object');
        $(document).on('click', '.dpib_delete_object', function () {
            $(this).parents('.form_extra_object').remove();
        });
    };
    DibPolicyAdd.prototype.backwardButtonAction = function (step) {
        $("#policy_step1_section").hide();
        $("#policy_step2_section").hide();
        $("#policy_step3_section").hide();
        $("#policy_step4_section").hide();
        $('.tabs-vertical').find('a.active').removeClass('active');
        $('#step-backward').hide();
        if (step == 1) {
            $("#policy_step1_section").show();
            $('#step-continue').attr('step', 1);
            $(".customvtab").tabs('option', 'active', 0);
            $("#step1").find('.nav-link').addClass('active');
        }
        else if (step == 2) {
            $("#policy_step2_section").show();
            $('#step-backward').show();
            $('#step-backward').attr('step', 1);
            $('#step-continue').attr('step', step);
            $(".customvtab").tabs('option', 'active', 1);
            $("#step2").find('.nav-link').addClass('active');
        }
        else {
            $("#policy_step3_section").show();
            $('#step-backward').show();
            $('#step-backward').attr('step', 2);
            $('#step-continue').attr('step', step);
            $(".customvtab").tabs('option', 'active', 2);
            $("#step3").find('.nav-link').addClass('active');
        }
    };
    DibPolicyAdd.prototype.productobjectEdit = function () {
        var _this = this;
        $(document).off('click', '.dp_policy_object_edit');
        $(document).on('click', '.dp_policy_object_edit', function () {
            $.ajax({
                url: $(this).attr('data-url'),
                type: "GET"
            }).done(function (data) {
                $("#db_policy_object_popup").remove();
                $('body').append('<div id="db_policy_object_popup" title="Edit object" style="display:none" >' + data.content + '</div>');
                var dialogElement = $("#db_policy_object_popup");
                dialogElement.dialog({
                    width: 900,
                    modal: true,
                    buttons: {
                        "Save": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text: 'Save',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                                $("form#objectForm").submit();
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
                        FORM.setDatePicker('.datefield');
                    }
                });
                DIB.centerDialog();
            });
        });
    };
    DibPolicyAdd.prototype.productObjectDelete = function () {
        $(document).on('click', '.dp_policy_object_remove', function () {
            if (!confirm(LOCALE.get('dib.object.action.delete.confirm'))) {
                return;
            }
            DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
            $.ajax({
                url: $(this).attr('data-url'),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'objId': $(this).attr('objId'),
                    'policyId': $(this).attr('product-id')
                }
            }).done(function (data) {
                if (data.success) {
                    location.reload(true);
                }
            });
        });
    };
    DibPolicyAdd.prototype.createObject = function () {
        var _this = this;
        $(document).off('click', '.dp_policy_object_add');
        $(document).on('click', '.dp_policy_object_add', function () {
            $.ajax({
                url: $(this).attr('data-url'),
                type: "GET"
            }).done(function (data) {
                $("#db_policy_object_popup").remove();
                $('body').append('<div id="db_policy_object_popup" title="Create object" style="display:none" >' + data.content + '</div>');
                var dialogElement = $("#db_policy_object_popup");
                dialogElement.dialog({
                    width: 900,
                    modal: true,
                    buttons: {
                        "Save": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text: 'Save',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                                $("form#objectForm").submit();
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
                        FORM.setDatePicker('.datefield');
                    }
                });
                DIB.centerDialog();
            });
        });
    };
    DibPolicyAdd.prototype.regenerateInstallment = function () {
        var _this = this;
        $(document).off('click', '#dpib_regenerate_installment');
        $(document).on('click', '#dpib_regenerate_installment', function () {
            if (!confirm('Do you really want to re-generate installment')) {
                return;
            }
            DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
            $.ajax({
                url: $(this).attr('data-url'),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'policyId': $(this).attr('policyId')
                }
            }).done(function (data) {
                if (data.success) {
                    location.reload(true);
                }
            });
        });
    };
    DibPolicyAdd.prototype.editPolicyPremium = function () {
        var _this = this;
        $(document).off('click', '#dpib_edit_premium_info');
        $(document).on('click', '#dpib_edit_premium_info', function () {
            $.ajax({
                url: $(this).attr('data-url'),
                type: "GET"
            }).done(function (data) {
                $("#db_policy_object_popup").remove();
                $('body').append('<div id="db_policy_object_popup" title="Edit premium info" style="display:none" >' + data.content + '</div>');
                var dialogElement = $("#db_policy_object_popup");
                dialogElement.dialog({
                    width: 900,
                    modal: true,
                    buttons: {
                        "Save": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text: 'Save',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                                console.log('click');
                                $("form#updatepremiumForm").submit();
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
                });
                DIB.centerDialog();
            });
        });
    };
    DibPolicyAdd.prototype.addPreviousPolicy = function () {
        $(document).off('change', '#policy_salestype');
        $(document).on('change', '#policy_salestype', function () {
            if ($(this).val() == '2') {
                $('#field_policy_renewal').show();
            }
            else {
                $('#field_policy_renewal').hide();
            }
        });
    };
    DibPolicyAdd.prototype.installmentCheckbox = function () {
        $(document).off('click', '.dib_installemnt_select_box');
        $(document).on('click', '.dib_installemnt_select_box', function () {
            if ($(this).parent('.element').find('.dib_select_box').is(":not(:checked)")) {
                $(this).removeClass('icon-check').addClass('icon-check');
                $(this).parent('.element').find('.dib_select_box').attr('checked', true);
                $(this).parent('.element').find('.installment_paid_status').val(1);
            }
            else {
                $(this).removeClass('icon-check');
                $(this).parent('.element').find('.dib_select_box').attr('checked', false);
                $(this).parent('.element').find('.installment_paid_status').val(0);
            }
        });
    };
    DibPolicyAdd.prototype.installmentEdit = function () {
        $(document).on('click', '.dpib_edit_installment', function () {
            $.ajax({
                url: $(this).attr('data-url'),
                type: "GET"
            }).done(function (data) {
                $("#db_policy_installment_popup").remove();
                $('body').append('<div id="db_policy_installment_popup" title="Edit installment info" style="display:none" >' + data.content + '</div>');
                var dialogElement = $("#db_policy_installment_popup");
                dialogElement.dialog({
                    width: 900,
                    modal: true,
                    buttons: {
                        "Update": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text: 'Update',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                                $("form#form_installment_edit").submit();
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
                        FORM.setDatePicker('#form_installment_edit.datefield');
                    }
                });
                DIB.centerDialog();
            });
        });
    };
    DibPolicyAdd.prototype.changePolicyStatus = function () {
        $(document).off('click', '.dpib_policy_flag_change');
        $(document).on('click', '.dpib_policy_flag_change', function () {
            var changeUrl = $(this).attr('data-url');
            var flagValue = $(this).attr('changeflag');
            if ($(this).attr('changeflag') == 4) {
                $("#db_policy_change_status_popup").remove();
                $('body').append('<div id="db_policy_change_status_popup" title="Delete policy" style="display:none" >Do you want to delete policy details?</div>');
                var dialogElement = $("#db_policy_change_status_popup");
                dialogElement.dialog({
                    width: 900,
                    resizable: false,
                    bgiframe: true,
                    modal: true,
                    buttons: {
                        "Delete": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text: 'Delete',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                                $.ajax({
                                    url: changeUrl,
                                    type: "post",
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: {
                                        'flag': flagValue
                                    }
                                }).done(function (data) {
                                    if (data.status) {
                                        window.location.href = data.redirect;
                                    }
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
                    }
                });
                DIB.centerDialog();
            }
            else if ($(this).attr('changeflag') == 2) {
                var template = _.template($("#policy_issued_template").html());
                var result = template();
                $("#db_policy_change_status_popup").remove();
                $('body').append('<div id="db_policy_change_status_popup" title="Issue/activate policy" style="display:none" >' + result + '</div>');
                var dialogElement = $("#db_policy_change_status_popup");
                dialogElement.dialog({
                    width: 900,
                    modal: true,
                    buttons: {
                        "Activate": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text: 'Activate',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                                // if ($.trim($("#policy_number").val()) != '') {
                                $("#policy_number").removeClass('error');
                                $("form#form_policy_status_change").submit();
                                $("#policy_number").removeClass('error');
                                // } else {
                                //   DIB.alert('<b> Please enter policy number</b>', 'Error!!!!');
                                //   $("#policy_number").addClass('error')
                                // }
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
            }
            else {
                var template = _.template($("#policy_post_template").html());
                var result = template();
                $("#db_policy_change_status_popup").remove();
                $('body').append('<div id="db_policy_change_status_popup" title="Post policy" style="display:none" >' + result + '</div>');
                var dialogElement = $("#db_policy_change_status_popup");
                dialogElement.dialog({
                    width: 900,
                    modal: true,
                    buttons: {
                        "Activate": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text: 'Post',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                                if ($.trim($("#policy_number").val()) != '') {
                                    $("#policy_number").removeClass('error');
                                    $("form#form_policy_status_change").submit();
                                    $("#policy_number").removeClass('error');
                                }
                                else {
                                    DIB.alert('<b> Please enter policy number</b>', 'Error!!!!');
                                    $("#policy_number").addClass('error');
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
                    }
                });
                DIB.centerDialog();
                // $.ajax({
                //   url: changeUrl,
                //   type: "post",
                //   headers: {
                //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //   },
                //   data: {
                //     'flag': flagValue
                //   }
                // }).done(function(data) {
                //   if (data.status) {
                //     location.reload(true);
                //   }
                // });
            }
        });
    };
    DibPolicyAdd.prototype.generateInvoice = function () {
        var _this = this;
        $(document).off('click', '#dpib_generate_invoice');
        $(document).on('click', '#dpib_generate_invoice', function () {
            var myCbValuesArray = $(".paymentIds").map(function () {
                if ($(this).is(":checked")) {
                    return $(this).val();
                }
            }).get();
            DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
            $.ajax({
                url: $(this).attr('data-url'),
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'policyId': $(this).attr('policyId'),
                    'installmentIds': myCbValuesArray
                }
            }).done(function (data) {
                if (data.success) {
                    location.reload(true);
                }
            });
        });
    };
    DibPolicyAdd.prototype.validatePolicyData = function (step, type) {
        var isValid = true;
        var errorMessage = "";
        var i = 0;
        $("form#savepolicyForm .required:visible").each(function () {
            if ($(this).val() == '') {
                isValid = false;
                $(this).parent('.form-group').addClass('error');
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
            return true;
        }
        else {
            DIB.alert(errorMessage, 'Error!!!!');
            return false;
        }
    };
    return DibPolicyAdd;
}());
