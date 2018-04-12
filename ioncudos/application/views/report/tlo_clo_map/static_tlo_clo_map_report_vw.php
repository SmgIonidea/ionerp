<?php
/*****************************************************************************************
* Description	:	Select activities that will elicit actions related to the verbs in the 
					learning outcomes. 
					Select Curriculum and then select the related term (semester) which 
					will display related course and select topic pertaining to the course.
					The Mapping data of Related TLO's to CLO's is displayed.
					
* Created By    :   Pavan D M					
* Created date	:	21th May, 2013

		  
* Modification History:
* Date                Modified By                Description

*******************************************************************************************/
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
				<?php  $this->load->view('includes/static_sidenav_3'); ?>
				<div class="span10">
					<!-- Contents
				================================================== -->
					<section id="contents">
						<div class="bs-docs-example">
							<!--content goes here-->
							<form target="_blank" name="form1" id="form1" method="POST" action="<?php echo base_url('report/tlo_clo_map_report/export_pdf'); ?>">
							<div class="navbar">
								 <div class="navbar-inner-custom">
									<?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>) to Course Outcomes (CO) Mapped Report <?php echo $this->lang->line('entity_topic'); ?>wise 
								 </div>
							</div>
							<div class="row-fluid">
								<div class="span12">
									<div class="row-fluid">
										<table>
										<tr>
										<td>
                                            <label>
                                                Curriculum:<font color="red"> * </font>
                                                <select size="1" id="crclm" name="crclm" aria-controls="example" onChange = "select_term();">
                                                    <option value="Curriculum" selected>Select Curriculum</option>
                                                    <?php foreach ($results as $listitem): ?>
                                                        <option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?> </option>
                                                    <?php endforeach; ?>
                                                </select>&nbsp;
											</label>
											</td>
                                            <td>
											<label>
                                                Term:<font color="red"> * </font>
                                                <select size="1" id="term" name="term" aria-controls="example" onChange = "select_course();">
                                                </select> &nbsp;													
											</label>
											</td>
											<td>
											<label>
                                                Course:<font color="red"> * </font>
                                                <select size="1" id="course" name="course" aria-controls="example" onChange = "select_topic();">
                                                </select> &nbsp;
											</label>
											</td>
											<td>
											<label>
                                                <?php echo $this->lang->line('entity_topic'); ?> 
                                                <select size="1" id="topic" name="topic" aria-controls="example" onChange = "func_grid();">
                                                </select> 													
                                            </label>
											</td>
										</tr>
										</table>
												<div id="main_table" class="bs-docs-example">
													
														<div id="table1" style="overflow:auto;">
														</div>
												</div>
									
												<br>
									
										</form>
			
									
									<input type="hidden" name="pdf" id="pdf" />
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
			url: "<?php echo base_url('report/tlo_clo_map_report/select_term'); ?>",
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
					url: "<?php echo base_url('report/tlo_clo_map_report/term_course_details'); ?>",
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
					url: "<?php echo base_url('report/tlo_clo_map_report/course_topic_details'); ?>",
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
		
		if(!data_val3)
		$("a#export").attr("href", "#");
		else
		$("a#export").attr("onclick", "generate_pdf();");

		var post_data={
		   'term_id':data_val,
           'crclm_id':data_val1,	   
           'crs_id':data_val2,	   
           'topic_id':data_val3,	   
		}
						   
		$.ajax({type: "POST",
			url: "<?php echo base_url('report/tlo_clo_map_report/tlo_details'); ?>",
			data: post_data,
						
			success: function(msg){
				document.getElementById('table1').innerHTML=msg;
			}
		});
	}
	
	function generate_pdf()
	{
			var cloned =$('#table1').clone().html();
			//var cloned1 =$('#po_stmt').clone().html();
			//console.log(cloned);
			$('#pdf').val(cloned);
			//$('#stmt').val(cloned1);
			$('#form1').submit();
	} 
	</script>
	</body>
</html>	