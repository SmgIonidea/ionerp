// Js File
var base_url = $('#get_base_url').val();
$('#crclm').on('change',function(){

var crclm_id = $(this).val();
var post_data = {
	'crclm_id' : crclm_id,
}
	$.ajax({type: "POST",
			url: base_url+'assessment_attainment/co_report/get_terms',
            data: post_data,
            success: function(msg) {
				$('#term').empty();
				$('#term').append(msg);
            }
        });

});

$('#term').on('change',function(){
var crclm_id = $('#crclm').val();
var term_id = $(this).val();
var post_val = {
	'crclm_id' : crclm_id,
	'term_id'  : term_id
	}
	
	$.ajax({type: "POST",
			url: base_url+'assessment_attainment/co_report/get_course_list',
            data: post_val,
            success: function(msg) {
				$('#course').empty();
				$('#course').append(msg);
            }
        });
	
});


$('#course').on('change',function(){
var crclm_id = $('#crclm').val();
var term_id = $('#term').val();
var crs_id = $(this).val();
var post_val = {
	'crclm_id' : crclm_id,
	'term_id'  : term_id,
	'crs_id'   : crs_id
	}
	
	$.ajax({type: "POST",
			url: base_url+'assessment_attainment/co_report/get_co_report',
            data: post_val,
            success: function(msg) {
				$('#table_view').empty();
				$('#table_view').html(msg);
            }
        });
	
});

$(document).on('click','.view_po',function(){
	var co_id = $(this).attr('co_id');
	var co_code = $(this).attr('clo_code');
	var clo_stmt = $(this).attr('clo_stmt');
	var crs_name = $('#course option:selected').text();
	var course_post = {
		'co_id' : co_id
	}
	
	$.ajax({type: "POST",
			url: base_url+'assessment_attainment/co_report/po_mapping_data',
            data: course_post,
			//dataType: 'JSON',
            success: function(msg) {
				
				$('#po_data_modal_body').empty();
				$('#crs_name').empty();
				$('#change_navbar').empty();
				$('#crs_name').html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Course Title : '+crs_name+'. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CO Code : <a title="'+clo_stmt+'" class="cursor_pointer">'+co_code+'</a></b>');
				$('#change_navbar').html('Program Outcome(PO) Mapping Details');
				$('#po_data_modal_body').html(msg);
				$('#po_modal_view').modal({dynamic:true});
            }
        });
});

$(document).on('click','.view_tlo',function(){
	var co_id = $(this).attr('co_id');
	var course_post = {
		'co_id' : co_id
	}
	
	$.ajax({type: "POST",
			url: base_url+'assessment_attainment/co_report/tlo_mapping_data',
            data: course_post,
            success: function(msg) {
				$('#po_data_modal_body').empty();
				$('#change_navbar').empty();
				$('#change_navbar').html('Topic & '+ entity_tlo +' Mapping Details');
				$('#po_data_modal_body').html(msg);
				$('#topic_table').dataTable({
				"fnDrawCallback":function(){
					$('.group').parent().css({'background-color':'#C7C5C5'}); 
					}
				}).rowGrouping({ iGroupingColumnIndex:0,
									bHideGroupingColumn: true });
				$('#po_modal_view').modal({dynamic:true});
            }
        });
});

/* $(document).on('click','.view_topic',function(){
	var co_id = $(this).attr('co_id');
	var course_post = {
		'co_id' : co_id
	}
	
	$.ajax({type: "POST",
			url: base_url+'report/co_report/topic_mapping_data',
            data: course_post,
            success: function(msg) {
				// $('#table_view').empty();
				// $('#table_view').html(msg);
            }
        });
}); */

$(document).on('click','.view_occasion',function(){
	var co_id = $(this).attr('co_id');
	var course_post = {
		'co_id' : co_id
	}
	
	$.ajax({type: "POST",
			url: base_url+'assessment_attainment/co_report/occa_mapping_data',
            data: course_post,
            success: function(msg) {
				$('#po_data_modal_body').empty();
				$('#change_navbar').empty();
				$('#change_navbar').html('Occasions Mapping Details');
				$('#po_data_modal_body').html(msg);
				
				
				
					
					$('#po_table').dataTable({
				"fnDrawCallback":function(){
					$('.group').parent().css({'background-color':'#C7C5C5'});
					//$('.group').attr('title',)
				}
				}).rowGrouping({ iGroupingColumnIndex:0,
									bHideGroupingColumn: true });
				
				
			
				
				$('#po_modal_view').modal({dynamic:true});
				
			
            }
        });
});


