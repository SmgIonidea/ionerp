$(document).ready(function () {

	$('#tab4_id').on('click', function () {
		$('#loading').show();
		$.ajax({
			type : "POST",
			url : base_url + 'dashboard/dashboard/dashboard_course_status',
			success : function (msg) {
				$('#Content').html(msg);
				$('#loading').hide();
			}
		});
	});

});
if($.cookie('remember_select') != null) {

    // set the option to selected that corresponds to what the cookie is set to

    $('#course_crclm_id option[value="' + $.cookie('remember_select') + '"]').attr('selected', 'selected');

}
 $.cookie('remember_select');
function curclm() {
 $.cookie('remember_select', $('#course_crclm_id option:selected').val(), { expires: 90, path: '/'});

	var crclm_id = $('#course_crclm_id').val();
	var post_data = {
		'curriculum_id' : crclm_id
	}
	$.ajax({
		type : "POST",
		url : base_url + 'dashboard/dashboard/course_level_term',
		data : post_data,
		success : function (msg) {

			document.getElementById('term_course').innerHTML = msg;

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
	$('#course_state_table_new').html('');
	$('#color_code1').hide();
	$('#color_code2').hide();
	$('#color_code3').hide();
}

function fetch_crclm1() {

	var crclmSelect = document.getElementById("course_crclm_id");
	var selectedcrclm = crclmSelect.options[crclmSelect.selectedIndex].text
		document.getElementById("curriculum_id1").value = selectedcrclm;

	var dept_pdf = document.getElementById("dept");
	var selectedcrclm = dept_pdf.options[dept_pdf.selectedIndex].text
		document.getElementById("dept_pdf").value = selectedcrclm;

	var prog_pdf = document.getElementById("course_pgm_id");
	var selectedcrclm = prog_pdf.options[prog_pdf.selectedIndex].text
		document.getElementById("prog_pdf").value = selectedcrclm;

	var term_pdf = document.getElementById("term_course");
	var selectedcrclm = term_pdf.options[term_pdf.selectedIndex].text
		document.getElementById("term_pdf").value = selectedcrclm;
}

$("#export_data,#export_data_down").on('click', function () {

	var cloned_crclm_level = $('#assess_attain').clone().html();
	var cloned_survey = $("#Survey").clone().html();
	var cloned_course_data1 = $("#crs_desg").clone().html();
	var cloned_Survey_peo_po = $("#Survey_PEO").clone().html();
	var cloned_color_code = $("#color_code").clone().html();

	$('#program_level_pdf_status').val(cloned_crclm_level);
	$('#Survey_level_pdf_status').val(cloned_survey);
	$('#cloned_course_data_status').val(cloned_course_data1);
	$('#cloned_PEO_PO_data_status').val(cloned_Survey_peo_po);
	$('#cloned_color_code_status').val(cloned_color_code);
	$('#course_status_id').submit();
});
function dept_curriculum1() {

	var data_val = document.getElementById('dept').value;
	var pgm_id = document.getElementById('course_pgm_id').value;
	var post_data = {
		'dept_id' : data_val,
		'pgm_id' : pgm_id
	}
	$.ajax({
		type : "POST",
		url : base_url + 'dashboard/dashboard/dept_active_curriculum1',
		data : post_data,
		success : function (msg) {
			document.getElementById('course_crclm_id').innerHTML = msg;
			var term = '<option>Select Term</option>';
			document.getElementById('term_course').innerHTML = term;
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
	$('#course_state_table_new').html('');
	$('#color_code1').hide();
	$('#color_code2').hide();
	$('#color_code3').hide();

}

function fetch_pgm() {
 $.cookie('remember_term', $('#course_crclm_id option:selected').val(), {expires: 90, path: '/'});
	var data_val = document.getElementById('dept').value;

	var post_data = {
		'dept_id' : data_val
	}
	$.ajax({
		type : "POST",
		url : base_url + 'dashboard/dashboard/fetch_program',
		data : post_data,
		success : function (msg) {

			document.getElementById('course_pgm_id').innerHTML = msg;
			var crclm = '<option value="">Select Curriculum</option>';
			var term = '<option value="">Select Term</option>';
			document.getElementById('course_crclm_id').innerHTML = crclm;
			document.getElementById('term_course').innerHTML = term;
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
	$('#course_state_table_new').html('');
	$('#color_code1').hide();
	$('#color_code2').hide();
	$('#color_code3').hide();
	$('#export_pdf').hide();
	$('#export_pdf1').hide();

}

/* $('#example_course').on('click', '.qp_cia_list', function () {
	var course_id = $(this).attr('data-crs_id');
	var term_id = $('#term_course').val();
	var dept_id = document.getElementById('dept').value;
	var crclm_id = $('#course_crclm_id').val();
	var post_data = {
		'curriculum_id' : crclm_id,
		'term_id' : term_id,
		'dept_id' : dept_id,
		'course_id' : course_id
	}

	$.ajax({
		type : "POST",
		url : base_url + 'dashboard/dashboard/fetch_cia_data',
		data : post_data,
		dataType : 'json',
		success : function (msg) {
			populate_table_modal(msg);
		}
	});
	$('#cia_qp').modal('show');

}); */

$('#term_course').on('change', function () {
 $.cookie('remember_term', $('#course_crclm_id option:selected').val(), {expires: 90, path: '/'});
$('#loading').show();
	$('#color_code').show();
	$('#course_state_table').empty();
	var term_id = $('#term_course').val();
	var dept_id = document.getElementById('dept').value;
	var crclm_id = $('#course_crclm_id').val();
	var post_data = {
		'curriculum_id' : crclm_id,
		'term_id' : term_id,
		'dept_id' : dept_id
	}

	$.ajax({
		type : "POST",
		url : base_url + 'dashboard/dashboard/fetch_term_course_data',
		data : post_data,
		dataType : 'json',
		success : function (msg) {
		$('#loading').hide();
			if (msg == "false") {
				$('#course_state_table_new').html("No data available ");
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
					$('#course_state_table_new').html("<b>No Courses are created for This Curriculum in this Term</b>");
				} else {

					if (total_map_strength == 0 && high_map_strength == 0 && medium_map_strength == 0 && low_map_strength) {
						course_piechart();
					} else {
						course_piechart();
						term_crs_extent_map(msg.term_crs_total_opp, msg.term_crs_mapped_opp);
						term_crs_map_strength(msg.high_map_val, msg.moderate_map_val, msg.low_map_val);
						$('#course_state_table_new').html(msg.course_state_table);
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
						$('#export_pdf').show();
						$('#export_pdf1').show();
						//$(".heading_table1").html("<b style='font-size:14px;'>Course Curricumlum Design Planning  </b>");
						//$(".heading_table2").html("<b  style='font-size:14px;'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Course Delivery Planning  </b>");
					}
				}
			}
		}

	});
	
 	$('.view_co_po_mapping').live('click',function(){
	
	var crs_id = $(this).attr('abbr');
	var crclm_id = $('#course_crclm_id').val();
	var term_id = $('#term_course').val();
	//crs_mapping_popup_status(crs_id,crclm_id);
	var post_data = {
		'crs_id' : crs_id,
		'crclm_id' : crclm_id ,
		'term_id' : term_id
	}

	
	 $.ajax({type: "POST",
        url: base_url + 'dashboard/dashboard/clo_details',
        data: post_data,
        success: function (msg) {			
		
			{
			
			var post_data = {
		'crs_id' : crs_id,
		'crclm_id' : crclm_id
	}

	$.ajax({
		type : "POST",
		url : base_url + 'dashboard/dashboard/crs_mapping_strength',
		data : post_data,
		dataType : 'json',
		success : function (msg) {		
			$.jqplot.config.enablePlugins = true;
			$('#crs_status_title').html(msg.course_title + ': CO to ' + so + ' Extent / Strength of Mapping');
			var series_data = [
				[['Total Mapping Opportunities', parseInt(msg.clo_po_map_opp_result)]],
				[['Actual Mapped Opportunities', parseInt(msg.clo_po_map_count)]]
			];

			// Pie chart
			var data = [[msg['map_level_wt'][0]['map_level_name_alias'] + ':' + msg.high_map_val, parseInt(msg.course_high_map_val)], [msg['map_level_wt'][1]['map_level_name_alias'] + ':' + msg.mid_map_val, parseInt(msg.course_mid_map_val)], [msg['map_level_wt'][2]['map_level_name_alias'] + ':' + msg.low_map_val, parseInt(msg.course_low_map_val)]];
	
			var crs_pie_plot = jQuery.jqplot('course_status_map_pie_chart', [data], {
					title : {
						text : 'CO to ' + so + ' Strength of Mapping Distribution',
						show : true,
					},
					seriesColors : ['#c36609', '#9ab3e5', '#ccccb3'],
					
					seriesDefaults : {
						renderer : jQuery.jqplot.PieRenderer,
						rendererOptions : {
							// Turn off filling of slices.
							fill : true,
							showDataLabels : true,
							// Add a margin to separate the slices.

							sliceMargin : 4,
							// stroke the slices with a little thicker line.
							lineWidth : 5,
							padding : 2,
							paddingleft :2,
							shadow: false,
						}
					},

					legend : {
						   show:true, 
							placement: 'inside', 
							rendererOptions: {numberRows: 1}, 
							location:'s',          
					}
				});
			$('#co_po_mapping').on('shown', function (e) {
				crs_pie_plot.replot();
			});

		}

	});
            $('#table_co_po_map').html(msg);
			$('#co_po_mapping').modal('show');
			} 
        }
    });
	
	});

	var term_id = $('#term_course').val();
	var dept_id = document.getElementById('dept').value;
	var post_data = {
		'curriculum_id' : crclm_id,
		'term_id' : term_id,
		'dept_id' : dept_id
	}
	$.ajax({
		type : "POST",
		url : base_url + 'dashboard/dashboard/fetch_survey_data',
		data : post_data,
		dataType : 'json',
		success : function (msg) {
			populate_table(msg);
		}
	});
	$.ajax({
		type : "POST",
		url : base_url + 'dashboard/dashboard/fetch_survey_peo_po',
		data : post_data,
		dataType : 'json',
		success : function (msg) {
			populate_table_survey_peo(msg);
		}
	});
	$.ajax({
		type : "POST",
		url : base_url + 'dashboard/dashboard/fetch_assessment_attainment_data',
		data : post_data,
		dataType : 'json',
		success : function (msg) {
			populate_table_assessment(msg);
		}
	});

});

function populate_table_survey_peo(msg) {

	$('#example_peo_po').dataTable().fnDestroy();
	$('#example_peo_po').dataTable({
		"aaSorting" : [],
		"bFilter" : false,
		"bPaginate" : false,
		"bInfo" : false,
		"orderable" : false,
		"aoColumns" : [{
				"sTitle" : "Sl No.",
				"mData" : "Sl_no",
				"sWidth" : "2%",
				"bSortable" : false,
				"sClass" : "rightalign",
			}, {
				"sTitle" : "Curriculum Name",
				"mData" : "crclm_name",
				"bSortable" : false,
			}, {
				"sTitle" : "Curriculum Owner",
				"mData" : "crclm_owner_name",
				"bSortable" : false,
			}, {
				"sTitle" : "Entity",
				"mData" : "Entity",
				"bSortable" : false,
			}, {
				"sTitle" : "Survey Name",
				"mData" : "survey_name",
				"bSortable" : false,
			}, {
				"sTitle" : "Survey Defined",
				"mData" : "survey_created",
				"bSortable" : false,
			}, {
				"sTitle" : "Survey Completion",
				"mData" : "survey_hosted",
				"bSortable" : false,
			},

		],
		"aaData" : msg['peo'],
		"sPaginationType" : "bootstrap",
		"fnDrawCallback" : function (settings) {
			$('.rightalign').css('text-align', 'right');
		}
	});
}
function populate_table(msg) {

	//Function to generate data table grid
	$('#example').dataTable().fnDestroy();
	$('#example').dataTable({
		"aaSorting" : [],
		"bFilter" : false,
		"bPaginate" : false,
		"bInfo" : false,
		"orderable" : false,
		"aoColumns" : [{
				"sTitle" : "Sl No.",
				"mData" : "Sl_no",
				"sWidth" : "2%",
				"bSortable" : false,
				"sClass" : "rightalign",
			},/*  {
				"sTitle" : "Course Title - Course Code",
				"mData" :  "mt_details_name",
				"bSortable" : false,
			}, */ {
				"sTitle" : "Course Title - Course Code",
				"mData" : "crs_name",
				"bSortable" : false,
			}, {
				"sTitle" : "Course Owner",
				"mData" : "crs_own_name",
				"bSortable" : false,
			}, {
				"sTitle" : "Survey Name",
				"mData" : "Survey_name",
				"bSortable" : false,
			}, {
				"sTitle" : "Survey Definition",
				"mData" : "not_created",
				"bSortable" : false,
			}, {
				"sTitle" : "Survey Completion",
				"mData" : "created",
				"bSortable" : false,
			},

		],
		"aaData" : msg['co'],
		"sPaginationType" : "bootstrap",
		"fnDrawCallback" : function (settings) {
			$('.rightalign').css('text-align', 'right');
		}
	});
	
	$('#example').dataTable().fnDestroy();
	$(example).dataTable({
		"sPaginationType": "bootstrap",
		"fnDrawCallback": function () {
		$('.group').parent().css({'background-color': '#C7C5C5'});
			
	}

	}).rowGrouping({iGroupingColumnIndex: 1,
	bHideGroupingColumn: true}); 

}

function populate_table_assessment(msg) {
	var max_count = msg['max_occa'];
	var table_head = table_head_empty = count = '' ; 

	$("#example_course > thead").empty();
	$("#example_course > tbody").empty(); 
	for (var k = 1; k <= max_count; k++) {
		table_head += '<th title="CIA - ' + k + '" style=" white-space: nowrap;border-top: 1px solid #ddd;color: #8E2727" class="t1 header " role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> </th><!--<th title="CIA - ' + k + ' status" style="border-top: 1px solid #ddd;color: #8E2727" class="t1 header " role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"></th>-->';				
	}
	count = parseInt(max_count) + 1;
	table_head_empty += '<th title=""  colspan = '+ (count) +' style=" white-space: nowrap;border-top: 1px solid #ddd;color: #8E2727" class="t1 header centeralign " role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" colspan = "">CIA</th>';
	
	$("#example_course > thead").append('<tr><th style="border-top: 1px solid #ddd;color: #8E2727;" class="t1 header " role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" ></th><th  style=" white-space: nowrap;border-top: 1px solid #ddd;color: #8E2727" class="t1 header " role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" ></th><th style="border-top: 1px solid #ddd;color: #8E2727;" class="t1 header " role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"></th>'+ table_head_empty +'<th style="border-top: 1px solid #ddd;color: #8E2727;" class="t1 header " role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> TEE </th></tr><tr role="row" ><th title="Sl No." style="white-space: nowrap;color: #8E2727; border-top: 1px solid #ddd;width:40px;" class="t1 header " role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"></th><th  style=" white-space: nowrap;border-top: 1px solid #ddd;color: #8E2727" class="t1 header " role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" ></th><th title="Course Name" style="border-top: 1px solid #ddd;color: #8E2727;" class="t1 header " role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"></th><th   title="No Of IAs" style="border-top: 1px solid #ddd;color: #8E2727;" class="t1 header " role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"></th>' + table_head + '<th title="SEE" style="border-top: 1px solid #ddd;color: #8E2727" class="t1 header " role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> </th></tr>');

	columnObj = [];
	sl_no = {
		"sTitle" : "Sl No.",
		"mData" : "sl_no",
		"sWidth" : "2%",
		"bSortable" : false,
		"sClass" : "rightalign",
	};
	cours_name = {
		"sTitle" : "Course Name",
		"mData" : "crs_name",
		"bSortable" : false,
	};
	section_name = {
		"sTitle" : "Section ",
		"mData" : "section_name",
		"bSortable" : false,
		"sClass" : "centeralign",
	};
	no_of_occ = {
		"sTitle" : "No.of IAs",
		"mData" : "no_of_occa",
		"bSortable" : false,
		"sClass" : "centeralign",
	};
	columnObj.push(sl_no);
	columnObj.push(cours_name);
	columnObj.push(section_name);
	columnObj.push(no_of_occ);
	for (var i = 1; i <= max_count; i++) {
		item = {}
		item["sTitle"] = "Internal Assessment - " + i + "";
		item["mData"] = "occa_" + i + "";
		item["sWidth"] = "8%";
		item["bSortable"] = false,
		columnObj.push(item);
	}
	see = {
		"sTitle" : tee,
		"mData" : "see",
		"bSortable" : false,
	};
	columnObj.push(see);
	$(".example_course").css("width", "550px");
	//Function to generate data table grid
	
	$('#example_course').dataTable().fnDestroy();
	$('.DataTables_sort_icon').remove();

	$('#example_course').dataTable({
		"aaSorting" : [],
		"bFilter" : false,
		"bPaginate" : false,
		"bInfo" : false,

		"aoColumns" : columnObj,
		"aaData" : msg['course'],
		"sPaginationType" : "bootstrap",
		"fnDrawCallback" : function (settings) {
			var styles = {
				'border-bottom-width' : '0',
				'border-top-width' : '0',
			};
			var color = {
				//'border-color':'red',
				'border-bottom' : 'none',
				'border-top' : 'none'
			};
			var border_set = {
				'border' : '1px solid #ddd',
			};

			$('.check').parent().css(styles);
			$('.example_course_border').css(color);
			$('.t1').css(border_set);
			$('.t2').css(color);
			$('.test').css('border', '1px solid #ddd');
			$('.rightalign').css('text-align', 'right');
			$('.centeralign').css('text-align', 'center');
			//$('.odd td').css(border_set);
		}
	});
 	
	$('#example_course').dataTable().fnDestroy();
	$(example_course).dataTable({
		"sPaginationType": "bootstrap",
		"fnDrawCallback": function () {
		$('.group').parent().css({'background-color': '#C7C5C5'});
			
	}

	}).rowGrouping({iGroupingColumnIndex: 1,
	bHideGroupingColumn: true}); 
}

function populate_table_modal(msg) {
	//Function to generate data table grid

	$('#example_modal_cia').dataTable().fnDestroy();

	$('#example_modal_cia').dataTable({
		"aaSorting" : [],
		"bFilter" : false,
		"bPaginate" : false,
		"bInfo" : false,

		"aoColumns" : [{
				"sTitle" : "Sl_No",
				"mData" : "Sl_no",
				"sWidth" : "2%",
				"sClass" : "rightalign test"
			}, {
				"sTitle" : "Question Paper Title",
				"mData" : "Qp_title",
				"sClass" : "test"
			}, {
				"sTitle" : "Rolled-Out",
				"mData" : "rollout",
				"sClass" : "test"
			}, {
				"sTitle" : "Not Rolled-Out ",
				"mData" : "not_rollout",
				"sClass" : "test"
			},

		],
		"aaData" : msg['course'],
		"sPaginationType" : "bootstrap",

	});

}
function course_piechart() {
	var data = [
		['State1: Not Created' + ' - ' + parseInt(st_1) + ' / ' + st_8, parseInt(st_1)], ['State2: Created / Review Pending' + ' - ' + parseInt(st_2) + ' / ' + st_8, parseInt(st_2)], ['State3: Review Rework' + ' - ' + parseInt(st_3) + ' / ' + st_8, parseInt(st_3)], ['State4: Reviewed' + ' - ' + parseInt(st_4) + ' / ' + st_8, parseInt(st_4)]
	];

	var pie_plot = jQuery.jqplot('course_clo_pie_chart', [data], {
			title : {
				text : 'Term-wise Course CO States',
				show : true,
			},
			seriesDefaults : {
				renderer : jQuery.jqplot.PieRenderer,
				rendererOptions : {
					// Turn off filling of slices.
					fill : true,
					showDataLabels : true,
					// Add a margin to separate the slices.

					sliceMargin : 4,
					// stroke the slices with a little thicker line.
					lineWidth : 5
				}
			},
			legend : {
				show : true,
				location : 'e'
			}
		});

	var course_clo_pie_chart = '#' + 'course_clo_pie_chart';
	var course_clo_pie_chart_pdf = '#' + 'course_clo_pie_chart_pdf';
	var imgData = $(course_clo_pie_chart).jqplotToImageStr({});
	var imgElem = $('<img/>').attr('src', imgData);
	$(course_clo_pie_chart_pdf).html(imgElem);

}


$('.crs_mapping_popup_status').live('click', function () {
//function crs_mapping_popup_status(crs_id,crclm_id){
	var crs_id = $(this).attr('abbr');
	var crclm_id = $('#course_crclm_id').val();
	
//}
});
