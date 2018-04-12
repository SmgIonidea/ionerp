	
	//Student Improvement Plan 

	var base_url = $('#get_base_url').val();

	$(document).ready(function() {
		//Tiny MCE script when the page loads
		tinymce.init({
			mode : "specific_textareas",
			editor_selector : "myTextEditor",
			plugins: [
				"advlist autolink lists autoresize charmap preview anchor",
				"searchreplace visualblocks code fullscreen",
				"insertdatetime table contextmenu paste moxiemanager"
				],
			toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"
		});
	
		//insert or update the details of tinymce when the user clicks on save button
        $('#update').click(function() {
            tinyMCE.triggerSave();
		
			var flag_val = $('#flag_val').val();

			//if flag is 1 insert improvement plan, if 2 update improvement plan
			if(flag_val == 1) {
				var improvement_plan_insert_update = 'assessment_attainment/improvement_plan/improvement_insert';
			} else {
				var improvement_plan_insert_update = 'assessment_attainment/improvement_plan/improvement_update';
			}
			
            $.post(base_url + improvement_plan_insert_update,
				//:input :hidden used to fetch & store values in text as well as hidden fields
				$(":input,:hidden").serialize(),
				function(result) {			
					if (result) {
						$('#loading').show();
						//modal body - successful message, on data save
							var data_options = '{"text":"Data has been saved successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
							var options = $.parseJSON(data_options);
							noty(options);
							window.location = base_url + 'assessment_attainment/student_threshold/'; 
					} else {
						$('#loading').show();
						//modal body - unsuccessful message, while trying to save data
							var data_options = '{"text":"Error updating data.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
							var options = $.parseJSON(data_options);
							noty(options);
					}					
					$('#loading').hide();				
				//to display the modal			
			}, "html");
        });
    });