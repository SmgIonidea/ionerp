<?php
/**
 * Description		:   Table to display Placement intake details.
 * Created		:   16-06-2016
 * Author		:   Shayista Mulla		  
 * Modification History :
 *    Date                  Modified By                			Description
 * 03-06-2017               Shayista Mulla                  Code clean up,indendation and issues fixed and added comments
  ---------------------------------------------------------------------------------------------- */
?>

<?php
$count = round(count($student_list) / 3);
$count1 = $count * 2;
if ($select_list == 1) {
    ?>   
    <b> <?php echo $this->lang->line('industry_sing'); ?>  : </b> <?php echo $company_name[0]['company_name'] ?>
    <br/>
    <b> Curricula : </b><?php
    echo $curriculum_name[0]['crclm_name'];
} else if ($select_list == 2) {
    ?>
    <b> University / College  : </b> <?php echo $company_name[0]['company_name'] ?>
    <br/>
    <b> Curricula : </b><?php
    echo $curriculum_name[0]['crclm_name'];
} else {
    echo '<b>Curriculum : </b>' . $curriculum_name[0]['crclm_name'];
}
?>
<br/><hr/>
<div class="row-fluid compa_display" style="width:100%; overflow:auto;">       
    <?php
    $sl_no = 1;
    foreach ($student_list as $student) {
        if ($sl_no == 1 OR $sl_no == $count + 1 OR $sl_no == $count1 + 1) {
            echo '<div class="span4">
                    <table id="example_display" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <th  style="width:15%;">Sl No.</th>
                        <th  style="width:40%;"> USN </th>
                        <th> Select </th>
                        </thead>
                        <tbody>';
        }
        if ($student['student_id'] == $student['stud_intake_id']) {
            if ($select_list == 1 OR $select_list == 2) {
                if ($plmt_id != $student['plmt_id']) {
                    $disabled = "disabled";
                } else {
                    $disabled = "";
                }

                $data_val = "checked";
            } else {
                if ($plmt_id != $student['e_id']) {
                    $disabled = "disabled";
                } else {
                    $disabled = "";
                }

                $data_val = "checked";
            }
        } else {
            $data_val = "";
            $disabled = "";
        }

        if ($student['interview_date'] != '') {
            $interview_date = explode("-", $student['interview_date']);
            $interview_date = $interview_date[2] . '-' . $interview_date[1] . '-' . $interview_date[0];
        } else {
            $interview_date = '';
        }

        $start_date = '';
        if ($student['start_date'] != '') {
            $start_date = explode("-", $student['start_date']);
            $start_date = $start_date[2] . '-' . $start_date[1] . '-' . $start_date[0];
        } else {
            $start_date = '';
        }

        if ($student['flag'] == 0 AND $student['flag'] != NULL) {
            $title = "Company / Industry Name : " . $student['company_name'] . "\r\nInterview Date : " . $interview_date . "\r\nPosition being offered : " . $student['role_offered'] . "\r\nLocation of posting : " . $student['place_posting'];
        } else if ($student['flag'] == 1) {
            $title = "University / College Name : " . $student['univ_colg_name'] . "\r\nInterview Date : " . $interview_date . "\r\nPosition being offered : " . $student['role_offered'] . "\r\nLocation of posting : " . $student['place_posting'];
        } else if ($student['flag'] == 2) {
            $title = "Name : " . $student['name'] . "\r\nStarted Date : " . $start_date . "\r\nSector: " . $student['sector'] . "\r\nLocation : " . $student['location'];
        } else {
            $title = '';
        }
        ?>
        <tr aria-live="polite" aria-relevant="all">
            <td class=""> <?php echo $sl_no++; ?> </td>
            <td class="" title="<?php echo "Curriculum : " . $student['crclm_name'] . "\r\nStudent Name : " . $student['title'] . " " . $student['first_name'] . " " . $student['last_name']; ?>"><?php echo $student['student_usn']; ?></td>
            <td style="width:50px;"> <input title=" <?php echo $title; ?>" <?php echo $data_val; ?>  <?php echo $disabled; ?> class="student_check" type="checkbox"  id="student_check" name="student_check[]" value="<?php echo $student['student_id']; ?>"/></td>            
        </tr>

        <?php
        if ($sl_no == 1 OR $sl_no == $count + 1 OR $sl_no == $count1 + 1) {
            echo'</tbody> 
            </table>
        </div>';
        }
    }
    ?>         
</div>
<!-- End of file placement_intake_table_vw.php 
                        Location: .nba_sar/modules/placement/placement_intake_table_vw.php  -->