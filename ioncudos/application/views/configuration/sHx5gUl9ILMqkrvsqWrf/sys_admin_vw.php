<?php
/**
 * Description	:	

 * Created		:	June 12th, 2014

 * Author		:	Arihant Prasad D

 * Modification History:
 * 	Date                Modified By                			Description
  --------------------------------------------------------------------------------------------- */
?>

<!--head here -->
<?php $this->load->view('includes/head'); ?>
    <!--branding here-->
	<?php $this->load->view('includes/branding'); ?>
        <!-- Navbar here -->
        <?php $this->load->view('includes/navbar'); ?>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12">
                    <!-- Contents -->
                    <section id="contents">
						<div class="bs-docs-example">
							<!--content goes here-->
							<div class="row-fluid">
								<h5><center align="left"> System Admin Configuration <b id="sys_header"> </b></center></h5>
								
								<form class="form-horizontal" method="POST" id="sys_unit" name="sys_unit" action="<?php echo base_url('configuration/sys_admin/generate_store'); ?>" >
									<table class="table table-bordered" style="width:75%">
										<tr>
											<td>
												<h5><center> Mac Address: </center></h5>
											</td>
											<td>
												<?php
													$mac = '';
													$iface = '';
												
													if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
														// Turn on output buffering
														ob_start();
														
														//Execute external program to display output
														system('ipconfig /all');
														
														// Capture the output into a variable
														$mycom = ob_get_contents();
														
														// Clean (erase) the output buffer
														ob_clean();

														$findme = "Physical";
														// Find the position of Physical text
														$pmac = strpos($mycom, $findme);
														
														// Get Physical Address
														$mac = substr($mycom,($pmac+36),17); ?>

														<input type="text" class="required span7" id="sys_mac" name="sys_mac" value="<?php echo $mac; ?>">
													<?php } else {
														//getMacLinux
														exec('netstat -ie', $result);
														
														if(is_array($result)) {
															$iface = array();
															
															foreach($result as $key => $line) {
																if($key > 0) {
																	$tmp = str_replace(" ", "", substr($line, 0, 10));
																	if($tmp <> "") {
																		$macpos = strpos($line, "HWaddr");
																		if($macpos !== false) {
																			$iface[] = array('iface' => $tmp, 'mac' => strtolower(substr($line, $macpos+7, 17)));
																		}
																	}
																}
															}
															
															$iface = $iface[0]['mac']; ?>
															
															<input type="text" class="required span7" id="sys_iface" name="sys_iface" value="<?php echo $iface; ?>">
														<?php } else { ?>
															<input type="text" class="required span7" id="sys_nf" name="sys_nf" value="<?php echo "Not Found"; ?>">
														<?php }
													}
												?>
											</td>
										</tr>

										<tr>
											<td>
												<h5><center> License Key: </center></h5>
											</td>
											<td>
												<input type="password" class="required span7" id="sys_lic" name="sys_lic" value="<?php echo '0gfv-q1d3-sg5y-b378'; ?>">
											</td>
										</tr>
										<tr>
											<td>
												<h5><center> No. of Programs: </center></h5>
											</td>
											<td>
												<input type="text" class="required span7" id="sys_progs" name="sys_progs" placeholder="Enter no. of programs...">
											</td>
										</tr>
										<tr>
											<td>
												<h5><center> Trial Period: </center></h5>
											</td>
											<td>
												<input type="text" class="required span7" id="sys_tp" name="sys_tp" value="<?php echo date("Y-m-d"); ?>">
											</td>
										</tr>
									</table><br>
									
									<div class="pull-right">
									 <button type="submit" id="sys_save" class="btn btn-primary"><i class="icon-file icon-white"></i> Save </button>
									</div>
								</form>
							</div>
							<!--content ends here-->
                        </div><br><br><br><br><br><br>
                    </section>
                    <!--Do not place contents below this line-->	
                </div>
            </div>
        </div>
	<!---place footer.php here -->
	<?php $this->load->view('includes/footer'); ?> 

	<!---place js.php here -->
	<?php $this->load->view('includes/js'); ?>
	