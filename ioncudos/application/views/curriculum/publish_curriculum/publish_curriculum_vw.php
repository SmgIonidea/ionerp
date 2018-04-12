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
                <?php $this->load->view('includes/sidenav_2'); ?>
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
                                        <p class="control-label">Curriculum:<font color='red'>*</font>
                                        <select id="curriculum" name="curriculum" class="input-xlarge span3" autofocus="autofocus" onchange="display_termwise_details();" >
                                            <option value="">Select Curriculum</option>
                                            <?php foreach ($curriculum_data as $listitem): ?>
                                                <option value="<?php echo htmlentities($listitem['crclm_id']); ?>"> <?php echo $listitem['crclm_name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
										</p>
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
			function display_termwise_details()
			{
				var crclm_id = document.getElementById("curriculum").value;
				var post_data = {
					'crclm_id': crclm_id

				}
				
				//course level history.
				$.ajax({type: "POST",
					url: "<?php echo base_url('curriculum/publish_curriculum/course_level_fetch_entity_state'); ?>",
					data: post_data,
					success: function(msg) {
						console.log(msg);
						// alert(msg);
						document.getElementById('course_level_state_grid').innerHTML = msg;
					}
				});		
			}
			
			//function to show publish modal
			var crclm_term_id;
			$('.publish_btn').live("click", function(e) {
			//$("#publish_table").live("click",".publish_btn", function(e) {
				e.preventDefault();
				crclm_term_id = $(this).parent().siblings('input[type=hidden]').val();
				if (crclm_term_id) {
					$('#myModal_publish').modal('show');
				}
			});
			
			//function to update term table on termwise publish finalized 
			$('#finalize_publish').live("click", function(e) {
				e.preventDefault();	
				if (crclm_term_id) {
					var post_data = {
						'crclm_term_id': crclm_term_id
					}
					$.ajax({type: "POST",
						url: base_url+'curriculum/publish_curriculum/termwise_publish',
						data: post_data,
						success: function(msg) {
						display_termwise_details();
						}
					});
				}
			});
			</script>
			
			
			