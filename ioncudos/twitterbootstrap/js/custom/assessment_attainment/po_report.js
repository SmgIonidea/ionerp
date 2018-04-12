// Js File
var base_url = $('#get_base_url').val();
$('#crclm').on('change',function(){

var crclm_id = $(this).val();
var post_data = {
	'crclm_id' : crclm_id,
}
	$.ajax({type: "POST",
			url: base_url+'assessment_attainment/po_report/get_terms',
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
			url: base_url+'assessment_attainment/po_report/get_po_report_data',
            data: post_val,
			async:false,
            success: function(msg) {
				$('#table_view').empty();
				$('#table_view').html(msg);
				$('#po_report_table').dataTable({
					"bLengthChange": false, "bPaginate": false,
					"fnDrawCallback":function(oSettings){
						$('.group').parent().css({'background-color':'#C7C5C5'});
					//-------------------------------------------------
					// SUM COLUMNS WITHIN GROUPS
					var total_cia = total_tee = total = 0;
					var total_course = 1;
					$("#po_report_table tbody tr").each(function(index) {
						if ($(this).find('td:first.group').html()) {
							total_cia = total_tee = total = total_course = 0;
							total_course = 1;
						} else {
							total_cia = parseFloat(total_cia) + parseFloat(this.cells[3].innerHTML);
							total_tee = parseFloat(total_tee) + parseFloat(this.cells[5].innerHTML);							
							total = (parseFloat(total_cia) + parseFloat(total_tee))/total_course;
							total_course++;
							$(this).closest('tr').prevAll('tr:has(td.group):first').find("span").html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PO Attainment :'+total.toFixed(2)+'%');
						}
					});
					
					
						//-------------------
					}
				}).rowGrouping({iGroupingColumnIndex:0,
								bHideGroupingColumn: true });
				}
		});
});

$(document).on('click','.course_id_po_id',function(){
	var po_crs_id = $(this).attr('po_crs_id');
	var crs_name = $(this).attr('course_title');
	
	var po_crs = po_crs_id.split('|');
	var crs_id = po_crs[0];
	var po_id = po_crs[1];
	
	var po_post = {
		'crs_id' : crs_id,
		'po_id' : po_id
	}
	
	$.ajax({type: "POST",
			url: base_url+'assessment_attainment/po_report/co_report_data',
            data: po_post,
			//dataType: 'JSON',
            success: function(msg) {
				
				$('#po_data_modal_body').empty();
				$('#change_navbar').empty();
				$('#crs_name').empty();
				$('#crs_name').html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Course Title :'+crs_name+'.</b>');
				$('#change_navbar').html('Course Outcome(CO) Mapping Details');
				$('#po_data_modal_body').html(msg);
				$('#po_modal_view').modal({dynamic:true});
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
			url: base_url+'assessment_attainment/po_report/po_mapping_data',
            data: course_post,
			//dataType: 'JSON',
            success: function(msg) {
				
				$('#co_drill_down_modal_body').empty();
				$('#crs_name_one').empty();
				$('#co_drill_change_navbar').empty();
				$('#co_drill_change_navbar').html('Program Outcome(PO) Mapping Details');
				$('#crs_name_one').html('&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b> CO Code : <a title="'+clo_stmt+'" class="cursor_pointer">'+co_code+'</a></b>');
				$('#co_drill_down_modal_body').html(msg);
				$('#co_drill_down_modal_view').modal({dynamic:true});
            }
        });
});

$(document).on('click','.view_tlo',function(){
	var co_id = $(this).attr('co_id');
	var course_post = {
		'co_id' : co_id
	}
	
	$.ajax({type: "POST",
			url: base_url+'assessment_attainment/po_report/tlo_mapping_data',
            data: course_post,
            success: function(msg) {
				$('#co_drill_down_modal_body').empty();
				$('#co_drill_change_navbar').empty();
				$('#co_drill_change_navbar').html('Topic & ' + entity_tlo + ' Mapping Details');
				$('#co_drill_down_modal_body').html(msg);
				$('#topic_table').dataTable({
				"fnDrawCallback":function(){
					$('.group').parent().css({'background-color':'#C7C5C5'}); 
					}
				}).rowGrouping({ iGroupingColumnIndex:0,
									bHideGroupingColumn: true });
				$('#co_drill_down_modal_view').modal({dynamic:true});
				
            }
        });
});



$(document).on('click','.view_occasion',function(){
	var co_id = $(this).attr('co_id');
	var course_post = {
		'co_id' : co_id
	}
	
	$.ajax({type: "POST",
			url: base_url+'assessment_attainment/po_report/occa_mapping_data',
            data: course_post,
            success: function(msg) {
				$('#co_drill_down_modal_body').empty();
				$('#co_drill_change_navbar').empty();
				$('#co_drill_change_navbar').html('Occasions Mapping Details');
				$('#co_drill_down_modal_body').html(msg);
					
					$('#po_table').dataTable({
				"fnDrawCallback":function(){
					$('.group').parent().css({'background-color':'#C7C5C5'});
					//$('.group').attr('title',)
				}
				}).rowGrouping({ iGroupingColumnIndex:0,
									bHideGroupingColumn: true });
				
			
				
				$('#co_drill_down_modal_view').modal({dynamic:true});
				
			
            }
        });
});


$(document).ready(function() {
    $('#example').dataTable( {
        
    } );
} );

