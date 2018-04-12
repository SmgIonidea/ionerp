<?php
/**
 * Description	:	Table Grid view for Course Stream Report Module. 
 * Created on	:	03-05-2013
 * Modification History:
 * Date                Modified By           Description
 * 12-09-2013		Abhinay B.Angadi        Added file headers, indentations & Code cleaning.
  -----------------------------------------------------------------------------------------------
 */
?>
<?php ?>
<table id="crs_stream_report_table_id" name="crs_stream_report_table_id" class="table table-bordered" style="width:100%">
    <thead>
    <th class="sorting1" rowspan="1" colspan="2" style="white-space:nowrap; width: 10px; color: blue" date-key="lg_crs_strm"> Course Stream (Domain) </th>
    <?php foreach ($term_list as $term): ?>				
        <th class="sorting1" style="white-space:nowrap; width: 10px;" id="<?php echo $term['crclm_term_id']; ?>"> <font color="#8E2727"><?php echo $term['term_name']; ?></font>
        </th>
    <?php endforeach; ?>
</thead>
<tbody>
    <?php foreach ($crs_domain_list as $domain): ?>
        <tr>
            <td colspan="2" style="width: 10px;"><b>
                    <p> <b> <font color="blue"><?php echo $domain['crs_domain_name'] ?></font> </b> </p></b> 
            </td>
            <?php
            foreach ($term_list as $term1):
                $i = 1;
                ?>
                <td class="<?php echo $term['crclm_term_id']; ?>" style="text-align:left; vertical-align:middle position relative;">
                    <?php
                    $count = 1;
                    foreach ($grid_details as $crs): {
                            if ($term1['crclm_term_id'] === $crs['crclm_term_id'] && $domain['crs_domain_name'] === $crs['crs_domain_name']) {
                                if ($i % 2 == 0) {
                                    ?>
                                    <font color = "gray"><?php
                                    echo $crs['crs_title'] . "</br>";
                                } else {
                                    ?>
                                    <font color = "green"><?php
                                    echo $crs['crs_title'] . "</br>";
                                }
                                //echo $i."." ." ". $crs['crs_title'];  //It displays serial number along with the course title.
                                echo nl2br("\n");
                                $i++;
                            }
                            $count++;
                        }
                    endforeach;
                    ?> 
                    <br>
                </td>
        <?php endforeach; ?>
        </tr>
<?php endforeach; ?>
</tbody>
</table>

<?php $this->load->view('includes/js'); ?>
