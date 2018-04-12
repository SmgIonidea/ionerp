<?php
/**
 * Description          :	Generate Student Graduate Details table in Crriculum Student performance view page.

 * Created		:	20-05-2016

 * Author		:       Shayista Mulla

 * Modification History:
 *   Date                Modified By                         Description

  ------------------------------------------------------------------------------------------ */
?>
<div>
    <table class="table table-bordered" style="width:100%;">
        <tbody>
            <tr>
                <td>
                    <h4 class="text-center"> Year</h4>
                </td>
                <td>
                    <h4 class="text-center">Number of students </h4>
                </td>
                <td>
                    <h4 class="text-center">
                        <?php
                        if ($pgm_type_id == 44) {
                            echo 'Passed without backlog';
                        } else {
                            echo 'Graduated without backlog';
                        }
                        ?>
                    </h4>  
                </td>
                <td>
                    <h4 class="text-center">Mean of graduate percentage average </h4>
                </td>
                <td>
                    <h4 class="text-center">Total No. of successful passed students </h4>
                </td>
            </tr>
            <?php
            if ($stud_graduate) {
                $i = 1;
                $term = ($term[0]["total_terms"]) / 2;

                foreach ($stud_graduate as $list_data) {
                    ?>
                    <tr>
                        <td>
        <?php echo "<p class='text-center'>" . $list_data['grad_year'] . "</p>"; ?>
                            <input type="hidden" name="term_count" id="term_count" value="<?php echo $term ?>">
                        </td>
                        <td>
                            <input type="text" id="num_stud<?php echo $i ?>" name="num_stud<?php echo $i ?>" class="numbers text-right" value="<?php echo $list_data['num_stud'] ?>">
                        </td>
                        <td>
                            <input type="text" id="without_backlog<?php echo $i ?>" name="without_backlog<?php echo $i ?>" class="numbers text-right" value="<?php echo $list_data['without_backlog'] ?>">
                        </td>
                        <td>
                            <input type="text" id="mean_grade<?php echo $i ?>" name="mean_grade<?php echo $i ?>" class="number onlyNumber text-right" value="<?php echo $list_data['mean_grade'] ?>">
                        </td>
                        <td>
                            <input type="text" id="successful_stud<?php echo $i ?>" name="successful_stud<?php echo $i ?>" class="numbers text-right" value="<?php echo $list_data['successful_stud'] ?>">
                        </td>
                    </tr>
                    <?php
                    $i++;
                }
            } else {
                $term = ($term[0]["total_terms"]) / 2;

                for ($i = 1; $i <= $term; $i++) {
                    ?>
                    <tr>
                        <td>
        <?php echo "<p class='text-center'>$i</p>"; ?>
                            <input type="hidden" name="term_count" id="term_count" value="<?php echo $term ?>">
                        </td>
                        <td>
                            <input type="text" id="num_stud<?php echo $i ?>" name="num_stud<?php echo $i ?>" class="numbers text-right">
                        </td>
                        <td>
                            <input type="text" id="without_backlog<?php echo $i ?>" name="without_backlog<?php echo $i ?>" class="numbers text-right">
                        </td>
                        <td>
                            <input type="text" id="mean_grade<?php echo $i ?>" name="mean_grade<?php echo $i ?>" class="number onlyNumber text-right">
                        </td>
                        <td>
                            <input type="text" id="successful_stud<?php echo $i ?>" name="successful_stud<?php echo $i ?>" class="numbers text-right">
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>
<!-- End of file student_graduate_table_vw.php 
                        Location: .report/curriculum_student_info/student_graduate_table_vw.php  -->