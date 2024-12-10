<?php
			$CI =& get_instance();
			$CI->load->model('commission_pat_model', 'commission_pat');
			
			// Fetch the data
			$user_id = $CI->session->userdata('user_id');
			
			
			
			$result = $CI->commission_pat->get_commission_by_pat_id($user_id);
							$res=$result->comm_rate;

		// 					echo '<pre>';
    // print_r($data);
    // echo '</pre>';
    // die("fasdf");
?>	

<!-- Breadcrumb -->
			<div class="breadcrumb-bar">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-md-12 col-12">
							<nav aria-label="breadcrumb" class="page-breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url() ?>"><?php 
									 /** @var array $language */
									echo $language['lg_home']; ?></a></li>
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_accounts']; ?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_accounts']; ?></h2>
						</div>
					</div>
				</div>
			</div>
			<!-- /Breadcrumb -->
			
			<!-- Page Content -->
			<div class="content">
				<div class="container-fluid">
					
					<div class="row">
						<div class="col-md-5 col-lg-4 col-xl-3 theiaStickySidebar">
							
							<!-- Profile Sidebar -->
							<?php $this->load->view('web/includes/patient_sidebar');?>
							<!-- /Profile Sidebar -->
							
						</div>
						
						<div class="col-md-7 col-lg-8 col-xl-9">
						<div id="account">
					<div class="row">
						<div class="col-lg-5 d-flex">
							<div class="card flex-fill">
								<div class="card-header">
									<div class="row">
										<div class="col-sm-6">
											<h3 class="card-title"><?php echo $language['lg_account5']; ?></h3>
										</div>
										<div class="col-sm-6">
											<div class="text-right">
												<a title="<?php echo $language['lg_edit_profile'] ?>" class="btn btn-primary btn-sm" id="btn-add-edit-title" onclick="add_account_details()"><i class="fas fa-pencil"></i> <?php echo isset($account_details) && !empty($account_details) ? $language['lg_edit_details'] : $language['lg_add_account_details']; ?></a>
											</div>
										</div>
									</div>
								</div>
								<div class="card-body">
									<div class="profile-view-bottom">
										<div class="row">
											<div class="col-lg-6">
												<div class="info-list">
													<div class="title"><?php echo $language['lg_bank_name']; ?></div>
													<div class="text" id="bank_name"><?php echo isset($account_details->bank_name)?$account_details->bank_name:'';?></div>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="info-list">
													<div class="title"><?php echo $language['lg_branch_name']; ?></div>
													<div class="text" id="branch_name"><?php echo isset($account_details->branch_name)?$account_details->branch_name:'';?></div>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="info-list">
													<div class="title">Account Type</div>
													<div class="text" id="account_type"><?php echo isset($account_details->account_type)?$account_details->account_type:'';?></div>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="info-list">
													<div class="title">Account Currency</div>
													<div class="text" id="account_currency"><?php echo isset($account_details->account_currency)?$account_details->account_currency:'';?></div>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="info-list">
													<div class="title">Routing Number</div>
													<div class="text" id="routing_number"><?php echo isset($account_details->routing_number)?$account_details->routing_number:'';?></div>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="info-list">
													<div class="title">ACH Acc. number</div>
													<div class="text" id="ach_number"><?php echo isset($account_details->ach_number)?$account_details->ach_number:'';?></div>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="info-list">
													<div class="title">SWIFT</div>
													<div class="text" id="swift"><?php echo isset($account_details->swift)?$account_details->swift:'';?></div>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="info-list">
													<div class="title">Bank Address</div>
													<div class="text" id="bank_address"><?php echo isset($account_details->bank_address)?$account_details->bank_address:'';?></div>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="info-list">
													<div class="title">Bank Country</div>
													<div class="text" id="bank_country"><?php echo isset($account_details->bank_country)?$account_details->bank_country:'';?></div>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="info-list">
													<div class="title"><?php echo $language['lg_account_number']; ?></div>
													<div class="text" id="account_no"><?php echo isset($account_details->account_no)?$account_details->account_no:'';?></div>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="info-list">
													<div class="title"><?php echo $language['lg_account_holder_']; ?></div>
													<div class="text" id="account_name"><?php echo isset($account_details->account_name)?$account_details->account_name:'';?></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						
						<div class="col-lg-7 d-flex">
							<div class="card flex-fill">
								<div class="card-body">
									<div class="row">
										<div class="col-lg-6">
											<div class="account-card bg-success-light">
												<span><?php 
                                                   /** @var string $currency_symbol */
                                                     /** @var float $earned */
                                                     /** @var integer $requested */
                                                     /** @var integer $balance */

                                                  
												echo $currency_symbol; ?><?php echo number_format($earned,2);?></span> <?php echo $language['lg_received']; ?>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="account-card bg-warning-light">
												<span><?php echo $currency_symbol."". number_format($requested,2);?></span> <?php echo $language['lg_requested']; ?>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="account-card bg-purple-light">
												<span><?php echo $currency_symbol; ?><?php echo number_format($balance-($earned+$requested),2);?></span> <?php echo $language['lg_balance']; ?>
											</div>
										</div>
										<!-- <div class="col-lg-6">
											<div class="account-card bg-danger-light">
												<span><?php 
                                                /** @var int $balance  */
                                              

												echo $currency_symbol;?><?php echo number_format($balance-($earned+$requested),2);?></span> GTC: Wallet
											</div>

										</div> -->
										<div class="col-lg-6">
											<div class="account-card bg-success-light">
												<span><?php 
												echo $res."%";
												?></span> Facilitation
											</div>
										</div>
										
											<!-- <div class="account-card bg-success-light"> -->
												<?php if (isset($_GET['bill'])) {
												// Sanitize the input to avoid issues
												$bill = $_GET['bill'];
												$user_currency = get_user_currency();
      $user_currency_code = $user_currency['user_currency_code'];
      $user_currency_rate = $user_currency['user_currency_rate'];

      $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
      $rate_symbol = currency_code_sign($currency_option); ?>
												
											<!-- </div> -->
											<div class="col-md-12 text-center" style="padding-bottom:5px;" >
											<a href="javascript:void(0);" onclick="payment(2)" class="btn btn-primary request_btn"> Pay: <?php echo $rate_symbol . number_format($bill, 2, '.', ''); ?></a>
										</div>
										
										<?php }if((number_format($balance-($earned+$requested)))>0){ ?>
										<div class="col-md-12 text-center">
											<a href="javascript:void(0);" onclick="payment_request(2)" class="btn btn-primary request_btn"><?php echo $language['lg_refund_request']; ?></a>
										</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>



					<div class="row">
						<div class="">
							<div class="card flex-fill">
								<div class="card-header">
									<div class="row">
										<div class="col-sm-6">
											<h3 class="card-title">Insurance Details</h3>
										</div>
										<div class="col-sm-6">
											<div class="text-right">
												<a title="<?php echo $language['lg_edit_profile'] ?>" class="btn btn-primary btn-sm" id="btn-add-edit-title" onclick="add_insurance_details()"><i class="fas fa-pencil"></i> <?php echo isset($insurance_details) && !empty($insurance_details) ? "Edit Insurance Details" : "Add Insurance Details"  ?></a>
											</div>
										</div>
									</div>
								</div>
								<div class="card-body">
									<div class="profile-view-bottom">
										<div class="row">
											<div class="col-lg-6">
												<div class="info-list">
													<div class="title">Insurance Company Name</div>
													<div class="text" id="insurance_company"><?php echo isset($insurance_details->insurance_company)?$insurance_details->insurance_company:'';?></div>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="info-list">
													<div class="title">Insurance Card Number</div>
													<div class="text" id="insurance_card_number"><?php echo isset($insurance_details->insurance_card_number)?$insurance_details->insurance_card_number:'';?></div>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="info-list">
													<div class="title">Insurance Type</div>
													<div class="text" id="insurance_type"><?php echo isset($insurance_details->insurance_type)?$insurance_details->insurance_type:'';?></div>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="info-list">
													<div class="title">Insurance Expiration</div>
													<div class="text" id="insurance_expiration"><?php echo isset($insurance_details->insurance_expiration)?$insurance_details->insurance_expiration:'';?></div>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="info-list">
													<div class="title">Benefits</div>
													<div class="text" id="benefits"><?php echo isset($insurance_details->benefits)?$insurance_details->benefits:'';?></div>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="info-list">
													<div class="title">Phone Number</div>
													<div class="text" id="phone_number"><?php echo isset($insurance_details->phone_number)?$insurance_details->phone_number:'';?></div>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="info-list">
													<div class="title">Dependants</div>
													<div class="text" id="dependants"><?php echo isset($insurance_details->dependants)?$insurance_details->dependants:'';?></div>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="info-list">
													<div class="title">DOB</div>
													<div class="text" id="dob"><?php echo isset($insurance_details->dob)?$insurance_details->dob:'';?></div>
												</div>
											</div>
										
										</div>
									</div>
								</div>
							</div>
						</div>

										</div>
						
					
					
					<div class="user-tabs">
					<ul class="nav nav-tabs nav-tabs-bottom nav-justified flex-wrap">
						<li class="nav-item">
							<a class="nav-link active" onclick="paccount_table()" href="#account_tab" data-toggle="tab"><?php echo $language['lg_accounts']; ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" onclick="doctor_request()" href="#doctor_request_tab" data-toggle="tab"><span><?php echo $language['lg_doctor_request']; ?></span></a>
						</li>
					</ul>
				   </div>
				   <div class="tab-content">
					<div id="account_tab" class="tab-pane fade show active">
						<div class="card card-table">  
							<div class="card-body">  
								<div class="table-responsive">
									<table id="accounts_table" class="table table-hover table-center mb-0">
										<thead>
											<tr>
												<th><?php echo $language['lg_sno']; ?></th>
												<th><?php echo $language['lg_date1']; ?></th>
												<th><?php echo $language['lg_doctor2']."/".$language['lg_clinic']; ?></th>
												<th><?php echo $language['lg_amount']; ?></th>
												<th><?php echo $language['lg_status']; ?></th>
												<th><?php echo $language['lg_action']; ?></th>
											</tr>
										</thead>
										<tbody>
											
										</tbody>
									</table>
								</div>
							</div>
						</div>
			   	    </div>
			   	    <div id="doctor_request_tab" class="tab-pane fade">
					<div class="card card-table">  
						<div class="card-body">  
							<div class="table-responsive">
								<table id="doctor_request" style="width: 100%" class="table table-hover table-center mb-0">
									<thead>
										<tr>
											<th><?php echo $language['lg_date1']; ?></th>
											<th><?php echo $language['lg_doctor_name']; ?></th>
											<th><?php echo $language['lg_amount']; ?></th>
											<th><?php echo $language['lg_status']; ?></th>
											<th><?php echo $language['lg_action']; ?></th>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
				
				</div>
						</div>

					</div>
				</div>

			</div>		
			<!-- /Page Content -->
   