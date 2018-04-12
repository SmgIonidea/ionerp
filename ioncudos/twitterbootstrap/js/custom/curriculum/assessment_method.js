var base_url = $('#get_base_url').val();
var controller = base_url + 'curriculum/assessment_method/';
var post_data = ' ';
$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\./]+([-\a-zA-Z0-9\./])*$/i.test(value);
}, "Field must contain only letters, numbers, dot or dashes.");
validate();
function validate() {
    var pgm_id = $('#pgmid').val();
    if (pgm_id != 0) {
        $(".ao_method_add_form_submit").attr("disabled", false);
        $(".clear_all").attr("disabled", false);
    } else {
        $(".ao_method_add_form_submit").attr("disabled", true);
        $(".clear_all").attr("disabled", true);
    }

}
$('#rubrics_count').val('');
function select_pgm_list() {
    $('#loading').show();

    $.cookie('remember_pgm', $('#pgmid option:selected').val(), {expires: 90, path: '/'});
    //Function to display list in the List View of Assessment Method for a Program selected.

    $('.add_ao_mtd').prop('disabled', false);
    $('#pgm_id_h').val($('#pgmid').val());
    var pgm_id = $('#pgmid').val();
    if (pgm_id != 0) {
        $(".ao_method_add_form_submit").attr("disabled", false);
        $(".clear_all").attr("disabled", false);
        $('#loading').hide();
    } else {
        $(".ao_method_add_form_submit").attr("disabled", true);
        $(".clear_all").attr("disabled", true);
        $('#loading').hide();
    }

    grid();
}

/* Function to fetch the assessment methods for the selected program
 * @param - program id
 * @returns - List of assessment method along with descriptions in List View Page.
 */
function grid() {
    var pgm_id = $('#pgmid').val();
    var post_data = {
        'program_id': pgm_id,
    }
    //var data= load_grid(post_data);
    $.ajax({type: "POST",
        url: base_url + 'curriculum/assessment_method/assessment_method_list',
        data: post_data,
        dataType: 'json',
        success: [populate_table, reset_achive]
    });


}

function reset_achive() {
    $("#ao_method_add_form").trigger('reset');
}
function populate_table(msg) {

    /*  $('#example').on( 'click', 'tbody td:not(:first-child)', function (e) {
     editor.inline( msg );
     } ); */
    $('#example').dataTable().fnDestroy();
    $('#example').dataTable({
        "sPaginationType": "full_numbers",
        "iDisplayLength": 20,
        "aoColumns": [
            {"sTitle": "Sl No.", "mData": "Sl_No", "sClass": "alignRight"},
            {"sTitle": "Assessment Method", "mData": "ao_method_name"},
            {"sTitle": "Description", "mData": "ao_method_description"},
            {"sTitle": "Manage Rubrics", "mData": "ao_method_rubrics"},
            {"sTitle": "View Rubrics", "mData": "view_rubrics"},
            {"sTitle": "Edit", "mData": "ao_method_edit"},
            {"sTitle": "Delete", "mData": "ao_method_delete"}
        ], "aaData": msg,
        "sPaginationType": "bootstrap"
    });

}

$('#example').on('click', '.assessment_edit', function (e) {
    $('#ao_method_id_edit').val($(this).attr('data-id'));
    $('#ao_method_name_edit').val($(this).attr('data-ao_name'));
    $('#ao_method_description_edit').val($(this).attr('data-ao_descrip'));
    $('#myModalEdit').modal('show');
});



$('#update_assessment').on('click', function () {
    $('#loading').show();
    var flag = $("#ao_edit").valid();
    $('#ao_method_name_edit').each(function () {
        $(this).rules("add",
                {
                    required: true,
                    //loginRegex : true
                });
    });
    var flag = $("#ao_edit").valid();
    var ao_method_id = $('#ao_method_id_edit').val();
    var ao_method_name = $('#ao_method_name_edit').val();
    var ao_method_description = $('#ao_method_description_edit').val();
    var ao_method_pgm_id = $('#pgmid').val();
    var post_data = {
        'ao_method_pgm_id': ao_method_pgm_id,
        'ao_method_id': ao_method_id,
        'ao_method_name': ao_method_name,
        'ao_method_description': ao_method_description
    }
    if (flag == true) {
        $.ajax({type: "POST",
            url: base_url + 'curriculum/assessment_method/assessment_method_update_record',
            data: post_data,
            //  dataType: 'json',
            success: function (msg) {
                if (msg == 1) {
                    success_update_modal(msg);
                } else {
                    fail_update_modal(msg);
                }
                grid();
                $('#loading').hide();
            }
        });
        $('#myModalEdit').modal('hide');
        $('#loading').hide();
    } else {
        $('#loading').hide();
    }
});

$(".ao_method_add_form_submit").on('click', function () {
    $('#loading').show();
    // Form validation rules are defined & checked before form is submitted to controller.		
    var flag = $("#ao_method_add_form").valid();
    $('#assessment_method_name').each(function () {
        $(this).rules("add",
                {
                    required: true,
                    //loginRegex : true
                });
    });
    var pgmid = $('#pgmid').val();
    var ao_method_name = $('#assessment_method_name').val();
    var ao_method_description = $('#ao_method_description').val();
    var post_data = {
        'pgm_id': pgmid,
        'ao_method_name': ao_method_name,
        'ao_method_description': ao_method_description
    }
    if (pgmid != 0 && flag == true) {
        $.ajax({type: "POST",
            url: base_url + 'curriculum/assessment_method/assessment_method_insert_record',
            data: post_data,
            //  dataType: 'json',
            success: function (msg) {
                if (msg == 1) {
                    success_modal(msg);
                } else {
                    fail_modal(msg);
                }
                grid();
                $('#loading').hide();
            }
        });
    } else {
        $('#loading').hide();
        $('#pgmid').focus();
    }
});


$('.get_id').live('click', function (e) {
    ao_method_id = $(this).attr('id');
    $('#ao_method_id').val(ao_method_id);
    //  table_row = $(this).closest("tr").get(0);
});
//Function to delete an existing assessment from the list 
$('.delete_ao_method').click(function (e) {
    $('#loading').show();
    e.preventDefault();
    var ao_method_id = $('#ao_method_id').val();
    var post_data = {
        'ao_method_id': ao_method_id,
    }
    $.ajax({type: "POST",
        url: base_url + 'curriculum/assessment_method/assessment_method_delete',
        data: post_data,
        datatype: "JSON",
        success: function (msg)
        {

            if (msg == -1) {
                $('#cant_delete').modal('show');
            }
            grid();
            /*  var oTable = $('#example').dataTable();
             oTable.fnDeleteRow(oTable.fnGetPosition(table_row)); */
            $('#loading').hide();
        }
    });
});

$('#example').on('click', '.manage_rubrics', function (e) {
    $('#rubrics_count').val('');
    $('#update_rubrics').hide();
    ao_method_id = $(this).attr('id');
    $('#ao_method_id').val(ao_method_id);
    $('#check_main').html('');
    load_criteria_block();
    load_rubrics_grid();
    fetch_ao_name();
});

$('#example').on('click', '.display_rubrics_view', function (e) {

    ao_method_id = $(this).attr('id');
    $('#ao_method_id').val(ao_method_id);
    load_rubrics_grid_view(ao_method_id);

});
function load_rubrics_grid_view(ao_method_id) {
    $('#loading').show();
    var post_data = {'ao_id': ao_method_id}
    $.ajax({
        type: "POST",
        url: base_url + 'curriculum/assessment_method/display_rubrics_modal',
        data: post_data,
        success: function (msg) {
            $('#display_rubrics_data').html(msg);
            fetch_ao_name();
            $('#display_rubrics_data_modal').modal('show');
            $('#loading').hide();
        }
    });
}
$.validator.addMethod("greterThanZero", function (value, element) {
    var regex = /^[1-9]{1,1}$/; //this is for numeric... you can do any regular expression you like...
    return this.optional(element) || regex.test(value);
}, "Invalid Input");
//Fucntion to display a section for defining the Rubrics data
$('#generate_rubrics').click(function () {
    $('#loading').show();
    var flag = $("#rubrics").valid();
    $('#rubrics_count').each(function () {
        $(this).rules("add",
                {
                    required: true,
                    greterThanZero: true
                });
    });
    var flag = $("#rubrics").valid();

    var count_of_range = $('#rubrics_count').val();
    var ao_method_id = $('#ao_method_id').val();
    $('#is_define_rubrics').val(1);
    $('#check_main').show();
    $('#check_main').html("");
    var post_data = {
        'count_of_range': count_of_range,
        'ao_method_id': ao_method_id
    }
    if ((count_of_range != 0 || count_of_range != "") && flag == true) {
        $.ajax({type: "POST",
            url: base_url + 'curriculum/assessment_method/design_criteria_section',
            data: post_data,
            datatype: "JSON",
            success: function (msg)
            {
                $('#define_rubrics').hide();
                $('#regenerate_rb_btn').hide();
                $('#check_main').append(msg);
                $('#loading').hide();
                $('#save_rubrics').show();
            }
        });
    } else {
        $('#regenerate_rb_btn').hide();
        $('#loading').hide();
    }
});

function fetch_ao_name() {
    var ao_id = $('#ao_method_id').val();
    var pgm_id = $('#pgmid').val();
    var pgm_name = $("#pgmid :selected").text();

    var post_data = {
        'pgm_id': pgm_id,
        'ao_method_id': ao_id
    }
    $.ajax({type: "POST",
        url: base_url + 'curriculum/assessment_method/fetch_ao_method_name',
        data: post_data,
        //datatype: "JSON",
        success: function (msg) {
            $('#display_ao_method_name').html(msg);
            $('#display_pgm_name').html(pgm_name);
            $('#display_ao_method_name_view').html(msg);
            $('#display_pgm_name_view').html(pgm_name);
        }
    });
}

$('#save_rubrics').live("click", function () {
    (document).getElementById('loading_data').style.visibility = 'visible';
    var flag = $('#save_rubrics_data').valid();
    $(".criteria_check").validate({
        errorPlacement: function (error, element) {
            error.appendTo(element.parents(".add_more_1"));
        }
    });
    var flag = $('#save_rubrics_data').valid();

    $('.criteria_check').each(function () {
        $(this).rules("add",
                {
                    required: false,
                });
    });


    var pgmid = $('#pgmid').val();
    var rubrics_count = $('#rubrics_count').val();
    var is_define_rubrics = $('#is_define_rubrics').val();
    var criteria = $('#criteria_1').val();
    var ao_method_id = $('#ao_method_id').val();
    var criteria_desc = $("textarea[name='criteria_desc[]']").map(function () { return $(this).val(); }).get();
    var criteria_range = $("input[name='range[]']").map(function () { return $(this).val(); }).get();
    var criteria_range_name = $("input[name='range_name[]']").map(function () { return $(this).val(); }).get();




    if (flag == true) {
        var post_data = {
            'pgmid': pgmid,
            'rubrics_count': rubrics_count,
            'is_define_rubrics': is_define_rubrics,
            'criteria': criteria,
            'criteria_desc': criteria_desc,
            'ao_method_id': ao_method_id,
            'criteria_range': criteria_range,
            'criteria_range_name': criteria_range_name,
        }
        var flag_check = 0;
        $("textarea[name='criteria_desc[]']").each(function (nr) {
            if ($(this).val() === "") {
                flag_check = 1;
            }
        });

        if (flag_check == 0) {
            $.ajax({type: "POST",
                url: base_url + 'curriculum/assessment_method/assessment_method_insert_record_rubrics',
                data: post_data,
                success: function (msg) {
                    if (msg == 1) {
                        success_update_modal(msg);
                    }
                    $('#check_main').html('');
                    load_rubrics_grid();
                    load_criteria_block();
                }
            });
        } else {
            required_fields('msg');
        }
        (document).getElementById('loading_data').style.visibility = 'hidden';

    } else {
        required_fields('msg');
        (document).getElementById('loading_data').style.visibility = 'hidden';
    }

});


function code_to_run(rubrics_criteria_id, ao_method_id) {
    $('#ao_method_id_criteria').val(ao_method_id);
    $('#criteria_id').val(rubrics_criteria_id);
    $('#myModaldelete_criteria').modal('show');


}
function load_rubrics_grid() {
    var post_data = {'ao_id': ao_method_id}
    $.ajax({
        type: "POST",
        url: base_url + 'curriculum/assessment_method/display_rubrics',
        data: post_data,
        success: function (msg) {
            $('#rubrics_data').html(msg);
        }
    });
}


$('.delete_ao_method_criteria').on('click', function () {
    var ao_method_id = $('#ao_method_id_criteria').val();
    var rubrics_criteria_id = $('#criteria_id').val();
    var post_data = {'rubrics_criteria_id': rubrics_criteria_id, 'ao_method_id': ao_method_id}
    $.ajax({type: "POST",
        url: base_url + 'curriculum/assessment_method/delete_criteria',
        data: post_data,
        //datatype: "JSON",
        success: function (data) {
            load_rubrics_grid();

            if (data == 1) {
                success_modal_delete(data);
            }
            load_criteria_block();
        }
    });
});
/* function load_grid(post_data) {
 
 $("#jsGrid").jsGrid({
 width : "100%",
 height : "400px",
 filtering : true,
 inserting : true,
 editing : true,
 dataType : 'json',
 sorting : true,
 paging : true,
 autoload : true,
 deleteButton : true,
 pageSize : 30,
 pageButtonCount : 5,
 insertRowRenderer : null,
 pageSize: 15,
 pageButtonCount: 5,
 
 deleteConfirm: "Are you sure?",
 
 onDataLoading : function () {},
 
 controller: {
 loadData: function() {
 //  return [{"Assessment Method Name":"ABC","Description":"XYZ","Rubrics":"Rubrics"}];
 
 return  $.ajax({type: "POST",
 url: base_url + 'curriculum/assessment_method/assessment_method_list',
 data: post_data,
 //success:function(msg){ mas['ao_list'];}
 });  
 
 }
 },
 
 fields: [
 { name: "ao_method_name", type: "text", width: 100 },
 { name: "ao_method_description", type: "text", width: 100 },
 { name: "ao_method_rubrics", type: "anchor", width: 100 },
 // { name: "Edit", type: "text", width: 100 },
 // { name: "Delete", type: "text", width: 100 },  
 { type: "control" }
 
 /* 		  { name: "Assessment Method Name", type: "text", width: 100 },
 { name: "Description", type: "text", width: 100 },
 { name: "Rubrics", type: "text", width: 100 },
 { type: "control" } */
//   ]


//});
//} */ //end of load grid

function edit_assessment_data(rubrics_criteria_id, ao_method_id, criteria_description_id) {
    var post_data = {'rubrics_criteria_id': rubrics_criteria_id, 'ao_method_id': ao_method_id, 'criteria_description_id': criteria_description_id}
    //	console.log("");
    $('#check_main').html('');
    $.ajax({type: "POST",
        url: base_url + 'curriculum/assessment_method/edit_assessment_data',
        data: post_data,
        //datatype: "JSON",
        success: function (msg) {
            //	console.log("");
            $('#save_rubrics').hide();
            $('#update_rubrics').show();
            $('#check_main').append(msg);
        }});
}

$('#reset').on('click', function () {
    $('#save_rubrics').show();
    $('#update_rubrics').hide();
    load_criteria_block();
});

function load_criteria_block() {
    $('#check_main').html('');
//		console.log("");
    $('#loading').show();
    var ao_method_id = $('#ao_method_id').val();
    var post_data = {'ao_id': ao_method_id}
    $.ajax({
        type: "POST",
        url: base_url + 'curriculum/assessment_method/count_criteria',
        data: post_data,
        success: function (msg) {
            if (msg != 0) {
                $('#rubrics_count').val(msg);
                var post_data = {'count_of_range': msg, 'ao_method_id': ao_method_id}
                $.ajax({type: "POST",
                    url: base_url + 'curriculum/assessment_method/design_criteria_section',
                    data: post_data,
                    datatype: "JSON",
                    success: function (msg) {
                        $('#generate_rb_btn').hide();
                        $('#regenerate_rb_btn').show();
                        $('#check_main').append(msg);
                        $('#add_rubrics').modal('show');
                        $('#save_rubrics').show();
                    }

                });
                $('#loading').hide();
            } else {
                $('#generate_rb_btn').show();
                $('#loading').hide();
                $('#save_rubrics').hide();
                $('#add_rubrics').modal('show');
                $('#regenerate_rb_btn').hide();
            }

        }
    });
}

$('#update_rubrics').on('click', function () {
    (document).getElementById('loading_data').style.visibility = 'visible';
    var flag = $('#save_rubrics_data').valid();
    $(".criteria_check").validate({
        errorPlacement: function (error, element) {
            error.appendTo(element.parents(".add_more_1"));
        }
    });
    var flag = $('#save_rubrics_data').valid();


    $('#c_stmt_1').each(function () {
        $(this).rules("add",
                {
                    required: true,
                });
    });
    var pgmid = $('#pgmid').val();
    var rubrics_count = $('#rubrics_count').val();
    var is_define_rubrics = $('#is_define_rubrics').val();
    var criteria = $('#criteria_1').val();
    var criteria_id = $('#criteria_edit').val();
    var ao_method_id = $('#ao_method_id').val();
    var criteria_desc = $("textarea[name='criteria_desc[]']")
            .map(function () {
                return $(this).val();
            }).get();
    var criteria_desc_id = $("input[name='criteria_desc_edit[]']")
            .map(function () {
                return $(this).val();
            }).get();


    if (flag == true) {
        var post_data = {
            'pgmid': pgmid,
            'rubrics_count': rubrics_count,
            'is_define_rubrics': is_define_rubrics,
            'criteria': criteria,
            'criteria_desc': criteria_desc,
            'ao_method_id': ao_method_id,
            'criteria_id': criteria_id,
            'criteria_desc_id': criteria_desc_id
        }

        var flag_check = 0;
        $("textarea[name='criteria_desc[]']").each(function (nr) {
            if ($(this).val() === "") {
                flag_check = 1;
            }
        });
        if (flag_check == 0) {
            $.ajax({type: "POST",
                url: base_url + 'curriculum/assessment_method/assessment_method_update_record_rubrics',
                data: post_data,
                success: function (msg) {
                    if (msg == 1) {
                        success_update_modal(msg);
                    }
                    $('#save_rubrics').show();
                    $('#update_rubrics').hide();
                    load_rubrics_grid();
                    load_criteria_block();



                }
            });
        } else {
            required_fields('msg');
        }
        (document).getElementById('loading_data').style.visibility = 'hidden';
    }
    (document).getElementById('loading_data').style.visibility = 'hidden';
});

$('#regenerate_rb_btn').on('click', function () {
    $('#regerate_rubrics_modal').modal('show');
});

$('#re_generate_table').on('click', function () {
    $('#loading').show();
    var ao_method_id = $('#ao_method_id').val();
    var post_data = {'ao_method_id': ao_method_id}
    $.ajax({type: "POST",
        url: base_url + 'curriculum/assessment_method/regenerate_rubrics',
        data: post_data,
        success: function (msg) {
            if (msg == 1) {
                $('#regerate_rubrics_modal').modal('hide');
                success_modal_delete(msg);
            }
            $('#loading').hide();
            load_rubrics_grid();
            load_criteria_block();
            $('#save_rubrics').hide();
            $('#update_rubrics').hide();
        }});
});
$('.noty').click(function () {
    var options = $.parseJSON($(this).attr('data-noty-options'));
    noty(options);
});


function success_modal(msg) {//$('#myModal_suc').modal('show'); 
    var data_options = '{"text":"Your data has been saved successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);

}
function success_modal_delete(msg) {//$('#myModal_suc').modal('show'); 
    var data_options = '{"text":"Your data has been deleted successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);

}
function success_update_modal(msg) {//$('#myModal_suc').modal('show'); 
    var data_options = '{"text":"Your data has been updated successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);

}
function fail_modal(msg) {//$('#myModal_fail').modal('show');				
    $('#loading').hide();
    var data_options = '{"text":"This Assessment Method already exist.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}
function fail_update_modal(msg) {//$('#myModal_fail').modal('show');				
    $('#loading').hide();
    var data_options = '{"text":"This Assessment Method already exist.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}

function required_fields(msg) {//$('#myModal_fail').modal('show');				
    $('#loading').hide();
    var data_options = '{"text":"All Fields must be filled before proceeding..","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}

