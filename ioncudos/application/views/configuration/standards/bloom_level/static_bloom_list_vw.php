<?php
/**
 * Description	:	Static bloom's level page will display bloom's level, levels of learning,
					characteristics of learning and bloom's Word in the grid.
 * 					
 * Created		:	25-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
 * 27-08-2013		   Arihant Prasad			File header, indentation, comments and variable 
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
                <?php $this->load->view('includes/static_sidenav_1'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Bloom's Level List
                                </div>
                            </div>
                            <div id="example_wrapper" class="dataTables_wrapper container-fluid" role="grid">
                                <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                                    <thead align = "center">
                                        <tr role="row">
                                            <th class="header span2" role="columnheader" tabindex="0" aria-controls="example"> Bloom's Levels </th>
                                            <th class="header headerSortDown span2" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> Level Of Learning </th>
                                            <th class="header span3" role="columnheader" tabindex="0" aria-controls="example"> Characteristics Of Learning </th>
											<th class="header span3" role="columnheader" tabindex="0" aria-controls="example"> Assessment Methods </th>
                                            <th class="header span3" role="columnheader" tabindex="0" aria-controls="example"> Bloom's Action Words </th>
                                        </tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                        <?php foreach ($records as $records): ?>
                                            <tr>
                                                <td class="sorting_1 table-left-align"><?php echo $records['level']; ?></td> 
                                                <td class="sorting_1 table-left-align"><?php echo $records['description']; ?></td>
                                                <td class="sorting_1 table-left-align"><?php echo $records['learning']; ?></td> 
												<td class="sorting_1 table-left-align">
													<a href = "#myModalassessment" id="<?php echo $records['bloom_id']; ?>" class=" get_id" data-toggle="modal" data-original-title="Assessment" rel="tooltip" title="Assessment methods" value="<?php echo $records['bloom_id']; ?>"><i class="icon-list icon-black"></i>Assessment method(s)
													</a>
												</td>
                                                <td class="sorting_1 table-left-align"><?php echo $records['bloom_actionverbs']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>	
                                    </tbody>
                                </table>
                            </div><br>
							<!-- Modal to display the Assessment Methods -->
							<div id="myModalassessment" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-header"><br>
                                    <div class="navbar-inner-custom">
                                        List of Assessment Methods defined. 
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th colspan="2">Bloom's Level: L3 - Applying </th>
                                            </tr>
                                        </thead>
                                        <tbody>
										<tr>
											<td width="10px">
											1.
											</td>
											<td>
											Quiz
											</td>
										</tr>
										<tr>
											<td>
											2.
											</td>
											<td>
											Practical Assignments
											</td>
										</tr>
										<tr>
											<td>
											3.
											</td>
											<td>
											Seminars
											</td>
										</tr>
										<tr>
											<td>
											4.
											</td>
											<td>
											Journal Paper Publishing
											</td>
										</tr>
                                        </tbody>
										<tr>
											<td>
											5.
											</td>
											<td>
											Mini Project
											</td>
										</tr>
                                    </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-danger "  data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button>
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
        <?php $this->load->view('includes/footer'); ?> 
        <?php $this->load->view('includes/js'); ?>
        <!---place js.php here -->

<!-- End of file static_list_edit_vw.php 
                      Location: .configuration/standards/bloom_level/static_list_edit_vw.php -->