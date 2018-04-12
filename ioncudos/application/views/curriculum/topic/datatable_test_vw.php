<?php
/*****************************************************************************************


page refresh -> enable/disable



* Description	:	Program grid provides the entire list of Programs along with their
					respective department, acronym, No. of credits and mode.
					Edit and Toggle buttons are also provided to edit the existing Program
					and to toggle the status between Enable/Disable respectively.
					
* Created		:	March 30th, 2013

* Author		:	Arihant Prasad D 
		  
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
				<?php  $this->load->view('includes/sidenav_2'); ?>
				<div class="span10">
					<!-- Contents
				================================================== -->
					<section id="contents">
					
					<div class="bs-docs-example fixed-height" >
					<!--content goes here-->
						<h2>Topic List </h2>
						
						<br/>
						<form class="form-inline">
		<fieldset>

		<!-- Form Name -->
		

<!-- Select Basic -->
			<div class="control-group ">


			<label class="control-label">Curriculum:</label>
 
			<select id="curriculum" name="curriculum" class="input-xlarge span3" onchange="select_term();">
	<option value="">Curriculum</option>
	
					<?php foreach($crclm_name_data as $listitem): ?>
								<option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?></option>
							<?php endforeach; ?>
				<!--//	<option>Option one</option>
					//<option>Option two</option>-->
			</select>
 
  &nbsp;
  &nbsp;
   &nbsp;
  &nbsp;
   &nbsp;
  &nbsp;
   &nbsp;
  &nbsp;
  
  
  
  
			<label class="control-label">Term:</label>
 
			<select id="term" name="term" class="input-xlarge span2" onchange="select_course();">
			<option>Terms</option>
				
			</select>
  &nbsp;
  &nbsp;
   &nbsp;
  &nbsp;&nbsp;
  &nbsp;
   &nbsp;
  &nbsp;
   &nbsp;
  &nbsp;
   &nbsp;
  &nbsp;
  
  
  
			<label class="control-label">Course:</label>
 
			<select id="course" name="course" class="input-xlarge span3">
			
			<option>Course</option>
				
		</select>
  
  
  
</div>



<!-- Select Basic -->


		</fieldset>
	</form>
	<div class="row">
							<a  class="btn btn-primary pull-right" onclick="GetSelectedValue();"><i class="icon-plus-sign icon-white" ></i>Show</a>
						</div>
								<div>
								</br>
									<table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
										<thead>
											<tr role="row">
												<th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >SL No</th>
												<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Value</th>
												<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Delete</th>
												
												
												
											</tr>
										</thead>
							
										<tbody role="alert" aria-live="polite" aria-relevant="all" id="1">
										<?php $k=0;
										for($k=0;$k<30;$k++)
										{
										?>
										<tr class="new_table" id="<?php echo $k;?>" onclick="displayResult(this)" >
										<td><?php echo $k;?></td>
										<td><?php echo ('Value').$k; ?></td>
										<td ><a href="" class="icon-remove delbutton"  id="datadelete<?php echo $k; ?>"></a></td>
										</tr>
									
											<?php }?>
										</tbody>
									</table>
								</div>
								
								</br></br>
								<div class="row">
							<a  class="btn btn-primary pull-right""" href="<?php echo base_url('configuration/topicadd');?>"><i class="icon-plus-sign icon-white"></i>ADD Topic</a>
							<button id="" class="btn btn-danger">Delete</button>
						</div>
								<!--Modal-->
								<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									<div class="modal-header">
									</div>
									
									<div class="modal-body">
										<p>Are you sure that you want to Enable?</p>
									</div>
													  
									<div class="modal-footer">
										<button class="btn" data-dismiss="modal" aria-hidden="true" Onclick="javascript:enable();">Ok</button>
										<button type="reset" class="cancel btn btn-primary" data-dismiss="modal">Cancel</button>
									</div>
								</div>
													
								<div id="myModal1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									<div class="modal-header">
									</div>
									
									<div class="modal-body">
										<p>Are you sure that you want to Disable?</p>
									</div>						
									<div class="modal-footer">
										<button class="btn" data-dismiss="modal" aria-hidden="true" Onclick="javascript:disable();">Ok</button>
										<button type="reset" class="cancel btn btn-primary" data-dismiss="modal">Cancel</button>
									</div>
								</div>
							</div>
					</div>
				</div>
			</div>
		</div>
			
		<!---place footer.php here -->
		<?php  $this->load->view('includes/footer'); ?> 
		<!---place js.php here -->
		<?php  $this->load->view('includes/js'); ?>
	


<script type="text/javascript">
$('.delbutton').click(function(e){
	e.preventDefault();
	var oTable = $('#example').dataTable();
	var row = $(this).closest("tr").get(0);
	oTable.fnDeleteRow(oTable.fnGetPosition(row));
});
// var oTable = $('#example').dataTable();
// $('.icon-remove').click(function(){
	// alert();
	// oTable.fnDeleteRow((x));
// });

//function displayResult(x)
//{
// var tval=$(this)
// alert(tval);
// var tab=$(this.rowIndex(tval));
// alert(tab);
//alert(tableRow[0].rowIndex);
//var oTable = $('#example').dataTable();
//alert("Row index is: " + (--x.rowIndex));
 //oTable.fnDeleteRow((x));
//}
/*$(document).ready(function() {
var oTable = $('#example').dataTable();
// var tval=$(this).closest('tr.new_table').find('.parent').val();
// $('td').click(function(){
   // var row_index = $(this).parent().index();
   // var col_index = $(this).index();
// });
$('a#datadelete').click(function(x){
  var tval=(x.rowIndex);
  alert(tval);
  // Immediately remove the first row
  oTable.fnDeleteRow(tval);
  });
} );*/
function select_term()
{

var data_val=document.getElementById('curriculum').value;

//alert(data_val);
                       var post_data={
                               'crclm_id':data_val
							   
                       }
					 // alert(data_val);
					   
					   $.ajax({type: "POST",
					url: "<?php echo base_url('configuration/topic/select_term'); ?>",
					data: post_data,
					
					success: function(msg){
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
					url: "<?php echo base_url('configuration/topic/select_course'); ?>",
					data: post_data,
					
					success: function(msg){
					//alert(msg);
					document.getElementById('course').innerHTML=msg;
					}
					});
}

function GetSelectedValue()
{

   
    var data_val=document.getElementById('curriculum').value;
    var data_val1=document.getElementById('term').value;
	var data_val2=document.getElementById('course').value;
   
	 var post_data={
                              'crclm_id':data_val,
							  'term_id':data_val1,
							  'course_id':data_val2,
							
                   }
	
					 
					   
					   $.ajax({type: "POST",
					url: "<?php echo base_url('configuration/topic/show_topic'); ?>",
					data: post_data,
					
					success: function(msg){
					
					document.getElementById('1').innerHTML=msg;
					//$("#example").addClass("table table-bordered table-hover");
					
					//$("example")
								
					
					
					}
					});
}

$('#delete').click(function (e) {
alert('hello123');
            e.preventDefault();
                        
            
            
            var post_data={
                'dept_id':data_val,
                'status':'1',
            }
            
                
                    
                    $.ajax({type: "POST",
                    url: "<?php echo base_url('configuration/add_department/deparment_delete'); ?>",
                    data: post_data,
                    datatype: "JSON",
                    success: function(msg){
                    data_val="enable"+data_val;
                    alert(data_val);
                        $('#data_val').removeClass('icon-ok-circle').addClass('icon-ban-circle');
                        //location.reload();
                    }
                
                    });
                    
                    
                    

                });

function select_value()
{

					 // alert(data_val);
					   
					   $.ajax({type: "POST",
					//url: "<?php echo base_url('clo/program/select_course'); ?>",
					data: post_data,
					
					success: function(msg){
					alert(msg);
					//document.getElementById('course').innerHTML=msg;
					}
					});
}
</script>
</body>
</html>



