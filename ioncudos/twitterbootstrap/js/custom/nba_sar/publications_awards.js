
//Publications and Awards in inter-institute events by students of the programme of study .js
	
var base_url = $('#get_base_url').val();

//cookie - department
if ($.cookie('remember_dept') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#department option[value="' + $.cookie('remember_dept') + '"]').prop('selected', true);
    select_pgm_list();
}
$.validator.addMethod("loginRegex_spec", function(value, element) {
    return this.optional(element) || /^[a-zA-Z 0-9\s]+([:\"\'\.\,\_\-\a-zA-Z 0-9\s\&])*$/i.test(value);
}, "Field must contain only numbers,letters,spaces or dashes.");
//Function to fetch program details on select of department
function select_pgm_list() {
    //$('#loading').show();
    $.cookie('remember_dept', $('#department option:selected').val(), {
        expires: 90, 
        path: '/'
    });
    var dept_id = $('#department').val();
		
    var post_data = {
        'dept_id': dept_id
    }
		
    $.ajax({
        type: "POST",
        url: base_url+'nba_sar/publications_awards/select_pgm_list',
        data: post_data,
        success: function(msg) {
            $('#pgm_id').html(msg);
            if ($.cookie('remember_pgm') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#pgm_id option[value="' + $.cookie('remember_pgm') + '"]').prop('selected', true);
                select_crclm_list();
                $('#loading').hide();
            }
        }
    });
}
	
//Function to fetch curriculum details on select of program
function select_crclm_list() {
    //$('#loading').show();
    $.cookie('remember_pgm', $('#pgm_id option:selected').val(), {
        expires: 90, 
        path: '/'
    });
    var pgm_id = $('#pgm_id').val();
		
    var post_data = {
        'pgm_id': pgm_id
    } 
		
    $.ajax({
        type: "POST",
        url: base_url+'nba_sar/publications_awards/select_crclm_list',
        data: post_data,
        success: function(msg) {
            $('#crclm_id').html(msg);
            if ($.cookie('remember_crclm') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#crclm_id option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
                select_termlist();
                $('#loading').hide();		
            }
        }
    });
}
	
//Function to fetch term details
function select_termlist() {
    //	$('#loading').show();
    $.cookie('remember_crclm', $('#crclm_id option:selected').val(), {
        expires: 90, 
        path: '/'
    });
    var crclm_id = $('#crclm_id').val();
		
    var post_data = {
        'crclm_id': crclm_id
    }
		
    $.ajax({
        type: "POST",
        url: base_url+'nba_sar/publications_awards/select_termlist',
        data: post_data,
        success: function(msg) {
            $('#term').html(msg);
            if ($.cookie('remember_term') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
                GetSelectedValue();
                $('#loading').hide();
            }
        }
    });
}

// For future use
//Function to fetch course details 
/* 	function select_courselist() {
		$.cookie('remember_term', $('#term option:selected').val(), {expires: 90, path: '/'});
		
		var course_list_path = base_url + 'nba_sar/publications_awards/select_course';
		var crclm_id = $('#crclm_id').val();
		var term_id = $('#term').val();
		
		if(term_id) {
			var post_data = {
				'crclm_id': crclm_id,
				'term_id': term_id
			}
			
			$.ajax({type: "POST",
				url: course_list_path,
				data: post_data,
				success: function(msg) { 
					$('#course').html(msg);	
					if ($.cookie('remember_course') != null) {
						// set the option to selected that corresponds to what the cookie is set to
						$('#course option[value="' + $.cookie('remember_course') + '"]').prop('selected', true);
					
					}
				}
			}); 
		} else {
			$('#course').html('<option value="">Select Course</option>');
		}
	} */
	
$('#term').on('change',function(){
    //	GetSelectedValue();
    });
	
/**Function To Initialize the tinymce **/
tinymce.init({
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
});
	
	
/**Function to initialize the tinymce**/
function tiny_init(){
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
} 				
$("#award_publc_date").datepicker( {	
    format: "dd-mm-yyyy",	
    endDate:'-1d'
}).on('changeDate',function (ev){
    $(this).blur();
    $(this).datepicker('hide');
});	
$('#award_publc_date_btn').click(function(){
    $(document).ready(function(){
        $('#award_publc_date').datepicker().focus();
    });
});
		
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

$('#update_award_publc').hide();
$('#update_award_publc,#save_award_publc').on('click',function(e){

    $('#loading').show();
    $("#publication_awards").validate({
        rules: {
            award_publc_date:{
                required: true,					
            },
            publication_title:{
                required: true,
                loginRegex_spec:true,
            },
            participants:{
                required: true,
                loginRegex_spec:true,
            },
            level_of_presentation:{
                required: true,
                loginRegex_spec:true,
            },
            position:{
                required : false,
                loginRegex_spec:true,
            },			 
            award_venue:{
                required : false,
                loginRegex_spec:false,
            },				
        },
        messages: {  
            award_publc_date:{
                required: "This field is required.",					
            }
        },
        errorPlacement : function(error, element) {
            if (element.attr('name') == "award_publc_date") {
                error.appendTo('#error_award_publc_date_btn');
            } else {
                error.insertAfter(element);
            }
        }
    });
    var idClicked = e.target.id; 
    var flag = $('#publication_awards').valid();
    var dept_id  = $('#department').val();
    var pgm_id = $('#pgm_id').val();			
    var crclm_id  = $('#crclm_id').val();
    var term = $('#term').val();
    if(term != "" && dept_id!="" && pgm_id !="" && crclm_id!=""){
        var buttonvalue;
        if(flag == true){
            tinyMCE.triggerSave();
            var str=$('#award_abstract').val();
            var str1=str.replace("<p>", " ");
            var str2=str1.replace("</p>"," ");
            var res= str2.replace('alt=""', 'alt="image"');
            var abstract_description=res;
			
            //	var course  = $('#course').val();
			
            if(idClicked == 'update_award_publc'){
                buttonvalue = 'update'
            } else if( idClicked == 'save_award_publc'){
                buttonvalue = 'save'
            }
            var values = $("#publication_awards").serialize();
            values = values + '&button_update='+buttonvalue;
            values = values + '&patent_abstracts='+abstract_description;
            values = values + '&dept_id='+dept_id;
            values = values + '&pgm_id='+pgm_id;
            values = values + '&crclm_id='+crclm_id;
            values = values + '&term='+term;
            //values = values + '&course='+course;
				
            $.ajax({
                type:"POST",
                url:base_url+'nba_sar/publications_awards/save_update_publication_awards',
                data:values,
                dataType:'json',
                success:function(data){
                    $('#loading').hide();
                    $('#update_award_publc').hide();
                    $('#save_award_publc').show();
                    GetSelectedValue();
                    reset_publication_awards();
                    if(data == 1){
                        success_modal(data);
                    }
                    if(data == 0){
                        fail_modal(data);
                    }
                }
            }); 
        }else{
            $('#loading').hide();
        } 
    }else{
        $('#loading').hide();
        $('#Exist').modal('show');
    }
});
function reset_publication_awards(){
    $("#publication_awards").trigger('reset');
    var validator = $('#publication_awards').validate();
    validator.resetForm();	
    $('#update_award_publc').hide();
    $('#save_award_publc').show();
}	
//Function to display the publications and awards table on select of curriculum
function GetSelectedValue() {
    $('#loading').show();
    var publications_awards_path = base_url + 'nba_sar/publications_awards/show_publications_awards';
		
    var dept_id = $('#dept_id').val();
    var pgm_id = $('#pgm_id').val();
    var crclm_id = $('#crclm_id').val();
    var term_id  = $('#term').val();
    //	var crs_id  = $('#course').val();
		
    var post_data = {
        'dept_id': dept_id,
        'pgm_id': pgm_id,
        'crclm_id': crclm_id,
        'term_id':term_id,
    //'crs_id':crs_id
    }
		
    $.ajax({
        type: "POST",
        url: publications_awards_path,
        data: post_data,
        dataType: 'json',
        success:function(msg){
            $('#loading').hide();
            populate_table(msg);
        }
    // populate_table
    });
}
	
	
/* Function is used to generate table grid of  course details.
     * @param - 
     * @returns- an array of course details.
     */	
function populate_table(msg) {		
    $('#example').dataTable().fnDestroy();
    //$('#example').empty();
    $('#example').dataTable(
    {	
        "sPaginate": true,	
        "aoColumns": [
        {
            "sTitle": "Sl No.", 
            "mData": "sl_no" ,
            "sType": 'numeric',
            "bSortable" : true, 
            "sSortDataType": "dom-text"
        },

        {
            "sTitle": "Title", 
            "mData": "title"
        },

        {
            "sTitle": "Participant(s)", 
            "mData": "participants"
        },  

        {
            "sTitle": "Position", 
            "mData": "position"
        },

        {
            "sTitle": "Venue", 
            "mData": "venue"
        },

        {
            "sTitle": "Date", 
            "mData": "date"
        },

        {
            "sTitle": "Upload", 
            "mData": "upload"
        },

        {
            "sTitle": "Edit", 
            "mData": "edit"
        },

        {
            "sTitle": "Delete", 
            "mData": "delete"
        }
						
        ], 
        "aaData": msg,				
        "sPaginationType": "bootstrap",
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            $('td:eq(0)', nRow).css("text-align", "right");
            return nRow;
        }				
    });

}
	
$('#example').on('click','.edit_award',function(e){
    var validator = $('#publication_awards').validate();
    validator.resetForm();

    $('#award_publc_id').val($(this).attr('id'));
    $('#publication_title').val($(this).attr('data-title'));
    $('#participants').val($(this).attr('data-participants'));
    $('#level_of_presentation').val($(this).attr('data-participants'));
    $('#position').val($(this).attr('data-position'));
    $('#award_venue').val($(this).attr('data-venue'));	
    $('#award_publc_date').val($(this).attr('data-date'));
    tiny_init();	
    tinyMCE.get('award_abstract').setContent($(this).attr('data-abstract'));
	
    $('#update_award_publc').show();
    $('#save_award_publc').hide();
}); 
	
$('#example').on('click','.delete_award',function(){
    $('#publication_awards').val($(this).attr('id')); 
    $('#delete_confirm').modal('show');
		
});
		
$('#delete_awards').on('click',function(){
    $('#loading').show();
    $('#loading').show();
    var publication_awards = $('#publication_awards').val();
    var post_data ={
        'publication_awards':publication_awards
    }
    $.ajax({
        type:"POST",
        url:base_url+'nba_sar/publications_awards/delete_publc_award',
        data:post_data,					
        success:function(data){ 
            $('#loading').hide();
            if(data == 1){ }
            success_modal_delete();
            $('#loading').hide();
            GetSelectedValue();
            $('#update_award_publc').hide();
            $('#save_award_publc').show();
            reset_publication_awards();
        }
    }); 
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
        'per_table_id':per_table_id
    }	
    var uploader = document.getElementById('uploaded_file');	 
    upclick({
        element: uploader,
        action_params : post_data,
        multiple: true,
        action: base_url+'nba_sar/publications_awards/upload',
        oncomplete:
        function(response_data) {	
            fetch_file_data(per_table_id);
            if(response_data=="file_name_size_exceeded") {
                $('#file_name_size_exc').modal('show');
            } else if(response_data=="file_size_exceed") {
                $('#larger').modal('show');
            } 
        }
    }); 					
				
});	
		
function fetch_file_data(per_table_id){
    document.getElementById('res_guid_files').innerHTML = '';				
    post_data =  {
        'per_table_id':per_table_id
    }
    $.ajax({
        type: 'POST',
        url: base_url+'nba_sar/publications_awards/fetch_files',
        data: post_data,
        success: function(data) {

            document.getElementById('res_guid_files').innerHTML = data;
        }
    });
}
	
function delete_file(my_id){
    $('#uload_id').val(my_id);
    $('#delete_res_guid_file_modal').modal('show');
}
	
$('#delete_uploaded_files').on('click',function(){

    var per_table_id = $('#uload_id').val();
		

    post_data =  {
        'per_table_id':per_table_id
    }
    $.ajax({
        type: 'POST',
        url: base_url+'nba_sar/publications_awards/delete_uploaded_files',
        data: post_data,
        success: function(data) {
            success_modal_delete(data);
            var per_table_id = $('#per_table_id').val();
            fetch_file_data(per_table_id);
        }
    });
});
	
	
$('#myform').on('submit', function(e) {
    e.preventDefault();
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
            url: base_url+'nba_sar/publications_awards/save_res_guid_desc',
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
    var data_options = '{"text":"Your log history display has been dissabled successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
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
