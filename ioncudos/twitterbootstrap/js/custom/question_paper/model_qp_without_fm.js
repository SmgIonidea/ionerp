 $('#update_header').hide();
 
 $('#add_ExpenseRow').click(function () {
    $get_lastID();
    $('#expense_table tbody').append($newRow);
});

$get_lastID = function () {
    var $id = $('#expense_table tr:last-child td:first-child input').attr("name");
    $lastChar = parseInt($id.substr($id.length - 2), 10);
    $lastChar = $lastChar + 1;
    $newRow = "<tr> \
					<td><input type='text' id='units_0" + $lastChar + "' name='units_0" + $lastChar + "' maxlength='255' class='required '/></td> \
					<td><input type='text' id='ques_no_0" + $lastChar + "' name='ques_no_0" + $lastChar + "' maxlength='255' class='required '/></td> \
					<td><input type='text' id='marks_0" + $lastChar + "' name='marks_0" + $lastChar + "' maxlength='11' class='required unit_max_marks' /></td> \
					 <td><a type = 'button'   class='del_ExpenseRow' ><i class='icon-remove icon-black'> </i></a></td>\
				</tr>"
    return $newRow;
}

$(".del_ExpenseRow").live("click", function () {
    $(this).closest('tr').remove();
    $lastChar = $lastChar - 2;
});
    $("#example").html($(this).html());
    var v = $("#model_qp_existance").val();
    var qpd_id = $('#qpp_id').val();
    $('#update').hide();
    if (v == 1) {
     
        $("#save_header").hide();
        $('#update_header').hide;
    } else {
        //$('#max_marks').attr('readonly', true);
        //	 $('#Grand_total').attr('readonly', true);
        //	$("#save_header").hide();
    }

    //$("#example").load(function(){
    post_data = {'qpd_id': qpd_id}

    $.ajax({type: "POST",
        url: base_url + 'question_paper/manage_model_qp/Fetch_dum',
        data: post_data,
        //dataType: 'json',
        success: populate_table

    });
	
	// Model QP JS file
// Base url 
var base_url = $('#get_base_url').val();
// Global variables
var $lastChar = 1, $newRow;
var sub_que_counter = 'a';
var temp_id = 0;
var img_counter = 0;
var message_text = true;




// Global Arrays
var sub_que_array = new Array();
var uploaded_image_name_array = new Array();

// Function to add sub question for each main questions in Model QP which is inherited from the QP framework.
$('.add_subque').on('click', function () {
    //tinymce.remove();
    var co_data;
    var topic_data;
    var bloom_level;
    var entity_val;
    tiny_init();
    var url_data = window.location.href;
    var replaced_data = url_data.replace(base_url + 'question_paper/manage_model_qp/generate_model_qp/', '');





    // console.log(msg.topic_details);
    // console.log(msg.bloom_data);
    // console.log(msg.qp_entity);

    var abbr_val = $(this).attr('abbr');
    var abbr_array = abbr_val.split('_');
    var id = parseInt(abbr_array[1]); // main question number

    sub_que_counter = abbr_array[2];// sub question alphabet

    var entity_array_val = $("#entity_array_val").val();
    console.log(entity_array_val);

    //sub_que_array.push({index:id, value:1});
    var sub_que_count_val = $('#sub_que_count' + id).val();
    sub_que_count_val++;

    sub_que_array.push({index: id, value: sub_que_count_val});

    $('#sub_que_count' + id).val(sub_que_count_val);
    sub_que_counter = String.fromCharCode(sub_que_counter.charCodeAt() + 1);

    $(this).attr('abbr', 'QNo_' + id + '_' + sub_que_counter);


    // ajax call for course data and entity data.
    $.ajax({type: "POST",
        url: base_url + 'question_paper/manage_model_qp/model_qp_data_ajax_call/' + replaced_data,
        dataType: "JSON",
        success: function (msg)
        {
            console.log(msg);
            co_data = msg.co_details.length;
            topic_data = msg.topic_details.length;
            bloom_level = msg.bloom_data.length;
            entity_val = msg.qp_entity.length;
            pi_code = msg.pi_list.length;

            //var sub_que = '<thead><tr><th><br></th></tr></thead>';
            //	sub_que += '<tbody>';

            var sub_que = '<tr id="row_' + id + '_' + sub_que_count_val + '" class = "row_' + id + '_' + sub_que_count_val + '"> <td colspan="12"><br></td></tr>';
            sub_que += '<tr id="row_' + id + '_' + sub_que_count_val + '" class = "row_' + id + '_' + sub_que_count_val + '">';
            sub_que += '<td style="white-space:nowrap;" class="textwrap">';
            sub_que += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="ques_nme_' + id + '_' + sub_que_count_val + '" id="ques_nme_' + id + '_' + sub_que_count_val + '" value="Q No ' + id + '-' + sub_que_counter + '" class="input-mini ques_nme_' + id + '" readonly />';
            sub_que += '<input type="hidden" name="question_name_' + id + '_' + sub_que_count_val + '" id="question_name_' + id + '_' + sub_que_count_val + '" value="Q_No_' + id + '_' + sub_que_counter + '" class="input-mini question_name_' + id + '" readonly /></td>';
            for (var i = 0; i < entity_val; i++) {
                if (msg.qp_entity[i].entity_id == 11) {
                    sub_que += '<td>';
                    sub_que += '<label class="inline_class font_bold" for="">CO<font color="red"><b>*</b></font> :</label>';
                    sub_que += '<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="co_list_' + id + '_' + sub_que_count_val + '" id="co_list_' + id + '_' + sub_que_count_val + '" class="input-small co_onchange required co_list_data">';
                    sub_que += '<option value="" abbr="Select CO">Select</option>';
                    for (var j = 0; j < co_data; j++) {
                        sub_que += '<option value="' + msg.co_details[j].clo_id + '" abbr="' + msg.co_details[j].clo_statement + '">' + msg.co_details[j].clo_code + '</option>';
                    }
                    sub_que += '</select>';
                    sub_que += '</td>';
                } else {
                }

                // }
                // for(var i =0; i< entity_val;i++ ){
                if (msg.qp_entity[i].entity_id == 6) {
                    sub_que += '<td>';
                    sub_que += '<label class="inline_class font_bold" for="inputPassword">PO<font color="red"><b>*</b></font> :</label>';
                    sub_que += '<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="po_list_' + id + '_' + sub_que_count_val + '" id="po_list_' + id + '_' + sub_que_count_val + '" class="input-small required po_list_data">';
                    sub_que += '<option value="" abbr="Select CO">Select</option>';
                    sub_que += '</select>';
                    sub_que += '</td>';
                } else {
                }

                if (msg.qp_entity[i].entity_id == 10) {
                    sub_que += '<td>';
                    sub_que += '<label class="inline_class font_bold" for="inputPassword">Topic<font color="red"><b>*</b></font> :</label>';
                    sub_que += '<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="topic_list_' + id + '_' + sub_que_count_val + '" id="topic_list_' + id + '_' + sub_que_count_val + '" class="input-small required topic_list_data">';
                    sub_que += '<option value="" abbr="Select Topic">Select</option>';
                    for (var j = 0; j < topic_data; j++) {
                        sub_que += '<option value="' + msg.topic_details[j].topic_id + '" abbr="' + msg.topic_details[j].topic_title + '">' + msg.topic_details[j].topic_title + '</option>';
                    }
                    sub_que += '</select>';
                    sub_que += '</td>';
                } else {
                }
                // }

                // for(var i =0; i< entity_val;i++ ){
                if (msg.qp_entity[i].entity_id == 23) {
                    sub_que += '<td>';
                    sub_que += '<label class="inline_class font_bold" for="inputPassword">Level<font color="red"><b>*</b></font> :</label>';
                    sub_que += '<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="bloom_list_' + id + '_' + sub_que_count_val + '" id="bloom_list_' + id + '_' + sub_que_count_val + '" class="input-small required bloom_list_data">';
                    sub_que += '<option value="" abbr="Select Level">Select</option>';
                    for (var k = 0; k < bloom_level; k++) {
                        sub_que += '<option value="' + msg.bloom_data[k].bloom_id + '" abbr="' + msg.bloom_data[k].bloom_actionverbs + '">' + msg.bloom_data[k].level + '</option>';
                    }
                    sub_que += '</select>';
                    sub_que += '</td>';
                } else {
                }
                // }

                // for(var i =0; i< entity_val;i++ ){
                if (msg.qp_entity[i].entity_id == 22) {
                    sub_que += '<td>';
                    sub_que += '<label class="inline_class font_bold" for="inputPassword">PI Code<font color="red"><b>*</b></font> :</label>';
                    sub_que += '<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="pi_code_' + id + '_' + sub_que_count_val + '" id="pi_code_' + id + '_' + sub_que_count_val + '" class="input-small pi_code_data">';
                    sub_que += '<option value="" abbr="Select PI Code">Select</option>';
                    for (var k = 0; k < pi_code; k++) {
                        sub_que += '<option value="' + msg.pi_list[k].msr_id + '" abbr="' + msg.pi_list[k].msr_statement + '">' + msg.pi_list[k].pi_codes + '</option>';
                    }
                    sub_que += '</select>';
                    sub_que += '</td>';
                } else {
                }
            }

            sub_que += '<td>';
            sub_que += '<label class="inline_class font_bold" for="inputPassword">Marks<font color="red"><b>*</b></font> :</label>';
            sub_que += '<div class=" input-append">';
            sub_que += '<input type="text" name="mq_marks_' + id + '_' + sub_que_count_val + '" id="mq_marks_' + id + '_' + sub_que_count_val + '" class="input-mini required mq_marks numeric"/>';
            sub_que += '<button class="btn btn-danger delete_subque error_add" type="button" id="delete_subque_' + id + '_' + sub_que_count_val + '" name="delete_subque_' + id + '_' + sub_que_count_val + '" abbr="QNo_' + id + '_' + sub_que_count_val + '"><i class="icon-minus-sign icon-white"></i></button>';
            sub_que += '</div>';
            sub_que += '</td>';
            sub_que += '</tr>';
            sub_que += '<tr>';
            sub_que += '<th colspan="12">';
            sub_que += '</th>';
            sub_que += '</tr>';
            sub_que += '<tr id="row_' + id + '_' + sub_que_count_val + '"  class = "row_' + id + '_' + sub_que_count_val + '">';
            sub_que += '<td colspan="12">';
            sub_que += '<textarea class="required text_area loginRegex question_textarea" name="question_' + id + '_' + sub_que_count_val + '" id="question_' + id + '_' + sub_que_count_val + '" style="margin: 0px; width: 551px; height: 40px;" ></textarea>';
            sub_que += '</td>';
            sub_que += '</tr>';


            //sub_que += '</tr>';

            //tinyMCE.execCommand("mceAddControl", false, 'question_'+id+'_'+sub_que_count_val);
            //tinyMCE.get('question_'+id+'_'+sub_que_count_val).setContent("");
            /* console.log(msg.co_details)
             console.log(msg.bloom_data)
             var sub_que = '<tr id="row_'+id+'_'+sub_que_count_val+'">';
             sub_que += '<td style="white-space:nowrap;" class="textwrap">';
             sub_que += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="ques_nme_'+id+'_'+sub_que_count_val+'" id="ques_nme_'+id+'_'+sub_que_count_val+'" value="Q No '+id+'-'+sub_que_counter+'" class="input-mini ques_nme_'+id+'" readonly />';
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
             sub_que += '<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="bloom_list_'+id+'_'+sub_que_count_val+'" id="bloom_list_'+id+'_'+sub_que_count_val+'" class="input-small required bloom_list_data">';
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
             sub_que += '<button class="btn btn-danger delete_subque error_add" type="button" id="delete_subque_'+id+'_'+sub_que_count_val+'" name="delete_subque_'+id+'_'+sub_que_count_val+'" abbr="QNo_'+id+'_'+sub_que_count_val+'"><i class="icon-minus-sign icon-white"></i></button>';
             sub_que += '</div>';
             sub_que += '</td>';
             sub_que += '</tr>';
             sub_que += '<tr id="img_placing_row_'+id+'_'+sub_que_count_val+'">';
             sub_que += '<td id=""></td>';
             sub_que += '<td id="place_img_'+id+'_'+sub_que_count_val+'" colspan="6"></td>';
             sub_que += '</tr>';
             sub_que += '<tr id="img_name_text_fields_'+id+'_'+sub_que_count_val+'">';
             sub_que += '<td id="placed_img_name_'+id+'_'+sub_que_count_val+'" colspan="8"></td>';
             sub_que += '</tr>'; */

            //tiny_init();
            //console.log(sub_que);

            var sub_question = $(sub_que);
            sub_question.insertBefore('#sub_que_ref_div_' + id);
            var upload_btn_val = id + '_' + sub_que_count_val;
            //register_button(upload_btn_val);

            var textAreaID = 'question_' + id + '_' + sub_que_count_val;
            $(this).parent().find('.text_area').html(sub_que).ready(function () {
                tinyMCE.execCommand('mceAddEditor', false, textAreaID);
            });

        }
    });
    //tinyMCE.execCommand('mceAddControl', true, 'question_'+id+'_'+sub_que_count_val);
    //tiny_init();

});

$('.qp_table').on('change', '.co_onchange', function () {
    //$( this ).off( event );
    var ele_id = $(this).attr('id');
    var co_data = ele_id.split('_');
    var main_q_id = co_data[2];
    var sub_q_id = co_data[3];


    var co_id = $(this).val();
    var crclm_id = $('#crclm_id').val();
    var term_id = $('#term_id').val();
    var crs_id = $('#crs_id').val();

    var post_data = {
        'co_id': co_id,
        'crclm_id': crclm_id,
        'term_id': term_id,
        'crs_id': crs_id
    }
    $.ajax({type: "POST",
        url: base_url + 'question_paper/manage_model_qp/po_list_data',
        data: post_data,
        dataType: "JSON",
        success: function (msg)
        {
            console.log(msg.entity_data[0]['qpf_config']);
            var entity_val = msg.entity_data[0]['qpf_config'];
            if (parseInt(entity_val) != 0) {
                var size = $.trim(msg.po_result.length);

                var i;
                $('#po_list_' + main_q_id + '_' + sub_q_id).empty();
                var my_ops = '';//$('<option></option>').val('').attr('abbr','Select PO').text('Select');
                $('#po_list_' + main_q_id + '_' + sub_q_id).append(my_ops);
                for (i = 0; i < size; i++) {
                    my_ops = $('<option></option>').val(msg.po_result[i]['po_id']).attr('abbr', msg.po_result[i]['po_statement']).attr('title', msg.po_result[i]['po_reference'] + '.' + msg.po_result[i]['po_statement']).text(msg.po_result[i]['po_reference']);
                    $('#po_list_' + main_q_id + '_' + sub_q_id).append(my_ops);
                }
                $('#po_list_' + main_q_id + '_' + sub_q_id).multiselect('rebuild');
            } else {

                var size = $.trim(msg.po_result.length);


                var select_dropdown = '<select id="po_list_' + main_q_id + '_' + sub_q_id + '" name="po_list_' + main_q_id + '_' + sub_q_id + '[]" multiple="multiple" style="display:none">';
                for (i = 0; i < size; i++) {
                    select_dropdown += '<option value="' + msg.po_result[i]['po_id'] + '" abbr="' + msg.po_result[i]['po_statement'] + '" title = "' + msg.po_result[i]['po_reference'] + '.' + msg.po_result[i]['po_statement'] + '"selected="selected">' + msg.po_result[i]['po_reference'] + '</option>';
                }
                select_dropdown += '</select>';
                $('#po_list_' + main_q_id + '_' + sub_q_id).remove();
                $('#co_attach_' + main_q_id + '_' + sub_q_id).append(select_dropdown);

            }

        }

    });

    $.ajax({type: "POST",
        url: base_url + 'question_paper/manage_model_qp/pi_list_data',
        data: post_data,
        dataType: "JSON",
        success: function (msg)
        {
            console.log(msg.pi_result);
            var size = $.trim(msg.pi_result.length);

            var i;
            var my_ops = ''
            $('#pi_code_' + main_q_id + '_' + sub_q_id).empty();
            //var my_ops =$('<option></option>').val('').attr('abbr','Select PI').text('Select PI');
            $('#pi_code_' + main_q_id + '_' + sub_q_id).append(my_ops);
            for (i = 0; i < size; i++) {
                my_ops = $('<option></option>').val(msg.pi_result[i]['msr_id']).attr('abbr', msg.pi_result[i]['msr_statement']).attr('title', msg.pi_result[i]['msr_statement'] + '-[' + msg.pi_result[i]['pi_codes'] + ']').text(msg.pi_result[i]['pi_codes']);
                $('#pi_code_' + main_q_id + '_' + sub_q_id).append(my_ops);
            }
            $('#pi_code_' + main_q_id + '_' + sub_q_id).multiselect('rebuild');
        }
    });

});
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
$('.qp_table').on('change', '.option_onchange', function () {
    var qpd_unitd_id = $('#unit_list_1_1').val();
    var q_no = $('#ques_nme').val();
    $.ajax({
        type: "POST",
        url: base_url + 'question_paper/manage_model_qp/check_question',
        data: {'qpd_unitd_id': qpd_unitd_id, 'q_no': q_no},
        success: function (data) {
            {

            }
        }
    });

});

//testing-------------------------------------------------------------------------------------------------------------------------------------------------------------


$('.qp_table').on('change', '.unit_onchange', function () {
    var unit_val = $(this).find("option:selected").text();
    $('#save_main_que').prop('disabled', false);
    var $unit_id = $(this).val();
    var qpd_id = $('#qpp_id').val();
    var index = ($('option:selected', $(this)).index());
    if ($unit_id == "") {
        $('#unit_marks').val(" ");
    }

    $.ajax({type: "POST",
        url: base_url + 'question_paper/manage_model_qp/unit_list_data',
        data: {'unit_id': $unit_id, 'qpd_id': qpd_id},
        dataType: 'JSON',
        success: function (msg)
        {
            var attemted_unit_marks = msg['attemted'][0]['SUM(qp_subq_marks)'];
            var total_unit_marks = msg['total_unit'][0]['qp_utotal_marks']
            if (attemted_unit_marks == null) {
                attemted_unit_marks = 0;
            }
            var total_unit_marks = attemted_unit_marks + " / " + total_unit_marks;
            $('#unit_marks').val(total_unit_marks);
            var count = msg['per'][0].qp_total_unitquestion;
            var counter = count;
            //	console.log(msg);
            var size = $.trim(msg['per'].length);

            var i;
            $('#Q_No_' + 1 + '_' + 1).empty();
            $('#questions').empty();
            $('#mar').empty();
            var my_ops = $('<option></option>').val('').attr('abbr', 'Select Main Q.No.').text('Select');
            $('#Q_No_' + 1 + '_' + 1).append(my_ops);
            var k = 0;
            //for(var i=1;i<=3;i++){if(index==i){k=(Number(msg['data'][i].qp_total_unitquestion));}}
            if (index == 1) {
                k = 1;
            } else if (index == 2) {
                k += (Number(msg['data'][0].qp_total_unitquestion) + Number(1));
            } else if (index == 3) {
                k += (Number(msg['data'][0].qp_total_unitquestion) + (Number(msg['data'][1].qp_total_unitquestion)) + Number(1));
            }
            else if (index == 4) {
                k += (Number(msg['data'][0].qp_total_unitquestion) + (Number(msg['data'][1].qp_total_unitquestion)) + (Number(msg['data'][2].qp_total_unitquestion)) + Number(1));
            }
            else if (index == 5) {
                k += (Number(msg['data'][0].qp_total_unitquestion) + (Number(msg['data'][1].qp_total_unitquestion)) + (Number(msg['data'][2].qp_total_unitquestion)) + (Number(msg['data'][2].qp_total_unitquestion)) + Number(1));
            }
            else if (index == 6) {
                k += (Number(msg['data'][0].qp_total_unitquestion) + (Number(msg['data'][1].qp_total_unitquestion)) + (Number(msg['data'][2].qp_total_unitquestion)) + (Number(msg['data'][3].qp_total_unitquestion)) + (Number(msg['data'][3].qp_total_unitquestion)) + Number(1));
            }
            else if (index == 7) {
                k += (Number(msg['data'][0].qp_total_unitquestion) + (Number(msg['data'][1].qp_total_unitquestion)) + (Number(msg['data'][2].qp_total_unitquestion)) + (Number(msg['data'][3].qp_total_unitquestion)) + (Number(msg['data'][4].qp_total_unitquestion)) + (Number(msg['data'][4].qp_total_unitquestion)) + Number(1));
            }
            else if (index == 8) {
                k += (Number(msg['data'][0].qp_total_unitquestion) + (Number(msg['data'][1].qp_total_unitquestion)) + (Number(msg['data'][2].qp_total_unitquestion)) + (Number(msg['data'][3].qp_total_unitquestion)) + (Number(msg['data'][4].qp_total_unitquestion)) + (Number(msg['data'][5].qp_total_unitquestion)) + (Number(msg['data'][5].qp_total_unitquestion)) + Number(1));
            }
            else if (index == 9) {
                k += (Number(msg['data'][0].qp_total_unitquestion) + (Number(msg['data'][1].qp_total_unitquestion)) + (Number(msg['data'][2].qp_total_unitquestion)) + (Number(msg['data'][3].qp_total_unitquestion)) + (Number(msg['data'][4].qp_total_unitquestion)) + (Number(msg['data'][5].qp_total_unitquestion)) + (Number(msg['data'][6].qp_total_unitquestion)) + (Number(msg['data'][6].qp_total_unitquestion)) + Number(1));
            }
            else if (index == 10) {
                k += (Number(msg['data'][0].qp_total_unitquestion) + (Number(msg['data'][1].qp_total_unitquestion)) + (Number(msg['data'][2].qp_total_unitquestion)) + (Number(msg['data'][3].qp_total_unitquestion)) + (Number(msg['data'][4].qp_total_unitquestion)) + (Number(msg['data'][5].qp_total_unitquestion)) + (Number(msg['data'][6].qp_total_unitquestion)) + (Number(msg['data'][7].qp_total_unitquestion)) + (Number(msg['data'][7].qp_total_unitquestion)) + Number(1));
            }
            count = (Number(count) + Number(k));

            for (i = k; i < count; i++) {
                my_ops = $('<option></option>').text(i);
                //my_ops = $('<option></option>').val(msg[0].qpf_num_mquestions).attr('abbr',msg[0].qpf_num_mquestions).text(msg[0].qpf_num_mquestions);
                $('#Q_No_' + 1 + '_' + 1).append(my_ops);

            }
            //$('#qp_max_marks').val((msg['total_attempted']));
            $('#qp_max_marks').append((msg['total_attempted']));
            $('#mar').append("[" + msg['attemted'][0]['SUM(qp_subq_marks)'] + "/" + msg['total_unit'][0]['qp_utotal_marks'] + "]");
            var c = msg['c'];
            $('#questions_one').show();
            $('#questions').show();
            var questions_display = '';
            var j;
            questions_display += '<div style="border:1px solid #ddd; position:relative; margin:0 0; padding: 10px 20px 10px;">';
            questions_display += '<b>' + unit_val + ' :- Question No. and their respective maximum marks.</b><br>';
            for (j = 0; j < c; j++) {
                var qcode = msg.qcode[j]['qp_subq_code'];
                var que_val = qcode.split('_');
                var q_marks = msg.qcode[j]['qp_subq_marks'];
                //questions_display += que_val[2]+''+que_val[3]+': '+q_marks+' || ';
                questions_display += qcode + ': ' + q_marks + ' || ';
                //$('#questions').append(" <b>Question No: "+qcode+" and </b>  <b> Marks: "+q_marks+" | </b>"); 
            }
            questions_display += '</div>';
            $('#questions').append(questions_display);
        }
    });

});


function question_remain(unit_id, Q_no) {
    var unit_val = $("#unit_list_1_1").find("option:selected").text();
    $('#save_main_que').prop('disabled', false);
    var $unit_id = unit_id;
    var qpd_id = $('#qpp_id').val();
    var index = ($('option:selected', $('#unit_list_1_1')).index());

    if ($unit_id == "") {
        $('#unit_marks').val(" ");
    }
    $.ajax({type: "POST",
        url: base_url + 'question_paper/manage_model_qp/unit_list_data',
        data: {'unit_id': $unit_id, 'qpd_id': qpd_id},
        dataType: 'JSON',
        success: function (msg)
        {
            var attemted_unit_marks = msg['attemted'][0]['SUM(qp_subq_marks)'];
            var total_unit_marks = msg['total_unit'][0]['qp_utotal_marks']
            if (attemted_unit_marks == null) {
                attemted_unit_marks = 0;
            }
            var total_unit_marks = attemted_unit_marks + " / " + total_unit_marks;
            $('#unit_marks').val(total_unit_marks);
            var count = msg['per'][0].qp_total_unitquestion;
            var counter = count;
            console.log(msg);
            var size = $.trim(msg['per'].length);
            var i;
            $('#Q_No_' + 1 + '_' + 1).empty();
            $('#questions').empty();
            $('#mar').empty();
            var my_ops = $('<option></option>').val('').attr('abbr', 'Select Main Q.NO.').text('Select');
            $('#Q_No_' + 1 + '_' + 1).append(my_ops);
            var k = 0;
            //for(var i=1;i<=3;i++){if(index==i){k=(Number(msg['data'][i].qp_total_unitquestion));}}
            if (index == 1) {
                k = 1;
            } else if (index == 2) {
                k += (Number(msg['data'][0].qp_total_unitquestion) + Number(1));
            } else if (index == 3) {
                k += (Number(msg['data'][0].qp_total_unitquestion) + (Number(msg['data'][1].qp_total_unitquestion)) + Number(1));
            }
            else if (index == 4) {
                k += (Number(msg['data'][0].qp_total_unitquestion) + (Number(msg['data'][1].qp_total_unitquestion)) + (Number(msg['data'][2].qp_total_unitquestion)) + Number(1));
            }
            else if (index == 5) {
                k += (Number(msg['data'][0].qp_total_unitquestion) + (Number(msg['data'][1].qp_total_unitquestion)) + (Number(msg['data'][2].qp_total_unitquestion)) + (Number(msg['data'][2].qp_total_unitquestion)) + Number(1));
            }
            else if (index == 6) {
                k += (Number(msg['data'][0].qp_total_unitquestion) + (Number(msg['data'][1].qp_total_unitquestion)) + (Number(msg['data'][2].qp_total_unitquestion)) + (Number(msg['data'][3].qp_total_unitquestion)) + (Number(msg['data'][3].qp_total_unitquestion)) + Number(1));
            }
            else if (index == 7) {
                k += (Number(msg['data'][0].qp_total_unitquestion) + (Number(msg['data'][1].qp_total_unitquestion)) + (Number(msg['data'][2].qp_total_unitquestion)) + (Number(msg['data'][3].qp_total_unitquestion)) + (Number(msg['data'][4].qp_total_unitquestion)) + (Number(msg['data'][4].qp_total_unitquestion)) + Number(1));
            }
            else if (index == 8) {
                k += (Number(msg['data'][0].qp_total_unitquestion) + (Number(msg['data'][1].qp_total_unitquestion)) + (Number(msg['data'][2].qp_total_unitquestion)) + (Number(msg['data'][3].qp_total_unitquestion)) + (Number(msg['data'][4].qp_total_unitquestion)) + (Number(msg['data'][5].qp_total_unitquestion)) + (Number(msg['data'][5].qp_total_unitquestion)) + Number(1));
            }
            else if (index == 9) {
                k += (Number(msg['data'][0].qp_total_unitquestion) + (Number(msg['data'][1].qp_total_unitquestion)) + (Number(msg['data'][2].qp_total_unitquestion)) + (Number(msg['data'][3].qp_total_unitquestion)) + (Number(msg['data'][4].qp_total_unitquestion)) + (Number(msg['data'][5].qp_total_unitquestion)) + (Number(msg['data'][6].qp_total_unitquestion)) + (Number(msg['data'][6].qp_total_unitquestion)) + Number(1));
            }
            else if (index == 10) {
                k += (Number(msg['data'][0].qp_total_unitquestion) + (Number(msg['data'][1].qp_total_unitquestion)) + (Number(msg['data'][2].qp_total_unitquestion)) + (Number(msg['data'][3].qp_total_unitquestion)) + (Number(msg['data'][4].qp_total_unitquestion)) + (Number(msg['data'][5].qp_total_unitquestion)) + (Number(msg['data'][6].qp_total_unitquestion)) + (Number(msg['data'][7].qp_total_unitquestion)) + (Number(msg['data'][7].qp_total_unitquestion)) + Number(1));
            }
            count = (Number(count) + Number(k));
            for (i = k; i < count; i++) {
                my_ops = $('<option></option>').text(i);
                //my_ops = $('<option></option>').val(msg[0].qpf_num_mquestions).attr('abbr',msg[0].qpf_num_mquestions).text(msg[0].qpf_num_mquestions);
                $('#Q_No_' + 1 + '_' + 1).append(my_ops);

            }

            var n = Q_no.toString();
            $('#Q_No_1_1').val(n);
            //$('#qp_max_marks').val((msg['total_attempted']));
            $('#qp_max_marks').append((msg['total_attempted']));
            $('#mar').append("[" + msg['attemted'][0]['SUM(qp_subq_marks)'] + "/" + msg['total_unit'][0]['qp_utotal_marks'] + "]");
            var c = msg['c'];
            $('#questions_one').show();
            $('#questions').show();
            var questions_display = '';
            var j = '';
            console.log(msg.qcode);
            questions_display += '<div style="border:1px solid #ddd; position:relative; margin:0 0; padding: 10px 20px 10px;">';
            questions_display += '<b>' + unit_val + ' :- Question No. and their respective maximum marks.</b><br>';
            for (j = 0; j < c; j++) {
                var qcode = msg.qcode[j]['qp_subq_code'];
                var que_val = qcode.split('_');
                var q_marks = msg.qcode[j]['qp_subq_marks'];
                //questions_display += que_val[2]+''+que_val[3]+': '+q_marks+' || ';
                //added by bhagya S S
                questions_display += qcode + ': ' + q_marks + ' || ';

                //$('#questions').append(" <b>Question No: "+qcode+" and </b>  <b> Marks: "+q_marks+" | </b>"); 
            }
            questions_display += '</div>';
            $('#questions').append(questions_display);
            //$().val();
        }
    });

}
//

//-----------------------------------------------------------------------------------------------------------------------------------------------------------



$('#qp_unit_table').on('click', '.delete_subque', function () {

    var abbr_val = $(this).attr('abbr');
    var id_val = this.id.replace('delete_subque_', "");
    var main_id = id_val.split('_');
    $('.row_' + id_val).remove();
    $('#img_placing_row_' + id_val).remove();
    $('#img_name_text_fields_' + id_val).remove();

    var abbr_array = id_val.split('_');
    var index_id = abbr_array[0];

    var sub_que_val = abbr_array[1];
    var index_val = functiontofindIndexByKeyValue(sub_que_array, index_id, sub_que_val);

    sub_que_array.splice(index_val, 1);
    console.log(sub_que_array);

    // renaming questions
    var alpha = 'a'
    $('.ques_nme_' + main_id[0]).each(function () {

        var question_id = $(this).attr('id');

        $('#' + question_id).val('Q No ' + main_id[0] + '-' + alpha);
        $('#add_subque_' + main_id[0]).attr('abbr', 'QNo_' + main_id[0] + '_' + alpha)
        alpha = String.fromCharCode(alpha.charCodeAt() + 1);

    });
    var que_alpha = 'a'
    $('.question_name_' + main_id[0]).each(function () {
        var question_id = $(this).attr('id');
        $('#' + question_id).val('Q_No_' + main_id[0] + '_' + que_alpha);
        //$('#add_subque_'+main_id[0]).attr('abbr','QNo_'+main_id[0]+'_'+alpha)
        que_alpha = String.fromCharCode(que_alpha.charCodeAt() + 1);
    });

});


function functiontofindIndexByKeyValue(arraytosearch, key, valuetosearch) {

    for (var i = 0; i < arraytosearch.length; i++) {

        if (arraytosearch[i].index == key && arraytosearch[i].value == valuetosearch) {
            return i;
        }
    }
    return null;
}

// image upload function starts from here

$(document).ready(function () {
    var counter = $('#total_counter').val();

    for (var i = 1; i <= counter; i++) {
        counter_val = i + '_1';
        sub_que_array.push({index: i, value: 1});
        //register_button(counter_val);
    }

});

/* function register_button(value) {
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
 img_counter++;
 
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
 } */

// function to delete the image
$('.image_remove').live('click', function () {
    var btn_id = $(this).attr("id").replace('romove_image', '');
    var remove_img = btn_id.split('_');

    $('#img_thmb_' + btn_id).remove();
    $('#image_hidden_' + btn_id).remove();
});


// function to set all main question as mandatory for the unit if the unit chosen as mandatory.

$('.main_unit').on('change', function () {
    var unit_val = $(this).attr('abbr');
    if ($(this).is(":checked")) {
        $('.' + unit_val).each(function () {

            $(this).prop('checked', true);
        })
    } else {
        $('.' + unit_val).each(function () {
            $(this).prop('checked', false);

        })
    }
});


// Form validation

$(document).ready(function () {
    $.validator.addMethod("loginRegex", function (value, element) {
        return this.optional(element) || /^[^~,]+$/i.test(value);
    }, "Field must contain only letters, spaces, ' or dashes or dot");

    $.validator.addMethod("numeric", function (value, element) {
        var regex = /^[0-9\s]+$/; //this is for numeric... you can do any regular expression you like...
        return this.optional(element) || regex.test(value);
    }, "Only numbers.");
});
$("#add_form_id").validate({
    errorClass: "help-inline font_color",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).addClass('error');
    },
    errorPlacement: function (error, element) {
        if (element.next().is('.error_add')) {
            error.insertAfter(element.parent(''));
        }
        else {
            error.insertAfter(element);
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('error');
        $(element).addClass('success');
    }

});
$(document).ready(function () {
    $.validator.addMethod("loginRegex", function (value, element) {
        return this.optional(element) || /^[^~,]+$/i.test(value);
    }, "Field must contain only letters, spaces, ' or dashes or dot");

    $.validator.addMethod("numeric", function (value, element) {
        var regex = /^[0-9\s]+$/; //this is for numeric... you can do any regular expression you like...
        return this.optional(element) || regex.test(value);
    }, "Only numbers.");
});
$("#add_question_details").validate({
    errorClass: "help-inline font_color",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).addClass('error');
    },
    errorPlacement: function (error, element) {

        if (element.next().is('.error_add')) {
            error.insertAfter(element.parent(''));
        }
        else {
            //error.insertAfter(element);				 
            error.insertAfter(element.parent(''));
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('error');
        $(element).addClass('success');
    }

});

$("#add_form_id").validate();
$("#add_question_details").validate();
$.validator.addMethod('max_marks_validation', function (value) {
    var maximum_marks = $('#qp_max_marks').val();
    var que_marks = 0;
    $('.mq_marks').each(function () {
        var each_que_marks = $(this).val();
        que_marks = parseInt(que_marks) + parseInt(each_que_marks);
    });

    if (que_marks != maximum_marks) {
        $.validator.messages.max_marks_validation = 'Mismatch in QP Max Marks and Sum of Question Marks ';
    }
    else
    {
        //$.validator.messages.max_marks_validation = '';
        return(que_marks == maximum_marks);
    }
}, $.validator.messages.max_marks_validation);


$('.attempt_question').on('change', function () {
    var attempt_que_data = $(this).attr('id');
    var attempt_que_val = $(this).val();
    var attempt_que_id = attempt_que_data.replace(/[^\d.]/g, '');
    var total_que_val = $('#total_question_' + attempt_que_id).val();
    if (total_que_val < attempt_que_val) {
        $.validator.messages.total_marks_validation = 'Total No. of Questions should be greater than or equal to No. of Questions to Attempt';
        message_text = false;
        return jQuery.validator.methods.total_marks_validation.call(null, message_text, null);
    } else {
        message_text = true;
    }

});

$.validator.addMethod('total_marks_validation', function (value) {
    return message_text;
}, $.validator.messages.total_marks_validation);




$('#save_data').on('click', function () {
    var a = $('#section_name').val();
    var c = $('#topic_list').val();
    var d = $('#bloom_list').val();

    var validation_flag = $('#add_form_id').valid();

    // adding rules for inputs with class 'comment'

    $('#section_name').each(function () {
        $(this).rules("add",
                {
                    loginRegex: true,
                    required: true
                });
    });
    $('#total_duration').each(function () {
        $(this).rules("add",
                {
                    //numeric: true,
                    required: true,
                });
    });
    $('#course_name').each(function () {
        $(this).rules("add",
                {
                    loginRegex: true,
                    required: true
                });
    });
    $('#max_marks').each(function () {
        $(this).rules("add",
                {
                    numeric: true,
                    required: true,
                    num: true
                });
    });

    $('#qp_max_marks').each(function () {
        $(this).rules("add",
                {
                    max_marks_validation: true
                });
    });

    $('#qp_notes').each(function () {
        $(this).rules("add",
                {
                    loginRegex: true,
                    required: true
                });
    });

    $('.total_question').each(function () {
        $(this).rules("add",
                {
                    numeric: true,
                    required: true
                });
    });

    $('.attempt_question').each(function () {
        $(this).rules("add",
                {
                    numeric: true,
                    required: true,
                    total_marks_validation: true,
                });
    });

    $('.text_area').each(function () {
        $(this).rules("add",
                {
                    loginRegex: false,
                    required: true
                });
    });
    $('.co_list_data').each(function () {
        $(this).rules("add",
                {
                    //loginRegex: true,
                    required: true
                });
    });
    $('.po_list_data').each(function () {
        $(this).rules("add",
                {
                    //loginRegex: true,
                    required: true
                });
    });
    /* $('.topic_list_data').each(function() {
     $(this).rules("add", 
     {
     loginRegex: true,
     required : true
     }); 
     });*/
    //	loginRegex: true,
    $('.pi_code_data').each(function () {
        $(this).rules("add",
                {
                    //loginRegex: true,
                    required: false
                });
    });
    $('.mq_marks').each(function () {
        $(this).rules("add",
                {
                    numeric: true,
                    required: true
                });
    });
    $('#sec_id').each(function () {
        $(this).rules("add",
                {
                    numeric: true,
                    required: true
                });
    });

    var qp_values = $('#sub_que_count1').val();
    var paramJSON = JSON.stringify(sub_que_array);
    $('#array_data').val(paramJSON);

    if (validation_flag == true) {
        $('#loading').show();
        $('#add_form_id').submit();
    }

});


function call_modal()
{
    $('#myModal4').modal('show');

}

/////////////////////////////////////////////////
function tool_tip_on(value) {
    var opt_val = $("#" + value + " option:selected").attr('abbr');
    $('#' + value).attr('data-original-title', opt_val);
    $('#' + value).tooltip('show');
}
$('.co_list_data').on('change', function () {
    if ($(this).val() != '') {
        $(this).tooltip('destroy');
        var id = $(this).attr('id');
        var opt_val = $("#" + id + " option:selected").attr('abbr');
        $(this).attr('data-original-title', opt_val);
        $(this).tooltip('hide');
    } else {
        $(this).attr('data-original-title', 'Select CO');
    }

});

$('.po_list_data').on('change', function () {
    if ($(this).val() != '') {
        $(this).tooltip('destroy');
        var id = $(this).attr('id');
        var opt_val = $("#" + id + " option:selected").attr('abbr');
        $(this).attr('data-original-title', opt_val);
        $(this).tooltip('hide');
    } else {
        $(this).attr('data-original-title', 'Select CO');
    }

});

$('.bloom_list_data').on('change', function () {
    if ($(this).val() != '') {
        $(this).tooltip('destroy');
        var id = $(this).attr('id');
        var opt_val = $("#" + id + " option:selected").attr('abbr');
        $(this).attr('data-original-title', opt_val);
        $(this).tooltip('hide');
    } else {
        $(this).attr('data-original-title', 'Select CO');
    }

});

$('.topic_list_data').on('change', function () {
    if ($(this).val() != '') {
        $(this).tooltip('destroy');
        var id = $(this).attr('id');
        var opt_val = $("#" + id + " option:selected").attr('abbr');
        $(this).attr('data-original-title', opt_val);
        $(this).tooltip('hide');
    } else {
        $(this).attr('data-original-title', 'Select CO');
    }

});

$('.pi_code_data').on('change', function () {
    if ($(this).val() != '') {
        $(this).tooltip('destroy');
        var id = $(this).attr('id');
        var opt_val = $("#" + id + " option:selected").attr('abbr');
        $(this).attr('data-original-title', opt_val);
        $(this).tooltip('hide');
    } else {
        $(this).attr('data-original-title', 'Select CO');
    }

});

//Tiny MCE script
tinymce.init({
    //selector: "textarea",
    mode: "specific_textareas",
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


function tiny_init() {

    tinymce.init({
        // selector: "textarea",
        mode: "specific_textareas",
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

function publish() {
    var id = $(this).val('id');

}


$(document).ready(function () {

    //$('#Edit_FM').hide();
    if ($('#FM').val() == 0) {

    }
    $("#example").html($(this).html());
    var v = $("#model_qp_existance").val();
    var qpd_id = $('#qpp_id').val();

    $('#update_header').hide();
    $('#update').hide();
    if (v == 1) {
        /* 	$("#qp_title").prop("readonly", true); 
         $("#Grand_total").prop("readonly", true); 
         $("#qp_notes").prop("readonly", true);
         $("#total_duration").prop("readonly", true);
         $("#max_marks").prop("readonly", true); */
        $("#save_header").prop("hide", true);
        $("#save_header").hide();
        $('#edit').show();
    }

    //$("#example").load(function(){
    post_data = {'qpd_id': qpd_id}

    $.ajax({type: "POST",
        url: base_url + 'question_paper/manage_model_qp/Fetch_dum',
        data: post_data,
        //dataType: 'json',
        success: populate_table

    });
    //});
});


/* $('#edit').on('click',function(){
 $("#qp_title").prop("readonly", false);$("#qp_title").prop("readonly", false); $("#Grand_total").prop("readonly", false); $("#qp_notes").prop("readonly", false);$("#total_duration").prop("readonly", false);$("#max_marks").prop("readonly", false);$('#update_header').show(); 
 }); */

$('#update_header').on('click', function () {
    var grand_total = parseInt($('#Grand_total').val());
    var max_marks = parseInt($('#max_marks').val());
    if (grand_total >= max_marks) {
        var mark = 0;
        $('.marks_val').each(function () {
            var cur_mark = $(this).text();
            mark = parseInt(mark) + parseInt(cur_mark);
        });
        if (grand_total >= mark) {
            var data_options = '{"text":"Update Successfull","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
            var options = $.parseJSON(data_options);
            noty(options);
            $('#loading').show();
            var qpd_id = $("#qpp_id").val();
            var qp_title = $("#qp_title").val();
            var time = $("#total_duration").val();
            var Grand_total = $("#Grand_total").val();
            var max_marks = $("#max_marks").val();
            var qp_notes = $('#qp_notes').val();
            if ($('#Model').is(':checked')) {
                qp_model = 1;
            } else {
                qp_model = 0;
            }
            post_data = {'qpd_id': qpd_id, 'qp_title': qp_title, 'time': time, 'Grand_total': Grand_total, 'max_marks': max_marks, 'qp_notes': qp_notes, 'qp_model': qp_model}
            $.ajax({type: "POST",
                url: base_url + 'question_paper/manage_model_qp/update_header',
                data: post_data,
                dataType: 'json',
                success: function (data) {
                    $("#qp_title").prop("readonly", true);
                    $("#Grand_total").prop("readonly", true);
                    $("#qp_notes").prop("readonly", true);
                    $("#total_duration").prop("readonly", true);
                    $("#max_marks").prop("readonly", true);
                    $("#update_header").hide();
                    $("#_header").hide();
                    $('#edit').show();
                    $('#loading').hide();
                }
            });

        } else {
            var data_options = '{"text":"Grand total Marks less than total Question Marks.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
            var options = $.parseJSON(data_options);
            noty(options);
        }
    } else {
        var data_options = '{"text":"Grand total Marks less than Maximum Marks.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
        var options = $.parseJSON(data_options);
        noty(options);
    }


});
$("#section_name").on('keyup', function () {
    $("#l1").html($("#section_name").val());
});
$("#sec_id").on('change', function () {
    $("#l2").html($("#sec_id").val());
});
$('#Q_No_1_1,#sec_id2').change(function () {
    $('#save_main_que').prop('disabled', false);
    $("#ques_nme").val($("#Q_No_1_1").val() + '' + $("#sec_id2").val());
});
$('#error_msg').css('color', 'red');

$('#save_header').on('click', function () {
    var grand_total = parseInt($('#Grand_total').val());
    var max_marks = parseInt($('#max_marks').val());
    {


        // adding rules for inputs with class 'comment'

        $('#qp_title').each(function () {
            $(this).rules("add",
                    {
                        required: true
                    });
        });
        $('#total_duration').each(function () {
            $(this).rules("add",
                    {
                        //numeric: true,
                        required: true,
                    });
        });

        $('#max_marks').each(function () {
            $(this).rules("add",
                    {
                        numeric: true,
                        required: true,
                        num: true
                    });
        });

        $('#Grand_total').each(function () {
            $(this).rules("add",
                    {
                        numeric: true,
                        required: true,
                        num: true
                    });
        });
        var validation_flag = $('#add_form_id').valid();
        if (validation_flag == true) {
            if (grand_total >= max_marks) {
                //var qpf_id = $("#qpf_id").val();
                var qpf_id = 30;
                var b = 2;
               // var qpd_type = 4;
                var crclm_id = $("#crclm_id").val();
                var term_id = $('#term_id').val();
                var crs_id = $("#crs_id").val();
                var pgm_id = $("#pgm_id").val();
                var qp_title = $("#qp_title").val();
                var time = $("#total_duration").val();
                var Grand_total = $("#Grand_total").val();
                var max_marks = $("#max_marks").val();
                var qp_notes = $('#qp_notes').val();
                var ao_id = $('#ao_id').val();
				var qpd_id = $('#qpd_id').val();
				var qpd_type = $('#qpd_type_data').val();				
                if ($('#Model').is(':checked')) {
                    qp_model = 1;
                } else {
                    qp_model = 0;
                }
                var all_unit_marks = 0;
                $('.unit_max_marks').each(function () {
                    all_unit_marks = parseInt(all_unit_marks) + parseInt($(this).val());
                });
                if (isNaN(all_unit_marks)) {
                    all_unit_marks = 0;
                }

                if (all_unit_marks != Grand_total && all_unit_marks != 0) {
                    var data_options = '{"text":"Total unit marks is not matching the grand total.    ","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
                    var options = $.parseJSON(data_options);
                    noty(options);
                } else {

                    var arr = [];
                    var count = $("#expense_table > tbody > tr").length;
                    for (var i = 0; i <= count; i++) {
                        arr.push({val1: $("#units_0" + i).val(), val2: $("#ques_no_0" + i).val(), val3: $("#marks_0" + i).val()});
                    }

                    post_data = {'qp_model': qp_model, 'val': arr, 'b': b, 'count': count, 'qpf_id': qpf_id, 'qpd_type': qpd_type, 'crclm_id': crclm_id, 'term_id': term_id, 'crs_id': crs_id, 'pgm_id': pgm_id, 'qp_title': qp_title, 'time': time, 'max_marks': max_marks, 'Grand_total': Grand_total, 'qp_notes': qp_notes}
                    $.ajax({type: "POST",
                        url: base_url + 'question_paper/manage_model_qp/qp_def',
                        data: post_data,
                        dataType: 'json',
                        success: function (data) {
                            if (data == "false") {
                                $("#error_msg").html("All FIELDS ARE MANDATORY *");
                                $("#qp_title").focus();
                            }
                            else if (data != "false") {
                                $('#p_id').val(data[0]['qpd_id']);
								if(qpd_type == 4){
                                window.location = base_url + 'question_paper/manage_model_qp/generate_model_qp_fm_not_defined/' + pgm_id + '/' + crclm_id + '/' + term_id + '/' + crs_id + '/' +qpd_type;
								}else{								
								window.location = base_url + 'question_paper/manage_model_qp/generate_tee_qp/' + pgm_id + '/' + crclm_id + '/' + term_id + '/' + crs_id + '/' +qpd_type;
								}
                            }
                        }
                    });

                }

            } else {
                var data_options = '{"text":"Grand Total Marks is less Than Maximum Marks.    ","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
                var options = $.parseJSON(data_options);
                noty(options);
            }
        }
    }

});


$.validator.addMethod("num", function (value, element) {
    var regex = /^([1-9][0-9]*)+$/; //this is for numeric... you can do any regular expression you like...
    return this.optional(element) || regex.test(value);
}, "Invalid Marks. ");
jQuery.validator.addMethod("greaterThanZero", function (value, element) {
    return this.optional(element) || (parseFloat(value) > 0);
}, "Invalid Marks");
$('#save_main_que').on('click', function () {




    // adding rules for inputs with class 'comment'

    $('#unit_list_1_1').each(function () {
        $(this).rules("add",
                {
                    required: true
                });
    });
    $('#Q_No_1_1').each(function () {
        $(this).rules("add",
                {
                    //numeric: true,
                    required: true,
                });
    });

    $('#sec_id2').each(function () {
        $(this).rules("add",
                {
                    required: false
                });
    });
    $('#mark').each(function () {
        $(this).rules("add",
                {
                    //	num:true,
                    greaterThanZero: true,
                    required: true
                });
    });
    $('#co_list_1_1').each(function () {
        $(this).rules("add",
                {
                    //loginRegex: true,
                    required: true
                });
    });
    var validation_flag = $('#add_question_details').valid();
    if (validation_flag == true) {
        $('#loading').show();
        $('#save_main_que').prop('disabled', true);
        var co_list = $('#co_list_1_1').val();//
        var qpd_id = $('#qpp_id').val();
        var sec_name = $("#unit_list_1_1").val();
        var sec_name2 = $("#unit_list_1_1 option:selected").text();
        var sec_id = $("#sec_id").val();
        var qes_num = $("#ques_nme").val();
        var qn = qes_num.charAt(5);
        var q_num = $('#Q_No_1_1').val();
        var co = $("#co_list_1_1").val();
        var po = $("#po_list_1_1").val();
        var pi = $("#pi_code_1_1").val();
        var topic = $("#topic_list_1_1").val();
        var bl = $("#bloom_list_1_1").val();
        var mark = $("#mark").val();
        var qp_max_marks = $('#qp_max_marks').val();

        var qp_marks = qp_max_marks.split("/");
        var marks_sum = parseInt(qp_marks[0]) + parseInt(mark);
        var mandatory;
        if ($('#mandatory').is(':checked')) {
            mandatory = 1;
        } else {
            mandatory = 0;
        }

        tinyMCE.triggerSave();
        tinyMCE.activeEditor.focus();
        var str = tinymce.get("question_1_1").getContent();
        var str1 = str.replace("<p>", " ");
        var question = str1.replace("</p>", " ");
        var count = $("#expense_table > tbody > tr").length;



        post_data = {'qpd_id': qpd_id, 'sec_name': sec_name, 'qn': qn, 'sec_name2': sec_name2, 'sec_id': sec_id, 'qes_num': qes_num, 'co': co, 'pi': pi, 'po': po, 'topic': topic, 'bl': bl, 'mark': mark, 'question': question, 'mandatory': mandatory, 'q_num': q_num}
        if (marks_sum <= parseInt(qp_marks[1])) {
            $.ajax({type: "POST",
                url: base_url + 'question_paper/manage_model_qp/add_qp_dat',
                data: post_data,
                dataType: 'json',
                success: function (data) {

                    console.log(data);
                    var new_data = parseInt($.trim(data.marks));
                    if (new_data > 0) {
                        $("#unit_marks").val(" ");
                        if (data.entity == 0) {
                            $('#po_list_1_1').remove();
                        } else {
                            $('#po_list_1_1').empty();
                            $('#po_list_1_1').multiselect('rebuild');
                        }
                        success_modal();
                        $('#save_main_que').prop('disabled', false);
                        $('#unit_list_1_1').find('option:selected').prop('selected', false);
                        $('#Q_No_1_1').find('option:selected').prop('selected', false);
                        $('#sec_id2').find('option:first').prop('selected', true);
                        $('#co_list_1_1').find('option:selected').prop('selected', false);
                        $('#co_list_1_1').multiselect('rebuild');
                        $('#topic_list_1_1').find('option:selected').prop('selected', false);
                        $('#topic_list_1_1').multiselect('rebuild');
                        $('#bloom_list_1_1').find('option:selected').prop('selected', false);
                        $('#pi_code_1_1').find('option:selected').prop('selected', false);
                        $('#pi_code_1_1').multiselect('rebuild');
                        $('#bloom_list_1_1').multiselect('rebuild');

                        $('#question_1_1').text('');

                        $('#ques_nme').val('');
                        $('#mark').val('');
                        $('#mandatory').prop('checked', false);
                        tinyMCE.get('question_1_1').setContent('');
                        $('#qp_max_marks').val($.trim(data.marks) + '/' + qp_marks[1]);
                    } else if (new_data == -1) {
                        data_full_modal();

                    } else {
                        fail_modal();

                    }
                    post_data = {'qpd_id': qpd_id};

                    $('#loading').hide();
                    $.ajax({type: "POST",
                        url: base_url + 'question_paper/manage_model_qp/Fetch_dum',
                        data: post_data,
                        //dataType: 'json',
                        success: populate_table});
                }
            });
        } else {
            exceeind_grand_total();
        }
    }
});



function success_modal(msg) {//$('#myModal_suc').modal('show'); 
    var data_options = '{"text":"Your data has been saved successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);

}
function fail_modal(msg) {//$('#myModal_fail').modal('show');
    $('#save_main_que').prop('disabled', false);
    $('#loading').hide();
    var data_options = '{"text":"This question number already exist.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}
function exceed_fail_modal(msg) {//$('#myModal_fail').modal('show');
    $('#save_main_que').prop('disabled', false);
    $('#loading').show();
    var data_options = '{"text":"You cannot add ' + msg + ' because Sum of all unit marks exceeding question paper Grand total marks.   ","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
    setTimeout(function ()
    {
        location.reload();
    }, 6000);
}
function data_full_modal(msg) {//$('#myModal_data_full').modal('show');
    $('#save_main_que').prop('disabled', false);
    $('#loading').hide();
    var data_options = '{"text":"Marks Exceeding for this Unit.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}
function exceeind_grand_total(msg) {//$('#myModal_data_full').modal('show');
    $('#save_main_que').prop('disabled', false);
    $('#loading').hide();
    var data_options = '{"text":"The total sum of max marks of all the questions exceeding grand total marks verify.   ","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}


function populate_table(msg) {
    $('#example').dataTable().fnDestroy();
    $('#tee_qp_data').html(msg);
    // $('#example').dataTable().fnDestroy();
    // $('#example').dataTable(
    // {"aoColumns": [
    // {"sTitle": "Unit Name", "mData": "sl_no"},
    // {"sTitle": "Question No", "mData": "sec_name"},
    // {"sTitle": "Question", "mData": "question"},
    // {"sTitle": "CO Code", "mData": "co_code"},
    // {"sTitle": "Topic Title", "mData": "topic_title"},
    // {"sTitle": "Blooms Level", "mData": "level_data"},
    // {"sTitle": "PO Reference", "mData": "po_data"},
    // {"sTitle": "Marks", "mData": "marks"},
    // {"sTitle": "Add/Edit Weightage", "mData": "Weightage"},
    // {"sTitle": "Edit", "mData": "edit"},
    // {"sTitle": "Delete", "mData": "Delete"},

    // ], "aaData": msg,

    // "sPaginationType": "bootstrap"});

    $('#example').dataTable().fnDestroy();

    $('#example').dataTable({
        "fnDrawCallback": function () {
            $('.group').parent().css({'background-color': '#C7C5C5'});
        },
        //"aaSorting": [[1, 'asc']],
        "aoColumnDefs": [
            //{ "sSortDataType": "dom-text", "aTargets": [ 1 ] },
            {"sType": "natural", "aTargets": [1]}
        ],
        "sPaginationType": "bootstrap"

    }).rowGrouping({iGroupingColumnIndex: 0,
        bHideGroupingColumn: true});


}




function populate_table_data(msg) {

    $('#example').dataTable().fnDestroy();
    $('#example').dataTable(
            {"aoColumns": [
                    {"sTitle": "Unit Namee", "mData": "sl_no"},
                ], "aaData": msg,
                "sPaginationType": "bootstrap"});

    $('#example').dataTable().fnDestroy();

    $('#example').dataTable({"fnDrawCallback": function () {
            $('.group').parent().css({'background-color': '#C7C5C5'});
        }
    }).rowGrouping({iGroupingColumnIndex: 0,
        bHideGroupingColumn: true});


}



$('#reload_data').on('click', function () {
    window.reload();
});


$('#example').on('click', '.delete_qp', function (e) {
    var id = $(this).attr('id');
    var qpd_id = $(this).data('qpd_id');
    $('#myModal4').data('id', id).modal('show');
    $('#myModal4').data('qpd_id', qpd_id).modal('show');
});


$('#example').on('click', '.edit_qp', function (e) {
    $('#tabl').find('span').html('');
    $('#co_list_1_1').find('option:selected').prop('selected', false);
    $('#co_list_1_1').multiselect('rebuild');
    $('#topic_list_1_1').find('option:selected').prop('selected', false);
    $('#topic_list_1_1').multiselect('rebuild');
    $('#bloom_list_1_1').find('option:selected').prop('selected', false);
    $('#bloom_list_1_1').multiselect('rebuild');
    $('#po_list_1_1').empty();

    $('#nav_title').empty();
    $('#questions').empty();
    $('#nav_title').html('Edit Question');
    var q_id = $(this).attr('id');
    var question_no = q_id.split("");
    var c_q = question_no.length;
    var marks = $(this).attr('data-marks');
    var qp_mq_id = $(this).attr('data-qp_mq_id');
    var qp_content = $(this).attr('data-qp_content');
    var data_qp_map_id = $(this).attr('data-qp_mq_id');
    var qpd_id = $('#qpp_id').val();
    var unit_id = $(this).attr('data-qpd_unit_id');
    var qp_max_marks = $('#qp_max_marks').val();
    var qp_marks = qp_max_marks.split("/");
    var crclm_id = $("#crclm_id").val();
    var term_id = $('#term_id').val();
    var crs_id = $("#crs_id").val();
    post_data = {'qp_map_id': data_qp_map_id,
        'crclm_id': crclm_id,
        'term_id': term_id,
        'crs_id': crs_id,
        'qpd_id': qpd_id,
        'unit_id': unit_id}
    $.ajax({type: 'POST',
        url: base_url + 'question_paper/manage_model_qp/fetch_Mapping_data',
        data: post_data,
        dataType: 'json',
        success: function (data) {

            $('#unit_marks').val(data['total_attemted_marks'][0]['SUM(qp_subq_marks)'] + " / " + data['total_unit_marks'][0]['qp_utotal_marks']);
            //console.log(data.level_mapping_data[0]['actual_mapped_id']);
            //console.log(data.mapped_po_data);
            var co_size = data.co_map_data.length;
            var topic_size = data.topic_mapping_data.length;
            var level_size = data.level_mapping_data.length;
            var pi_size = data.pi_mapping_data.length;
            var po_size = data.po_list.length;
            var co_rel_po = data.mapped_po_data.length;
            var entity_po = data.entity_config.length;
            var total_marks = data.marks_total
            $('#qp_max_marks').val(total_marks + '/' + qp_marks[1]);
            var qp_mq_code = data.qp_mq_code;
            var mandet = data.mandatory[0]['qp_mq_flag'];
            if (mandet == 1) {
                $('#mandatory').prop('checked', true);
            } else {
                $('#mandatory').prop('checked', false);
            }
            var c;
            tinymce.remove();
            $('#edit_qp').modal('show');
            for (c = 0; c < co_size; c++) {
                console.log(data.co_map_data[c]['actual_mapped_id']);
                $("#co_list_1_1 option[value='" + data.co_map_data[c]['actual_mapped_id'] + "']").attr("selected", "selected");
            }
            $("#co_list_1_1").multiselect('rebuild');

            if (data.entity_config[0]['qpf_config'] == 0) {

                var select_dropdown = '<select id="po_list_1_1" name="po_list_1_1[]" multiple="multiple" style="display:none;" >';
                for (var p = 0; p < co_rel_po; p++) {
                    select_dropdown += '<option value="' + data.mapped_po_data[p]['po_id'] + '" title="' + data.mapped_po_data[p]['po_statement'] + '" abbr="' + data.mapped_po_data[p]['po_statement'] + '" selected="selected">' + data.mapped_po_data[p]['po_reference'] + '</option>';

                }
                $('#co_attach_1_1').append(select_dropdown);

            } else {
                for (var p = 0; p < co_rel_po; p++) {
                    $("#po_list_1_1").append($('<option>', {value: data.mapped_po_data[p]['po_id'], title: data.mapped_po_data[p]['po_statement'], text: data.mapped_po_data[p]['po_reference']}));
                }
                for (var po = 0; po < po_size; po++) {
                    $("#po_list_1_1 option[value='" + data.po_list[po]['actual_mapped_id'] + "']").attr("selected", "selected");
                }
                $("#po_list_1_1").multiselect('rebuild');
            }


            $("#po_list_1_1").multiselect('rebuild');

            for (var pi = 0; pi < pi_size; pi++) {
                $("#pi_code_1_1 option[value='" + data.pi_mapping_data[pi]['actual_mapped_id'] + "']").attr("selected", "selected");
            }
            $("#pi_code_1_1").multiselect('rebuild');
            for (var tp = 0; tp < topic_size; tp++) {
                $("#topic_list_1_1 option[value='" + data.topic_mapping_data[tp]['actual_mapped_id'] + "']").attr("selected", "selected");
            }
            $("#topic_list_1_1").multiselect('rebuild');
            for (var bl = 0; bl < level_size; bl++) {
                $("#bloom_list_1_1 option[value='" + data.level_mapping_data[bl]['actual_mapped_id'] + "']").attr("selected", "selected");
            }
            $("#bloom_list_1_1").multiselect('rebuild');
            /* $("#bloom_list_1_1 option[value='" + data.level_mapping_data[0]['actual_mapped_id'] + "']").attr("selected","selected");	
             $("#bloom_list_1_1").multiselect('rebuild'); */
            $("#ques_nme").val(q_id);
            $('#mark').val(marks);
            $("#qp_mq_id").val(qp_mq_id);
            $("#question_1_1").val(qp_content);
            $("#ques_nme").prop("readonly", true);
            if (c_q > 2) {
                $("#sec_id2").val(question_no[2]);
            }
            else {
                $("#sec_id2").val(question_no[1]);
            }
            $('#unit_list_1_1').val(unit_id);
            $('#save_main_que').hide();
            $('#fetch').hide();
            $('#main_que_row_head').hide();
            //$('#main_que_row').hide();
            $('#del').hide();
            $('#update').show();
            $('#delet').show();
            tiny_init();
            question_remain(unit_id, qp_mq_code);
        }
    });

});

$('#Add_Question').on('click', function () {

    var unit_sum = $('#unit_sum').val();
    var Grand_total = $('#Grand_total_h').val();
    if (unit_sum == Grand_total) {
        $('#tabl').find('span').html('');
        $('#unit_list_1_1').show();
        $("#Q_No_1_1").show();
        $('#save_main_que').show();
        $('#sec_id2').show();
        $('#main_que_row_head').show();
        $('#main_que_row').show();
        $("#ques_nme").val('');
        $("#mark").val('');
        $('#questions').empty();
        $('#nav_title').empty();
        $('#nav_title').html('Add Question');
        $('#update').hide();
        tinymce.remove();
        $('#question_1_1').text('');
        var qpd_id = $('#qpp_id').val();
        var qp_max_marks = $('#qp_max_marks').val();
        var qp_marks = qp_max_marks.split("/");
        var post_data = {'qpd_id': qpd_id};
        var total_marks;
        $.ajax({type: 'POST',
            url: base_url + 'question_paper/manage_model_qp/fetch_total_marks',
            data: post_data,
            dataType: 'json',
            success: function (data) {
                total_marks = parseInt($.trim(data.marks));
                $('#qp_max_marks').val(total_marks + '/' + qp_marks[1]);
                var unit_size = data.units.length;
                $("#unit_list_1_1").empty();
                $("#unit_list_1_1").append($('<option>', {value: '', text: 'Select'}));
                for (var u = 0; u < unit_size; u++) {
                    $("#unit_list_1_1").append($('<option>', {value: data.units[u]['qpd_unitd_id'], title: data.units[u]['qp_unit_code'], text: data.units[u]['qp_unit_code']}));

                }
            }
        });
        $('#edit_qp').modal('show');
        //$('#co_list_1_1').empty();
        $('#unit_list_1_1').find('option:selected').prop('selected', false);
        $('#Q_No_1_1').find('option:selected').prop('selected', false);
        $('#sec_id2').find('option:first').prop('selected', true);
        $('#co_list_1_1').find('option:selected').prop('selected', false);
        $('#topic_list_1_1').find('option:selected').prop('selected', false);
        $('#bloom_list_1_1').find('option:selected').prop('selected', false);
        $('#po_list_1_1').find('option:selected').prop('selected', false);
        $('#po_list_1_1').empty();
        $('#pi_code_1_1').find('option:selected').prop('selected', false);
        $('.co_list_data').multiselect({
            includeSelectAllOption: true,
            buttonWidth: '110px',
            nonSelectedText: 'Select CO',
            templates: {
                button: '<button type="button" class="input-small multiselect btn btn-link co_multi dropdown-toggle" data-toggle="dropdown"></button>'
            }

        });
        $('.co_list_data').multiselect('rebuild');

        $('.po_list_data').multiselect({
            includeSelectAllOption: true,
            buttonWidth: '110px',
            nonSelectedText: 'Select PO',
            templates: {
                button: '<button type="button" class="multiselect btn btn-link co_multi dropdown-toggle" data-toggle="dropdown"></button>'
            }

        });
        $('.po_list_data').multiselect('rebuild');

        $('.pi_code_data').multiselect({
            includeSelectAllOption: true,
            buttonWidth: '140px',
            nonSelectedText: 'Select PO',
            templates: {
                button: '<button type="button" class="input-small multiselect btn btn-link co_multi dropdown-toggle" data-toggle="dropdown"></button>'
            }

        });
        $('.pi_code_data').multiselect('rebuild');

        $('.topic_list_data').multiselect({
            includeSelectAllOption: true,
            buttonWidth: '110px',
            nonSelectedText: 'Select Topic',
            templates: {
                button: '<button type="button" class="multiselect btn btn-link co_multi dropdown-toggle" data-toggle="dropdown"></button>'
            }
        });
        $('.topic_list_data').multiselect('rebuild');

        $('.bloom_list_data').multiselect({
            buttonWidth: '150px',
            nonSelectedText: 'Select Level',
            templates: {
                button: '<button type="button" class="multiselect btn btn-link co_multi dropdown-toggle" data-toggle="dropdown"></button>'
            }
        });
        $('.bloom_list_data').multiselect('rebuild');


        tiny_init();
        tinyMCE.get('question_1_1').setContent(''); // to remove the 
    } else {
        var data_options = '{"text":"Missmatch in Grand Total and Unit total Sum.    ","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
        var options = $.parseJSON(data_options);
        noty(options);
    }
});


$('#example').on('click', '.weightage_add', function () {
    var main_que_id = $(this).attr('data_qp_mq_id');
    $('#main_question_id').val(main_que_id);
    $('#myModal_mapping_weightage').modal({dynamic: true});
    var post_data = {'main_que_id': main_que_id};
    $.ajax({type: 'POST',
        url: base_url + 'question_paper/manage_model_qp/fetch_mapped_weightage',
        data: post_data,
        dataType: 'json',
        success: function (data) {
            console.log(data.co_table);
            $('#co_weight_mapped_table').html(data.co_table);
            $('#po_weight_mapped_table').html(data.po_table);

        }
    });
});

$('#myModal_mapping_weightage').on('click', '#save_weight', function () {
    var main_question_id = $('#main_question_id').val();
    var co_weight_array = new Array();
    var co_map_id_array = new Array();
    var po_weight_array = new Array();
    var po_map_id_array = new Array();
    var co_weight_count = $('#co_weight_count').val();
    for (var c = 0; c < co_weight_count; c++) {
        co_weight_array.push($('#co_weight_' + c).val());
        co_map_id_array.push($('#co_map_id_' + c).val());
    }

    var po_weight_count = $('#po_weight_count').val();
    for (var p = 0; p < po_weight_count; p++) {
        po_weight_array.push($('#po_weight_' + p).val());
        po_map_id_array.push($('#po_map_id_' + p).val());
    }

    var post_data = {'co_weight_array': co_weight_array,
        'co_map_id_array': co_map_id_array,
        'po_weight_array': po_weight_array,
        'po_map_id_array': po_map_id_array,
        'main_question_id': main_question_id};

    $.ajax({type: 'POST',
        url: base_url + 'question_paper/manage_model_qp/update_weightage',
        data: post_data,
        dataType: 'json',
        success: function (data) {
            console.log(data);
            if (data == 0) {
                var data_options = '{"text":"Data Updated Successfully.    ","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                var options = $.parseJSON(data_options);
                noty(options);
                $('#myModal_mapping_weightage').modal('hide');
            } else {
                var data_options = '{"text":" Data Not Updated.    ","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
                var options = $.parseJSON(data_options);
                noty(options);
            }

        }
    });
})

$('#delet').on('click', function () {
    window.reload();
});
$.validator.addMethod("num", function (value, element) {
    var regex = /^([1-9][0-9]*)+$/; //this is for numeric... you can do any regular expression you like...
    return this.optional(element) || regex.test(value);
}, "Invalid Marks. ");

jQuery.validator.addMethod("greaterThanZero", function (value, element) {
    return this.optional(element) || (parseFloat(value) > 0);
}, "Invalid Marks");
$('#update').on('click', function () {

    // adding rules for inputs with class 'comment'

    $('#unit_list_1_1').each(function () {
        $(this).rules("add",
                {
                    required: true
                });
    });
    $('#Q_No_1_1').each(function () {
        $(this).rules("add",
                {
                    //numeric: true,
                    required: true,
                });
    });

    $('#sec_id2').each(function () {
        $(this).rules("add",
                {
                    required: false
                });
    });
    $('#co_list_1_1').each(function () {
        $(this).rules("add",
                {
                    loginRegex: true,
                    required: true
                });
    });
    $('#mark').each(function () {
        $(this).rules("add",
                {
                    //num:true,
                    greaterThanZero: true,
                    required: true
                });
    });

    var validation_flag = $('#add_question_details').valid();
    if (validation_flag == true) {
        //tinymce.remove();
        tinyMCE.triggerSave();
        var co = $("#co_list_1_1").val();
        var po = $("#po_list_1_1").val();
        var pi = $("#pi_code_1_1").val();
        var topic = $("#topic_list_1_1").val();
        var bl = $("#bloom_list_1_1").val();
        //var mark = $("#mark").val();

        var qp_subq_code = $("#ques_nme").val();
        var qp_subq_marks = $("#mark").val();
        var qp_mq_id = $("#qp_mq_id").val();
        var qp_mq_code = $('#Q_No_1_1').val();
        var que_content = $("#question_1_1").val();
        var unit_q_no = $("#unit_list_1_1").val();
        var qpd_id = $('#qpp_id').val();
        var mandatory;
        if ($('#mandatory').is(':checked')) {
            mandatory = 1;
        } else {
            mandatory = 0;
        }
        var qp_post_data = {'qpd_id': qpd_id};
        // var str1=str.replace("<p>", " ");
        // var qp_con=str1.replace("</p>"," ");

        post_data = {'qp_subq_code': qp_subq_code, 'qp_subq_marks': qp_subq_marks, 'qp_mq_id': qp_mq_id, 'qp_content': que_content, 'co': co, 'unit_q_no': unit_q_no,
            'po': po, 'pi': pi, 'topic': topic, 'bl': bl, 'mandatory': mandatory, 'qp_mq_code': qp_mq_code}
        $.ajax({type: 'POST',
            url: base_url + 'question_paper/manage_model_qp/update_qp',
            data: post_data,
            //dataType: 'JSON',
            success: function (msg) {
                if (msg == -1) {
                    var data_options = '{"text":"Marks exceeding for this unit.    ","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
                    var options = $.parseJSON(data_options);
                    noty(options);
                } else if (msg == -2) {
                    fail_modal();
                } else {
                    $.ajax({type: "POST",
                        url: base_url + 'question_paper/manage_model_qp/Fetch_dum',
                        data: qp_post_data,
                        //dataType: 'json',
                        success: populate_table});
                    var data_options = '{"text":"Your data has been updated successfully.    ","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                    var options = $.parseJSON(data_options);
                    noty(options);
                    load_graph();
                    $(document).trigger('ready');
                    $('#edit_qp').modal('hide');
                }

            }
        });
        tiny_init();
    }
});

$('#close').on('click', function () {
    $.modal.close();
});

$('#btnYes').on('click', function () {
    var id = $('#myModal4').data('id');
    var qpd_id = $('#myModal4').data('qpd_id');

    post_data = {'val': id}
    $.ajax({type: 'POST',
        url: base_url + 'question_paper/manage_model_qp/deleteUser_Roles',
        data: post_data,
        success: function (data) {
            post_data = {'qpd_id': qpd_id}
            $.ajax({type: "POST",
                url: base_url + 'question_paper/manage_model_qp/Fetch_dum',
                data: post_data,
                //dataType: 'json',
                success: populate_table
            });
        }
    });
});

$('#generate_FM').on('click', function () {
    mytable = $('<table  class="table table-bordered table-hover" id="example" aria-describedby="example_info"></table>').attr({id: "basicTable"});
    var rows = new Number($("#rowcount").val());
    //var cols = new Number($("#columncount").val());
    var tr = [];
    for (var i = 0; i < rows; i++) {
        var row = $('<tr></tr>').attr({class: ["class1", "class2", "class3"].join(' ')}).appendTo(mytable);
        for (var j = 0; j < 3; j++) {
            $('<td><input type="text"></td>').text("fg").appendTo(row);
        }

    }
    console.log("TTTTT:" + mytable.html());
    mytable.appendTo("#box");
});

$('#add_ExpenseRow').click(function () {
    $get_lastID();
    $('#expense_table tbody').append($newRow);
});

$get_lastID = function () {
    var $id = $('#expense_table tr:last-child td:first-child input').attr("name");
    $lastChar = parseInt($id.substr($id.length - 2), 10);
    $lastChar = $lastChar + 1;
    $newRow = "<tr> \
					<td><input type='text' id='units_0" + $lastChar + "' name='units_0" + $lastChar + "' maxlength='255' class='required '/></td> \
					<td><input type='text' id='ques_no_0" + $lastChar + "' name='ques_no_0" + $lastChar + "' maxlength='255' class='required '/></td> \
					<td><input type='text' id='marks_0" + $lastChar + "' name='marks_0" + $lastChar + "' maxlength='11' class='required unit_max_marks' /></td> \
					 <td><a role = 'button'   class='del_ExpenseRow' ><i class='icon-remove icon-black'> </i></a></td>\
				</tr>"
    return $newRow;
}



$(".del_ExpenseRow").live("click", function () {
    $(this).closest('tr').remove();
    $lastChar = $lastChar - 2;
});




$("#frame_work_container").validate({
    errorClass: "help-inline font_color",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).addClass('error');
    },
    errorPlacement: function (error, element) {
        if (element.next().is('.error_add')) {
            error.insertAfter(element.parent(''));
        }
        else {
            error.insertAfter(element);
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('error');
        $(element).addClass('success');
    }

});


$('#save_FM').on('click', function () {

    var val_flag = $('#frame_work_container').valid();

    if (val_flag == true) {

        var Grand_total = $('#Grand_total').val();
        var unit_name = $('#unit_01').val();
        var unit = $('#ques_no_01').val();
        var all_unit_marks = $('#marks_01').val();

        $('.edit_unit_max_marks').each(function () {
            all_unit_marks = parseInt(all_unit_marks) + parseInt($(this).val());

        });

        if (all_unit_marks > Grand_total) {
            var data_options = '{"text":"All unit marks is greater than grand total.     ","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
            var options = $.parseJSON(data_options);
            noty(options);
        } else {
            var count = $("#expense_table > tbody > tr").length;
            var tr_count = $("#Edit_FM_table > tbody > tr").length;
            var arr = [];
            var qpd_id = $('#qpp_id').val();
            for (var i = 0; i <= count; i++) {
                arr.push({val1: $("#units_0" + i).val(), val2: $("#ques_no_0" + i).val(), val3: $("#marks_0" + i).val()});
            }


            post_data = {'val': arr, 'count': count, 'qpd_id': qpd_id}
            $.ajax({type: 'POST',
                url: base_url + 'question_paper/manage_model_qp/generate_FM',
                data: post_data,
                dataType: 'json',
                success: function (data) {
                    $('#unit_sum').val(data['unit_sum']);
                    if (data['result'] == "false") {
                        fail_modal();
                    } else if (data['result'] == "exceed") {
                        exceed_fail_modal(unit_name);
                    } else {
                        var html_data;
                        console.log(data['result']);
                        for (var j = 1; j <= count; j++) {

                            tr_count++;
                            html_data += '<tr class="table_row"><td>' + tr_count + '</td><td><input type="text" name="unitd_id[]" id="unitd_id" class="input-mini required" value="' + $("#units_0" + j).val() + '"</td>';
                            html_data += '<td><input type="text" name="no_q[]" id="no_q" class="input-mini required" value="' + $("#ques_no_0" + j).val() + '"</td>';
                            html_data += '<td><input type="text" name="sub_marks[]" id="sub_marks" class="input-mini edit_unit_max_marks required max_marks" value="' + $("#marks_0" + j).val() + '"</td>';
                            html_data += '<td><i id="' + data['result'] + '" class="icon-remove icon-black delete_Unit"> </i></td></tr>';

                            $('#units_01').val('');
                            $('#ques_no_01').val('');
                            $('#marks_01').val('');

                        }

                        $("#expense_table tr:gt(1)").remove()
                        $('#Edit_FM_table >tbody> tr:last').after(html_data);

                        var data_options = '{"text":"Unit added Successfully. .    ","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                        var options = $.parseJSON(data_options);
                        noty(options);

                    }
                }
            });

        }

    }

});


$('#update_FM').on('click', function () {
    var Grand_total = $('#Grand_total').val();
    var all_unit_marks = 0;
    $('.edit_unit_max_marks').each(function () {
        all_unit_marks = parseInt(all_unit_marks) + parseInt($(this).val());

    });
    if (all_unit_marks > Grand_total) {
        var data_options = '{"text":"All unit marks is greater than grand total.    ","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
        var options = $.parseJSON(data_options);
        noty(options);
    }/* else if(all_unit_marks != Grand_total ){
     var data_options = '{"text":"Miss Match in grandtotal and unit marks allotment","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
     var options = $.parseJSON(data_options);
     noty(options);$("#Add_Question").prop("disabled", true);
     } */ else {

        var grand_total = parseInt($('#Grand_total').val());
        var max_marks = parseInt($('#max_marks').val());

        $('#qp_title').each(function () {
            $(this).rules("add",
                    {
                        required: true
                    });
        });
        $('#total_duration').each(function () {
            $(this).rules("add",
                    {
                        //numeric: true,
                        required: true,
                    });
        });

        $('#max_marks').each(function () {
            $(this).rules("add",
                    {
                        numeric: true,
                        required: true,
                        num: true
                    });
        });

        $('#Grand_total').each(function () {
            $(this).rules("add",
                    {
                        numeric: true,
                        required: true,
                        num: true
                    });
        });
        var validation_flag = $('#add_form_id').valid();
        if (validation_flag == true) {
            if (grand_total >= max_marks) {
                var mark = 0;
                $('.marks_val').each(function () {
                    var cur_mark = $(this).text();
                    mark = parseInt(mark) + parseInt(cur_mark);
                });

                if (grand_total >= mark) {

                    var data_options = '{"text":"Update Successfull","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                    var options = $.parseJSON(data_options);
                    //noty(options);
                    $('#loading').show();
                    var qpd_id = $("#qpp_id").val();
                    var qp_title = $("#qp_title").val();
                    var time = $("#total_duration").val();
                    var Grand_total = $("#Grand_total").val();
                    var max_marks = $("#max_marks").val();
                    var qp_notes = $('#qp_notes').val();
                    //var qp_model;
                    if ($('#Model').is(':checked')) {
                        qp_model = 1;
                    } else {
                        qp_model = 0;
                    }
                    post_data = {'qpd_id': qpd_id, 'qp_title': qp_title, 'time': time, 'Grand_total': Grand_total, 'max_marks': max_marks, 'qp_notes': qp_notes, 'qp_model': qp_model}
                    $.ajax({type: "POST",
                        url: base_url + 'question_paper/manage_model_qp/update_header',
                        data: post_data,
                        dataType: 'json',
                        success: function (data) {
                            //noty(options);	
                            $('#loading').hide();
                        }
                    });
                    var count = $("#Edit_FM > tbody > tr").length;
                    var qpd_id = $('#qpp_id').val();
                    var unit = $("input[name='unitd_id[]']")
                            .map(function () {
                                return $(this).val();
                            }).get();
                    var unit_id = $("input[name='unitd_id_one[]']")
                            .map(function () {
                                return $(this).val();
                            }).get();
                    var no_q = $("input[name='no_q[]']")
                            .map(function () {
                                return $(this).val();
                            }).get();
                    var sub_marks = $("input[name='sub_marks[]']")
                            .map(function () {
                                return $(this).val();
                            }).get();

                    var unit_marks = $("input[name='sub_marks[]']")
                            .map(function () {
                                return $(this).data('unit_marks');
                            }).get();
                    post_data = {'units': unit, 'unit_ids': unit_id, 'no_q': no_q, 'sub_marks': sub_marks, 'qpd_id': qpd_id, 'unit_marks': sub_marks}
                    //post_data={'val':arr}
                    $.ajax({type: 'POST',
                        url: base_url + 'question_paper/manage_model_qp/update_FM',
                        data: post_data,
                        dataType: 'json',
                        success: function (data) {
                            var crclm_id = $("#crclm_id").val();
                            var term_id = $('#term_id').val();
                            var crs_id = $("#crs_id").val();
                            var qpd_id = $('#qpp_id').val();
                            var qpd_type = $("#qpd_type").val();
                            post_data = {'crclm_id': crclm_id, 'term_id': term_id, 'crs_id': crs_id, 'qpd_id': qpd_id, 'qpd_type': qpd_type}
                            $.ajax({type: 'POST',
                                url: base_url + 'question_paper/manage_model_qp/get_grand_total',
                                data: post_data,
                                dataType: 'json',
                                success: function (data) {
                                    $('#Grand_total_h').val(data);
                                    //$('#qp_max_marks').val(data);
                                }});
                            $('#unit_sum').val(data['unit_sum']);
                            console.log(data['result']);
                            if (data['result'] != -1) {
                                var data_options = '{"text":"Unit details updated successfully.    ","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                                var options = $.parseJSON(data_options);
                                noty(options);
                                $('#loading').hide();
                            } else {
                                var data_options = '{"text":"Sum of all questions max marks defined under ' + unit + '  is mismatching with ' + "Grand Total." + '     ","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
                                var options = $.parseJSON(data_options);
                                noty(options);
                                $('#loading').hide();
                            }
                        }
                    });
                } else {
                    var data_options = '{"text":"Grand total Marks less than total Question Marks.     ","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
                    var options = $.parseJSON(data_options);
                    noty(options);
                    $('#loading').hide();
                }
            } else {
                var data_options = '{"text":"Grand total Marks less than Maximum Marks.     ","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
                var options = $.parseJSON(data_options);
                noty(options);
                $('#loading').hide();
            }
        }
    }
});

//Global variable.
var tr_val;
$('#Edit_FM').on('click', '.delete_Unit', function (e) {
    /*  */

    var unit_id = $(this).attr('id');
    tr_val = table_row = $(this).closest("tr").get(0);
    $('#myModal5').data('id', unit_id).modal('show');



});
$('#delete_Unit_modal').on('click', function () {

    var unit_id = $('#myModal5').data('id');
    var qpd_id = $('#qpp_id').val();
    post_data = {'unit_id': unit_id, 'qpd_id': qpd_id}
    $.ajax({type: 'POST',
        url: base_url + 'question_paper/manage_model_qp/delete_Unit',
        data: post_data,
        dataType: 'json',
        success: function (data) {
            $('#unit_sum').val(data['unit_sum']);
            if (data['deleted'] == true) {
                var oTable = $('#Edit_FM_table').dataTable();
                //var row = $(this).closest("tr").get(0);
                oTable.fnDeleteRow(oTable.fnGetPosition(tr_val));
                var data_options = '{"text":"Unit Deleted successfully.    ","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                var options = $.parseJSON(data_options);
                noty(options);
            } else {
                var data_options = '{"text":"Unit Delete is failed.    ","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
                var options = $.parseJSON(data_options);
                noty(options);
            }
        }

    });
});





// multiselect function
$(function () {

    var $form = $("#add_question_details");
    var validator = $form.data('validator');

    validator.settings.ignore = ':hidden:not(".co_list_data"):hidden:not(".po_list_data"):hidden:not(".topic_list_data"):hidden:not(".bloom_list_data")';

    $('.co_list_data').multiselect({
        includeSelectAllOption: true,
        buttonWidth: '110px',
        nonSelectedText: 'Select CO',
        templates: {
            button: '<button type="button" class="multiselect btn btn-link co_multi dropdown-toggle" data-toggle="dropdown"></button>'
        }
    });

    $('.topic_list_data').multiselect({
        includeSelectAllOption: true,
        buttonWidth: '110px',
        nonSelectedText: 'Select Topic',
        templates: {
            button: '<button type="button" class="multiselect btn btn-link co_multi dropdown-toggle" data-toggle="dropdown"></button>'
        }
    });

    $('.bloom_list_data').multiselect({
        includeSelectAllOption: true,
        buttonWidth: '110px',
        nonSelectedText: 'Select level',
        templates: {
            button: '<button type="button" class="multiselect btn btn-link co_multi dropdown-toggle" data-toggle="dropdown"></button>'
        }
    });

    $('.po_list_data').multiselect({
        includeSelectAllOption: true,
        buttonWidth: '110px',
        nonSelectedText: 'Select PO',
        templates: {
            button: '<button id="" type="button" class="multiselect po_multi dropdown-toggle btn btn-link" data-toggle="dropdown"></button>'
        }

    });


    $('.pi_code_data').multiselect({
        includeSelectAllOption: true,
        buttonWidth: '110px',
        nonSelectedText: 'Select PI',
        templates: {
            button: '<button id="" type="button" class="multiselect po_multi dropdown-toggle btn btn-link" data-toggle="dropdown"></button>'
        }

    });
    $('#example-filterBehavior').multiselect({
        includeSelectAllOption: true

    });

    $('.co_list_data').click(function () {
        var selected = $('.co_list_data option:selected');
        var message = "";
        selected.each(function () {
            message += $(this).text() + " " + $(this).val() + "\n";
        });


    });
});

$('#edit_qp').on('click','#import_rev_assin_questions',function(){
   var crs_id = $(this).attr('data-crs_id');
   $('#course_id_one').val(crs_id);
   $('.question_type').each(function(){
       $(this).prop('checked',false);
   });
   $.fn.modal.Constructor.prototype.enforceFocus = function () {};
   $('#question_list_div').empty();
   $('#rev_assin_question_list').modal('show');
        
});

$('#rev_assin_question_list').on('click','.question_type',function(){
    var crs_id = $('#course_id_one').val();
    var ques_type = $(this).val();
    var post_data = {'crs_id':crs_id,'que_type':ques_type};
    $.ajax({type: "POST",
                url: base_url+'question_paper/manage_model_qp/get_rev_assin_questions',
                data: post_data,
                //dataType: 'json',
                success:function(data){
                    $('#question_list_div').empty();
                    $('#question_list_div').html(data);
                     $('#rev_assin_question').dataTable({

                            "fnDrawCallback":function(){
                                    $('.group').parent().css({'background-color':'#C7C5C5'}); 
                                    },
                             "aoColumnDefs": [{ "sType": "natural", "aTargets": [ 1 ] } ],
                            ///"sPaginationType": "bootstrap"
                            }).rowGrouping({ iGroupingColumnIndex:1,
                                            bHideGroupingColumn: true });
                }
            });
})


$('#rev_assin_question_list').on('click','#question_ok',function(){
    var question = ''; 
    var current_que = tinyMCE.activeEditor.getContent();
     question += current_que+"<p>";
    $('.select_question').each(function(){
        if($(this).is(':checked')){
            question += $(this).attr('data-question');
            question += "</p>";
        }
        
    });
    if(question != ''){
         
        tinyMCE.activeEditor.setContent('');
        tinyMCE.activeEditor.setContent(question);
        $('#question_1_1').text(question);
        tinyMCE.execCommand("mceRepaint");
        $('#rev_assin_question_list').modal('hide');
    }else{
        var data_options = '{"text":"<b>Please Select Question.</b>","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
        var options = $.parseJSON(data_options);
        noty(options);
    }
    
});

/* 	$('#Model').on('change',function(){
 if(this.checked) {qp_model =1;}else{qp_model = 0;}
 post_data =  {'qp_model':qp_model}
 $.ajax({type: "POST",
 url: base_url+'question_paper/manage_model_qp/cia_model_qp_def_model',
 data: post_data,
 dataType: 'json',
 success:function(data){}});
 }); */

// function to set the question paper as model or as actual question paper

$('#Model').on('click',function(){
   if($(this).is(':checked')){
      
       $('#model_qp_actual_qp').modal('show');
   } else{
        $('#unset_model_qp_actual_qp').modal('show');
   }
});

$('#model_qp_actual_qp').on('click','#click_cancel',function(){
    if($('#Model').is(':checked')){
        $('#Model').prop('checked',false);
    }
});

$('#unset_model_qp_actual_qp').on('click','#unset_click_cancel',function(){
    if($('#Model').is(':checked')){  
    }else{
        $('#Model').prop('checked',true);
    }
});

$('.noty').click(function () {

    var options = $.parseJSON($(this).attr('data-noty-options'));
    noty(options);
});

