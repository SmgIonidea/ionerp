<?php
/**
 * Description          :	View for NBA SAR Report - Section 2.2.2 (TIER 1) -  Question Papers table.
 * Created              :	8-11-2016
 * Author               :       Jyoti 
 * Modification History :
 * Date	                        Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.nailthumb.1.1.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.nailthumb.1.1.min.css'); ?>" media="screen" />
<div class="container-fluid">
        <div class="row-fluid">
                <div class="span12" id="nba_qp_div">
                        <!-- Contents-->
                        <section id="contents">
                                <?php if ($meta_data) {
                                        extract($meta_data); ?>
                                        <table border=0 style="width:100%;">
                                                <tr>									
                                                        <td style="text-align:center" colspan="3"><b>Question Paper Title:</b>
                                                                <?php echo $meta_data[0]['qpd_title']; ?>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td style="text-align:left"><b>Total Duration (H:M):</b><?php echo $meta_data[0]['qpd_timing']; ?></td>
                                                        <td style="text-align:center"><b>Course  :</b><?php echo $meta_data[0]['crs_title'] ?> (<?php echo $meta_data[0]['crs_code'] ?>)</td>	
                                                        <td style="text-align:right"><b>Maximum Marks :</b><?php echo $meta_data[0]['qpd_max_marks']; ?></td>
                                                </tr>
                                                <tr>	
                                                        <td style="text-align:center" colspan="3"><b>Note :</b><?php echo $meta_data[0]['qpd_notes']; ?></td>
                                                </tr>
                                        </table>

                                        <table border=0 class="table table-bordered dataTable" id="qp_table_data">
                                                <thead>
                                                        <tr>
                                                                <th>Unit Name</th>
                                                                <th><center><b>Question No.</b></center> </th>
                                                <th><center><b>Question</b></center> </th>
                                                <?php foreach ($entity_list as $qp_config) {
                                                        if ($qp_config['entity_id'] == 11) { ?>
                                                                <th><center><b><?php echo $this->lang->line('entity_clo'); ?> </b></center></th>
                                                        <?php } ?>
                                                        <?php if ($qp_config['entity_id'] == 6) { ?>
                                                                <th><center><b><?php echo $this->lang->line('so'); ?></b></center></th>
                                                        <?php } ?>
                                                        <?php if ($qp_config['entity_id'] == 10) { ?>
                                                                <th><center><b><?php echo $this->lang->line('entity_topic'); ?></b></center></th>
                                                        <?php } ?>
                                                        <?php if ($qp_config['entity_id'] == 23) { ?>
                                                                <th><center><b>Level</b></center> </th>
                                                        <?php } ?>
                                                        <?php if ($qp_config['entity_id'] == 22) { ?>
                                                                <th><center><b>PI Code</b></center></th>
                                                        <?php }
                                                } ?>
                                                <th><center><b>Marks</b></center></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                        <?php echo $qp_table; ?>
                                                </tbody>
                                        </table>
                                        <?php if ($meta_data[0]['qpd_gt_marks'] != '') { ?>
                                                <div class="span12">
                                                        <div class="control-group">
                                                                <div class=" controls">
                                                                        <div class="form-inline pull-right">
                                                                                <div id="total"><label for="qp_max_marks"><b>Grand Total Marks</b></label>
                                                                                        <?php echo $meta_data[0]['qpd_gt_marks']; ?></div>
                                                                        </div>		
                                                                </div>		
                                                        </div>		
                                                </div>
                                        <?php } ?>
                                <?php } else { ?>
                                        <div class="bs-docs-example custom_question_paper_undefined_disp"> 
                                                <h3 class="custom_question_paper_undefined"><center>Question Paper has not been defined for this course.</center></h3>
                                        </div>
                                <?php } ?>
                        </section>
                </div>
        </div>  
</div>
<!---place js.php here -->
<script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/SimpleAjaxUploader.min.js'); ?> "> </script>
<script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/jquery.nailthumb.1.1.js'); ?> "> </script>
<!-- End of file t1ug_c2_2_2_qp_display_vw.php 
        Location: .nba_sar/ug/tier1/criterion_2/t1ug_c2_2_2_qp_display_vw.php -->