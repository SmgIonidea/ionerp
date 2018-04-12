
	//import assessment data (TEE Assessment Data Import)
	
	var base_url = $('#get_base_url').val();
	$("#hint a").tooltip();
	var crs_id;
	var table_row;
	
	/* Function is used to fetch term ids & term names by sending the curriculum id to controller.
	* @param - curriculum id.
	* @returns- an array of term ids & term names.
	*/		
	function select_pgm_list() {
		var dept_id = document.getElementById('department').value;
		
		var post_data = {
			'dept_id': dept_id
		}
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/import_assessment_data/select_pgm_list',
			data: post_data,
			success: function(msg) {
				document.getElementById('program').innerHTML = msg;
			}
		});
	}
	
	function select_crclm_list() {
		var pgm_id = document.getElementById('program').value;
		var post_data = {
			'pgm_id': pgm_id
		} 
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/import_assessment_data/select_crclm_list',
			data: post_data,
			success: function(msg) {
				document.getElementById('curriculum').innerHTML = msg;
			}
		});
	}
	
	//function to fetch term dropdown
	$('#curriculum').on('change',function(){
		var crclm_id = document.getElementById('curriculum').value;
		
		var post_data = {
			'crclm_id': crclm_id
		}
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/import_assessment_data/select_termlist',
			data: post_data,
			success: function(msg) {
				document.getElementById('term').innerHTML = msg;
			}
		});
	});
		
	//function to fetch course
	$('#term').on('change',function(){
		//$('#example').dataTable().fnDestroy();
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
			url: base_url+'assessment_attainment/import_assessment_data/show_course',
			data: post_data,
			dataType: 'json',
			success: populate_table
		});
	});

	// List View JS functions.
	$('.get_crs_id').live("click", function() {
		crs_id = $(this).attr('id');
		table_row = $(this).closest("tr").get(0);
	});

	/* Function is used to generate table grid of  course details.
	* @param - 
	* @returns- an array of course details.
	*/	
	function populate_table(msg) {
		var m = 'd';
		$('#example_table').dataTable().fnDestroy();
		$('#example_table').dataTable(
				{"aoColumns": [
						{"sTitle": "Sl No.", "mData": "sl_no"},
						{"sTitle": "Code", "mData": "crs_code"},
						{"sTitle": "Course Title", "mData": "crs_title"},
						{"sTitle": "Core / Elective", "mData": "crs_type_name"},
						{"sTitle": "Credits", "mData": "total_credits"},
						{"sTitle": "Total Marks", "mData": "total_marks"},
						{"sTitle": "Course Owner", "mData": "username"},
						{"sTitle": "Mode", "mData": "crs_mode"},
						{"sTitle": "View QP", "mData": "view_qp"},
						{"sTitle": "Template Actions", "mData": "export_import"},
						{"sTitle": "Import Status", "mData": "qp_status_flag"}
					], "aaData": msg,
					"sPaginationType": "bootstrap"});
	}
	
	//to display QP details on click of view QP
	$(document).on('click','.displayQP',function(){
		document.getElementById('qp_content_display').innerHTML = "";
		$('#loading').show();
		var abbr_val = $(this).attr('abbr');
		var data = abbr_val.split("/");
		var pgmtype = data[0];
		var crclm_id = data[1];
		var term_id = data[2];
		var crs_id = data[3];
		var qp_type = data[4];
		var qpd_id = data[5];
		var post_data = {
			'pgmtype' : pgmtype,
			'crclm_id': crclm_id,
			'term_id' : term_id,
			'crs_id' : crs_id,
			'qp_type' : qp_type,
			'qpd_id' : qpd_id
		}
		
		$.ajax({type:"POST",
			url: base_url+'question_paper/manage_model_qp/generate_model_qp_modal_tee',
			data: post_data,
			success:function(msg){
				document.getElementById('qp_content_display').innerHTML = msg;
				$('#loading').hide();
				
				// BloomsPlannedCoverageDistribution
				if($('#no_data').val() == 0) {
					$('#export').hide();
				} else {
					$('#export').show();
					var plot1, plot2, plot3;
					var BloomsLevel = $('#BloomsLevel').val();
					var PlannedPercentageDistribution = $('#PlannedPercentageDistribution').val();
					var ActualPercentageDistribution = $('#ActualPercentageDistribution').val();
					var BloomsLevelDescription = $('#BloomsLevelDescription').val();
					var pln = PlannedPercentageDistribution.split(",");
					var actual = ActualPercentageDistribution.split(",");
					var ticks = BloomsLevel.split(",");	 
					var level = BloomsLevelDescription.split(",");
			/* 		plot1 = $.jqplot('chart1', [actual,pln], {
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
								tickRenderer: $.jqplot.CanvasAxisTickRenderer,
								ticks: ticks
							},
							yaxis: {
								min:0,
								max:100,
								tickOptions: {
									formatString: '%d'
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
					}); */
					var k = 0;
					$('#bloomslevelplannedcoveragedistribution > tbody:first').append("<tr><td><center><b>Bloom's Level</b></center></td><td><center><b>Framework level Distribution</b></center></td><td><center><b>Planned Distribution</b></center></td></tr>");
					$.each(ticks, function(){
						$('#bloomslevelplannedcoveragedistribution > tbody:last').append('<tr><td><center>'+ticks[k]+'</center></td><td><center>'+actual[k]+' %</center></td><td><center>'+pln[k]+' %</center></td></tr>');
						k++;
					});
					$('#bloomslevelplannedcoveragedistribution_note').append("<br><div class='bs-docs-example'><b>Note:</b><table class='table table-bordered'><tbody><tr><td>The above bar graph depicts the individual Bloom's Level planned coverage percentage distribution and Bloom's Level actual coverage percentage distribution as in the question paper.<br>Planned distribution is defined by the QP Framework.</td></tr><tr><td><b>Distribution % = ((Count of questions at each Bloom's Level) / (Total number of questions) ) * 100 </b></td></tr></tbody></table></div>");
					
					// BloomsLevelMarksDistribution		
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
										dataLabelFormatString: '%.2f%%'
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
					$('#bloomslevelplannedmarksdistribution > tbody:first').append("<tr><td><center><b>Bloom's Level</b></center></td><td><center><b>Marks Distribution</b></center></td><td><center><b>% Distribution</b></center></td></tr>");
					var l = 0;
					$.each(blooms_level, function(){
						$('#bloomslevelplannedmarksdistribution > tbody:last').append('<tr><td><center>'+blooms_level[l]+'</center></td><td><center>'+total_marks_dist[l]+'</center></td><td><center>'+percentage_dist[l]+' %</center></td></tr>');
						l++;
					});					
					$('#bloomslevelplannedmarksdistribution_note').append("<br><div class='bs-docs-example'><b>Note:</b><table class='table table-bordered'><tbody><tr><td>The above pie chart depicts the individual Bloom's Level actual marks percentage distribution as in the question paper.</td></tr><tr><td><b> Distribution % = (Sum(Count of individual Bloom's Level marks) * 100) / (Total marks)</b></td></tr></tbody></table></div>");
					// COPlannedCoverageDistribution		
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
										dataLabelFormatString: '%.2f%%'
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
					$('#coplannedcoveragesdistribution > tbody:first').append('<tr><td><center><b>CO Level</b></center></td><td><center><b>Planned Marks</b></center></td><td><center><b>Planned Distribution</b></center></td></tr>');
					var m = 0;
					$.each(clo_code, function(){
						$('#coplannedcoveragesdistribution > tbody:last').append('<tr><td><center>'+clo_code[m]+'</center></td><td><center>'+clo_total_marks_dist[m]+'</center></td><td><center>'+clo_percentage_dist[m]+' %</center></td></tr>');
						m++;
					});				
					$('#coplannedcoveragesdistribution_note').append('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above pie chart depicts the individual Course Outcome(CO) wise actual marks percentage distribution as in the question paper.</td></tr><tr><td><b> Planned Distribution % = (Sum(Count of individual CO marks) * 100) / (Total marks)</b></td></tr></tbody></table></div>');
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
									$('#topicplannedcoveragesdistribution > tbody:first').append('<tr><td><center><b>'+entity_topic+'</b></center></td><td><center><b>Planned Marks</b></center></td><td><center><b>Planned Distribution</b></center></td></tr>');
									var m = 0;
									$.each(topic_title, function(){
										$('#topicplannedcoveragesdistribution > tbody:last').append('<tr><td><center>'+topic_title[m]+'</center></td><td><center>'+topic_marks_dist[m]+'</center></td><td><center>'+topic_percentage_dist[m]+' %</center></td></tr>');
										m++;
									});				
									$('#topicplannedcoveragesdistribution_note').append('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above pie chart depicts the '+entity_topic +'Coverage wise actual marks percentage distribution as in the question paper.</td></tr><tr><td><b> Planned Distribution % = (Sum(Count of individual '+ entity_topic +'marks) * 100) / (Total marks)</b></td></tr></tbody></table></div>');
					$('.myModalQPdisplay_paper_modal_2').on('shown',function(){
							plot1.replot();
							plot2.replot();
							plot3.replot();
							topic_chart_plot.replot();
						});
				}
			}
		});	
		$('.myModalQPdisplay_paper_modal_2').modal('show');
	});
        
        // Code added by Mritunjay B S
// Date: 27/12/2016.
$(document).on('click','.tee_qp_download_template',function(){
    var crclm_id = $(this).attr('data-crclm_id');
   var download_excel_url = $(this).attr('abbr_href');
   var post_data = {
       'crclm_id':crclm_id,
   };
   $.ajax({ type: "POST",
            url: base_url + 'assessment_attainment/import_assessment_data/check_student_uploaded_or_not',
            data: post_data,
            dataType: "JSON",
            success: function(msg) {
                console.log(msg.student_count);
                if($.trim(msg.student_count) != 0){
                    window.location = download_excel_url;
                }else{
                    var link  = base_url+'curriculum/student_list';
                    $('#students_upload_link').attr('href',link);
                    $('#students_not_uploaded_modal').modal('show');
                }
	}
		});
});

/*
 * Function to check Target Levels are Exists are not
 * code added by : Mritunjay B S
 * Date : 25/11/2016
 */
$('#example_table').on('click','.import_data_details',function(e){
    var crclm_id = $(this).attr('data-crclm_id');
    var term_id = $(this).attr('data-term_id');
    var crs_id = $(this).attr('data-crs_id');
    var url = $(this).attr('abbr_href');
    var link = '';
    var post_data = { 'crclm_id':crclm_id,
                      'term_id':term_id,
                      'crs_id':crs_id,
                  };
                  
    $.ajax({type: "POST",
			url: base_url + 'assessment_attainment/import_assessment_data/get_organisation_type',
			data: post_data,
			dataType: "JSON",
			success: function(msg) {
				if($.trim(msg.org_type) == 'org2'){
                                    if(msg.admin_hod_po_val == 1){
                                        link = base_url + 'tier_ii/attainment_level';
                                        $('#define_threshold_target').attr('href',link);
                                    }else{
                                        $('#link_stmt').hide();
                                        $('#link_stmt').empty();
                                        $('#link_stmt').html('Request Chairman(HoD) or Program Owner to add Thresholds/Targets OR Attainment Levels.');
                                        $('#link_stmt').show();
                                    }
				    
				}else{
                                    if(msg.admin_hod_po_val == 1){
                                        link = base_url + 'assessment_attainment/bl_po_co_threshold'; 
                                        $('#define_threshold_target').attr('href',link);
                                    }else{
                                        $('#link_stmt').hide();
                                        $('#link_stmt').empty();
                                        $('#link_stmt').html('Request Chairman(HoD) or Program Owner to add Thresholds/Targets OR Attainment Levels.');
                                        $('#link_stmt').show();
                                    }
				}
				
				if($.trim(msg.target_or_threshold) != 0 && $.trim(msg.org_type) == 'org2'){
					window.location = url;  
				}else if($.trim(msg.target_or_threshold) != 0 && $.trim(msg.org_type) == 'org1'){
					 window.location = url;
				}else{
					
					$('#target_or_threshold_warning_modal').modal('show');
				}
			}
		});
    
});
$(document).on('click','.view_tee_rubrics',function(){
    var ao_method_id = $(this).attr('data-ao_method_id');
    var qpd_id = $(this).attr('data-qpd_id');
    var post_data = {
                        'ao_method_id':ao_method_id,
                        'qpd_id':qpd_id
                    };
    $.ajax({type: "POST",
	url: base_url + 'assessment_attainment/import_assessment_data/get_rubrics_table_modal_view',
	data: post_data,
	dataType: 'html',
	success: function (data) {
            if($.trim(data)!='false'){
            $('#rubrics_table_div').empty();
            $('#rubrics_table_div').html(data);
        }else{
            $('#rubrics_table_div').empty();
            $('#rubrics_table_div').html('<center><font color="red"><b>No Data to Display</b></font></center>');
        }
            $('#rubrics_table_view_modal').modal('show');
            
        }
    });
});
	
	$('#export').on('click',function(e){				
		var imgData_1 = $('#chart1').jqplotToImageStr({});
		var imgElem_1 = $('<img/>').attr('src',imgData_1);
		$('#chart1_img').html('<br><b>Blooms Planned Coverage Distribution</b>');
		$('#chart1_img').append(imgElem_1);
		$('#chart1_img').append($('#bloomslevelplannedcoveragedistribution_div').clone().html());
		$('#chart1_img').append($('#bloomslevelplannedcoveragedistribution_note').clone().html());
		
		var imgData_2 = $('#chart2').jqplotToImageStr({});
		var imgElem_2 = $('<img/>').attr('src',imgData_2);
		$('#chart2_img').html('<b>Blooms Planned Marks Distribution</b>');
		$('#chart2_img').append(imgElem_2);
		$('#chart2_img').append($('#bloomslevelplannedmarksdistribution_div').clone().html());
		$('#chart2_img').append($('#bloomslevelplannedmarksdistribution_note').clone().html());
		
		var imgData_3 = $('#chart3').jqplotToImageStr({});
		var imgElem_3 = $('<img/>').attr('src',imgData_3);
		$('#chart3_img').html('<br><b>Course Outcome Planned Coverage Distribution</b>');
		$('#chart3_img').append(imgElem_3);
		$('#chart3_img').append($('#coplannedcoveragesdistribution_div').clone().html());
		$('#chart3_img').append($('#coplannedcoveragesdistribution_note').clone().html());
		
		//Topic Coverage Distribution
		var imgData_4 = $('#chart4').jqplotToImageStr({});
		var imgElem_4 = $('<img/>').attr('src',imgData_4);
		$('#chart4_img').html('<br><b>'+entity_topic +'Coverage Distribution</b><br>');
		$('#chart4_img').append(imgElem_4);
		$('#chart4_img').append($('#topicplannedcoveragesdistribution_div').clone().html());
		$('#chart4_img').append($('#topicplannedcoveragesdistribution_note').clone().html());
		
		var question_paper = $('#qp_content_pdf').clone().html();
		var total_val = $('#total').clone().html();
		
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
	
	//upload .csv file
	var uploader = document.getElementById('uploader');
	var crs_id = $('#crs_id').val();
	var qpd_id = $('#qpd_id').val();
	var ao_id = $('#ao_id').val();
	
	upclick({
		element: uploader,
		action: base_url+'assessment_attainment/import_assessment_data/to_database'+'/'+crs_id+'/'+qpd_id+'/'+ao_id,
		oncomplete:
			function(response_data) {
				if($.trim(response_data) == '0') {
					$('#incorrect_file_name').modal('show');
				} else if($.trim(response_data) == '3') {
					$('#incorrect_file_header').modal('show');
				} else if($.trim(response_data) == '4') {
					$('#csv_file_empty').modal('show');
				} else {
					//display data in temp_generate_table
					$('#student_marks').html(response_data);
				}
			}
	 });
	
	//insert into main table
	function insert_into_main_table() {            
		$('#loading').show();
		var qpd_id = $('#qpd_id').val();
		var crs_id = $('#crs_id').val();
		var ao_id = $('#ao_id').val();
		var crclm_id = $('#crclm_id').val();	
                var term_id = $('#term_id').val();
                var section_id =  $('#section_id').val(); 
		var post_data = {
			'qpd_id': qpd_id,
			'crs_id': crs_id,
			'ao_id': ao_id,
                        'crclm_id' :crclm_id,
                        'term_id':term_id,
                        'section_id' : section_id
		}
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/import_assessment_data/insert_into_student_table',
			data: post_data,
			success: function(msg) {
				if(msg == 0) {
					$('#remarks_pending').modal('show');
				} else if (msg == 1) {
					$('#insert_complete').modal('show');
				} else {
					$('#file_not_uploaded').modal('show');
				}
				$('#loading').hide();
			}
		});
	}
	
	//return back to main page
	function main_page() {	
	
		window.location = base_url+'assessment_attainment/import_assessment_data/index';	
	}
	
	//return back to cia add page
	function cia_add_page() {
		var dept_id = $('#dept_id').val();
		var prog_id = $('#prog_id').val();
		var crclm_id = $('#crclm_id').val();
		var term_id = $('#term_id').val();
		var crs_id = $('#crs_id').val();
		var section_id = $('#section_id').val();
	
		window.location = base_url+'assessment_attainment/import_cia_data/manage_cia_marks/'+dept_id+'/'+prog_id+'/'+crclm_id+'/'+term_id+'/'+crs_id+'/'+section_id;
	}	
	
	function mte_add_page() {
		var dept_id = $('#dept_id').val();
		var prog_id = $('#prog_id').val();
		var crclm_id = $('#crclm_id').val();
		var term_id = $('#term_id').val();
		var crs_id = $('#crs_id').val();
		var section_id = 0;
	
		window.location = base_url+'assessment_attainment/import_mte_data/manage_mte_marks/'+dept_id+'/'+prog_id+'/'+crclm_id+'/'+term_id+'/'+crs_id+'/'+section_id;
	}
	
	//drop main table - confirm
	function drop_main_table_confirm() {
		$('#drop_main_table_confirmation').modal('show');
	}
	
	//drop temporary table on cancel / discard
	function drop_temp_table() {
		var crs_id = $('#crs_id').val();
		
		var post_data = {
			'crs_id': crs_id
		}
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/import_assessment_data/drop_temp_table',
			data: post_data,
			success: function(msg) {
				
			}
		});
		window.location = base_url+'assessment_attainment/import_assessment_data/index';
	}
	
	//CIA drop temporary table
	function cia_drop_temp_table() {
		var dept_id = $('#dept_id').val();
		var prog_id = $('#prog_id').val();
		var crclm_id = $('#crclm_id').val();
		var term_id = $('#term_id').val();
		var crs_id = $('#crs_id').val();
		var section_id = $('#section_id').val();
		
		var post_data = {
			'crs_id': crs_id
		}
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/import_assessment_data/drop_temp_table',
			data: post_data,
			success: function(msg) {
				
			}
		});
		window.location = base_url+'assessment_attainment/import_cia_data/manage_cia_marks/'+dept_id+'/'+prog_id+'/'+crclm_id+'/'+term_id+'/'+crs_id+'/'+section_id;
	}	
	
	function mte_drop_temp_table() {
		var dept_id = $('#dept_id').val();
		var prog_id = $('#prog_id').val();
		var crclm_id = $('#crclm_id').val();
		var term_id = $('#term_id').val();
		var crs_id = $('#crs_id').val();
		var section_id = $('#section_id').val();
		
		var post_data = {
			'crs_id': crs_id
		}
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/import_assessment_data/drop_temp_table',
			data: post_data,
			success: function(msg) {
				
			}
		});
		window.location = base_url+'assessment_attainment/import_mte_data/manage_mte_marks/'+dept_id+'/'+prog_id+'/'+crclm_id+'/'+term_id+'/'+crs_id+'/'+0;
	}
	
	//drop main table
	function drop_main_table() {
		var qpd_id = $('#qpd_id').val();
		
		var post_data = {
			'qpd_id': qpd_id
		}
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/import_assessment_data/drop_main_table',
			data: post_data,
			success: function(msg) {
				
			}
		});
		window.location = base_url+'assessment_attainment/import_assessment_data/index';
	}	
	
	
	//drop main table
	function mte_drop_main_table() {
		var qpd_id = $('#qpd_id').val();
		
		var post_data = {
			'qpd_id': qpd_id
		}
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/import_assessment_data/drop_main_table',
			data: post_data,
			success: function(msg) {
				
			}
		});
		
		mte_add_page();
	//	window.location = base_url+'assessment_attainment/import_cia_data/manage_cia_marks/'+dept_id+'/'+prog_id+'/'+crclm_id+'/'+term_id+'/'+crs_id+'/'+section_id;
	}
	
	//cia drop main table
	function cia_drop_main_table() {
		var dept_id = $('#dept_id').val();
		var prog_id = $('#prog_id').val();
		var crclm_id = $('#crclm_id').val();
		var term_id = $('#term_id').val();
		var crs_id = $('#crs_id').val();
		var qpd_id = $('#qpd_id').val();
		var section_id = $('#section_id').val();
		
		var post_data = {
			'qpd_id': qpd_id
		}
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/import_assessment_data/drop_main_table',
			data: post_data,
			success: function(msg) {
				
			}
		});
		window.location = base_url+'assessment_attainment/import_cia_data/manage_cia_marks/'+dept_id+'/'+prog_id+'/'+crclm_id+'/'+term_id+'/'+crs_id+'/'+section_id;
	}

	//to display data analysis modal
	function dataAnalysis() {
		var qpd_id = $('#qpd_id').val();
		
		var post_data = {
			'qpd_id': qpd_id
		}
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/import_assessment_data/dataAnalysis',
			data: post_data,
			beforeSend:function(){
				$('#loading').show();
			},
			success: function(msg) {
				document.getElementById('dataAnalysis_modal').innerHTML = msg;
				$('#loading').hide();
			}
		});
		$('#myModal_dataAnalysis').modal('show');
	}


	
