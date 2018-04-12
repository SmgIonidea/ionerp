
//bulk_email.js


var base_url = $('#get_base_url').val();

//function is to fetch roles.
function fetch_roles() {
    var dept = $("#department").val();
    $("#loading").show();
    if (dept) {
        var post_data = {
            'dept_id': dept,
        }
        $.ajax({
            type: "POST",
            url: base_url + 'configuration/bulk_email/fetch_roles',
            data: post_data,
            success: function (msg) {
                document.getElementById('roles').innerHTML = msg;
                $('#roles').multiselect('rebuild');
                $("#loading").hide();
            }
        });
    } else {
        $('#roles').find('option:selected').prop('selected', false);
        $('#roles').multiselect('rebuild');
        $('#to').val("");
        $("#loading").hide();
    }
}

//function to fetch email id
function fetch_email_id() {
    var dept = $("#department").val();
    var roles = $("#roles").val();
    var post_data = {
        'dept_id': dept,
        'roles': roles,
    }
    
    if(roles){
        $.ajax({
            type: "POST",
            url: base_url + 'configuration/bulk_email/fetch_email_id',
            data: post_data,
            success: function (msg) {
                $('#to').val(msg);
            }
        });
    }else{
        $('#to').val("");
    }
}

// Form validation rules are defined for add form & checked before form is submitted to controller.
$("#add_form").validate({
    errorClass: "help-inline font_color",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('success');
        $(element).addClass('error');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).addClass('success');
    }
});

//function is to save email data.
$("#add_form_submit").click(function () {
    var flag = $("#add_form").valid();
    var str = $("#to").val();
    var str_array = str.split(',');

    for (var i = 0; i < str_array.length; i++) {
        // Trim the excess whitespace.
        str_array[i] = str_array[i].replace(/^\s*/, "").replace(/\s*$/, "");
    }

    var length = 0;
    if (str_array[str_array.length - 1] == "") {
        length = str_array.length - 1;
    } else {
        length = str_array.length;
    }

    for (i = 0; i < length; i++) {
        var result = email_validate(str_array[i]);
        if (result == false) {
            $("#mail_to").text("Verify you have a valid email address seperated by commas.").css("color", "red");
            flag = false;
            break;
        } else {
            $("#mail_to").text("");
        }
    }

    var to = $("#to").val();
    var subject = $("#subject").val();
    var body = $("#body").val().replace(/\n/g, "<br />");
    var signature = $("#signature").val().replace(/\n/g, "<br />");
    
    var form_data = new FormData();
    var cnt=0;
    $.each($('input[name="usr_file[]"]'), function() {
        if($(this).val() != '') {
           cnt++;
        }
    });
    for(var f = 0 ; f< cnt; f++) {
        var file_data = $('input[name="usr_file[]"]')[f].files;   
        var file_size = $('input[name="usr_file[]"]')[f].files[0].size;         
        for(var i = 0;i<file_data.length;i++){
            form_data.append("file_"+f, file_data[i]);
        }
        var i = parseInt(Math.floor(Math.log(file_size) / Math.log(1024)));
        var size = (file_size / Math.pow(1024, i)).toFixed(2);

    }       

    var other_data = $('#add_form').serializeArray();
    $.each(other_data,function(key,input){
        form_data.append(input.name,input.value);
    });
    
    if (flag) {
        $("#loading").show();
        var post_data = {
            'to': to,
            'subject': subject,
            'body': body,
            'signature': signature
        }
        $.ajax({
            type: "POST",
            url: base_url + 'configuration/bulk_email/insert_email_data',
            data: form_data,
            contentType: false,
            processData: false,
            datatype: "json",
            success: function (data) {
                if (data == 1) {
                    $("#loading").hide();
                    success_modal();
                    $("#reset").trigger("click");
                    $('div.upload_attachments_div').remove();
                }
            }
        });
    }
});

//function is to validate given input is email id or not. 
function email_validate(value) {
    var result = /^(^[A-Za-z0-9]+([\._-]?[A-Za-z0-9]+)*@[A-Za-z0-9]+([-]?[0-9a-zA-Z]+)*(\.[A-Za-z0-9]+([-]?[0-9a-zA-Z]+)*)+)+$/.test(value);
    return result;
}

//function to give save message on add.
function success_modal(msg) {
    var data_options = '{"text":"Your email has been sent successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}

//function to reset form fields
$(document).on('click', '#reset', function (e) {
    $('div#div_attachments > div.upload_attachments_div').remove();
    $('#roles').find('option:selected').prop('selected', false);
    $('#roles').multiselect('rebuild');
    var validator = $('#add_form').validate();
    validator.resetForm();
});

$(document).ready(function() {
    var upload_attachment ='';
    var attach_cnt = 1;
    var id_array = [];
    var total_upload_cnt = attachment_cnt_limit;
    var max_file_size = attachment_size_limit;
    $('.upload_attachments').css("display", "none");
   
    $(document).on('click', '.add_attachment', function(e) {
        e.preventDefault();
        
        var upload_count = 1;
        $.each($('input:file'), function(i, files){
            upload_count++;
        });
        if(upload_count <= total_upload_cnt) {
            $("div#div_attachments > div.hidden_div").remove();
            $('#div_attachments').append('<div class="upload_attachments_div hidden_div" id="attachment_div_'+attach_cnt+'"><br><br><input type="text" name="attachment_files_'+attach_cnt+'" id="attachment_files_'+attach_cnt+'" readonly="readonly" size="50" style="width:300px" /><input type="file" name="usr_file[]" id="add_attachment_'+attach_cnt+'"  class="upload_attachments" />&nbsp;<a href="#" title="Remove Attachment" name="remove_attachment_'+attach_cnt+'" class="remove_attachment" ><i class="icon icon-remove-sign icon-remove"></i> </a></div>');
            $('.upload_attachments').css("display", "none");
            $('#attachment_div_'+attach_cnt).css("display", "none");
            upload_attachment = 'add_attachment_'+attach_cnt;
            $('input[id="'+upload_attachment+'"]').trigger('click');
            $('input[id="'+upload_attachment+'"]').value = null;
        } else {
            var last_id = attach_cnt - 1;
            $('#attachment_div_'+last_id).removeClass("hidden_div");  
            $('#file_upload_maximum_count').modal('show');
        }
    });
    
    $(document).live('change', 'input[id="'+upload_attachment+'"]', function(e){
        e.preventDefault();
        
        var str = upload_attachment.split('_');
        var id = parseInt(str[2]);
        var filename = $('input[id="'+upload_attachment+'"]').val();//.replace(/C:\\fakepath\\/i, '');        
        if(filename != '' && filename != null) {            
            var flag = ValidateExtension(filename);
            if(flag){               
                var flag = ValidateExtension(filename);
                var totalFileSize = 0;
                $.each($('input[name="usr_file[]"]'), function(i, files) {
                    var file_size = $('input[name="usr_file[]"]')[i].files[0].size;
                    var i = parseInt(Math.floor(Math.log(file_size) / Math.log(1024)));
                    var size = (file_size /1048576).toFixed(2);;
                    totalFileSize = totalFileSize + parseFloat(size);
                });  
                if(flag && parseInt(totalFileSize) <= max_file_size)  {                    
                    $('#attachment_div_'+id).css("display", "inline");
                    $('input[name="attachment_files_'+attach_cnt+'"]').val(filename);
                    $('input[name="attachment_files_'+attach_cnt+'"]').attr('title', filename);
                    var upload_count = 1;
                    $.each($('input[name="usr_file[]"]'), function(i, files){
                        upload_count++;
                    });
                    if(upload_count <= total_upload_cnt ) {                       
                        $('#attachment_div_'+id).css("display", "inline");
                        $('.upload_attachments').css("display", "none");
                        $('#attachment_div_'+id).removeClass("hidden_div");  
                        $('input[name="attachment_files_'+attach_cnt+'"]').val(filename);
                        $('input[name="attachment_files_'+attach_cnt+'"]').attr('title', filename);
                    } else {
                        
                    }
                    $('.upload_attachments').css("display", "none");
                    attach_cnt = attach_cnt + 1;
                    id_array.push(attach_cnt);
                }  else if(totalFileSize > 10) {
                    $('div#attachment_div_'+id).remove();
                    $('#file_upload_maximum_size').modal('show');
                }
                upload_attachment = '';         
            } else {
                $('div#attachment_div_'+id).remove();
                $('#invalid_file_upload').modal('show');
            }
        } else {
            $('div#add_attachments > #attachments_div_'+id).css("display", "inline");
        }
    });
    
    $(document).on('click', '.remove_attachment', function(e){
        e.preventDefault(); 
        var name = $(this).attr('name');
        $('div.upload_attachments_div').each(function() {
            $(this).removeClass("hidden_div");
        });
        var str = name.split('_');
        var id = parseInt(str[2]);
        id_array.splice($.inArray(id, id_array),1);
        $('#attachment_div_'+id).remove();
    });
    
    function ValidateExtension() {
        var allowedFiles = [ ".doc", ".docx", ".xls", ".xlsx", ".jpg", ".png", ".txt", ".ppt", ".pptx", ".pdf", ".odt" , ".rtf"];
        var filename = arguments[0];
        var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
        if (!regex.test(filename.toLowerCase())) {
            return false;
        }
        return true;
    }
    
    $(document).on('click', '#max_file_upload', function(e) {
        e.preventDefault();
        $('#file_upload_maximum_count').modal('show');
    }); 
});