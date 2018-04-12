// Static View JS functions.
	var base_url = $('#get_base_url').val();
	//$("#hint a").tooltip();
	var crs_id;
	var table_row;
	
	if ($.cookie('remember_dept') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#department option[value="' + $.cookie('remember_dept') + '"]').prop('selected', true);
    select_pgm_list();
}
		
	function select_pgm_list() {
	$.cookie('remember_dept',$('#department option:selected').val(),{expires:90, path:'/'});
		var dept_id = document.getElementById('department').value;
		
		var post_data = {
			'dept_id': dept_id
		}
		
		$.ajax({type: "POST",
			url: base_url+'question_paper/manage_model_qp/select_pgm_list',
			data: post_data,
			success: function(msg) {
				//document.getElementById('program').innerHTML = msg;
				$('#program').html(msg);
				if($.cookie('remember_program')!= null){
				 // set the option to selected that corresponds to what the cookie is set to
                $('#program option[value="' + $.cookie('remember_program') + '"]').prop('selected', true);
				select_crclm_list();
				}
				}
		});
	}

	function select_crclm_list()
	{
		$.cookie('remember_program',$('#program option:selected').val(),{expires:90, path:'/'});
		var pgm_id = document.getElementById('program').value;
		var post_data = {
			'pgm_id': pgm_id
		} 
		
		$.ajax({type: "POST",
			url: base_url+'question_paper/manage_model_qp/select_crclm_list',
			data: post_data,
			success: function(msg) {
				//document.getElementById('curriculum').innerHTML = msg;
				$('#curriculum').html(msg);
				if($.cookie('remember_crclm')!=null){
				$('#curriculum option[value="' + $.cookie('remember_crclm') +'"]').prop('selected',true);
				select_termlist();
				
				}
			}
		});
	}
	
	function select_termlist() {
		$.cookie('remember_crclm',$('#curriculum option:selected').val(),{expires:90, path:'/'});
		var crclm_id = document.getElementById('curriculum').value;
		
		var post_data = {
			'crclm_id': crclm_id
		}
		
		$.ajax({type: "POST",
			url: base_url+'question_paper/manage_model_qp/select_termlist',
			data: post_data,
			success: function(msg) {
				//document.getElementById('term').innerHTML = msg;
				$('#term').html(msg);
				if($.cookie('remember_term')!=null){
				$('#term option[value="' + $.cookie('remember_term') +'"]').prop('selected',true);
				GetSelectedValue();
				
				}
			}
		});
	}
		
	/* Function is used to fetch course details.
	* @param - curriculum id & term id.
	* @returns- an array of course details.
	*/	
	function GetSelectedValue() {
	$.cookie('remember_term', $('#term option:selected').val(), {expires: 90, path: '/'});
		var dept_id = document.getElementById('department').value;
		var prog_id = document.getElementById('program').value;
		var crclm_id = document.getElementById('curriculum').value;
		var crclm_term_id = document.getElementById('term').value;
		
		var post_data = {
			'dept_id': dept_id,
			'prog_id': prog_id,
			'crclm_id': crclm_id,
			'crclm_term_id': crclm_term_id
		}
		
		$.ajax({type: "POST",
			url: base_url+'question_paper/manage_model_qp/show_course',
			data: post_data,
			dataType: 'json',
			success: populate_table
		});
	}

	// List View JS functions.
	$('.get_crs_id').live("click", function() {
		crs_id = $(this).attr('id');
		$('#crs_id').val(crs_id);
		table_row = $(this).closest("tr").get(0);
	});
	
	$('#qp_without_framework_ok').on('click',function(){
		var dept_id = document.getElementById('department').value;
		var pgm_id = document.getElementById('program').value;
		var crclm_id = document.getElementById('curriculum').value;
		var term_id = document.getElementById('term').value;
		var crs_id = document.getElementById('crs_id').value;
		 window.location = base_url + 'question_paper/manage_model_qp/generate_model_qp_fm_not_defined/' + pgm_id + '/' + crclm_id + '/' + term_id + '/' + crs_id + '/4';
	});

 	$('#example').on('click' , '.topic_not_defined', function(){	
		$('.topic_error_msg').html( $(this).attr('data-error_mag') + '<br/><a href ="'+ base_url + 'curriculum/topic'+'"> Click here to add Topics.<a>');
				$('#topic_not_defined_modal').modal('show');
	}); 
	

	/* Function is used to generate table grid of  course details.
	* @param - 
	* @returns- an array of course details.
	*/	
	function populate_table(msg) {
		var m = 'd';
		$('#example').dataTable().fnDestroy();
		$('#example').dataTable(
				{"aoColumns": [
						{"sTitle": "Sl No.", "mData": "sl_no"},
						{"sTitle": "Course Code", "mData": "crs_code"},
						{"sTitle": "Course Title", "mData": "crs_title"},
						{"sTitle": "Core / Elective", "mData": "crs_type_name"},
						{"sTitle": "Credits", "mData": "total_credits"},
						{"sTitle": "Total Marks", "mData": "total_marks"},
						{"sTitle": "Course Owner", "mData": "username"},
						{"sTitle": "Mode", "mData": "crs_mode"},
						{"sTitle": "View QP Details", "mData": "crs_id_edit"},
						{"sTitle": "Manage Model QP", "mData": "crs_id_delete"},
						{"sTitle": "Import Model QP", "mData": "import_qp"},
						{"sTitle": "Delete", "mData": "delete_qp"},
						{"sTitle": "Model QP Status", "mData": "publish"}
					], "aaData": msg,
					"sPaginationType": "bootstrap"});
	}
	
	$('.displayQP').live('click',function(){
		$('#loading').show();
		document.getElementById('qp_content_display').innerHTML = "";	
		var values =  $(this).attr('abbr');
		var data = values.split("/");
		var pgmtype = data[0];
		var crclm_id = data[1];
		var term_id = data[2];
		var crs_id = data[3];
		var qp_type = data[4];
		var post_data = {
			'pgmtype' : pgmtype,
			'crclm_id': crclm_id,
			'term_id' : term_id,
			'crs_id' : crs_id,
			'qp_type' : qp_type,
		}
		$.ajax({type:"POST",
				url: base_url+'question_paper/manage_model_qp/isModelQPDefined',
				data: post_data,
				dataType: 'json',
				success:function(msg){				
					if(msg == 1){
						$.ajax({type:"POST",
						url: base_url+'question_paper/manage_model_qp/generate_model_qp_modal',
						data: post_data,
						success:function(msg){	
							document.getElementById('qp_content_display').innerHTML = msg;	
							
							$('#loading').hide();
							$('#qp_table_data').dataTable().fnDestroy();
														
							$('#qp_table_data').dataTable({
								"fnDrawCallback":function(){
							$('.group').parent().css({'background-color':'#C7C5C5'}); 
								},
								"bPaginate": false,
								"bFilter": false,
								"bInfo": false,
								//"aaSorting": [[1, 'asc']],
								
							}).rowGrouping({ iGroupingColumnIndex:0,
											bHideGroupingColumn: true });
							//$('.myModalQPdisplay_paper_modal_2').on('shown',function(){	
								$('#chart1').empty();
								$('#chart2').empty();
								$('#chart3').empty();
								$('#chart4').empty();
								//$('#bloomslevelplannedcoveragedistribution > tbody:first').empty();
								//$('#bloomslevelplannedcoveragedistribution_note').empty();
								$('#bloomslevelplannedmarksdistribution > tbody:first').empty();
								$('#bloomslevelplannedmarksdistribution_note').empty();
								$('#coplannedcoveragesdistribution > tbody:first').empty();
								$('#coplannedcoveragesdistribution_note').empty();
								$('#topicplannedcoveragesdistribution > tbody:first').empty();
								$('#topicplannedcoveragesdistribution_note').empty();
								$('#export').show();
								var plot1,plot2,plot3;
								var BloomsLevel = $('#BloomsLevel').val();
								var PlannedPercentageDistribution = $('#PlannedPercentageDistribution').val();
								var ActualPercentageDistribution = $('#ActualPercentageDistribution').val();
								var BloomsLevelDescription = $('#BloomsLevelDescription').val();
								var s1 = PlannedPercentageDistribution.split(",");
								var s2 = ActualPercentageDistribution.split(",");
								var ticks = BloomsLevel.split(",");	 
								var level = BloomsLevelDescription.split(",");
								var i = 0;
								var s1_arr = new Array();
								$.each(s1, function(){
									s1_arr.push(Number(s1[i++]));
								});
								var j = 0;
								var s2_arr = new Array();
								$.each(s2, function(){
									s2_arr.push(Number(s2[j++]));
								});
							/* 	plot1 = $.jqplot('chart1', [s1_arr,s2_arr], {
										seriesDefaults:{
											renderer:$.jqplot.BarRenderer,
											pointLabels: { show: true }
										},
										series:[
											{label:'Framework Level Distribution'},
											{label:'Planned Distribution'}
										],
										axes: {
											xaxis: {
												renderer: $.jqplot.CategoryAxisRenderer,
												tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
												ticks: ticks
											},
											yaxis: {
												min:0,
												max:100,
												tickOptions: {
													formatString: '%d%%'
												}
											}
										},
										highlighter: {					
											show: true,
											tooltipLocation: 'e', 
											tooltipAxes: 'x', 
											fadeTooltip	:true,
											showMarker:false,
											tooltipContentEditor:function (str, seriesIndex, pointIndex, plot){						
												return level[pointIndex];							
											}
										}, 
										legend: {
											show: true,
											location: 'ne',
											placement: 'inside'
										}
								}); 							
								var k = 0;							
								$('#bloomslevelplannedcoveragedistribution > tbody:first').append('<tr><td><center><b>Blooms Level</b></center></td><td><center><b>Framework level Distribution</b></center></td><td><center><b>Planned Distribution</b></center></td></tr>');
								$.each(ticks, function(){
									$('#bloomslevelplannedcoveragedistribution > tbody:last').append('<tr><td><center>'+ticks[k]+'</center></td><td><center>'+s1[k]+' %</center></td><td><center>'+s2[k]+' %</center></td></tr>');
									k++;
								});
								$('#bloomslevelplannedcoveragedistribution_note').append('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above bar graph depicts the individual Blooms Level planned coverage percentage distribution and Blooms Level actual coverage percentage distribution as in the question paper.<br>Planned distribution is defined by the QP Framework.</td></tr><tr><td><b>Distribution % = ((Count of questions at each Blooms Level) / (Total number of questions) ) * 100 </b></td></tr></tbody></table></div>');	 */				
								//BloomsLevelMarksDistribution		
								var blooms_level_marks_dist = $('#blooms_level_marks_dist').val();
								var total_marks_marks_dist = $('#total_marks_marks_dist').val();
								var percentage_distribution_marks_dist = $('#percentage_distribution_marks_dist').val();
								var bloom_level_marks_desc = $('#bloom_level_marks_description').val();
								blooms_level = blooms_level_marks_dist.split(",");
								total_marks_dist = total_marks_marks_dist.split(",");
								percentage_dist = percentage_distribution_marks_dist.split(",");
								bloom_lvl_marks_desc = bloom_level_marks_desc.split(",");
								actual_data = new Array();
								var i = 0;
								var j = 0;
								$.each(blooms_level,function() {
									var bloom_lvl = blooms_level[i];
									var percent_distr = percentage_dist[i];
									data = new Array();					
									data.push(bloom_lvl,Number(percent_distr));
									i++;
									actual_data[j++] = data;
								});
								plot2 = jQuery.jqplot('chart2', [actual_data],{
										title: {
											text: '',//Blooms Level Planned Marks Distribution',
											show: true 
										},							
										seriesDefaults: {
											renderer: jQuery.jqplot.PieRenderer,
											rendererOptions: {
												fill: true,
												showDataLabels: true,
												sliceMargin: 4,
												lineWidth: 5,
												dataLabelFormatString: '%.2f%'
											}
										},				
										legend: {show: true, location: 'ne'},
										highlighter: {
											show: true,
											tooltipLocation: 's', 
											tooltipAxes: 'y', 
											useAxesFormatters: false,
											tooltipFormatString: '%s',
											tooltipContentEditor:function(str, seriesIndex, pointIndex, plot){
												return bloom_lvl_marks_desc[pointIndex];
											}
										}
									}); 								
								$('#bloomslevelplannedmarksdistribution > tbody:first').append('<tr><td><center><b>Blooms Level</b></center></td><td><center><b>Total Marks</b></center></td><td><center><b>% Distribution</b></center></td></tr>');
								var l = 0;
								$.each(blooms_level, function(){
									$('#bloomslevelplannedmarksdistribution > tbody:last').append('<tr><td><center>'+blooms_level[l]+'</center></td><td><center>'+total_marks_dist[l]+'</center></td><td><center>'+percentage_dist[l]+' %</center></td></tr>');
									l++;
								});					
								$('#bloomslevelplannedmarksdistribution_note').append('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above pie chart depicts the individual Blooms Level actual marks percentage distribution as in the question paper.</td></tr><tr><td><b> X = Individual Bloom\'s Level marks  <br/> Y = Grand Total Marks  <br/> Distribution (%) = (X / Y) * 100 </b></td></tr></tbody></table></div>');
								
								//COPlannedCoverageDistribution		
								var clo_code = $('#clo_code').val();
								var clo_total_marks_dist = $('#clo_total_marks_dist').val();
								var clo_percentage_dist = $('#clo_percentage_dist').val();
								var clo_statement_dist = $('#clo_statement_dist').val();
								clo_code = clo_code.split(",");
								clo_total_marks_dist = clo_total_marks_dist.split(",");
								clo_percentage_dist = clo_percentage_dist.split(",");
								clo_statement_dist = clo_statement_dist.split(",");
								actual_data = new Array();
								var i = 0;
								var j = 0;
								$.each(clo_code,function() {
									var clo_code_data = clo_code[i];
									var clo_percentage_dist_data = clo_percentage_dist[i];
									data = new Array();					
									data.push(clo_code_data,Number(clo_percentage_dist_data));
									i++;
									actual_data[j++] = data;
								});
								plot3 = jQuery.jqplot('chart3', [actual_data],{
											title: {
												text: '',//Blooms Level Planned Marks Distribution',
												show: true 
											},							
											seriesDefaults: {
												renderer: jQuery.jqplot.PieRenderer,
												rendererOptions: {
													fill: true,
													showDataLabels: true,
													sliceMargin: 4,
													lineWidth: 5,
													dataLabelFormatString: '%.2f%'
												}
											},				
											legend: {show: true, location: 'ne'},
											highlighter: {
												show: true,
												tooltipLocation: 's', 
												tooltipAxes: 'y', 
												useAxesFormatters: false,
												tooltipFormatString: '%s',
												tooltipContentEditor:function(str, seriesIndex, pointIndex, plot){
													return clo_statement_dist[pointIndex];
												}
											}
									});  
								$('#coplannedcoveragesdistribution > tbody:first').append('<tr><td><center><b>'+co_lang+' Level</b></center></td><td><center><b>Total Marks</b></center></td><td><center><b>% Distribution</b></center></td></tr>');
								var m = 0;
								$.each(clo_code, function(){
									$('#coplannedcoveragesdistribution > tbody:last').append('<tr><td><center>'+clo_code[m]+'</center></td><td><center>'+clo_total_marks_dist[m]+'</center></td><td><center>'+clo_percentage_dist[m]+' %</center></td></tr>');
									m++;
								});				
								$('#coplannedcoveragesdistribution_note').append('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above pie chart depicts the individual Course Outcome('+co_lang+') wise actual marks percentage distribution as in the question paper.</td></tr><tr><td><b> 	X = Individual CO marks <br/> Y = Grand Total marks <br/> Planned Distribution (%) = (X / Y) * 100 </b></td></tr></tbody></table></div>');	
								
								//topicCoverageDistribution
								var topic_title = $('#topic_title').val();
								var topic_marks_dist = $('#topic_marks_dist').val();
								var topic_percentage_dist = $('#topic_percentage_dist').val();
								topic_title = topic_title.split(",");
								topic_marks_dist = topic_marks_dist.split(",");
								topic_percentage_dist = topic_percentage_dist.split(",");
								actual_topic_data = new Array();
								var i = 0;
								var j = 0;
								$.each(topic_title,function() {
									var topic_title_data = topic_title[i];
									var topic_percentage_dist_data = topic_percentage_dist[i];
									topic_data = new Array();					
									topic_data.push(topic_title_data,Number(topic_percentage_dist_data));
									i++;
									actual_topic_data[j++] = topic_data;
								});
								topic_chart_plot = jQuery.jqplot('chart4', [actual_topic_data],{
											title: {
												text: '',//Blooms Level Planned Marks Distribution',
												show: true 
											},							
											seriesDefaults: {
												renderer: jQuery.jqplot.PieRenderer,
												rendererOptions: {
													fill: true,
													showDataLabels: true,
													sliceMargin: 4,
													lineWidth: 5,
													dataLabelFormatString: '%.2f%'
												}
											},				
											legend: {show: true, location: 'ne'},
											highlighter: {
												show: true,
												tooltipLocation: 's', 
												tooltipAxes: 'y', 
												useAxesFormatters: false,
												tooltipFormatString: '%s',
												tooltipContentEditor:function(str, seriesIndex, pointIndex, plot){
													return topic_title[pointIndex];
												}
											}
										});										
								$('#topicplannedcoveragesdistribution > tbody:first').append('<tr><td><center><b>'+topic_lang +'</b></center></td><td><center><b>Planned Marks</b></center></td><td><center><b>Planned Distribution</b></center></td></tr>');
								var m = 0;
								$.each(topic_title, function(){
									$('#topicplannedcoveragesdistribution > tbody:last').append('<tr><td><center>'+topic_title[m]+'</center></td><td><center>'+topic_marks_dist[m]+'</center></td><td><center>'+topic_percentage_dist[m]+' %</center></td></tr>');
										m++;
								});				
								$('#topicplannedcoveragesdistribution_note').append('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above pie chart depicts the '+topic_lang +' Coverage wise actual marks percentage distribution as in the question paper.</td></tr><tr><td><b> Planned Distribution % = (Sum(Count of individual '+topic_lang +' marks) * 100) / (Total marks)</b></td></tr></tbody></table></div>');
							//	});	
								$('.myModalQPdisplay_paper_modal_2').on('shown',function(){
									plot1.replot();
									plot2.replot();
									plot3.replot();
									topic_chart_plot.replot();
								});	
								
								
								
							} 
						});
					$('.myModalQPdisplay_paper_modal_2').modal('show');		
				}
				if(msg == 0){
					$('#loading').hide();
					$('#qp_not_defined_modal').modal('show');
				}
			}
		});
	});
	
	$('#export').on('click',function()
	{	
		var question_paper = $('#qp_content_pdf').clone().html();
	 	var total_val = $('#total').clone().html();
		var imgData_1 = $('#chart1').jqplotToImageStr({});
		var imgElem_1 = $('<img/>').attr('src',imgData_1);
		$('#chart1_img').html('<b>Blooms Planned Coverage Distribution</b><br><br>');
		$('#chart1_img').append(imgElem_1);
		$('#chart1_img').append($('#bloomslevelplannedcoveragedistribution_div').clone().html());
		$('#chart1_img').append($('#bloomslevelplannedcoveragedistribution_note').clone().html()); 
		
		var imgData_2 = $('#chart2').jqplotToImageStr({});
		var imgElem_2 = $('<img/>').attr('src',imgData_2);
		$('#chart2_img').html('<b>Blooms Planned Marks Distribution</b><br><br>');
		$('#chart2_img').append(imgElem_2);
		$('#chart2_img').append($('#bloomslevelplannedmarksdistribution_div').clone().html());
		$('#chart2_img').append($('#bloomslevelplannedmarksdistribution_note').clone().html());
		
		var imgData_3 = $('#chart3').jqplotToImageStr({});
		var imgElem_3 = $('<img/>').attr('src',imgData_3);
		$('#chart3_img').html('<b>Course Outcome Planned Coverage Distribution</b><br><br>');
		$('#chart3_img').append(imgElem_3);
		$('#chart3_img').append($('#coplannedcoveragesdistribution_div').clone().html());
		$('#chart3_img').append($('#coplannedcoveragesdistribution_note').clone().html());
		
		
		//Topic Coverage Distribution
		var imgData_4 = $('#chart4').jqplotToImageStr({});
		var imgElem_4 = $('<img/>').attr('src',imgData_4);
		$('#chart4_img').html('<b>'+topic_lang+' Coverage Distribution</b><br><br>');
		$('#chart4_img').append(imgElem_4);
		$('#chart4_img').append($('#topicplannedcoveragesdistribution_div').clone().html());
		$('#chart4_img').append($('#topicplannedcoveragesdistribution_note').clone().html());
		
		var chart1_img = $('#chart1_img').clone().html();
		var chart2_img = $('#chart2_img').clone().html();
		var chart3_img = $('#chart3_img').clone().html();
		var chart4_img = $('#chart4_img').clone().html();
		
		$('#qp_hidden').val(question_paper);
		$('#total_val_hidden').val(total_val);		
		$('#chart1_img_hidden').val(chart1_img);	
		$('#chart2_img_hidden').val(chart2_img);	
		$('#chart3_img_hidden').val(chart3_img);	
		$('#chart4_img_hidden').val(chart4_img);	
		$('#export_model_qp_pdf').submit();
	});	
	
	$('#example').on('click','.delete_qp',function(){
		$('#pgmtype_id').val($(this).attr('data-pgm_id'));
		$('#crclm_id').val($(this).attr('data-crclm_id'));
		$('#term_id').val($(this).attr('data-term_id'));
		$('#crs_id').val($(this).attr('data-crs_id'));
 		$.ajax({type: "POST",
						url: base_url+'question_paper/manage_model_qp/fetch_model_qp_details',
						data: {'pgmtype_id' : $(this).attr('data-pgm_id'),'crclm_id':$(this).attr('data-crclm_id'),'term_id':$(this).attr('data-term_id'),'crs_id':$(this).attr('data-crs_id')},
						dataType: 'JSON',
						success: function(msg){						
						if(msg==0){$('#qp_not_defined_modal').modal('show');}else{$('#model_qp_delete').modal('show');}
						}}); 
		
		

	});
	
	$('#delete_qp').on('click',function(){
	var pgmtype_id=$('#pgmtype_id').val();
	var crclm_id=$('#crclm_id').val();
	var term_id=$('#term_id').val();
	var crs_id=$('#crs_id').val();
 		$.ajax({type: "POST",
						url: base_url+'question_paper/manage_model_qp/delete_model_qp',
						data: {'pgmtype_id' : pgmtype_id,'crclm_id':crclm_id,'term_id':term_id,'crs_id':crs_id},
						dataType: 'JSON',
						success: function(msg){
						if(msg){ location.reload();}
						}}); 
	});
        
   /*
    * Related Function to IMPORT Model Question Paper Begins From Here.
    * Author: Mritunjay B S.
    * Start Date: 13/4/2016
    */
   
   $('#example').on('click','.import_model_qp',function(){
      var dept_id = $('#department').val();
      var pgm_type_id = $('#program').val();
      var crclm_id = $('#curriculum').val();
      var term_id = $('#term').val();
      var crs_id = $(this).attr('data-crs_id');
      var crs_title = $(this).attr('data-crs_title');
      var crclm_name = $('#curriculum option:selected').text()
      var term_name = $('#term option:selected').text();
      $('#pop_dept_list').empty();
      $('#pop_prog_list').empty();
      $('#pop_prog_list').html('<option value>Select Program</option>');
      $('#pop_crclm_list').empty();
      $('#pop_crclm_list').html('<option value>Select Curriculum</option>');
      $('#pop_term_list').empty();
      $('#pop_term_list').html('<option value>Select Term</option>');
      $('#pop_course_list').empty();
      $('#pop_course_list').html('<option value>Select Course</option>');
      $('#import_qp_button').prop('disabled',true);
      var post_data = {'program_type':pgm_type_id,'crclm_id':crclm_id,'term_id':term_id,'crs_id':crs_id};
      $.ajax({type: "POST",
			url: base_url + 'question_paper/manage_model_qp/get_dept_list',
			data: post_data,
			//dataType: 'json',
			success: function(data){
                            $('#program_id').val(pgm_type_id);   
                            $('#curriculum_id').val(crclm_id);   
                            $('#qpterm_id').val(term_id);   
                            $('#course_id').val(crs_id);   
                            $('#pop_dept_list').empty();
                            $('#pop_dept_list').html(data);
                            $('#crlcm_name_font').empty();
                            $('#crlcm_name_font').html(crclm_name);
                            $('#term_name_font').empty();
                            $('#term_name_font').html(term_name);
                            $('#crs_name_font').empty();
                            $('#crs_name_font').html(crs_title);
                            $('#import_model_question_paper').modal('show');
                        }
                    });
         
      
   });
   
   $('#import_model_question_paper').on('change','#pop_dept_list',function(){
      var dept_id = $(this).val();
      var post_data = {'dept_id':dept_id};
      $.ajax({type: "POST",
			url: base_url + 'question_paper/manage_model_qp/get_program_list',
			data: post_data,
			//dataType: 'json',
			success: function(data){
                            $('#pop_prog_list').empty();
                            $('#pop_prog_list').html(data);
                        }
                    });
   });
   
   $('#import_model_question_paper').on('change','#pop_prog_list',function(){
      var dept_id = $('#pop_dept_list').val();
      var prog_id = $(this).val();
      var post_data = {'dept_id':dept_id,'pgm_id':prog_id};
      $.ajax({type: "POST",
			url: base_url + 'question_paper/manage_model_qp/get_curriculum_list',
			data: post_data,
			//dataType: 'json',
			success: function(data){
                            $('#pop_crclm_list').empty();
                            $('#pop_crclm_list').html(data);
                        }
                    });
   });
   
   $('#import_model_question_paper').on('change','#pop_crclm_list',function(){
      var dept_id = $('#pop_dept_list').val();
      var prog_id = $('#pop_prog_list').val();
      var crclm_id = $(this).val();
      var post_data = {'dept_id':dept_id,'pgm_id':prog_id,'crclm_id':crclm_id};
      $.ajax({type: "POST",
			url: base_url + 'question_paper/manage_model_qp/get_term_list',
			data: post_data,
			//dataType: 'json',
			success: function(data){
                            $('#pop_term_list').empty();
                            $('#pop_term_list').html(data);
                        }
                    });
   });
   
   $('#import_model_question_paper').on('change','#pop_term_list',function(){
      var dept_id = $('#pop_dept_list').val();
      var prog_id = $('#pop_prog_list').val();
      var crclm_id = $('#pop_crclm_list').val();
      var to_crs_id = $('#course_id').val();
      var term_id = $(this).val();
      var post_data = {'dept_id':dept_id,'pgm_id':prog_id,'crclm_id':crclm_id,'term_id':term_id,'to_crs_id':to_crs_id,};
      $.ajax({type: "POST",
			url: base_url + 'question_paper/manage_model_qp/get_course_list',
			data: post_data,
			//dataType: 'json',
			success: function(data){
                            $('#pop_course_list').empty();
                            $('#pop_course_list').html(data);
                        }
                    });
   });
   
    $('#import_model_question_paper').on('change','#pop_course_list',function(){
      var from_dept_id = $('#pop_dept_list').val();
      var from_prog_id = $('#pop_prog_list').val();
      var from_crclm_id = $('#pop_crclm_list').val();
      var from_term_id = $('#pop_term_list').val();
      var from_crs_id = $(this).val();
      var to_crs_id = $('#course_id').val();
      var to_term_id = $('#qpterm_id').val();
      var to_crclm_id = $('#curriculum_id').val();
     
      var post_data = {'from_dept_id':from_dept_id,'from_pgm_id':from_prog_id,'from_crclm_id':from_crclm_id,'from_term_id':from_term_id,'from_crs_id':from_crs_id,'to_crs_id':to_crs_id,'to_term_id':to_term_id,'to_crclm_id':to_crclm_id};
      $.ajax({type: "POST",
			url: base_url + 'question_paper/manage_model_qp/get_question_paper_list',
			data: post_data,
			//dataType: 'json',
			success: function(data){
                            $('#qp_list_div').empty();
                            $('#qp_list_div').html(data);
                        }
                    });
   });
   
   $('#import_model_question_paper').on('click','.selected_model_qp',function(){
      
       if($(this).is(':checked')){
          $('#import_qp_button').prop('disabled',false);
       }else{
           $('#import_qp_button').prop('disabled',true);
       }
     
   });
   
   $('#import_model_question_paper').on('click','#import_qp_button',function(){
      
    var validate = $('#select_form').valid();
    var qpd_id;
   $('.selected_model_qp').each(function(){
       if($(this).is(':checked')){
           qpd_id = $(this).attr('data-qpd_id');
       }
   });
    var to_crclm_id = $('#curriculum_id').val();
    var to_term_id = $('#qpterm_id').val();
    var to_crs_id = $('#course_id').val();
    //var ao_id = $('#occasion_ao_id').val();
    var post_data = {'qpd_id':qpd_id,'to_crclm_id':to_crclm_id,'to_term_id':to_term_id,'to_crs_id':to_crs_id};
                if(validate == true){
                            $.ajax({type: "POST",
                                      url: base_url + 'question_paper/manage_model_qp/existance_of_qp',
                                      data: post_data,
                                      //dataType: 'json',
                                      success: function(data){
                                          
                                          if($.trim(data)>=1){
                                                  $('#curriculum_id_one').val(to_crclm_id);
                                                  $('#qpterm_id_one').val(to_term_id);
                                                  $('#course_id_one').val(to_crs_id);
                                                  //$('#ao_id_one').val(ao_id);
                                                  $('#qpd_id_one').val(qpd_id);
                                                  $.fn.modal.Constructor.prototype.enforceFocus = function () {};
                                                  $('#model_existance_body_msg').html('Model Question Paper already exist under this Course. Do you want to overwrite the existing question paper ?');
                                                  
                                                  //$.fn.modal.Constructor.prototype.enforceFocus = function () {};
                                                  $('#import_model_qp_existance').modal('show');
                                              }else{
                                                    $('#loading').show();
                                                      $.ajax({type: "POST",
                                                        url: base_url + 'question_paper/manage_model_qp/get_qp_data_import',
                                                        data: post_data,
                                                        //dataType: 'json',
                                                        success: function(msg){
                                                                if($.trim(msg) == "true"){
                                                                    
                                                                      var data_options = '{"text":"QP import is successfull.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                                                                      var options = $.parseJSON(data_options);
                                                                      noty(options);

                                                                }
                                                                $('#loading').hide();
                                                        }
                                                    });
                                              }


                                      }
                              });
                            
                                             
                }
   
    
});

$('#import_model_qp_existance').on('click','#overwrite_qp',function(){
    var to_crclm_id = $('#curriculum_id_one').val();
    var to_term_id =  $('#qpterm_id_one').val();
    var to_crs_id  =  $('#course_id_one').val();
   // var ao_id   =  $('#ao_id_one').val();
    var qpd_id = $('#qpd_id_one').val();
    var post_data = {'qpd_id':qpd_id,'to_crs_id':to_crs_id,'to_term_id':to_term_id,'to_crclm_id':to_crclm_id};
    $('#loading_popup').show();
    $.ajax({type: "POST",
			url: base_url + 'question_paper/manage_model_qp/overwrite_qp',
			data: post_data,
			//dataType: 'json',
			success: function(data){
                            if($.trim(data) == "true" ){
                                
                                var data_options = '{"text":"QP overwrite is successfull.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
				var options = $.parseJSON(data_options);
				noty(options);
                            }else{
                                
                                var data_options = '{"text":"<b>This question paper already exist for this occasion.</b> </br> <b>You cannot import the same question paper for this occasion.</b>","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
				var options = $.parseJSON(data_options);
				noty(options);
                            }
                            
                            $('#loading_popup').hide();
                            $('#import_model_qp_existance').modal('hide');
                        }
                    });
    
});



$("#select_form").validate({
	
        errorClass: "help-inline font_color",
        wrapper: "span",
        highlight: function (label) {
            $(label).closest('.control-group').removeClass('success').addClass('error');
        },
        errorPlacement: function (error, element) {
            if (element.parent().parent().hasClass("input-append")) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element.parent());
            }
        },
        onkeyup: false,
        onblur: false,
        success: function (error,label) {
            $(label).closest('.control-group').removeClass('error').addClass('success');
        } 
	
    });
    
       
    $('.cancel').on('click',function(){
    $('#question_paper_list').remove();
    var validator = $( "#select_form" ).validate();
        validator.resetForm();
    
})

    
     $('.noty').click(function () {
        
        var options = $.parseJSON($(this).attr('data-noty-options'));
        noty(options);
		
    });
   $(document).ready(function () {
	    $('#import_model_qp_existance').on('show.bs.modal', function () {
	        $('#import_model_question_paper').css('z-index', 1039);
	    });

	    $('#import_model_qp_existance').on('hidden.bs.modal', function () {
	        $('#import_model_question_paper').css('z-index', 1041);
	    });
	});