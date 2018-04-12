<?php
/**
* Description	:	List View for Publish Curriculum Module.
* Created		:	04-12-2013. 
* Modification History:
* Date				Modified By				Description
* 04-12-2013		Abhinay B.Angadi        Added file headers, indentations.
* 04-12-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
--------------------------------------------------------------------------------------------------------
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
                <?php $this->load->view('includes/static_sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents
    ================================================== -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Publish Curriculum List
                                </div>
                            </div>
							<div class="control-group">
                                    <div class="controls">
                                        <label class="control-label">Curriculum:<font color='red'>*</font>
                                        <select id="curriculum" name="curriculum" class="input-xlarge span3" onchange="static_display_termwise_details();" >
                                            <option value="">Select Curriculum</option>
                                            <?php foreach ($curriculum_data as $listitem): ?>
                                                <option value="<?php echo htmlentities($listitem['crclm_id']); ?>"> <?php echo $listitem['crclm_name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
										</label>
                                    </div>
                            </div>
                            <div id="course_level_state_grid" >
                            </div>
							<!--Modal to display publish confirmation message -->
                                    <div id="myModal_publish" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_publish" data-backdrop="static" data-keyboard="false"></br>
                                        <div class="container-fluid">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    Publish Confirmation
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-body">
                                            <p> Are you sure you want to Publish it for Delivery Planning? </p>
                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-primary" data-dismiss="modal" id="finalize_publish" name="finalize_publish"><i class="icon-ok icon-white"></i> Ok </button> 
                                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button>
                                        </div>
                                    </div>
                            <!--Do not place contents below this line-->	
                            </section>	
                        </div>
                </div>
            </div>
            <!---place footer.php here -->
            <?php $this->load->view('includes/footer'); ?> 
            <!---place js.php here -->
            <?php $this->load->view('includes/js'); ?>
			<script>
			var base_url = $('#get_base_url').val();
			function static_display_termwise_details()
			{
				var crclm_id = document.getElementById("curriculum").value;
				var post_data = {
					'crclm_id': crclm_id

				}
				
				//course level history.
				$.ajax({type: "POST",
					url: "<?php echo base_url('curriculum/publish_curriculum/static_course_level_fetch_entity_state'); ?>",
					data: post_data,
					success: function(msg) {
						console.log(msg);
						// alert(msg);
						document.getElementById('course_level_state_grid').innerHTML = msg;
					}
				});		
			}
			</script>
			
			
			