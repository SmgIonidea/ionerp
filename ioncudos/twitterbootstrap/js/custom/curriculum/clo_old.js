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
        }
    });
    $('#myModal_publish').modal('show');
});

//Function to publish the course learning objective statements
function publish() {
    var curriculum_id = document.getElementById('curriculum').value;
    var term_id = document.getElementById('term').value;
    var course_id = document.getElementById('course').value;

    var post_data = {
        'curriculum_id': curriculum_id,
        'course_id': course_id,
        'term_id': term_id,
    }

    $.ajax({type: "POST",
        url: base_url + 'curriculum/clo/publish_details',
        data: post_data,
        success: function (msg) {
		   	$(location).attr('href', './clo_po_map/map_po_clo/'+curriculum_id+'/'+term_id+'/'+course_id); 
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
            var oTable = $('#example').dataTable();
            oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
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
                //select_clo();
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
    }
    else {
        $('#add_clo').attr("disabled", true);
        $('#add_clo_clone').attr("disabled", true);
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
/////////////////////////
function static_populate_table(msg) {
    var m = 'd';
    $('#example').dataTable().fnDestroy();
    $('#example').dataTable(
            {"aoColumns": [
                    {"sTitle": "Course Outcomes(COs)", "mData": "clo_statement"},
                ], "aaData": msg});
}
//Function to generate data table grid
function populate_table(msg) {
    var m = 'd';
    var curriculum_id = $('#curriculum').val();
    var term_id = $('#term').val();
    var course_id = $('#course').val();

    $('#example').dataTable().fnDestroy();
    $('#example').dataTable(
            {
				"aaSorting" : [],
				"aoColumns": [
					{"sTitle": "CO Code", "mData": "clo_code"},
                    {"sTitle": "Course Outcomes(COs)", "mData": "clo_statement"},
                    {"sTitle": "Bloom's Level", "mData": "bloom_level"},
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
        }
        else {
            $('#publish').attr("disabled", false);
            $('#add_clo').attr("disabled", false);
            $('#add_clo_clone').attr("disabled", false);
        }
    }
    else {
        $('#publish').attr("disabled", true);
        $('#add_clo').attr("disabled", true);
        $('#add_clo_clone').attr("disabled", true);
    }

}
// Function to EDIT the Freezed CLO.
$('#example').on('click', '.force_edit', function () {
    var id_val = $(this).attr('id');
    $('#clo_data').val(id_val);
    $('#my_force_edit_modal').modal('show');

});

// on Modal ok button this function get called
function force_edit()
{

    var clo_value = $('#clo_data').val();
    window.location = base_url + 'curriculum/clo/edit_clo/' + clo_value;

}

// Function to DELETE the Freezed CLO.
$('#example').on('click', '.force_delete', function () {
    var id_val = $(this).attr('id');
    $('#clo_del_data').val(id_val);
    $('#my_force_delete_modal').modal('show');

});
// function force_edit(id){
// }
//Course Learning Objective Add Page
/*You may use scrollspy along with creating and removing elements form DOM. But if you do so, you have to call the refresh method . 
 The following code shows how you may do that:*/

$('[data-spy="scroll"]').each(function () {
    var $spy = $(this).scrollspy('refresh')
});

/* /Function to 
 function select_clo() {
    $.cookie('remember_selected_value', $('#course option:selected').val(), {expires: 90, path: '/'});
    $.ajax({type: "POST",
        url: base_url + 'curriculum/clo/clo_textarea',
        success: function (msg) {
            $('#table_view').html(msg);
        }
    });
} */
////////////////////////////////////////////////////////////////////////////
$(document).ready(function () {
    var cloneCntr = 2;	
    /* $.validator.addMethod("numeric", function(value, element) {
     var regex = /^[0-9]+$/; //this is for numeric... you can do any regular expression you like...
     return this.optional(element) || regex.test(value);
     m
     }, "Field must contain only numbers."); */


    $.validator.addMethod("loginRegex", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9\_\,\!\.\%\&\-\s\:\;\'\`\(\/)']+$/i.test(value);
    }, "Field must contain only letters, numbers, spaces, underscore, colon, semicolon, round bracket, forward slash, ' or dashes.");

    $("#clo_form").validate({
        errorClass: "help-inline font_color",
        errorElement: "span",
        highlight: function (label) {
            $(label).closest('.control-group').addClass('error');
        },
        errorPlacement: function (error, element) {
            if (element.parent().parent().hasClass("input-append")) {
                error.insertAfter(element.parent().parent());
            } else {
                error.insertAfter(element.parent().parent());
            }
        },
        onkeyup: false,
        onblur: false,
        success: function (label) {
            $(label).closest('.control-group').removeClass('error');
        }
    });
    $('.submit').on('click', function (event) {
        $('#clo_form').validate();
        // adding rules for inputs with class 'comment'
        $('.clo_stmt').each(function () {
            $(this).rules("add",
                    {
                        loginRegex: true
                    });
        });
    });




///////////////////////////////////////////////////////////////////////////
    //Function to dynamically add more course learning objective statements
    $(".add_clo").live("click", function () {
	
        /* var clo_block_one = '<div class="bs-docs-example" id="add_me' + cloneCntr + '"><div class="control-group input-append" style="width:100%;"><label class="control-label" for="clo_statement_' + cloneCntr + '">Course Outcomes (CO) Statement: <font color="red">*</font></label>'; */

       /*  var clo_block_two = '<div class="controls"> <textarea name="clo_statement_' + cloneCntr + '" cols="100"  id="clo_statement_' + cloneCntr + '" rows="2" type="text" class="required clo_stmt" style="margin-left: 0px; margin-right: 0px; width: 80%;"></textarea> <button id="clo_btn_' + cloneCntr + '" class="btn btn-danger delete_clo" type="button"><i class="icon-minus-sign icon-white"></i> Delete CO</button></div></div><br><br><div class="span12"><div class="span6"><label class="control-label" for="bloom_level'+cloneCntr+ '">Bloom Level   :   <select name="bloom_level_'+cloneCntr+'[]" id="bloom_level_'+cloneCntr+'" multiple="multiple" class="example-getting-started bloom_level_class" onchange="call_func('+cloneCntr+')">' + bloom_level_options + '</select></label></div><div id="abc_"'+cloneCntr+'><div id="bloom_level_'+cloneCntr+'_actionverbs"></div></div><div class="span6"><label class="control-label" for="delivery_method' + cloneCntr + '">Delivery Method   :    <select name="delivery_method_'+cloneCntr+'[]" id="delivery_method_'+cloneCntr+'" multiple="multiple" class="example-getting-started">' + delivery_method_options + '</select></label></div></div><br><br>';  */
	   
	   /*  var clo_block_two = '<div class="controls"> <textarea name="clo_statement_' + cloneCntr + '" cols="100"  id="clo_statement_' + cloneCntr + '" rows="2" type="text" class="required clo_stmt" style="margin-left: 0px; margin-right: 0px; width: 80%;"></textarea> <button id="clo_btn_' + cloneCntr + '" class="btn btn-danger delete_clo" type="button"><i class="icon-minus-sign icon-white"></i> Delete CO</button></div></div><br><br><div class="span12"><div class="span6"><label class="control-label" for="bloom_level'+cloneCntr+ '">Bloom Level   :   <select name="bloom_level_'+cloneCntr+'[]" id="bloom_level_'+cloneCntr+'" multiple="multiple" class="example-getting-started bloom_level_class" onchange="list_selected_blooms_level('+cloneCntr+')">' + bloom_level_options + '</select></label></div><div class="span6"><div id="bloom_level_'+cloneCntr+'_actionverbs"  style="bgcolor:#E6E6FA;"></div></div><div class="span12"><div class="span6"><label class="control-label" for="delivery_method' + cloneCntr + '">Delivery Method   :    <select name="delivery_method_'+cloneCntr+'[]" id="delivery_method_'+cloneCntr+'" multiple="multiple" class="example-getting-started">' + delivery_method_options + '</select></label></div></div></div></div></div><br><br>';
 */
 /* 
 <div class="span12"><div class="span3" style="text-align: center;"> Bloom Level  : </div><div class="span4">'+dropdown.bloom_level_dropdown+'</div><div class="span4" id="bloom_level_actionverbs">'+dropdown.bloom_level_action_verb+'</div><br><br><br></div><div class="span12"><div class="span3">Delivery Method  : </div><div class="span4">'+dropdown.delivery_method_dropdown+'</div><div class="span4"></div></div><br><br><br><br></div> */
 
		 /* var clo_block_two = '<div class="controls"> <textarea name="clo_statement_' + cloneCntr + '" cols="100"  id="clo_statement_' + cloneCntr + '" rows="2" type="text" class="required clo_stmt" style="margin-left: 0px; margin-right: 0px; width: 80%;"></textarea> <button id="clo_btn_' + cloneCntr + '" class="btn btn-danger delete_clo" type="button"><i class="icon-minus-sign icon-white"></i> Delete CO</button></div></div><br><br><div class="span12"><div class="span3">Bloom Level   :   </div><div class="span4"><select name="bloom_level_'+cloneCntr+'[]" id="bloom_level_'+cloneCntr+'" multiple="multiple" class="example-getting-started bloom_level_class" onchange="list_selected_blooms_level('+cloneCntr+')">' + bloom_level_options + '</select></div><div id="bloom_level_'+cloneCntr+'_actionverbs" class="span4"></div></div><br><br><div class="span12"><div class="span3">Delivery Method   :    </div><div class="span4"><select name="delivery_method_'+cloneCntr+'[]" id="delivery_method_'+cloneCntr+'" multiple="multiple" class="example-getting-started">' + delivery_method_options + '</select></div><div class="span4"></div></div><br><br><br><br></div>';  */
		
		/* var clo_block_two = '<div class="bs-docs-example" id="add_me' + cloneCntr + '"><div class="control-group input-append" style="width:100%;"><label class="control-label" for="clo_statement_' + cloneCntr + '">Course Outcomes (CO) Statement: <font color="red">*</font></label><div class="controls"><textarea name="clo_statement_' + cloneCntr + '" cols="100"  id="clo_statement_' + cloneCntr + '" rows="2" type="text" class="required clo_stmt" style="margin-left: 0px; margin-right: 0px; width: 80%;"></textarea><button id="clo_btn_' + cloneCntr + '" class="btn btn-danger delete_clo" type="button"><i class="icon-minus-sign icon-white"></i> Delete CO</button></div></div><br><br><div class="span12"><div class="span2">Bloom Level   :   </div><div class="span3"><select name="bloom_level_'+cloneCntr+'[]" id="bloom_level_'+cloneCntr+'" multiple="multiple" class="example-getting-started blooms_level_class" onchange="list_selected_blooms_level('+cloneCntr+')">' + bloom_level_options + '</select></div><div class="span7" id="bloom_level_'+cloneCntr+'_actionverbs"></div></div><br><br><div class="span12"><div class="span2">	Delivery Method   :   </div><div class="span3"> <select name="delivery_method_'+cloneCntr+'[]" id="delivery_method_'+cloneCntr+'" multiple="multiple" class="example-getting-started delivery_method_class">' + delivery_method_options + '</select></div></div></div>'; */
		
		var clo_block = '<div class="bs-docs-example" id="add_me'+cloneCntr+ '" style="height:200px;"><div class="control-group input-append" style="width:100%;">		<label class="control-label" for="clo_statement_' + cloneCntr + '">Course Outcomes (CO) Statement: <font color="red">*</font></label><div class="controls"><textarea name="clo_statement_' + cloneCntr + '" cols="100"  id="clo_statement_' + cloneCntr + '" rows="2" type="text" class="required clo_stmt" style="margin-left: 0px; margin-right: 0px; width: 80%;"></textarea><button id="clo_btn_' + cloneCntr + '" class="pull-right btn btn-danger delete_clo" type="button"><i class="icon-minus-sign icon-white"></i> Delete CO</button></div></div><br><br><div class="span12"><div class="span2">Bloom\'s Level   :   </div><div class="span3"><select name="bloom_level_'+cloneCntr+'[]" id="bloom_level_'+cloneCntr+'" multiple="multiple" class="example-getting-started blooms_level_class" onchange="list_selected_blooms_level('+cloneCntr+')">' + bloom_level_options+'</select>		</div><div class="span7" id="bloom_level_'+cloneCntr+'_actionverbs"></div></div><br><br><div class="span12"><div class="span2">	Delivery Method   :   </div><div class="span3"> <select name="delivery_method_'+cloneCntr+'[]" id="delivery_method_'+cloneCntr+'" multiple="multiple" class="example-getting-started delivery_method_class">' + delivery_method_options + '</select></div></div></div>';
		
        var newclo = $(clo_block);
        $('#table_view').append(newclo);
        clo_counter.push(String(cloneCntr));
        $('#counter_val').val(clo_counter);
       
		$('.blooms_level_class').multiselect({
			//includeSelectAllOption: true,
			maxHeight: 200,
			buttonWidth: 160,
			numberDisplayed: 5,
			nSelectedText: 'selected',
			nonSelectedText: 'Select Blooms Level'
		});	
		$('.delivery_method_class').multiselect({
			//includeSelectAllOption: true,
			maxHeight: 200,
			buttonWidth: 160,
			numberDisplayed: 5,
			nSelectedText: 'selected',
			nonSelectedText: 'Select Delivery Method'
			
		});		
		cloneCntr++;
    });
});

function list_selected_blooms_level(cloneCntr){	
	var selectedOptions = $('#bloom_level_'+cloneCntr+' option:selected'); 
	if (selectedOptions.length >= 4) {
		// Disable all other checkboxes.
		var nonSelectedOptions = $('#bloom_level_'+cloneCntr+' option').filter(function() {
			return !$(this).is(':selected');
		});

		var dropdown = $('#bloom_level_'+cloneCntr).siblings('.multiselect-container');
		nonSelectedOptions.each(function() {
			var input = $('input[value="' + $(this).val() + '"]');
			input.prop('disabled', true);
			input.parent('li').addClass('disabled');
		});
	}
	else {
		// Enable all checkboxes.
		var dropdown = $('#bloom_level_'+cloneCntr).siblings('.multiselect-container');
		$('#bloom_level_'+cloneCntr+' option').each(function() {
			var input = $('input[value="' + $(this).val() + '"]');
			input.prop('disabled', false);
			input.parent('li').addClass('disabled');
		});
	}
	var selections = [];
	var action_verb_data = [];
	$("#bloom_level_"+cloneCntr+" option:selected").each(function(){
		var bloom_level_id = $(this).val();
		var bloom_level = $(this).text();
		var action_verbs = $(this).attr('title');				
		selections.push(bloom_level_id);
		action_verb_data.push('<b>'+bloom_level+'-</b>'+action_verbs);
	});
	var action_verb = action_verb_data.join("<b>;</br></b>");
	$('#bloom_level_'+cloneCntr+'_actionverbs').html(action_verb.toString());	   
}

$('.delete_clo').live('click', '.bs-docs-example', function () {
    if ($('.clo_stmt').length > 1) {
        $(this).parent().parent().parent().remove();

        var replaced_id = $(this).attr('id').replace('clo_btn_', '');
        var clo_counter_index = $.inArray(replaced_id, clo_counter);

        clo_counter.splice(clo_counter_index, 1);
        $('#counter_val').val(clo_counter);

        return false;
    }
});

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
////Form validation check
/* 	$("#clo_form").validate({
 rules: {
 curriculum: {
 maxlength: 50,
 required: true
 },
 term: {
 maxlength: 20,
 required: true
 },
 course: {
 maxlength: 20,
 required: true
 },
 clo_statement_1: {
 maxlength: 100,
 required: true
 },
 },
 messages: {
 curriculum: {
 required: " curriculum is required"
 },
 term: {
 required: " term is required"
 },
 course: {
 required: " course is required"
 
 },
 clo_statement_1: {
 required: "clo_statment"
 },
 },
 errorClass: "help-inline",
 errorElement: "span",
 highlight: function(element, errorClass, validClass) {
 $(element).parent().parent().addClass('error');
 },
 unhighlight: function(element, errorClass, validClass) {
 $(element).parent().parent().removeClass('error');
 $(element).parent().parent().addClass('success');
 }
 });
 */
/* $.validator.addMethod("noSpecialChars", function(value, element) {
 return this.optional(element) || /^[a-zA-Z\s\.\&\_]+$/i.test(value);
 }, "This must contain only space letters and underscore."); */

//Function to highlight on validation
/* function valid() {
 for (var i = 1; i < cloneCntr; i++)
 {
 var clo_statement = document.getElementById('clo_statement_' + i);
 if (clo_statement)
 {
 if (clo_statement.value == "")
 {
 clo_statement.focus();
 clo_statement.style.borderColor = "#FF0000";
 return false;
 }
 clo_statement.style.borderColor = "#1eb486";
 }
 }
 return true;
 } */

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

    var post_data = {
			'crclm_id' : pre_requisite_crclm_id,
			'term_id' : pre_requisite_term_id,
			'crs_id' : pre_requisite_course_id,
		} 
	
	$('#pre_requisite_crclm_id').val(pre_requisite_crclm_id);
    $('#pre_requisite_term_id').val(pre_requisite_term_id);
    $('#pre_requisite_course_id').val(pre_requisite_course_id);
    $('#pre_requisite_curriculum_name').empty();
    $('#pre_requisite_curriculum_name').html('<font color="blue">'+curriculu_name+'</font>');
    $('#pre_requisite_term_name').empty();
    $('#pre_requisite_term_name').html('<font color="blue">'+term_name+'</font>');
    $('#pre_requisite_course_name').empty();
    $('#pre_requisite_course_name').html('<font color="blue">'+course_name+'</font>');
    
	if(pre_requisite_crclm_id !='' && pre_requisite_term_id !='' && pre_requisite_course_id !='') {
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


	$('#save_pre_requisite').on('click',function() {
	
		var pre_requisite_crclm_id = $('#curriculum').val();
		var pre_requisite_term_id = $('#term').val();
		var pre_requisite_course_id = $('#course').val();
		var pre_requisite_statement = $('#pre_requisite_statement').val();
		
		var post_data = {
			'crclm_id' : pre_requisite_crclm_id,
			'term_id' : pre_requisite_term_id,
			'crs_id' : pre_requisite_course_id,
			'pre_requisite_statement' : pre_requisite_statement
		}
		$.ajax({type: "POST",
			url: base_url + 'curriculum/clo/manage_pre_requisite',
			data: post_data,
			datatype: "JSON",
			success: function(msg) {
				if(msg == 1) {
					$('#modal_update_pre_requisite').modal('show');
				}
			}
		});
	});
// Pre-requisite Course script ends here... 


// Course-Wise import for courses starts from here
$('#course_data_import').on('click', function () {
    var term_import_crclm_id = $('#curriculum').val();
    var curriculu_name = $('#curriculum option:selected').text();
    var term_import_term_id = $('#term').val();
    var term_name = $('#term option:selected').text();
    var course_id = $('#course').val();
    var course_name = $('#course option:selected').text();
    
    $('#import_crclm_id').val(term_import_crclm_id);
    $('#import_term_id').val(term_import_term_id);
    $('#import_course_id').val(course_id);
    $('#place_curriculum_name').empty();
    $('#place_curriculum_name').html('<font color="blue">'+curriculu_name+'</font>');
    $('#place_term_name').empty();
    $('#place_term_name').html('<font color="blue">'+term_name+'</font>');
    $('#place_course_name').empty();
    $('#place_course_name').html('<font color="blue">'+course_name+'</font>');
    if(term_import_crclm_id !='' && term_import_term_id !='' && course_id !='')
    {
    $.ajax({type: "POST",
        url: base_url + 'curriculum/import_curriculum/populate_dropdowns',
        //data: post_data,
        datatype: "JSON",
        success: function (msg) {
            $('#place_department_dropdown').empty();
            $('#place_department_dropdown').html(msg);
            $('#course_import_modal').modal({ dynamic: true });
        }
    });
   }else{
        $('#modal_error_alert').modal({dynamic:true});
   }
    //$("#course_import_modal").animate({"width":"1200px","margin-left":"-600px"},600,'linear');
    
});

$('#place_department_dropdown').on('change','#department_id',function(){
    var department_id = $('#department_id').val();
    var post_data = {'dept_id':department_id};
    $.ajax({type: "POST",
        url: base_url + 'curriculum/import_curriculum/populate_program_dropdown',
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
            $('#place_program_dropdown').empty();
            $('#place_program_dropdown').html(msg);
        }
    });
    
    
});

$('#place_program_dropdown').on('change','#program_id',function(){
    var pgm_id = $('#program_id').val();
    var to_crclm_id = $('#import_crclm_id').val();
    var post_data = {'pgm_id':pgm_id, 'to_crclm_id':to_crclm_id};
    $.ajax({type: "POST",
        url: base_url + 'curriculum/import_curriculum/populate_curriculum_dropdown',
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
            $('#place_curriculum_dropdown').empty();
            $('#place_curriculum_dropdown').html(msg);
        }
    });
    
    
});

$('#place_curriculum_dropdown').on('change','#crclm_name_id',function(){
    var crclm_id = $('#crclm_name_id').val();
    var post_data = {'crclm_id':crclm_id};
    $.ajax({type: "POST",
        url: base_url + 'curriculum/import_curriculum/populate_term_dropdown',
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
            $('#place_term_dropdown').empty();
            $('#place_term_dropdown').html(msg);
        }
    });
    
    
});

$('#place_term_dropdown').on('change','#term_id_val',function(){
     var dept_id = $('#department_id').val();
    var crclm_id = $('#crclm_name_id').val();
    var term_id = $('#term_id_val').val();
    
    var post_data = {'crclm_id':crclm_id,
                     'term_id':term_id,
                    'dept_id':dept_id};
    $.ajax({type: "POST",
        url: base_url + 'curriculum/import_curriculum/populate_course_dropdown',
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
                $('#place_course_dropdown').empty();
                $('#place_course_dropdown').html(msg)
        }
    });
    
    
});


$('#place_course_dropdown').on('change','#course_id_val',function(){
   
    var crclm_id = $('#crclm_name_id').val();
    var term_id = $('#term_id_val').val();
    var course_id = $('#course_id_val').val();
    if(course_id != ''){
//    var post_data = {'crclm_id':crclm_id,
//                     'term_id':term_id};
    $.ajax({type: "POST",
        url: base_url + 'curriculum/import_curriculum/populate_course_entity',
        //data: post_data,
        datatype: "JSON",
        success: function (msg) {
                $('#place_co_entity_list').empty();
                $('#place_co_entity_list').html(msg)
        }
    });
    }
    
    
});
var entity_id_array = new Array();// global Array
$('#place_co_entity_list').on('click','.course_entity', function(){
    if($(this).is(':checked')){
        entity_id_array.push($(this).val());
        $('#entity_ids').val(entity_id_array);
        var crs_id = $('#entity_ids').val();
        if (crs_id != '') {
            $('#courses_entity_level_import').attr('disabled', false);
        } else {
            $('#courses_entity_level_import').attr('disabled', true);
        }
        
    }else{
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

$('#place_co_entity_list').on('click','.clo_enity_check_all', function(){
    if($(this).is(':checked')){
        $('.course_entity').each(function(){
            $(this).attr('checked',true);
            entity_id_array.push($(this).val());
        });
        $('#entity_ids').val(entity_id_array);
        var crs_id = $('#entity_ids').val();
        if (crs_id != '') {
            $('#courses_entity_level_import').attr('disabled', false);
        } else {
            $('#courses_entity_level_import').attr('disabled', true);
        }
        
    }else{
        $('.course_entity').each(function(){
            $(this).attr('checked',false);
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


//$('#place_co_entity_list').on('click','.topic_check', function(){
//    if($(this).is(':checked')){
//        $('.co_check').attr('checked',true)
//            entity_id_array.push($('.co_check').val());
//            $('#entity_ids').val(entity_id_array);
//    }else{
//        
//    }
//});


$('#place_co_entity_list').on('click','.tlo_check', function(){
    if($(this).is(':checked')){
        
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
            if(msg !=0){
                $('#modal_tlo_existence_alert').modal({dynamic:true});
            }
        }
    });
        
        
       // $('.co_check').attr('checked',true)
        $('.topic_check').attr('checked',true)
        //entity_id_array.push($('.co_check').val());
        entity_id_array.push($('.topic_check').val());
        $('#entity_ids').val(entity_id_array);
        
    }else{
        
    }
});

$('#place_co_entity_list').on('click','.topic_check', function(){
    if($(this).is(':checked')){
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
            if(msg !=0){
                $('#modal_topic_existence_alert').modal({dynamic:true});
            }
        }
    });
    }else{
         $('.tlo_check').attr('checked',false);
         var val = '12';
         var index = $.inArray(val, entity_id_array);
         //alert(index);
         if(index != -1){
            entity_id_array.splice(index, 1);
        }
            $('#entity_ids').val(entity_id_array);
            var crs_id = $('#entity_ids').val();
        if (crs_id != '') {
            $('#courses_entity_level_import').attr('disabled', false);
        } else {
            $('#courses_entity_level_import').attr('disabled', true);
        }
    }
});

$('#place_co_entity_list').on('click','.co_check', function(){
    if($(this).is(':checked')){
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
            if(msg !=0){
                $('#modal_co_existence_alert').modal({dynamic:true});
            }
        }
    });
    
    }else{
         $('.course_entity').each(function(){
            $(this).attr('checked',false);
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


$('#courses_entity_level_import').on('click',function(){
    var course_entity_array = new Array();
    var entity_id;
    var to_crclm_id = $('#import_crclm_id').val();
    var to_term_id = $('#import_term_id').val();
    var to_course_id = $('#import_course_id').val();
    var from_crclm_id = $('#crclm_name_id').val();
    var from_term_id = $('#term_id_val').val();
    var from_course_id = $('#course_id_val').val();
    
    $('.course_entity').each(function(){
            if($(this).is(':checked')){
                entity_id = $(this).val();
                course_entity_array.push(entity_id); // creating array of course id (selected courses)
            }else{
                
            }
        });
    var post_data = {
        'to_crclm_id': to_crclm_id,
        'to_term_id': to_term_id,
        'to_course_id': to_course_id,
        'from_crclm_id': from_crclm_id,
        'from_term_id': from_term_id,
        'from_course_id': from_course_id,
        'course_entity_ids': course_entity_array
    };
    
    $('#loading').show();
    $.ajax({type: "POST",
        url: base_url + 'curriculum/import_curriculum/course_entity_import_insert',
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
            if(msg == 1){
                
               location.reload();
            }
//            $('#place_term_dropdown').empty();
//            $('#place_term_dropdown').html(msg);
        }
    });
});