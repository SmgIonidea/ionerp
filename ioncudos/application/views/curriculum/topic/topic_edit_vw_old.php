<!DOCTYPE html>
<html lang="en">
<!--head here -->
<?php  $this->load->view('includes/head'); ?>
<body data-spy="scroll" data-target=".bs-docs-sidebar">
<!--branding here-->
<?php  $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php  $this->load->view('includes/navbar'); ?> 
<div class="container">
	<div class="row-fluid">
		<!--sidenav.php-->
		<?php  $this->load->view('includes/sidenav_1'); ?>
		<div class="span10">
			<!-- Contents
        ================================================== -->
			<section id="contents">
			
			<div class="bs-docs-example fixed-height">
			<div class="navbar">
			 <div class="navbar-inner-custom">
				<a class="brand-custom" name= "topic_edit_title_heading" id="topic_edit_title_heading" type="text" style="text-decoration: none"> Edit Topic </a>
			 </div>
			</div>
			<br>
			<!--content goes here-->	
				
					<style type="text/css">
	div{
		padding:8px;
	}
</style>
 
</head>
<form name="form1" id="form1" class="form-horizontal form-inline" method="POST" action="<?php echo base_url('curriculum/topicadd/edit_topic').'/'.$topic_update[0]['topic_id'].'/'.$topic_update[0]['course_id'];?>" >
					<div class="row-fluid">
					<div class="span12">
					
					<div class="row-fluid">
					<div class="span4">
					
			 <label><b>Curriculum Name :</b> </label>
			 <label class="select inline">
			 <b><font color="blue"><?php echo $curriculum[0]['crclm_name'];?></font></b>
			 </label>
					</div>
					<div class="span3">
					<label><b>Term :</b> </label>
			 <label class="select inline">
			  <b><font color="blue"><?php echo $term[0]['term_name'];?></font></b>
			</label>
					</div>
					<div class="span5">
					 <label><b>Course Name : </b></label>
			 <label class="select inline">
			  <b><font color="blue"><?php echo $course[0]['crs_title'];?></font></b>
			 </label>
			 
					</div>
					</div>
					
					
					</div>
					</div>
					
						<div class="row-fluid" id="topic">
						  <div class="span12 add_me">
							<div class="row-fluid">
							  <div class="span6">
								<div class="control-group">
								  <label class="control-label" for="topic_title1">Topic Title <font color='red'>*</font></label>
									<div class="controls">
										<?php echo form_input($topictitle);?>
									</div>
								 </div>
								
							  </div>
							  <div class="span6">
								<div class="control-group">
								  <label class="control-label" for="topic_hours1">Duration in Hours <font color='red'>*</font></label>
								
									<div class="controls">
										<?php echo form_input($topic_hours);?>

									</div>
								
								 </div>
							  </div>
							  
							  <div class="control-group">
								  <label class="control-label" for="topic_content1">Topic Content<font color='red'>*</font></label>
									<div class="controls">
									<?php echo form_textarea($topic_content);?>
										<!--<textarea name="topic_content[1]" id="topic_content1" rows="5" cols="20" type="text" class="required loginRegex" style="margin: 0px; width: 814px; height: 123px;"></textarea>-->
									</div>
								 </div>
							  
							  
							</div>
						  </div>
						</div>
					
					

			 
			 
			 
			
	
<div class="pull-right">
<button type="submit" value="Update" id="update" class="btn btn-primary"><i class="icon-file icon-white"></i>&nbsp;Update</button>
<a class="btn btn-danger" id="cancel" href="<?php echo base_url('curriculum/topic');?>"><i class="icon-remove icon-white"></i>Cancel</a>
</div>
</form>
			<!--Do not place contents below this line-->	
			</div>
			</section>
		</div>
	</div>
</div>
<!---place footer.php here -->
<?php  $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php  $this->load->view('includes/js'); ?>
<!--link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />-->
  <!--script src="http://code.jquery.com/jquery-1.9.1.js"></script>-->
  <!--script type="text/javascript" src="<?php echo base_url('js/jquery_ui.js'); ?>"></script>-->
  <!--link rel="stylesheet" href="/resources/demos/style.css" />-->
  <!--script type="text/javascript" src="<?php echo base_url('js/jquery.validate.js'); ?>"> </script>-->
 <script type="text/javascript">
 
$(document).ready(function(){

    var counter = 2;
 
    $("#addButton").click(function () {
	
 
	var newTextBoxDiv = $(document.createElement('div'))
	     .attr("id", 'TextBoxDiv' + counter);
		 
		 var newTextBoxDiv1 = $(document.createElement('div'))
	     .attr("id", 'TextBoxDivision' + counter);
 
	newTextBoxDiv.after().html('<label class="inline">Topic Title<font color="red">*</font>'+' '+'<input type="text" name="topictitle[]" id="topictitle' + counter + '" value="" ></label>');
	newTextBoxDiv1.after().html('<label class="inline">&nbsp;&nbsp;&nbsp;Duration<font color="red">*</font>'+' '+'<input type="text" name="topic_hours[]" id="topic_hours' + counter + '" value="" ></label>');
 
	newTextBoxDiv.appendTo("#TextBoxesGroup");
	newTextBoxDiv1.appendTo("#TextBoxesGroup");
 
 
	counter++;
     });
 
     $("#removeButton").click(function () {
	if(counter==1){
          alert("No more textbox to remove");
          return false;
       }   
 
		counter--;
 
        $("#TextBoxDiv" + counter).remove();
		 $("#TextBoxDivision" + counter).remove();
 
     });
 
     $("#getButtonValue").click(function () {
 
	var msg = '';
	for(i=1; i<counter; i++){
   	  msg += "\n Textbox #" + i + " : " + $('#textbox' + i).val();
	}
    	  alert(msg);
     });
  });
  
  
  
function select_term()
{
//alert('helllo');

var data_val=document.getElementById('crclm1').value;
//alert(data_val);

                       var post_data={
                               'crclm_id':data_val
							   
                       }
					 // alert(data_val);
					   
					   $.ajax({type: "POST",
					url: "<?php echo base_url('curriculum/topicadd/select_term'); ?>",
					data: post_data,
					
					success: function(msg){
					alert(msg);
					$('#term1').html(msg);
					}
					});
}

function select_course()
{
//var data_val=document.getElementById('curriculum').value;
var data_val=document.getElementById('term1').value;
//alert(data_val);

                       var post_data={
                              // 'crclm_id':data_val
							   'term_id':data_val
                       }
					 // alert(data_val);
					   
					   $.ajax({type: "POST",
					url: "<?php echo base_url('curriculum/topicadd/select_course'); ?>",
					data: post_data,
					
					success: function(msg){
					//alert(msg);
					document.getElementById('course').innerHTML=msg;
					}
					});
}
</script>
   
<style>
input:focus {
    box-shadow: 0 0 5px rgba(0, 0, 255, 1);
    -webkit-box-shadow: 0 0 5px rgba(0, 0, 255, 1); 
    -moz-box-shadow: 0 0 5px rgba(0, 0, 255, 1);
    border:1px solid rgba(0,0,255, 0.8); 
}
</style>

</body>
</html>