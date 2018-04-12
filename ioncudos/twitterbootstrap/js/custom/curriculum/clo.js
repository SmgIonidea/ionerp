// Course Learning Objectives (CLOs)....
var clo_id;
var table_row;
var cloneCntr = 2;
var base_url = $('#get_base_url').val();
var clo_counter = new Array();
clo_counter.push(1);

//Course Learning Objectives (CLOs) List Page

$('#publish').on('click', function () {
    var curriculum_id = document.getElementById('curriculum').value;
    var term_id = document.getElementById('term').value;
    var course_id = document.getElementById('course').value;
    var post_data = {
        'crclm_id': curriculum_id,
        'crs_id': course
    }
    $.ajax({type: "GET",
        url: base_url + 'curriculum/clo/fetch_course_owner' + '/' + curriculum_id + '/' + course_id,
        data: post_data,
        dataType: "JSON",
        processData: false,
        success: function (result) {
            $('#course_owner_name').html(result.course_owner_name);
            $('#crclm_name').html(result.crclm_name);
            $('#term_name').html(result.term_name);
            $('#crs_name').html(result.crs_name);
            $('#crs_code').html(result.crs_code);
        }
    });
    $('#myModal_publish').modal('show');
});

//Function to publish the course learning objective statements
function publish() {
    var page = $('#page_diff').val();
    if (page == 0) {
        var curriculum_id = document.getElementById('curriculum').value;
        var term_id = document.getElementById('term').value;
        var course_id = document.getElementById('course').value;
    } else if (page == 1) {
        var curriculum_id = document.getElementById('crclm_id').value;
        var term_id = document.getElementById('term_id').value;
        var course_id = document.getElementById('crs_id').value;
    }

    var post_data = {
        'curriculum_id': curriculum_id,
        'course_id': course_id,
        'term_id': term_id,
    }

    $.ajax({type: "POST",
        url: base_url + 'curriculum/clo/publish_details',
        data: post_data,
        success: function (msg) {
            $(location).attr('href', base_url + 'curriculum/clo_po_map/map_po_clo/' + curriculum_id + '/' + term_id + '/' + course_id);
        }
    });
}

$('.get_clo_id').live("click", function () {
    clo_id = $(this).attr('id');
    table_row = $(this).closest("tr").get(0);
});

//Function to delete course learning objective
function delete_clo() {
    $.ajax({type: "POST",
        url: base_url + 'curriculum/clo/delete_clo' + "/" + clo_id,
        success: function (msg) {
            if (msg == -1) {
                $('#co_data_import').modal('show');
            } else {
                var oTable = $('#example').dataTable();
                oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
            }
        }
    });
}
//Function to delete course learning objective
function delete_clo1() {
    $.ajax({type: "POST",
        url: base_url + 'curriculum/clo/delete_clo' + "/" + clo_id,
        success: function (msg) {
            if (msg == -1) {
                $('#co_data_import').modal('show');
            } else {
                var oTable = $('#example_add').dataTable();
                oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
            }

        }
    });
}
$('.delbutton').click(function (e) {
    e.preventDefault();
    var oTable = $('#example').dataTable();
    var row = $(this).closest("tr").get(0);
    oTable.fnDeleteRow(oTable.fnGetPosition(row));
});

//Function to fetch term details
if ($.cookie('remember_term') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#curriculum option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
    select_term();
}

//function to fetch terms
function select_term() {
    $.cookie('remember_term', $('#curriculum option:selected').val(), {expires: 90, path: '/'});

    var curriculum_id = $('#curriculum').val();
    var post_data = {
        'curriculum_id': curriculum_id
    }

    $.ajax({type: "POST",
        url: base_url + 'curriculum/clo/select_term',
        data: post_data,
        success: function (msg) {
            $('#term').html(msg);
            if ($.cookie('remember_course') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#term option[value="' + $.cookie('remember_course') + '"]').prop('selected', true);
                select_course();
            }
        }
    });
}

//Function to fetch course details
function select_course() {
    $.cookie('remember_course', $('#term option:selected').val(), {expires: 90, path: '/'});
    $('#co_table_body_id').empty();
    $('#example_info').empty();
    var curriculum_id = $('#curriculum').val();
    var term_id = $('#term').val();

    var post_data = {
        'curriculum_id': curriculum_id,
        'term_id': term_id
    }
    $.ajax({type: "POST",
        url: base_url + 'curriculum/clo/select_course',
        data: post_data,
        success: function (msg) {
            $('#course').html(msg);
            if ($.cookie('remember_selected_value') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#course option[value="' + $.cookie('remember_selected_value') + '"]').prop('selected', true);
                get_selected_value();
            }
        }
    });
}

//Function to fetch the grid details
function get_selected_value() {
    $.cookie('remember_selected_value', $('#course option:selected').val(), {expires: 90, path: '/'});
    var curriculum_id = $('#curriculum').val();
    var term_id = $('#term').val();
    var course_id = $('#course').val();

    if (curriculum_id != 0 && term_id != 0 && course_id != 0) {
        $('#add_clo').attr("disabled", false);
        $('#add_clo_clone').attr("disabled", false);
        $(".co_force_edit").hide();
    } else {
        $('#add_clo').attr("disabled", true);
        $('#add_clo_clone').attr("disabled", true);
        $(".co_force_edit").hide();
    }

    var post_data = {
        'curriculum_id': curriculum_id,
        'term_id': term_id,
        'course_id': course_id,
    }

    $.ajax({type: "POST",
        url: base_url + 'curriculum/clo/show_clo',
        data: post_data,
        dataType: 'json',
        success: populate_table
    });
}

// function to populate static page data.
function static_get_selected_value() {
    $.cookie('remember_selected_value', $('#course option:selected').val(), {expires: 90, path: '/'});
    var curriculum_id = $('#curriculum').val();
    var term_id = $('#term').val();
    var course_id = $('#course').val();
    var post_data = {
        'curriculum_id': curriculum_id,
        'term_id': term_id,
        'course_id': course_id,
    }

    $.ajax({type: "POST",
        url: base_url + 'curriculum/clo/static_show_clo',
        data: post_data,
        dataType: 'json',
        success: static_populate_table
    });
}

//function to populate table
function static_populate_table(msg) {
    var m = 'd';
    $('#example').dataTable().fnDestroy();
    $('#example').dataTable(
            {"aoColumns": [
                    {"sTitle": "Course Outcome(CO)", "mData": "clo_statement"},
                ], "aaData": msg});
}

//Function to generate data table grid
function populate_table(msg) {
    $('#clo_import_data').val(msg["clo_import_manage"]);
    var m = 'd';
    var curriculum_id = $('#curriculum').val();
    var term_id = $('#term').val();
    var course_id = $('#course').val(); 
if(msg["clo_bl_flag"] == 0){ var data = {"sTitle": "Bloom's Level", "mData": "bloom_level" ,  "sClass": "center", "bSortable": false, "bVisible": false}; }else{
	var data = {"sTitle": "Bloom's Level", "mData": "bloom_level" ,  "sClass": "center", "bSortable": false, "bVisible": true};
}

    $('#example').dataTable().fnDestroy();
    $('#example').dataTable(
            {
                "aaSorting": [],
                "aoColumns": [
                    {"sTitle": "CO Code", "mData": "clo_code"},
                    {"sTitle": "Course Outcome(CO)", "mData": "clo_statement"},
                    //{"sTitle": "Bloom's Level", "mData": "bloom_level"},
					data ,
					//{"sTitle": "Bloom's Level", "mData": "bloom_level" ,  "sClass": "center", "bSortable": false, "bVisible": false},
                    {"sTitle": "Delivery Methods", "mData": "delivery_method"},
                    {"sTitle": "Edit", "mData": "clo_edit"},
                    {"sTitle": "Delete", "mData": "clo_remove"}
                ], "aaData": msg["clo_data"],
                "sPaginationType": "bootstrap"});

    if (msg["course_state"]["state_id"] == 1 || msg["course_state"]["state_id"] == 3 || msg["course_state"]["state_id"] == 6) {

        if (msg["clo_data"][0]["clo_edit"] == "") {
            $('#publish').attr("disabled", true);
            $('#add_clo').attr("disabled", false);
            $('#add_clo_clone').attr("disabled", false);
            $(".co_force_edit").hide();
        } else {
            $('#publish').attr("disabled", false);
            $('#add_clo').attr("disabled", false);
            $('#add_clo_clone').attr("disabled", false);
            $(".co_force_edit").hide();
        }

    } else {
        $('#publish').attr("disabled", true);
        $('#add_clo').attr("disabled", true);
        $('#add_clo_clone').attr("disabled", true);
        $(".co_force_edit").show();
    }

}

//Function to populate table.
function populate_table_add(msg) {
    $('#clo_import_data').val(msg["clo_import_manage"]);
	
	
	    var course_id = $('#course').val();
if(msg["clo_bl_flag"] == 0){ var data = {"sTitle": "Bloom's Level", "mData": "bloom_level" ,  "sClass": "center", "bSortable": false, "bVisible": false}; }else{
	var data = {"sTitle": "Bloom's Level", "mData": "bloom_level" ,  "sClass": "center", "bSortable": false, "bVisible": true};
}
//Function to generate data table grid
    $('#example_add,#example').dataTable().fnDestroy();
   var table = $('#example_add,#example').dataTable(
            {
                "aaSorting": [],
                "aoColumns": [
                    {"sTitle": "CO Code", "mData": "clo_code"} ,
                    {"sTitle": "Course Outcome(CO)", "mData": "clo_statement"},
                  //{"sTitle": "Bloom's Level", "mData": "bloom_level"}, {"sTitle": "Commencement Date","mData":"year","sClass": "alignright"},
					data ,
                    {"sTitle": "Delivery Methods", "mData": "delivery_method"},
                    {"sTitle": "Edit", "mData": "clo_edit"},
                    {"sTitle": "Delete", "mData": "clo_remove"}
                ], "aaData": msg["clo_data"],
                "sPaginationType": "bootstrap"});
			
        // Toggle the visibility	
}



// Function to EDIT the Freezed CLO.
$('#example').on('click', '.force_edit', function () {

    var id_val = $(this).attr('id');
    var clo_id = $(this).attr('data-clo_id');
    var course_id = $(this).attr('data-course_id');
    $('#clo_data').val(id_val);
    $('#clo_id1').val(clo_id);
    $('#course_id').val(course_id);
		var term = $('#term').val();
	var crclm_id =  $('#curriculum').val();
	
	var course_id = $('#course_id').val();
    var post_data = {
        'clo_id': clo_id,
		'term_id' :  term , 
		'crclm_id': crclm_id,
        'course_id': course_id}
		
	$.ajax({
        type: "POST",
        url: base_url + 'curriculum/clo/edit_clo_check_co_map',
        data: post_data,
        success: function (data) {
           if(data == 0){ $('#my_force_edit_modal').modal('show');	} else{$('#my_cont_edit_modal').modal('show');}
        }
    });  
});

// on Modal ok button this function get called
function force_edit()
{
    var clo_value = $('#clo_data').val();
    var clo_id = $('#clo_id1').val(); 
	var term = $('#term').val();
	var crclm_id =  $('#curriculum').val();

    var course_id = $('#course_id').val();
    var post_data = {
        'clo_id': clo_id,
		'term_id' :  term , 
		'crclm_id': crclm_id,
        'course_id': course_id}

		
    $.ajax({
        type: "POST",
        url: base_url + 'curriculum/clo/edit_clo_new',
        data: post_data,
        success: function (data) {
            $('#edit_Co').html(data);
            $('#myModal_edit_clo').modal('show');
        }
    }); 

}

//function to reset dropdowns.
function reset_achive() {
    $("#clo_form").trigger('reset');
    $("#bloom_level_1").multiselect("clearSelection");
    $("#bloom_level_2").multiselect("clearSelection");
    $("#bloom_level_3").multiselect("clearSelection");
    $('#bloom_level_1_actionverbs').html('');
    $("#delivery_method_1").multiselect("clearSelection");
    $("#bloom_level_1").multiselect('refresh');
    $("#bloom_level_2").multiselect('refresh');
    $("#bloom_level_3").multiselect('refresh');
    $("#delivery_method_1").multiselect('refresh');
}

// Function to DELETE the Freezed CLO.
$('#example').on('click', '.force_delete', function () {
    var id_val = $(this).attr('id');
    $('#clo_del_data').val(id_val);
    $('#my_force_delete_modal').modal('show');
});

//function to show edit modal
$('#example_add,#example').on('click', '.edit_clo', function () {
    var clo_id = $(this).attr('data-clo_id'); 
    var course_id = $(this).attr('data-course_id');
/*     var post_data = {
        'clo_id': clo_id,
        'course_id': course_id} */
		
	var term = $('#term').val();
	var crclm_id =  $('#curriculum').val();
		    var post_data = {
        'clo_id': clo_id,
		'term_id' :  term , 
		'crclm_id': crclm_id,
        'course_id': course_id}
		
	$.ajax({
        type: "POST",
        url: base_url + 'curriculum/clo/edit_clo_check_co_map',
        data: post_data,
        success: function (data) {
           if(data == 0){ 
				    $.ajax({
						type: "POST",
						url: base_url + 'curriculum/clo/edit_clo_new',
						data: post_data,
						success: function (data) {
							$('#edit_Co').html(data);
							$('#myModal_edit_clo').modal('show');
						}
					});
		   } else{$('#my_cont_edit_modal').modal('show');}
        }
    }); 
});

$('[data-spy="scroll"]').each(function () {
    var $spy = $(this).scrollspy('refresh')
});


$(document).ready(function () {
    $(".co_force_edit").hide();

    var cloneCntr = 2;
    $.validator.addMethod("loginRegex", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9\_\,\!\.\%\&\-\s\:\;\'\`\(\/)']+$/i.test(value);
    }, "Field must contain only letters, numbers, spaces, underscore, colon, semicolon, round bracket, forward slash, ' or dashes.");
    $.validator.addMethod('selectcheck', function (value) {
        return (value != ' ');
    }, "This field is required.");

    $('.bloom_list_data').multiselect({
        includeSelectAllOption: true,
        buttonWidth: '110px',
        nonSelectedText: 'Select level',
        templates: {
            button: '<button type="button" class="multiselect btn btn-link co_multi dropdown-toggle" data-toggle="dropdown"></button>'
        }
    });

    //function to save clo data
    $('.submit').on('click', function () {
        var curriculum_id = document.getElementById('crclm_id').value;
        var term_id = document.getElementById('term_id').value;
        var course_id = document.getElementById('crs_id').value;
        var clo_statement = $('#clo_statement_1').val();
        var bloom_domain_id = new Array;
        var bloom_level = $("#bloom_level_1").val();
        bloom_domain_id.push($("#bld_id_1").val());
        bloom_domain_id.push($("#bld_id_2").val());
        bloom_domain_id.push($("#bld_id_3").val());
        var bloom_level_1 = $("#bloom_level_2").val();
        var bloom_level_2 = $("#bloom_level_3").val();
        var delivery_method = $("#delivery_method_1").val();
        var bloom_field_id = $('#bloom_filed_id').val();
        var clo_bl_flag = $('#clo_bl_flag').val();
        var id_val = '#' + bloom_field_id;
        var value = $(id_val).val();
        var i = 1; 
        if (clo_bl_flag  == 1) {
            $('input[name="bloom_filed_id[]').each(function () {
                id = '#' + $(this).val();
                var values = $(id).val();
                if (values === null) {
                    flag1 = 0;
                    return false;

                } else {
                    flag1 = 1;
                }
                i++;
            });
        } else {

            flag1 = 1;
        }
        $("#clo_form").validate({
            errorClass: "help-inline font_color",
            errorElement: "span",
            highlight: function (label) {
                $(label).closest('.control-group').removeClass('success').addClass('error');
            },
            onkeyup: false,
            onblur: true,
            success: function (error, label) {
                $('#error_placeholder').html("");
                $(label).closest('.control-group').removeClass('error').addClass('success');

            }
        });
        var flag = $('#clo_form').valid();
        var post_data = {
            'crclm_id': curriculum_id,
            'term_id': term_id,
            'crs_id': course_id,
            'clo_statement': clo_statement,
            'bloom_level': bloom_level,
            'delivery_method': delivery_method,
            'bloom_domain_id': bloom_domain_id,
            'bloom_level_1': bloom_level_1,
            'bloom_level_2': bloom_level_2
        };
        if (flag === true && flag1 == 1) {
		
            $('#error_placeholder').html(" ");
            $.ajax({
                type: "POST",
                url: base_url + 'curriculum/clo/clo_insert',
                data: post_data,
                success: function (data) {
                    if (data != 1) {
                        var post_data = {
                            'curriculum_id': curriculum_id,
                            'term_id': term_id,
                            'course_id': course_id,
                        }

                        $.ajax({type: "POST",
                            url: base_url + 'curriculum/clo/show_clo',
                            data: post_data,
                            dataType: 'json',
                            success: [populate_table_add, reset_achive, success_modal]
                        });
                        $("#char_span_support").text("0 of 2000.");
                        $("#bloom_level_1_actionverbs").html("");
                        $("#bloom_level_2_actionverbs").html("");
                        $("#bloom_level_3_actionverbs").html("");
                        $(".error_placeholder_bl").html("");
                    } else {
                        $('#myModal_Warning').modal('show');
                    }
                }});
        } else {
		
            if (clo_bl_flag == 1) {		
			for(i = 1 ;i<4 ; i++){
			 $('input[name="bloom_filed_id[]').each(function () {
                    id = '#' + $(this).val();
                    var values = $(id).val(); 
                    if (values == null || values == 'null') { 
                        $('#error_placeholder_bl' + i).html("<span style='color:#b94a48'>This field is required</span>");
                        $('#mandatory_mark').html('*');
                    } else {
                        $('#error_placeholder_bl' + i).html("<span style='color:#b94a48'></span>");
                    }                  
                });
				}
            }
        }
    });
});
var page = $('#page_diff').val();

if (page == 0) {
    var curriculum_id = document.getElementById('curriculum').value;
    var term_id = document.getElementById('term').value;
    var course_id = document.getElementById('course').value;
} else if (page == 1) {
    var curriculum_id = document.getElementById('crclm_id').value;
    var term_id = document.getElementById('term_id').value;
    var course_id = document.getElementById('crs_id').value;
}

var post_data = {
    'curriculum_id': curriculum_id,
    'term_id': term_id,
    'course_id': course_id,
}

 $.ajax({type: "POST",
    url: base_url + 'curriculum/clo/show_clo',
    data: post_data,
    dataType: 'json',
    success: [populate_table_add, reset_achive]
}); 

function success_modal(msg) {
    var data_options = '{"text":"Your data has been saved successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);

}
function success_modal1(msg) {
    var data_options = '{"text":"Your data has been updated successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);

}

//To fetch help content related to program educational objective
$('.show_help').on('click', function () {
    $.ajax({
        url: base_url + 'curriculum/clo/clo_help',
        datatype: "JSON",
        success: function (msg) {
            $('#clo_help_content').html(msg);
        }
    });
});

$('.add_clo_submit').on('click', function () {
    var crclm_id = $('#curriculum').val();
    var term_id = $('#term').val();
    var crs_id = $('#course').val();
    window.location = base_url + 'curriculum/clo/clo_add/' + crclm_id + '/' + term_id + '/' + crs_id;
});

//loading image till the email is sent
$('.submit1').click(function () {
    $('#loading').show();
});

// Pre-requisite Course script starts here...
$('#pre_requisite').on('click', function () {
    var pre_requisite_crclm_id = $('#curriculum').val();
    var curriculu_name = $('#curriculum option:selected').text();
    var pre_requisite_term_id = $('#term').val();
    var term_name = $('#term option:selected').text();
    var pre_requisite_course_id = $('#course').val();
    var course_name = $('#course option:selected').text();
    var course_code = $('#course option:selected').attr('crs_code');

    var post_data = {
        'crclm_id': pre_requisite_crclm_id,
        'term_id': pre_requisite_term_id,
        'crs_id': pre_requisite_course_id,
    }

    $('#pre_requisite_crclm_id').val(pre_requisite_crclm_id);
    $('#pre_requisite_term_id').val(pre_requisite_term_id);
    $('#pre_requisite_course_id').val(pre_requisite_course_id);
    $('#pre_requisite_curriculum_name').empty();
    $('#pre_requisite_curriculum_name').html('<font color="blue">' + curriculu_name + '</font>');
    $('#pre_requisite_term_name').empty();
    $('#pre_requisite_term_name').html('<font color="blue">' + term_name + '</font>');
    $('#pre_requisite_course_name').empty();
    $('#pre_requisite_course_name').html('<font color="blue">' + course_name + ' (' + course_code + ')' + '</font>');

    if (pre_requisite_crclm_id != '' && pre_requisite_term_id != '' && pre_requisite_course_id != '') {
        $.ajax({type: "POST",
            url: base_url + 'curriculum/clo/fetch_pre_requisite',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                $('#pre_requisite_statement').val($.trim(msg));
                $('#pre_requisite_modal').modal('show');
            }
        });
    } else {
        $('#modal_error_alert').modal('show');
    }
});


$('#save_pre_requisite').on('click', function () {

    var pre_requisite_crclm_id = $('#curriculum').val();
    var pre_requisite_term_id = $('#term').val();
    var pre_requisite_course_id = $('#course').val();
    var pre_requisite_statement = $('#pre_requisite_statement').val();

    var post_data = {
        'crclm_id': pre_requisite_crclm_id,
        'term_id': pre_requisite_term_id,
        'crs_id': pre_requisite_course_id,
        'pre_requisite_statement': pre_requisite_statement
    }
    $.ajax({type: "POST",
        url: base_url + 'curriculum/clo/manage_pre_requisite',
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
            if (msg == 1) {
                $('#modal_update_pre_requisite').modal('show');
            }
        }
    });
});
// Pre-requisite Course script ends here... 

// Course-Wise import for courses starts from here
$('#course_data_import').on('click', function () {
    var clo_import_data = $('#clo_import_data').val();
    if (clo_import_data == 0) {
        var term_import_crclm_id = $('#curriculum').val();
        var curriculu_name = $('#curriculum option:selected').text();
        var term_import_term_id = $('#term').val();
        var term_name = $('#term option:selected').text();
        var course_id = $('#course').val();
        var course_name = $('#course option:selected').text();
        var crs_mode = $('#course option:selected').attr('crs_mode');
        var crs_code = $('#course option:selected').attr('crs_code');

        $.ajax({
            type: "POST",
            url: base_url + 'curriculum/import_curriculum/select_crs_mode',
            data: {
                'crs_name': course_name,
                'course_id': course_id
            },
            //datatype: "JSON",
            success: function (msg) {
                var course_mode = msg;
                $('#place_course_mode').html('<font color="blue">' + course_mode + '</font>');
            }
        });

        $('#import_crclm_id').val(term_import_crclm_id);
        $('#import_term_id').val(term_import_term_id);
        $('#import_course_id').val(course_id);
        $('#place_curriculum_name').empty();
        $('#place_curriculum_name').html('<font color="blue">' + curriculu_name + '</font>');
        $('#place_term_name').empty();
        $('#place_term_name').html('<font color="blue">' + term_name + '</font>');
        $('#place_course_name').empty();
        $('#place_course_name').html('<font color="blue">' + course_name + ' (' + crs_code + ') ' + '</font>');

        if (term_import_crclm_id != '' && term_import_term_id != '' && course_id != '')
        {
            $.ajax({type: "POST",
                url: base_url + 'curriculum/import_curriculum/populate_dropdowns',
                //data: post_data,
                datatype: "JSON",
                success: function (msg) {
                    $('#place_department_dropdown').empty();
                    $('#place_department_dropdown').html(msg);
                    $('#crs_mode').val(crs_mode);
                    $('#course_import_modal').modal({dynamic: true});
                }
            });
        } else {
            $('#modal_error_alert').modal({dynamic: true});
        }
    } else {
        $('#co_data_import').modal('show');
    }
});

$('#place_department_dropdown').on('change', '#department_id', function () {
    var department_id = $('#department_id').val();
    var post_data = {'dept_id': department_id};
    $.ajax({type: "POST",
        url: base_url + 'curriculum/import_curriculum/populate_program_dropdown',
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
            $('#place_program_dropdown').empty();
            $('#place_program_dropdown').html(msg);

            $('#crclm_name_id').empty();
            var crclm_option = $('<option>Select Curriculum</option>');
            $('#crclm_name_id').append(crclm_option);

            $('#term_id_val').empty();
            var term_option = $('<option>Select Term</option>');
            $('#term_id_val').append(term_option);

            $('#course_id_val').empty();
            var crs_option = $('<option>Select Course</option>');
            $('#course_id_val').append(crs_option);

            $('#place_co_entity_list').empty();
        }
    });


});

$('#place_program_dropdown').on('change', '#program_id', function () {
    var pgm_id = $('#program_id').val();
    var to_crclm_id = $('#import_crclm_id').val();
    var post_data = {'pgm_id': pgm_id, 'to_crclm_id': to_crclm_id};
    $.ajax({type: "POST",
        url: base_url + 'curriculum/import_curriculum/populate_curriculum_dropdown',
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
            $('#place_curriculum_dropdown').empty();
            $('#place_curriculum_dropdown').html(msg);

            $('#term_id_val').empty();
            var term_option = $('<option>Select Term</option>');
            $('#term_id_val').append(term_option);

            $('#course_id_val').empty();
            var crs_option = $('<option>Select Course</option>');
            $('#course_id_val').append(crs_option);

            $('#place_co_entity_list').empty();
        }
    });
});

$('#place_curriculum_dropdown').on('change', '#crclm_name_id', function () {
    var crclm_id = $('#crclm_name_id').val();
    var post_data = {'crclm_id': crclm_id};
    $.ajax({type: "POST",
        url: base_url + 'curriculum/import_curriculum/populate_term_dropdown',
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
            $('#place_term_dropdown').empty();
            $('#place_term_dropdown').html(msg);

            $('#course_id_val').empty();
            var crs_option = $('<option>Select Course</option>');
            $('#course_id_val').append(crs_option);

            $('#place_co_entity_list').empty();
        }
    });
});

$('#place_term_dropdown').on('change', '#term_id_val', function () {
    var dept_id = $('#department_id').val();
    var crclm_id = $('#crclm_name_id').val();
    var term_id = $('#term_id_val').val();
    var crs_mode = $('#crs_mode').val();


    var post_data = {'crclm_id': crclm_id,
        'term_id': term_id,
        'dept_id': dept_id,
        'crs_mode': crs_mode};
    $.ajax({type: "POST",
        url: base_url + 'curriculum/import_curriculum/populate_course_dropdown',
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
            $('#place_course_dropdown').empty();
            $('#place_course_dropdown').html(msg);
            $('#place_co_entity_list').empty();
        }
    });
});

$('#place_course_dropdown').on('change', '#course_id_val', function () {
    $('#place_co_entity_list').empty();
    var to_import_crclm_id = $('#import_crclm_id').val();
    var crclm_id = $('#crclm_name_id').val();
    var term_id = $('#term_id_val').val();
    var course_id = $('#course_id_val').val();
    if (course_id != '') {
        var post_data = {'crclm_id': crclm_id,
            'term_id': term_id,
            'course_id': course_id,
            'to_import_crclm_id': to_import_crclm_id,
        };
        $.ajax({type: "POST",
            url: base_url + 'curriculum/import_curriculum/populate_course_entity',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                $('#place_co_entity_list').empty();
                $('#place_co_entity_list').html(msg)

            }
        });
    }
});

var entity_id_array = new Array();// global Array
$('#place_co_entity_list').on('click', '.course_entity', function () {
    if ($(this).is(':checked')) {
        entity_id_array.push($(this).val());
        $('#entity_ids').val(entity_id_array);
        var crs_id = $('#entity_ids').val();
        if (crs_id != '') {
            $('#courses_entity_level_import').attr('disabled', false);
        } else {
            $('#courses_entity_level_import').attr('disabled', true);
        }

    } else {
        var id = $(this).val();
        var index = $.inArray(id, entity_id_array);
        entity_id_array.splice(index, 1);
        $('#entity_ids').val(entity_id_array);
        var crs_id = $('#entity_ids').val();
        if (crs_id != '') {
            $('#courses_entity_level_import').attr('disabled', false);
        } else {
            $('#courses_entity_level_import').attr('disabled', true);
        }
    }

});

$('#place_co_entity_list').on('click', '.clo_enity_check_all', function () {
    if ($(this).is(':checked')) {
        $('.course_entity').each(function () {
            $(this).attr('checked', true);
            entity_id_array.push($(this).val());
        });
        $('#entity_ids').val(entity_id_array);
        var crs_id = $('#entity_ids').val();
        if (crs_id != '') {
            $('#courses_entity_level_import').attr('disabled', false);
        } else {
            $('#courses_entity_level_import').attr('disabled', true);
        }
    } else {
        $('.course_entity').each(function () {
            $(this).attr('checked', false);
        });
        entity_id_array = [];
        $('#entity_ids').val(entity_id_array);
        var crs_id = $('#entity_ids').val();
        if (crs_id != '') {
            $('#courses_entity_level_import').attr('disabled', false);
        } else {
            $('#courses_entity_level_import').attr('disabled', true);
        }
    }

});

$('#place_co_entity_list').on('click', '.tlo_check', function () {
    if ($(this).is(':checked')) {

        var entity_id = $(this).val();
        var to_crclm_id = $('#import_crclm_id').val();
        var to_term_id = $('#import_term_id').val();
        var to_course_id = $('#import_course_id').val();
        var from_crclm_id = $('#crclm_name_id').val();
        var from_term_id = $('#term_id_val').val();
        var from_course_id = $('#course_id_val').val();
        var post_data = {
            'to_crclm_id': to_crclm_id,
            'to_term_id': to_term_id,
            'to_course_id': to_course_id,
            'from_crclm_id': from_crclm_id,
            'from_term_id': from_term_id,
            'from_course_id': from_course_id,
            'entity_id': entity_id
        };
        $.ajax({type: "POST",
            url: base_url + 'curriculum/import_curriculum/topic_entity_check',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                if (msg != 0) {
                    $('#modal_tlo_existence_alert').modal({dynamic: true});
                }
            }
        });
        $('.topic_check').attr('checked', true)
        entity_id_array.push($('.topic_check').val());
        $('#entity_ids').val(entity_id_array);

    } else {
        $('.tlo_co_check').attr('checked', false);
        var val = ['12','17'];
        var myArray = entity_id_array.filter( function( el ) {
                            return !val.includes( el );
                          } );
        $('#entity_ids').val(myArray);
        if (myArray.length != 0) {
            $('#courses_entity_level_import').attr('disabled', false);
        } else {
            $('#courses_entity_level_import').attr('disabled', true);
        }
    }
});

$('#place_co_entity_list').on('click', '.topic_check', function () {
    if ($(this).is(':checked')) {
        var entity_id = $(this).val();
        var to_crclm_id = $('#import_crclm_id').val();
        var to_term_id = $('#import_term_id').val();
        var to_course_id = $('#import_course_id').val();
        var from_crclm_id = $('#crclm_name_id').val();
        var from_term_id = $('#term_id_val').val();
        var from_course_id = $('#course_id_val').val();
        var post_data = {
            'to_crclm_id': to_crclm_id,
            'to_term_id': to_term_id,
            'to_course_id': to_course_id,
            'from_crclm_id': from_crclm_id,
            'from_term_id': from_term_id,
            'from_course_id': from_course_id,
            'entity_id': entity_id
        };
        $.ajax({type: "POST",
            url: base_url + 'curriculum/import_curriculum/topic_entity_check',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                if (msg != 0) {
                    $('#modal_topic_existence_alert').modal({dynamic: true});
                }
            }
        });
    } else {
        $('.tlo_check').attr('checked', false);
        $('.tlo_co_check').attr('checked', false);
        var val = ['12','10','17'];
        var myArray = entity_id_array.filter( function( el ) {
                            return !val.includes( el );
                          } );
        $('#entity_ids').val(myArray);
        if (myArray.length != 0) {
            $('#courses_entity_level_import').attr('disabled', false);
        } else {
            $('#courses_entity_level_import').attr('disabled', true);
        }
    }
});

$('#place_co_entity_list').on('click', '.co_check', function () {
    if ($(this).is(':checked')) {
        //$('.tlo_check').show();
        var entity_id = $(this).val();
        var to_crclm_id = $('#import_crclm_id').val();
        var to_term_id = $('#import_term_id').val();
        var to_course_id = $('#import_course_id').val();
        var from_crclm_id = $('#crclm_name_id').val();
        var from_term_id = $('#term_id_val').val();
        var from_course_id = $('#course_id_val').val();
        var post_data = {
            'to_crclm_id': to_crclm_id,
            'to_term_id': to_term_id,
            'to_course_id': to_course_id,
            'from_crclm_id': from_crclm_id,
            'from_term_id': from_term_id,
            'from_course_id': from_course_id,
            'entity_id': entity_id
        };
        $.ajax({type: "POST",
            url: base_url + 'curriculum/import_curriculum/co_entity_check',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                if (msg != 0) {
                    $('#modal_co_existence_alert').modal({dynamic: true});
                }
            }
        });

    } else {
        $('.course_entity').each(function () {
            $(this).attr('checked', false);
        });
        entity_id_array = [];
        $('#entity_ids').val(entity_id_array);
        var crs_id = $('#entity_ids').val();
        if (crs_id != '') {
            $('#courses_entity_level_import').attr('disabled', false);
        } else {
            $('#courses_entity_level_import').attr('disabled', true);
        }
    }
});

$('#place_co_entity_list').on('click', '.co_po_map_check', function () {
    if ($(this).is(':checked')) {
        //$('.tlo_check').show();
        var entity_id = $(this).val();
        var to_crclm_id = $('#import_crclm_id').val();
        var to_term_id = $('#import_term_id').val();
        var to_course_id = $('#import_course_id').val();
        var from_crclm_id = $('#crclm_name_id').val();
        var from_term_id = $('#term_id_val').val();
        var from_course_id = $('#course_id_val').val();
        var post_data = {
            'to_crclm_id': to_crclm_id,
            'to_term_id': to_term_id,
            'to_course_id': to_course_id,
            'from_crclm_id': from_crclm_id,
            'from_term_id': from_term_id,
            'from_course_id': from_course_id,
            'entity_id': entity_id
        };
        $.ajax({type: "POST",
            url: base_url + 'curriculum/import_curriculum/clo_po_entity_check',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                if (msg != 0) {
                    $('#co_po_map_existence_alert').modal({dynamic: true});
                }
            }
        });

    } else {
        $('.course_entity').each(function () {
            $(this).attr('checked', false);
        });
        entity_id_array = [];
        $('#entity_ids').val(entity_id_array);
        var crs_id = $('#entity_ids').val();
        if (crs_id != '') {
            $('#courses_entity_level_import').attr('disabled', false);
        } else {
            $('#courses_entity_level_import').attr('disabled', true);
        }
    }
});

// Function to check the TLOs to CO Map data exists or not

$('#place_co_entity_list').on('click', '.tlo_co_check', function () {
    if ($(this).is(':checked')) {
        //$('.tlo_check').show();
        var entity_id = $(this).val();
        var to_crclm_id = $('#import_crclm_id').val();
        var to_term_id = $('#import_term_id').val();
        var to_course_id = $('#import_course_id').val();
        var from_crclm_id = $('#crclm_name_id').val();
        var from_term_id = $('#term_id_val').val();
        var from_course_id = $('#course_id_val').val();
        var post_data = {
            'to_crclm_id': to_crclm_id,
            'to_term_id': to_term_id,
            'to_course_id': to_course_id,
            'from_crclm_id': from_crclm_id,
            'from_term_id': from_term_id,
            'from_course_id': from_course_id,
            'entity_id': entity_id
        };
        $.ajax({type: "POST",
            url: base_url + 'curriculum/import_curriculum/topic_entity_check',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                if (msg != 0) {
                    $('#modal_tlo_co_map_existence_alert').modal({dynamic: true});
                }
            }
        });
        $('.topic_check').attr('checked', true)
        $('.tlo_check').attr('checked', true)
        entity_id_array.push($('.topic_check').val());
        entity_id_array.push($('.tlo_check').val());
        $('#entity_ids').val(entity_id_array);
    } else {
        var val = ['17'];
        var myArray = entity_id_array.filter( function( el ) {
                            return !val.includes( el );
                          } );
        $('#entity_ids').val(myArray);
        if (myArray.length != 0) {
            $('#courses_entity_level_import').attr('disabled', false);
        } else {
            $('#courses_entity_level_import').attr('disabled', true);
        }
    }
});

$('#courses_entity_level_import').on('click', function () {
    var course_entity_array = new Array();
    var entity_id;
    var to_crclm_id = $('#import_crclm_id').val();
    var to_term_id = $('#import_term_id').val();
    var to_course_id = $('#import_course_id').val();
    var from_crclm_id = $('#crclm_name_id').val();
    var from_term_id = $('#term_id_val').val();
    var from_course_id = $('#course_id_val').val();
    var crs_mode = $('#crs_mode').val();

    $('.course_entity').each(function () {
        if ($(this).is(':checked')) {
            entity_id = $(this).val();
            course_entity_array.push(entity_id); // creating array of course id (selected courses)
        } else {

        }
    });
    var post_data = {
        'to_crclm_id': to_crclm_id,
        'to_term_id': to_term_id,
        'to_course_id': to_course_id,
        'from_crclm_id': from_crclm_id,
        'from_term_id': from_term_id,
        'from_course_id': from_course_id,
        'course_entity_ids': course_entity_array,
        'crs_mode': crs_mode
    };

    $('#loading').show();
    $.ajax({type: "POST",
        url: base_url + 'curriculum/import_curriculum/course_entity_import_insert',
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
            if (msg == 1) {

                location.reload();
            }
        }
    });
});

/**********artifacts***********/

//displaying the modal
$('#artifacts_modal').click(function (e) {
    e.preventDefault();
    display_artifact();
});

//displaying the modal content
function display_artifact() {
    var artifact_value = $('#art_val').val();
    var crclm_id = $('#curriculum').val();
    if (crclm_id != '') {
        var post_data = {
            'art_val': artifact_value,
            'crclm': crclm_id
        }

        $.ajax({
            type: "POST",
            url: base_url + 'upload_artifacts/artifacts/modal_display',
            data: post_data,
            async: false,
            success: function (data) {
                $('#art').html(data);
                $('#mymodal').modal('show');
            }
        });
    } else {
        $('#select_crclm').modal('show');
    }
}

//uploading the file 
$('.art_facts,#curriculum').on('click change', function (e) {
    var uploader = document.getElementById('uploaded_file');
    var crclm_id = $('#curriculum').val();
    var art = $('#art_val').val();
    var val = $(this).attr('uploaded-file');
    var folder_name = $('#curriculum option:selected').val();
    var post_data = {
        'crclm': crclm_id,
        'art_val': art,
        'crclm': folder_name
    }
    upclick({
        element: uploader,
        action_params: post_data,
        action: base_url + 'upload_artifacts/artifacts/modal_upload',
        onstart: function (filename) {
            (document).getElementById('loading_edit').style.visibility = 'visible';
        },
        oncomplete: function (response_data) {
            if (response_data == "file_name_size_exceeded") {
                $('#file_name_size_exc').modal('show');
            } else if (response_data == "file_size_exceed") {
                $('#larger').modal('show');
            }
            display_artifact();
            (document).getElementById('loading_edit').style.visibility = 'hidden';
        }

    });
});

//deleting the file
$('#art').on('click', '.artifact_entity', function (e) {
    var del_id = $(this).attr('data-id');

    $('#delete_file').modal('show');
    $('#delete_selected').click(function (e) {
        $.ajax({
            type: "POST",
            url: base_url + 'upload_artifacts/artifacts/modal_delete_file',
            data: {'artifact_id': del_id},
            success: function (data) {
                display_artifact();
            }
        });
        $('#delete_file').modal('hide');
    });

});

$('body').on('focus', '.std_date', function () {
    $("#af_actual_date").datepicker({
        format: "yyyy-mm-dd",
        //endDate:'-1d'
    }).on('changeDate', function (ev) {
        $(this).blur();
        $(this).datepicker('hide');
    });
    $(this).datepicker({
        format: "yyyy-mm-dd",
        //endDate:'-1d'
    }).on('changeDate', function (ev) {
        $(this).blur();
        $(this).datepicker('hide');
    });
});

$('#art').on('click', '.std_date_1', function () {
    $(this).siblings('input').focus();
});

$('#update_edit').on('click', function () {
    $('#loading').show();
    var page = $('#page_diff').val();
    if (page == 0) {
        var curriculum_id = document.getElementById('curriculum').value;
        var term_id = document.getElementById('term').value;
        var course_id = document.getElementById('course').value;
    } else if (page == 1) {
        var curriculum_id = document.getElementById('crclm_id').value;
        var term_id = document.getElementById('term_id').value;
        var course_id = document.getElementById('crs_id').value;
    }
    var flag = $('#clo_edit_form').valid();
    var clo_statement = $('#clo_statement').val();
    var bloom_level = $("#bloom_level_edit_1").val();
    var bloom_domain_id = new Array;
    bloom_domain_id.push($("#bld_id_1").val());
    bloom_domain_id.push($("#bld_id_2").val());
    bloom_domain_id.push($("#bld_id_3").val());
    var bloom_level_1 = $("#bloom_level_edit_2").val();
    var bloom_level_2 = $("#bloom_level_edit_3").val();
    var delivery_method = $("#delivery_method").val();
    var clo_id = $('#clo_id').val();
    var clo_code = $('#clo_code').val();
    var clo_bl_flag = $('#clo_bl_flag').val();
    var post_data = {
        'crclm_id': curriculum_id,
        'term_id': term_id,
        'crs_id': course_id,
        'clo_statement': clo_statement,
        'bloom_level': bloom_level,
        'delivery_method': delivery_method,
        'clo_id': clo_id,
        'clo_code': clo_code,
        'bloom_domain_id': bloom_domain_id,
        'bloom_level_1': bloom_level_1,
        'bloom_level_2': bloom_level_2
    };

    var bloom_field_id = $('#bloom_filed_edit_id').val();
    var id_val = '#' + bloom_field_id;
    var value = $(id_val).val();
    var flag1 = 0;
    if (clo_bl_flag == 1) {
        var i = 1;
        $('input[name="bloom_filed_edit_id[]').each(function () {
            id = '#' + $(this).val();
            var values = $(id).val();
            if (values === null) {
                flag1 = 0;
                return false;

            } else {
                flag1 = 1;
            }
            i++;
        });
    } else {

        flag1 = 1;
    }

    if (flag === true && flag1 == 1) {

        $.ajax({type: "POST",
            url: base_url + 'curriculum/clo/edit_clo_check',
            data: post_data,
            dataType: 'json',
            success: function (data) {
                if (data === 0) {
                    $.ajax({type: "POST",
                        url: base_url + 'curriculum/clo/update_clo',
                        data: post_data,
                        dataType: 'json',
                        success: function (data) {
                            if (data === true) {
                                $('#publish').attr("disabled", false);
                                $('#add_clo').attr("disabled", false);
                                $('#add_clo_clone').attr("disabled", false);
                                $(".co_force_edit").hide();
                            }
                            var post_data = {
                                'curriculum_id': curriculum_id,
                                'term_id': term_id,
                                'course_id': course_id
                            };

                            $.ajax({type: "POST",
                                url: base_url + 'curriculum/clo/show_clo',
                                data: post_data,
                                dataType: 'json',
                                success: [populate_table_add, reset_achive]
                            });
                            if (data === 'false') {
                                $('#co_exist').html("Sorry. This Course Outcome(CO) Already Exist.");
                                //fail_modal();
                            } else {
                                $("#myModal_edit_clo").modal('hide');
                                $('#loading').hide();
                                success_modal1();
                                $('#co_exist').html(" ");
                            }
                        }
                    });//
                } else
                    $("#edit_checking").modal("show");
                $('#loading').hide();
            }
        });
    } else {
  $('#loading').hide();
        if (clo_bl_flag == 1) {
            var j = 1;
            $('input[name="bloom_filed_edit_id[]').each(function () {
                id = '#' + $(this).val();
                var values = $(id).val();
                if (values === null) {
                    $('#error_placeholder_edit_bl' + j).html("<span style='color:#b94a48'> This field is required. </span>");
                } else {
                    $('#error_placeholder_edit_bl' + j).html(" ");
                }
                j++;
            });
        }
    }
});

function fail_modal(msg) {
    var data_options = '{"text":"Sorry. This Course Outcome(CO) Already Exist.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}
//on save artifact description and date
$('#save_artifact').live('click', function (e) {
    e.preventDefault();
    $('#myform').submit();
});

$('#myform').on('submit', function (e) {
    e.preventDefault();
    var form_data = new FormData(this);
    var form_val = new Array();

    $('.save_form_data').each(function () {
        //values fetched will be inserted into the array
        form_val.push($(this).val());
    });

    //check whether file any file exists or not
    if (form_val.length > 0) {
        //if file exists
        $.ajax({
            type: "POST",
            url: base_url + 'upload_artifacts/artifacts/save_artifact',
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (msg) {
                if ($.trim(msg) == 1) {
                    //display success message on save
                    var data_options = '{"text":"Your data has been saved successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                    var options = $.parseJSON(data_options);
                    noty(options);
                } else {
                    //display error message - if description and date could not be saved
                    var data_options = '{"text":"Your data could not be saved.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
                    var options = $.parseJSON(data_options);
                    noty(options);
                }
            }
        });
    } else {
        //display error message if file does not exist and user tries to click save button
        var data_options = '{"text":"File needs to be uploaded.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
        var options = $.parseJSON(data_options);
        noty(options);
    }
});

//message to display animated notification instead of modal
$('.noty').click(function () {
    var options = $.parseJSON($(this).attr('data-noty-options'));
    noty(options);
});

//count number of characters entered in the description box
$('.char-counter').live('keyup', function () {
    var max = parseInt($(this).attr('maxlength'));
    var len = $(this).val().length;
    var spanId = 'char_span_support';
    if (len >= max) {
        $('#' + spanId).css('color', 'red');
        $('#' + spanId).text(' You have reached the limit.');
    } else {
        $('#' + spanId).css('color', '');
        $('#' + spanId).text(len + ' of ' + max + '.');
    }
});

//count number of characters entered in the description box
$('#clo_statement').live('keyup', function () {
    var max = parseInt($(this).attr('maxlength'));
    var len = $(this).val().length;
    var spanId = 'char_span_support1';
    if (len >= max) {
        $('#' + spanId).css('color', 'red');
        $('#' + spanId).text(' You have reached the limit.');
    } else {
        $('#' + spanId).css('color', '');
        $('#' + spanId).text(len + ' of ' + max + '.');
    }
});