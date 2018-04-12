/*
 * Description	:	Tier II CO Level Attainment Script
 
 * Created		:	December 14th, 2015
 
 * Author		:	 Shivaraj B
 
 * Modification History:
 * Date				Modified By				Description
 
 ----------------------------------------------------------------------------------------------*/
var base_url = $('#get_base_url').val();
var cie_counter = new Array();
cie_counter.push(1);
var co_po_attainment_total = 0;

function empty_direct_attainment() {
    $('#actual_data_div').empty();
    $('#co_level_nav').empty();
    $('#chart_plot_1').empty();
    $('#co_level_attain_data').empty();
    $('#chart1').remove();
    $('#finalized_co_attain_data_div').empty();
    $('#finalized_po_data_div').empty();
	 $("#finalize_attainment").empty();
	$('#finalize_attainment').html("");
}
function empty_indirect_attainment() {
    $('#chart_plot_indirect_attain').empty();
    $('#graph_val').empty();

    $('#co_level_indirect_nav').empty();
    $('#co_level_indirect_nav_table_id').remove();
    $('#co_level_indirect_nav_body').remove();

    $('#chart6').remove();
}

function empty_direct_indirect_attainment() {
    $('#survey_occasion_actual_data_div').empty();
    $('#co_level_comparison_nav').empty();
    $('#chart_plot_7').empty();
    $('#co_level_comparison_nav_table_id').remove();
    $('#co_level_comparison_nav_body').remove();
    $('#chart7').remove();
    $('#co_level_table_id').remove();
    $('#co_level_body').remove();
    $('#student_docs').remove();
    $('#docs').remove();
    $('#actual_course_attainment').remove();

}
function empty_all_divs() {
    empty_direct_attainment();
    empty_indirect_attainment();
    empty_direct_indirect_attainment();
}

$(document).ready(function () {
//    document.getElementById('finalize_direct_indirect_div').style.visibility = 'hidden';
});

//############################################################################################
//Direct Attainment
//############################################################################################
//List Page
if ($.cookie('remember_crclm') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#crclm_id option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
    select_term();
}

function select_crclm() {
 $('#occasion').trigger('change');
    empty_all_divs();
    $.cookie('remember_pgm', $('#pgm_id option:selected').val(), {
        expires: 90,
        path: '/'
    });
    var crclm_list_path = base_url + 'tier_ii/co_section_level_attainment/select_crclm';
    var data_val = $('#pgm_id').val();
    var post_data = {
        'pgm_id': data_val
    }
    $.ajax({
        type: "POST",
        url: crclm_list_path,
        data: post_data,
        success: function (msg) {
            $('#crclm_id').html(msg);
            if ($.cookie('remember_crclm') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#crclm_id option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
                //get_selected_value();
                select_term(); $('#occasion').trigger('change');
            }
        }
    });
}


function select_term() {
 //$('#occasion').trigger('change');
    $.cookie('remember_crclm', $('#crclm_id option:selected').val(), {
        expires: 90,
        path: '/'
    });
    empty_all_divs();
    var term_list_path = base_url + 'tier_ii/co_section_level_attainment/select_term';
    var data_val = $('#crclm_id').val();
	//$('#export_doc').prop('disabled', true);
		var post_data = {
			'crclm_id': data_val
		}

		$.ajax({
			type: "POST",
			url: term_list_path,
			data: post_data,
			success: function (msg) {
				$('#term').html(msg);
				if ($.cookie('remember_term') != null) {
					// set the option to selected that corresponds to what the cookie is set to
					$('#term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);

					select_course(); 
					//$('#occasion').trigger('change');
				}

			}
		});
	
}

function select_course() {
 $('#occasion').trigger('change');
    $.cookie('remember_term', $('#term option:selected').val(), {
        expires: 90,
        path: '/'
    });
    empty_all_divs();
    var course_list_path = base_url + 'tier_ii/co_section_level_attainment/select_course';
    var data_val = $('#term').val();
    if (data_val) {
        var post_data = {
            'term_id': data_val
        }
        $.ajax({
            type: "POST",
            url: course_list_path,
            data: post_data,
            success: function (msg) {
                $('#course').html(msg);
                if ($.cookie('remember_course') != null) {
                    // set the option to selected that corresponds to what the cookie is set to
                    $('#course option[value="' + $.cookie('remember_course') + '"]').prop('selected', true);
                }
				 select_section(); $('#occasion').trigger('change');
            }
        });
			$('#before_finalized_data').show();
    } else {
	$('#before_finalized_data').hide();
        $('#course').html('<option value="">Select Course</option>');  select_section(); $('#occasion').trigger('change');
    }
}

function select_section(){
     $.cookie('remember_course', $('#course option:selected').val(), {
        expires: 90,
        path: '/'
    });
    empty_all_divs();
    var course_list_path = base_url + 'tier_ii/co_section_level_attainment/select_section';
    var data_val = $('#term').val();
    var crclm_id = $('#crclm_id').val();
    var crs_id =  $('#course').val();
    if (data_val) {
        var post_data = {
            'term_id': data_val,
            'crclm_id': crclm_id,
            'crs_id':crs_id
        }
        $.ajax({
            type: "POST",
            url: course_list_path,
            data: post_data,
            success: function (msg) {
                $('#section_id').html(msg);
                if ($.cookie('remember_section_id') != null) {
                    // set the option to selected that corresponds to what the cookie is set to
                    $('#section_id option[value="' + $.cookie('remember_section_id') + '"]').prop('selected', true);
                    

                }select_type(); $('#section_id').trigger('change');
            }
        });
    } else {
        $('#section_id').html('<option value="">Select Section</option>'); $('#section_id').trigger('change');
    }

}
function select_type() {
    
    $.cookie('remember_section_id', $('#section_id option:selected').val(), {
        expires: 90,
        path: '/'
    });
    empty_all_divs();
    $('#occasion_div').css({
        "display": "none"
    });
    var course_id = $('#course').val();
   // document.getElementById('finalize_direct_indirect_div').style.visibility = 'hidden';
    if (course_id) {
        	$('#type_data').html('<option value=0>Select Type</option><option value=2>' + entity_cie+ '</option>');
//        $('#type_data').html('<option value=0>Select Type</option><option value=2>' + entity_cie + '</option><option value=1>' + entity_see + '</option><option value="cia_tee">Both ' + entity_cie + ' & ' + entity_see + '</option>');
//        $('#type_data_survey').html('<option value=0>Select Type</option><option value=2>' + entity_cie + '</option><option value=1>' + entity_see + '</option><option value="cia_tee">Both ' + entity_cie + ' & ' + entity_see + '</option>');
    } else {
        $('#occasion_div').css({
            "display": "none"
        });
        $('#type_data').html('<option value=0>Select Type</option>');
        $('#type_data_survey').html('<option value=0>Select Type</option>');
    }
      if ($.cookie('remember_section') != null) {
	// set the option to selected that corresponds to what the cookie is set to
	$('#section_id option[value="' + $.cookie('remember_type') + '"]').prop('selected', true);
	$('#section_id').trigger('change');
    }
    select_survey();
}

$('.navbar1').hide();
$('#section_id').on('change', function () {
    var type_data_id = $('#section_id').val();
    var type_data= 2;
    empty_direct_attainment();
    if (type_data == 2 && type_data_id) {
        empty_direct_attainment();
        var crclm_id = $('#crclm_id').val();
        var term_id = $('#term').val();
        var crs_id = $('#course').val();  
		var section_id = type_data_id;
        var post_data1 = {
            'crclm_id': crclm_id,
            'term_id': term_id,
            'crs_id': crs_id,
			'section_id' : type_data_id
        }
        $.ajax({
            type: "POST",
            url: base_url + 'tier_ii/co_section_level_attainment/select_occasion',
            data: post_data1,
            success: function (occasionList) {
                if (occasionList != 0) {
                    $('#occasion_div').css({
                        "display": "inline"
                    });
                    $('#occasion').html(occasionList);
					$('#occasion').multiselect('rebuild');
                } else {
                    $('#occasion_div').css({
                        "display": "none"
                    });
                    $('#actual_data_div').html('<font color="red">Continuous Internal Assessment ('+ entity_cie +') Occasions are not defined</font>');
                 $('#occasion').trigger('change');
				}
				$.ajax({
					type: "POST",
					url: base_url + 'tier_ii/co_section_level_attainment/fetch_section_data',
					data: post_data1,
					dataType: "json",
					success: function (success_msg) {
							    if(success_msg.table_finalize_flag == 'Finalized'){										
									$('#Attainment_List_nav_co').html('<br/><div class="row-fluid"><div class="span12"><div class="navbar-inner-custom">'+ entity_clo_full +'('+ entity_clo +') Attainment </div></div></div>');		
									
									
									$('#Attainment_List_nav_targets').html('<br/><div class="row-fluid"><div class="span12"><div class="navbar-inner-custom"> Direct Attainment / Target Levels </div></div></div>');
									$('#section_finalize_status_tbl').empty();
									$('#section_finalize_status_tbl').show();
									$('#section_finalize_status_tbl').html(success_msg.section_wise_table);
									$('#cla_data_table').empty();
									$('#cla_data_table').show();
									$('#cla_data_table').html(success_msg.cla_data_table);									

								}else{
										$('#Attainment_List_nav_co').html('');
										$('#Attainment_List_nav_targets').html('');
										$('#display_finalized_attainment_div').hide();
										$('#display_finalized_attainment_tbl').empty();									
										$('#section_finalize_status_tbl').empty();
										$('#section_finalize_status_tbl').hide();
										$('#cla_data_table').empty();
										$('#cla_data_table').hide();	
								}
					}					
					});
				
            }
        });
    }else{
         $('#occasion').html("<option>Select Occasion</option> ");
		 $('#Attainment_List_nav_co').html('');
										$('#Attainment_List_nav_targets').html('');
										$('#display_finalized_attainment_div').hide();
										$('#display_finalized_attainment_tbl').empty();									
										$('#section_finalize_status_tbl').empty();
										$('#section_finalize_status_tbl').hide();
										$('#cla_data_table').empty();
										$('#cla_data_table').hide();	
		 $('#occasion').trigger('change');
    }
});
function show_finalized_co_grid() {
    var crclm_id = $('#crclm_id').val();
    var term_id = $('#term').val();
    var crs_id = $('#course').val();
    var post_data = {
        'crclm_id': crclm_id,
        'term_id': term_id,
        'crs_id': crs_id,
    }
   
    $.ajax({
        type: "POST",
        url: base_url + 'tier_ii/co_section_level_attainment/get_finalized_co_attainment_data',
        data: post_data,
        dataType: 'HTML',
        success: function (co_po_data) {
			
            var co_po_data = $.parseJSON(co_po_data);
			var po_data = co_po_data['po_result'];
			var clo_data = co_po_data['co_result'];
			var attainment_overall_co = len_co = 0;
			$('#export_data_div').html('<a id="exportToPDF" href="#" class="btn btn-success pull-right"><i class="icon-book icon-white"></i> Export </a> <div class="clearfix"></div>');
            $('#finalized_co_attain_data_div').html('<br /><div class="navbar"><div class="navbar-inner-custom">Overall '+course_outcome+' Attainment finalized </div></div>');
            if (clo_data.length > 0) {
                var table = '';
                table += '<table class="table table-bordered">';
                table += '<thead><tr><!--<th>Sl No.</th>--><th style="white-space:nowrap;"><center>'+entity_co_single+' code</center></th><th><center>'+course_outcome+'</center></th><th style="white-space:nowrap;"><center>Attainment %</center></th><th style="white-space:nowrap;"><center>Attainment Level</center></th></tr></thead>';
                var i = 0;
                table += '<tbody>';
                $.each(clo_data, function () {
                    table += '<tr>';
                    // table +='<td><center>'+(i+1)+'</center></td>';
                    table += '<td><center>' + clo_data[i].clo_code + '</center></td>';
                    table += '<td>' + clo_data[i].clo_statement + '</td>';
                    table += '<td style="text-align:right">' + clo_data[i].overall_attainment + ' %</td>';
                    table += '<td style="text-align:right">' + clo_data[i].attainment_level + '</td>';
                    table += '</tr>';
					attainment_overall_co += parseFloat(clo_data[i].overall_attainment);
					len_co = parseInt(clo_data[i].overall_attainment.length);
                    i++;
                });
                table += '</tbody>';
                table += '</table> ';
                table +='<div><b>Course Attainment: </b> '+ (attainment_overall_co / len_co).toFixed(2) +' % </div><br />';
                $('#finalized_co_attain_data_div').append(table);
				
				//	PO Attainment table view js scripts
				$('#finalized_po_data_div').html("<div class='navbar'><div class='navbar-inner-custom'>"+ student_outcomes_full + "("+sos+") Attainment by the Course  </div></div>");
				var po_table = '';
					po_table += '<table class="table table-bordered">';
					po_table += '<thead><tr><th style="white-space:nowrap;">Sl No.</th><th style="white-space:nowrap;"><center>'+ so + ' Reference</center></th><th style="white-space:nowrap;"><center>Mapped '+entity_co_single+' </center></th><th><center>'+ student_outcome + ' ('+ so + ') Statemnet</center></th><th style="white-space:nowrap;"><center>Attainment %</center></th><th style="white-space:nowrap;"><center>Attainment Level</center></th></tr></thead>';
					var i = 0;
					po_table += '<tbody>';
					$.each(po_data, function () {
						po_table += '<tr>';
						po_table +='<td><center>'+(i+1)+'</center></td>';
						po_table += '<td><center>' + po_data[i].po_reference + '</center></td>';
						po_table += '<td>' + po_data[i].co_mapping + '</td>';
						po_table += '<td>' + po_data[i].po_statement + '</td>';
						po_table += '<td style="text-align:right">' + po_data[i].overall_attainment + ' % </td>';
						po_table += '<td style="text-align:right">' + po_data[i].attainment_level + '</td>';
						po_table += '</tr>';
						i++;
					});
					po_table += '</tbody>';
					po_table += '</table>';
					$('#finalized_po_data_div').append(po_table);
            } else {
                $('#finalized_co_attain_data_div').append("<center><span class='err_msg'>"+course_outcome+" not finalized for this course.</span></center>");
            }

        },
    });

}
$('#occasion').on('change', function () {
    $('#actual_data_div').empty();
    var course = $('#course').val();
    var qpd_id = $('#occasion').val();
    var type = 'cia';
	var section_id = $('#section_id').val();
	var occasion_not_selected = $('#occasion option:not(:selected)').length;  
   tier_ii_plot_graphs(course, qpd_id, type , section_id , occasion_not_selected);
});

function tier_ii_plot_graphs(course, qpd_id, type, section_id , occasion_not_selected) {

   // $('#loading').show();
    var post_data = {
        'course': course,
        'qpd_id': qpd_id,
        'type': type,
        'section_id': section_id,
		'occasion_not_selected':occasion_not_selected
    } 

	if($('#crclm_name').val() =='' || $('#term').val() == '' || $('#course').val() == '' || $('#section_id').val() == '' || $('#occasion').val() == null ){	
		$('#export_doc').prop('disabled', true);
		     $("#finalize_attainment").empty();
					$('#finalize_attainment').html("");
	}else{
		$('#export_doc').prop('disabled', false);
		     $("#finalize_attainment").empty();
					$('#finalize_attainment').html("");
	}
	
	//if(qpd_id && (qpd_id != 'Select Occasion' || qpd_id != ' ')){	
	
	if(qpd_id != null){

    $('#chart_plot_1').html('<div class=span12><div class="navbar"><div class="navbar-inner-custom">'+ entity_clo_full_singular +'('+ entity_clo +') Attainment</div></div><div class=span12><div id=chart1 style=position:relative; class=jqplot-target></div></div></div></div></br>');
	}else{
	  
	    $('#chart_plot_1').html('<div class=span12><div class="navbar"><div class=span12><div id=chart1 style=position:relative; class=jqplot-target></div></div></div></div><br/>');
		$('#co_level_attain_data').hide();
	}
		$('#co_level_nav').empty('');
	if(qpd_id && course &&  section_id){
    $.ajax({
        type: "POST",
        url: base_url + 'tier_ii/co_section_level_attainment/get_tire_ii_clo_attainment',
        data: post_data,
        dataType: 'HTML',
        success: function (json_clo_data) {
            var data = jQuery.parseJSON(json_clo_data); 
			if(data['col_data'][0]['status_data'] == 'Rolled0ut')
			{
            var crs_lvl_attain_data = get_course_attainment_levels(course); //get assessment levels
            var table_data = '<table class="table table-bordered table-stripped" style="width:100%">';
            table_data += '<tr><th width =50 ><center>Sl No.</center></th><th width = 150><center>'+ entity_clo_full +' ('+ entity_clo +')</center></th><th width =150> <center> Threshold based <br/> Attainment </center></th><th width =150><center>Attainment <br/>Level</center></th><th width =150><center>Average based <br/> Attainment %</center></th></tr>';
            table_data += '<tbody>';
            var i = attainment =  attainment_wt = len = Count1 = Count2 =0 ;
            var datax = new Array();
            var datay = new Array();
            var co_code = new Array();
            var co_perc = new Array();
            var co_statements = new Array();
            $.each(data['col_data'], function () {
                co_code[i] = data['col_data'][i].clo_code;
                co_perc[i] = data['col_data'][i].threshold_direct_attainment;
                co_statements.push(data['col_data'][i].clo_code + ": " + data['col_data'][i].clo_statement);
                datax.push(co_code[i]);
                datay.push(co_perc[i]);

                table_data += '<tr>';
                table_data += '<td  width =50 style="text-align:right;">' + (i + 1) + '</td>';
                table_data += '<td width =150><center>' + data['col_data'][i].clo_code + '<br><a co_id="' + data['col_data'][i].clo_id + '" class="cursor_pointer view_questions">View details</a></center></td>';
                //type == "cia" ||
				if (qpd_id == "all_occasion") {
                    table_data += '<td width =150 ><center>' + data['col_data'][i].threshold_direct_attainment_display + '<br><a co_id="' + data['col_data'][i].clo_id + '" crs_id="' + course + '" class="cursor_pointer view_drilldown">drill down</a></center></td>';
					table_data += '<td width =150 style="text-align:right;">' + data['col_data'][i].attainment_level + '</td>';
				//len = parseInt((data[i].individual_da_percentage).length+1);<br/>
				 //len = $('table td ').length - 2;
				 Count1 ++; len = Count1;
                } else {
                    table_data += '<td width =150 style="text-align:right;">' + data['col_data'][i].threshold_direct_attainment_display + '</td>';
					table_data += '<td width =150 style="text-align:right;">' + data['col_data'][i].attainment_level + '</td>';
				//	len = parseInt((data[i].individual_da_percentage).length+1);
				//len = $('table td ').length - 2;
				Count2++; len = Count2;
                }
				if (qpd_id == "all_occasion") {
                    table_data += '<td width =150 ><center>' + data['col_data'][i].average_direct_attainment_display + '<br><a co_id="' + data['col_data'][i].clo_id + '" crs_id="' + course + '" class="cursor_pointer view_drilldown_average">drill down</a></center></td>';
				//len = parseInt((data[i].individual_da_percentage).length+1);<br/>
				 //len = $('table td ').length - 2;
				 Count1 ++; len = Count1;
                } else {
				
                    table_data += '<td width =150 style="text-align:right;">' + data['col_data'][i].average_direct_attainment_display + '</td>';
				//	len = parseInt((data[i].individual_da_percentage).length+1);
				//len = $('table td ').length - 2;
				Count2++; len = Count2;
                }
                
                table_data += '</tr>';
				attainment += parseFloat(data['col_data'][i].threshold_direct_attainment);
				attainment_wt += parseFloat(data['col_data'][i].threshold_clo_da_attainment_wgt);
				
                i++;
            });
            
			attainment = attainment / (data['col_data'].length); 
                        if(isNaN(attainment)){ attainment = "" ; }else {
                            attainment = (attainment).toFixed(2);
                        }	
						
						
		attainment_wt = attainment_wt / (data['col_data'].length); 
                        if(isNaN(attainment_wt)){ attainment_wt = "" ; }else {
                            attainment_wt = (attainment_wt).toFixed(2);
                        }	
		//	table_data += '<tr><td>'+attainment+'</td></tr>';
            table_data += '</tbody>';
            table_data += '</table>';
			table_data += '<div><b>Actual Course Attainment :</b>'+ attainment +'\t     <b>Course Attainment After Weightage: </b> '+ attainment_wt +' % </div>';
			$('#table_data').val(table_data);
			$('#finalize_attainment').html('<div> '+ attainment +'</div>');
            if (crs_lvl_attain_data == "") {
                crs_lvl_attain_data = "<h4 style='color:red;'><center>Attainment levels are empty.</center></h4>";
            } 
		
            if (data['col_data'].length == 0) {			
				$('#co_level_attain_data').hide();
					//	$('#display_finalized_attainment_div').hide();
				//		$('#display_finalized_attainment_tbl').empty();
            } else {
			if (data['col_data'].length != 0){
                plot_graph(datax, datay, co_statements);
				$('#co_level_attain_data').show();
                $('#co_level_attain_data').html('<br/><div class="row-fluid"><div class="span6"><div class="navbar-inner-custom">Direct Attainment / Target Levels</div>' + crs_lvl_attain_data + '</div><div class="span6"><div class="navbar-inner-custom">'+ entity_clo_full +'('+ entity_clo +') Attainment</div>' + table_data + '</div></div>');
                 $('#co_level_attain_data').append('<br/><div class="span12"><table border="1" class="table table-bordered" style="width:100%"><tbody><tr><td width : 650 colspan="2"><b>Note:</b> The above bar graph depicts the overall class performance with respect to the Threshold % for individual '+ entity_clo_full +' ('+ entity_clo +'). The Threshold based Attainment % & Average based Attainment % is calculated using the below formula.</td></tr><tr><td width = 325 ><b>For Threshold based Attainment % = ( x / y ) * 100 </b><br/> x = Count of Students &gt;= to Threshold % <br/> y = Total number of Students Attempted .</td><td width = 325 ><b>For Average based Attainment % = ( x / y ) *100 </b> <br/> x = Average Secured marks of Attempted Students <br/> y = Maximum Marks . </td></tr></tbody></table></div>'); 
				}else{				
					$('#co_level_attain_data').hide();
					$("#finalize_attainment").empty();
				}
                   if(data['col_data'].table_finalize_flag == 'Finalized'){										
						$('#display_finalized_attainment_div').show();
						$('#display_finalized_attainment_tbl').empty();
						$('#display_finalized_attainment_tbl').html(data['col_data'].table_finalize);

					}else{
						$('#display_finalized_attainment_div').hide();
						$('#display_finalized_attainment_tbl').empty();
					}
                if (occasion_not_selected == 0 && $('#course').val != '') {	
					if( data['occasions_status'].length == 0 ) {
						//if(data['occasions_status'][0]['status_data'] == ''){
						$('#finalize_attainment').html('<hr><div class="row-fluid"><div class="pull-right"><a href="#myModalfinalize" id="finalize_clo" class="btn btn-medium btn-success" data-toggle="modal" data-original-title="Finalize" rel="tooltip" title="finalize"><i class="icon-ok icon-white"></i> Finalize Attainment</a></div></div>');
						//}
					}else{
					 $("#finalize_attainment").empty();
					$('#finalize_attainment').html("");
					var error_msg  = '';	var j = 0;
						 $.each(data['occasions_status'], function () {
							error_msg += data['occasions_status'][j]['status_data'];
							j++;
						 });
						 $('#co_level_nav').empty('');
						 $('#co_level_nav').append('<div class="span12 bs-docs-example"><b><span class="" style = "color:red"> You cannot Finalize the Course - ' + entity_cie + '-' + course_outcome +' Attainment for this Section/Division . Kindly complete the below activities : <br/>  '+ error_msg +'</span></b><center><p><a href="'+base_url+'assessment_attainment/import_cia_data/"class="cursor_pointer" target="_blank" >Click here to upload course '+ entity_cie +' data </a></p><p>OR</p> <a href="'+base_url+'question_paper/manage_cia_qp/"class="cursor_pointer" target="_blank">Click here to Create QP  course '+ entity_cie +' data</a></p></center></div><br/><br/><br/><br/>')
					}
                } else {
							var error_msg  = '';	var j = 0;
						 $.each(data['occasions_status'], function () {
							error_msg += data['occasions_status'][j]['status_data'];
							j++;
						 });
						 $('#co_level_nav').empty('');
						if(error_msg != ""){  $('#co_level_nav').append('<div class="span12 bs-docs-example"><b><span class="" style = "color:red"> You cannot Finalize the Course - ' + entity_cie + '-' + course_outcome +' Attainment for this Section/Division . Kindly complete the below activities : <br/>  '+ error_msg +'</span></b><center><p><a href="'+base_url+'assessment_attainment/import_cia_data/"class="cursor_pointer" target="_blank" >Click here to upload course '+ entity_cie +' data </a></p><p>OR</p> <a href="'+base_url+'question_paper/manage_cia_qp/"class="cursor_pointer" target="_blank">Click here to Create QP course '+ entity_cie +' data</a></p></center></div><br/><br/><br/><br/>');}
                    $("#finalize_attainment").empty();
					$('#finalize_attainment').html("");
                }
            }
			}else{
				$('#co_level_attain_data').hide();
				if(qpd_id == "all_occasion"){
				$('#chart_plot_1').append('<div class="span12 bs-docs-example"><b><span class="" style = "color:red"> You cannot Finalize the Course - ' + entity_cie + '-' + course_outcome +' Attainment for this Section/Division . Kindly complete the below activities : <br/>  '+ data['col_data'][0]['status_data'] +'</span></b><center><p><a href="'+base_url+'assessment_attainment/import_cia_data/"class="cursor_pointer" target="_blank">Click here to upload course '+ entity_cie +' data</a></p> <p>OR</p> <a href="'+base_url+'question_paper/manage_cia_qp/"class="cursor_pointer" target="_blank">Click here to Create QP  course '+ entity_cie +' data</a> </p></center></div>');
				}else{$('#chart_plot_1').append('<br/><div class="" ><center><p><b><span class = "" style="color:red">' + data['col_data'][0]['status_data'] + '</span></b></p><p><a href="'+base_url+'assessment_attainment/import_cia_data/"class="cursor_pointer" target="_blank" >Click here to upload course '+ entity_cie +' data </a></p><p>OR</p> <a href="'+base_url+'question_paper/manage_cia_qp/"class="cursor_pointer" target="_blank">Click here to Create QP  course '+ entity_cie +' data</a></p></center></div>');}
				$('#export_doc').prop('disabled', true);
				$("#finalize_attainment").empty();
			}
            $('#loading').hide();
        } // end of success
    }); //end of ajax
	}else{
	
	 $('#loading').hide();
	}
}
function plot_graph(datax, datay, co_statements) {
    var ticks = datax;
    var plot1 = $.jqplot('chart1', [datay], {
        seriesColors: ["#4BB2C5"], //"#3efc70",
        seriesDefaults: {
            renderer: $.jqplot.BarRenderer,
//            rendererOptions: {
//                barWidth: 15,
//                fillToZero: true
//            },
 rendererOptions: {
                           // fill: true,
                            showDataLabels: true,
                            sliceMargin: 1,
                            lineWidth: 1,
                            barWidth: 15,   
                            dataLabelFormatString: '%.2f%'
                    },
            pointLabels: {
                show: true
            }
        },
        series: [{
                label: 'Threshold Direct <br/>Attainment %'
            },
        ],
        highlighter: {
            show: true,
            tooltipLocation: 'e', // location of tooltip: n, ne, e, se, s, sw, w, nw.
            tooltipAxes: 'x', // which axis values to display in the tooltip, x, y or both.
            showMarker: false,
            tooltipContentEditor: function (str, seriesIndex, pointIndex, plot) {
                return co_statements[pointIndex];
            }
        },
        legend: {
            show: true,
            //placement: 'outsideGrid',
            	location: 'ne',
                placement: 'outside'
            
        },
        axes: {
            xaxis: {
                renderer: $.jqplot.CategoryAxisRenderer,
                tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
                ticks: ticks
            },
            yaxis: {
                padMin: 0,
                min:0,
                max:100,
				tickInterval:'10',
                tickOptions: {
                        formatString: '%d%%'
                }
            }
        }

    }); //end of jplot
}

$('.cancel_button').on('click', function(){
	window.location.href = base_url + 'tier_ii/course_clo_attainment';
});
function get_course_attainment_levels(course_id) {
    var post_data = {
        'crs_id': course_id,
    }
    var table = "";
    $.ajax({
        type: "POST",
        url: base_url + 'tier_ii/co_section_level_attainment/display_course_level_attainemnt',
        data: post_data,
        async: false,
        dataType: 'HTML',
        success: function (json_cla_data) {
            var cla_data = jQuery.parseJSON(json_cla_data);
            if (cla_data.length != 0) {
                table = '<table class="table table-bordered table-stripped" style="width:100%">';
                table += '<tr><th width = 50 style="text-align: -webkit-right;">Sl No.</th><th width = 150 ><center> Attainment<br/> Level Name</center></th><th width = 150 style="text-align: -webkit-right;">Attainment <br/>Level Value</th><th width = 300 ><center> Target</center></th></tr>';
                table += '<tbody>';
                var j = 0;
                $.each(cla_data, function () {
                    table += '<tr>';
                    table += '<td width = 50  style="text-align: -webkit-right;">' + (j + 1) + '</td>';
                    table += '<td width = 150 >' + cla_data[j].assess_level_name_alias + '</td>';
                    table += '<td width = 150 style="text-align: -webkit-right;">' + cla_data[j].assess_level_value + '</td>';
                    table += '<td width = 300 >' + cla_data[j].cia_direct_percentage + '% students scoring ' + cla_data[j].conditional_opr + ' ' + cla_data[j].cia_target_percentage + '% marks out of relevant<br/> maximum marks.</td>';
                    table += '</tr>';
                    j++;
                });
                table += '</tbody>';
                table += '</table>';
            }
			//end of if
        }
    });
    return table;
}

$(document).on('click', '.view_questions', function () {
    var co_id = $(this).attr('co_id');
    var type_data = 2;
    var qpd_id = $('#occasion').val();
	var section_id = $('#section_id').val();
	var occasion_not_selected = $('#occasion option:not(:selected)').length;  
    var post_data = {
        'clo_id': co_id,
        'type_data': type_data,
        'qpd_id': qpd_id,
		'section_id':section_id,
		'occasion_not_selected' : occasion_not_selected
    }
    $('#co_questions').empty(); 
    $.ajax({
        type: "POST",
        url: base_url + 'tier_ii/co_section_level_attainment/get_co_questions',
        data: post_data,
        dataType: 'HTML',
        success: function (json_clo_data) {		
            if (json_clo_data.trim() == '0') {
                $('#co_questions').html("<h4 style='color:red;'><center>No Assessment data found.</center></h4>");
            } else {
                $('#co_questions').html(json_clo_data);
            }
        }
    });
    $('#view_ques_modal').modal('show');
});

$(document).on('click', '.view_drilldown', function () {
    var co_id = $(this).attr('co_id');
    var crs_id = $(this).attr('crs_id');
    var type_data = $('#type_data').val();
    var qpd_id = $('#occasion').val();
	var crclm_name = $('#crclm_id option:selected').text();
    var term_name = $('#term option:selected').text();
    var crs_name = $('#course option:selected').text();
	var section_id = $('#section_id').val();
    var post_data = {
        'clo_id': co_id,
        'type_data': type_data,
        'qpd_id': qpd_id,
        'crs_id': crs_id,
		'section_id' : section_id,
    }
    $('#dridown_perc').empty();
    $.ajax({
        type: "POST",
        url: base_url + 'tier_ii/co_section_level_attainment/get_drilldown_for_co',
        data: post_data,
        dataType: 'HTML',
        success: function (json_clo_data) {
            var arr_data = jQuery.parseJSON(json_clo_data);
            //console.log(arr_data.wgt_arr);cia_wgt
			
            if (arr_data.query_data.length > 0) {
						
				$('#clo_crclm_name').empty();
				$('#clo_crclm_name').html(crclm_name);
				$('#clo_term_name').empty();
				$('#clo_term_name').html(term_name);
				$('#clo_crs_name').empty();
				$('#clo_crs_name').html(crs_name);
				
                var data = arr_data.query_data;
                var table = '';
                table += '<div class="row-fluid"><div class="span6"><b>' + entity_cie + ' Weight: </b>' + arr_data.wgt_arr.cia_wgt + '% </div></div><hr/>';
                table +=  entity_co_single +' Statement : <br/><h4>' + data[0].clo_code + ': ' + data[0].clo_statement + '</h4>';
                table += '<table class="table table-bordered table-stripped">';
                table += '<thead><tr><th style="width:8%;"><center>Sl No.</center></th><th style="width:12%;"><center> '+ entity_co_single +' Code</center></th><th style="width:20%;"><center>Occassion</center></th><th style="width:25%;"><center>Actual Attainment %</center></th><th><center>Actual Attainment Level</center></th></tr></thead>';
                table += '<tbody>';
                var i = 0;
                var per_avg = 0.00,
                        attain_avg = 0.00;
                $.each(data, function () {
                    var ao_desc = data[i].ao_description;
                    if (ao_desc == 0) {
                        ao_desc = "TEE";
                    } else {
                        ao_desc += ',..';
                    }
                    table += '<tr><td><center>' + (i + 1) + '</center></td>';
                    table += '<td style="text-align:center;">' + data[i].clo_code + '</td>';
                    table += '<td style="text-align:center;">' + ao_desc + '</td>';
                    table += '<td style="text-align:right;">' + data[i].ip + '% </td>';
                   // table += '<td style="text-align:right;">' + data[i].individual_da_percentage + '% </td>';
                    table += '<td style="text-align:right;">' + data[i].al + '</td>';
                  //  table += '<td style="text-align:right;">' + data[i].attainment_level + '</td>';
                    table += '</tr>';
                    per_avg = per_avg + parseFloat(data[i].ip);
                    attain_avg = attain_avg + parseFloat(data[i].al);
                    i++;
                });

                table += '<tr><td colspan="3"></td><td style="text-align:right;"><b>Total Attainment %: </b>' + (parseFloat(per_avg / arr_data.query_data.length)).toFixed(2) + '% </td><td style="text-align:right;"><b>Total Attainment Level: </b>' + ((attain_avg / arr_data.query_data.length)).toFixed(2) + '</td></tr>';
                table += '</tbody>';
                table += '</table>';
                $('#dridown_perc').html(table);
            } else {
                $('#dridown_perc').html("<h4 style='color:red;'>No data found</h4>");
            }
            $('#view_drilldown_modal').modal('show');
        }
    });
});

$(document).on('click', '.view_drilldown_average', function () {
    var co_id = $(this).attr('co_id');
    var crs_id = $(this).attr('crs_id');
    var type_data = $('#type_data').val();
    var qpd_id = $('#occasion').val();
	 var section_id  = $('#section_id').val();
	
	 var crclm_name = $('#crclm_id option:selected').text();
	var term_name = $('#term option:selected').text();
	var crs_name = $('#course option:selected').text();
    var post_data = {
        'clo_id': co_id,
        'type_data': type_data,
        'qpd_id': qpd_id,
        'crs_id': crs_id,
		'section_id':section_id
    }
	        $('#clo_crclm_name').empty();
                            $('#clo_crclm_name').html(crclm_name);
                            $('#clo_term_name').empty();
                            $('#clo_term_name').html(term_name);
                            $('#clo_crs_name').empty();
                            $('#clo_crs_name').html(crs_name);
    $('#dridown_perc').empty();
    $.ajax({
        type: "POST",
        url: base_url + 'tier_ii/co_section_level_attainment/get_drilldown_for_co_average',
        data: post_data,
        dataType: 'HTML',
        success: function (json_clo_data) {
            var arr_data = jQuery.parseJSON(json_clo_data);
            if (arr_data.query_data.length > 0) {
			
                var data = arr_data.query_data;
                var table = '<h4>' + data[0].clo_code + ': ' + data[0].clo_statement + '</h4><hr/>';
                table += '<div class="row-fluid"><div class="span6"><center><b>' + entity_cie + ' sd Weight: </b>' + arr_data.wgt_arr.cia_wgt + '% </center></div></div>';
                table += '<table class="table table-bordered table-stripped">';
                table += '<thead><tr><th><center>Sl No.</center></th><th><center>'+ entity_co_single +' Code</center></th><th><center>Occassion</center></th><th><center>Actual Attainment %</center></th></tr></thead>';
                table += '<tbody>';
                var i = 0;
                var per_avg = 0.00,
                        attain_avg = 0.00;
                $.each(data, function () {
                    var ao_desc = data[i].ao_description;
                    if (ao_desc == 0) {
                        ao_desc = "TEE";
                    } else {
                        ao_desc += ',..';
                    }
                    table += '<tr><td><center>' + (i + 1) + '</center></td>';
                    table += '<td style="text-align:center;">' + data[i].clo_code + '</td>';
                    table += '<td style="text-align:center;">' + ao_desc + '</td>';
                    table += '<td style="text-align:right;">' + data[i].ip + '% </td>';
                  //  table += '<td style="text-align:right;">' + data[i].individual_da_percentage + '% </td>';
                  //  table += '<td style="text-align:right;">' + data[i].al + '</td>';
               //     table += '<td style="text-align:right;">' + data[i].attainment_level + '</td>';
                    table += '</tr>';
                    per_avg = per_avg + parseFloat(data[i].ip);
                    attain_avg = attain_avg + parseFloat(data[i].al); 
					
					per_avg1 = per_avg / arr_data.query_data.length;
					attain_avg1 = attain_avg / arr_data.query_data.length;
                    i++;
                });

                table += '<tr><td colspan="3"></td><td style="text-align:right;"><b>Total Attainment: </b>' + (parseFloat(per_avg1)).toFixed(2) + '% </td></tr>';
                table += '</tbody>';
                table += '</table>';
                $('#dridown_perc').html(table);
            } else {
                $('#dridown_perc').html("<h4 style='color:red;'>No data found</h4>");
            }
            $('#view_drilldown_modal').modal('show');
        }
    });
});

$('#finalize_clo_attainment').on('click', function () {
    var post_data = {
        'crs_id': $('#course').val(),
        'term_id': $('#term').val(),
        'crclm_id': $('#crclm_id').val(),
        'section_id' : $('#section_id').val(),
        'occasion': $('#occasion').val(),
        
    }
    $.ajax({
        type: "POST",
        url: base_url + 'tier_ii/co_section_level_attainment/finalize_clo_attainment',
        data: post_data,
        dataType: 'HTML',
        success: function (msg) {
            $('#myModalfinalize').modal('hide');
            $('#finalize_direct_indirect_success').modal('show');
            $('#course').trigger('change');
        }
    });
});

$('#add_form').on('click', '#exportToPDF', function () {
    var crclm_name = $('#crclm_id :selected').text();
    var term_name = $('#term :selected').text();
    var course_name = $('#course :selected').text();

    var course_attainment_graph = $('#chart1').jqplotToImageStr({});
    var course_attainment_graph_image = $('<img/>').attr('src', course_attainment_graph);
    $('#course_outcome_attainment_graph_data').html('<table><tr><td><b>Curriculum :</b></td><td>' + crclm_name + '</td></tr><tr><td><b>Term :</b></td><td>' + term_name + '</td></tr><td><b>Course :</b></td><td>' + course_name + '</td></tr></table><br><b>Tier II '+ entity_clo_full +'('+ entity_clo  +') Attainment</b><br /><br />');
	$('#course_outcome_attainment_graph_data').append($('#finalized_co_attain_data_div').clone().html());
	$('#course_outcome_attainment_graph_data').append("<pagebreak>");
	$('#course_outcome_attainment_graph_data').append($('#finalized_po_data_div').clone().html());
    $('#course_outcome_attainment_graph_data').append(course_attainment_graph_image);
    $('#course_outcome_attainment_graph_data').append($('#co_level_attain_data').clone().html());
    $('#course_outcome_attainment_graph_data').append($('#docs').clone().html());
    var course_outcome_attainment_pdf = $('#course_outcome_attainment_graph_data').clone().html();
    $('#course_outcome_attainment_graph_data_hidden').val(course_outcome_attainment_pdf);
    $('#add_form').submit();
});
//############################################################################################
//Indirect Attainment
//############################################################################################


if ($.cookie('remember_dept_indirect') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#dept_id_indirect option[value="' + $.cookie('remember_dept_indirect') + '"]').prop('selected', true);
    select_pgm_indirect();
}
function select_pgm_indirect() {
    $.cookie('remember_dept_indirect', $('#dept_id_indirect option:selected').val(), {
        expires: 90,
        path: '/'
    });
    empty_all_divs();
    var pgm_list_path = base_url + 'tier_ii/co_section_level_attainment/select_pgm';

    var data_val = $('#dept_id_indirect').val();
    var post_data = {
        'dept_id': data_val
    }
    $.ajax({
        type: "POST",
        url: pgm_list_path,
        data: post_data,
        success: function (msg) {
            $('#pgm_id_indirect').html(msg);
            if ($.cookie('remember_pgm_indirect') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#pgm_id_indirect option[value="' + $.cookie('remember_pgm_indirect') + '"]').prop('selected', true);
                select_crclm_indirect();
            }
        }
    });
}

function select_crclm_indirect() {
    empty_all_divs();
    $.cookie('remember_pgm_indirect', $('#pgm_id_indirect option:selected').val(), {
        expires: 90,
        path: '/'
    });
    var crclm_list_path = base_url + 'tier_ii/co_section_level_attainment/select_crclm';
    var data_val = $('#pgm_id_indirect').val();
    var post_data = {
        'pgm_id': data_val
    }
    $.ajax({
        type: "POST",
        url: crclm_list_path,
        data: post_data,
        success: function (msg) {
            $('#crclm_id_indirect').html(msg);
            if ($.cookie('remember_crclm_indirect') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#crclm_id_indirect option[value="' + $.cookie('remember_crclm_indirect') + '"]').prop('selected', true);
                //get_selected_value();
                select_term_indirect();
            }
        }
    });
}

function select_term_indirect() {
    $.cookie('remember_crclm_indirect', $('#crclm_id_indirect option:selected').val(), {
        expires: 90,
        path: '/'
    });
    empty_all_divs();
    var term_list_path = base_url + 'tier_ii/co_section_level_attainment/select_term';
    var data_val = $('#crclm_id_indirect').val();
    var post_data = {
        'crclm_id': data_val
    }

    $.ajax({
        type: "POST",
        url: term_list_path,
        data: post_data,
        success: function (msg) {
            $('#term_indirect').html(msg);
            if ($.cookie('remember_term_indirect') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#term_indirect option[value="' + $.cookie('remember_term_indirect') + '"]').prop('selected', true);

                select_course_indirect();
            }

        }
    });
}

function select_course_indirect() {
    $.cookie('remember_term_indirect', $('#term_indirect option:selected').val(), {
        expires: 90,
        path: '/'
    });
    empty_all_divs();
    var course_list_path = base_url + 'tier_ii/co_section_level_attainment/select_course';
    var data_val = $('#term_indirect').val();
    if (data_val) {
        var post_data = {
            'term_id': data_val
        }
        $.ajax({
            type: "POST",
            url: course_list_path,
            data: post_data,
            success: function (msg) {
                $('#course_indirect').html(msg);
                if ($.cookie('remember_course_indirect') != null) {
                    // set the option to selected that corresponds to what the cookie is set to
                    $('#course_indirect option[value="' + $.cookie('remember_course_indirect') + '"]').prop('selected', true);
                    select_survey();
                }
            }
        });
    } else {
        $('#course_indirect').html('<option value="">Select Course</option>');
    }
}

function select_survey() {
    $.cookie('remember_course_indirect', $('#course_indirect option:selected').val(), {
        expires: 90,
        path: '/'
    });
    empty_all_divs();
    var course_list_path = base_url + 'tier_ii/co_section_level_attainment/select_survey';
    var data_val = $('#course').val();
    if (data_val) {
        var post_data = {
            'course': data_val
        }
        $.ajax({
            type: "POST",
            url: course_list_path,
            data: post_data,
            success: function (msg) {
                $('#survey_id').html(msg);
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

$('#indirect_attainment_form').on('change', '#survey_id', function () {
    var survey_id = $('#survey_id').val();
    plot_graphs_indirect_attainment(survey_id);
});

function plot_graphs_indirect_attainment(survey_id) {
    var post_data = {
        'report_type_val': 3, //default:3
        'su_for': 8,
        'survey_id': survey_id,
    }
    var actual_data = [];
    var co_level_indirect_nav = [];
    var dept_name = [];
    var su_for_label = 'CO';
    var i = 1;
    $.ajax({
        type: "POST",
        url: base_url + 'survey/surveys/getSurveyQuestions',
        data: post_data,
        dataType: 'html',
        success: function (survey_data) {
            $("#co_level_indirect_nav").html('');
            $("#graph_val").html("<center><h3>" + $('#survey_id :selected').text() + "</h3></center>");
            $("#graph_val").append(survey_data);
            $('.attainment').each(function () {
                graphVal = $(this).val();
                actual_data.push(graphVal);
                dept_name.push(su_for_label + ' ' + i);
                i++;
            });

            $.ajax({
                type: 'POST',
                url: base_url + 'survey/surveys/getSurveyQuestionsForGraph',
                data: post_data,
                dataType: 'html',
                success: function (survey_data_gp) {
                    var i = 0;
                    var data = jQuery.parseJSON(survey_data_gp);
                    var data1 = new Array();
                    var data2 = new Array();

                    $.each(data, function () {
                        data1.push(data[i].co_code);
                        data2.push(Number(data[i].Attaintment));
                        i++;

                    });
                    $('#co_level_indirect_nav').html('<div class="row-fluid"><div class="span6"></div><div class="span6 rightJustified" ><a id="exportToPDF" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a></div></div><br><div class="navbar"><div class="navbar-inner-custom">'+ entity_clo_full +'('+ entity_clo +') Indirect Attainment</div></div>');
                    $('#chart_plot_indirect_attain').html('<div class=row-fluid><div class=span12><div id=indirect_attain_chart_shv style=position:relative; class=jqplot-target></div></div></div>');

                    var ticks = data1;
                    var s1 = data2;
                    //var s2 = data2;
                    var plot1 = $.jqplot('indirect_attain_chart_shv', [s1], {
                        seriesColors: ["#4BB2C5"], //, "#4BB2C5"
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
                                label: entity_co_single+' Attainment %'
                            },
                        ],
                        highlighter: {
                            show: true,
                            tooltipLocation: 'e', // location of tooltip: n, ne, e, se, s, sw, w, nw.
                            tooltipAxes: 'x', // which axis values to display in the tooltip, x, y or both.
                            showMarker: false,
                            tooltipContentEditor: function (str, seriesIndex, pointIndex, plot) {
                                return data1[pointIndex];
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
									tickInterval:'10',
                                tickOptions: {
                                    formatString: '%.2f%'
                                }
                            }
                        }

                    });
                }
            });
        }
    });

}

//############################################################################################
//Direct Indirect Attainment Analysis
//############################################################################################

if ($.cookie('remember_dept_comparison') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#dept_id_comparison option[value="' + $.cookie('remember_dept_comparison') + '"]').prop('selected', true);
    select_pgm_comparison();
}
function select_pgm_comparison() {
    $.cookie('remember_dept_comparison', $('#dept_id_comparison option:selected').val(), {
        expires: 90,
        path: '/'
    });
    empty_all_divs();
    var pgm_list_path = base_url + 'tier_ii/co_section_level_attainment/select_pgm';

    var data_val = $('#dept_id_comparison').val();
    var post_data = {
        'dept_id': data_val
    }
    $.ajax({
        type: "POST",
        url: pgm_list_path,
        data: post_data,
        success: function (msg) {

            $('#pgm_id_comparison').html(msg);
            if ($.cookie('remember_pgm_comparison') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#pgm_id_comparison option[value="' + $.cookie('remember_pgm_comparison') + '"]').prop('selected', true);
                select_crclm_comparison();
            }
        }
    });
}

function select_crclm_comparison() {
    empty_all_divs();
    $.cookie('remember_pgm_comparison', $('#pgm_id_comparison option:selected').val(), {
        expires: 90,
        path: '/'
    });
    var crclm_list_path = base_url + 'tier_ii/co_section_level_attainment/select_crclm';
    var data_val = $('#pgm_id_comparison').val();
    var post_data = {
        'pgm_id': data_val
    }
    $.ajax({
        type: "POST",
        url: crclm_list_path,
        data: post_data,
        success: function (msg) {
            $('#crclm_id_comparison').html(msg);
            if ($.cookie('remember_crclm_comparison') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#crclm_id_comparison option[value="' + $.cookie('remember_crclm_comparison') + '"]').prop('selected', true);
                //get_selected_value();
                select_term_comparison();
            }
        }
    });
}

function select_term_comparison() {
    $.cookie('remember_crclm_comparison', $('#crclm_id_comparison option:selected').val(), {
        expires: 90,
        path: '/'
    });
    empty_all_divs();
    var term_list_path = base_url + 'tier_ii/co_section_level_attainment/select_term';
    var data_val = $('#crclm_id_comparison').val();
    var post_data = {
        'crclm_id': data_val
    }

    $.ajax({
        type: "POST",
        url: term_list_path,
        data: post_data,
        success: function (msg) {
            $('#term_comparison').html(msg);
            if ($.cookie('remember_term_comparison') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#term_comparison option[value="' + $.cookie('remember_term_comparison') + '"]').prop('selected', true);

                select_course_comparison();
            }

        }
    });
}

function select_course_comparison() {
    $.cookie('remember_term_comparison', $('#term_comparison option:selected').val(), {
        expires: 90,
        path: '/'
    });
    empty_all_divs();
    var course_list_path = base_url + 'tier_ii/co_section_level_attainment/select_course';
    var data_val = $('#term_comparison').val();
    if (data_val) {
        var post_data = {
            'term_id': data_val
        }
        $.ajax({
            type: "POST",
            url: course_list_path,
            data: post_data,
            success: function (msg) {
                $('#course').html(msg);
                if ($.cookie('remember_course_comparison') != null) {
                    // set the option to selected that corresponds to what the cookie is set to
                    $('#course_comparison option[value="' + $.cookie('remember_course_comparison') + '"]').prop('selected', true);
                    select_survey_comparison();
                }
            }
        });
    } else {
        $('#course_comparison').html('<option value="">Select Course</option>');
    }
}

function select_survey_comparison() {
    $.cookie('remember_course_comparison', $('#course_comparison option:selected').val(), {
        expires: 90,
        path: '/'
    });
    empty_all_divs();
    $('#direct_indirect_submit').attr('disabled', true);
    var course_list_path = base_url + 'tier_ii/co_section_level_attainment/select_survey';
    var data_val = $('#course').val();
    var clo_attain_type = $('#clo_attainment_type').val();
    if (data_val) {
        var post_data = {
            'course': data_val
        }
        $.ajax({
            type: "POST",
            url: course_list_path,
            data: post_data,
            success: function (msg) {
                $('#direct_indirect_submit').attr('disabled', false);
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

/* 	function comparison_direct_indirect(){
 $.cookie('remember_survey_comparison', $('#comparison_survey_id option:selected').val(), {expires: 90, path: '/'});
 empty_all_divs();
 var course_comparison = $('#course_comparison').val();
 
 var course_list_path = base_url + 'tier_ii/co_section_level_attainment/getDirectIndirectCOAttaintmentData';
 
 if(data_val){
 var post_data = {
 'course': data_val
 }
 $.ajax({type: "POST",
 url: course_list_path,
 data: post_data,
 success: function(msg) {
 $('#survey_id').html(msg);
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
 */

// CO Attainment Direct & Indirect Scripts

$('#indirect_direct_attainment_form').on('change', '#type_data_survey', function () {
    var type_data_id = $('#type_data_survey').val();
    document.getElementById('direct_attainment_val').value = 00;
    document.getElementById('indirect_attainment_val').value = 00;
    $('#direct_indirect_submit').attr('disabled', false);
    if (type_data_id == 1) {
        empty_all_divs();
        $('#survey_occasion_div').css({
            "display": "none"
        });
        $('#survey_occasion_actual_data_div').empty();
        var course = $('#course').val();
    } else if (type_data_id == 2) {
        empty_all_divs();
        var crclm_id = $('#crclm_id').val();
        var term_id = $('#term').val();
        var crs_id = $('#course').val();
        $('#survey_occasion_actual_data_div').empty();
        var post_data1 = {
            'crclm_id': crclm_id,
            'term_id': term_id,
            'crs_id': crs_id
        }
        $.ajax({
            type: "POST",
            url: base_url + 'tier_ii/co_section_level_attainment/select_occasion',
            data: post_data1,
            success: function (occasionList) {
                if (occasionList != 0) {
                    $('#survey_occasion_div').css({
                        "display": "inline"
                    });
                    $('#survey_occasion').html(occasionList);
                } else {
                    $('#survey_occasion_div').css({
                        "display": "none"
                    });
                    $('#survey_occasion_actual_data_div').html('<font color="red">Continuous Internal Assessment ('+ entity_cie +') Occasions are not defined</font>');
                }
            }
        });
    } else if (type_data_id == 'cia_tee') {
        empty_all_divs();
        $('#survey_occasion_div').css({
            "display": "none"
        });
        $('#survey_occasion_actual_data_div').empty();
        var course = $('#course').val();
    } else {
        empty_all_divs();
        $('#survey_occasion_div').css({
            "display": "none"
        });
        $('#survey_occasion_actual_data_div').empty();

    }
});

/* $('#direct_indirect_submit').on('click', function (e) {
 $('#direct_indirect_submit').attr('disabled', false);
 }); */
// Finalize Direct & Indirect Attainment button to store COs Attainment values onto table direct_attainment
$('#finalize_direct_indirect').on('click', function (e) {
    $('#finalize_direct_indirect_confirmation').modal('show');
});

$('#finalize_direct_indirect_confrim').on('click', function (e) {
    var survey_id = $('#comparison_survey_id').val();
    var course_id = $('#course').val();
    var type_data = $('#type_data_survey').val();
    var occasion = $('#survey_occasion').val();
    var direct_attainment_val = $('#direct_attainment_val').val();
    var indirect_attainment_val = $('#indirect_attainment_val').val();

    var post_data = {
        'survey_id': survey_id,
        'course_comparison': course_id,
        'direct_attainment_val': direct_attainment_val,
        'indirect_attainment_val': indirect_attainment_val,
        'type_data': type_data,
        'occasion': occasion

    }

    $.ajax({
        type: "POST",
        url: base_url + 'tier_ii/co_section_level_attainment/finalize_getDirectIndirectCOAttaintmentData',
        data: post_data,
        dataType: 'JSON',
        success: function (msg) {
            if (msg != 0) {
                $('#finalize_direct_indirect_success').modal('show');
            }
        }
    });
});

// Submit of Direct & Indirect button to plot table
$('#direct_indirect_submit').on('click', function (e) {
    var survey_id = $('#comparison_survey_id').val();
    var course_id = $('#course').val();
    var type_data = $('#type_data_survey').val();
    var occasion = $('#survey_occasion').val();
    var direct_attainment_val = $('#direct_attainment_val').val();
    var indirect_attainment_val = $('#indirect_attainment_val').val();
    /* if (survey_id != 0) {
     plot_graphs_comparison_attainment(survey_id,course_id,direct_attainment_val,indirect_attainment_val,type_data,occasion);
     }else {
     $('#error_dialog_window').modal('show');
     } */
});

function plot_graphs_comparison_attainment(survey_id, course_id, direct_attainment_val, indirect_attainment_val, type_data, occasion) {

    var post_data = {
        'survey_id': survey_id,
        'course_comparison': course_id,
        'direct_attainment_val': direct_attainment_val,
        'indirect_attainment_val': indirect_attainment_val,
        'type_data': type_data,
        'occasion': occasion

    }

    $.ajax({
        type: "POST",
        url: base_url + 'tier_ii/co_section_level_attainment/getDirectIndirectCOAttaintmentData',
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

                clo_statement_data = new Array();
                question_data = new Array();
                Agree_data = new Array();
                Disagree_data = new Array();
                Fairly_Agree_data = new Array();
                Strongly_Agree_data = new Array();

                //Data for graph
                var data1 = new Array();
                var data2 = new Array();

                $.each(json_graph_data, function () {
                    attainment[i] = this['Attaintment'];
                    attainment_pc[i] = this['AttaintmentPercentage'];
                    clo_code[i] = this['clo_code'];
                    clo_id[i] = this['clo_id'];
                    direct_attainment[i] = this['directAttaintment'];
                    direct_percentage[i] = this['directPercentage'];
                    indirect_attainment[i] = this['indirectAttaintment'];
                    indirect_percentage[i] = this['indirectPercentage'];
                    total_direct_attainment[i] = this['totalDirectAttaintment'];

                    /*
                     clo_statement_data.push(Number(clo_statement[i]));
                     question_data.push(Number(question[i]));
                     Agree_data.push(Number(Agree[i]));
                     Disagree_data.push(Disagree[i]);
                     Fairly_Agree_data.push(Fairly_Agree[i]);
                     Strongly_Agree_data.push(Strongly_Agree[i]); */

                    data1.push(clo_code[i]);
                    data2.push(Number(attainment[i]));

                    i++;

                });
			//	$('#actual_course_attainment').html('<b>Course Attainment: </b> ' + Math.round((sec_marks_sum * 100) / (max_marks_sum)) + '%');
                $('#co_level_comparison_nav').html('<div class="row-fluid"><div class="span11 rightJustified" ><a id="exportToPDF" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a></div></div><br><div class="navbar"><div class="navbar-inner-custom">'+ entity_clo_full_singular +' ('+ entity_co_single +') Direct and Indirect Attainment Analysis</div></div>');
                $('#chart_plot_7').html('<div class=row-fluid><div class=span12><div id=clo_atn_chart_shv style=position:relative; class=jqplot-target></div></div><div class=span12><div class=span6></div><div class=span11 id="course_outcome_attainment_div"> <table border=1 class="table table-bordered" id=co_level_comparison_nav_table_id><thead></thead><tbody id="co_level_body"></tbody></table><div id="actual_course_attainment">tst dfhf</div></div><div id=docs class=span12></div></div></div>');
                $('#co_level_comparison_nav_table_id > tbody:first').append('<tr><td><center><b>' + entity_clo + ' Code</b></center></td><td><center><b>Actual Direct Attainment %</b></center></td><td><center><b>Actual Indirect Attainment %</b></center></td><td><center><b>Direct Attainment Weightage %</b></center></td> <td><center><b>Indirect Attainment Weightage %</b></center></td><td style="white-space:no-wrap;"><center><b>After Weightage Direct Attainment %</b></center></td> <td><center><b>After Weightage Indirect Attainment %</b></center></td><td><center><b>Overall Attainment %</b></center></td></tr>');
                $.each(json_graph_data, function () {
                    $('#co_level_comparison_nav_table_id > tbody:last').append('<tr><td><center>' + this['clo_code'] + '</center></td><td style="text-align: right;">' + this['totalDirectAttaintment'] + '</td><td style="text-align: right;">' + parseFloat(this['AttaintmentPercentage']).toFixed(2) + '</td><td style="text-align: right;">' + parseFloat(this['directPercentage']).toFixed(2) + '</td><td style="text-align: right;">' + parseFloat(this['indirectPercentage']).toFixed(2) + '</td><td style="text-align: right;">' + this['directAttaintment'] + '</td><td style="text-align: right;">' + this['indirectAttaintment'] + '</td><td style="text-align: right;">' + this['Attaintment'] + '</td></tr>');
                });
                var ticks = data1;
                var s1 = data2;
                var plot1 = $.jqplot('clo_atn_chart_shv', [s1], {
                    seriesColors: ["#4bb2c5"], //,"#3efc70"
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
                            label: entity_clo_singular + ' Attainment %'
                        },
                    ],
                    highlighter: {
                        show: true,
                        tooltipLocation: 'e', // location of tooltip: n, ne, e, se, s, sw, w, nw.
                        tooltipAxes: 'x', // which axis values to display in the tooltip, x, y or both.
                        showMarker: false,
                        tooltipContentEditor: function (str, seriesIndex, pointIndex, plot) {
                            return clo_code[pointIndex];
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
								tickInterval:'10',
                            tickOptions: {
                                formatString: '%.2f%'
                            }
                        }
                    }

                }); //end of graph
                //document.getElementById('finalize_direct_indirect_div').style.visibility = 'visible';
            }
        }
    });
	
}



$('form[name="add_form"]').on('click', '#export_doc', function(e){

var values = $.map($('#occasion option'), function(e) { return e.text; });
values = (values.slice(1,-1));

// as a comma separated string
//$('#values').text("values are: " + values.join(','));

    e.preventDefault();   
	$( ".navbar-inner-custom" ).wrapInner( "<b></b>" );
	var crclm_name = $('#crclm_id :selected').text();
	var term_name = $('#term :selected').text();	
	var course_name = $('#course :selected').text();
	var section_name = $('#section_id :selected').text();
	
	if($("#occasion").val() == 'all_occasion'){ var occasion_name = values.join(',');}else{var occasion_name = $('#occasion :selected').text();}
	
		$('#head_data').html('<table class="table table-bordered" style="width:100%;"><tr><td width=325><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Curriculum : </b><b needAlt=1 class="font_h ul_class">'+ crclm_name +'</b></td><td width=325><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Term : </b><b needAlt=1 class="font_h ul_class">'+ term_name +'</b></td></tr><tr><td width=325><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Course : </b><b needAlt=1 class="font_h ul_class">'+ course_name +'</b></td><td width=325><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Section : </b><b needAlt=1 class="font_h ul_class">'+ section_name +'</b></td></tr><tr><td width=325><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Occasion : </b><b needAlt=1 class="font_h ul_class">'+ occasion_name +'</b></td></tr></table><br/><b><center> '+ course_outcome +'Attainment </center></b><br/>');
		
	
    var graph_data = $('#chart1').clone().html();
    var imgData_1  = $('#chart1').jqplotToImageStr({});
    var imgElem_1  = $('<img/>').attr('src',imgData_1);
	$('#form_name').val('add_form');
	var main_head =  $('#head_data').html(); 
    var export_data = $('#co_level_attain_data').clone().html() + $('#finalised_data').clone().html();
    $('#main_head').val(main_head);
    $('#export_graph_data_to_doc').val(imgData_1);
    $('#export_data_to_doc').val(export_data);

    $('#add_form').submit();	
	 $(".navbar-inner-custom").find("b").contents().unwrap();

})



$(document).ready(function () {
	$('#occasion').multiselect({
			includeSelectAllOption : true,
			buttonWidth : '160px',
			nonSelectedText : 'Select Occasions',	
			nonSelectedAll : 'all',
			templates : {
				button : '<button type="button" class="multiselect btn btn-link dropdown-toggle" data-toggle="dropdown"></button>'
			}

		});
});