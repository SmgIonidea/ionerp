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
	console.log("");
	$('#error_marks').empty();
	//$("#occasion").empty();


}

function disable_export_to_doc() {
    $('.export_doc').prop('disabled', true);
}

$('#tier1_crclm_id').on('change',function(){
   // $('#loading').show();
    disable_export_to_doc();
    $.cookie('remember_crclm', $('#tier1_crclm_id option:selected').val(), {
		expires : 90,
		path : '/'
	});
	empty_divs(); //function call for clearing divs.
	$('#finalize_div').hide();
	$('#chart1').hide();
	var term_list_path = base_url + 'assessment_attainment/tier1_mte_clo_attainment/select_term';
	var data_val = $('#tier1_crclm_id').val();
	var post_data = {
		'crclm_id' : data_val
	}
	$('#tier1_term').find('option:first').attr('selected','selected');
	$('#tier1_course').find('option:first').attr('selected','selected');
	$('#tier1_section').find('option:first').attr('selected','selected');
	$('#type_data').find('option:first').attr('selected','selected');
	//$('#occasion').find('option:first').attr('selected','selected');
	$.ajax({
		type : "POST",
		url : term_list_path,
		data : post_data,
		success : function (msg) {
		    $('#loading').hide();
			$('#tier1_term').html(msg);
			if ($.cookie('remember_crclm') != null) {
				// set the option to selected that corresponds to what the cookie is set to
				$('#tier1_term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
				$('#tier1_term').trigger('change');
				$('#occassion').val('');
			}

		}
	});

});
$('#tier1_term').on('change',function(){
   $('#loading').show();
   disable_export_to_doc();
    $.cookie('remember_term', $('#tier1_term option:selected').val(), {
		expires : 90,
		path : '/'
	});
	$('#tier1_section').trigger('change');
	var course_list_path = base_url + 'assessment_attainment/tier1_mte_clo_attainment/select_course';
	var data_val = $('#tier1_term').val();
	
	empty_divs(); //function call for clearing divs.
	$('#finalize_div').hide();
	$('#chart1').hide();
	$('#tier1_course').find('option:first').attr('selected','selected');
	$('#tier1_section').find('option:first').attr('selected','selected');
	$('#type_data').find('option:first').attr('selected','selected');
	//$('#occasion').find('option:first').attr('selected','selected');
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
				$('#tier1_course').html(msg);   $('#loading').hide();
                                if ($.cookie('remember_term') != null) {
                                    
					// set the option to selected that corresponds to what the cookie is set to
					$('#tier1_course option[value="' + $.cookie('remember_course') + '"]').prop('selected', true);
                    $('#tier1_course').trigger('change');$('#occassion').val('');
				}
				
			}
		});
	} else {
	   $('#loading').hide();
		$('#tier1_course').html('<option value="">Select Course</option>');
	/* 	$('#occasion').empty();$('#occasion').rebuild();
		$('#occasion').multiselect(); */ 
		

	}

});

// Function to populate course related sections.

// $('#tier1_course').on('change',function(){
      // $('#loading').show();
      // disable_export_to_doc();
    // $.cookie('remember_course', $('#tier1_course option:selected').val(), {
		// expires : 90,
		// path : '/'
	// });	
	// var course_id = $(this).val();
	// alert(course_id);
	// if(course_id != ''){
	
	// }
// $('#loading').hide();
// });
	
// function select_type() {
// /* 	$.cookie('remember_section', $('#tier1_section option:selected').val(), {
		// expires : 90,
		// path : '/'
	// }); */
	// empty_divs(); //function call for clearing divs.
	// $('#finalize_div').hide();
	// $('#chart1').hide();
	// $('#type_data').find('option:first').attr('selected', 'selected');
	// $('#occasion_div').css({
		// "display" : "none"
	// });
	// var course_id = $('#tier1_course').val(); alert(course_id);
//	document.getElementById('finalize_direct_indirect_div').style.visibility = 'hidden';
	// if (course_id) {
		// $('#type_data').html('<option value=0>Select Type</option><option value=2>' + entity_mte+ '</option>');	
	// } else {
		// $('#occasion_div').css({
			// "display" : "none"
		// });
		// $('#type_data').html('<option value=0>Select Type</option>');
		// $('#type_data_survey').html('<option value=0>Select Type</option>');
	// }
//	select_survey();
//	select_survey_comparison();
                                // if ($.cookie('remember_section') != null) {
//					set the option to selected that corresponds to what the cookie is set to
					// $('#type_data option[value="' + $.cookie('remember_type') + '"]').prop('selected', true);
					// $('#type_data').trigger('change');
                                    // }
// }

$('.cancel_button').on('click', function(){
	window.location.href = 'tier1_course_clo_attainment';
});

$('#indirect_direct_attainment_form').on('change', '#tier1_course', function () {
    disable_export_to_doc();
   
	var type_data_id = 2;
	var tier1_course = $('#tier1_course').val(); 
	if(tier1_course){
	
	//$('#occasion').trigger('change');
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
		var post_data1 = {
			'crclm_id' : crclm_id,
			'term_id' : term_id,
			'crs_id' : crs_id,			
		}
		
		$.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_mte_clo_attainment/select_occasion',
			data : post_data1,
			success : function (occasionList) {
				if (occasionList != 0) {
					$('#occasion_div').css({
						"display" : "inline"
					});
					$('#occasion').empty();
					$('#occasion').html(occasionList);
					
					$('#occasion').multiselect('rebuild');
                                        $('.multi_select_decoration').prop('style','overflow:hidden;');
				} else {
					$('#occasion_div').css({
						"display" : "none"
					});
					$('#actual_data_div').html('<font color="red">Continuous Internal Assessment ('+ entity_mte +') Occasions are not defined</font>');
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
	   $('#loading').hide();
		empty_divs(); //function call for clearing divs.
		$('#finalize_div').hide();
		$('#chart1').hide();		
	}
	 
	}else{
	   $('#loading').hide();
	empty_divs(); //function call for clearing divs.

		$('#finalize_div').hide();
		$('#chart1').hide();		
	}
});

$('#occasion').on('change',function(){
  // $('#loading').show();
    disable_export_to_doc();

    var crclm_id = $('#tier1_crclm_id').val();
    var term_id = $('#tier1_term').val();
    var course_id = $('#tier1_course').val();    
    var type_id = 2;
    var occasion_id = $('#occasion').val(); 
		
	var occasion_not_selected = $('#occasion option:not(:selected)').length; 


    if(occasion_id != ""  && occasion_id != null  && occasion_id.length>0){ 
	$('#doc_occasion_id').val(occasion_id);
      
	var post_data= {
                        'crclm_id':crclm_id,
                        'term_id':term_id,
                        'course_id':course_id,
                        'type_id':type_id,
                        'occasion_id':occasion_id,
						'occasion_not_selected':occasion_not_selected
                       };
        $.ajax({
            type : "POST",
            url : base_url + 'assessment_attainment/tier1_mte_clo_attainment/fetch_clo_ao_ttainment',
            data : post_data,
            dataType : 'json',
            success : function (success_msg) {
                $('#chart1').show();
                if(occasion_id != "" ){ 
                    $('#export_doc').prop('disabled', false);
                    $('#note_data').html('<div class="span12"><table border="1" class="table table-bordered"><tbody><tr><td colspan="2"><b>Note:</b> The above bar graph depicts the overall class performance with respect to the Threshold % for individual Course Outcomes (COs). The Threshold based Attainment % & Average based Attainment % is calculated using the below formula.</td></tr><tr><td><b>For Threshold based Attainment % = ( x / y ) * 100 </b><br/> x = Count of Students &gt;= to Threshold % <br/> y = Total number of Students Attempted .</td><td><b>For Average based Attainment % = ( x / y ) *100 </b> <br/> x = Average Secured marks of Attempted Students <br/> y = Maximum Marks . </td></tr></tbody></table></div>');
                }else{
                    $('#export_doc').prop('disabled', true);
                    $('#note_data').html("");
                }
                $('#loading').hide();
                $('#chart1').show();
                if(occasion_id != "" ){ $('#note_data').html('<div class="span12"><table border="1" class="table table-bordered"><tbody><tr><td colspan="2"><b>Note:</b> The above bar graph depicts the overall class performance with respect to the Threshold % for individual Course Outcomes (COs). The Threshold based Attainment % & Average based Attainment % is calculated using the below formula.</td></tr><tr><td><b>For Threshold based Attainment % = ( x / y ) * 100 </b><br/> x = Count of Students &gt;= to Threshold % <br/> y = Total number of Students Attempted .</td><td><b>For Average based Attainment % = ( x / y ) *100 </b> <br/> x = Average Secured marks of Attempted Students <br/> y = Maximum Marks . </td></tr></tbody></table></div>');}else{
                    $('#note_data').html("");
                }
                if(success_msg.error == 'false'){
				
						if($.trim(occasion_not_selected) == 0 && crclm_id != '' &&  term_id !='' && course_id != '' &&  success_msg.finalized_or_not == ''){
							$('#type_all_selected').val(occasion_not_selected);
								$('#finalize_div').show();
						}else{
								$('#finalize_div').hide();
								empty_divs(); 
						}
                    $('#export_doc').prop('disabled', false);
                    //$('#finalize_div').show();
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
                        //	$('#note_data').html("");
                    }
                    $('#section_wise_finalize').empty();
                    $('#section_wise_finalize').html(success_msg.table_finalize);									
                    if(success_msg.table_finalize_flag == 'Finalized'){										
                        $('#display_finalized_attainment_div').show();
                        $('#display_finalized_attainment_tbl').empty();
                        $('#display_finalized_attainment_tbl').html(success_msg.table_finalize);
                    }else{
                        $('#display_finalized_attainment_div').hide();
                        $('#display_finalized_attainment_tbl').empty();
                    }
                    $('#course_clo_attaiment_div').empty();
                    $('#course_clo_attaiment_div').html(success_msg.table);
					$('#error_marks').empty();
					$('#error_marks').html(success_msg.error_marks);
                    var ticks = success_msg.clo_code;
                    $('#chart1').empty();
                    var plot1 = $.jqplot('chart1', [success_msg.threshold_array, success_msg.attainment_array], {
                                seriesColors : ["#3efc70", "#4bb2c5"], // #4bb2c5   colors that will
                                // be assigned to the series.  If there are more series than colors, colors
                                // will wrap around and start at the beginning again.
                                seriesDefaults : {
                                        renderer : jQuery.jqplot.BarRenderer,
                                        rendererOptions : {
                                                barWidth : 25,
                                                fill : true,
                                                showDataLabels : true,
                                                sliceMargin : 4,
                                                lineWidth : 5,
                                                dataLabelFormatString : '%.2f%'								
                                        },
                                        pointLabels : {
                                                show : true,
                                                stackedValue: true,
                                                dataLabelFormatString : '%.2f%',
                                                //labels : ['a' , '%']
                                        }
                                },
                                series : [
                                        {
                                                label : entity_mte + ' Threshold %'
                                        }, {
                                                label : 'Threshold based <br/> Attainment %'
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
                    } else {
                        
                        $('#export_doc').prop('disabled', true);
                        $('#chart1').empty();
                        $('#chart1').html(success_msg.error_marks); $('#chart1').show();
                        $('#finalize_div').hide();
                        $('#course_clo_attaiment_div').empty();$('#error_marks').empty();
                        $('#display_finalized_attainment_div').hide();
                        $('#display_finalized_attainment_tbl').empty();
                        $('#co_finalized_tbl_div').empty();
                        $('#note_data').html("");
                    }
                }
            });
	}else{
	
			$('#export_doc').prop('disabled', true);
                        $('#chart1').empty();
                        $('#finalize_div').hide();
                        $('#course_clo_attaiment_div').empty(); $('#error_marks').empty();
                        $('#display_finalized_attainment_div').hide();
                        $('#display_finalized_attainment_tbl').empty();
                        $('#co_finalized_tbl_div').empty();
                        $('#note_data').html("");
            $('#display_finalized_attainment_div').hide();
            $('#display_finalized_po_attainment_div').hide();
            $('#note_data').hide();
	    $('#loading').hide();
	}
});

// Function to show the attainment drilldown

$(document).on('click','.tier1_attainment_drilldown',function(){
  var clo_id = $(this).attr('data-clo_id');
 // var sec_id = $('#tier1_section').val();
    var crclm = $('#tier1_crclm_id option:selected').text();
    var term = $('#tier1_term option:selected').text();
    var crs = $('#tier1_course option:selected').text();
    var section = $('#tier1_section option:selected').text();
  var post_data = {'clo_id':clo_id};
   $.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_mte_clo_attainment/fetch_drilldown_attainment_data',
			data : post_data,
			dataType : 'json',
			success : function (success_msg) { 
                                    $('#mte').empty();
                                    $('#mte').html(success_msg.mte_wieghtage+' %');
                                    $('#tee').empty();
                                    $('#tee').html(success_msg.tee_wieghtage+' %');
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
    var type_id = $('#type_data').val();
    var occasion_id = $('#occasion').val();
    
    var crclm = $('#tier1_crclm_id option:selected').text();
    var term = $('#tier1_term option:selected').text();
    var crs = $('#tier1_course option:selected').text();
    //var section = $('#tier1_section option:selected').text();
		var occasion_not_selected = $('#occasion option:not(:selected)').length; 
    
    var post_data= {
                    'clo_id':clo_id,
                    'crclm_id':crclm_id,
                    'term_id':term_id,
                    'course_id':course_id,
                    'type_id':type_id,
                    'occasion_id':occasion_id,
					'occasion_not_selected': occasion_not_selected
                   };
   $.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_mte_clo_attainment/get_co_questions',
			data : post_data,
			//dataType : 'json',
			success : function (success_msg) {			
                            if(success_msg != 0){
                                $('#clo_statement_table').empty();
                                    $('#clo_statement_table').html(success_msg);
                            }else{
                                    $('#clo_statement_table').empty();
                                    $('#clo_statement_table').html('<b>No data to display.</b>');
                            }
                                    $('#crclm_name').html(crclm);
                                    $('#term_name').html(term);
                                    $('#crs_name').html(crs);
                                    //$('#sec_name').html(section);
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
    var type_id = 2;
    var occasion_id = $('#occasion').val();
    
    var post_data= {
                    'crclm_id':crclm_id,
                    'term_id':term_id,
                    'course_id':course_id,
                    'type_id':type_id,
                    'occasion_id':occasion_id,
                   };
                   
                   $.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_mte_clo_attainment/get_co_finalize',
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









$('form[name="indirect_direct_attainment_form"]').on('click', '#export_doc', function(e){
    e.preventDefault();
   
    var graph_data = $('#chart1').clone().html();
    var imgData_1 = $('#chart1').jqplotToImageStr({});
    var imgElem_1 = $('<img/>').attr('src',imgData_1);
    $('#form_name').val('indirect_direct_attainment_form');
    var export_data = $('#course_clo_attaiment_div').html() + $('#note_data').html() + $('#display_finalized_attainment_div').html();
    $('#export_graph_data_to_doc').val(imgData_1);
    $('#export_data_to_doc').val(export_data);
    $('#indirect_direct_attainment_form').submit();
    
});

$(document).ready(function () {
	$('#occasion').multiselect({
			includeSelectAllOption : true,
			buttonWidth : '180px',
			nonSelectedText : 'Select Occasions',	
			nonSelectedAll : 'all',
			templates : {
				button : '<button type="button" class="multiselect btn btn-link dropdown-toggle" data-toggle="dropdown"></button>'
			}

		});
});