/*
 * Description	:	JScript for Tier II GA Level Attainment

 * Created		:	December 21st, 2015

 * Author		:	 Shivaraj B

 * Modification History:
 * Date				Modified By				Description

----------------------------------------------------------------------------------------------*/
var base_url = $('#get_base_url').val();
$(document).ready(function () {
	if ($.cookie('remember_dept_id') != null) {
		$('#dept_id option[value="' + $.cookie('remember_dept_id') + '"]').prop('selected', true);
		$('#dept_id').trigger('change');
	}
});

$('#dept_id').live('change', function () {
	$.cookie('remember_dept_id', $('#dept_id option:selected').val(), {
		expires : 90,
		path : '/'
	});
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/ga_level_attainment/populate_program_list',
		data : '',
		dataType : 'HTML',
		success : function (pgm_list) {
			$('#pgm_type').html(pgm_list);
			if ($.cookie('remember_pgm_type') != null) {
				$('#pgm_type option[value="' + $.cookie('remember_pgm_type') + '"]').prop('selected', true);
				$('#pgm_type').trigger('change');
			}
		},
		error : function (err_msg) {
			console.log(err_msg);
		}

	});
});
//populate GA's
$('#pgm_type').live('change', function () {
	$.cookie('remember_pgm_type', $('#pgm_type option:selected').val(), {
		expires : 90,
		path : '/'
	});
	var pgm_type_id = $('#pgm_type').val();
	var post_data = {
		'pgm_type_id' : pgm_type_id,
	}
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/ga_level_attainment/poppulate_ga',
		data : post_data,
		dataType : 'HTML',
		success : function (ga_list) {
			$('#ga_id').html(ga_list);
			if ($.cookie('remember_ga') != null) {
				$('#ga_id option[value="' + $.cookie('remember_ga') + '"]').prop('selected', true);
				$('#ga_id').trigger('change');
			}
		},
		error : function (err_msg) {
			console.log(err_msg);
		}

	});
});
//get crclm wise ga data
$('#ga_id').live('change', function () {
	$.cookie('remember_ga', $('#ga_id option:selected').val(), {
		expires : 90,
		path : '/'
	});
	//$('#loading').show();
	var ga_id = $('#ga_id').val();
	var dept_id = $('#dept_id').val();
	var pgm_type = $('#pgm_type').val();
	if (ga_id != 0) {
		var post_data = {
			'ga_id' : ga_id,
			'dept_id' : dept_id,
			'pgmtype_id' : pgm_type,
		}
		$.ajax({
			type : "POST",
			url : base_url + 'tier_ii/ga_level_attainment/get_ga_data',
			data : post_data,
			dataType : 'HTML',
			success : function (ga_list) {
				var data = jQuery.parseJSON(ga_list);
				var table = '';
				var i = 0;
				var attain_perc = new Array();
				var crclm_list = new Array();
				var datax = new Array();
				var datay = new Array();

				$('#ga_attainment_chart').html('<div class=row-fluid><div class="span6"></div><div class="span6 rightJustified" ><a id="exportToPDF" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a></div><br/><br/><div class=span12><div class="navbar"><div class="navbar-inner-custom">Graduate Attributes (GAs) Level Attainment</div></div><div class=span12><div id=chart1 style=position:relative; class=jqplot-target></div></div></div><div class=span10 style=overflow:auto; id="po_attainment_div"></div><div id=po_attainment_data class=span11></div></div>');
				$('#ga_statement').html("<h4><u>Graduate Attributes (GAs)</u>:<br> " + data[0].ga_reference + " . " + data[0].ga_statement + " :- " + data[0].ga_description + "</h4><hr/>");

				table += '<table class="table table-bordered table-stripped" id="ga_table">';
				table += '<tbody><tr><th><center>Sl No.</center></th><th><center>Curriculum</center></th><th><center>Attainment %</center></th><th><center>Attainment Level</center></th></tr></thead>';
				table += '<tbody>';
				$.each(data, function () {
					attain_perc[i] = data[i].crclm_name;
					crclm_list[i] = data[i].overall_attainment;
					datay.push(crclm_list[i]);
					datax.push(attain_perc[i]);
					table += '<tr>';
					table += '<td style="text-align:center;">' + (i + 1) + '</td>';
					table += '<td style="text-align:center;">' + data[i].crclm_name + '</td>';
					table += '<td style="text-align:center;">' + data[i].overall_attainment + '%<br><a id="' + data[i].crclm_id + '" class="cursor_pointer view_drilldown" crclm="' + data[i].crclm_name + '">Drill Down</a></td>';
					table += '<td style="text-align:center;">' + data[i].attainment_level + '</td>';
					i++;
				});
				table += '</tbody>';
				table += '</table>';

				$('#ga_crclm_data').html(table);

				var ticks = datax;
				var plot1 = $.jqplot('chart1', [datay], {
						seriesColors : ["#4BB2C5"], //"#3efc70",
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
								label : 'GA Attainmet %'
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
								max : 110,
								tickOptions : {
									formatString : '%.2f%'
								}
							}
						}

					}); //end of jplot
				$('#loading').hide();
			},
			error : function (err_msg) {
				console.log(err_msg);
				$('#loading').hide();
			}

		});
	}
});
$(document).on('click', '.view_drilldown', function () {
	var crclm_id = $(this).attr('id');
	var crclm_name = $(this).attr('crclm');

	var post_data = {
		'ga_id' : $('#ga_id').val(),
		'crclm_id' : crclm_id,
	}
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/ga_level_attainment/get_ga_drill_down',
		data : post_data,
		dataType : "HTML",
		success : function (drill_down_data) {
			var data = jQuery.parseJSON(drill_down_data);
			$('#ga_drill_down_data').empty();
			if (data.length > 0) {
				$('#ga_drill_down_data').html("<h4>Curriculum: " + crclm_name + "</h4>");
				var table = "";
				table += '<table class="table table-bordered table-stripped">';
				table += '<thead><tr><th><center>Sl No.</center></th><th><center>'+ student_outcomes_full + '('+sos+')  Statement</center></th><th><center>Attainment %</center></th><th><center>Attainment Level</center></th></tr></thead>';
				table += '<tbody>';
				var i = 0;
				$.each(data, function () {
					table += '<tr>';
					table += '<td><center>' + (i + 1) + '</center></td>';
					table += '<td>' + data[i].po_reference + ' .' + data[i].po_statement + '</td>';
					table += '<td style="text-align:right">' + data[i].overall_attainment + '%</td>';
					table += '<td style="text-align:center"><b>' + data[i].attainment_level + '</b><br/>' + '<a id="' + data[i].po_id + '" class="cursor_pointer view_assess_levels">' + data[i].performance_level_name_alias + '</a></td>';
					table += '</tr>';
					i++;
				});
				table += '<tbody>';
				table += '</table>';
				$('#ga_drill_down_data').append(table);
			} else {
				$('#ga_drill_down_data').html("<h4 style='color:red;'><center>No Dril downs found</center></h4>");
			}
		},
		error : function (msg) {},
	});
	$('#drilldown_modal').modal('show');
});

$(document).on('click', '.view_assess_levels', function () {
	var po_id = $(this).attr('id');
	var post_data = {
		'po_id' : po_id,
	}
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/ga_level_attainment/get_assessment_levels_po',
		data : post_data,
		dataType : "HTML",
		success : function (assess_level_data) {
			var data = jQuery.parseJSON(assess_level_data);
			$('#ga_asess_level_for_po').empty();
			$('#ga_asess_level_for_po').html("<h4><b><u>Program Outcome (PO):- </u></b> <br/>" + data[0].po_reference + ". " + data[0].po_statement + "</h4>");
			if (data.length > 0) {
				var table = "";
				table += '<table class="table table-bordered table-stripped">';
				table += '<thead><tr><th><center>Sl No.</center></th><th><center>Performance <br>Level Name</center></th><th><center>Performance <br>Level Value</center></th><th><center>Performance Scale</center></th></tr></thead>';
				table += '<tbody>';
				var i = 0;
				$.each(data, function () {
					table += '<tr>';
					table += '<td><center>' + (i + 1) + '</center></td>';
					table += '<td>' + data[i].performance_level_name_alias + '</td>';
					table += '<td style="text-align:center">' + data[i].performance_level_value + '</td>';
					table += '<td><center>' + data[i].start_range + ' ' + data[i].conditional_opr + ' ' + data[i].end_range + '</center></td>';
					table += '</tr>';
					i++;
				});
				table += '<tbody>';
				table += '</table>';
				$('#ga_asess_level_for_po').append(table);
			} else {
				$('#ga_asess_level_for_po').append("<h4 style='color:red;'><center>No Levels found</center></h4>");
			}
		},
		error : function (msg) {},
	});
	$('#assess_level_modal').modal('show');
});

$('#ga_add_form').on('click', '#exportToPDF', function () {
	var dept_name = $('#dept_id :selected').text();
	var pgm_type = $('#pgm_type :selected').text();
	var ga_title = $('#ga_id :selected').text();

	var po_attainment_graph = $('#chart1').jqplotToImageStr({});
	var po_attainment_graph_image = $('<img/>').attr('src', po_attainment_graph);
	$('#ga_attainment_graph_data').html('<table><tr><td><b>Department :</b> ' + dept_name + '</td></tr><tr><td><b>Program Type :</b> ' + pgm_type + '</td></tr><tr><td><b>Graduate Attribute (GA): </b>' + ga_title + '</td></tr></table><br><hr><br/><b>Graduate Attributes (GAs) Attainment </b><br />');
	$('#ga_attainment_graph_data').append("<br/>");
	$('#ga_attainment_graph_data').append(po_attainment_graph_image);
	$('#ga_attainment_graph_data').append("<br/><br/><br/>");
	$('#ga_attainment_graph_data').append($('#ga_statement').clone().html());
	$('#ga_attainment_graph_data').append($('#ga_crclm_data').clone().html());
	var po_attainment_pdf = $('#ga_attainment_graph_data').clone().html();
	$('#ga_attainment_graph_data_hidden').val(po_attainment_pdf);
	$('#ga_add_form').submit();

});
