
<!--
* Description: Adds the curriculum .
* Created: 1/4/2013
* Author: Jevi V. G
* Modification History:
* Date                Modified By                Description
-----------------------------------------------------------------------------------

-->


<!--head here -->
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
				Curriculum Details
			 </div>
			</div>	
			<form class="form-horizontal" method="POST" id="add_curr" action="<?php echo base_url('curriculum/curriculum/update');?>" name="frm">
			<div class="row-fluid">
			  <div class="span12">
				<div class="row-fluid">
				
				<!-- Span6 starts here-->
				<div class="span7">
				
				
									
			<div class="control-group">
	          <label class="control-label" for="pgm_title">Program Title: <font color='red'>*</font></label>
				<div class="controls">
					<?php foreach($programlist as $listitem1) {
                        $select_options1[$listitem1['pgm_id']] = $listitem1['pgm_title'];//group name column index
                                    }
                                echo form_dropdown('pgm_title', $select_options1, $curriculum_details['0']['pgm_id'],'id="pgm_title"');
                                ?>
                                <input name="pgm_title" type="hidden" value="<?php echo $curriculum_details['0']['pgm_id'];?>"/>
                                
				</div>
                        </div>
					
					
					<div class="control-group">
					<label class="control-label" for="crclm_name">Name of the  Curriculum: </label>
					<div class="controls">
						<input class="input-xlarge span9" name = "crclm_name" id="crclm_name" type="text" value="<?php echo $curriculum_details['0']['pgm_acronym']; ?>"readonly>	
					</div>
					</div>
						
					
					
					
					<div class="control-group">
					<label class="control-label" for="crclm_description">Description</label>
					<div class="controls">
						<?php echo form_textarea($crclm_description);?>
					</div>
					</div>
					
					<div class="control-group">
					<label class="control-label" for="start_year">Start Year: <font color='red'>*</font></label>
							<div class="controls">
                            
                            <select name="start_year" id="start_year" class="required yearchange" disabled="disabled"  >
                            <?php $year=$curriculum_details['0']['start_year']; ?>
                            <?php for($i=1940;$i<=$year;$i++)  { ?>
                    
                             <option value="<?php echo $i;?>" <?php if($i==$year) { ?> selected="selected" <?php } ?>> <?php echo $i;?></option>
                             <?php } ?>
                             </select>
                            </div>
					</div>
					
					
					<div class="control-group">
					<label class="control-label" for="end_year"  >End Year: <font color='red'>*</font></label>
							  <div class="controls">
                            
                            <select name="end_year" id="end_year"class="required yearchange" disabled="disabled"  >
                            <?php $year=$curriculum_details['0']['end_year']; ?>
                            <?php for($i=1940;$i<=$year;$i++)  { ?>
                    
                             <option value="<?php echo $i;?>" <?php if($i==$year) { ?> selected="selected" <?php } ?>> <?php echo $i;?></option>
                             <?php } ?>
                             </select>
                            </div>
					</div>
					 
					
					<div class="control-group">
					<label class="control-label" for="crclm_owner">Curriculum Owner: <font color='red'>*</font></label>
							  <div class="controls"  >
				
								<?php foreach($userlist as $listitem3) {
								$select_options3[$listitem3['id']] = $listitem3['first_name'].' '.$listitem3['last_name'];
								}
								echo form_dropdown('crclm_owner',$select_options3,$curriculum_details['0']['crclm_owner'],'class="required" id="crclm_owner" disabled="disabled"' );
						   ?>
					
					</div>
					</div>
				</div> <!-- Ends here-->
				
				
				<!-- Span6 starts here-->
				<div class="span5">
				  
				<div class="bs-docs-example">
					<div class="navbar">
						<div class="navbar-inner-custom">
						<a class="brand-custom" name= "pgm_title_heading" id="pgm_title_heading" type="text">Program Details</a>
						</div>
					</div>
					

						
					<div class="control-group">
					<label class="control-label" for="total_terms">Total No. of Terms</label>
					<div class="controls">
										
					<input class="required input-xlarge span11" name = "total_terms" id="total_terms" value="<?php echo $curriculum_details['0']['total_terms']; ?>" type="text" readonly>
					
					</div>
					</div>
					
					<div class="control-group">
					<label class="control-label" for="total_credits">Total Credits</label>
					<div class="controls">
					     <input class="required input-xlarge span11" name = "total_credits" id="total_credits" value="<?php echo $curriculum_details['0']['total_credits']; ?>" type="text" readonly>	
					</div>
					</div>
					
					<div class="control-group">
					<label class="control-label" for="term_min_credits">Term Minimum Credits</label>
					<div class="controls">
						<input class="required input-xlarge span11" name = "term_min_credits" id="term_min_credits" value="<?php echo $curriculum_details['0']['term_min_credits']; ?>" type="text" readonly>	
					</div>
					</div>
					
					<div class="control-group">
					<label class="control-label" for="term_max_credits">Term Maximum Credits</label>
					<div class="controls">
						<input class="required input-xlarge span11" name = "term_max_credits" id="term_max_credits" value="<?php echo $curriculum_details['0']['term_max_credits']; ?>" type="text" readonly>	
					</div>
					</div>
					
					<div class="control-group">
					<label class="control-label" for="term_min_duration">Term Minimum Duration</label>
							  <div class="controls">
							  <input class="required input-xlarge span11" name = "term_min_duration" id="term_min_duration" value="<?php echo $curriculum_details['0']['term_min_duration']; ?>" type="text" readonly>	
                            
                          
                            </div>
					</div>
					
					<div class="control-group">
					<label class="control-label" for="term_max_duration">Term Maximum Duration</label>
							 <div class="controls">
							  <input class="required input-xlarge span11" name = "term_max_duration" id="term_max_duration" value="<?php echo $curriculum_details['0']['term_max_duration']; ?>" type="text" readonly>	
                            
                          
                            </div>
					</div>
				
				</div>
				
				</div>
			</div> <!--End of row-fluid-->
			</div><!--End of span12-->
		</div><!--End of row-fluid-->
		<br>
		
		<div class="bs-docs-example">
            <div class="navbar">
              <div class="navbar-inner-custom">
               Curriculum Term Details
              </div>
			  <?php //var_dump($term_details); exit; ?>
				<table class="table table-bordered" style="width:100%">
					  <thead>
						<tr>
						<th>Sl No. </th>
						<th> Name </th>
						<th> Duration </th>
						<th>Credits </th>
						<th>Theory Courses</th>
						<th>Practical Courses</th>
						
						</tr>
					  </thead>
					  <tbody id="s">
				<?php $imax =sizeof($term_details['term_details']); 
				for($i=0;$i<$imax;$i++):?>
				<tr>
					<td> <?php echo ($i+1)?> </td>
					<td> <input class=" required loginRegex" type="text" name="term_name[]" value="<?php echo $term_details['term_details'][$i]['term_name']; ?>" disabled="disabled"></td>
					<input class="" type="hidden" name="crclm_term_id[]" value="<?php echo $term_details['term_details'][$i]['crclm_term_id']; ?>">
					<td> <input id="term_duration_<?php echo $i ?>" class="input-mini required digits" type="text" name="term_duration[]" value="<?php echo $term_details['term_details'][$i]['term_duration']; ?>" disabled="disabled" ></td>
					<td> <input id="term_credits_<?php echo $i ?>" class="input-mini required digits" type="text" name="term_credits[]" value="<?php echo $term_details['term_details'][$i]['term_credits']; ?>" disabled="disabled" ></td>
					<td> <input id="total_theory_courses_<?php echo $i ?>" class="input-mini required digits" type="text" name="total_theory_courses[]" value="<?php echo $term_details['term_details'][$i]['total_theory_courses']; ?>" disabled="disabled"  ></td>
					<td> <input id="total_practical_courses_<?php echo $i ?>" class="input-mini required digits" type="text" name="total_practical_courses[]" value="<?php echo $term_details['term_details'][$i]['total_practical_courses']; ?>" disabled="disabled"> </td>
				</tr>

              <?php  endfor; ?>
						
						
						
					  </tbody>
					</table>
					
            </div>
			<br>
          </div>
		  <br>
		  <br>
			
			<div class="bs-docs-example">
            <div class="navbar">
              <div class="navbar-inner-custom">
                Curriculum Assignment Details
              </div>
			  
			  <table class="table table-bordered" style="width:100%">
              <thead>
                <tr>
                  <th>OBE Elements</th>
                  <th>Department</th>
                  <th>Name</th>
                  <th>Last Date</th>
                </tr>
              </thead>
              <tbody>
			  <tr>
			  <td colspan="4">Program Educational Objectives</td>
			  </tr>
                
                <tr>
                  <th>Approver</th>
                  <td>
				   <?php foreach($departmentlist as $itemlist2){
					$selectoptions2[$itemlist2['dept_id']] = $itemlist2['dept_name'];
					}
					echo form_dropdown('dept_name_peo', $selectoptions2, $approver_details['peo_details']['0']['dept_id'], 'class="required dept_name_peo" id="dept_name_peo" disabled="disabled"');
					?>
				  </td>
                  <td>
				  <?php foreach($userlist as $listitem3) {
						$select_options3[$listitem3['id']] = $listitem3['first_name'].' '.$listitem3['last_name'];
						}
						
						echo form_dropdown('username_peo', $select_options3, $approver_details['peo_details']['0']['approver_id'],'class="required" id="username_peo" disabled="disabled"' );
				 		echo form_input($peo_aid);
				   ?>
				  </td>
                  <td>
				    
                     <div class="control-group">
					<div class="input-prepend">
					   <span class="add-on"><i class="icon-calendar"></i></span><input type="text" class="datepicker required" id="last_date_peo" disabled="disabled"'  value="<?php echo $approver_details['peo_details']['0']['last_date'] ; ?>" name="last_date_peo" />
					</div>
				  </div>
                    
				  </td>
				</tr>
				
				<tr>
			  <td colspan="4"><?php echo $this->lang->line('student_outcomes_full'); ?></td>
			  </tr>
               
                  <th>Approver</th>
                  <td>
				   <?php foreach($departmentlist as $itemlist2){
					$selectoptions2[$itemlist2['dept_id']] = $itemlist2['dept_name'];
					}
					echo form_dropdown('dept_name_po', $selectoptions2, $approver_details['po_details']['0']['dept_id'], 'class="required dept_name_po" id="dept_name_po" disabled="disabled"');
					?>
				  </td>
                  <td>
				  <?php foreach($userlist as $listitem3) {
						$select_options3[$listitem3['id']] = $listitem3['first_name'].' '.$listitem3['last_name'];
						}
						echo form_dropdown('username_po', $select_options3, $approver_details['po_details']['0']['approver_id'],'class="required" id="username_po" disabled="disabled"');
				   echo form_input($po_aid);
				   ?>
				  </td>
                  <td>
				    
                    <div class="control-group">
					<div class="input-prepend">
					   <span class="add-on"><i class="icon-calendar"></i></span><input type="text" class="datepicker required" id="last_date_po" disabled="disabled"  value="<?php echo $approver_details['po_details']['0']['last_date'] ; ?>" name="last_date_po" />
					</div>
				  </div>
                    
				  </td>
				</tr>
				
				<tr>
			  <td colspan="4">PO and PEO Mapping</td>
			  </tr>
              
                  <th>Approver</th>
                  <td>
				   <?php foreach($departmentlist as $itemlist2){
					$selectoptions2[$itemlist2['dept_id']] = $itemlist2['dept_name'];
					}
					echo form_dropdown('dept_name_po_peo_mapping', $selectoptions2, $approver_details['po_peo_details']['0']['dept_id'], 'class="required dept_name_po_peo_mapping" id="dept_name_po_peo_mapping" disabled="disabled"');
					echo form_input($po_peo_aid);
					?>
				  </td>
                  <td>
				  <?php foreach($userlist as $listitem3) {
						$select_options3[$listitem3['id']] = $listitem3['first_name'].' '.$listitem3['last_name'];
						}
						echo form_dropdown('username_po_peo_mapping',$select_options3,$approver_details['po_peo_details']['0']['approver_id'] ,'class="required" id="username_po_peo_mapping" disabled="disabled"' );
				   ?>
				  </td>
                  <td>
				  
                     <div class="control-group">
					<div class="input-prepend">
					   <span class="add-on"><i class="icon-calendar"></i></span><input type="text" class="datepicker required" id="last_date_po_peo_mapping" disabled="disabled"  value="<?php echo $approver_details['po_peo_details']['0']['last_date'] ; ?>" name="last_date_po_peo_mapping" />
					</div>
				  </div>
                    
				  
				  </td>
				</tr>
			</tbody>
            </table>
			</div>
          </div>
		  <br>
		  <div class="pull-right">       
				
				<a href= "<?php echo base_url('curriculum/curriculum/static_index'); ?>"><b class="btn btn-danger"><i class="icon-remove icon-white"></i><span></span>Cancel</b></a>
		</div>
		
		
	<input class="" type="hidden" name="crclm_id" value="<?php echo $crclm_id; ?>">
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
<script>
function callAndUpdateProgramDetails(value){
console.log(value);
$.get(
    "<?php echo site_url('curriculum/curriculum/program_details_by_program_id'); ?>"+"/"+value,
    null,
    function(data) {
        var arrayData = JSON.parse(data);
		console.log(arrayData[0]);
		$('#pgm_title').val(arrayData[0].pgm_title);
		$('#total_terms').val(arrayData[0].total_terms);
		$('#total_credits').val(arrayData[0].total_credits);
		$('#term_min_credits').val(arrayData[0].term_min_credits);
		$('#term_max_credits').val(arrayData[0].term_max_credits);
		$('#term_min_duration').val(arrayData[0].term_min_duration);
		$('#term_max_duration').val(arrayData[0].term_max_duration);
		$('#pgm_acronym').val(arrayData[0].pgm_acronym);
		$('a#pgm_title_heading').text(arrayData[0].pgm_title);
		var start_year=$('#start_year').val();
		var end_year=$('#end_year').val();
		var acronym=$('#pgm_acronym').val();
		crclm_name=acronym+' '+start_year+'-'+end_year;
		$('#crclm_name').val(crclm_name);
		n=arrayData[0].total_terms;
		$('#s').empty();
        $('#s').append  ('<tr><th>Sl.No</th> <th>Name</th> <th>Duration</th> <th>Credits</th> <th>Total Theory courses</th><th>Total Practical courses</th><th>Action</th></tr>');
        for(var i=0;i<n;i++){
		$('#s').append('<tr><td><input class="input-mini required" type="text" name=slno value='+(i+1)+'></td><td><input type="text" name=tname_'+(i+1)+'></td><td><input class="input-mini" type="text" name=dur_'+(i+1)+'></td><td><input type="text" class="input-mini" name=credits_'+(i+1)+'></td><td><input type="text" class="input-mini" name=theory_'+(i+1)+'></td><td><input class="input-mini" type="text" name=practical_'+(i+1)+'></td><td><a href="#" id="myModal" class="icon-pencil" data-toggle="modal"></a></tr>');
		}
    },
    "html"
);
}

$('.target').change(function() {
var value=$(this).val();
callAndUpdateProgramDetails(value);
});

$('.yearchange').change(function() {
var start_year=$('#start_year').val();
var end_year=$('#end_year').val();
var acronym=$('#pgm_acronym').val();
crclm_name=acronym+' '+start_year+'-'+end_year;
$('#crclm_name').val(crclm_name);

});

</script>

<script>
    
    function curriculum_name(value){
	console.log(value);

   $.get(
    "<?php echo site_url('curriculum/curriculum/curriculum_name'); ?>"+"/"+value,
    null,
    function(data) {
        var arrayData = JSON.parse(data);
		console.log(arrayData[0]);
		$('#pgm_acronym').val(arrayData[0].pgm_acronym);
		$('#start_year').val(arrayData[0].start_year);
		$('#end_year').val(arrayData[0].end_year);
		var curriculumName = pgm_acronym +' '+ start_year +' '+end_year;
		$('#end_year').val(curriculumName);
},
    "html"
);
}


</script>



<script type="text/javascript">
$(document).ready(function(){
    $.validator.addMethod("loginRegex", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9\_\-\s\']+$/i.test(value);
    }, "Field must contain only letters, numbers, spaces, ' or dashes.");  
});

$.validator.addMethod(
        "regex",
        function(value, element, regexp) {
            var re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
        },
        "Only letter and numbers are allowed(minimum 6 characters)"
);
	  $(document).ready(function(){
	document.getElementById("pgm_title").disabled=true;
			$("#add_curr").validate({
				
				
				errorClass: "help-inline",
				errorElement: "span",
				highlight:function(element, errorClass, validClass) {
					$(element).parents('.control-group').addClass('error');
				},
				unhighlight: function(element, errorClass, validClass) {
					$(element).parents('.control-group').removeClass('error');
					$(element).parents('.control-group').addClass('success');
				}
			});
				  $(function() {
        $( '.datepicker' ).datepicker({dateFormat: 'yy-mm-dd'});
	
		
    });
		
		});
		
		
  </script>
  
  
  
  <script>

function callAndUpdateUser_peo(value){
$("#username_peo").empty();
$.get(
    "<?php echo site_url('/curriculum/curriculum/users_by_department'); ?>"+"/"+value,
    null,
    function(data) {
  console.log("In success callaback");
        var arrayData = JSON.parse(data);
        var i=0;
        if (arrayData!='')
        var completeOptions = '<option value="">Select User</option>';
        else
        var completeOptions = '<option value="">No Users in this department</option>';
        for(i=0;i<arrayData.length; i++){

            var item = arrayData[i];
            completeOptions += '<option value="' + item.id + '">' + item.first_name + item.last_name +'</option>';
        }
        $('#username_peo').html(completeOptions);
    },
    "html"
);
}

$('.dept_name_peo').change(function() {
var value=$(this).val();
callAndUpdateUser_peo(value);

});



function callAndUpdateUser_po(value){
$("#username_po").empty();
$.get(
    "<?php echo site_url('/curriculum/curriculum/users_by_department'); ?>"+"/"+value,
    null,
    function(data) {
  console.log("In success callaback");
        var arrayData = JSON.parse(data);
        var i=0;
        if (arrayData!='')
        var completeOptions = '<option value="">Select User</option>';
        else
        var completeOptions = '<option value="">No Users in this department</option>';
        for(i=0;i<arrayData.length; i++){

            var item = arrayData[i];
            completeOptions += '<option value="' + item.id + '">' + item.first_name + item.last_name +'</option>';
        }
        $('#username_po').html(completeOptions);
    },
    "html"
);
}

$('.dept_name_po').change(function() {
var value=$(this).val();
callAndUpdateUser_po(value);

});



function callAndUpdateUser_mp(value){
$("#username_po_peo_mapping").empty();
$.get(
    "<?php echo site_url('/curriculum/curriculum/users_by_department'); ?>"+"/"+value,
    null,
    function(data) {
  console.log("In success callaback");
        var arrayData = JSON.parse(data);
        var i=0;
        if (arrayData!='')
        var completeOptions = '<option value="">Select User</option>';
        else
        var completeOptions = '<option value="">No Users in this department</option>';
        for(i=0;i<arrayData.length; i++){

            var item = arrayData[i];
            completeOptions += '<option value="' + item.id + '">' + item.first_name + item.last_name +'</option>';
        }
        $('#username_po_peo_mapping').html(completeOptions);
    },
    "html"
);
}

$('.dept_name_pe_peo_mapping').change(function() {
var value=$(this).val();
callAndUpdateUser_mp(value);

});
</script> 
  
  
</body>

</html>

