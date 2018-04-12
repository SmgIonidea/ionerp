
	//Bloom's Level, Program outcome and Course Threshold
	
	var base_url = $('#get_base_url').val();

	//set cookie
	if ($.cookie('remember_crclm') != null) {
		// set the option to selected that corresponds to what the cookie is set to
		$('#crclm option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
		select_term();
	}
	
	//function to fetch term details
	function select_term() {
		$.cookie('remember_crclm', $('#crclm option:selected').val(), {expires: 90, path: '/'});
		var curriculum_id = document.getElementById('crclm').value;
		
		var post_data = {
			'curriculum_id': curriculum_id
		}
		
		$.ajax({type: "POST",
			url: base_url + 'assessment_attainment/bl_po_co_threshold/select_term',
			data: post_data,
			success: function(msg) {
				document.getElementById('term').innerHTML = msg;
				bl_po_crs();
			}
		});
	}
	
	//function to fetch bloom's level, program outcome and course details
	function bl_po_crs() {
		var crclm_id = $('#crclm').val();
		var term_id = $('#term').val();
		
		var post_data = {
			'crclm_id' : crclm_id,
			'term_id' : term_id
		}
		
		$.ajax({
			type : "POST",
			url  : base_url + 'assessment_attainment/bl_po_co_threshold/bl_po_crs',
			data : post_data,
			dataType: 'json',
			success : function(msg) {
				//to display data in the bloom's level grid
				$('.generate_bl_table_view').html(msg['d1']);
				
				//to display data in the po grid
				$('.generate_po_table_view').html(msg['d2']);
				
				//to display data in the course grid
				$('.generate_course_table_view').html(msg['d3']);
			}
		});
	}
	
	function bl_crs_wise(){
		
		var crclm_id = $('#crclm').val();
		var term_id = $('#term').val();
		var crs_id = $('#crs_id_data').val();
		var post_data = {
			'crclm_id' : crclm_id,
			'term_id' : term_id ,
			'crs_id' : crs_id
		}
		
		$.ajax({
			type : "POST",
			url  : base_url + 'assessment_attainment/bl_po_co_threshold/bl_course_wise',
			data : post_data,			
			success : function(msg) {
				$('#bloom_data').html(msg);
				$('#bloom_level_crs_wise').modal('show');
			}
		});
	}
	$('#save_course_bloom_level').on('click',function(){
            

		var bl_min = $("input[name='bl_min_data[]']")
							  .map(function(){return $(this).val();}).get();
		var mte_min = $("input[name='mte_min_data[]']")
							  .map(function(){return $(this).val();}).get();
		var tee_min = $("input[name='tee_min_data[]']")
							  .map(function(){return $(this).val();}).get();                                                  
		var bl_stud = $("input[name='bl_stud_data[]']")
							  .map(function(){return $(this).val();}).get();
        var bl_justify_data = $("textarea[name='bl_justify_data[]']")
							  .map(function(){return $(this).val();}).get();
	
   	    var bloom_id = $("input[name='bloom_id_data[]']")
							  .map(function(){return $(this).val();}).get();		
		var crs_id_data =  $('#crs_id_data').val();
					
		var crclm_id = $('#crclm').val();
		var term_id = $('#term').val();
		var mte_flag = $('#mte_flag').val();
		 $('#bl_course_table_view').validate(); 
		var flag = $('#bl_course_table_view').valid(); 
		var post_data = {
			'crclm_id' : crclm_id,
			'term_id' : term_id,
			'bl_min': bl_min,
            'tee_min': tee_min,
			'mte_min': mte_min,
			'bl_stud' : bl_stud ,
			'bl_justify' :bl_justify_data,
			'bloom_id' : bloom_id,
			'crs_id_data':crs_id_data,
			'mte_flag' : mte_flag
		}  
		if(flag == true){
			$.ajax({
				type : "POST",
				url  : base_url + 'assessment_attainment/bl_po_co_threshold/save_bloom_course_wise',
				data : post_data,			
				success : function(msg) {
					if(msg == 1){success_modal(msg);}else{fail_modal(msg);}
				}
			});					  
		}
	});
        
        
        $('#save_course_clo_level').on('click',function(){
		var mte_flag  = $('#mte_flag').val();
		
		var clo_cia_min = $(".clo_cia_min_data").map(function(){return $(this).val();}).get();
		var clo_tee_min = $(".clo_tee_min_data").map(function(){return $(this).val();}).get();    
		if(mte_flag == 1){var clo_mte_min = $(".clo_mte_min_data").map(function(){return $(this).val();}).get();   }else{ $clo_mte_min = '';}
		var clo_stud = $(".clo_stud_data").map(function(){return $(this).val();}).get();
                var clo_justify_data = $(".clo_justify_data").map(function(){return $(this).val();}).get();
                var clo_ids = $("input[name='clo_id_data[]']").map(function(){return $(this).val();}).get();		
		var crs_id_data =  $('#update_crs_id').val();
		var crclm_id = $('#crclm_id').val();
		var term_id = $('#term_id').val();
		
		$('#clo_course_table_view').validate(); 
		var flag = $('#clo_course_table_view').valid(); 
		var post_data = {
			'crclm_id' : crclm_id,
			'term_id' : term_id,
			'clo_cia_min': clo_cia_min,
            'clo_tee_min': clo_tee_min,  
			'clo_mte_min': clo_mte_min,
			'clo_stud' : clo_stud ,
			'clo_justify' :clo_justify_data,
			'clo_id' : clo_ids,
			'crs_id_data':crs_id_data,
			'mte_flag':mte_flag,
			
		}  
		if(flag == true){
                    //$('#loading').show();
			$.ajax({
				type : "POST",
				url  : base_url + 'assessment_attainment/bl_po_co_threshold/save_course_clo_wise_threshold_details',
				data : post_data,			
				success : function(msg) {
                                    if($.trim(msg) == 'success'){
                                        $('#loading').hide();
                                            var data_options = '{"text":"CO threshold updated successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                                            var options = $.parseJSON(data_options);
                                            noty(options);
                                    }else{
                                         $('#loading').hide();
                                            var data_options = '{"text":"CO threshold updation failed.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
                                            var options = $.parseJSON(data_options);
                                            noty(options);
                                    }
					//if(msg == 1){success_modal(msg);}else{fail_modal(msg);}
				}
			});					  
		}
	});
        
        
	//only digits - validation
	$(document).ready(function() {
		$.validator.addMethod("onlyDigit", function(value, element) {
			return this.optional(element) || value.match(/^[0-9]+$/);
		}, "Field should contain only digits.");
	});

		function a(crs_id){
		 $('#crs_id_data').val(crs_id);
			bl_crs_wise();
		}
		
// Function to fetch the course clo threshold data
function clo_data_fetch(crs_id){
       fecth_course_clo_threshold_data(crs_id);
}

function fecth_course_clo_threshold_data(crs_id){
    var crclm_id = $('#crclm').val();
    var term_id = $('#term').val();
    var course_id = crs_id;
    $('#update_crs_id').val(course_id);
		var post_data = {
			'crclm_id' : crclm_id,
			'term_id' : term_id ,
			'crs_id' : course_id
		}
		
		$.ajax({
			type : "POST",
			url  : base_url + 'assessment_attainment/bl_po_co_threshold/fetch_course_clo_thresold_details',
			data : post_data,			
			success : function(msg) {
				$('#clo_data').html(msg);
				$('#clo_threshold_modal').modal('show');
			}
		});
}
		
	//function to save bloom's level data & validate
	$('#myTabContent').on('click','#bl_values',function() {
		$('#bl_table_view').validate();
		$('#bl_table_view').submit();
	});
	
/* 	//function to save bloom's level data & validate
	$('#myTabContent').on('click','#save_bloom_course_wise',function() {
		$('#bl_course_table_view').validate();
		$('#bl_course_table_view').submit();
	}); */
	
	//function to save program outcome data & validate
	$('#myTabContent').on('click','#po_values',function() {
		$('#po_table_view').validate();
		$('#po_table_view').submit();
	});
	
	//function to save program outcome data & validate
	$('#myTabContent').on('click','#crs_values',function() {
		$('#crs_table_view').validate();
		$('#crs_table_view').submit();
	});
        
        // function to be in active tab after page reload
        $(document).ready(function(){
	$('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
		localStorage.setItem('activeTab', $(e.target).attr('href'));
	});
	var activeTab = localStorage.getItem('activeTab');
	if(activeTab){
	$('#myTab a[href="' + activeTab + '"]').tab('show');
	}
        });
	
	/**Calling the modal on success**/
		function success_modal(msg) { 
				var data_options = '{"text":"Your data has been updated successfully","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
									var options = $.parseJSON(data_options);
									noty(options);				
		}

		function fail_modal(msg){//$('#myModal_fail').modal('show');				
				$('#loading').hide();
				var data_options = '{"text":"Your data not updated! ","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
									var options = $.parseJSON(data_options);
									noty(options);
		}
	