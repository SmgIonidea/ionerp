/*
 * Description	:	JScript for Tier II PEO Level Attainment
 
 * Created		:	March 24th, 2016
 
 * Author		:	 
 
 * Modification History:
 * Date				Modified By				Description
 
 ----------------------------------------------------------------------------------------------*/
var base_url = $('#get_base_url').val();

$(document).ready(function () {
    $.validator.addClassRules("required", {
	required: true,
    });
});
//function to empty div's related to direct attainment
function empty_direct_attainment() {
    $('#peo_attainment_chart_1').empty();
    $('#course_attainment_nav').empty();
    $('#course_attainment_no_data').empty();
    $('#peo_attainment_chart_2').empty();
}
//function to empty div's related to indirect attainment
function empty_indirect_attainment() {
    $('#peo_attainment_data').empty();
    $('#peo_level_indirect_div').empty();
    $('#chart_plot_peo_indirect_attain').empty();
    $('#graph_val').empty();
    $('#chart1').empty();
}
//function to empty div's related to direct indirect attainment
function empty_direct_indirect_attainment() {
    $('#peo_level_comparison_nav').empty();
    $('#chart_plot_7').empty();
    $('#peo_attainment_no_data_survey').empty();
//    $('#finalize_btn').empty();

}
//function to empty all the div's
function empty_all_divs() {
    empty_direct_attainment();
    empty_indirect_attainment();
    empty_direct_indirect_attainment();
}

//function to select the survey
function select_survey() {

    empty_indirect_attainment();
    var course_list_path = base_url + 'tier_ii/peo_level_attainment/select_survey';
    var data_val = $('#crclm_id').val();
    if (data_val) {
	var post_data = {
	    'crclm_id': data_val
	}
	$.ajax({
	    type: "POST",
	    url: course_list_path,
	    data: post_data,
	    success: function (msg) {
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

//Direct Attainment

$('#crclm_id').on('change', function () {
    select_survey();
    get_finalized_data($('#crclm_id').val());
    var crclm_id = $('#crclm_id').val();
    $('#finalized_peo_data').html("");
    var post_data = {
	'crclm_id': crclm_id
    }

    $('#peo_attainment_chart_1').html('<div class=row-fluid><div class="span12 rightJustified"><a id="exportToPDF" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a></div><br/><br/><div class=span12><div class="navbar"><div class="navbar-inner-custom">Program Educational Objective (PEO) Attainment</div></div><div class=span12><div id=chart1 style=position:relative; class=jqplot-target></div></div></div><div class=span10 style=overflow:auto; id="peo_attainment_div"></div><div id=peo_attainment_data class=span11></div></div><br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>Individual PEO attainment : Average of all the ' + sos + ' mapped to their respective PEO.</td></tr></tbody></table></div>');

    $.ajax({
	type: "POST",
	url: base_url + 'tier_ii/peo_level_attainment/direct_attainment',
	data: post_data,
	dataType: 'HTML',
	success: function (json_graph_data) {
	    var data = $.parseJSON(json_graph_data);
	    $('#loading').hide();
	    if (data.length > 0) {
		var table = '<table class="table table-bordered table-stripped" id="peo_attainment_table">';
		table += '<thead>';
		table += '<tr><th><center>PEO Reference</center></th><th><center>Program Educational Outcome (PEO) Statement</center></th><th><center>Attainment %</center></th><th><center>Attainmet Level</center></th></tr>';
		table += '</thead>';
		table += '<tbody>';
		var i = 0;
		var datax = new Array();
		var datay = new Array();
		var peo_val = new Array();
		var peo_perc = new Array();
		var peo_statement = new Array();
		$.each(data, function () {
		    //populate data for graph
		    peo_val[i] = data[i].peo_reference;	
		    peo_perc[i] = data[i].attainment_perc;
		    peo_statement.push(data[i].peo_statement);
		    datax.push(peo_val[i]);	
		    datay.push(parseFloat(this['attainment_perc'])) ;

		    table += '<tr>';
		    //table += '<td><center>' + (i + 1) + '</center></td>';
		    table += '<td><center>' + data[i].peo_reference + '</center></td>';
		    table += '<td><left>' + data[i].peo_statement + '</left></td>';
		    table += '<td style="text-align:right;">' + data[i].attainment_perc + ' %' +
			    ' - <a title="PEO Attainment Drilldown" href="#" id="2" class="myModalQPdisplay displayDrilldown" abbr="' + this['peo_id'] + '" data-id="' + this['po_id'] + '"><i class="myTagRemover "></i>Drilldown</a></td>';
		    table += '<td><center>' + data[i].attainment_level + '</center></td>';
		    table += '</tr>';
		    i++;
		});
		table += '<tbody>';
		table += '</table>';

		$('#peo_attainment_data').html(table);
		
		var ticks = datax;
		var plot1 = $.jqplot('chart1', [datay], {
		    seriesColors: ["#4BB2C5"],
		    seriesDefaults: {
			renderer: $.jqplot.BarRenderer,
			rendererOptions: {
			    barWidth: 15,
			    fillToZero: true
			},
			pointLabels: {
			    show: true
			}
		    },
		    series: [{
			    label: 'Direct Attainment %'
			},
		    ],
		    highlighter: {
			show: true,
			tooltipLocation: 'e', 
			tooltipAxes: 'x', 
			showMarker: false,
			tooltipContentEditor: function (str, seriesIndex, pointIndex, plot) {
			    return peo_statement[pointIndex];
			}
		    },
		    legend: {
			show: true,
			placement: 'outsideGrid'
		    },
		    axes: {
			xaxis: {
			    renderer: $.jqplot.CategoryAxisRenderer,
			    ticks: ticks
			},
			yaxis: {
			    padMin: 0,
			    min: 0,
			    max: 110,
			    tickOptions: {
				formatString: '%.2f%'
			    }
			}
		    }
		}); 
	    } else {
		$('#peo_attainment_data').html('<h4 class="err_msg">'+ student_outcomes_full + '('+sos+') are not finalized for this Curriculum. <br> Kindly finalize the overall PO Attainment.</h4>');
	    }

	}

    });
});

//export button for direct attainment

$('#add_form').on('click', '#exportToPDF', function () {
    var crclm_name = $('#crclm_id :selected').text();
    var peo_attainment_graph = $('#chart1').jqplotToImageStr({});
    var peo_attainment_graph_image = $('<img/>').attr('src', peo_attainment_graph);
    $('#peo_attainment_graph_data').html('<table><tr><td><b>Curriculum :</b></td><td>' + crclm_name + '</td></tr></table><br><b>Program Educational Objective (PEO) Attainment</b><br/><br/>');
    $('#peo_attainment_graph_data').append(peo_attainment_graph_image);
    $('#peo_attainment_graph_data').append('<br><br>');
    $('#peo_attainment_graph_data').append($('#peo_attainment_data').clone().html());
    $('#peo_attainment_graph_data').append($('#peo_attainment_note').clone().html());
    var peo_attainment_pdf = $('#peo_attainment_graph_data').clone().html();
    $('#peo_attainment_graph_data_hidden').val(peo_attainment_pdf);
    $('#add_form').submit();
});

//Drilldown in Direct Attainment
$('.displayDrilldown').live('click', function () {

    document.getElementById('drilldown_content_display').innerHTML = "";
    var crclm_id = $('#crclm_id').val();
    var peo_id = $(this).attr('abbr');
    var po_id = $(this).attr('data-id');

    var post_data = {
	'crclm_id': crclm_id,
	'peo_id': peo_id
    }

    $.ajax({
	type: "POST",
	url: base_url + 'tier_ii/peo_level_attainment/course_peo_attainment',
	data: post_data,
	dataType: 'JSON',
	success: function (json_graph_data) {
	    if (json_graph_data.length > 0) {
		var i = 0;
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

		peo_attainment = new Array();

		cours_code = new Array();
		course_title = new Array();
		attainment_perc = new Array();
		attainment_level = new Array();
		$.each(json_graph_data, function () {
		    value3[i] = this['peo_statement'];
		    value4[i] = this['peo_reference'];
		    value1[i] = this['attainment_perc'];
		    crs_id[i] = this['crs_title'];
		    crs_code[i] = this['crs_code'];
		    attainment_level[i] = json_graph_data[i].attainment_level;
		    var graph_value = parseFloat(value1[i]);
		    if (graph_value.toFixed(2) != 0.00) {
			data1.push(parseFloat(graph_value));
			data3.push(crs_id[i]);
			data4.push(crs_code[i]);
			data5.push(value3[i]);
			data6.push(value4[i]);
			peo_attainment.push(parseFloat(this['attainment_perc']));
		    }

		    i++;
		});
		var m = 0;
		var i = 0;
		$('#peo_statement').html('<div class="row-fluid"><div class="span12"><p colspan="2"><center><b>Program Educational Objective Statement</b> :  ' + data6[m] + '.  ' + data5[m] + '</center></p></div></div>');
		$('#drilldown_content_display').html('<div class="row-fluid"><div class="span12"><div class="span6" id="course_po_graph" class="jqplot-target" style="height:250px;"></div><div class="span6"><table border=1 class="table table-bordered" id=course_peo_attainment><thead></thead><tbody id="course_attainment_body"></tbody></table></div><div id=course_peo_attainment_note class=span12></div></div></div>');
		$('#course_peo_attainment > tbody:first').append('<tr><td><center><b>PO Statement</b></td><td><center><b> PO Attainment %</b></center></td><td><center><b>Attainment Level</b></center></td></tr>');
		$.each(json_graph_data, function () {
		    var perc = value1[i];
		    if (perc == 0.00) {
			perc = "-";
		    } else {
			perc = perc + '%';
		    }
		    var attain_level = json_graph_data[i].attainment_level;
		    if (parseFloat(attain_level).toFixed(2) == 0.00) {
			attain_level = "-";
		    }
		    $('#course_peo_attainment > tbody:last').append('<tr><td><font color="blue">' + json_graph_data[i].po_reference + '</font>-' + json_graph_data[i].po_statement + '</td><td><center>' + perc + ' </center></td><td><center>' + attain_level + ' </center></td></tr>');
		    i++;
		});
		$('#course_peo_attainment_note').html('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above bar graph depicts individual ' + so + ' attainment contributed under selected PEO Statement.</td></tr></tbody></table></div>');

		var s1 = data1;
		var ticks = data4;

		var plot3 = $.jqplot('course_po_graph', [peo_attainment], {
		    seriesDefaults: {
			renderer: $.jqplot.BarRenderer,
			rendererOptions: {
			    barWidth: 20,
			    fillToZero: true
			},
			pointLabels: {
			    show: true
			}
		    },
		    series: [{
			    label: so + ' Attainment %'
			}
		    ],
		    highlighter: {
			show: true,
			tooltipLocation: 'e',
			tooltipAxes: 'x',
			showMarker: false,
			tooltipContentEditor: function (str, seriesIndex, pointIndex, plot) {
			    return crs_id[pointIndex];
			}
		    },
		    legend: {
			show: true,
			placement: 'outsideGrid'
		    },
		    axes: {
			xaxis: {
			    renderer: $.jqplot.CategoryAxisRenderer,
			    ticks: ticks

			},
			yaxis: {
			    padMin: 0,
			    min: 0,
			    max: 110,
			    tickOptions: {
				formatString: '%.2f%'
			    }
			}
		    }
		});
		$('.myModalQPdisplay_paper_modal_2').on('shown', function () {
		    plot3.replot();
		});
	    } else {
		$('#course_po_attainment_nav').html('<div class="navbar"><div class="navbar-inner-custom">' + student_outcome_full + ' Attainment</div></div>');
		$('#course_po_attainment_no_data').html('<h5><center>' + student_outcome_full + ' Attainment data unavailable for selected term.</center></h5>');
	    }
	}
    });
    //$('#drilldown_content_display').html('<div class="row-fluid"><div class="span12"><div class="span6" id="course_po_graph" class="jqplot-target" style="height:250px;"></div><div class="span6"><table border=1 class="table table-bordered" id=course_po_attainment><thead></thead><tbody id="course_attainment_body"></tbody></table></div><div id=course_po_attainment_note class=span12></div></div></div>');
    $('.myModalQPdisplay_paper_modal_2').modal('show');
});


//Indirect Attainment

$('#indirect_attainment_form').on('change', '#survey_id', function () {
    var survey_id = $('#survey_id').val();
    plot_graphs_indirect_attainment(survey_id);
});

function plot_graphs_indirect_attainment(survey_id) {
    $('#graph_val').empty();
    $('#loading').show();
    if (survey_id == "") {
	$('#chart_plot_peo_indirect_attain').html('');
	$('#graph_val').html("<center><h4 style='color:red;'>Survey is empty</h4></center>");
	$('#loading').hide();
    } else {

	var post_data = {
	    'survey_id': survey_id,
	    'crclm_id': $('#crclm_id').val(),
	}
	$.ajax({
	    type: "POST",
	    url: base_url + 'tier_ii/peo_level_attainment/get_survey_data_indirect_attainment',
	    data: post_data,
	    dataType: 'HTML',
	    success: function (survey_data) {
		var data = jQuery.parseJSON(survey_data);
		if (data.length > 0) {
		    $('#peo_level_indirect_div').html('<div class="row-fluid"><div class="span12 rightJustified" ><a id="exportToPDF" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a></div<div class="span12"></div></div><br><div class="navbar"><div class="navbar-inner-custom">Program Educational Objective (PEO) Indirect Attainment Analysis</div></div>');
		    $('#chart_plot_peo_indirect_attain').html('<div class=row-fluid><div class=span12><div id=peoindirect_attain_chart_shv style=position:relative; class=jqplot-target></div></div></div>');
		    var table = '<table class="table table-bordered table-stripped">';
		    table += '<thead><tr><th><center>PEO Reference</center></th><th><center>PEO Statement</center></th><th><center>Attainment %</center></th><th><center>Attainment Level</center></th></tr></thead>';
		    table += '<tbody>';
		    var i = 0;
		    var datax = new Array();
		    var datay = new Array();
		    var peo_ref = new Array();
		    var attainment_perc = new Array();
		    $.each(data, function () {
			peo_ref[i] = data[i].peo_reference;
			attainment_perc[i] = data[i].ia_percentage;
			datax.push(peo_ref[i]);	
			datay.push(parseFloat(this['ia_percentage']));

			table += '<tr>';
			table += '<td>' + data[i].peo_reference + '</td>';
			table += '<td>' + data[i].peo_statement + '</td>';
			table += '<td><center>' + data[i].ia_percentage + '%</center></td>';
			table += '<td><center>' + data[i].attainment_level + '</center></td>';
			table += '</tr>';
			i++;
		    });
		    table += '</tbody>';
		    table += '</table>';
		    var ticks = datax;
		    var plot1 = $.jqplot('peoindirect_attain_chart_shv', [datay], {
			seriesColors: ["#4BB2C5"],
			seriesDefaults: {
			    renderer: $.jqplot.BarRenderer,
			    rendererOptions: {
				barWidth: 15,
				fillToZero: true
			    },
			    pointLabels: {
				show: true
			    }
			},
			series: [{
				label: 'Attainment %'
			    },
			],
			highlighter: {
			    show: true,
			    tooltipLocation: 'e',
			    tooltipAxes: 'x',
			    showMarker: false,
			    tooltipContentEditor: function (str, seriesIndex, pointIndex, plot) {
				return datax[pointIndex];
			    }
			},
			legend: {
			    show: true,
			    placement: 'outsideGrid'
			},
			axes: {
			    xaxis: {
				renderer: $.jqplot.CategoryAxisRenderer,
				ticks: ticks
			    },
			    yaxis: {
				padMin: 0,
				min: 0,
				max: 100,
				tickOptions: {
				    formatString: '%.2f%'
				}
			    }
			}

		    });
		    $('#graph_val').html(table);
		} else {
		    var survey_link = base_url + 'survey/host_survey';
		    $('#graph_val').html("<center><span style='color:red;'>The selected survey is still <b>In-Progress</b> status. Change the survey status to <b>Closed</b> to view Program Educational Outcomes (PEOs) Indirect Attainment.<br\> Use this link to close the hosted survey : <a href=" + survey_link + "> </span>survey close link</a></center>");
		}
		$('#loading').hide();
	    },
	});
    } 
}

//Export button for Indirect attainment

$('#indirect_attainment_form').on('click', '#exportToPDF', function () {
    var crclm_name = $('#crclm_id :selected').text();
    var term_name = $('#term :selected').text();
    var course_name = $('#course :selected').text();
    var peo_attainment_graph = $('#peoindirect_attain_chart_shv').jqplotToImageStr({});
    var peo_attainment_graph_image = $('<img/>').attr('src', peo_attainment_graph);
    $('#peo_indirect_attainment_graph_data').html('<table><tr><td><b>Curriculum :</b></td><td>' + crclm_name + '</td></tr></table><br><b>Program Educational Objectives (PEOs) Indirect Attainment Analysis</b><br />');
    $('#peo_indirect_attainment_graph_data').append("<br>");
    $('#peo_indirect_attainment_graph_data').append(peo_attainment_graph_image);
    $('#peo_indirect_attainment_graph_data').append("<br/><br/><br/>");
    $('#peo_indirect_attainment_graph_data').append($('#graph_val').clone().html());
    var peo_indirect_attainment_pdf = $('#peo_indirect_attainment_graph_data').clone().html();
    $('#peo_indirect_attainment_graph_data_hidden').val(peo_indirect_attainment_pdf);
    $('#indirect_attainment_form').submit();

});


//get finalized PEO data for direct indirect attainment
function get_finalized_data(crclm_id) {
    var url = base_url + 'tier_ii/peo_level_attainment/get_finalized_peo_data';
    $.ajax({
	type: "POST",
	url: url,
	data: {'crclm_id': crclm_id},
	success: function (peo_data) {
	    var data = $.parseJSON(peo_data);
	    //$('#finalized_peo_data').html("<div class='navbar'><div class='navbar-inner-custom'>Overall " + student_outcome + " Attainment finalized </div></div>");
	    if (data.length > 0) {
		
	    } else {
		$('#finalized_peo_data').append("<center><span class='err_msg'>" + student_outcome + " is not finalized for this curriculum.</span></center><hr>");
	    }

	},
    });
}

//Direct Indirect Attainment

$('#peo_indirect_direct_attainment_form').on('click', '#save_peo_attainment', function (e) {
    $('#peo_indirect_direct_attainment_form').validate({
	errorPlacement: function (error, element) {
	    error.appendTo(element.parent());
	}
    });

    $('#add_form').validate();
    empty_direct_indirect_attainment();
    var k = 0;
    $('.survey_title_shv').each(function () {
	if ($('.survey_title_shv').val().length > 0) {
	    k++;
	}
    });
    var valid_status = survey_form_validation();
    if (k == 0) {
	valid_status = true;
    }
    if ($('#peo_indirect_direct_attainment_form').valid()) {
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
	
	    plot_graphs_comparison_attainment(crclm_id, term_id, direct_attainment_val, indirect_attainment_val, type_data, survey_id_arr, survey_perc_arr, po_attainment_type, core_courses_cbk);
	}
    }
    e.preventDefault();
});

// function for displaying graph
function plot_graphs_comparison_attainment(crclm_id, term_id, direct_attainment_val, indirect_attainment_val, type_data, survey_id_arr, survey_perc_arr, po_attainment_type, core_courses_cbk) {
    $('#loading').show();
    var post_data = {
	'survey_id_arr': survey_id_arr,
	'crclm_id': crclm_id,
	'term_id': term_id,
	'direct_attainment_val': direct_attainment_val,
	'indirect_attainment_val': indirect_attainment_val,
	'type_data': type_data,
	'survey_perc_arr': survey_perc_arr,
	'po_attainment_type': po_attainment_type,
	'core_courses_cbk': core_courses_cbk,
    }

    $.ajax({
	type: "POST",
	url: base_url + 'tier_ii/peo_level_attainment/get_direct_indirect_peo_attainment_data',
	data: post_data,
	dataType: 'JSON',
	success: function (json_graph_data) {
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
		attainment_level = new Array();

		clo_statement_data = new Array();
		question_data = new Array();
		Agree_data = new Array();
		Disagree_data = new Array();
		Fairly_Agree_data = new Array();
		Strongly_Agree_data = new Array();

		var data1 = new Array();
		var data2 = new Array();
		var data3 = new Array();
		$.each(json_graph_data, function () {
		    attainment[i] = this['Attaintment'];
		    attainment_pc[i] = this['totalIndirectAttaintment'];
		    clo_code[i] = this['peo_reference'];
		    clo_id[i] = this['peo_id'];
		    direct_attainment[i] = this['directAttaintment'];
		    direct_percentage[i] = this['directPercentage'];
		    indirect_attainment[i] = this['indirectAttaintment'];
		    indirect_percentage[i] = this['indirectPercentage'];
		    total_direct_attainment[i] = this['totalDirectAttaintment'];
		    attainment_level[i] = this['Attaintment_level'];

		    data1.push(clo_code[i]);
		    data2.push(i);
		    data3.push(Number(attainment[i]));
		    i++;

		});
		$('#peo_level_comparison_nav').html('<div class="row-fluid"><div class="span6"></div><div class="span6 rightJustified" ><a id="exportToPDF" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export</a></div></div><br><div class="navbar"><div class="navbar-inner-custom">' + student_outcome_full + ' ' + student_outcome_val + ' Direct and Indirect Attainment Analysis</div></div>');
		$('#chart_plot_7').html('<div class=row-fluid><div class=span12><div class=span12><div id=chart_shv1 style=position:relative; class=jqplot-target></div></div><div class=span11 id="course_outcome_attainment_div"> <table border=1 class="table table-bordered" id=po_level_comparison_nav_table_id><thead></thead><tbody id="co_level_body"></tbody></table><div id="actual_course_attainment"></div></div><div id=docs class=span12></div></div></div>');
		$('#po_level_comparison_nav_table_id > tbody:first').append('<tr><td><center><b>PEO Reference</b></center></td><td><center><b>Actual Direct Attainment %</b></center></td><td><center><b>Actual Indirect Attainment %</b></center></td><td><center><b>Direct Attainment Weightage %</b></center></td> <td><center><b>Indirect Attainment Weightage %</b></center></td><td style="white-space:no-wrap;"><center><b>After Weightage Direct Attainment %</b></center></td> <td><center><b>After Weightage Indirect Attainment %</b></center></td><td><center><b>Overall Attainment %</b></center></td><td><center><b>Attainment Level</b></center></td><!--<td><center><b>Threshold</b></center></td>--></tr>');
		$.each(json_graph_data, function () {
		    $('#po_level_comparison_nav_table_id > tbody:last').append('<tr><td><center>' + this['peo_reference'] + '</center></td><td style="text-align: right;">' + parseFloat(this['totalDirectAttaintment']).toFixed(2) + '</td><td style="text-align: right;">' +parseFloat(this['totalIndirectAttaintment']).toFixed(2) + '</td><td style="text-align: right;">' + parseFloat(this['directPercentage']).toFixed(2) + '</td><td style="text-align: right;">' + parseFloat(this['indirectPercentage']).toFixed(2) + '</td><td style="text-align: right;">' + this['directAttaintment'] + '</td><td style="text-align: right;">' + this['indirectAttaintment'] + '</td><td style="text-align: right;">' + this['Attaintment'] + '</td><td style="text-align: right;">' + this['Attaintment_level'] + '</td><!--<td style="text-align: right;">' + this['po_minthreshhold'] + '</td>--></tr>');
		});
//		$('#finalize_btn').html('<hr><div class="row-fluid"><div class="pull-right"><a href="#finalize_direct_indirect_confirmation" id="finalize_po" class="btn btn-medium btn-success" data-toggle="modal" data-original-title="Finalize" rel="tooltip" title="finalize"><i class="icon-ok icon-white"></i> Finalize Attainment</a></div></div>');

		var ticks = data1;
		//var s1 = data2;
		var plot1 = $.jqplot('chart_shv1', [data3], {
		    seriesColors: ["#4bb2c5"],
		    seriesDefaults: {
			renderer: $.jqplot.BarRenderer,
			rendererOptions: {
			    barWidth: 15,
			    fillToZero: true
			},
			pointLabels: {
			    show: true
			}
		    },
		    series: [{
			    label: ' PEO Attainment %'
			},
		    ],
		    highlighter: {
			show: true,
			tooltipLocation: 'e',
			tooltipAxes: 'x',
			showMarker: false,
			tooltipContentEditor: function (str, seriesIndex, pointIndex, plot) {
			    return data3[pointIndex] + '%';
			}
		    },
		    legend: {
			show: true,
			placement: 'outsideGrid'
		    },
		    axes: {
			xaxis: {
			    renderer: $.jqplot.CategoryAxisRenderer,
			    ticks: ticks
			},
			yaxis: {
			    padMin: 0,
			    min: 0,
			    max: 100,
			    tickOptions: {
				formatString: '%.2f%'
			    }
			}
		    }

		});

	    } else {
		$('#po_attainment_no_data_survey').html('<h5><center>' + student_outcome_full + ' Attainment data unavailable for selected term.</center></h5>');
	    }
	    $('#loading').hide();
	}
    });
}

$('#peo_indirect_direct_attainment_form').on('click', '#exportToPDF', function () {
    var crclm_name = $('#crclm_id :selected').text();
    var peo_attainment_graph = $('#chart_shv1').jqplotToImageStr({});
    var peo_attainment_graph_image = $('<img/>').attr('src', peo_attainment_graph);
    $('#peo_direct_indirect_attainment_graph_data').html('<table><tr><td><b>Curriculum :</b></td><td>' + crclm_name + '</td></tr></tr></table><br><b>Tier II Program Outcome (POs) Direct Indirect Attainment Analysis</b><br />');
    $('#peo_direct_indirect_attainment_graph_data').append("<br><center>");
    $('#peo_direct_indirect_attainment_graph_data').append(peo_attainment_graph_image);
    $('#peo_direct_indirect_attainment_graph_data').append("</center><br/><br/><br/>");
    $('#peo_direct_indirect_attainment_graph_data').append($('#course_outcome_attainment_div').clone().html());
    var peo_attainment_pdf = $('#peo_direct_indirect_attainment_graph_data').clone().html();
    $('#peo_direct_indirect_attainment_graph_data_hidden').val(peo_attainment_pdf);
    $('#peo_indirect_direct_attainment_form').submit();
});

var s_counter = new Array();
s_counter.push(1);

$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();
    $.validator.addMethod("onlyDigit", function (value, element) {
	return this.optional(element) || value.match(/^[0-9]+$/);
    }, "only digits.");
    jQuery.validator.addClassRules("required", {
	required: true,
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
		'survey_count': counter_val,
		's_data': survey_dd_data,
	    };
	    $.ajax({
		type: "POST",
		url: base_url + 'tier_ii/peo_level_attainment/add_more_rows',
		data: count_val,
		async: false,
		success: function (s_type) {
		    ++counter_val;
		    $('#survey_counter').val(counter_val);
		    $("#add_more_survey_rows").append(s_type);

		    s_counter.push(counter_val);
		    $('#counter').val(s_counter);
		    $('#indirect_attainment_val').trigger('change');
		}
	    });
	}
	e.preventDefault();

    });

    //Function to delete row
    $('.Delete').live('click', function () {

	rowId = $(this).attr("id").match(/\d+/g);

	if (rowId != 1) {
	    $(this).parent().parent().parent().remove();

	    var replaced_id = $(this).attr('id').replace('remove_field', '');
	    var peo_counter_index = $.inArray(parseInt(replaced_id), s_counter);
	    s_counter.splice(peo_counter_index, 1);
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

});

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
    } else {
	if (wgt_status == false) {
	    var k = 0;
	    $('.survey_title_shv').each(function () {
		if ($('.survey_title_shv').val().length > 0) {
		    k++;
		}
	    });
	    if (k == 0) {
		$('#msg').html("<p style='color:red' class='alert alert-info' role='alert'>Note: Below analysis is based purly on Direct Attainment as you have not selected any survey.</p>");
	    } else {
		$('#msg').html("<p style='color:red' class='alert alert-danger' role='alert'>Error!!! Please make sure that sum of Weightage is 100.</p>");
	    }

	    return false;
	} else {
	    if (repeated_dp == 0 && wgt_status == true) {
		$('#err_msg').html("");
		$('#msg').html("");
		return true;
	    }
	}

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

