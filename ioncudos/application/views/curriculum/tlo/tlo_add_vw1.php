
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
			<!--content goes here-->	
			<div class="bs-docs-example">
			<form class="form-inline" method="post" action="<?php echo base_url('curriculum/tlo/tlo_insert'); ?>">
			
			
			<!-- Form Name -->
			<div class="navbar">
								 <div class="navbar-inner-custom">
									Add <?php echo $this->lang->line('entity_tlo_full_singular'); ?> (<?php echo $this->lang->line('entity_tlo_singular'); ?>)
								 </div>
							</div>

			<!-- Select Basic -->
		

				<div class="row-fluid">
				
				<div class="span12">
					<div>
						<label class="control-label">Curriculum:<font color="red">*</font></label>
 
						<select id="curriculum" name="curriculum" class="input-xlarge span3" onchange="select_term(); ">
						<option value="">Select Curriculum</option>
						<?php foreach($crclm_name_data as $listitem): ?>
						<option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?></option>
						<?php endforeach; ?>
						<!--//	<option>Option one</option>
							//<option>Option two</option>-->
						</select>
						 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
						 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
									  &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
  
				<label class="control-label">Term:<font color="red">*</font></label>
				
 
				<select id="term" name="term" class="input-xlarge span3" onchange="select_course(); "></select>
			</div>
			  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
  
			<div>
  
				<label class="control-label">Course:<font color="red">*</font></label>
				&nbsp;&nbsp;
				&nbsp;
			<select id="course" name="course" class="input-xlarge span3"  onchange="select_topic();"></select>
			 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
			  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
				<label class="control-label">Topic:<font color="red">*</font></label>
 
				<select id="topic" name="topic" class="input-xlarge span3" onchange="select_tlo();"></select>
  
			</div>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
  
				<!-- Select Basic -->
			
	
				<!--content goes here-->
			
					<div id="add_tlo">
						<div id="remove" >
							<div id="tlo_statement" name="tlo_statement" data-spy="scroll" class="bs-docs-example span3" style="width:930px; height:100px;">
								<div class="control-group">
									<label class="control-label" for="tlo_statement" ><?php echo $this->lang->line('entity_tlo_full_singular'); ?> (<?php echo $this->lang->line('entity_tlo_singular'); ?>) Statement: <font color="red">*</font></label>
									
									<?php echo form_textarea($tlo_name);?>
								
								&nbsp;&nbsp;&nbsp; 
								
								<label class="control-label">Bloom's Level: <font color="red">*</font></label>
								
									<select id="bloom_level" name="bloom_level[]" class="input-large" align="center">
									<option value="bloom_level" selected>Select Level</option>
									<?php foreach($bloom_level as $bloom):	?>
									<option value="<?php echo $bloom['bloom_id'];?>"><?php echo $bloom['level'];?></option>
									<?php endforeach;?>
									</select>
									<a id="remove_field_1" class="Delete" href="#"><i class="icon-remove"></i> </a>
								</div>
							</div>
						</div>
					</div>
				
							<div id="insert_before">
							</div>		
							<div class="control-group pull-right">
								<div class="controls">
									<a id="add_field" class="btn btn-primary"><i class="icon-plus-sign icon-white"></i>Add More <?php echo $this->lang->line('entity_tlo_singular'); ?></a>
								</div>
							</div>
						
							<br><br><br><br><br>
								<div id="exercise_question" name="exercise_question" data-spy="scroll" class="bs-docs-example span3" style="width:930px; height:130px;">	
									<label class="control-label" for="exercise_question"> Exercise Questions: <font color="red">*</font></label>
									 <?php echo form_textarea($exercise_question); ?>
									 
									 <label> Review Questions:<font color="red">*</font></label>
									<?php echo form_textarea($review_question); ?>
								</div>
													
					
				</div>
				
					<div class="control-group pull-right">
						<label class="control-label"></label>
					
						<button  class="submit1 btn btn-primary" type="submit"><i class="icon-file icon-white"></i><span></span> Save</button>
						<a href= "<?php echo base_url('curriculum/tlo_list'); ?>"><b class="btn btn-danger"><i class="icon-remove icon-white"></i><span></span> Cancel</b></a>
						<button id="update" class="btn btn-success"><i class="icon-user icon-white"></i> Send for Review </button>
						</div>
					
					</div>	
				
				</div>	
		</form>	
	</section>
		</div>
					
		</div>
	</div>

			


		<!---place footer.php here -->
<?php  $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php  $this->load->view('includes/js'); ?>

<script type="text/javascript">
function fixIds(elem, cntr) {
    $(elem).find("[id]").add(elem).each(function() {
        this.id = this.id.replace(/\d+$/, "") + cntr;
    });	
	
}
var cloneCntr = 2;
$("a#add_field").click(function () { 
console.log('here');
    var table = $("#add_tlo").clone(true,true) 
    fixIds(table, cloneCntr);
    table.insertBefore("#insert_before");
	$('#tlo_statement'+cloneCntr).val('');
	$('#bloom_id_div'+cloneCntr+' div select').attr('name', 'level_new[]');
    cloneCntr++;
})

$('.Delete').click(function() {
rowId=$(this).attr("id").match(/\d+/g);
if(rowId!=1)
{
    $(this).parent().parent().parent().remove();
	
    return false;
}
});


function select_term()
{

var data_val=document.getElementById('curriculum').value;


                       var post_data={
                               'crclm_id':data_val
							   
                       }
					 // alert(data_val);
					   
					   $.ajax({type: "POST",
					url: "<?php echo base_url('curriculum/tlo/select_term'); ?>",
					data: post_data,
					
					success: function(msg){
					//alert(msg);
					document.getElementById('term').innerHTML=msg;
					}
					});
}

function select_course()
{
//var data_val=document.getElementById('curriculum').value;
var data_val=document.getElementById('term').value;
//alert(data_val);

                       var post_data={
                              // 'crclm_id':data_val
							   'term_id':data_val
                       }
					 // alert(data_val);
					   
					   $.ajax({type: "POST",
					url: "<?php echo base_url('curriculum/tlo/select_course'); ?>",
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


                       var post_data={
                               'crs_id':data_val
							   
                       }
					// alert(data_val);
					   
					   $.ajax({type: "POST",
					url: "<?php echo base_url('curriculum/tlo/select_topic'); ?>",
					data: post_data,
					
					success: function(msg){
					//alert(msg);
					document.getElementById('topic').innerHTML=msg;
					}
					});
}




    


/*You may use scrollspy along with creating and removing elements form DOM. But if you do so, you have to call the refresh method . 
	The following code shows how you may do that:*/


	
	//to display po statement to the user
	
	function writetext2(po)
	{
		//var data_val=$(this).attr("id");
		//alert(po);
		document.getElementById('displayclo').innerHTML=po;
	}
	function textout2()
	{
		//var data_val=$(this).attr("id");
		//alert(po);
		document.getElementById('displayclo').innerHTML='';
	}
	
	
	//display grid on select of term
	function select_tlo()
	{
	
		var data_val1=document.getElementById('curriculum').value;


		var post_data={
		  
           'crclm_id':data_val1,	   
		}
						   
		$.ajax({type: "POST",
			url: "<?php echo base_url('curriculum/tlo/tlo_details'); ?>",
			data: post_data,
						
			success: function(msg){
				document.getElementById('table1').innerHTML=msg;
			}
		});
	}

	


</script>

</body>
</html>