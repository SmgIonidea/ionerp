<?php
/**
 * Description          :	Add/Edit/Delete Approved Program Outcome grid along with its corresponding Performance Indicators and Measures

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 * 11-01-2014		   Arihant Prasad			File header, function headers, indentation 
 * 7/7/2017			   Bhagyalaxmi S S			
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
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
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



                                    <p class="control-label" style="float:left;" for="po_statement_1"> <?php echo $this->lang->line('student_outcome_full'); ?> (<?php echo $this->lang->line('so'); ?>) Statement: </p>
                                    <p class="control-label" style="float:right;  width:50%;" for="po_statement_1"> BoS Comments: </p>
                                    <?php
                                    if ($po_data['0']['pso_flag'] == 0) {
                                        ?>
                                        <!-- display po statement -->
                                        <textarea class="required input-xxlarge readonly valid" col="40" row="2" readonly="readonly" name="po_statement[]" id="po_statement_1" type="text" style="float:left; margin: 0px; width: 49%; height: 60px;"><?php echo $po_data['0']['po_reference'] . '. ' . $po_data['0']['po_statement']; ?></textarea>
                                    <?php } else {
                                        ?>
                                        <textarea class="required input-xxlarge readonly valid" col="40" style="color:blue;" row="2" readonly="readonly" name="po_statement[]" id="po_statement_1" type="text" style="float:left; margin: 0px; width: 49%; height: 60px;"><?php echo $po_data['0']['po_reference'] . '. ' . $po_data['0']['po_statement']; ?></textarea>
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

                                    <br><br><br><br><br>

                                    <div id="" class="add_attr_style"> <br/>						
                                        <table class="table table-bordered table-hover" id="example1" style="font-size:12px;" >
                                            <thead>
                                                <tr>
                                                    <th style="width:25px;">Sl.No</th><th>Competencies</th><th style="width:125px;">Add/Edit PI</th>
                                                    <th style="width:35px;">Edit</th><th style="width:45px;">Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="width:25px;">No Data Available</td><td></td><td></td><td style="width:25px;"></td><td style="width:25px;"></td>
                                                </tr>
                                            </tbody>
                                        </table> 
                                    </div><br/><br/><br/><br/>
                                    <div>
                                        <b><font color="blue "><p class="control-label" for="Offerprogram">Add / Edit Competencies Statement: </p></font></b>													
                                        <form id="measures_edit_data" method="POST" action="">															
                                            <input type="hidden" id="static_po_id" name="static_po_id" value="<?php echo $po_data[0]['po_id']; ?>">
                                            <input type="hidden" id="static_pi_id" name="static_pi_id" value="">
                                            <table style="bordered">
                                                <tr>	
                                                    <td><b><font size="2px;">Competency Statement :  </font> </b>	</td>
                                                    <td><textarea placeholder="Enter Competency Statement" style="width:600px" rows="3" cols="50" type="text" id="pi_statement" name="pi_statement" class="required"></textarea><br/><span id="error_pi_statement"></span></td>
                                                    <td> &nbsp;&nbsp;&nbsp;&nbsp;<button type="button" name="save_competencies" id="save_competencies" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Save</button> 
                                                        <button type="button" name="update_competencies" id="update_competencies" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Update</button> &nbsp;
                                                        <button type="reset" name="reset_competencies" id="reset_competencies" class="clear_all btn btn-info"><i class="icon-refresh icon-white"></i><span></span> Reset</button> &nbsp;
                                                        <a class="brand-custom" href= "<?php echo base_url('curriculum/pi_and_measures'); ?>" id="cancel_button"><span class="btn btn-danger pull-right"><i class="icon-remove-sign icon-white"></i><span></span> Close</span></a></td>
                                                </tr>
                                            </table>
                                        </form>

                                    </div>

                                </div>
                            </div>								
                    </section>

                    <input type = "hidden" id="measure_id" name="measure_id"	/>		
                    <input type = "hidden" id="measure_edit_id" name="measure_edit_id"	/>					
                    <div id="confirmation_delete" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Delete Confirmation
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">									
                            <p>          Are you sure you want to Delete?</p>
                        </div>

                        <div class="modal-footer">
                                <!--<a class="btn btn-primary" onClick="javascript:publish();"><i class="icon-ok icon-white"></i> Ok </a> -->
                            <a class="btn btn-primary" data-dismiss="modal" id="delete_competencies"><i class="icon-ok icon-white"></i> Ok </a> 
                            <button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
                        </div>
                    </div>		

                    <div id="add_edit_pi_modal" class="modal hide fade"  style="width:1000px;left:500px;height:500px;" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="" data-backdrop="static" data-keyboard="false"></br>
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Manage Performance Indicators (PIs)
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<textarea readonly id="pi_static_statement" width="250px" style="width:650px;" ></textarea>
                        </div>			

                        <div id="" class="" style=" padding: 30px; ">			

                            <table class="table table-bordered table-hover" id="example2" style="font-size:12px;" >
                                <thead>
                                    <tr>
                                        <th style="width:50px;">Sl.No</th><th>Performance Indicator </th><th style="width:40px;">Edit</th><th style="width:50px;">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>No Data Available</td><td></td><td></td><td></td>
                                    </tr>
                                </tbody>
                            </table> 
                        </div>			
                        <div>														
                            <form id="pi_edit_data" method="POST" action="">															
                                <input type="hidden" id="static_po_id" name="static_po_id" value="<?php echo $po_data[0]['po_id']; ?>">
                                <input type="hidden" id="static_pi_id" name="static_pi_id" value="">
                                <div  style="padding:12px;">
                                    <b><font color="blue "><p class="control-label" for="Offerprogram">Add / Edit Performance Indicators (PIs): </p></font></b>	
                                    <table style="margin-left: 2cm;">
                                        <tr>	
                                            <td><b><font size="2px;">Performance Indicator:  </font> </b>	</td>
                                            <td><textarea placeholder="Enter Performance Indicators (PIs)" style="width:650px" rows="2" cols="5" type="text" id="pi_stmt" name="pi_stmt" class="required"></textarea></td>
                                            <td><span id="error_pi_stmt"></span></td>							
                                        </tr>
                                    </table>
                                </div>
                            </form>				
                        </div>									 
                        <div class="modal-footer">
                                <!--<a class="btn btn-primary" onClick="javascript:publish();"><i class="icon-ok icon-white"></i> Ok </a> -->
                            <a class="btn btn-primary"  id="save_pi"><i class="icon-file icon-white"></i> Save </a>  
                            <a class="btn btn-primary"  id="update_pi"><i class="icon-file icon-white"></i> Update </a>
                            <button type="reset" name="reset_measures" id="reset_measures" class="clear_all btn btn-info"><i class="icon-refresh icon-white"></i><span></span> Reset</button>
                            <button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
                        </div>
                    </div>	
                    <div id="confirmation_delete_messures" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Delete Confirmation
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">									
                            <p>          Are you sure you want to Delete?</p>
                        </div>

                        <div class="modal-footer">
                                <!--<a class="btn btn-primary" onClick="javascript:publish();"><i class="icon-ok icon-white"></i> Ok </a> -->
                            <a class="btn btn-primary" data-dismiss="modal" id="delete_competencies_measures"><i class="icon-ok icon-white"></i> Ok </a> 
                            <button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
                        </div>
                    </div><br><br>
                </div>
                <!---place footer.php here -->
                <?php $this->load->view('includes/footer'); ?> 
                <!---place js.php here -->
                <?php $this->load->view('includes/js'); ?>
                <script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/pi_and_measures.js'); ?>" type="text/javascript"></script>
                <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>