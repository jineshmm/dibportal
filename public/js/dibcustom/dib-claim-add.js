var nrequire;
/// <reference path="../Typescript/directives/jquery.d.ts" />
/// <reference path="../Typescript/directives/underscore.d.ts" />
/// <reference path="../Typescript/directives/jqueryui.d.ts" />
var DibClaimAdd = /** @class */ (function () {
    function DibClaimAdd(options) {
        this.defaultSettings = {
            requestFormUrl: '',
        };
        this.settings = $.extend(true, {}, this.defaultSettings, options);
    }
    DibClaimAdd.prototype.initialSetting = function () {
        this.saveClaimDetails();
        this.addClaimant();
        this.checkboxClick();
        this.removeClaimant();
        this.createClaimant();
        this.deleteClaimant();
        this.editClaimantInfo();
        this.editStatus();
    };
    DibClaimAdd.prototype.saveClaimDetails = function () {
        var _that = this;
        $(document).off('click', '.submit_claim');
        $(document).on('click', '.submit_claim', function () {
            var type = $(this).attr('step-type');
            var step = $(this).attr('step');
            $("#claim_step").val(step);
            $("#step_type").val(type);
            var form = $('form#saveclaimForm');
            var url = form.attr('action');
            if (type == 'back') {
                _that.backwardButtonAction(step);
                return;
            }
            if (step == 1) {
                if ($('#complaint_policy').val() == '') {
                    DIB.alert('<b> Please select policy number</b>', 'Error!!!!');
                    return;
                }
            }
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
                        $("#claim_step2_section").empty();
                        $("#claim_step2_section").html(data.content);
                        $("#claim_step2_section").show();
                        $("#claim_id").val(data.claimId);
                        $("#step1").addClass('done');
                        $(".customvtab").tabs('option', 'active', 1);
                        $("#step2").find('.nav-link').addClass('active');
                    }
                    else if (step == 2) {
                        $(".customvtab").tabs("enable", 2);
                        $("#claim_step3_section").empty();
                        $("#claim_step3_section").html(data.content);
                        $("#claim_step3_section").show();
                        $("#step2").addClass('done');
                        $(".customvtab").tabs('option', 'active', 2);
                        $("#step3").find('.nav-link').addClass('active');
                    }
                    else {
                        window.location.href = data.returnUrl;
                    }
                }
            });
            // $('form#savepolicyForm').submit();
        });
    };
    DibClaimAdd.prototype.backwardButtonAction = function (step) {
        $("#claim_step1_section").hide();
        $("#claim_step2_section").hide();
        $("#claim_step3_section").hide();
        $("#claim_step4_section").hide();
        $('.tabs-vertical').find('a.active').removeClass('active');
        $('#step-backward').hide();
       
        if (step == 1) {
            $("#claim_step1_section").show();
            $('#step-continue').attr('step', 1);
            $(".customvtab").tabs('option', 'active', 0);
            $("#step2").find('.nav-link').addClass('active');
        }
        else if (step == 2) {
            $("#claim_step2_section").show();
            $('#step-backward').show();
            $('#step-backward').attr('step', 1);
            $('#step-continue').attr('step', step);
            $(".customvtab").tabs('option', 'active', 1);
            $("#step2").find('.nav-link').addClass('active');
        }
        else {
            $("#claim_step3_section").show();
            $('#step-backward').show();
            $('#step-backward').attr('step', 2);
            $('#step-continue').attr('step', step);
            $(".customvtab").tabs('option', 'active', 2);
            $("#step3").find('.nav-link').addClass('active');
        }
    };
    DibClaimAdd.prototype.addClaimant = function () {
        var _that = this;
        $(document).off('click', '.dpib_add_claimant');
        $(document).on('click', '.dpib_add_claimant', function () {
            var rand = _.random(0, 100);
            var selectedClaim = $("#claimanttype option:selected").text();
            var selectedClaimType = $("#claimanttype option:selected").val();
            var data = {
                title: selectedClaim,
                'randomNum': rand
            };
           var template='';
            if(selectedClaimType ==3) {
                template = _.template($("#claimant_object_template_medical").html());
            } else if(selectedClaimType==4) {
                 template = _.template($("#claimant_object_template_motor").html());
            } else {
              template = _.template($("#claimant_object_template").html());  
            }
            
            var result = template(data);
            $("#claimant_object_append_area").prepend(result);
            $("#claimanttype_" + rand).val($("#claimanttype").val());
        });
    };
    DibClaimAdd.prototype.checkboxClick = function () {
        $(document).off('click', '#policyholder_is_claimant');
        $(document).on('click', '#policyholder_is_claimant', function () {
            if (!$(this).is(":not(:checked)")) {
                $(this).parent('.element').find('span').removeClass('icon-check').addClass('icon-check');
                $(this).attr('checked', true);
                $(this).val(1);
            }
            else {
                $(this).parent('.element').find('span').removeClass('icon-check');
                $(this).attr('checked', false);
                $(this).val(0);
            }
        });
    };
    DibClaimAdd.prototype.removeClaimant = function () {
        $(document).off('click', '.dpib_remove_claimant_info');
        $(document).on('click', '.dpib_remove_claimant_info', function () {
            var divId = $(this).attr('remove_id');
            $("#" + divId).remove();
        });
    };
    DibClaimAdd.prototype.createClaimant = function () {
        $(document).off('click', '.dpib_claimant_create');
        $(document).on('click', '.dpib_claimant_create', function () {
            var calimantType = $(this).attr('create-type');
            var template = '';
            $("#db_claim_create_popup").remove();
            if(calimantType ==3) {
                template = _.template($("#claim_claimant_template_medical").html());
            } else if(calimantType==4) {
                 template = _.template($("#claim_claimant_template_motor").html());
            } else {
              template = _.template($("#claim_claimant_template").html());  
            }

            var result = template();
            $('body').append('<div id="db_claim_create_popup" title="Add claimant" style="display:none" >' + result + '</div>');
            var dialogElement = $("#db_claim_create_popup");
            dialogElement.dialog({
                width: 900,
                modal: true,
                buttons: {
                    "Save": {
                        class: "btn waves-effect waves-light btn-rounded btn-success",
                        text: 'Save',
                        click: function () {
                            DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                            $("form#form_claimant_add").submit();
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
                open: function () {
                    $("#claimanttype").val(calimantType);
                }
            });
            DIB.centerDialog();
        });
    };
    DibClaimAdd.prototype.deleteClaimant = function () {
        $(document).off('click', '.dpib_delete_claimant_info');
        $(document).on('click', '.dpib_delete_claimant_info', function () {
            DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
            var deleteUrl = $(this).attr('data-url');
            var claimId = $(this).attr('claim-id');
            $('body').append('<div id="db_policy_change_status_popup" title="Delete claimant" style="display:none" >Do you want to delete claimant info?</div>');
            var dialogElement = $("#db_policy_change_status_popup");
            dialogElement.dialog({
                width: 900,
                modal: true,
                buttons: {
                    "Delete": {
                        class: "btn waves-effect waves-light btn-rounded btn-success",
                        text: 'Delete',
                        click: function () {
                            DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                            $.ajax({
                                url: deleteUrl,
                                type: "post",
                                data: {
                                    'claimId': claimId
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
        });
    };
    DibClaimAdd.prototype.editClaimantInfo = function () {
        $(document).off('click', '.dpib_edit_claimant_info');
        $(document).on('click', '.dpib_edit_claimant_info', function () {
            var dataDetails = $.parseJSON($(this).attr('data'));
            var originalType = $(this).attr('data-originalType');
            var template ='';
            if(originalType ==3) {
                data = {
                    'claimantName': dataDetails['name'],
                    'claimantId': dataDetails['id'],
                    'idNumber':dataDetails['Idnumber'],
                    'membershipNumber':dataDetails['membershipNumber']
                };
                 template = _.template($("#claim_editclaimant_template_medical").html());
            } else if(originalType ==4){
                data = {
                    'claimantName': dataDetails['name'],
                    'claimantId': dataDetails['id'],
                    'plateNumber':dataDetails['plateNumber'],
                    'chaseNumber':dataDetails['chaseNumber'],
                    'certificateNumber':dataDetails['certificateNumber']
                };
                 template = _.template($("#claim_editclaimant_template_motor").html());
            } else {
                data = {
                    'claimantName': dataDetails['name'],
                    'claimantId': dataDetails['id']
                };  
                 template = _.template($("#claim_editclaimant_template").html());
            }
            
            $("#db_claim_edit_popup").remove();
            
            var result = template(data);
            $('body').append('<div id="db_claim_edit_popup" title="Edit claimant" style="display:none" >' + result + '</div>');
            var dialogElement = $("#db_claim_edit_popup");
            dialogElement.dialog({
                width: 900,
                modal: true,
                buttons: {
                    "Update": {
                        class: "btn waves-effect waves-light btn-rounded btn-success",
                        text: 'Update',
                        click: function () {
                            DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                            $("form#form_claimant_add").submit();
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
    };
    DibClaimAdd.prototype.editStatus = function () {
        $(document).off('click', '.dpib_policy_flag_change');
        $(document).on('click', '.dpib_policy_flag_change', function () {
            $("#db_claim_edit_popup").remove();
            var template = _.template($("#claim_updatestatus_template").html());
            var result = template();
            $('body').append('<div id="db_claim_edit_popup" title="Change status" style="display:none" >' + result + '</div>');
            var dialogElement = $("#db_claim_edit_popup");
            dialogElement.dialog({
                width: 900,
                modal: true,
                buttons: {
                    "Update": {
                        class: "btn waves-effect waves-light btn-rounded btn-success",
                        text: "Update",
                        click: function () {
                            DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                            $("form#form_claimant_add").submit();
                        }
                    },
                    "cancel": {
                        class: "btn waves-effect waves-light btn-rounded btn-success",
                        text: "Cancel",
                        click: function () {
                            dialogElement.dialog('close');
                            dialogElement.remove();
                        }
                    }
                }
            });
            DIB.centerDialog();
        });
    };
    return DibClaimAdd;
}());
