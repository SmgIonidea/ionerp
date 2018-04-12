<?php
/*****************************************************************************************
* Description	:	
		  
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
				================================================== 
				-->
					<section id="contents">
						<div class="bs-docs-example">
							<!--content goes here-->
			<form target="_blank" name="form1" id="form1" method="POST">
							<div class="navbar">
			 <div class="navbar-inner-custom">
				Transpose of Program Articulation Matrix Report
			 </div>
			</div>			<div class="row-fluid">
								<div class="span12">
									<div class="row-fluid">
												<label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													Curriculum <font color="red"> * </font>
													<select size="1" id="crclm" name="crclm" aria-controls="example" onChange = "select_pos(); fetch_crclm();">
														<option value="Curriculum" selected>Select Curriculum</option>
														<?php foreach($crclm_list as $listitem): ?>
																<option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?> </option>
														<?php endforeach; ?>
													</select> 
													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;							
												</label>
							
												<div class="bs-docs-example">
														<div id="table1" style="overflow:auto;">
														</div>
												</div>
									<br>	
									</form>
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
		
	//display grid on select of curriculum
	function select_pos()
	{
	
		
		var data_val1=document.getElementById('crclm').value;
		if(!data_val1)
		$("a#export").attr("href", "#");
		else
		$("a#export").attr("onclick", "generate_pdf();");

		var post_data={
		 
           'crclm_id':data_val1,	   
		   	   
		}
						   
		$.ajax({type: "POST",
			url: "<?php echo base_url('report/transpose_report/grid'); ?>",
			data: post_data,
						
			success: function(msg){
				document.getElementById('table1').innerHTML=msg;
			}
		});
	}
		
</script>
