<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: CLO to PO Mapping Rework View page which facilitates to view the comment for the 
 * Mapping of clo's (Course Learning Outcome)to particular course with po's (program outcomes) and rework on comments .	  
 * Modification History:
 * Date				Modified By				Description
 * 05-09-2013      Mritunjay B S     Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
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
				<?php  $this->load->view('includes/static_sidenav_2'); ?>
				<div class="span10">
					<!-- Contents
				================================================== -->
					<section id="contents">
					<div id="show">
					<div id="hide">
						<div class="bs-docs-example">
							<!--content goes here-->
							
							<div class="navbar">
                            <div class="navbar-inner-custom">
                                Mapping of Course Outcomes (CO) to <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> Coursewise
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
													<select id="term" name="term" aria-controls="example" onChange = "select_course();">
													<option value="" selected> Select Term</option>
													</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												</label>
											</td>
											<td>
												<label>		
												Course: <font color="red"> * </font>
													<select id="course" name="course" aria-controls="example" onchange="display_grid(); display_reviewer(); select_state();">
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
													<label> Course Outcomes (CO) </label>
													<textarea id="text2" rows="5" cols="5" disabled>
														
													</textarea>
										 		</div>
											</div><!--span4 ends here-->
										</div>
									</div>
									
									
										</form>
									
										
									
								<div id="reviewer">
							
									<!--Reviewer: <font color="red"> * </font> <input type="text" id="reviewer" name="reviewer" value="" readonly />
									id:	<input type="text" id="id" name="id" value="" readonly />-->
									
									</div>
								
									<!--####################################################-->
									<!--Checkbox Modal-->
									<div id="myModal1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
										
										<div class="container-fluid"> <div class="navbar"> <div class="navbar-inner-custom"> Performance Indicators &amp; Measures</div> </div> </div>
										<div class="modal-body" id="comment">
										
										</div>
												
										<div class="modal-footer">
											<button  id="update" onclick="return validateForm();" class="btn btn-primary" data-dismiss="modal"><i class="icon-file icon-white"></i> Save </button>
											
											<button onclick="uncheck();" class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Cancel </button>  
										</div>
										
									</div>	

										<!--Modal to confirm before deleting peo statement-->
								<div id="myModal2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
									<div class="modal-header">
									</div>
									
									<div class="modal-body">
										<p> Are you sure that you want to Uncheck the mapping? </p>
									</div>
													  
									<div class="modal-footer">
								
										<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="unmapping();">  <i class="icon-ok icon-white"></i> Ok </button>
										<button type="" class="cancel btn btn-danger" data-dismiss="modal" onClick="check();"> <i class="icon-remove icon-white"> </i> Cancel </button>
									</div>
								</div>
								
								<!--##############################################################################-->
                                    <!--Modal to display the message "All are checked"-->
                                    <div id="myModal3" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal3" data-backdrop="static" data-keyboard="false"></br>
                                        <div class="container-fluid">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    Mapping Status ! ! ! !
                                                 </div>
                                            </div>
                                        </div>
                                        
                                        <div class="modal-body">
                                            <p> All are checked. </p>
                                        </div>
                                                          
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" data-dismiss="modal" onClick="approve();"><i class="icon-ok icon-white"></i> Ok </button> 
                                        </div>
                                    </div>
                                    
                                    <!--##############################################################################-->
                                    <!--Modal to display the message "Sent for Approval"-->
                                    <div id="myModal4" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
                                        <div class="container-fluid">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    Review Confirmation ! ! ! !
                                                 </div>
                                            </div>
                                        </div>
                                        
                                        <div class="modal-body">
                                            <p> Mapping has been sent for Review. </p>
                                        </div>
                                                          
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" data-dismiss="modal" ><i class="icon-ok icon-white"></i> Ok </button> 
                                        </div>
                                    </div>
                                    
                                    <!--##############################################################################-->
                                    <!--Modal to display the message "Rows marked grey needs your attention"-->
                                    <div id="myModal5" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal5" data-backdrop="static" data-keyboard="false"></br>
                                        <div class="container-fluid">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    Send for Review Failure ! ! ! !
                                                 </div>
                                            </div>
                                        </div>
                                        
                                        <div class="modal-body">
                                            <p> Mapping has to be completed before sending for Reviewing </p>
                                        </div>
                                                          
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" data-dismiss="modal" ><i class="icon-ok icon-white"></i> Ok </button> 
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
			url: "<?php echo base_url('curriculum/clo_po_map/select_term'); ?>",
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
					url: "<?php echo base_url('curriculum/clo_po_map/select_course'); ?>",
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
			url: "<?php echo base_url('curriculum/clo_po_map/static_clo_details'); ?>",
			data: post_data,
						
			success: function(msg){
				document.getElementById('table1').innerHTML=msg;
			}
		});
	}
	
	
	//display Reviewer
	function display_reviewer()
	{
	
		var data_val1=document.getElementById('course').value;
		
		

		var post_data={
		  
           'course_id':data_val1,	   
            
		}
						   
		$.ajax({type: "POST",
			url: "<?php echo base_url('curriculum/clo_po_map/clo_reviewer'); ?>",
			data: post_data,
			//dataType:'json',			
			success: function(msg){
				console.log(msg);
				
				// alert(msg);
				document.getElementById('reviewer').innerHTML=msg;
				// document.getElementById('id').value=msg;
			}
		});
		$('#reviewer').hide();
	}
	
	//onmouseover
	function writetext2(po, clo){
		document.getElementById('text1').innerHTML=po;
		document.getElementById('text2').innerHTML=clo;
	}
	
	//on tick
	//checkbox
	$('.check').live("click",function (){
		var id = $(this).attr('value');
		globalid = $(this).attr('id');
		console.log(globalid);
		console.log(id);
		 window.id = id;
		var data_val=document.getElementById('curriculum').value;
		//alert(id);
		var post_data={
			'po':id,
			'crclm_id':data_val,
		}
		
		if($(this).is(":checked"))
		{
			$.ajax({type: "POST",
				url: "<?php echo base_url('curriculum/clo_po_map/load_pi'); ?>",
				data: post_data,
							
				success: function(msg){
					$('#myModal1').modal('show');
					document.getElementById('comment').innerHTML=msg;
				}
			});
		}
		
		else
		{
			
					$('#myModal2').modal('show');
					document.getElementById('comment').innerHTML;
				}
			
		
	});
	
	
	function map_insert()
	{
	
		var data_val1=document.getElementById('course').value;
		var data_val2=document.getElementById('curriculum').value;
		var data_val3=document.getElementById('term').value;
		var data_val4=document.getElementById('po_id').value;
		var data_val5=document.getElementById('clo_id').value;
	
		var data_val6=document.getElementById('pi').value;
		//var data_val6=document.getElementById('chk').value;
		var chkB=$('input[name="cbox[]"]:checked');
		
		var values = new Array();
		$.each($("input[name='cbox[]']:checked"), function() {
		values.push($(this).val());
 // or you can do something to the actual checked checkboxes by working directly with  'this'
 // something like $(this).hide() (only something useful, probably) 
		});
		var post_data={
		  
           'course_id':data_val1,	   
           'crclm_id':data_val2,	   
           'term_id':data_val3,	  
			'po_id':data_val4,
			'clo_id':data_val5,
			'pi':data_val6,
			'cbox':values,
		}
						   
		$.ajax({type: "POST",
			url: "<?php echo base_url('curriculum/clo_po_map/oncheck_save'); ?>",
			data: post_data,
						
			success: function(msg){
		//	alert(msg);
				//document.getElementById('table1').innerHTML=msg;
			}
		});
	}
	
	//from modal2 
	function unmapping()
	{
		
		console.log(window.id);
		
		var post_data = {
			'po': id,
			
		}
						   
		$.ajax({type: "POST",
			url: "<?php echo base_url('curriculum/clo_po_map/unmap'); ?>",
			data: post_data,
		});
	}
	
	
	
	//validate pi msr form - whether related checkboxes are selected for that radio button
	function validateForm() {
		var formValid = false;
		
		var ckboxLength =$('input[name="cbox[]"]:checked').length;
		var rdbtnLength=$('input[name="pi"]:checked').length;
		
		if (rdbtnLength && ckboxLength) 
			formValid = true;
		
		if (!formValid) 
			alert("Select PI and its Measures!");
		
		else
			map_insert();		

		return formValid;
	}
	
	//disable other radio buttons and checkboxes when one radio button is selected
	$(function() {
		$('#comment').on('change', '.toggle-family', function() {
			if ($(this).attr('checked')) {
				$("[name='cbox[]']").addClass('hide');
				$('.pi_'+$(this).val()).removeClass('hide');
			} 
			$('input[name="cbox[]"]:checked').removeAttr('checked');
		});
	});
	
	//reset when close or cancel button is pressed - on tick
    function uncheck() {
	//alert(globalid);
        $('#'+globalid).prop('checked', false);
    }

    
    //reset when close or cancel button is pressed - on untick
    function check() {
	
        $('#'+globalid).prop('checked', true);
    }
	
	
	// scan row for check (before)
    // $('#scan_row_col').live('click',function(){
	
		// var data_val1=document.getElementById('course').value;
		// if(data_val1){
        // var all_checked = true;
        // $('#table1 tr:not(:nth-child(1), .one)').each(function(){
            // $(this).removeAttr("style");
            // if(!$(this).find('input:checked').length > 0)
            // {
                // $(this).css("background-color", "grey");
                // all_checked = false;
            // }
        // });
        
        // if(all_checked)
            // alert('All are checked');
        
        // else
        // {
            // alert('Rows marked gray needs your attention.');
            // all_checked = true;
        // }
    // }
	// });
	
	
	// scan row for check
    $('#scan_row_col').live('click',function(){
       var data_val=document.getElementById('course').value;
        
        if(data_val)
        {
            var all_checked = true;
            var cbox_len = $(":checkbox").length;
            
            if(cbox_len == 0)
                all_checked = false;
            
            $('#table1 tr:not(:nth-child(1), .one)').each(function(){
                $(this).removeAttr("style");
                
                if(!$(this).find('input:checked').length > 0)
                {
                    $(this).css("background-color", "grey");
                    all_checked = false;
                }
            });
            
            if(all_checked == true )
            {
                //all checked. send for approval
                $('#myModal3').modal('show');
                send_review();
            }
            
            else
            {
                //mapping incompelte
                $('#myModal5').modal('show');
                all_checked = true;
            }
        }
    });

	
	
	
	
	
	// function delete_all()
	// {
	
		// var data_val1=document.getElementById('course').value;
		// var data_val2=document.getElementById('curriculum').value;
		
		
		
		// var post_data={
		  
           // 'course_id':data_val1,	   
           // 'crclm_id':data_val2,	   
         
		   
		   // }
		   
		   // $.ajax({type: "POST",
			// url: "<?php echo base_url('curriculum/clo_po_map/delete_all'); ?>",
			// data: post_data,
						
			// success: function(msg){
		
			// }
		// });
	// }
	
	//comments
	$(document).ready(function() {
		$('.comment').live('click',function(){
			$('a[rel=popover]').not(this).popover('destroy');
			$('a[rel=popover]').popover({
				html: 'true',
				placement: 'top'
			})
		});
	
		$('.close').live('click',function(){
			$('a[rel=popover]').not(this).popover('destroy');
		});
	});
	
	
	function send_review()
	{
		
		var data_val1=document.getElementById('course').value;
		var data_val2=document.getElementById('reviewer_id').value;
		var data_val3=document.getElementById('curriculum').value;
		
		// alert(data_val1);
		// alert(data_val2);
		
		
		
		
		var post_data={
		  
           'course_id':data_val1,	
		   'receiver_id':data_val2,
		   'crclm_id':data_val3,	 
           
		}
						   
		$.ajax({type: "POST",
			url: "<?php echo base_url('curriculum/clo_po_map/dashboard_data'); ?>",
			data: post_data,
						
			success: function(msg){
		//	alert(msg);
				//document.getElementById('table1').innerHTML=msg;
			}
		});
	}
	
	function select_state()
	{
		
		var data_val1=document.getElementById('course').value;
		var data_val2=document.getElementById('curriculum').value;
		
		var post_data={
		  
           'course_id':data_val1,
			'crclm_id':data_val2,	 
           
		}
						   
		$.ajax({type: "POST",
			url: "<?php echo base_url('curriculum/clo_po_map/static_select_data'); ?>",
			data: post_data,
						
			success: function(msg){
			
			//	alert(msg);
				document.getElementById('table1').innerHTML=msg;
		
			}
		});
	}
	
	 function approve()
    {
	//alert();
        var data_val = document.getElementById('curriculum').value;
        
        var post_data = {
           'crclm_id':data_val,       
        }
                           
        $.ajax({type: "POST",
            url: "<?php echo base_url('curriculum/clo_po_map/approve_details'); ?>",
            data: post_data,
                        
            success: function(msg){
                $('#myModal4').modal('show');
                //alert('Mapping has been sent for approval');
            }
        });
    } 
	
	
</script>
