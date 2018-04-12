$(function () {
    
    
    
    /** VALIDATION RULES **/
    $.validator.addMethod("greterThanZero", function (value, element) {
        var regex = /^[1-9]{1,1}$/; //this is for numeric... you can do any regular expression you like...
        return this.optional(element) || regex.test(value);
    }, "Invalid Input");
    $.validator.addMethod("outcome", function (value, element) {
        return value.trim() == 'Select Program Outcome';
    }, "Program outcome is required.");
    $.validator.addMethod("selectOption", function (value, element) {
        return this.optional(element) || /^[1-9][0-9]*$/i.test(value);
    }, "This field is required.");
    $.validator.addMethod("checkValue", function (value, element) {
        var response = ((value > 0) && (value <= 100)) || ((value == 'test1') || (value == 'test2'));
        return response;
    }, "invalid value");

    $('.cal_date')
            .datepicker({
                autoclose: true,
                format: 'mm/dd/yyyy'
            });

    
    var base_url = $('#get_base_url').val();
    $('#program').on('change', function () {
        var $program_id = $(this).val();
        if ($program_id) {
            $.ajax({type: "POST",
                url: base_url + 'Extra_curricular_activities/Extra_curricular_activities/get_curriculum_box',
                data: {
                    program_id: $program_id
                },
                success: function (result) {
                    $('#curriculum_box').html(result);
                    $('#program_id').val($program_id);
                    //var post_data = {program_id: $program_id};
                    //generate_activity_table(post_data);
                     if ($.cookie('remember_pgm') !== 0) {
                        // set the option to selected that corresponds to what the cookie is set to
                            $('#curriculum option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
                            $('#curriculum').trigger('change');
                    }
                }
            });
        }
    });
    
    //FILTER BOX
    if ($.cookie('remember_pgm') !== 0) {
		// set the option to selected that corresponds to what the cookie is set to
		$('#program option[value="' + $.cookie('remember_pgm') + '"]').prop('selected', true);
                $('#program').trigger('change');
	}
    
    $('#filter_form').on('change', '#curriculum', function () {
        $.cookie('remember_crclm', $('#curriculum option:selected').val(), {expires: 90, path: '/'});
        var $curriculum_id = $(this).val();
        var $program_id = $('#program').val();
        if ($curriculum_id) {
            $.ajax({type: "POST",
                url: base_url + 'Extra_curricular_activities/Extra_curricular_activities/get_item_box',
                data: {
                    curriculum_id: $curriculum_id
                },
                success: function (result) {
                    $('#item_box').html(result);
                    $('#curriculum_id').val($curriculum_id);
                    $('#program_id').val($program_id);
                    //  var post_data = {program_id: $program_id, curriculum_id: $curriculum_id};
                    //generate_activity_table(post_data);
                    if ($.cookie('remember_crclm') !== 0) {
                                // set the option to selected that corresponds to what the cookie is set to
                                $('#item option[value="'+$.cookie('remember_term')+'"]').prop('selected', true);
                                $('#item').trigger('change');
                        }
                }
            });
        }
    });
    
//    if ($.cookie('remember_crclm') !== null) {
//        alert($.cookie('remember_crclm'));
//		// set the option to selected that corresponds to what the cookie is set to
//		$('#curriculum option[value="'+$.cookie('remember_crclm')+'"]').prop('selected', true);
////                alert();
////                $('#curriculum').trigger('change');
//	}

    $('#filter_form').on('change', '#item', function () {
        $('#activity_reset').trigger('click'); // to reset the add activity form
        $.cookie('remember_term', $('#item option:selected').val(), {expires: 90, path: '/'});
        var $item_id = $(this).val();
        var $curriculum_id = $('#curriculum').val();
        var $program_id = $('#program').val();
        $('#item_id').val($item_id);
        if ($curriculum_id && $item_id) {
            var post_data = {
                program_id: $program_id,
                curriculum_id: $curriculum_id,
                item_id: $item_id
            };
           if ($.cookie('remember_term') !== 0 ) {
                                // set the option to selected that corresponds to what the cookie is set to
                                $('#item option[value="'+$.cookie('remember_term')+'"]').prop('selected', true);
                                generate_activity_table(post_data);
                        }
            
        }
    });
    var table;



   // generate_activity_table();

    $('#rubrics_modal').on('hidden.bs.modal', function () {
        $('#rubrics_modal').removeData('bs.modal');

        var $item_id = $('#item').val();
        var $curriculum_id = $('#curriculum').val();
        var $program_id = $('#program').val();
        var post_data = {
            program_id: $program_id,
            curriculum_id: $curriculum_id,
            item_id: $item_id
        };
        generate_activity_table(post_data);
        return;
    });

    $('#add_extra_curricular_activities_submit').on('click', function (e) {
        e.preventDefault();
        $('#filter_form').validate({
            rules: {
                'program': 'selectOption',
                'curriculum': 'selectOption',
                'item': 'selectOption'
            }
        });
        var filter = false;
        if ($('#filter_form').valid()) {
            filter = true;
        }
        save_update_activities(filter, 'save', 'save_activity');
    });

    function save_update_activities(filter, action, method) {

        $('#add_extra_curricular_activities_form').validate({
            rules: {
                'activity_name': 'required',
                'conduct_date': 'required',
            }
        });

        if ($('#add_extra_curricular_activities_form').valid()) {
            var post_data = {
                activity_name: $('#activity_name').val(),
                conduct_date: $('#conduct_date').val(),
                activity_desc: $('#activity_desc').val(),
                organised_addr: $('#organised_addr').val(),
                program_id: $('#program_id').val(),
                curriculum_id: $('#curriculum_id').val(),
                item_id: $('#item_id').val(),
                activity_id: $('#activity_id').val(),
                action: action
            }
            if (filter) {
                $.ajax({
                    type: "POST",
                    url: base_url + 'Extra_curricular_activities/Extra_curricular_activities/' + method,
                    data: post_data,
                    success: function (dataa) {
                        var data = JSON.parse(dataa);
                        if (data.key == 'Success') {
                            var $item_id = $('#item').val();
                            var $curriculum_id = $('#curriculum').val();
                            var $program_id = $('#program').val();
                            if ($curriculum_id && $item_id) {
                                var post_data = {
                                    program_id: $program_id,
                                    curriculum_id: $curriculum_id,
                                    item_id: $item_id
                                };
                                generate_activity_table(post_data);
                            }
                            $('#activity_reset').click();
                            var data_options = '{"text":"' + data.message + '","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                            show_noty(data_options);
                        }
                    }
                });
            }

        }
    }

    $(document).on('click', '.edit_po_activity', function (e) {
        e.preventDefault();
        var post_data = {
            activity_id: $(this).data('activity_id'),
            action: 'get'
        };

        $.ajax({
            type: "POST",
            url: base_url + 'Extra_curricular_activities/Extra_curricular_activities/update_activity',
            data: post_data,
            success: function (dataa) {
                var data = JSON.parse(dataa);

                $('#loading').hide();
                $('#add_extra_curricular_activities_submit').hide();
                $('#update_extra_curricular_activities_submit').show();
                $('#activity_id').val(data.po_extca_id);
                $('#activity_name').val(data.activity_name);
                $('#conduct_date').val(data.conducted_date);
                $('#activity_desc').val(data.activity_description);
                $('#organised_addr').val(data.organized_by_address);
            }
        });

    });

    $(document).on('click', '#update_extra_curricular_activities_submit', function () {
        save_update_activities(true, 'update', 'update_activity');
        $('#activity_reset').click();
        $('#update_extra_curricular_activities_submit').hide();
        $('#add_extra_curricular_activities_submit').show();
    });

    $(document).on('click', '.delete_po_activity', function (e) {
        e.preventDefault();
        var finalized = $(this).data('finalized');
        var activity_id = $(this).data('activity_id');
        
        $('#finalized').val(finalized);
        $('#activity_id').val(activity_id);
        var del_data = {
            activity_id: $(this).data('activity_id')
        };
        if (finalized == 'yes') {
             $('#delete_activity_msg').html('Rubrics is already finalized.<br>Press ok to delete all the Rubrics data.');
            $('#delete_activity_modal').modal('show');
        }else{
            $('#delete_activity_msg').html('Do you want to delete the Assessment method?<br>Press ok to delete all the Assessment data.');
            $('#delete_activity_modal').modal('show');
        }
    });
    
    $('#delete_activity_modal').on('click', '#delete_activity_confirm', function () {
       var finalized =   $('#finalized').val();
       var activity_id =  $('#activity_id').val();
        var del_data = {
            activity_id: activity_id,
        };
        //process for delet
        delete_po_activity(del_data);
       
    });

    
    
    function delete_po_activity(post_data) {
        $.ajax({
            type: "POST",
            url: base_url + 'Extra_curricular_activities/Extra_curricular_activities/delete_po_activity',
            data: post_data,
            success: function (dataa) {
                var data = JSON.parse(dataa);

                if (data.key == "Success") {
                    var $item_id = $('#item').val();
                    var $curriculum_id = $('#curriculum').val();
                    var $program_id = $('#program').val();
                    if ($curriculum_id && $item_id) {
                        var post_data = {
                            program_id: $program_id,
                            curriculum_id: $curriculum_id,
                            item_id: $item_id
                        };
                        generate_activity_table(post_data);
                    }
                    var data_options = '{"text":"' + data.message + '","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                    show_noty(data_options);
                }
            }
        });
    }

    /*DO NOT CHANGE THE CODE SEQUENCE*/
    var datas = new function () {
        this.activity_id = '';
        this.activity_name = '';
        this.program_name = '';
        this.range = '';
        this.rubrics_flag = 0;
    };

    /**Manage Rubrics**/
    $('#myTable').on('click', '.manage_rubrics', function (e) {
        e.preventDefault();
        var pgm_id = $('#program').val();
        var crclm_id = $('#curriculum').val();
        var term = $('#item').val();
        if (pgm_id != 0 && crclm_id != 0 && term != 0) {
            datas.program_name = $(this).data('program');
            datas.crclm_name = $(this).data('crclm_name');
            datas.term_name = $(this).data('term_name');
            datas.activity_id = $(this).data('activity_id');
            datas.activity_name = $(this).data('activity');
            datas.rubrics_flag = $(this).data('rubrics_flag');
            datas.crclm_id = $(this).data('crclm_id');
            datas.range = $(this).data('rubrics_range');
            //console.log('datas',datas);
            $('#display_pgm_name').html(datas.program_name);
            $('#display_crclm_name').html(datas.crclm_name);
            $('#display_term_name').html(datas.term_name);
            $('#display_activity_name').html(datas.activity_name);
            manage_rubrics(datas.rubrics_flag, datas.crclm_id);
            $('#rubrics_modal').modal({backdrop: 'static', keyboard: false});
        } else {
            var data_options = '{"text":"Please Select All Dropdowns before proceeding.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
            show_noty(data_options);
        }

    });


    /*GENERATE RUBRICS FIELDS IN MODAL*/
    $('#generate_rubrics').on('click', function () {
        $('#rubrics').validate({
            rules: {
                rubrics_count: {
                    required: true,
                    greterThanZero: true
                }
            }
        });
        var flag = $("#rubrics").valid();

        if (flag == true) {

            datas.range = $('#rubrics_count').val();

            if (datas.range != 0 || datas.range != "") {

                var post_data = {
                    'count_of_range': datas.range,
                    'activity_id': datas.activity_id,
                    'activity_name': datas.activity_name
                }
                //console.log('generate rubrics post_data',post_data);                

                $('#rubrics_data_form').html('');
                $('#save_rubrics_form').show();
                $('#save_rubrics').show();
                generate_criteria_fields_ajax(post_data);
            }
        } else {
            $('#regenerate_rb_btn').hide();
            $('#loading').hide();
        }
    });

    /*SAVE RUBRICS DATA*/
    $('#save_rubrics').on("click", function () {

        $("#save_rubrics_form").validate({
            ignore: []
        });

        $(".criteria_check").each(function () {
            $(this).rules("add", {
                required: true
            });
        });
        $("#criteria_1").rules("add", {
            required: true
        });
        $("#outcome").rules("add", {
            required: true
        });

        if ($('#save_rubrics_form').valid()) {
            $('#loading').show();
            $.ajax({
                type: "POST",
                url: base_url + 'Extra_curricular_activities/Extra_curricular_activities/save_rubrics',
                data: $("#save_rubrics_form").serialize(),
                success: function (data) {
                    $('#loading').hide();
                    if (parseInt(data)) {
                        $('#generate_rubrics').hide();
                        $('.rubrics_count_holder').hide();
                        $('#regenerate_rubrics').show();

                        var post_data = {
                            'activity_id': datas.activity_id, //$('#activity_id').val()
                        }
                        generate_rubrics_table_ajax(post_data, '#rubrics_data_holder')
                        var data_options = '{"text":"Rubrics successfully saved.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                        show_noty(data_options);

                        $('#rubrics_count').val(datas.range);
                        $("#generate_rubrics").trigger('click');
                        $('#outcome').multiselect('refresh');
                        $('#rubrics_note').show();
                    } else {
                        var data_options = '{"text":"Data Saving Error,Please reload the page and tray again.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
                        show_noty(data_options);
                    }

                }
            });
        }
    });

    $(document).on('click', '.rubric_edit', function (e) {
        e.preventDefault();

        var post_data = {
            'activity_id': datas.activity_id
        }
        var res = get_rubrics_status(post_data);
        datas.criteria_id = $(this).data('criteria_id');
        var post_data = {
            'count_of_range': datas.range,
            'activity_id': datas.activity_id,
            'criteria_id': datas.criteria_id
        }

        if (res) {
            $('#cancel_rubrics_finailize_modal').modal({backdrop: 'static', keyboard: false})
                    .one('click', '#cancel_rubrics_finailize_data', function () {
                        $('#rubrics_data_form').html('');
                        $('#save_rubrics_form').show();
                        $('#save_rubrics').hide();
                        $('#update_rubrics').show();
                        $('#rubrics_finalize').removeAttr('disabled');
                        $('#rubrics_note').show();
                        var status = {
                            'activity_id': datas.activity_id,
                            "status": 'no'
                        }
                        get_rubrics_status(status)
                        generate_criteria_fields_ajax(post_data);

                    });
        } else {
            $('#rubrics_data_form').html('');
            $('#save_rubrics_form').show();
            $('#save_rubrics').hide();
            $('#update_rubrics').show();
            generate_criteria_fields_ajax(post_data);
        }



    });
    $('#update_rubrics').on('click', function () {
        $('#loading').show();
        $("#save_rubrics_form").validate({
            ignore: []

        });

        $(".criteria_check").each(function () {
            $(this).rules("add", {
                required: true
            });
        });
        $("#criteria_1").rules("add", {
            required: true
        });
        $("#outcome").rules("add", {
            required: true
        });

        if ($('#save_rubrics_form').valid()) {
            $('#loading').show();
            $.ajax({
                type: "POST",
                url: base_url + 'Extra_curricular_activities/Extra_curricular_activities/update_rubrics',
                data: $("#save_rubrics_form").serialize(),
                success: function (data) {
                    if (parseInt(data)) {

                        var post_data = {
                            'activity_id': datas.activity_id, //$('#activity_id').val()
                        }
                        generate_rubrics_table_ajax(post_data, '#rubrics_data_holder')
                        var data_options = '{"text":"Rubrics successfully saved.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                        show_noty(data_options);
                        $('#rubrics_count').val(datas.range);
                        $("#generate_rubrics").trigger('click');
                        $('#outcome').multiselect('refresh');
                        $('#update_rubrics').hide();
                    } else {
                        var data_options = '{"text":"Data Saving Error,Please reload the page and tray again.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
                        show_noty(data_options);
                    }
                    $('#loading').hide();

                }
            });
        }
    });
    $(document).on('click', '.rubric_delete', function (e) {
        e.preventDefault();
        var criteria_id = $(this).data('criteria_id');
        $('#criteria_id').val(criteria_id);
        //$('#post_data1').val(post_data2);
        $('#modal_msg').empty();
        $('#modal_msg').html('Are you sure you want to delete this Criteria?');
        $('#delete_criteria_modal').modal('show');
    });
    
        $('#delete_criteria_modal').on('click', '#delete_criteria_data', function (e) {
                   e.preventDefault();
                    //delete function
                    var criteria_id = $('#criteria_id').val();
                    var post_data1 = {
                        'activity_id': datas.activity_id
                    }
                    var post_data2 = {
                        'activity_id': datas.activity_id,
                        'criteria_id': criteria_id
                    }
                    
                   if ($('#rubrics tr').length == 2) {
                      delete_rubrics(post_data1, true);
                      datas.rubrics_flag = 0;
                     manage_rubrics(datas.rubrics_flag);
                   } else {
                      delete_rubrics(post_data2);
                    }

                });
    

    $('#regenerate_rubrics').on('click', function () {
        $('#re_generate_modal_msg').empty();
        $('#re_generate_modal_msg').html('Are you sure you want to delete all rubrics data?');
        $('#regenerate_criteria_modal').modal('show');
        
    });
    
    $('#regenerate_criteria_modal').on('click', '#regenerate_delete_criteria_data', function () {
        var post_data = {
            'activity_id': datas.activity_id
        }
                    //delete function
                    delete_rubrics(post_data, true);
                    datas.rubrics_flag = 0;
                    manage_rubrics(datas.rubrics_flag);
                    $('#rubrics_data_form').empty();
                });
    $('#myTable').on('click', '.view_rubrics', function (e) {
        e.preventDefault();
            var program_name = $(this).data('program');
            var crclm_name = $(this).data('crclm_name');
            var term_name = $(this).data('term_name');
            var activity_name = $(this).data('activity');
            //console.log('datas',datas);
            $('#display_pgm_name_one').html(program_name);
            $('#display_crclm_name_one').html(crclm_name);
            $('#display_term_name_one').html(term_name);
            $('#display_activity_name_one').html(activity_name);
        var post_data = {
            'activity_id': $(this).data('activity_id'),
            'view': 1
        }
        $('#view_pgm_name').html($(this).data('program'));
        $('#view_activity_name').html($(this).data('activity'));
        generate_rubrics_table_ajax(post_data, '#rubrics_view_data_holder');
        $('#rubrics_view_modal').modal('show');

    });

    $(document).on('click', '#rubrics_finalize', function (e) {
        e.preventDefault();
        var post_data = {
            'activity_id': datas.activity_id,
            'status': 'yes'
        }

        $('#finailize_rubrics_modal').modal({backdrop: 'static', keyboard: false})
                .one('click', '#finailize_rubrics_data', function () {
                    $('#loading').show();
                    $.ajax({
                        type: "POST",
                        url: base_url + 'Extra_curricular_activities/Extra_curricular_activities/rubrics_status',
                        data: post_data,
                        datatype: "JSON",
                        success: function (data) {
                            $('#loading').hide();
                            if (data == 'success') {
                                $('#save_rubrics_form').hide();
                                $('#save_rubrics').hide();
                                $('#update_rubrics').hide();
                                $('#rubrics_finalize').attr('disabled', 'disabled');
                                var data_options = '{"text":"Rubrics finalized successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                                show_noty(data_options);
                            } else {
                                var data_options = '{"text":"There is an error,Please reload the page and tray again.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
                                show_noty(data_options);
                            }
                        }
                    });

                });
    });
    function get_rubrics_status(post_data) {
        $('#loading').show();
        var result = '';
        $.ajax({
            type: "POST",
            url: base_url + 'Extra_curricular_activities/Extra_curricular_activities/rubrics_status',
            data: post_data,
            datatype: "JSON",
            async: false,
            success: function (data) {
                $('#loading').hide();
                if (data == 'yes') {
                    result = 1;
                } else {
                    result = 0;
                }
            }
        });
        console.log('result', result);
        return result;
    }
    function manage_rubrics(rubrics_flag, crclm_id) {
        if (rubrics_flag) {
            //rubrics has already been finalized
            $('#regenerate_rubrics').show();
            $('#generate_rubrics').hide();
            $('.rubrics_count_holder').hide();
            $('#update_rubrics').hide();
            $('#rubrics_data_form').html('');
            $('#save_rubrics_form').show();
            var post_data = {
                'activity_id': datas.activity_id
            }
            generate_rubrics_table_ajax(post_data, '#rubrics_data_holder');

            var post_data = {
                'count_of_range': datas.range,
                'activity_id': datas.activity_id,
                'activity_name': datas.activity_name,
                'crclm_id': datas.crclm_id
            }
            generate_criteria_fields_ajax(post_data);
            var value = get_rubrics_status(post_data);
            console.log('value', value);
            if (value == 1) {
                $('#rubrics_finalize').prop('disabled', true);
                $('#generate_row').hide();
                $('#save_rubrics').hide();
                $('#rubrics_note').hide();
                $('#save_rubrics_form').hide();
            } else {
                $('#rubrics_note').show();
            }

        } else {
            //console.log('no rubrics');
            $('#rubrics_count').val('');
            $('.rubrics_count_holder').show();
            $('#generate_rubrics').show();

            $('#rubrics_data_holder').html('');
            $('#rubrics_data_holder').html('<b style="color:red;">Rubrics not defined.</b>');
            $('#regenerate_rubrics').hide();
            $('#save_rubrics_form').hide();
            $('#save_rubrics').hide();
            $('#update_rubrics').hide();
        }
    }
    function delete_rubrics(post_data, regenerate_rubrics_flag) {
        $('#loading').show();
        $.ajax({
            type: "POST",
            url: base_url + 'Extra_curricular_activities/Extra_curricular_activities/delete_rubrics',
            data: post_data,
            datatype: "JSON",
            success: function (data) {
                if (data == 'success') {
                    if (!regenerate_rubrics_flag) {
                        generate_rubrics_table_ajax(post_data, '#rubrics_data_holder')
                    }
                    var data_options = '{"text":"Data successfully deleted.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                    show_noty(data_options);
                    $('#save_rubrics_form').show();
                    $('#save_rubrics').show();
                } else {
                    var data_options = '{"text":"Data deleteing Error,Please reload the page and tray again.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
                    show_noty(data_options);
                }
                $('#loading').hide();
            }
        });
    }
    function generate_criteria_fields_ajax(post_data) {
        $('#loading').show();
        $.ajax({
            type: "POST",
            url: base_url + 'Extra_curricular_activities/Extra_curricular_activities/design_criteria_section',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                $('#rubrics_data_form').append(msg);
                //$('#save_rubrics_form').append(msg);
                $('#loading').hide();
                show_multi_select_box();
            }
        });
    }

    function generate_rubrics_table_ajax(post_data, element) {
        $('#loading').show();
        $.ajax({
            type: "POST",
            url: base_url + 'Extra_curricular_activities/Extra_curricular_activities/fetch_rubrics',
            data: post_data,
            datatype: "JSON",
            success: function (data) {
                $(element).show();
                $(element).html(data);
                $('#loading').hide();
                $('#export_rubrics_form_activity_id').val(post_data.activity_id);
            }
        });
    }
    function show_multi_select_box() {
        $('#outcome').multiselect({
            nonSelectedText: 'Select Outcome',
            buttonContainer: '<div class=\"btn-group width100\">',
            buttonClass: 'multiselect dropdown-toggle arrow multi_select_decoration width100 multi_outcome_box',
            //enableFiltering: true,
            maxHeight: 70,
        });
    }
    function show_noty($option) {
        if ($option) {
            $option = $.parseJSON($option);
            noty($option);
        }
    }
    
});//end jquery


