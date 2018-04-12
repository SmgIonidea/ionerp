	var base_url = $('#get_base_url').val();

$(document).ready(function(){
var exist= $('#model_qp_existance').val();
var qpp_id=$('#qpp_id').val();

//alert(qpp_id);
$('#myModal_qp').modal('show');
if(exist==0)
{
			$('#myModal_qp').modal('show');
	 
}
$('#ok').on('click',function(){

		var qp_type = $('input:radio[name=FM]:checked').val();
		if(qp_type==4){
			 $('.FM').hide();
			 $("#b").val("1");
			 $('#max_marks').attr('readonly', true);
			 $('#Grand_total').attr('readonly', true);
			 $('#update_header').hide();
			$("#tabl tbody tr.ch2").hide();
			$('#unit_defined_fm').show();
		
		}
		else if(qp_type==0){
			$("#b").val("2");		
			$('#Grand_total').val("");
			$("#tabl tbody tr.ch1").hide();
			$('#update_header').hide();$('#unit_defined_fm').hide();
		}
		//else{alert("Fail to load"); window.location.href(base_url+"list_model_qp_vw.php");}
	
		
		
	});
$('input[type=radio]').live('change', function() { 
$('.generate_fm').show();
 });	

});
