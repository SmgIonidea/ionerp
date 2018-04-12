var base_url = $('#get_base_url').val();

//Function to fetch term details
if ($.cookie('remember_dept') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#department option[value="' + $.cookie('remember_dept') + '"]').prop('selected', true);
    select_pgm_list();
}


function select_pgm_list() {

	$.cookie('remember_dept', $('#department option:selected').val(), {
		expires : 90,
		path : '/'
	});
	var dept_id = document.getElementById('department').value;

	var post_data = {
		'dept_id' : dept_id
	}

	$.ajax({
		type : "POST",
		url : base_url + 'question_paper/manage_mte_qp/select_pgm_list',
		data : post_data,
		success : function (msg) {
			//document.getElementById('program').innerHTML = msg;
			$('#program').html(msg);
			if ($.cookie('remember_pgm') != null) {
				// set the option to selected that corresponds to what the cookie is set to
				$('#program option[value="' + $.cookie('remember_pgm') + '"]').prop('selected', true);
				select_crclm_list();

			}
		}
	});
}


function select_crclm_list() {
	$.cookie('remember_pgm', $('#program option:selected').val(), {
		expires : 90,
		path : '/'
	});
	var pgm_id = document.getElementById('program').value;
	var post_data = {
		'pgm_id' : pgm_id
	}

	$.ajax({
		type : "POST",
		url : base_url + 'question_paper/manage_mte_qp/select_crclm_list',
		data : post_data,
		success : function (msg) {
			//document.getElementById('curriculum').innerHTML = msg;
			$('#curriculum').html(msg);
			if ($.cookie('remember_crclm') != null) {
				// set the option to selected that corresponds to what the cookie is set to
				$('#curriculum option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
				//get_selected_value();
				select_termlist();
			}
		}
	});
}



function select_termlist() {
	$.cookie('remember_crclm', $('#curriculum option:selected').val(), {
		expires : 90,
		path : '/'
	});
	var crclm_id = document.getElementById('curriculum').value;

	var post_data = {
		'crclm_id' : crclm_id
	}

	$.ajax({
		type : "POST",
		url : base_url + 'question_paper/manage_mte_qp/select_termlist',
		data : post_data,
		success : function (msg) {
			//document.getElementById('term').innerHTML = msg;
			$('#term').html(msg);
			if ($.cookie('remember_term') != null) {
				// set the option to selected that corresponds to what the cookie is set to
				$('#term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
				GetSelectedValue();
				// select_termlist();
			}
		}
	});
}



/* Function is used to fetch course details.
 * @param - curriculum id & term id.
 * @returns- an array of course details.
 */
function GetSelectedValue() {
	$.cookie('remember_term', $('#term option:selected').val(), {
		expires : 90,
		path : '/'
	});
	var dept_id = document.getElementById('department').value;
	var prog_id = document.getElementById('program').value;
	var crclm_id = document.getElementById('curriculum').value;
	var crclm_term_id = document.getElementById('term').value;

	var post_data = {
		'dept_id' : dept_id,
		'prog_id' : prog_id,
		'crclm_id' : crclm_id,
		'crclm_term_id' : crclm_term_id
	}

	$.ajax({
		type : "POST",
		url : base_url + 'question_paper/manage_mte_qp/mte_show_course',
		data : post_data,
		dataType : 'json',
		success : populate_table
	});
}


/* Function is used to generate table grid of  course details.
 * @param -
 * @returns- an array of course details.
 */
function populate_table(msg) {
	var m = 'd';
	$('#mte_qp_list').dataTable().fnDestroy();
	$('#mte_qp_list').dataTable({
		"aoColumns" : [{
				"sTitle" : "Sl No.",
				"mData" : "sl_no"
			}, {
				"sTitle" : "Course Code",
				"mData" : "crs_code"
			}, {
				"sTitle" : "Course Title",
				"mData" : "crs_title"
			}, {
				"sTitle" : "Core / Elective",
				"mData" : "crs_type_name"
			}, {
				"sTitle" : "Credits",
				"mData" : "total_credits"
			}, {
				"sTitle" : "Total Marks",
				"mData" : "total_marks"
			}, {
				"sTitle" : "Course Owner",
				"mData" : "username"
			}, {
				"sTitle" : "Mode",
				"mData" : "crs_mode"
			}, {
				"sTitle" : "Manage " + entity_mte + " QP",
				"mData" : "crs_id_edit"
			}  , {
				"sTitle" : "Import " + entity_mte + " QP",
				"mData" : "upload_mte_qp"
			} ,/*{
				"sTitle" : "Import " + entity_mte + " QP",
				"mData" : "import_qp"
			}, {
				"sTitle" : "Status",
				"mData" : "publish"
			} */
		],
		"aaData" : msg,
		"sPaginationType" : "bootstrap"
	});
}

$('#example , #mte_qp_list').on('click' , '.topic_not_defined', function(){
		$('.topic_error_msg').html( $(this).attr('data-error_mag') + '<br/><a href ="'+ base_url + $(this).attr('data-link') +'"> Click here to  '+ $(this).attr('data-type_name') +' .<a>');
		$('#topic_not_defined_modal').modal('show');
});  

$('.topic_not_defined').live('click' ,function(){
		$('.topic_error_msg').html( $(this).attr('data-error_mag') + '<br/><a href ="'+ base_url + $(this).attr('data-link') +'"> Click here to  '+ $(this).attr('data-type_name') +' .<a>');
		$('#topic_not_defined_modal').modal('show');
});  


$('#mte_qp_list').on('click', '.fetch_mte_qp_data', function () {
	var qp_url = $(this).attr('abbr');
	var crs_id = $(this).attr('value');
	var pgm_id =  $('#program').val();
	var crclm_id = $('#curriculum').val();
	var term_id = $('#term').val();
	var qpd_type = 3;
	var ao_id ='';
	var topic_count = $(this).attr('data-topic_count'); 
	var topic_status = $(this).attr('data-topic_staus');
	var clo_bl_flag =  $(this).attr('data-clo_bl_flag');
	var clo_bl_status =  $(this).attr('data-bl_status');
	$('#abbr_address').val(qp_url);
	
	$.ajax({
		type : "POST",
		url : qp_url,
		dataType : 'JSON',
		success : function (msg) {
            console.log();			
			$('#modal_title').empty();
			$('#modal_title').html('' + entity_mte + ' Question Paper(s)/Rubrics List');
			$('#mte_qp_modal_text').html(msg.qp_table);
                         $('#rubrics_table_display_div').empty();
                        $('#rubrics_table_display_div').html(msg.rubrics_table); 
			 $('#modal_footer').empty();
              
			mte_qp_url =  base_url + 'question_paper/manage_mte_qp/generate_mte_model_qp/' + pgm_id + '/' + crclm_id + '/' + term_id + '/' + crs_id + '/' + ao_id + '/' + qpd_type;
			var mte_rubrics = base_url + 'question_paper/mte_rubrics/mte_add_edit_rubrics/'+ pgm_id + '/' + crclm_id + '/' + term_id + '/' + crs_id + '/' + qpd_type; 
			  var button = '<button type="button" id="define_mte_rubrics" class="btn btn-success" data-dismiss="modal" aria-hidden="true" data-rubrics_link = "' + mte_rubrics + '" ><i class="icon-ok icon-white"></i> Define Rubrics </button>';
			if(clo_bl_flag == 1 && clo_bl_status == 0){
						var data = 'Cannot Add / Edit Question Paper as Blooms Level are mandatory for this course . <br/> Bloom\'s Level are missing in Course Outcome . ';			
						button  += '<button id="" type="button" data-link = "curriculum/clo"  data-type_name = " Map Bloom Level "  data-error_mag = "'+ data +'"  class="topic_not_defined btn btn-primary"  ><i class="icon-ok icon-white"></i> Create New ' + entity_mte + '</button>';
			}
			else if(topic_status == 1 && topic_count == 0){
						var data = 'Cannot Create Question Paper as topics are not defined for this Course.';				
						button  += '<button id="" type="button" data-link = "curriculum/topic"  data-type_name = " Add Topics"  data-error_mag = "'+ data +'"  class="topic_not_defined btn btn-primary"  ><i class="icon-ok icon-white"></i> Create New ' + entity_mte + '</button>';
					
			}else{
					button  += '<button id="mte_qp_creation" type="button" class="cancel btn btn-primary" data-dismiss="modal"  abbr = "' + mte_qp_url + '"><i class="icon-ok icon-white"></i> Create New ' + entity_mte + '</button>';
			}		
			 button +='<button id="btn_close" type="button" class="btn btn-danger"data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>';
			$('#modal_footer').html(button); 
			$("#mte_qp_modal").animate({
				"width" : "950px",
				"margin-left" : "-450px",
				"margin-right" : "-300px"
			}, 600, 'linear');			
            /*             if(msg.tee_qp == 0 ){
                            $('#analysis_details').hide();
                        }else{
                            $('#analysis_details').show();
                        } */
			$('#mte_qp_modal').modal('show');

		}

	});

});

function regenerate_mte_data(crs_id){	
	var qp_url = $('#abbr_address').val();
	var pgm_id =  $('#program').val();
	var crclm_id = $('#curriculum').val();
	var term_id = $('#term').val();
	var qpd_type = 3;
	var ao_id ='';
	var topic_count = $(this).attr('data-topic_count'); 
	var topic_status = $(this).attr('data-topic_staus');
	var clo_bl_flag =  $(this).attr('data-clo_bl_flag');
	var clo_bl_status =  $(this).attr('data-bl_status');
	
	
	$.ajax({
		type : "POST",
		url : qp_url,
		dataType : 'JSON',
		success : function (msg) {
            console.log();			
			$('#modal_title').empty();
			$('#modal_title').html('' + entity_mte + ' Question Paper(s)/Rubrics List');
			$('#mte_qp_modal_text').html(msg.qp_table);
                         $('#rubrics_table_display_div').empty();
                        $('#rubrics_table_display_div').html(msg.rubrics_table); 
			 $('#modal_footer').empty();
              
			mte_qp_url =  base_url + 'question_paper/manage_mte_qp/generate_mte_model_qp/' + pgm_id + '/' + crclm_id + '/' + term_id + '/' + crs_id + '/' + ao_id + '/' + qpd_type;
			var mte_rubrics = base_url + 'question_paper/mte_rubrics/mte_add_edit_rubrics/'+ pgm_id + '/' + crclm_id + '/' + term_id + '/' + crs_id + '/' + qpd_type; 
			  var button = '<button type="button" id="define_mte_rubrics" class="btn btn-success" data-dismiss="modal" aria-hidden="true" data-rubrics_link = "' + mte_rubrics + '" ><i class="icon-ok icon-white"></i> Define Rubrics </button>';
			if(clo_bl_flag == 1 && clo_bl_status == 0){
						var data = 'Cannot Add / Edit Question Paper as Blooms Level are mandatory for this course . <br/> Bloom\'s Level are missing in Course Outcome . ';			
						button  += '<button id="" type="button" data-link = "curriculum/clo"  data-type_name = " Map Bloom Level "  data-error_mag = "'+ data +'"  class="topic_not_defined btn btn-primary"  ><i class="icon-ok icon-white"></i> Create New ' + entity_mte + '</button>';
			}
			else if(topic_status == 1 && topic_count == 0){
						var data = 'Cannot Create Question Paper as topics are not defined for this Course.';				
						button  += '<button id="" type="button" data-link = "curriculum/topic"  data-type_name = " Add Topics"  data-error_mag = "'+ data +'"  class="topic_not_defined btn btn-primary"  ><i class="icon-ok icon-white"></i> Create New ' + entity_mte + '</button>';
					
			}else{
					button  += '<button id="mte_qp_creation" type="button" class="cancel btn btn-primary" data-dismiss="modal"  abbr = "' + mte_qp_url + '"><i class="icon-ok icon-white"></i> Create New ' + entity_mte + '</button>';
			}		
			 button +='<button id="btn_close" type="button" class="btn btn-danger"data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>';
			$('#modal_footer').html(button); 
			$("#mte_qp_modal").animate({
				"width" : "950px",
				"margin-left" : "-450px",
				"margin-right" : "-300px"
			}, 600, 'linear');			
            /*             if(msg.tee_qp == 0 ){
                            $('#analysis_details').hide();
                        }else{
                            $('#analysis_details').show();
                        } */
			$('#mte_qp_modal').modal('show');

		}

	});

}

$('#mte_qp_modal').on('click', '.delete_individual_qp', function () {
	table_row = $(this).closest("tr").get(0);
	var delete_qp_url = $(this).attr('abbr');
	$('#mte_qp_delete_modal').modal('show');
	$('#mte_qp_delete_ok').attr('abbr', delete_qp_url);
});

/*
 * Edit Rubrics Criteria functions starts from here.
 */
$("#mte_qp_modal").on('click','.edit_mte_rubrics_data',function(){
   var page_link = $(this).attr('tee_rubrics_abbr');
   window.location = page_link;
});

$('#mte_qp_delete_modal').on('click', '#mte_qp_delete_ok', function () {
	var delete_qp_url = $(this).attr('abbr');	
	table_row.remove();
	$.ajax({
		type : "POST",
		url : delete_qp_url,
		async : false,
		dataType : 'JSON',
		success : function (msg) {		
		}
	});
});
$('#mte_qp_modal').on('click', '#mte_qp_creation', function () {
	var qp_url = $('#mte_qp_creation').attr('abbr');
	window.location = qp_url;
});


$('#mte_qp_modal').on('click', '.view_qp', function () {
	document.getElementById('qp_content_display').innerHTML = "";
	$('#loading').prependTo($('#qp_content_display'));
	$('#loading').show();
	var abbr_val = $(this).attr('abbr');
	var data = abbr_val.split("/");
	var pgmtype = data[0];
	var crclm_id = data[1];
	var term_id = data[2];
	var crs_id = data[3];
	var qp_type = data[4];
	var qpd_id = data[5];
	var post_data = {
		'pgmtype' : pgmtype,
		'crclm_id' : crclm_id,
		'term_id' : term_id,
		'crs_id' : crs_id,
		'qp_type' : qp_type,
		'qpd_id' : qpd_id
	}
	$.ajax({
		type : "POST",
		url : base_url + 'question_paper/manage_mte_qp/generate_model_mte_qp',
		data : post_data,
		cache : false,
		success : function (msg) {
			document.getElementById('qp_content_display').innerHTML = msg;
			$('#loading').hide();
			$('#qp_table_data').dataTable().fnDestroy();
			$('#qp_table_data').dataTable({
				"fnDrawCallback" : function () {
					$('.group').parent().css({
						'background-color' : '#C7C5C5'
					});
				},
				"bPaginate" : false,
				"bFilter" : false,
				"bInfo" : false,
				//"aaSorting" : [[1, 'asc']],

			}).rowGrouping({
				iGroupingColumnIndex : 0,
				bHideGroupingColumn : true
			});
			//BloomsPlannedCoverageDistribution

			$('#chart1').empty();
			$('#chart2').empty();
			$('#chart3').empty();
			$('#chart4').empty();
		/* 	$('#bloomslevelplannedcoveragedistribution > tbody:first').empty();
			$('#bloomslevelplannedcoveragedistribution_note').empty(); */
			$('#bloomslevelplannedmarksdistribution > tbody:first').empty();
			$('#bloomslevelplannedmarksdistribution_note').empty();
			$('#coplannedcoveragesdistribution > tbody:first').empty();
			$('#coplannedcoveragesdistribution_note').empty();
			$('#topicplannedcoveragesdistribution > tbody:first').empty();
			$('#topicplannedcoveragesdistribution_note').empty();
			var plot1,
			plot2,
			plot3;
			var BloomsLevel = $('#BloomsLevel').val();
			var PlannedPercentageDistribution = $('#PlannedPercentageDistribution').val();
			var ActualPercentageDistribution = $('#ActualPercentageDistribution').val();
			var BloomsLevelDescription = $('#BloomsLevelDescription').val();
			var pln = PlannedPercentageDistribution.split(",");
			var actual = ActualPercentageDistribution.split(",");
			var ticks = BloomsLevel.split(",");
			var level = BloomsLevelDescription.split(",");
			var i = 0;
			var s1_arr = new Array();
			$.each(pln, function () {
				s1_arr.push(Number(pln[i++]));
			});
			var j = 0;
			var s2_arr = new Array();
			$.each(actual, function () {
				s2_arr.push(Number(actual[j++]));
			});


			//BloomsLevelMarksDistribution
			var blooms_level_marks_dist = $('#blooms_level_marks_dist').val();
			var total_marks_marks_dist = $('#total_marks_marks_dist').val();
			var percentage_distribution_marks_dist = $('#percentage_distribution_marks_dist').val();
			var bloom_level_marks_desc = $('#bloom_level_marks_description').val();
			blooms_level = blooms_level_marks_dist.split(",");
			total_marks_dist = total_marks_marks_dist.split(",");
			percentage_dist = percentage_distribution_marks_dist.split(",");
			bloom_lvl_marks_desc = bloom_level_marks_desc.split(",");
			actual_data = new Array();
			var i = 0;
			var j = 0;
			$.each(blooms_level, function () {
				var bloom_lvl = blooms_level[i];
				var percent_distr = percentage_dist[i];
				data = new Array();
				data.push(bloom_lvl, Number(percent_distr));
				i++;
				actual_data[j++] = data;
			});
			plot2 = jQuery.jqplot('chart2', [actual_data], {
					title : {
						text : '', //Blooms Level Planned Marks Distribution',
						show : true
					},
					seriesDefaults : {
						renderer : jQuery.jqplot.PieRenderer,
						rendererOptions : {
							fill : true,
							showDataLabels : true,
							sliceMargin : 4,
							lineWidth : 5,
							dataLabelFormatString : '%.2f%'
						}
					},
					legend : {
						show : true,
						location : 'ne'
					},
					highlighter : {
						show : true,
						tooltipLocation : 's',
						tooltipAxes : 'y',
						useAxesFormatters : false,
						tooltipFormatString : '%s',
						tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
							return bloom_lvl_marks_desc[pointIndex];
						}
					}
				});
			$('#bloomslevelplannedmarksdistribution > thead:first').html('<tr><td><center><b>Blooms Level</b></center></td><td><center><b>Marks Distribution</b></center></td><td><center><b>% Distribution</b></center></td></tr>');
			var l = 0;
			$.each(blooms_level, function () {
				$('#bloomslevelplannedmarksdistribution > tbody:last').append('<tr><td><center>' + blooms_level[l] + '</center></td><td><center>' + total_marks_dist[l] + '</center></td><td><center>' + percentage_dist[l] + ' %</center></td></tr>');
				l++;
			});
			$('#bloomslevelplannedmarksdistribution_note').append('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above pie chart depicts the individual Blooms Level actual marks percentage distribution as in the question paper.</td></tr><tr><td> <b> X = Individual Bloom\'s Level marks  <br/> Y = Sum of all Bloom\'s Level marks <br/> Distribution (%) = (X / Y) * 100 </b> </td></tr></tbody></table></div>');

			//COPlannedCoverageDistribution
			var clo_code = $('#clo_code').val();
			var clo_total_marks_dist = $('#clo_total_marks_dist').val();
			var clo_percentage_dist = $('#clo_percentage_dist').val();
			var clo_statement_dist = $('#clo_statement_dist').val();
			clo_code = clo_code.split(",");
			clo_total_marks_dist = clo_total_marks_dist.split(",");
			clo_percentage_dist = clo_percentage_dist.split(",");
			clo_statement_dist = clo_statement_dist.split(",");
			actual_data = new Array();
			var i = 0;
			var j = 0;
			$.each(clo_code, function () {
				var clo_code_data = clo_code[i];
				var clo_percentage_dist_data = clo_percentage_dist[i];
				data = new Array();
				data.push(clo_code_data, Number(clo_percentage_dist_data));
				i++;
				actual_data[j++] = data;
			});
			plot3 = jQuery.jqplot('chart3', [actual_data], {
					title : {
						text : '', //Blooms Level Planned Marks Distribution',
						show : true
					},
					seriesDefaults : {
						renderer : jQuery.jqplot.PieRenderer,
						rendererOptions : {
							fill : true,
							showDataLabels : true,
							sliceMargin : 4,
							lineWidth : 5,
							dataLabelFormatString : '%.2f%'
						}
					},
					legend : {
						show : true,
						location : 'ne'
					},
					highlighter : {
						show : true,
						tooltipLocation : 's',
						tooltipAxes : 'y',
						useAxesFormatters : false,
						tooltipFormatString : '%s',
						tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
							return clo_statement_dist[pointIndex];
						}
					}
				});
			$('#coplannedcoveragesdistribution > thead:first').html('<tr><td><center><b>' + entity_clo + ' Level</b></center></td><td><center><b>Planned Marks</b></center></td><td><center><b>Planned Distribution</b></center></td></tr>');
			var m = 0;
			$.each(clo_code, function () {
				$('#coplannedcoveragesdistribution > tbody:last').append('<tr><td><center>' + clo_code[m] + '</center></td><td><center>' + clo_total_marks_dist[m] + '</center></td><td><center>' + clo_percentage_dist[m] + ' %</center></td></tr>');
				m++;
			});
			$('#coplannedcoveragesdistribution_note').append('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above pie chart depicts the individual Course Outcome(CO) wise actual marks percentage distribution as in the question paper.</td></tr><tr><td><b> 	X = Individual '+ entity_clo_full_singular +' marks <br/> Y = Sum of all '+ entity_clo_full +' marks <br/> Planned Distribution (%) = (X / Y) * 100 </b> </td></tr></tbody></table></div>');

			//topicCoverageDistribution
			var topic_title = $('#topic_title').val();
			var topic_marks_dist = $('#topic_marks_dist').val();
			var topic_percentage_dist = $('#topic_percentage_dist').val();
			topic_title = topic_title.split(",");
			topic_marks_dist = topic_marks_dist.split(",");
			topic_percentage_dist = topic_percentage_dist.split(",");
			actual_topic_data = new Array();
			var i = 0;
			var j = 0;
			$.each(topic_title, function () {
				var topic_title_data = topic_title[i];
				var topic_percentage_dist_data = topic_percentage_dist[i];
				topic_data = new Array();
				topic_data.push(topic_title_data, Number(topic_percentage_dist_data));
				i++;
				actual_topic_data[j++] = topic_data;
			});
			topic_chart_plot = jQuery.jqplot('chart4', [actual_topic_data], {
					title : {
						text : '', //Blooms Level Planned Marks Distribution',
						show : true
					},
					seriesDefaults : {
						renderer : jQuery.jqplot.PieRenderer,
						rendererOptions : {
							fill : true,
							showDataLabels : true,
							sliceMargin : 4,
							lineWidth : 5,
							dataLabelFormatString : '%.2f%'
						}
					},
					legend : {
						show : true,
						location : 'ne'
					},
					highlighter : {
						show : true,
						tooltipLocation : 's',
						tooltipAxes : 'y',
						useAxesFormatters : false,
						tooltipFormatString : '%s',
						tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
							return topic_title[pointIndex];
						}
					}
				});
			$('#topicplannedcoveragesdistribution > thead:first').html('<tr><td><center><b>' + entity_topic + '</b></center></td><td><center><b>Planned Marks</b></center></td><td><center><b>Planned Distribution</b></center></td></tr>');
			var m = 0;
			$.each(topic_title, function () {
				console.log(topic_title[m]);
				$('#topicplannedcoveragesdistribution > tbody:last').append('<tr><td><center>' + topic_title[m] + '</center></td><td><center>' + topic_marks_dist[m] + '</center></td><td><center>' + topic_percentage_dist[m] + ' %</center></td></tr>');
				m++;
			});
			$('#topicplannedcoveragesdistribution_note').append('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above pie chart depicts the ' + entity_topic + ' Coverage wise actual marks percentage distribution as in the question paper.</td></tr><tr><td><b> Planned Distribution % = (Sum(Count of individual ' + entity_topic + ' marks) * 100) / (Total marks)</b></td></tr></tbody></table></div>');
			$('.myModalQPdisplay').on('shown', function () {
				plot1.replot();
				plot2.replot();
				plot3.replot();
				topic_chart_plot.replot();
			});
		}
	});
	$('.myModalQPdisplay').modal('show');
});


$('#mte_qp_list').on('click', '.import_mte_qp', function () {
    var crclm_id = $(this).attr('data-crclm_id');
    var term_id = $(this).attr('data-term_id');
    var crs_id = $(this).attr('data-course_id');
/*     var ao_id = $(this).attr('data-ao_id');
    var ao_name = $(this).attr('data-ao_name');
    var sec_name = $(this).attr('data-section_name'); */
    var post_data = {'crclm_id': crclm_id, 'term_id': term_id, 'crs_id': crs_id};
    var crclm_name = $('#curriculum option:selected').text(); 
    var term_name = $('#term option:selected').text();
    var crs_name = $(this).attr('data-crs_title');;
    $('#curriculum_id').val(crclm_id);
    $('#qpterm_id').val(term_id);
    $('#course_id').val(crs_id);
    //$('#occasion_ao_id').val(ao_id);
    $('#import_qp_button').prop('disabled', true);
    $.ajax({type: "POST",
	url: base_url + 'question_paper/manage_mte_qp/get_dept_list',
	data: post_data,
	//dataType: 'json',
	success: function (data) {
	    $('#crlcm_name_font').html(crclm_name);
	    $('#term_name_font').empty();
	    $('#term_name_font').html(term_name);
	    $('#crs_name_font').empty();
	    $('#crs_name_font').html(crs_name);
            $('#sec_name_font').empty();
	    //$('#sec_name_font').html(sec_name);
	    $('#ao_name_font').empty();
	    //$('#ao_name_font').html(ao_name);
	    $('#pop_dept_list').empty();
	    $('#pop_dept_list').html(data);
	    $('#pop_prog_list').empty();
	    $('#pop_prog_list').html($('<option value>Select Program</option>'));
	    $('#pop_crclm_list').empty();
	    $('#pop_crclm_list').html($('<option value> Select Curriculum</option>'));
	    $('#pop_term_list').empty();
	    $('#pop_term_list').append($('<option value> Select Term</option>'));
	    $('#pop_term_list').trigger("chosen:updated");
	    $('#pop_course_list').empty();
	    $('#pop_course_list').append($('<option value> Select Course</option>'));
	    $('#pop_course_list').trigger("chosen:updated");
	    $('#check_box_div').remove();
	    $('#import_occasions_question_paper').modal('show');
	}
    });


});

/*
 * Function to fetch the program list
 */
$('#import_occasions_question_paper').on('change', '#pop_dept_list', function () {
    $('#import_qp_button').prop('disabled',true);
    var dept_id = $(this).val();
    var post_data = {'dept_id': dept_id};
    $.ajax({type: "POST",
	url: base_url + 'question_paper/manage_mte_qp/get_pgm_list',
	data: post_data,
	//dataType: 'json',
	success: function (data) {
	    $('#pop_prog_list').empty();
	    $('#pop_prog_list').html(data);
            $('#pop_crclm_list').empty();
	    $('#pop_crclm_list').append($('<option>Select Curriculum</option>'));
            $('#pop_term_list').empty();
	    $('#pop_term_list').append($('<option>Select Term</option>'));
            $('#pop_course_list').empty();
	    $('#pop_course_list').append($('<option>Select Course</option>'));
            $('#cia_qp_list_table_wrapper').empty();

	}
    });

});


/*
 * Function to fetch the term list
 */
$('#import_occasions_question_paper').on('change', '#pop_crclm_list', function () {
    $('#import_qp_button').prop('disabled',true);
    var dept_id = $('#pop_dept_list').val();
    var prog_id = $('#pop_prog_list').val();
    var crclm_id = $(this).val();
    var post_data = {'dept_id': dept_id, 'pgm_id': prog_id, 'crclm_id': crclm_id};
    $.ajax({type: "POST",
	url: base_url + 'question_paper/manage_mte_qp/get_term_list',
	data: post_data,
	//dataType: 'json',
	success: function (data) {
	    $('#pop_term_list').empty();
	    $('#pop_term_list').html(data);
           
            $('#pop_course_list').empty();
	    $('#pop_course_list').append($('<option>Select Course</option>'));
            $('#cia_qp_list_table_wrapper').empty();

	}
    });

});

/*
 * Function to fetch the program curriculum list
 */
$('#import_occasions_question_paper').on('change', '#pop_prog_list', function () {
console.log("");
    $('#import_qp_button').prop('disabled',true);
    var dept_id = $('#pop_dept_list').val();
    var prog_id = $(this).val();
    var post_data = {'dept_id': dept_id, 'pgm_id': prog_id};
    $.ajax({type: "POST",
	url: base_url + 'question_paper/manage_mte_qp/get_crclm_list',
	data: post_data,
	//dataType: 'json',
	success: function (data) {
	    $('#pop_crclm_list').empty();
	    $('#pop_crclm_list').html(data);
            
            $('#pop_term_list').empty();
	    $('#pop_term_list').append($('<option>Select Term</option>'));
            $('#pop_course_list').empty();
	    $('#pop_course_list').append($('<option>Select Course</option>'));
            $('#cia_qp_list_table_wrapper').empty();
            

	}
    });

});


/*
 * Function to fetch the course list
 */
$('#import_occasions_question_paper').on('change', '#pop_term_list', function () {
    $('#import_qp_button').prop('disabled',true);
    var to_crs_id = $('#course_id').val();
    var dept_id = $('#pop_dept_list').val();
    var prog_id = $('#pop_prog_list').val();
    var crclm_id = $('#pop_crclm_list').val();
    var term_id = $(this).val();
    var post_data = {'dept_id': dept_id, 'pgm_id': prog_id, 'crclm_id': crclm_id, 'term_id': term_id, 'course_id': to_crs_id};
    $.ajax({type: "POST",
	url: base_url + 'question_paper/manage_mte_qp/get_course_list',
	data: post_data,
	//dataType: 'json',
	success: function (data) {
	    $('#pop_course_list').empty();
	    $('#pop_course_list').html(data);
           
            $('#cia_qp_list_table_wrapper').empty();

	}
    });

});

/*
 * Function to fetch the list of CIA QP
 */
$('#import_occasions_question_paper').on('change', '#pop_course_list', function () {
    $('#import_qp_button').prop('disabled',true);
    var dept_id = $('#pop_dept_list').val();
    var prog_id = $('#pop_prog_list').val();
    var crclm_id = $('#pop_crclm_list').val();
    var term_id = $('#pop_term_list').val();
    var course_id = $(this).val();
    var ao_id = $('#occasion_ao_id').val();
    var post_data = {'dept_id': dept_id, 'pgm_id': prog_id, 'crclm_id': crclm_id, 'term_id': term_id, 'crs_id': course_id, 'ao_id': ao_id};
    $.ajax({type: "POST",
	url: base_url + 'question_paper/manage_mte_qp/get_qp_list',
	data: post_data,
	//dataType: 'json',
	success: function (data) {
	    $('#qp_list_div').empty();
	    $('#qp_list_div').html(data);
	    $('#cia_qp_list_table').dataTable({
		"bPaginate": false,
		"bFilter": false,
		"bSearchable": false,
		"fnDrawCallback": function () {
		    $('.group').parent().css({'background-color': '#C7C5C5'});
		},
		"aoColumnDefs": [
		    {"sType": "natural", "aTargets": [1]}
		],
		"sPaginationType": "bootstrap"

	    }).rowGrouping({
                iGroupingColumnIndex: 0,
		bHideGroupingColumn: true});


	}
    });

});

$('#import_occasions_question_paper').on('click', '.qp_list', function () {

    if ($(this).is(':checked')) {
	var ao_id = $(this).attr('data-ao_id'); 
	$('#import_ao_id').val(ao_id);	
	$('#import_qp_button').prop('disabled', false);
	
	
    } else {
	$('#import_qp_button').prop('disabled', true);
    }

});



$('#import_occasions_question_paper').on('click', '#import_qp_button', function () {
    
    var validate = $('#select_form').valid();
    var qpd_id;
    $('.qp_list').each(function () {
	if ($(this).is(':checked')) {
	    qpd_id = $(this).val();
	}
    });
    
    var crclm_id = $('#curriculum_id').val();
    var term_id = $('#qpterm_id').val();
    var crs_id = $('#course_id').val();
    var ao_id = $('#import_ao_id').val();
    var post_data = {'qpd_id': qpd_id, 'ao_id': ao_id, 'crs_id': crs_id, 'term_id': term_id, 'crclm_id': crclm_id };
    if (validate == true) {
      //  $('#loading').show();
        if(qpd_id){
        $('#import_qp_button').prop('disabled', false);
        }else{
            $('#import_qp_button').prop('disabled', true);
        }
		
			$.ajax({type: "POST",
			url: base_url + 'question_paper/manage_mte_qp/get_qp_data_import',
			data: post_data,
			//dataType: 'json',
			success: function (msg) {
			    if ($.trim(msg) == "true") {
				var data_options = '{"text":"QP import is successfull.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
				var options = $.parseJSON(data_options);
				noty(options);
				$('#pop_course_list').val('');
				$('#qp_list_div').empty();$('#qp_list_div').html('');
				$('#loading').hide();
			    }
			}
		    });
						
    }
});

  $(document).on('click','#define_mte_rubrics',function(){
            var url_link = $(this).attr('data-rubrics_link');			
            window.location=url_link;
  });

  /*
 * Function to Delete the Rubrics 
 */
$("#mte_qp_modal").on('click','.delete_individual_mte_rubrics_data',function(){
    table_row = $(this).closest("tr").get(0);
    var qpd_id = $(this).attr('data-qpd_id');
    var ao_method_id = $(this).attr('data-ao_method_id');
    
    $('#mte_rubrics_delete_ok').attr('data-qpd_id', qpd_id);
    $('#mte_rubrics_delete_ok').attr('data-ao_method_id', ao_method_id);
    $('#mte_rubrics_modal').modal('show');
});

$('#mte_rubrics_modal').on('click', '#mte_rubrics_delete_ok', function () {
	var qpd_id = $(this).attr('data-qpd_id');
	var ao_method_id = $(this).attr('data-ao_method_id');
        var post_data = {
            'qpd_id':qpd_id,
            'ao_method_id':ao_method_id,
        };
	table_row.remove();
	$.ajax({
		type : "POST",
		url : base_url + 'question_paper/mte_rubrics/delete_mte_rubrics_data',
		data : post_data,
		dataType : 'html',
		success : function (msg) {
                    
                }
	});
});

$(document).on('click','.cant_edit_tee_rubrics_data',function(){
   $('#cant_edit_rubrics_modal').modal('show'); 
});

$(document).on('click','.cant_delete_individual_mte_rubrics_data',function(){
   $('#cant_delete_rubrics_modal').modal('show'); 
});

$(document).on('click','.show_rubrics_pending_modal',function(){
   $('#').modal('show'); 
});

$('#mte_qp_modal').on('click', '.marks_uploaded_already1', function () {
    $('#marks_uploaded_already_modal').modal('show');
});

$('#mte_qp_modal').on('click', '.mte_qp_delete_warning', function () {
    $('#mte_qp_delete_warning').modal('show');
});
$(document).on('click','.view_mte_rubrics_data',function(){
    var ao_method_id = $(this).attr('data-ao_method_id');
    var qpd_id = $(this).attr('data-qpd_id');
    var crclm_id = $(this).attr('data-crclm_id');
    var term_id = $(this).attr('data-term_id');
    var crs_id = $(this).attr('data-crs_id');
    var post_data = {
                        'ao_method_id':ao_method_id,
                        'qpd_id':qpd_id,
                        'crclm_id':crclm_id,
                        'term_id':term_id,
                        'crs_id':crs_id,
                    };
    $.ajax({type: "POST",
	url: base_url + 'question_paper/mte_rubrics/get_rubrics_table_modal_view',
	data: post_data,
	dataType: 'html',
	success: function (data) {
            if($.trim(data)!='false'){
            $('#rubrics_table_div').empty();
            $('#rubrics_table_div').html(data);
        }else{
            $('#rubrics_table_div').empty();
            $('#rubrics_table_div').html('<center><font color="red"><b>No Data to Display</b></font></center>');
        }
            $('#rubrics_table_view_modal').modal('show');
            
        }
    });
});
$(document).on('click','#export_to_pdf',function(){
   var clone_data = $('#rubrics_table_div').clone().html();
  $('#report_in_pdf').val(clone_data);
  $('#rubrics_report').submit();
});

