<?php
/**
* Description	:	List View for Course Domain Module.
* Created		:	07-06-2013.. 
* Modification History:
* Date				Modified By				Description
* 10-09-2013		Abhinay B.Angadi        Added file headers, indentations.
* 11-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
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
                                    Course Domain (Department Verticals) List
                                </div>
                            </div>	
                            <div class="row pull-right">   
                                <a href="<?php echo base_url('curriculum/course_domain/add'); ?>" align="right">
                                    <button type="button" class="btn btn-primary"><i class="icon-plus-sign icon-white"> </i> Add</button>
                                </a>
                            </div>
                            <br><br>
                            <div id="example_wrapper" class="dataTables_wrapper container-fluid" role="grid">
                                <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                                    <thead align = "center">
                                        <tr role="row">
                                            <th class="header span2" role="columnheader" tabindex="0" aria-controls="example">Course Domain Name</th>
                                            <th class="header span3" role="columnheader" tabindex="0" aria-controls="example">Course Domain Description</th>
                                            <th class="header span1"> Edit </th>
                                            <th class="header span1"> Delete </th>
                                        </tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                        <?php foreach ($records as $records): ?>
                                            <tr>
                                                <td class="sorting_1 table-left-align"><?php echo $records['crs_domain_name']; ?></td>  
                                                <td class="sorting_1 table-left-align"><?php echo $records['crs_domain_description']; ?></td>  
                                                <td> <a class="" href="<?php echo base_url('curriculum/course_domain/edit') . '/' . $records['crs_domain_id']; ?>">
                                                        <i class="icon-pencil icon-black"> </i></a></td>
												<?php
                                                if ($records['isdomain'] == 0) {
                                                    ?>
                                                <td>
                                                    <div id="hint">
                                                        <center><a href = "#delete_warning_dialog" id="<?php echo $records['crs_domain_id']; ?>" class=" get_id icon-remove" data-toggle="modal" data-original-title="Delete"  
                                                                   title="Delete" 
                                                                   value="<?php echo $records['crs_domain_id']; ?>"
                                                                   onclick="delete();" >
                                                            </a>
                                                        </center>
                                                    </div>
                                                </td>
												<?php } else { ?>
												<td>
                                                    <div id="hint">
                                                        <center><a class=" get_id icon-remove" data-toggle="modal" href="#cant_delete" data-original-title="Delete"  
                                                                   title="Delete" >
                                                            </a>
                                                        </center>
                                                    </div>
                                                </td>
												<?php } ?>
                                            </tr>
                                        <?php endforeach; ?>	
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <!-- Modal -->
                            <div id="delete_warning_dialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                                 style="display: none;" data-controls-modal="delete_warning_dialog" data-backdrop="static" data-keyboard="false">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Delete confirmation
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete?</p>
                                </div>
                                <div class="modal-footer">
                                    <button class="delete_course_domain btn btn-primary "  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                                    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i>Cancel</button>
                                </div>
                            </div>

							<div id="cant_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-header">
                                    <div class="navbar-inner-custom">
                                        Delete confirmation 
                                    </div>           
                                </div>
                                <div class="modal-body" id="comment">
                                    <p> You cannot delete this course domain. It is used in course. </p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button>
                                </div>
                            </div>
							
                            <div class="pull-right">   
                                <a href="<?php echo base_url('curriculum/course_domain/add'); ?>" >
                                    <button type="button" class="btn btn-primary"><i class="icon-plus-sign icon-white"> </i> Add</button>
                                </a>
                            </div>	
                            <br><br><br>
                        </div>
                        <!--Do not place contents below this line-->	
                    </section>	
                </div>
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <?php $this->load->view('includes/js'); ?>
        <!---place js.php here -->
		<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/course_domain.js'); ?>" type="text/javascript"> </script>