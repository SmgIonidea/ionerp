
//send_mail.js
//Function after loading fie. 
$(document).ready(function(){
    $(function() {
            $('#crclm').multiselect({
                    includeSelectAllOption: true,
                    maxHeight: 200,
                    buttonWidth: 160,
                    nSelectedText: 'select All',
                    nonSelectedText: "Select Curriculum"
            });

            $('#term').multiselect({
                    includeSelectAllOption: true,
                    maxHeight: 200,
                    buttonWidth: 235,
                    nSelectedText: 'select All',
                    nonSelectedText: "Select Term"
            });

            list_send_mail();
    });

    //Function is to list sent mail. 
    function list_send_mail(){
            $.ajax({
                    type: "POST",
                    url: base_url + 'curriculum/send_mail/list_send_mail',
                    data: {},
                    success: function(data) {
                            $("#mail_list").html("");
                            $("#mail_list").html(data);
                            $('#send_mail_table').dataTable().fnDestroy();
                            $('#send_mail_table').dataTable({
                                    "aaSorting" : [[3, 'desc']],
                                    "sPaginationType": "bootstrap",
                                    "bSort": true
                            });
                    }
            });
    }

    //Function is to load mail details in the modal.
    $("#mail_list").on("click",".view_details",function(){
            var se_id =$(this).attr("data-id");

            $.ajax({
                    type: "POST",
                    url: base_url + 'curriculum/send_mail/fetch_mail_details',
                    data: {
                            'se_id':se_id
                    },
                    success: function(data) {
                            $(".mail_details").html(data);
                            $("#view_mail_modal").modal("show");
                    }
            });       
    });

    //Function is to fetch program dropdown.
    function fetch_program(){
        var roles = $("#roles").val();
        $('#crclm_co').hide();
        $('#term_co').hide();
        $("#to").val("");
        $("#cc").val("");
        
        if(roles){
                $.ajax({
                        type: "POST",
                        url: base_url + 'curriculum/send_mail/fetch_program',
                        data: {},
                        success: function(data) {
                                if(roles == 6){
                                        $("#program").attr("onChange","fetch_crclm();");
                                        $("#program").html(data);
                                }else{
                                        $("#program").attr("onChange","fetch_email_id();");
                                        $("#program").html(data);
                                }
                        
                        }
                });
        }else{
                $("#program").html('<option value="" selected> Select Program </option>');
                $("#program").attr("onChange","");
        }
    }

    //Function is to fetch curriculum dropdown
    function fetch_crclm(){
        var pgm_id = $("#program").val();
        $('#crclm_co').show();
        $("#to").val("");
        $("#cc").val("");
        var post_data = {
                'pgm_id' : pgm_id
        }

        if(pgm_id){
                $.ajax({
                        type: "POST",
                        url: base_url + 'curriculum/send_mail/fetch_curriculum',
                        data: post_data,
                        success: function(data) {
                                $("#crclm").html(data);
                                $('#crclm').multiselect('rebuild');
                        }
                });
        }else{
                $("#crclm").html("");
                $('#crclm').find('option:selected').prop('selected', false);
                $('#crclm').multiselect('rebuild');
                $("#term").html("");
                $('#term').find('option:selected').prop('selected', false);
                $('#term').multiselect('rebuild');
        }
    }

    //Function is to fetch term dropdown.
    function fetch_term(){
            var crclm_id = $("#crclm").val();
            $('#term_co').show();
            $("#to").val("");
            $("#cc").val("");
            var post_data = {
                    'crclm_id' : crclm_id
            }
            if(crclm_id){
                    $.ajax({
                            type: "POST",
                            url: base_url + 'curriculum/send_mail/fetch_term',
                            data: post_data,
                            success: function(data) {
                                    $("#term").html(data);
                                    $('#term').multiselect('rebuild');
                            }
                    });
            }else{
                    $("#term").html("");
                    $('#term').find('option:selected').prop('selected', false);
                    $('#term').multiselect('rebuild');
            }
    }

    //Function is to fetch co email id
    function fetch_co_mail_id(){
        var term_id = $("#term").val();
        var dept_id=$('#program').find(':selected').attr('data-id');
        $("#to").val("");
        $("#cc").val("");
        var post_data = {
                'term_id': term_id,
                'dept_id': dept_id
        }
        
        if(term_id){
                $.ajax({
                        type: "POST",
                        url: base_url + 'curriculum/send_mail/fetch_co_email_id',
                        dataType: 'json',
                        data: post_data,
                        success: function (data) {
                                $('#to').val(data['to']);
                                $('#cc').val(data['cc']);
                        }
                });
        }       
}

    //Function is to fetch po and chairman mail id
    function fetch_email_id(){
        var dept_id=$('#program').find(':selected').attr('data-id');
        var roles = $("#roles").val();
        $("#to").val("");
        $("#cc").val("");
        var post_data = {
                'dept_id': dept_id,
                'roles': roles
        }
        
        $.ajax({
                type: "POST",
                url: base_url + 'curriculum/send_mail/fetch_email_id',
                dataType: 'json',
                data: post_data,
                success: function (data) {
                        $('#to').val(data['to']);
                        $('#cc').val(data['cc']);
                }
        });
    }

    //function is to validate given input is email id or not. 
    function email_validate(value) {
        var result = /^(^[A-Za-z0-9]+([\._-]?[A-Za-z0-9]+)*@[A-Za-z0-9]+([-]?[0-9a-zA-Z]+)*(\.[A-Za-z0-9]+([-]?[0-9a-zA-Z]+)*)+)+$/.test(value);
        return result;
    }

    //function is to save email data.
    $("#add_form_submit").click(function (e) {
        e.preventDefault();
        
    
        $("#mail_to").html("");
        $("#mail_cc").html("");
        var flag = $("#add_form").valid();
        var str = $("#to").val();
        var str_array = str.split(',');

        for (var i = 0; i < str_array.length; i++) {
                // Trim the excess whitespace.
               // str_array[i] = str_array[i].replace(/^\s*/, "").replace(/\s*$/, "");
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
                        $("#mail_to").html("<br/>Verify you have a valid email address seperated by commas.").css("color", "red");
                        flag = false;
                        break;
                } else {
                        $("#mail_to").html("");
                }
        }
        
        var str = $("#cc").val();
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
                        $("#mail_cc").html("<br/>Verify you have a valid email address seperated by commas.").css("color", "red");
                        flag = false;
                        break;
                } else {
                        $("#mail_cc").html("");
                }
        }
        
        var role = $("#roles").val();
        var pgm_id = $("#program").val();
        var to = $("#to").val();
        var cc =  $("#cc").val();
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
                'role' :role,
                'pgm_id' : pgm_id,
                'to': to,
                'cc': cc,
                'subject': subject,
                'body': body,
                'signature': signature
            }
            $.ajax({
                type: "POST",
                url: base_url + 'curriculum/send_mail/save_send_email_data',
                data: form_data,
                contentType: false,
                processData: false,// post_data,
                datatype: "json",
                success: function (data) {
                    if (data == 1) {
                            $("#loading").hide();
                            success_modal();
                            list_send_mail();
                            $("#reset").trigger("click");
                            $('div.upload_attachments_div').remove();
                    }
                }
            });
        }
    });

    //function to reset form fields
    $("#reset").click(function () {
            $("#mail_to").html("");
            $("#mail_cc").html("");
            $('div#div_attachments > div.upload_attachments_div').remove();
            var validator = $('#add_form').validate();
            validator.resetForm();
    });

    //function to give save message on add.
    function success_modal(msg) {
            var data_options = '{"text":"Your email has been sent successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
            var options = $.parseJSON(data_options);
            noty(options);
    }

        
    //Snippets for email with attachment    
        
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
                }  else if(totalFileSize > attachment_size_limit) {
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