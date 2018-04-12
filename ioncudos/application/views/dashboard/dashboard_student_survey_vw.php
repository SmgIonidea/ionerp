
<div class="accordion-inner">
    <table class="table table-bordered table-hover" id="table_student_survey">
        <thead>
            <tr>
                <th width="12%"> Curriculum </th>
                <th width="12%"> Course </th>
                <th width="50%"> Survey </th>
                <th width="8%"> Survey End Date </th>
                <th width="7%"> Action Due </th>
            </tr>
        </thead>
        <tbody>
            <?php //var_dump($student_surveys);
                if(!empty($student_surveys)) {
                foreach ($student_surveys as $student_survey) : ?>
                <tr>
                    <td><?php echo $student_survey['crclm_name']; ?></td>
                    <td><?php echo $student_survey['crs_title']. ' ('.$student_survey['crs_code'].')'; ?></td>
                    <td><?php echo $student_survey['name']. (isset($student_survey['subject']) ? ' - '.$student_survey['subject'] : ""); ?></td>
                    <td><?php echo $student_survey['end_date']; ?></td>
                    <td>
                        <?php if($student_survey['response_status'] != '2') { ?>
                        <p><a href="<?php echo base_url().$student_survey['link']; ?>">Take survey</a></p>
                        <?php } else { ?>
                            <p>Survey responded</p>
                        <?php } ?>
                    </td>
                </tr>        
            <?php endforeach; }
                else {
                    ?><tr><td colspan="5"><b>No data to display</b></td></tr><?php
                }
            ?>
        </tbody>
    </table>
</div>