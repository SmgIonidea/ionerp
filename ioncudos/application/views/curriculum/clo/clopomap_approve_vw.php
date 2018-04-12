<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: CLO to PO Mapping View page which provides the facility to map the clo's (Course Learning Outcome) 
 * of particular course with po's (program outcomes) .	  
 * Modification History:
 * Date				Modified By				Description
 * 05-09-2013      Mritunjay B S     Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>

<!DOCTYPE html>
<html lang="en">
	<!--head here -->
	<?php  
		$this->load->view('includes/head'); 
	?>

	<body data-spy="scroll" data-target=".bs-docs-sidebar">
		<!--branding here-->
		<?php  
			$this->load->view('includes/branding'); 
		?>
		
		<!-- Navbar here -->
		<?php  
			$this->load->view('includes/navbar'); 
		?> 
		
		<div class="container-fluid">
			<div class="row-fluid">
				<!--sidenav.php-->
				<?php  $this->load->view('includes/sidenav_2'); ?>
				<div class="span10">
					<!-- Contents
				================================================== -->
					<section id="contents">
						<div class="bs-docs-example">
							<!--content goes here-->
							<div class="navbar">
                            <div class="navbar-inner-custom">
                                Mapping of Course Outcomes (COs) to <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> Course-wise
                             </div>
                            </div> 
							
							<div class="row-fluid">
								<div class="span12">
									<div class="row-fluid">
											<table>
											<tr>
											<td>
												<label>
												Curriculum: <font color="red"> * </font>
													<select size="1" id="curriculum" name="curriculum" aria-controls="example" onChange = "select_term();">
														<option value="" selected> Select Curriculum</option>
														<?php foreach($results as $listitem): ?>
																<option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?> </option>
														<?php endforeach; ?>
													</select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												</label>
											</td>
											<td>
												<label>
												Term: <font color="red"> * </font>
													<select size="1" id="term" name="term" aria-controls="example" onChange = "select_course();">
													<option value="" selected> Select Term</option>
													</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												</label>
											</td>
											<td>
												<label>
												Course: <font color="red"> * </font>
													<select id="course" name="course" onchange="display_grid();">
													<option value="" selected> Select Course</option>
													</select>
												</label>
											</td>
											</tr>
											</table>
												<div class="bs-docs-example span8 scrollspy-example" style="width: 775px; height:100%; overflow:auto;" >
														<form id="table1" > 
												</div>
											
										<div class="span3">
											<div data-spy="scroll" class="bs-docs-example span3" style="width:260px; height:325px;">	
												<div >
													<label> <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcome'); ?> </label>
													<textarea id="text1" rows="5" cols="5" disabled>
													</textarea>
												</div>	
												</br>
								
												<div>
													<label> Course Outcome (CO) </label>
													<textarea id="text2" rows="5" cols="5" disabled>
														
													</textarea>
										 		</div>
											</div><!--span4 ends here-->
										</div>
									</div>
									
								
										</form>
										
										<div class="pull-right">
										
										<b id="scan_row_col"  class="btn btn-success" ><i class="icon-ok icon-white"></i> Accept </b>
										<b id="rework"  class="btn btn-danger" ><i class="icon-repeat icon-white"></i> Rework </b>
									</div>
								
								
									
								
								</div>
							</div>
						</div>
						<!--Do not place contents below this line-->
					</section>			
				</div>					
			</div>
		</div>

		<!---place footer.php here -->
		<?php  $this->load->view('includes/footer'); ?> 
		<!---place js.php here -->
		<?php  $this->load->view('includes/js'); ?>
	</body>
</html>


<script>
	/*You may use scrollspy along with creating and removing elements form DOM. But if you do so, you have to call the refresh method . 
	The following code shows how you may do that:*/

	$('[data-spy="scroll"]').each(function () {
	console.log($(this));
		var $spy = $(this).scrollspy('refresh')
	});
	
	//second dropdown - term
	function select_term()
	{
		var data_val=document.getElementById('curriculum').value;

		var post_data={
		   'crclm_id':data_val						   
		}
						   
		$.ajax({type: "POST",
			url: "<?php echo base_url('curriculum/clopomap_review/select_term'); ?>",
			data: post_data,
						
			success: function(msg){
				document.getElementById('term').innerHTML=msg;
			}
		});
	}
		
		
	function select_course()
	{
	
	var data_val=document.getElementById('term').value;
	

                       var post_data={
                              
							   'term_id':data_val
                       }
					
					   
					   $.ajax({type: "POST",
					url: "<?php echo base_url('curriculum/clopomap_review/select_course'); ?>",
					data: post_data,
					
					success: function(msg){
					
					document.getElementById('course').innerHTML=msg;
					}
					});
}	
		
		
		
		
		
		
		
	//display grid on select of term
	function display_grid()
	{
	
		var data_val1=document.getElementById('course').value;
		var data_val2=document.getElementById('curriculum').value;
		var data_val3=document.getElementById('term').value;

		var post_data={
		  
           'course_id':data_val1,	   
           'crclm_id':data_val2,	   
           'term_id':data_val3,	   
		}
						   
		$.ajax({type: "POST",
			url: "<?php echo base_url('curriculum/clopomap_review/clo_details'); ?>",
			data: post_data,
						
			success: function(msg){
			
				document.getElementById('table1').innerHTML=msg;
				
			}
		});
	}
	
	
	$('.comment').live('click',function(){
//alert();
$('a[rel=popover]').not(this).popover('destroy');
$('a[rel=popover]').popover({
    html: 'true',
placement: 'top'
})
$('.close_btn').live('click',function(){
//alert();
$('a[rel=popover]').not(this).popover('destroy');
});

});//]]
$('.cmt_submit').live('click',function(){
$('a[rel=popover]').not(this).popover('hide');
var po_id=document.getElementById('po_id').value;
var clo_id=document.getElementById('clo_id').value;
var crclm_id=document.getElementById('crclm_id').value;
var crs_id=document.getElementById('course').value;
var clo_po_cmt=document.getElementById('clo_po_cmt').value;
var post_data={
			'po_id':po_id,
			'clo_id':clo_id,
			'crclm_id':crclm_id,
			'crs_id':crs_id,
			'clo_po_cmt':clo_po_cmt,
		}
	$.ajax({type: "POST",
			url: "<?php echo base_url('curriculum/cloadd/clo_po_cmt_insert'); ?>",
			data: post_data,
						
			success: function(msg){
			
			}
		});
});
	//onmouseover
	function writetext2(po, clo){
		document.getElementById('text1').innerHTML=po;
		document.getElementById('text2').innerHTML=clo;
	}
	
	//on tick
	//checkbox
	
	
	$('.check').live("click",function (){
  if ($(this).is(':checked')){
  document.getElementById(this.id).checked = false;
	 
  }
  else{
   document.getElementById(this.id).checked = true;
  }
});
	
	
	
	
	
	
	
	
	
</script>
