<?php
/*****************************************************************************************
* Description	:	
					
* Created		:	June 2nd, 2013

* Author		:	Mritunjay B S
					Shilpa B 
		  
* Modification History:
* Date                Modified By                Description
*******************************************************************************************/
?>
	<!--head here -->
<?php  
		$this->load->view('includes/head'); 
	?>
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
				<?php  $this->load->view('includes/static_sidenav_3'); ?>
				<div class="span10">
					<!-- Contents
				================================================== -->
					<section id="contents">
						<div class="bs-docs-example">
							<!--content goes here-->
							<form target="_blank" name="form1" id="form1" method="POST" action="<?php echo base_url('report/crs_articulation_report/export_pdf'); ?>">
							<div class="navbar">
								 <div class="navbar-inner-custom">
									Program Articulation Matrix Report
								 </div>
							</div>
							<div class="row-fluid">
								<div class="span12">
								<div>
									<div class="row-fluid">
											<table>
												<tr>
												<td>
												<label>
													Curriculum: <font color="red"> * </font>
													<select size="1" id="crclm" name="crclm" aria-controls="example" onChange = "select_term(); fetch_crclm();">
														<option value="Curriculum" selected>Curriculum</option>
														<?php foreach($results as $listitem): ?>
																<option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?> </option>
														<?php endforeach; ?>
													</select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												</label>
												</td>	
												<td>
												<label>
													Term: <font color="red"> * </font>
													<select size="1" id="term" name="term" aria-controls="example" onChange = "func_grid();">
													</select> 													
												</label>
												</td>
												</tr>
											</table>
											<div class="span8">
												<div id="main_table" class="bs-docs-example">
														<div id="table1" style="overflow:auto;">
														</div>
												</div>
											</div><!-- span8 ends here-->
											
											<div class="span3" id= "po_stmt">
											<div class="bs-docs-example span3" style="overflow:auto; width:330px;" >	
												<div >
													<label> <b><font color="blue"><?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?></font></b></label>
													<div style="overflow:auto;" id="text1">
													</div>
												</div>	
											</div><!--span4 ends here-->
										</div>
										
																				
										
									</div>
								<input type="hidden" name="pdf" id="pdf" />
								<input type="hidden" name="stmt" id="stmt" />
								<input type="hidden" name="curr" id="curr"/>
								
								
									</form>
								
									
									<div id="myModal1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" 	aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
										</br>
										<div class="container-fluid">
											<div class="navbar">
												<div class="navbar-inner-custom">
													List of Mapped CLOs to <?php echo $this->lang->line('sos'); ?>.
												 </div>
											</div>
										</div>
								
										<div class="modal-body" id="comments">
											
										</div>
													
										<div class="modal-footer">
											<button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
										</div>
									</div>
									
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

<script>
	/*You may use scrollspy along with creating and removing elements form DOM. But if you do so, you have to call the refresh method . 
	The following code shows how you may do that:*/
	
	
	$('[data-spy="scroll"]').each(function () {
		var $spy = $(this).scrollspy('refresh')
	});
	
	
	//second dropdown - term
	function select_term()
	{
		var data_val=document.getElementById('crclm').value;
		var post_data={
		   'crclm_id':data_val						   
		}
						   
		$.ajax({type: "POST",
			url: "<?php echo base_url('report/crs_articulation_report/select_term'); ?>",
			data: post_data,
						
			success: function(msg){
				document.getElementById('term').innerHTML = msg;
			}
		});
	}
	
	
	//display grid on select of term
	function func_grid()
	{
		var data_val=document.getElementById('term').value;
		var data_val1=document.getElementById('crclm').value;
		
		if(!data_val)
		$("a#export").attr("href", "#");
		else
		$("a#export").attr("onclick", "generate_pdf();");
		//$("a#edit_peo").attr("href", "<?php echo base_url('curriculum/peo/peo_edit').'/';?>"+data_val1);
		
		var post_data={
		   'crclm_term_id':data_val,
           'crclm_id':data_val1,	   
		}
						   
		$.ajax({type: "POST",
			url: "<?php echo base_url('report/crs_articulation_report/clo_details'); ?>",
			data: post_data,
						
			success: function(msg){
				document.getElementById('table1').innerHTML = msg;
			}
		});
		
		$.ajax({type: "POST",
			url: "<?php echo base_url('report/crs_articulation_report/po_details'); ?>",
			data: post_data,
						
			success: function(msg1){
				document.getElementById('text1').innerHTML = msg1;
			}
		});
	}
	
	function generate_pdf()
	{
			var cloned =$('#table1').clone().html();
			var cloned1 =$('#po_stmt').clone().html();
			//console.log(cloned);
			$('#pdf').val(cloned);
			$('#stmt').val(cloned1);
			$('#form1').submit();
	} 
	
	function fetch_crclm()
	{
		var crclmSelect = document.getElementById("crclm");
		var selectedcrclm = crclmSelect.options[crclmSelect.selectedIndex].text
		document.getElementById("curr").value=selectedcrclm;
	}
	
	function clo_details(temp)
	{
		
		var post_data={
		   'crs_id':temp,	   
		}
						   
		$.ajax({type: "POST",
			url: "<?php echo base_url('report/crs_articulation_report/fetch_clo'); ?>",
			data: post_data,
						
			success: function(msg){
				$('#myModal1').modal('show');
				document.getElementById('comments').innerHTML = msg;
			}
		});
	}
	
	


</script>