<?php
/**
 * Description	:	Generates Internal & Final Exam Report

 * Created		:	December 15th, 2015

 * Author		:	Arihant Prasad D

 * Modification History:
 *   Date                Modified By                         Description
 * 
  ------------------------------------------------------------------------------------------ */
?>

<?php // Title of the Page ?>

<div id="question_paper_report" >
    <?php $m = 0; ?>
    <?php if (!empty($qp_questions_marks)) { ?>
        <table class="table table-bordered" style="width:100%;">
            <tbody>
                <tr>
                    <th style="text-align:center" colspan="3" gridSpan="4" width=800>
                        <h4 class="ul_class">Question Paper Title: <?php echo $qp_basic_details[$m]['qpd_title']; ?></h4>
                    </th>
                </tr>
                <tr>
                    <th width=300 style="text-align:center;">
                        <h4 class="ul_class">Total Duration (H:M):<?php echo $qp_basic_details[$m]['qpd_timing']; ?></h4>
                    </th>
                    <th width=500 style="text-align:center;">
                        <h4 class="ul_class">Course : <?php echo $qp_basic_details[$m]['crs_title'] ?> (<?php echo $qp_basic_details[$m]['crs_code'] ?>)</h4>
                    </th>	
                    <th style="text-align:center;" width=300>
                        <h4 class="ul_class">Maximum Marks :<?php echo $qp_basic_details[$m]['qpd_max_marks']; ?></h4>
                    </th>
                </tr>
                <tr>	
                    <td width=800 gridSpan="4" style="text-align:center" colspan="3"><h4 class="ul_class">Note :<?php echo $qp_basic_details[$m]['qpd_notes']; ?></h4></td>
                </tr>
            </tbody>
        </table>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td width="170" style="white-space:nowrap; text-align:center;"><h4 style="text-align:center; margin:0px;">Q.No.</h4></td>
                    <td width="870" style="width:95%;"><h4 style="text-align:center; margin:0px;">Questions</h4></td>
                    <td width="120"><h4 style="text-align:center; margin:0px;">Marks</h4></td>
                </tr>

                <?php
                $sl_no = 1;
                $temp = '';
                foreach ($qp_questions_marks as $qp_qm) {
                    if ($qp_qm['qpd_unitd_id'] != $temp) {
                        ?>
                        <tr>
                            <td width="800" gridspan="4" colspan="12" style="width: 40px; background-color: rgb(199, 197, 197);">
                                <h4 style="text-align:center; margin:0px;"><?php echo $qp_qm['qp_unit_code']; ?></h4>
                            </td>
                        </tr>
                        <?php
                        $temp = $qp_qm['qpd_unitd_id'];
                        $m++;
                    }
                    ?>
                    <tr>
                        <td width="170" style="text-align:center;"><?php echo "<p class='font_h h_class ul_class'>" . $qp_qm['qp_subq_code'] . "</p>"; ?></td>
                        <td width="870" style="width:95%">
                            <h4 class="font_h h_class ul_class row_color" style="text-align: both; font-weight:normal;"><?php echo trim($qp_qm['qp_content']); ?></h4>
                            <?php
                            $img_temp = '';
                            if ($img_temp != $qp_qm['qp_mq_code']) {
                                $img_temp = $qp_qm['qp_mq_code'];
                                if (isset($qp_qm['image_name'])) {
                                    $image_names = explode(",", $qp_qm['image_name']);
                                    $img_size = sizeof($image_names);

                                    for ($img = 0; $img < $img_size; $img++) {
                                        $img_counter = $img;
                                        $img_counter++;
                                        $thumb_div = '<div class="controls span1" id="img_thmb_' . $mq_counter . '_' . $sub_counter . '_' . $img_counter . '">';
                                        $thumb_div .= '<table class=""><tr><td>';
                                        ?>

                                        <?php
                                        if (@getimagesize(base_url() . '/uploads/' . $image_names[$img])) {
                                            list($width, $height, $type, $attr) = getimagesize(base_url() . '/uploads/' . $image_names[$img]);

                                            $img_width = '350';
                                            $img_height = '300';

                                            if ($width > $img_width) {
                                                $width = $img_width;
                                            } else {
                                                
                                            }

                                            if ($height > $img_height) {
                                                $height = $img_height;
                                            } else {
                                                
                                            }
                                            ?>

                                            <img src=<?php echo base_url() . '/uploads/' . $image_names[$img]; ?> class="img-rounded" style="width:<?php echo $width; ?>px; height:<?php echo $height; ?>px;" />
                                        <?php } else { ?>
                                            <div style="text-align:center;color:#b94a48;">(Image not found)</div>
                                            <?php
                                        }
                                        $thumb_div .= '</td></tr></table></div><div class="img_margin"></div><span style="display: block;"></span>';
                                        echo $thumb_div;
                                    }
                                }
                            } else {
                                if (isset($qp_qm['image_name'])) {
                                    $image_names = explode(",", $qp_qm['image_name']);
                                    $img_size = sizeof($image_names);

                                    for ($img = 0; $img < $img_size; $img++) {
                                        $img_counter = $img;
                                        $img_counter++;
                                        $thumb_div = '<div class="controls span1" id="img_thmb_' . $mq_counter . '_' . $sub_counter . '_' . $img_counter . '">';
                                        $thumb_div .= '<table class=""><tr><td><img src="' . base_url() . '/uploads/' . $image_names[$img] . '" class="img-rounded img-thumbnail" />';
                                        $thumb_div .= '</td></tr></table></div><div class="img_margin"></div><span style="display: block;"></span>';
                                        echo $thumb_div;
                                    }
                                }
                            }
                            ?>
                        </td>
                        <td width="120" style="text-align:center; margin:0px;"lass="font_h h_class ul_class">
                            <?php
                            echo "<p class='font_h h_class ul_class'>" . $qp_qm['qp_subq_marks'] . "</p>";
                            if (isset($mq_data['image_name'])) {
                                $thumb_div = '<div class="controls span1" id="img_thmb_' . $mq_counter . '_' . $sub_counter . '_' . $img_counter . '">';
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    </div>
<?php } ?>