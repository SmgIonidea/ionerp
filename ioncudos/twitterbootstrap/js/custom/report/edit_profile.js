var base_url = $('#get_base_url').val();
var log = $('#log_history_ds').val();
var log_history_set;

/**Funtion to toggle log history button**/
if(log == 0){$(".locked_active").addClass( "btn-success" ); $(".unlocked_inactive" ).removeClass( "btn-success" );}
else if(log == 1){$(".locked_active").removeClass( "btn-success" );$(".unlocked_inactive" ).addClass( "btn-success" );} 
 
 
$('#toggle_event_editing button').click(function(){
	if($(this).hasClass('locked_active')){				   
		 log_history_set = 0;
		 $('#toggle_event_editing button').eq(0).addClass('btn-default btn-success');$('#toggle_event_editing button').eq(1).removeClass('btn-success btn-default');
		 enable_modal(log); 
	}else{
   	    log_history_set = 1;
 	 	$('#toggle_event_editing button').eq(1).addClass('btn-success btn-default');$('#toggle_event_editing button').eq(0).removeClass('btn-success btn-default');
 		dissable_modal(log);		
	}

	var user_id = $('#user_id').val();
	post_data =  {'log_history_set':log_history_set,'user_id':user_id}
		$.ajax({type:"POST",
					url:base_url+'report/edit_profile/set_log_history',
					data:post_data,
					dataType:'json',
					success:function(data){}
			});
});
//base_url('twitterbootstrap/js/utils.js'); 
// initialise plugin
$("#patent_status option").html(function(i,str){
  return str.replace(/On Going|Completed/g,
     function(m,n){
         return (m == "On Going")?"Applied":"Granted";
     });
});

var telInput = $("#mobile-number");
var errorMsg = $("#error-msg");
var validMsg = $("#valid-msg");

var reset = function() {
  telInput.removeClass("error");
  errorMsg.addClass("hide");
  validMsg.addClass("hide");
};
// on blur: validate
 telInput.on('blur change ',function() {
 reset();
  if ($.trim(telInput.val())) {  
    if (telInput.intlTelInput("isValidNumber")) {
      validMsg.removeClass("hide");
	  //	  document.getElementById('mobile-number').style.borderColor='green';
    } else {
      telInput.addClass("error");
      errorMsg.removeClass("hide");
	 // document.getElementById('mobile-number').style.borderColor='#e52213';
    }
  }
}); 


// on keyup / change flag: reset
//telInput.on("keyup change", reset);
/*Function to fecth user achivements*/
var user_id=$('#user_id').val();
	//loading my Achievements and my training details
	fetch_research($("#type_publication_filter option:selected").val());
	function fetch_research(type){
	post_data =  {'user_id':user_id , 'publication_type' : type}
	$.ajax({type: "POST",
			url: base_url+'report/edit_profile/fetch_my_achievements',
			data: post_data,
			dataType: 'json',
		   success: populate_table
	});
	}
	
	$('#type_publication_filter').on('change',function(){
			fetch_research($("#type_publication_filter option:selected").val());
	});
	
	fetch_user_designation_list();
	function fetch_user_designation_list(){
	
		var design_user_id = $('#design_user_id').val();
			post_data =  {'user_id':design_user_id}
		$.ajax({type: "POST",
				url: base_url+'report/edit_profile/fetch_user_designation_list',
				data: post_data,
				dataType: 'json',
			   success: populate_table_user_designation_table
		});
	}
/*Function to fecth user journal  publications*/
	fetch_journal();
	function fetch_journal(){
	post_data =  {'user_id':user_id}
	$.ajax({type: "POST",
			url: base_url+'report/edit_profile/fetch_journal_publication',
			data: post_data,
			dataType: 'json',
		   success: populate_training_table
	});
	}
/*Function to fecth user workload*/
	post_data =  {'user_id':user_id}
	$.ajax({type: "POST",
			url: base_url+'report/edit_profile/fetch_my_teching_workload',
			data: post_data,
			dataType: 'json',
		   success: populate_teaching_table
	});
/* 	fetch_innovation();
	function fetch_innovation(){
	
	var user_id=$('#user_id').val();
	post_data =  {'user_id':user_id}
	$.ajax({type: "POST",
			url: base_url+'report/edit_profile/fetch_my_innovations',
			data: post_data,
			dataType: 'json',
		    success: populate_my_innovation
	});
	}  */
	/*Function to fecth consultancy data*/
	fetch_consultant_project_data();
	function fetch_consultant_project_data(){
		var user_id=$('#user_id').val();
		post_data =  {'user_id':user_id}
		$.ajax({type: "POST",
				url: base_url+'report/edit_profile/fetch_consultant_project_data',
				data: post_data,
				dataType: 'json',
				success: populate_consultant_project
		});
		
	}	
/*Function to fecth sponsored project  data*/
	fetch_spo_project_data();
	function fetch_spo_project_data(){
		var user_id=$('#user_id').val();
		post_data =  {'user_id':user_id}
		$.ajax({type: "POST",
				url: base_url+'report/edit_profile/fetch_spo_project_data',
				data: post_data,
				dataType: 'json',
				success: populate_sponsored_project
		});
		
	}	
	/*Function to fecth award -honour  data*/
	fetch_award_honour_data();
	function fetch_award_honour_data(){
		var user_id=$('#user_id').val();
		post_data =  {'user_id':user_id}
		$.ajax({type: "POST",
				url: base_url+'report/edit_profile/fetch_award_honour_data',
				data: post_data,
				dataType: 'json',
				success: populate_award_honour_data
		});
		
	}	
		/*Function to fecth patent data*/
	fetch_patent();
	function fetch_patent(){
		var user_id=$('#user_id').val();
		post_data =  {'user_id':user_id}
		$.ajax({type: "POST",
				url: base_url+'report/edit_profile/fetch_patent',
				data: post_data,
				dataType: 'json',
				success: populate_patent
		});
		
	}	
	/*Function to fecth Fellowship -Scholoarship data*/
	fetch_scholar();
	function fetch_scholar(){
		var user_id=$('#user_id').val();
		post_data =  {'user_id':user_id}
		$.ajax({type: "POST",
				url: base_url+'report/edit_profile/fetch_scholar',
				data: post_data,
				dataType: 'json',
				success: populate_scholar
		});
		
	}
	
		/*Function to fecth paper presentation  data*/
	$('#select_level_present').on('change',function(){
	fetch_paper_presentation();
	});	
 	//fetch_paper_presentation();
	function fetch_paper_presentation(){
	var select_level_present = $('#select_level_present').val();
		var user_id=$('#user_id').val();
		post_data =  {'user_id':user_id ,'select_level_present':select_level_present}
		$.ajax({type: "POST",
				url: base_url+'report/edit_profile/fetch_paper_presentation',
				data: post_data,
				dataType: 'json',
				success: populate_fetch_paper_presentation
		});
	}  	
		/*Function to fecth book data*/
	fetch_text_reference_book();
	function fetch_text_reference_book(){
		var user_id=$('#user_id').val();
		post_data =  {'user_id':user_id}
		$.ajax({type: "POST",
				url: base_url+'report/edit_profile/fetch_text_reference_book',
				data: post_data,
				dataType: 'json',
				success: populate_text_reference_book
		});
	} 	
	
	var select_training_type = $('#select_training_type').val(); 
	
	$('#select_training_type').on('change',function(){
	fetch_training_workshop_conference();
	});
	
	/*Function to fecth tarining_workshop_conference  data*/
	
	fetch_training_workshop_conference();
	function fetch_training_workshop_conference(){
		var select_training_type = $('#select_training_type').val();
		var user_id=$('#user_id').val();
		post_data =  {'user_id':user_id , 'select_training_type':select_training_type}
		$.ajax({type: "POST",
				url: base_url+'report/edit_profile/fetch_training_workshop_conference',
				data: post_data,
				dataType: 'json',
				success: populate_fetch_training_workshop_conference
		});
	} 	
	
	fetch_training_workshop_conference_attended();
	function fetch_training_workshop_conference_attended(){
		var select_training_type = $('#select_training_type').val();
		var user_id=$('#user_id').val();
		post_data =  {'user_id':user_id , 'select_training_type':select_training_type}
		$.ajax({type: "POST",
				url: base_url+'report/edit_profile/fetch_training_workshop_conference_attended',
				data: post_data,
				dataType: 'json',
				success: populate_fetch_training_workshop_conference_attended
		});
	} 
	fetch_research_pojects();
	function fetch_research_pojects(){			
		var user_id=$('#user_id').val();
		post_data =  {'user_id':user_id , 'select_training_type':select_training_type}
		$.ajax({type: "POST",
				url: base_url+'report/edit_profile/fetch_research_projects',
				data: post_data,
				dataType: 'json',
				success: populate_research_projects
		});
	}
	
	
$(document).ready(function(){
    $(".nav-tabs a").click(function(){
        var href = $(this).attr('href');
		var avoid = "#";
		var tab=href.replace(avoid, '');
		$('#upload_id').val(tab);
    });
});
	

		// validation to accept only letters
	$.validator.addMethod("alphabetsOnly",function(value, element, regexp) {
            var re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
    },"Please Enter Valid Data!!!.");
	
	// select box validation
	$.validator.addMethod('selectcheck', function (value) {
        return (value != '0');
    }, "This field is required.");	// select box validation
	
	$.validator.addMethod('selectcheck_year', function (value) {
        return (value != '0000-00-00');
    }, "This field is required.");	
	
 	$.validator.addMethod("loginRegex", function(value, element) {
         return this.optional(element) || /^[a-zA-Z\.\s]+([-\a-zA-Z\.\s])*$/i.test(value);
    }, "Field must contain only letters, spaces or dashes.");
	
	/* $.validator.addMethod("loginRegex_spec", function(value, element) {
         return this.optional(element) || /^[a-zA-Z 0-9\s]+([.\+\#\&\,\_\-\a-zA-Z 0-9\s\&])*$/i.test(value);
    }, "Field contains invalid data.");
	 */
		
	$.validator.addMethod("loginRegex_spec", function(value, element) {
         return this.optional(element) || /^[a-zA-Z 0-9\s]+([-\a-zA-Z\/\.\(\)\:\;\{\}\[\]+\"\'\\\#\@\&\,\_\-\a-zA-Z 0-9\s\&])*$/i.test(value);
    }, "Field contains invalid data.");
		
	$.validator.addMethod("pages_valid", function(value, element) {
		var regex =/^[0-9]+(-[0-9]+)?$/; //this is for numeric... you can do any regular expression you like...
		return this.optional(element) || regex.test(value);
	}, "Field contains invalid data");
	
	$.validator.addMethod("numeric", function(value, element) {
		var regex =/^(?:[0-9]\d*|0)?(?:\.\,\d+)?$/; //this is for numeric... you can do any regular expression you like...
	//	var regex = /^\s*?([\d\,]+(\.\d{1,2})?|\.\d{1,2})\s*$/;
		return this.optional(element) || regex.test(value);
	}, "Field must contain only numbers.");  

	$.validator.addMethod("hour_validation", function(value, element) {
		var regex =/^([0-5]?[0-9]|59)$/; //this is for numeric... you can do any regular expression you like...
		return this.optional(element) || regex.test(value);
	}, "Invalid!.");  

	$.validator.addMethod("percent", function(value, element) {
		var regex =/^(?:[0-9]|[0-9][0-9]|100)$/; //this is for numeric... you can do any regular expression you like...
		return this.optional(element) || regex.test(value);
	}, "must be bellow 100");	

	$.validator.addMethod("percentage", function (value, element) {
    return this.optional(element) || /^[0-9\.]+$/i.test(value);
}, "Enter a valid number.");
	
/* 	$.validator.addMethod("loginRegex", function(value, element) {
			return this.optional(element) || /^[a-zA-Z 0-9\_\-\s\'\/\,\!\#\$\%\^\&\*\(\)\.\:\;\"\<\>\?]+$/i.test(value);
	}, "Field must contain only letters, spaces, ' or dashes or dot");   */  
	
	
	$.validator.addMethod("url_valid", function(value, element) {
		var regex =/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i; //this is for numeric... you can do any regular expression you like...
		return this.optional(element) || regex.test(value);
	}, "Invalid URL Type");	
    
	$.validator.addMethod('email', function (value, element) {
        return this.optional(element) || /^(^[A-Za-z0-9]+([\._-]?[A-Za-z0-9]+)*@[A-Za-z0-9]+([-]?[0-9a-zA-Z]+)*(\.[A-Za-z0-9]+([-]?[0-9a-zA-Z]+)*)+)+$/.test(value);
    },
            'Invalid email address.'
     );
	
	var design = $("#designation option:selected").text();
	$('#current_designation').html(design);
	
	
	
	$('#teach_experiance , #indus_experiance').live('keyup blur',function(){
	var teach_val = parseInt($('#teach_experiance').val());
	var indust_val = parseInt($('#indus_experiance').val());
	var experiance = teach_val + indust_val;
			$('#experiance').val(experiance);
	});
	
	/* My_Profile tab validation and data updation*/
	$('#my_profile').on('click', function(){

	 $('#loading').show();
        $("#edit_my_profile").validate({
            rules: {
                first_name: {
                    loginRegex: true,
                    maxlength: 100,
                },
				email_id_alt:{
					email:true
				},
				last_name:{
					loginRegex: true,
                    maxlength: 100,
				},
                university: {
                    required: true,
					loginRegex_spec : true
                },
				heighest_qualification:{
					required:true,
					selectcheck:true
				},
				blood_group:{
				selectcheck:false
				},
				experiance:{
					//required:true,
					numeric:false
				},
				teach_experiance:{
					//required:true,
					numeric:true
				},
				indus_experiance:{
					//required:true,
					numeric:true
				},
				salary_pay:{
					//required:true,
					numeric:false
				},
				user_title:{
					required:true
				},
				 dp3: {
                    required: true
                },
				present_adr:{
					required:false
				},
				permanent_adr:{
					required:false
				},
                select: {
                    required: true
                },
				faculty_type:{
					selectcheck : true
				},
				emp_no:{
					loginRegex_spec : true
				},
				designation: {
               //    required:true,
					selectcheck:true
                },
				dp2:{
				//required : true,
				selectcheck_year : true
				},
				resigning_date:{
				selectcheck_year : false
				},
				faculty_serving:{
				selectcheck : true
				},
				user_website:{
					url_valid : true,
				},
				phd_from:{
					loginRegex_spec : true
				},	
				superviser:{
					loginRegex_spec : true
				},	
				phd_status:{
					loginRegex_spec : true
				},	
				research_interest:{
					loginRegex_spec : true
				},
				skills:{
					loginRegex_spec : true
				},	
				remark:{
					loginRegex_spec : true
				},		
				responsibilities:{
					loginRegex_spec : true
				},	
				phd_guidance:{
					loginRegex_spec : true
				},		
				user_specialization:{
					loginRegex_spec : true
				},
				professional_bodies:{
					loginRegex_spec : true
				},
            },
            messages: {
                first_name: {
                    required: "This field is required.",
                },
				last_name:{
					required: "This field is required."
				},				  
                dp3: {
                    required: "This field is required.",
                }, 
				dp2: {
                    required: "This field is required.",
                }, 	
				resigning_date: {
                    required: "This field is required.",
                }, 	
				faculty_serving: {
                    required: "This field is required.",
                },            
				present_adr:{
					required:"Required",
				},
                university: {
                    required: "This field is required.",
                },

                select: "Select This Field."
            },
          	errorPlacement : function(error, element) {
			if (element.attr('name') == "dp3") {
				error.appendTo('#error_placeholder');
			} else if (element.attr('name') == "dp2") {
				error.appendTo('#error_placeholder_yoj');
			} else if (element.attr('name') == "resigning_date") {
				error.appendTo('#error_placeholder_resign');
			}
			else {
				error.insertAfter(element);
			}
		
		}
        });
		//
		var validation_flag = $('#edit_my_profile').valid();
		if(validation_flag == true )
		{
			var title=$('#user_title').val();
			var first_name=$('#first_name').val();
			var last_name=$('#last_name').val();
			var email_id=$('#email_id').val();
			var contact=$('#mobile-number').val();
			var heighest_qualification=$('#heighest_qualification').val();
			var university=$('#university').val();
			var experiance=$('#experiance').val();
			var permanent_adr=$('#permanent_adr').val();
			var present_adr=$('#present_adr').val();
			var dob=$('#dob').val();
			var year_of_graduation=$('#dp3').val();
			var yoj = $('#dp2').val(); 
			var designation = $('#designation').val();
			var resigning_date = $('#resigning_date').val();
			var faculty_serving = $('#faculty_serving').val();
			var faculty_mode;
			var user_id = $('#user_id').val();
			var password = $('#reset_password').val();
			var retirement_date = $('#retirement_date').val();
			var remark = $('#remark').val();
			var emp_no = $('#emp_no').val();
			var faculty_type = $('#faculty_type').val();
			var last_promotion = $('#last_promotion').val();
			var teach_experiance = $('#teach_experiance').val();
			var indus_experiance = $('#indus_experiance').val();
			var salary_pay = $('#salary_pay').val();
			var user_website = $('#user_website').val();
			var phd_from = $('#phd_from').val();
			var superviser = $('#superviser').val();
			var phd_status = $('#phd_status').val(); 
			var research_interest = $('#research_interest').val();
			var skills = $('#skills').val();
			var responsibilities = $('#responsibilities').val();
			var guidance_within_org = $('#guidance_within_org').val();
			var guidance_outside_org = $('#guidance_outside_org').val();
			var user_specialization = $('#user_specialization').val();
			var phd_assessment_year = $('#phd_assessment_year').val();
			var email_id_alt = $('#email_id_alt').val();
			var Mem_BloodGr = $('#Mem_BloodGr').val();
			var professional_bodies = $('#professional_bodies').val();
			var phd_url = $('#phd_url').val();
			
			 if($('#faculty_mode').is(':checked')){faculty_mode = 1;}else { faculty_mode = 0}
			 if($('#log_history_dissable').attr('checked')) {prevent_log_history = 1;}else{prevent_log_history = 0;}
			post_data={'title':title,'first_name':first_name,'last_name':last_name,'email_id':email_id,'contact':contact,'heighest_qualification':heighest_qualification,'university':university,'experiance':experiance,'year_of_graduation':year_of_graduation,'dob':dob,'present_adr':present_adr,'permanent_adr':permanent_adr,'yoj':yoj,'designation':designation,'resigning_date':resigning_date,'faculty_serving':faculty_serving,'faculty_mode':faculty_mode,'user_id':user_id,'reset_password':password,'prevent_log_history':prevent_log_history,'retirement_date':retirement_date ,'remark':remark,'emp_no':emp_no ,'faculty_type':faculty_type,'last_promotion':last_promotion,
			'teach_experiance':teach_experiance,'indus_experiance':indus_experiance ,'salary_pay':salary_pay,'user_website':user_website,'phd_from':phd_from , 'superviser':superviser,'phd_status':phd_status,'research_interest':research_interest , 'skills':skills ,'responsibilities':responsibilities ,'guidance_within_org':guidance_within_org , 'guidance_outside_org':guidance_outside_org , 'user_specialization':user_specialization , 'phd_assessment_year':phd_assessment_year ,'email_id_alt':email_id_alt ,'Mem_BloodGr':Mem_BloodGr,'professional_bodies':professional_bodies ,
			'phd_url' : phd_url}
			
		 	$.ajax({type:"POST",
					url:base_url+'report/edit_profile/save_my_profile',
					data:post_data,
					dataType:'json',
					success:function(data){success_modal(); $("#reset_password").val('');
					if(data=='password_set'){ window.location = base_url + 'dashboard/dashboard/'; } 
					$('#loading').hide();}
			}); 
		}else{	
	/* 	telInput.removeClass("error");
		errorMsg.addClass("hide");
		validMsg.addClass("hide"); */
		
	//	telInput.addClass("error");
	//	errorMsg.removeClass("hide");
		//document.getElementById('mobile-number').style.borderColor='#e52213';
		$('#loading').hide();}
	
	});

	$('#faculty_mode').on('click',function(){
	
	});

	
	
	// Save description of each file in research papers and guid
 	$('#save_res_guid_description').live('click', function(e) {
		e.preventDefault();
		$('#myform').submit();
	}); 
	
	$('#myform').on('submit', function(e) {
		e.preventDefault();
	var str = "";

			$(':checkbox').each(function() {
				str += this.checked ? "1," : "0,";
			});

			str = str.substr(0, str.length - 1);  
		$('#fetch_approve').val(str); 
		
		var form_data = new FormData(this);
		var form_val = new Array();	
				
	
		$('.save_form_data').each(function() {
			//values fetched will be inserted into the array	 		 
			form_val.push($(this).val());		
		});

		//check whether file any file exists or not
		 if(form_val.length > 0) {
			//if file exists
			$.ajax({
				  type: "POST",
				  url: base_url+'report/edit_profile/save_res_guid_desc',
				   data : form_data,
				   contentType : false,
				   cache : false,
				   processData : false,
				   success: function(msg) {
						if($.trim(msg) == 1) {
							//display success message on save
							var data_options = '{"text":"Your data has been updated successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
							var options = $.parseJSON(data_options);
							noty(options);
						} else {
							//display error message - if description and date could not be saved
							var data_options = '{"text":"Your data could not be saved.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
							var options = $.parseJSON(data_options);
							noty(options);
							//$('#upload_modal').modal('hide');
						}
				  }
			});
		} else {
			//display error message if file does not exist and user tries to click save button
			var data_options = '{"text":"File needs to be uploaded.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
			var options = $.parseJSON(data_options);
			noty(options);
		} 
	});
	

	$('#dept').live('click change ',function(){
	$('#loading').show();
	$('#workload_switch').val('2');
		fetch_pro_type();	
	});
fetch_pro_type();
	// To Fetch program Types 
	function  fetch_pro_type(){	
	var dept =   $('#dept :selected').val();
	var post_data ={'dept':dept}
			$.ajax({type:"POST",
					url:base_url+'report/edit_profile/fetch_program_type',
					data:post_data,
					success:function(data){	
						$('#loading').hide();
					 document.getElementById('program_type').innerHTML = data;
					 fetch_pro();
					}
			});
	}	
	
	function  fetch_pro_type_one(){	
	var dept =   $('#dept :selected').val();
	var post_data ={'dept':dept}
			$.ajax({type:"POST",
					url:base_url+'report/edit_profile/fetch_program_type',
					data:post_data,
					success:function(data){	
						$('#loading').hide();
					 document.getElementById('program_type').innerHTML = data;
					 fetch_pro(); //visiting Development
					}
			});
	}
	
	// To Fetch Programs
	function fetch_pro(){		
	var dept =   $('#dept :selected').val();
	var  program_type_id =  $('#program_type_id :selected').val();
	var post_data ={'dept':dept,'program_type_id':program_type_id}
			$.ajax({type:"POST",
					url:base_url+'report/edit_profile/fetch_program',
					data:post_data,
					success:function(data){
						$('#loading').hide();
					 document.getElementById('program').innerHTML = data;
					 program_fetch();
					}
			});
	
	}
	
	// To Fetch Prgram Category
	function program_fetch(){
		var dept = $('#dept :selected').val();
		var program_id = $('#program_id :selected').val();		
		var post_data ={'dept':dept,'program_id':program_id}
			$.ajax({type:"POST",
					url:base_url+'report/edit_profile/program_category',
					data:post_data,
					success:function(data){document.getElementById('progra_cat').innerHTML = data;}
			});
			$.ajax({type:"POST",
					url:base_url+'report/edit_profile/fetch_year',
					data:post_data,
					success:function(data){ document.getElementById('terms').innerHTML = data;}
			});
			return 1;
	}
	
	// To save and update teaching details of user
	$('#my_teaching,#my_teaching_update').on('click', function(){ 	
		//$('#loading').show();
        $("#my_teaching_pro").validate({
            rules: {
              dept: {       
					selectcheck:true
                },
				work_load:{
					//numeric: true,
					percentage: true,
                    maxlength: 100,
					required:true,
				},                
              	program_id:{
					required:true,
					selectcheck:true
				},
				year_sem:{
					required:true,
					selectcheck:true
				},
				accademic:{
					required:true,
					selectcheck:true
				},
				type_of_work:{
					required:true,
					selectcheck:true
				
				},
            },
  			errorPlacement : function(error, element) {
			if (element.attr('name') == "accademic") {
				error.appendTo('#accademic_error');
			} else {
				error.insertAfter(element);
			}
		
		}
        });

		var validation_flag = $('#my_teaching_pro').valid();		
		if(validation_flag == true){ 	
			var type_of_work;
			var dept_name=$('#dept').val();
			var user_id=$('#user_id').val();
			var designation=$('#designation').val();
			var work_load=$('#work_load').val();
			var program_id=$('#program_id').val();
			var pgm_category = $('#pgm_category').val(); 
			var program_type_id = $('#program_type_id').val();
			var year = $('#year_sem').val();
			var my_wid = $('#my_wid').val();
			var my_teaching_edit_val = $('#my_teaching_edit_val').val(); 			
			var yoj=$('#dp2').val();
			var accademic = $('#accademic').val();
			var type_of_work_val = $('#type_of_work').val(); 
			if(type_of_work_val != 177){ type_of_work = $("#type_of_work option:selected").text(); }else{ type_of_work = $('#type_of_work_others').val();}
			post_data={'type_of_work':type_of_work,'program_type_id':program_type_id,'pgm_category':pgm_category,'accademic':accademic,'dept_name':dept_name,'year':year,'program':program_id,'workload':work_load,'user_id':user_id,'my_teaching_edit_val':my_teaching_edit_val,'my_wid':my_wid ,'work_type':type_of_work_val} 
	
			$.ajax({type:"POST",
					url:base_url+'report/edit_profile/save_my_teaching',
					data:post_data,
					dataType:'json',
					success:function(data){{ $('#my_teaching_edit_val').val('0');$('#loading').hide(); if(data == 1){success_modal();}else if(data == 0){fail_modal(data);}
												reset_my_teaching();$('#my_teaching_update').hide();$('#my_teaching').show();
												var user_id=$('#user_id').val();
												post_data =  {'user_id':user_id}
												$.ajax({type: "POST",
														url: base_url+'report/edit_profile/fetch_my_teching_workload',
														data: post_data,
														dataType: 'json',
													   success: populate_teaching_table
												});

											}}
			});			
		}else{$('#loading').hide();}
	});
	
	// To reset the my_teaching form 
	function reset_my_teaching(){
		fetch_pro_type();
		$("#my_teaching_pro").trigger('reset');$('#my_teaching_edit_val').val(0); 
		var validator = $('#my_teaching_pro').validate();
		validator.resetForm();	
		$('#my_teaching_update').hide();$('#my_teaching').show();$('#type_of_work_others').hide();
	}


	$('#type_of_work').on('change' , function(){
		if($('#type_of_work').val() == 177){
			$('#type_of_work_others').show();
			$('#type_of_work_others').attr('required',true);
			 $('#my_teaching_pro').validate();
		}else{	$('#type_of_work_others').hide();}
	});
	
		
	/**	my_research Paper tab validation , data save and updation**/

	$('#my_research_pape_save,#my_research_peper_update').on('click',function(e){
 	$("#research_publication").validate({
            rules: {
               title_res_research: {
                   required: true,	
				   loginRegex_spec : true
                },
				author_research:{
                   required: false,	
				   loginRegex_spec : true					
				},
				publisher_research:{
					required: false,	
				   loginRegex_spec : true
				},
				title_res: {
					required : true,
					loginRegex_spec : true
				},
				author: {
					required : false,
					loginRegex_spec : true
				},
				contribution_res_guid_research: {
					required : false,
					loginRegex_spec : true
				},			
				index_terms_research: {
					required : false,
					loginRegex_spec : true
				},	
				publish_online_research: {
					required : false,
					loginRegex_spec : false,
					url_valid : true
				},
				pages_research:{
					numeric : false,
					pages_valid : true,
				},
			  dp4:{
					required: true,					
			  }
				
            },
            messages: {
                publica: {
                    required: "This field is required.",
                },
				dp4:{
					required: "This field is required.",					
			  }
            },
			errorPlacement : function(error, element) {
				if (element.attr('name') == "dp4") {
					error.appendTo('#start_error_placeholder_dp4');
				} else if (element.attr('name') == "dp4_end") {
				error.appendTo('#end_error_placeholder_dp4');
				}else {
					error.insertAfter(element);
				}
			}

        }); 
		 var idClicked = e.target.id; 
		 var idClicked = e.target.id; 
		var validation_flag = $('#research_publication').valid();
	
 	 	if(validation_flag == true)
		{ 
		
		tinyMCE.triggerSave();
		var str=$('#abstract_data_research').val();
		var str1=str.replace("<p>", " ");
		var str2=str1.replace("</p>"," ");
		var res= str2.replace('alt=""', 'alt="image"');
		var abstract_description=res;
		if(idClicked == 'my_research_peper_update'){ buttonvalue = 'update'} else if( idClicked == 'my_research_pape_save'){ buttonvalue = 'save' }
		   var values = $("#research_publication").serialize();
			values = values + '&button_update='+buttonvalue;
		//	values = values + '&about_book='+abstract_description;
			console.log(values);
			$.ajax({type:"POST",
					url:base_url+'report/edit_profile/save_my_research_papers',
					data:values,
					//dataType:'json',
					success:function(data){$('#loading').hide();fetch_research($("#type_publication_filter option:selected").val());success_modal(data);$('#my_research_pape_save').show();$('#my_research_peper_update').hide();reset_reaearch_papers();}
			}); 
		} else{$('#loading').hide();}
			
	});
	
	// To Reset my research Paper Form
	function reset_reaearch_papers(){
		$("#research_publication").trigger('reset');$('#my_res_guid_id_update_val').val(0);
		$('#research_id').val("");
		var validator = $('#research_publication').validate();
		validator.resetForm();	 $( "#publication_award_won").prop('checked', false);	$('.publication_won').hide(); 
		$('#my_research_peper_update').hide();$('#my_research_pape_save').show();
	}
	
	$("#publication_award_won").on('click',function(){
		check_checkbox();	
	});
	check_checkbox();
	function check_checkbox(){
		if($("#publication_award_won").is(':checked')){
		$('.publication_won').show();  // checked
		$('#publication_award_won_text').attr('required',true);
		}
		else{
			$('.publication_won').hide(); }
	}
	
	
	$('#update_consult_project').hide();
	$('#save_consult_project,#update_consult_project').on('click',function(e){
	$('#loading').show();
		$("#consultancy_projects").validate({
		rules: {
			  year_consult:{
					required: true,					
			  },
			  project_code:{
					loginRegex_spec :true,
			  },			  
			  project_title:{
					loginRegex_spec :true,
			  },		
			  client:{
					loginRegex_spec :true,
			  },
			  consultant:{
					loginRegex_spec :true,
			  },	
			  co_consultant:{
					loginRegex_spec :true,
			  },
			  amount_consult:{
					numeric :false,
			  },
				
            },
            messages: {  
				year_consult:{
					required: "This field is required.",					
			  }
            },
			errorPlacement : function(error, element) {
				if (element.attr('name') == "year_consult") {
					error.appendTo('#error_year_consult_btn');
				} else {
					error.insertAfter(element);
				}
			}
		});
		 var idClicked = e.target.id; 
		var flag = $('#consultancy_projects').valid(); 
		var buttonvalue;
		if(flag == true){
		tinyMCE.triggerSave();
		var str=$('#abstract_consult').val();
		var str1=str.replace("<p>", " ");
		var str2=str1.replace("</p>"," ");
		var res= str2.replace('alt=""', 'alt="image"');
		var abstract_description=res;
		
		if(idClicked == 'update_consult_project'){ buttonvalue = 'update'} else if( idClicked == 'save_consult_project'){ buttonvalue = 'save' }
		   var values = $("#consultancy_projects").serialize();
			values = values + '&button_update='+buttonvalue;
			//values = values + '&abstract_consult='+abstract_description;
			console.log(values);
		   var post_data ={'data':values}
		   $.ajax({type:"POST",
					url:base_url+'report/edit_profile/save_update_consult_projects',
					data:values,
					dataType:'json',
					success:function(data){$('#loading').hide();$('#update_consult_project').hide();$('#save_consult_project').show();fetch_consultant_project_data(data);
					reset_consult_project();	if(data == 1){success_modal(data);}if(data == 0){fail_modal(data);}}});
		}else{$('#loading').hide();}
	});
	$('#consultant_role').hide();
	$('#consult_role').on('change',function(){
		var role  = $('#consult_role').val();
		if(role == 181){$('#consultant').attr('required',true);$('#consultant_role').show();$('#consultant').val('');}else{$('#consultant_role').hide();
		$('#consultant').val($("#consult_role option:selected").text());
		}
	});
	function reset_consult_project(){
		$("#consultancy_projects").trigger('reset');
		$('#c_id').val("");
		var validator = $('#consultancy_projects').validate();
		validator.resetForm();	
		$('#update_consult_project').hide();$('#save_consult_project').show();$('#consultant_role').hide();
	}
	
	$('#update_spo_project').hide();
	$('#save_spo_project,#update_spo_project').on('click',function(e){
	$('#loading').show();
		$("#sponsored_projects").validate({
		rules: {
			  spo_year:{
					required: true,					
			  },
			  spo_amount:{
					numeric :false,
			  }, 
			  spo_project_code:{
					loginRegex_spec :true,
			  },
			  spo_project_title:{
					loginRegex_spec :true,
			  },	
			  spo_investigator:{
					loginRegex_spec :true,
			  }, 
			  co_spo_investigator:{
					loginRegex_spec :true,
			  },  
			  spo_oganization:{
					loginRegex_spec :true,
			  },
			  collaborating_organization:{
				loginRegex_spec :true,
			  },
				
            },
            messages: {  
				spo_year:{
					required: "This field is required.",					
			  }
            },
			errorPlacement : function(error, element) {
				if (element.attr('name') == "spo_year") {
					error.appendTo('#error_spo_year_btn');
				} else {
					error.insertAfter(element);
				}
			}
		});
		 var idClicked = e.target.id; 
		var flag = $('#sponsored_projects').valid(); 
		var buttonvalue;
		if(flag == true){
		tinyMCE.triggerSave();
		var str=$('#abstract_spo').val();
		var str1=str.replace("<p>", " ");
		var str2=str1.replace("</p>"," ");
		var res= str2.replace('alt=""', 'alt="image"');
		var abstract_description=res;
		if(idClicked == 'update_spo_project'){ buttonvalue = 'update'} else if( idClicked == 'save_spo_project'){ buttonvalue = 'save' }
		   var values = $("#sponsored_projects").serialize();
			values = values + '&button_update='+buttonvalue;
			//values = values + '&abstract_spo='+abstract_description;	
			console.log(values);
		   var post_data ={'data':values}
		   $.ajax({type:"POST",
					url:base_url+'report/edit_profile/save_update_sponsored_projects',
					data:values,
					dataType:'json',
					success:function(data){$('#update_spo_project').hide();$('#save_spo_project').show();fetch_spo_project_data(data);
					reset_spo_project();$('#loading').hide();if(data == 1){success_modal(data);}if(data == 0){fail_modal(data);}}});
		}else{$('#loading').hide();}
	});
	$('#tab7').on('click','.delete_sponsored_projects',function(){
		$('#s_id').val($(this).attr('id'));	
		$('#delete_confirm_sponsored').modal('show');
		
	});
		
	$('#delete_sponsored').on('click',function(){
	$('#loading').show();
	var s_id = $('#s_id').val();
	 var post_data ={'s_id':s_id}
		   $.ajax({type:"POST",
					url:base_url+'report/edit_profile/delete_sponsored_project',
					data:post_data,					
					success:function(data){ 
					if(data == 1){ }$('#loading').hide();
					success_modal_delete();
					$('#update_spo_project').hide();$('#save_spo_project').show();fetch_spo_project_data(data);
					reset_spo_project();}}); 
	});	
	
	$('#tab8').on('click','.delete_award_honour',function(){
		$('#award_id').val($(this).attr('id'));	
		$('#delete_award_confirm').modal('show');
		
	});
		
	$('#delete_award').on('click',function(){
	
	var award_id = $('#award_id').val();
	 var post_data ={'award_id':award_id}
		   $.ajax({type:"POST",
					url:base_url+'report/edit_profile/delete_award',
					data:post_data,					
					success:function(data){ 
					if(data == 1){ }
					success_modal_delete();
					$('#update_award_honour').hide();$('#save_award_honour').show();fetch_award_honour_data(data);
					reset_award_honour();}}); 
	});		
	
	$('#tab9').on('click','.delete_patent',function(){
		$('#patent_id').val($(this).attr('id')); 
		$('#delete_patent_confirm').modal('show');
		
	});
		
	$('#delete_patent').on('click',function(){
	$('#loading').show();
	var patent_id = $('#patent_id').val();
	 var post_data ={'patent_id':patent_id}
		   $.ajax({type:"POST",
					url:base_url+'report/edit_profile/delete_patent',
					data:post_data,					
					success:function(data){ 
					if(data == 1){ }success_modal_delete();
					$('#loading').hide();
					$('#update_patent').hide();$('#save_patent').show();fetch_patent(data);
					reset_patent();}}); 
	});	
	$('#tab10').on('click','.delete_scholor',function(){
		$('#scholar_id').val($(this).attr('id'));	
		$('#delete_scholor_confirm').modal('show');
		
	});
		
	$('#delete_scholor').on('click',function(){
	$('#loading').show();
	var scholar_id = $('#scholar_id').val();
	 var post_data ={'scholar_id':scholar_id}
		   $.ajax({type:"POST",
					url:base_url+'report/edit_profile/delete_scholor',
					data:post_data,					
					success:function(data){ 
					if(data == 1){ }success_modal_delete();$('#loading').hide();
					$('#update_scholar').hide();$('#save_scholar').show();fetch_scholar(data);
					reset_patent();}}); 
	});	
	
	// To Reset consultancy projects
	function reset_spo_project(){
		$("#sponsored_projects").trigger('reset');
		$('#s_id').val("");
		var validator = $('#sponsored_projects').validate();
		validator.resetForm();	
		$('#update_spo_project').hide();$('#save_spo_project').show();
	}		
	
	
	
	$('#update_award_honour').hide();
	$('#save_award_honour,#update_award_honour').on('click',function(e){
	$('#loading').show();
		$("#award_honours").validate({
		rules: {
			  awarded_year:{
					required: true,					
			  },
			  spo_amount:{
					numeric :false,
			  },
			  award_org:{
					loginRegex_spec:true,
			  }, 
			  award_name:{
					loginRegex_spec:true,
			  },		 
			  award_for:{
					loginRegex_spec:true,
			  },
			  award_remarks:{
					loginRegex_spec:true,
			  },
				
            },
            messages: {  
				awarded_year:{
					required: "This field is required.",					
			  }
            },
			errorPlacement : function(error, element) {
				if (element.attr('name') == "awarded_year") {
					error.appendTo('#error_awarded_year_btn');
				} else {
					error.insertAfter(element);
				}
			}
		});
		 var idClicked = e.target.id; 
		var flag = $('#award_honours').valid(); 
		var buttonvalue;
		if(flag == true){
		if(idClicked == 'update_award_honour'){ buttonvalue = 'update'} else if( idClicked == 'save_award_honour'){ buttonvalue = 'save' }
		   var values = $("#award_honours").serialize();
		       values = values + '&button_update='+buttonvalue;
		   $.ajax({type:"POST",
					url:base_url+'report/edit_profile/save_update_award_honour',
					data:values,
					dataType:'json',
					success:function(data){$('#loading').hide();reset_award_honour();$('#update_award_honour').hide();$('#save_award_honour').show();fetch_award_honour_data(data);
						if(data == 1){success_modal(data);}if(data == 0){fail_modal(data);}}});
		}else{$('#loading').hide();}
	});
	
		
	// To Reset consultancy projects
	function reset_award_honour(){	
		$("#award_honours").trigger('reset');
		$('#scholar_id').val("");
		var validator = $('#award_honours').validate();
		validator.resetForm();	
		$('#update_award_honour').hide();$('#save_award_honour').show();
	}	
	
	
	$('#update_research_project').hide();
	$('#save_research_project,#update_research_project').on('click',function(e){
	$('#loading').show();
		$("#research_project").validate({
		rules: {
			  awarded_year:{
					required: true,					
			  },
			  research_project_title:{
					loginRegex_spec:true,
			  },
			  research_project_team_member:{
					loginRegex_spec:true,
			  }, 
			  research_project_collabration:{
					loginRegex_spec:true,
			  },		 
			  research_project_funding_agency:{
					loginRegex_spec:true,
			  },
			  award_remarks:{
					loginRegex_spec:true,
			  },
				
            },
            messages: {  
				research_project_sactioned_date:{
					required: "This field is required.",					
			  }
            },
			errorPlacement : function(error, element) {
				if (element.attr('name') == "research_project_sactioned_date") {
					error.appendTo('#error_research_project_title_btn');
				} else {
					error.insertAfter(element);
				}
			}
		});
		 var idClicked = e.target.id; 
		var flag = $('#research_project').valid(); 
		var buttonvalue;
		if(flag == true){
		if(idClicked == 'update_research_project'){ buttonvalue = 'update'} else if( idClicked == 'save_research_project'){ buttonvalue = 'save' }
		   var values = $("#research_project").serialize();
			values = values + '&button_update='+buttonvalue;
		   $.ajax({type:"POST",
					url:base_url+'report/edit_profile/save_update_research_project',
					data:values,
					dataType:'json',
					success:function(data){$('#loading').hide();reset_research_project();$('#update_research_project').hide();$('#save_research_project').show();fetch_research_pojects();
					reset_award_honour();	if(data == 1){success_modal(data);}if(data == 0){fail_modal(data);}}});
		}else{$('#loading').hide();}
	});
	
		
	// To Reset consultancy projects
	function reset_research_project(){
		$("#research_project").trigger('reset');
		$('#research_id').val("");
		var validator = $('#research_project').validate();
		validator.resetForm();	
		$('#update_research_project').hide();$('#save_research_project').show();
	}	
	
	
	$('#update_patent').hide();
	$('#update_patent,#save_patent').on('click',function(e){
	$('#loading').show();
		$("#patent").validate({
		rules: {
			  patent_year:{
					required: true,					
			  },
			  patent_title:{
					required: true,
					loginRegex_spec:true,
			  },
			  inventors:{
					required: false,
					loginRegex_spec:true,
			  },
			  patent_no:{
					required : false,
					loginRegex_spec:true,
			  },
			 innovation_link: {
					url_valid :true,
            },
				
            },
            messages: {  
				patent_year:{
					required: "This field is required.",					
			  }
            },
			errorPlacement : function(error, element) {
				if (element.attr('name') == "patent_year") {
					error.appendTo('#error_patent_year_btn');
				} else {
					error.insertAfter(element);
				}
			}
		});
		 var idClicked = e.target.id; 
		var flag = $('#patent').valid(); 
		var buttonvalue;
		if(flag == true){
		tinyMCE.triggerSave();
		var str=$('#patent_abstract').val();
		var str1=str.replace("<p>", " ");
		var str2=str1.replace("</p>"," ");
		var res= str2.replace('alt=""', 'alt="image"');
		var abstract_description=res;
		if(idClicked == 'update_patent'){ buttonvalue = 'update'} else if( idClicked == 'save_patent'){ buttonvalue = 'save' }
		   var values = $("#patent").serialize();
			values = values + '&button_update='+buttonvalue;
			//values = values + '&patent_abstracts='+abstract_description;
			console.log(values);
	 	   $.ajax({type:"POST",
					url:base_url+'report/edit_profile/save_update_patent',
					data:values,
					dataType:'json',
					success:function(data){$('#loading').hide();$('#update_patent').hide();$('#save_patent').show();fetch_patent(data);
					reset_patent();	if(data == 1){success_modal(data);}if(data == 0){fail_modal(data);}}}); 
		}else{$('#loading').hide();}
	});	
	
	
	$('#update_scholar').hide();
	$('#update_scholar,#save_scholar').on('click',function(e){
	$('#loading').show();
		$("#sholarship").validate({
		rules: {
			scholar_year:{
					required: true,					
			  }, 
			sholarship_for:{
					required: true,	
					loginRegex_spec : true
			  },			
			awarded_by:{
					required: false,	
					loginRegex_spec : true   
			  },
				
            },
            messages: {  
				scholar_year:{
					required: "This field is required.",					
			  }
            },
/* 			errorPlacement : function(error, element) {
				if (element.attr('name') == "scholar_year") {
					error.appendTo('#error_scholar_year_btn');
				} else {
					error.insertAfter(element);
				}
			} */
			
			errorPlacement : function(error, element) {
				if (element.attr('name') == "scholar_year" || element.attr('name') == "scholar_end_year") {
					$("#error_scholar_year_btn").html(""); error.appendTo('#error_scholar_year_btn');				
				}	
				else {	error.insertAfter(element);}				
			}
		});
		 var idClicked = e.target.id; 
		var flag = $('#sholarship').valid(); 
		var buttonvalue;
		if(flag == true){
		tinyMCE.triggerSave();
		var str=$('#scholar_abstract').val();
		var str1=str.replace("<p>", " ");
		var str2=str1.replace("</p>"," ");
		var res= str2.replace('alt=""', 'alt="image"');
		var abstract_description=res;
		if(idClicked == 'update_scholar'){ buttonvalue = 'update'} else if( idClicked == 'save_scholar'){ buttonvalue = 'save' }
		   var values = $("#sholarship").serialize();
			values = values + '&button_update='+buttonvalue;
			//values = values + '&scholar_abstract='+abstract_description;
			//console.log(values);
	 	   $.ajax({type:"POST",
					url:base_url+'report/edit_profile/save_update_scholar',
					data:values,
					dataType:'json',
					success:function(data){$('#loading').hide();$('#update_scholar').hide();$('#save_scholar').show(); fetch_scholar();
					reset_scholar();	if(data == 1){success_modal(data);}if(data == 0){fail_modal(data);}}}); 
		}else{$('#loading').hide();}
	});	
	
	
	$('#update_paper_present').hide();
	$('#update_paper_present,#save_paper_present').on('click',function(e){
	$('#loading').show();
		$("#paper_present").validate({
		rules: {
			  paper_present_year:{
					required: true,					
			  }, 
			  paper_present_title:{
					required: true,		
			loginRegex_spec : true					
			  },
				
            },
            messages: {  
				paper_present_year:{
					required: "This field is required.",					
			  }
            },
			errorPlacement : function(error, element) {
				if (element.attr('name') == "paper_present_year") {
					error.appendTo('#error_paper_present_year_btn');
				} else {
					error.insertAfter(element);
				}
			}
		});
		var idClicked = e.target.id; 
		var flag = $('#paper_present').valid(); 
		var buttonvalue;
		if(flag == true){
		tinyMCE.triggerSave();
		var str=$('#paper_present_abstract').val();
		var str1=str.replace("<p>", " ");
		var str2=str1.replace("</p>"," ");
		var res= str2.replace('alt=""', 'alt="image"');
		var abstract_description=res;
		if(idClicked == 'update_paper_present'){ buttonvalue = 'update'} else if( idClicked == 'save_paper_present'){ buttonvalue = 'save' }
		   var values = $("#paper_present").serialize();
			values = values + '&button_update='+buttonvalue;
			//values = values + '&paper_present_abstract='+abstract_description;
			console.log(values);
	 	   $.ajax({type:"POST",
					url:base_url+'report/edit_profile/save_update_paper_preset',
					data:values,
					dataType:'json',
					success:function(data){ 					
					$('#loading').hide(); $('#update_paper_present').hide();$('#save_paper_present').show(); fetch_paper_presentation();success_modal(data); 
					reset_paper_present();}}); 
		}else{$('#loading').hide();}
	});
	
	
	$('#update_text_reference_book').hide();
	$('#update_text_reference_book,#save_text_reference_book').on('click',function(e){
	$('#loading').show();
		$("#text_reference_book").validate({
		rules: {
			book_no:{
				loginRegex_spec : true
			},	
			book_title:{
				loginRegex_spec : true
			},	
			co_author:{
				loginRegex_spec : true
			},	
			published_by:{
				loginRegex_spec : true
			},	
			printed_at:{
				loginRegex_spec : true
			},			
			isbn_no:{
				loginRegex_spec : true
			},
			year_of_publication:{
					required: true,					
			  },			 
			copyright_year:{
					required: false,					
			  },
			chapters:{
			loginRegex_spec : true,
			},
				
            },
            messages: {  
				year_of_publication:{
					required: "This field is required.",					
			  },
			  copyright_year:{
					required: "This field is required.",					
			  }
            },
			errorPlacement : function(error, element) {
				if (element.attr('name') == "year_of_publication") {
					error.appendTo('#error_year_of_publication_btn');
				}				
				else if (element.attr('name') == "copyright_year") {
					error.appendTo('#error_copyright_year_btn');
				} else {
					error.insertAfter(element);
				}
				
				
			}
		});
 		 var idClicked = e.target.id; 
		var flag = $('#text_reference_book').valid(); 
		var buttonvalue;
		if(flag == true){
		tinyMCE.triggerSave();
		var str=$('#about_book').val();
		var str1=str.replace("<p>", " ");
		var str2=str1.replace("</p>"," ");
		var res= str2.replace('alt=""', 'alt="image"');
		var abstract_description=res;
		if(idClicked == 'update_text_reference_book'){ buttonvalue = 'update'} else if( idClicked == 'save_text_reference_book'){ buttonvalue = 'save' }
		   var values = $("#text_reference_book").serialize();
			values = values + '&button_update='+buttonvalue;
			console.log(values);
			//values = values + '&about_book='+abstract_description;
	 	   $.ajax({type:"POST",
					url:base_url+'report/edit_profile/save_update_text_reference_book',
					data:values,
					dataType:'json',
					success:function(data){$('#loading').hide();$('#update_text_reference_book').hide();$('#save_text_reference_book').show(); fetch_text_reference_book();success_modal(data); 
					reset_text_reference_book();}});  		
		}else{ $('#loading').hide();}
	});	
	$('.book_type_name').hide();
	$('#book_type').on('change',function(){
		if($('#book_type').val() == 178){
		$('#book_type_name').attr('required',true);
			$('.book_type_name').show();
		}else{ $('.book_type_name').hide(); }
	});
	
	
	$('#update_training_workshop_conference').hide();
	$('#update_training_workshop_conference,#save_training_workshop_conference').on('click',function(e){
	//$('#loading').show();
		$("#training_workshop_conference").validate({
		rules: {
			program_title:{
				loginRegex_spec : true
			},	
	
			coordinators:{
				loginRegex_spec : true
			},	
			training_venue:{
				loginRegex_spec : false
			},	
			pedagogy:{
				loginRegex_spec : true
			},	
			collaboration:{
				loginRegex_spec : true
			},				
			training_role:{
				loginRegex_spec : true
			},		
			event_organizer:{
				loginRegex_spec : true
			},			
			training_sposored_by:{
				loginRegex_spec : true
			},
			from_date:{
					required: true,					
			  },			 
			copyright_year:{
					required: true,					
			  },
			program_fees:{
					numeric: false,
			},
			duration_hours:{
					numeric: true,
					hour_validation : false
			},	
			duration_minutes:{
					numeric: false,
					hour_validation:true,
			},
				
            },
            messages: {  
				error_from_to_date:{
					required: "This field is required.",					
			  },
			  copyright_year:{
					required: "This field is required.",					
			  }
            },
			errorPlacement : function(error, element) {
				if (element.attr('name') == "from_date" || element.attr('name') == "to_date") {
				$("#error_from_to_date").html(""); error.appendTo('#error_from_to_date');				
			}  else if(element.attr('name') == "duration_hours" || element.attr('name') == "duration_minutes"){
					$("#error_hour_minute").html("");error.appendTo('#error_hour_minute');
			}
			else {
				error.insertAfter(element);
			}
				
				
			}
		});
 		 var idClicked = e.target.id; 
		var flag = $('#training_workshop_conference').valid(); 
		var buttonvalue;
		if(flag == true){
		tinyMCE.triggerSave();
		var str=$('#trarinin_objectives').val();
		var str1=str.replace("<p>", " ");
		var str2=str1.replace("</p>"," ");
		var res= str2.replace('alt=""', 'alt="image"');
		var abstract_description=res;
		if(idClicked == 'update_training_workshop_conference'){ buttonvalue = 'update'} else if( idClicked == 'save_training_workshop_conference'){ buttonvalue = 'save' }
		   var values = $("#training_workshop_conference").serialize();
			values = values + '&button_update='+ buttonvalue;
			//values = values + '&trarinin_objectives='+ abstract_description;
			console.log(values);
	 	   $.ajax({type:"POST",
					url:base_url+'report/edit_profile/save_update_training_workshop_conference',
					data:values,
					dataType:'json',
					success:function(data){$('#loading').hide(); $('#update_training_workshop_conference').hide();$('#save_training_workshop_conference').show();reset_training_workshop_conference(); success_modal(data); fetch_training_workshop_conference();
					}
			});  
async:true			
		}else{$('#loading').hide();}
	});
		
	// To Reset consultancy projects
	function reset_training_workshop_conference(){

		$("#training_workshop_conference").trigger('reset');
		$('#twc_id').val("");
		var validator = $('#training_workshop_conference').validate();
		validator.resetForm();	
		$('#update_training_workshop_conference').hide();$('#save_training_workshop_conference').show();
	}		
	
	
	$('#update_training_workshop_conference_attended').hide();
	$('#update_training_workshop_conference_attended,#save_training_workshop_conference_attended').on('click',function(e){
	//$('#loading').show();
		$("#training_workshop_conference_attended").validate({
		rules: {
			program_title_attended:{
				loginRegex_spec : true
			},	
	
			training_venue_attended:{
				loginRegex_spec : false
			},	
			event_organizer_attended:{
				loginRegex_spec : true,
				required :false
			},			
			training_sposored_by_attended:{
				loginRegex_spec : true
			},
			from_date_attended:{
				required: true,					
			  },			 
			program_fees_attended:{
				numeric: false,
			},
			duration_hours_attended:{
				numeric: true,
				hour_validation : false
			},	
			duration_minutes_attended:{
				numeric: false,
				hour_validation:true,
			},
				
            },
            messages: {  
				error_from_to_date:{
				required: "This field is required.",					
			  },
			copyright_year:{
				required: "This field is required.",					
			  }
            },
			errorPlacement : function(error, element) {
				if (element.attr('name') == "from_date_attended" || element.attr('name') == "to_date_attended") {
				$("#error_from_to_date_attended").html(""); error.appendTo('#error_from_to_date_attended');				
			}  else if(element.attr('name') == "duration_hours_attended" || element.attr('name') == "duration_minutes_attended"){
					$("#error_hour_minute_attended").html("");error.appendTo('#error_hour_minute_attended');
			}
			else {
				error.insertAfter(element);
			}
				
				
			}
		});
 		var idClicked = e.target.id; 
		var flag = $('#training_workshop_conference_attended').valid(); 
		var buttonvalue;
		if(flag == true){
		tinyMCE.triggerSave();
		var str=$('#trarinin_objectives_attended').val();
		var str1=str.replace("<p>", " ");
		var str2=str1.replace("</p>"," ");
		var res= str2.replace('alt=""', 'alt="image"');
		var abstract_description=res; 
		if(idClicked == 'update_training_workshop_conference_attended'){ buttonvalue = 'update'} else if( idClicked == 'save_training_workshop_conference_attended'){ buttonvalue = 'save' }
		   var values = $("#training_workshop_conference_attended").serialize();  
		console.log(values);		   
		   //var values = $("#training_workshop_conference_attended").not(".question_textarea").serialize();
		   //values = values + '&trarinin_objectives='+ abstract_description;
			   values = values + '&button_update='+ buttonvalue;
	 	   $.ajax({type:"POST",
					url:base_url+'report/edit_profile/save_update_training_workshop_conference_attended',
					data:values,
					dataType:'json',
					success:function(data){$('#loading').hide(); $('#update_training_workshop_conference_attended').hide();$('#save_training_workshop_conference_attended').show();reset_training_workshop_conference_attended(); success_modal(data); fetch_training_workshop_conference_attended();
					}
			});  
			async:true			
		}else{$('#loading').hide();}
	});
	$('#attended_specify_div').hide();
	
	$('#training_role_attended').on('change',function(){
			var role  = $('#training_role_attended').val();
		if(role == 186){$('#training_role_attended_specify').attr('required',true);$('#attended_specify_div').show();$('#training_role_attended_specify').val('');}else{$('#attended_specify_div').hide();
		$('#training_role_attended_specify').val($("#training_role_attended option:selected").text());
		}
	});
	// To Reset consultancy projects
	function reset_training_workshop_conference_attended(){
		$('#attended_specify_div').hide();
		$('#training_role_attended_specify').val($("#training_role_attended option:selected").text());
		$("#training_workshop_conference_attended").trigger('reset');
		$('#twc_id').val("");
		var validator = $('#training_workshop_conference_attended').validate();
		validator.resetForm();	
		$('#update_training_workshop_conference_attended').hide();$('#save_training_workshop_conference_attended').show();
	}	
	
	// To Reset consultancy projects
	function reset_text_reference_book(){
		$("#text_reference_book").trigger('reset');
		$('#text_ref_id').val("");
		var validator = $('#text_reference_book').validate();
		validator.resetForm();	
		$('.book_type_name').hide();
		$('#update_text_reference_book').hide();$('#save_text_reference_book').show();
	}

	// To Reset consultancy projects
	function reset_scholar(){
		$("#sholarship").trigger('reset');
		$('#scholar_id').val("");
		var validator = $('#sholarship').validate();
		validator.resetForm();	
		$('#update_scholar').hide();$('#save_scholar').show();
	}	// To Reset consultancy projects
	
	
	function reset_patent(){
		$("#patent").trigger('reset');
		$('#patent_id').val("");
		var validator = $('#patent').validate();
		validator.resetForm();	
		$('#update_patent').hide();$('#save_patent').show();
	}		
	
	function reset_paper_present(){
		$("#paper_present").trigger('reset');
		$('#paper_id').val("");
		var validator = $('#paper_present').validate();
		validator.resetForm();	
		$('#update_paper_present').hide();$('#save_paper_present').show();
	}	

	$('#update_conference_seminar_book').hide();
	$('#update_conference_seminar_book,#save_conference_seminar_book').on('click',function(e){
		$("#training_workshop_conference").validate({
		rules: {
			book_no:{
				loginRegex_spec : true
			},	
			book_title:{
				loginRegex_spec : true
			},	
			co_author:{
				loginRegex_spec : true
			},	
			published_by:{
				loginRegex_spec : true
			},	
			printed_at:{
				loginRegex_spec : true
			},			
			isbn_no:{
				loginRegex_spec : true
			},
			year_of_publication:{
					required: true,					
			},			 
			copyright_year:{
					required: true,					
			}
				
            },
            messages: {  
				year_of_publication:{
					required: "This field is required.",					
			  },
			  copyright_year:{
					required: "This field is required.",					
			  }
            },
			errorPlacement : function(error, element) {
				if (element.attr('name') == "year_of_publication") {
					error.appendTo('#error_year_of_publication_btn');
				}				
				else if (element.attr('name') == "copyright_year") {
					error.appendTo('#error_copyright_year_btn');
				} else {
					error.insertAfter(element);
				}
				
				
			}
		});
 		 var idClicked = e.target.id; 
		var flag = $('#training_workshop_conference').valid(); 
		var buttonvalue;
		if(flag == true){
		tinyMCE.triggerSave();
		//var str=$('#about_book').val();
		var str = (tinyMCE.get('trarinin_objectives').getContent());
		var str1=str.replace("<p>", " ");
		var str2=str1.replace("</p>"," ");
		var res= str2.replace('alt=""', 'alt="image"');
		var abstract_description=res;
		if(idClicked == 'update_text_reference_book'){ buttonvalue = 'update'} else if( idClicked == 'save_text_reference_book'){ buttonvalue = 'save' }
		   var values = $("#training_workshop_conference").serialize();
			values = values + '&button_update='+buttonvalue;
			values = values + '&about_book='+abstract_description;
	 	   $.ajax({type:"POST",
					url:base_url+'report/edit_profile/save_update_text_reference_book',
					data:values,
					dataType: "json",
					success:function(data){$('#update_text_reference_book').hide();$('#save_text_reference_book').show(); fetch_text_reference_book();
					reset_text_reference_book();}
		   });  		
		}
	});



	$('#update_save_user_designations').hide();
	$('#update_save_user_designations,#save_save_user_designations').on('click',function(e){
		$("#save_user_designations").validate({
		rules: {
			
			designation_list:{
					selectcheck:true,					
			  },			 
			designation_date:{
					required: true,					
			  },
				
            },
            messages: {  
				designation_list:{
					required: "This field is required.",					
			  },
			  designation_date:{
					required: "This field is required.",					
			  }
            },
			errorPlacement : function(error, element) {
				if (element.attr('name') == "designation_date") {
					error.appendTo('#error_designation_date');
				} else {
					error.insertAfter(element);
				}
				
				
			}
		});
 		 var idClicked = e.target.id;
		var flag = $('#save_user_designations').valid(); 
		var buttonvalue;
		if(flag == true){
		if(idClicked == 'update_save_user_designations'){ buttonvalue = 'update'} else if( idClicked == 'save_save_user_designations'){ buttonvalue = 'save' }
		   var values = $("#save_user_designations").serialize();
		   values = values + '&button_update='+buttonvalue;
	 	   $.ajax({type:"POST",
					url:base_url+'report/edit_profile/save_update_user_designation',
					data:values,
					dataType:'json',
					success:function(data){ 
					if(data == "data_exist"){fail_modal(data);}else{success_modal(data);}
					$('#update_save_user_designations').hide();$('#save_save_user_designations').show(); 					
					fetch_user_designation_list();
					reset_save_user_designations();
					}});  		
		}
	});
	
	function reset_save_user_designations(){
	$("#save_user_designations").trigger('reset');
	$('#user_usd_id').val('');
		var validator = $('#save_user_designations').validate();
		validator.resetForm();	
	$('#update_save_user_designations').hide();$('#save_save_user_designations').show(); 	
	}
	
	
	$('#tab6').on('click','.delete_consultant_projects',function(){
		$('#c_id').val($(this).attr('id'));
		$('#delete_confirm_consult').modal('show');				
	});
	
	$('#delete_consult').on('click',function(){
	$('#loading').show();
	var c_id = $('#c_id').val();
	 var post_data ={'c_id':c_id}
		   $.ajax({type:"POST",
					url:base_url+'report/edit_profile/delete_consult_project',
					data:post_data,					
					success:function(data){ 
					$('#loading').hide();
					if(data == 1){ }success_modal_delete(data);
					$('#update_consult_project').hide();$('#save_consult_project').show();fetch_consultant_project_data(data);
					reset_spo_project();}}); 
	});	
	
	$('#tab12').on('click','.delete_text_ref_book',function(){
		$('#text_ref_id').val($(this).attr('id'));
		$('#delete_confirm_text_reff_book').modal('show');				
	});
	
	$('#delete_text_ref').on('click',function(){
	$('#loading').show();
	var text_ref_id = $('#text_ref_id').val();
	 var post_data ={'text_ref_id':text_ref_id}
		   $.ajax({type:"POST",
					url:base_url+'report/edit_profile/delete_text_ref',
					data:post_data,					
					success:function(data){ 
					$('#loading').hide();
					if(data == 1){ }success_modal_delete();
					$('#update_consult_project').hide();$('#save_consult_project').show();fetch_text_reference_book(data);
					reset_spo_project();}}); 
	});	
	
	$('#tab13').on('click','.delete_training_workshop_conference',function(){
		$('#twc_id').val($(this).attr('id'));
		$('#ttr').val($(this).attr('data-ttr'));
		$('#delete_confirm_training_conferrence').modal('show');				
	});

	$('#tab14').on('click','.delete_training_workshop_conference_attended',function(){
		$('#twca_id').val($(this).attr('id'));
		$('#delete_confirm_training_conferrence_attended').modal('show');				
	});
	
	$('#delete_traning').on('click',function(){
	$('#loading').show();
	var twc_id = $('#twc_id').val();
	var ttr = $('#ttr').val();
	 var post_data ={'twc_id':twc_id , 'ttr':ttr}
		   $.ajax({type:"POST",
					url:base_url+'report/edit_profile/delete_traning',
					data:post_data,					
					success:function(data){ 
					$('#loading').hide();
					if(data == 1){ }success_modal_delete();
					$('#update_training_workshop_conference').hide();$('#save_training_workshop_conference').show();fetch_training_workshop_conference(data);
					reset_spo_project();}}); 
	});		
	
	$('#tab15').on('click','.delete_research_projects',function(){
		$('#research_id').val($(this).attr('id'));
		$('#delete_research_projects').modal('show');				
	});
	
	$('#delete_research_projects_data').on('click',function(){
	$('#loading').show();
	var research_id = $('#research_id').val();
	 var post_data ={'research_id':research_id}
		   $.ajax({type:"POST",
					url:base_url+'report/edit_profile/delete_research_proj',
					data:post_data,					
					success:function(data){ 
					$('#loading').hide();
					if(data == 1){ }success_modal_delete();
					$('#update_research_project').hide();$('#save_research_project').show();fetch_research_pojects();
					reset_spo_project();}}); 
	});	
	
	$('#delete_traning_attended').on('click',function(){
	$('#loading').show();
	var twca_id = $('#twca_id').val();
	 var post_data ={'twca_id':twca_id}
		   $.ajax({type:"POST",
					url:base_url+'report/edit_profile/delete_traning_attended',
					data:post_data,					
					success:function(data){ 
					$('#loading').hide();
					if(data == 1){ }success_modal_delete();
					$('#update_training_workshop_conference_attended').hide();$('#save_training_workshop_conference_attended').show();fetch_training_workshop_conference_attended(data);
					reset_spo_project_attended();}}); 
	});

	$('#jourrnal_update').hide();
	/**my_research_paper and Development Tab validation , data save and updation**/
	$('#jourrnal_update,#jourrnal_save').on('click',function(e){
	$('#loading').show();
  	$("#journal_publication").validate({
            rules: {
              title_res_jourrnal: {
                   required: true,	
				   loginRegex_spec : true
                },
				author_jourrnal:{
                   required: false,	
				   loginRegex_spec : true					
				},
				publisher_jourrnal:{
					required: false,	
				   loginRegex_spec : true
				},
				title_res: {
					required : false,
					loginRegex_spec : true
				},
				pages_jourrnal: {
					numeric : false,
					pages_valid :true,
				},
				author: {
					required : false,
					loginRegex_spec : true
				},
				contribution_res_guid_jourrnal: {
					required : false,
					loginRegex_spec : true
				},			
				index_terms_jourrnal: {
					required : false,
					loginRegex_spec : true
				},	
				publish_online_jourrnal: {
					required : false,
					loginRegex_spec : false,
					url_valid : true
				},
				pages_research: {
					numeric:false,
					pages_valid:true
				},
			  dp4:{
					required: true,					
			  }
				
            },
            messages: {
                publica: {
                    required: "This field is required.",
                },
				dp4:{
					required: "This field is required.",					
			  }
            },
			errorPlacement : function(error, element) {
				if (element.attr('name') == "dp4") {
					error.appendTo('#start_error_placeholder_dp4');
				} else if (element.attr('name') == "dp4_end") {
				error.appendTo('#end_error_placeholder_dp4');
				}else {
					error.insertAfter(element);
				}
			}

        }); 
		 var idClicked = e.target.id; 
		 var idClicked = e.target.id; 
		var validation_flag = $('#journal_publication').valid();
 	 	if(validation_flag == true)
		{ 
		
		tinyMCE.triggerSave();
		var str=$('#abstract_data_jourrnal').val();
		var str1=str.replace("<p>", " ");
		var str2=str1.replace("</p>"," ");
		var res= str2.replace('alt=""', 'alt="image"');
		var abstract_description=res;

		if(idClicked == 'jourrnal_update'){ buttonvalue = 'update'} else if( idClicked == 'jourrnal_save'){ buttonvalue = 'save' }
		   var values = $("#journal_publication").serialize();
			values = values + '&button_update='+buttonvalue;
			values = values + '&about_book='+abstract_description;
			console.log(values);
			$.ajax({type:"POST",
					url:base_url+'report/edit_profile/save_update_journal_publication',
					data:values,
					dataType:'json',
					success:function(data){$('#loading').hide();$('#my_training_update').val('0');fetch_journal();success_modal(data);$('#jourrnal_save').show();$('#jourrnal_update').hide();reset_jourrnal();}
			}); 
		} else{$('#loading').hide();}
	});

	// Function to reset my research details form
	function reset_jourrnal(){
		$("#journal_publication").trigger('reset');
		$('#journal_id').val("");
		var validator = $('#journal_publication').validate();
		validator.resetForm();		
		$('#jourrnal_save').show();$('#jourrnal_update').hide();
	}	
	
	function reset_training(){
		$("#my_research_paper").trigger('reset');$('#my_training_update').val(0);	
		var validator = $('#my_research_paper').validate();
		validator.resetForm();		
		$('#my_tr_button').show();$('#my_tr_button_update').hide();
	}
	
	$('#my_qualification_save').on('click',function(){	
		var save = "-1";
		var my_qua_id = $('#my_qua_id').val();
		save_my_qualification_function(save,my_qua_id);
	});
	
	$('#my_qualification_update').on('click',function(){
		var my_qua_id = $('#my_qua_id').val();
		var update = "-2";
		save_my_qualification_function(update,my_qua_id);
	});
	
	/** Function to validate , save and update my_qualification data**/
	function save_my_qualification_function(data,my_qua_id){
	$('#loading').show();
		 $("#my_qualification").validate({
            rules: {

				degree:{
				required: true,
					selectcheck:true
				},
				dept_id:{
				required: true,
				loginRegex_spec : true
					//selectcheck:true
				},            
                my_university: {
                    required: true,
					loginRegex_spec : true
                },
				start_date:{
					required:true,					
				},
            },
            messages: {
				start_date:{
					required : "This field is required.",
				},
                select: "Select This Field."
            },
			errorPlacement : function(error, element) {
			if (element.attr('name') == "start_date") {
				error.appendTo('#error_placeholder_start_date');
			} else {
				error.insertAfter(element);
			}
		}
        });  
		var validation_flag = $('#my_qualification').valid();
		if(validation_flag == true){
			var degree = $('#degree').val();
			var university = $('#my_university').val();
			var degree_name = $("#degree option:selected").text();
			var my_qua_id = $('#my_qua_id').val();
			var yog = $('#start_date').val();	
			var user_id = $('#user_id').val();	
			var dept_id = $('#dept_id').val();
			var post_data={'degree':degree,'university':university,'yog':yog,'save_update':data,'degree_name':degree_name,'my_qua_id':my_qua_id,'user_id':user_id ,'dept_id':dept_id}
			
			$.ajax({type:"POST",
					url:base_url+'report/edit_profile/save_my_qualification',
					data:post_data,
					dataType:'json',
					success:function(msg){
					$('#my_qualification_update').hide();
					$('#my_qualification_save').show();
					if(msg == '-1'){fail_modal(msg);}
					if(msg == '1'){success_modal(msg);}
					if(msg == '0'){fail_modal(msg);}
					$('#loading').hide();
					fetch_my_qualification();
					reset_my_qualification();
					}
					//[populate_training_table,success_modal]
			}); 
		
		}else{$('#loading').hide();}
	}
	
	fetch_my_qualification();
	// Function to reset qualification form
	function reset_my_qualification(){
		$("#my_qualification").trigger('reset');
		var validator = $('#my_qualification').validate();
		validator.resetForm();	
		$('#my_qualification_save').show();$('#my_qualification_update').hide();
	}
	
	// To Fetch qualification Data
	function fetch_my_qualification(){
		var user_id = $('#user_id').val();
		var post_data={'user_id':user_id}
			
			$.ajax({type:"POST",
					url:base_url+'report/edit_profile/fetch_my_qualification',
					data:post_data,
					dataType:'json',
					success:[populate_fetch_my_qualification]
			}); 
	}	
	/**Function to pass id to  delete modal of the achievement */
 		$('#example1').on('click', '.delete_journal', function(e){
			var id = $(this).attr('id');		
			$('#myModal4').data('id', id).modal('show');
		});	 	
		
		$('#example3').on('click', '.delete_qp', function(e){
			var id = $(this).attr('id');		
			$('#myModal4').data('id', id).modal('show');
		});
		$('#example2').on('click','.delete_workload',function(e){
			var id=$(this).attr('id');
			$('#myModal6').data('id', id).modal('show');
		});
		
		
		
		
		
		$('#my_teaching_update').hide();
		
		// Edit form of user teaching details
		$('#example2').on('click','.edit_my_teaching',function(e){
	
			$('html,body').animate({ scrollTop: $(".tab2").offset().top},'slow');
			edit_notify();			
			$('#my_wid').val($(this).attr('data-my_wid'));
			$('#dept').val($(this).attr('data-dept')); 			
			$('#dept').trigger('change'); 
			var val = $('#workload_switch').val(); 
			if(val == 2){			
			//$('#program_type_id').val($(this).attr('data-pgm_type_id'));
			$('#program_type_id').val($(this).attr('data-pgm_type_id')).attr("selected", "selected");
			fetch_pro();
			$('#program_id').val($(this).attr('data-pgm_id'));
			$('#pgm_category').val($(this).attr('data-pgm_cate'));
			$('#pgm_load_data').val($(this).attr('data-pgm_cate_name'));
			$('#year_sem').val($(this).attr('data-year')); 
			$('#work_load').val($(this).attr('data-workload'));
			$('#user_id').val($(this).attr('data-user_id'));
			$('#accademic').val($(this).attr('data-accademic_year'));
			$('#my_teaching').hide();$('#my_teaching_update').show();
			var type_of_work =$(this).attr('data-type_of_work');
			var work_type =$(this).attr('data-work_type'); 
			$('#type_of_work').val(work_type);	
			if(work_type == 177){  $('#type_of_work_others').show(); $('#type_of_work_others').val(type_of_work);  }
			else{$('#type_of_work_others').hide();}
			
			$('#my_teaching_edit_val').val('-1');
			}
		});
	/** Function to pass id to delete modal of  the training  **/
/* 		$('#example1').on('click','.delete_training',function(e){
			var id=$(this).attr('id');
			$('#myModal5').data('id', id).modal('show');
		}) */

	/**To populate DataTable for tab my_acheivement**/
		function populate_training_table(msg) {	
		$('#example1').dataTable().fnDestroy();
		$('#example1').dataTable({	
					"scrollX": true,
					"sSort": true,
					"sPaginate": true,
					"scrollY": true,
					"aoColumns": [
						{"sTitle": "Sl No.", "mData":"sl_no","sType":"numeric"},
						{"sTitle": "Project Title", "mData": "title"},
						{"sTitle": "Co-Author","mData":"authors"}, 			
						{"sTitle": "Volume No", "mData": "vol_no","sClass": "alignright"},
						{"sTitle": "Page(s)", "mData": "pages","sClass": "alignright"},	
						{"sTitle": "Citation Index", "mData": "citation_count","sClass": "alignright"},
						{"sTitle": "h-index", "mData": "hindex","sClass": "alignright"},
						{"sTitle": "i10_index", "mData": "i10_index","sClass": "alignright"},												
						{"sTitle": "ISSN", "mData": "issn"},
						{"sTitle": "DOI", "mData": "doi"},
						{"sTitle": "Sponsored by", "mData": "sponsored_by"},
						{"sTitle": "Publisher", "mData": "publisher"},						
						{"sTitle": "Upload","mData":"view"},
						{"sTitle": "Edit", "mData": "edit"},
						{"sTitle": "Delete", "mData": "Delete"},						
						
					], "aaData": msg,
					
					"sPaginationType": "bootstrap",
					
					}); 	
		}  
			var user_id=$('#user_id').val();
			post_data =  {'user_id':user_id}
							$.ajax({type: "POST",
									url: base_url+'report/edit_profile/fetch_my_teching_workload',
									data: post_data,
									dataType: 'json',
								   success: populate_teaching_table
						 });
		

		/**To populate DataTable for tab teaching_workload**/
		function populate_teaching_table(msg) {	
	
			$('#example2').dataTable().fnDestroy();
			$('#example2').dataTable(
					{	"sSort": true,
						"sPaginate": true,
						
						"aoColumns": [
							{"sTitle": "Sl No.", "mData":"sl_no","sType":"numeric"},
							{"sTitle": "Department", "mData":"dept"},
							{"sTitle": "Program Type", "mData": "prgm_type"},
							{"sTitle": "Program", "mData": "program"},
							{"sTitle": "Program Category", "mData": "program_category"},
							{"sTitle": "Work Type", "mData": "type_of_work"},
							{"sTitle": "Workload Distribution<br/>(in years)", "mData": "year"},							
							{"sTitle": "Academic Year", "mData": "accademic_year","sClass": "alignright"},
							{"sTitle": "Workload(%)", "mData": "workload", "sClass": "alignright"},
							{"sTitle": "Edit", "mData": "Edit"},
							{"sTitle": "Delete", "mData": "Delete"},
						], "aaData": msg,
						
						"sPaginationType": "bootstrap",
						
						}); 	
						//{"sTitle": "Type of work", "mData": "type_of_work"},
			}	
		/** Function to populate user qualification details**/
 		function populate_fetch_my_qualification(msg){
			$('#my_qualification_tbl').dataTable().fnDestroy();
			$('#my_qualification_tbl').dataTable(
						{
						"sSort": true,
						"sPaginate": true,
						
						"aoColumns": [
							{"sTitle": "Sl No.", "mData":"sl_no","sType":"numeric"},
							{"sTitle": "Qualification", "mData": "qualification"},
							{"sTitle": "University", "mData": "university"},
							{"sTitle": "Year of Graduation", "mData": "yog"},
							{"sTitle": "Upload","mData":"upload"},
							{"sTitle": "Edit", "mData": "edit"},
							{"sTitle": "Delete", "mData": "Delete"},
						], "aaData": msg,
						
						"sPaginationType": "bootstrap",
						});
		} 
		
		/**To Populate DataTable for tab my_research_paper and development**/
 		function populate_table(msg){
		$('#example3').dataTable().fnDestroy();
		$('#example3').dataTable({	
					"scrollX": true,
					"sSort": true,
					"sPaginate": true,
					"scrollY": true,
					"aoColumns": [
						
					//	{"sTitle": "Sl No.", "mData":"sl_no","sType":"numeric"},
						{"sTitle": "Publication Level", "mData":"publication_level"},						
						{"sTitle": "Title of Paper", "mData": "title"},
						{"sTitle": "Co-Author","mData":"authors"}, 			
						{"sTitle": "Publication Title", "mData": "publication_title" , "sClass": "alignright"},	
						{"sTitle": "Publication Date", "mData": "publication_date"},
						{"sTitle": "Publisher", "mData": "publisher"},															
						{"sTitle": "Volume No", "mData": "vol_no","sClass": "alignright"},											
						{"sTitle": "ISSN / ISBN", "mData": "issn"},
						{"sTitle": "Citation Index", "mData": "citation_count","sClass": "alignright"},
						//{"sTitle": "h-index", "mData": "hindex","sClass": "alignright"},
						//{"sTitle": "i10_index", "mData": "i10_index","sClass": "alignright"},
						//{"sTitle": "DOI", "mData": "doi"},
						//{"sTitle": "Sponsored by", "mData": "sponsored_by"},												
						{"sTitle": "Upload","mData":"view"},
						{"sTitle": "Edit", "mData": "edit"},
						{"sTitle": "Delete", "mData": "Delete"},						
						
					], "aaData": msg,
					  "order": [[ 1, 'asc' ]],
					"sPaginationType": "bootstrap",
					
					});
			    $('#example3').dataTable().fnDestroy();
				$('#example3').dataTable({
					"sPaginationType": "bootstrap",
					"fnDrawCallback": function () {
					$('.group').parent().css({'background-color': '#C7C5C5'});
						
				}

				}).rowGrouping({iGroupingColumnIndex: 0,
				bHideGroupingColumn: true});
					
		} 

$(document).ready(function(){
    $(this).tooltip();
});	
	/**To Populate DataTable for tab my_innovation details**/
 		function populate_my_innovation(msg){
		$('#example5').dataTable().fnDestroy();
		$('#example5').dataTable(
				{	"sSort": true,
					"sPaginate": true,
					
					"aoColumns": [
						{"sTitle": "Sl No.", "mData":"sl_no"},
						{"sTitle": "Title", "mData": "title"},
						{"sTitle": "Link","mData":"link"},
						{"sTitle": "Description","mData":"desc"}, 			
						{"sTitle": "Edit", "mData": "Edit"},
						{"sTitle": "Delete", "mData": "Delete"},
						
					], "aaData": msg,
					
					"sPaginationType": "bootstrap",
					
					});
		} 
		
		/** To populate DataTable for consultancy projects**/
		function populate_consultant_project(msg){
		$('#example6').dataTable().fnDestroy();
		$('#example6').dataTable(
				{	"sSort": true,
					"sPaginate": true,
					"scrollX": true,
					"aoColumns": [
						{"sTitle": "Sl No.", "mData":"sl_no","sType": 'numeric'},
						{"sTitle": "Project Code ", "mData": "project_code"},
						{"sTitle": "Project Title ","mData":"project_title"},
						{"sTitle": "Client","mData":"client"},
						{"sTitle": "Your Role","mData":"consultant"},
						//{"sTitle": "Co-consultant(s)", "mData": "co_consultant"},
						{"sTitle": "Commencement Date","mData":"year","sClass": "alignright"},
						//{"sTitle": "Abstract","mData":"abstract"},
						{"sTitle": "Status","mData":"status"}, 			
						{"sTitle": "Upload", "mData": "upload"},
						{"sTitle": "Edit", "mData": "edit"},
						{"sTitle": "Delete", "mData": "delete"},
						
					], "aaData": msg,
					
					"sPaginationType": "bootstrap",
					
					});
		}
		
		
		function populate_sponsored_project(msg){
		$('#example7').dataTable().fnDestroy();
		$('#example7').dataTable(
				{	"sSort": true,
					"sPaginate": true,
					"scrollX": true,
					"aoColumns": [
						{"sTitle": "Sl No.", "mData":"sl_no","sType": 'numeric'},
						//{"sTitle": "Project Code ", "mData": "project_code"},
						{"sTitle": "Project Title ","mData":"project_title"},
						{"sTitle": "Sponsoring Organization","mData":"spo_organization"},
						{"sTitle": "Principal Investigator","mData":"investigator"},
						{"sTitle": "Co-Investigator(s)", "mData": "co_investigator"},
						{"sTitle": "Duration","mData":"duration"},						
						{"sTitle": "Status","mData":"status"}, 			
						{"sTitle": "Upload", "mData": "upload"},
						{"sTitle": "Edit", "mData": "edit"},
						{"sTitle": "Delete", "mData": "delete"},
						
					], "aaData": msg,
					
					"sPaginationType": "bootstrap",
					
					});
		}		
		
		function populate_award_honour_data(msg){
		$('#example8').dataTable().fnDestroy();
		$('#example8').dataTable(
				{	"sSort": true,
					"sPaginate": true,
					"scrollX": true,
					"aoColumns": [
						{"sTitle": "Sl No.", "mData":"sl_no","sType":"numeric"},
						{"sTitle": "Awarded Name", "mData": "award_name"},					
						{"sTitle": "Awarded for","mData":"award_for"},
						{"sTitle": "Awarding Organization","mData":"spo_oganization"},
						{"sTitle": "Awarded Year","mData":"year"},			
						{"sTitle": "Award Details","mData":"remarks"},							
						{"sTitle": "Upload", "mData": "upload"},
						{"sTitle": "Edit", "mData": "edit"},
						{"sTitle": "Delete", "mData": "delete"},
						
					], "aaData": msg,
					
					"sPaginationType": "bootstrap",
					
					});
		}		
		
		function populate_patent(msg){
		$('#example9').dataTable().fnDestroy();
		$('#example9').dataTable(
				{	"sSort": true,
					"sPaginate": true,
					"scrollX": true,
					"aoColumns": [
						{"sTitle": "Sl No.", "mData":"sl_no","sType":"numeric"},
						{"sTitle": "Title", "mData": "patent_title"},					
						{"sTitle": "Inventor(s)","mData":"inventors"},
						{"sTitle": "Patent No.","mData":"patent_no"},
						{"sTitle": "Year","mData":"year"},			
						{"sTitle": "Status","mData":"status"},							
						{"sTitle": "Upload", "mData": "upload"},
						{"sTitle": "Edit", "mData": "edit"},
						{"sTitle": "Delete", "mData": "delete"},
						
					], "aaData": msg,
					
					"sPaginationType": "bootstrap",
					
					});
		}		
		
		
		function populate_scholar(msg){

		$('#example10').dataTable().fnDestroy();
		var table = $('#example10').dataTable(
				{	"sSort": true,
					"sPaginate": true,
					"scrollX": true,
					"aoColumns": [
						{"sTitle": "Sl No.", "mData":"sl_no"},
						{"sTitle": "Fellowship / Scholarship for", "mData": "fellow_scholar_for"},					
						{"sTitle": "Awarded by","mData":"awarded_by"},
						{"sTitle": "Date","mData":"year"},			
						{"sTitle": "Type","mData":"type"},		
					//	{"sTitle": "Abstract", "mData": "abstract"},						
						{"sTitle": "Upload", "mData": "upload"},
						{"sTitle": "Edit", "mData": "edit"},
						{"sTitle": "Delete", "mData": "delete"},
						
					], "aaData": msg,
					
					"sPaginationType": "bootstrap",
					
					});
		}		
		
		function populate_fetch_paper_presentation(msg){
		$('#example11').dataTable().fnDestroy();
		$('#example11').dataTable(
				{	"sSort": true,
					"sPaginate": true,
					//"scrollX": true,
					"aoColumns": [
					//	{"sTitle": "Sl No.", "mData":"sl_no"},
						{"sTitle": "Title", "mData": "title"},					
						{"sTitle": "Venue","mData":"venue"},
						{"sTitle": "Date","mData":"year"},			
					//	{"sTitle": "Presentation Level", "mData": "presentation_level"},
						{"sTitle": "Presentation Type", "mData": "presentation_type"},
						{"sTitle": "Presentation Role", "mData": "presentation_role"},						
						{"sTitle": "Upload", "mData": "upload"},
						{"sTitle": "Edit", "mData": "edit"},
						{"sTitle": "Delete", "mData": "delete"},
						
					], "aaData": msg,
					
					"sPaginationType": "bootstrap",
					
					});
					
				 $('#example11').dataTable().fnDestroy();
					$('#example11').dataTable({
					"sPaginationType": "bootstrap",
					"fnDrawCallback": function () {
					$('.group').parent().css({'background-color': '#C7C5C5'});
				}
				}).rowGrouping({iGroupingColumnIndex: 4	,
				bHideGroupingColumn: true});
		}		
		
		function populate_text_reference_book(msg){
		$('#example12').dataTable().fnDestroy();
		$('#example12').dataTable(
				{	"sSort": true,
					"sPaginate": true,
					//"scrollX": true,
					"aoColumns": [
						//{"sTitle": "Sl No.", "mData":"sl_no"},
						{"sTitle": "Sl No.", "mData":"book_type"},						
						{"sTitle": "Book Title","mData":"book_title"},															
						{"sTitle": "Co - Author(s)","mData":"co_author"},			
						{"sTitle": "ISBN No.", "mData": "isbn_no"},
						{"sTitle": "Language(s)", "mData": "language"},
						{"sTitle": "Publisher Name", "mData": "published_by"},	
						{"sTitle": "Year of publication", "mData": "year_of_publication"},
					//	{"sTitle": "About the book", "mData": "about_book"},						
						{"sTitle": "Upload", "mData": "upload"},
						{"sTitle": "Edit", "mData": "edit"},
						{"sTitle": "Delete", "mData": "delete"},
						
					], "aaData": msg,
					
					"sPaginationType": "bootstrap",
					
					});
					
			$('#example12').dataTable().fnDestroy();
		   $('#example12').dataTable({
		   "sPaginationType": "bootstrap",
		   "fnDrawCallback": function () {
					$('.group').parent().css({'background-color': '#C7C5C5'});
				}
			}).rowGrouping({iGroupingColumnIndex: 0,
				bHideGroupingColumn: true});
		}		
		
		function populate_fetch_training_workshop_conference(msg){

		$('#example13').dataTable().fnDestroy();
		$('#example13').dataTable(
				{	"sSort": true,
					"sPaginate": true,
					//"scrollX": true,
					"sPaginate": true,
					"aoColumns": [					
						{"sTitle": "Program Title", "mData": "program_title"},
						{"sTitle": "Level", "mData": "level"},					
						{"sTitle": "Event Organizer","mData":"event_organizer"},			
						{"sTitle": "Collaboration", "mData": "collaboration"},
						{"sTitle": "Date", "mData": "from_date"},
					    //{"sTitle": "To date", "mData": "to_date"},
						{"sTitle": "Sponsored by", "mData": "sponsored_by"},
						{"sTitle": "Your Role", "mData": "role_fetched"},
						{"sTitle": "Upload", "mData": "upload"},
						{"sTitle": "Edit", "mData": "edit"},
						{"sTitle": "Delete", "mData": "delete"},
						
					], "aaData": msg,
					
					"sPaginationType": "bootstrap",
					
			});

		   $('#example13').dataTable().fnDestroy();
		   $('#example13').dataTable({
		   "sPaginationType": "bootstrap",
		   "fnDrawCallback": function () {
					$('.group').parent().css({'background-color': '#C7C5C5'});
				}
			}).rowGrouping({iGroupingColumnIndex: 6,
				bHideGroupingColumn: true});
		}		
		
		function populate_fetch_training_workshop_conference_attended(msg){

		$('#example14').dataTable().fnDestroy();
		$('#example14').dataTable(
				{	"sSort": true,
					"sPaginate": true,
					//"scrollX": true,
					"sPaginate": true,
					"aoColumns": [					
						{"sTitle": "training_type", "mData": "training_type"},
						{"sTitle": "Program Title", "mData": "program_title"},
						{"sTitle": "Level", "mData": "level"},					
						{"sTitle": "Event Organizer","mData":"event_organizer"},			
						{"sTitle": "Date", "mData": "from_date"},
						//{"sTitle": "To date", "mData": "to_date"},
						{"sTitle": "Your Role", "mData": "role_fetched"},
						{"sTitle": "Upload", "mData": "upload"},
						{"sTitle": "Edit", "mData": "edit"},
						{"sTitle": "Delete", "mData": "delete"},
						
					], "aaData": msg,
					
					"sPaginationType": "bootstrap",
					
			});

 		   $('#example14').dataTable().fnDestroy();
		   $('#example14').dataTable({
		   "sPaginationType": "bootstrap",
		   "fnDrawCallback": function () {
					$('.group').parent().css({'background-color': '#C7C5C5'});
				}
			}).rowGrouping({iGroupingColumnIndex: 0,
				bHideGroupingColumn: true}); 
		}		
		
		function populate_research_projects(msg){

		$('#example15').dataTable().fnDestroy();
		$('#example15').dataTable(
				{	"sSort": true,
					"sPaginate": true,
					//"scrollX": true,
					"sPaginate": true,
					"aoColumns": [					
						{"sTitle": "status", "mData": "status"},
						//{"sTitle": "Sl.No ", "mData": "sl_no"},
						{"sTitle": "Program Title ", "mData": "program_title"},
						{"sTitle": "Role", "mData": "role_fetched"},					
						{"sTitle": "Sanctioned Date","mData":"sanctioned_date"},			
						{"sTitle": "Duration", "mData": "duration"},
						{"sTitle": "Collaboration", "mData": "collabration"},
						{"sTitle": "Upload", "mData": "upload"},
						{"sTitle": "Edit", "mData": "edit"},
						{"sTitle": "Delete", "mData": "delete"},
						
					], "aaData": msg,
					
					"sPaginationType": "bootstrap",
					
			});

 		   $('#example15').dataTable().fnDestroy();
		   $('#example15').dataTable({
		   "sPaginationType": "bootstrap",
		   "fnDrawCallback": function () {
					$('.group').parent().css({'background-color': '#C7C5C5'});
				}
			}).rowGrouping({iGroupingColumnIndex: 0,
				bHideGroupingColumn: true});  
		}
		
			
		function populate_table_user_designation_table(msg){
		$('#example_designation').dataTable().fnDestroy();
		$('#example_designation').dataTable(
				{	"sSort": true,
					"sPaginate": true,
					"scrollX": true,
					"aoColumns": [
						{"sTitle": "Sl No.", "mData":"sl_no" ,"sType" : "numeric"},
						{"sTitle": "Department", "mData": "department"},					
						{"sTitle": "Designation","mData":"designation"},
						{"sTitle": "Year","mData":"year"},
						{"sTitle": "Edit", "mData": "Edit"},
						{"sTitle": "Delete", "mData": "Delete"},
						
					], "aaData": msg,
					
					"sPaginationType": "bootstrap",
					
					});
		}

		
	$('#tab6').on('click','.edit_consultancy_projects',function(e){
	$('html,body').animate({ scrollTop: $(".tab6").offset().top},'slow');
	edit_notify();
	var validator = $('#consultancy_projects').validate();
	validator.resetForm();
	data = $(this).attr('data-alldata');
	$('#c_id').val($(this).attr('id'));
	$('#project_code').val($(this).attr('data-project_code'));
	$('#project_title').val($(this).attr('data-project_title'));	
	$('#client').val($(this).attr('data-client'));
	$('#amount_consult').val($(this).attr('data-amount'));
	//$('#consultant').val($(this).attr('data-consultant'));
	$('#co_consultant').val($(this).attr('data-co_consultant'));	
	$('#year_consult').val($(this).attr('data-year'));
	$('#consult_status').val($(this).attr('data-status'));
	$('#consult_role').val($(this).attr('data-user_role'));
	if($(this).attr('data-user_role') == 181){ $('#consultant_role').show(); $('#consultant').val($(this).attr('data-consultant'));}
	else{$('#consultant_role').hide();$('#consultant').val($("#consult_role option:selected").text());}
	tiny_init();	
			tinyMCE.get('abstract_consult').setContent($(this).attr('data-abstract'));	
	$('#update_consult_project').show();$('#save_consult_project').hide();
	});	
	
 	$('#tab7').on('click','.edit_sponsored_projects',function(e){
			$('html,body').animate({ scrollTop: $(".tab7").offset().top},'slow');
		edit_notify();
	var validator = $('#sponsored_projects').validate();
	validator.resetForm();	
	data = $(this).attr('data-alldata');
	$('#s_id').val($(this).attr('id'));
	$('#spo_project_code').val($(this).attr('data-project_code'));
	$('#spo_project_title').val($(this).attr('data-project_title'));	
	$('#spo_oganization').val($(this).attr('data-client'));
	$('#spo_amount').val($(this).attr('data-amount'));
	$('#spo_investigator').val($(this).attr('data-consultant'));
	$('#co_spo_investigator').val($(this).attr('data-co_consultant'));	
	$('#spo_year').val($(this).attr('data-year'));
	$('#spo_status').val($(this).attr('data-status'));
	$('#collaborating_organization').val($(this).attr('data-collaborating_org'));	
	$('#spo_duration').val($(this).attr('data-duration'));
	
	tiny_init();	
			tinyMCE.get('abstract_spo').setContent($(this).attr('data-abstract'));	   
	$('#update_spo_project').show();$('#save_spo_project').hide();
	});  	
	
	$('#tab8').on('click','.edit_award_honour',function(e){
		$('html,body').animate({ scrollTop: $(".tab8").offset().top},'slow');
		edit_notify();
	var validator = $('#award_honours').validate();
	validator.resetForm();
	data = $(this).attr('data-alldata');
	$('#award_id').val($(this).attr('id'));
	$('#award_name').val($(this).attr('data-award_name'));
	$('#award_for').val($(this).attr('data-award_for'));	
	$('#award_org').val($(this).attr('data-spo_oganization'));
	$('#award_venue').val($(this).attr('data-venue'));
	$('#award_remarks').val($(this).attr('data-remarks'));
	$('#cash_award').val($(this).attr('data-cash_award'));	
	$('#awarded_year').val($(this).attr('data-year'));
	$('#award_user_id').val($(this).attr('data-user_id'));
		$('#update_award_honour').show();$('#save_award_honour').hide();
	}); 	
	
	$('#patent').on('keyup keypress', function(e) {
	  var keyCode = e.keyCode || e.which;

	 /*  if (keyCode === 13) { 
		e.preventDefault();
		return false;
	  } */
	});
	
	$('#tab9').on('click','.edit_patent',function(e){
		$('html,body').animate({ scrollTop: $(".tab9").offset().top},'slow');
		edit_notify();
		tinyMCE.triggerSave();
	var validator = $('#patent').validate();
	validator.resetForm();
	data = $(this).attr('data-alldata');
	$('#patent_id').val($(this).attr('id'));
	$('#patent_title').val($(this).attr('data-patent_title'));
	$('#inventors').val($(this).attr('data-inventors'));	
	$('#patent_no').val($(this).attr('data-patent_no'));
	$('#patent_status').val($(this).attr('data-status'));	
	$('#patent_year').val($(this).attr('data-year'));
	$('#patent_user_id').val($(this).attr('data-user_id'));
	$('#innovation_link').val($(this).attr('data-link'));
	tiny_init();	
		tinyMCE.get('patent_abstract').setContent($(this).attr('data-abstract'));
	
		$('#update_patent').show();$('#save_patent').hide();
	}); 	
	
	$('#tab10').on('click','.edit_scholor',function(e){
		$('html,body').animate({ scrollTop: $(".tab10").offset().top},'slow');
		edit_notify();
	var validator = $('#sholarship').validate();
	validator.resetForm();
	data = $(this).attr('data-alldata');
	$('#scholar_id').val($(this).attr('id'));
	$('#sholarship_for').val($(this).attr('data-fellow_scholar_for'));
	$('#awarded_by').val($(this).attr('data-awarded_by'));	
	$('#scholar_type').val($(this).attr('data-type'));
	$('#scholar_year').val($(this).attr('data-year'));
	$('#scholar_end_year').val($(this).attr('data-end_year'));
	$('#scholar_user_id').val($(this).attr('data-user_id'));
	$('#fellow_amount').val($(this).attr('data-amount'));	
		tiny_init();	
		tinyMCE.get('scholar_abstract').setContent($(this).attr('data-abstract'));	
		$('#update_scholar').show();$('#save_scholar').hide();
	});	
	
	$('#tab11').on('click','.edit_paper_present',function(e){
		$('html,body').animate({ scrollTop: $(".tab11").offset().top},'slow');
		edit_notify();
	var validator = $('#paper_present').validate();
	validator.resetForm();
	data = $(this).attr('data-alldata');
	$('#paper_present_id').val($(this).attr('id'));
	$('#paper_present_title').val($(this).attr('data-title'));
	$('#paper_present_venue').val($(this).attr('data-venue'));	
	$('#presentation_type').val($(this).attr('data-presentation_type'));
	$('#presentation_role').val($(this).attr('data-presentation_role')); 
	$('#level_of_presentation').val($(this).attr('data-presentation_level'));	
	$('#paper_present_year').val($(this).attr('data-year'));
	$('#paper_present_user_id').val($(this).attr('data-user_id'));
	
	tiny_init();	
		tinyMCE.get('paper_present_abstract').setContent($(this).attr('data-abstract'));
		$('#update_paper_present').show();$('#save_paper_present').hide();
	}); 	
	
	$('#tab12').on('click','.edit_text_ref_book',function(e){
		$('html,body').animate({ scrollTop: $(".tab12").offset().top},'slow');
		edit_notify();
	var validator = $('#text_reference_book').validate();
	validator.resetForm();
	data = $(this).attr('data-alldata');
	$('#text_ref_id').val($(this).attr('id'));
	$('#book_title').val($(this).attr('data-book_title'));
	$('#book_no').val($(this).attr('data-book_no'));
	$('#co_author').val($(this).attr('data-co_author'));
	$('#published_by').val($(this).attr('data-published_by'));	
	$('#isbn_no').val($(this).attr('data-isbn_no'));	
	$('#printed_at').val($(this).attr('data-printed_at'));
	if($(this).attr('data-copyright_year') != '0000'){ $('#copyright_year').val($(this).attr('data-copyright_year'));}
	$('#book_type').val($(this).attr('data-book_type'));	
	$('#year_of_publication').val($(this).attr('data-year_of_publication'));
	$('#book_user_id').val($(this).attr('data-user_id'));	
	$('#no_of_chapters').val($(this).attr('data-no_chap'));
	$('#chapters').val($(this).attr('data-chapter'));
	 if($(this).attr('data-book_type') == 178){$('.book_type_name').show();	$('#book_type_name').attr('required',true);$('#book_type_name').val($(this).attr('data-book_type_name'));}else{$('.book_type_name').hide();}
	
	tiny_init();	
		tinyMCE.get('about_book').setContent($(this).attr('data-about_book'));
		$('#update_text_reference_book').show();$('#save_text_reference_book').hide();
	});	
	function edit_notify(){
	$.notify('Edit here','info', {
			className:"info",
			allow_dismiss: false,
			showProgressbar: true,
		},
		 { position:"right" });
	/*  $(".table-bordered").notify(
		  "Edit Here", 'info',
		  { position:"bottom" }
		);	 	 */			
	}
	
	
	$('#tab13').on('click','.edit_training_workshop_conference',function(e){
		$('#loading').show();
	
		$('html,body').animate({ scrollTop: $(".tab13").offset().top},'slow');
		edit_notify();
 		
		 
	//	$.notify( "Edit Table", { position:"right" });
	var validator = $('#training_workshop_conference').validate();
	validator.resetForm();
	data = $(this).attr('data-alldata');
	$('#twc_id').val($(this).attr('id'));
	$('#program_title').val($(this).attr('data-program_title'));
	$('#training_type').val($(this).attr('data-training_type'));
	$('#coordinators').val($(this).attr('data-coodinators'));
	$('#training_venue').val($(this).attr('data-venue'));	
	$('#program_fees').val($(this).attr('data-fees'));
	
	$('#collaboration').val($(this).attr('data-collabration'));
	$('#event_organizer').val($(this).attr('data-event_organizer'));	
	$('#no_of_participants').val($(this).attr('data-no_of_participants'));	
	
	
	$('#duration_hours').val($(this).attr('data-hours'));
	$('#duration_minutes').val($(this).attr('data-minute'));	
	$('#from_date').val($(this).attr('data-from_date'));
	$('#to_date').val($(this).attr('data-to_date'));
	$('#pedagogy').val($(this).attr('data-pedogogy'));	
	$('#training_sposored_by').val($(this).attr('data-sponsored_by'));	
	$('#year_of_publication').val($(this).attr('data-year_of_publication'));
	$('#training_role').val($(this).attr('data-role'));
	$('#ttr').val($(this).attr('data-ttr'));
	

	tiny_init();	
		tinyMCE.get('trarinin_objectives').setContent($(this).attr('data-objective'));
		$('#update_training_workshop_conference').show();$('#save_training_workshop_conference').hide();
			$('#loading').hide();
	}); 	
	
	$('#tab14').on('click','.edit_training_workshop_conference_attended',function(e){
		$('#loading').show();
	
		$('html,body').animate({ scrollTop: $(".tab14").offset().top},'slow');
		edit_notify();
 		
		 
	//	$.notify( "Edit Table", { position:"right" });
	var validator = $('#training_workshop_conference_attended').validate();
	validator.resetForm();
	data = $(this).attr('data-alldata');
	$('#twca_id').val($(this).attr('id'));
	$('#program_title_attended').val($(this).attr('data-program_title'));
	$('#training_type_attended').val($(this).attr('data-training_type'));
	$('#training_venue_attended').val($(this).attr('data-venue'));	
	$('#program_fees_attended').val($(this).attr('data-fees'));		
	$('#from_date_attended').val($(this).attr('data-from_date'));
	$('#to_date_attended').val($(this).attr('data-to_date'));	
	$('#training_role_attended').val($(this).attr('data-role'));
	$('#select_level_conference_attended').val($(this).attr('data-level'));	
	$('#event_organizer_attended').val($(this).attr('data-event_organizer'));	
	$('#delegates_attended').val($(this).attr('data-delegate'));

	 if($(this).attr('data-role') == 186){$('#attended_specify_div').show();	$('#training_role_attended_specify').attr('required',true);$('#training_role_attended_specify').val($(this).attr('data-user_role_specify'));}else{$('#attended_specify_div').hide();}
	 
	 
	tiny_init();	
		tinyMCE.get('trarinin_objectives_attended').setContent($(this).attr('data-objective'));
		$('#update_training_workshop_conference_attended').show();$('#save_training_workshop_conference_attended').hide();
			$('#loading').hide();
	}); 	
	
	
	$('#tab15').on('click','.edit_research_projects',function(e){
		$('#loading').show();
	
		$('html,body').animate({ scrollTop: $(".tab15").offset().top},'slow');
		edit_notify();
 				 
		var validator = $('#research_project').validate();
		validator.resetForm(); 
		data = $(this).attr('data-alldata');

		$('#research_project_title').val($(this).attr('data-program_title'));
		$('#research_project_user_role').val($(this).attr('data-role_fetched'));
		$('#research_project_amount').val($(this).attr('data-amount'));	
		$('#research_project_team_member').val($(this).attr('data-team'));		
		$('#research_project_funding_agency').val($(this).attr('data-agency'));
		$('#research_project_collabration').val($(this).attr('data-collabration'));	
		$('#research_project_sactioned_date').val($(this).attr('data-sanctioned_date'));
		$('#research_project_duration').val($(this).attr('data-duration'));	
		$('#research_project_status').val($(this).attr('data-status'));	
		$('#research_project_id').val($(this).attr('id'));
		
		$('#update_research_project').show();$('#save_research_project').hide();
		$('#loading').hide();
	}); 
		
		$('#my_tr_button').show();	$('#my_tr_button_update').hide();
		/**Function to call edit modal for my_research_paper Tab**/
		$('#tab4').on('click','.edit_training',function(e){
			$('html,body').animate({ scrollTop: $(".tab4").offset().top},'slow');
			edit_notify();
			var validator = $('#my_research_paper').validate();
			validator.resetForm();
			
			$('#title_res_det').val($(this).attr('data-title'));
			$('#Contribution').val($(this).attr('data-contribution'));
			tiny_init();	
			tinyMCE.get('abstract_res_detail').setContent($(this).attr('data-abstract'));
			$('#spe_domain').val($(this).attr('data-specialization'));
			$('#research_type').val($(this).attr('data-res_type'));
			$('#end_date').val($(this).attr('data-year'));
			$('#start_date_dev').val($(this).attr('data-start_date'));			
			$('#my_training_update').val('1');
			$('#my_res_detail_id').val($(this).attr('data-id'));
			$('#amount_c').val($(this).attr('data-amount'));
			$('#status').val($(this).attr('data-status'));
			$('#venue').val($(this).attr('data-venue'));
			$('#my_tr_button').hide();	$('#my_tr_button_update').show();
		});
		
		$('#my_qualification_tbl').on('click', '.delete_my_qualification', function(e){
			$('#my_qua_id').val($(this).attr('id'));
			$('#delete_my_qualification').modal('show');
		});
		$('#my_qualification_update').hide();
		$('#my_qualification_tbl').on('click','.edit_my_qualification',function(e){
		$('#loading').show();
			$('html,body').animate({ scrollTop: $(".tab4").offset().top},'slow');
			edit_notify();
			var validator = $('#my_qualification').validate();
			validator.resetForm();
			$('#degree').val($(this).attr('data-qua_id'));
			$('#my_university').val($(this).attr('data-university_name'));
			$('#start_date').val($(this).attr('data-yog'));
			$('#my_qua_id').val($(this).attr('data-mqa_id'));	
			$('#dept_id').val($(this).attr('data-specialization'));
			//$('select[name^="salesrep"] option[value="Bruce Jones"]').attr("selected","selected");
			
			$('#my_qualification_save').hide();		
			$('#my_qualification_update').show();
			$('#loading').hide();
		});
		
		$('#delete_my_qua_btn').on('click',function(){
		$('#loading').show();
			var my_qua_id = $('#my_qua_id').val();
			var user_id = $('#user_id').val();
			post_data =  {'my_qua_id':my_qua_id ,'user_id':user_id}
			$.ajax({type: 'POST',
					url: base_url+'report/edit_profile/delete_my_qualifications',
					data: post_data,
					success: function(data) {
					fetch_my_qualification();
					if(data == 1){success_modal_delete(data);}
					if(data == 0){fail_modal(data);}
					$('#loading').hide(); reset_my_qualification();
					}});
		});
		
		$('div.accordion-body').on('shown', function () {
			$(this).parent("div").find(".icon-chevron-down")
				.removeClass("icon-chevron-down").addClass("icon-chevron-up");
		});

		$('div.accordion-body').on('hidden', function () {
			$(this).parent("div").find(".icon-chevron-up")
				   .removeClass("icon-chevron-up").addClass("icon-chevron-down");
		});
		/**Function to close modal**/

		$("#my_tr_cancel").click(function() {
		 $("#tab4").modal('hide');
				activaTab('tab3');
			 location.reload();
		});
		$(document).on('click','#cancel',function(evt){
				location.reload();
		});
		$('#tab4').on('show', function () {
			$('.modal-body',this).css({width:'auto',height:'auto', 'max-height':'10%'});
		});

// Functions for Auto - save of file description and date 
/* 		$(document).delegate('.res_detail_description,.actual_date_data_detl','change  paste click', function() {
			var desc = $('#res_detail_description').val();
			var date = $('#actual_date_data_detl').val(); 
			var my_id_data = $('#my_id_data').val();  
			var user_id = $('#user_id').val(); 
					post_data =  {'user_id':user_id,'my_id':my_id_data,'desc':desc,'actual_date':date}
					$.ajax({type: 'POST',
					url: base_url+'report/edit_profile/save_description_detl',
					data: post_data,
					success: function(data) {
						
					}}); 
		
		});  
		
	/* 	$(document).delegate('.res_description_data,.actual_date_data','change  paste click', function() {
			var desc = $('#res_description').val();
			var date = $('#actual_date').val(); 
			var my_id_data = $('#my_id_data').val();  
			var user_id = $('#user_id').val(); 
					post_data =  {'user_id':user_id,'my_id':my_id_data,'desc':desc,'actual_date':date}
					$.ajax({type: 'POST',
					url: base_url+'report/edit_profile/save_description',
					data: post_data,
					success: function(data) {
						
					}}); 
		
		});  */
/* 		
	$('#log_history_dissable').on('click',function(){
if($('#log_history_dissable').attr('checked')) {
post_data = {'val':1}
		$.ajax({type: "POST",
        url: base_url + 'login/prevent_log_history',
        data: post_data,
        success: function(data){}
    });
} else {
 post_data = {'val':0}
		$.ajax({type: "POST",
        url: base_url + 'login/prevent_log_history',
        data: post_data,
        success: function(data){}
    });
}
}); */		
		/** Function Delete Innovation details of user **/
		$('#example11').on('click','.delete_paper_present',function(e){
			$('#paper_present_id').val($(this).attr('id'));
			$('#paper_presentation_modal').modal('show');
		});
		
		$('#delete_paper_presentation').on('click',function(){
		$('#loading').show();
			var paper_present_id = $('#paper_present_id').val();
			var user_id = $('#user_id').val();
			post_data =  {'paper_present_id':paper_present_id,'user_id':user_id}
			$.ajax({type: 'POST',
					url: base_url+'report/edit_profile/delete_paper_presentation',
					data: post_data,
					success: function(data) {
					$('#loading').hide();
					fetch_paper_presentation();
					if(data == 1){success_modal_delete();}
					if(data == 0){fail_modal(data);}
					$('#loading').hide(); reset_innovation();
					}});
			
		});

	$('#example5').on('click','.edit_my_innovations',function(e){
		tiny_init();
			$('#my_innovation_id').val($(this).attr('data-my_innovation_id'));
			$('#innovation_title').val($(this).attr('data-title'));
			$('#innovation_link').val($(this).attr('data-link'));
			tinyMCE.get('innovation_description').setContent($(this).attr('data-desc')); 
			$('#my_innovation_save').hide();		
			$('#my_innovation_update').show();
			$('#my_innovation_edit_val').val(1);
	});		
		
		/** Function to upload files of research paper and research details of user**/
		$( '.upload_data_file').live('click', function(e){					

				$('#upload_modal').modal('show');
					var user_id = $('#user_id').val(); 
					var tab_id =  $('#upload_id').val();
					var per_table_id = $(this).attr('id');
					$('#per_table_id').val($(this).attr('id'));
					fetch_file_data(per_table_id);
					var post_data = {
						'tab_id' : tab_id,
						'user_id' : user_id,
						'per_table_id':per_table_id
					}	
 					var uploader = document.getElementById('uploaded_file');	 
						upclick({
							element: uploader,
							action_params : post_data,
							multiple: true,
							action: base_url+'report/edit_profile/upload',
							oncomplete:
								function(response_data) {	
									fetch_file_data(per_table_id);
											if(response_data=="file_name_size_exceeded") {
												$('#file_name_size_exc').modal('show');
											} else if(response_data=="file_size_exceed") {
												$('#larger').modal('show');
											} else{
												var data_options = '{"text":"Your file has been uploaded successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
												var options = $.parseJSON(data_options);
												noty(options);
											}
								}
						 }); 					
				
		});		
		
		
		
		
		
		$('.close_up_div').on('click',function(){
			$('#per_table_id').val('');
		});
				
		function fetch_file_data(per_table_id){
		document.getElementById('res_guid_files').innerHTML = '';
					var user_id = $('#user_id').val(); 
					var tab_id =  $('#upload_id').val();
					var per_table_id =per_table_id;
			
					post_data =  {'user_id':user_id,'tab_id':tab_id,'per_table_id':per_table_id}
					$.ajax({type: 'POST',
					url: base_url+'report/edit_profile/fetch_files',
					data: post_data,
					success: function(data) {

						document.getElementById('res_guid_files').innerHTML = data;
					}});
		}
/* 		
		function fetch_res_detl_files(){
			var my_res_detl_id = $('#my_res_detl_id').val();
			var user_id = $('#user_id_data').val();
			
					post_data =  {'user_id':user_id,'my_res_detl_id':my_res_detl_id}
					$.ajax({type: 'POST',
					url: base_url+'report/edit_profile/fetch_files_detl',
					data: post_data,
					success: function(data) {
						document.getElementById('res_guid_files').innerHTML = data;
					}});
		} */
		
	
		function delete_file(user,my_id){
			$('#user_id').val(user);$('#my_id').val(my_id);
			$('#delete_res_guid_file_modal').modal('show');
		}
		
		function delete_research_file(user,my_id){
			$('#user_id').val(user);$('#my_id').val(my_id);
			$('#delete_res_deatil_file_modal').modal('show');
		}
		
		$('#delete_res_guid_file').on('click',function(){
		
			var user_id = $('#user_id').val();
			var my_id = $('#my_id').val();
		

					post_data =  {'user_id':user_id,'my_id':my_id}
					$.ajax({type: 'POST',
					url: base_url+'report/edit_profile/delete_res_guid_file',
					data: post_data,
					success: function(data) {
						var data_options = '{"text":"Your file has been deleted successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
							var options = $.parseJSON(data_options);
							noty(options);
					var per_table_id = $('#per_table_id').val();;
						fetch_file_data(per_table_id);
					}});
		});		
		
		$('#delete_res_detail_file').on('click',function(){
		
			var user_id = $('#user_id').val();
			var my_id = $('#my_id').val();
		

					post_data =  {'user_id':user_id,'my_id':my_id}
					$.ajax({type: 'POST',
					url: base_url+'report/edit_profile/delete_res_detail_file',
					data: post_data,
					success: function(data) {
					//if(data == 1){success_modal(data);}else{fail_modal(data);}
						fetch_res_detl_files();
					}});
		});

		$('#my_research_peper_update').hide();
		/**Function call edit modal for my_research_papers Tab**/	
		$('#tab3').on('click', '.edit_qp', function(e){
			$('html,body').animate({ scrollTop: $(".tab3").offset().top},'slow');
			edit_notify();
			var validator = $('#research_publication').validate();
			validator.resetForm();
			$('#title_res_research').val($(this).attr('data-title'));
			$('#author_research').val($(this).attr('data-author'));

			$('#dp4_research').val($(this).attr('data-publication_date'));
			$('#dp4_end_research').val($(this).attr('data-current_version_date'));
			$('#issue_date_research').val($(this).attr('data-issue_date'));
			
			$('#amount_res_research').val($(this).attr('data-amount'));
			$('#contribution_res_guid_research').val($(this).attr('data-sponsored_by'));
			$('#citation_research').val($(this).attr('data-citation_count'));
			$('#hindex_research').val($(this).attr('data-hindex'));
			$('#i10_index_research').val($(this).attr('data-i10_index'));
			
			$('#issn_research').val($(this).attr('data-issn'));
			$('#doi_research').val($(this).attr('data-doi'));
			$('#status_research').val($(this).attr('data-status'));
			$('#index_terms_research').val($(this).attr('data-index_terms'));
			$('#publish_online_research').val($(this).attr('data-publication_online'));
			$('#publisher_research').val($(this).attr('data-publisher'));
			
			$('#vol_no_research').val($(this).attr('data-vol_no'));
			$('#issue_no_research').val($(this).attr('data-issue_no'));
			$('#pages_research').val($(this).attr('data-pages'));
			
			$('#type_publication').val($(this).attr('data-publication_type'));
			$('#publisher_status').val($(this).attr('data-publication_level'));
			$('#publication_title').val($(this).attr('data-publication_title'));
			$('#publication_award_won').attr('checked');
			$('#publication_award_won_text').val($(this).attr('data-publication_prize_won'));
			if($(this).attr('data-publication_prize_won') != ''){ 
				$('#publication_award_won').attr('checked','checked');
				$('.publication_won').show();
			}
			tiny_init();	
			tinyMCE.get('abstract_data_research').setContent($(this).attr('data-abstract'));
			$('#research_id').val($(this).attr('data-id'));
			$('#user_id_research').val($(this).attr('data-user_id'));
			$('#my_research_pape_save').hide();$('#my_research_peper_update').show();
		});		
		
		
		/**Function call edit modal for my_research_papers Tab**/	
		$('#tab4').on('click', '.edit_journal', function(e){
		$('html,body').animate({ scrollTop: $(".tab1").offset().top},'slow');
			edit_notify();
			var validator = $('#journal_publication').validate();
			validator.resetForm();
			var validator = $('#research_publication').validate();
			validator.resetForm();
			$('#title_res_jourrnal').val($(this).attr('data-title'));
			$('#author_jourrnal').val($(this).attr('data-author'));

			$('#dp4_jourrnal').val($(this).attr('data-publication_date'));
			$('#dp4_end_jourrnal').val($(this).attr('data-current_version_date'));
			$('#issue_date_jourrnal').val($(this).attr('data-issue_date'));
			
			$('#amount_res_jourrnal').val($(this).attr('data-amount'));
			$('#contribution_res_guid_jourrnal').val($(this).attr('data-sponsored_by'));
			$('#citation_jourrnal').val($(this).attr('data-citation_count'));
			$('#hindex_jourrnal').val($(this).attr('data-hindex'));
			$('#i10_index_jourrnal').val($(this).attr('data-i10_index'));
			
			$('#issn_jourrnal').val($(this).attr('data-issn'));
			$('#doi_jourrnal').val($(this).attr('data-doi'));
			$('#status_jourrnal').val($(this).attr('data-status'));
			$('#index_terms_jourrnal').val($(this).attr('data-index_terms'));
			$('#publish_online_jourrnal').val($(this).attr('data-publication_online'));
			$('#publisher_jourrnal').val($(this).attr('data-publisher'));
			
			$('#vol_no_jourrnal').val($(this).attr('data-vol_no'));
			$('#issue_no_jourrnal').val($(this).attr('data-issue_no'));
			$('#pages_jourrnal').val($(this).attr('data-pages'));
			
			tiny_init();	
			tinyMCE.get('abstract_data_jourrnal').setContent($(this).attr('data-abstract'));

			$('#user_id_journal').val($(this).attr('data-user_id'));
			
			
			$('#journal_id').val($(this).attr('data-id'));
			$('#jourrnal_save').hide();$('#jourrnal_update').show();
		});
			
		/**Function to Delete the My_Acheivements Data**/
		$('#btnYes').on('click',function(){
		$('#loading').show();
		var user_id = $('#user_id').val();
		var id = $('#myModal4').data('id');
		var tab_id = $('#upload_id').val();
			post_data =  {'my_aid':id ,'tab_id':tab_id , 'user_id':user_id }
			$.ajax({type: 'POST',
					url: base_url+'report/edit_profile/delete_my_achievements',
					data: post_data,
					success: function(data) {
								$('#loading').hide();reset_reaearch_papers(); fetch_journal(); reset_jourrnal();success_modal_delete(data);
								post_data =  {'user_id':user_id}
								$.ajax({type: "POST",
								url: base_url+'report/edit_profile/fetch_my_achievements',
								data: post_data,
								dataType: 'json',
							   success: populate_table
							});
					}
			});
		});
		/**Function to Delete the my_training Data**/
		$('#btnYest').on('click',function(){
			$('#loading').show();
			var id=$('#myModal5').data('id'); 
			var user_id = $('#user_id').val();
			post_data={'my_res_detl_id':id}
			$.ajax({type:'POST',
					url: base_url+'report/edit_profile/delete_my_training',
					data: post_data,
					success: function(data) {
								$('#loading').hide();reset_training();
								post_data =  {'user_id':user_id}
								$.ajax({type: "POST",
								url: base_url+'report/edit_profile/fetch_my_training',
								data: post_data,
								dataType: 'json',
							   success: populate_training_table
							});
					}
			});
		});

		/**To Delete the workload**/
		$('#btnDelWork').on('click',function(){
		$('#loading').show();
		var id=$('#myModal6').data('id');
		post_data={'my_tw_id':id}
			$.ajax({type:'POST',
					url: base_url+'report/edit_profile/delete_my_teaching_workload',
					data: post_data,
					success: function(data) {		
								post_data =  {'user_id':user_id}
								$.ajax({type: "POST",
										url: base_url+'report/edit_profile/fetch_my_teching_workload',
										data: post_data,
										dataType: 'json',
									   success: function(data){populate_teaching_table(data);success_modal_delete(data);reset_my_teaching();}
									});
					$('#loading').hide();
					}
			}); 
		});
		
	
		
	$('#reset_password_btn').on('click',function(){
		var reset_password = $('#reset_password').val(); 
		if(reset_password == ""){	
			$('#reset_password').css('border-color', 'red');
			$('#reset_password').addClass('num_valid');
			$('#reset_password').attr("placeholder", "Enter Password");
		}else if(reset_password != ""){
			$('#reset_password').css('border-color', '#CCCCCC');
			var password_user_id = $('#password_user_id').val();
			var reset_password =$('#reset_password').val();
			post_data =  {'user_id':password_user_id ,'reset_password':reset_password}
			$.ajax({type: "POST",
					url: base_url+'report/edit_profile/change_password',
					data: post_data,
					dataType: 'json',
				   success: function(data){	
						if(data == 1){ 
							var data_options = '{"text":"Your password has been changed successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
							var options = $.parseJSON(data_options);
							noty(options);window.location = base_url + 'dashboard/dashboard/'; 
						}else{
							var data_options = '{"text":"Your password has not been changed !","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
							var options = $.parseJSON(data_options);
							noty(options);
						}
				   }
			});
		}
	});
		

		
	$('#example_designation').on('click','.delete_user_designation',function(){
	$('#design_user_id').val($(this).attr('data-user_id'));
	$('#user_usd_id').val($(this).attr('id'));
	$('#delete_user_designation').modal('show');
	});
		
	$('#delete_user_designation_btn').on('click',function(){
		var design_user_id = $('#design_user_id').val();	
		var user_usd_id = $('#user_usd_id').val();
		
		post_data =  {'user_id':design_user_id ,'user_usd_id':user_usd_id}
								$.ajax({type: "POST",
										url: base_url+'report/edit_profile/delete_user_designation',
										data: post_data,
										dataType: 'json',
									   success: function(data){	
									   success_modal_delete(data);
									   fetch_user_designation_list();
									   }
									});
				
	});
	
	$('#example_designation').on('click','.edit_user_designation',function(){
	 $('#user_usd_id').val($(this).attr('id'));
	 $('#design_user_id').val($(this).attr('data-user_id'));
	 $('#dept_user').val($(this).attr('data-dept_id')); 
	 $('#designation_list').val($(this).attr('data-designation'));
	 $('#designation_date').val($(this).attr('data-year'));
		$('#update_save_user_designations').show();$('#save_save_user_designations').hide(); 
	});
	
		
	/**Calling the modal on success**/
		function success_modal(msg) { 
				var data_options = '{"text":"Your data has been saved successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
									var options = $.parseJSON(data_options);
									noty(options);				
		}	
		/**Calling the modal on success**/
		function enable_modal(msg) { 
				var data_options = '{"text":"Your log history display has been enabled successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
									var options = $.parseJSON(data_options);
									noty(options);				
		} 		
		
		/**Calling the modal on success**/
		function dissable_modal(msg) { 
				var data_options = '{"text":"Your log history display has been disabled successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
									var options = $.parseJSON(data_options);
									noty(options);				
		} 
	/**Calling the model on successfull update**/
	function success_modal_update(msg) { 
				var data_options = '{"text":"Your data has been updated successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
									var options = $.parseJSON(data_options);
									noty(options);											
		}
		
		function success_modal_delete(msg) { 
				var data_options = '{"text":"Your data has been deleted successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
									var options = $.parseJSON(data_options);
									noty(options);											
		}
	
	function fail_modal(msg){//$('#myModal_fail').modal('show');				
				$('#loading').hide();
				var data_options = '{"text":"This Data already exist.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
									var options = $.parseJSON(data_options);
									noty(options);
		} 
	/**Function To Initialize the tinymce **/
/* 		 tinymce.init({
			//selector: "textarea",
			 mode : "specific_textareas",
			editor_selector: "question_textarea",
			//plugins: "paste",
			relative_urls: false,
			plugins: [
				//"advlist autolink lists link image charmap print preview anchor",
				"searchreplace visualblocks code fullscreen",
				"insertdatetime media table contextmenu paste jbimages",
			
			],
			paste_data_images: true,
			toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages",
			//height : 300;		
		}); */
	
	
	/**Function to initialize the tinymce**/
/*  	 function tiny_init(){
		tinymce.init({
	   // selector: "textarea",
		mode : "specific_textareas",
		editor_selector: "question_textarea",
		//plugins: "paste",
		relative_urls: false,
		plugins: [
		"advlist autolink lists link image charmap print preview anchor",
		"searchreplace visualblocks code fullscreen",
		"insertdatetime media table contextmenu paste jbimages",
		
		],
		paste_data_images: true,
		toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages",
		//height : 300;
		
		});
	}  */				
	
function tiny_init() {
    tinymce.init({
        mode: "specific_textareas",
        editor_selector: "question_textarea",	
		theme: 'modern',
        relative_urls: false,
		allow_conditional_comments: true,
		allow_html_in_named_anchor: true,
        relative_urls: true,
		plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste jbimages",
        ],
        paste_data_images: true,
/*         toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages", */
		 toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages | bold italic underline | bullist numlist | subscript superscript "
    });
}

//call function to initailize tiny mce
tiny_init();
	 var counter = 2;
	/**Function to clone the table**/
	$('#add_ExpenseRow').click(function(){
		var rowCount = $('#expense_table tr').length;   var arr=$('#wkl').val();
		if(rowCount<=(arr))	{
			$('#expense_table tr').last().after('<tr><td><select><option>Select</option>$.each(arr, function(index, value){<option>value</option>}</select></td><td><input type="text" name="txtbx'+counter+'" value=""></td><td><i class="del_tw icon-remove icon-black"> </i></td></tr>');
			counter++;}else{$("#work_load_error").html("Sorry Cant Add More");}
	});	
	/**Function to delete the dynamically created div **/
	$(".del_tw").live("click",function(){
		var rowCount = $('#expense_table tr').length;
		$(this).closest('tr').remove();
		$("#error_msg").html(" ");
	});
	/**Function to delete the teaching Workload created div **/
	$(".del_ExpenseRow").live("click", function(){ 
		var id=$(this).attr('id');
		$('#myModal6').data('id', id).modal('show');
	});		
	
$('#imgArea').hover(
   function() {
         $(this).children('div').children('img').fadeTo('slow', 0.2);
  },
   function() {
         $(this).children('div').children('img').fadeTo('slow', 1);
  }
);
$('#imgContainer').live('change', '#image_upload_file', function (event) {
	event.preventDefault();
	var progressBar = $('.progressBar'), bar = $('.progressBar .bar'), percent = $('.progressBar .percent');

	$('#image_upload_form').ajaxForm({
		beforeSend: function() {
			progressBar.fadeIn();
			$('#img_val').fadeIn();
			$(this).find('.box-hover').fadeToggle(100);
			var percentVal = '100%';
			bar.width(percentVal)
			percent.html(percentVal);
		},
		uploadProgress: function(event, position, total, percentComplete) {
			var percentVal = percentComplete + '%';
			bar.width(percentVal)
			percent.html(percentVal);
		},
		success: function(html, statusText, xhr, $form) {			
			obj = $.parseJSON(html);
			if(obj){		
				var percentVal = '100%';
				bar.width(percentVal)
				percent.html(percentVal);
				var tmppath = URL.createObjectURL(event.target.files[0]);
				var img_tag = '<img src="'+tmppath+'" id="upload_img" alt="" />';
				$(".img_val").empty();	
				$(".img_val").html(img_tag);
				
			}else{
				$('#invalid_file').modal('show');
			}
		},
		complete: function(xhr) {
			progressBar.fadeOut();			
		}	
	}).submit();
});

	
	
$(function() {
	$('.monthYearPicker').datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'MM yy'
	}).focus(function() {
		var thisCalendar = $(this);
		$('.ui-datepicker-calendar').detach();
		$('.ui-datepicker-close').click(function() {
var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
thisCalendar.datepicker('setDate', new Date(year, month, 1));
		});
	});
});
if($('#rdb1').attr('checked')) {$('.show_hide_agency').show();} else if($('#rdb2').attr('checked')){$('.show_hide_author_training').hide();$('.show_hide_agency').hide();}
	
 $("#rdb1").click(function(){
$('.show_hide_agency').show();$('#show_hide_author_training').show();
});
$("#rdb2").click(function(){
$('.show_hide_agency').hide();$('#show_hide_author_training').hide();$('#author_training').val('');$('#Contribution').val('');
}); 

	/** Function to validate and handle the datepicker**/
	$(document).ready(function() {
	
	 var startDate = new Date('');
	var FromEndDate = new Date();
	var ToEndDate = new Date();	
	var startDaters = new Date();
	var startDaterd = new Date();

	ToEndDate.setDate(ToEndDate.getDate()+365);
	
	$('#from_date').datepicker({
		format:"dd-mm-yyyy",
		weekStart: 1,
		autoclose: true
	}).on('changeDate', function(selected){
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $('#to_date').datepicker('setStartDate', startDate);
    }); 
	$('#btn_from_date').click(function(){
			$(document).ready(function(){
				$('#from_date').datepicker().focus();
			});
	});
	
	$('#to_date').datepicker({
		format:"dd-mm-yyyy",
		weekStart: 1,
		startDate: startDate,
		endDate: ToEndDate,
		autoclose: true
	}).on('changeDate', function(selected){
        FromEndDate = new Date(selected.date.valueOf());
        FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
        $('#from_date').datepicker('setEndDate', FromEndDate);
    });
	$('#to_date_btn').click(function(){
		$(document).ready(function(){
			$('#to_date').datepicker().focus();
		});
	});	
	
	$('#from_date_attended').datepicker({
		format:"dd-mm-yyyy",
		weekStart: 1,
		autoclose: true
	}).on('changeDate', function(selected){
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $('#to_date_attended').datepicker('setStartDate', startDate);
    }); 
	$('#btn_from_date_attended').click(function(){
			$(document).ready(function(){
				$('#from_date_attended').datepicker().focus();
			});
	});
	
	$('#to_date_attended').datepicker({
		format:"dd-mm-yyyy",
		weekStart: 1,
		startDate: startDate,
		endDate: ToEndDate,
		autoclose: true
	}).on('changeDate', function(selected){
        FromEndDate = new Date(selected.date.valueOf());
        FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
        $('#from_date_attended').datepicker('setEndDate', FromEndDate);
    });
	$('#to_date_btn_attended').click(function(){
		$(document).ready(function(){
			$('#to_date_attended').datepicker().focus();
		});
	});

/* 
	$("#scholar_year").datepicker( {	
			format: "MM-yyyy",	
			startView: "months", 
			minViewMode: "months",
	}).on('changeDate',function (ev){
		    $(this).blur();
			$(this).datepicker('hide');
	});	 
	$('#scholar_year_btn').click(function(){
			$(document).ready(function(){
				$('#scholar_year').datepicker().focus();
			});
	});
 */
	$('#scholar_year').datepicker({
		format: "MM-yyyy",	
		startView: "months", 
		minViewMode: "months",
		weekStart: 1,
		autoclose: true
	}).on('changeDate', function(selected){
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $('#scholar_end_year').datepicker('setStartDate', startDate);
    }); 
	$('#scholar_year_btn').click(function(){
			$(document).ready(function(){
				$('#scholar_year').datepicker().focus();
			});
	});
	
	$('#scholar_end_year').datepicker({
		format: "MM-yyyy",	
		startView: "months", 
		minViewMode: "months",
		weekStart: 1,
		startDate: startDate,
		endDate: ToEndDate,
		autoclose: true
	}).on('changeDate', function(selected){
        FromEndDate = new Date(selected.date.valueOf());
        FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
        $('#from_date').datepicker('setEndDate', FromEndDate);
    });
	$('#scholar_end_year').click(function(){
		$(document).ready(function(){
			$('#scholar_end_year').datepicker().focus();
		});
	});
	
	
	$("#year_of_publication").datepicker( {	
			format: "yyyy",
			viewMode: "years", 
			minViewMode: "years",	
			endDate:'-1d'
	}).on('changeDate',function (ev){
		    $(this).blur();
			$(this).datepicker('hide');
	});
	$('#year_of_publication_btn').click(function(){
			$(document).ready(function(){
				$('#year_of_publication').datepicker().focus();
			});
	});
	$("#copyright_year").datepicker({	
			 format: "yyyy",
			viewMode: "years", 
			minViewMode: "years",	
		//	startDate: '-3d',
			endDate:'-1d'
	}).on('changeDate',function (ev){
		    $(this).blur();
			$(this).datepicker('hide');
	});
	$('#year_copyright_year_btn').click(function(){
			$(document).ready(function(){
				$('#copyright_year').datepicker().focus();
			});
	});
	
	$("#paper_present_year").datepicker( {	
			 format: "dd-mm-yyyy",
			endDate:'-1d'
	}).on('changeDate',function (ev){
		    $(this).blur();
			$(this).datepicker('hide');
	});	
	$('#paper_present_year_btn').click(function(){
			$(document).ready(function(){
				$('#paper_present_year').datepicker().focus();
			});
	});
	

	$("#patent_year").datepicker( {
			format: "yyyy",
			viewMode: "years", 
			minViewMode: "years",	
			endDate:'-1d'
	}).on('changeDate',function (ev){
		    $(this).blur();
			$(this).datepicker('hide');
	});
	$('#patent_year_btn').click(function(){
			$(document).ready(function(){
				$('#patent_year').datepicker().focus();
			});
	});
	$("#awarded_year").datepicker( {	
			 format: "yyyy",
			viewMode: "years", 
			minViewMode: "years",	
		//	startDate: '-3d',
			endDate:'-1d'
	}).on('changeDate',function (ev){
		    $(this).blur();
			$(this).datepicker('hide');
	});	
	$('#awarded_year_btn').click(function(){
			$(document).ready(function(){
				$('#awarded_year').datepicker().focus();
			});
	});
	$("#spo_year").datepicker( {	
			 format: "yyyy",
			viewMode: "years", 
			minViewMode: "years",	
		//	startDate: '-3d',
			endDate:'-1d'
	}).on('changeDate',function (ev){
		    $(this).blur();
			$(this).datepicker('hide');
	});	 
	$('#spo_year_btn').click(function(){
			$(document).ready(function(){
				$('#spo_year').datepicker().focus();
			});
	});
	$("#dp4_jourrnal").datepicker( {	
		format: "dd-mm-yyyy",
	//	endDate: '-1d' 
	}).on('changeDate',function (ev){
	    $(this).blur();
		$(this).datepicker('hide');
	});
	$('#btn2').click(function(){
			$(document).ready(function(){
				$('#dp4_jourrnal').datepicker().focus();
			});
	});
	$("#dp4_end_jourrnal").datepicker( {	
		format: "dd-mm-yyyy",
		endDate: '-1d' 
	}).on('changeDate',function (ev){
	    $(this).blur();
		$(this).datepicker('hide');
	});
	$('#dp4_end_btn').click(function(){
		$(document).ready(function(){
			$('#dp4_end_jourrnal').datepicker().focus();
		});
	});	
	$("#issue_date_jourrnal").datepicker( {	
			format: "dd-mm-yyyy",
			//endDate: '-1d' 
	}).on('changeDate',function (ev){
		    $(this).blur();
			$(this).datepicker('hide');
	});	
	$('#issue_date_btn_jourrnal').click(function(){
		$(document).ready(function(){
			$('#issue_date_jourrnal').datepicker().focus();
		});
	});	
	$("#dp4_research").datepicker( {	
			format: "dd-mm-yyyy",
		//	endDate: '-1d' 
	}).on('changeDate',function (ev){
		    $(this).blur();
			$(this).datepicker('hide');
	});
	$('#research_btn').click(function(){
		$(document).ready(function(){
			$('#dp4_research').datepicker().focus();
		});
	});		
	$("#dp4_end_research").datepicker( {	
			format: "dd-mm-yyyy",
			endDate: '-1d' 
	}).on('changeDate',function (ev){
		    $(this).blur();
			$(this).datepicker('hide');
	});
	$('#dp4_end_research_btn').click(function(){
		$(document).ready(function(){
			$('#dp4_end_research').datepicker().focus();
		});
	});	
	$("#issue_date_research").datepicker( {	
			format: "dd-mm-yyyy",
			//endDate: '-1d' 
	}).on('changeDate',function (ev){
		    $(this).blur();
			$(this).datepicker('hide');
	});	
	$('#issue_date_btn_research').click(function(){
		$(document).ready(function(){
			$('#issue_date_research').datepicker().focus();
		});
	});		
	$("#dob").datepicker({
			format:"dd-mm-yyyy",
			endDate:'-1d',
			changeYear: true 
			// viewMode: 'years'
	}).on('changeDate',function(ev){
			$(this).blur();
			$(this).datepicker('hide');
	});	
	$('#dob_btn').click(function(){
		$(document).ready(function(){
			$('#dob').datepicker().focus();
		});
	});	
	$("#start_date").datepicker({
			format:"dd-mm-yyyy",
			endDate:'-1d',
			changeYear: true 
			// viewMode: 'years'
	}).on('changeDate',function(ev){
			$(this).blur();
			$(this).datepicker('hide');
	});	
	$('#start_btn').click(function(){
		$(document).ready(function(){
			$('#start_date').datepicker().focus();
		});
	});	
	$("#dp2").datepicker( {	
			format:"dd-mm-yyyy",
				weekStart: 1,
				startDate: '',
				endDate: FromEndDate, 
				autoclose: true
	}).on('changeDate load focus', function(selected){
				startDate = new Date(selected.date.valueOf());
				startDate.setDate(startDate.getDate(new Date(selected.date.valueOf()))); 
				$('#resigning_date').datepicker('setStartDate', startDate);
				$('#retirement_date').datepicker('setStartDate', startDate);
	});
	$('#btn1').click(function(){
		$(document).ready(function(){
			$("#dp2").datepicker().focus();	
		});
	});	
	startDaters = $('#dp2').val();
	$("#resigning_date").datepicker( {		
			format:"dd-mm-yyyy",
			weekStart: 1,
			startDate: startDaters,
			endDate: ToEndDate,
			autoclose: true
			}).on('changeDate load focus', function(selected){
			FromEndDate = new Date(selected.date.valueOf());
			FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
			$('#dp2').datepicker('setEndDate', FromEndDate);
	});	
	$('#btn_resign').click(function(){
		$(document).ready(function(){
			$("#resigning_date").datepicker().focus();	
		});
	});	
	startDaterd = $('#dp2').val(); 
	$("#retirement_date").datepicker( {	
			format:"dd-mm-yyyy",
			weekStart: 1,
			startDate: startDaterd,
			endDate: ToEndDate,
			autoclose: true
			}).on('changeDate load focus', function(selected){
			FromEndDate = new Date(selected.date.valueOf());
			FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
			$('#dp2').datepicker('setEndDate', FromEndDate);
	});	
	$('#btn_retirement ,#retirement_date').click(function(){
		$(document).ready(function(){
			$("#retirement_date").datepicker().focus();	
		});
	});			
	$("#year_consult").datepicker( {	
		format: "yyyy",
		viewMode: "years", 
		minViewMode: "years",	
		//	startDate: '-3d',
		endDate:'-1d'
	}).on('changeDate',function (ev){
	    $(this).blur();
		$(this).datepicker('hide');
	});	 		
	$('#year_consult_btn').click(function(){
		$(document).ready(function(){
			$('#year_consult').datepicker().focus();
		});
	});	
	$("#last_promotion").datepicker( {	
		format: "dd-mm-yyyy",
		//endDate:'dd' 
	}).on('changeDate',function (ev){
	    $(this).blur();
		$(this).datepicker('hide');
	});
	$('#btn_last_promotion ,#last_promotion').click(function(){
		$(document).ready(function(){
			$("#last_promotion").datepicker().focus();	
		});
	});		
	
	$("#phd_assessment_year").datepicker( {	
		format: "yyyy",
			viewMode: "years", 
		minViewMode: "years",
		//endDate:'dd' 
	}).on('changeDate',function (ev){
	    $(this).blur();
		$(this).datepicker('hide');
	});
	$('#btn_phd_assessment_year ,#phd_assessment_year').click(function(){
		$(document).ready(function(){
			$("#phd_assessment_year").datepicker().focus();	
		});
	});		
	
	$("#designation_date").datepicker( {	
		format: "yyyy",
			viewMode: "years", 
		minViewMode: "years",
		//endDate:'dd' 
	}).on('changeDate',function (ev){
	    $(this).blur();
		$(this).datepicker('hide');
	});
	$('#btn_designation_date ,#designation_date').click(function(){
		$(document).ready(function(){
			$("#designation_date").datepicker().focus();	
		});
	});	
	$("#accademic").datepicker( {	
		format: "dd-mm-yyyy",
		//endDate:'dd' 
	}).on('changeDate',function (ev){
	    $(this).blur();
		$(this).datepicker('hide');
	});
	$('#btn_accademic').click(function(){
		$(document).ready(function(){
			$("#accademic").datepicker().focus();	
		});
	})	
	
	$("#research_project_sactioned_date").datepicker( {	
		format: "dd-mm-yyyy",
		//endDate:'dd' 
	}).on('changeDate',function (ev){
	    $(this).blur();
		$(this).datepicker('hide');
	});
	$('#research_project_scanctioned_btn').click(function(){
		$(document).ready(function(){
			$("#research_project_sactioned_date").datepicker().focus();	
		});
	});	


/** Dont remove  // To validate  start and end date**/


	/*$('#start_date_dev').datepicker({
		format:"yyyy-mm-dd",
		weekStart: 1,
		//startDate: '01/01/2012',
		//endDate: FromEndDate, 
		autoclose: true
	}).on('changeDate', function(selected){
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $('#end_date').datepicker('setStartDate', startDate);
    }); 
	
	$('#end_date').datepicker({
			format:"yyyy-mm-dd",
			weekStart: 1,
			startDate: startDate,
			endDate: ToEndDate,
			autoclose: true
		}).on('changeDate', function(selected){
        FromEndDate = new Date(selected.date.valueOf());
        FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
        $('#start_date_dev').datepicker('setEndDate', FromEndDate);
    });	 */

		$('body').on('focus',".actual_date_data_fg", function(){
			$("#actual_date").datepicker( {	
						format: "dd-mm-yyyy",
						//endDate:'-1d' 
				}).on('changeDate',function (ev){
					$(this).blur();
					$(this).datepicker('hide');
				});
				$(this).datepicker({	
						format: "dd-mm-yyyy",
						//endDate:'-1d' 
				}).on('changeDate',function (ev){
					$(this).blur();
					$(this).datepicker('hide');
				});
				
				$('#actual_btn').click(function(){
		$(document).ready(function(){
			$("#actual_date").datepicker().focus();	
		});
	});
		});	

		
		$('body').on('focus',".actual_date_data_res_detl", function(){
			$("#actual_date_data_detl").datepicker( {	
						format: "yyyy-mm-dd",
						//endDate:'-1d' 
				}).on('changeDate',function (ev){
					$(this).blur();
					$(this).datepicker('hide');
				});
				$(this).datepicker({	
						format: "yyyy-mm-dd",
						//endDate:'-1d' 
				}).on('changeDate',function (ev){
					$(this).blur();
					$(this).datepicker('hide');
				});
		});
	});
	
	$(".allownumericwithdecimal").on('keypress blur', function (event) {
            if ((event.which != 8 && event.which != 0 && event.which != 44  && (event.which != 46 || $(this).val().indexOf('.') != -1)) && (event.which < 48 || event.which > 57)) {
					$("#errmsg").html("Digits Only").show().fadeOut("slow");
					$(this).css('border-color', 'red');
					$(this).append('<span>Invalid Value</span>');
					$(this).addClass('num_valid');
					$(this).attr("placeholder", "Only Digits!");					
				    return false;
            } else{		
					$(this).removeClass('num_valid');
					$(this).attr('placeholder', 'Enter Data');
					$(this).css('border-color', '#CCCCCC');
			}
    });
/* 	$(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {    
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
    }); */

	  $(".allownumericwithoutdecimal").on('keypress blur',function (e) {
     //if the letter is not digit then display error and don't type anything
    if(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
			$("#errmsg").html("Digits Only").show().fadeOut("slow");
			$(this).css('border-color', 'red');
			$(this).append('<span>Invalid Value</span>');
			$(this).addClass('num_valid');
			$(this).attr("placeholder", "Only Digits!");

               return false;
    }else{
			$(this).removeClass('num_valid');
			$(this).attr('placeholder', 'Enter Data');
			$(this).css('border-color', '#CCCCCC'); 
	}
	});
	 
	 function activaTab(tab){
			$(this).attr("placeholder", "");
			$('.nav-tabs a[href="#' + tab + '"]').tab('show');
	};