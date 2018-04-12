/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function() {
    if ($.cookie('remember_crclm') != null) {
	// set the option to selected that corresponds to what the cookie is set to
	$('#tier1_crclm_id option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
        $('#tier1_crclm_id').trigger('change');
	
}
});
function empty_divs(){
	$('#clo_finalize_data_div').empty();
	$('#po_attainment_div').empty();
	$('#chart1').empty();
	$('#course_clo_attaiment_div').empty();
}
$('#tier1_crclm_id').on('change',function(){
    $.cookie('remember_crclm', $('#tier1_crclm_id option:selected').val(), {
		expires : 90,
		path : '/'
	});
	empty_divs(); //function call for clearing divs.
	$('#finalize_div').hide();
	$('#chart1').hide();
	var term_list_path = base_url + 'assessment_attainment/tier1_clo_overall_attainment/select_term';
	var data_val = $('#tier1_crclm_id').val();
	var post_data = {
		'crclm_id' : data_val
	}

	$.ajax({
		type : "POST",
		url : term_list_path,
		data : post_data,
		success : function (msg) {
			$('#tier1_term').html(msg);
			if ($.cookie('remember_crclm') != null) {
				// set the option to selected that corresponds to what the cookie is set to
				$('#tier1_term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);

				$('#tier1_term').trigger('change');
			}

		}
	});

});
$('#tier1_term').on('change',function(){
    $.cookie('remember_term', $('#tier1_term option:selected').val(), {
		expires : 90,
		path : '/'
	});
	//empty_all_divs();
	var course_list_path = base_url + 'assessment_attainment/tier1_clo_overall_attainment/select_course';
	var data_val = $('#tier1_term').val();
	
	empty_divs(); //function call for clearing divs.
	$('#finalize_div').hide();
	$('#chart1').hide();
	
	if (data_val) {
		var post_data = {
			'term_id' : data_val
		}
		$.ajax({
			type : "POST",
			url : course_list_path,
			data : post_data,
                        //async: false,
			success : function (msg) {
				$('#tier1_course').html(msg);
                                if ($.cookie('remember_term') != null) {
                                    
					// set the option to selected that corresponds to what the cookie is set to
					$('#tier1_course option[value="' + $.cookie('remember_course') + '"]').prop('selected', true);
                                        $('#tier1_course').trigger('change');
//					select_type();
//					select_survey();
//					select_survey_comparison();
//					show_finalized_co_grid();
				}
				
			}
		});
	} else {
		$('#tier1_course').html('<option value="">Select Course</option>');
	}

});

// Function to populate course related sections.

$('#tier1_course').on('change',function(){
   
    $.cookie('remember_course', $('#tier1_course option:selected').val(), {
		expires : 90,
		path : '/'
	});
  var course_id = $(this).val();
  var crclm_id = $('#tier1_crclm_id').val();
  var term_id = $('#tier1_term').val();
  $('#tier1_section').find('option:first').attr('selected', 'selected');
  $('#type_data').find('option:first').attr('selected', 'selected');
  $('#occasion').find('option:first').attr('selected', 'selected');
  empty_divs(); //function call for clearing divs.
	$('#finalize_div').hide();
	$('#chart1').hide();
	
  var post_data =  {'course_id':course_id,'crclm_id':crclm_id,'term_id':term_id};
  $.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_clo_overall_attainment/fetch_section_data',
			data : post_data,
			success : function (msg) {
                                    $('#tier1_section').html(msg);
                                    if ($.cookie('remember_course') != null) {
					// set the option to selected that corresponds to what the cookie is set to
					$('#tier1_section option[value="' + $.cookie('remember_section') + '"]').prop('selected', true);
					$('#tier1_section').trigger('change');
				}
			}
		});
});

$('#tier1_section').on('change',function(){
    $.cookie('remember_section', $('#tier1_section option:selected').val(), {
		expires : 90,
		path : '/'
	});
    select_type();
//    select_survey();
//    select_survey_comparison();
//    show_finalized_co_grid();
   // show_finalized_co_grid();
   
    
});
	
function select_type() {
	$.cookie('remember_section', $('#tier1_section option:selected').val(), {
		expires : 90,
		path : '/'
	});
	empty_divs(); //function call for clearing divs.
	$('#finalize_div').hide();
	$('#chart1').hide();
	$('#type_data').find('option:first').attr('selected', 'selected');
	$('#occasion_div').css({
		"display" : "none"
	});
	var course_id = $('#tier1_course').val();
	//document.getElementById('finalize_direct_indirect_div').style.visibility = 'hidden';
	if (course_id) {
//		$('#type_data').html('<option value=0>Select Type</option><option value=2>' + entity_cie + '</option><option value=1>' + entity_see + '</option><option value="cia_tee">Both ' + entity_cie + ' & ' + entity_see + '</option>');
		$('#type_data').html('<option value=0>Select Type</option><option value=2>' + entity_cie+ '</option>');
		//$('#type_data_survey').html('<option value=0>Select Type</option><option value=2>' + entity_cie + '</option><option value=1>' + entity_see + '</option><option value="cia_tee">Both ' + entity_cie + ' & ' + entity_see + '</option>');
		//$('#type_data_survey').html('<option value=0>Select Type</option><option value=2>' + entity_cie+ '</option>');
	} else {
		$('#occasion_div').css({
			"display" : "none"
		});
		$('#type_data').html('<option value=0>Select Type</option>');
		$('#type_data_survey').html('<option value=0>Select Type</option>');
	}
//	select_survey();
//	select_survey_comparison();
                                if ($.cookie('remember_section') != null) {
					// set the option to selected that corresponds to what the cookie is set to
					$('#type_data option[value="' + $.cookie('remember_type') + '"]').prop('selected', true);
					$('#type_data').trigger('change');
                                    }
}

$('#indirect_direct_attainment_form').on('change', '#type_data', function () {
    $.cookie('remember_type', $('#type_data option:selected').val(), {
		expires : 90,
		path : '/'
	});
	var type_data_id = $('#type_data').val();
//alert(type_data_id);
	if (type_data_id == 1) {
		//empty_direct_attainment_divs();
		$('#occasion_div').css({
			"display" : "none"
		});
		$('#actual_data_div').empty();
		var course = $('#tier1_course').val();
		var qpd_id = '';
		var type = 'tee';
		plot_graphs(course, qpd_id, type);
	} else if (type_data_id == 2) {
		//empty_direct_attainment_divs();
		var crclm_id = $('#tier1_crclm_id').val();
		var term_id = $('#tier1_term').val();
		var crs_id = $('#tier1_course').val();
		var section_id = $('#tier1_section').val();
		var post_data1 = {
			'crclm_id' : crclm_id,
			'term_id' : term_id,
			'crs_id' : crs_id,
                        'section_id': section_id,
		}
		$.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_clo_overall_attainment/select_occasion',
			data : post_data1,
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
					$('#actual_data_div').html('<font color="red">Continuous Internal Assessment (CIA) Occasions are not defined</font>');
				}
                             if ($.cookie('remember_type') != null) {
					// set the option to selected that corresponds to what the cookie is set to
					$('#occasion option[value="' + $.cookie('remember_occa') + '"]').prop('selected', true);
					$('#occasion').trigger('change');
                            }
			}
                       
		});
	} else if (type_data_id == 'cia_tee') {
		empty_direct_attainment_divs();
		$('#occasion_div').css({
			"display" : "none"
		});
		$('#actual_data_div').empty();
		var course = $('#course').val();
		var qpd_id = '';
		var type = 'cia_tee';
		//plot_graphs(course, qpd_id, type);
	}else{
		empty_divs(); //function call for clearing divs.
		$('#finalize_div').hide();
		$('#chart1').hide();
		$('#occasion').find('option:first').attr('selected', 'selected');
	}
});

$('#occasion').on('change',function(){
    $.cookie('remember_occa', $('#occasion option:selected').val(), {
		expires : 90,
		path : '/'
	});
    var crclm_id = $('#tier1_crclm_id').val();
    var term_id = $('#tier1_term').val();
    var course_id = $('#tier1_course').val();
    var section_id = $('#tier1_section').val();
    var type_id = $('#type_data').val();
    var occasion_id = $('#occasion').val();
    if(occasion_id == 'all_occasion'){
        $('#finalize_div').show();
    }else{
        $('#finalize_div').hide();
    }
    var post_data= {
                    'crclm_id':crclm_id,
                    'term_id':term_id,
                    'course_id':course_id,
                    'section_id':section_id,
                    'type_id':type_id,
                    'occasion_id':occasion_id,
                   };
        $.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_clo_overall_attainment/fetch_clo_ao_ttainment',
			data : post_data,
			dataType : 'json',
			success : function (success_msg) {
                            //console.log(success_msg.attainment_array);
							
							$('#chart1').show();
                            console.log(success_msg);
                            if(success_msg.error == 'false'){
								$('#finalize_div').show();
                                if(success_msg.flag == '1'){
                                    $('#co_finalized_tbl_div').empty();
                                    $('#co_finalized_tbl_div').html(success_msg.co_finalize_tbl);
                                    $('#po_attainment').empty();
                                    $('#po_attainment').html(success_msg.po_attainment_tbl);
                                    $('#clo_finalize_data_div').show();
                                    $('#po_attainment_div').show();
                                    
                                }else{
                                    $('#clo_finalize_data_div').hide();
                                    $('#po_attainment_div').hide();
                                }
                                    $('#course_clo_attaiment_div').empty();
                                    $('#course_clo_attaiment_div').html(success_msg.table);
                                var ticks = success_msg.clo_code;
//				var s1 = data1;
//				var s2 = data2;
                                $('#chart1').empty();
                                var plot1 = $.jqplot('chart1', [success_msg.threshold_array, success_msg.attainment_array], {
						//seriesColors : ["#FFA500", "#31B404"], // #4bb2c5   colors that will
						seriesColors : ["#3efc70", "#4bb2c5"], // #4bb2c5   colors that will
						// be assigned to the series.  If there are more series than colors, colors
						// will wrap around and start at the beginning again.
						seriesDefaults : {
							renderer : $.jqplot.BarRenderer,
							rendererOptions : {
								barWidth : 17,
								fillToZero : true
							},
							pointLabels : {
								show : true
							}
						},
						series : [{
								label : 'Threshold %'
							}, {
								label : 'Attainment %'
							}
						],
						highlighter : {
							show : true,
							tooltipLocation : 'e',
							tooltipAxes : 'x',
							fadeTooltip : true,
							showMarker : false,
							tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
								return success_msg.clo_stmt[pointIndex];
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
								max : 100,
								padMin : 0,
								tickOptions : {
									formatString : '%#.2f%'
								}
							}
						}
					});
                                
                            }else{
                                $('#chart1').empty();
                                $('#chart1').html(success_msg.error)
                                $('#finalize_div').hide();
								$('#course_clo_attaiment_div').empty();
                            }
				
			}
		});
});

// Function to show the attainment drilldown

$(document).on('click','.tier1_attainment_drilldown',function(){
  var clo_id = $(this).attr('data-clo_id');
  var sec_id = $('#tier1_section').val();
    var crclm = $('#tier1_crclm_id option:selected').text();
    var term = $('#tier1_term option:selected').text();
    var crs = $('#tier1_course option:selected').text();
    var section = $('#tier1_section option:selected').text();
  var post_data = {'clo_id':clo_id,'sec_id':sec_id,};
   $.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_clo_overall_attainment/fetch_drilldown_attainment_data',
			data : post_data,
			dataType : 'json',
			success : function (success_msg) {
                                    $('#cia').empty();
                                    $('#cia').html(success_msg.cia_wieghtage);
                                    $('#tee').empty();
                                    $('#tee').html(success_msg.tee_wieghtage);
                                    $('#clo_statement').empty();
                                    $('#clo_statement').html(success_msg.clo_data);
                                    $('#display_attainment_div').empty();
                                    $('#display_attainment_div').html(success_msg.table_data);
                                    
                                    $('#drill_crclm_name').html(crclm);
                                    $('#drill_term_name').html(term);
                                    $('#drill_crs_name').html(crs);
                                    $('#drill_sec_name').html(section);
                                    
                                    $('#attainmnet_modal').modal('show');
                        }
                });
});



$(document).on('click','.clo_view_details',function(){
  var clo_id = $(this).attr('data-clo_id');
   var crclm_id = $('#tier1_crclm_id').val();
    var term_id = $('#tier1_term').val();
    var course_id = $('#tier1_course').val();
    var section_id = $('#tier1_section').val();
    var type_id = $('#type_data').val();
    var occasion_id = $('#occasion').val();
    
    var crclm = $('#tier1_crclm_id option:selected').text();
    var term = $('#tier1_term option:selected').text();
    var crs = $('#tier1_course option:selected').text();
    var section = $('#tier1_section option:selected').text();
    
    var post_data= {
                    'clo_id':clo_id,
                    'crclm_id':crclm_id,
                    'term_id':term_id,
                    'course_id':course_id,
                    'section_id':section_id,
                    'type_id':type_id,
                    'occasion_id':occasion_id,
                   };
   $.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_clo_overall_attainment/get_co_questions',
			data : post_data,
			//dataType : 'json',
			success : function (success_msg) {
                            if(success_msg != 0){
                                $('#clo_statement_table').empty();
                                    $('#clo_statement_table').html(success_msg);
                            }else{
                                    $('#clo_statement_table').empty();
                                    $('#clo_statement_table').html('<b>No Data to Display.</b>');
                            }
                                    $('#crclm_name').html(crclm);
                                    $('#term_name').html(term);
                                    $('#crs_name').html(crs);
                                    $('#sec_name').html(section);
                                    $('#clo_details_modal').modal('show');
                        }
                });
});

$(document).on('click','#clo_attainment_finalize',function(){
    $('#section_attainment_finalize').modal('show');
});

$(document).on('click','#finalize_clo_attainment',function(){
    var crclm_id = $('#tier1_crclm_id').val();
    var term_id = $('#tier1_term').val();
    var course_id = $('#tier1_course').val();
    var section_id = $('#tier1_section').val();
    var type_id = $('#type_data').val();
    var occasion_id = $('#occasion').val();
    
    var post_data= {
                    'crclm_id':crclm_id,
                    'term_id':term_id,
                    'course_id':course_id,
                    'section_id':section_id,
                    'type_id':type_id,
                    'occasion_id':occasion_id,
                   };
                   
                   $.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_clo_overall_attainment/get_co_finalize',
			data : post_data,
			//dataType : 'json',
			success : function (success_msg) {
                           if($.trim(success_msg) == 'true'){
                               $('#section_attainment_finalize').modal('hide');
                               $('#clo_attainment_final_success').modal('show');
                           }else{
                               $('#clo_attainment_final_unsuccess').modal('show');
                           }
                        }
                });
});