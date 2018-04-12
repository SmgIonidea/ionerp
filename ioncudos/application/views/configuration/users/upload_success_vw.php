<?php
/**
 *
 * Description	:	Displaying the list of users, adding new users, editing existing users
 * 					
 * Created		:	27-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
 * 02-09-2013		   Arihant Prasad			File header, indentation, comments and variable 
												naming.
 *
  ------------------------------------------------------------------------------------------------- */
?>

<!--head here -->
    <?php $this->load->view('includes/head'); ?>
        <!--branding here-->
        <?php $this->load->view('includes/branding'); ?>

        <!-- Navbar here -->
        <?php $this->load->view('includes/navbar'); ?> 
        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php $this->load->view('includes/sidenav_1'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->	
                            <h2> Test <!-----this title should be passed from controller----></h2>	
                            <form class="form-horizontal">
                                <h3> Your file was successfully uploaded! </h3>

                                <?php foreach ($upload_data as $item => $value): ?>
                                    <li><?php echo $item; ?>: <?php echo $value; ?></li>
                                <?php endforeach; ?>

                                <p>
									<button>
										<?php echo anchor('configuration/users/do_upload', 'Preview Link!'); ?>
									</button>
								</p>
                            </form>
                        </div>
					</section>
                </div>
            </div>		
            <? $this->load->view('includes/footer'); ?>
        </div>

<!-- End of file upload_success_vw.php 
			Location: .configuration/users/upload_success_vw.php -->