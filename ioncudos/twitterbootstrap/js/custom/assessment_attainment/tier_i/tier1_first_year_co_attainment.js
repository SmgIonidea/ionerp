var base_url = $('#get_base_url').val();

$(document).ready(function () {

 if ($.cookie('remember_dept') != null) {
	// set the option to selected that corresponds to what the cookie is set to
	//$('#dept_id option[value="' + $.cookie('remember_dept') + '"]').prop('selected', true);
    //$('#dept_id').trigger('change');
	
} 
$('#export_doc_btn').prop('disabled', true);
$('#dept_id').on('change' , function(){
$('#export_doc_btn').prop('disabled', true);
	$('#term_id , #crs_id , #crclm_id').empty();
	$('#term_id , #crs_id , #crclm_id').multiselect('rebuild'); 
	var dept_id =  $('#dept_id').val();empty_div();
	if(dept_id != ''){
		post_data = {'dept_id' : dept_id}
		$.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_first_year_co_attainment/fetch_program',
			data : post_data,
			success : function (msg) {
				$('#loading').hide();
				$('#pgm_id').html(msg);
				if ($.cookie('remember_dept') != null) {
					// set the option to selected that corresponds to what the cookie is set to
					$('#pgm_id option[value="' + $.cookie('remember_pgm') + '"]').prop('selected', true);
					$('#pgm_id').trigger('change');	
				}
			}
		});
	}else{
		$('#term_id , #crs_id , #crclm_id').empty();
		$('#term_id , #crs_id , #crclm_id').multiselect('rebuild'); 
	}
});

 $('#pgm_id').on('change' , function(){
 $('#export_doc_btn').prop('disabled', true);
	var dept_id  =   $('#dept_id').val(); 
	var pgm_id =  $('#pgm_id').val(); 
		$('#term_id , #crs_id , #crclm_id').empty();empty_div();
		$('#term_id , #crs_id , #crclm_id').multiselect('rebuild'); 
	if(pgm_id != ''){	
		post_data = {'dept_id' : dept_id ,'pgm_id' : pgm_id  }
		$.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_first_year_co_attainment/fetch_curriculum',
			data : post_data,
			success : function (msg) {
				$('#loading').hide(); 
				$('#crclm_id').html(msg);
				$('#crclm_id').multiselect('rebuild');
				if ($.cookie('remember_pgm') != null) {
					// set the option to selected that corresponds to what the cookie is set to
					$('#crclm_id option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
					$('#crclm_id').trigger('change');				
				}
			}
		});
	}else{
		$('#term_id , #crs_id , #crclm_id').empty();
		$('#term_id , #crs_id , #crclm_id').multiselect('rebuild'); 
	}
});

$('#crclm_id').on('change' , function(){
$('#export_doc_btn').prop('disabled', true);
	$('#term_id , #crs_id').empty();
	$('#term_id , #crs_id').multiselect('rebuild'); 		
	var dept_id  =   $('#dept_id').val(); 
	var pgm_id =  $('#pgm_id').val();
	var crclm_id =  $('#crclm_id').val(); empty_div();
	var crclm_id_selected = $('#crclm_id option:selected').length;
	post_data = {'dept_id' : dept_id ,'pgm_id' : pgm_id  , 'crclm_id' : crclm_id   }
	if(crclm_id_selected != 0){
		$.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_first_year_co_attainment/fetch_terms',
			data : post_data,
			success : function (msg) {
				$('#loading').hide();
				$('#term_id').html(msg);
				$('#term_id').multiselect('rebuild');
			}
		});
	}else{
		$('#term_id , #crs_id').empty();
		$('#term_id , #crs_id').multiselect('rebuild'); 
		
	}
});
$('#term_id').on('change' , function(){
$('#export_doc_btn').prop('disabled', true);
	var dept_id  =   $('#dept_id').val(); 
	var pgm_id =  $('#pgm_id').val();
	var crclm_id =  $('#crclm_id').val(); 
	var term_id =  $('#term_id').val(); 
	var term_id_selected = $('#term_id option:selected').length;
	empty_div();
	$('#crs_id').empty(); 
	$('#crs_id').multiselect('rebuild');
	post_data = {'dept_id' : dept_id ,'pgm_id' : pgm_id  , 'crclm_id' : crclm_id  , 'term_id' : term_id }	
	if(term_id_selected != 0){
		$.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_first_year_co_attainment/fetch_course',
			data : post_data,
			success : function (msg) {
				$('#loading').hide();
				 $('#crs_id').html(msg);
				$('#crs_id').multiselect('rebuild'); 	
				var crs_id_selected = $('#crs_id option:selected').length;		
				if(crs_id_selected != 0){ GroupDropdownlist();	}
			}
		});
		
	}else{
		$('#crs_id').empty();
		$('#crs_id').multiselect('rebuild'); 
	}
}); 

function empty_div(){
	$('#export_doc_btn').prop('disabled', true); $('#dynamic_grid').empty();$('#fdy_attainment_grid').hide();
}
$('#crs_id').on('change' , function(){
	var dept_id  =   $('#dept_id').val(); 
	var pgm_id =  $('#pgm_id').val();
	var crclm_id =  $('#crclm_id').val(); 
	var term_id =  $('#term_id').val();
	var crs_id =  $('#crs_id').val(); 
	post_data = {'dept_id' : dept_id ,'pgm_id' : pgm_id  , 'crclm_id' : crclm_id  , 'term_id' : term_id, 'crs_id' : crs_id }
	var crs_id_selected = $('#crs_id option:selected').length;
	var parameter_list = new Array();
	if(crs_id_selected != 0){
	$('#export_doc_btn').prop('disabled', false);
		$.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_first_year_co_attainment/find_tier',
			data : post_data,
			dataType : 'json',
			success : function (msg) {	
						if(msg == "TIER-II"){
							if(crs_id_selected != 0){
									$.ajax({
										type : "POST",
										url : base_url + 'assessment_attainment/tier1_first_year_co_attainment/fetch_fdy_co_attainment_tier_ii',
										data : post_data,
										dataType : 'json',
										success : function (msg) {		
											$('#dynamic_grid').html(msg['dynamic_div']);
												plot_chart(msg);
										}
									});
									$.ajax({
										type : "POST",
										url : base_url + 'assessment_attainment/tier1_first_year_co_attainment/fetch_fdy_co_attainment_overall_tier_ii',
										data : post_data,
										dataType : 'json',
										success : function (msg) {																
											populate_data_overall_attainment(msg); plot_chart_overall_attainment(msg);
										}
									});
									$('#fdy_attainment_grid').show();
							}
						
						}else{
							if(crs_id_selected != 0){
							
								$.ajax({
									type : "POST",
									url : base_url + 'assessment_attainment/tier1_first_year_co_attainment/fetch_fdy_co_attainment_tier_i',
									data : post_data,
									dataType : 'json',
									success : function (msg) {		
										$('#dynamic_grid').html(msg['dynamic_div']);
											plot_chart(msg);
									}
								});
								$.ajax({
										type : "POST",
										url : base_url + 'assessment_attainment/tier1_first_year_co_attainment/fetch_fdy_co_attainment_overall_tier_i',
										data : post_data,
										dataType : 'json',
										success : function (msg) {																
											populate_data_overall_attainment(msg); plot_chart_overall_attainment(msg);
										}
								});
								$('#fdy_attainment_grid').show();
							}
						}
			}
		});	
	}else{
		$('#export_doc_btn').prop('disabled', true);
		empty_div();
	}
});
$('#fdy_attainment_grid').hide();
	function populate_data(msg){	
		$('#FDYAttainment').dataTable().fnDestroy();
		$('#FDYAttainment').dataTable({
			"aaSorting" : [],
			"bFilter" : false,
			"bPaginate" : false,
			"bInfo" : false,
			"orderable" : false,
			"aoColumns" : [{
					"sTitle" : "Sl No.  ",
					"mData" : "Sl_no",
					"sWidth" : "5%",
					"bSortable" : false,
					"sClass" : "rightalign",
				}, {
					"sTitle" : "CO Code",
					"mData" : "clo_code",
					"bSortable" : false,
				},  {
					"sTitle" : "CO Statement",
					"mData" : "clo_statement",
					"bSortable" : false,
				},{
					"sTitle" : " Average based Attainment Method",
					"mData" : "average_attainment",
					"bSortable" : false,
					"sClass" : "centeralign",
				}, {
					"sTitle" : "Course",
					"mData" : "crs_title",
					"bSortable" : false,
				}

			],
			"aaData" : msg['attainment'],
			"sPaginationType" : "bootstrap",
			"fnDrawCallback" : function (settings) {
				$('.rightalign').css('text-align', 'right');
			}
	});
	$('#FDYAttainment').dataTable().fnDestroy();
	$('#FDYAttainment').dataTable({
	  "sSort": false,
			"sPaginate": false,
			"bPaginate": false,
			"bFilter": false,
			"bInfo": false,
		"sPaginationType": "bootstrap",
		"fnDrawCallback": function () {
		$('.group').parent().css({'background-color': '#C7C5C5'});
			
	}

	}).rowGrouping({iGroupingColumnIndex: 4,
	bHideGroupingColumn: true}); 
	
	}	
	function populate_data_overall_attainment(msg){	
		$('#FDYAttainment_overall').dataTable().fnDestroy();
		$('#FDYAttainment_overall').dataTable({
				"aaSorting" : [],
				"bFilter" : false,
				"bPaginate" : false,
				"bInfo" : false,
				"orderable" : false,
				"aoColumns" : [{
						"sTitle" : "Sl No.  ",
						"mData" : "Sl_no",
						"sWidth" : "5%",
						"bSortable" : false,
						"sClass" : "rightalign",
					}, {
						"sTitle" : "CO Code",
						"mData" : "clo_code",
						"sWidth" : "10%",
						"bSortable" : false,
					},  {
					"sTitle" : "CO Statement",
					"mData" : "clo_statement",
					"bSortable" : false,
					}, {
						"sTitle" : " Average based Attainment Method",
						"mData" : "average_attainment",
						"bSortable" : false,
						"sClass" : "rightalign",						
					}

				],
				"aaData" : msg['overall_attainment'],
				"sPaginationType" : "bootstrap",
				"fnDrawCallback" : function (settings) {
					$('.rightalign').css('text-align', 'right');
				}
		});	
	
	}
	
	function plot_chart(msg){	
		$('#attainment_chart').empty();
		parameter_list  = new Array();
		tool_tip = new Array();
		tool_tip.push("crs1");tool_tip.push("crs2");
		//for(m = 0; m < 5 ; m++){
			for(j=0;j<$.trim(msg['crs_ids'].length);j++){						
				parameter_list.push(msg.threshold_array[msg.crs_ids[j]]);
			} 
		//}										
		ticks = msg['clo_code'];
		series_data = new Array();

		for(j=0;j<$.trim(msg['crs_code'].length);j++){		
			series_data.push({label:msg['crs_code'][j]});
		}
		var po_attainment_graph = $.jqplot('attainment_chart', parameter_list, {		 			
			seriesColors : [ "#4bb2c5" , "#3efc70" , "#f781f3" , "#c5b47f" ,"#fe9a2e"],
			seriesDefaults : {
				renderer : $.jqplot.BarRenderer,
				rendererOptions : {
					barWidth : 7,
					fillToZero : true,
					barPadding: 6
				},
				pointLabels : {
					show : true
				}
			},
			series : series_data,
			highlighter : {
				show : true,
				tooltipLocation : 'e', // location of tooltip: n, ne, e, se, s, sw, w, nw.
				tooltipAxes : 'x', // which axis values to display in the tooltip, x, y or both.
				showMarker : false,
				tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
					return msg['crs_title'];
				}
			},
			legend : {
				show : true,
				placement : 'outsideGrid'
			},
			axes : {
				xaxis : {
					renderer : $.jqplot.CategoryAxisRenderer,
					ticks : ticks,
					tickOptions : {
						formatString : '%.0f%'
					}
				},
				yaxis : {
					padMin : 100,
					min : 0,
					max : 100,
					tickOptions : {
						formatString : '%.0f%'
					}
				}
			}
		});	
	}	
	
	
	function plot_chart_overall_attainment(msg){	
		$('#attainment_chart_overall').empty();
		ticks = msg['clo_code'];
		series_data = new Array();
 
		var po_attainment_graph = $.jqplot('attainment_chart_overall', [msg['average_attainment_graph']], {		 			
			seriesColors : [ "#4bb2c5" , "#3efc70" , "#f781f3" , "#c5b47f" ,"#fe9a2e"],
			seriesDefaults : {
				renderer : $.jqplot.BarRenderer,
				rendererOptions : {
					barWidth : 7,
					fillToZero : true,
					barPadding: 6
				},
				pointLabels : {
					show : true
				}
			},
			series : [{label : 'Average Attainment %'}],
			highlighter : {
				show : true,
				tooltipLocation : 'e', // location of tooltip: n, ne, e, se, s, sw, w, nw.
				tooltipAxes : 'x', // which axis values to display in the tooltip, x, y or both.
				showMarker : false,
				tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
					return "Average";
				}
			},
			legend : {
				show : true,
				placement : 'outsideGrid'
			},
			axes : {
				xaxis : {
					renderer : $.jqplot.CategoryAxisRenderer,
					ticks : ticks,
					tickOptions : {
						formatString : '%.0f%'
					}
				},
				yaxis : {
					padMin : 100,
					min : 0,
					max : 100,
					tickOptions : {
						formatString : '%.0f%'
					}
				}
			}
		});	
	}


 	$('#crclm_id').multiselect({
			includeSelectAllOption : false,
			buttonWidth : '160px',
			nonSelectedText : 'Select Curriculum',	
			nonSelectedAll : 'all',
			maxHeight: 250 ,
			onChange: function(option, checked) {
                // Get selected options.
                var selectedOptions = $('#crclm_id option:selected');
                if (selectedOptions.length >= 4) {
                    // Disable all other checkboxes.
                    var nonSelectedOptions = $('#crclm_id option').filter(function() {
                        return !$(this).is(':selected');
                    });
 
                    nonSelectedOptions.each(function() {
                        var input = $('input[value="' + $(this).val() + '"]');
                        input.prop('disabled', true);
                        input.parent('li').addClass('disabled');
                    });
                }
                else {
                    // Enable all checkboxes.
                    $('#crclm_id option').each(function() {
                        var input = $('input[value="' + $(this).val() + '"]');
                        input.prop('disabled', false);
                        input.parent('li').addClass('disabled');
                    });
                }
            },			
			templates : {
				button : '<button type="button" class="multiselect btn btn-link dropdown-toggle" data-toggle="dropdown"></button>'
			}
	});
	$('#crclm_id').multiselect('rebuild');  
 	$('#term_id').multiselect({
			includeSelectAllOption : false,		
			buttonWidth : '160px',
			nonSelectedText : 'Select Term',	
			nonSelectedAll : 'all',
			maxHeight: 250 ,
			enableCaseInsensitiveFiltering: true,			
			enableFiltering: true,	
			onChange: function(option, checked) {
                // Get selected options.
                var selectedOptions = $('#term_id option:selected');
                if (selectedOptions.length >= 4) {
                    // Disable all other checkboxes.
                    var nonSelectedOptions = $('#term_id option').filter(function() {
                        return !$(this).is(':selected');
                    });
 
                    nonSelectedOptions.each(function() {
                        var input = $('input[value="' + $(this).val() + '"]');
                        input.prop('disabled', true);
                        input.parent('li').addClass('disabled');
                    });
                }
                else {
                    // Enable all checkboxes.
                    $('#term_id option').each(function() {
                        var input = $('input[value="' + $(this).val() + '"]');
                        input.prop('disabled', false);
                        input.parent('li').addClass('disabled');
                    });
                }
            },			
			templates : {
				button : '<button type="button" class="multiselect btn btn-link dropdown-toggle" data-toggle="dropdown"></button>'
			}
	});
	$('#term_id').multiselect('rebuild');   	
	$('#crs_id').multiselect({
			includeSelectAllOption : false,
			buttonWidth : '160px',
			nonSelectedText : 'Select Course',	
			nonSelectedAll : 'all',
			selectAllNumber: false,
			maxHeight: 250 ,				
			enableCaseInsensitiveFiltering: true,			
			enableFiltering: true,			
			enableClickableOptGroups: true,
            enableCollapsibleOptGroups: true,
			onChange: function(option, checked) {
                // Get selected options.
                var selectedOptions = $('#crs_id option:selected');
                if (selectedOptions.length >= 4) {
                    // Disable all other checkboxes.
                    var nonSelectedOptions = $('#crs_id option').filter(function() {
                        return !$(this).is(':selected');
                    });
 
                    nonSelectedOptions.each(function() {
                        var input = $('input[value="' + $(this).val() + '"]');
                        input.prop('disabled', true);
                        input.parent('li').addClass('disabled');
                    });
                }
                else {
                    // Enable all checkboxes.
                    $('#crs_id option').each(function() {
                        var input = $('input[value="' + $(this).val() + '"]');
                        input.prop('disabled', false);
                        input.parent('li').addClass('disabled');
                    });
                }
            },
			templates : {
				button : '<button type="button" class="multiselect btn btn-link dropdown-toggle" data-toggle="dropdown"></button>'
			}
	});
	$('#crs_id').multiselect('rebuild');   
	$('#export_doc_btn').prop('disabled', false);
	
	$( '.attainment_drill_down' ).live('click' ,function(){	
		var crclm_id = $(this).attr('data-crclm_id');  
		var term_id = $(this).attr('data-term_id');  
		var crs_id = $(this).attr('data-crs_id');  
		var clo_id = $(this).attr('data-clo_id')
		var clo_code = $(this).attr('data-clo_code');		
		var clo_stmt = $(this).attr('data-clo_stmt');
		var crclm_name = $(this).attr('data-crclm_name');
		var term_name = $(this).attr('data-term_name');
		var crs_name = $(this).attr('data-crs_name');
		var tier = $(this).attr('data-tier'); 
		var dept_id  =   $('#dept_id').val(); 
		var pgm_id =  $('#pgm_id').val();
		post_data = {'dept_id' : dept_id ,'pgm_id' : pgm_id  , 'crclm_id' : crclm_id  , 'term_id' : term_id, 'crs_id' : crs_id  , 'clo_id' : clo_id , 'tier' : tier , 'clo_code' : clo_code , 'crclm_name' : crclm_name ,'term_name' : term_name , 'crs_name': crs_name ,'clo_stmt' : clo_stmt}
		$.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/tier1_first_year_co_attainment/fetch_attainment_drill_down_data',
			data : post_data,
			dataType : 'json',
			success : function (msg) {	
				$('#attainment_table_grid').html(msg['attainment_table']);
				$('#course_wise_drill_down_modal').modal('show');
			}			
		});
	});
	
	
	$('#export_doc_btn').on('click' , function(){		 

		var dept_name = $('#dept_id :selected').text(); 
		var pgm_name = $('#pgm_id :selected').text(); 
		var crclm_name = $.map($('#crclm_id option:selected') , function(e) { return e.text; });
		var term_name = $.map($('#term_id option:selected') , function(e) { return e.text; });		
		var course_name = $.map($('#crs_id option:selected') , function(e) { return e.text; });
				
			$('#head_data').html('<table class="table table-bordered" style="width:100%;"><tr><td width=325><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Department : </b><b needAlt=1 class="font_h ul_class">'+ dept_name +'</b></td><td width=325><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Program : </b><b needAlt=1 class="font_h ul_class">'+ pgm_name +'</b></td></tr><tr><td width=325><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Curriculum : </b><b needAlt=1 class="font_h ul_class">'+ crclm_name +'</b></td><td width=325><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Term : </b><b needAlt=1 class="font_h ul_class">'+ term_name +'</b></td></tr><tr><td width=325><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Course : </b><b needAlt=1 class="font_h ul_class">'+ course_name +'</b></td></tr></table><br/><b><center> Individual Course Outcomes ( '+ entity_clo +') Attainment </center></b><br/>');
			$('#main_head').val($('#head_data').html());
			
			var imgData_1  = $('#attainment_chart').jqplotToImageStr({});  
			
			var imgData_2  = $('#attainment_chart_overall').jqplotToImageStr({});
			
			var export_data = $('#row').clone().html();
			var export_data_overall = $('#FDYAttainment_overall').clone().html();
			
			$('#export_graph_data_to_doc_individual').val(imgData_1);
			$('#export_graph_data_to_doc_overall').val(imgData_2);
			$('#individual_attmt').val(export_data);
			$('#overall_attmt').val(export_data_overall);

		$('#export_to_doc_form').submit();	
			
	});
	
});
