<?php
/*****************************************************************************************
* Description	:	Select activities that will elicit actions related to the verbs in the 
					learning outcomes. 
					Select Curriculum and then select the related term (semester) which 
					will display related course and select topic pertaining to the course.
					The Mapping data of Related TLO's to CLO's is displayed.
					
* Created By    :   
* Created date	:	21th May, 2013

		  
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
				<?php  $this->load->view('includes/sidenav_3'); ?>
				<div class="span10">
					<!-- Contents
				================================================== -->
					<section id="contents">
						<div class="bs-docs-example">
							<!--content goes here-->
							<div class="navbar">
								 <div class="navbar-inner-custom">
									<?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>) to Course Outcomes (CO) Mapped Report - <?php echo $this->lang->line('entity_topic'); ?>wise 
								 </div>
							</div>
							<div class="row-fluid">
								<div class="span12">
									<div class="row-fluid">
										<label>
													Curriculum <font color="red"> * </font>
													<select size="1" id="crclm" name="crclm" autofocus = "autofocus" aria-controls="example" onChange = "select_term();">
														<option value="Curriculum" selected>Curriculum</option>
														<?php foreach($results as $listitem): ?>
																<option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?> </option>
														<?php endforeach; ?>
													</select> 
													
													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													
													Term <font color="red"> * </font>
													<select size="1" id="term" name="term" aria-controls="example" onChange = "select_course();">
													</select> 													
												
													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												
													Course <font color="red"> * </font>
													<select size="1" id="course" name="course" aria-controls="example" onChange = "select_topic();">
													</select> 
													
													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													<?php echo $this->lang->line('entity_topic'); ?> <font color="red"> * </font>
													<select size="1" id="topic" name="topic" aria-controls="example" onChange = "func_grid();">
													</select> 													
												</label>
									
												<div class="bs-docs-example">
												
												
														
												<form id="table1" > 
												</div>
										
									</form>
									
									
								
								
								<div id="myModal3" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false" >
									 <div class="container-fluid">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    <a class="brand-custom" name= "tlo_verbs" id="tlo_verbs" type="text" style="text-decoration: none">Suggested Bloom's Level(s)</a>
                                                 </div>
                                            </div>
                                        </div>
									
									<div class="modal-body" id="comment">
										<p> <?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>) Action Verbs Found</p>
									</div>
									<div id="tlo-suggest">
									</div>
									
													  
									<div class="modal-footer">
										<button  class="btn btn-primary" data-dismiss="modal" aria-hidden="true" ><i class="icon-ok icon-white"></i> Ok</button>
										<button  type="reset" class="cancel btn btn-danger" data-dismiss="modal" onClick="check();"><i class="icon-remove icon-white"></i> Cancel</button>
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
			url: "<?php echo base_url('report/tlo_report/select_term'); ?>",
			data: post_data,
						
			success: function(msg){
				document.getElementById('term').innerHTML=msg;
			}
		});
	}
	
	
	function select_course()
{

var data_val1=document.getElementById('term').value;




                       var post_data={
                              // 'crclm_id':data_val
							   'term_id':data_val1
                       }
					 // alert(data_val);
					   
					   $.ajax({type: "POST",
					url: "<?php echo base_url('report/tlo_report/term_course_details'); ?>",
					data: post_data,
					
					success: function(msg){
					//alert(msg);
					document.getElementById('course').innerHTML=msg;

					}
					});
}

function select_topic()
{

var data_val=document.getElementById('course').value;
var data_val1=document.getElementById('term').value;

                       var post_data={
                               'crs_id':data_val,
							   'term_id':data_val1,
							   
                       }
					
					   
					   $.ajax({type: "POST",
					url: "<?php echo base_url('report/tlo_report/course_topic_details'); ?>",
					data: post_data,
					
					success: function(msg){
					//alert(msg);
					document.getElementById('topic').innerHTML=msg;
					}
					});
}

		
	//display grid on select of term
	function func_grid()
	{
		var data_val=document.getElementById('term').value;
		var data_val1=document.getElementById('crclm').value;
		var data_val2=document.getElementById('course').value;
		var data_val3=document.getElementById('topic').value;

		var post_data={
		   'term_id':data_val,
           'crclm_id':data_val1,	   
           'crs_id':data_val2,	   
           'topic_id':data_val3,	   
		}
						   
		$.ajax({type: "POST",
			url: "<?php echo base_url('report/tlo_report/tlo_details'); ?>",
			data: post_data,
						
			success: function(msg){
				document.getElementById('table1').innerHTML=msg;
			}
		});
	}
	
	
	
	// $(".suggest-btn").click(function () {
// var index = parseInt($(this).attr("id").match(/suggest(\d+)/)[1], 10);

// console.log(index);
// });
	
	function suggest(clicked_id)
	{
			// var id=this.id;
			suggestId='suggest'+clicked_id;
			suggesVal=$('#'+suggestId).val();
			console.log(suggesVal);
			var myarr = suggesVal.split("|");
			var myvar = myarr[0] + "|" + myarr[1];
			var tlo=myarr[0];
			var bloom=myarr[1];
// console.log(myvar);
			
			
			var post_data={
		   'tlo_id':tlo,
           'bloo_id':bloom,	   
              
		}
		

			$.ajax({type: "POST",
			url: "<?php echo base_url('report/tlo_report/suggest_bloom'); ?>",
			data: post_data,
						
			success: function(msg){
				document.getElementById('tlo-suggest').innerHTML=msg;
				$('#myModal3').modal('show');
				
			}
		});
	}
				
	
	
	
	</script>
	</body>
</html>	