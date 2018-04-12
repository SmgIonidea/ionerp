	//List view JS functions
	var base_url = $('#get_base_url').val();
	var flag1 = true;
	
	//Form validation rules are defined and checked before it is submitted to controller for update
	$.validator.addMethod("onlyNumbers", function(value, element) {
	    return (value.match(/^([0-9])*$/));
	}, "This field should contain only Numbers.");
	
	$.validator.addMethod("alpha_dash", function(value, element) {
        return this.optional(element) || /^[a-zA-Z]+[a-z0-9_ \-]*$/i.test(value); 
    }, "This field must start with letters followed by numbers, spaces, underscore or dashes.");

	$.validator.addMethod("qpRegex", function(value, element) {
		return this.optional(element) || /^[a-zA-Z0-9\_\,\!\.\%\&\-\s\:\;\(\/)']+$/i.test(value);
	}, "Field must contain only letters, numbers, spaces, underscore, colon, semicolon, round bracket, forward slash, ' or dashes.");
			
	$.validator.addMethod("onlyDigit", function(value, element) {
		return this.optional(element) || /^[0-9\.\_]+$/i.test(value);
	}, "This field must contain only Numbers.");

	$("#qp_frmwrk_form").validate({
		errorClass: "help-inline font_color",
		errorElement: "span",
		highlight: function(element, errorClass, validClass) {
			$(element).parent().parent().addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parent().parent().removeClass('error');
			$(element).parent().parent().addClass('success');
		},errorPlacement: function(error, element) {
			var num = element.attr("abbr");
			if(element.attr("name") == ("level_percent_"+num) ){
				error.appendTo("#bloom_percent_error_"+num);
			}else{
				error.insertAfter(element);
			}
		}
	});
		
	$('.save_qp_data').on('click', function(event) {
		$('#qp_frmwrk_form').validate();			
			// adding rules for inputs with class 'comment'
		$(".question_no").each(function() {	
			$(this).rules("add", 
				{
					onlyDigit: true
				});
		});
		$('.qp_unit').each(function() {
			$(this).rules("add", 
				{					
					qpRegex: true
				});
		});
		$('.qp_unit_marks').each(function() {
			$(this).rules("add", 
				{					
					onlyDigit:true
				});
		});
		$('.question_marks').each(function() {
			$(this).rules("add", 
				{					
					onlyDigit:true
				});
		});			 
	});	
	
	
	$(document).ready(function() {
		$('#section_generate').attr('disabled',true);
		$('#gen_sub_section').attr('disabled',true);
	});
	
	$('.enable_section_gen').change(function(){
		var prog_type = $('#program_type').val();
		var section = $('#section').val();
		var section_hidden = $('#section_hidden').val();
		var grand_total = Number($('#grand_total').val());
		var grand_total_hidden = Number($('#grand_total_hidden').val());
		var max_marks = Number($('#max_marks').val());
		if( (prog_type != '') && (section != '') && (grand_total != '') && (max_marks != '') && (grand_total < max_marks) ) {
			$('#marks_exceed_error').css("color","red");
			$('#marks_exceed_error').html("Max Attemptable Marks exceeds Grand Total");
			$('#section_generate').attr('disabled',true);
			$('#gen_sub_section').attr('disabled',true);
			flag1 = false;
		}
		else if ( (prog_type != '') && (section != '') && (grand_total != '') && (max_marks != '') && (grand_total >= max_marks ) ) {
			$('#marks_exceed_error').html("");
			flag1 = true;
			if( (section != section_hidden) || (grand_total != grand_total_hidden) ) {
				$('#clear_question_section').val('1');
				$('#section_generate').attr('disabled',false);
				$('#gen_sub_section').attr('disabled',false);
			}
		}
	});		
	
	$("#hint a").tooltip();
	var currentID;
	//Function to fetch the current (event actioned) program type id.
	function currentIDSt(id)
	{
		currentID=id;
		active_status='1';
		deactive_status='0';
	}
	
	$('.get_id').live('click', function(e)
	{
		data_val = $(this).attr('id');
		table_row = $(this).closest("tr").get(0);
	});

	
	/* Function is to delete qp framework by sending the qpf id to controller.
	* @param - qpf id.
	* @returns- updated list view.
	*/
	$('.delete_qp').click(function(e) {
		e.preventDefault();
		var base_url = $('#get_base_url').val();
		var post_data = {
			'qpf_id': data_val,
		}
		$.ajax({type: "POST",
			url: base_url+'question_paper/manage_qp_framework/delete_qp',
			data: post_data,
			datatype: "JSON",
			success: function(msg)
			{
				var oTable = $('#example').dataTable();
				oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
			}
		});
	});
			
//----------------------------------------------------------------------------//

	//Author : Mritunjay
	$('#section_generate').on('click', function(){
		var clear_val = $('#clear_question_section').val();
		if( clear_val == 1) {
			$('#myModal_clear_question_distribution').modal('show');
		}
	});
	
	$('.clear_distribution').click(function(){
		$('#qp_section_data').empty();
		var section_val = $('#section').val();
		var grand_total_val = $('#grand_total').val();
		var i;
		var sections;
		var units;
		for( i = 1; i <= section_val; i++){
			sections  = '<tr id="section_data_'+i+'" class="info">';
			sections += '<td><center>';
			sections += '<input type="text"  name ="no_section_'+i+'" id="no_section_'+i+'" abbr="'+i+'" class="input-medium required qp_unit alpha_dash" />';
			sections += '</center></td>';
			sections += '<td><center>';
			sections += '<input  type="text" name ="question_'+i+'" id="question_'+i+'" abbr="'+i+'" maxlength=2 class="input-mini required question_no onlyNumbers" />';
			sections += '</center></td>';
			sections += '<td><center>';
			sections += '<input type="text"  name ="marks_'+i+'" id="marks_'+i+'" abbr="'+i+'" maxlength=3 class="input-mini required qp_unit_marks qp_unit_marks_val onlyNumbers" />';
			sections += '</center></td></tr>';
			sections += '<tr id="sub_section_data_'+i+'"></tr>';
			units = $(sections);
			$('#qp_section_data').append(units);
		}
		$('#grand_total_marks').val('0 / '+grand_total_val);
		$('#actual_total_percent').val('0 / 100');
		$('.level_percent').each(function(){
			$(this).val('0');
		});
	});
	
	$('#qp_section_data').on('change','.qp_unit_marks_val',function(){
		ini_grand_total = 0;
		
		$('.qp_unit_marks_val').each(function(){
			if(parseInt($(this).val()))
				ini_grand_total = parseInt(ini_grand_total) + parseInt($(this).val()); 
		});
		$('#grand_total_marks').val(ini_grand_total+' / '+$('#grand_total').val());
	});
	
	$('.restore_data').click(function(){
		location.reload();
	});

	$('#gen_sub_section').on('click',function(){
		var grand_total_mrks = $('#grand_total').val();
		var question_array = new Array();
		var marks_array = new Array();
		var grd_total = 0;
		var j = 1;
		$('.question_no').each(function(){
			var question_val = $('#question_'+j).val();
			if(question_val){
			question_array.push(question_val);
			}
			marks_array.push($('#marks_'+j).val());
			if($('#marks_'+j).val()){
			grd_total = parseInt(grd_total)+parseInt($('#marks_'+j).val());
			}else{
			grd_total;
			} 
			j++;
		});

		var t=1;
		var sum;
		var array_size = question_array.length;
		var n, m, k=1;

		$('.tb_sub_head').each(function(){
		$(this).remove();
		});
		$('.sub_section').each(function(){
		$(this).remove();
		});
		 
		if(grd_total == grand_total_mrks){
		for(n = 0 ; n < array_size; n++ ){
			if(($('#marks_'+(n+1)).val()) && ($('#question_'+(n+1)).val())){
			var mark_distribution = (parseInt(marks_array[n])/parseInt(question_array[n]))
			var table_head = '<tr id="tb_head_'+k+'" class="tb_sub_head"><th><center>Question No.</center></th><th><center>Sl No</center></th><th><center>Question Marks</center></th></tr>';
			var tb_heading = $(table_head);
			$(tb_heading).insertAfter('#section_data_'+k);
			
			for(m = 1 ; m <= question_array[n]; m++ ){
					
				var main_question_section  ='<tr id="sub_section_'+k+'" class="sub_section" ><td><center><input type="text" name ="main_question_num_'+k+'_'+m+'" id="main_question_num_'+k+'_'+m+'" abbr="'+m+'" class="input-medium required main_questions"  value="Q No '+t+'" readonly/></center></td>';
					main_question_section += '<td><center>';
					main_question_section += m;
					main_question_section += '</center></td>';
					main_question_section += '<td><center>';
					main_question_section += '<input type="text" name ="que_marks_'+k+'_'+m+'" id="que_marks_'+k+'_'+m+'" maxlength="3" abbr="'+m+'" class="input-mini required question_marks" value ="'+parseInt(mark_distribution)+'"/>';
					main_question_section += '</center></td></tr>';
				var main_questions = $(main_question_section);
				$(main_questions).insertBefore('#sub_section_data_'+k);
				t++;
			}
			k++;
		}else{
			$('#myModal_qs_marks_warning').modal('show');
		}
		}
			$('#grand_total_marks').val(grd_total+' / '+grand_total_mrks);
			$("#grand_total_marks").css({"border-color": "green"});
		}else{
			$('#myModal_total_marks_warning').modal('show');
			$('#grand_total_marks').val(grd_total+' / '+grand_total_mrks);
			$("#grand_total_marks").css({"border-color": "red"});
		}

	});		
	
	$('#program_type').on('change', function(){
		var pgm_type_val = $('#program_type').val();
		if(pgm_type_val){
			var pgm_type = $("#program_type option:selected").text();
			$('#qp_title').val(pgm_type+' TEE fff Framework');
		}else{
			$('#qp_title').val('');
		}
	});
	
	$('.level_percent').on('change',function(){
		percent_total = 0;
		
		$('.level_percent').each(function(){
			if(parseInt($(this).val()))
				percent_total = parseInt(percent_total) + parseInt($(this).val()); 
		});
		$('#actual_total_percent').val(percent_total+' / 100');
	});
	
	/*
	Function to check the marks distribution of QPF.
	*/	
	$('#qp_data_save').on('click',function(){
		var form_valid = $('#qp_frmwrk_form').valid();
		var section_cnt = $('#section').val();
		myArray = new Array();
		for(var i=1;i<=section_cnt;i++){
			if($('#no_section_'+i).val() != '') {
				myArray[i] = ($('#no_section_'+i).val()).toLowerCase();
			}
		}
		section_duplicate = 0;
		for (var i = 0; i < myArray.length; i++) 
		{
			for (var j = 0; j < myArray.length; j++) 
			{
				if (i != j) 
				{
					if (myArray[i] == myArray[j]) 
					{
						section_duplicate = 1;
						break;
					}
				}
			}
		}
		if(section_duplicate == 1) {
			$('#section_duplicate').modal('show');
		}
		if( form_valid && flag1 && (section_duplicate == 0)) {
			var grand_total = $('#grand_total').val();
			var flag = new Array();
			var section_name = new Array();
			var i = 0;
			var j;
			var sub_que_marks;
			var section_marks_total = 0;
			$('.question_no').each(function(){
					var sub_sec_total = 0;
					var abbr = $(this).attr('abbr');
					var sub_que = $(this).val();
					var section_marks = $('#marks_'+abbr).val();
					
					
					for ( i =1; i <= sub_que; i++){
						sub_que_marks = $('#que_marks_'+abbr+'_'+i).val();
						sub_sec_total = parseInt(sub_sec_total)+parseInt(sub_que_marks);
					}
					
					section_marks_total = parseInt(section_marks_total) + parseInt(section_marks);
					
					if( sub_sec_total != section_marks){
						flag.push(abbr);
					}else{
						flag;
					}
			});
			
			var percent_total = 0 ;
			$('.level_percent').each(function(){
				percent_total = parseInt(percent_total) + parseInt($(this).val()); 
			});
		 
			if(section_marks_total != grand_total){
					$("#grand_total_marks").val(section_marks_total+' / '+grand_total);
					$("#grand_total_marks").css({"border-color": "red"});
					$('#myModal_grand_total_warning').modal('show');
			}else{
				var flag_size = flag.length;
				if(  flag_size != 0){
					for(j = 0 ; j < flag_size ; j++){
						section_name.push($('#no_section_'+flag[j]).val());
					}
				}
				if(  flag_size != 0){
					$('#section_name_value').html(section_name);
					$('#myModal_total_marks_section_warning').modal('show');
				}
				else{
					if(percent_total !=100){ 
						$('#myModal_bloom_distribution_warning').modal('show');
					}else{
						$('#qp_frmwrk_form').submit();
					}
				}
			}
		}
	});
