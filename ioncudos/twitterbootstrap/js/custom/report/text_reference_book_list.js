function select_term()
{

    $.cookie('remember_curriculum', $('#crclm option:selected').val(), {expires: 90, path: '/'});
    var data_val = document.getElementById('crclm').value;
    var select_term_path = base_url + 'report/text_reference_book_list/select_term';
    $('#loading').show();
    var post_data = {
	'crclm_id': data_val
    }
    $.ajax({type: "POST",
	url: select_term_path,
	data: post_data,
	success: function (msg) {
	    document.getElementById('term').innerHTML = msg;
	  /*   if ($.cookie('remember_term') != null) {
		$('#term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);		
	    } */export_btn();
	    $('#loading').hide();
	}
    });      
}

function fetch_syllabus(){
	var term_id = $('#term').val();
	var crclm_id = $('#crclm').val();	
	 var post_data = {
	'crclm_id': crclm_id,
	'term_id' : term_id
    }
    $.ajax({type: "POST",
	url: base_url + 'report/text_reference_book_list/fetch_syllabus',
	data: post_data,
	success: function (msg) {	
	 document.getElementById('syllabus_list').innerHTML = msg; 
	 document.getElementById('crclm_name').innerHTML = "Curriculum : <b>"+$("#crclm").find(":selected").text()+"</b>"; 	
	 document.getElementById('term_name').innerHTML = "Term : <b>"+$("#term").find(":selected").text()+"</b>";
	 
	}
	});

}

function generate_pdf() {
	$('table.border_remove').find('tr td').css({'border-bottom':'1px solid blue'});
	$('#crclm_name').val($("#crclm").find(":selected").text());
	$('#term_name').val($("#term").find(":selected").text());
	var cloned = $('#syllabus_list').clone().html();	
	$('#pdf').val(cloned);
    $('#form1').submit();
}

$('#term').live('change',function(){
	export_btn();
});
export_btn();
function export_btn(){
var crclm = $('#crclm').val();
var term = $('#term').val();
	if( term == 'Select Term' || term == ''){
		$('#export_top').hide();$('#export_bottom').hide();
	}else{
	$('#export_top').show();$('#export_bottom').show();
	}
}
