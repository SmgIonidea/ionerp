// Static View JS functions.
var base_url = $('#get_base_url').val();

if ($.cookie('remember_dept') != null) {
	// set the option to selected that corresponds to what the cookie is set to
	$('#department option[value="' + $.cookie('remember_dept') + '"]').prop('selected', true);
	select_pgm();
}

function select_pgm() {

	$.cookie('remember_dept', $('#department option:selected').val(), {
		expires : 90,
		path : '/'
	});
	$('#occasion_div').css({
		"display" : "none"
	});
	var dept_id = $('#department').val();
	var post_data = {
		'dept_id' : dept_id
	}
	$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/data_series/select_pgm_list',
		data : post_data,
		success : function (msg) {
			$('#actual_data_div').html('');
			$('#program').html(msg);
			if ($.cookie('remember_pgm') != null) {
				// set the option to selected that corresponds to what the cookie is set to
				$('#program option[value="' + $.cookie('remember_pgm') + '"]').prop('selected', true);
				select_crclm();
			}
		}
	});
}

function select_crclm() {
	$.cookie('remember_pgm', $('#program option:selected').val(), {
		expires : 90,
		path : '/'
	});
	$('#occasion_div').css({
		"display" : "none"
	});
	var pgm_id = $('#program').val();
	var post_data = {
		'pgm_id' : pgm_id
	}
	$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/data_series/select_crclm_list',
		data : post_data,
		success : function (msg) {
			$('#actual_data_div').html('');
			$('#curriculum').html(msg);
			if ($.cookie('remember_crclm') != null) {
				// set the option to selected that corresponds to what the cookie is set to
				$('#curriculum option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
				//get_selected_value();
				select_term();
			}
		}
	});

}

function select_term() {
	$.cookie('remember_crclm', $('#curriculum option:selected').val(), {
		expires : 90,
		path : '/'
	});
	$('#occasion_div').css({
		"display" : "none"
	});
	var crclm_id = $('#curriculum').val();
	var post_data = {
		'crclm_id' : crclm_id
	}
	$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/data_series/select_termlist',
		data : post_data,
		success : function (msg) {
			$('#actual_data_div').html('');
			$('#term').html(msg);
			if ($.cookie('remember_term') != null) {
				// set the option to selected that corresponds to what the cookie is set to
				$('#term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);

				select_course();
			}
		}
	});
}

function select_course() {
	$.cookie('remember_term', $('#term option:selected').val(), {
		expires : 90,
		path : '/'
	});
	$('#occasion_div').css({
		"display" : "none"
	});
	var term_id = $('#term').val();
	var post_data = {
		'term_id' : term_id
	}
	$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/data_series/select_course',
		data : post_data,
		success : function (msg) {
			$('#actual_data_div').html('');
			$('#course').html(msg);
			if ($.cookie('remember_course') != null) {
				// set the option to selected that corresponds to what the cookie is set to
				$('#course option[value="' + $.cookie('remember_course') + '"]').prop('selected', true);
				select_type();
			}
		}
	});
}

function select_type() {
	$.cookie('remember_course', $('#course option:selected').val(), {
		expires : 90,
		path : '/'
	});
	$('#occasion_div').css({
		"display" : "none"
	});
	$('#actual_data_div').empty();
	var course_id = $('#course').val();
	if (course_id) {
	//	$('#type_data').html('<option value=0>Select Type</option><option value=1>' + entity_see + '</option><option value=2>' + entity_cie + '</option>');
	var course_id = $('#course').val();
    var crclm_id = $('#curriculum').val();
    var term_id = $('#term').val();	
    var post_data =  {'course_id':course_id,'crclm_id':crclm_id,'term_id':term_id};
	
	$.ajax({
            type : "POST",
            url : base_url + 'assessment_attainment/data_series/fetch_type_data',
            data : post_data,
            dataType: 'json',
            success : function (msg) {
				$('#type_data').html(msg.type_list);
			
	}});
	
	
	
	} else {
		$('#occasion_div').css({
			"display" : "none"
		});
		$('#actual_data_div').html('');
		$('#type_data').html('<option value=0>Select Type</option>');
	}
}

$('#data_series_list_form').on('change', '#type_data', function () {
	$('#actual_data_div').html('');
	$('#data_series_analysis_nav').html('');
	$('#data_series_graph').html('');
	var type_data_id = $('#type_data').val(); 
	if (type_data_id == 5) {
		$('#occasion_div').css({
			"display" : "none"
		});
		var crclm_id = $('#curriculum').val();
		var term_id = $('#term').val();
		var course_id = $('#course').val();
		var post_data = {
			'crclm_id' : crclm_id,
			'term_id' : term_id,
			'crs_id' : course_id
		}
		$.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/data_series/getTEEQPId',
			data : post_data,
			success : function (qpd_id) {
				if (Number(qpd_id)) {
					var qpd_data = {
						'qpd_id' : qpd_id
					}
					$.ajax({
						type : "POST",
						url : base_url + 'assessment_attainment/import_assessment_data/dataAnalysis',
						data : qpd_data,
						beforeSend : function () {
							$('#loading').show();
						},
						success : function (analysisData) {
							$('#data_series_analysis_nav').html('<div class="row-fluid"><div class="span9"></div><div class="span3 " ><a id="exportToPDF" href="#" class="pull-left btn btn-success"><i class="icon-book icon-white"></i> Export to PDF</a>&nbsp;&nbsp;&nbsp;<a id="export_doc" href="#" class="pull-right btn btn-success"><i class="icon-book icon-white"></i> Export to Doc </a><input type="hidden" name="type_id" id="type_id" value="2" /><input type="hidden" name="form_name" id="form_name" value="" /></div></div><br><div class="navbar"><div class="navbar-inner-custom">Data Analysis Report</div></div>');
							$('#actual_data_div').html("<br><br>" + analysisData);
							$('#loading').hide();
						}
					});
				} else if (!Number(qpd_id)) {
					$('#actual_data_div').empty();
					$('#actual_data_div').html('<font color="red">' + entity_see + ' Question has not been rolled out</font>');
				}
			}
		});
	}
	if (type_data_id == 3) {
		var crclm_id = $('#curriculum').val();
		var term_id = $('#term').val();
		var crs_id = $('#course').val();
		var post_data = {
			'crclm_id' : crclm_id,
			'term_id' : term_id,
			'crs_id' : crs_id
		}
		$.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/data_series/select_occasion',
			data : post_data,

			success : function (occasionList) {
				if (occasionList != 0) {
					$('#occasion_div').css({
						"display" : "inline"
					});
					$('#occasion').html(occasionList);
				} else {
					$('#occasion_div').css({
						"display" : "none"
					});
					$('#actual_data_div').html('<font color="red">' + entity_cie_full + ' (CIA) Occasions are not defined</font>');
				}
			}
		});
	}	
	
	if (type_data_id == 6) {
		var crclm_id = $('#curriculum').val();
		var term_id = $('#term').val();
		var crs_id = $('#course').val();
		var post_data = {
			'crclm_id' : crclm_id,
			'term_id' : term_id,
			'crs_id' : crs_id
		}
		$.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/data_series/select_occasion_mte',
			data : post_data,

			success : function (occasionList) {
				if (occasionList != 0) {
					$('#occasion_div').css({
						"display" : "inline"
					});
					$('#occasion').html(occasionList);
				} else {
					$('#occasion_div').css({
						"display" : "none"
					});
					$('#actual_data_div').html('<font color="red">' + entity_cie_full + ' (CIA) Occasions are not defined</font>');
				}
			}
		});
	}
});

$('#data_series_list_form').on('change', '#occasion', function () {
	$('#actual_data_div').empty();
	var qpd_id = $('#occasion').val();
	var post_data = {
		'qpd_id' : qpd_id
	}
	if(qpd_id){
		$.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/import_assessment_data/dataAnalysis',
			data : post_data,
			beforeSend : function () {
				$('#loading').show();
			},
			success : function (msg) {
				$('#data_series_analysis_nav').html('<div class="row-fluid"><div class="span9"></div><div class="span3" ><a id="exportToPDF" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export to PDF </a><a id="export_doc" href="#" class="pull-right btn btn-success"><i class="icon-book icon-white"></i> Export to Doc </a><input type="hidden" name="type_id" id="type_id" value="2" /><input type="hidden" name="form_name" id="form_name" value="" /></div></div><br><div class="navbar"><div class="navbar-inner-custom">Data Series Analysis Report</div></div>');
				$('#actual_data_div').html(msg);
				$('#loading').hide();

			}
		});
	}else{
		$('#exportToPDF').hide();	$('#export_doc').hide();
	}
});

function plotGraphForDataSeriesAnalysis(id) {

	$('#data_series_graph').empty();
	$('#data_series_graph').html('<hr><div class=row-fluid><div class=span6><h4>Actual Number of Attempts:</h4><div id=chart6 style=position:relative; class=jqplot-target></div></div><div class=span6><h4>Percentage of Attainment vs. Percentage of Attempt  </h4><div id=chart7 style=position:relative; class=jqplot-target></div></div></div>');
	//$('#data_series_graph').append('');

	$('#chart6').empty();
	$('#chart7').empty();
	if (id == 1) {
		var crclm_id = $('#curriculum').val();
		var term_id = $('#term').val();
		var course_id = $('#course').val();
		var post_data = {
			'crclm_id' : crclm_id,
			'term_id' : term_id,
			'crs_id' : course_id
		}
		$.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/data_series/getTEEQPId',
			data : post_data,
			success : function (qpd_id) {
				if (Number(qpd_id)) {
					var qpd_data = {
						'qpd_id' : qpd_id
					}
					$.ajax({
						type : "POST",
						url : base_url + 'assessment_attainment/import_assessment_data/dataAnalysisDataForGraph',
						data : qpd_data,
						beforeSend : function () {
							$('#loading').show();
						},
						success : function (msg) {
							var qArray = new Array();
							var attainment = new Array();
							var attempt_per = new Array();
							var no_of_attempts = new Array();
							var i = 0;
							var data = jQuery.parseJSON(msg);
							$.each(data.questions, function (index, value) {
								qArray.push(value);
							});
							$.each(data.attainment, function (index, value) {
								attainment.push(Number(value));
							});
							$.each(data.attempt_per, function (index, value) {
								attempt_per.push(Number(value));
							});
							$.each(data.no_of_attempts, function (index, value) {
								no_of_attempts.push(Number(value));
							});
							var ticks = qArray;
							var plot2 = $.jqplot('chart6', [no_of_attempts], {
								seriesColors : ["#4BB2C5"],
									seriesDefaults : {
										markerOptions : {
											show : true
										},
										rendererOptions : {
											smooth : true
										}
									},
									axesDefaults : {
										labelRenderer : $.jqplot.CanvasAxisLabelRenderer
									},
									series : [{
											label : 'Number of attempts'
										},

									],
									highlighter : {
										show : true,
										tooltipLocation : 'e',
										tooltipAxes : 'x',
										fadeTooltip : true,
										showMarker : false,
										tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
											return no_of_attempts[pointIndex];
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
											min : 0
										}
									}

								});

							//Graph attaiment,attempt percentage v/s Questions
							var plot3 = $.jqplot('chart7', [attainment, attempt_per], {
									seriesDefaults : {
										markerOptions : {
											show : true
										},
										rendererOptions : {
											smooth : true
										}
									},
									axesDefaults : {
										labelRenderer : $.jqplot.CanvasAxisLabelRenderer
									},
									series : [{
											label : 'Attainment %'
										}, {
											label : 'Attempt %'
										}
									],
									highlighter : {
										show : true,
										tooltipLocation : 'e',
										tooltipAxes : 'x',
										fadeTooltip : true,
										showMarker : false,
										tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
											return attainment[pointIndex];
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
											min : 0
										}
									}
								});
							$('#loading').hide();
						} //end of success
					}); // end of ajax2

				} else if (!Number(qpd_id)) {
					$('#actual_data_div').empty();
				}
			}

		}); //end of ajax1
	} //end of if for type id

	if (id == 2) {
		var qpd_id = $('#occasion').val();
		var post_data = {
			'qpd_id' : qpd_id,
		}
		$.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/import_assessment_data/dataAnalysisDataForGraph',
			data : post_data,
			beforeSend : function () {
				$('#loading').show();
			},
			success : function (msg) {
				//$('#graphdata').html(msg);
				$('#loading').hide();
				
				var qArray = new Array();
				var attainment = new Array();
				var attempt_per = new Array();
				var no_of_attempts = new Array();
				var i = 0;
				var data = jQuery.parseJSON(msg);
				$.each(data.questions, function (index, value) {
					qArray.push(value);
				});
				$.each(data.attainment, function (index, value) {
					attainment.push(Number(value));
				});
				$.each(data.attempt_per, function (index, value) {
					attempt_per.push(Number(value));
				});
				$.each(data.no_of_attempts, function (index, value) {
					no_of_attempts.push(Number(value));
				});

				var ticks = qArray;
				var plot2 = $.jqplot('chart6', [no_of_attempts], {
						seriesDefaults : {
							markerOptions : {
								show : true
							},
							rendererOptions : {
								smooth : true
							}
						},
						axesDefaults : {
							labelRenderer : $.jqplot.CanvasAxisLabelRenderer
						},
						series : [{
								label : 'Number of attempts'
							},

						],
						highlighter : {
							show : true,
							tooltipLocation : 'e',
							tooltipAxes : 'x',
							fadeTooltip : true,
							showMarker : false,
							tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
								return no_of_attempts[pointIndex];
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
								min : 0
							}
						}

					});
				
				var plot3 = $.jqplot('chart7', [attainment, attempt_per], {
						seriesColors : ["#4BB2C5", "#3efc70"],
						seriesDefaults : {
							markerOptions : {
								show : true
							},
							rendererOptions : {
								smooth : true
							}
						},
						axesDefaults : {
							labelRenderer : $.jqplot.CanvasAxisLabelRenderer
						},
						series : [{
								label : 'Attainment %'
							}, {
								label : 'Attempt %'
							}
						],
						highlighter : {
							show : true,
							tooltipLocation : 'e',
							tooltipAxes : 'x',
							fadeTooltip : true,
							showMarker : false,
							tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
								return attainment[pointIndex];
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
								min : 0
							}
						}

					});
				$('#loading').hide();
			}
		});
	} //end of if


}

$('#data_series_list_form').on('click', '#exportToPDF', function () {
	var department_name = $('#department :selected').text();
	var program_name = $('#program :selected').text();
	var crclm_name = $('#curriculum :selected').text();
	var term_name = $('#term :selected').text();
	var course_name = $('#course :selected').text();

	$('#data_series_analysis_rep').html('<table><tr><td><b>Department Name:</b></td><td>' + department_name + '</td></tr><tr><td><b>Program:</b></td><td>' + program_name + '</td></tr><tr><td><b>Curriculum :</b></td><td>' + crclm_name + '</td></tr><tr><td><b>Term :</b></td><td>' + term_name + '</td></tr><td><b>Course :</b></td><td>' + course_name + '</td></tr></table><br><b>Data Series Analysis Report</b><br />');
	$('#data_series_analysis_rep').append("<br>");
	$('#data_series_analysis_rep').append($('#actual_data_div').clone().html());
	var data_series_analysis_rep_pdf = $('#data_series_analysis_rep').clone().html();
	$('#data_series_analysis_rep_hidden').val(data_series_analysis_rep_pdf);
	$('#data_series_list_form').submit();

});

$('#data_series_list_form').on('click', '#showGraph', function () {
	var type_data_id = $('#type_data').val();
	plotGraphForDataSeriesAnalysis(type_data_id);
});


$('form[name="data_series_list_form"]').on('click', '#export_doc', function(e){
		var department_name = $('#department :selected').text();
	var program_name = $('#program :selected').text();
	var crclm_name = $('#curriculum :selected').text();
	var term_name = $('#term :selected').text();
	var course_name = $('#course :selected').text();
	var type_id = $('#type_data :selected').val();	
	var occasion = $('#occasion :selected').text();
	if(type_id == 2){var table_data = "<tr><td><b>Occasion :</b> "+ occasion +" </td></tr>";}else{}
	$('#data_series_analysis_rep').html('<table style="width:100%"><tr><td width = 325 ><b>Program : </b>' + program_name + '</td><td width = 325><b>Curriculum :</b>' + crclm_name + '</td></tr><tr><td width = 325><b>Term :</b>' + term_name + '</td><td width = 325 ><b>Course :</b> ' + course_name + '</td></tr>'+ table_data +'</table><br><br/><b>Data Series Analysis Report</b>');
		var export_data =  $('#actual_data_div').html();
		$('#export_data_to_doc').val(export_data);
		var imgData = '';
		var main_head =  $('#data_series_analysis_rep').html();		
		var filename  = 'Data_Analysis_Report';
	$('#pdf_doc').val('doc');
	$('#file_name').val(filename);
	$('#export_data_to_doc').val(export_data);
	$('#export_graph_data_to_doc').val(imgData);
	$('#main_head').val(main_head);
	$('#data_series_list_form').submit();
});

