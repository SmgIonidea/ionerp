<?php
/**
 * Description	:	Add/Edit/Delete Approved Program Outcome grid along with its corresponding 
  Performance Indicators and Measures

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 * 11-01-2014		   Arihant Prasad			File header, function headers, indentation 
  and comments.
  ---------------------------------------------------------------------------------------------- */
?>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <link href="<?php echo base_url('twitterbootstrap/img/favicon.ico'); ?>" rel="shortcut icon" type="image/x-icon" />
        <title> <?php if (isset($title)) echo $title . ' | '; ?>Curriculum Design</title>
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

        <style>
            body .modal-measures {
                /* new custom width */
                width: 850px;
                /* must be half of the width, minus scrollbar on the left (30px) */
                margin-left: -425px;
            }	
        </style>
    </head>

    <body data-spy="scroll" data-target=".bs-docs-sidebar" onload="noBack();" onpageshow="if (event.persisted) noBack();">
        <input type="hidden" value="<?php echo base_url(); ?>" id="get_base_url" />
        <!--branding here-->
        <?php $this->load->view('includes/branding'); ?>
        <?php $this->load->view('includes/navbar'); ?>
        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php $this->load->view('includes/sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <div id="loading" class="ui-widget-overlay ui-front">
                        <img src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                    </div>
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Manage <?php echo $this->lang->line('outcome_element_plu_full'); ?> and Performance Indicators(PIs)
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div>
                                        <div class="control-group">
                                            <div class="controls">
                                                <p class="control-label" style="float:left;" for="po_statement_1"> <?php echo $this->lang->line('student_outcome_full'); ?> (<?php echo $this->lang->line('so'); ?>) Statement: </p>
                                                <p class="control-label" style="float:right;  width:50%;" for="po_statement_1"> BoS Comments: </p>
                                                <?php 
                                                if($po_data['0']['pso_flag'] == 0){
                                                ?>
                                                <!-- display po statement -->
                                                <textarea class="required input-xxlarge readonly valid" col="40" row="2" readonly="readonly" name="po_statement[]" id="po_statement_1" type="text" style="float:left; margin: 0px; width: 49%; height: 60px;"><?php echo $po_data['0']['po_reference'] . '. ' . $po_data['0']['po_statement'];  ?></textarea>
                                                <?php }else{
                                                ?>
                                                <textarea class="required input-xxlarge readonly valid" col="40" style="color:blue;" row="2" readonly="readonly" name="po_statement[]" id="po_statement_1" type="text" style="float:left; margin: 0px; width: 49%; height: 60px;"><?php echo $po_data['0']['po_reference'] . '. ' . $po_data['0']['po_statement'];  ?></textarea>
                                                <?php } ?>
                                                <?php
                                                $i = 1;
                                                $value = '';
                                                foreach ($cmt_data as $bos_comment) {
                                                    $value.= trim($i++ . '. ' . $bos_comment['cmt_statement']);
                                                }
                                                $data = array(
                                                    'name' => 'bos_comment',
                                                    'id' => 'bos_comment',
                                                    'class' => 'required input-xxlarge readonly valid',
                                                    'value' => $value,
                                                    'readonly' => 'readonly',
                                                    'rows' => '2',
                                                    'col' => '40',
                                                    'style' => 'float:right; margin: 0px; width: 50%; height: 60px;'
                                                );

                                                echo form_textarea($data);
                                                ?>
                                                <!-- display bos comments -->

                                                <br><br><br><br>
                                                <div class="control-group">
                                                    <div class="controls"><br>
                                                        <h5 class="pull-left"> <?php echo $this->lang->line('outcome_element_plu_full'); ?> </h5>
                                                        <a id="edit_field" class="btn btn-primary pull-right"><i class="icon-plus-sign icon-white"></i> Add More <?php echo $this->lang->line('outcome_element_plu_full'); ?> </a>
                                                    </div>
                                                </div>
                                                <!-- display message when performance indicator(s) are saved -->
                                                <center><h4><b><?php echo $pi_confirmation_msg; ?></b><h4></center>
                                                            </div>
                                                            </div>

                                                            <?php
                                                            $cloneCntr = 1;
                                                            $size = sizeof($pi_data);

                                                            if ($size != 0) {
                                                                foreach ($pi_data as $item) {
                                                                    ?>
                                                                    <!-- For editing (existing) Performance Indicators or adding more Performance Indicators -->
                                                                    <form class="form-horizontal" id="pi_edit" method="POST" action="<?php echo base_url('curriculum/pi_and_measures/insert_pi'); ?>">
                                                                        <!-- Form Name -->
                                                                        <?php
                                                                        $pi_statement['value'] = $item['pi_statement'];
                                                                        $pi_id['value'] = $item['pi_id'];
                                                                        $pi_statement['id'] = "pi_statement" . $cloneCntr;
                                                                        $pi_statement['name'] = "pi_statement" . $cloneCntr;
                                                                        $pi_statement['abbr'] = $cloneCntr;
                                                                        ?>
                                                                        <input type="hidden" id="static_po_id" name="static_po_id" value="<?php echo $po_data[0]['po_id']; ?>">

                                                                        <div id="add_pi">
                                                                            <div id="remove" >
                                                                                <div id="pi_statement_div" class="bs-docs-example span3" style="width:100%; height:auto;">
                                                                                    <p class="control-label" for="pi_statement"> <?php echo $this->lang->line('outcome_element_sing_full'); ?> Statement: <font color="red"> * </font></p>

                                                                                    <div class="controls clone" id="pi_clone">
                                                                                        <?php echo form_input($pi_statement); ?> &nbsp; &nbsp;

                                                                                        <input class="pi_class" type="hidden" value="<?php echo $item['pi_id']; ?>" name="pi_id[]">

                                                                                        <a id="remove_field<?php echo $cloneCntr; ?>" class="Delete cursor_pointer"><i class="icon-remove cursor_pointer"></i></a> &nbsp; &nbsp; &nbsp;

                                                                                        <a class="add_measures" href="#"> Add/Edit PIs </a> &nbsp;

                                                                                        <!--<a class="view_measures" href="#"> View PIs </a>
                                                                                        --></div>
                                                                                </div>
                                                                            </div>	
                                                                        </div>
                                                                        <?php $cloneCntr++; ?>
                                                                    <?php } ?>
                                                                    <div id="add_before">
                                                                    </div>

                                                                    <div class="row pull-right">
                                                                        <button type="submit" id="save_pi" name="save_pi" class="btn btn-primary save_performance_indicators sticky_button"><i class="icon-file icon-white"></i> Save <?php echo $this->lang->line('outcome_element_sing_short'); ?> </button>

                                                                        <a href= "<?php echo base_url('curriculum/pi_and_measures'); ?>" class="btn btn-danger sticky_button"><i class="icon-remove icon-white"></i> Close</a>
                                                                    </div>
                                                                    <input type="hidden" name="add_edit_more_pi_counter" id="add_edit_more_pi_counter" value=""/>
                                                                </form>
                                                            <?php } else { ?>
                                                                <!-- For adding new Performance Indicators -->
                                                                <form class="form-horizontal" id="pi_edit" method="POST" action="<?php echo base_url('curriculum/pi_and_measures/insert_pi'); ?>">
                                                                    <!-- Form Name -->
                                                                    <input type="hidden" id="static_po_id" name="static_po_id" value="<?php echo $po_data[0]['po_id']; ?>">

                                                                    <div id="add_pi">
                                                                        <div id="remove" >
                                                                            <div id="pi_statement_div" class="bs-docs-example span3" style="width:1060px; height:120px;">
                                                                                <p class="control-label" for="pi_statement"> <?php echo $this->lang->line('outcome_element_sing_full'); ?> Statement: <font color="red"> * </font></p>

                                                                                <div class="controls clone" id="pi_clone">	
                                                                                    <?php echo form_input($pi_statement); ?> &nbsp; &nbsp;

                                                                                    <a id="remove_field<?php echo $cloneCntr; ?>" name="remove_field<?php echo $cloneCntr; ?>" class="confirm_delete Delete cursor_pointer"><i class="icon-remove cursor_pointer"></i></a> &nbsp; &nbsp;

                                                                                    <a class="add_measures" href="#"> Add/Edit PIs </a> &nbsp;

                                                                                    <!--<a class="view_measures" href="#"> View PIs </a>
                                                                                    --></div>

                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div id="add_before">
                                                                    </div>

                                                                    <br><br>
                                                                    <div class="row pull-right">
                                                                        <br/><button type="submit" id="save_pi" name="save_pi" class="btn btn-primary save_performance_indicators" style="margin-right: 2px;"><i class="icon-file icon-white"></i> Save <?php echo $this->lang->line('outcome_element_sing_short'); ?> </button>

                                                                        <a href= "<?php echo base_url('curriculum/pi_and_measures'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Close</a>
                                                                    </div>
                                                                    <input type="hidden" name="add_more_pi_counter" id="add_more_pi_counter" value=""/>
                                                                </form>
                                                            <?php } ?>


                                                            <!-- Modal to display PIs (Add / Edit) -->
                                                            <div id="myModal_add_measures" class="modal modal-measures hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_add_measures" data-backdrop="static" data-keyboard="false">
                                                                <div class="modal-header">
                                                                    <div class="navbar">
                                                                        <div class="navbar-inner-custom">
                                                                            Manage Performance Indicators (PIs)
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="modal-body" id="add_msr_modal">

                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button class="btn btn-danger close1" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                                                                </div>
                                                            </div>

                                                            <!-- Modal to display warning message -->
                                                            <div id="myModal_measures_warning" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_measures_warning" data-backdrop="static" data-keyboard="true">
                                                                <div class="modal-header">
                                                                    <div class="navbar">
                                                                        <div class="navbar-inner-custom">
                                                                            Warning
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <p> All <?php echo $this->lang->line('outcome_element_plu_full'); ?> needs to be saved before proceeding to Create or to View Performance Indicators. </p>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                                                                </div>
                                                            </div>

                                                            <!-- Modal to display warning message to avoid deleting first textarea -->
                                                            <div id="myModal_parent_textbox" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_parent_textbox" data-backdrop="static" data-keyboard="true">
                                                                <div class="modal-header">
                                                                    <div class="navbar">
                                                                        <div class="navbar-inner-custom">
                                                                            Warning
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="modal-body" id="oe_pi_msg">
                                                                    <p> There should be atleast one <?php echo $this->lang->line('measures_full'); ?> for each <?php echo $this->lang->line('outcome_element_sing_full'); ?>. </p>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                                                                </div>
                                                            </div>

                                                            <!-- Modal to display warning message to avoid deleting first textarea -->
                                                            <div id="OE_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_parent_textbox" data-backdrop="static" data-keyboard="true">
                                                                <div class="modal-header">
                                                                    <div class="navbar">
                                                                        <div class="navbar-inner-custom">
                                                                            Warning
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="modal-body" id="oe_pi_msg">
                                                                    <p> There should be atleast one <?php echo $this->lang->line('outcome_element_sing_full'); ?> for each PO. </p>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                                                                </div>
                                                            </div>

                                                            <!-- Modal to display existing and newly added measures -->
                                                            <div id="myModal_view_measures" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_view_measures" data-backdrop="static" data-keyboard="true"></br>
                                                                <div class="container-fluid">
                                                                    <div class="navbar">
                                                                        <div class="navbar-inner-custom">
                                                                            Added Performance Indicator(s)

                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="modal-body" id="added_msr_view_modal">

                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button class="btn btn-danger close1" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                                                                </div>
                                                            </div>
                                                            </div>
                                                            </div>
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
                                                            <script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/pi_and_measures.js'); ?>" type="text/javascript"></script>
