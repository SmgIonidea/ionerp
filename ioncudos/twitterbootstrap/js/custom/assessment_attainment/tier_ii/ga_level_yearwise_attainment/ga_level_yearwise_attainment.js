var base_url = $('#get_base_url').val();
var ga_controller = base_url + 'tier_ii/ga_level_yearwise_attainment/';

$(document).ready(function () {
	empty_divs();
	if ($.cookie('remember_dept_id') != null) {
		$('#dept_id option[value="' + $.cookie('remember_dept_id') + '"]').prop('selected', true);
		$('#dept_id').trigger('change');
	}
	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	})
});

function empty_divs() {
	//$('#pgm_id').empty();
	$('#ga_year_wise_data').empty();
	$('#ga_attainment_chart').empty();
	$('#ga_chart').remove();

}
$('#dept_id').live('change', function () {
	var dept_id = $('#dept_id').val();
	$.cookie('remember_dept_id', $('#dept_id option:selected').val(), {
		expires : 90,
		path : '/'
	});
	var post_data = {
		'dept_id' : dept_id,
	}
	$.ajax({
		type : "POST",
		url : ga_controller + 'get_programs_by_dept',
		dataType : 'HTML',
		data : post_data,
		success : function (program_list) {
			$('#pgm_id').html(program_list);
			if ($.cookie('remember_pgm_id') != null) {
				$('#pgm_id option[value="' + $.cookie('remember_pgm_id') + '"]').prop('selected', true);
				$('#pgm_id').trigger('change');
			}
		},
		error : function (err_msg) {
			console.log(err_msg);
		}
	});
});

$('#pgm_id').live('change', function () {
	$.cookie('remember_pgm_id', $('#pgm_id option:selected').val(), {
		expires : 90,
		path : '/'
	});
	var pgm_id = $('#pgm_id').val();
	empty_divs();
	var date = new Date();
	var cur_year = date.getFullYear();
	var list = '';
	list += '<option value="0">Select Year</option>';
	for (var i = cur_year; i >= (cur_year - 10); i--) {
		list += '<option value="' + i + '">' + i + '</option>';
	}
	$('#year').html(list);
	if ($.cookie('remember_year') != null) {
		$('#year option[value="' + $.cookie('remember_year') + '"]').prop('selected', true);
		$('#year').trigger('change');
	}
});

$('#year').live('change', function () {
	$.cookie('remember_year', $('#year option:selected').val(), {
		expires : 90,
		path : '/'
	});
	empty_divs();
	var dept_id = $('#dept_id').val();
	var pgm_id = $('#pgm_id').val();
	var year = $('#year').val();
	$('#ga_attainment_chart').html('<div class=row-fluid><div class="span12 rightJustified"><a id="exportToPDF" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a></div><br/><br/><div class=span12><div class="navbar"><div class="navbar-inner-custom">Graduate Attributes (GAs) Attainment (Year wise)</div></div><div class=span12><div id=ga_chart style=position:relative; class=jqplot-target></div></div></div><div class=span10 style=overflow:auto; id="po_attainment_div"></div><div id=po_attainment_data class=span11></div></div>');
	var post_data = {
		'dept_id' : dept_id,
		'pgm_id' : pgm_id,
		'year' : year,
	}

	$.ajax({
		type : "POST",
		url : ga_controller + 'get_ga_report_year_wise',
		dataType : 'HTML',
		data : post_data,
		success : function (ga_report) {
			var data = $.parseJSON(ga_report);
			if (data.length > 0) {
				var datax = new Array();
				var datay = new Array();
				var ga_statements = new Array();
				var ga_id = new Array();
				var attainment = new Array();

				var table = '';
				table += '<table class="table table-bordered table-stripped">';
				table += '<thead>';
				table += '<tr><th><center>Sl No.</center></th><th><center>GA Statement</center></th><th><center>Attainment %</center></th><th><center>Attainment Level</center></th></tr>';
				table += '</thead>';
				table += '<tbody>';
				var i = 0;
				$.each(data, function () {
					ga_statements[i] = data[i].ga_statement;
					ga_id[i] = data[i].ga_id;
					attainment[i] = Number(data[i].Attainment).toFixed(2);
					datax.push("GA-"+(i+1));
					datay.push(attainment[i]);

					table += '<tr>';
					table += '<td><center>' + (i + 1) + '</center></td>';
					table += '<td><b>' + data[i].ga_statement + ' :</b> ' + data[i].ga_description + '</td>';
					table += '<td style="text-align:right;">' + Number(data[i].Attainment).toFixed(2) + ' %<br><a id="' + data[i].ga_id + '" class="cursor_pointer view_drilldown" ga="<b>' + data[i].ga_statement + '</b> : ' + data[i].ga_description + '">Drill Down</a></td>';
					table += '<td style="text-align:right;">' + Number(data[i].AttainmentLevel).toFixed(2) + '</td>';
					table += '</tr>';
					i++;
				});
				table += '</tbody>';
				table += '</table>';
				$('#ga_year_wise_data').html(table);

				var ticks = datax;
				var plot1 = $.jqplot('ga_chart', [datay], {
						seriesColors : ["#4BB2C5"], //"#3efc70",
						seriesDefaults : {
							renderer : $.jqplot.BarRenderer,
							rendererOptions : {
								barWidth : 15,
								fillToZero : true
							},
							pointLabels : {
								show : true,
								formatString : this.suffix = '%',
							}
						},
						series : [{
								label : 'Attainmet %'
							},
						],
						highlighter : {
							show : true,
							tooltipLocation : 'e', // location of tooltip: n, ne, e, se, s, sw, w, nw.
							tooltipAxes : 'x', // which axis values to display in the tooltip, x, y or both.
							showMarker : false,
							tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
								return ga_statements[pointIndex];
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
									formatString : '%.2f%',
								},
								suffix : '%',

							}
						}
					}); //end of jplot
			} else {
				$('#ga_year_wise_data').html("<h4 class='err_msg'>No data found</h4>");
			}
		},
		error : function (err_msg) {
			console.log(err_msg);
		}
	});
});

$('#ga_yearwise_add_form').on('click', '#exportToPDF', function () {
	var dept_name = $('#dept_id :selected').text();
	var pgm_id = $('#pgm_id :selected').text();
	var year = $('#year :selected').text();

	var ga_attainment_graph = $('#ga_chart').jqplotToImageStr({});
	var ga_attainment_graph_image = $('<img/>').attr('src', ga_attainment_graph);
	$('#ga_attainment_graph_data').html('<table><tr><td><b>Department :</b> ' + dept_name + '</td></tr><tr><td><b>Program :</b> ' + pgm_id + '</td></tr><tr><td><b>Academic End Year: </b>' + year + '</td></tr></table><br><hr><br/><b>Graduate Attributes (GAs) Attainment Year wise</b><br />');
	$('#ga_attainment_graph_data').append("<br/>");
	$('#ga_attainment_graph_data').append(ga_attainment_graph_image);
	$('#ga_attainment_graph_data').append("<br/><br/><br/>");
	$('#ga_attainment_graph_data').append($('#ga_year_wise_data').clone().html());
	var po_attainment_pdf = $('#ga_attainment_graph_data').clone().html();
	$('#ga_attainment_graph_data_hidden').val(po_attainment_pdf);
	$('#ga_yearwise_add_form').submit();

});

$(document).on('click', '.view_drilldown', function () {
	var ga_id = $(this).attr('id');
	var ga_desc = $(this).attr('ga');
	var dept_id = $('#dept_id').val();
	var pgm_id = $('#pgm_id').val();
	var year = $('#year').val();
	var post_data = {
		'ga_id' : ga_id,
		'pgm_id' : pgm_id,
		'year' : year,
	}

	$.ajax({
		type : "POST",
		url : ga_controller + 'get_ga_drill_down',
		data : post_data,
		dataType : "HTML",
		success : function (drill_down_data) {
			var data = jQuery.parseJSON(drill_down_data);
			$('#ga_drill_down_data').empty();
			if (data.length > 0) {

				$('#ga_drill_down_data').html("<h4>" + ga_desc + "</h4>");
				var table = "";
				table += '<table class="table table-bordered table-stripped">';
				table += '<thead><tr><th><center>Sl No.</center></th><th><center>Curriculum Name</center></th><th><center>Attainment %</center></th><th><center>Attainment Level</center></th></tr></thead>';
				table += '<tbody>';
				var i = 0;
				$.each(data, function () {
					table += '<tr>';
					table += '<td><center>' + (i + 1) + '</center></td>';
					table += '<td>' + data[i].crclm_name + '</td>';
					table += '<td style="text-align:right">' + Number(data[i].Attainment).toFixed(2) + '%</td>';
					table += '<td style="text-align:center"><b>' + Number(data[i].AttainmentLevel).toFixed(2) + '</b><br/>' + '<a ga_id="'+ga_id+'" id="' + data[i].crclm_id + '" crclm_name="' + data[i].crclm_name + '"class="cursor_pointer view_crclm_drilldown">Drill down' + '</a></td>';
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

$(document).on('click', '.view_crclm_drilldown', function () {
	var crclm_id = $(this).attr('id');
	var ga_id = $(this).attr('ga_id');

	var dept_id = $('#dept_id').val();
	var pgm_id = $('#pgm_id').val();
	var year = $('#year').val();
	var crclm_name = $(this).attr('crclm_name');
	var post_data = {
		'ga_id' : ga_id,
		'crclm_id' : crclm_id,
		'pgm_id' : pgm_id,
		'year' : year,
	}

	$.ajax({
		type : "POST",
		url : ga_controller + 'get_crclm_drill_down',
		data : post_data,
		dataType : "HTML",
		success : function (drill_down_data) {
			var data = jQuery.parseJSON(drill_down_data);
			if (data.length > 0) {

				$('#crclm_drill_down_data').html("<h4>Curriculum: " + crclm_name + "</h4>");
				var table = "";
				table += '<table class="table table-bordered table-stripped">';
				table += '<thead><tr><th><center>Sl No.</center></th><th><center>Program Outcome (PO)</center></th><th><center>Attainment %</center></th><th><center>Attainment Level</center></th></tr></thead>';
				table += '<tbody>';
				var i = 0;
				$.each(data, function () {
					table += '<tr>';
					table += '<td><center>' + (i + 1) + '</center></td>';
					table += '<td><a id="tooltip" data-toggle="tooltip" title="' + data[i].po_statement + '">' + data[i].po_reference + ': ' + getSubString(data[i].po_statement) + '</a></td>';
					table += '<td style="text-align:right">' + Number(data[i].Attainment).toFixed(2) + '%</td>';
					table += '<td style="text-align:center"><b>' + Number(data[i].AttainmentLevel).toFixed(2) + '</b></td>';
					table += '</tr>';
					i++;
				});
				table += '<tbody>';
				table += '</table>';
				$('#crclm_drill_down_data').append(table);
			} else {
				$('#crclm_drill_down_data').html("<h4 style='color:red;'><center>No Dril downs found</center></h4>");
			}
		},
		error : function (msg) {},
	});
	$('#crclm_drilldown_modal').modal('show');
});

function getSubString(str) {
	if (str.length <= 40) {
		return str;
	} else {
		return str.substring(0, 40) + '...';
	}
}
