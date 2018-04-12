// Model QP JS file
// Base url 
var base_url = $('#get_base_url').val();
// Global variables
var sub_que_counter = 'a';
var temp_id = 0;
var img_counter = 0;
var message_text = true;

// Global Arrays
var main_que_array =  new Array();
var sub_que_array =  new Array();
var uploaded_image_name_array = new Array();


// Function to add sub question for each main questions in Model QP which is inherited from the QP framework.
$('#qp_table_load').on('click','.add_subque', function(){

var co_data;
var topic_data;
var bloom_level;
var entity_val;

var url_data = window.location.href;
var replaced_data = url_data.replace(base_url+'question_paper/manage_model_qp/generate_model_qp/','');

var abbr_val = $(this).attr('abbr');
var main_q_val = $(this).attr('main_q_num');
var abbr_array =  abbr_val.split('_');
//var id = parseInt(abbr_array[1]); // main question number
var id = main_q_val; // main question number

//sub_que_counter = abbr_array[2];// sub question alphabet

/* var entity_array_val = $("#entity_array_val").val();
alert(entity_array_val)
console.log(entity_array_val); */

//sub_que_array.push({index:id, value:1});
var sub_que_count_val = $('#sub_que_count'+main_q_val).val(); 
sub_que_count_val++;

console.log(sub_que_array);
sub_que_array.push({index:id, value:sub_que_count_val});


var main_que_abbr_val = $(this).attr('main_que_abbr');
var main_que_data = main_que_abbr_val.split('_');
var main_que_alphbet = main_que_data[2];

$('#sub_que_count'+id).val(sub_que_count_val);
sub_que_counter = String.fromCharCode(main_que_alphbet.charCodeAt() + 1);

$(this).attr('main_que_abbr','QNo_'+main_que_data[1]+'_'+sub_que_counter);

/*--- These Values fetched from the CIA View*/
	var pgm_id = $('#pgm_id').val();
	var crclm_id = $('#cia_curriculum_id').val();
	var term_id = $('#cia_term_id').val();
	var crs_id = $('#cia_course_id').val();
/**********************************************/


// ajax call for course data and entity data.
$.ajax({type: "POST",
					url: base_url+'question_paper/manage_model_qp/model_qp_data_ajax_call/'+pgm_id+'/'+crclm_id+'/'+term_id+'/'+crs_id+'/'+3,
					dataType: "JSON",
					success: function(msg)
					{
						
						co_data = msg.co_details.length;
						topic_data = msg.topic_details.length;
						bloom_level = msg.bloom_data.length;
						entity_val = msg.qp_entity.length;
						pi_code = msg.pi_list.length;
						
						 console.log(msg.co_details)
						 console.log(msg.bloom_data)
								var sub_que = '<tr id="row_'+id+'_'+sub_que_count_val+'" class="row_'+id+'">';
									 sub_que += '<td style="white-space:nowrap;" class="textwrap">';
									 sub_que += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="ques_nme_'+id+'_'+sub_que_count_val+'" id="ques_nme_'+id+'_'+sub_que_count_val+'" value="Q No '+main_que_data[1]+'-'+sub_que_counter+'" class="input-mini ques_nme_'+id+'" readonly />';
									 sub_que += '<input type="hidden" name="question_name_'+id+'_'+sub_que_count_val+'" id="question_name_'+id+'_'+sub_que_count_val+'" value="Q_No_'+main_que_data[1]+'_'+sub_que_counter+'" class="input-mini question_name_'+id+'" readonly /></td>';
									 sub_que += '<td style="width: 43%;">';
									 sub_que += '<textarea class="required span12 text_area " name="question_'+id+'_'+sub_que_count_val+'" id="question_'+id+'_'+sub_que_count_val+'" rows="3" ></textarea>';
									 sub_que += '</td>';
									 sub_que += '<td>';
									 sub_que += '<button type="button" id="upload-btn'+id+'_'+sub_que_count_val+'" name="upload"  abbr="1" class=" btn btn-success btn-small clearfix test" value=""><i class="icon-upload icon-white"></i></button>';
									 sub_que += '</td>';
							 for(var i =0; i< entity_val;i++ ){
								if(msg.qp_entity[i].entity_id == 11){
									 sub_que += '<td>';
									 sub_que += '<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="co_list_'+id+'_'+sub_que_count_val+'" id="co_list_'+id+'_'+sub_que_count_val+'" class="input-small co_onchange required co_list_data">';
									 sub_que += '<option value="" abbr="Select CO">Select</option>';
							 for(var j=0; j < co_data; j++){
									 sub_que += '<option value="'+msg.co_details[j].clo_id+'" abbr="'+msg.co_details[j].clo_statement+'">'+msg.co_details[j].clo_code+'</option>';
								}
									 sub_que += '</select>';
									 sub_que += '</td>';
							 }else{
							 }
							// }
							// for(var i =0; i< entity_val;i++ ){
							if(msg.qp_entity[i].entity_id == 6){
									 sub_que += '<td>';
									 sub_que += '<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="po_list_'+id+'_'+sub_que_count_val+'" id="po_list_'+id+'_'+sub_que_count_val+'" class="input-mini required po_list_data">';
									 sub_que += '<option value="" abbr="Select CO">Select</option>';
									 sub_que += '</select>';
									 sub_que += '</td>';
							 }else{
							 }
								if(msg.qp_entity[i].entity_id == 10){
									 sub_que += '<td>';
									 sub_que += '<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="topic_list_'+id+'_'+sub_que_count_val+'" id="topic_list_'+id+'_'+sub_que_count_val+'" class="input-small required topic_list_data">';
									 sub_que += '<option value="" abbr="Select Topic">Select</option>';
							for(var j=0; j < topic_data; j++){
									 sub_que += '<option value="'+msg.topic_details[j].topic_id+'" abbr="'+msg.topic_details[j].topic_title+'">'+msg.topic_details[j].topic_title+'</option>';
								}
									 sub_que += '</select>';
									 sub_que += '</td>';
							 }else{
							 }
							// }
							 
							// for(var i =0; i< entity_val;i++ ){
								if(msg.qp_entity[i].entity_id == 23){
									 sub_que += '<td>';
									 sub_que += '<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="bloom_list_'+id+'_'+sub_que_count_val+'" id="bloom_list_'+id+'_'+sub_que_count_val+'" class="input-mini required bloom_list_data">';
									 sub_que += '<option value="" abbr="Select Level">Select</option>';
							for(var k=0; k < bloom_level; k++){
									 sub_que += '<option value="'+msg.bloom_data[k].bloom_id+'" abbr="'+msg.bloom_data[k].bloom_actionverbs+'">'+msg.bloom_data[k].level+'</option>';
								}
									 sub_que += '</select>';
									 sub_que += '</td>';
							 }else{
							 }
							// }
							// for(var i =0; i< entity_val;i++ ){
								if(msg.qp_entity[i].entity_id == 22){
									 sub_que += '<td>';
									 sub_que += '<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="pi_code_'+id+'_'+sub_que_count_val+'" id="pi_code_'+id+'_'+sub_que_count_val+'" class="input-small pi_code_data">';
									 sub_que += '<option value="" abbr="Select PI Code">Select</option>';
									 for(var k=0; k < pi_code; k++){
									 sub_que += '<option value="'+msg.pi_list[k].msr_id+'" abbr="'+msg.pi_list[k].msr_statement+'">'+msg.pi_list[k].pi_codes+'</option>';
								}
									 sub_que += '</select>';
									 sub_que += '</td>';
							 }else{
							 }
							 }
									 sub_que += '<td>';
									 sub_que += '<div class=" input-append">';
									 sub_que += '<input type="text" name="mq_marks_'+id+'_'+sub_que_count_val+'" id="mq_marks_'+id+'_'+sub_que_count_val+'" class="input-mini required mq_marks numeric"/>';
									 sub_que += '<button class="btn btn-inverse error_add" type="button" id="delete_subque_'+id+'_'+sub_que_count_val+'" name="delete_subque_'+id+'_'+sub_que_count_val+'" main_q_num="'+id+'" disabled="disabled" ><i class="icon-plus-sign icon-white"></i></button>';
									 sub_que += '<button class="btn btn-danger delete_'+id+' delete_subque error_add" type="button" id="delete_subque_'+id+'_'+sub_que_count_val+'" name="delete_subque_'+id+'_'+sub_que_count_val+'" abbr="QNo_'+id+'_'+sub_que_count_val+'" sub_que_abbr="'+main_que_data[1]+'"><i class="icon-minus-sign icon-white"></i></button>';
									 sub_que += '</div>';
									 sub_que += '</td>';
									 sub_que += '</tr>';
									 sub_que += '<tr id="img_placing_row_'+id+'_'+sub_que_count_val+'" class="row_'+id+'">';
									 sub_que += '<td id=""></td>';
									 sub_que += '<td id="place_img_'+id+'_'+sub_que_count_val+'" colspan="6"></td>';
									 sub_que += '</tr>';
									 sub_que += '<tr id="img_name_text_fields_'+id+'_'+sub_que_count_val+'" class="row_'+id+'">';
									 sub_que += '<td id="placed_img_name_'+id+'_'+sub_que_count_val+'" class="row_'+id+'" colspan="8"></td>';
									 sub_que += '</tr>';
						//console.log(sub_que);
						var sub_question = $(sub_que);
						sub_question.insertBefore('#sub_que_ref_div_'+id);
						var upload_btn_val = id+'_'+sub_que_count_val;
						register_button(upload_btn_val);
												  
												
											}
				});
	

});
$('#qp_table_load').on('change','.co_onchange', function(){
	var ele_id = $(this).attr('id');
	var co_data = ele_id.split('_');
	var main_q_id = co_data[2];
	var sub_q_id = co_data[3];
	
	var co_id = $(this).val();
	var crclm_id = $('#crclm_id').val();
	var term_id = $('#term_id').val();
	var crs_id = $('#crs_id').val();
	var post_data ={
		'co_id':co_id,
		'crclm_id':crclm_id,
		'term_id':term_id,
		'crs_id':crs_id
	}
	$.ajax({type: "POST",
					url: base_url+'question_paper/manage_model_qp/po_list_data',
					data: post_data,
					dataType: "JSON",
					success: function(msg)
					{
					/* console.log(msg);
						var size = $.trim(msg.length);
						// alert(size)
						var i;
						$('#po_list_'+main_q_id+'_'+sub_q_id).empty();
						var my_ops = $('<option></option>').val('').attr('abbr','Select PO').text('Select');
						$('#po_list_'+main_q_id+'_'+sub_q_id).append(my_ops);
						for(i = 0; i< size; i++){
						my_ops = $('<option></option>').val(msg[i].po_id).attr('abbr',msg[i].po_statement).text(msg[i].po_reference);
						$('#po_list_'+main_q_id+'_'+sub_q_id).append(my_ops);
						} */
						
						console.log(msg);
						var size = $.trim(msg['po_list'].length);
						// alert(size)
						var i;
						$('#po_list_'+main_q_id+'_'+sub_q_id).empty();
						var my_ops = $('<option></option>').val('').attr('abbr','Select PO').text('Select');
						$('#po_list_'+main_q_id+'_'+sub_q_id).append(my_ops);
						for(i = 0; i< size; i++){
						my_ops = $('<option></option>').val(msg['po_list'][i].po_id).attr('abbr',msg['po_list'][i].po_statement).text(msg['po_list'][i].po_reference);
						$('#po_list_'+main_q_id+'_'+sub_q_id).append(my_ops);
						}
						
						var pi_size = $.trim(msg['pi_code_list'].length);
						var k;
						$('#pi_code_'+main_q_id+'_'+sub_q_id).empty();
						var my_ops = $('<option></option>').val('').attr('abbr','Select PI').text('Select');
						$('#pi_code_'+main_q_id+'_'+sub_q_id).append(my_ops);
						for(k = 0; k< pi_size; k++){
						my_ops = $('<option></option>').val(msg['pi_code_list'][k].msr_id).attr('abbr',msg['pi_code_list'][k].msr_statement).text(msg['pi_code_list'][k].pi_codes);
						$('#pi_code_'+main_q_id+'_'+sub_q_id).append(my_ops);
						}
						
					}
				}); 
	
});
$('#qp_table_load').on('click','.delete_subque', function(){

var abbr_val = $(this).attr('sub_que_abbr');
var id_val = this.id.replace('delete_subque_', "");
var main_id = id_val.split('_');
$('#row_'+id_val).remove();
$('#img_placing_row_'+id_val).remove();
$('#img_name_text_fields_'+id_val).remove();

var abbr_array =  id_val.split('_');
var index_id = abbr_array[0];

var sub_que_val = abbr_array[1];
var index_val = functiontofindIndexByKeyValue(sub_que_array, index_id, sub_que_val);

sub_que_array.splice(index_val,1);
console.log(sub_que_array);

// renaming questions
var alpha = 'a' 
$('.ques_nme_'+main_id[0]).each(function(){
var question_id = $(this).attr('id');
$('#'+question_id).val('Q No '+abbr_val+'-'+alpha);
$('#add_subque_'+main_id[0]+'_1').attr('main_que_abbr','QNo_'+abbr_val+'_'+alpha)
alpha = String.fromCharCode(alpha.charCodeAt() + 1);
});
var que_alpha = 'a'
$('.question_name_'+main_id[0]).each(function(){
var question_id = $(this).attr('id');
$('#'+question_id).val('Q_No_'+abbr_val+'_'+que_alpha);
//$('#add_subque_'+main_id[0]).attr('abbr','QNo_'+main_id[0]+'_'+alpha)
que_alpha = String.fromCharCode(que_alpha.charCodeAt() + 1);
});

});


function functiontofindIndexByKeyValue(arraytosearch, key, valuetosearch) {
 
for (var i = 0; i < arraytosearch.length; i++) {

if (arraytosearch[i].index == key && arraytosearch[i].value==valuetosearch) {
	return i;
}
}
return null;
}

// image upload function starts from here

function register_button(value) {
//alert(value);
	var main_que_data = value.split('_');
	var mque_id = main_que_data[0];
	var subque_id = main_que_data[1];
	 
  //alert($(this).attr("abbr"));
 // var cloneimgCntr = cloneCntr_ques-1;
		var image_counter = new Array();
		var image_upload_counter = new Array();
		image_upload_counter.push(1);
		image_counter.push(1);
		
	var id_value = '#upload-btn'+value;
	var btn = $(id_value),
      wrap = document.getElementById('pic-progress-wrap'),
      picBox = document.getElementById('picbox'),
      errBox = document.getElementById('errormsg');
	//alert(picBox);
	//alert(errBox)
	//alert(base_url+'curriculum/tlo_list/image_doc_upload');
  var uploader = new ss.SimpleUpload({
        button: btn,
        url: base_url+'curriculum/tlo_list/image_doc_upload',
        name: 'imgfile',
		btnId: 'upload-btn'+value,
        btnClass: 'upload_btn',
        multiple: true,
        maxUploads: 2,
        maxSize: 50000,
        allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
        accept: 'image/*',
        hoverClass: 'btn-hover',
        focusClass: 'active',
        disabledClass: 'disabled',
        responseType: 'json',
        onExtError: function(filename, extension) {
          alert(filename + ' is not a permitted file type.'+"\n\n"+'Only PNG, JPG, and GIF files are allowed.');
        },
        onSizeError: function(filename, fileSize) {
          alert(filename + ' is too big. (500K max file size)');
        },
        startXHR: function() {
        },   
			
        onComplete: function(filename, response) {
		img_counter++;
			
			var thumb_div  = '<div class="controls span1" id="img_thmb_'+mque_id+'_'+subque_id+'_'+img_counter+'">';
				thumb_div += '<table class=""><tr><td><img src="'+base_url+'/uploads/'+filename+'" class="img-rounded img-thumbnail" />';
				thumb_div += '<i id="romove_image'+mque_id+'_'+subque_id+'_'+img_counter+'" class="icon-remove image_remove img_float_rght cursor_pointer">';
				thumb_div += '</i></td></tr></table></div><div class="img_margin"></div>';
			
			var image = '';
				image +='<input name="image_hidden_'+mque_id+'_'+subque_id+'[]" id="image_hidden_'+mque_id+'_'+subque_id+'_'+img_counter+'" type="hidden" class="input-small" value="'+filename+'"/>';
				
				var newImage = $(image);
				var img_load = $(thumb_div);
				console.log(img_load);
				$('#place_img_'+mque_id+'_'+subque_id).append(img_load);
				$('#placed_img_name_'+mque_id+'_'+subque_id).append(newImage);
			
				
            if (!response) {
              errBox.innerHTML = 'Unable to upload file';
              return;
            }     
            
          }
		 
	});
} 

// function to delete the image
$('#qp_table_load').on('click','.image_remove',function(){
  var btn_id = $(this).attr("id").replace('romove_image','');
  var remove_img = btn_id.split('_');
 // alert(remove_img[0]+'_'+remove_img[1])
	$('#img_thmb_'+btn_id).remove();
	$('#image_hidden_'+btn_id).remove();
  });

  
  // function to set all main question as mandatory for the unit if the unit chosen as mandatory.
  
  $('#qp_table_load').on('change','.main_unit', function(){
	var unit_val = $(this).attr('abbr');
	if($(this).is(":checked")){
		$('.'+unit_val).each(function(){
		//alert('s')
		$(this).prop('checked',true);
		})
	}else{
		$('.'+unit_val).each(function(){
				$(this).prop('checked',false);
				//alert('n')
			})
	}
  });
  
  
// Form validation

	$(document).ready(function(){
		$.validator.addMethod("loginRegex", function(value, element) {
			return this.optional(element) || /^[a-zA-Z 0-9\_\-\s\'\/\,\!\#\$\%\^\&\*\(\)\.\:\;\"\<\>\?]+$/i.test(value);
		}, "Field must contain only letters, spaces, ' or dashes or dot");
		
		$.validator.addMethod("numeric", function(value, element) {
        var regex = /^[0-9\s]+$/; //this is for numeric... you can do any regular expression you like...
        return this.optional(element) || regex.test(value);
    }, "Field must contain only numbers.");
	});	
		$("#add_form_id").validate({
			errorClass: "help-inline font_color",
			errorElement: "span",
			highlight:function(element, errorClass, validClass) {
				$(element).addClass('error');
			},
			errorPlacement : function(error, element) {
        if (element.next().next().is('.error_add')) {
             error.insertAfter(element.parent());
        } 
        else {
             error.insertAfter(element);
        }
		},
			unhighlight: function(element, errorClass, validClass) {
				$(element).removeClass('error');
				$(element).addClass('success');
			}
		});	 
	
  $.validator.addMethod('max_marks_validation', function(value) {
  var maximum_marks = $('#max_marks').val();
  var que_marks = 0;
  $('.mq_marks').each(function() {
			var each_que_marks = $(this).val();
              que_marks = parseInt(que_marks)+parseInt(each_que_marks);
            });	
			
			if( que_marks != maximum_marks) {
			$.validator.messages.max_marks_validation = 'Mismatch in QP Max Marks and Sum of Question Marks ';      
			}
			else
			{
			//$.validator.messages.max_marks_validation = '';
			return( que_marks == maximum_marks);
			}
        },$.validator.messages.max_marks_validation);
  
$('#modal_footer').unbind("click").on('click','#save_data', function(){
		var validation_flag = $('#add_form_id').valid();
			
            // adding rules for inputs with class 'comment'
         $('#qp_title').each(function() {
                $(this).rules("add", 
                    {
						loginRegex: false,
						required : true
                    });
            });
			$('#total_duration').each(function() {
                $(this).rules("add", 
                    {
						//numeric: true,
						required : true,
                    });
            });
			$('#course_name').each(function() {
                $(this).rules("add", 
                    {
						loginRegex: false,
						required : true
                    });
            });
			$('#max_marks').each(function() {
                $(this).rules("add", 
                    {
						numeric: true,
						required : true,
						max_marks_validation: true
                    });
            });
			
			
			$('#qp_notes').each(function() {
                $(this).rules("add", 
                    {
						loginRegex: false,
						required : true
                    });
            });
			
			$('.total_question').each(function() {
                $(this).rules("add", 
                    {
						numeric: true,
						required : true
                    });
            });
			
			$('.attempt_question').each(function() {
                $(this).rules("add", 
                    {
						numeric: true,
						required : true
                    });
            });
			
			$('.text_area').each(function() {
                $(this).rules("add", 
                    {
						loginRegex: false,
						required : true
                    });
            });
			$('.co_list_data').each(function() {
                $(this).rules("add", 
                    {
						loginRegex: true,
						required : true
                    });
            });
			$('.po_list_data').each(function() {
                $(this).rules("add", 
                    {
						loginRegex: true,
						required : true
                    });
            });
			/* $('.topic_list_data').each(function() {
                $(this).rules("add", 
                    {
						loginRegex: true,
						required : true
                    }); 
            });*/
			$('.bloom_list_data').each(function() {
                $(this).rules("add", 
                    {
						loginRegex: true,
						required : true
                    });
            });
			$('.pi_code_data').each(function() {
                $(this).rules("add", 
                    {
						loginRegex: true,
						required : false
                    });
            });
			$('.mq_marks').each(function() {
                $(this).rules("add", 
                    {
						numeric: true,
						required : true
                    });
            });

var qp_values = $('#sub_que_count1').val();
var paramJSON = JSON.stringify(sub_que_array);
$('#array_data').val(paramJSON);
	if(validation_flag == true){
	$('#loading').attr('style','z-index: 9999 !important');
	$('#loading').show();
		$.ajax({
					type: "POST",
					url: base_url+'question_paper/manage_model_qp/cia_add_qp_data/3',
					data: $('#add_form_id').serialize(),
					dataType : 'JSON',
					success: function(success_msg) {
						console.log(success_msg);
						var ao_type_id = $('#ao_type_id').val();
						if(success_msg){
							$('#qpd_id_'+ao_type_id).val(success_msg);
							$('#cia_add_qp_modal').modal('hide');
							$('#loading').hide();
						}
						}
		});
	}
	else{
	}
	
//$('#add_form_id').submit();
});



/////////////////////////////////////////////////
function tool_tip_on(value){
var opt_val=$( "#"+value+" option:selected" ).attr('abbr');
$('#'+value).attr('data-original-title',opt_val);
$('#'+value).tooltip('show');
}
$('.co_list_data').on('change',function(){
if($(this).val() !=''){
$(this).tooltip('destroy');
var id = $(this).attr('id');
var opt_val=$( "#"+id+" option:selected" ).attr('abbr');
$(this).attr('data-original-title',opt_val);
$(this).tooltip('hide');
}else{
$(this).attr('data-original-title','Select CO');
}

});

$('#qp_table_load').on('change','.po_list_data',function(){
if($(this).val() !=''){
$(this).tooltip('destroy');
var id = $(this).attr('id');
var opt_val=$( "#"+id+" option:selected" ).attr('abbr');
$(this).attr('data-original-title',opt_val);
$(this).tooltip('hide');
}else{
$(this).attr('data-original-title','Select CO');
}

});

$('#qp_table_load').on('change','.bloom_list_data',function(){
if($(this).val() !=''){
$(this).tooltip('destroy');
var id = $(this).attr('id');
var opt_val=$( "#"+id+" option:selected" ).attr('abbr');
$(this).attr('data-original-title',opt_val);
$(this).tooltip('hide');
}else{
$(this).attr('data-original-title','Select CO');
}

});

$('#qp_table_load').on('change','.topic_list_data',function(){
if($(this).val() !=''){
$(this).tooltip('destroy');
var id = $(this).attr('id');
var opt_val=$( "#"+id+" option:selected" ).attr('abbr');
$(this).attr('data-original-title',opt_val);
$(this).tooltip('hide');
}else{
$(this).attr('data-original-title','Select CO');
}

});

$('#main_question_add_btn').on('click','.add_main_que', function(){

var co_data;
var topic_data;
var bloom_level;
var entity_val;
/*--- These Values fetched from the CIA View*/
	var pgm_id = $('#pgm_id').val();
	var crclm_id = $('#cia_curriculum_id').val();
	var term_id = $('#cia_term_id').val();
	var crs_id = $('#cia_course_id').val();
/**********************************************/
		
//var url_data = window.location.href;
//var replaced_data = url =base_url+'question_paper/manage_model_qp/generate_model_qp'+'/'+pgm_id+'/'+crclm_id+'/'+term_id+'/'+crs_id+'/'+3;

//alert(replaced_data);
var total_counter = $('#main_que_counter').val();

var unit_count = $(this).attr('unit_count');
var que_counter = $('#total_question_'+unit_count).val();
que_counter++;
total_counter++;
main_que_array.push(total_counter);
sub_que_array.push({index:total_counter, value:1});

console.log(sub_que_array);

$('#main_que_counter').val(total_counter);
$('#main_que_array').val(main_que_array);
$('#total_question_'+unit_count).val(que_counter);
//var unit_val = $(this).attr('unit_data');

// ajax call for course data and entity data.
$.ajax({type: "POST",
					url: base_url+'question_paper/manage_model_qp/model_qp_data_ajax_call/'+pgm_id+'/'+crclm_id+'/'+term_id+'/'+crs_id+'/'+3,
					dataType: "JSON",
					success: function(msg)
					{
						
						co_data = msg.co_details.length;
						topic_data = msg.topic_details.length;
						bloom_level = msg.bloom_data.length;
						entity_val = msg.qp_entity.length;
						pi_code = msg.pi_list.length;
						
						 console.log(msg.co_details)
						 console.log(msg.bloom_data)
								var main_que = '<tr id="row_'+total_counter+'_1" abbr="main_que_'+total_counter+'" class="row_'+total_counter+'">';
									 main_que += '<td style="white-space:nowrap;" class="textwrap">';
									 main_que += '<input type="checkbox" name="unit_'+unit_count+'_'+total_counter+'" id="unit_'+unit_count+'_'+total_counter+'" class="unit_'+unit_count+'" value="1"></input> &nbsp;';
									 main_que += '<input type="text" name="ques_nme_'+total_counter+'_1" id="ques_nme_'+total_counter+'_1" value="Q No '+que_counter+'-a" class="input-mini ques_nme_'+total_counter+'" readonly />';
									 main_que += '<input type="hidden" name="question_name_'+total_counter+'_1" id="question_name_'+total_counter+'_1" value="Q_No_'+que_counter+'_a" class="input-mini question_name_'+total_counter+'" readonly /></td>';
									 main_que += '<td style="width: 43%;">';
									 main_que += '<textarea class="required span12 text_area " name="question_'+total_counter+'_1" id="question_'+total_counter+'_1" rows="3" ></textarea>';
									 main_que += '<input type="hidden" name="sub_que_count'+total_counter+'" id="sub_que_count'+total_counter+'" value="1"/>';
									 main_que += '</td>';
									 main_que += '<td>';
									 main_que += '<button type="button" id="upload-btn'+total_counter+'_1" name="upload"  abbr="1" class=" btn btn-success btn-small clearfix test" value=""><i class="icon-upload icon-white"></i></button>';
									 main_que += '</td>';
							 for(var i =0; i< entity_val;i++ ){
								if(msg.qp_entity[i].entity_id == 11){
									 main_que += '<td>';
									 main_que += '<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="co_list_'+total_counter+'_1" id="co_list_'+total_counter+'_1" class="input-small co_onchange required co_list_data">';
									 main_que += '<option value="" abbr="Select CO">Select</option>';
							 for(var j=0; j < co_data; j++){
									 main_que += '<option value="'+msg.co_details[j].clo_id+'" abbr="'+msg.co_details[j].clo_statement+'">'+msg.co_details[j].clo_code+'</option>';
								}
									 main_que += '</select>';
									 main_que += '</td>';
							 }else{
							 }
							// }
							// for(var i =0; i< entity_val;i++ ){
							if(msg.qp_entity[i].entity_id == 6){
									 main_que += '<td>';
									 main_que += '<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="po_list_'+total_counter+'_1" id="po_list_'+total_counter+'_1" class="input-mini required po_list_data">';
									 main_que += '<option value="" abbr="Select CO">Select</option>';
									 main_que += '</select>';
									 main_que += '</td>';
							 }else{
							 }
								if(msg.qp_entity[i].entity_id == 10){
									 main_que += '<td>';
									 main_que += '<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="topic_list_'+total_counter+'_1" id="topic_list_'+total_counter+'_1" class="input-small required topic_list_data">';
									 main_que += '<option value="" abbr="Select Topic">Select</option>';
							for(var j=0; j < topic_data; j++){
									 main_que += '<option value="'+msg.topic_details[j].topic_id+'" abbr="'+msg.topic_details[j].topic_title+'">'+msg.topic_details[j].topic_title+'</option>';
								}
									 main_que += '</select>';
									 main_que += '</td>';
							 }else{
							 }
							// }
							 
							// for(var i =0; i< entity_val;i++ ){
								if(msg.qp_entity[i].entity_id == 23){
									 main_que += '<td>';
									 main_que += '<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="bloom_list_'+total_counter+'_1" id="bloom_list_'+total_counter+'_1" class="input-mini required bloom_list_data">';
									 main_que += '<option value="" abbr="Select Level">Select</option>';
							for(var k=0; k < bloom_level; k++){
									 main_que += '<option value="'+msg.bloom_data[k].bloom_id+'" abbr="'+msg.bloom_data[k].bloom_actionverbs+'">'+msg.bloom_data[k].level+'</option>';
								}
									 main_que += '</select>';
									 main_que += '</td>';
							 }else{
							 }
							// }
							// for(var i =0; i< entity_val;i++ ){
								if(msg.qp_entity[i].entity_id == 22){
									 main_que += '<td>';
									 main_que += '<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="pi_code_'+total_counter+'_1" id="pi_code_'+total_counter+'_1" class="input-small pi_code_data">';
									 main_que += '<option value="" abbr="Select PI Code">Select</option>';
									 for(var k=0; k < pi_code; k++){
									 main_que += '<option value="'+msg.pi_list[k].msr_id+'" abbr="'+msg.pi_list[k].msr_statement+'">'+msg.pi_list[k].pi_codes+'</option>';
								}
									 main_que += '</select>';
									 main_que += '</td>';
							 }else{
							 }
							 }
									 main_que += '<td>';
									 main_que += '<div class=" input-append">';
									 main_que += '<input type="text" name="mq_marks_'+total_counter+'_1" id="mq_marks_'+total_counter+'_1" class="input-mini required mq_marks numeric"/>';
									 main_que += '<button class="btn btn-primary add_subque error_add" type="button" id="add_subque_'+total_counter+'_1" name="add_subque_'+total_counter+'_1" abbr="QNo_'+que_counter+'_a" main_que_abbr="QNo_'+que_counter+'_a" main_q_num="'+total_counter+'"><i class="icon-plus-sign icon-white"></i></button>';
									 main_que += '<button class="btn btn-danger del_main_que error_add" type="button" id="del_main_que_'+total_counter+'_1" name="del_main_que_'+total_counter+'_1" abbr="QNo_'+que_counter+'_1" del_main_count ="'+unit_count+'" del_main_que ="row_'+total_counter+'"><i class="icon-minus-sign icon-white"></i></button>';
									 main_que += '</div>';
									 main_que += '</td>';
									 main_que += '</tr>';
									 main_que += '<tr id="img_placing_row_'+total_counter+'_1" class="row_'+total_counter+'">';
									 main_que += '<td id=""></td>';
									 main_que += '<td id="place_img_'+total_counter+'_1" colspan="6"></td>';
									 main_que += '</tr>';
									 main_que += '<tr id="img_name_text_fields_'+total_counter+'_1" class="row_'+total_counter+'">';
									 main_que += '<td id="placed_img_name_'+total_counter+'_1" colspan="8"></td>';
									 main_que += '</tr>';
									 main_que += '<tr id="sub_que_ref_div_'+total_counter+'" class="row_'+total_counter+'"></tr>';
						//console.log(sub_que);
						var sub_question = $(main_que);
						sub_question.insertBefore('#ref_add_main');
						var upload_btn_val = total_counter+'_1';
						register_button(upload_btn_val);
												  
												
											}
				});
//alert($(this).attr('unit_data'));
});

$('#qp_table_load').on('click','.del_main_que',function(){
var i;
var del_count_val = $(this).attr('del_main_count');
var del_main_que_class = $(this).attr('del_main_que');
var dele_main_que_data = del_main_que_class.split('_');
var main_que_num = parseInt(dele_main_que_data[1]);
//alert(main_que_num);
var total_que_val = $('#total_question_'+del_count_val).val();
if(total_que_val > 1){
			var main_que_index = $.inArray(main_que_num,main_que_array);
			main_que_array.splice(main_que_index,1);
			$('#main_que_array').val(main_que_array);
			sub_que_array = delete_main_question(sub_que_array, main_que_num); // function call to delete the main question values from the array
			console.log(sub_que_array);
			$('.'+del_main_que_class).remove();
			total_que_val--;
			$('#total_question_'+del_count_val).val(total_que_val);
			
			//renaming text fields 
			
			var mq = 1;
			//alert(main_que_array.length);
			for(i=0; i<main_que_array.length; i++){
			var alphabet = 'a';
			var alphabet_one = 'a'; 
			$('.ques_nme_'+main_que_array[i]).each(function(){
				$(this).val('Q No '+mq+'-'+alphabet);
				$('#add_subque_'+main_que_array[i]+'_1').attr('main_que_abbr','QNo_'+mq+'_'+alphabet);
				alphabet = String.fromCharCode(alphabet.charCodeAt() + 1);
			});
			
			$('.question_name_'+main_que_array[i]).each(function(){
				$(this).val('Q_No_'+mq+'_'+alphabet_one);
				alphabet_one = String.fromCharCode(alphabet_one.charCodeAt() + 1);
			});
			
			$('.delete_'+main_que_array[i]).each(function(){
				$(this).attr('sub_que_abbr', mq);
			});
			
			mq++;
			}
}else{

}

});


function delete_main_question(arraytosearch, key) {
var i = 0;
	while( i < arraytosearch.length ) {
		if (arraytosearch[i].index == key ) {
			sub_que_array.splice(i,1);
			i=0;
		}else{
			i++;
		}
	}
return arraytosearch;
}

