// Static View JS functions.
$.fn.modal.Constructor.prototype.enforceFocus = function () {};
var base_url = $('#get_base_url').val();
$("#hint a").tooltip();
var crs_id;
var table_row;
var golbal;
$('#add_crs_domain').hide();
/* Function is used to fetch course details.
 * @param - curriculum id & term id.
 * @returns- an array of course details.
 */
 
 var vw_page_type = $('#vw_page_type').val();
  flag = (vw_page_type == 'add_page') ?  	default_load_weightage() :  default_edit_page();

	function default_load_weightage(){
		var length = $('input.mte_flag_check_box:checked').length; 
		var percent = length  > 0 ? (100) / length : "00.00";
		if(length  > 0 ){percent =  percent.toFixed(2);}
		var total_percent = 0;
		$( ".total_wt:enabled" ).val(percent);
		$(".total_wt").each(function() {
			total_percent +=  $( ".total_wt:enabled" ) ? (parseFloat(this.value)) : "00.00";
		});
		$('#total_weightage').val(Math.round(total_percent));
		
	}
		
	
	function default_edit_page(){

		var length = $('input.mte_flag_check_box:checked').length; 
		$('#weightage_status').val(length);
		    $('.mte_flag_check_box').each(function () {
			
				switch($(this).attr('id')){
				case 'cia_check' :  id ="#total_cia_weightage"; check_id = "#cia_check";
				break;
				case 'mte_check' :  id ="#total_mte_weightage"; check_id = "#mte_check";
				break;	
				case 'tee_check' :  id ="#total_tee_weightage"; check_id = "#tee_check";
				break;
				default : console.log("");
			
				}
		
				if ($(this).is(':checked')) {
					$(id).attr('disabled' , false);
					
				} else {
					$(id).attr('disabled' , true);
					
				}
			});	
		var total_percent = 0;
		$(".total_wt").each(function() {
			total_percent +=  $( ".total_wt:enabled" ) ? (parseFloat(this.value)) : "00.00";
		});
		
		$('#total_weightage').val(Math.round(total_percent));
	}
	
$('.mte_flag_check_box').on('change' , function(){

	var length = $('input.mte_flag_check_box:checked').length; 
	var percent = length  > 0 ? (100) / length : "00.00";
	var switch_id = $(this).attr('id');
	if(length  > 0 ){percent =  percent.toFixed(2);}
	switch(switch_id){
		case 'cia_check' :  id ="#total_cia_weightage"; check_id = "#cia_check";
		break;
		case 'mte_check' :  id ="#total_mte_weightage"; check_id = "#mte_check";
		break;	
		case 'tee_check' :  id ="#total_tee_weightage"; check_id = "#tee_check";
		break;
		default : console.log("");
	
	} 
	
	if(length  > 0){	
		$('.edit_check_error').html('');
		flag = $('#'+switch_id).prop('checked')  ? ( $(id).prop('disabled' , false) , $(check_id).val(1),  $( ".total_wt:disabled" ).val('00.00') , $( ".total_wt:enabled" ).val(percent)) : ( (length  > 0) ? ($(id).prop('disabled' , true) , $( ".total_wt:enabled" ).val(percent) ,   $(check_id).val(0) , $( ".total_wt:disabled" ).val('00.00') ) : ($(id).prop('disabled' , false)) );
	 }else{		
		  $(check_id).attr("checked",true);
			$('.edit_check_error').html('<span> You can not uncheck . Atleast one weightage is  required. </span>');
	} 
});
 
 
function static_get_selected_value()
{
    var data_val = document.getElementById('crclm_id').value;
    var data_val1 = document.getElementById('term').value;
    var post_data = {
	'crclm_id': data_val,
	'crclm_term_id': data_val1,
    }
    $.ajax({type: "POST",
	url: base_url + 'curriculum/course/show_course',
	data: post_data,
	dataType: 'json',
	success: static_populate_table
    });
}

$('#crclm_id').on('change', function () {
    var data_val = document.getElementById('crclm_id').value;
    $('#add_crs_domain').show();
    if(data_val == ''){
	$('#add_crs_domain').hide();
    }
});
/* Function is used to generate table grid of  course details.
 * @param - 
 * @returns- an array of course details.
 */
function static_populate_table(msg) {
    var m = 'd';
    $('#example').dataTable().fnDestroy();
    $('#example').dataTable(
	    {"aoColumns": [
		    {"sTitle": "Sl No.", "mData": "sl_no", "sClass": "alignRight"},
		    {"sTitle": "Code", "mData": "crs_code"},
		    {"sTitle": "Course Title", "mData": "crs_title"},
		    {"sTitle": "Core / Elective", "mData": "crs_type_name"},
		    {"sTitle": "L", "mData": "lect_credits", "sClass": "alignRight"},
		    {"sTitle": "T", "mData": "tutorial_credits", "sClass": "alignRight"},
		    {"sTitle": "P", "mData": "practical_credits", "sClass": "alignRight"},
		    {"sTitle": "SS", "mData": "self_study_credits", "sClass": "alignRight"},
		    {"sTitle": credits, "mData": "total_credits", "sClass": "alignRight"},
		    {"sTitle": entity_cie + " Marks", "mData": "cie_marks"},
		    {"sTitle": "TEE Marks", "mData": "see_marks"},
		    {"sTitle": "Total Marks", "mData": "total_marks"},
		    {"sTitle": course_owner, "mData": "username"},
		    {"sTitle": "Course Reviewer", "mData": "reviewer"},
		    {"sTitle": "Mode", "mData": "crs_mode"},
		    {"sTitle": "TEE Duration (hours)", "mData": "see_duration"}
		], "aaData": msg,
		"sPaginationType": "bootstrap"});
}

/* Function is used to fetch term ids & term names by sending the curriculum id to controller.
 * @param - curriculum id.
 * @returns- an array of term ids & term names.
 */
if ($.cookie('remember_selected_curriculum_value') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#curriculum option[value="' + $.cookie('remember_selected_curriculum_value') + '"]').prop('selected', true);
    select_termlist();
}
function select_termlist() {

    $.cookie('remember_selected_curriculum_value', $('#curriculum option:selected').val(), {expires: 90, path: '/'});
    var data_val = document.getElementById('curriculum').value;
    var post_data = {
	'crclm_id': data_val
    }
    $.ajax({type: "POST",
	url: base_url + 'curriculum/course/select_termlist',
	data: post_data,
	success: function (msg) {

	    document.getElementById('term').innerHTML = msg;
	    if ($.cookie('remember_selected_term_value') != null) {
		// set the option to selected that corresponds to what the cookie is set to
		$('#term option[value="' + $.cookie('remember_selected_term_value') + '"]').prop('selected', true);
		GetSelectedValue();
	    }
	}
    });
}

/* Function is used to fetch course details.
 * @param - curriculum id & term id.
 * @returns- an array of course details.
 */
function GetSelectedValue()
{
    $.cookie('remember_selected_term_value', $('#term option:selected').val(), {expires: 90, path: '/'});
    var data_val = document.getElementById('curriculum').value;
    var data_val1 = document.getElementById('term').value;
    var post_data = {
	'crclm_id': data_val,
	'crclm_term_id': data_val1,
    }
    $.ajax({type: "POST",
	url: base_url + 'curriculum/course/show_course',
	data: post_data,
	dataType: 'json',
	success: populate_table
    });
}


// List View JS functions.
$('.get_crs_id').live("click", function () {
    crs_id = $(this).attr('id');
    table_row = $(this).closest("tr").get(0);
});

$('.bloom_option_mandatory').live('click' , function(){
	$('#clo_bl_flag_data').val($(this).attr('data-clo_bl_flag'));	
	$('#crs_id_bloom').val($(this).attr('id')); 
	var confirmation_msg = $(this).attr('data-confirmation_msg');
	$('.confirmation_msg').html(confirmation_msg);

	
	$('#bloom_modal').modal('show');
	
	
});
$('.bloom_option_mandatory_error').live('click' , function(){
	$('#myModal_mandatory_error').modal('show');	
});

function bloom_mandatory_optional(){
var clo_bl_flag  = 	$('#clo_bl_flag_data').val();
var crs_id  = $('#crs_id_bloom').val();
 var post_data = {'clo_bl_flag' :  clo_bl_flag , 'crs_id' : crs_id}
		$.ajax({type: "POST",
		url: base_url + 'curriculum/course/bloom_option_mandatory',
		data: post_data,
		datatype: "JSON",
		success: function (msg) {
			location.reload();
		}
		});  

}

/* Function is used to delete course by sending the course id to controller.
 * @param - course id.
 * @returns- updated course list view.
 */
function delete_course()
{
    $('#loading').show();
    $.ajax({type: "POST",
	url: base_url + 'curriculum/course/course_delete' + '/' + crs_id,
	success: function (msg) {
	    var oTable = $('#example').dataTable();
	    oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
	    $('#loading').hide();
	}
    });
}


$('.check_weightage').live('click',function(){
	
	var length = $(this).attr('data-weightage');	
	if(length > 0) {
		$('#myModal3').modal('show');
	}else{
		$('#weightage_warning').modal('show');
	}
	
});

/* Function is used to initiate the CLO creation process by sending the course id to controller.
 * @param - course id.
 * @returns- an updated list view of course.
 */
//new publish code
function publish_course()
{
    $('#loading').show();
    var post_data = {
	'crs_id': crs_id,
    }
	
	    $.ajax({type: "POST",
		url: base_url + 'curriculum/course/publish_course' + '/' + crs_id,
		data: post_data,
		datatype: "JSON",
		success: function (msg) {
			location.reload();
		}
		}); 
	
}

/* Class is used to remove a course form the table grid of course list.
 * @param - tr(row).
 * @returns- updated course table grid.
 */
$('.delbutton').click(function (e) {
    e.preventDefault();
    var oTable = $('#example').dataTable();
    var row = $(this).closest("tr").get(0);
    oTable.fnDeleteRow(oTable.fnGetPosition(row));
});

/* Function is used to generate table grid of  course details.
 * @param - 
 * @returns- an array of course details.
 */
function populate_table(msg) {
    var m = 'd';
    $('#example').dataTable().fnDestroy();
    $('#example').dataTable(
	    {"aoColumns": [
		    {"sTitle": "Sl No.", "mData": "sl_no", "sClass": "alignRight"},
		    {"sTitle": "Code", "mData": "crs_code"},
		    {"sTitle": "Course Title", "mData": "crs_title"},
		    {"sTitle": "Core / Elective", "mData": "crs_type_name"},
		    /*{ "sTitle": "L", "mData": "lect_credits", "sClass": "alignRight"},
		    {"sTitle": "T", "mData": "tutorial_credits", "sClass": "alignRight"},
		    {"sTitle": "P", "mData": "practical_credits", "sClass": "alignRight"},
		    {"sTitle": "SS", "mData": "self_study_credits", "sClass": "alignRight"}, */
		    {"sTitle": credits, "mData": "total_credits", "sClass": "alignRight"},
		    {"sTitle": "Total Marks", "mData": "total_marks", "sClass": "alignRight"},
		    {"sTitle": course_owner, "mData": "username"},
		    {"sTitle": "Course Reviewer", "mData": "reviewer"},
		    {"sTitle": "Mode", "mData": "crs_mode"},
		    {"sTitle": "Section-wise <br/> Course Instructor", "mData": "mng_section"},
		    {"sTitle": "Edit", "mData": "crs_id_edit"},
		    {"sTitle": "Delete", "mData": "crs_id_delete"},
			{"sTitle": "Course - COs to Bloom's Level Mapping Status", "mData": "bloom_option_mandatory"},
		    {"sTitle": "CO Creation", "mData": "publish"},
			
		], "aaData": msg,
		"sPaginationType": "bootstrap"});
}

// Common JS functions in Add & Edit	

$(function () {
    $('.datepicker').datepicker({
	dateFormat: 'yy-mm-dd',
	showOn: "button",
	buttonImage: base_url + "/twitterbootstrap/img/calendar.gif",
	buttonImageOnly: true
    });
});

/* Function is used to compute the total credits of a course.
 * @param - 
 * @returns- an integer value.
 */
function total()
{
    var data_val1 = document.getElementById('lect_credits').value;
    var data_val2 = document.getElementById('tutorial_credits').value;
    var data_val3 = document.getElementById('practical_credits').value;
   // var data_val4 = document.getElementById('self_study_credits').value;
    //term work or CIE marks or CIA marks
    var data_val5 = document.getElementById('cie_marks').value;

    //total theory marks or SEE or TEE
    var data_val6 = document.getElementById('see_marks').value;
    //self study or PR/OR marks
   var data_val7 = document.getElementById('mid_term_marks').value;

    var total = parseFloat(data_val1) + parseFloat(data_val2) + parseFloat(data_val3);// + parseFloat(data_val4);
    var total_marks = parseFloat(data_val5) + parseFloat(data_val6) + parseFloat(data_val7);
    document.getElementById('total_credits').value = total;
    document.getElementById('total_marks').value = total_marks;
}

/*unction is used to set the default values to the various credits fields depending upon  radio buttons 
 of  Theory ,Lab with Theory and Lab
 Added by Bhagya S S
 ***/
$('.radio_button').on('click', function () {

    if ($('#toggleElement1').is(':checked')) {
	$('#lect_credits').attr('disabled', false);
	$('#tutorial_credits').attr('disabled', false);
	//$('#self_study_credits').attr('disabled', false);
	$('#practical_credits').attr('disabled', false);
	document.getElementById('crs_mode').value = 1;
	document.getElementById('lect_credits').value = 0;
	document.getElementById('tutorial_credits').value = 0;
	document.getElementById('practical_credits').value = 0;
	//document.getElementById('self_study_credits').value = 0;
	document.getElementById('mid_term_marks').value = 0;
	var x = document.getElementById('lect_credits').value;
	var y = document.getElementById('tutorial_credits').value;
	var z = document.getElementById('practical_credits').value;
//	var p = document.getElementById('self_study_credits').value;
	var total_sum = parseFloat(x) + parseFloat(y) + parseFloat(z);//+ parseFloat(p);
	document.getElementById('total_credits').value = total_sum;

    }
    if ($('#toggleElement0').is(':checked')) {
	$('#lect_credits').attr('disabled', false);
	$('#tutorial_credits').attr('disabled', false);
//$('#self_study_credits').attr('disabled', false);
	$('#practical_credits').attr('disabled', false);
	document.getElementById('crs_mode').value = 0;
	document.getElementById('practical_credits').value = 0;
	var a = document.getElementById('lect_credits').value;
	var b = document.getElementById('tutorial_credits').value;
	var c = document.getElementById('practical_credits').value;
//	var d = document.getElementById('self_study_credits').value;
	var total = parseFloat(a) + parseFloat(b) + parseFloat(c) ;//+ parseFloat(d);
	document.getElementById('total_credits').value = total;
    }
    if ($('#toggleElement2').is(':checked')) {

	document.getElementById('crs_mode').value = 2;

    }

});

/* Class is used to display the course names by fetching the in-putted live string from prerequisite course tab.
 * @param- live data (live search data)
 * @returns - an object.
 */
$(function () {
    $('#fetch_prerequisite_courses').typeahead({
	source: function (typeahead, query) {
	    $.ajax({
		url: base_url + 'curriculum/course/course_name',
		type: "POST",
		data: "query=test",
		dataType: "JSON",
		async: false,
		success: function (data) {
		    typeahead.process(data);
		}
	    });
	},
	property: 'crs_title',
	items: 8,
	onselect: function (obj) {
	}
    });
});

/* Function is to send the department id to controller for fetching users list of that department
 * @param - department id
 */
function select_course_reviewer_dept() {
    $('#loading').show();
    var data_val = document.getElementById('dept_id').value;
    var post_data = {
	'dept_id': data_val
    }
    $.ajax({
	type: "POST",
	url: base_url + 'curriculum/course/reviewer_list',
	data: post_data,
	success: function (msg) {
	    document.getElementById('course_reviewer').innerHTML = msg;
	    $('#loading').hide();
	}
    });
}

/* Function is used to set the default values to the various credits fields.
 * @param - 
 * @returns- 
 */
function addCourseToggleStatus() {
    $('#lect_credits').attr('disabled', false);
    $('#tutorial_credits').attr('disabled', false);
    $('#practical_credits').attr('disabled', false);
    $('#self_study_credits').attr('disabled', false);
    document.getElementById('practical_credits').value = 0;
    document.getElementById('lect_credits').value = 0;
    document.getElementById('tutorial_credits').value = 0;
   // document.getElementById('self_study_credits').value = 0;
    document.getElementById('total_credits').value = 0;
    document.getElementById('crs_mode').value = 0;
    document.getElementById('contact_hours').value = 0;
    document.getElementById('cie_marks').value = 00;
    document.getElementById('see_marks').value = 00;
  //  document.getElementById('ss_marks').value = 00;
    document.getElementById('total_marks').value = 00;
    document.getElementById('see_duration').value = 0;
    document.getElementById('mid_term_marks').value = 00;
}

/* Function is used to fetch term names.
 * @param- curriculum id
 * @returns - an html data (drop-down).
 */
function callAndUpdateTermDetails(value) {
    $.get(
	    base_url + 'curriculum/course/term_details_by_crclm_id' + '/' + value,
	    null,
	    function (data) {
		var arrayData = JSON.parse(data);
		$("#crclm_term_id > option").remove();
		var i = 0;
		if (arrayData != '')
		    var completeOptions = '<option value="">Select Term</option>';
		else
		    var completeOptions = '<option value=""></option>';
		for (i = 0; i < arrayData.length; i++) {
		    var item = arrayData[i];
		    completeOptions += '<option value="' + item.crclm_term_id + '">' + item.term_name + '</option>';
		}
		$('#crclm_term_id').html(completeOptions);
	    },
	    "html"
	    );
}

$('.crclm').change(function () {
    var value = $(this).val();
    callAndUpdateTermDetails(value);
});

/* Function is used to fetch course details.
 * @param- curriculum id
 * @returns - an html data.
 */
function callAndUpdateCourseDetails(value) {
    $.get(
	    base_url + 'curriculum/course/course_details_by_crclm_id' + '/' + value,
	    null,
	    function (data) {
		var arrayData = JSON.parse(data);
	    },
	    "html"
	    );
}

/* Function is used to fetch curriculum, PEO, PO & Term-wise Course details.
 * @param- curriculum id
 * @returns - an html table data.
 */
function GetCurriculumValue(value) {
    var data_val1 = $('#crclm_id').val();//put this id to curriculum drop-down
    var post_data = {
	'crclm_id': data_val1,
    }
    $.ajax({
	type: "POST",
	url: base_url + 'curriculum/course/display_crclm_details', //put function url here
	data: post_data,
	success: function (msg) {
	    document.getElementById('fetch_curriculum_details').innerHTML = msg;
	}
    });
    $.ajax({
	type: "POST",
	url: base_url + 'curriculum/course/display_peo_details', //put function url here
	data: post_data,
	success: function (msg) {
	    document.getElementById('fetch_peo_details').innerHTML = msg;
	}
    });
    $.ajax({
	type: "POST",
	url: base_url + 'curriculum/course/display_po_details', //put function url here
	data: post_data,
	success: function (msg) {
	    document.getElementById('fetch_po_details').innerHTML = msg;
	}
    });
    $.ajax({
	type: "POST",
	url: base_url + 'curriculum/course/display_course_details', //put function url here
	data: post_data,
	success: function (msg) {
	    document.getElementById('fetch_termwise_course_details').innerHTML = msg;
	}
    });
    $.ajax({
	type: "POST",
	url: base_url + 'curriculum/course/select_term',
	data: post_data,
	success: function (msg) {
	    document.getElementById('crclm_term_id').innerHTML = msg;
	}
    });
    $.ajax({
	type: "POST",
	url: base_url + 'curriculum/course/select_course_type',
	data: post_data,
	success: function (msg) {
	    document.getElementById('crs_type_id').innerHTML = msg;
	}
    });
    $.ajax({
	type: "POST",
	url: base_url + 'curriculum/course/designer_list',
	data: post_data,
	success: function (msg) {
	    document.getElementById('course_designer').innerHTML = msg;
	}
    });
}

/* Class is used to fetch curriculum, PEO, PO & Term-wise Course details.
 * @param- curriculum id
 * @returns - an html table data.
 */
$('.target1').change(function () {
    var value = $(this).val();
    $('#loading').show();
    golbal = value;
    GetCurriculumValue(value);
    $('#loading').hide();
});


$.validator.addMethod("noSpecialChars", function (value, element) {
    return this.optional(element) || /^[a-zA-Z\_\-\s\'\ / \ + \&\,\#]+$/i.test(value);
}, "This field must contain only letters space and underscore.");

$.validator.addMethod("noSpecialChars2", function (value, element) {
    return this.optional(element) || /^[a-zA-Z 0-9\\_]+$/i.test(value);
}, "This field must contain only letters, numbers and underscores.");

$.validator.addMethod("onlyDigit", function (value, element) {
    return this.optional(element) || /^[0-9\.\_]+$/i.test(value);
}, "This field must contain only Numbers.");

$.validator.addMethod("percentage", function (value, element) {
    return this.optional(element) || /^(100)$/.test(value);
}, "Total weightage should be 100%.");

// Form validation rules are defined & checked before form is submitted to controller.
$("#add_form_id").validate({
    rules: {
	crclm_id: {
	    maxlength: 20,
	    required: true
	},
	crclm_term_id: {
	    maxlength: 20,
	    required: true
	},
	crs_type_id: {
	    maxlength: 10,
	    required: true
	},
	crs_domain_id: {
	    maxlength: 10,
	    required: true
	},
	crs_code: {
	    maxlength: 20,
	    noSpecialChars2: true
	},
	crs_title: {
	    maxlength: 100
	},
	crs_acronym: {
	    maxlength: 10
	},
	clo_owner_id: {
	    maxlength: 20,
	    required: true
	},
	course_designer: {
	    maxlength: 3,
	    required: true
	},
	course_reviewer: {
	    maxlength: 3,
	    required: true
	},
	dept_id: {
	    maxlength: 3,
	    required: true
	},
	lect_credits: {
	    maxlength: 3,
	    required: true
	},
	tutorial_credits: {
	    maxlength: 3,
	    required: true
	},
	practical_credits: {
	    maxlength: 3,
	    required: true
	},
	self_study_credits: {
	    maxlength: 3,
	    required: true
	},
	contact_hours: {
	    maxlength: 3,
	    required: true
	},
	cie_marks: {
	    maxlength: 3,
	    required: true
	},
	mid_term_marks: {
	    maxlength: 3,
	    required: true
	},
	see_marks: {
	    maxlength: 3,
	    required: true
	},
	ss_marks: {
	    maxlength: 4,
	    required: true
	},
	see_duration: {
	    maxlength: 1,
	    required: true
	},
	review_dept: {
	    maxlength: 20,
	    required: true
	},
	fetch_prerequisite_courses: {
	    maxlength: 20,
	    required: true
	},
	last_date: {
	    maxlength: 20,
	    required: true
	},
	datepicker: {
	    "date": "2012-09-01T00:00:00.000Z"
	},
	total_cia_weightage: {
	    max: 100,
	},
	total_tee_weightage: {
	    max: 100,
	},
	total_mte_weightage: {
	    max: 100,
	},
	total_weightage:{
		percentage:true,
	},
    },
    errorClass: "help-inline font_color",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
	$(element).parent().parent().addClass('error');
    },
    errorPlacement: function (error, element) {
	if (element.next().is('.date_error')) {
	    error.insertAfter(element.parent());
	} else if (element.next().is('.date_error')) {
	    error.insertAfter(element.parent());
	} else if (element.next().is('.date_error')) {
	    error.insertAfter(element.parent());
	} else {
	    error.insertAfter(element);
	}
    },
    unhighlight: function (element, errorClass, validClass) {
	$(element).parent().parent().removeClass('error');
	$(element).parent().parent().addClass('success');
    }
});

// Form validation rules are defined & checked before form is submitted to controller.
$("#section_form").validate({
//    rules: {
//	section: {
//	    required: true,
//	},
//	course_instructor_name: {
//	    required: true,
//	},
//    },
//    errorClass: "help-inline font_color",
//    errorElement: "span",
//   
//    
});

// Class is used to fetch predecessor course ids of deleted predecessor courses.
$('.delete_course').live('click', '.bs-docs-example', function () {
    var delete_id = $(this).parent().attr('id');
    var delet_input = '<input type="hidden" name="delete_crs_id[]" value="' + delete_id + '" />';
    $('#delete_crs_id').append(delet_input);
    $(this).parent().remove();
    return false;
});


//Function to add toal weightage of cia and tee			Added by bhagya
$('.total_wt , .mte_flag_check_box').on('keyup change', function () {   
	var total_percent = 0 ;
	    var num = parseFloat($(this).val());
		var cleanNum = num.toFixed(2); 
			if(num/cleanNum > 1){ $('.edit_check_error').text('Only 2 decimal places are allowed.');} 
		$(".total_wt").each(function() {
		if(this.value != ""){ val = this.value;} else{ 	val = 0;}
			total_percent +=  $( ".total_wt:enabled" ) ? (parseFloat(val)) : "00.00";

		$('#total_weightage').val(total_percent);
});
});
//Function to call the uniqueness checks for course code & course title.
$('.add_form_submit_id').on('click', function (e) {
    e.preventDefault();
    var flag , fetch_clo_bl_flag_val;
    flag = $('#add_form_id').valid();
    fetch_clo_bl_flag_val = $('#fetch_clo_bl_flag_val').val(); 
    flag1 = 0;    
    if(fetch_clo_bl_flag_val == '1'){
        console.log("");
        if($(".bloom_leves:checkbox:checked").length > 0){   console.log("");                
            $('.error_span1').html(" ");flag1 = 1;
        }else{            
            $('.error_span1').html("This field is required.");

        }
        
    }else{
        $('.error_span1').html(" ");
        flag1 = 1;
    }
 
    if ($('#bloom_domain_1').is(':checked')) {
	$("#bld_1").val(1);
    } else {
	$("#bld_1").val("");
    }
    if ($('#bloom_domain_2').is(':checked')) {
	$("#bld_2").val(1);
    } else {
	$("#bld_2").val("");
    }
    if ($('#bloom_domain_3').is(':checked')) {
	$("#bld_3").val(1);
    } else {
	$("#bld_3").val("");
    }
    var data_val = document.getElementById('crclm_id').value;
    var data_val2 = document.getElementById("crs_code").value;
    var data_val1 = document.getElementById("crs_title").value;
    $('#loading').show();
    var post_data = {
	'crclm_id': data_val,
	'crs_title': data_val1,
	'crs_code': data_val2
    };
    if (flag === true && flag1 == 1) {            
	$.ajax({
	    type: "POST",
	    url: base_url + 'curriculum/course/course_title_search',
	    data: post_data,
	    datatype: "JSON",
	    success: function (msg) {
		if ($.trim(msg) == 'valid')
		{
			//if($('#total_weightage').val() == 100){                    
			publish_course();
		    $("#add_form_id").submit();
		    $('#loading').hide();
	/* 		}
			else{
				$('#loading').hide();
					$('.edit_date_error').html("<span color='red'> Please make value less than or equal to 100 </span>");
				} */
			
		} else
		{
		    $('#add_warning_dialog').modal('show');
		    $('#loading').hide();
		}
	    }
	});
    } else {
	$('#loading').hide();             
    }
});
var date = new Date();
date.setDate(date.getDate() - 1);
$("#last_date").datepicker({
    format: "yyyy-mm-dd",
    // startDate: date

}).on('changeDate', function (ev) {
    $(this).blur();
    $(this).datepicker('hide');
});

$('#btn').click(function () {
    $(document).ready(function () {
	$("#last_date").datepicker().focus();
    });
});

// Edit View JS functions.

/* Function is used to fetch the term & users details.
 * @param - curriculum id.
 * @returns- a array of values of the term & users details.
 */
function select_term() {
    var data_val = document.getElementById('crclm_id').value;
    var data_val2 = document.getElementById('crs_id').value;
    var post_data = {
	'crclm_id': data_val
    }
    $.ajax({type: "POST",
	url: base_url + 'curriculum/course/select_term',
	data: post_data,
	success: function (msg) {
	    document.getElementById('crclm_term_id').innerHTML = msg;
	}
    });
    $.ajax({
	type: "POST",
	url: base_url + 'curriculum/course/select_course_type',
	data: post_data,
	success: function (msg) {
	    document.getElementById('crs_type_id').innerHTML = msg;
	}
    });
    $.ajax({type: "POST",
	url: base_url + 'curriculum/course/designer_list',
	data: post_data,
	success: function (msg) {
	    document.getElementById('clo_owner_id').innerHTML = msg;
	}
    });
}

/* Function is used to fetch the term & users details.
 * @param - curriculum id.
 * @returns- a array of values of the term & users details.
 */
function select_term() {
    $('#loading').show();
    var data_val = document.getElementById('crclm_id').value;
    var data_val2 = document.getElementById('crs_id').value;
    var post_data = {
	'crclm_id': data_val
    }
    $.ajax({type: "POST",
	url: base_url + 'curriculum/course/select_term',
	data: post_data,
	success: function (msg) {
	    document.getElementById('crclm_term_id').innerHTML = msg;
	}
    });
    $.ajax({
	type: "POST",
	url: base_url + 'curriculum/course/select_course_type',
	data: post_data,
	success: function (msg) {
	    document.getElementById('crs_type_id').innerHTML = msg;
	}
    });
    $.ajax({type: "POST",
	url: base_url + 'curriculum/course/designer_list',
	data: post_data,
	success: function (msg) {
	    document.getElementById('clo_owner_id').innerHTML = msg;
	}
    });
    $('#loading').hide();
}

/* Function is used to fetch the users details.
 * @param - department id.
 * @returns- a array of values of the users details.
 */
function select_validator_dept() {
    $('#loading').show();
    var data_val = document.getElementById('dept_id').value;
    var post_data = {
	'dept_id': data_val
    }
    $.ajax({type: "POST",
	url: base_url + 'curriculum/course/reviewer_list',
	data: post_data,
	success: function (msg) {
	    document.getElementById('validator_id').innerHTML = msg;
	    $('#loading').hide();
	}
    });
}

// Function is used to set the default values to the various credits fields.
function editCourseToggleStatus() {
    if (document.getElementById('crs_mode').value == 1) {
	$('#toggleElement1').attr('checked', true);
	$('#lect_credits').attr('disabled', false);
	$('#tutorial_credits').attr('disabled', false);
	$('#self_study_credits').attr('disabled', false);
	$('#practical_credits').attr('disabled', false);
    } else if (document.getElementById('crs_mode').value == 0) {
	$('#toggleElement0').attr('checked', true);
	document.getElementById('crs_mode').value = 0;
	$('#lect_credits').attr('disabled', false);
	$('#tutorial_credits').attr('disabled', false);
	$('#self_study_credits').attr('disabled', false);
	$('#practical_credits').attr('disabled', false);
    } else {
	$('#toggleElement2').attr('checked', true);
    } // added by bhagya
}

$.validator.addMethod("noSpecialChars", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\_\s]+$/i.test(value);
}, "This field must contain only letters space and underscore.");

$.validator.addMethod("noSpecialChars2", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\_]+$/i.test(value);
}, "This field must contain only letters, numbers and underscores.");

$.validator.addMethod("onlyDigit", function (value, element) {
    return this.optional(element) || /^[0-9\.\_]+$/i.test(value);
}, "This field must contain only Numbers.");

$.validator.addMethod("credits_validation", function () {
    var cia_wt = $('#total_cia_weightage').val();
    var tee_wt = $('#total_tee_weightage').val();
    return ((parseInt(cia_wt) + parseInt(tee_wt)) <= 100);

}, "Please make value less than or equal to 100.");

/**Calling the modal on success**/
function success_modal(msg) {
    var data_options = '{"text":"Your data has been saved successfully","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}


// Form validation rules are defined & checked before form is submitted to controller.
$("#edit_form_id").validate({
    rules: {
	crclm_id: {
	    maxlength: 20,
	    required: true
	},
	crclm_term_id: {
	    maxlength: 20,
	    required: true
	},
	crs_type_id: {
	    maxlength: 10,
	    required: true
	},
	crs_domain_id: {
	    maxlength: 10,
	    required: true
	},
	crs_code: {
	    maxlength: 20,
	    noSpecialChars2: true
	},
	crs_title: {
	    maxlength: 100
	},
	crs_acronym: {
	    maxlength: 10,
	},
	clo_owner_id: {
	    maxlength: 20,
	    required: true
	},
	course_designer: {
	    maxlength: 3,
	    required: true
	},
	validator_id: {
	    maxlength: 3,
	    required: true
	},
	dept_id: {
	    maxlength: 3,
	    required: true
	},
	lect_credits: {
	    maxlength: 3,
	    required: true
	},
	tutorial_credits: {
	    maxlength: 3,
	    required: true
	},
	practical_credits: {
	    maxlength: 3,
	    required: true
	},
	self_study_credits: {
	    maxlength: 3,
	    required: true
	},
	contact_hours: {
	    maxlength: 3,
	    required: true
	},
	cie_marks: {
	    maxlength: 3,
	    required: true
	},
	mid_term_marks: {
	    maxlength: 3,
	    required: true
	},
	see_marks: {
	    maxlength: 3,
	    required: true
	},
	ss_marks: {
	    maxlength: 4,
	    required: true
	},
	see_duration: {
	    maxlength: 1,
	    required: true
	},
	review_dept: {
	    maxlength: 20,
	    required: true
	},
	fetch_prerequisite_courses: {
	    maxlength: 20,
	    required: true
	},
	last_date: {
	    maxlength: 20,
	    required: true
	},
	total_tee_weightage: {
	    max: 100,
	},
	total_cia_weightage: {
	    max: 100,
	},	
	total_mte_weightage: {
	    max: 100,
	},
	total_weightage: {
	    percentage: true,
	    credits_validation: false
	}
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
	$(element).parent().parent().addClass('error');
    },
    errorPlacement: function (error, element) {
	if (element.next().is('.edit_date_error')) {
	    error.insertAfter(element.parent());
	} else if (element.next().is('.edit_date_error')) {
	    error.insertAfter(element.parent());
	} else if (element.next().is('.edit_date_error')) {
	    error.insertAfter(element.parent());
	} else {
	    error.insertAfter(element);
	}
    },
    unhighlight: function (element, errorClass, validClass) {
	$(element).parent().parent().removeClass('error');
	$(element).parent().parent().addClass('success');
    }
});

//Function to call the uniqueness checks for course code & course title.		
$('.edit_form_submit_id').on('click', function (e) {
    e.preventDefault();
    $('#loading').show();
    var flag;
    flag = $('#edit_form_id').valid();
    var data_val = document.getElementById('crclm_id').value;
    var data_val2 = document.getElementById("crs_code").value;
    var data_val1 = document.getElementById("crs_title").value;
    var data_val3 = document.getElementById("crs_id").value;
        fetch_clo_bl_flag_val = $('#fetch_clo_bl_flag_val').val(); 
    flag1 = 0;
   
    if(fetch_clo_bl_flag_val == '1'){       
        console.log("");
        if($(".bloom_leves:checkbox:checked").length > 0){   console.log("");                
            $('.error_span1').html("");flag1 = 1;
        }else{            
            $('.error_span1').html("This field is required.");

        }
        
    }else{
        $('.error_span1').html(" ");
        flag1 = 1;
    }
    
    
    if ($('#bloom_domain_1').is(':checked')) {
	$("#bld_1").val(1);
    } else {
	$("#bld_1").val("");
    }
    if ($('#bloom_domain_2').is(':checked')) {
	$("#bld_2").val(1);
    } else {
	$("#bld_2").val("");
    }
    if ($('#bloom_domain_3').is(':checked')) {
	$("#bld_3").val(1);
    } else {
	$("#bld_3").val("");
    }
    var post_data = {
	'crclm_id': data_val,
	'crs_title': data_val1,
	'crs_code': data_val2,
	'crs_id': data_val3
    };    
	var total_weightage = $('#total_weightage').val();
    if (flag == true && flag1 == 1) {
	//	if(total_weightage == 100){
			$.ajax({type: "POST",
				url: base_url + 'curriculum/course/course_title_search_edit',
				data: post_data,
				datatype: "JSON",
				success: function (msg) {
				if ($.trim(msg) == 'valid') {
					var length = $('input.mte_flag_check_box:checked').length;  
					if(length > 0){
					$('.edit_check_error').html("");
					$("#edit_form_id").submit();					
					$('#loading').hide();
					}else{
						$('.edit_check_error').html("Atleast one weightage should be checked.");
						$('#loading').hide();
					}
				} else {
					$('#edit_warning_dialog').modal('show');
					$('#loading').hide();
				}
				}
			});
/* 		}else{
		$('#loading').hide();
			$('.edit_date_error').html("<span color='red'> Invalid Data </span>");
		} */
    } else {
	$('#loading').hide();
    }
});

//To fetch help content related to course
$('.show_help').live('click', function () {
    $.ajax({
	url: base_url + 'curriculum/course/course_help',
	datatype: "JSON",
	success: function (msg) {
	    $('#help_content').html(msg);
	}
    });
});

$('#crclm_id').on('change' , function(){
   
    var crclm_id = $('#crclm_id').val(); 
     post_data = {
	'crclm_id': crclm_id
    };
    $.ajax({
        type: "POST",
	url: base_url + 'curriculum/course/fetch_clo_bl_flag',
        data: post_data,
	success: function (msg) {           
	if(msg == 1){
       			 $('.mandatory').html('*');               
    }else{
        $('.mandatory').html('');
    }
	   $('#fetch_clo_bl_flag_val').val(msg);
	}
    });  
});


// Term-Wise import for courses starts from here
$('#term_wise_import').on('click', function () {
    $('#loading').show();
    var term_import_crclm_id = $('#curriculum').val();
    var curriculu_name = $('#curriculum option:selected').text();
    var term_import_term_id = $('#term').val();
    var term_name = $('#term option:selected').text();
    $('#term_import_crclm_id').val(term_import_crclm_id);
    $('#term_import_term_id').val(term_import_term_id);
    $('#place_curriculum_name').empty();
    $('#place_curriculum_name').html('<font color="blue">' + curriculu_name + '</font>');
    $('#place_term_name').empty();
    $('#place_term_name').html('<font color="blue">' + term_name + '</font>');
    if (term_import_crclm_id != '' && term_import_term_id != '') {
	$.ajax({type: "POST",
	    url: base_url + 'curriculum/import_curriculum/populate_dropdowns',
	    datatype: "JSON",
	    success: function (msg) {
		$('#place_department_dropdown').empty();
		$('#place_department_dropdown').html(msg);
		$('#term_course_import_modal').modal({dynamic: true});
		$('#loading').hide();
	    }
	});
    } else {
	$('#loading').hide();
	$('#modal_error_alert').modal({dynamic: true});
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
	    console.log(msg);
            if(department_id){
                $('#place_program_dropdown').empty();
                $('#place_program_dropdown').html(msg);
                //////////////////////////////
                $('#place_course_list').empty();
                $('#crclm_name_id').empty();
                $('#crclm_name_id').append($('<option>Select Curriculum</option>'));
                $('#term_id_val').empty();
                $('#term_id_val').append($('<option>Select Term</option>'));
                $('#term_courses_import').prop('disabled',true);
            }else{
                $('#place_course_list').empty();
                $('#program_id').empty();
                $('#program_id').append($('<option>Select Program</option>'));
                $('#crclm_name_id').empty();
                $('#crclm_name_id').append($('<option>Select Curriculum</option>'));
                $('#term_id_val').empty();
                $('#term_id_val').append($('<option>Select Term</option>'));
                $('#term_courses_import').prop('disabled',true);
                
            }
	    
	}
    });


});

$('#place_program_dropdown').on('change', '#program_id', function () {
    var pgm_id = $('#program_id').val();
    var to_crclm_id = $('#term_import_crclm_id').val();
    var post_data = {'pgm_id': pgm_id, 'to_crclm_id': to_crclm_id};
    $.ajax({type: "POST",
	url: base_url + 'curriculum/import_curriculum/populate_curriculum_dropdown',
	data: post_data,
	datatype: "JSON",
	success: function (msg) {
	    console.log(msg);
            if(pgm_id){
                $('#place_curriculum_dropdown').empty();
                $('#place_curriculum_dropdown').html(msg);
                ////////////////////////////////////////
                $('#place_course_list').empty();
                $('#term_id_val').empty();
                $('#term_id_val').append($('<option>Select Term</option>'));
                $('#term_courses_import').prop('disabled',true);
            }else{
                $('#place_course_list').empty();
                $('#crclm_name_id').empty();
                $('#crclm_name_id').append($('<option>Select Curriculum</option>'));
                $('#term_id_val').empty();
                $('#term_id_val').append($('<option>Select Term</option>'));
                $('#term_courses_import').prop('disabled',true);
            }
	    
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
	    console.log(msg);
            if(crclm_id){
                $('#place_term_dropdown').empty();
                $('#place_term_dropdown').html(msg);
                ////////////////////////////////////
                $('#place_course_list').empty();
                $('#term_courses_import').prop('disabled',true);
            }else{
                $('#place_course_list').empty();
                $('#term_id_val').empty();
                $('#term_id_val').append($('<option>Select Term</option>'));
                $('#term_courses_import').prop('disabled',true);
            }
	    
	}
    });


});

$('#place_term_dropdown').on('change', '#term_id_val', function () {
    var dept_id = $('#department_id').val();
    var crclm_id = $('#crclm_name_id').val();
    var term_id = $('#term_id_val').val();
    var to_crclm_id = $('#curriculum').val();
    var to_term_id = $('#term').val();
    var post_data = {'crclm_id': crclm_id,
	'term_id': term_id,
	'dept_id': dept_id,
	'to_crclm_id': to_crclm_id,
	'to_term_id': to_term_id};
    $.ajax({type: "POST",
	url: base_url + 'curriculum/import_curriculum/populate_course_list',
	data: post_data,
	success: function (msg) {
	    console.log(msg);
            if(term_id){
                $('#place_course_list').empty();
                $('#place_course_list').html(msg);
            }else{
                $('#place_course_list').empty();
                $('#term_courses_import').prop('disabled',true);
            }
	    
	}
    });


});

var new_crs_id_array = new Array(); // global array variable declaration.

$('#place_course_list').on('click', '.course_check_all', function () {

    if ($(this).is(':checked')) {
	$('.course_check').each(function () {
	    $(this).attr('checked', true);
	    new_crs_id_array.push($(this).val());
	});
	$('#course_ids').val(new_crs_id_array);
	var crs_id = $('#course_ids').val();
	if (crs_id != '') {
	    $('#term_courses_import').attr('disabled', false);
            
	} else {
	    $('#term_courses_import').attr('disabled', true);
	}

    } else {
	$('.course_check').each(function () {
	    $(this).attr('checked', false);
	});
	new_crs_id_array = [];
	$('#course_ids').val('');
	var crs_id = $('#course_ids').val();
	if (crs_id != '') {
	    $('#term_courses_import').attr('disabled', false);
	} else {
	    $('#term_courses_import').attr('disabled', true);
	}
    }

});

$('#place_course_list').on('click', '.course_check', function () {

    if ($(this).is(':checked')) {
	new_crs_id_array.push($(this).val());
	$('#course_ids').val(new_crs_id_array);
	var crs_id = $('#course_ids').val(); console.log("");
	if (crs_id != '') {
	    $('#term_courses_import').attr('disabled', false);
	} else {
	    $('#term_courses_import').attr('disabled', true);
            
	}
    } else {
	var id = $(this).val();
	var index = $.inArray(id, new_crs_id_array);
	new_crs_id_array.splice(index, 1);
	$('#course_ids').val(new_crs_id_array);
	var crs_id = $('#course_ids').val();
	if (crs_id != '') {
	    $('#term_courses_import').attr('disabled', false);
	} else {
	    $('#term_courses_import').attr('disabled', true);
            $('#check_all').prop('checked',false);
	}
    }

});

$('#term_courses_import').on('click', function () {
    $('#loading').show();
    var course_id_array = new Array();
    var course_id;
    var to_dept_id = $('#department_id').val();
    var to_crclm_id = $('#term_import_crclm_id').val();
    var to_term_id = $('#term_import_term_id').val();
    var from_crclm_id = $('#crclm_name_id').val();
    var from_term_id = $('#term_id_val').val();

    $('.course_check').each(function () {
	if ($(this).is(':checked')) {
	    course_id = $(this).val();
	    course_id_array.push(course_id); // creating array of course id (selected courses)
	} else {

	}
    });

    var post_data = {
	'to_crclm_id': to_crclm_id,
	'to_term_id': to_term_id,
	'from_crclm_id': from_crclm_id,
	'from_term_id': from_term_id,
	'course_ids': course_id_array,
	'to_dept_id': to_dept_id
    };


    $.ajax({type: "POST",
	url: base_url + 'curriculum/import_curriculum/term_wise_import_insert',
	data: post_data,
	datatype: "JSON",
	success: function (msg) {
	    $('#loading').hide();
	    if (msg == 1) {
		location.reload();
	    }
	}
    });

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

//function check whether bloom's domain disable or not
$(".check").click(function () {
    var id = $(this).attr("id");
    var bld_id = $(this).attr("data-bld");
    var crs_id = document.getElementById("crs_id").value;
    var post_data = {
	'bld_id': bld_id,
	'crs_id': crs_id
    }
    $.ajax({
	type: "POST",
	url: base_url + 'curriculum/course/check_disable_bloom_domain',
	data: post_data,
	datatype: "json",
	success: function (data) {
	    $("#loading").hide();
	    if (data > 0) {
		$("#cantDisable").modal('show');
		$('#' + id).attr('checked', true);
	    } else {

	    }
	}
    });
});

/*
 * COurse instructor assignment JS function starts from here.
 * Author: Mritunjay B S
 * Date: 13-7-2016
 */

//Function to assign/edit course instructor section wise.

$('#example').on('click', '.assign_course_instructor', function () {
    var course_name = $(this).attr('data-crs_name');
    var course_code = $(this).attr('data-crs_code');
    var course_id = $(this).attr('data-crs_id');
    var crlm_name = $('#curriculum option:selected').text();
    var crclm_id = $('#curriculum').val();
    var term_name = $('#term option:selected').text();
    var term_id = $('#term').val();
    $('#font_crclm_name').html(crlm_name);
    $('#font_term_name').html(term_name);
    $('#font_course_name').html(course_name);
    $('#font_course_code').html(course_code);
    var post_data = {
	'course_id': course_id,
	'crclm_id': crclm_id,
	'term_id': term_id, };
    $.ajax({
	type: "POST",
	url: base_url + 'curriculum/course/assign_course_instructor',
	data: post_data,
	datatype: "JSON",
	success: function (data) {
	    var success_msg = $.parseJSON(data);
	    $('#ci_crclm_id').val(crclm_id);
	    $('#ci_term_id').val(term_id);
	    $('#ci_crs_id').val(course_id);
	    $('#course_instructor_data_body').html(success_msg.course_instructor_display);
	    $('#section').html(success_msg.section_list);
	    $('#course_instructor_name').html(success_msg.user_list);
	    $('#assign_course_instructor_modal').modal('show');

	}
    });




});


// Function to save the course instructor.

$('#assign_course_instructor_modal').on('click', '#save_instructor', function () {
    
    var flag = $('#section_form').valid();
    var section_id = $('#section').val()
    var instructor_id = $('#course_instructor_name').val()
    var ci_crclm_id = $('#ci_crclm_id').val()
    var ci_term_id = $('#ci_term_id').val()
    var ci_crs_id = $('#ci_crs_id').val()
    flag =  $('#section_form').valid();
    var post_data = {
	'section_id': section_id,
	'instructor_id': instructor_id,
	'ci_crclm_id': ci_crclm_id,
	'ci_term_id': ci_term_id,
	'ci_crs_id': ci_crs_id, };
    if(flag == true){
        $.ajax({
	type: "POST",
	url: base_url + 'curriculum/course/add_new_course_instructor', // Assign course instructor to new sections
	data: post_data,
	dataType: "json",
	success: function (data) {
            console.log(data.populate_table);
            console.log(data.section_list);
            $('#course_instructor_data_body').empty();
		$('#course_instructor_data_body').html(data.populate_table);
		var data_options = '{"text":"Course Instructor added successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
		var options = $.parseJSON(data_options);
		noty(options);
		$('#section').empty();
		$('#section').append($(data.section_list));
		$('#course_instructor_name option:eq(0)').attr('selected', 'selected');
//	    if ($.trim(data) == '-1') {
//		var data_options = '{"text":"Course Instructor alresdy exist for this section.    ","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
//		var options = $.parseJSON(data_options);
//		noty(options);
//	    } else {
//		$('#course_instructor_data_body').empty();
//		$('#course_instructor_data_body').html(data);
//		var data_options = '{"text":"Course Instructor added successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
//		var options = $.parseJSON(data_options);
//		noty(options);
//		$('#section option:eq(0)').attr('selected', 'selected');
//		$('#course_instructor_name option:eq(0)').attr('selected', 'selected');
//
//
//	    }

	}
    });
    }else{
        var data_options = '{"text":"Warning Please Select All Dropdowns.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
		var options = $.parseJSON(data_options);
		noty(options);
    }
    
});

// function to show edit instructor dropdown

$('#assign_course_instructor_modal').on('click', '.edit_instructor', function () {
    var counter = $(this).attr('data-edit_counter');
    $('#instructor_name_' + counter).hide();
    $('#show_instructor_dropdown_' + counter).show();



})

//function to save course instructor name in edit.

$('#assign_course_instructor_modal').on('click', '.save_data_button', function () {
    var save_counter = $(this).attr('data-save_counter');
    var instructor_id = $('#instructor_list_' + save_counter).val();
    var inst_name = $('#instructor_list_' + save_counter + ' option:selected').text();
    var mcci_id = $(this).attr('data-edit_id');
    var post_data = {
	'instructor_id': instructor_id,
	'mcci_id': mcci_id,
    };
    $.ajax({
	type: "POST",
	url: base_url + 'curriculum/course/edit_save_course_instructor', // Assign course instructor to new sections
	data: post_data,
	//datatype: "JSON",
	success: function (data) {
	    if ($.trim(data) == 'true') {
		var data_options = '{"text":"Course Instructor updated successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
		var options = $.parseJSON(data_options);
		noty(options);
		$('#instructor_name_' + save_counter).empty();
		$('#instructor_name_' + save_counter).html(inst_name);
		$('#instructor_name_' + save_counter).show();
		$('#show_instructor_dropdown_' + save_counter).hide();
	    } else {
		var data_options = '{"text":"Course Instructor update fails.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
		var options = $.parseJSON(data_options);
		noty(options);
	    }
	}
    });

});

$('#assign_course_instructor_modal').on('click', '.delete_instructor', function () {
    var delete_instructor = $(this).attr('data-delete_id');
    var section_name = $(this).attr('data-section_name');
    var section_id = $(this).attr('data-section_id');
    var crs_id = $(this).attr('data-crs_id');
    $('#ci_section_id').val(section_id);
    var post_data = {
	'crs_id': crs_id,
	'section_id': section_id,
    };
    if (section_name != 'A') {
	$('#delete_ins_id').val(delete_instructor);
	$.ajax({
	    type: 'POST',
	    url: base_url + 'curriculum/course/section_co_finalize', // Assign course instructor to new sections
	    data: post_data,
	    datatype: "JSON",
	    success: function (data) {
			if ($.trim(data) == 'true') {
				$('#delete_instructor_confirm').modal('show');
			} else {
				$('#section_delete_fail').modal('show');
			}
	    }
	});

    } else {

	$('#section_connot_delete').modal('show');

    }

});

$('#delete_instructor_confirm').on('click', '#delete_record', function () {
    var delete_instructor = $('#delete_ins_id').val();
    var crclm_id = $('#ci_crclm_id').val();
    var term_id = $('#ci_term_id').val();
    var course_id = $('#ci_crs_id').val();
    var section_id = $('#ci_section_id').val();
    var post_data = {'delete_instructor': delete_instructor, 'crclm_id': crclm_id, 'term_id': term_id, 'course_id': course_id, 'section_id': section_id, };
    $.ajax({
	type: "POST",
	url: base_url + 'curriculum/course/delete_course_instructor', // Assign course instructor to new sections
	data: post_data,
	dataType: "json",
	success: function (data) {
		$('#course_instructor_data_body').empty();
		$('#course_instructor_data_body').html(data.populate_table);
                $('#section').empty();
                $('#section').append($(data.section_list));
		var data_options = '{"text":"Section deleted successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
		var options = $.parseJSON(data_options);
		noty(options);
		$('#delete_instructor_confirm').modal('hide');
	}
    });
});

$('.noty').click(function () {
    var options = $.parseJSON($(this).attr('data-noty-options'));
    noty(options);

});

function success_modal(msg) {
    var data_options = '{"text":"Your data has been saved successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);

}

$('#save_crs_domain').click(function () {
    var data_crclm = $("#crclm_id").val();
    var data_val = $("#crs_domain_name").val();
    var data_val_descr = $("#crs_domain_description").val();
    var post_data = {
	'crclm_id': data_crclm,
	'crs_domain_name': data_val,
	'crs_domain_description': data_val_descr
    }
    if (data_val == "") {
	$('#message').css('color', 'red');
	$('#message').html("This field is required.<br>");
    } else {
	$(
		'#loading').show();
	//if (flag == true) {
	$.ajax({type: "POST",
	    url: base_url + 'curriculum/course_domain/unique_course_domain',
	    data: post_data,
	    datatype: "JSON",
	    success: function (msg) {

		if ($.trim(msg) == 'valid')
		{
		    $.ajax({type: "POST",
			url: base_url + 'curriculum/course_domain/insert',
			data: post_data,
			dataType: "JSON",
			success: function (msg) {
			    if (msg) {
				post_data = {'a': 1}
				$.ajax({
				    type: "POST",
				    url: base_url + 'curriculum/course_domain/select_all',
				    data: post_data,
				    dataType: 'JSON',
				    success: function (msg) {
					//console.log(msg);
					$('#crs_domain_id').empty();
					$('#crs_domain_id').html(msg);
					success_modal();
					document.getElementById("crs_domain_name").value = "";
					document.getElementById("crs_domain_description").value = "";
					$('#message').html("");
				    }
				});

			    }
			}

		    });

		} else
		{
		    $('#loading').hide();
		    $('#warning_dialog').modal('show');
		}
	    }
	});
	$('#loading').hide();
    }
});

function ClearFields() {
    document.getElementById("crs_domain_name").value = "";
    document.getElementById("crs_domain_description").value = "";
}

	$(".allownumericwithdecimal").on('keypress blur', function (event) {	
            if ((event.which != 8 && event.which != 0 && event.which != 44  && (event.which != 46 || $(this).val().indexOf('.') != -1)) && (event.which < 48 || event.which > 57)) {
					$("#errmsg").html("Digits Only").show().fadeOut("slow");
					$(this).css('border-color', 'red');
					$(this).append('<span>Invalid Value</span>');
					$(this).addClass('num_valid');
					$(this).attr("placeholder", "Only Digits!");					
				    return false;
            } else{		
					$(this).removeClass('num_valid');
					$(this).attr('placeholder', 'Enter Data');
					$(this).css('border-color', '#CCCCCC');
			}
    });

/*$('#example').on('hover', '.assign_course_instructor', function () {
    var course_name = $(this).attr('data-crs_name');
    var course_id = $(this).attr('data-crs_id');
    var crlm_name = $('#curriculum option:selected').text();
    var crclm_id = $('#curriculum').val();
    var term_name = $('#term option:selected').text();
    var term_id = $('#term').val();
    $('#font_crclm_name').html(crlm_name);
    $('#font_term_name').html(term_name);
    $('#font_course_name').html(course_name);
    var post_data = {
	'course_id': course_id,
	'crclm_id': crclm_id,
	'term_id': term_id, };
    $.ajax({
	type: "POST",
	url: base_url + 'curriculum/course/assign_course_instructor',
	data: post_data,
	datatype: "JSON",
	success: function (data) {
	    var success_msg = $.parseJSON(data);
	    $('#ci_crclm_id').val(crclm_id);
	    $('#ci_term_id').val(term_id);
	    $('#ci_crs_id').val(course_id);
	    $('#course_instructor_data_body').html(success_msg.course_instructor_display);
	    $('#section').html(success_msg.section_list);
	    $('#course_instructor_name').html(success_msg.user_list);
	    //$('#assign_course_instructor_modal').modal('show');

	}
    });




});*/