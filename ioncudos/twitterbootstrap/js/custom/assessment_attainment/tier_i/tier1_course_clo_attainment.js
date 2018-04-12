var base_url = $('#get_base_url').val();
var cie_counter = new Array();
cie_counter.push(1);
var co_po_attainment_total = 0;

function empty_direct_attainment_divs() {
	$('#actual_data_div').empty();
	$('#co_level_nav').empty();
	$('#co_level_table_id').remove();
	$('#co_level_body').remove();
	$('#student_docs').remove();
	$('#docs').remove();
	$('#actual_course_attainment').remove();
	$('#chart1').remove();

	$('#co_level_student_threshold_nav').empty();
	$('#co_level_student_threshold_nav_table_id').remove();
	$('#co_level_student_threshold_nav_body').remove();
	$('#docs_1').remove();
	$('#actual_course_attainment_1').remove();
	$('#chart5').remove();

	$('#bloom_level_nav').empty();
	$('#bloom_level').remove();
	$('#bloom_level_body').remove();
	$('#bloom_level_note').remove();
	$('#chart3').remove();

	$('#cumulative_performance_nav').empty();
	$('#cumulative_performance').remove();
	$('#cumulative_performance_body').remove();
	$('#cumulative_performance_note').remove();
	$('#chart6').remove();

	$('#course_attainment_nav').empty();
	$('#course_attainment').remove();
	$('#course_attainment_body').remove();
	$('#course_attainment_note').remove();
	$('#chart2').remove();

	$('#CLO_PO_attainment_nav').empty();
	$('.crs_co_po_attainment').remove();
	$('#CLO_PO_attainment_body').remove();
	$('#CLO_PO_attainment_note').empty();
}

function empty_indirect_attainment_divs() {
	$('#co_level_indirect_nav').empty();
	$('#co_level_indirect_nav_table_id').remove();
	$('#co_level_indirect_nav_body').remove();
	$('#chart6').remove();
	$('#chart_plot_indirect_attain').empty();
	$('#graph_val').empty();
}
function empty_direct_indirect_attainment_divs() {
	$('#co_level_comparison_nav').empty();
	$('#co_level_comparison_nav_table_id').remove();
	$('#co_level_comparison_nav_body').remove();
	$('#chart7').remove();
	$('#chart_plot_7').empty();
    $('#co_attain_data_finalized_div').empty();
    $('#po_attain_data_div').empty();

}
function empty_all_divs() {
	empty_direct_attainment_divs();
	empty_indirect_attainment_divs();
	empty_direct_indirect_attainment_divs();
	$('#indirect_direct_attainment_form').trigger('reset');$('#student_actual_course_attainment').remove();
}

$(document).ready(function () {
	$('#finalize_direct_indirect_div').hide();
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
$('#crclm_id').on('change',function(){
$('#section_finalize_status_tbl').empty();
$('#mte_finalize_status_tbl').empty();  
$('#note_data').empty();
$('#display_finalized_attainment_div').hide();
$('#display_finalized_po_attainment_div').hide();
});
function select_pgm() {
	$.cookie('remember_dept', $('#dept_id option:selected').val(), {
		expires : 90,
		path : '/'
	});
	empty_all_divs();
	var pgm_list_path = base_url + 'assessment_attainment/tier1_course_clo_attainment/select_pgm';
	var data_val = $('#dept_id').val();
	var post_data = {
		'dept_id' : data_val
	}
	$.ajax({
		type : "POST",
		url : pgm_list_path,
		data : post_data,
		success : function (msg) {
			$('#pgm_id').html(msg);
			if ($.cookie('remember_pgm') != null) {
				// set the option to selected that corresponds to what the cookie is set to
				$('#pgm_id option[value="' + $.cookie('remember_pgm') + '"]').prop('selected', true);
				select_crclm();
			}
		}
	});
}


function select_crclm() {
 $('#loading').show();
$('#select_occa_type_blm').trigger('change');
	empty_all_divs();
	$.cookie('remember_pgm', $('#pgm_id option:selected').val(), {
		expires : 90,
		path : '/'
	});
	var crclm_list_path = base_url + 'assessment_attainment/tier1_course_clo_attainment/select_crclm';
	var data_val = $('#pgm_id').val();
	var post_data = {
		'pgm_id' : data_val
	}
	$.ajax({
		type : "POST",
		url : crclm_list_path,
		data : post_data,
		success : function (msg) {
			$('#crclm_id').html(msg); $('#loading').hide();
			if ($.cookie('remember_crclm') != null) {
				// set the option to selected that corresponds to what the cookie is set to
				$('#crclm_id option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
				//get_selected_value();
				select_term(); 
			}
		}
	});
}

function select_term() {
 $('#loading').show();
    $('.export_doc').prop('disabled', true);
$('#select_occa_type_blm').trigger('change');
//$('#select_occa_type').trigger('change');
$('#select_occa_type').val(''); 
type_val = $('#select_occa_type').val(); 
if(type_val == ''){
$('#breaks').html('');
		 $('#occasion_wise_attainment_div').hide();		
	} 
	$.cookie('remember_crclm', $('#crclm_id option:selected').val(), {
		expires : 90,
		path : '/'
	});
	empty_all_divs();
	var term_list_path = base_url + 'assessment_attainment/tier1_course_clo_attainment/select_term';
	var student_list_path = base_url + 'assessment_attainment/tier1_course_clo_attainment/student_list';
	var data_val = $('#crclm_id').val();
	var post_data = {
		'crclm_id' : data_val
	}

	$.ajax({
		type : "POST",
		url : term_list_path,
		data : post_data,
		success : function (msg) {
			$('#term').html(msg); $('#loading').hide();
			if ($.cookie('remember_term') != null) {
				// set the option to selected that corresponds to what the cookie is set to
				$('#term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);

				
			}
select_course();
		}
	});
	
	
 	$.ajax({
		type : "POST",
		url : student_list_path,
		data : post_data,
		success : function (msg) {
			$('#student_list').html(msg); $('#loading').hide();
		}
	});	
}

function select_course() {
 $('#loading').show();
 $('.export_doc').prop('disabled', true);
$('#select_occa_type_blm').trigger('change');
//$('#select_occa_type').trigger('change');
	$.cookie('remember_term', $('#term option:selected').val(), {
		expires : 90,
		path : '/'
	});
	empty_all_divs();
	$('#section_finalize_status_tbl').empty();
	$('#mte_finalize_status_tbl').empty();  
	$('#select_occa_type').val(''); 
	type_val = $('#select_occa_type').val(); 
        if(type_val == ''){
		$('#breaks').html('');
		 $('#occasion_wise_attainment_div').hide();		
	} 
	$('#note_data').empty();
	var course_list_path = base_url + 'assessment_attainment/tier1_course_clo_attainment/select_course';
	var data_val = $('#term').val();
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
				$('#course').html(msg); $('#loading').hide();
                                if ($.cookie('remember_course') != null) {
                                    
					// set the option to selected that corresponds to what the cookie is set to
					$('#course option[value="' + $.cookie('remember_course') + '"]').prop('selected', true);
                     $('#course').trigger('change');
					 
				}
				
			}
		});
	} else {
	 $('#loading').hide();
		$('#course').html('<option value="">Select Course</option>');
		$('#section_finalize_status_tbl').empty();
		$('#mte_finalize_status_tbl').empty();  
		$('#note_data').empty();
		 $('#course').trigger('change');
	}
}

// Function to populate course related sections.

$('#course').on('change',function(){

    $('#select_occa_type_blm').trigger('change').val('');
    $('#select_occa_type').val(''); 
    type_val = $('#select_occa_type').val();
    if(type_val == ''){
        $('#breaks').html('');
        $('#occasion_wise_attainment_div').hide();		 
		$('#attainment_finalize_button_div').hide();		
    }    
    $.cookie('remember_course', $('#course option:selected').val(), {
        expires : 90,
        path : '/'
    });
    $('#display_finalized_attainment_div').show();
    $('#display_finalized_po_attainment_div').show();
    var course_id = $(this).val();
    var crclm_id = $('#crclm_id').val();
    var term_id = $('#term').val();	
    var post_data =  {'course_id':course_id,'crclm_id':crclm_id,'term_id':term_id};
	
    if(course_id){
	   $('.export_doc').prop('disabled',false);
    $('#occasion_wise_attainment_navbar').hide();
    $('#ocaasion_type_navbar').empty();
		$.ajax({
            type : "POST",
            url : base_url + 'assessment_attainment/tier1_course_clo_attainment/fetch_section_data',
            data : post_data,
            dataType: 'json',
            success : function (msg) {
			$('#loading').hide();
						//$('#direct_attainment').show();$('#mte_attainment').show();
						$('#select_occa_type').html(msg.type_list);
						$('#select_occa_type').multiselect('rebuild');
                        $('#section_finalize_status_tbl').empty();     
						$('#mte_finalize_status_tbl').empty();    
						$('#redirect_link').empty();   $('#redirect_link_mte').empty();    
						
						
                        $('#section_finalize_status_tbl').html(msg.section_wise_table);
						$('#redirect_link').html(msg.cia_redirect);
						
						$('#mte_finalize_status_tbl').html(msg.mte_wise_table);
						$('#redirect_link_mte').html(msg.mte_redirect);
                        
                        if(msg.section_wise_table  != false){
                            $('#export_tab1_to_doc').prop('disabled',false);
                        }
                        { $('#note_data').html('<div class="span12"><table width = 900 border="1" class="table table-bordered"><tbody><tr><td width = 300  colspan="2"><b>Note:</b> The above bar graph depicts the overall class performance with respect to the Threshold % for individual Course Outcomes (COs). The Threshold based Attainment % & Average based Attainment % is calculated using the below formula.</td></tr><tr><td width = 300 ><b>For Threshold based Attainment % = ( x / y ) * 100 </b><br/> x = Count of Students &gt;= to Threshold % <br/> y = Total number of Students Attempted. </td><td width = 300 ><b>For Average based Attainment % = ( x / y ) * 100 </b> <br/> x = Average Secured marks of Attempted Students <br/> y = Maximum Marks. </td></tr></tbody></table></div>');} 
						{ $('#note_data_mte').html('<div class="span12"><table width = 900 border="1" class="table table-bordered"><tbody><tr><td width = 300 colspan="2"><b>Note:</b> The above bar graph depicts the overall class performance with respect to the Threshold % for individual Course Outcomes (COs). The Threshold based Attainment % & Average based Attainment % is calculated using the below formula.</td></tr><tr><td width = 300 ><b>For Threshold based Attainment % = ( x / y ) * 100 </b><br/> x = Count of Students &gt;= to Threshold % <br/> y = Total number of Students Attempted. </td><td width = 300 ><b>For Average based Attainment % = ( x / y ) * 100 </b> <br/> x = Average Secured marks of Attempted Students <br/> y = Maximum Marks. </td></tr></tbody></table></div>');}
                       // $('#select_occa_type').prop('selectedIndex',0);
                        $('#all_section_attainment').empty();
                        $('#attainment_finalize_button_div').hide();
                        $('#all_section_co_attainment_chart').empty();
                        $('#all_section_attainment').empty();
                        $('#note_data1').empty();
                        $('#all_section_co_attainment_chart').empty();
                        $("#all_section_co_attainment_chart").css("height",400);
                        if(msg.finalized_data_tbl != 'false'){
                            $('#display_finalized_attainment_div').show();
                            $('#display_finalized_attainment_tbl').empty();
                            $('#display_finalized_attainment_tbl').html(msg.finalized_data_tbl);

                        }else{
                            $('#display_finalized_attainment_div').hide();
                            $('#display_finalized_attainment_tbl').empty();
                        }

                        if(msg.po_attainment_tbl != 'false'){
                            $('#display_finalized_po_attainment_div').show();
                            $('#display_finalized_po_attainment_tbl').empty();
                            $('#display_finalized_po_attainment_tbl').html(msg.po_attainment_tbl);

                            $('#display_finalized_po_attainment_div_note').empty(); 
                                                            $('#display_finalized_po_attainment_div_note').show();  
                                                            $('#display_finalized_po_attainment_div_note').append(msg.note);

                                                            $('#display_finalized_po_attainment_mapping_tbl').show();
                            $('#display_finalized_po_attainment_mapping_tbl').empty();
                            $('#display_finalized_po_attainment_mapping_tbl').html(msg.po_attainment_mapping_tbl);  

                                                            $('#display_map_level_weightage').show();
                                                            $('#display_map_level_weightage').empty();
                            $('#display_map_level_weightage').html(msg.display_map_level_weightage);								
                        }else{
                            $('#display_finalized_po_attainment_div_note').hide();  
                            $('#display_finalized_po_attainment_div').hide();
                            $('#display_finalized_po_attainment_tbl').empty();  	
                        }
                    }
		});
  }else{
	$('#loading').hide();
		$('#display_finalized_po_attainment_div_note').hide();  
		$('#section_finalize_status_tbl').empty();
		$('#mte_finalize_status_tbl').empty();  
                $('#export_tab1_to_doc').prop('disabled',true);
		$('#note_data').empty();
		$('#display_finalized_po_attainment_div').hide();
		$('#display_finalized_attainment_div').hide();
		$('#occasion_wise_attainment_div').hide();
  }
  
});
$('.cancel_button').on('click', function(){

		window.location.href = 'tier1_course_clo_attainment';
		
});
$('#select_occa_type_blm , #student_list').on('change', function(){ 

	var type_val =  $('#select_occa_type_blm').val();
	var course_id = $("#course").val();
	var crclm_id = $('#crclm_id').val();
	var term_id = $('#term').val();
	var student_usn = $('#student_list').val();
	var post_data = {'type_val':type_val, 'course_id':course_id, 'crclm_id': crclm_id , 'term_id':term_id};
	
	if(type_val == 3){
	 $('#loading').show();
	$('.section_div').show();$('.occassion_div').show();
	$('.bloom_navabar_header').empty();
	if(student_usn == ''){$('.bloom_navabar_header').html(entity_cie+" Bloom's Level Attainment");} else{$('.bloom_navabar_header').html("Student "+ entity_cie +" Bloom's Level Attainment");}
	$('#display_bloom_data').hide();
	  var post_data =  {'course_id':course_id,'crclm_id':crclm_id,'term_id':term_id};
	  $.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_course_clo_attainment/fetch_sections',
			data : post_data,
			success : function (msg) {
					 $('#tier1_section').html(msg); $('#loading').hide();
					 if ($.cookie('remember_course') != null) {
					// set the option to selected that corresponds to what the cookie is set to
					$('#tier1_section option[value="' + $.cookie('remember_section') + '"]').prop('selected', true);
					if(student_usn ==''){$('#tier1_section').trigger('change');}
				}
			}
		});	 
		var occasion = $('#occasion').val();
		var student_usn = $('#student_list').val();
		var post_data = {'type_val':type_val, 'course_id':course_id, 'crclm_id': crclm_id , 'term_id':term_id ,'occasion':occasion , 'student_usn' : student_usn};
/* 		$.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_course_clo_attainment/fetch_student_threshold',
			data : post_data,
			dataType : 'JSON',
			success : function (msg) {
			 $('#loading').hide();
					$('#display_student_data').html(msg.table);
			}
		}); */
	}else if(type_val == 5){
	 $('#loading').show();
	$('#example_bloom').show();
 	$('#diplay_bloom_data_chart').show();
		$("#diplay_bloom_data_chart").css("height",350);
	$('.section_div').hide();
	$('.occassion_div').hide();

		var course_id = $("#course").val();
		var crclm_id = $('#crclm_id').val();
		var term_id = $('#term').val();
		var type_val = $('#select_occa_type_blm').val(); 
		var student_usn = $('#student_list').val();
		
		
		if(student_usn == ''){
			var student_list_path = base_url + 'assessment_attainment/tier1_course_clo_attainment/student_list_tee';
			var data_val = $('#crclm_id').val();
			var post_data = {
				'crclm_id' : data_val,				
				'crs_id'   : course_id
			}
			
			$.ajax({
				type : "POST",
				url : student_list_path,
				data : post_data,
				success : function (msg) {
					$('#student_list').html(msg); $('#loading').hide();
				}
			});	 
		}
		$('.bloom_navabar_header').empty();
		if(student_usn == ''){$('.bloom_navabar_header').html(entity_tee+ " Bloom's Level Attainment");}else{ $('.bloom_navabar_header').html("Student "+ entity_tee +" Bloom's Level Attainment"); }
		var post_data = {'type_val':type_val, 'course_id':course_id, 'crclm_id': crclm_id , 'term_id':term_id , 'student_usn' : student_usn};
		  $.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_course_clo_attainment/cia_bloom_level_attainment',
			data : post_data,
			dataType: 'json',
			success : function (msg) {
			 $('#loading').hide();
				populate_table_cia(msg); 				
				if(msg.attainment!= ''){
					show_chart(msg);
				}else{
							$('#diplay_bloom_data_chart').empty();
							$("#diplay_bloom_data_chart").css("height",0);
							$('display_student_data').empty();
							$('display_student_data').html('');
				}
			}
		});
		
		var occasion = $('#occasion').val();
		var student_usn = $('#student_list').val();
		var post_data = {'type_val':type_val, 'course_id':course_id, 'crclm_id': crclm_id , 'term_id':term_id ,'occasion':occasion , 'student_usn' : student_usn};
	/* 	$.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_course_clo_attainment/fetch_student_threshold',
			data : post_data,
			dataType : 'JSON',
			success : function (msg) {
					$('#display_student_data').html(msg.table);
			}
		}); */
	}/* else{
	$('.section_div').hide();$('.occassion_div').hide();
	} */
	
}); 


$('#tier1_section').on('change' , function(){
 $('#loading').show();
		var section_id =  $(this).val();
		var course_id = $("#course").val();
		var crclm_id = $('#crclm_id').val();
		var term_id = $('#term').val();
		var type_val = $('#select_occa_type_blm').val(); 
		var post_data = {'type_val':type_val, 'course_id':course_id, 'crclm_id': crclm_id , 'term_id':term_id , 'section_id':section_id};
		  $.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_course_clo_attainment/fetch_occasions',
			data : post_data,
			success : function (msg) {
					 $('#occasion').html(msg); $('#loading').hide();
					 if ($.cookie('remember_course') != null) {
					// set the option to selected that corresponds to what the cookie is set to
					$('#occasion option[value="' + $.cookie('remember_section') + '"]').prop('selected', true);
				}
			}
		});

});

$('#occasion ').on('change' , function(){
 $('#loading').show();
$('#example_bloom').show();
 	$('#diplay_bloom_data_chart').show();
		$("#diplay_bloom_data_chart").css("height",350);
		var occasion =  $('#occasion').val();
		var course_id = $("#course").val();
		var crclm_id = $('#crclm_id').val();
		var term_id = $('#term').val();
		var section_id = $('#tier1_section').val();
		var type_val = $('#select_occa_type_blm').val(); 
		var student_usn = $('#student_list').val();
		var post_data = {'type_val':type_val, 'course_id':course_id, 'crclm_id': crclm_id , 'term_id':term_id , 'section_id':section_id , 'occasion': occasion , 'student_usn':student_usn};
		 if(occasion != '-1'){
		  $.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_course_clo_attainment/cia_bloom_level_attainment',
			data : post_data,
			dataType: 'json',
			success : function (msg) {
			$('#display_bloom_data').show(); $('#loading').hide();
				populate_table_cia(msg);
				if(msg.attainment!= ''){
					show_chart(msg);
				}else{
						$('#diplay_bloom_data_chart').empty();
						$("#diplay_bloom_data_chart").css("height",0);
				}
			}
		});
	var student_list_path = base_url + 'assessment_attainment/tier1_course_clo_attainment/student_list';
	var data_val = $('#crclm_id').val();
	var post_data = {
		'crclm_id' : data_val,
		'ao_id'   : occasion,
		'crs_id'   : course_id
	}
	
 	$.ajax({
		type : "POST",
		url : student_list_path,
		data : post_data,
		success : function (msg) {
			$('#student_list').html(msg); $('#loading').hide();
		}
	});	
		
	 }else{
		$('#example_bloom').hide(); $('#loading').hide();
		$('#diplay_bloom_data_chart').hide();
	 	$('#diplay_bloom_data_chart').empty();
		$("#diplay_bloom_data_chart").css("height",0);
		$('display_student_data').hide();
	 }

});


$('#student_list').on('change' , function(){
 $('#loading').show();
$('#example_bloom').show();
 	$('#diplay_bloom_data_chart').show();
		$("#diplay_bloom_data_chart").css("height",350);
		var occasion =  $('#occasion').val();
		var course_id = $("#course").val();
		var crclm_id = $('#crclm_id').val();
		var term_id = $('#term').val();
		var section_id = $('#tier1_section').val();
		var type_val = $('#select_occa_type_blm').val(); 
		var student_usn = $('#student_list').val();
		var post_data = {'type_val':type_val, 'course_id':course_id, 'crclm_id': crclm_id , 'term_id':term_id , 'section_id':section_id , 'occasion': occasion , 'student_usn':student_usn};
		 if(type_val == 3){
		 if(occasion != '-1'){
		  $.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_course_clo_attainment/cia_bloom_level_attainment',
			data : post_data,
			dataType: 'json',
			success : function (msg) {
			$('#display_bloom_data').show(); $('#loading').hide();
				populate_table_cia(msg);
				if(msg.attainment!= ''){
					show_chart(msg);
				}else{
						$('#diplay_bloom_data_chart').empty();
						$("#diplay_bloom_data_chart").css("height",0);
				}
			}
		});
		
	 }else{
		$('#example_bloom').hide(); $('#loading').hide();
		$('#diplay_bloom_data_chart').hide();
	 	$('#diplay_bloom_data_chart').empty();
		$("#diplay_bloom_data_chart").css("height",0);
		$('display_student_data').hide();
	 }
	 }else if(type_val == 5){ {
		var post_data = {'type_val':type_val, 'course_id':course_id, 'crclm_id': crclm_id , 'term_id':term_id , 'student_usn' : student_usn};
		  $.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_course_clo_attainment/cia_bloom_level_attainment',
			data : post_data,
			dataType: 'json',
			success : function (msg) {
			 $('#loading').hide();
				populate_table_cia(msg); 				
				if(msg.attainment!= ''){
					show_chart(msg);
				}else{
							$('#diplay_bloom_data_chart').empty();
							$("#diplay_bloom_data_chart").css("height",0);
							$('display_student_data').empty();
							$('display_student_data').html('');
				}
			}
		});
	 }}

});
	function populate_table_cia(msg){
	$('#example_bloom').show();
		$('#example_bloom').dataTable().fnDestroy();
		$('#example_bloom').dataTable(
				{
				"sSort": false,
				"sPaginate": false,
				"bPaginate" : false,
				"bFilter" : false,
				"bInfo" : false,
				"sEmptyTable" : "Your custom message for empty table",
						"aoColumns": [
						{"sTitle": "Sl No.", "mData":"sl_no"},
						{"sTitle": "Bloom's Level", "mData": "level"},
						{"sTitle": "Attainment %","mData":"attainment"},
						{"sTitle": "Threshold %","mData":"percent"}, 			
						
					], "aaData": msg['data'],
					
					"sPaginationType": "bootstrap",
					
		});
	}	
	

	function show_chart(msg){
	var type_val = $('#select_occa_type_blm').val(); 
	$('#diplay_bloom_data_chart').empty();
	$("#diplay_bloom_data_chart").css("height",350);
	if(type_val == 3){ cia_tee = "CIA";}else{  cia_tee = "TEE";}
	if(msg.attainment != 'false'){
	if(msg.bloomlevel_minthreshold != 'false' )
	{	
	var ticks = msg.level;
	  var plot1 = $.jqplot('diplay_bloom_data_chart', [msg.bloomlevel_minthreshold ,msg.attainment], {										
				seriesColors : ["#3efc70" , "#4bb2c5" ], // #4bb2c5   colors that will
				// be assigned to the series.  If there are more series than colors, colors
				// will wrap around and start at the beginning again.
				seriesDefaults : {
					renderer : $.jqplot.BarRenderer,
					rendererOptions : {
						barWidth : 17,
						fillToZero : true,								
					},
					pointLabels : {
						show : true
					}
				},
				series : [{
						label : ' Threshold %'
					}, {
						 label : ' Attainment %'
					}
				],
				highlighter : {
					show : true,
					tooltipLocation : 'e',
					tooltipAxes : 'x',
					fadeTooltip : true,
					showMarker : false,
					tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
						return msg.level;
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
						tickInterval:'10',
						tickOptions : {
							formatString : '%#.2f%'
						}
					}
				}
			});
	} else if(msg.bloomlevel_minthreshold != ''){
	var ticks = msg.level;
	  var plot1 = $.jqplot('diplay_bloom_data_chart', [msg.attainment], {										
				seriesColors : ["#4bb2c5"], // #4bb2c5   colors that will
				// be assigned to the series.  If there are more series than colors, colors
				// will wrap around and start at the beginning again.
				seriesDefaults : {
					renderer : $.jqplot.BarRenderer,
					rendererOptions : {
						barWidth : 17,
						fillToZero : true,								
					},
					pointLabels : {
						show : true
					}
				},
				series : [{
						label : ' Attainment %'
					}
				],
				highlighter : {
					show : true,
					tooltipLocation : 'e',
					tooltipAxes : 'x',
					fadeTooltip : true,
					showMarker : false,
					tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
						//return msg.co_stmt[pointIndex];
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
						tickInterval:'10',
						tickOptions : {
							formatString : '%#.2f%'
						}
					}
				}
			});
	}
	}else{
			$('#diplay_bloom_data_chart').empty();
			$("#diplay_bloom_data_chart").css("height",0);
	}
	}
	
//Function to show the drill down attainment

$(document).on('click','.attainment_drill_down',function(){
   var sec_id = $(this).attr('data-section_id'); 
   var clo_id = $(this).attr('data-clo_id');
   var curriculum = $('#crclm_id option:selected').text();
   var term = $('#term option:selected').text();
   var crs = $('#course option:selected').text();
   var post_data = {'sec_id':sec_id, 'clo_id':clo_id, };
   $.ajax({
            type : "POST",
            url : base_url + 'assessment_attainment/tier1_course_clo_attainment/section_attainment_drill_down',
            data : post_data,
            dataType: 'json',
            success : function (success_msg) {
                                    $('#crclm_name').html(curriculum);
                                    $('#term_name').html(term);
                                    $('#crs_name').html(crs);
                                    $('#sec_name').html(success_msg.section);
                                    $('#cia').empty();
                                    $('#cia').html(success_msg.cia_wieghtage);
//                                    $('#tee').empty();
//                                    $('#tee').html(success_msg.tee_wieghtage);
                                    $('#clo_statement').empty();
                                    $('#clo_statement').html(success_msg.clo_data);
                                    $('#display_attainment_div').empty();
                                    $('#display_attainment_div').html(success_msg.table_data);
                                    $('#attainmnet_modal').modal('show');
                        }
        });
});


$(document).on('click','.attainment_drill_down_mte',function(){
   var sec_id = $(this).attr('data-section_id'); 
   var clo_id = $(this).attr('data-clo_id');
   var curriculum = $('#crclm_id option:selected').text();
   var term = $('#term option:selected').text();
   var crs = $('#course option:selected').text();
   var post_data = {'sec_id':sec_id, 'clo_id':clo_id, };
   $.ajax({
            type : "POST",
            url : base_url + 'assessment_attainment/tier1_course_clo_attainment/attainment_drill_down_mte',
            data : post_data,
            dataType: 'json',
            success : function (success_msg) {
                                    $('#crclm_name').html(curriculum);
                                    $('#term_name').html(term);
                                    $('#crs_name').html(crs);
                                   // $('#sec_name').html(success_msg.section);
                                    $('#cia').empty();
                                    $('#cia').html(success_msg.cia_wieghtage);
//                                    $('#tee').empty();
//                                    $('#tee').html(success_msg.tee_wieghtage);
                                    $('#clo_statement').empty();
                                    $('#clo_statement').html(success_msg.clo_data);
                                    $('#display_attainment_div').empty();
                                    $('#display_attainment_div').html(success_msg.table_data);
                                    $('#attainmnet_modal').modal('show');
                        }
        });
});

// Function to fetch the CLO attainment Data based on the occasion type
$('#occasion_wise_attainment_div').hide();
$('#select_occa_type').on('change',function(){
    var type_val = $(this).val(); 
    if(type_val == '') {
        $('#export_tab2_to_doc').prop('disabled', true);
		$('.type_data').empty();$('.type_data').html('');
		
    }
    var type_text = $(this).find('option:selected').text();
    var crclm_id = $('#crclm_id').val(); 
    var term_id = $('#term').val();
    var crs_id = $('#course').val();
	var type_not_selected = $('#select_occa_type option:not(:selected)').length; 
    
    var all_dropdown_selected = crclm_id && term_id && crs_id;
    if($(this).val() != '' && all_dropdown_selected){
        $('#export_tab2_to_doc').prop('disabled', false);
    } else {
        $('#export_tab2_to_doc').prop('disabled', true);
    }
    //$('#loading').show();
    $('#occasion_wise_attainment_div').show(); 
	//$('html,body').animate({ scrollTop: $("#all_section_attainment").offset().top},'slow');
   
    var post_data = {'crclm_id':crclm_id, 'term_id':term_id, 'crs_id':crs_id, 'type_val':type_val, 'type_not_selected' : type_not_selected};
 
    if(crclm_id && term_id && crs_id ){
	if( type_val!= null && type_val != ''){
 
	
    if(type_text == '' || type_val == ''){
        $('#breaks').html('');
        $('#occasion_wise_attainment_navbar').hide();
        $('#ocaasion_type_navbar').empty();
        $('#attainment_finalize_button_div').hide();
    }                  

    if(type_val){
		
   

    $.ajax({
        type : "POST",
        url : base_url + 'assessment_attainment/tier1_course_clo_attainment/show_section_co_attainment_multiple',
        data : post_data,
        dataType: 'json',
        success : function (msg) {
		console.log(msg);
            $('#loading').hide(); 				
			$('.type_data').html('<center><font color="red"><b>'+  msg['error_status'] +'</font></center>');
			$('.type_data').show();
            if(type_not_selected == 0){
                $('#attainment_finalize_button_div').show();
                $('#clo_id_data').val(msg.co_ids);
                $('#clo_code_data').val(msg.co_code);
                $('#tee_cia_attainment').val(msg.attainment_array);
                $('#average_ao_attainment').val(msg.ao_da_average);
                $('#threshold_co_attainment').val(msg.threshold_array);
                $('#threshold_tee_attainment').val(msg.threshold_tee_array);
                $('#average_attainment').val(msg.average_attainment_array);
                $('#breaks_after').empty();								
                if(msg.threshold_array == ''){
                    $('#attainment_finalize_button_div').hide();
                }else{
                    $('#attainment_finalize_button_div').show();
                }
            }else{ $('#attainment_finalize_button_div').hide(); }
		
				if(msg.msg == 'nothing'){
				$('#all_section_attainment').empty();
				$('#all_section_attainment').html(msg.all_section_table);


				$('#all_section_co_attainment_chart').show();
				{ $('#note_data1').html('<div class="span12"><table border="1" class="table table-bordered"><tbody><tr><td colspan="2"><b>Note:</b> The above bar graph depicts the overall class performance with respect to the Threshold % for individual Course Outcomes (COs). The Threshold based Attainment % & Average based Attainment % is calculated using the below formula.</td></tr><tr><td><b>For Threshold based Attainment % = ( x / y ) * 100 </b><br/> x = Count of Students &gt;= to Threshold % <br/> y = Total number of Students Attempted.</td><td><b>For Average based Attainment % = ( x / y ) * 100 </b> <br/> x = Average Secured marks of Attempted Students <br/> y = Maximum Marks. </td></tr></tbody></table></div>');}
												// plot attainment graph.
				var ticks = msg.co_code;
				$('#all_section_co_attainment_chart').empty();
				$('#export_tab2_to_doc').prop('disabled', false);

				var parameter_list = new Array();
				var side_val = new Array();
                var colors_list = new Array();
					for(m = 0; m < (msg.org_array.length) ; m++){
									val = msg.org_array[m];
									if(val == 'cia_clo_minthreshhold'){ parameter_list.push(msg.cia_threshold_array); colors_list.push("#3efc70");  
									//side_val[m]['label'] = entity_cie + ' Threshold %';
									}
									if(val == 'mte_clo_minthreshhold'){parameter_list.push(msg.mte_threshold_array); colors_list.push("#888888"); //side_val[m]['label'] = entity_mte + ' Threshold %';
									}
									if(val == 'tee_clo_minthreshhold'){parameter_list.push(msg.tee_threshold_array); colors_list.push("#FFA500"); //side_val[m]['label'] = entity_tee + ' Threshold %';
									}
									
									 
					}
					
					//var ticks = success_msg.po_reference;
					parameter_list.push(msg.attainment_array);
					colors_list.push("#4bb2c5");
					
						var plot1 = $.jqplot('all_section_co_attainment_chart', parameter_list , {
                          //seriesColors : ["#FFA500", "#31B404"], // #4bb2c5   colors that will
                         seriesColors : colors_list,
                          // be assigned to the series.  If there are more series than colors, colors
                          // will wrap around and start at the beginning again.
                          seriesDefaults : {
                                  renderer : $.jqplot.BarRenderer,
                                  rendererOptions : {
                                          barWidth : 17,
                                          fillToZero : true,								
                                  },
                                  pointLabels : {
                                          show : true
                                  }
                          },
                          series : msg.label,
                          highlighter : {
                                show : true,
                                tooltipLocation : 'e',
                                tooltipAxes : 'x',
                                fadeTooltip : true,
                                showMarker : false,
                                tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
                                        return msg.co_stmt[pointIndex];
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
                                        tickInterval:'10',
                                        tickOptions : {
                                                formatString : '%#.2f%'
                                        }
                                }
                        }
                });
		
                        }else{
								empty_div();
							/*  $('#all_section_attainment').html('<center><font color="red"><b><p> '+ ''+' data is not uploaded for the course</p></b><p><a href="'+base_url+'/assessment_attainment/import_assessment_data/"class="cursor_pointer">Click here to upload course '+ '' +' data</a></p></font></center>'); */
							 
						}
					}
                    });
			 }else{
        $('#all_section_co_attainment_chart').hide();
        $('#all_section_attainment').empty();
        $('#note_data1').empty();
        $('#loading').hide();$('.type_data').empty();$('.type_data').html('');
    }
		   }
		   else{
			$('#loading').hide();			
				empty_div();
		   }
		   }else{
				$('#loading').hide();
				$('#select_all_dropdowns').modal('show');
			  $('#occasion_wise_attainment_div').hide();	
				$('#attainment_finalize_button_div').hide();	
				$('#all_section_attainment').empty();
				$('#all_section_co_attainment_chart').hide();$('.type_data').empty();$('.type_data').html('');
				 $('#note_data1').empty();
					
		   }
   
   
});

function empty_div(){
	$('#occasion_wise_attainment_div').hide();	
	$('#attainment_finalize_button_div').hide();	
	$('#all_section_attainment').empty();
	$('#all_section_co_attainment_chart').hide();
	 $('#note_data1').empty();
	 $('.type_data').empty();$('.type_data').html('');

}

//Function for showing the cia & tee attainment
$(document).on('click','.cia_tee_drill_down',function(){
  var clo_id = $(this).attr('data-clo_id');  
  var crs_id = $(this).attr('data-crs_id');
  var crclm_name = $('#crclm_id option:selected').text();
  var term_name = $('#term option:selected').text();
  var crs_name = $('#course option:selected').text();
  var post_data = {'clo_id':clo_id,'crs_id':crs_id};
  $.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_course_clo_attainment/show_cia_tee_drilldown',
			data : post_data,
                        dataType: 'json',
                        success : function (msg) {
                            $('#clo_crclm_name').empty();
                            $('#clo_crclm_name').html(crclm_name);
                            $('#clo_term_name').empty();
                            $('#clo_term_name').html(term_name);
                            $('#clo_crs_name').empty();
                            $('#clo_crs_name').html(crs_name);
							$('#total_cia_weightage').empty();
                            $('#total_cia_weightage').html(msg.total_cia_weightage+' %');
							$('#total_tee_weightage').empty();
                            $('#total_tee_weightage').html(msg.total_tee_weightage+' %');
                            $('#cia_tee_clo_statement').empty();
                            $('#cia_tee_clo_statement').html('<b>CO Statement</b><br>'+msg.clo_stmt);
                            $('#cia_tee_clo_statement_table_display').empty();
                            $('#cia_tee_clo_statement_table_display').html(msg.table);
                            $('#clo_cia_tee_attainment').modal('show');
                        }
                    });
  
});

// Function to finalize the cia and tee data

$('#tee_cia_attainment_finalize_btn').on('click',function(){
	$('#clo_attainment_finalize').modal('show');
});


$('#finalize_clo_attainment').on('click',function(){
    var clo_ids = $('#clo_id_data').val();
    var tee_cia_attain = $('#tee_cia_attainment').val();
    var avg_ao_attain = $('#average_ao_attainment').val();
    var threshold_ao_attain = $('#average_ao_attainment').val();
    var co_threshold =  $('#threshold_co_attainment').val();
	var tee_threshold = $('#threshold_tee_attainment').val();
    var crclm_id = $('#crclm_id').val();
    var term_id = $('#term').val();
    var crs_id = $('#course').val();
    var post_data = {'crclm_id':crclm_id, 'term_id':term_id, 'crs_id':crs_id, 'clo_ids':clo_ids,
                        'tee_cia_attain':tee_cia_attain,'avg_ao_attain':avg_ao_attain,'threshold_ao_attain':threshold_ao_attain,
                        'co_threshold':co_threshold, 'tee_threshold':tee_threshold
                    };
   $.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_course_clo_attainment/finalize_co_overall_attainment',
			data : post_data,
                        dataType: 'json',
                        success : function (msg) {  
							if($.trim(msg) == 'true'){
                               $('#clo_attainment_finalize').modal('hide');
                               $('#clo_attainment_final_success').modal('show');
							}else{
                               $('#clo_attainment_final_unsuccess').modal('show');
							}
                        }   
                    });
    
});

$('#section').on('change',function(){
    select_type();
    select_survey();
    select_survey_comparison();
    show_finalized_co_grid();
   // show_finalized_co_grid();
    
});

function select_type() {
	$.cookie('remember_section', $('#section option:selected').val(), {
		expires : 90,
		path : '/'
	});
	empty_all_divs();
	$('#occasion_div').css({
		"display" : "none"
	});
	var course_id = $('#course').val();
	$('#finalize_direct_indirect_div').hide();
	if (course_id) {
//		$('#type_data').html('<option value=0>Select Type</option><option value=2>' + entity_cie + '</option><option value=1>' + entity_tee + '</option><option value="cia_tee">Both ' + entity_cie + ' & ' + entity_tee + '</option>');
		$('#type_data').html('<option value=0>Select Type</option><option value=2>' + entity_cie+ '</option>');
		//$('#type_data_survey').html('<option value=0>Select Type</option><option value=2>' + entity_cie + '</option><option value=1>' + entity_tee + '</option><option value="cia_tee">Both ' + entity_cie + ' & ' + entity_tee + '</option>');
		$('#type_data_survey').html('<option value=0>Select Type</option><option value=2>' + entity_cie+ '</option>');
	} else {
		$('#occasion_div').css({
			"display" : "none"
		});
		$('#type_data').html('<option value=0>Select Type</option>');
		$('#type_data_survey').html('<option value=0>Select Type</option>');
	}
	select_survey();
	select_survey_comparison();
}

$('#add_form').on('change', '#type_data', function () {
	var type_data_id = $('#type_data').val();

	if (type_data_id == 1) {
		empty_direct_attainment_divs();
		$('#occasion_div').css({
			"display" : "none"
		});
		$('#actual_data_div').empty();
		var course = $('#course').val();
		var qpd_id = '';
		var type = 'tee';
		plot_graphs(course, qpd_id, type);
	} else if (type_data_id == 2) {
		empty_direct_attainment_divs();
		var crclm_id = $('#crclm_id').val();
		var term_id = $('#term').val();
		var crs_id = $('#course').val();
		var section_id = $('#section').val();
		var post_data1 = {
			'crclm_id' : crclm_id,
			'term_id' : term_id,
			'crs_id' : crs_id,
                        'section_id': section_id,
		}
		$.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_course_clo_attainment/select_occasion',
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
					$('#actual_data_div').html('<font color="red">Continuous Internal Assessment ('+ entity_cie +') Occasions are not defined</font>');
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
		plot_graphs(course, qpd_id, type);
	}
});

$('#add_form').on('change', '#occasion', function () {
	$('#actual_data_div').empty();
	var course = $('#course').val();
	var section_id = $('#section').val();
	var qpd_id = $('#occasion').val();
	var type = 'cia';
	plot_graphs(course, qpd_id, type, section_id);
});

function plot_graphs(course, qpd_id, type, section_id) {

	var post_data = {
		'course' : course,
		'qpd_id' : qpd_id,
		'type' : type,
                'section_id':section_id,
	}
	$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/tier1_course_clo_attainment/getCourseCOThreshholdAttainment',
		data : post_data,
		dataType : 'JSON',
		success : function (json_graph_data) {
			if (json_graph_data.length > 0) {
				var i = 0;
				value1 = new Array();
				value2 = new Array();
				value3 = new Array();
				value4 = new Array();
				threshold = new Array();
				clo_stmt = new Array();
				data1 = new Array();
				data2 = new Array();
				data3 = new Array();
				data4 = new Array();
				threshold_data = new Array();
				$.each(json_graph_data, function () {
					value3[i] = this['clo_code'];
					value1[i] = this['cloMaxMarks'];
					value2[i] = this['cloSecuredMarks'];
					value4[i] = this['Attainment'];
					clo_stmt[i] = this['clo_statement'];
					threshold[i] = this['cia_clo_minthreshhold'];
					data1.push(Number(value1[i]));
					data2.push(Number(value2[i]));
					data4.push(Number(Math.round(((this['cloSecuredMarks'] / this['cloMaxMarks']) * 100) * 100) / 100));
					data3.push(value3[i]);
					threshold_data.push(Number(threshold[i]));
					i++;
				});
				
				$('#co_level_nav').html('<div class="row-fluid"><div class="span6"></div><div class="span6 rightJustified" ><a id="exportToPDF" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a></div></div><br><div class="navbar"><div class="navbar-inner-custom">Course Outcomes(COs) Attainment</div></div>');
				$('#chart_plot_1').html('<div class=row-fluid><div class=span12><div class=span6><div id=chart1 style=position:relative; class=jqplot-target></div></div><div class=span6 style=overflow:auto; id="course_outcome_attainment_div"><table border=1 class="table table-bordered" id=co_level_table_id><thead></thead><tbody id="co_level_body"></tbody></table><div id="actual_course_attainment"></div></div><div id=docs class=span12></div></div></div>');
				$('#co_level_table_id > tbody:first').append('<tr><td><center><b>Course Outcomes</b></center></td><td><center><b>Max Marks</b></center></td><td style="white-space:no-wrap;"><center><b>Average of Secured Marks</b></center></td><td><center><b>Threshold %</b></center></td><td><center><b>Attainment %</b></center></td></tr>');
				$.each(json_graph_data, function () {
					$('#co_level_table_id > tbody:last').append('<tr><td><center>' + this['clo_code'] + '</center></td><td style="text-align: right;"  >' + this['cloMaxMarks'] + '</td><td style="text-align: right;"  >' + this['cloSecuredMarks'] + '</td><td style="text-align: right;">' + parseFloat(this['cia_clo_minthreshhold']).toFixed(2) + '%</td><td style="text-align: right;">' + parseFloat(this['Attainment']).toFixed(2) + '%</td></tr>');
				});

				//Compute sum of Max Marks
				var max_marks_sum = 0;
				for (var j = 0; j < data1.length; j++) {
					max_marks_sum += data1[j];
				}
				//Compute sum of Secured Marks
				var sec_marks_sum = 0;
				for (var j = 0; j < data2.length; j++) {
					sec_marks_sum += data2[j];
				}
				//Compute sum of attainment
				var attainment = 0;
				for (var j = 0; j < data4.length; j++) {
					attainment += data4[j];
				}
				attainment = attainment / data4.length;

				// Removed this last row from view as it was adding multiple COs marks (almost double)
				/* $('#co_level_table_id > tbody:last').append('<tr><td><center>TOTAL</center></td><td style="text-align: right;"  >'+max_marks_sum.toFixed(2)+'</td><td style="text-align: right;"  >'+sec_marks_sum.toFixed(2)+'</td><td><center></center></td></tr>'); */

				$('#actual_course_attainment').html('<b>Course Attainment: </b> ' + Math.round((sec_marks_sum * 100) / (max_marks_sum)) + '%');
				$('#docs').html('<br><div class="bs-docs-example"><b>Note:</b><table border=1 class="table table-bordered"><tbody><tr><td>The above bar graph depicts the individual COs planned marks distribution and average of secured marks distribution as in the question paper, and its respective attainment percentage is calculated using the below formula.</td></tr><tr><td>x = Average of secured marks <br/> y = Max marks <br/> For individual ' + entity_clo + ' attainment % = ( x / y ) x 100</td></tr></tbody></table></div>');

				var ticks = data3;
				var s1 = data1;
				var s2 = data2;

				var plot1 = $.jqplot('chart1', [threshold_data, data4], {
						seriesColors : ["#3efc70", "#4bb2c5"], // colors that will
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
								return clo_stmt[pointIndex];
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
								tickInterval:'10',
								tickOptions : {
									formatString : '%#.2f%'
								}
							}
						}
					});
			}
		}
	});

	//Threshold
	$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/tier1_course_clo_attainment/getCourseCOThreshholdAttainment',
		data : post_data,
		dataType : 'JSON',
		success : function (json_graph_data) {
			if (json_graph_data.length > 0) {
				var i = 0;
				value1 = new Array();
				value2 = new Array();
				value3 = new Array();
				value4 = new Array();

				data1 = new Array();
				data2 = new Array();
				data3 = new Array();
				data4 = new Array();

				$.each(json_graph_data, function () {
					value1[i] = this['clo_studentthreshhold'];
					value2[i] = this['PercentStudentAboveThreshhold'];
					value3[i] = this['cia_clo_minthreshhold'];
					value4[i] = this['clo_code'];

					data1.push(Number(value1[i]));
					data2.push(Number(value2[i]));
					data3.push(value3[i]);
					data4.push(value4[i]);
					i++;
				});
				$('#co_level_student_threshold_nav').html('<div class="row-fluid"><div class="span6"></div></div><br><div class="navbar"><div class="navbar-inner-custom">Course Outcomes(COs) wise Class Performance % for Students above or equal to % of Threshold (Target)</div></div>');
				$('#chart_plot_5').html('<div class=row-fluid><div class=span12><div class=span6><div id=chart5 style=position:relative; class=jqplot-target></div></div><div class=span6 style=overflow:auto; id="student_course_outcome_attainment_div"><table border=1 class="table table-bordered" id=co_level_student_threshold_nav_table_id><thead></thead><tbody id="co_level_body"></tbody></table><div id="student_actual_course_attainment"></div></div><div id="student_docs" class=span12></div></div></div>');
				$('#co_level_student_threshold_nav_table_id > tbody:first').append('<tr><td><center><b>Course Outcomes</b></center></td><td><center><b>% of Threshold</b></center></td><td><center><b>% of Student Threshold</b></center></td><td style="white-space:no-wrap;"><center><b>Students Attainment % </b></center></td></tr>');
				$.each(json_graph_data, function () {
					$('#co_level_student_threshold_nav_table_id > tbody:last').append('<tr><td><center>' + this['clo_code'] + '</center></td><td style="text-align: right;"  >' + this['cia_clo_minthreshhold'] + '%</td><td style="text-align: right;"  >' + this['clo_studentthreshhold'] + '%</td><td style="text-align: right;"  >' + this['PercentStudentAboveThreshhold'] + '%</td></tr>');
				});
				//$('#student_actual_course_attainment').html('');
				var max_marks_sum = 0;
				for (var j = 0; j < data1.length; j++) {
					max_marks_sum += data1[j];
				}
				//Compute sum of Secured Marks
				var sec_marks_sum = 0;
				for (var j = 0; j < data2.length; j++) {
					sec_marks_sum += data2[j];
				}
				//Compute sum of attainment
				var attainment = 0;
				for (var j = 0; j < data2.length; j++) {
					attainment += data2[j];
				}
				attainment = attainment / data2.length;

				// Removed this last row from view as it was adding multiple COs marks (almost double)
				/* $('#co_level_table_id > tbody:last').append('<tr><td><center>TOTAL</center></td><td style="text-align: right;"  >'+max_marks_sum.toFixed(2)+'</td><td style="text-align: right;"  >'+sec_marks_sum.toFixed(2)+'</td><td><center></center></td></tr>'); */

				$('#student_actual_course_attainment').html('<b>Course Attainment: </b> ' + (attainment).toFixed(2) + '%');
				$('#student_docs').html('<br><div class="span12"><table border="1" class="table table-bordered"><tbody><tr><td colspan="2"><b>Note:</b> The above bar graph depicts the overall class performance with respect to the Threshold % for individual Course Outcomes (COs). The Threshold based Attainment % & Average based Attainment % is calculated using the below formula.</td></tr><tr><td><b>For Threshold based Attainment % = ( x / y ) * 100 </b><br/> x = Count of Students &gt;= to Threshold % <br/> y = Total number of Students Attempted.</td><td><b>For Average based Attainment % = ( x / y ) * 100 </b> <br/> x = Average Secured marks of Attempted Students <br/> y = Maximum Marks. </td></tr></tbody></table></div>');

				var ticks = data4;
				var s1 = data1;
				var s2 = data2;

				var plot1 = $.jqplot('chart5', [data1, data2], {
						seriesColors : ["#3efc70", "#4bb2c5"], // colors that will
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
								return clo_stmt[pointIndex];
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
								tickInterval:'10',
								tickOptions : {
									formatString : '%#.2f%'
								}
							}
						}
					});
			}else{
			$('#student_actual_course_attainment').html('');
			}
		}
	});

	// Direct Attainment Tab : - Course Outcomes(COs) Contribution scripts starts here -------------------------------------------

	/*	$.ajax({type: "POST",
	url: base_url + 'assessment_attainment/tier1_course_clo_attainment/getCourseCOAttainment',
	data: post_data,
	dataType: 'JSON',
	success: function(json_graph_data) {
	if (json_graph_data.length > 0) {
	var i=0;
	var j=0;
	items1=new Array();
	items2=new Array();
	ticks = new Array();
	clo_stmt_arr = new Array();
	head = new Array();
	value = new Array();
	totalMarks = new Array();
	value1 = new Array();
	value2 = new Array();
	data1 = new Array();
	data2 = new Array();
	data3 = new Array();
	data4 = new Array();
	data5 = new Array();
	clo_stmt_data = new Array();
	$.each(json_graph_data, function() {

	ticks[i] = this['clo_code'];
	head[i] = this['MaxContribution'];
	value[i] = this['ActualContribution'];
	totalMarks[i] = this['totalMarks'];
	value1[i] = this['cloMaxMarks'];
	value2[i] = this['cloSecuredMarks'];
	clo_stmt_arr[i] = this['clo_statement'];
	data1.push(Number(value[i]));
	data2.push(Number(head[i]));
	data3.push(Number(value1[i]));
	data4.push(Number(value2[i]));
	data5.push(Number(totalMarks[i]));
	clo_stmt_data.push(clo_stmt_arr[i]);
	i++;
	});
	$('#course_attainment_nav').html('<div class="navbar"><div class="navbar-inner-custom">Course Outcomes(COs) Contribution</div></div>');
	$('#chart_plot_2').html('<div class=row-fluid><div class=span12><div class=span6><div id=chart2 style=position:relative; class=jqplot-target></div></div><div class=span6 style=overflow:auto; id="course_outcome_contribution_div"><table border=1 class="table table-bordered" id=course_attainment><thead></thead><tbody id="course_attainment_body"></tbody></table></div><div id=course_attainment_note class=span12></div></div></div>');
	$('#course_attainment > tbody:first').append('<tr><td style="white-space:no-wrap;"><center><b>Course Outcomes</b></center></td><td style="white-space:no-wrap;"><center><b>Total Marks</b></center></td><td style="white-space:no-wrap;"><center><b>Max Planned Marks</b></center></td><td style="white-space:no-wrap;"><center><b>Average of Secured Marks</b></center></td><td style="white-space:no-wrap;"><center><b>Planned Contribution %</b></center></td><td style="white-space:no-wrap;"><center><b>Actual Contribution %</b></center></td></tr>');
	$.each(json_graph_data, function(){
	$('#course_attainment > tbody:last').append('<tr><td><center>'+this['clo_code']+'</center></td><td style="text-align: right;"> '+this['totalMarks']+' </td><td style="text-align: right;"  >'+this['cloMaxMarks']+'</td><td style="text-align: right;"  >'+this['cloSecuredMarks']+'</td><td style="text-align: right;"> '+this['MaxContribution']+'% </td><td style="text-align: right;"> '+this['ActualContribution']+'% </td></tr>');
	});

	//Compute sum of Max Marks
	var max_marks_sum = 0;
	for(var j = 0; j < data5.length; j++){
	max_marks_sum = data5[j] ;
	}
	//Compute sum of Secured Marks
	var sec_marks_sum = 0;
	for(var j = 0; j < data4.length; j++){
	sec_marks_sum += data4[j] ;
	}

	// Removed this last row from view as it was adding multiple COs marks (almost double)

	/* $('#course_attainment > tbody:last').append('<tr><td><center>TOTAL</center></td><td><center></center></td><td style="text-align: right;"  >'+max_marks_sum.toFixed(2)+'</td><td style="text-align: right;"  >'+sec_marks_sum.toFixed(2)+'</td><td style="text-align: right;"><center></center></td><td><center></center></td></tr>'); */

	/*

	$('#course_attainment_note').html('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above bar graph depicts the individual COs planned percentage contribution and actual percentage contribution as in the question paper, and its respective contribution percentage is calculated using the below formula.</td></tr><tr><td>Planned Contribution % = (Max planned marks / Total marks ) x 100 </td></tr><tr><td>Actual Contribution % = (Average of secured marks / Total marks ) x 100  </td></tr></tbody></table></div>');
	$('#chart2').empty();
	var plot1 = $.jqplot('chart2', [data2,data1], {
	seriesDefaults:{
	renderer:$.jqplot.BarRenderer,
	rendererOptions: {barWidth:17, fillToZero: true},
	pointLabels: { show: true }
	},
	series:[
{label:'Planned Contribution %'},
{label:'Actual Contribution %'},

	],
	highlighter: {
	show: true,
	tooltipLocation: 'e',
	tooltipAxes: 'x',
	fadeTooltip	:true,
	showMarker:false,
	tooltipContentEditor:function (str, seriesIndex, pointIndex, plot){
	return clo_stmt_arr[pointIndex];
	}
	},
	legend: {
	show: true,
	placement: 'outsideGrid'
	},
	axes: {
	// Use a category axis on the x axis and use our custom ticks.
	xaxis: {
	renderer: $.jqplot.CategoryAxisRenderer,
	tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
	ticks: ticks
	},
	// Pad the y axis just a little so bars can get close to, but
	// not touch, the grid boundaries.  1.2 is the default padding.
	yaxis: {
	padMin: 0,
	min:0,
	max:100,
	tickOptions: {formatString: '%.2f%'}
	}
	}
	});
	}
	}
	});	 		*/
	// Direct Attainment Tab : - Course Outcomes(COs) Contribution scripts ends here ------------------------------------

	// Direct Attainment Tab : - Blooms Level Distribution scripts starts here -------------------------------------------
	$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/tier1_course_clo_attainment/CourseBloomsLevelThresholdAttainment',
		data : post_data,
		dataType : 'JSON',
		success : function (json_graph_data) {
			if (json_graph_data.length > 0) {
				var i = 0;
				value1 = new Array();
				value2 = new Array();
				value3 = new Array();
				value4 = new Array();
				value5 = new Array();
				value6 = new Array();
				bloom_stmt = new Array();
				bloom_stmt_data = new Array();
				data1 = new Array();
				data2 = new Array();
				data3 = new Array();
				data4 = new Array();
				data5 = new Array();
				$.each(json_graph_data, function () {
					value3[i] = this['level'];
					value1[i] = this['bloomlevel_studentthreshhold'];
					value6[i] = this['cia_bloomlevel_minthreshhold'];
					value2[i] = this['PercentStudentAboveThreshhold'];
					value4[i] = Number(this['blMaxMarks']);
					value5[i] = Number(this['blSecuredMarks']);
					bloom_stmt[i] = this['description'];
					data1.push(Number(value1[i]));
					data5.push(Number(value6[i]));
					data2.push(Number(value2[i]));
					data3.push(value3[i]);
					data4.push(value4[i]);
					bloom_stmt_data.push(value3[i] + "-" + bloom_stmt[i]);
					i++;
				});
				$('#bloom_level_nav').html('<div class="span12"><table border="1" class="table table-bordered"><tbody><tr><td colspan="2"><b>Note:</b> The above bar graph depicts the overall class performance with respect to the Threshold % for individual Course Outcomes (COs). The Threshold based Attainment % & Average based Attainment % is calculated using the below formula.</td></tr><tr><td><b>For Threshold based Attainment % = ( x / y ) * 100 </b><br/> x = Count of Students &gt;= to Threshold % <br/> y = Total number of Students Attempted.</td><td><b>For Average based Attainment % = ( x / y ) * 100 </b> <br/> x = Average Secured marks of Attempted Students <br/> y = Maximum Marks. </td></tr></tbody></table></div>');
				//Compute sum of Max Marks
				var max_marks_sum = 0;
				for (var j = 0; j < data4.length; j++) {
					max_marks_sum += data4[j];
				}

				max_marks_total = new Array();
				secured_marks_total = new Array();
				//Compute cumulative sum max for levels
				for (var i = 0; i < value4.length; i++) {
					var j = i;
					max_marks_total[i] = 0;
					for (var k = 0; k <= j; k++) {
						max_marks_total[i] += value4[k];
					}
				}

				//Compute cumulative sum secured for levels
				for (var i = 0; i < value5.length; i++) {
					var j = i;
					secured_marks_total[i] = 0;
					for (var k = 0; k <= j; k++) {
						secured_marks_total[i] += value5[k];
					}
				}
				var ticks = data3;
				var s1 = data1;
				var s2 = data2;
				$('#chart3').empty();
				var plot1 = $.jqplot('chart3', [s1, s2], {
						seriesColors : ["#3efc70", "#4bb2c5"], // colors that will
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
								label : 'Min Students Threshold %'
							}, {
								label : 'Students >= Threshold %'
							}
						],
						highlighter : {
							show : true,
							tooltipLocation : 'e',
							tooltipAxes : 'x',
							fadeTooltip : true,
							showMarker : false,
							tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
								return bloom_stmt_data[pointIndex];
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
								pad : 1.05,
								min : 0,
								max : 100,
								tickInterval:'10',
								tickOptions : {
									formatString : '%.2f%'
								}
							}
						}
					});
				$('#bloom_level > tbody:first').append("<tr><td><center><b>Bloom's Level</b></center></td><td><center><b>Max Marks</b></center></td><td><center><b>Average of Secured Marks</b></center></td><td><center><b>Min Attainmnet Threshold % </b></center></td><td><center><b>Min Student Threshold </b></center></td><td><center><b>Students >= Min Threshold </b></center></td><td><center> <b>Bloom\'s Level Attainment %</b></center></td></tr>");
				var m = 0;
				$.each(json_graph_data, function () {
					var secured_marks = secured_marks_total[m];
					secured_marks = secured_marks.toFixed(2);
					if(value4[m] != 0 ){bloom_attainment = (value5[m] / value4[m]) * 100;} else { bloom_attainment = 0;}
					$('#bloom_level > tbody:last').append('<tr><td><center>' + this['level'] + '</center></td><td style="text-align: right;"> ' + value4[m] + ' </td><td style="text-align: right;"> ' + value5[m] + ' </td><td style="text-align: right;"> ' + data5[m] + '% </td><td style="text-align: right;"> ' + data1[m] + '% </td><td style="text-align: right;"> ' + data2[m].toFixed(2) + '% </td><td style="text-align: right;">' + bloom_attainment.toFixed(2) + ' </td></tr>');
					m++;
				});
				$('#bloom_level_note').html('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above bar graph depicts the overall class performance with respect to the Min Threshold % for individual Bloom\'s Level and the Students >= Min Threshold % (Target) for individual Bloom\'s Level as in the question paper. The Students >= Min Threshold % is calculated using the below formula.</td></tr><tr><td>x = Count of Students greater than or equal to Min Attainment Threshold % <br/> y =  Total number of Students Attempted <br/> For Students >= Min Threshold % = ( x / y ) x 100</td></tr></tbody></table></div>');
			}
		}
	});

	// Direct Attainment Tab : - Blooms Level Distribution scripts ends here -------------------------------------------

	// Direct Attainment Tab : - Blooms Level - Cumulative Performance scripts starts here -----------------------------------

	$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/tier1_course_clo_attainment/CourseBloomsLevelAttainment',
		data : post_data,
		dataType : 'JSON',
		success : function (json_graph_data) {
			if (json_graph_data.length > 0) {
				var i = 0;
				value3 = new Array();
				value4 = new Array();
				value5 = new Array();
				data1 = new Array();
				data2 = new Array();
				data3 = new Array();
				$.each(json_graph_data, function () {
					value3[i] = this['level'];
					value4[i] = Number(this['MaxContribution']);
					value5[i] = Number(this['ActualContribution']);
					data1.push(Number(this['MaxMarks']));
					data2.push(Number(this['SecuredMarks']));
					data3.push(value3[i]);
					i++;
				});
				$('#cumulative_performance_nav').html("<div class='navbar'><div class='navbar-inner-custom'>Bloom's Level - Cumulative Performance</div></div>");
				$('#chart_plot_4').html('<div class=row-fluid><div class=span12><div class=span6><div id=chart6 style=position:relative; class=jqplot-target></div></div><div class=span6 style=overflow:auto; id="bloom_level_cumm_perf_div"><table border=1 class="table table-bordered" id=cumulative_performance><thead></thead><tbody id="cumulative_performance_body"></tbody></table></div><div id=cumulative_performance_note class=span12></div></div></div>');
				max_marks_total = new Array();
				secured_marks_total = new Array();
				//Compute cumulative sum max for levels
				for (var i = 0; i < data1.length; i++) {
					var j = i;
					max_marks_total[i] = 0;
					for (var k = 0; k <= j; k++) {
						max_marks_total[i] += data1[k];
					}
				}
				//Compute cumulative sum secured for levels
				for (var i = 0; i < data2.length; i++) {
					var j = i;
					secured_marks_total[i] = 0;
					for (var k = 0; k <= j; k++) {
						secured_marks_total[i] += data2[k];
					}
				}
				item1 = new Array();
				l1 = new Array();
				for (var i = 0; i < data3.length; i++) {
					l1 = [];
					l1.push(data3[i], Number(max_marks_total[i]));
					item1[i] = l1;
				}
				item2 = new Array();
				l2 = new Array();
				for (var i = 0; i < data3.length; i++) {
					l2 = new Array();
					l2.push(data3[i], Number(secured_marks_total[i]));
					item2[i] = l2;
				}
				//Compute sum of Max Marks
				var max_marks_sum = 0;
				for (var j = 0; j < data1.length; j++) {
					max_marks_sum += data1[j];
				}
				//Compute sum of Secured Marks
				var secured_marks_sum = 0;
				for (var k = 0; k < data2.length; k++) {
					secured_marks_sum += data2[k];
				}
				//Compute sum of Max distribution
				var sum_max_marks_distr = 0;
				for (var l = 0; l < value4.length; l++) {
					sum_max_marks_distr += value4[l];
				}
				//Compute sum of Max distribution
				var sum_secured_marks_distr = 0;
				for (var m = 0; m < value5.length; m++) {
					sum_secured_marks_distr += value5[m];
				}
				$('#chart6').empty();
				var plot2 = $.jqplot('chart6', [item1, item2], {
						seriesDefaults : {
							markerOptions : {
								show : false
							},
						},
						axesDefaults : {
							labelRenderer : $.jqplot.CanvasAxisLabelRenderer
						},
						series : [{
								label : 'Cumulative Max Marks'
							}, {
								label : 'Cumulative Actual Marks'
							}
						],

						legend : {
							show : true,
							placement : 'outsideGrid'
						},
						axes : {
							xaxis : {
								renderer : $.jqplot.CategoryAxisRenderer,
								tickRenderer : $.jqplot.CanvasAxisTickRenderer
							},
							yaxis : {
								min : 0
							}
						}

					});

				$('#cumulative_performance > tbody:first').append("<tr><td><center><b>Bloom's Level</b></center></td><td><center><b>Max Planned Marks</b></center></td><td><center><b>Average of Secured Marks</b></center></td><td><center><b>Cumulative Sum(Planned marks)</b></center></td><td><center><b>Cumulative Sum(Secured marks)</b></center></td><td><center><b>Planned Max Marks Distribution</b></center></td><td><center><b>Secured Marks Distribution</b></center></td></tr>");
				var m = 0;
				$.each(json_graph_data, function () {
					var secured_marks = secured_marks_total[m];
					secured_marks = secured_marks.toFixed(2);
					$('#cumulative_performance > tbody:last').append('<tr><td><center>' + this['level'] + '</center></td><td style="text-align: right;"  >' + data1[m] + '</td><td style="text-align: right;"  >' + data2[m] + '</td><td style="text-align: right;"> ' + max_marks_total[m] + ' </td><td style="text-align: right;"> ' + secured_marks + ' <td style="text-align: right;"> ' + value4[m] + '% <td style="text-align: right;"> ' + value5[m] + '% </tr>');
					m++;
				});
				secured_marks_sum = secured_marks_sum.toFixed(2);
				$('#cumulative_performance > tbody:last').append('<tr><td><center>TOTAL</center></td><td style="text-align: right;"  >' + max_marks_sum + '</td><td style="text-align: right;"  >' + secured_marks_sum + '</td><td style="text-align: right;">  </td><td style="text-align: right;">  </td><td style="text-align: right;">  </td><td style="text-align: right;">  </td></tr>');
				$('#cumulative_performance_note').html("<br><div class='bs-docs-example'><b>Note:</b><table class='table table-bordered'><tbody><tr><td>The above run chart depicts the cumulative sum of  Bloom's Level planned marks distribution and cumulative sum of Bloom's Level actual marks distribution as in the question paper .</td></tr><tr><td>x = Max planned marks <br/> y = Total marks <br/> Planned Contribution % = ( x / y ) x 100 </td></tr><tr><td>x = Average of secured marks <br/> y = Total marks <br/> Actual Contribution % = ( x / y ) x 100  </td></tr></tbody></table></div>");
			}
		}
	});

	// Direct Attainment Tab : - Blooms Level - Cumulative Performance scripts ends here ----------------------------------

	// Direct Attainment Tab : - Course Outcome (CO) contribution to Program Outcome (PO) within the Course scripts starts here ------------
	//POCOAttainment
	/*	var po;
	$.ajax({type: "POST",
	url: base_url + 'assessment_attainment/tier1_course_clo_attainment/CoursePOCOAttainment',
	data: post_data,
	dataType: 'JSON',
	success: function(json_graph_data) {
	$('#CLO_PO_attainment_nav').empty();
	$('#CLO_PO_attainment_nav').append('<div class="navbar"><div class="navbar-inner-custom">Course Outcome (CO) contribution to Program Outcome (PO) within the Course</div></div>');
	$('#chart_plot').empty();
	var new_data = new Array();
	co_po_attainment_total = json_graph_data.graph_data.length;
	for(var i = 0; i < json_graph_data.graph_data.length; i++){
	var length = json_graph_data.graph_data[i].length;
	var graph_divs = '<div class="row-fluid crs_co_po_attainment"><div class="span12"><div id="main_div_'+i+'"></div>';
	graph_divs+= 	'<div class="span12">';
	graph_divs+=		'<div class="span6"> <div id="chart_'+i+'" class="getDivId"></div> </div>';
	graph_divs+= 		'<div class="span6" style="overflow:auto;" id="co_po_attainment_'+i+'_div"> <div id="table_'+i+'" ><table id="CLO_PO_attainment'+i+'" border=1 class="table table-bordered "><thead></thead><tbody></tbody></table></div></div></div></div><hr>';
	graph_divs+= '<div class="row-fluid"></div></div>';
	var graphs = $(graph_divs);
	$('#chart_plot').append(graphs);
	data1 = new Array();
	data2 = new Array();
	data3 = new Array();
	po_no_data = new Array();
	actualContribution = new Array();
	PlannedContribution = new Array();
	clo_stmt_data = new Array();
	clo_data = new Array();
	$('#CLO_PO_attainment'+i+' > thead:first').append('<tr><td colspan=5><center><b>Program Outcome - '+json_graph_data.po_reference[i]+'</b></center></td></tr>');
	$('#CLO_PO_attainment'+i+' > tbody:first').append('<tr><td style="white-space:no-wrap;"><center><b>Course Outcomes</b></center></td><td style="white-space:no-wrap;"><center><b>Average of Secured Marks</b></center></td><td style="white-space:no-wrap;"><center><b>Max Marks</b></center></td><td style="white-space:no-wrap;"><center><b>Planned Contribution </b></center></td><td style="white-space:no-wrap;"><center><b>Actual Contribution </b></center></td></tr>');
	var k=0;
	for(var j=0; j< length ; j++)
{
	var cloMaxMarks_val = json_graph_data.graph_data[i][j].cloMaxMarks;
	var cloSecuredMarks_val = json_graph_data.graph_data[i][j].cloSecuredMarks;
	var clo_code_val = json_graph_data.graph_data[i][j].clo_code;
	var ActualContribution_val = json_graph_data.graph_data[i][j].ActualContribution;
	var MaxContribution_val = json_graph_data.graph_data[i][j].MaxContribution;
	var clo_data = json_graph_data.graph_data[i][j].clo_statement;
	data1.push(Number(cloMaxMarks_val));
	data2.push(Number(cloSecuredMarks_val));
	data3.push(clo_code_val);
	actualContribution.push(Number(ActualContribution_val));
	PlannedContribution.push(Number(MaxContribution_val));
	clo_stmt_data.push(clo_data);
	$('#CLO_PO_attainment'+i+' > tbody:last').append('<tr><td><center> '+clo_code_val+' </center></td><td style="text-align: right;"> '+cloSecuredMarks_val+' </td><td style="text-align: right;"> '+cloMaxMarks_val+' </td><td style="text-align: right;"> '+MaxContribution_val+'% </td><td style="text-align: right;"> '+ActualContribution_val+'% </td></tr>');
	}
	var ticks = data3;
	var s1 = data1;
	var s2 = data2;
	s1_count=0;
	s2_count=0;
	for (var u = 0; u < s1.length; u++) {
	if (s1[u] == 0) {
	s1_count++;
	}
	}
	for (var v = 0; v < s2.length; v++) {
	if (s2[v] == 0) {
	s2_count++;
	}
	}
	if((s1.length == s1_count) && (s2.length == s2_count)){
	$('#table_'+i).empty();
	$('#chart_'+i).empty();
	$('#main_div_'+i).html('<table class="table table-bordered"><tr><td><center class="error_color_maroon"><b>No data available for Program Outcome - '+json_graph_data.po_reference[i]+'</b></center></td></tr></table>');

	}else{
	var plot1 = $.jqplot('chart_'+i, [s1, s2], {
	seriesDefaults:{
	renderer:$.jqplot.BarRenderer,
	rendererOptions: {barWidth:20, fillToZero: true},
	pointLabels: { show: true }
	},
	series:[
{label:'Max Marks'},
{label:'Secured Marks'}
	],
	legend: {
	show: true,
	placement: 'outsideGrid'
	},
	axes: {
	xaxis: {
	renderer: $.jqplot.CategoryAxisRenderer,
	tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
	ticks: ticks
	},
	yaxis: {
	pad: 1.05,
	min:0,
	max:100,
	tickOptions: {formatString: '%d'},
	tickOptions:{
	formatString: "%.2f"
	}
	}
	}
	});
	}
	}
	$('#CLO_PO_attainment_note').html('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The below bar graphs depicts the individual Program Outcome(POs)wise planned marks distribution and cumulative sum of Blooms Level actual marks distribution as in the question paper .</td></tr><tr><td>Planned Contribution % = (Max planned marks / Total marks ) x 100 </td></tr><tr><td>Actual Contribution % = (Average of secured marks / Total marks ) x 100</td></tr></tbody></table></div><br>');
	}
	}); */
	// Direct Attainment Tab : - Course Outcome (CO) contribution to Program Outcome (PO) within the Course scripts starts here ------------

}

$('#add_form').on('click', '#exportToPDF', function () {
	var dept_name = $('#dept_id :selected').text();
	var pgm_name = $('#pgm_id :selected').text();
	var crclm_name = $('#crclm_id :selected').text();
	var term_name = $('#term :selected').text();
	var course_name = $('#course :selected').text();

	var course_attainment_graph = $('#chart1').jqplotToImageStr({});
	var course_attainment_graph_image = $('<img/>').attr('src', course_attainment_graph);
	$('#course_outcome_attainment_graph_data').html('<table><tr><td><b>Curriculum :</b></td><td>' + crclm_name + '</td></tr><tr><td><b>Term :</b></td><td>' + term_name + '</td></tr><td><b>Course :</b></td><td>' + course_name + '</td></tr></table><br><b>Course Outcomes(COs) Attainment</b><br />');
	$('#course_outcome_attainment_graph_data').append(course_attainment_graph_image);
	$('#course_outcome_attainment_graph_data').append($('#course_outcome_attainment_div').clone().html());
	$('#course_outcome_attainment_graph_data').append($('#docs').clone().html());
	var course_outcome_attainment_pdf = $('#course_outcome_attainment_graph_data').clone().html();
	$('#course_outcome_attainment_graph_data_hidden').val(course_outcome_attainment_pdf);

	// CO contribution graph data
	var course_contribution_graph = $('#chart5').jqplotToImageStr({});
	var course_contribution_graph_image = $('<img/>').attr('src', course_contribution_graph);
	$('#course_outcome_contribution_graph_data').html('<b>Class Performance % for Students above or equal to Min Threshold (Target)</b><br />');
	$('#course_outcome_contribution_graph_data').append(course_contribution_graph_image);
	$('#course_outcome_contribution_graph_data').append($('#student_course_outcome_attainment_div').clone().html());
	$('#course_outcome_contribution_graph_data').append($('#student_docs').clone().html());
	var course_contribution_pdf = $('#course_outcome_contribution_graph_data').clone().html();
	$('#co_contribution_graph_data_hidden').val(course_contribution_pdf);

	// Bloom Level distribution graph data
	var bloom_level_distribution_graph = $('#chart3').jqplotToImageStr({});
	var bloom_level_distribution_graph_image = $('<img/>').attr('src', bloom_level_distribution_graph);
	$('#bloom_level_distribution_graph_data').html('<b>Bloom\'s Level-wise Class Performance % for Students above or equal to Min Threshold (Target)</b><br />');
	$('#bloom_level_distribution_graph_data').append(bloom_level_distribution_graph_image);
	$('#bloom_level_distribution_graph_data').append($('#bloom_level_distribution_div').clone().html());
	$('#bloom_level_distribution_graph_data').append($('#bloom_level_note').clone().html());
	var bloom_level_distribution_pdf = $('#bloom_level_distribution_graph_data').clone().html();
	$('#bloom_level_distribution_hidden').val(bloom_level_distribution_pdf);

	// Bloom Level Cumulative graph data
	var bloom_level_cumm_perf_graph = $('#chart6').jqplotToImageStr({});
	var bloom_level_cumm_perf_graph_image = $('<img/>').attr('src', bloom_level_cumm_perf_graph);
	$('#bloom_level_cumm_perf_graph_data').html('<b>Bloom\'s Level - wise Cumulative Performance</b><br />');
	$('#bloom_level_cumm_perf_graph_data').append(bloom_level_cumm_perf_graph_image);
	$('#bloom_level_cumm_perf_graph_data').append($('#bloom_level_cumm_perf_div').clone().html());
	$('#bloom_level_cumm_perf_graph_data').append($('#cumulative_performance_note').clone().html());
	var bloom_level_cumm_perf_graph_pdf = $('#bloom_level_cumm_perf_graph_data').clone().html();
	$('#bloom_level_cumm_perf_graph_hidden').val(bloom_level_cumm_perf_graph_pdf);

	// CO - PO mapping contribution graph data
	/* $('#co_po_graph_data').html('<b>Course Outcome (CO) contribution to Program Outcome (PO) within the Course</b>');
	for(n=0; n < co_po_attainment_total; n++) {
	var co_po_graph = $('#chart_'+n).jqplotToImageStr({});
	if(!co_po_graph) {
	$('#co_po_graph_data').append($('#main_div_'+n).clone().html());
	} else {
	var co_po_graph_image = $('<img/>').attr('src',co_po_graph);
	$('#co_po_graph_data').append(co_po_graph_image);
	$('#co_po_graph_data').append($('#co_po_attainment_'+n+'_div').clone().html());
	}
	}
	$('#co_po_graph_data').append($('#CLO_PO_attainment_note').clone().html());
	var co_po_graph_data_pdf = $('#co_po_graph_data').clone().html();
	$('#co_po_graph_hidden').val(co_po_graph_data_pdf); */

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
		expires : 90,
		path : '/'
	});
	empty_all_divs();
	var pgm_list_path = base_url + 'assessment_attainment/tier1_course_clo_attainment/select_pgm';

	var data_val = $('#dept_id_indirect').val();
	var post_data = {
		'dept_id' : data_val
	}
	$.ajax({
		type : "POST",
		url : pgm_list_path,
		data : post_data,
		success : function (msg) {			
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
		expires : 90,
		path : '/'
	});
	var crclm_list_path = base_url + 'assessment_attainment/tier1_course_clo_attainment/select_crclm';
	var data_val = $('#pgm_id_indirect').val();
	var post_data = {
		'pgm_id' : data_val
	}
	$.ajax({
		type : "POST",
		url : crclm_list_path,
		data : post_data,
		success : function (msg) {
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
		expires : 90,
		path : '/'
	});
	empty_all_divs();
	var term_list_path = base_url + 'assessment_attainment/tier1_course_clo_attainment/select_term';
	var data_val = $('#crclm_id_indirect').val();
	var post_data = {
		'crclm_id' : data_val
	}

	$.ajax({
		type : "POST",
		url : term_list_path,
		data : post_data,
		success : function (msg) {
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
		expires : 90,
		path : '/'
	});
	empty_all_divs();
	var course_list_path = base_url + 'assessment_attainment/tier1_course_clo_attainment/select_course';
	var data_val = $('#term_indirect').val();
	if (data_val) {
		var post_data = {
			'term_id' : data_val
		}
		$.ajax({
			type : "POST",
			url : course_list_path,
			data : post_data,
			success : function (msg) {
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
		expires : 90,
		path : '/'
	});
	empty_indirect_attainment_divs();
	var course_list_path = base_url + 'assessment_attainment/tier1_course_clo_attainment/select_survey';
	var data_val = $('#course').val();
	if (data_val) {
		var post_data = {
			'course' : data_val
		}
		$.ajax({
			type : "POST",
			url : course_list_path,
			data : post_data,
			success : function (msg) {
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
	var crs_id = $('#course').val();
	if (survey_id != '' || survey_id != 0 ) {
		plot_indirect_attainment_graph(survey_id, crs_id);
	} else {
		var survey_link = base_url +'survey/surveys';
				$('#graph_val').html("<br/><center><span style='color:red;'>There are no Surveys found under this Course. Create the survey to view Course Outcomes (COs) Indirect Attainment.<br\> Use this link to create the survey : <a href="+survey_link+"> </span>survey create link</a></center>");
	}
});
function plot_indirect_attainment_graph(survey_id, crs_id) {
	var post_data = {
		'survey_id' : survey_id,
		'crs_id' : crs_id,
	};
	$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/tier1_course_clo_attainment/get_indirect_attainment_data',
		data : post_data,
		success : function (attainment_data) {
			$('#co_level_indirect_nav').html('<div class="row-fluid"><div class="span6"></div><div class="span6 rightJustified" ><a id="exportToPDF" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a></div></div><br><div class="navbar"><div class="navbar-inner-custom">Course Outcomes(COs) Indirect Attainment</div></div>');
			$('#chart_plot_indirect_attain').html('<div class=row-fluid><div class=span12><div id=indirect_attain_chart_shv style=position:relative; class=jqplot-target></div></div></div>');
			var data = jQuery.parseJSON(attainment_data);
			if(data.length>0){
				var xaxis = new Array();
				var yaxis = new Array();
				
				var i = 0;
				var table = '<table class="table table-bordered"><thead><tr><th>Sl No.</th><th>CO Code - Course Outcome (CO) Statement</th><th>Attainment %</th></tr></thead>';
				table += '<tbody>';
				$.each(data, function () {
					console.log(data[i].clo_statement);
					xaxis.push(data[i].clo_code);
					yaxis.push(Number(data[i].ia_percentage));
					table += '<tr>';
					table += '<td>' + (i + 1) + '</td>';
					table += '<td>' + data[i].clo_code + ' - ' + data[i].clo_statement + '</td>';
					table += '<td>' + data[i].ia_percentage + '% </td>';
					table += '</tr>';
					i++;
				});
				table += '</tbody></table>';
				
				var ticks = xaxis;
						var s1 = yaxis;
						//var s2 = data2;
						var plot1 = $.jqplot('indirect_attain_chart_shv', [s1], {
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
										label : ' CO Attainment %'
									},
								],
								highlighter : {
									show : true,
									tooltipLocation : 'e', // location of tooltip: n, ne, e, se, s, sw, w, nw.
									tooltipAxes : 'x', // which axis values to display in the tooltip, x, y or both.
									showMarker : false,
									tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
										return xaxis[pointIndex];
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
										tickInterval:'10',
										tickOptions : {
											formatString : '%.2f%'
										}
									}
								}

							});
				//table data
				$('#graph_val').html(table);
			} else{
			var survey_link = base_url +'survey/host_survey';
				$('#graph_val').html("<center><span style='color:red;'>The selected survey is still <b>In-Progress</b> status. Change the survey status to <b>Closed</b> to view Course Outcomes (COs) Indirect Attainment.<br\> Use this link to close the hosted survey : <a href="+survey_link+"> </span>survey close link</a></center>");
			}
		} //end of success
	});
}

function plot_graphs_indirect_attainment(survey_id) {

	var post_data = {
		'report_type_val' : 3, //default:3
		'su_for' : 8,
		'survey_id' : survey_id,
	}
	var actual_data = [];
	var co_level_indirect_nav = [];
	var dept_name = [];
	var su_for_label = 'CO';
	var i = 1;
	$.ajax({
		type : "POST",
		url : base_url + 'survey/surveys/getSurveyQuestions',
		data : post_data,
		dataType : 'html',
		success : function (survey_data) {
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
				type : 'POST',
				url : base_url + 'survey/surveys/getSurveyQuestionsForGraph',
				data : post_data,
				dataType : 'html',
				success : function (survey_data_gp) {
					var i = 0;
					var data = jQuery.parseJSON(survey_data_gp);
					var data1 = new Array();
					var data2 = new Array();

					$.each(data, function () {
						data1.push(data[i].co_code);
						data2.push(Number(data[i].Attaintment));
						i++;

					});
					$('#co_level_indirect_nav').html('<div class="row-fluid"><div class="span6"></div><div class="span6 rightJustified" ><a id="exportToPDF" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a></div></div><br><div class="navbar"><div class="navbar-inner-custom">Course Outcomes(COs) Indirect Attainment</div></div>');
					$('#chart_plot_indirect_attain').html('<div class=row-fluid><div class=span12><div id=indirect_attain_chart_shv style=position:relative; class=jqplot-target></div></div></div>');

					var ticks = data1;
					var s1 = data2;
					//var s2 = data2;
					var plot1 = $.jqplot('indirect_attain_chart_shv', [s1], {
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
									label : ' CO Attainment %'
								},
							],
							highlighter : {
								show : true,
								tooltipLocation : 'e', // location of tooltip: n, ne, e, se, s, sw, w, nw.
								tooltipAxes : 'x', // which axis values to display in the tooltip, x, y or both.
								showMarker : false,
								tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
									return data1[pointIndex];
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
									tickInterval:'10',
									tickOptions : {
										formatString : '%.2f%'
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
		expires : 90,
		path : '/'
	});
	empty_all_divs();
	var pgm_list_path = base_url + 'assessment_attainment/tier1_course_clo_attainment/select_pgm';

	var data_val = $('#dept_id_comparison').val();
	var post_data = {
		'dept_id' : data_val
	}
	$.ajax({
		type : "POST",
		url : pgm_list_path,
		data : post_data,
		success : function (msg) {

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
		expires : 90,
		path : '/'
	});
	var crclm_list_path = base_url + 'assessment_attainment/tier1_course_clo_attainment/select_crclm';
	var data_val = $('#pgm_id_comparison').val();
	var post_data = {
		'pgm_id' : data_val
	}
	$.ajax({
		type : "POST",
		url : crclm_list_path,
		data : post_data,
		success : function (msg) {
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
		expires : 90,
		path : '/'
	});
	empty_all_divs();
	var term_list_path = base_url + 'assessment_attainment/tier1_course_clo_attainment/select_term';
	var data_val = $('#crclm_id_comparison').val();
	var post_data = {
		'crclm_id' : data_val
	}

	$.ajax({
		type : "POST",
		url : term_list_path,
		data : post_data,
		success : function (msg) {
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
		expires : 90,
		path : '/'
	});
	empty_all_divs();
	var course_list_path = base_url + 'assessment_attainment/tier1_course_clo_attainment/select_course';
	var data_val = $('#term_comparison').val();
	if (data_val) {
		var post_data = {
			'term_id' : data_val
		}
		$.ajax({
			type : "POST",
			url : course_list_path,
			data : post_data,
			success : function (msg) {
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
	show_finalized_co_grid();
	$.cookie('remember_course_comparison', $('#course_comparison option:selected').val(), {
		expires : 90,
		path : '/'
	});
	empty_direct_indirect_attainment_divs();
	$('#direct_indirect_submit').attr('disabled', true);
	var course_list_path = base_url + 'assessment_attainment/tier1_course_clo_attainment/select_survey';
	var data_val = $('#course').val();
	var clo_attain_type = $('#clo_attainment_type').val();
	if (data_val) {
		var post_data = {
			'course' : data_val
		}
		$.ajax({
			type : "POST",
			url : course_list_path,
			data : post_data,
			success : function (msg) {
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

var course_list_path = base_url + 'assessment_attainment/tier1_course_clo_attainment/getDirectIndirectCOAttaintmentData';

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
		empty_direct_indirect_attainment_divs();
		$('#survey_occasion_div').css({
			"display" : "none"
		});
		$('#survey_occasion_actual_data_div').empty();
		var course = $('#course').val();
	} else if (type_data_id == 2) {
		empty_direct_indirect_attainment_divs();
		var crclm_id = $('#crclm_id').val();
		var term_id = $('#term').val();
		var crs_id = $('#course').val();
		var section_id = $('#section').val();
		$('#survey_occasion_actual_data_div').empty();
		var post_data1 = {
			'crclm_id' : crclm_id,
			'term_id' : term_id,
			'crs_id' : crs_id,
                        'section_id':section_id,
		}
		$.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_course_clo_attainment/select_occasion',
			data : post_data1,
			success : function (occasionList) {
				if (occasionList != 0) {
					$('#survey_occasion_div').css({
						"display" : "inline"
					});
					$('#survey_occasion').html(occasionList);
				} else {
					$('#survey_occasion_div').css({
						"display" : "none"
					});
					$('#survey_occasion_actual_data_div').html('<font color="red">Continuous Internal Assessment ('+ entity_cie +') Occasions are not defined</font>');
				}
			}
		});
	} else if (type_data_id == 'cia_tee') {
		empty_direct_indirect_attainment_divs();
		$('#survey_occasion_div').css({
			"display" : "none"
		});
		$('#survey_occasion_actual_data_div').empty();
		var course = $('#course').val();
	} else {
		empty_direct_indirect_attainment_divs();
		$('#survey_occasion_div').css({
			"display" : "none"
		});
		$('#survey_occasion_actual_data_div').empty();

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
    console.log(post_data);
    $.ajax({
        type: "POST",
        url: base_url + 'assessment_attainment/tier1_course_clo_attainment/get_finalized_co_attainment_data',
        data: post_data,
        dataType: 'HTML',
        success: function (co_po_data) {
            var co_po_data = $.parseJSON(co_po_data);
			var po_data = co_po_data['po_result'];
			var co_data = co_po_data['co_result'];			
			
            $('#co_attain_data_finalized_div').html("<div class='navbar'><div class='navbar-inner-custom'>Overall "+course_outcome+" Attainment finalized (Direct & Indirect Attainment)</div></div>");
            if (co_data.length > 0) {
                var table = '';
                table += '<table class="table table-bordered">';
                table += '<thead><tr><!--<th>Sl No.</th>--><th><center>'+course_outcome+'</center></th><th><center>Direct Weightage %</center></th><th><center>Direct Attainment %</center></th><th><center>Indirect Weightage %</center></th><th><center>Indirect Attainment %</center></th><th><center>Overall Attainment %</center></th></tr></thead>';
                var i = 0;var attainment = 0;
                table += '<tbody>';
                $.each(co_data, function () {
                    table += '<tr>';
                    // table +='<td><center>'+(i+1)+'</center></td>';
                    table += '<td><center title="' + co_data[i].clo_statement + '">' + co_data[i].clo_code + '</center></td>';
                    table += '<td><center>' + co_data[i].da_weightage + ' %</center></td>';
                    table += '<td><center>' + co_data[i].da_percentage + ' %</center></td>';
                    table += '<td><center>' + co_data[i].ia_weightage + ' %</center></td>';
                    table += '<td><center>' + co_data[i].ia_percentage + ' %</center></td>';
                    table += '<td><center>' + co_data[i].overall_attainment + ' %</center></td>';
                    table += '</tr>';
                    
				
					attainment = attainment + parseFloat(co_data[i].overall_attainment);
					len = (parseInt(co_data[i].overall_attainment.length)+1);
					i++;
                });	
				attainment = (attainment / len ).toFixed(2);			
                table += '</tbody>';
                table += '</table>'; 
				table += '<div id = "actual_course_attainment_co"></div>';
                table += '<hr>';
				
                $('#co_attain_data_finalized_div').append(table); 
				$('#actual_course_attainment_co').html('<b>Course Attainment: </b> ' + attainment  + '%');
				// PO Attainment table view js script
				$('#po_attain_data_div').html("<div class='navbar'><div class='navbar-inner-custom'>"+ student_outcomes_full + "("+sos+") Attainment by the Course  </div></div>");
				var po_table = '';
				po_table += '<table class="table table-bordered">';
					po_table += '<thead><tr><th style="white-space:nowrap;">Sl No.</th><th style="white-space:nowrap;"><center>'+ so + ' Reference</center></th><th style="white-space:nowrap;"><center>Mapped CO </center></th><th><center>'+ student_outcome + ' ('+ so + ') Statement</center></th><th style="white-space:nowrap;"><center>Attainment %</center></th></thead>';
					var i = 0;
					po_table += '<tbody>';
					$.each(po_data, function () {
						po_table += '<tr>';
						po_table +='<td><center>'+(i+1)+'</center></td>';
						po_table += '<td><center>' + po_data[i].po_reference + '</center></td>';
						po_table += '<td>' + po_data[i].co_mapping + '</td>';
						po_table += '<td>' + po_data[i].po_statement + '</td>';
						po_table += '<td style="text-align:right">' + po_data[i].direct_attainment + ' % </td>';
						po_table += '</tr>';
						i++;
					});
                po_table += '</tbody>';
                po_table += '</table>';
                po_table += '<hr>';
                $('#po_attain_data_div').append(po_table);
            } else {
                $('#co_attain_data_finalized_div').append("<center><span class='err_msg'>"+course_outcome+" not finalized for this course.</span></center><hr>");
            }

        },
    });

}

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
		'survey_id' : survey_id,
		'course_comparison' : course_id,
		'direct_attainment_val' : direct_attainment_val,
		'indirect_attainment_val' : indirect_attainment_val,
		'type_data' : type_data,
		'occasion' : occasion

	}

	$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/tier1_course_clo_attainment/finalize_getDirectIndirectCOAttaintmentData',
		data : post_data,
		dataType : 'JSON',
		success : function (msg) {
			if (msg != 0) {
				$('#finalize_direct_indirect_success').modal('show');
				show_finalized_co_grid();
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
	plot_graphs_comparison_attainment(survey_id, course_id, direct_attainment_val, indirect_attainment_val, type_data, occasion);
	} else {
	$('#error_dialog_window').modal('show');
	} */
	plot_graphs_comparison_attainment(survey_id, course_id, direct_attainment_val, indirect_attainment_val, type_data, occasion);
});

function plot_graphs_comparison_attainment(survey_id, course_id, direct_attainment_val, indirect_attainment_val, type_data, occasion) {

	var post_data = {
		'survey_id' : survey_id,
		'course_comparison' : course_id,
		'direct_attainment_val' : direct_attainment_val,
		'indirect_attainment_val' : indirect_attainment_val,
		'type_data' : type_data,
		'occasion' : occasion

	}

	$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/tier1_course_clo_attainment/getDirectIndirectCOAttaintmentData',
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
					data1.push(clo_code[i]);
					data2.push(Number(attainment[i]));

					i++;
				});
				$('#co_level_comparison_nav').html('<br/><div class="row-fluid"><div class="span12 rightJustified" ><a id="exportToPDF" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a></div></div><br><div class="navbar"><div class="navbar-inner-custom">Course Outcome (CO) Direct and Indirect Attainment Analysis</div></div>');
				$('#chart_plot_7').html('<div class=row-fluid><div class=span12><div id=clo_atn_chart_shv style=position:relative; class=jqplot-target></div></div><div class=span12><div class=span6></div><div class=span11 id="course_outcome_attainment_div"> <table border=1 class="table table-bordered" id=co_level_comparison_nav_table_id><thead></thead><tbody id="co_level_body"></tbody></table><div id="actual_course_attainment"></div></div><div id=docs class=span12></div></div></div>');
				$('#co_level_comparison_nav_table_id > tbody:first').append('<tr><td><center><b>' + entity_clo + ' Code</b></center></td><td><center><b>Actual Direct Attainment %</b></center></td><td><center><b>Actual Indirect Attainment %</b></center></td><td><center><b>Direct Attainment Weightage %</b></center></td> <td><center><b>Indirect Attainment Weightage %</b></center></td><td style="white-space:no-wrap;"><center><b>After Weightage Direct Attainment %</b></center></td> <td><center><b>After Weightage Indirect Attainment %</b></center></td><td><center><b>Overall Attainment %</b></center></td></tr>');
				$.each(json_graph_data, function () {
					$('#co_level_comparison_nav_table_id > tbody:last').append('<tr><td><center>' + this['clo_code'] + '</center></td><td style="text-align: right;">' + this['totalDirectAttaintment'] + '</td><td style="text-align: right;">' + parseFloat(this['AttaintmentPercentage']).toFixed(2) + '</td><td style="text-align: right;">' + parseFloat(this['directPercentage']).toFixed(2) + '</td><td style="text-align: right;">' + parseFloat(this['indirectPercentage']).toFixed(2) + '</td><td style="text-align: right;">' + this['directAttaintment'] + '</td><td style="text-align: right;">' + this['indirectAttaintment'] + '</td><td style="text-align: right;">' + this['Attaintment'] + '</td></tr>');
				});
				var ticks = data1;
				var s1 = data2;
				var plot1 = $.jqplot('clo_atn_chart_shv', [s1], {
						seriesColors : ["#4bb2c5"], //,"#3efc70"
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
								label : ' CO Attainment %'
							},
						],
						highlighter : {
							show : true,
							tooltipLocation : 'e', // location of tooltip: n, ne, e, se, s, sw, w, nw.
							tooltipAxes : 'x', // which axis values to display in the tooltip, x, y or both.
							showMarker : false,
							tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
								return clo_code[pointIndex];
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
								tickInterval:'10',
								tickOptions : {
									formatString : '%.2f%'
								}
							}
						}

					}); //end of graph
				$('#finalize_direct_indirect_div').show();
			}
		}
	});

}

//Edited by Shivaraj B Date: 13/10/2015

$('#indirect_attainment_form').on('click', '#exportToPDF', function () {
	var crclm_name = $('#crclm_id :selected').text();
	var term_name = $('#term :selected').text();
	var course_name = $('#course :selected').text();

	var course_attainment_graph = $('#indirect_attain_chart_shv').jqplotToImageStr({});
	var course_attainment_graph_image = $('<img/>').attr('src', course_attainment_graph);
	$('#course_outcome_indirect_attainment_graph_data').html('<table><tr><td><b>Curriculum :</b></td><td>' + crclm_name + '</td></tr><tr><td><b>Term :</b></td><td>' + term_name + '</td></tr><td><b>Course :</b></td><td>' + course_name + '</td></tr></table><br><b>Course Outcomes(COs) Indirect Attainment</b><br />');
	$('#course_outcome_indirect_attainment_graph_data').append("<br><br>");
	$('#course_outcome_indirect_attainment_graph_data').append(course_attainment_graph_image);
	$('#course_outcome_indirect_attainment_graph_data').append("<br><pagebreak/><br><br>");
	$('#course_outcome_indirect_attainment_graph_data').append($('#graph_val').clone().html());
	var course_outcome_indirect_attainment_pdf = $('#course_outcome_indirect_attainment_graph_data').clone().html();
	$('#course_outcome_indirect_attainment_graph_data_hidden').val(course_outcome_indirect_attainment_pdf);
	$('#indirect_attainment_form').submit();
});

$('#indirect_direct_attainment_form').on('click', '#exportToPDF', function () {
	var crclm_name = $('#crclm_id :selected').text();
	var term_name = $('#term :selected').text();
	var course_name = $('#course :selected').text();
	var course_attainment_graph = $('#clo_atn_chart_shv').jqplotToImageStr({});
	var course_attainment_graph_image = $('<img/>').attr('src', course_attainment_graph);
	$('#direct_indirect_attain_data').html('<table><tr><td><b>Curriculum :</b></td><td>' + crclm_name + '</td></tr><tr><td><b>Term :</b></td><td>' + term_name + '</td></tr><td><b>Course :</b></td><td>' + course_name + '</td></tr></table><br><b>Course Outcomes(COs) Direct and Indirect Attainment Analysis</b><br />');
	$('#direct_indirect_attain_data').append(course_attainment_graph_image);
	$('#direct_indirect_attain_data').append("<pagebreak/>");
	$('#direct_indirect_attain_data').append($('#course_outcome_attainment_div').clone().html());
	$('#direct_indirect_attain_data').append("<pagebreak/>");
	$('#direct_indirect_attain_data').append($('#co_attain_data_finalized_div').clone().html());
	$('#direct_indirect_attain_data').append("<pagebreak/>");
	$('#direct_indirect_attain_data').append($('#po_attain_data_div').clone().html());
	var course_outcome_indirect_attainment_pdf = $('#direct_indirect_attain_data').clone().html();
	$('#direct_indirect_attain_data_hidden').val(course_outcome_indirect_attainment_pdf);
	$('#indirect_direct_attainment_form').submit();

});


$('.noty').click(function () {
    var options = $.parseJSON($(this).attr('data-noty-options'));
    noty(options);

});

$('.toggle_doc_button').on('click' , function(){
	tab_id  = $(this).attr('id'); 
	var type_val = $('#select_occa_type').find('option:selected').val();
	var type_text = $('#select_occa_type').find('option:selected').text();
	if(tab_id == 'tab1'){
		if($('#course').val() == ''){ 	$('.export_doc').prop('disabled', true); } else{ $('.export_doc').prop('disabled', false); }
		
	}else if(tab_id == 'tab2'){ 
		if($('#select_occa_type').val() == '' || $('#select_occa_type').val()== null){ $('.export_doc').prop('disabled', true); } else{ $('.export_doc').prop('disabled', false); }
	}else if(tab_id == 'tab4'){
		if($('#course').val() == ''){ 	$('.export_doc').prop('disabled', true); } else{ $('.export_doc').prop('disabled', false); }
	}
	
});



$('#course_attainment_form').on('click', '.export_doc', function() {
    href_content = $(".attainment_drill_down_mte").prop( "href" ); 
  // $(".attainment_drill_down_mte").attr("href", "#");
   //$('.attainment_drill_down_mte').child().text($('.attainment_drill_down_mte').text());
    var tab_name = $('ul.nav-tabs li.active > a').attr('href').slice(1);
    $('#tab_name').val(tab_name);	
	tab_id  = $("ul#myTab li.active").attr('id');

    var text_value = $('ul.nav-tabs li.active').text();
    $('#file_name').val(text_value.trim());
    var export_data = '';
	$('#active_tab_name').val(tab_name);
    if(tab_id == 'tab1') {
		export_data = $('#section_finalize_status_tbl').clone().html() + $('#note_data').clone().html();
		
    } else if(tab_id === 'tab2') {
       var export_data = '';
        var imgData = $('#all_section_co_attainment_chart').jqplotToImageStr({});
        $('#export_graph_data_to_doc').val(imgData); 
    } else if(tab_id === 'tab4') {				
		export_data = $('#mte_finalize_status_tbl').clone().html() + $('#note_data_mte').html();
    } else {
    }
	
    $('#export_doc_data').val(export_data);
    $('#course_attainment_form').submit();
});

$(document).ready(function () {
	$('#select_occa_type').multiselect({
			includeSelectAllOption : true,
			buttonWidth : '160px',
			nonSelectedText : 'Select Occasions',	
			nonSelectedAll : 'all',
			templates : {
				button : '<button type="button" class="multiselect btn btn-link dropdown-toggle" data-toggle="dropdown"></button>'
			}

		});
});
