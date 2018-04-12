/*
 * Description	:	JScript for Tier II PO Level Attainment

 * Created		:	December 11th, 2015

 * Author		:	 Shivaraj B

 * Modification History:
 * Date				Modified By				Description

----------------------------------------------------------------------------------------------*/
var base_url = $('#get_base_url').val();

$(document).ready(function () {
	$.validator.addClassRules("required", {
		required : true,
	});
});
//function to empty div's related to direct attainment
function empty_direct_attainment() {
	$('#po_attainment_chart_1').empty();
	$('#course_attainment_nav').empty();
	$('#course_attainment_no_data').empty();
	$('#po_attainment_chart_2').empty();
}
//function to empty div's related to indirect attainment
function empty_indirect_attainment() {
	$('#po_attainment_data').empty();
	$('#po_level_indirect_div').empty();
	$('#chart_plot_po_indirect_attain').empty();
	$('#graph_val').empty();
	$('#chart1').empty();
}
//function to empty div's related to direct indirect attainment
function empty_direct_indirect_attainment() {
	$('#po_level_comparison_nav').empty();
	$('#chart_plot_7').empty();
	$('#po_attainment_no_data_survey').empty();
	$('#finalize_btn').empty();

}
//function to empty all the div's
function empty_all_divs() {
	empty_direct_attainment();
	empty_indirect_attainment();
	empty_direct_indirect_attainment();
}

/* Function is used to set the default values to the various credits fields.
 * @param -
 * @returns-
 */
function setToggleStatusValues() {
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

/*     $('#term').multiselect({     
     buttonWidth: 220,
     numberDisplayed: 2,
     nSelectedText: 'selected',
     nonSelectedText: "Select Sector",
	 includeSelectAllOption: true,
	 templates: {
                button: '<button type="button" class="multiselect btn btn-link  dropdown-toggle" data-toggle="dropdown"></button>'
     }	 
    }); */

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

//List Page
if ($.cookie('remember_crclm') != null) {
	// set the option to selected that corresponds to what the cookie is set to
	$('#crclm_id option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
	select_term();
	select_survey();
        get_finalized_data($.cookie('remember_crclm'));
}
$('#crclm_id').live('change', function () {
	select_survey();
        get_finalized_data($('#crclm_id').val());
});

function select_term() {
	empty_all_divs();
	$.cookie('remember_crclm', $('#crclm_id option:selected').val(), {
		expires : 90,
		path : '/'
	});
	var term_list_path = base_url + 'tier_ii/po_level_attainment/select_term';
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
	showAttainment();
}




//To display PO Indirect Attainment data
function showAttainment() {
	empty_indirect_attainment();
	$('#loading').show();
	var term = $('#term').val();
	var crclm_id = $('#crclm_id').val();
	var core_crs_id = $('#core_crs_id').val();
if (term === null) {$('#export_doc').prop('disabled', true); }else{$('#export_doc').prop('disabled', false); }
	var post_data = {
		'crclm_id' : crclm_id,
		'term' : term,
		'core_crs_id' : core_crs_id,
	}
// dont remove bellow commented code 
//<div class="span12 rightJustified"><a id="exportToPDF" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a></div><br/><br/>
	$('#po_attainment_chart_1').html('<div class=row-fluid><div class=span12><div class="navbar"><div class="navbar-inner-custom">'+ student_outcome_full +' '+ student_outcome_val +' Attainment</div></div><div class="span12"><div id="chart1" style="position:relative;" class="jqplot-target"></div></div></div><div class=span10 style=overflow:auto; id="po_attainment_div"></div><div id=po_attainment_data class=span12></div></div>');

	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/po_level_attainment/POAttainment',
		data : post_data,
		dataType : 'JSON',
		success : function (json_graph_data) {
                        
		//var data = $.parseJSON(json_graph_data);
			var data = json_graph_data;
                        
			$('#loading').hide();
			if (data.po_data.length > 0) {
			
				var i = 0;
				var datax = new Array();
				var datay = new Array();
				var po_val = new Array();
				var po_perc = new Array();
				var po_statement = new Array();
				var parameter_list = new Array();
				var colors_list = new Array();
				
				var avarage_attmt_array = new Array();
				var threshold_attmt_array = new Array();
				var hml_attmt_array = new Array();
				var hml_mul_attmt_array =  new Array();
				var po_minthreshhold_array = new Array();
				
				var avarage_attmt = new Array();
				var threshold_attmt = new Array();
				var hml_attmt = new Array();
				var hml_mul_attmt =  new Array();
				var po_minthreshhold = new Array();
				$i=0;
				$.each(data['po_data'], function () {
					//populate data for graph
					po_val[i] = data['po_data'][i].po_reference;					
					po_statement.push(data['po_data'][i].po_statement);
					po_perc[i] = data['po_data'][i].direct_attainment;
					
					avarage_attmt_array[i] = data['po_data'][i].average_po_direct_attainment;
					threshold_attmt_array[i] = data['po_data'][i].threshold_po_direct_attainment;				
					hml_attmt_array[i] = data['po_data'][i].hml_weighted_average_da;
					hml_mul_attmt_array[i] = data['po_data'][i].hml_weighted_multiply_maplevel_da;
					po_minthreshhold_array[i] = data['po_data'][i].po_minthreshhold;
					
					avarage_attmt.push(avarage_attmt_array[i]);
					threshold_attmt.push(threshold_attmt_array[i]);
					hml_attmt.push(hml_attmt_array[i]);		
					hml_mul_attmt.push(hml_mul_attmt_array[i]);
					po_minthreshhold.push(po_minthreshhold_array[i]);
					datax.push(po_val[i]);					
					datay.push(po_perc[i]);
					i++;
				});		
				$('#po_attainment_data').html(data.po_attainment_tbl);
				
				$('#po_attainment_data').append(data.note);
				parameter_list.push(po_minthreshhold);
				colors_list.push("#3efc70");
				if(data['org_config'] == 'ALL'){					
					parameter_list.push(avarage_attmt); colors_list.push("#fe9a2e");
					parameter_list.push(threshold_attmt); colors_list.push("#4BB2C5");
					parameter_list.push(hml_attmt); colors_list.push("#f781f3");
					parameter_list.push(hml_mul_attmt); colors_list.push("#c5b47f");
				var note ="ALL";
				}else{
					for(m = 0; m < (data['org_array'].length) ; m++){
						val = data['org_array'][m]; 
						if(val == 'average_po_direct_attainment'){ parameter_list.push(avarage_attmt); colors_list.push("#fe9a2e"); }
						if(val == 'threshold_po_direct_attainment'){parameter_list.push(threshold_attmt); colors_list.push("#4BB2C5");}
						if(val == 'hml_weighted_average_da'){parameter_list.push(hml_attmt); colors_list.push("#f781f3"); }
						if(val == 'hml_weighted_multiply_maplevel_da'){parameter_list.push(hml_mul_attmt); colors_list.push("#c5b47f"); }
					}
				}
				var ticks = datax;
				var plot1 = $.jqplot('chart1', parameter_list , {
						seriesColors : colors_list, //"#3efc70",
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
									label : ' Threshold %'
								}, {
									label : 'Avg ' + so + ' Attainment %'
									
								}, {
                                     label : so + ' Attainment %'
                                }, {
                                     label : 'Average - Map Level <br/> Weighted  Attainment %'
                                }, {
                                     label : 'Average - Map Level <br/> Weighted * Mapped Value %'
                                }
						],
						highlighter : {
							show : true,
							tooltipLocation : 'e', // location of tooltip: n, ne, e, se, s, sw, w, nw.
							tooltipAxes : 'x', // which axis values to display in the tooltip, x, y or both.
							showMarker : false,
							tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
								return po_statement[pointIndex];
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
				 $('#export_doc').prop('disabled', false); 
			} else {
				if (term === null) {
					 $('#export_doc').prop('disabled', true); 
					$('#po_attainment_data').html('<h4 class="err_msg">Term is not selected</h4>');
				} else {			
					$('#export_doc').prop('disabled', true); 				
					$('#po_attainment_data').html('<h4 class="err_msg">' + student_outcome_full + ' (' + so + ') Attainment can be viewed ony after the Course - Course Outcomes (COs) Attainment is finalized.<br/> Kindly verfiy that Courses are finalized for Course Outcomes(COs) Attainment</h4>');
				}

			}

		}
	}); //ajax
} //end of showAttainment()

$('#finalize_po_attainment').live('click', function () {
	$('#po_indirect_direct_attainment_form').validate();
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
			var post_data = {
				'survey_id_arr' : survey_id_arr,
				'crclm_id' : crclm_id,
				'term_id' : term_id,
				'direct_attainment_val' : direct_attainment_val,
				'indirect_attainment_val' : indirect_attainment_val,
				'survey_perc_arr' : survey_perc_arr,
				'core_courses_cbk' : core_courses_cbk,
			}
			$.ajax({
				type : "POST",
				url : base_url + 'tier_ii/po_level_attainment/finalize_po_attainment',
				data : post_data,
				dataType : 'HTML',
				success : function (msg) {
					console.log(msg);
					$('#finalize_direct_indirect_confirmation').modal('hide');
					$('#finalize_direct_indirect_success').modal('show');
				}
			});
		}
	}
});

//Course Drilldown
$('.displayDrilldown').live('click', function () {
	//$('#loading').show();
	document.getElementById('drilldown_content_display').innerHTML = "";
	var crclm_id = $('#crclm_id').val();
	var type_data = $('#type_data').val();
	var term = $('#term').val();
	var po_id = $(this).attr('abbr');
	var data_display = $(this).attr('data-type'); 

	var po_attainment_type = $('#po_attainment_type').val();
	var core_crs_id = $('#core_crs_id').val();

	var post_data = {
		'term' : term,
		'po_id' : po_id,
		'crclm_id' : crclm_id,
		'core_crs_id' : core_crs_id,
		'data_display' : data_display,		
	}

	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/po_level_attainment/CoursePOAttainment',
		data : post_data,
		dataType : 'JSON',
		success : function (json_graph_data) {
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
				
				
				average_po_direct_attainment = new Array();
				threshold_po_direct_attainment = new Array();
				hml_weighted_average_da = new Array();
				hml_weighted_multiply_maplevel_da = new Array();
				

				po_attainment = new Array();
				var type_name ='';
				cours_code = new Array();
				course_title = new Array();
				attainment_perc = new Array();
				attainment_level = new Array();
				$.each(json_graph_data, function () {
					value3[i] = this['po_statement'];
					value4[i] = this['po_reference'];
					value1[i] = this['direct_attainment'];
					
					average_po_direct_attainment[i] = this['average_po_direct_attainment'];
					threshold_po_direct_attainment[i] = this['threshold_po_direct_attainment'];
					hml_weighted_average_da[i] = this['hml_weighted_average_da'];
					hml_weighted_multiply_maplevel_da[i] = this['hml_weighted_multiply_maplevel_da'];
					
					crs_id[i] = this['crs_title'];
					crs_code[i] = this['crs_code'];
					attainment_level[i] = json_graph_data[i].attainment_level;
					var graph_value1 = parseFloat(value1[i]); 
					var graph_value = '';
					if(data_display == 'average_po_direct_attainment'){						
						 graph_value = parseFloat(average_po_direct_attainment[i]);	
						type_name = 'Average of Secured Marks based (Average) Attainment %';
					}else if(data_display == 'threshold_po_direct_attainment'){
						 graph_value = parseFloat(threshold_po_direct_attainment[i]);
						 type_name = 'Threshold based (Average) Attainment %';
					}else if(data_display == 'hml_weighted_average_da'){
						 graph_value = parseFloat(hml_weighted_average_da[i]);
						 type_name = 'Threshold based (Average - Map Level Weighted) Attainment %';
					}else if(data_display == 'hml_weighted_multiply_maplevel_da'){
						 graph_value = parseFloat(hml_weighted_multiply_maplevel_da[i]);	
						 type_name = 'Threshold based (Average - Map Level Weighted * Mapped Value) Attainment %';
					}

					
					if (graph_value1.toFixed(2) != 0.00 ) {
						data1.push(parseFloat(graph_value1));
						data3.push(crs_id[i]);
						data4.push(crs_code[i]);
						data5.push(value3[i]);
						data6.push(value4[i]);
						po_attainment.push(parseFloat(graph_value));
					}

					i++;
				});
				var m = 0;
				var i = 0;
				$('#po_statement').html('<div class="row-fluid"><div class="span12"><p colspan="2"><center><b>' + student_outcome_full + '</b> :  ' + data6[m] + '.  ' + data5[m] + '</center></p></div></div>');
				$('#drilldown_content_display').html('<div class="row-fluid"><div class=""><div class="span12" id="course_po_graph" class="jqplot-target" style="height:250px;"></div><div class="span12"><table border=1 class="table table-bordered" id=course_po_attainment><thead></thead><tbody id="course_attainment_body"></tbody></table></div><div id=course_po_attainment_note class=span12></div></div></div>');
				$('#course_po_attainment > tbody:first').append('<tr><td><center><b>Course Code - Course Title</b></td><td><center><b>'+ type_name +'</b></center></td><td><center><b>Attainment Level</b></center></td></tr>');
				$.each(json_graph_data, function () {
					
					

					
					if(data_display == 'average_po_direct_attainment'){
						var perc = average_po_direct_attainment[i];
						var attain_level = json_graph_data[i].average_po_attainment_level;
					}else if(data_display == 'threshold_po_direct_attainment'){
						var perc = threshold_po_direct_attainment[i];
						var attain_level = json_graph_data[i].threshold_po_attainment_level;
					}else if(data_display == 'hml_weighted_average_da'){
						var perc = hml_weighted_average_da[i];
						var attain_level = json_graph_data[i].hml_wtd_avg_attainment_level;
					}else if(data_display == 'hml_weighted_multiply_maplevel_da'){
						var perc = hml_weighted_multiply_maplevel_da[i];					
						var attain_level = json_graph_data[i].hml_wtd_avg_mul_attainment_level;
					}
					
					
					
					if (perc == 0.00 || perc == 'null') {
						perc = "-";
					} else {
						perc = perc + '%';
					}
					
					if (parseFloat(attain_level).toFixed(2) == 0.00) {
						attain_level = "-";
					}
					$('#course_po_attainment > tbody:last').append('<tr><td><font color="blue">' + json_graph_data[i].crs_code + '</font>-' + json_graph_data[i].crs_title + '</td><td><center>' + perc + ' </center></td><td><center>' + attain_level + ' </center></td></tr>');
					i++;
				});
				$('#course_po_attainment_note').html('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>If <b>'+ so +' Attainment %</b> and <b>Attainment Level</b> are blank, it indicates that CO attainment for the respective courses are not finalized for calculating '+ so +' attainment.</td></tr><tr><td>The above bar graph depicts individual ' + so + ' attainment contributed by courses under selected Terms (Semester).</td></tr></tbody></table></div>');

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

	$('.myModalQPdisplay_paper_modal_2').modal('show');

});
$('#add_form').on('click', '#exportToPDF', function () {
	var crclm_name = $('#crclm_id :selected').text();
	var term_name = $('#term :selected').text();
	var po_attainment_graph = $('#chart1').jqplotToImageStr({});
	var po_attainment_graph_image = $('<img/>').attr('src', po_attainment_graph);
	$('#po_attainment_graph_data').html('<table><tr><td><b>Curriculum :</b></td><td>' + crclm_name + '</td></tr><tr><td><b>Term :</b></td><td>' + term_name + '</td></tr></table><br><b>' + student_outcome + ' Attainment</b><br/><br/>');
	$('#po_attainment_graph_data').append(po_attainment_graph_image);
	$('#po_attainment_graph_data').append('<br><br>');
	$('#po_attainment_graph_data').append($('#po_attainment_data').clone().html());
	$('#po_attainment_graph_data').append($('#po_attainment_note').clone().html());
	var po_attainment_pdf = $('#po_attainment_graph_data').clone().html();
	$('#po_attainment_graph_data_hidden').val(po_attainment_pdf);
	$('#add_form').submit();
});

//Indirect attainment
$('#po_attainment_form').on('click', '#exportToPDF', function () {
	var crclm_name = $('#crclm_id :selected').text();
	var term_name = $('#term :selected').text();
	var course_name = $('#course :selected').text();
	var po_attainment_graph = $('#poindirect_attain_chart_shv').jqplotToImageStr({});
	var po_attainment_graph_image = $('<img/>').attr('src', po_attainment_graph);
	$('#po_indirect_attainment_graph_data').html('<table><tr><td><b>Curriculum :</b></td><td>' + crclm_name + '</td></tr><tr><td><b>Term :</b></td><td>' + term_name + '</td></tr><tr><!--<td><b>Course :</b></td><td>' + course_name + '</td>--></tr></table><br><b>' + student_outcome_full + ' ' + student_outcomes_val + ' Indirect Attainment Analysis</b><br />');
	$('#po_indirect_attainment_graph_data').append("<br>");
	$('#po_indirect_attainment_graph_data').append(po_attainment_graph_image);
	$('#po_indirect_attainment_graph_data').append("<br/><br/><br/>");
	$('#po_indirect_attainment_graph_data').append($('#graph_val').clone().html());
	var po_indirect_attainment_pdf = $('#po_indirect_attainment_graph_data').clone().html();
	$('#po_indirect_attainment_graph_data_hidden').val(po_indirect_attainment_pdf);
	$('#po_attainment_form').submit();

});
//get finalized PO data for direct indirect attainment
function get_finalized_data(crclm_id){
    var url = base_url + 'tier_ii/po_level_attainment/get_finalized_po_data';
    $.ajax({
        type:"POST",
        url:url,
        data:{'crclm_id':crclm_id},
        success:function(po_data){
            var data = $.parseJSON(po_data);
            $('#finalized_po_data').html("<div class='navbar'><div class='navbar-inner-custom'>Overall "+student_outcome_val+" Attainment finalized </div></div>");
            if(data.length>0){
                var table = '';
                table +='<table class="table table-bordered" style="width:100%">';
                table +='<tr><!--<th>Sl No.</th>--><th><center>'+student_outcome_val+'</center></th><th><center>Direct Weightage</center></th><th><center>Direct Attainment %</center></th><th><center>Indirect Weightage</center></th><th><center>Indirect Attainment %</center></th><th><center>Overall Attainment %</center></th><th><center>Attainment Level</center></th></tr>';
                var i = 0;
                table +='<tbody>';
                $.each(data,function(){
                    table +='<tr>';
                   // table +='<td><center>'+(i+1)+'</center></td>';
                    table +='<td><center title="'+data[i].po_statement+'">'+data[i].po_reference+'</center></td>';
                    table +='<td style="text-align:center">'+data[i].da_weightage+'</td>';
                    table +='<td style="text-align:right">'+data[i].da_percentage+' %</td>';
                    table +='<td style="text-align:center">'+data[i].ia_weightage+'</td>';
                    table +='<td style="text-align:right">'+data[i].ia_percentage+' %</td>';
                    table +='<td style="text-align:right">'+data[i].overall_attainment+' %</td>';
                    table +='<td style="text-align:right">'+data[i].attainment_level+'</td>';
                    table +='</tr>';
                    i++;
                });
                table +='</tbody>';
                table +='</table>';
                table +='<hr>';
                $('#finalized_po_data').append(table);
            }else{
                $('#finalized_po_data').append("<center><span class='err_msg'>"+student_outcome+" is not finalized for this curriculum.</span></center><hr>");
            }
            
        },
    });
}
function select_survey() {
	$.cookie('remember_course_indirect', $('#course_indirect option:selected').val(), {
		expires : 90,
		path : '/'
	});
	empty_indirect_attainment();
	var course_list_path = base_url + 'tier_ii/po_level_attainment/select_survey';
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

$('#po_attainment_form').on('change', '#survey_id', function () {
	var survey_id = $('#survey_id').val();
	if(survey_id == ''){ $('#exportToPDF').hide();}else{  $('#exportToPDF').show();}
	plot_graphs_indirect_attainment(survey_id);
});

function plot_graphs_indirect_attainment(survey_id) {
	$('#graph_val').empty();
	$('#loading').show();
	//	 if($('#survey_id').val() == ''){ $('#export_doc').prop('disabled', true); } else{ $('#export_doc').prop('disabled', false); }
	if (survey_id == "") {
	$('#export_doc').prop('disabled', true); 
		$('#chart_plot_po_indirect_attain').html('');
		$('#graph_val').html("<center><h4 style='color:red;'>Survey is empty</h4></center>");
		$('#loading').hide();
	} else {
$('#export_doc').prop('disabled', false);
		var post_data = {
			'survey_id' : survey_id,
			'crclm_id' : $('#crclm_id').val(),
		}
		$.ajax({
			type : "POST",
			url : base_url + 'tier_ii/po_level_attainment/get_survey_data_indirect_attainment',
			data : post_data,
			dataType : 'HTML',
			success : function (survey_data) {
				var data = jQuery.parseJSON(survey_data);
				if(data.length>0) {
				//$('#po_level_indirect_div').html('<div class="row-fluid"><div class="span12 rightJustified" ><a id="exportToPDF" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a></div><div class="span12"></div></div><br><div class="navbar"><div class="navbar-inner-custom">' + student_outcome_full + ' ' + student_outcomes_val + ' Indirect Attainment Analysis</div></div>');
                                $('#po_level_indirect_div').html('<div class="row-fluid"><div class="span11 pull-right" ><button id="export_tab3_to_doc" class="export_doc btn-fix btn btn-success pull-right" abbr="3"><i class="icon-book icon-white"></i> Export .doc</button></div></div><br><div class="navbar"><div class="navbar-inner-custom">' + student_outcome_full + ' ' + student_outcomes_val + ' Indirect Attainment Analysis</div></div>');
				$('#chart_plot_po_indirect_attain').html('<div class=row-fluid><div class=span12><div id=poindirect_attain_chart_shv style=position:relative; class=jqplot-target></div></div></div>');
				var table = '<table class="table table-bordered table-stripped" style="width:100%">';
				table += '<tr><th width = 100><center>'+ so + ' reference</center></th><th width = 350><center>'+ student_outcome + ' ('+ so + ') statement</center></th><th width = 100><center>Attainment %</center></th><th width = 100><center>Attainment Level</center></th></tr>';
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
					table += '<td width = 100>' + data[i].po_reference + '</td>';
					table += '<td width = 350>' + data[i].po_statement + '</td>';
					table += '<td width = 100><center>' + data[i].ia_percentage + '%</center></td>';
					table += '<td width = 100><center>' + data[i].attainment_level + '</center></td>';
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
									formatString : '%.2f%'
								}
							}
						}

					});
				$('#graph_val').html(table);
			} else {
			var survey_link = base_url +'survey/host_survey';
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
	empty_direct_indirect_attainment();
	var course_list_path = base_url + 'tier_ii/po_level_attainment/select_survey';
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
	$('#po_attainment_form').validate();
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
			//if (survey_id != 0 && term_id !== null) {
			if (term_id !== null) {
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
		url : base_url + 'tier_ii/po_level_attainment/getDirectIndirectPOAttaintmentData',
		data : post_data,
		dataType : 'JSON',
		success : function (json_graph_data) {
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
					clo_code[i] = this['po_reference'];
					clo_id[i] = this['po_id'];
					direct_attainment[i] = this['directAttaintment'];
					direct_percentage[i] = this['directPercentage'];
					indirect_attainment[i] = this['indirectAttaintment'];
					indirect_percentage[i] = this['indirectPercentage'];
					total_direct_attainment[i] = this['totalDirectAttaintment'];
					attainment_level[i] = this['Attaintment_level'];

					//threshold[i] = this['po_minthreshhold'];

					data1.push(clo_code[i]);
					data2.push(i); //reserved for threshold column : By shivaraj B
					data3.push(Number(attainment[i]));
					i++;

				});
				$('#po_level_comparison_nav').html('<div class="row-fluid"><div class="span6"></div><div class="span6 rightJustified" ><a id="exportToPDF" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a></div></div><br><div class="navbar"><div class="navbar-inner-custom">' + student_outcome_full + ' ' + student_outcome_val + ' Direct and Indirect Attainment Analysis</div></div>');
				$('#chart_plot_7').html('<div class=row-fluid><div class=span12><div class=span12><div id=chart_shv1 style=position:relative; class=jqplot-target></div></div><div class=span11 id="course_outcome_attainment_div"> <table border=1 class="table table-bordered" id=po_level_comparison_nav_table_id><thead></thead><tbody id="co_level_body"></tbody></table><div id="actual_course_attainment"></div></div><div id=docs class=span12></div></div></div>');
				$('#po_level_comparison_nav_table_id > tbody:first').append('<tr><td><center><b>' + so + ' Reference</b></center></td><td><center><b>Actual Direct Attainment %</b></center></td><td><center><b>Actual Indirect Attainment %</b></center></td><td><center><b>Direct Attainment Weightage %</b></center></td> <td><center><b>Indirect Attainment Weightage %</b></center></td><td style="white-space:no-wrap;"><center><b>After Weightage Direct Attainment %</b></center></td> <td><center><b>After Weightage Indirect Attainment %</b></center></td><td><center><b>Overall Attainment %</b></center></td><td><center><b>Attainment Level</b></center></td><!--<td><center><b>Threshold</b></center></td>--></tr>');
				$.each(json_graph_data, function () {
					$('#po_level_comparison_nav_table_id > tbody:last').append('<tr><td><center>' + this['po_reference'] + '</center></td><td style="text-align: right;">' + parseFloat(this['totalDirectAttaintment']).toFixed(2) + '</td><td style="text-align: right;">' + parseFloat(this['totalIndirectAttaintment']).toFixed(2) + '</td><td style="text-align: right;">' + parseFloat(this['directPercentage']).toFixed(2) + '</td><td style="text-align: right;">' + parseFloat(this['indirectPercentage']).toFixed(2) + '</td><td style="text-align: right;">' + this['directAttaintment'] + '</td><td style="text-align: right;">' + this['indirectAttaintment'] + '</td><td style="text-align: right;">' + this['Attaintment'] + '</td><td style="text-align: right;">' + this['Attaintment_level'] + '</td><!--<td style="text-align: right;">' + this['po_minthreshhold'] + '</td>--></tr>');
				});
				$('#finalize_btn').html('<hr><div class="row-fluid"><div class="pull-right"><a href="#finalize_direct_indirect_confirmation" id="finalize_po" class="btn btn-medium btn-success" data-toggle="modal" data-original-title="Finalize" rel="tooltip" title="finalize"><i class="icon-ok icon-white"></i> Finalize Attainment</a></div></div>');

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

			} else {
				$('#po_attainment_no_data_survey').html('<h5><center>' + student_outcome_full + ' Attainment data unavailable for selected term.</center></h5>');
			}
			$('#loading').hide();
		}
	});
}
$('#po_indirect_direct_attainment_form').on('click', '#exportToPDF', function () {
	var crclm_name = $('#crclm_id :selected').text();
	var term_name = $('#term :selected').text();
	var course_name = $('#course :selected').text();

	var po_attainment_graph = $('#chart_shv1').jqplotToImageStr({});
	var po_attainment_graph_image = $('<img/>').attr('src', po_attainment_graph);
	$('#po_direct_indirect_attainment_graph_data').html('<table><tr><td><b>Curriculum :</b></td><td>' + crclm_name + '</td></tr><tr><td><b>Term :</b></td><td>' + term_name + '</td></tr></tr></table><br><b>Tier II Program Outcome (POs) Direct Indirect Attainment Analysis</b><br />');
	$('#po_direct_indirect_attainment_graph_data').append("<br><center>");
	$('#po_direct_indirect_attainment_graph_data').append(po_attainment_graph_image);
	$('#po_direct_indirect_attainment_graph_data').append("</center><br/><br/><br/>");
	$('#po_direct_indirect_attainment_graph_data').append($('#course_outcome_attainment_div').clone().html());
	var po_attainment_pdf = $('#po_direct_indirect_attainment_graph_data').clone().html();
	$('#po_direct_indirect_attainment_graph_data_hidden').val(po_attainment_pdf);
	$('#po_indirect_direct_attainment_form').submit();
});

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
				url : base_url + 'tier_ii/po_level_attainment/add_more_rows',
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


// Code added by Mritunjay B S

	
$(document).on('change','#term',function(){
    //empty_all_divs();
	//$('#loading').show();
	var crclm_id = $('#crclm_id').val();
	var term = $('#term').val();
        var post_data = {
      'crclm_id' : crclm_id,  
      'term' : term,  
    };
    $.ajax({
		type : "POST",
		url : base_url + 'tier_ii/po_level_attainment/select_activity',
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
 
  if(activity_id != ''){$('#export_doc').prop('disabled', true);} else{$('#export_doc').prop('disabled', true);}
  $.ajax({
		type : "POST",
		url : base_url + 'tier_ii/po_level_attainment/get_po_wise_attainment',
		data : post_data,
                dataType:'json',
		success : function (data) {
		
                    if($.trim(data.status) == 'success'){
                        //$(document).dataTable().fnDestroy();
                        $('#extra_crricular_po_attain_table_plot_div').empty();
                        $('#extra_crricular_activity_export_div').empty();
                        $('#extra_crricular_po_attain_table_plot_div').html(data.table);
                        var ticks = data.po_ref;
                                $('#extra_crricular_po_attain_chart').empty();
                                $('#extra_crricular_activity_export_div').append('<div class="row-fluid"><div class="span11 pull-right"><button type="button" id="export_tab2_to_doc" class="export_doc btn-fix btn btn-success pull-right" abbr="2"><i class="icon-book icon-white"></i> Export .doc</button></div></div>');
                                var plot1 = $.jqplot('extra_crricular_po_attain_chart', [data.po_attainment], {
								seriesColors : ["#4bb2c5"], // #4bb2c5   colors that will
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
                                        
                       // $('#po_attainment_data').dataTable();
                        // Table Row Grouping
                        $('#tab_po_attainment_data').dataTable({
                                "fnDrawCallback": function () {
                                    $('.group').parent().css({'background-color': '#C7C5C5'});
                                },
                                "aoColumnDefs": [
                                    {"sType": "natural", "aTargets": [0]}
                                ],
                                "sPaginationType": "bootstrap"

                            }).rowGrouping({iGroupingColumnIndex: 0,
                                bHideGroupingColumn: true});
                        
                    }else{
                        $('#export_doc').prop('disabled', true);
                        $('#extra_crricular_activity_export_div').empty();
                        $('#extra_crricular_po_attain_table_plot_div').empty();
                        $('#extra_crricular_po_attain_chart').empty();
                        $('#extra_crricular_po_attain_chart').html('<center>'+data.error_msg+'</center>');
                    }
                    
		}
	});
});

$('.toggle_doc_button').on('click' , function(){

	tab_id  = $(this).attr('id'); 
	if(tab_id == 'tab1'){
		if($('#term').val() == ''){ 	$('#export_doc').prop('disabled', true); } else{ $('#export_doc').prop('disabled', false); }
		
	}else if(tab_id == 'tab2'){
		if($('#activity_data').val() == ''){ $('#export_doc').prop('disabled', true); } else{ $('#export_doc').prop('disabled', true); }
	}else if(tab_id == 'tab3'){
	 if($('#survey_id').val() == ''){ $('#export_doc').prop('disabled', true); } else{ $('#export_doc').prop('disabled', false); }
	}
	
});

$('form[name="po_attainment_form"]').on('click', '#export_doc', function(e){
	var crclm_name = $("#crclm_id option:selected").text(); var term_name = $("#term option:selected").text();
		$('#po_attainment_graph_data').html('<table class="table table-bordered" style="width:100%;"><tr><td width=325><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Curriculum : </b><b needAlt=1 class="font_h ul_class">'+ crclm_name +'</b></td><td width=325><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Term :</b><b needAlt=1 class="font_h ul_class">' + term_name + '</b></td></tr></table>');

 	tab_id  = $("ul#myTab li.active").attr('id');
    e.preventDefault();
	if(tab_id == 'tab1' && $('#term').val() != ''){
		var imgData = $('#chart1').jqplotToImageStr({});
		var graph = $('#chart1').jqplotToImageStr({});
		//$('#image_data').html(graph);
		$('#form_name').val('po_attainment_form');
		//var export_data = $('#po_attainment_graph_data').html() + $('#image_data').html() +  $('#po_attainment_data').html();
		var export_data =  $('#po_attainment_data').html();
		$('#export_data_to_doc').val(export_data);
		filename = sos+'_Attainment';
		$('#po_attainment_graph_data').append('<br/><br/><b>Program Outcome ('+ sos +') Attainment</b><br><br/>');
		main_head = $('#po_attainment_graph_data').html() ;
    }else if(tab_id = 'tab2' && $('#activity_data').val() != ''){
	
		/*$('#form_name').val('extra_curriculum');
		var imgData = $('#extra_crricular_po_attain_chart').jqplotToImageStr({});
		var graph = $('#chart1').jqplotToImageStr({});
		var export_data =  $('#extra_crricular_po_attain_table_plot_div').html();
		filename = 'Extra-curricular/Co-curricular Activity ';
		$('#po_attainment_graph_data').append('<br/><br/><b>Extracurricular / Co - curricular Activity</b><br><br/>');
		main_head = $('#po_attainment_graph_data').html() ;*/
	} else if(tab_id = 'tab3' && $('#survey_id').val() != ''){ 

	$('#image_data').empty();
		var imgData = $('#poindirect_attain_chart_shv').jqplotToImageStr({});
			var imgElem1 = $('<img/>').attr('src', imgData);
		
		
	/* 	var imgelem = $('#poindirect_attain_chart_shv').jqplotToImageElem();
		var imageSrc = imgelem.src; // this stores the base64 image url in imagesrc
		open(imageSrc);
		
		 var img = new Image();
    img.src = imageSrc;
    //img_home.appendChild(img);
	alert(img); */

	//var export_data =   $('#po_attainment_graph_data').html() + $('#po_attainment_graph_data').append(imgElem1) + $('#graph_val').html();
	var export_data =    $('#graph_val').html();
	filename = 'Indirect_Attainment';
			$('#po_attainment_graph_data').append('<br/><br/><b>Program Outcome '+sos+' Indirect Attainment Analysis</b><br/></br>');
	main_head = $('#po_attainment_graph_data').html() ;
/* 		
		var test = $("#poindirect_attain_chart_shv").jqplotSaveImage();
		var imgElem2 = $('<img/>').attr('src', test); */
	}
	$('#file_name').val(filename);
	$('#export_data_to_doc').val(export_data);
	$('#export_graph_data_to_doc').val(imgData);
	$('#main_head').val(main_head);
	$('#po_attainment_form').submit();
});

$('#po_attainment_form').on('click', '.export_doc', function() {
    var tab_name = $('ul.nav-tabs li.active > a').attr('href').slice(1);
    $('#tab_name').val(tab_name);
    var text_value = $('ul.nav-tabs li.active').text();
    $('#file_name').val(text_value.trim());
    var export_data = '';
    if($(this).attr('id') == 'export_tab1_to_doc') {
       /* var imgData = $('#po_attainmnet_graph').jqplotToImageStr({});
        $('#export_graph_data_to_doc').val(imgData); */
    } else if($(this).attr('id') === 'export_tab2_to_doc') {
        var imgData = $('#extra_crricular_po_attain_chart').jqplotToImageStr({});
        $('#export_graph_data_to_doc').val(imgData); 
    } else if($(this).attr('id') === 'export_tab3_to_doc') {
        var imgData = $('#poindirect_attain_chart_shv').jqplotToImageStr({});
        $('#export_graph_data_to_doc').val(imgData); 
    } else {
    }
    $('#export_doc_data').val(export_data);
    $('#po_attainment_form').submit();
});

$('.myModalLevelDispaly').live('click', function (e) {
	$('#po_id').val($(this).attr('data-id'));
	$('#po_stmt').val($(this).attr('data-po_stmt'));	
	var post_data = {
		'po_id' : $('#po_id').val(),
	}
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/po_level_attainment/get_performance_level_attainments_by_po',
		data : post_data,
		datatype : "html",
		success : function (msg) {
			var data = jQuery.parseJSON(msg);
			var id = $('#po_id').val();
			var po_statement = $('#po_stmt').val();			

			$('#selected_po_vw').html("<p>" + po_statement + "</p>");
			if (data.length <= 0) {
				$('#performance_po_list').html("<h4><center>No Performance Levels found for this Program Outcome (PO)</center></h4>");
			} else {

				$('#performance_po_list').html("<table id='perform_po_list' class='table table-bordered'><thead><th><center>Sl.No</center></th><th><center>Level Name</center></th><th><center>Level Value</center></th><th><center>Start Range</center></th><th><center></center></th><th><center>End Range</center></th><th><center>Description</center></th></thead><tbody>");
				var i = 0;

				$.each(data, function () {
					var add_more_rows = "";
					add_more_rows += '<tr>';
					add_more_rows += '<td><center>' + (i + 1) + '</center></td>';
					add_more_rows += '<td><center>' + data[i].performance_level_name_alias + '</center></td>';
					add_more_rows += '<td><center>' + data[i].performance_level_value + '</center></td>';
					add_more_rows += '<td><center>' + data[i].start_range + '</center></td>';
					add_more_rows += '<td><center>' + data[i].conditional_opr + '</center></td>';
					add_more_rows += '<td><center>' + data[i].end_range + '</center></td>';
					add_more_rows += '<td><center>' + data[i].description + '</center></td>';
					add_more_rows += '</tr>';
					$('#perform_po_list').append(add_more_rows);
					i++;
				});
				$('#perform_po_list').append("</tbody></table>");
				
				
			} //end of if
			$('#myModalViewPoAssess').modal('show');
		}
	});
	e.preventDefault();
});


