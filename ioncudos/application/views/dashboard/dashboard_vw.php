<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?> 
<div id="loading" class="ui-widget-overlay ui-front">
    <img src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
</div>
<div class="container-fluid-custom">
    <div class="row-fluid">
        <!--sidenav.php-->
        <div class="span12">
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example fixed-height">
                    <div class="row-fluid">
                        <!--content goes here-->	
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Dashboard
                            </div>
                        </div>	
                        <div class="tabbable"> <!-- Only required for left/right tabs -->
                            <ul class="nav nav-tabs">
                                <?php if ($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner')) : ?>
                                    <li><a href="#tab1" data-toggle="tab">My Curriculum</a></li>
                                <?php endif ?>
                                <?php if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner') || $this->ion_auth->in_group('BOS')) : ?>
                                    <li class="active"><a href="#tab2" data-toggle="tab">My Actions</a></li>
                                <?php endif ?>
                                <!-- <?php if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner')) : ?>
                                                                            <li><a href="#tab3" data-toggle="tab">Curriculum Summary & Graphs</a></li>
                                <?php endif ?> -->
                                <?php if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner')) : ?>
                                    <li><a href="#tab4" data-toggle="tab" id="tab4_id"> Dashboard </a></li>
                                <?php endif ?> 
                                <!--<?php if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner')) : ?>
                                            <li><a href="#tab5" data-toggle="tab" id="tab5_id"> Dashboard_curriculum </a></li>
                                <?php endif ?>-->
                                <?php if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner')) : ?>
                                    <li><a href="#tab6" data-toggle="tab" id="tab6_id"> Survey - Dashboard</a></li>
                                <?php endif ?>
								<?php if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner')) : ?>
								  <li><a href="#tab7" data-toggle="tab" id="tab7_id"> Faculty - Dashboard</a></li>
                                <?php endif ?>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane" id="tab1"><!-- Tab-1 Contents-->  
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <div class="row-fluid">
                                                <?php
                                                if ($this->ion_auth->is_admin()) {
                                                    ?>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <label>Department: <font color="red"> * </font>:
                                                                    <select  class="span5" name="dept_id" id="dept_id" onchange="curriculums();"></label>
                                                                <option value="">Select Department</option>
                                                                <?php foreach ($dept_name as $dept): ?>
                                                                    <option value="<?php echo $dept['dept_id']; ?>"><?php echo $dept['dept_name']; ?></option>
                                                                <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="span2">
                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <label>Active Curriculum: <input type="checkbox" name="state" id="state" value="1" onclick="active_curriculums();"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                                <?php
                                                if (!$this->ion_auth->is_admin()) {
                                                    ?>
                                                    <div class="span2">
                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <?php foreach ($department as $dept_name): ?>
                                                                    <input type="hidden" name="dept_name" id="dept_name" value="<?php echo $dept_name['dept_id']; ?>"/>
                                                                <?php endforeach; ?>
                                                                <label>Active Curriculum: <input type="checkbox" name="state1" id="state1" value="1" onclick="dept_active_curriculums();"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="crclm_list"> 	
                                        <?php
                                        if (!$this->ion_auth->is_admin()) {
                                            ?>
                                            <table class="table table-bordered table-hover" id="table1" >
                                                <thead >
                                                    <tr>
                                                        <th><h5><font color="#8A0808">Curricula List</font></h5></th>
                                                    </tr>
                                                </thead>
                                                <thead >
                                                    <tr>
                                                        <th><h5><font color="#8A0808">Curriculum Name</font></h5></th>
                                                        <th><h5><font color="#8A0808">Curriculum Owner</font></h5></th>
                                                        <th><h5><font color="#8A0808">Credits</font></h5></th>
                                                        <th><h5><font color="#8A0808">Total Terms</font></h5></th>	
                                                        <th><h5><font color="#8A0808">Minimum Duration(Years)</font></h5></th>
                                                        <th><h5><font color="#8A0808">Maximum Duration(Years)</font></h5></th>
                                                        <th><h5><font color="#8A0808">Curriculum State</font></h5></th>																
                                                    </tr>
                                                </thead>
                                                <tbody >
                                                    <?php foreach ($curriculum_name as $crclm_details):
                                                        ?>
                                                        <tr>
                                                            <td ><b><?php echo $crclm_details['crclm_name']; ?></b></td>
                                                            <td> <?php echo $crclm_details['title'] . " " . $crclm_details['first_name'] . " " . $crclm_details['last_name']; ?> </td>
                                                            <td> <?php echo $crclm_details['total_credits']; ?> </td>
                                                            <td> <?php echo $crclm_details['total_terms']; ?> </td>
                                                            <td> <?php echo $crclm_details['pgm_min_duration']; ?> </td>
                                                            <td> <?php echo $crclm_details['pgm_max_duration']; ?> </td>

                                                            <?php
                                                            if ($crclm_details['status'] == 1) {
                                                                ?>
                                                                <td><b><font color="blue">Active</font></b></td>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <td><b><font color="red">Inactive</font></b></td>
                                                                <?php
                                                            }
                                                            ?>


                                                        </tr>	
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                            <?php
                                        }
                                        ?>
                                    </div>		 

                                </div> <!-- Tab-1 Ends Here-->
                                <div class="tab-pane active" id="tab2">   <!-- Tab-2 Starts Here-->
                                    <div class="container-fluid">
                                        <label>Curriculum Name <font color="red">*</font> : <select name="crclm_name" id="crclm_name" autofocus = "autofocus" onchange="my_action();"></label>
                                        <option value="">Select Curriculum</option>
                                        <?php foreach ($curriculum_name as $crclm_name): ?>
                                            <option value="<?php echo $crclm_name['crclm_id']; ?>" selected><?php echo $crclm_name['crclm_name']; ?></option>
                                        <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="accordion-inner">
                                        <table class="table table-bordered table-hover" id="table1" >
                                            <thead >
                                                <tr>
                                                    <th  >Curriculum Name</th>
                                                    <th  > Details</th>
                                                    <th >Action Due</th>
                                                </tr>
                                            </thead>
                                            <tbody id="my_action_data">
                                                <?php foreach ($dashboard as $dashboard_data2): ?>
                                                    <tr>	
                                                        <?php
                                                        foreach ($curriculum_name as $curriculum):
                                                            $temp = "";
                                                            if ($dashboard_data2['crclm_id'] == $curriculum['crclm_id']) {
                                                                ?>
                                                                <td ><?php echo $curriculum['crclm_name']; ?></td>
                                                                <td ><?php echo $dashboard_data2['description']; ?></td>
                                                                <?php
                                                            }
                                                        endforeach;
                                                        ?>
                                                        <?php
                                                        foreach ($state_id as $state):
                                                            if ($dashboard_data2['state'] == $state['state_id']) {
                                                                ?>
                                                                <td ><?php if ($dashboard_data2['url'] != '#') { ?>
                                                                        <a  href="<?php
                                                                        ////////////////////////////////////////////
                                                                        //changes has been made over here
                                                                        ////////////////////////////////////////////
                                                                        $base = parse_url($dashboard_data2['url']);
                                                                        $base_path = explode('/', $base['path']);
                                                                        unset($base_path[1]);
                                                                        /* unset($base_path[2]);
                                                                          unset($base_path[3]);
                                                                          unset($base_path[4]); */
                                                                        $new_path = implode('/', $base_path);
                                                                        echo base_url() . ltrim($new_path, '/');
                                                                        ?>"><?php
                                                                                //echo $state['status'];
                                                                                echo "My Action";
                                                                                ?> </a>
                                                                        <?php
                                                                        /* echo $base['path']."<br>";
                                                                          echo $new_path."<br>";
                                                                          echo base_url() ; */
                                                                    } else {
                                                                        echo " ";
                                                                        ?>
                                                                    </td>
                                                                    <?php
                                                                }
                                                            }
                                                        endforeach;
                                                        ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                        <br><br>
                                        <br><br>
                                    </div>
                                </div>   <!-- Tab-2 Ends Here-->
                                <div class="tab-pane" id="tab3">   <!-- Tab-3 Starts Here-->
                                    <form target="_blank" name="form_id" id="form_id" method="POST" action="<?php echo base_url('dashboard/dashboard/export_pdf'); ?>">
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <div class="span5">
                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <label>Department<font color="red"> * </font> : 
                                                                    <select  class="" name="dept_crclm" id="dept_crclm" autofocus = "autofocus" style="width:250px;"  onchange="dept_curriculum();"></label>
                                                                <option value="">Select Department</option>
                                                                <?php foreach ($dept_details as $department): ?>
                                                                    <option value="<?php echo $department['dept_id']; ?>"><?php echo $department['dept_name']; ?></option>
                                                                <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="span2">
                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <label>Active Curriculum: <input type="checkbox" name="state2" id="state2" value="1" onclick="dept_curriculum_active();"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="span5">
                                                        <div class="control-group">
                                                            <div class="controls" id="dash_crclm">
                                                                <label>Curriculum <font color="red"> * </font> : 
                                                                    <select  class="" name="dept1" id="dept_id1" onchange="display();
                                                                            fetch_crclm();"></label>
                                                                <option value="">Select Curriculum</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>	
                                                    <!-- <div class="pull-right">
                                                            <a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a> 
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="controls row-fluid">
                                                <div class="span12">		
                                                    <div class="span4" style="height:300px;">
                                                        <div class="span4">
                                                            <div id="chart1" style="height: 300px; width: 420px; position:relative;" class="jqplot-target">
                                                            </div>
                                                            <br>
                                                        </div>
                                                    </div>
                                                    <div class="span4" style="height:300px;">
                                                        <div class="span4">
                                                            <div id="chart1b" style="height: 300px; width: 420px; position:relative;" class="jqplot-target">
                                                            </div>
                                                            <br>
                                                        </div>
                                                    </div>
                                                    <div class="span4" style="height:300px;">
                                                        <div class="span4">
                                                            <div id="chart1c" style="height: 300px; width: 420px; position:relative;" class="jqplot-target">
                                                            </div>
                                                            <br>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="program_level" style="display:none;">
                                            <div class="controls row-fluid">
                                                <div class="span12">		
                                                    <div class="span4" style="height:300px;">
                                                        <div class="span4">
                                                            <div id="chart1_pdf" style="height: 300px; width: 420px; position:relative;" class="jqplot-target">
                                                            </div>
                                                            <br>
                                                        </div>
                                                    </div>
                                                    <div class="span4" style="height:300px;">
                                                        <div class="span4">
                                                            <div id="chart1b_pdf" style="height: 300px; width: 420px; position:relative;" class="jqplot-target">
                                                            </div>
                                                            <br>
                                                        </div>
                                                    </div>
                                                    <div class="span4" style="height:300px;">
                                                        <div class="span4">
                                                            <div id="chart1c_pdf" style="height: 300px; width: 420px; position:relative;" class="jqplot-target">
                                                            </div>
                                                            <br>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="program_level_pdf" id="program_level_pdf" />
                                        <div class="controls row-fluid">		
                                            <div id="state_grid" >
                                            </div>	
                                        </div>

                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Curriculum: Course Level CO To <?php echo $this->lang->line('so'); ?> Mapping Summary
                                            </div>
                                        </div>

                                        <div class="controls row-fluid">		
                                            <div id="" class="span3" >
                                                <label id="crclm_course" for="crclm_for_course"> Curriculum : </label>
                                                <select name="crclm_for_course" id="crclm_for_course" disabled >
                                                    <option>Select Curriculum</option>
                                                </select>
                                            </div>	
                                            <div id="crclm_name_for_course" class="span3" >
                                                <label id="crclm_course" for="crclm_term_for_course"> Term : </label>
                                                <select name="crclm_term_for_course" id="crclm_term_for_course">
                                                    <option>Select Term</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div>
                                            <div class="span4" style="height:300px;">
                                                <div class="span5">
                                                    <div id="course_clo_pie_chart" style="height: 300px; width: 420px; position:relative;" class="jqplot-target">
                                                    </div>
                                                    <br>
                                                </div>
                                            </div>
                                            <div class="span4" style="height:300px;">
                                                <div class="span4">
                                                    <div id="total_map_extent" style="height: 300px; width: 420px; position:relative;" class="jqplot-target">
                                                    </div>
                                                    <br>
                                                </div>
                                            </div>

                                            <div class="span4" style="height:300px;">
                                                <div class="span4">
                                                    <div id="total_map_strength" style="height: 300px; width: 420px; position:relative;" class="jqplot-target">
                                                    </div>
                                                    <br>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="course_level" style="display:none;">
                                            <div class="span4" style="height:300px;">
                                                <div class="span5">
                                                    <div id="course_clo_pie_chart_pdf" style="height: 300px; width: 420px; position:relative;" class="jqplot-target">
                                                    </div>
                                                    <br>
                                                </div>
                                            </div>
                                            <div class="span4" style="height:300px;">
                                                <div class="span4">
                                                    <div id="total_map_extent_pdf" style="height: 300px; width: 420px; position:relative;" class="jqplot-target">
                                                    </div>
                                                    <br>
                                                </div>
                                            </div>

                                            <div class="span4" style="height:300px;">
                                                <div class="span4">
                                                    <div id="total_map_strength_pdf" style="height: 300px; width: 420px; position:relative;" class="jqplot-target">
                                                    </div>
                                                    <br>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="course_level_pdf" id="course_level_pdf" />
                                        <div class="controls row-fluid">		
                                            <div id="course_state_table">

                                            </div>
                                        </div>

                                        <!-- Modal to Course wise Strength of Mapping Graph-->
                                        <div id="crs_map_strength_modal"  class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true" >
                                            <div class="modal-header">
                                                <div class="navbar">
                                                    <div class="navbar-inner-custom" id="crs_title">

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-body" id="crs_map_strenth_body">

                                                <div class="">
                                                    <div id="crs_total_map_strength" style="height: 300px; width: 500px; position:relative;" class="jqplot-target">
                                                    </div>
                                                    <br>
                                                </div>


                                                <div class="">
                                                    <div id="course_map_pie_chart" style="height: 300px; width: 500px; position:relative;" class="jqplot-target">
                                                    </div>
                                                    <br>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close </button>
                                            </div>
                                        </div>

                                        <!-- Topic Level State Grid -->
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Curriculum: Coursewise <?php echo $this->lang->line('entity_topic'); ?> Level <?php echo $this->lang->line('entity_tlo'); ?> To CO Mapping Summary
                                            </div>
                                        </div>

                                        <div class="controls row-fluid">		
                                            <div id="crclm_name_for_topic" class="span3" >
                                                <label id="crclm_course_topic" for="crclm_for_course"> Curriculum : </label>
                                                <select name="course_topic" id="course_topic" disabled>
                                                    <option>Select Curriculum</option>
                                                </select>
                                            </div>	

                                            <div id="term_dropdown" class="span3" >
                                                <label id="course_term" for="course_term_for_topic"> Term : </label>
                                                <select name="course_term_for_topic" id="course_term_for_topic">
                                                    <option>Select Term</option>
                                                </select>
                                            </div>

                                            <div id="course_dropdown" class="span3" >
                                                <label id="course_term" for="course_term_for_topic"> Course : </label>
                                                <select name="course_list" id="course_list">
                                                    <option>Select Course</option>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="controls row-fluid" id="no_data_msg">		

                                        </div>
                                        <div>
                                            <div class="span5" style="height:300px;">
                                                <div class="span5">
                                                    <div id="topic_tlo_pie_chart" style="height: 300px; width: 500px; position:relative;" class="jqplot-target">
                                                    </div>
                                                    <br>
                                                </div>
                                            </div>

                                            <div class="span5" style="height:300px;">
                                                <div class="span5">
                                                    <div id="tlo_clo_bar_chart" style="height: 300px; width: 500px; position:relative;" class="jqplot-target">
                                                    </div>
                                                    <br>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="topic_level" style="display:none;">
                                            <div class="span5" style="height:300px;">
                                                <div class="span5">
                                                    <div id="topic_tlo_pie_chart_pdf" style="height: 300px; width: 500px; position:relative;" class="jqplot-target">
                                                    </div>
                                                    <br>
                                                </div>
                                            </div>

                                            <div class="span5" style="height:300px;">
                                                <div class="span5">
                                                    <div id="tlo_clo_bar_chart_pdf" style="height: 300px; width: 500px; position:relative;" class="jqplot-target">
                                                    </div>
                                                    <br>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="topic_level_pdf" id="topic_level_pdf" />
                                        <div class="controls row-fluid">		
                                            <div id="topic_state_table">

                                            </div>
                                        </div>

                                        <!-- Topic Level State Grid Ends Here-->


                                        <!--<div class="controls row-fluid">				
                                                <div id="course_level_state_grid" >
                                                </div>
                                        </div>-->

                                        <input type="hidden" name="pdf_cloned_crclm_level" id="pdf_cloned_crclm_level" />
                                        <input type="hidden" name="pdf_cloned_course_level" id="pdf_cloned_course_level" />
                                        <input type="hidden" name="pdf_cloned_topic_level" id="pdf_cloned_topic_level" />
                                        <input type="hidden" name="curriculum_id" id="curriculum_id"/>
                                        <br>	
                                        <div class="pull-right">
                                            <a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a>
                                        </div>
                                    </form>
                                </div>  <!-- Tab-3 Ends Here-->
                                <!-- Tab-4 Starts Here-->
                                <div class="tab-pane" id="tab4"> 		
                                    <form target="_blank" name="course_status_id" id="course_status_id" method="POST" action="<?php echo base_url('dashboard/dashboard/export_pdf_course_status'); ?>">
                                        <div id="Content"></div>
                                    </form>
                                </div><!--Tab-4 Ends here-->
                                <!-- Tab-5 Starts Here-->
                               <!--<div class="tab-pane" id="tab5"> 		
                                        <form target="_blank" name="course_status_id_crclm" id="course_status_id_crclm" method="POST" action="<?php echo base_url('dashboard/dashboard/export_pdf_course_status'); ?>">
                                                <div id="Content_crclm"></div>
                                        </form>
                                </div><!--Tab-5 Ends here-->
                                <!-- Tab-6 Starts Here-->
                                <div class="tab-pane" id="tab6"> 		
                                    <form target="_blank" name="survey_term_id" id="survey_term_id" method="POST" action="<?php echo base_url('dashboard/dashboard_survey/survey_export_pdf'); ?>">
                                        <div id="Content_survey_term"></div>
                                    </form>
                                </div><!--Tab-6 Ends here-->  
								<!-- Tab-7 Starts Here-->
                                <div class="tab-pane" id="tab7"> 		
                                    <form target="_blank" name="faculty_dashboard" id="faculty_dashboard" method="POST" action="<?php echo base_url('dashboard/dashboard_survey/survey_export_pdf'); ?>">
                                        <div id="faculty_dashboard_data"></div>
                                    </form>
                                </div><!--Tab-6 Ends here-->

                            </div>
                        </div>
                        <!--Do not place contents below this line-->
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
</body>
</html>
<script src="<?php echo base_url('twitterbootstrap/js/jquery.jqplot.min.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/jquery.jqplot.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/jqplot.barRenderer.min.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/jqplot.pieRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.dateAxisRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.mekkoRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.mekkoAxisRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasTextRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasAxisLabelRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasAxisTickRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.categoryAxisRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.pieRenderer.js'); ?>"></script>

<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.barRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.pointLabels.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.pointLabels.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasOverlay.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasOverlay.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/dashboard/dashboard.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/dashboard/dashboard_course_status.js'); ?>"></script>
<!--<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/dashboard/dashboard_course_status_new.js'); ?>">-->
</script>	<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/dashboard/dashboard_survey_status.js'); ?>"></script>
<script>
    var student_outcome = "<?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcome'); ?>";
    var student_outcome_short = "<?php echo $this->lang->line('student_outcome'); ?>";
    var so = "<?php echo $this->lang->line('so'); ?>";
    var student_outcomes = "<?php echo $this->lang->line('student_outcomes_full'); ?><?php echo $this->lang->line('student_outcomes'); ?>";
    var student_outcomes_short = "<?php echo $this->lang->line('student_outcomes'); ?>";
    var sos = "<?php echo $this->lang->line('sos'); ?>";
    var entity_topic = "<?php echo $this->lang->line('entity_topic') ?>";
</script>

