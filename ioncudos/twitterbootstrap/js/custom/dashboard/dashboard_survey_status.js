$(document).ready(function () {

	$('#tab6_id').on('click', function () {
		$('#loading').show();
		$.ajax({
			type : "POST",
			url : base_url + 'dashboard/dashboard_survey/dashboard_survey_term_status',
			success : function (msg) {
				$('#Content_survey_term').html(msg);
				$('#loading').hide();
			}
		});
	});
	
});
if($.cookie('remember_select') != null) {

    // set the option to selected that corresponds to what the cookie is set to

    $('#survey_crclm_id_crclm option[value="' + $.cookie('remember_select') + '"]').attr('selected', 'selected');

}

$('#term_collapse').on('click',function(){
	alert("");
});
 $.cookie('remember_select');
function curclm() {
 $.cookie('remember_select', $('#survey_crclm_id_crclm option:selected').val(), { expires: 90, path: '/'});

	var crclm_id = $('#survey_crclm_id_crclm').val(); 
	var post_data = {
		'curriculum_id' : crclm_id
	}
	$.ajax({
		type : "POST",
		url : base_url + 'dashboard/dashboard_survey/course_level_term',
		data : post_data,
		success : function (msg) {

			//document.getElementById('term_course').innerHTML = msg;

		}
	});

	$('#crclm_head').hide();
	$('#assessment_head').hide();
	$('#survey_co_head').hide();
	$('#survey_po_peo').hide();
	$('#example_peo_po').hide();
	$('#example').hide();
	$('#example_course').hide();
	$('#color_code').hide();
	$('#survey_status_view').html('');
	$('#color_code1').hide();
	$('#color_code2').hide();
	$('#color_code3').hide();
}

function fetch_crclm1() {

	var crclmSelect = document.getElementById("survey_crclm_id_crclm");
	var selectedcrclm = crclmSelect.options[crclmSelect.selectedIndex].text
		document.getElementById("curriculum_id1").value = selectedcrclm;

	var dept_pdf = document.getElementById("survey_dept");
	var selectedcrclm = dept_pdf.options[dept_pdf.selectedIndex].text
		document.getElementById("dept_pdf").value = selectedcrclm;

	var prog_pdf = document.getElementById("survey_pgm_id_crclm");
	var selectedcrclm = prog_pdf.options[prog_pdf.selectedIndex].text
		document.getElementById("prog_pdf").value = selectedcrclm;

//	var term_pdf = document.getElementById("term_course");
//	var selectedcrclm = term_pdf.options[term_pdf.selectedIndex].text
//		document.getElementById("term_pdf").value = selectedcrclm;
}

$("#survey_export_data,#survey_export_data_down").on('click', function () {

	var cloned_course_survey_status = $('#export_pdf_data').clone().html();
	var cloned_peo_po_survey_status = $("#Survey_PEO").clone().html();
	var cloned_color_code = $("#color_code").clone().html();

	$('#course_survey_status').val(cloned_course_survey_status);
	$('#peo_po_survey_status').val(cloned_peo_po_survey_status);
	$('#cloned_color_code_status').val(cloned_color_code);

	$('#survey_term_id').submit();
});
function dept_curriculum1() {

	var data_val = document.getElementById('survey_dept').value;
	var pgm_id = document.getElementById('survey_pgm_id_crclm').value;
	var post_data = {
		'dept_id' : data_val,
		'pgm_id' : pgm_id
	}
	$.ajax({
		type : "POST",
		url : base_url + 'dashboard/dashboard_survey/dept_active_curriculum1',
		data : post_data,
		success : function (msg) {
			document.getElementById('survey_crclm_id_crclm').innerHTML = msg;
			var term = '<option>Select Term</option>';
			//document.getElementById('term_course').innerHTML = term;
		}
	});
	$('#crclm_head').hide();
	$('#assessment_head').hide();
	$('#survey_co_head').hide();
	$('#survey_po_peo').hide();
	$('#example_peo_po').hide();
	$('#example').hide();
	$('#example_course').hide();
	$('#color_code').hide();
	$('#survey_status_view').html('');
	$('#color_code1').hide();
	$('#color_code2').hide();
	$('#color_code3').hide();

}

function fetch_pgm() {

 $.cookie('remember_term', $('#survey_crclm_id_crclm option:selected').val(), {expires: 90, path: '/'});
	var data_val = document.getElementById('survey_dept').value;

	var post_data = {
		'dept_id' : data_val
	}
	$.ajax({
		type : "POST",
		url : base_url + 'dashboard/dashboard_survey/fetch_program',
		data : post_data,
		success : function (msg) {

			document.getElementById('survey_pgm_id_crclm').innerHTML = msg;
			var crclm = '<option value="">Select Curriculum</option>';
			var term = '<option value="">Select Term</option>';
			document.getElementById('survey_crclm_id_crclm').innerHTML = crclm;
			//document.getElementById('term_course').innerHTML = term;
		}
	});

	$('#crclm_head').hide();
	$('#assessment_head').hide();
	$('#survey_co_head').hide();
	$('#survey_po_peo').hide();
	$('#example_peo_po').hide();
	$('#example').hide();
	$('#example_course').hide();
	$('#color_code').hide();
	$('#survey_status_view').html('');
	$('#color_code1').hide();
	$('#color_code2').hide();
	$('#color_code3').hide();
	$('#survey_export_pdf').hide();
	$('#survey_export_pdf1').hide();

}




$('#survey_crclm_id_crclm').on('change', function () {

 $.cookie('remember_term', $('#survey_crclm_id_crclm option:selected').val(), {expires: 90, path: '/'});
//$('#loading').show();
	$('#color_code').show();
	$('#course_state_table').empty();
	//var term_id = $('#term_course').val();
	var term_id = 0;
	var dept_id = document.getElementById('survey_dept').value;
	var crclm_id = $('#survey_crclm_id_crclm').val();
	var post_data = {
		'curriculum_id' : crclm_id,
		'term_id' : term_id,
		'dept_id' : dept_id
	}

	$.ajax({
		type : "POST",
		url : base_url + 'dashboard/dashboard_survey/fetch_term_course_data',
		data : post_data,
		dataType : 'json',
		success : function (msg) {	
		$('#loading').hide();
			if (msg == "false") {
				$('#survey_status_view').html("No data available ");
			} else {
				st_1 = msg.not_created;
				st_2 = msg.review_pending;
				st_3 = msg.review_rework;
				st_4 = msg.review_completed;
				st_5 = msg.approval_pending;
				st_6 = msg.approval_rework;
				st_7 = msg.approved;
				st_8 = msg.size;
				total_map_strength = msg.term_total_map_strength;
				high_map_strength = msg.term_high_map_val;
				medium_map_strength = msg.term_mid_map_val;
				low_map_strength = msg.term_low_map_val;
				$('#course_clo_pie_chart_new').empty();
				$('#total_map_extent_new').empty();
				$('#total_map_strength_new').empty();
				if (msg.no_data == 0) {
					$('#course_clo_pie_chart_new').html("<b>No Courses are created for This Curriculum in this Term</b>");
					$('#survey_status_view').html("<b>No Courses are created for This Curriculum in this Term</b>");
				} else {
					if (total_map_strength == 0 && high_map_strength == 0 && medium_map_strength == 0 && low_map_strength) {
						course_piechart();
					} else {					
						$('#survey_status_view').html(msg);						
						$('#crclm_head').show();
						$('#assessment_head').show();
						$('#survey_co_head').show();
						$('#survey_po_peo').show();
						$('#example_peo_po').show();
						$('#example').show();
						$('#example_course').show();
						$('#color_code1').show();
						$('#color_code2').show();
						$('#color_code3').show();
						$('#survey_export_pdf').show();
						$('#survey_export_pdf1').show();
						$('#survey_status_view').show();
						
					}
				}
			}
		}

	});


	//var term_id = $('#term_course').val();
	var dept_id = document.getElementById('survey_dept').value;
	var post_data = {
		'curriculum_id' : crclm_id,
		'term_id' : term_id,
		'dept_id' : dept_id
	}
});

$('.crs_mapping_popup_status').live('click', function () {
//function crs_mapping_popup_status(crs_id,crclm_id){
	var crs_id = $(this).attr('abbr');
	var crclm_id = $('#survey_crclm_id_crclm').val();
	
//}
});
