
	/*
		JS for Academic Year Wise PO attainment
	*/
	
	var base_url = $('#get_base_url').val();
	$("#hint a").tooltip();
	var crs_id;
	var table_row;

	if ($.cookie('remember_dept') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#department option[value="' + $.cookie('remember_dept') + '"]').prop('selected', true);
    select_pgm_list();
}
	function select_pgm_list() {
	export_button();empty_div();
	$.cookie('remember_dept',$('#department option:selected').val(),{expires:90, path:'/'});
		var dept_id = $('#department').val();
		
		var post_data = {
			'dept_id': dept_id
		}
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/tier1_po_attainment_academic_year_wise/select_pgm_list',
			data: post_data,
			success: function(msg) {
				//document.getElementById('program').innerHTML = msg;
				$('#program').html(msg);
				if ($.cookie('remember_pgm') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#program option[value="' + $.cookie('remember_pgm') + '"]').prop('selected', true);
                //select_crclm_list();
				$('#program').trigger('change');
            }
				}
		});
	}
	
	$('#program').on('change',function(){
	export_button(); empty_div();
		var pgm_id = $(this).val();
		var post_data = {'pgm_id':pgm_id};
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/tier1_po_attainment_academic_year_wise/academic_year',
			data: post_data,
			dataType:'JSON',
			success: function(msg) {						
						$('#academic_year').empty();
						$('#academic_year').append($(msg.academic_year));
						
				}
		});
	});
	
	$('#academic_year').on('change' , function(){
		export_button();
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/tier1_po_attainment_academic_year_wise/get_tier1_or_tier2',			
			dataType:'JSON',
			success: function(msg) {	
						$('#tier').val(msg);
							if(msg == "TIER-I"){ 	tier1_attainment_data(); } else if(msg == 'TIER-II'){ tier2_attainment_data(); }
				}
		});
		
	
	});
	function tier1_attainment_data(){
	  $('#loading').show();
			var dept_id = $('#department').val();
			var pgm_id = $('#program').val();
			var academic_year = $('#academic_year').val();
			var tier_val = $('#tier').val();
			var post_data = {'dept_id': dept_id , 'pgm_id':pgm_id , 'academic_year':academic_year , 'tier_val' : tier_val};
			$.ajax({type: "POST",
			url: base_url+'assessment_attainment/tier1_po_attainment_academic_year_wise/fetch_po_attainment_year_wise',
			data: post_data,
			dataType:'JSON',
			success: function(msg) {	
			empty_div();			
			var total = 0; 
			for (var i = 0; i < msg.check_empty_crclm.length; i++) {
				total += msg.check_empty_crclm[i];
			}  
			if(msg.crclm_ids.length == 0 || (msg.crclm_ids.length == total )  || msg.po_flag == 1){
				if(msg.crclm_ids.length != 0){
					if(msg.po_flag == 1){
						$("#academic_data_table").html('<span class="pan10" style="color:red"><center><b> PO Reference(s) of Curriculum are different under this CAY.</b></center></span>');
					}else{	
						$("#academic_data_table").html('<span class="pan10" style="color:red"><center><b> Attainment has not finalized for any of the Curriculum under this CAY.</b></center></span>');
					}
				}else{
					$("#academic_data_table").html('<span class="pan10" style="color:red"><center><b>No Curriculum(s) defined under this CAY. </b></center></span>');
				}
					$("#academic_data_table_note").html('');
			  $('#loading').hide();
			}else{
				$("#academic_data_table").html(msg.academic_data);
				$("#academic_data_table_note").html(msg.note);
			
					
					var parameter_list = new Array();var parameter_list1 = new Array();	var parameter_list2 = new Array(); var parameter_list3 = new Array(); var parameter_list4 = new Array();
					var	tool_tip1 = new Array();var	tool_tip2 = new Array();var	tool_tip3 = new Array();var	tool_tip4 = new Array();
					var colors_list = new Array();var colors_list1 = new Array();var colors_list2 = new Array();var colors_list3 = new Array();var colors_list4 = new Array();					
					var ticks = msg.po_reference;

					
					for(m = 0; m < (msg.org_array.length) ; m++){
						val = msg.org_array[m]; 						
						if(val == 'avg_po_attainment'){
							for(j=0;j<$.trim(msg.crclm_ids.length);j++){						
								parameter_list1.push(msg.threshold_array[msg.crclm_ids[j]['crclm_id']]);		
								 var label = msg.crclm_ids[j]['crclm_name']; tool_tip1.push({label:label});							 
							}colors_list.push("#fe9a2e");colors_list.push("#4BB2C5"); colors_list.push("#f781f3"); colors_list.push("#c5b47f"); 
							Label_data = 'Attainment based on Actual Secured Marks';						
							plot_graph(parameter_list1 , colors_list , ticks , 'po_attainment_method_wise_chart1' , tool_tip1 , Label_data);	
						
						}
						
						if(val == 'po_threshold_attainment'){
							for(j=0;j<$.trim(msg.crclm_ids.length);j++){						
								parameter_list2.push(msg.average_attainment_array[msg.crclm_ids[j]['crclm_id']]);	
								var label = msg.crclm_ids[j]['crclm_name']; tool_tip2.push({label:label});								
							} colors_list.push("#fe9a2e");colors_list.push("#4BB2C5"); colors_list.push("#f781f3"); colors_list.push("#c5b47f");
							Label_data = 'Attainment based on Threshold method';										
							plot_graph(parameter_list2 , colors_list , ticks , 'po_attainment_method_wise_chart2' , tool_tip2 , Label_data);						
						
						}
						if(val == 'hml_weighted_average_da_avg'){
							for(j=0;j<$.trim(msg.crclm_ids.length);j++){						
								parameter_list3.push(msg.threshold_hml_array[msg.crclm_ids[j]['crclm_id']]);	
								var label = msg.crclm_ids[j]['crclm_name']; tool_tip3.push({label:label});
							} colors_list.push("#fe9a2e");colors_list.push("#4BB2C5"); colors_list.push("#f781f3"); colors_list.push("#c5b47f");  
							Label_data = 'Attainment based on Weighted Average Method ';						
							plot_graph(parameter_list3 , colors_list , ticks , 'po_attainment_method_wise_chart3' , tool_tip3 , Label_data);
						
						}
						
						if(val == 'hml_weighted_multiply_maplevel_da_avg'){
							for(j=0;j<$.trim(msg.crclm_ids.length);j++){						
								parameter_list4.push(msg.threshold_hml_multiply_array[msg.crclm_ids[j]['crclm_id']]);	
								var label = msg.crclm_ids[j]['crclm_name']; tool_tip4.push({label:label});
							} colors_list.push("#fe9a2e");colors_list.push("#4BB2C5"); colors_list.push("#f781f3"); colors_list.push("#c5b47f"); 	
							Label_data = 'Attainment based on Relative Weighted Average Method';
							
							plot_graph(parameter_list4 , colors_list , ticks , 'po_attainment_method_wise_chart4' , tool_tip4 , Label_data);
						}
					}
					  
				}
			}
		});
			
	}	
	function empty_div(){
			$('#po_attainment_method_wise_chart1').empty();$('#po_attainment_method_wise_chart2').empty();$('#po_attainment_method_wise_chart3').empty();$('#po_attainment_method_wise_chart4').empty();
			$('#po_attainment_method_wise_chart1').hide();$('#po_attainment_method_wise_chart2').hide();$('#po_attainment_method_wise_chart3').hide();$('#po_attainment_method_wise_chart4').hide();
			$("#academic_data_table").html('');
			$("#academic_data_table_note").html("");
	}
	function tier2_attainment_data(){
	    $('#loading').show();
		var dept_id = $('#department').val();
		var pgm_id = $('#program').val();
		var academic_year = $('#academic_year').val();
		var tier_val = $('#tier').val();
		var post_data = {'dept_id': dept_id , 'pgm_id':pgm_id , 'academic_year':academic_year , 'tier_val' : tier_val};
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/tier1_po_attainment_academic_year_wise/fetch_po_attainment_year_wise_tier2',
			data: post_data,
			dataType:'JSON',
			success: function(msg) {		
				
					empty_div();
			var total = 0; 
			for (var i = 0; i < msg.check_empty_crclm.length; i++) {
				total += msg.check_empty_crclm[i];
			}  
			if(msg.crclm_ids.length == 0 || (msg.crclm_ids.length == total )  || msg.po_flag == 1){
				if(msg.crclm_ids.length != 0){
					if(msg.po_flag == 1){
						$("#academic_data_table").html('<span class="pan10" style="color:red"><center><b> PO Reference(s) of Curriculum are different under this CAY.</b></center></span>');
					}else{	
						$("#academic_data_table").html('<span class="pan10" style="color:red"><center><b> Attainment has not finalized for any of the Curriculum under this CAY.</b></center></span>');
					}
				}else{
					$("#academic_data_table").html('<span class="pan10" style="color:red"><center><b>No Curriculum(s) defined under this CAY. </b></center></span>');
				}
					$("#academic_data_table_note").html('');
			  $('#loading').hide();
			}else{ 
				
				$("#academic_data_table").html(msg.academic_data);
				$("#academic_data_table_note").html(msg.note);
			
					
					var parameter_list = new Array();var parameter_list1 = new Array();	var parameter_list2 = new Array(); var parameter_list3 = new Array(); var parameter_list4 = new Array();
					var	tool_tip1 = new Array();var	tool_tip2 = new Array();var	tool_tip3 = new Array();var	tool_tip4 = new Array();
					var colors_list = new Array();var colors_list1 = new Array();var colors_list2 = new Array();var colors_list3 = new Array();var colors_list4 = new Array();					
					var ticks = msg.po_reference;
					
					for(m = 0; m < (msg.org_array.length) ; m++){
						val = msg.org_array[m]; 						
						if(val == 'average_po_direct_attainment'){
							for(j=0;j<$.trim(msg.crclm_ids.length);j++){						
								parameter_list1.push(msg.threshold_array[msg.crclm_ids[j]['crclm_id']]);		
								 var label = msg.crclm_ids[j]['crclm_name']; tool_tip1.push({label:label});							 
							}colors_list.push("#fe9a2e");colors_list.push("#4BB2C5"); colors_list.push("#f781f3"); colors_list.push("#c5b47f"); 
							Label_data = 'Attainment based on Actual Secured Marks';						
							plot_graph(parameter_list1 , colors_list , ticks , 'po_attainment_method_wise_chart1' , tool_tip1 , Label_data);	
						
						}
						
						if(val == 'threshold_po_direct_attainment'){
							for(j=0;j<$.trim(msg.crclm_ids.length);j++){						
								parameter_list2.push(msg.average_attainment_array[msg.crclm_ids[j]['crclm_id']]);	
								var label = msg.crclm_ids[j]['crclm_name']; tool_tip2.push({label:label});								
							} colors_list.push("#fe9a2e");colors_list.push("#4BB2C5"); colors_list.push("#f781f3"); colors_list.push("#c5b47f");
							Label_data = 'Attainment based on Threshold method';										
							plot_graph(parameter_list2 , colors_list , ticks , 'po_attainment_method_wise_chart2' , tool_tip2 , Label_data);						
						
						}
						
						if(val == 'hml_weighted_average_da'){
							for(j=0;j<$.trim(msg.crclm_ids.length);j++){						
								parameter_list3.push(msg.threshold_hml_array[msg.crclm_ids[j]['crclm_id']]);	
								var label = msg.crclm_ids[j]['crclm_name']; tool_tip3.push({label:label});
							} colors_list.push("#fe9a2e");colors_list.push("#4BB2C5"); colors_list.push("#f781f3"); colors_list.push("#c5b47f");  
							Label_data = 'Attainment based on Weighted Average Method ';						
							plot_graph(parameter_list3 , colors_list , ticks , 'po_attainment_method_wise_chart3' , tool_tip3 , Label_data);
						
						}
						
						if(val == 'hml_weighted_multiply_maplevel_da'){
							for(j=0;j<$.trim(msg.crclm_ids.length);j++){						
								parameter_list4.push(msg.threshold_hml_multiply_array[msg.crclm_ids[j]['crclm_id']]);	
								var label = msg.crclm_ids[j]['crclm_name']; tool_tip4.push({label:label});
							} colors_list.push("#fe9a2e");colors_list.push("#4BB2C5"); colors_list.push("#f781f3"); colors_list.push("#c5b47f"); 	
							Label_data = 'Attainment based on Relative Weighted Average Method ';
							
							plot_graph(parameter_list4 , colors_list , ticks , 'po_attainment_method_wise_chart4' , tool_tip4 , Label_data);
						}
					}
					  
				}
			}
		});
		// $('#loading').hide();
	}
	
	function plot_graph(parameter_list , colors_list , ticks , div_id , tool_tip , Label_data){	
		if(parameter_list.length != 0){		
		$('#'+ div_id).show();
				var po_attainment_graph = $.jqplot(div_id, parameter_list, {
					 title:  Label_data ,
						seriesColors : colors_list,
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
						series : tool_tip,
						highlighter : {
							show : true,
							tooltipLocation : 'e', // location of tooltip: n, ne, e, se, s, sw, w, nw.
							tooltipAxes : 'x', // which axis values to display in the tooltip, x, y or both.
							showMarker : false,
							tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
								return tool_tip;
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
				$('#loading').hide();
		}else{
		$('#'+div_id).empty(); $('#loading').hide();
		}
		
	}

	$('body').on('click' ,'.term_wise_data' , function(){
		var po_id = $(this).attr('data-po_id');  
		var crclm_id = $(this).attr('data-crclm_id');   
		var academic_year = $("#academic_year").val();
		var dept_id = $('#department').val();
		var pgm_id = $('#program').val();
		var col_name = $(this).attr('data-col_data');
		var po_reference = $(this).attr('data-po_reference');
		var tier_val = $('#tier').val(); 
		$('#crclm_name_val').val($(this).attr('data-crclm_name')); 
		var post_data = {'dept_id': dept_id , 'pgm_id':pgm_id , 'academic_year':academic_year , 'po_id': po_id , 'crclm_id':crclm_id , 'col_name':col_name , 'po_reference' : po_reference , "tier_val" : tier_val};
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/tier1_po_attainment_academic_year_wise/fetch_po_attainment_term_wise',
			data: post_data,
			dataType:'JSON',
			success: function(msg) {	
					$('#crclm_name_term_modal').html("<p><b>Curriculum : " + $('#crclm_name_val').val()  + "</b></p>");
					$('.term_data').html(msg['term_data']);
					$('.po_reference').html(msg['po_reference']);
				 		 $('#table_data').dataTable().fnDestroy();					 							
						 $('#table_data td:nth-child(3)').hide();  
						 $('#table_data th:nth-child(3)').hide();  
						 $('#table_data').dataTable({
								"fnDrawCallback":function(){
							$('.group').parent().css({'background-color':'#C7C5C5'}); 
								},
								"sSort": false,
								"sPaginate": false,
								"bPaginate": false,
								"bFilter": false,
								"bInfo": false,																
							}).rowGrouping({ iGroupingColumnIndex:0,
							bHideGroupingColumn: true });  
							
											
					var ticks = msg.crs_code;

					var po_attainment_graph = $.jqplot('chart1', [msg.threshold_array], {
							seriesColors : ["#4BB2C5"],
							seriesDefaults : {
								renderer : $.jqplot.BarRenderer,
								rendererOptions : {
									barWidth : 15,
									fillToZero : true,
                                    barPadding: 15
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
								tooltipLocation : 'e', // location of tooltip: n, ne, e, se, s, sw, w, nw.
								tooltipAxes : 'x', // which axis values to display in the tooltip, x, y or both.
								showMarker : false,
								tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
									return msg.crs_title;
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
									padMin : 10,
									min : 0,
									max : 100,
									tickOptions : {
										formatString : '%.2f%'
									}
								}
							}

                    });
					$('#term_wise_attainment_details').modal('show');
					$('#term_wise_attainment_details').on('shown', function () {
					po_attainment_graph.replot();
				});
				$('#term_wise_attainment_details').modal('show');
				}
		});
	});	

$('.myModalLevelDispaly').live('click', function (e) {
	$('#po_id').val($(this).attr('data-po_id'));
	$('#po_stmt').val($(this).attr('data-po_reference'));
	$('#crclm_name_val').val($(this).attr('data-crclm_name')); 
	
	var tier_val = $('#tier').val();
	var post_data = {
		'po_id' : $(this).attr('data-po_id'), 'tier_val': tier_val,
	}
	$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/tier1_po_attainment_academic_year_wise/get_performance_level_attainments_by_po',
		data : post_data,
		datatype : "html",
		success : function (msg) {
			var data = jQuery.parseJSON(msg);
			var id = $('#po_id').val();
			var po_statement = $('#po_stmt').val();			

			$('#crclm_name').html("<p><b>Curriculum : " + $('#crclm_name_val').val() + "</b></p>");
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
	$('#po_attainment_form').on('click', '.export_doc', function() {
		
	var dept_name = $("#department option:selected").text(); var pgm_name = $("#program option:selected").text();
		$('#po_attainment_main_head_data').html('<b style="font-size:14; color:green">Current Academic Year('+ $("#academic_year option:selected").text() +') PO Attainment</b>');
		 
		main_head = $('#po_attainment_main_head_data').html() ; 
		$('#main_head').val(main_head);
		
		var export_data =    $('#academic_data_table').clone().html() + $('#academic_data_table_note').clone().html() ;					
	 	var imgData = $('.graph').jqplotToImageStr({});
		var course_attainment_graph_image = $('<img/>').attr('src',imgData);
	

	
		$('#dept_name').val(dept_name);  
		$('#pgm_name').val(pgm_name);  
		$('#export_graph_data_to_doc').val(imgData);  
		$('#export_data').val(export_data);  
		$('#po_attainment_form').submit();

	});
	
	function export_button(){		
		if($('#department').val() =='' || $('#program').val() == '' || $('#academic_year').val() == '' ){
			$('#export_to_doc').prop('disabled', true);
		}else{	
			$('#export_to_doc').prop('disabled', false);
		}	
	}
