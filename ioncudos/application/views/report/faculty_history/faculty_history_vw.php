<?php
/**
 *
 * Description	:	Displaying the list of users, adding new users, editing existing users
 * 					
 * Created		:	29th june 2016
 *
 * Author		:	Bhagyalaxmi.shivapuji
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
 *
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
        <?php $this->load->view('includes/sidenav_3'); ?>
        <div class="span10">
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example">
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Faculty Profile
                        </div>
                    </div>	


                    <div>
                        <label>
                            Department:<font color="red"> * </font>
                            <select size="1" id="dept_id" name="dept_id" autofocus = "autofocus" aria-controls="example" style="" onchange="select_users();">
                                <option value="0" selected>Select Department </option>
                                <?php foreach ($dept_data as $list_item): ?>
                                    <option value="<?php echo $list_item['dept_id']; ?>"> <?php echo $list_item['dept_name']; ?> </option>
                                <?php endforeach; ?>
                            </select>									
                            <?php echo str_repeat("&nbsp;", 40); ?>
                            User:<font color="red"> * </font>					
                            <select id="user_list" name="user_list" class="input-medium" onchange="">
                                <option value="">Select User</option>
                            </select>	
                            <a id="export" href="#" style="display: none;" class="btn btn-success pull-right"><i class="icon-book icon-white"></i> Export .pdf </a>								
                        </label>				
                    </div>                                                                 									                                    
                </div>  

            </section>	
            <form target="_blank" name="form_id" id="form_id" method="POST" action="<?php echo base_url('report/faculty_history/export'); ?>">	
                <div id="export_data">   
                    <input type="hidden" name="pdf" id="pdf" />				
                    <section id="contents" class="faculty_details"  style="display: none;">
                        <div id="user_basic_data" ></div>
                        <div class="bs-docs-example" style=" height:auto; overflow:auto;">  
                            <div class="row-fluid" id="">	

                                <div id="my_qualification_tbl_div" class="add_attr_style"> <br/>
                                    <div style="padding:0 0 0 0px";><h4><b><font color="#800000" size="2px"> Qualification Details : </font></b></h4/></div>
                                    <table class="table table-bordered table-hover" id="my_qualification_tbl" style="font-size:12px;" >
                                        <thead>
                                            <tr>
                                                <th style="width: 10px;">Sl.No</th><th style="width: 250px;">Highest Qualification:</th><th >University</th>
                                                <th style="width: 125px;">Year of Graduation</th><th style="width: 65px;">Upload</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>No Data Available</td><td></td><td></td><td></td><td></td>
                                            </tr>
                                        </tbody>
                                    </table> 
                                </div>
                                <div id="example2_div" class="add_attr_style"> 						
                                    <div style="padding:0 0 0 0px";><h4><b><font color="#800000" size="2px">  Teaching Workload Details : </font></b></h4/></div>
                                    <table class="table table-bordered table-hover " id="example2" style="font-size:12px;text-align:right" >
                                        <thead>
                                            <tr>
                                                <th style="width:30px;">Sl.No</th><th style="width: 130px;">Department</th><th style="width: 60px;">Program Type</th><th style="width: 190px;">Program</th><th style="width:80px;">Program Category</th>
                                                <th  style="width:40px;">Workload Distribution (In Year)</th><th style="width: 70px;">Academic Year</th><th style="width: 50px;">Workload</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                            </tr>
                                        </tbody>
                                    </table></div>
                                <div id="example3_div" class="add_attr_style"> 
                                    <div style="padding:0 0 0 0px";><h4><b><font color="#800000" size="2px"> Research Details :  </font></b></h4/></div>						
                                    <table class="table table-bordered table-hover display nowrap" id="example3" cellspacing="0" width="100%"aria-describedby="example_info" style="font-size:12px;">
                                        <thead>
                                            <tr>
                                                <th style="width: 45px;"></th><th ></th><th></th><th ></th><th style="width: 50px;"></th><th></th><th style="width: 60px;"></th><th style="width: 65px;"></th><th style="width: 65px;"></th><th></th><th></th><th style="width: 50px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                        </tbody>
                                    </table></div>
                                <div id="example1_div" class="add_attr_style"> 
                                    <div style="padding:0 0 0 0px";><h4><b><font color="#800000" size="2px"> Journal Publication  Details : </font></b></h4/></div>	
                                    <table class="table table-bordered table-hover display nowrap" id="example1" cellspacing="0" width="100%"aria-describedby="example_info" style="font-size:12px;">
                                        <thead>
                                            <tr>
                                                <th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                        </tbody>
                                    </table></div>										
                                <div id="example6_div" class="add_attr_style"> 
                                    <div style="padding:0 0 0 0px";><h4><b><font color="#800000" size="2px"> Consultancy Projects Details :  </font></b></h4/></div>							
                                    <table class="table table-bordered table-hover" id="example6" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th style="width: 30px;"> Sl.No </th> <th style="width: 95px;"> Project Code </th> <th style="width: 155px;"> Project Title </th> <th style="width: 115px;"> Client </th> <th style="width: 95px;"> Consultant </th> <th style="width: 85px;"> Co - Consultant </th><th style="width: 30px;">Year</th><th style="width: 50px;">status</th><th style="width: 40px;">Upload</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                            </tr>
                                        </tbody>
                                    </table></div>			
                                <div id="example7_div" class="add_attr_style"> 
                                    <div style="padding:0 0 0 0px";><h4><b><font color="#800000" size="2px"> Sponsored Projects Details :</font></b></h4/></div>	
                                    <table class="table table-bordered table-hover" id="example7" style="width:100px">
                                        <thead>
                                            <tr>
                                                <th style="width: 30px;"> Sl.No </th> <th style="width: 95px;"> Project Code </th> <th style="width: 115px;"> Project Title </th> <th style="width: 115px;"> Principal Investigator </th> <th style="width: 95px;"> Co - Principal Investigator </th> <th style="width: 125px;"> Sponsored Organization</th><th style="width: 30px;">Year</th><th style="width: 50px;">status</th><th style="width: 40px;">Upload</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                            </tr>
                                        </tbody>
                                    </table></div>

                                <div id="example8_div" class="add_attr_style"> 						
                                    <div style="padding:0 0 0 0px";><h4><b><font color="#800000" size="2px"> Award & Honours Details : </font></b></h4/></div>
                                    <table class="table table-bordered table-hover" id="example8" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th style="width: 30px;"> Sl.No </th> <th style="width: 125px;"> Award Name </th><th style="width: 135px;">Award for</th> <th style="width: 240px;"> Sponsored Organization</th><th style="width: 40px;"> Awarded Year </th><th style="width: 115px;">Remarks</th><th style="width: 40px;">Upload</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div id="example9_div" class="add_attr_style"> 						
                                    <div style="padding:0 0 0 0px";><h4><b><font color="#800000" size="2px"> Patent Details : </font></b></h4/></div>
                                    <table class="table table-bordered table-hover" id="example9" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th style="width: 30px;"> Sl.No </th> <th style="width: 280px;"> Title </th><th style="width: 200px;"> Inventor(s) </th> <th style="width: 50px;"> Patent No.</th> <th style="width: 40px;"> Year </th><th style="width: 50px;"> Status</th><th style="width: 40px;">Upload</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                            </tr>
                                        </tbody>
                                    </table></div>

                                <div id="example10_div" class="add_attr_style"> 						
                                    <div style="padding:0 0 0 0px";><h4><b><font color="#800000" size="2px"> Fellowship / Scholarship  Details :  </font></b></h4/></div>
                                    <table class="table table-bordered table-hover" id="example10" style="width:100%">
                                        <thead>
                                            <tr>
                                            <!-- <th> Abstract </th>-->
                                                <th style="width: 30px;"> Sl.No </th> <th style="width: 350px;"> Fellowship / Scholarship </th><th style="width: 140px;"> Awarded by </th> <th style="width: 50px;"> Year</th><th style="width: 50px;"> Type </th><th style="width: 30px;">Upload</th>								</tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td><td></td><td></td><td></td><td></td><td></td>
                                            </tr>
                                        </tbody>
                                    </table></div>					

                                <div id="example11_div" class="add_attr_style"> 						
                                    <div style="padding:0 0 0 0px";><h4><b><font color="#800000" size="2px"> Paper Presentation Details :   </font></b></h4/></div>
                                    <table class="table table-bordered table-hover" id="example11" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th> Title </th><th> Venue </th><th> Year </th> <th> Presentation Type </th><th> Presentation Role </th><th> Presentation Level </th> <th>Upload</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div id="example12_div" class="add_attr_style" > 						
                                    <div style="padding:0 0 0 0px";><h4><b><font color="#800000" size="2px"> Text / Reference Book Details :   </font></b></h4/></div>						
                                    <table class="table table-bordered table-hover" id="example12" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th> Sl.No </th> <th> Book.No </th><th> Book Title </th><th> Co - Author </th><th> ISBN No </th> <th> Copyright Year </th><th>  Year of publication </th> <th>Upload</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                            </tr>
                                        </tbody>
                                    </table></div>

                                <div id="example13_div" class="add_attr_style"> 						
                                    <div style="padding:0 0 0 0px";><h4><b><font color="#800000" size="2px"> Conference / Seminar / Training / Workshop  Details :   </font></b></h4/></div>								
                                    <table class="table table-bordered table-hover dataTable" id="example13" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th> Program Title </th><th>Level</th><th>Type</th><th> Co-ordinator(s)</th> <th>Duration</th> <th> From date </th> <th> To dates </th><th> Sponsored By</th> <th> Sponsored By</th> <th>Upload</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                            </tr>
                                        </tbody>
                                    </table>						
                                </div>				
                                <div id="example14_div"> 
                                    <label><font color="#800000" > <b> User Departments: </b></font></label>						
                                    <table class="table table-bordered table-hover dataTable" id="example14" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="width:50px;"> </th><th></th><th></th><th> </th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td><td></td><td></td><td></td>
                                            </tr>
                                        </tbody>
                                    </table>						
                                </div>

                            </div>
                            <br/><br/>
                        </div>			 		
                    </section>
                </div>			
            </form>
            <a id="export_bottom" href="#"  class="btn btn-success pull-right"><i class="icon-book icon-white"></i> Export .pdf </a>	
            <div id="view_files" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;width:650px;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Records
                        </div>
                    </div>
                </div>
                <div class="modal-body">									
                    <div id="View_uploaded_data"> </div>
                </div>

                <div class="modal-footer">
                        <!--<a class="btn btn-primary" onClick="javascript:publish();"><i class="icon-ok icon-white"></i> Ok </a> -->
                    <a class="btn btn-primary" data-dismiss="modal" id=""><i class="icon-ok icon-white"></i> Ok </a> 				
                </div>
            </div>

        </div>
    </div>				
</div>	
</div>

<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="<?php echo base_url('twitterbootstrap/js/custom/report/faculty_history.js'); ?>" type="text/javascript"></script>

<style>
    .alignright{
        text-align : center;
    }
</style>