var base_url = $('#get_base_url').val();
	var bloom_drop_down = 1;
	
var cloneCntr_ques = 2;
var cloneCntr = 2;

var  rvw_cntr;
var assign_cntr;
var edit_rev_counter_array = new Array();
var edit_assign_counter_array = new Array();
	// function to count the number of question and assignment questions for the chapter
	$(document).ready(function() {
	var i;
	var j;
	var edit_rev_cntr_val = $('#questions_counter').val();
	cloneCntr_ques = parseInt(edit_rev_cntr_val)+1;
	var edit_assign_cntr_val = $('#assignment_counter').val();
	assign_cntr = parseInt(edit_assign_cntr_val)+1;
	for(i = 1; i <= edit_rev_cntr_val; i++){
		edit_rev_counter_array.push(i);
		//register_button(i);// calling function to initialize the image upload btn.
	}
	for(j = 1; j <= edit_assign_cntr_val; j++){
		edit_assign_counter_array.push(j);
	}
	
	$('#edit_counter_array').val(edit_rev_counter_array);
	$('#assignment_array').val(edit_assign_counter_array);
	});
// SLO Lesson Schedule Script functions starts from here //
$(document).ready(function() {

	

	 $(".add_assignment_question").click(function() {
	
			  var assignment_question_block1 = '<div class="" id="add_me1' + assign_cntr + '"><div class="control-group input-append">';
              var assignment_question_block2 = '<div class="row-fluid"><div class="span12"><div class="control-group" ><label class="control-label" for="assignment_question_1" style="width:0px;">Assignment:</label><div class="controls" style=" margin-left:90px;"><textarea type="textarea" class="required" style="margin: 0px; width: 900px; height: 40px;" name="assignment_question_'+assign_cntr+'" id ="assignment_question_'+assign_cntr+'"></textarea><a id="remove_field'+assign_cntr+'" class="delete_assignment_question" href="#"><i class="icon-remove pull-right"></i> </a> </div></div></div></div>';
        var assignment_question_block = assignment_question_block2;		
        var newAssignQuestion = $(assignment_question_block);
        $('#assignment_question_insert').append(newAssignQuestion);
		edit_assign_counter_array.push(assign_cntr);
		$('#assignment_array').val(edit_assign_counter_array);
		$('#assignment_counter').val(assign_cntr);
        assign_cntr++;
		tiny_init();
    });

    $('.delete_assignment_question').live('click', function() {
       // console.log('in delete');
        $(this).parent().parent().parent().parent().remove();
		var replaced_id = $(this).attr("id").match(/\d+/g);
		var assignment_question_counter_index = $.inArray(parseInt(replaced_id),edit_assign_counter_array);
		edit_assign_counter_array.splice(assignment_question_counter_index,1);
			$('#assignment_array').val(edit_assign_counter_array);
        return false;
    });
	
	
		function fixIds(elem, cntr) {
		$(elem).find("[id]").add(elem).each(function() {
		
			this.id = this.id.replace(/\d+$/, "") + cntr;
			this.name = this.id;
		});
	}
	
	//Function to insert new textarea for adding program outcomes
	$("#add_question").click(function() {
		var tlo_id = $('#tlo_id'+bloom_drop_down).val();
		var blo_id = $('#bloom_id'+bloom_drop_down).val();
		//var pi_code = $('#pi_code'+bloom_drop_down).val();
		if( tlo_id != '' && blo_id != ''){
		var table = $("#question_details").clone(true, true);
		fixIds(table, cloneCntr_ques);
		
              var review_question_block = '<div id="question_'+cloneCntr_ques+'" class="control-group"><label class="control-label" for="review_question_'+cloneCntr_ques+'" style="width:0px;">Question:<font color="red"><b>*</b></font></label><div class="controls" style=" margin-left:90px;"><textarea class="required" type="textarea" name="review_question_'+cloneCntr_ques+'" id ="review_question_'+cloneCntr_ques+'"></textarea></div></div>';
			  var edit_img_cnt = '<input type="hidden" name="edit_img_cntr_'+cloneCntr_ques+'" id="edit_img_cntr_'+cloneCntr_ques+'" value="0"/>'
        
        var newAssignQuestion = $(review_question_block);
       $('#add_before').append(newAssignQuestion).append(table).append(edit_img_cnt);
		
		edit_rev_counter_array.push(cloneCntr_ques);
		$('#questions_counter').val(cloneCntr_ques);
		$('#tlo_id'+cloneCntr_ques).val('');	
		$('#bloom_id'+cloneCntr_ques).html('<option value="">Select Bloom\'s Level</option>');	
		$('#pi_code'+cloneCntr_ques).html('<option value="">Select PI Codes</option>');	
		$('#userfile_'+cloneCntr_ques).val('');	
		$('#upload_'+cloneCntr_ques).val('');	
		//$('#image_hidden_'+cloneCntr_ques).val('');	
		$('#image_show_'+cloneCntr_ques).empty();	
		$('#image_insert_'+cloneCntr_ques).empty();	
		$('#edit_counter_array').val(edit_rev_counter_array);
		
		cloneCntr_ques++;
		bloom_drop_down++;	
		
		//register_button((cloneCntr_ques-1));
		
		tiny_init();
		}else{
		alert('Select SLO and Bloom\'s Level before proceeding');
		}
	});

	//Function to delete unwanted textarea
	$('.Delete_question').live('click', function() {
	var question_count = $('#questions_counter').val();	
	var question_count_one = $('#add_more_book_counter').val();
	rowId = $(this).attr("id").match(/\d+/g);
    if (rowId != 1){
			$(this).parent().parent().remove();
			$('#question_'+rowId+'').remove();
			$('#add_more_book_counter').val(question_count_one-1);
			var replaced_id = $(this).attr('id').replace('remove_field','');
			var question_counter_index = $.inArray(parseInt(replaced_id),edit_rev_counter_array);
			edit_rev_counter_array.splice(question_counter_index,1);
			$('#edit_counter_array').val(edit_rev_counter_array);
			return false;
		}
		
	});
	
	
	  });

	  // Form validation rules are defined & checked before form is submitted to controller.	

$.validator.addMethod("loginRegex", function(value, element) {
			return this.optional(element) || /^[a-zA-Z0-9\_\,\!\.\%\&\-\s\:\;\(\/)']+$/i.test(value);
		}, "Field must contain only letters, numbers, spaces, underscore, colon, semicolon, round bracket, forward slash, ' or dashes.");
		
    
	$("#lesson_schedule_edit_form").validate({
		errorClass: "help-inline font_color",
		errorElement: "span",
		highlight: function(element, errorClass, validClass) {
			$(element).parent().parent().addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parent().parent().removeClass('error');
			$(element).parent().parent().addClass('success');
		}
	});	  
	
	$.validator.addMethod('noSpecialChars',function(value, element){
            return this.optional(element) || /^(^[A-Za-z\s]+([\._]?[A-Za-z\s\?\.\,\:]+))+$/.test(value);
        },
        'Verify you have a valid entry.'
    );
	
	$.validator.addMethod('noSpecialChars1',function(value, element){
            return this.optional(element) || /^(^[A-Za-z0-9]+([\._]?[A-Za-z]+))+$/.test(value);
        },
        'Verify you have a valid entry.'
    );
	
	 $.validator.addMethod("onlyDigit", function(value, element) {
       return this.optional(element) || /^[0-9\.\_]+$/i.test(value);
	}, "This field must contain only Numbers.");
	
		$('.add_details').on('click', function(event) {
		
		$('#lesson_schedule_edit_form').validate();			
            // adding rules for inputs with class 'comment'
         $(".portion_per_hour").each(function() {	
                $(this).rules("add", 
                    {
						noSpecialChars: true
                    });
            });
		$('.review_question').each(function() {
                $(this).rules("add", 
                    {
						
						noSpecialChars: true
                    });
            });
			$('.assignment_question').each(function() {
                $(this).rules("add", 
                    {
						
						noSpecialChars:true
                    });
            });
			
			$('.tlo_bloom_level ').each(function() {
                $(this).rules("add", 
                    {
						required:true
                    });
            });
			
			 
	});
	
	

function safe_tags( str ) {
  return String( str )
           .replace( /&/g, '&amp;' )
           .replace( /"/g, '&quot;' )
           .replace( /'/g, '&#39;' )
           .replace( /</g, '&lt;' )
           .replace( />/g, '&gt;' );
}
// window.onload =function(){
// register_button(1);
// };
var cloneimgCntr;
var image_counter_val;
/* function register_button(value) {
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
         //$('#myModalstart').modal('');
        },          
        onComplete: function(filename, response) {
		
		//alert(filename);
		var image = '<div class="controls"><input name="image_hidden_' + cloneimgCntr + '[]" id="image_hidden_' + cloneimgCntr+''+image_counter_val+ '" type="hidden" value="'+filename+'"/></div>';
		
		var thumb_div = '<div class="controls span1" id="img_thmb_'+cloneimgCntr+''+image_counter_val+'"><table class="imgtbleclass"><tr><td><img src="'+base_url+'/uploads/'+filename+'" class="imgclass" /><i id="romove_image'+cloneimgCntr+''+image_counter_val+'" class="icon-remove image_remove img_float_rght"></i></td></tr></table></div><div class="img_margin"></div>';
		
       	/* var thumb_div = '<div class="controls span1" id="img_thmb_'+cloneimgCntr+''+image_counter_val+'"><table class=""><tr><td><img src="'+base_url+'/uploads/'+filename+'" class="imgclass" /><i class="icon-remove image_remove img_float_rght"</div>'; 
       	//var thumb_div = '<img src="'+base_url+'/uploads/'+filename+'" class="imgclass"/>';
		//var image_data = $(thumb_div).nailthumb({width:30,height:30});
        var newImage = $(image);
        $('#image_insert_'+cloneimgCntr).append(newImage);
		// image_upload_counter.push(cloneimgCntr);
		// $('#image_counter').val(image_upload_counter);
		$('#image_show_'+cloneimgCntr).append(thumb_div);
		$('#edit_img_cntr_'+cloneimgCntr).val(image_counter_val);		
            if (!response) {
              errBox.innerHTML = 'Unable to upload file';
              return;
            }     
            
          }
		 
	});
}; */

 $('.image_remove').live('click',function(){
  var btn_id = $(this).attr("id").match(/\d+/g);
	$('#img_thmb_'+btn_id).remove();
	$('#image_hidden_'+btn_id).remove();
  });
  
//new code
//Tiny MCE script

tinymce.init({
    selector: "textarea",
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

function tiny_init(){

tinymce.init({
    selector: "textarea",
	//plugins: "paste",
	relative_urls: false,
	plugins: [
    "advlist autolink lists link image charmap print preview anchor",
    "searchreplace visualblocks code fullscreen",
    "insertdatetime media table contextmenu paste jbimages",
	
    ],
	paste_data_images: true,
	content_css: base_url+'twitterbootstrap/tinymce/plugins/tinymce_equation_editor/mathquill.css',
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages",
	//height : 300;
	
});

}
 function select_bl()
    {
		
        var tlo_id = $('#tlo_ids').val();
		
		var bl_drop_down_fill_path = base_url+'curriculum/tlo_list/select_bl';
        var post_data = {
            'tlo_id': tlo_id
        }

        $.ajax({type: "POST",
            url: bl_drop_down_fill_path, 
            data: post_data,
            success: function(msg) {
                $('#bloom_id').html(msg);
				select_pi_code();
                
            }
        });
    } 
	
$('.tlo_bloom_level').on('change',function(){
	tlo_rowId = $(this).attr("id").match(/\d+/g);
	var tlo_id = $(this).val();
		
		var bl_drop_down_fill_path = base_url+'curriculum/tlo_list/select_bl';
        var post_data = {
            'tlo_id': tlo_id
        }

        $.ajax({type: "POST",
            url: bl_drop_down_fill_path, 
            data: post_data,
            success: function(msg) {
                $('#bloom_id'+tlo_rowId).html(msg);
                
            }
        });
});
//function to fetch the PI codes
$('.bl_pi_codes').on('change',function(){
		bl_rowId = $(this).attr("id").match(/\d+/g);
        var tlo_id = $('#tlo_id'+bloom_drop_down).val();
        var course_id = $('#lesson_course_id').val();
		var bloom_id = $('#bloom_id'+bloom_drop_down).val();
		var pi_drop_down_fill_path = base_url+'curriculum/tlo_list/select_pi_code';
        var post_data = {
            'tlo_id': tlo_id,
			'crs_id':course_id,
			'bloom_id': bloom_id,
        }

        $.ajax({type: "POST",
            url: pi_drop_down_fill_path, 
            data: post_data,
            success: function(msg) {
                $('#pi_code'+bl_rowId).html(msg);
            }
        });
    });

	// lesson schedule review question counter
	// var edit_review_question_counter = new Array();
	// $(document).ready(function(){
				
	// }
$('.upload_btn').live('click',function(){
var btn_id = $(this).attr("id").match(/\d+/g);
var img_num = $('#edit_img_cntr_'+btn_id).val();
//register_button(btn_id);
cloneimgCntr = btn_id;
image_counter_val = parseInt(img_num)+1;
}); 