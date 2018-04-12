var base_url = $('#get_base_url').val();

function empty_direct_attainment_divs() {
	$('#po_attainment_nav').empty();
	$('#po_attainment_no_data').empty();
	$('#po_attainment_chart_1').empty();
	$('#course_attainment_nav').empty();
	$('#course_attainment_no_data').empty();
	$('#po_attainment_chart_2').empty();
	$('#chart1').empty();
	$('#po_attainment_note').empty();
	$('#chart2').empty();
	$('#course_attainment_note').empty();
	$('#po_attainment_div').empty();
	$('#po_attainment_body').empty();
	$('#course_attainment_body').empty();

}
function empty_indirect_attainment_divs() {
	$('#po_level_indirect_div').empty();
	$('#chart_plot_po_indirect_attain').empty();
	$('#graph_val').empty();
}
function empty_direct_indirect_attainment_divs() {
	$('#po_attainment_no_data_survey').empty();
	$('#po_level_comparison_nav').empty();
	$('#chart_plot_7').empty();
}
function empty_all_divs() {
	empty_direct_attainment_divs();
	empty_indirect_attainment_divs();
	empty_direct_indirect_attainment_divs();
	$("#survey_id option:selected").removeAttr("selected");
}

/* Function is used to set the default values to the various credits fields.
 * @param -
 * @returns-
 */
function setToggleStatusValues() {
	$('#po_attainment_tbl_div').empty();
	$('#po_attainmnet_graph').empty();
	if ($('#core_course').is(':checked')) {
		document.getElementById('core_crs_id').value = 1;
	} else {
		document.getElementById('core_crs_id').value = 0;
	}
	empty_all_divs();
	$('.survey_title_shv').each(function () {
		$(this).val('');
	});
	$('.max_wgt').each(function () {
		$(this).val('');
	});
	select_term();
}

$(document).ready(function () {
	//Term dropdown
	$('.term_id').multiselect({
		includeSelectAllOption : true,
		maxHeight : 200,
		buttonWidth : 165,
		numberDisplayed : 2,
		nSelectedText : 'selected',
		nonSelectedText : "Select Terms",
			 templates: {
                button: '<button type="button" class="multiselect btn btn-link  dropdown-toggle" data-toggle="dropdown"></button>'
     }
	});	
});
$('#crclm_id').on('change',function(){
	$('#po_attainment_tbl_div').empty();
	$('#po_attainmnet_graph').empty();
    
});
//List Page
if ($.cookie('remember_crclm') != null) {
	// set the option to selected that corresponds to what the cookie is set to
	$('#crclm_id option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
	select_term();
        $('#crclm_id').trigger('change');
}
function disable_export_to_doc() {
    $('.export_doc').prop('disabled', true);
}
function select_term() {
	empty_all_divs();
        disable_export_to_doc();
	$.cookie('remember_crclm', $('#crclm_id option:selected').val(), {
		expires : 90,
		path : '/'
	});
	var term_list_path = base_url + 'assessment_attainment/tier1_po_attainment/select_term';
	var data_val = $('#crclm_id').val();
	var post_data = {
		'crclm_id' : data_val
	}

	$.ajax({
		type : "POST",
		url : term_list_path,
		data : post_data,
		success : function (msg) {
			$('#term').html(msg);
			$('#term').multiselect('rebuild');

			if ($.cookie('remember_term') != null) {
				// set the option to selected that corresponds to what the cookie is set to
				$('#term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
				select_type();
				select_survey();
				select_survey_comparison();
			}
                        select_survey();
		}
	});
}

function select_type() {
	empty_all_divs();
	$.cookie('remember_term', $('#term option:selected').val(), {
		expires : 90,
		path : '/'
	});

	var term = $('#term').val();
	if (term) {
		$('#type_data').html('<option value=0>Select Type</option><option value=2>' + entity_cie + '</option><option value=1>' + entity_see + '</option><option value="cia_tee">Both ' + entity_cie + ' & ' + entity_see + '</option>');
	} else {
		$('#type_data').html('<option value="0">Select Type</option>');
	}
}

function display() {
	empty_all_divs();
        disable_export_to_doc();
	$('#loading').show();
	var crclm_id = $('#crclm_id').val();
	var term = $('#term').val();
	//var type_data = $('#type_data').val();
	var po_attainment_type = $('#po_attainment_type').val(); //Added by Shivaraj B
	var core_crs_id = $('#core_crs_id').val();
	var post_data = {
		'crclm_id' : crclm_id,
		'term' : term,
		//'type_data' : type_data,
		'po_attainment_type' : po_attainment_type,
		'core_crs_id' : core_crs_id,
	}
		if(term){
                    
				$.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_po_attainment/tier1_POThreshholdAttainment',
			data : post_data,
			dataType : 'JSON',
			success : function (success_msg) {
						var parameter_list = new Array();
                                            var colors_list = new Array();
					if(success_msg.msg == 'true'){
                                            $('#export_tab1_to_doc').prop('disabled', false);
						$('#po_attainment_tbl_div').empty();
						$('#po_attainment_tbl_div').html(success_msg.table);
						$('#po_attainment_tbl_div').append(success_msg.note);
						$('#po_attainmnet_graph').empty();
						var ticks = success_msg.po_reference;
						parameter_list.push(success_msg.po_minthreshold);
						colors_list.push("#3efc70");
						if(success_msg.po_select == 'ALL'){
							parameter_list.push(success_msg.avg_po_array); colors_list.push("#fe9a2e");
							parameter_list.push(success_msg.threshold_po_array); colors_list.push("#4BB2C5");
							parameter_list.push(success_msg.hml_weighted_average_da); colors_list.push("#f781f3"); 
							parameter_list.push(success_msg.hml_weighted_multiply_maplevel_da); colors_list.push("#c5b47f");				
						var note ="ALL";
						}else{
								for(m = 0; m < (success_msg.org_array.length) ; m++){
									val = success_msg.org_array[m]; 
									if(val == 'avg_po_attainment'){ parameter_list.push(success_msg.avg_po_array); colors_list.push("#fe9a2e"); }
									if(val == 'po_threshold_attainment'){parameter_list.push(success_msg.threshold_po_array); colors_list.push("#4BB2C5");}
									if(val == 'hml_weighted_average_da_avg'){parameter_list.push(success_msg.hml_weighted_average_da); colors_list.push("#f781f3"); }
									if(val == 'hml_weighted_multiply_maplevel_da_avg'){parameter_list.push(success_msg.hml_weighted_multiply_maplevel_da); colors_list.push("#c5b47f"); }
								}
						
						}
					var plot1 = $.jqplot('po_attainmnet_graph', parameter_list , {
							seriesColors : colors_list,
							seriesDefaults : {
								renderer : $.jqplot.BarRenderer,
								rendererOptions : {
									barWidth : 8,
									fillToZero : true,									
									numberRows: '1',
                                    //barPadding: 15
								},
								pointLabels : {
									show : true
								}
							},
							series : [
								{
									label : ' Threshold %'
								}, {
									label : 'Avg. based Attainment %'
									
								}, {
                                     label : 'Threshold based Attainment %'
                                }, {
                                     label : 'Map Level Weighted Attainment %'
                                }, {
                                     label : 'Map Level Weighted * Mapped Value Attainment %'
                                }
							],
							highlighter : {
								show : true,
								tooltipLocation : 'e', // location of tooltip: n, ne, e, se, s, sw, w, nw.
								tooltipAxes : 'x', // which axis values to display in the tooltip, x, y or both.
								showMarker : false,
								tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
									return success_msg.po_stmt_data[pointIndex];
								}
							},
							/* legend : {
								show : true,
								location: 's',
								placement : 'outsideGrid',
								 rendererOptions: {
									  numberRows: '1',
									},
							}, */	
						legend:{
							renderer: $.jqplot.EnhancedLegendRenderer,
							show: true,
							location: 's',
							placement : 'outsideGrid',
							rendererOptions: {
								numberRows: 1
							}
						},							
							axes : {
								xaxis : {
									renderer : $.jqplot.CategoryAxisRenderer,
									ticks : ticks
								},
								yaxis : {
									padMin : 10,
									min : 0,
									max : 110,
									tickOptions : {
										formatString : '%.2f%'
									}
								}
							}

                                    });
                                }else{
									$('#po_attainment_tbl_div').empty();
									$('#po_attainmnet_graph').empty();
									$('#po_attainmnet_graph').html('<center><font color="red"><b>No Data to Display</b></font></center>');
								}
                        }
                    });
		}else{
				$('#po_attainment_tbl_div').empty();
				$('#po_attainmnet_graph').empty();
		}
		
				$('#loading').hide();
			

	
}



/*

					legend: {
	// Enable rows and columns in the legend
	renderer: $.jqplot.EnhancedLegendRenderer,
	show: true,
	// place label on top of graph
	location: 'n',
	// place label outsid the graph plot and add space above the graph for no overlay
	placement: 'outsideGrid',
	// set the labels for the legend to the labels array
	labels: labels,
	// set the number of rows and columns for the legend
	marginTop: 0,
	rendererOptions: {
	numberRows: 1,
	numberColumns: ,
	seriesToggle: false,
	disableIEFading: true
	}
	},
*/

//Course Drilldown
$(document).on('click','.po_drilldown',function(){
    
	var crclm_id = $('#crclm_id').val();
	var type_data = $('#type_data').val();
	var term = $('#term').val();
	var po_id = $(this).attr('data-po_id');
	var type_display = $(this).attr('data-type');	
	var type_name = $(this).attr('data-type_name');
	var po_attainment_type = $('#po_attainment_type').val();
	var core_crs_id = $('#core_crs_id').val();
        
    var post_data = {
		'term' : term,
		'po_id' : po_id,
		'crclm_id' : crclm_id,
		'type_data' : type_data,
		'po_attainment_type' : po_attainment_type,
		'core_crs_id' : core_crs_id,
		'type_display' : type_display,
		'type_name': type_name,
	}
        $.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/tier1_po_attainment/tier1_CoursePOAttainment',
		data : post_data,
		dataType : 'JSON',
		success : function (success_msg) {
                    if(success_msg.msg == 'true'){
                        $('#po_statement_div').empty();
                        $('#po_statement_div').html('<b>Program Outcome: </b>'+success_msg.po_stmt);
                        
                        $('#crs_title_crs_code_tble').empty();
                        $('#crs_title_crs_code_tble').html(success_msg.table);
                      $('#course_po_attainment_note').html('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>If <b>'+ so +' Attainment %</b> and <b>Attainment Level</b> are blank, it indicates that CO attainment for the respective courses are not finalized for calculating '+ so +' attainment.</td></tr><tr><td>The above bar graph depicts individual ' + so + ' attainment contributed by courses under selected Terms (Semester).</td></tr></tbody></table></div>');  
                        //plot graph
				
				var ticks = success_msg.sl_no;
//					var s1 = data1;
//					var s2 = data2;
					var po_attainment_graph = $.jqplot('po_attainment_chart_div', [success_msg.po_threshold_data], {
							seriesColors : ["#4BB2C5"],
							seriesDefaults : {
								renderer : $.jqplot.BarRenderer,
								rendererOptions : {
									barWidth : 15,
									fillToZero : true,
                                    barPadding: 15
								},
								pointLabels : {
									show : true
								}
							},
							series : [{
									label : so + ' Attainment %'
									
								},{
									label : 'Avg '+so + ' Attainment %'
                                }
							],
							highlighter : {
								show : true,
								tooltipLocation : 'e', // location of tooltip: n, ne, e, se, s, sw, w, nw.
								tooltipAxes : 'x', // which axis values to display in the tooltip, x, y or both.
								showMarker : false,
								tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
									return success_msg.crs_title[pointIndex] +" ["+ success_msg.crs_code[pointIndex] + "]";
								}
							},
							legend : {
								show : true,
								placement : 'outsideGrid'
							},
							axes : {
								xaxis : {
									renderer : $.jqplot.CategoryAxisRenderer,
									ticks : ticks
								},
								yaxis : {
									padMin : 10,
									min : 0,
									max : 110,
									tickOptions : {
										formatString : '%.2f%'
									}
								}
							}

                                    });
//                        po_attainment_graph.replot();
                            $('#tier1_po_attainmnet_modal').modal('show');
                            $('.tier1_po_attainmnet_modal').on('shown', function () {
					po_attainment_graph.replot();
				});
                        
                    }
                    
                    
                }
            });
        
});
$('.displayDrilldown').live('click', function () {
	//$('#loading').show();
	document.getElementById('drilldown_content_display').innerHTML = "";
	var crclm_id = $('#crclm_id').val();
	var term = $('#term').val();
	var po_id = $(this).attr('abbr');

	var po_attainment_type = $('#po_attainment_type').val();
	var core_crs_id = $('#core_crs_id').val();

	var post_data = {
		'term' : term,
		'po_id' : po_id,
		'crclm_id' : crclm_id,
		'po_attainment_type' : po_attainment_type,
		'core_crs_id' : core_crs_id,
	}
        
        

	$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/tier1_po_attainment/CoursePOAttainment',
		data : post_data,
		dataType : 'JSON',
		success : function (json_graph_data) {
			if (json_graph_data.length > 0) {
				
				value1 = new Array();
				value3 = new Array();
				value4 = new Array();
				crs_id = new Array();
				crs_code = new Array();
				data1 = new Array();
				data3 = new Array();
				data4 = new Array();
				data5 = new Array();
				data6 = new Array();

				po_attainment = new Array();

				if (po_attainment_type == "CO_TARGET") {
					//console.log(value1);
					var i = 0;
					$.each(json_graph_data, function () {
						value3[i] = this['po_statement'];
						value4[i] = this['po_reference'];
						value1[i] = this['poAttainment'];
						crs_id[i] = this['crs_title'];
						crs_code[i] = this['crs_code'];
						//console.log(value1[i]);
						var graph_value = parseFloat(value1[i]);
						if (graph_value.toFixed(2) != 0.00) {
							data1.push(parseFloat(graph_value));
							data3.push(crs_id[i]);
							data4.push(crs_code[i]);
							data5.push(value3[i]);
							data6.push(value4[i]);
							po_attainment.push(parseFloat(this['poAttainment']));
						}
						i++;
					});
					var m = 0;
					$('#po_statement').html('<div class="row-fluid"><div class="span12"><p colspan="2"><center><b>' + student_outcome_full + '</b> :  ' + data6[m] + '.  ' + data5[m] + '</center></p></div></div>');
					$('#drilldown_content_display').html('<div class="row-fluid"><div class="span12"><div class="span6" id="course_po_graph" class="jqplot-target" style="height:250px;"></div><div class="span6"><table border=1 class="table table-bordered" id=course_po_attainment><thead></thead><tbody id="course_attainment_body"></tbody></table></div><div id=course_po_attainment_note class=span12></div></div></div>');
					$('#course_po_attainment > tbody:first').append('<tr><td><center><b>Course Code - Course Title</b></td><td><center><b> ' + so + ' Attainment %</b></center></td></tr>');
					var i =0;
					$.each(json_graph_data, function () {
						console.log(json_graph_data);
						var poAttainment_one = "";
						if (parseFloat(json_graph_data[m].poAttainment).toFixed(2) == 0.00) {
							poAttainment_one = "-";
							$('#course_po_attainment > tbody:last').append('<tr><td><font color="blue">' + json_graph_data[i].crs_code + '</font>-' + json_graph_data[i].crs_title + '</td><td><center>-</center></td></tr>');
						} else {
							poAttainment_one = parseFloat(json_graph_data[m].poAttainment).toFixed(2);
							if (isNaN(poAttainment_one)) {
								$('#course_po_attainment > tbody:last').append('<tr><td><font color="blue">' + json_graph_data[i].crs_code + '</font>-' + json_graph_data[i].crs_title + '</td><td><center>-</center></td></tr>');
							} else {
								$('#course_po_attainment > tbody:last').append('<tr><td><font color="blue">' + json_graph_data[i].crs_code + '</font>-' + json_graph_data[i].crs_title + '</td><td><center>' + poAttainment_one + ' %</center></td></tr>');
							}
						}

						m++;i++;
					});
					$('#course_po_attainment_note').html('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above bar graph depicts Individual PO Attainment by Contributing Courses under a selected Term (Semester).</td></tr></tbody></table></div>');

				} else {
					var i=0;
					$.each(json_graph_data, function () {
						value3[i] = this['po_statement'];
						value4[i] = this['po_reference'];
						value1[i] = this['Attainment'];
						crs_id[i] = this['crs_title'];
						crs_code[i] = this['crs_code'];
						var graph_value = parseFloat(value1[i]);
						if (graph_value.toFixed(2) != 0.00) {
							data1.push(parseFloat(graph_value));
							data3.push(crs_id[i]);
							data4.push(crs_code[i]);
							data5.push(value3[i]);
							data6.push(value4[i]);
							po_attainment.push(parseFloat(this['Attainment']));
						}
						i++;
					});
					var m = 0;
					$('#po_statement').html('<div class="row-fluid"><div class="span12"><p colspan="2"><center><b>' + student_outcome_full + '</b> :  ' + data6[m] + '.  ' + data5[0] + '</center></p></div></div>');
					$('#drilldown_content_display').html('<div class="row-fluid"><div class="span12"><div class="span6" id="course_po_graph" class="jqplot-target" style="height:250px;"></div><div class="span6"><table border=1 class="table table-bordered" id=course_po_attainment><thead></thead><tbody id="course_attainment_body"></tbody></table></div><div id=course_po_attainment_note class=span12></div></div></div>');
					$('#course_po_attainment > tbody:first').append('<tr><td><center><b>Course Code - Course Title</b></td><td><center><b> ' + so + ' Attainment %</b></center></td></tr>');
					var i=0;var m = 0;
					$.each(json_graph_data, function () {
					//	console.log(json_graph_data);
						var poAttainment = "";
						if (json_graph_data[m].Attainment == 0.00) {
							poAttainment = "-";
						} else {
							poAttainment = json_graph_data[m].Attainment+ "%";
						}
						$('#course_po_attainment > tbody:last').append('<tr><td><font color="blue">' + json_graph_data[i].crs_code + '</font>-' + json_graph_data[i].crs_title + '</td><td><center>' + poAttainment + '</center></td></tr>');

						m++;i++
					});
					$('#course_po_attainment_note').html('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above bar graph depicts Individual ' + so + ' Attainment Contributing by Courses undera selected Terms\' (Semesters\').</td></tr></tbody></table></div>');
				} //end of if po attenment type


				var s1 = data1;
				var ticks = data4;
				var plot3 = $.jqplot('course_po_graph', [po_attainment], {
						seriesDefaults : {
							renderer : $.jqplot.BarRenderer,
							rendererOptions : {
								barWidth : 20,
								fillToZero : true
							},
							pointLabels : {
								show : true
							}
						},
						series : [{
								label : so + ' Attainment %'
							}
						],
						highlighter : {
							show : true,
							tooltipLocation : 'e', // location of tooltip: n, ne, e, se, s, sw, w, nw.
							tooltipAxes : 'x', // which axis values to display in the tooltip, x, y or both.
							showMarker : false,
							tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
								return crs_id[pointIndex];
							}
						},
						legend : {
							show : true,
							placement : 'outsideGrid'
						},
						axes : {
							xaxis : {
								renderer : $.jqplot.CategoryAxisRenderer,
								ticks : ticks

							},
							yaxis : {
								padMin : 0,
								min : 0,
								max : 110,
								tickOptions : {
									formatString : '%.2f%'
								}
							}
						}
					});	//plot3.replot();
				$('.myModalQPdisplay_paper_modal_2').on('shown', function () {
					plot3.replot();
				});
			} else {
	
				$('#course_po_attainment_nav').html('<div class="navbar"><div class="navbar-inner-custom">' + student_outcome_full + ' Attainment</div></div>');
				$('#course_po_attainment_no_data').html('<h5><center>' + student_outcome_full + ' Attainment data unavailable for selected term.</center></h5>');
			}
		}
	});

	$('.myModalQPdisplay_paper_modal_2').modal('show');

});

function select_survey() {
	$.cookie('remember_course_indirect', $('#course_indirect option:selected').val(), {
		expires : 90,
		path : '/'
	});
	empty_indirect_attainment_divs();
	var course_list_path = base_url + 'assessment_attainment/tier1_po_attainment/select_survey';
	var data_val = $('#crclm_id').val();
	if (data_val) {
		var post_data = {
			'crclm_id' : data_val
		}
		$.ajax({
			type : "POST",
			url : course_list_path,
			data : post_data,
			success : function (msg) {
				$('#survey_id').html(msg);
				assign_survey_titles(msg); //Added by shivaraj B
				if ($.cookie('remember_survey') != null) {
					// set the option to selected that corresponds to what the cookie is set to
					$('#survey_id option[value="' + $.cookie('remember_survey') + '"]').prop('selected', true);
				}
			}
		});
	} else {
		$('#survey_id').html('<option value="">Select Survey</option>');
	}
}

$('#po_indirect_attainment').on('change', '#survey_id', function () {
	var survey_id = $('#survey_id').val();
	if (survey_id != 0) {
		plot_graphs_indirect_attainment(survey_id);
	} else {
		empty_indirect_attainment_divs();
	}

});

//function plot_graphs_indirect_attainment(survey_id) {
//
//	$('#loading').show();
//	var post_data = {
//		'report_type_val' : 3,
//		'su_for' : 7,
//		'survey_id' : survey_id
//	}
//	var actual_data = [];
//	var po_level_indirect_div = [];
//	var dept_name = [];
//	var su_for_label = 'PO';
//	var i = 1;
//	$.ajax({
//		type : "POST",
//		url : base_url + 'survey/surveys/getSurveyQuestions',
//		data : post_data,
//		dataType : 'html',
//		success : function (survey_data) {
//			$("#po_level_indirect_div").html('');
//			$("#graph_val").html("<center><h3>" + $('#survey_id :selected').text() + "</h3></center>");
//			$("#graph_val").append(survey_data);
//			$('.attainment').each(function () {
//				graphVal = $(this).val();
//				actual_data.push(graphVal);
//				dept_name.push(su_for_label + ' ' + i);
//				i++;
//			});
//			$.ajax({
//				type : 'POST',
//				url : base_url + 'survey/surveys/getSurveyQuestionsForGraph',
//				data : post_data,
//				dataType : 'html',
//				success : function (survey_data_gp) {
//					var i = 0;
//					var data = jQuery.parseJSON(survey_data_gp);
//
//					var data1 = new Array();
//					var data2 = new Array();
//
//					$.each(data, function () {
//						data1.push("PO" + data[i].po_reference);
//						data2.push(Number(data[i].Attaintment));
//						i++;
//
//					});
//					$('#po_level_indirect_div').html('<div class="row-fluid"><div class="span12"></div><div class="span11 rightJustified" ><a id="exportToPDF" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a></div></div><br><div class="navbar"><div class="navbar-inner-custom">' + student_outcome_full + ' ' + student_outcomes_val + ' Indirect Attainment Analysis</div></div>');
//					$('#chart_plot_po_indirect_attain').html('<div class=row-fluid><div class=span12><div id=poindirect_attain_chart_shv style=position:relative; class=jqplot-target></div></div></div>');
//					//$('#chart_plot_po_indirect_attain').html(data);
//
//					var ticks = data1;
//					var s1 = data2;
//					//var s2 = data2;
//					var plot1 = $.jqplot('poindirect_attain_chart_shv', [s1], {
//							seriesColors : ["#4BB2C5"], //, "#4BB2C5"
//							seriesDefaults : {
//								renderer : $.jqplot.BarRenderer,
//								rendererOptions : {
//									barWidth : 15,
//									fillToZero : true
//								},
//								pointLabels : {
//									show : true
//								}
//							},
//							series : [{
//									label : 'PO Attainment %'
//								},
//							],
//							highlighter : {
//								show : true,
//								tooltipLocation : 'e', // location of tooltip: n, ne, e, se, s, sw, w, nw.
//								tooltipAxes : 'x', // which axis values to display in the tooltip, x, y or both.
//								showMarker : false,
//								tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
//									return data1[pointIndex];
//								}
//							},
//							legend : {
//								show : true,
//								placement : 'outsideGrid'
//							},
//							axes : {
//								xaxis : {
//									renderer : $.jqplot.CategoryAxisRenderer,
//									ticks : ticks
//								},
//								yaxis : {
//									padMin : 0,
//									min : 0,
//									max : 100,
//									tickOptions : {
//										formatString : '%.2f%'
//									}
//								}
//							}
//
//						});
//				}
//			});
//			$('#loading').hide();
//		} //End of first success
//	});
//
//}

// Direct Indirect Attainment Tab Script starts here -------------------

function plot_graphs_indirect_attainment(survey_id) {
	$('#graph_val').empty();
	$('#loading').show();
	if (survey_id == "") {
		$('#chart_plot_po_indirect_attain').html('');
		$('#graph_val').html("<center><h4 style='color:red;'>Survey is empty</h4></center>");
		$('#loading').hide();
	} else {

		var post_data = {
			'survey_id' : survey_id,
			'crclm_id' : $('#crclm_id').val(),
		}
		$.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_po_attainment/get_survey_data_indirect_attainment',
			data : post_data,
			dataType : 'HTML',
			success : function (survey_data) {
				var data = jQuery.parseJSON(survey_data);
				if(data.length>0) {
				$('#po_level_indirect_div').html('<div class="row-fluid"><div class="span12 rightJustified" ><button type="button" id="export_tab3_to_doc" class="export_doc btn-fix btn btn-success pull-right" abbr="0"><i class="icon-book icon-white"></i> Export .doc</button></div><div class="span12"></div></div><br><div class="navbar"><div class="navbar-inner-custom">' + student_outcome_full + ' ' + student_outcomes_val + ' Indirect Attainment Analysis</div></div>');
				$('#chart_plot_po_indirect_attain').html('<div class=row-fluid><div class=span12><div id=poindirect_attain_chart_shv style=position:relative; class=jqplot-target></div></div></div>');
				var table = '<table class="table table-bordered table-stripped">';
				table += '<thead><tr><th><center>'+so+ ' reference</center></th><th><center>'+ so + ' statement</center></th><th><center>Attainment%</center></th></tr></thead>';
				table += '<tbody>';
				var i = 0;
				var datax = new Array();
				var datay = new Array();
				var po_ref = new Array();
				var attainment_perc = new Array();
				$.each(data, function () {
					po_ref[i] = data[i].po_reference;
					attainment_perc[i] = data[i].ia_percentage;
					datax.push(po_ref[i]);
					datay.push(attainment_perc[i]);

					table += '<tr>';
					table += '<td>' + data[i].po_reference + '</td>';
					table += '<td>' + data[i].po_statement + '</td>';
					table += '<td style="text-align: -webkit-right;">' + data[i].ia_percentage + '%</td>';
					//table += '<td><center>' + data[i].attainment_level + '</center></td>';
					table += '</tr>';
					i++;
				});
				table += '</tbody>';
				table += '</table>';
				var ticks = datax;
				var plot1 = $.jqplot('poindirect_attain_chart_shv', [datay], {
						seriesColors : ["#4BB2C5"], //, "#4BB2C5"
						seriesDefaults : {
							renderer : $.jqplot.BarRenderer,
							rendererOptions : {
								barWidth : 15,
								fillToZero : true
							},
							pointLabels : {
								show : true
							}
						},
						series : [{
								label : 'Attainment %'
							},
						],
						highlighter : {
							show : true,
							tooltipLocation : 'e', // location of tooltip: n, ne, e, se, s, sw, w, nw.
							tooltipAxes : 'x', // which axis values to display in the tooltip, x, y or both.
							showMarker : false,
							tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
								return datax[pointIndex];
							}
						},
						legend : {
							show : true,
							placement : 'outsideGrid'
						},
						axes : {
							xaxis : {
								renderer : $.jqplot.CategoryAxisRenderer,
								ticks : ticks
							},
							yaxis : {
								padMin : 0,
								min : 0,
								max : 100,
								tickOptions : {
									formatString : '%.2f%%'
								}
							}
						}

					});
				$('#graph_val').html(table);
			} else {
			var survey_link = base_url +'survey/host_survey';
			$('#chart_plot_po_indirect_attain').empty();
				$('#graph_val').html("<center><span style='color:red;'>The selected survey is still <b>In-Progress</b> status. Change the survey status to <b>Closed</b> to view "+ student_outcomes_full + "("+sos+") Indirect Attainment.<br\> Use this link to close the hosted survey : <a href="+survey_link+"> </span>survey close link</a></center>");
			}
				$('#loading').hide();
			},
		});
	} //end of main if
}
// Direct Indirect Attainment Tab Script starts here -------------------

function select_survey_comparison() {
	$.cookie('remember_course_comparison', $('#course_comparison option:selected').val(), {
		expires : 90,
		path : '/'
	});
	empty_direct_indirect_attainment_divs();
	var course_list_path = base_url + 'assessment_attainment/tier1_po_attainment/select_survey';
	var data_val = $('#crclm_id').val();
	if (data_val) {
		$('#po_direct_indirect_submit').attr('disabled', false);
		var post_data = {
			'crclm_id' : data_val
		}
		$.ajax({
			type : "POST",
			url : course_list_path,
			data : post_data,
			success : function (msg) {
				$('#comparison_survey_id').html(msg);
				if ($.cookie('remember_survey_comparison') != null) {
					// set the option to selected that corresponds to what the cookie is set to
					$('#comparison_survey_id option[value="' + $.cookie('remember_survey_comparison') + '"]').prop('selected', true);
				}
			}
		});
	} else {
		$('#comparison_survey_id').html('<option value="">Select Survey</option>');
	}
}

//Edited by Shivaraj B
//Code starts here
$('#po_indirect_direct_attainment_form').on('click', '#save_po_attainment', function (e) {
	$('#po_indirect_direct_attainment_form').validate({
		errorPlacement : function (error, element) {
			error.appendTo(element.parent());
		}
	});
	$('#tier1_po_attainment_form').validate();
	var j = 0;
	$('.survey_title_shv').each(function () {
		if ($('.survey_title_shv').val().length > 0) {
			j++;
		}
	});
	var valid_status = survey_form_validation();
	if (j == 0) {
		valid_status = true;
	}
	if ($('#po_indirect_direct_attainment_form').valid()) {
		if (valid_status == true) {
			var core_courses_cbk = $('#core_crs_id').val();
			var survey_id = $('#survey_title_shv_1').val();
			var crclm_id = $('#crclm_id').val();
			var term_id = $('#term').val();
			var type_data = $('#type_data').val();
			var direct_attainment_val = $('#direct_attainment_val').val();
			var indirect_attainment_val = $('#indirect_attainment_val').val();
			var po_attainment_type = $('#po_attainment_type').val();
			var survey_arr = new Array();
			var survey_wgt_arr = new Array();
			$('.survey_title_shv').each(function () {
				survey_arr.push($(this).val());
			});
			$('.max_wgt').each(function () {
				survey_wgt_arr.push($(this).val());
			});
			var survey_id_arr = survey_arr.join(",");
			var survey_perc_arr = survey_wgt_arr.join(",");
			
			if (term_id !== null && type_data !=0) {
				plot_graphs_comparison_attainment(crclm_id, term_id, direct_attainment_val, indirect_attainment_val, type_data, survey_id_arr, survey_perc_arr, po_attainment_type, core_courses_cbk);
			} else {
				$('#error_dialog_window').modal('show');
			}
		} //End of valid_status if
	}
	e.preventDefault();
});
//Edited by Shivaraj B . for displaying graph
function plot_graphs_comparison_attainment(crclm_id, term_id, direct_attainment_val, indirect_attainment_val, type_data, survey_id_arr, survey_perc_arr, po_attainment_type, core_courses_cbk) {
	$('#loading').show();

	var post_data = {
		'survey_id_arr' : survey_id_arr,
		'crclm_id' : crclm_id,
		'term_id' : term_id,
		'direct_attainment_val' : direct_attainment_val,
		'indirect_attainment_val' : indirect_attainment_val,
		'type_data' : type_data,
		'survey_perc_arr' : survey_perc_arr,
		'po_attainment_type' : po_attainment_type,
		'core_courses_cbk' : core_courses_cbk,
	}

	$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/tier1_po_attainment/getDirectIndirectPOAttaintmentData',
		data : post_data,
		dataType : 'JSON',
		success : function (json_graph_data) {
		console.log(json_graph_data);
			if (json_graph_data.length > 0) {
				var i = 0;
				attainment = new Array();
				attainment_pc = new Array();
				clo_code = new Array();
				clo_id = new Array();
				direct_attainment = new Array();
				direct_percentage = new Array();
				indirect_attainment = new Array();
				indirect_percentage = new Array();
				total_direct_attainment = new Array();
				threshold = new Array();

				clo_statement_data = new Array();
				question_data = new Array();
				Agree_data = new Array();
				Disagree_data = new Array();
				Fairly_Agree_data = new Array();
				Strongly_Agree_data = new Array();

				var data1 = new Array();
				var data2 = new Array();
				var data3 = new Array();
				var da_weightage = new Array();
				var ia_weightage = new Array();
				var threshold_da_attainment = new Array();
				var threshold_ia_attainment = new Array();
				var average_da_attainment = new Array();
				var average_ia_attainment = new Array();
				var threshold_po_overall_attainment = new Array();
				var average_po_overall_attainment = new Array();
				var po_id = new Array();
				
				$.each(json_graph_data, function () {
					attainment[i] = this['threshold_overallDirectAttaintment'];
					attainment_pc[i] = this['totalIndirectAttaintment'];
					clo_code[i] = this['po_reference'];
					clo_id[i] = this['po_id'];
					direct_attainment[i] = this['directAttaintment'];
					direct_percentage[i] = this['directPercentage'];
					indirect_attainment[i] = this['indirectAttaintment'];
					indirect_percentage[i] = this['indirectPercentage'];
					total_direct_attainment[i] = this['totalDirectAttaintment'];

					//threshold[i] = this['po_minthreshhold'];

					data1.push(clo_code[i]);
					data2.push(i); //reserved for threshold column : By shivaraj B
					data3.push(Number(attainment[i]));
					da_weightage.push(this['directPercentage']);
					ia_weightage.push(this['indirectPercentage']);
					threshold_da_attainment.push(this['threshold_based_attainment']);
					threshold_ia_attainment.push(this['totalIndirectAttaintment']);
					average_da_attainment.push(this['average_based_attainment']);
					average_ia_attainment.push(this['totalIndirectAttaintment']);
					threshold_po_overall_attainment.push(this['threshold_overallDirectAttaintment']);
					average_po_overall_attainment.push(this['Avg_overallAttaintment']);
					po_id.push(this['po_id']);
					i++;

				});
				// po_id, po_reference, totalDirectAttaintment, totalAvgDirectAttaintment, directPercentage, directAttaintment, avgdirectAttaintment, totalIndirectAttaintment, indirectPercentage, indirectAttaintment, indirectAvgAttaintment, overallDirectAttaintment, overallAvgAttaintment
				$('#da_weightage').val(da_weightage);
				$('#ia_weightage').val(ia_weightage);
				$('#threshold_da_attainment').val(threshold_da_attainment);
				$('#threshold_ia_attainment').val(threshold_ia_attainment);
				$('#average_da_attainment').val(average_da_attainment);
				$('#average_ia_attainment').val(average_ia_attainment);
				$('#threshold_po_overall_attainment').val(threshold_po_overall_attainment);
				$('#average_po_overall_attainment').val(average_po_overall_attainment);
				$('#po_id_val').val(po_id);
				
				$('#po_level_comparison_nav').html('<div class="row-fluid"><div class="span6"></div><div class="span6 rightJustified" ><a id="exportToPDF" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a></div></div><br><div class="navbar"><div class="navbar-inner-custom">' + student_outcome_full + ' ' + student_outcome_val + ' Direct and Indirect Attainment Analysis</div></div>');
				$('#chart_plot_7').html('<div class=row-fluid><div class=span12><div class=span12><div id=chart_shv1 style=position:relative; class=jqplot-target></div></div><div class=span11 id="course_outcome_attainment_div"> <table border=1 class="table table-bordered" id=po_level_comparison_nav_table_id><thead></thead><tbody id="co_level_body"></tbody></table><div id="actual_course_attainment"></div></div><div id=docs class=span12></div></div></div>');
				$('#po_level_comparison_nav_table_id > tbody:first').append('<tr><td><center><b>' + so + ' Reference</b></center></td><td><center><b>Actual Direct Attainment %</b></center></td><td><center><b>Actual Indirect Attainment %</b></center></td><td><center><b>Direct Attainment Weightage %</b></center></td> <td><center><b>Indirect Attainment Weightage %</b></center></td><td style="white-space:no-wrap;"><center><b>After Weightage Direct Attainment %</b></center></td> <td><center><b>After Weightage Indirect Attainment %</b></center></td><td><center><b>Overall Attainment %</b></center></td><!--<td><center><b>Threshold</b></center></td>--></tr>');
				$.each(json_graph_data, function () {
					$('#po_level_comparison_nav_table_id > tbody:last').append('<tr><td><center>' + this['po_reference'] + '</center></td><td style="text-align: right;">' + parseFloat(this['threshold_based_attainment']).toFixed(2) + '</td><td style="text-align: right;">' + parseFloat(this['totalIndirectAttaintment']).toFixed(2) + '</td><td style="text-align: right;">' + parseFloat(this['directPercentage']).toFixed(2) + '</td><td style="text-align: right;">' + parseFloat(this['indirectPercentage']).toFixed(2) + '</td><td style="text-align: right;">' + this['threshold_based_directAttaintment'] + '</td><td style="text-align: right;">' + this['indirectAttaintment'] + '</td><td style="text-align: right;">' + this['threshold_overallDirectAttaintment'] + '</td><!--<td style="text-align: right;">' + this['po_minthreshhold'] + '</td>--></tr>');
				});

				var ticks = data1;
				//var s1 = data2;
				var plot1 = $.jqplot('chart_shv1', [data3], {
						seriesColors : ["#4bb2c5"], //,"#3efc70"
						seriesDefaults : {
							renderer : $.jqplot.BarRenderer,
							rendererOptions : {
								barWidth : 15,
								fillToZero : true
							},
							pointLabels : {
								show : true
							}
						},
						series : [{
								label : ' PO Attainment %'
							},
							//{label:' Threshold'}
						],
						highlighter : {
							show : true,
							tooltipLocation : 'e', // location of tooltip: n, ne, e, se, s, sw, w, nw.
							tooltipAxes : 'x', // which axis values to display in the tooltip, x, y or both.
							showMarker : false,
							tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
								return data3[pointIndex] + '%';
							}
						},
						legend : {
							show : true,
							placement : 'outsideGrid'
						},
						axes : {
							xaxis : {
								renderer : $.jqplot.CategoryAxisRenderer,
								ticks : ticks
							},
							yaxis : {
								padMin : 0,
								min : 0,
								max : 100,
								tickOptions : {
									formatString : '%.2f%'
								}
							}
						}

					});
					$('#po_finalize').show();

			} else {
				$('#po_attainment_no_data_survey').html('<h5><center>' + student_outcome_full + ' Attainment data unavailable for selected term.</center></h5>');
				$('#po_finalize').hide();
			}
			$('#loading').hide();
		}
	});
}

// Edited By Mritunjay B S
// Finalize PO attainment.
$('#po_finalize').on('click',function(){
				var da_weightage = $('#da_weightage').val();
				var ia_weightage = $('#ia_weightage').val();
				var threshold_da_attainment = $('#threshold_da_attainment').val();
				var threshold_ia_attainment = $('#threshold_ia_attainment').val();
				var average_da_attainment = $('#average_da_attainment').val();
				var average_ia_attainment = $('#average_ia_attainment').val();
				var threshold_po_overall_attainment = $('#threshold_po_overall_attainment').val();
				var average_po_overall_attainment = $('#average_po_overall_attainment').val();
				var crclm_id = $('#crclm_id').val();
				var term_id = $('#term').val();
				var po_id = $('#po_id_val').val();
				var post_data = {	'da_weightage':da_weightage,
									'ia_weightage':ia_weightage,
									'threshold_da_attainment':threshold_da_attainment, 
									'threshold_ia_attainment':threshold_ia_attainment, 
									'average_da_attainment':average_da_attainment, 
									'average_ia_attainment':average_ia_attainment,
									'threshold_po_overall_attainment':threshold_po_overall_attainment, 'average_po_overall_attainment':average_po_overall_attainment,
									'po_id':po_id,
									'crclm_id':crclm_id,
									'term_id':term_id, };
			$('#loading').show();
				$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/tier1_po_attainment/insert_po_finalize_data',
		data : post_data,
		//dataType : 'JSON',
		success : function (msg) {
			if($.trim(msg)=='true'){
                                 var data_options = '{"text":"PO Data finalized Successfully. ","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                                    var options = $.parseJSON(data_options);
                                    noty(options);
									$('#loading').hide();
                            }else{
                                var data_options = '{"text":"PO Data finalization failed. ","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
                                    var options = $.parseJSON(data_options);
                                    noty(options);
									$('#loading').hide();
                            }
		}
		});
});

// Edited by Shivaraj B
// Added for survey changes in Direct and Indirect Attenment
var s_counter = new Array();
s_counter.push(1);

$(document).ready(function () {

	$('[data-toggle="tooltip"]').tooltip();
	$.validator.addMethod("onlyDigit", function (value, element) {
		return this.optional(element) || value.match(/^[0-9]+$/);
	}, "only digits.");
	jQuery.validator.addClassRules("required", {
		required : true,
	});

	$('#tooltip').tooltip();

	$('.add_survery_field').live('click', function (e) {

		var counter_val = $('#survey_counter').val();
		var max_rows_for_survey = max_no_of_rows_allowed - 1;
		if (max_rows_for_survey == counter_val) {
			$('#err_msg').html("<p class='alert alert-danger' role='alert' style='color:red;'>You cannot add rows more than no.of Surveys</p>");
			$('.add_survery_field').attr("disabled", "disabled");
		} else {
			$('.add_survery_field').removeAttr("disabled");
			count_val = {
				'survey_count' : counter_val,
				's_data' : survey_dd_data,
			};
			$.ajax({
				type : "POST",
				url : base_url + 'assessment_attainment/tier1_po_attainment/add_more_rows',
				data : count_val,
				async : false,
				success : function (s_type) {
					++counter_val;
					$('#survey_counter').val(counter_val);
					$("#add_more_survey_rows").append(s_type);

					s_counter.push(counter_val);
					$('#counter').val(s_counter);
					$('#indirect_attainment_val').trigger('change');
				}
			});
		} //End of if
		e.preventDefault();

	});
	//Function to delete row
	$('.Delete').live('click', function () {

		rowId = $(this).attr("id").match(/\d+/g);

		if (rowId != 1) {
			$(this).parent().parent().parent().remove();

			var replaced_id = $(this).attr('id').replace('remove_field', '');
			var po_counter_index = $.inArray(parseInt(replaced_id), s_counter);
			s_counter.splice(po_counter_index, 1);
			$('#counter').val(s_counter);
			var d = parseInt($('.survey_counter').val());
			$('.survey_counter').val(d - 1);
			return false;
		}
	});
	$('#indirect_attainment_val').live("change", function () {
		if ($('#indirect_attainment_val').val() != 0) {
			var d = $('.survey_counter').val();
			var sp_wgt = parseInt($('#indirect_attainment_val').val()) / parseInt(d);
			$('.spc_wgt').each(function () {
				$(this).val(sp_wgt.toFixed(2));
			});
		}
		$('#indirect_attainment').val($('#indirect_attainment_val').val());
	});
	$('#direct_attainment_val').live("change", function () {
		$('#direct_attainment').val($('#direct_attainment_val').val());
	});

}); // end of document ready function

/*
 * This function takes input from select_survey()
 */
var survey_dd_data = "";
var max_no_of_rows_allowed = 0;
function assign_survey_titles(data) {
	$('.survey_title_shv').each(function () {
		$('.survey_title_shv').html(data);
		survey_dd_data = data;
	});
	max_no_of_rows_allowed = $('select.survey_title_shv option').length;
}

function survey_form_validation() {
	$('#err_msg').html("");
	$('#msg').html("");
	var repeated_dp = null;
	repeated_dp = survey_title_validation();
	var wgt_status = false;
	wgt_status = weightage_validation();
	if (repeated_dp > 0) {
		$('#err_msg').html("<p style='color:red' class='alert alert-danger' role='alert'>ERROR: You have selected Survey multiple times</p>");
		return false;
	} else if (wgt_status == false) {
			var k = 0;
			$('.survey_title_shv').each(function () {
				if ($('.survey_title_shv').val().length > 0) {
					k++;
				}
			});
			if (k ==0) {
				$('#msg').html("<p style='color:red' class='alert alert-info' role='alert'>Note: Below analysis is based purly on Direct Attainment as you have not selected any survey.</p>");
			} else {
				$('#msg').html("<p style='color:red' class='alert alert-danger' role='alert'>Error!!! Please make sure that sum of Weightage is 100.</p>");
			}
			return false;
		} else if (repeated_dp == 0 && wgt_status == true) {
			$('#err_msg').html("");
			$('#msg').html("");
			return true;
		}
	return false;
}

function survey_title_validation() {
	var data_arr = new Array();
	var repeated = 0;
	$('.survey_title_shv').each(function () {
		if ($('.survey_title_shv').val().length > 0) {
			if (jQuery.inArray($(this).val(), data_arr) > -1) {
				repeated++;
			} else {
				data_arr.push($(this).val());
			}
		}
	});
	return repeated;
}

function weightage_validation() {
	var sum_arr = new Array();
	$('.max_wgt').each(function () {
		sum_arr.push($(this).val());
	});
	var sum = 0;
	sum_arr.map(function (item) {
		sum += parseInt(item);
	});

	if (parseInt(sum) < 0 || isNaN(sum)) {
		$('#msg').html("<p style='color:red' class='alert alert-danger' role='alert'>Please check Weightage values</p>");
		return false;
	} else {
		if (parseInt(sum) == 100) {
			$('.total_wgt').val(sum);
			return true;
		} else {
			$('.total_wgt').val(sum);
			return false;
		}
		return false;
	}
	return false;
}

$('#indirect_attainment_form').on('click', '#exportToPDF', function () {

	var crclm_name = $('#crclm_id :selected').text();
	var term_name = $('#term :selected').text();
	var course_name = $('#course :selected').text();
	var po_attainment_graph = $('#poindirect_attain_chart_shv').jqplotToImageStr({});
	var po_attainment_graph_image = $('<img/>').attr('src', po_attainment_graph);
	$('#po_indirect_attainment_graph_data').html('<table><tr><td><b>Curriculum :</b></td><td>' + crclm_name + '</td></tr><tr><td><b>Term :</b></td><td>' + term_name + '</td></tr><tr><!--<td><b>Course :</b></td><td>' + course_name + '</td>--></tr></table><br><b>' + student_outcome_full + ' ' + student_outcomes_val + ' Indirect Attainment Analysis</b><br />');
	$('#po_indirect_attainment_graph_data').append("<br>");
	$('#po_indirect_attainment_graph_data').append(po_attainment_graph_image);
	$('#po_indirect_attainment_graph_data').append("<br><pagebreak/><br><br>");
	$('#po_indirect_attainment_graph_data').append($('#graph_val').clone().html());
	var po_indirect_attainment_pdf = $('#po_indirect_attainment_graph_data').clone().html();
	$('#po_indirect_attainment_graph_data_hidden').val(po_indirect_attainment_pdf);
	$('#indirect_attainment_form').submit();

});

$('#po_indirect_direct_attainment_form').on('click', '#exportToPDF', function () {

	var crclm_name = $('#crclm_id :selected').text();
	var term_name = $('#term :selected').text();
	var course_name = $('#course :selected').text();

	var po_attainment_graph = $('#chart_shv1').jqplotToImageStr({});
	var po_attainment_graph_image = $('<img/>').attr('src', po_attainment_graph);
	$('#po_direct_indirect_attainment_graph_data').html('<table><tr><td><b>Curriculum :</b></td><td>' + crclm_name + '</td></tr><tr><td><b>Term :</b></td><td>' + term_name + '</td></tr></tr></table><br><b>Program Outcome (POs) Direct Indirect Attainment Analysis</b><br />');
	$('#po_direct_indirect_attainment_graph_data').append("<br>");
	$('#po_direct_indirect_attainment_graph_data').append(po_attainment_graph_image);
	$('#po_direct_indirect_attainment_graph_data').append("<br><pagebreak/><br><br>");
	$('#po_direct_indirect_attainment_graph_data').append($('#course_outcome_attainment_div').clone().html());
	var po_attainment_pdf = $('#po_direct_indirect_attainment_graph_data').clone().html();
	$('#po_direct_indirect_attainment_graph_data_hidden').val(po_attainment_pdf);
	$('#po_indirect_direct_attainment_form').submit();

});

//code ends here
//@shivaraj B

$('.noty').click(function () {
    var options = $.parseJSON($(this).attr('data-noty-options'));
    noty(options);

});

// Code added by Mritunjay B S

	
$(document).on('change','#term',function(){
    empty_all_divs();
	//$('#loading').show();
	var crclm_id = $('#crclm_id').val();
	var term = $('#term').val();
        var post_data = {
      'crclm_id' : crclm_id,  
      'term' : term,  
    };
    $.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/tier1_po_attainment/select_activity',
		data : post_data,
		success : function (msg) {
                        $('#extra_crricular_activity_display_div').empty();
                        $('#extra_crricular_activity_display_div').html(msg);
//			$('#term').html(msg);
//			$('#term').multiselect('rebuild');
//
//			if ($.cookie('remember_term') != null) {
//				// set the option to selected that corresponds to what the cookie is set to
//				$('#term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
//				select_type();
//				select_survey();
//				select_survey_comparison();
//			}
		}
	});
        
});

$(document).on('change','#activity_data',function(){
  var activity_id =  $(this).val();
  var crclm_id = $('#crclm_id').val();
  var term = $('#term').val();
  var post_data = {
    'activity_id' :activity_id,
    'crclm_id':crclm_id,
    'term':term,
  };
  $.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/tier1_po_attainment/get_po_wise_attainment',
		data : post_data,
                dataType:'json',
		success : function (data) {
                    if($.trim(data.status) == 'success'){
                        $('#extra_crricular_po_attain_table_plot_div').empty();
                        $('#extra_crricular_po_attain_table_plot_div').html(data.table);
                        var ticks = data.po_ref;
                        $('#extra_crricular_activity_export_div').empty();
                                $('#extra_crricular_po_attain_chart1').empty();
                                $('#extra_crricular_activity_export_div').append('<div class="row-fluid"><div class="span11 pull-right"><button type="button" id="export_tab2_to_doc" class="export_doc btn-fix btn btn-success pull-right" abbr="2"><i class="icon-book icon-white"></i> Export .doc</button></div></div>');
                                var plot1 = $.jqplot('extra_crricular_po_attain_chart1', [data.po_attainment], {
								seriesColors : ["#2E9AFE"], // #4bb2c5   colors that will
						// be assigned to the series.  If there are more series than colors, colors
						// will wrap around and start at the beginning again.
						seriesDefaults : {
							renderer : jQuery.jqplot.BarRenderer,
							rendererOptions : {
								barWidth : 20,
								fill : true,
								showDataLabels : true,
								sliceMargin : 10,
								lineWidth : 5,
                                                                fillToZero : true,
								//dataLabelFormatString : '%.2f%'							
							},
							pointLabels : {
								show : true,
								stackedValue: true,
								//dataLabelFormatString : '%.2f%',
                                                               // formatString: '%s (%%)',
								//labels : [data.avg_po_att+'%', data.avg_dir_att+'%',data.hml_po_att+'%',data.hml_po_wtd_att+'%']
							}
						},
						series : [
							{
								label : 'Attainment %'
							}, {
								label : 'Threshold Based <BR/> Attainment%'
							}
						],
						highlighter : {
							show : true,
							tooltipLocation : 'e',
							tooltipAxes : 'x',
							fadeTooltip : true,
							showMarker : false,
							tooltipFormatString : '%s',
							tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
								return data.po_ref_state[pointIndex];
							}
						},
						legend : {
							show : true,
							placement : 'outsideGrid'
						},
						axes : {
							xaxis : {
								renderer : $.jqplot.CategoryAxisRenderer,
								tickRenderer : $.jqplot.CanvasAxisTickRenderer,
								ticks : ticks
							},
							yaxis : {
								min : 0,
								max : 110,
								padMin : 0,
								tickOptions : {
									formatString : '%#.2f%'
								}
							}
						}
					});
                                        
                        // Table Row Grouping
                        $('#po_attainment_data').dataTable({
                                "fnDrawCallback": function () {
                                    $('.group').parent().css({'background-color': '#C7C5C5'});
                                },
                                "aoColumnDefs": [
                                    {"sType": "natural", "aTargets": [2]}
                                ],
                                "sPaginationType": "bootstrap"

                            }).rowGrouping({iGroupingColumnIndex: 0,
                                bHideGroupingColumn: true});
                        
                    }else{
                        $('#extra_crricular_activity_export_div').empty();
                        $('#extra_crricular_po_attain_table_plot_div').empty();
                        $('#extra_crricular_po_attain_chart1').empty();
                        $('#extra_crricular_po_attain_chart1').html('<center>'+data.error_msg+'</center>');
                    }
                    
		}
	});
});


$('#tier1_po_attainment_form').on('click', '.export_doc', function() {
    var tab_name = $('ul.nav-tabs li.active > a').attr('href').slice(1);
    $('#tab_name').val(tab_name);
    var text_value = $('ul.nav-tabs li.active').text();
    $('#file_name').val(text_value.trim());
    var export_data = '';
    if($(this).attr('id') == 'export_tab1_to_doc') {
        var imgData = $('#po_attainmnet_graph').jqplotToImageStr({});
        $('#export_graph_data_to_doc').val(imgData); 
    } else if($(this).attr('id') === 'export_tab2_to_doc') {
        var imgData = $('#extra_crricular_po_attain_chart1').jqplotToImageStr({});
        $('#export_graph_data_to_doc').val(imgData); 
    } else if($(this).attr('id') === 'export_tab3_to_doc') {
        var imgData = $('#poindirect_attain_chart_shv').jqplotToImageStr({});
        $('#export_graph_data_to_doc').val(imgData); 
    } else {
    }
    $('#export_doc_data').val(export_data);
    $('#tier1_po_attainment_form').submit();
});
