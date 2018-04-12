var base_url = $('#get_base_url').val();
var cie_counter = new Array();
cie_counter.push(1);
var co_po_attainment_total=0;

function empty_all_divs(){ 
	$('#data_div').empty();
	$('#co_level_nav').empty();
	$('#co_level_table_id').remove();
	$('#co_level_body').remove();
	$('#docs').remove();
	$('#actual_course_attainment').remove();
	$('#chart1').remove();
	$('#student_name_usn').empty();
	  
	$('#course_attainment_nav').empty();
	$('#course_attainment').remove();
	$('#course_attainment_body').remove();
	$('#course_attainment_note').remove();
	$('#chart2').remove();

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

	$('#CLO_PO_attainment_nav').empty();
	$('.crs_co_po_attainment').remove();
	$('#CLO_PO_attainment_body').remove();
	$('#CLO_PO_attainment_note').empty();
}

//List Page
if ($.cookie('remember_crclm') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#crclm_id option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
    select_term();
}
	function select_pgm()
	{
		 $.cookie('remember_dept', $('#dept_id option:selected').val(), {expires: 90, path: '/'});
		empty_all_divs();
		var pgm_list_path = base_url + 'assessment_attainment/student_attainment/select_pgm';
		var data_val = $('#dept_id').val();
		var post_data = {
			'dept_id': data_val
		}
		$.ajax({type: "POST",
			url: pgm_list_path,
			data: post_data,
			success: function(msg) {
				$('#pgm_id').html(msg);
				if ($.cookie('remember_pgm') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#pgm_id option[value="' + $.cookie('remember_pgm') + '"]').prop('selected', true);
                select_crclm();
            }
			}
		}); 
	}

	function select_crclm()
	{ select_term();$('#export_doc').prop('disabled', true);
		$.cookie('remember_pgm', $('#pgm_id option:selected').val(), {expires: 90, path: '/'});
		empty_all_divs();
		var crclm_list_path = base_url + 'assessment_attainment/student_attainment/select_crclm';
		var data_val = $('#pgm_id').val();
		var post_data = {
			'pgm_id': data_val
		}
		$.ajax({type: "POST",
			url: crclm_list_path,
			data: post_data,
			success: function(msg) {
				$('#crclm_id').html(msg);
				if ($.cookie('remember_crclm') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#crclm_id option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
                //get_selected_value();
                select_term();
            }
			}
		}); 
	}

	function select_term()
	{
	$('#export_doc').prop('disabled', true);
		$.cookie('remember_crclm', $('#crclm_id option:selected').val(), {expires: 90, path: '/'});
		empty_all_divs();
		var term_list_path = base_url + 'assessment_attainment/student_attainment/select_term';
		var data_val = $('#crclm_id').val();
		var post_data = {
			'crclm_id': data_val
		}
		
		$.ajax({type: "POST",
			url: term_list_path,
			data: post_data,
			success: function(msg) {
				$('#term').html(msg);
				if ($.cookie('remember_term') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
                
               select_course();
            }
				
			}
		}); 
	}

	function select_course()
	{
		$.cookie('remember_term', $('#term option:selected').val(), {expires: 90, path: '/'});
		empty_all_divs();
		var course_list_path = base_url + 'assessment_attainment/student_attainment/select_course';
		var data_val = $('#term').val();
		if(data_val){
			var post_data = {
				'term_id': data_val
			}
			$.ajax({type: "POST",
				url: course_list_path,
				data: post_data,
				success: function(msg) {
					$('#course').html(msg);	
				if ($.cookie('remember_course') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#course option[value="' + $.cookie('remember_course') + '"]').prop('selected', true);
                select_type();
				}
				}
			}); 
		} else {
		select_type();
			$('#course').html('<option value="">Select Course</option>');
		}
	}
 
	function select_type(){
	
		$.cookie('remember_course', $('#course option:selected').val(), {expires: 90, path: '/'});
		empty_all_divs();
		$('#occasion_div').css({"display":"none"});$('#section_div').css({"display":"none"});
		var course_id = $('#course').val();
		if(course_id) {
			$('#usn_div').css({"display":"none"});
			$('#actual_data_div').empty();		
		
				var course_id = $('#course').val();
				var crclm_id = $('#crclm_id').val();
				var term_id = $('#term').val();	
				var post_data =  {'course_id':course_id,'crclm_id':crclm_id,'term_id':term_id};
				
				$.ajax({
						type : "POST",
						url : base_url + 'assessment_attainment/student_attainment/fetch_type_data',
						data : post_data,
						dataType: 'json',
						success : function (msg) {
							$('#type_data').html(msg.type_list);
						
				}});
//		$('#type_data').html('<option value=0>Select Type</option><option value=2>'+entity_cie+'</option><option value=1>'+entity_see+'</option><option value="3">Both '+entity_cie+' & '+entity_see+'</option>');
		
		
		
		
		} else {
			$('#occasion_div').css({"display":"none"});$('#section_div').css({"display":"none"});
			$('#type_data').html('<option value=0>Select Type</option>');
		}
	
	}

	$('#add_form').on('change','#type_data',function(){
	empty_all_divs();
		$('#occasion_div').css({"display":"none"});$('#section_div').css({"display":"none"});
	var type_data_id = $('#type_data').val();
		if(type_data_id == 3) {
		empty_all_divs(); 
			$('#usn_div').css({"display":"none"});
			var crclm_id = $('#crclm_id').val();
			var term_id = $('#term').val();
			var crs_id = $('#course').val();
			var post_data1 = {
				'crclm_id' : crclm_id,
				'term_id' : term_id,
				'crs_id' : crs_id
			}
			$.ajax({type: "POST",
				url: base_url+'assessment_attainment/data_series/select_section',
				data: post_data1,
				success: function(sectionList) {
					if(sectionList != 0){
						$('#section_div').css({"display":"inline"});
						$('#section').html(sectionList);
					} else {
						$('#section_div').css({"display":"none"});						
						$('#actual_data_div').html('<font color="red">Continuous Internal Assessment (CIA) Occasions are not defined</font>');
					}
				}
			}); 
		}else{	empty_all_divs();
				$('#occasion_div').css({"display":"none"});$('#section_div').css({"display":"none"});
				fetch_data();
			}
			
	});
	$('#add_form').on('change','#section',function(){
		fetch_data();
	});
	//$('#add_form').on('change','#section',function(){
	function fetch_data(){	
		var type_data_id = $('#type_data').val(); 
		if(type_data_id == 5) {
			empty_all_divs(); 	
			$('#occasion_div').css({"display":"none"});		
			$('#section_div').css({"display":"none"});			
			$('#actual_data_div').empty();	
			var course = $('#course').val();		
			var qpd_id = '';		
			var type = 'tee';
			var post_data1 = {
					'course' : course,
					'qpd_id' : qpd_id,
					'type'   : type
				}
				$.ajax({type: "POST",
					url: base_url+'assessment_attainment/student_attainment/select_usn',
					data: post_data1,
					dataType: 'json',
					success: function(usnList) {					
						if(usnList != 0){
							$('#usn_div').css({"display":"inline"});
							$('#student_usn').html(usnList['usn']);
							$('#tee_qpd_id').val(usnList['qpd_id']);
						} else {
							$('#usn_div').css({"display":"none"});		
							$('#actual_data_div').html('<font color="red">Assessment Occasions are not defined</font>');
						}
					}
				}); 
			
			//plot_graphs(course,qpd_id,type);
		}
		else if(type_data_id == 3){
			empty_all_divs(); 
			$('#usn_div').css({"display":"none"});
			var crclm_id = $('#crclm_id').val();
			var term_id = $('#term').val();
			var crs_id = $('#course').val();
			var section = $('#section').val();
			var post_data1 = {
				'crclm_id' : crclm_id,
				'term_id' : term_id,
				'crs_id' : crs_id,
				'section_id' : section
			}
			$.ajax({type: "POST",
				url: base_url+'assessment_attainment/data_series/select_occasion_student',
				data: post_data1,
				success: function(occasionList) {
					if(occasionList != 0){
						$('#occasion_div').css({"display":"inline"});
						$('#occasion').html(occasionList);
					} else {
						$('#occasion_div').css({"display":"none"});						
						$('#actual_data_div').html('<font color="red">Continuous Internal Assessment (CIA) Occasions are not defined</font>');
					}
				}
			}); 
		}else if(type_data_id == 6){
			empty_all_divs(); 
			$('#usn_div').css({"display":"none"});
			var crclm_id = $('#crclm_id').val();
			var term_id = $('#term').val();
			var crs_id = $('#course').val();
			var section = $('#section').val();
			var post_data1 = {
				'crclm_id' : crclm_id,
				'term_id' : term_id,
				'crs_id' : crs_id,
				'section_id' : section
			}
			$.ajax({type: "POST",
				url: base_url+'assessment_attainment/data_series/select_occasion_mte',
				data: post_data1,
				success: function(occasionList) {
					if(occasionList != 0){
						$('#occasion_div').css({"display":"inline"});
						$('#occasion').html(occasionList);
					} else {
						$('#occasion_div').css({"display":"none"});						
						$('#actual_data_div').html('<font color="red">Continuous Internal Assessment (CIA) Occasions are not defined</font>');
					}
				}
			}); 
		}
		// Both CIA & TEE option
		else {
			empty_all_divs(); 
			$('#occasion_div').css({"display":"none"});		
			$('#section_div').css({"display":"none"});			
			$('#usn_div').css({"display":"none"});
			$('#actual_data_div').empty(); 
			if(type_data_id == 'ALL') 
			{
				$('#occasion_div').css({"display":"none"});						
				$('#actual_data_div').empty();	
				var course = $('#course').val();		
				var qpd_id = '';		
				var type = 'both';
				var post_data1 = {
						'course' : course,
						'qpd_id' : qpd_id,
						'type'   : type
					}
					$.ajax({type: "POST",
						url: base_url+'assessment_attainment/student_attainment/select_usn',
						data: post_data1,
						dataType: 'json',
						success: function(usnList) {
						
							if(usnList != 0){
								$('#usn_div').css({"display":"inline"});
								$('#student_usn').html(usnList['usn']);
								$('#tee_qpd_id').val(usnList['qpd_id']);
							} else {
								$('#usn_div').css({"display":"none"});	
								$('#actual_data_div').html('<font color="red">Assessment Occasions are not defined. </font>');
							}
						}
					}); 
				}
				//plot_graphs(course,qpd_id,type);
		}
		}
	//});

	$('#add_form').on('change','#occasion',function(){
		$('#actual_data_div').empty();
		var course = $('#course').val();		
		var qpd_id = $('#occasion').val();		
		var type = 'cia';
		var post_data1 = {
				'course' : course,
				'qpd_id' : qpd_id
				
			}
			$.ajax({type: "POST",
				url: base_url+'assessment_attainment/student_attainment/cia_select_usn',
				data: post_data1,
				success: function(usnList) {
					if(usnList != 0){
						$('#usn_div').css({"display":"inline"});
						$('#student_usn').html(usnList);
					} else {
						$('#usn_div').css({"display":"none"});						
					}
				}
			}); 
	}); 
	
	 $('#add_form').on('change','#student_usn',function(){
		$('#actual_data_div').empty();
		var student_usn = $('#student_usn').val();
		var course = $('#course').val();		
		var type = $('#type_data').val();
		if(student_usn !=""){ $('#export_doc').prop('disabled', false);}else{$('#export_doc').prop('disabled', true);}
		if (type == 5){
			var qpd_id = $('#tee_qpd_id').val();
			$('#stud_question_analysis').css({"display":"inline"});
			plot_graphs(course,qpd_id,type,student_usn);
		} else if (type == 3){
			var qpd_id = $('#occasion').val();
			if(qpd_id == 'all_occasion'){
				$('#stud_question_analysis').css({"display":"none"});						
				plot_graphs(course,qpd_id,type,student_usn);		
			} else {	
				$('#stud_question_analysis').css({"display":"inline"});
				plot_graphs(course,qpd_id,type,student_usn);		
			}
		}else if (type == 6){
			var qpd_id = $('#occasion').val();
			if(qpd_id == 'all_occasion'){
				$('#stud_question_analysis').css({"display":"none"});						
				plot_graphs(course,qpd_id,type,student_usn);		
			} else {	
				$('#stud_question_analysis').css({"display":"inline"});
				plot_graphs(course,qpd_id,type,student_usn);		
			}
		} else {
			qpd_id = '';
			plot_graphs(course,qpd_id,type,student_usn);		
			$('#stud_question_analysis').css({"display":"none"});						
		}
	});  
	
	
	$('.input-medium').on('change' , function(){
		val = $(this).attr('id'); 
		if(val == 'student_usn'){}else{ $('#student_usn').val('');}
		var student_usn =$("#student_usn option:selected").val();
		if( student_usn == ''){ $('#export_doc').prop('disabled', true);}
	});
	function plot_graphs(course,qpd_id,type, student_usn) {
		var post_data = {
			'course': course,
			'qpd_id': qpd_id,
			'type' : type,
			'student_usn':student_usn
		}
 		// $('#loading').show();
		if (type != 'ALL') {
			$('#loading').show();
			$.ajax({type: "POST",
				url: base_url+'assessment_attainment/student_data_series/StudentAttainmentAnalysis',
				data: post_data,
				success: function(msg) {
					$('#student_name_usn').html($("#student_usn option:selected").text() +' - '+ $("#student_usn option:selected").attr('title'));
					$('#data_div').html(msg);
					$('#loading').hide();
				}
			});
		}
		$.ajax({type: "POST",
				url: base_url + 'assessment_attainment/student_attainment/CourseCOAttainment',
				data: post_data,
				dataType: 'JSON',
				success: function(json_graph_data) {				
					  if (json_graph_data.length > 0) {
						var i=0;
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
						$.each(json_graph_data, function() {
							value3[i] = this['clo_code'];
							value1[i] = this['cloMaxMarks'];
							value2[i] = this['cloSecuredMarks'];
							value4[i] = this['Attainment'];
							clo_stmt[i] = this['clo_statement']; 
						if (type == 3){	threshold[i] = this['cia_clo_minthreshhold']; clo_minthreshhold = this['cia_clo_minthreshhold'];}							
						if (type == 6){	threshold[i] = this['mte_clo_minthreshhold']; clo_minthreshhold = this['mte_clo_minthreshhold'];}							
						else if(type == 5)	{threshold[i] = this['tee_clo_minthreshhold']; clo_minthreshhold = this['tee_clo_minthreshhold']; }  
						else if( type == 'ALL') { threshold[i] = ( parseFloat( this['tee_clo_minthreshhold'])+ parseFloat (this['cia_clo_minthreshhold']) + parseFloat (this['mte_clo_minthreshhold']))/ 2; 
						clo_minthreshhold = ( parseFloat(this['tee_clo_minthreshhold'] ) + parseFloat (this['cia_clo_minthreshhold']) +  parseFloat (this['mte_clo_minthreshhold'])/ 2);						
						}
							data1.push(Number(value1[i]));
							data2.push(Number(value2[i]));
							data4.push(Number(Math.round(((this['cloSecuredMarks']/this['cloMaxMarks'])*100)*100)/100));
							data3.push(value3[i]);	
							threshold_data.push(Number(threshold[i]));	
							i++;
						});
						// for future use
						//<div class="row-fluid"><div class="span6"></div><div class="span6 rightJustified" ><a id="exportToPDF" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export to PDF </a></div></div><br/>
						$('#co_level_nav').html('<div class="row-fluid"><div class="span6"></div><div class="span6 rightJustified" ><a id="exportToPDF" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export to PDF </a></div></div><br/><div class="navbar"><div class="navbar-inner-custom">Student Course Outcomes(COs) Attainment</div></div>');
						$('#chart_plot_1').html('<div class=row-fluid><div class=span12><div class=span6><div id=chart1 style=position:relative; class=jqplot-target></div></div><div class=span6 style=overflow:auto; id="course_outcome_attainment_div"><table border=1 class="table table-bordered" id=co_level_table_id><thead></thead><tbody id="co_level_body"></tbody></table><div id="actual_course_attainment"></div></div><div id=docs class=span12></div></div></div>');
						$('#co_level_table_id > tbody:first').append('<tr><td width = 130 ><center><b>Course Outcomes</b></center></td><td width = 120><center><b>Max Marks</b></center></td><td width = 130 style="white-space:no-wrap;"><center><b> Secured Marks</b></center></td><td width = 130 ><center><b>Threshold %</b></center></td><td width = 140 ><center><b>Attainment %</b></center></td></tr>');
						$.each(json_graph_data, function(){
							var attain_per = '-';
							if(!isNaN(parseFloat(this['Attainment']).toFixed(2))){
								attain_per = parseFloat(this['Attainment']).toFixed(2) + '%';
							}
							$('#co_level_table_id > tbody:last').append('<tr><td width = 130 ><center>'+this['clo_code']+'</center></td><td  width = 120  style="text-align: right;"  >'+this['cloMaxMarks']+'</td><td width = 130  style="text-align: right;"  >'+this['cloSecuredMarks']+'</td><td width = 130  style="text-align: right;">'+parseFloat(clo_minthreshhold).toFixed(2)+'%</td><td width = 140  style="text-align: right;">'+attain_per+'</td></tr>');
						});
						
						//Compute sum of Max Marks
						var max_marks_sum = 0;
						for(var j = 0; j < data1.length; j++){
							max_marks_sum += data1[j] ;
						}
						//Compute sum of Secured Marks
						var sec_marks_sum = 0;
						for(var j = 0; j < data2.length; j++){
							sec_marks_sum += data2[j] ;
						}
						//Compute sum of attainment
						var attainment = 0;
						for(var j = 0; j < data4.length; j++){
							attainment += data4[j] ;
						}
						attainment = attainment/data4.length;						
						$('#co_level_table_id > tbody:last').append('<tr><td width = 130  ><center>TOTAL</center></td><td width = 120  style="text-align: right;"  >'+max_marks_sum.toFixed(2)+'</td><td width = 130  style="text-align: right;"  >'+sec_marks_sum.toFixed(2)+'</td><td width = 130 > <center></center></td></tr>');
						$('#actual_course_attainment').html('<b>Course Attainment: </b> '+Math.ceil( (sec_marks_sum * 100) / (max_marks_sum) )+'%');
						$('#docs').html('<br><div class="span12"><table border=1 class="table table-bordered" style="width:100%"><tbody><tr><td width = 650> <b>Note:</b>  The above bar graph depicts the individual COs planned marks distribution and secured marks distribution as in the question paper, and its respective attainment percentage is calculated using the below formula.</td></tr><tr><td width = 650>For individual '+entity_clo+' attainment % = (Secured marks / Max marks ) * 100</td></tr></tbody></table><div>');
						
						var ticks = data3;
						var s1 = data1;
						var s2 = data2;
					
						var plot1 = $.jqplot('chart1', [threshold_data,data4], {						 
							seriesColors: ["#3efc70", "#4bb2c5"],  // colors that will
											 // be assigned to the series.  If there are more series than colors, colors
											 // will wrap around and start at the beginning again.
							seriesDefaults:{
								renderer:$.jqplot.BarRenderer,
								rendererOptions: {barWidth:17,fillToZero: true},
								pointLabels: { show: true }
							},       
							series:[
								{label:'Threshold %'},
								{label:'Attainment %'}
							],
							highlighter: {					
								show: true,
								tooltipLocation: 'e', 
								tooltipAxes: 'x', 
								fadeTooltip	:true,
								showMarker:false,
								tooltipContentEditor:function (str, seriesIndex, pointIndex, plot){
									return clo_stmt[pointIndex];
								}
							},
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
									min:0,
									max:100,
									padMin: 0,
									tickOptions: {formatString: '%#.2f%'}
								}
							}
						}); 
					}else{
					
						$('#co_level_body').remove();
						$('#docs').remove();
						$('#co_level_nav').remove();
						$('#actual_course_attainment').remove();
						$('#course_outcome_attainment_div').remove();
						$('#chart1').remove();
					}					
				}		
			});
			
	// Blooms Level Distribution for Student Attainment scripts starts here .......---------------------------------------
		$.ajax({type: "POST",
				url: base_url + 'assessment_attainment/student_attainment/CourseBloomsLevelAttainment',
				data: post_data,
				dataType: 'JSON',
				success: function(json_graph_data) {

				count_array = new Array();
				k=0
				$.each(json_graph_data, function() {
							count_array[k] = this['level'];
							k++;
				});

					  if (json_graph_data.length > 0 && count_array != '' ) {
						var i=0;
						value1 = new Array();
						value2 = new Array();
						value3 = new Array();
						value4 = new Array();
						value5 = new Array();
						value6 = new Array();
						value7 = new Array();
						bloom_stmt = new Array();
						bloom_stmt_data = new Array();
						data1 = new Array();					
						data2 = new Array();
						data3 = new Array();	
						data4 = new Array();	
						data5 = new Array();	
						data6 = new Array();
						data7 = new Array();
						bloom_attainment_data = new Array();
						$.each(json_graph_data, function() {
							value3[i] = this['level'];
							value1[i] = this['tee_bloomlevel_minthreshhold'];
							value7[i] = this['mte_bloomlevel_minthreshhold']; 
							value6[i] = this['cia_bloomlevel_minthreshhold'];
							
							value2[i] = this['PercentStudentAboveThreshhold'];
							value4[i] = Number(this['blMaxMarks']);
							value5[i] = Number(this['blSecuredMarks']);
							bloom_stmt[i] = this['description'];
							data1.push(Number(value1[i]));
							data7.push(Number(value7[i]));
							 if (type == 3){data5.push(Number(value6[i]));} else if(type == 5){ data5.push(Number(value1[i])); } else if(type == 6){ data5.push(Number(value7[i])); }
							else if(type == 'ALL'){ data5.push(Number(value6[i]));data6.push(Number(value1[i])); data7.push(Number(value7[i])); } 
							data2.push(Number(value2[i]));
							data3.push(value3[i]);					
							data4.push(value4[i]);	
							
							bloom_stmt_data.push(value3[i]+"-"+bloom_stmt[i]);
							
							if(value4[i] != 0){bloom_attainment_data.push((value5[i]/value4[i])*100);} else { bloom_attainment_data.push(0); }
							i++;
						});						
						$('#bloom_level_nav').html('<div class="navbar"><div class="navbar-inner-custom">Bloom\'s Level-wise Student Performance % for above or equal to Min Threshold (Target)</div></div>');
						$('#chart_plot_3').html('<div class=row-fluid><div class=span12><div class=span6><div id=chart3 style=position:relative; class=jqplot-target></div></div><div class=span6 style=overflow:auto; id="bloom_level_distribution_div"><table border=1 class="table table-bordered" id=bloom_level><thead></thead><tbody id="bloom_level_body"></tbody></table></div><div id=bloom_level_note class=span12></div></div></div>');
						//Compute sum of Max Marks
						var max_marks_sum = 0;
						for(var j = 0; j < data4.length; j++){
							max_marks_sum += data4[j] ;
						}
					
						max_marks_total = new Array();
						secured_marks_total = new Array();
						//Compute cumulative sum max for levels
						for (var i = 0; i < value4.length; i++) {
							var j = i;
							max_marks_total[i] = 0;
							for(var k=0 ; k <= j ; k++) {
								max_marks_total[i] += value4[k] ;
							}
						}
					
						//Compute cumulative sum secured for levels
						for (var i = 0; i < value5.length; i++) {
							var j = i;
							secured_marks_total[i] = 0;
							for(var k=0 ; k <= j ; k++) {
								secured_marks_total[i] += value5[k] ;
							}
						}
						var parameter_list = new Array();
						var colors_list = new Array();
						
						if (type == 3){parameter_list.push(data5); colors_list.push("#3efc70");  } else if(type == 5){ parameter_list.push(data1);  colors_list.push("#3efc70"); } else if(type == 6){ parameter_list.push(data7);  colors_list.push("#3efc70"); } 
							else if(type == 'ALL'){ parameter_list.push(data5); colors_list.push("#4bb2c5"); parameter_list.push(data1);   colors_list.push("#fe9a2e");parameter_list.push(data7);   colors_list.push("#fe9a2e");} 
							parameter_list.push(bloom_attainment_data);
							colors_list.push("#4bb2c5");
						var ticks = data3;
						var s1 = data5;
						var s2 = bloom_attainment_data;
						$('#chart3').empty();
						var plot1 = $.jqplot('chart3', parameter_list , {
							seriesColors: colors_list ,  // colors that will
											 // be assigned to the series.  If there are more series than colors, colors
											 // will wrap around and start at the beginning again.
							seriesDefaults:{
								renderer:$.jqplot.BarRenderer,
								rendererOptions: {barWidth:12,fillToZero: true},
								pointLabels: { show: true }
							},       
							series:[
								/* {label: entity_cie + 'Threshold %'},
								{label: entity_see + 'Threshold %'},
								{label:'Attainment %'} */
								{label: 'Threshold %'},
								{label:'Attainment %'}
							],
							highlighter: {					
								show: true,
								tooltipLocation: 'e', 
								tooltipAxes: 'x', 
								fadeTooltip	:true,					
								showMarker:false,
								tooltipContentEditor:function (str, seriesIndex, pointIndex, plot){
									return bloom_stmt_data[pointIndex];
								}
							},
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
									tickOptions: {formatString: '%.2f%'}
								}
							}
						}); 
						$('#bloom_level > tbody:first').append('<tr><td width = 100 ><center><b>Bloom\'s Level</b></center></td><td width = 80 ><center><b>Max Marks</b></center></td><td width = 100 ><center><b>Secured Marks</b></center></td><td width = 100 ><center><b>'+ entity_cie +' Attainment Threshold </b></center></td><td width = 100 ><center><b>'+ entity_mte +' Attainment Threshold </b></center></td><td width = 100 ><center><b>'+ entity_see +' Attainment Threshold </b></center></td><td width = 100 ><center><b> Threshold Based Attainment % </b></center></td><td width = 100 ><center> <b> Attainment %</b></center></td></tr>');
						var m = 0;
						$.each(json_graph_data, function(){
							var secured_marks = secured_marks_total[m];
							secured_marks = secured_marks.toFixed(2);
							if(value4[m] != 0){bloom_attainment = (value5[m]/value4[m])*100;} else { bloom_attainment = 0; }
							$('#bloom_level > tbody:last').append('<tr><td width = 100 ><center>'+this['level']+'</center></td><td width = 80  style="text-align: right;"> '+value4[m]+' </td><td width = 100 style="text-align: right;"> '+value5[m]+' </td><td width = 100  style="text-align: right;">'+data5[m]+'% </td><td width = 100  style="text-align: right;">'+data7[m]+'% </td><td width = 100  style="text-align: right;"> '+data1[m]+'% </td><td width = 100 style="text-align: right;"> '+data2[m].toFixed(2)+'% </td><td width = 100  style="text-align: right;">'+bloom_attainment.toFixed(2)+' </td></tr>');
							m++;
						});
						/*$('#bloom_level_note').html('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above bar graph depicts the overall class performance with respect to the Min Threshold % for individual Bloom\'s Level and the Student >= Min Threshold % (Target) for individual Bloom\'s Level as in the question paper. The Student >= Min Threshold % is calculated using the below formula.</td></tr><tr><td>For Student >= Min Threshold % = (Count of Students >= to Min Attainment Threshold % / Total number of Students Attempted) x 100</td></tr></tbody></table></div>');*/
						
						 $('#bloom_level_note').html('<div class="span12"><table border="1" class="table table-bordered"><tbody><tr><td colspan="2" width = 650><b>Note:</b> The above bar graph depicts the overall class performance with respect to the Threshold % for individual '+ entity_clo_full_singular +' ('+ entity_clo +'). The Attainment % is calculated using the below formula.</td></tr><tr><td width = 650>For Attainment % = (Secured marks / Max marks ) * 100 . </td></tr></tbody></table></div>');
					}else{
						$('#chart3').remove();$('#bloom_level_note').remove();$('#bloom_level_nav').remove();$('#bloom_level_distribution_div').remove();
					
					}
				} 
			}); 			
	// Course Outcomes (COs)-wise Student Performance % for above or equal to Min Threshold (Target) script starts here --------
	/*
					$.ajax({type: "POST",
				url: base_url + 'assessment_attainment/student_attainment/CourseCOAttainment',
				data: post_data,
				dataType: 'JSON',
				success: function(json_graph_data) {
					  if (json_graph_data.length > 0) {
						var i=0;
						value1 = new Array();
						value2 = new Array();
						value3 = new Array();
						
						data1 = new Array();					
						data2 = new Array();
						data3 = new Array();	
						
						$.each(json_graph_data, function() {
							value3[i] = this['clo_code'];
							value1[i] = this['clo_studentthreshhold'];
							value2[i] = this['PercentStudentAboveThreshhold'];
							
							data1.push(Number(value1[i]));
							data2.push(Number(value2[i]));
							data3.push(value3[i]);		
							i++;
						});
						$('#co_level_student_threshold_nav').html('<div class="row-fluid"><div class="span6"></div><div class="span6 rightJustified" ></div></div><br><div class="navbar"><div class="navbar-inner-custom">Course Outcomes (COs)-wise Student Performance % for above or equal to Min Threshold (Target)</div></div>');
						$('#chart_plot_5').html('<div class=row-fluid><div class=span12><div class=span6><div id=chart5 style=position:relative; class=jqplot-target></div></div><div class=span6 style=overflow:auto; id="course_outcome_attainment_div"><table border=1 class="table table-bordered" id=co_level_student_threshold_nav_table_id><thead></thead><tbody id="co_level_body"></tbody></table><div id="actual_course_attainment"></div></div><div id=docs class=span12></div></div></div>');
						 $('#co_level_student_threshold_nav_table_id > tbody:first').append('<tr><td><center><b>Course Outcomes</b></center></td><td><center><b>Min % students above min attainment</b></center></td><td style="white-space:no-wrap;"><center><b>Actual % students above min attainment</b></center></td></tr>');
						$.each(json_graph_data, function(){
							$('#co_level_student_threshold_nav_table_id > tbody:last').append('<tr><td><center>'+this['clo_code']+'</center></td><td style="text-align: right;"  >'+this['clo_studentthreshhold']+'</td><td style="text-align: right;"  >'+this['PercentStudentAboveThreshhold']+'</td></tr>');
						}); 
						
						
						$('#student_docs').html('<br><div class="bs-docs-example"><b>Note:</b><table border=1 class="table table-bordered"><tbody><tr><td>The above bar graph depicts the overall class performance with respect to the Min Threshold % for individual Course Outcomes (COs) and the Students >= Min Threshold % (Target) for individual Course Outcomes (COs) as in the question paper. The Students >= Min Threshold % is calculated using the below formula.</td></tr><tr><td>For Students >= Min Threshold % = (Count of Students >= to Min Attainment Threshold % / Total number of Students Attempted) x 100</td></tr></tbody></table></div>');
						
						var ticks = data3;
						var s1 = data1;
						var s2 = data2;
					
						var plot1 = $.jqplot('chart5', [data1,data2], {						 
							seriesColors: ["#3efc70", "#4bb2c5"],  // colors that will
											 // be assigned to the series.  If there are more series than colors, colors
											 // will wrap around and start at the beginning again.
							seriesDefaults:{
								renderer:$.jqplot.BarRenderer,
								rendererOptions: {barWidth:17,fillToZero: true},
								pointLabels: { show: true }
							},       
							series:[
								{label:'Minimum Attainment %'},
								{label:'Actual Attainment %'}
							],
							highlighter: {					
								show: true,
								tooltipLocation: 'e', 
								tooltipAxes: 'x', 
								fadeTooltip	:true,
								showMarker:false,
								tooltipContentEditor:function (str, seriesIndex, pointIndex, plot){
									return clo_stmt[pointIndex];
								}
							},
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
									min:0,
									max:100,
									padMin: 0,
									tickOptions: {formatString: '%#.2f%'}
								}
							}
						}); 
					}					
				}		
			});
		*/
// Course Outcomes (COs)-wise Student Performance % for above or equal to Min Threshold (Target) script ends here ---------------
			
// Course Outcomes(COs) Contribution for Student Attainment scripts starts here .......---------------------------------------
		/*
		$.ajax({type: "POST",
				url: base_url + 'assessment_attainment/student_attainment/CourseCOAttainment_Contribution',
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
							value1[i] = this['cloMaxMarks'];
							value2[i] = this['cloSecuredMarks'];
							value3[i] = this['totalMarks'];
							clo_stmt_arr[i] = this['clo_statement'];
							data1.push(Number(value[i]));
							data2.push(Number(head[i]));
							data3.push(Number(value1[i]));
							data4.push(Number(value2[i]));
							data5.push(Number(value3[i]));
							clo_stmt_data.push(clo_stmt_arr[i]);
							i++;
						});
						$('#course_attainment_nav').html('<div class="navbar"><div class="navbar-inner-custom">Course Outcomes(COs) Contribution</div></div>');
						$('#chart_plot_2').html('<div class=row-fluid><div class=span12><div class=span6><div id=chart2 style=position:relative; class=jqplot-target></div></div><div class=span6 style=overflow:auto; id="course_outcome_contribution_div"><table border=1 class="table table-bordered" id=course_attainment><thead></thead><tbody id="course_attainment_body"></tbody></table></div><div id=course_attainment_note class=span12></div></div></div>');
						$('#course_attainment > tbody:first').append('<tr><td style="white-space:no-wrap;"><center><b>Course Outcomes</b></center></td><td style="white-space:no-wrap;"><center><b>Total Marks</b></center></td><td style="white-space:no-wrap;"><center><b>Max Planned Marks</b></center></td><td style="white-space:no-wrap;"><center><b>Secured Marks</b></center></td><td style="white-space:no-wrap;"><center><b>Planned Contribution %</b></center></td><td style="white-space:no-wrap;"><center><b>Actual Contribution %</b></center></td></tr>');
						
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
						
						$.each(json_graph_data, function(){
							$('#course_attainment > tbody:last').append('<tr><td><center>'+this['clo_code']+'</center></td><td style="text-align: right;"> '+this['totalMarks']+' </td><td style="text-align: right;"  >'+this['cloMaxMarks']+'</td><td style="text-align: right;"  >'+this['cloSecuredMarks']+'</td><td style="text-align: right;"> '+this['MaxContribution']+'% </td><td style="text-align: right;"> '+this['ActualContribution']+'% </td></tr>');
						});
												
						$('#course_attainment > tbody:last').append('<tr><td><center>TOTAL</center></td><td><center></center></td><td style="text-align: right;"  >'+max_marks_sum+'</td><td style="text-align: right;"  >'+sec_marks_sum.toFixed(2)+'</td><td style="text-align: right;"><center></center></td><td><center></center></td></tr>');						
						$('#course_attainment_note').html('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above bar graph depicts the individual COs planned percentage contribution and actual percentage contribution as in the question paper, and its respective contribution percentage is calculated using the below formula.</td></tr><tr><td>Planned Contribution % = (Max planned marks / Total marks ) x 100 </td></tr><tr><td>Actual Contribution % = (Secured marks / Total marks ) x 100  </td></tr></tbody></table></div>');
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
			});
			*/
	
	// Blooms Level Distribution for Student Attainment scripts ends here .......---------------------------------------
			
			// Bloom's Level Cumulative Graphs script starts here--------------------------------------------------------
			
			$.ajax({type: "POST",
				url: base_url + 'assessment_attainment/student_attainment/CourseBloomsLevelCumulativeData',
				data: post_data,
				dataType: 'JSON',
				success: function(json_graph_data) {
				
								count_array = new Array();
				k=0
				$.each(json_graph_data, function() {
							count_array[k] = this['level'];
							k++;
				});

					  if (json_graph_data.length > 0 && count_array != '' ) {
						var i=0;
						value3 = new Array();
						value4 = new Array();
						value5 = new Array();
						data1 = new Array();
						data2 = new Array();
						data3 = new Array();
						$.each(json_graph_data, function() {
							value3[i] = this['level'];
							value4[i] = Number(this['MaxContribution']);
							value5[i] = Number(this['ActualContribution']);
							data1.push(Number(this['MaxMarks']));
							data2.push(Number(this['SecuredMarks']));
							data3.push(value3[i]);
							i++;
						});
						$('#cumulative_performance_nav').html('<div class="navbar"><div class="navbar-inner-custom">Bloom\'s Level - Cumulative Performance</div></div>');
						$('#chart_plot_4').html('<div class=row-fluid><div class=span12><div class=span6><div id=chart6 style=position:relative; class=jqplot-target></div></div><div class=span6 style=overflow:auto; id="bloom_level_cumm_perf_div"><table border=1 class="table table-bordered" id=cumulative_performance><thead></thead><tbody id="cumulative_performance_body"></tbody></table></div><div id=cumulative_performance_note class=span12></div></div></div>');
						max_marks_total = new Array();
						secured_marks_total = new Array();
						//Compute cumulative sum max for levels
						for (var i = 0; i < data1.length; i++) {
							var j = i;
							max_marks_total[i] = 0;
							for(var k=0 ; k <= j ; k++) {
								max_marks_total[i] += data1[k] ;
							}
						}
						//Compute cumulative sum secured for levels
						for (var i = 0; i < data2.length; i++) {
							var j = i;
							secured_marks_total[i] = 0;
							for(var k=0 ; k <= j ; k++) {
								secured_marks_total[i] += data2[k] ;
							}
						}
						item1 = new Array();
						l1 = new Array();
						for(var i = 0; i < data3.length; i++) {
							l1 = [];
							l1.push(data3[i],Number(max_marks_total[i]));
							item1[i] = l1;
						}
						item2 = new Array();
						l2 = new Array();
						for(var i = 0; i < data3.length; i++) {
							l2 = new Array();
							l2.push(data3[i],Number(secured_marks_total[i]));
							item2[i] = l2;
						}
						//Compute sum of Max Marks
						var max_marks_sum = 0;
						for(var j = 0; j < data1.length; j++){
							max_marks_sum += data1[j] ;
						}
						//Compute sum of Secured Marks
						var secured_marks_sum = 0;
						for(var k = 0; k < data2.length; k++){
							secured_marks_sum += data2[k] ;
						}
						//Compute sum of Max distribution
						var sum_max_marks_distr = 0;
						for(var l = 0; l < value4.length; l++) {
							sum_max_marks_distr += value4[l];
						}
						//Compute sum of Max distribution
						var sum_secured_marks_distr = 0;
						for(var m = 0; m < value5.length; m++) {
							sum_secured_marks_distr += value5[m];
						}
						$('#chart6').empty();
						var plot2 = $.jqplot ('chart6', [item1,item2], {								   
									seriesDefaults: {
										markerOptions:{
											show:false
										},
									},
									axesDefaults: {
										labelRenderer: $.jqplot.CanvasAxisLabelRenderer
									},
									series:[
										 {label:'Cumulative Max Marks'},
										 {label:'Cumulative Actual Marks'}
									 ],
									 
									legend: {
										show: true,
										placement: 'outsideGrid'
									},
									axes: {
										xaxis: {
											renderer:$.jqplot.CategoryAxisRenderer,
											tickRenderer: $.jqplot.CanvasAxisTickRenderer
											},
										yaxis: {
											min:0
										}
									}

								});
					   
						$('#cumulative_performance > tbody:first').append('<tr><td width = 80 ><center><b>Bloom\'s Level</b></center></td><td width = 80 ><center><b>Max Planned Marks</b></center></td><td width = 80 ><center><b>Secured Marks</b></center></td><td width = 80 ><center><b>Cumulative Sum(Planned marks)</b></center></td><td width = 80 ><center><b>Cumulative Sum(Secured marks)</b></center></td><td width = 80 ><center><b>Planned Max Marks Distribution</b></center></td><td width = 80><center><b>Secured Marks Distribution</b></center></td></tr>');
						var m = 0;
						$.each(json_graph_data, function(){
							var secured_marks = secured_marks_total[m];secured_marks = secured_marks.toFixed(2);
							$('#cumulative_performance > tbody:last').append('<tr><td width = 80 ><center>'+this['level']+'</center></td><td width = 80  style="text-align: right;"  >'+data1[m]+'</td><td width = 80 style="text-align: right;"  >'+data2[m]+'</td><td width = 80 style="text-align: right;"> '+max_marks_total[m]+' </td><td width = 80 style="text-align: right;"> '+secured_marks+' <td width = 80 style="text-align: right;"> '+value4[m]+'% <td width = 80 style="text-align: right;"> '+value5[m]+'% </tr>');
							m++;
						});
						secured_marks_sum = secured_marks_sum.toFixed(2);
						$('#cumulative_performance > tbody:last').append('<tr><td width = 80 ><center>TOTAL</center></td><td width = 80 style="text-align: right;"  >'+max_marks_sum+'</td><td width = 80  style="text-align: right;"  >'+secured_marks_sum+'</td><td width = 80 style="text-align: right;">  </td><td width = 80 style="text-align: right;">  </td><tdwidth = 80  style="text-align: right;">  </td><td width = 80  style="text-align: right;">  </td></tr>');						
						$('#cumulative_performance_note').html('<br/><div class="span12"><table class="table table-bordered" style="width:100%"><tbody><tr><td width = 650><b>Note:</b> The above run chart depicts the cumulative sum of  Bloom\'s Level planned marks distribution and cumulative sum of Bloom\'s Level actual marks distribution as in the question paper .</td></tr><tr><td width = 650> Planned Max Marks Distribution % = (Max Planned Marks / TOTAL ) * 100 </td></tr><tr><td width = 650>Secured Marks Distribution % = (Secured Marks / TOTAL ) * 100  </td></tr></tbody></table></div>');
					}else{
						
						$('#cumulative_performance_nav').empty();
						$('#cumulative_performance').remove();
						$('#cumulative_performance_body').remove();
						$('#cumulative_performance_note').remove();
						$('#chart6').remove();
					}
				}
			}); 
		// Bloom's Level Cumulative Graphs script ends here--------------------------------------------------------
		
			/*
			//POCOAttainment
			var po;	
			$.ajax({type: "POST",
			url: base_url + 'assessment_attainment/student_attainment/CoursePOCOAttainment',
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
							$('#CLO_PO_attainment'+i+' > tbody:first').append('<tr><td style="white-space:no-wrap;"><center><b>Course Outcomes</b></center></td><td style="white-space:no-wrap;"><center><b>Secured Marks</b></center></td><td style="white-space:no-wrap;"><center><b>Max Marks</b></center></td><td style="white-space:no-wrap;"><center><b>Planned Contribution </b></center></td><td style="white-space:no-wrap;"><center><b>Actual Contribution </b></center></td></tr>');
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
					$('#CLO_PO_attainment_note').html('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The below bar graphs depicts the individual Program Outcome(POs)wise planned marks distribution and cumulative sum of Blooms Level actual marks distribution as in the question paper .</td></tr><tr><td>Planned Contribution % = (Max planned marks / Total marks ) x 100 </td></tr><tr><td>Actual Contribution % = (Secured marks / Total marks ) x 100</td></tr></tbody></table></div><br>');
				}	 	
			});	*/
	}
	
	$('#add_form').on('click','#exportToPDF ,#export_doc ',function(){
		var val = $(this).attr('id');
		if(val == 'export_doc'){ $('#pdf_or_doc').val('doc');}else{$('#pdf_or_doc').val('pdf');}
		var cia_occa_name = '';
		var crclm_name = $('#crclm_id :selected').text();
		var term_name = $('#term :selected').text();
		var course_name = $('#course :selected').text();
		var occa_name = $('#type_data :selected').text();
		var type = $('#type_data :selected').val();
		var cia_occa_name = $('#occasion :selected').text();
		if(cia_occa_name == 'Select Occasion'){ cia_occa_name = '';}
		var stud_id = $('#student_usn :selected').text();
		var stud_name = $('#student_usn :selected').attr('title');
		var section_name = $('#section :selected').text(); 
		
		var course_attainment_graph = $('#chart1').jqplotToImageStr({});
		var course_attainment_graph_image = $('<img/>').attr('src',course_attainment_graph);
		if(type == 3){
		var table = '<tr><td width = 325> <b>Section  :</b> '+ section_name +'</td><td width = 325><b>Occasion :</b> '+ cia_occa_name +' </td></tr>';
		}else{ var table = '';}
		$('#course_outcome_attainment_graph_data_data').html('<table style="width:100%"><tr><td width = 325 ><b>Curriculum :</b> '+crclm_name+'</td><td width = 325 ><b>Term :</b>'+term_name+'</td></tr><tr><td width = 325 ><b>Course :</b>'+course_name+'</td><td width = 325 ><b>Assessment Type :</b>' + occa_name + '</td></tr>'+ table +'<tr><td  width = 325 ><b>Student ID & Name :</b>' + stud_id +' - '+ stud_name + '</td></tr></table><br><b>Student Course Outcomes(COs) Attainment</b><br />');
		$('#course_outcome_attainment_graph_data').html('');
		$('#course_outcome_attainment_graph_data').append(course_attainment_graph_image);
		$('#course_outcome_attainment_graph_data').append($('#course_outcome_attainment_div').clone().html());
		$('#course_outcome_attainment_graph_data').append($('#docs').clone().html());
		var course_outcome_attainment_pdf = $('#course_outcome_attainment_graph_data').clone().html();
		$('#course_outcome_attainment_graph_data_hidden').val(course_outcome_attainment_pdf);
		
		// Bloom Level distribution graph data
		 var bloom_level_distribution_graph = $('#chart3').jqplotToImageStr({});
		var bloom_level_distribution_graph_image = $('<img/>').attr('src',bloom_level_distribution_graph);
		$('#bloom_level_distribution_graph_data').html('<b>Bloom\'s Level-wise Class Performance % for Students above or equal to Min Threshold (Target)</b><br />');
		$('#bloom_level_distribution_graph_data').append(bloom_level_distribution_graph_image);
		$('#bloom_level_distribution_graph_data').append($('#bloom_level_distribution_div').clone().html());
		$('#bloom_level_distribution_graph_data').append($('#bloom_level_note').clone().html());
		var bloom_level_distribution_pdf = $('#bloom_level_distribution_graph_data').clone().html();
		$('#bloom_level_distribution_hidden').val(bloom_level_distribution_pdf); 
		
		// Bloom Level Cumulative graph data
		var bloom_level_cumm_perf_graph = $('#chart6').jqplotToImageStr({});
		var bloom_level_cumm_perf_graph_image = $('<img/>').attr('src',bloom_level_cumm_perf_graph);
		$('#bloom_level_cumm_perf_graph_data').html('<b>Bloom\'s Level - wise Cumulative Performance</b><br />');
		$('#bloom_level_cumm_perf_graph_data').append(bloom_level_cumm_perf_graph_image);
		$('#bloom_level_cumm_perf_graph_data').append($('#bloom_level_cumm_perf_div').clone().html());
		$('#bloom_level_cumm_perf_graph_data').append($('#cumulative_performance_note').clone().html());
		var bloom_level_cumm_perf_graph_pdf = $('#bloom_level_cumm_perf_graph_data').clone().html();
		$('#bloom_level_cumm_perf_graph_hidden').val(bloom_level_cumm_perf_graph_pdf);
		
		//  Student Attainment Analysis (Each Question-wise Attainment List)
		$('#student_attainment_analysis_data').html('<b>Student Attainment Analysis (Each Question-wise Attainment List)</b><br />');
		$('#student_attainment_analysis_data').append($('#data_div').clone().html());
		var student_attainment_analysis_pdf = $('#student_attainment_analysis_data').clone().html();
		$('#student_attainment_analysis_data_hidden').val(student_attainment_analysis_pdf);
		 main_head = $('#course_outcome_attainment_graph_data_data').html();
		$('#file_name').val('student_attainment');
		$('#image1').val(course_attainment_graph);
		$('#image2').val(bloom_level_distribution_graph);
		$('#image3').val(bloom_level_cumm_perf_graph);
		$('#main_head').val(main_head)
		$('#add_form').submit();
	});
