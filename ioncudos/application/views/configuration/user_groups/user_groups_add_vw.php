<?php
/**
 * Description	:	To add new group and allocate permissions to that group.
 * 					
 * Created		:	25-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
 * 23-08-2013		   Arihant Prasad			File header, indentation, comments and variable 
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
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Add User Group 
                                </div>
                            </div>
                            <br/>

                            <form class="form-horizontal" method="POST" action="<?php echo base_url('configuration/user_groups/insert_new_user_group'); ?>" name="roleform" id="roleform">
                                <div class="control-group ">
                                    <p class="control-label inline" for="name"> Name: <font color="red"> * </font></p>
                                    <div class="controls">
                                        <?php echo form_input($name); ?> 
                                    </div>
                                </div>

                                <div class="control-group ">
                                    <p class="control-label inline" for="description"> Description: </p>
                                    <div class="controls">
                                        <?php echo form_textarea($description); ?> 
                                    </div>
                                </div>

                                <div class="control-group ">
                                    <p class="control-label inline" for="inputSpecialization"><b> Permissions List </b></p>
                                    <table class="table">
                                        <tbody>
                                            <?php
                                            $permission_list_size = sizeof($permission_list);
                                            for ($k = 0; $k < $permission_list_size; $k = $k + 3):
                                                ?>
                                                <tr>
                                                    <td class="span1">
                                                        <input type="checkbox" name="list[]" value="<?php echo $permission_list[$k]['permission_id']; ?>"/>
                                                    </td>
                                                    <td class="span3">
                                                        <?php echo $permission_list[$k]['permission_function']; ?>
                                                    </td>

                                                    <?php if (array_key_exists($k + 1, $permission_list)) { ?>
                                                        <td class="span1">
                                                            <input type="checkbox" name="list[]" value="<?php echo $permission_list[$k + 1]['permission_id']; ?>"/>
                                                        </td>
                                                        <td class="span3">
                                                            <?php echo $permission_list[$k + 1]['permission_function']; ?>
                                                        </td>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <td class="span1">&nbsp;
                                                        </td>
                                                        <td class="span3">&nbsp;
                                                        </td>
                                                    <?php } ?>

														<?php if (array_key_exists($k + 2, $permission_list)) { ?>
														<td class="span1">
                                                            <input type="checkbox" name="list[]" value="<?php echo $permission_list[$k + 2]['permission_id']; ?>"/>
                                                        </td>
                                                        <td class="span3">
                                                            <?php echo $permission_list[$k + 2]['permission_function']; ?>
                                                        </td>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <td class="span1">&nbsp;
														</td>
                                                        <td class="span3">&nbsp;
                                                        </td>
													<?php } ?>
												</tr>
                                                <?php endfor ?>
                                        </tbody>
									</table>
                                </div>
                                <br>

                                <div class="pull-right">       
                                    <button class="submit1 btn btn-primary" type="submit"><i class="icon-file icon-white"></i> Save</button>
                                    <button class="btn btn-info" type="reset"><i class="icon-refresh icon-white"></i> Reset</button>
							</form>
									<a href= "<?php echo base_url('configuration/user_groups'); ?>"><b class="btn btn-danger"><i class="icon-remove icon-white"></i><span></span> Cancel </b></a>
								</div>
							</form>
							<!--Do not place contents below this line-->	
						</div>
					</section>
				</div>
			</div>
		</div>
		<!---place footer.php here -->
		<?php $this->load->view('includes/footer'); ?>
		<!---place js.php here -->
		<?php $this->load->view('includes/js'); ?>
	<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/user_group.js'); ?>" type="text/javascript"> </script>
	
<!-- End of file user_groups_add_vw.php 
                        Location: .configuration/user_groups/user_groups_add_vw.php -->