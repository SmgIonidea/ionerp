var base_url = $('#get_base_url').val();
var topic_id;
var table_row;
var po_counter = new Array();
var book_sl_no_counter = new Array();
var assessment_name_counter = new Array();
var assess_del_array = new Array();
po_counter.push(1);
//book_sl_no_counter.push(1);
//assessment_name_counter.push(1);
var cloneCntr = $('#book_counter').val();
cloneCntr++;
var cie_cloneCntr;

function check_ref(msg) {

    var course_id = $('#book_course_id').val();
    var post_data = {'course_id': course_id, 'ref': msg}
    $.ajax({type: "POST",
        url: base_url + 'curriculum/topic/generate_book_table',
        data: post_data,
        dataType: 'json',
        success: function (msg) {
            populate_table(msg);
        }
    });

    function populate_table(msg) {
        $('#example').dataTable().fnDestroy();
        $('#example').dataTable({
            "aoColumns": [
                {"sTitle": "Sl.No", "mData": "book_sl_no"},
                {"sTitle": "Author", "mData": "book_author"},
                {"sTitle": "Title", "mData": "book_title"},
		{"sTitle": "Edition", "mData": "book_edition"},
		{"sTitle": "Website", "mData": "book_website"},                
                {"sTitle": "Publication", "mData": "book_publication"},
                {"sTitle": "Publication Year", "mData": "book_publication_year"},
                {"sTitle": "Edit", "mData": "T_R"},
                {"sTitle": "Edit", "mData": "Edit"},
                {"sTitle": "Delete", "mData": "Delete"}
            ], "aaData": msg["book_details"],
            "sPaginationType": "bootstrap"
        });
    }

}

$(document).ready(function () {

    var course_id = $('#book_course_id').val();
    var post_data = {
        'course_id': course_id
    }

    $.ajax({type: "POST",
        url: base_url + 'curriculum/topic/generate_book_list',
        data: post_data,
        dataType: 'json',
        success: function (msg) {
            populate_table(msg);
        }
    });



    function populate_table(msg) {
        $('#example').dataTable().fnDestroy();
        $('#example').dataTable({
            "aoColumns": [{"sTitle": "Sl No.", "mData": "Book_Sl_No"}, {
                    "sTitle": "Author", "mData": "Author"}, {
                    "sTittle": "Title", "mData": "Title"}, {
                    "sTitle": "Edition", "mData": "Edition"}, {
		    "sTitle": "Website", "mData": "Website"}, {
                    "sTitle": "Publication", "mData": "Publication"}, {
                    "sTitle": "Publication Year", "mData": "Punlication_yrar"}, {
                    "sTitle": "Text Book / Reference Book", "mData": "T_R"}, {
                    "sTitle": "Edit", "mData": "Edit"}, {
                    "sTitle": "Delete", "mData": "Delete"}],
            "aaData": msg['book_details'],
            "sPaginationType": "bootstrap"
        });
    }

    $('#save_book_details').on('click', function () {
        $('#save_books').validate();
        flag = $('#save_books').valid();

        // adding rules for inputs with class 'comment'
        $(".sl_no").each(function () {
            $(this).rules("add",
                    {
                        onlyDigit: true,
                        required: true
                    });
        });
        $('.author').each(function () {
            $(this).rules("add",
                    {
                        noSpecialChars: true
                    });
        });
        $('.title').each(function () {
            $(this).rules("add",
                    {
                        noSpecialChars: true
                    });
        });
	$('.website').each(function () {
            $(this).rules("add",
                    {
                        noSpecialChars: true,
			valid_url : true
                    });
        });
        $('.edition').each(function () {
            $(this).rules("add",
                    {
                        noSpecialChars1: true
                    });
        });
        $('.publication').each(function () {
            $(this).rules("add",
                    {
                        noSpecialChars: true
                    });
        });
        $('.publication_year').each(function () {
            $(this).rules("add",
                    {
                        loginRegex: true
                    });
        });
        var course_id = $('#book_course_id').val();
        var book_sl_no = $('#book_sl_no1').val();
        var book_author = $('#book_author1').val();
        var book_title = $('#book_title1').val();
	var book_website = $('#book_website1').val();
        var book_edition = $('#book_edition1').val();
        var book_publication = $('#book_publication1').val();

        var book_type = $('input:radio[name=ref]:checked').val();
//if ($('#book_type1').is(':checked')) { book_type= 1;}else{book_type=0;}
//var book_type=$('#book_type1').val();
        var book_publication_year = $('#book_publication_year').val();
        if (flag == true) {
            $("#loading").show();
            var post_data = {
                'course_id': course_id,
                'book_sl_no': book_sl_no,
                'book_author': book_author,
                'book_title': book_title,
		'book_website': book_website,
                'book_edition': book_edition,
                'book_publication': book_publication,
                'book_type': book_type,
                'book_publication_year': book_publication_year
            }
            $.ajax({type: "POST",
                url: base_url + 'curriculum/topic/save_book_list',
                data: post_data,
                success: function (msg) {
                    $("#save_books").trigger('reset');
                    $('#loading').hide();
                    var post_data = {
                        'course_id': course_id
                    }

                    $.ajax({type: "POST",
                        url: base_url + 'curriculum/topic/generate_book_list',
                        data: post_data,
                        dataType: 'json',
                        success: function (msg) {
                            populate_table(msg);
                        }
                    });
                }

            });
        }

    });
    $('.get_id').live('click', function (e) {
        data_val = $(this).attr('id');
        $('#book_id1').val(dat_val);
    });
    //Function is to delete po type by sending the po type id to controller.
    $('.delete_dm').click(function (e) {
        $('#loading').show();
        var dat_val = $('#nook_id1').val();
        e.preventDefault();
        var base_url = $('#get_base_url').val();
        var course_id = $('#book_course_id').val();
        var post_data = {'book_id': data_val, }
        $.ajax({
            type: "POST",
            url: base_url + 'curriculum/topic/delete_book',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                $('#loading').hide();
                var post_data = {'course_id': course_id}

                $.ajax({type: "POST",
                    url: base_url + 'curriculum/topic/generate_book_list',
                    data: post_data,
                    dataType: 'json',
                    success: function (msg) {
                        populate_table(msg);
                    }
                });
            }
        });
    });

    $('#example').on('click', '.edit_book', function (e) {
        var book_id = $(this).attr('data-id');
        $('#book_sl_no_e').val($(this).attr('data-sl_no'));
        $('#book_author_e').val($(this).attr('data-author'));
        $('#book_title_e').val($(this).attr('data-title'));
        $('#book_website_e').val($(this).attr('data-website'));
        $('#book_edition_e').val($(this).attr('data-edition'));
        $('#book_publication_e').val($(this).attr('data-public'));
        $('#book_publication_year_e').val($(this).attr('data-public_year'));
        $('#book_id').val(book_id);

        if ($(this).attr('data-book_type') == 1) {
            $('.r').prop('checked', true);
        } else {
            $('.t').prop('checked', true);
        }
        $('#edit_book_details').modal('show');
    });


    $('#update_book_details').on('click', function () {
        $('#edit_books').validate();
        flag = $('#edit_books').valid();
        // adding rules for inputs with class 'comment'
        $(".sl_no_e").each(function () {
            $(this).rules("add",
                    {
                        onlyDigit: true,
                        required: true
                    });
        });
        $('.author_e').each(function () {
            $(this).rules("add",
                    {
                        noSpecialChars: true
                    });
        });
        $('.title_e').each(function () {
            $(this).rules("add",
                    {
                        noSpecialChars: true
                    });
        });
	$('.website_e').each(function () {
            $(this).rules("add",
                    {
                        noSpecialChars: true,
			valid_url : true
                    });
        });
        $('.edition_e').each(function () {
            $(this).rules("add",
                    {
                        noSpecialChars1: true
                    });
        });
        $('.publication_e').each(function () {
            $(this).rules("add",
                    {
                        noSpecialChars: true
                    });
        });
        $('.publication_year_e').each(function () {
            $(this).rules("add",
                    {
                        loginRegex: true
                    });
        });
        var course_id = $('#book_course_id').val();
        var book_sl_no = $('#book_sl_no_e').val();
        var book_author = $('#book_author_e').val();
        var book_title = $('#book_title_e').val();
	var book_website = $('#book_website_e').val();
        var book_edition = $('#book_edition_e').val();
        var book_publication = $('#book_publication_e').val();
        var book_type = $('input:radio[name=ref_e]:checked').val();
//var book_type;
//if ($('#book_type_e').is(':checked')) { book_type= 1;}else{book_type=0;}
//var book_type=$('#book_type_e').val();
        var book_publication_year = $('#book_publication_year_e').val();
        var book_id = $('#book_id').val();
        if (flag == true) {
            var post_data = {
                'course_id': course_id,
                'book_sl_no': book_sl_no,
                'book_author': book_author,
                'book_title': book_title,
                'book_website': book_website,
                'book_edition': book_edition,
                'book_publication': book_publication,
                'book_type': book_type,
                'book_publication_year': book_publication_year,
                'book_id': book_id
            }
            $('#loading').show();
            $.ajax({type: "POST",
                url: base_url + 'curriculum/topic/update_book_list',
                data: post_data,
                success: function (msg) {
                    $('.check').attr('checked', false);
                    $('#edit_book_details').modal('hide');
                    $('#loading').hide();
                    var post_data = {
                        'course_id': course_id
                    }

                    $.ajax({type: "POST",
                        url: base_url + 'curriculum/topic/generate_book_list',
                        data: post_data,
                        dataType: 'json',
                        success: function (msg) {
                            populate_table(msg);

                        }
                    });
                }});
        }
    });
    var cie_val = $('#cie_eval').val()
    var book_counter = $('#book_counter').val()
    var cie_ckeck_length = $('.assmnt_mode:checked').length;

    $('#check_counter').val(cie_ckeck_length);
    cie_cloneCntr = parseInt(cie_val) + 1;
    var i, j;
    for (i = 1; i <= cie_val; i++) {
        assessment_name_counter.push(i)
        $('#counter_eval').val(assessment_name_counter);
    }
    for (j = 1; j <= book_counter; j++) {
        book_sl_no_counter.push(j)
        $('#counter').val(book_sl_no_counter);
    }
    var marks = 0;
    $('.marks').each(function () {

        marks = parseFloat($(this).val()) + marks;

    });
    $('#total').val(marks);
});
//Function to keep count of textarea that has been added or deleted
function fixIds(elem, cntr) {
    $(elem).find("[id]").add(elem).each(function () {
        this.id = this.id.replace(/\d+$/, "") + cntr;
        this.name = this.id;
    });
}

//Function to insert new textarea for adding program outcomes
$("#add_book_details").click(function () {

    var table = $("#book_details").clone(true, true);
    fixIds(table, cloneCntr);
    table.insertBefore("#add_before");
    $('#book_sl_no' + cloneCntr).val('');
    $('#book_id' + cloneCntr).remove();
    $('#book_author' + cloneCntr).val('');
    $('#book_title' + cloneCntr).val('');
    $('#book_type' + cloneCntr).val(1);
    $('#book_edition' + cloneCntr).val('');
    $('#book_publication' + cloneCntr).val('');
    $('#book_publication_year' + cloneCntr).val('');
    $('#book_type' + cloneCntr).attr('checked', false);
    $('#book_publication_year_' + cloneCntr + ' option:selected').prop('selected', false);
    $('#book_sl_no_' + cloneCntr).focus();
    book_sl_no_counter.push(cloneCntr);
    $('#counter').val(book_sl_no_counter);
    cloneCntr++;
});

//Function to delete unwanted textarea
$('.Delete').live('click', function () {
    var book_count = $('#counter').val();
    var book_count_one = $('#add_more_book_counter').val();
    rowId = $(this).attr("id").match(/\d+/g);
    if (rowId != 1) {
        $(this).parent().parent().parent().remove();
        $('#add_more_book_counter').val(book_count_one - 1);
        var replaced_id = $(this).attr('id').replace('remove_field', '');
        var book_counter_index = $.inArray(parseInt(replaced_id), book_sl_no_counter);
        book_sl_no_counter.splice(book_counter_index, 1);
        $('#counter').val(book_sl_no_counter);
        return false;
    }

});

//Add Evaluation Scheme


//Function to keep count of textarea that has been added or deleted
function fixIds_val(elem, cntr) {
    $(elem).find("[id]").add(elem).each(function () {
        this.id = this.id.replace(/\d+$/, "") + cntr;
        this.name = this.id;

    });
}

//Function to insert new textarea for adding program outcomes
$("#add_evaluation_details").click(function () {
    var table = $("#evaluation_details").clone(true, true);
    table.find("label.help-inline").remove();
    $('#generate').prop('disabled', false);
    $('#revert').prop('disabled', false);
    fixIds_val(table, cie_cloneCntr);
    table.insertBefore("#add_more");
    $('#assessment_name' + cie_cloneCntr).val('');
    $('#assessment_mode' + cie_cloneCntr).attr('checked', false);
    $('#weightage_in_marks' + cie_cloneCntr).val('');
    $('#assessment_name' + cie_cloneCntr).focus();
    $('#assessment_id' + cie_cloneCntr).remove();
    assessment_name_counter.push(cie_cloneCntr);
    $('#counter_eval').val(assessment_name_counter);
    $('#cie_eval').val(cie_cloneCntr);
    cie_cloneCntr++;
});

// Function to generate the CIE Evaluation table

$('#generate').on('click', function () {
    $('#book_add_form').validate();
    flag = $('#book_add_form').valid();
    $('.assessment_name').each(function () {
        $(this).rules("add",
                {
                    loginRegex: true
                });
    });
    if (flag) {
        var i;
        var j;
        var k;
        var assessment_id = new Array();
        var assess_name = new Array();
        var checked = new Array();
        var total = 0;
        $('.marks').each(function () {
            var id = $(this).attr("id").match(/\d+/g);
		//var id = $(this).attr("id").match(/^[0-9\.]+/g);
            var weightage_sum = parseFloat($.trim($('#weightage_in_marks' + id).val()));	

            if (weightage_sum == '') {
                total = 0;
            } else {

                total = parseFloat(total) + weightage_sum;

            }

        });

        $('#total').val(total);
        var assmnt_mode_count = $('.assmnt_mode:checkbox:not(":checked")').length;
        var assmnt_mode_checked_count = $('.assmnt_mode:checked').length;
        $('#check_counter').val(assmnt_mode_checked_count);
        //checked.push($('.assmnt_mode').val());
        $('.assmnt_mode').each(function () {
            if (!$(this).prop('checked')) {
                var id_val = $(this).attr("id").match(/\d+/g);
                checked.push(id_val);
                checked.push(id_val);
                assess_name.push($('#assessment_name' + id_val).val());
            }
        });
        $('#checked_val').val(checked);
        var cie_count_val = $('#cie_eval').val();
        var array_size = checked.length;
        var crclm_id = $('#book_curriculum_id').val();
        var term_id = $('#book_term_id').val();
        var crs_id = $('#book_course_id').val();
        for (k = 0; k < array_size; k++) {
            assessment_id.push($('#assessment_id' + checked[k]).val());
        }

        var post_data = {
            'cie_count': assmnt_mode_count,
            'crclm_id': crclm_id,
            'term_id': term_id,
            'crs_id': crs_id,
            'checked_val': checked,
            'assessment_id': assessment_id,
            'assess_name': assess_name
        }

        $.ajax({type: "POST",
            url: base_url + 'curriculum/topic/edit_generate_cie_table',
            data: post_data,
            success: function (msg) {
                $('.identify_remove').removeClass('Delete_assessment');
                // $('.marks').prop('readonly', true);
                // $('.assessment_name').prop('readonly', true);
                // $('.assmnt_mode').prop('readonly', true);
                //$('#add_book_details').prop('disabled',true);
                $("#add_evaluation_details").attr("disabled", "disabled");
                $("#generate").attr("disabled", "disabled");
                console.log(msg);
                $('#cie_table').html(msg);

            }
        });
        $('#table_counter').val(assmnt_mode_count);
    }
});

$('#revert').on('click', function () {
    $('#cie_table').empty();
    $('.identify_remove').addClass('Delete_assessment');
    $('.marks').prop('readonly', false);
    $('.assessment_name').prop('readonly', false);
    $('.assmnt_mode').prop('readonly', false);
    //$('#add_book_details').prop('disabled',true);
    $("#add_evaluation_details").attr("disabled", false);
    $("#generate").attr("disabled", false);
});

//Function to delete unwanted textarea
$('.Delete_assessment').live('click', function () {
    var assessment_count = $('#counter_eval').val();
    var assessment_count_one = $('#add_more_assessment_counter').val();
    $('#generate').prop('disabled', false);
    $('#revert').prop('disabled', false);
    rowId = $(this).attr("id").match(/\d+/g);
    assess_del_array.push($('#assessment_id' + rowId).val());
    if (rowId != 1) {
        $(this).parent().parent().parent().remove();
        $('#add_more_assessment_counter').val(assessment_count_one - 1);
        var replaced_id = $(this).attr('id').replace('remove_field', '');
        var assessment_counter_index = $.inArray(parseInt(replaced_id), assessment_name_counter);
        assessment_name_counter.splice(assessment_counter_index, 1);
        $('#counter_eval').val(assessment_name_counter);
        $('#assess_del_val').val(assess_del_array);
        return false;
    }

});

$('.assmnt_mode').on('change', function () {
    $('#generate').prop('disabled', false);
    $('#revert').prop('disabled', false);
});

function select_evaluation() {
    var book_sl_no = document.getElementById('book_sl_no_1').value;
    var book_author = document.getElementById('book_author_1').value;
    var book_title = document.getElementById('book_title_1').value;
    var book_edition = document.getElementById('book_edition_1').value;
    var book_publication = document.getElementById('book_publication_1').value;
    var book_publication_year = document.getElementById('book_publication_year_1').value;
    var assessment_name = document.getElementById('assessment_name_1').value;
    var assessment_mode = document.getElementById('assessment_mode_1').value;
    var weightage_in_marks = document.getElementById('weightage_in_marks_1').value;


    var post_data = {
        'book_sl_no_1': book_sl_no,
        'book_author_1': book_author,
        'book_title_1': book_title,
        'book_edition_1': book_edition,
        'book_publication_1': book_publication,
        'book_publication_year_1': book_publication_year,
        'assessment_name_1': assessment_name,
        'assessment_mode_1': assessment_mode,
        'weightage_in_marks_1': weightage_in_marks,
    }

    $.ajax({type: "POST",
        url: base_url + 'curriculum/topic/add_books_evaluation',
        data: post_data,
        //dataType: 'json',
        //success: static_populate_table
    });
}

//To fetch help content related to topic
$('.show_help').live('click', function () {
    $.ajax({
        url: base_url + 'curriculum/topic/topic_help',
        //datatype: "JSON",
        success: function (msg) {
            console.log(msg);
            $('#help_content').html(msg);
        }
    });
});

//Topic Edit Script Ends Here

/* var date = new Date();
 date.setDate(date.getDate()-1);
 $(".yearpicker").live('click',function(){
 var book_publication_year = $(this).attr('id');
 $('#'+$.trim(book_publication_year)).datepicker({
 changeMonth: true,
 changeYear: true,
 gotoCurrent: true,
 dateFormat: 'yy-mm-dd',
 yearRange: '1980:c',
 defaultDate: '-10y'
 }); */
//$("#"+$(this).attr('id')).datepicker().focus();
/*$("#"+$(this).attr('id')).datepicker({
 format: " yyyy",
 viewMode: "years", 
 minViewMode: "years"
 
 });*/
//});
/*$('.cal_btn').click(function(){
 
 $(document).ready(function(){
 $(".yearpicker").datepicker().focus();
 
 });
 });*/

/* $("#book_publication_year_1").datepicker( {
 format: "yyyy",
 viewMode: "years", 
 minViewMode: "years"			
 }).on('changeDate',function (ev){
 $(this).blur();
 $(this).datepicker('hide');
 });
 
 $('#btn').click(function(){
 $(document).ready(function(){
 $("#book_publication_year_1").datepicker().focus();	
 });
 }); */

$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\_\,\!\.\%\&\-\s\:\;\(\/)']+$/i.test(value);
}, "Field must contain only letters, numbers, spaces, underscore, colon, semicolon, round bracket, forward slash, ' or dashes.");

$.validator.addMethod("valid_url", function(value, element) {
		var regex =/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i; //this is for numeric... you can do any regular expression you like...
		return this.optional(element) || regex.test(value);
	}, "This is not valid URL.");	

$("#book_add_form").validate({
    errorClass: "help-inline font_color",
    errorElement: "label",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().addClass('error');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).parent().parent().addClass('success');
    }
});



// Form validation rules are defined & checked before form is submitted to controller.		

$.validator.addMethod('noSpecialChars', function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\_\,\!\.\%\&\-\s\:\;\(\/\+)']+$/i.test(value);
},
        'Verify you have a invalid entry.'
        );

$.validator.addMethod('noSpecialChars1', function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\_\,\!\.\%\&\-\s\:\;\(\/)']+$/i.test(value);
},
        'Verify you have a invalid entry.'
        );

$.validator.addMethod("onlyDigit", function (value, element) {
    return this.optional(element) || /^[0-9\.]+$/i.test(value);
}, "This field must contain only Numbers.");

$('.add_details').on('click', function (event) {

    // adding rules for inputs with class 'comment'
    $(".sl_no").each(function () {
        $(this).rules("add",
                {
                    onlyDigit: true,
                    required: true
                });
    });
    $('.author').each(function () {
        $(this).rules("add",
                {
                    noSpecialChars: true
                });
    });
    $('.title').each(function () {
        $(this).rules("add",
                {
                    noSpecialChars: true
                });
    });
    $('.edition').each(function () {
        $(this).rules("add",
                {
                    noSpecialChars1: true
                });
    });
    $('.publication').each(function () {
        $(this).rules("add",
                {
                    noSpecialChars: true
                });
    });
    $('.publication_year').each(function () {
        $(this).rules("add",
                {
                    required: true,
                    loginRegex: true

                });
    });
    $('.assessment_name').each(function () {
        $(this).rules("add",
                {
                    loginRegex: true
                });
    });
    $('.assessment_mode').each(function () {
        $(this).rules("add",
                {
                    loginRegex: true
                });
    });
    $('.marks').each(function () {
        $(this).rules("add",
                {
                    onlyDigit: true
                });
    });

    $('#book_add_form').validate();
});





/* $('.add_details').on('click', function(e) {
 $('#book_add_form').validate();			
 // adding rules for inputs with class 'comment'
 $('.sl_no').each(function() {
 $(this).rules("add", 
 {
 loginRegex: true
 });
 });
 
 /* $('.author').each(function() {
 $(this).rules("add", 
 {
 loginRegex: true
 });
 });
 $('.title').each(function() {
 $(this).rules("add", 
 {
 loginRegex: true
 });
 });
 $('.edition').each(function() {
 $(this).rules("add", 
 {
 loginRegex: true
 });
 });
 $('.publication').each(function() {
 $(this).rules("add", 
 {
 loginRegex: true
 });
 });
 $('.publication_year').each(function() {
 $(this).rules("add", 
 {
 loginRegex: true
 });
 });
 $('.assessment_name').each(function() {
 $(this).rules("add", 
 {
 loginRegex: true
 });
 });
 $('.assessment_mode').each(function() {
 $(this).rules("add", 
 {
 loginRegex: true
 });
 });
 $('.marks').each(function() {
 $(this).rules("add", 
 {
 loginRegex: true
 });
 }); 
 }); */


function on_check_viva() {
    if ($('#assessment_mode').is(':checked')) {
        document.getElementById('assessment_mode').value = 'Viva';

    }
    if (!$('#assessment_mode').is(':checked')) {
        document.getElementById('assessment_mode').value = 'Theory';
    }

} 
