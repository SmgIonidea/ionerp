<?php
/**
 * Description          :	Bloom's level list page will display the grid containing different bloom's levels
  stages, levels of learning, characteristics and Words. A new bloom's level can be
  added, edited or deleted.
 * 					
 * Created		:	25-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                   Modified By                     Description
 * 27-08-2013		   Arihant Prasad		File header, indentation, comments and variable naming.
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
                            Bloom's Level List
                        </div>
                    </div>
                    <div class="row pull-right">   
                        <a href="<?php echo base_url('configuration/bloom_level/bloom_add_record'); ?>" align="right">
                            <button type="button" class="btn btn-primary"><i class="icon-plus-sign icon-white"> </i> Add </button>
                        </a>
                    </div>
                    <div>
                        <label >
                            <span data-key="lg_crclm">Bloom's Domain</span>:<font color="red"> * </font>
                            <select id="bloom_domain" name="bloom_domain" autofocus = "autofocus" >
                                <!--<option value=""> Select Bloom Domain</option>-->
                                <?php foreach ($bloom_domain_list as $list_item): ?>
                                    <option value="<?php echo $list_item['bld_id']; ?>"> <?php echo $list_item['bld_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </div>
                    <br><br>
                    <div id="example_wrapper" class="dataTables_wrapper container-fluid" role="grid">
                        <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                            <thead align = "center">
                                <tr role="row">
                                    <th class="header" style ="width: 90px;" role="columnheader" tabindex="0" aria-controls="example"> Bloom's Levels</th>
                                    <th class="header headerSortDown" style ="width: 100px;" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> Level Of Learning </th>
                                    <th class="header" style ="width: 270px;" role="columnheader" tabindex="0" aria-controls="example"> Characteristics Of Learning </th>
                                    <th class="header" style ="width: 220px;" role="columnheader" tabindex="0" aria-controls="example"> Bloom's Action Verbs </th>
                                    <th class="header norap" style ="width: 40px;"> Edit </th>
                                    <th class="header" style ="width: 50px;"> Delete </th>
                                </tr>
                            </thead>
                            <tbody role="alert" aria-live="polite" aria-relevant="all" id="check">
                            </tbody>
                        </table>
                    </div>
                    <br>

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
                    <!-- Modal to display the delete confirmation message -->
                    <div id="myModaldelete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Delete Confirmation 
                            </div>
                        </div>
                        <div class="modal-body">
                            <p> Are you sure you want to Delete? </p>
                        </div>
                        <div class="modal-footer">
                            <button class="delete_bloom btn btn-primary "  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button>
                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button>
                        </div>
                    </div>

                    <div id="cant_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: 	none;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Warning 
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>You cannot delete this Bloom's Level, as it is assigned to a <?php echo $this->lang->line('entity_clo_full'); ?> (<?php echo $this->lang->line('entity_clo'); ?>) or 
                                <?php echo $this->lang->line('entity_tlo_full_singular'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>).</p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                        </div>
                    </div>

                    <div class="pull-right">   
                        <a href="<?php echo base_url('configuration/bloom_level/bloom_add_record'); ?>" >
                            <button type="button" class="btn btn-primary"><i class="icon-plus-sign icon-white"> </i> Add </button>
                        </a>
                    </div>	
                    <br><br>
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
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/blooms_level.js'); ?>" type="text/javascript"></script>

<!-- End of file bloom_list_vw.php 
                                        Location: .configuration/standards/bloom_level/bloom_list_vw.php -->
