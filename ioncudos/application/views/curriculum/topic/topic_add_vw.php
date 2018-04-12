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
			<h2>Add <?php echo $this->lang->line('entity_topic'); ?></h2>
				<br><br>
			<!--content goes here-->	
				
					<style type="text/css">
	div{
		padding:8px;
	}
</style>
 
</head>
<form name="form1" id="form1" class="form-horizontal form-inline" method="POST" action="<?php echo base_url('configuration/topicadd');?>" >
					
					<div class="row-fluid control-group">
					<div class="span12">
					
					<div class="row-fluid">
					<div class="span5 control-group"">
					
			 <label>Curriculum Name<font color="red">*</font></label>
			 <label class="select inline">
			 <select name="crclm" id="crclm1" OnChange="select_term();">
			 <option value="">Select Curriculum</option>
					<?php foreach($crclm_name_data as $listitem): ?>
					<option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?></option>
					<?php endforeach; ?>
			 </select></label>
			
					</div>
					<div class="span2">
					<label>Term<font color="red">*</font></label>
			 <label class="select inline">
			 <select name="term" id="term1" class="input-mini" onchange="select_course();">
			 <option>Term</option>
			 </select></label>
					</div>
					<div class="span5">
					 <label>Course Name<font color="red">*</font></label>
			 <label class="select inline">
			 <select name="course" id="course">
			 <option>Select Course</option>
			 </select></label>
			 
					</div>
					</div>
					</div>
					</div>
					
					<div id='TextBoxesGroup' class="row-fluid control-group" >
					<br>
					<div id="TextBoxDiv">
					<label class="inline"><?php echo $this->lang->line('entity_topic'); ?> Title<font color="red">*</font> <?php echo form_input($topictitle);?></label>
					</div>
					<br>
					<div id="TextBoxDivision">
					<label class="inline">&nbsp;&nbsp;&nbsp;Duration<font color="red">*</font> <?php echo form_input($topic_hours);?></label>
					</div>
					</div>
					<br>
					
					

			 
			 
			 
			 
			
<div class="">	
<input type='button' value='+ <?php echo $this->lang->line('entity_topic'); ?>' id='addButton' class="btn btn-primary">
<input type='button' value='X Remove' id='removeButton' class="btn btn-danger">
</div>
<br><br>
<div class="pull-right">
<button type="submit" id="submit" class="btn btn-primary"><i class="icon-file icon-white"></i>Save</button>
<button type="reset" id="submit" class="btn btn-info"><i class="icon-refresh icon-white"></i>Reset</button>
<a href="<?php echo base_url('configuration/topic');?>" id="submit" class="btn btn-danger"><i class="icon-remove icon-white"></i>Cancel</a>

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
 
	newTextBoxDiv.after().html('<label class="inline"><?php echo $this->lang->line('entity_topic'); ?> Title<font color="red">*</font>'+' '+'<input type="text" name="topictitle[]" id="topictitle' + counter + '" value="" class="required" required="" ></label>');
	newTextBoxDiv1.after().html('<label class="inline">&nbsp;&nbsp;&nbsp;Duration<font color="red">*</font>'+' '+'<input type="text" name="topic_hours[]" id="topic_hours' + counter + '" value="" class="required" required="" ></label>');
 
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
	 
	 	$.validator.addMethod("loginRegex", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9\_\-\s\']+$/i.test(value);
    }, "Field must contain only letters, numbers, spaces,underscore ' or dashes.");  
	
	$('#submit1').click(function(e){
		//e.preventDefault();
		//alert('f');
	});
	
	
	
    $.validator.addMethod("loginRegex", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9\_\-\s\']+$/i.test(value);
    }, "Field must contain only letters, numbers, spaces,underscore ' or dashes.");  
  
    
    $("#form1").validate({
	
    	rules:{
    		
    		topictitle: {
					
				 loginRegex: true,
				
				},
				
			topic_hours: {
					
				 loginRegex: true,
				
				},
				
				},
			message:{
			topictitle:"This field is required",
			topic_hours:"This field is required"
			},
			
				errorClass: "help-inline",
				errorElement: "span",
				highlight:function(element, errorClass, validClass) {
					$(element).parent().parent().parent().addClass('error');
				},
				unhighlight: function(element, errorClass, validClass) {
					$(element).parent().parent().parent().removeClass('error');
					$(element).parent().parent().parent().addClass('success');
				}
    	
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
					url: "<?php echo base_url('configuration/topicadd/select_term'); ?>",
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
					url: "<?php echo base_url('configuration/topicadd/select_course'); ?>",
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