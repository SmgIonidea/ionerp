<?php
/**
 * Description          :	List View of Approval of PO(Program Outcomes) to PEO(Program Educational Objectives) Mapping Module.
 * Created		:	25-03-2013. 
 * Modification History:
 * Date				Modified By				Description
 * 24-09-2013               Abhinay B.Angadi                File header, function headers, indentation and comments.
 * ------------------------------------------------------------------------------------------------------
 */
?>
<html lang="en">
    <head>
        <meta charset="utf-8"  content="no-cache">	
        <link href="<?php echo base_url('twitterbootstrap/img/favicon.ico'); ?>" rel="shortcut icon" type="image/x-icon" />
        <title> <?php if (isset($title)) echo $title . ' | '; ?> IonCUDOS </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/bootstrap.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/bootstrap-responsive.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/docs.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/js/google-code-prettify/prettify.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/custom.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery-ui.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery-ui.min.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery-ui-custom.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery-ui-custom.min.css'); ?>" media="screen" />
        <!--<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/datepicker.css'); ?>" media="screen" />-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.jqplot.min.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/yearpicker.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/bootstrap-datepicker.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/bootstrap-datepicker.min.css'); ?>" media="screen" />
        <!--<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/bootstrap.min.css'); ?>" media="screen" />-->
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
                <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Le fav and touch icons -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
        <link rel="shortcut icon" href="../assets/ico/favicon.png">

    </head>
    <body data-spy="scroll" data-target=".bs-docs-sidebar" onload="noBack();" onpageshow="if (event.persisted) noBack();">
        <input type="hidden" value="<?php echo base_url(); ?>" id="get_base_url" />
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
                <?php // $this->load->view('includes/static_sidenav_2'); ?>
                <div class="span12">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Approve the entire mapping between <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> and Program Educational Objectives (PEOs) 
                                </div>
                            </div>
                            <div class="span12">
                                <input type="hidden" name="crclm_id" id="crclm_id" value="<?php echo $crclm_id; ?>"/>
                                <p> Curriculum : <font color="red"> * </font>
                                    <select name="curriculum_list" id="curriculum_list" disabled >
                                        <option value="Select Curriculum"> Select Curriculum </option>
                                        <?php foreach ($curriculum_list as $curriculum): ?>
                                            <option value="<?php echo $curriculum['crclm_id']; ?>" <?php
                                            if ($crclm_id == $curriculum['crclm_id']) {
                                                echo "selected=selected";
                                            }
                                            ?> > <?php echo $curriculum['crclm_name']; ?></option>
                                                <?php endforeach; ?>
                                    </select>
                                </p>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">                                        
                                        <div class="bs-docs-example span12 scrollspy-example" style="width:100%; height:auto; overflow:auto;" >
                                            <form method="post" id="po_peo_frm" name="po_peo_frm" action="<?php echo base_url('curriculum/map_po_peo/approver_rework_accept'); ?>" >
                                                <table class="table table-bordered table-hover" style="font-size:12px;  height:'450px'" id="popeoList" aria-describedby="example_info">
                                                    <thead align="center">
                                                        <tr>
                                                            <th class="sorting1" rowspan="1" colspan="1" style="white-space: nowrap; width: 30px;"> 
                                                                <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> / Program Educational Objectives (PEOs) </th>
                                                            <?php
                                                            foreach ($peo_list as $peo):
                                                                /* if ($po['pso_flag'] == 0) {
                                                                  $po_reference = $po['po_reference'];
                                                                  $po_statement = $po['po_statement'];
                                                                  } else {
                                                                  $po_reference = '<font color="blue">' . 'PSO - ' . $po['po_reference'] . '</font>';
                                                                  $po_statement = '<font color="blue">' . $po['po_statement'] . '</font>';
                                                                  } */
                                                                ?>
                                                                <th class="sorting1" rowspan="1" colspan="1" style="width: 10px;" onmouseover="write_po_statement('<?php echo $peo['peo_reference'] . '. ' . $peo['peo_statement']; ?>');" id="<?php echo $peo['peo_statement']; ?>"><?php echo $peo['peo_reference']; ?></th>
                                                            <?php endforeach; ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!--	<?php
                                                        $counter = 1;
                                                        foreach ($po_list as $po):
                                                            ?>
                                                            <?php
                                                            if ($po['pso_flag'] == 0) {
                                                                $po_reference = $po['po_reference'];
                                                                $po_statement = $po['po_statement'];
                                                            } else {
                                                                $po_reference = '<font color="blue">' . $po['po_reference'] . '</font>';
                                                                $po_statement = '<font color="blue">' . $po['po_statement'] . '</font>';
                                                            }
                                                            ?>
                                                                                        <tr id="<?php echo $po['po_id']; ?>">
                                                                                            <td>
                                                                                                <p><br><?php echo $po_reference . '. ' . $po_statement ?> </p>
                                                                                            </td>
                                                            <?php foreach ($peo_list as $peo): ?>
                                                                                                                                                    <td id="<?php echo $po['po_id']; ?>"
                                                                                                                                                        class="pocol<?php echo $peo['peo_id']; ?>" onmouseover="write_po_statement('<?php echo $peo['peo_reference'] . '. ' . $peo['peo_statement']; ?>');" ><br><center>
                                                                                                                                                <input 
                                                                                                                                                    type="text" 
                                                                                                                                                    name='po[]'  
                                                                                                                                                    value="<?php echo $peo['peo_id'] . '|' . $po['po_id'] ?>"  
                                                                                                                                                    onmouseover="write_po_statement('<?php echo $peo['peo_reference'] . '. ' . $peo['peo_statement']; ?>');"
                                                                                
                                                                                                                                                    disabled="disabled"
                                                                <?php
                                                                foreach ($mapped_po_peo as $map_list): {
                                                                        if ($map_list['po_id'] === $po['po_id'] && $map_list['peo_id'] === $peo['peo_id']) {
                                                                            echo 'checked = "checked"';
                                                                        }
                                                                    }
                                                                endforeach;
                                                                ?>
                                                                                                                                                    /></center>
                                                                                                                                            </td>
                                                            <?php endforeach; ?>
                                                                                    </tr>
                                                            <?php
                                                            $counter++;
                                                        endforeach;
                                                        ?> -->
                                                        <?php
                                                        $counter = 1;
                                                        foreach ($po_list as $po):
                                                            ?>
                                                            <?php
                                                            if ($po['pso_flag'] == 0) {
                                                                $po_reference = $po['po_reference'];
                                                                $po_statement = $po['po_statement'];
                                                            } else {
                                                                $po_reference = '<font color="blue">' . $po['po_reference'] . '</font>';
                                                                $po_statement = '<font color="blue">' . $po['po_statement'] . '</font>';
                                                            }
                                                            ?>
                                                        <input type="hidden" name="crclm_id" id="crclm_id" value="<?php echo $crclm_id; ?>"/>
                                                        <tr id="<?php echo $po['po_id']; ?>">
                                                            <td><p><?php echo $po_reference . '. ' . $po_statement ?> </p></td>
                                                            <?php foreach ($peo_list as $peo): ?>
                                                                <td id="<?php echo $po['po_id']; ?>" 
                                                                    class="pocol<?php echo $peo['peo_id']; ?>">

                                                                    <?php
                                                                    foreach ($mapped_po_peo as $map_list): {
                                                                            if ($map_list['peo_id'] == $peo['peo_id'] && $map_list['po_id'] == $po['po_id']) {
                                                                                ?><center title="<?php echo $map_list['map_level_name']; ?>"> <?php echo $map_list['map_level_short_form']; ?> </center>


                                                                        <?php
                                                                        if ($indv_mappig_just[0]['indv_mapping_justify_flag'] == '1') {
                                                                            foreach ($mapped_po_peo as $map_list) {
                                                                                if ($map_list['peo_id'] == $peo['peo_id'] && $map_list['po_id'] == $po['po_id']) {
                                                                                    ?>														

                                                                                    <div id="just"  style="">
                                                                                        <center><a id="comment_popup" title = "<?php
                                                                                            if (htmlspecialchars($map_list['justification']) != null) {
                                                                                                $date = $map_list['created_date'];
                                                                                                $date_new = date('d-m-Y', strtotime($date));
                                                                                                echo $date_new . ":\r\n" . htmlspecialchars($map_list['justification']);
                                                                                            } else {
                                                                                                echo "No Justification has defined.";
                                                                                            };
                                                                                            ?>" abbr="<?php echo $map_list['po_id'] . '|' . $map_list['peo_id'] . '|' . $map_list['pp_id'] . "|" . $map_list['crclm_id']; ?>" class="comment_just cursor_pointer comment" rel="popover" data-content='
                                                                                                   <form id="mainForm" name="mainForm" >
                                                                                                   <textarea readonly id="justification" name="justification" rows="4" cols="5" class="required"></textarea>
                                                                                                   <div class="pull-right">
                                                                                                   <a class="btn btn-danger close_btn cursor_pointer"><i class="icon-remove icon-white"></i> Close</a>
                                                                                                   </div>
                                                                                                   </form>' data-placement="left" data-original-title="Justification: "> Justify </a></center>
                                                                                            <?php break; ?>
                                                                                    </div>

                                                                                <?php } ?>


                                                                                <?php
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            endforeach;
                                                            ?>

                                                            </td>
                                                        <?php endforeach; ?>
                                                        </tr>
                                                        <?php
                                                        $counter++;
                                                    endforeach;
                                                    ?>

                                                    </tbody>
                                                </table>
                                                <input type="hidden" name="crclm_id" id="crclm_id" value="<?php echo $crclm_id; ?>"/>
                                                <!--	</div>	<br/>	
                                                <div class="span12">-->
                                                <div data-spy="scroll" class="bs-docs-example span12" style="width:100%;" id="comment_notes_textarea">	
                                                    <div class="span6">
                                                        <!--<p> <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcome'); ?> :</p>-->
                                                        <p> Program Educational Objectives (PEOs) :</p>
                                                        <textarea id="po_display_textbox_id" style="width:95%" rows="3" cols="5" disabled></textarea>
                                                    </div>
                                                    <div class="span6">
                                                        <p> Overall Justification : </p>
                                                        <textarea id="approver_po_peo_comment_box_id" style="width:95%" rows="3" cols="5" placeholder=""  readonly>
                                                            <?php
                                                            if (!empty($peo_po_notes)) {
                                                                echo $peo_po_notes[0]['notes'];
                                                            } else {
                                                                
                                                            }
                                                            ?>
                                                        </textarea>
                                                    </div>
                                                    <div class="span12">
                                                        <p> Comments : </p>
                                                        <textarea id="peo_po_comment_add" style="width:95%" rows="3" cols="5" placeholder="Enter text here..." >
                                                            <?php
                                                            if (!empty($peo_po_comment_result)) {
                                                                echo $peo_po_comment_result[0]['cmt_statement'];
                                                            } else {
                                                                
                                                            }
                                                            ?>
                                                        </textarea>
                                                    </div>
                                                    <!--Individual Justification-->
                                                    <div class="span12">
                                                        <?php
                                                        $count = count($mapped_po_peo);
                                                        if (!empty($mapped_po_peo)) {
                                                            ?>
                                                            <div id="individual_justification_view" style="overflow:auto;"> Individual Justification:


                                                                <!--table-->
                                                                <table class="table table-bordered" style="width:95%">
                                                                    <thead>
                                                                        <tr style="font-size:12px;">
                                                                            <th><?php echo $this->lang->line('so'); ?> Reference - PEO Reference</th>
                                                                            <th>Justification</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $condition1 = 0;
                                                                        $condition2 = 0;
                                                                        //$condition =0;
                                                                        foreach ($mapped_po_peo as $map_po_peo) {
                                                                            if (!empty($map_po_peo['justification'])) {
                                                                                $po_reference = $map_po_peo['po_reference'];
                                                                                $peo_reference = $map_po_peo['peo_reference'];
                                                                                $justification = htmlspecialchars($map_po_peo['justification']);
                                                                                $condition1 = 1;
                                                                                if ($justification != null) {
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td class="table-bordered" width="90" style="border-left:1px solid #dddddd; font-size:12px; ":><?php echo $po_reference; ?> - <?php echo $peo_reference; ?></td>
                                                                                        <td class="table-bordered" width="790" gridspan = "10" colspan="10" style="width: 610px; font-size:12px;":><?php echo $justification; ?></td>

                                                                                    </tr>
                                                                                    <?php
                                                                                }
                                                                            } else {

                                                                                $condition2 = 1;
                                                                            }
                                                                        }
                                                                        if ($condition1 == 0 && $condition2 != 0) {
                                                                            ?>
                                                                            <tr>
                                                                                <td class="table-bordered" width="90" style="border-left:1px solid #dddddd; font-size:12px; ":>No Justification Defined.</td>
                                                                                <td class="table-bordered" width="790" gridspan = "10" colspan="10" style="width: 610px; font-size:12px;":></td>

                                                                            </tr>

                                                                        <?php }
                                                                        ?>

                                                                    </tbody>
                                                                </table>

                                                            </div>
                                                            <?php
                                                        }
//}
                                                        ?>


                                                    </div>
                                                    <!--End of Individual Justification-->
                                                </div>
                                                <!-- span4 ends here -->
                                        </div>									
                                    </div>
                                    <div id="error"></div>	<div id="po_peo_comment_box_id" style="visibility:hidden"></div>
                                    <input type="hidden" name="state" id="state" />
                                    <?php if ($button_state == 1) {
                                        ?>
                                        <div class="pull-right"><br>
                                            <a id="rework" class="btn btn-danger"><i class="icon-refresh icon-white"></i>Rework</a>
                                                                                        <!-- <input type="button" id = "rework"  class="btn btn-danger />-->
                                            <a id="approved" class="btn btn-success"><i class="icon-ok icon-white"></i> Accept</a>
                                        </div>
                                    <?php } ?>
                                    </form>

                                    <!-- modal for rework confirmation -->
                                    <div id="myModal_rework" class="modal hide fade"  role="dialog" aria-labelledby="myModal_rework" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-header">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    Send for rework of mapping between <?php echo $this->lang->line('sos'); ?> and PEOs
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <p><b>Current step : </b>Review of mapping between <?php echo $this->lang->line('sos'); ?> and PEOs has been completed.
                                            <p><b>Next step : </b>Rework on entire mapping between <?php echo $this->lang->line('sos'); ?> and PEOs.
                                            <p> An email will be sent to Chairman (HOD) - <b id="chairman_username_review" style="color:#E67A17;"></b>
                                            <h4><center>Current state for curriculum : <font color="brown"><b id="crclm_name_review" style="color:#E67A17; text-decoration: underline;"></b></center></font></h4>
                                            <img src="<?php echo base_url('twitterbootstrap/img/modal_workflow_img/review_map_po_to_peo.png'); ?>">
                                            </img>
                                        </div>
                                        <div class="modal-body">
                                            <p> Are you sure you want to send entire mapping between <?php echo $this->lang->line('sos'); ?> and PEOs for rework? </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="ok_rework btn btn-primary" data-dismiss="modal" id="refresh_hide" 
                                                    "><i class="icon-ok icon-white"></i> Ok</button> 
                                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                                        </div>
                                    </div>

                                    <!--Modal to display the message "Sent for Approval"-->
                                    <div id="myModal_approve" class="modal hide fade"  role="dialog" aria-labelledby="myModal_approve" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-header">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    Approve mapping between <?php echo $this->lang->line('sos'); ?> and PEOs
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <p><b>Current step : </b>Review of mapping between <?php echo $this->lang->line('sos'); ?> and PEOs has been completed.
                                            <p><b>Next step : </b>Add <?php echo $this->lang->line('outcome_element_plu_full'); ?> & Performance Indicators (PIs) for the respective approved   <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?>.
                                            <p> An email will be sent to <?php echo $this->lang->line('program_owner_full'); ?> (Curriculum Owner) - <b id="programowner_username" style="color:#E67A17;"></b>
                                            <h4><center>Current state for curriculum : <font color="brown"><b id="crclm_name" style="color:#E67A17; text-decoration: underline;"></b></center></font></h4>
                                            <img src="<?php echo base_url('twitterbootstrap/img/modal_workflow_img/review_map_po_to_peo.png'); ?>">
                                            </img>
                                        </div>
                                        <div class="modal-body">
                                            <p> Are you sure you want to approve the entire mapping between <?php echo $this->lang->line('sos'); ?> and PEOs?
                                        </div>
                                        <div class="modal-footer">
                                            <button class="ok_approve btn btn-primary" data-dismiss="modal" id="refresh"><i class="icon-ok icon-white"></i> Ok</button> 
                                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
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

        <div id="loading" class="ui-widget-overlay ui-front">
            <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
        </div>


        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
        <?php $this->load->view('includes/js'); ?>
        <script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/po_peo_map.js'); ?>" type="text/javascript"></script>
        <script type="text/javascript">
                                                                    $.fn.popover.Constructor.prototype.fixTitle = function () {};
        </script>
