// Model QP JS file
// Base url 
var base_url = $('#get_base_url').val();
// Global variables
var sub_que_counter = 'a';
var temp_id = 0;
var img_counter = 0;
var message_text = true;

// Global Arrays
var sub_que_array =  new Array();
var temp_array =  new Array();
var temp_val = new Array();
var uploaded_image_name_array = new Array();

// Function to add sub question for each main questions in Model QP which is inherited from the QP framework.
$('.add_subque').on('click', function(){

var co_data;
var topic_data;
var bloom_level;
var entity_val;

var url_data = window.location.href;
var replaced_data = url_data.replace(base_url+'question_paper/manage_model_qp/generate_model_qp/','');



						
								
						// console.log(msg.topic_details);
						// console.log(msg.bloom_data);
						// console.log(msg.qp_entity);

var abbr_val = $(this).attr('abbr');
var abbr_array =  abbr_val.split('-');
var id = abbr_array[0]; // main question number
var q_id = id.split(' ');
id = q_id[2];
sub_que_counter = abbr_array[1];// sub question alphabet


var entity_array_val = $("#entity_array_val").val();
console.log(entity_array_val);

//sub_que_array.push({index:id, value:1});

var sub_que_count_val = $('.sub_que_count'+id+':last').val(); 

sub_que_count_val++;

sub_que_array.push({index:id, value:sub_que_count_val});

$('.sub_que_count'+id+':last').val(sub_que_count_val);
sub_que_counter = String.fromCharCode(sub_que_counter.charCodeAt() + 1);

$(this).attr('abbr','Q No '+id+'-'+sub_que_counter);


// ajax call for course data and entity data.
$.ajax({type: "POST",
					url: base_url+'question_paper/manage_model_qp/model_qp_data_ajax_call/'+replaced_data,
					dataType: "JSON",
					success: function(msg)
					{
						
						co_data = msg.co_details.length;
						topic_data = msg.topic_details.length;
						bloom_level = msg.bloom_data.length;
						entity_val = msg.qp_entity.length;
						pi_code = msg.pi_list.length;
						
						 console.log(msg.bloom_data)
						 console.log(bloom_level)
								var sub_que = '<tr id="row_'+id+'_'+sub_que_count_val+'">';
									 sub_que += '<td style="white-space:nowrap;" class="textwrap">';
									 sub_que += '&nbsp&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="ques_nme_'+id+'_'+sub_que_count_val+'" id="ques_nme_'+id+'_'+sub_que_count_val+'" value="Q No '+id+'-'+sub_que_counter+'" class="input-mini ques_nme_'+id+'" readonly />';
									 sub_que += '<input type="hidden" name="question_name_'+id+'_'+sub_que_count_val+'" id="question_name_'+id+'_'+sub_que_count_val+'" value="Q_No_'+id+'_'+sub_que_counter+'" class="input-mini question_name_'+id+'" readonly /></td>';
									 sub_que += '<td style="width: 43%;">';
									 sub_que += '<textarea class="required text_area" name="question_'+id+'_'+sub_que_count_val+'" id="question_'+id+'_'+sub_que_count_val+'" style="margin: 0px; width: 551px; height: 40px;" ></textarea>';
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
									 sub_que += '<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="po_list_'+id+'_'+sub_que_count_val+'" id="po_list_'+id+'_'+sub_que_count_val+'" class="input-small required po_list_data">';
									 sub_que += '<option value="" abbr="Select PO">Select</option>';
							// for(var j=0; j < topic_data; j++){
									 // sub_que += '<option value="'+msg.topic_details[j].topic_id+'">'+msg.topic_details[j].topic_title+'</option>';
								// }
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
									 sub_que += '<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="bloom_list_'+id+'_'+sub_que_count_val+'" id="bloom_list_'+id+'_'+sub_que_count_val+'" class="input-small required bloom_list_data">';
									 sub_que += '<option value="" abbr="Select Bloom\'s Level">Select</option>';
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
									 sub_que += '<input type="text" name="mq_marks_'+id+'_'+sub_que_count_val+'" id="mq_marks_'+id+'_'+sub_que_count_val+'" class="input-mini required mq_marks"/>';
									 sub_que += '<button class="btn btn-danger delete_subque error_add" type="button" id="delete_subque_'+id+'_'+sub_que_count_val+'" name="delete_subque_'+id+'_'+sub_que_count_val+'" abbr="QNo_'+id+'_'+sub_que_count_val+'"><i class="icon-minus-sign icon-white"></i></button>';
									 sub_que += '</div>';
									 sub_que += '</td>';
									 sub_que += '</tr>';
									 sub_que += '<tr id="img_placing_row_'+id+'_'+sub_que_count_val+'">';
									 sub_que += '<td id=""></td>';
									 sub_que += '<td id="place_img_'+id+'_'+sub_que_count_val+'" colspan="6"></td>';
									 sub_que += '</tr>';
									 sub_que += '<tr id="img_name_text_fields_'+id+'_'+sub_que_count_val+'">';
									 sub_que += '<td id="placed_img_name_'+id+'_'+sub_que_count_val+'" colspan="8"><input name="image_count_'+id+'_'+sub_que_count_val+'" id="image_count_'+id+'_'+sub_que_count_val+'" type="hidden" class="input-small" value="0"/></td>';
									 sub_que += '</tr>';
						//console.log(sub_que);
						var sub_question = $(sub_que);
						sub_question.insertBefore('.sub_que_ref_div_'+id+':last');
						var upload_btn_val = id+'_'+sub_que_count_val;
						register_button(upload_btn_val);
												  
												
											}
				});
	

});

$('#qp_unit_table').on('click','.delete_subque', function(){

var abbr_val = $(this).attr('abbr');
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
$('#'+question_id).val('Q No '+main_id[0]+'-'+alpha);
$('#add_subque_'+main_id[0]+'_1').attr('abbr','Q No '+main_id[0]+'-'+alpha)
alpha = String.fromCharCode(alpha.charCodeAt() + 1);
});

var que_alpha = 'a'
$('.question_name_'+main_id[0]).each(function(){
var question_id = $(this).attr('id');
$('#'+question_id).val('Q_No_'+main_id[0]+'_'+que_alpha);
//$('#add_subque_'+main_id[0]).attr('abbr','QNo_'+main_id[0]+'_'+alpha)
que_alpha = String.fromCharCode(que_alpha.charCodeAt() + 1);
});

});


$('.qp_table').on('change','.co_onchange', function(){
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
	};
					$.ajax({type: "POST",
					url: base_url+'question_paper/manage_model_qp/po_list_data',
					data: post_data,
					dataType: "JSON",
					success: function(msg)
					{               /*                                                      
                                                 var size_bl = $.trim(msg.bl_result.length);
                                                var i;
                                                $('#bloom_list_' + main_q_id + '_' + sub_q_id).empty();
                                                var my_ops = '';//$('<option></option>').val('').attr('abbr','Select PO').text('Select');
                                                $('#bloom_list_' + main_q_id + '_' + sub_q_id).append(my_ops);
                                                for (i = 0; i < size_bl; i++) {
                                                      my_ops = $('<option></option>').val(msg.bl_result[i]['bloom_id']).attr('abbr', msg.bl_result[i]['bloom_actionverbs']).attr('title', msg.bl_result[i]['level'] + ' || ' + msg.bl_result[i]['learning'] + ' || ' + msg.bl_result[i]['bloom_actionverbs'] ).text(msg.bl_result[i]['level']);
                                                    $('#bloom_list_' + main_q_id + '_' + sub_q_id).append(my_ops);
                                                }
                                                $('#bloom_list_' + main_q_id + '_' + sub_q_id).multiselect('rebuild');  */
                                                
//                                                var size = $.trim(msg['bl_result'].length);
//                                                $('#bloom_list'+main_q_id+'_'+sub_q_id).empty();
//						var my_ops = $('<option></option>').val('').attr('abbr','Select PO').text('Select');
//						$('#bloom_list'+main_q_id+'_'+sub_q_id).append(my_ops);
//						for(i = 0; i< size; i++){
//						my_ops = $('<option></option>').val(msg['po_list'][i].po_id).attr('abbr',msg['po_list'][i].po_statement).text(msg['po_list'][i].po_reference);
//						$('#bloom_list'+main_q_id+'_'+sub_q_id).append(my_ops);
//						}
                                                
                                               // console.log(msg);
                                              
                                   /*              if(msg.entity_data[0]['qpf_config'] == 1){
												var size_po = $.trim(msg.po_result.length);
                                               var i;
                                                $('#po_list_' + main_q_id + '_' + sub_q_id).empty();
                                                var my_ops = '';//$('<option></option>').val('').attr('abbr','Select PO').text('Select');
                                                $('#po_list_' + main_q_id + '_' + sub_q_id).append(my_ops);
                                                for (i = 0; i < size_po; i++) {
                                                    my_ops = $('<option></option>').val(msg.po_result[i]['po_id']).attr('abbr', msg.po_result[i]['po_statement']).attr('title', msg.po_result[i]['po_reference'] + '.' + msg.po_result[i]['po_statement']).text(msg.po_result[i]['po_reference']);
                                                    $('#po_list_' + main_q_id + '_' + sub_q_id).append(my_ops);
                                                }
                                                $('#po_list_' + main_q_id + '_' + sub_q_id).multiselect('rebuild');
                                            }  */
                                            
                                            
//                                                var size = $.trim(msg['po_list'].length);
//						var i;
//						$('#po_list_'+main_q_id+'_'+sub_q_id).empty();
//						var my_ops = $('<option></option>').val('').attr('abbr','Select PO').text('Select');
//						$('#po_list_'+main_q_id+'_'+sub_q_id).append(my_ops);
//						for(i = 0; i< size; i++){
//						my_ops = $('<option></option>').val(msg['po_list'][i].po_id).attr('abbr',msg['po_list'][i].po_statement).text(msg['po_list'][i].po_reference);
//						$('#po_list_'+main_q_id+'_'+sub_q_id).append(my_ops);
//						}
                                                
//						var pi_size = $.trim(msg.pi_result.length);
//						var k;
//						$('#pi_code_'+main_q_id+'_'+sub_q_id).empty();
//						var my_ops = $('<option></option>').val('').attr('abbr','Select PO').text('Select');
//						$('#pi_code_'+main_q_id+'_'+sub_q_id).append(my_ops);
//						for(k = 0; k< pi_size; k++){
//						my_ops = $('<option></option>').val(msg.pi_result[k].msr_id).attr('abbr',msg['pi_code_list'][k].msr_statement).text(msg['pi_code_list'][k].pi_codes);
//						$('#pi_code_'+main_q_id+'_'+sub_q_id).append(my_ops);
//						}
					}
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

$(document).ready(function(){
	var m;
	var counter = $('#total_counter').val();

	for( var i=1 ; i<=counter; i++){
	counter_val = i+'_1';
	sub_que_array.push({index:i, value:1});
	console.log(sub_que_array);
	register_button(counter_val);
	}
	for(m = 0; m< temp_val.length; m++ ){
	var tmp_var = temp_val[m].split('_');
	sub_que_array.push({index:parseInt(tmp_var[0]), value:parseInt(tmp_var[1])});
	}

});
function register_button(value) {
	var main_que_data = value.split('_');
	var mque_id = main_que_data[0];
	var subque_id = main_que_data[1];
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
		img_counter = $('#image_count_'+mque_id+'_'+subque_id).val();
		img_counter++;
		$('#image_count_'+mque_id+'_'+subque_id).val(img_counter);		
			/*	
			var thumb_div  = '<div class="controls span1" id="img_thmb_'+cloneimgCntr+''+img_counter+'">';
				thumb_div += '<table class="add_imgtbleclass"><tr><td><img src="'+base_url+'/uploads/'+filename+'" class="imgclass" />';
				thumb_div += '<i id="romove_image'+cloneimgCntr+''+img_counter+'" class="icon-remove image_remove img_float_rght">';
				thumb_div += '</i></td></tr></table></div><div class="img_margin"></div>';
				
			var newImage = $(image);
			image_upload_counter.push(cloneimgCntr);
			$('#image_counter').val(image_upload_counter);
			$('#image_show_'+cloneimgCntr).append(thumb_div);
			$('#image_insert_'+cloneimgCntr).append(newImage);
			img_counter++; */
			
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
$('.image_remove').live('click',function(){
  var btn_id = $(this).attr("id").replace('romove_image','');
  var remove_img = btn_id.split('_');
  var main_q_id = remove_img[0];
  var sub_q_id = remove_img[1];
  var count_val = parseInt(remove_img[2]);
	$('#img_thmb_'+btn_id).remove();
	$('#image_hidden_'+btn_id).remove();
	$('#image_count_'+main_q_id+'_'+sub_q_id).val(--count_val);	
	
  });

  
  // function to set all main question as mandatory for the unit if the unit chosen as mandatory.
  
  $('.main_unit').on('change', function(){
	var unit_val = $(this).attr('abbr');
	if($(this).is(":checked")){
		$('.'+unit_val).each(function(){
		$(this).prop('checked',true);
		})
	}else{
		$('.'+unit_val).each(function(){
				$(this).prop('checked',false);
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
		rules: {
                qp_title: {
                    required: true,
					loginRegex: true
                }
				},
			errorClass: "help-inline font_color",
			errorElement: "span",
			highlight:function(element, errorClass, validClass) {
				$(element).addClass('error');
			},
			errorPlacement : function(error, element) {
        if (element.next().is('.error_add')) {
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
			  var maximum_marks = $('#qp_max_marks').val();
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
	
		
		 $('.attempt_question').on('change',function() {
						var attempt_que_data = $(this).attr('id');
						var attempt_que_val = $(this).val();
						var attempt_que_id = attempt_que_data.replace ( /[^\d.]/g, '' );
						var total_que_val = $('#total_question_'+attempt_que_id).val();
							if( total_que_val < attempt_que_val) {
								$.validator.messages.total_marks_validation = 'Total No. of Questions should be greater than or equal to No. of Questions to Attempt';   
									message_text = false;
									return jQuery.validator.methods.total_marks_validation.call(null,message_text, null);
							}else{
								message_text = true;
							}
							
						});	
		
		 $.validator.addMethod('total_marks_validation', function(value) {
						return message_text;
					},$.validator.messages.total_marks_validation);
					
	
	
$('#save_data').on('click', function(){
		$('#add_form_id').validate();
		var validation_flag = $('#add_form_id').valid();
  // adding rules for inputs with class 'comment'
         $('.qpaper_title').rules("add", 
                    {
						loginRegex: true,
						required : true
                    });
            
			$('#total_duration').each(function() {
                $(this).rules("add", 
                    {
					//	numeric: true,
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
						required : true
                    });
            });
			
			$('#qp_max_marks').each(function() {
                $(this).rules("add", 
                    {
						max_marks_validation: true
                    });
            });
			
			$('#qp_notes').each(function() {
                $(this).rules("add", 
                    {
						loginRegex: false,
						required : false
                    });
            });
			
			$('#total_question').each(function() {
                $(this).rules("add", 
                    {
						numeric: true,
						required : true
                    });
            });
			
			$('#attempt_question').each(function() {
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
						loginRegex: false,
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
			$('#loading').show();
			$('#add_form_id').submit();
		}
		else{
		}



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

$('.po_list_data').on('change',function(){
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

$('.bloom_list_data').on('change',function(){
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

$('.topic_list_data').on('change',function(){
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



//Graphs
//$(document).ready(function(){
graph_load();

function graph_load(){
	var course = $('#crs_id').val();
	var qid = $('#qpp_id').val();
	var qpd_type = $('#qpd_type').val();
	

    var post_data = {
        'crs': course,
		'qid': qid,
		'qpd_type':qpd_type
    }
	

	
	$.ajax({type: "POST",
        url: base_url + 'question_paper/manage_model_qp/BloomsLevelPlannedCoverageDistribution',
        data: post_data,
        dataType: 'JSON',
        success: function(json_graph_data) {
//					console.log(json_graph_data);
					if (json_graph_data.length > 0) {
						var i=0;
						var j=0; 
						items1=new Array();
						head = new Array();
						planned_value = new Array();
						actual_value = new Array();
						level = new Array();
						$.each(json_graph_data, function() {
							head[i] = this['BloomsLevel'];
							planned_value[i] = this['PlannedPercentageDistribution'];	
							actual_value[i] = this['ActualPercentageDistribution'];	
							level[i] = this['description'];	
							data = new Array();		
							data.push(head[i],Number(actual_value[i]));
							items1[j++] = data;
							i++;
						});//console.log(level);
						$('#chart1').children().remove();
						var plot2 = $.jqplot('chart1', [planned_value,actual_value], {
										title: {
											text: '',//Blooms Level Planned Vs Coverage Distribution',
											show: true, 
										},
										seriesDefaults: {
													renderer:$.jqplot.BarRenderer,
													pointLabels: { show: true }
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
										series:[
											{label:'Framework Level Distribution'},
											{label:'Planned Distribution'}
										],
										axes: {
											xaxis: {
												renderer: $.jqplot.CategoryAxisRenderer,
												ticks: head
											},
											yaxis: {
												min:0,
												max:100,
												tickOptions: {
													formatString: '%d'
												}
											}
										},
										legend: {
												show: true,
												location: 'ne',
												placement: 'inside'
										}
						});
						$("#bloomslevelplannedcoveragedistribution tr").remove();
						$('#bloomslevelplannedcoveragedistribution_note div').remove('');
						
						//$('#bloomslevelplannedcoveragedistribution > thead:first').append('<tr><td colspan=3><center><b>Blooms Level Planned vs. Coverage Distribution</b></center></td></tr>');
						$('#bloomslevelplannedcoveragedistribution > tbody:first').append('<tr><td><center><b>Bloom\'s Level</b></center></td><td><center><b>Framework level Distribution</b></center></td><td><center><b>Planned Distribution</b></center></td></tr>');
						$.each(json_graph_data, function(){
							$('#bloomslevelplannedcoveragedistribution > tbody:last').append('<tr><td><center>'+this['BloomsLevel']+'</center></td><td><center>'+this['PlannedPercentageDistribution']+' %</center></td><td><center>'+this['ActualPercentageDistribution']+' %</center></td></tr>');
						});
					//	$('#bloomslevelplannedcoveragedistribution_note').append('<div class="span6"><b>Note : </b><br>The above bar graph depicts the individual Blooms Level planned coverage percentage distribution and Blooms Level actual coverage percentage distribution as in the question paper.</div> <div class="span6"> <br><b>Distribution % = ((Count of questions at each Blooms Level) / (Total number of questions) ) * 100 </b></div>');

					$('#bloomslevelplannedcoveragedistribution_note').append('<div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above bar graph depicts the individual Bloom\'s Level planned coverage percentage distribution and Bloom\'s Level actual coverage percentage distribution as in the question paper.<br>Planned distribution is defined by the QP Framework.</td></tr><tr><td><b>Distribution % = ((Count of questions at each Bloom\'s Level) / (Total number of questions) ) * 100 </b></td></tr></tbody></table></div>');
					}
		}
	});	
	
	$.ajax({type: "POST",
        url: base_url + 'question_paper/manage_model_qp/BloomsLevelMarksDistribution',
        data: post_data,
        dataType: 'JSON',
        success: function(json_graph_data) {
				if (json_graph_data.length > 0) {
				var i=0;
				var j=0; 
				items1=new Array();
				head = new Array();
				value = new Array();
				bloom_level = new Array();
                $.each(json_graph_data, function() {
                    head[i] = this['BloomsLevel'];
					value[i] = this['PercentageDistribution'];	
					bloom_level[i] = this['description'];
					data = new Array();					
					data.push(head[i],Number(value[i]));
					items1[j++] = data;
					i++;
                });
				$('#chart2').children().remove();
				var plot2 = jQuery.jqplot('chart2', [items1],
							{
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
										return bloom_level[pointIndex];
									}
								}
							});
					
					$("#bloomslevelplannedmarksdistribution tr").remove();
					$('#bloomslevelplannedmarksdistribution_note div').remove('');
						
					//$('#bloomslevelplannedmarksdistribution > thead:first').append('<tr><td colspan=3><center><b>Blooms Level Marks Distribution</b></center></td></tr>');
					$('#bloomslevelplannedmarksdistribution > tbody:first').append('<tr><td><center><b>Bloom\'s Level</b></center></td><td><center><b>Total Marks</b></center></td><td><center><b>% Distribution</b></center></td></tr>');
					$.each(json_graph_data, function(){
						$('#bloomslevelplannedmarksdistribution > tbody:last').append('<tr><td><center>'+this['BloomsLevel']+'</center></td><td><center>'+this['TotalMarks']+'</center></td><td><center>'+this['PercentageDistribution']+' %</center></td></tr>');
					});
					//$('#bloomslevelplannedmarksdistribution_note').append('<div class="span6"><b>Note : </b><br>The above pie chart depicts the individual Blooms Level actual marks percentage distribution as in the question paper.</div><div class="span6"><br><b> Distribution % = (Sum(Count of individual Blooms Level marks) * 100) / (Total marks)</b></div>');
					
					$('#bloomslevelplannedmarksdistribution_note').append('<div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above pie chart depicts the individual Bloom\'s Level actual marks percentage distribution as in the question paper.</td></tr><tr><td><b> x = Individual Bloom\'s Level marks  <br/> y = Grand Total marks <br/> % Distribution = (x / y) * 100 </b></td></tr></tbody></table></div>');
				}
			}	
    });
	
	$.ajax({type: "POST",
        url: base_url + 'question_paper/manage_model_qp/COLevelMarksDistribution',
        data: post_data,
        dataType: 'JSON',
        success: function(json_graph_data) {
			if (json_graph_data.length > 0) {
				var i=0;
				var j=0; 
				items1=new Array();
				head = new Array();
				value = new Array();
				co_data = new Array();
                $.each(json_graph_data, function() {
                    head[i] = this['clocode'];
					value[i] = this['PercentageDistribution'];	
					co_data[i] = this['clo_statement'];	
					data = new Array();					
					data.push(head[i],Number(value[i]));
					items1[j++] = data;
					i++;
                });
				$('#chart3').children().remove();
				var plot2 = jQuery.jqplot('chart3', [items1],
							{
								title: {
									text: '',//CO Level Marks Distribution',
									show: true
								},							
								seriesDefaults: {
									renderer: jQuery.jqplot.PieRenderer,
									rendererOptions: {
										fill: true,
										showDataLabels: true,
										sliceMargin: 4,
										lineWidth: 5,
										dataLabelFormatString:'%.2f'
									}
								},
							
								legend: {
									show: true, 
									location: 'ne'
								},
								
								highlighter: {
								  show: true,
								  tooltipLocation: 's', 
								  tooltipAxes: 'y', 
								  useAxesFormatters: false,
								  tooltipFormatString: '%s',
								  tooltipContentEditor:function(str, seriesIndex, pointIndex, plot){
										return co_data[pointIndex];
									}
								}
							});
						
					$("#coplannedcoveragesdistribution tr").remove();
					$('#coplannedcoveragesdistribution_note div').remove('');
					
					//$('#coplannedcoveragesdistribution > thead:first').append('<tr><td colspan=3><center><b>CO Level Marks Distribution</b></center></td></tr>');
					$('#coplannedcoveragesdistribution > tbody:first').append('<tr><td><center><b>'+co_lang+' Level</b></center></td><td><center><b>Total Marks</b></center></td><td><center><b>% Distribution</b></center></td></tr>');
					$.each(json_graph_data, function(){
						$('#coplannedcoveragesdistribution > tbody:last').append('<tr><td><center>'+this['clocode']+'</center></td><td><center>'+this['TotalMarks']+'</center></td><td><center>'+this['PercentageDistribution']+' %</center></td></tr>');
					});
				//	$('#coplannedcoveragesdistribution_note').append('<div class="span6"><b>Note : </b><br>The above pie chart depicts the individual Course Outcome(CO) wise actual marks percentage distribution as in the question paper.</div><div class="span6"><br><b> Distribution % = (Sum(Count of individual CO marks) * 100) / (Total marks)</b></div>');	
				
				$('#coplannedcoveragesdistribution_note').append('<div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above pie chart depicts the individual Course Outcome('+co_lang+') wise actual marks percentage distribution as in the question paper.</td></tr><tr><td><b> 	x = Individual CO marks <br/> y = Grand Total marks <br/> % Distribution = (x / y) * 100 </b></td></tr></tbody></table></div>');
				}
			}			
				
    });
}
//});
