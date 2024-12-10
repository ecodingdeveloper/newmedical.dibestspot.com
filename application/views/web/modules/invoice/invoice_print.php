<!DOCTYPE html> 
<html lang="en">
  <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
     <title><?php echo !empty(settings("meta_title"))?settings("meta_title"):"Doccure";?></title>
    <meta content="<?php echo !empty(settings("meta_keywords"))?settings("meta_keywords"):"";?>" name="keywords">
    <meta content="<?php echo !empty(settings("meta_description"))?settings("meta_description"):"";?>" name="description">
        <!-- Favicons -->
    <link href="<?php echo !empty(base_url().settings("favicon"))?base_url().settings("favicon"):base_url()."assets/img/favicon.png";?>" rel="icon">
    
     <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css">
    
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/fontawesome/css/all.min.css">
    
    
    <!-- Main CSS -->
    
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/toastr.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css">
    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?php //echo base_url();?>assets/js/html5shiv.min.js"></script>
      <script src="<?php //echo base_url();?>assets/js/respond.min.js"></script>
    <![endif]-->
  
  </head>
			<div class="content">
				<div class="container-fluid">

					<div class="row">
						<div class="col-lg-8 offset-lg-2">
							<div class="invoice-content">
								<div class="invoice-item">
									<div class="row">
										<div class="col-md-6">
											<div class="invoice-logo">
												<img src="<?php echo !empty(base_url().settings("logo_front"))?base_url().settings("logo_front"):base_url()."assets/img/logo.png";?>" alt="logo">
											</div>
										</div>
										<div class="col-md-6">
											<p class="invoice-details">
												<strong><?php echo $language['lg_invoice_no']; ?>:</strong> <?php echo $invoices['invoice_no'];?> <br>
												<strong><?php echo $language['lg_issued']; ?>:</strong> <?php echo date('d M Y',strtotime($invoices['payment_date']));?>
											</p>
										</div>
									</div>
								</div>
								
								<!-- Invoice Item -->
								<div class="invoice-item">
									<div class="row">
										<div class="col-md-6">
											<div class="invoice-info">
												<strong class="customer-text"><?php echo $language['lg_invoice_from']; ?></strong>
												<p class="invoice-details invoice-details-two">
													<?php if($role==1){ echo $language['lg_dr']; } ?> <?php echo ucfirst($invoices['doctor_name']);?> <br>
													<?php echo $invoices['doctoraddress1'].', '.$invoices['doctoraddress2'];?>,<br>
													<?php echo $invoices['doctorcityname'].', '.$invoices['doctorcountryname'];?>
													<?php
													if (
														isset($invoices['doctorpostalcode']) && 
														!empty($invoices['doctorpostalcode'])
													) {
														echo ' - '.$invoices['doctorpostalcode'];
													}
													?> <br>
													<?php echo $invoices['doctormobile'];?>
												</p>
											</div>
										</div>
										<div class="col-md-6">
											<div class="invoice-info invoice-info2">
												<strong class="customer-text"><?php echo $language['lg_invoice_to']; ?></strong>
												<p class="invoice-details">
													<?php echo ucfirst($invoices['patient_name']);?> <br>
													<?php echo $invoices['patientaddress1'].', '.$invoices['patientaddress2'];?>,<br>
													<?php echo $invoices['patientcityname'].', '.$invoices['patientcountryname'];?>
													<?php
													if (
														isset($invoices['patientpostalcode']) && 
														!empty($invoices['patientpostalcode'])
													) {
														echo ' - '.$invoices['patientpostalcode'];
													}
													?><br>
													<?php echo $invoices['patientmobile'];?>
												</p>
											</div>
										</div>
									</div>
								</div>
								<!-- /Invoice Item -->
								
								<!-- Invoice Item -->
								<div class="invoice-item">
									<div class="row">
										<div class="col-md-12">
											<div class="invoice-info">
												<strong class="customer-text"><?php echo $language['lg_payment_method']; ?></strong>
												<p class="invoice-details invoice-details-two">
													<?php
														if($invoices['payment_status']==0){
														$invoices['payment_type']="GTC: Invoice";
													}
													echo ucfirst($invoices['payment_type']);?><br>
													
												</p>
											</div>
										</div>
									</div>
								</div>
								<!-- /Invoice Item -->
								<?php


            $user_currency=get_user_currency();
            $user_currency_code=$user_currency['user_currency_code'];
            $user_currency_rate=$user_currency['user_currency_rate'];

            $currency_option = (!empty($user_currency_code))?$user_currency_code:$invoices['currency_code'];
            $rate_symbol = currency_code_sign($currency_option);

                      
            $rate=get_doccure_currency($invoices['per_hour_charge'],$invoices['currency_code'],$user_currency_code);
            $rate=number_format($rate,2,'.',',');
                     
            $amount=$rate_symbol.''.$rate;
              
            $transcation_charge=get_doccure_currency($invoices['transcation_charge'],$invoices['currency_code'],$user_currency_code);
            $transaction_charge_percentage = $invoices['transaction_charge_percentage'];

            $tax_amount=get_doccure_currency($invoices['tax_amount'],$invoices['currency_code'],$user_currency_code);

             $total_amount=get_doccure_currency($invoices['total_amount'],$invoices['currency_code'],$user_currency_code);
			 
			 // $transcation_charge_amt = !empty(settings("transaction_charge"))?settings("transaction_charge"):"0";



								?>
								
								<!-- Invoice Item -->
								<div class="invoice-item invoice-table-wrap">
									<div class="row">
										<div class="col-md-12">
											<div class="table-responsive">
												<table class="invoice-table table table-bordered">
													<thead>
														<tr><th><?php echo $language['lg_sno']; ?></th>
															<th><?php echo $language['lg_description']; ?></th>
															<th class="text-right"><?php echo $language['lg_total1']; ?></th>
														</tr>
													</thead>
													<tbody>
													<?php
													$mode='';
													if($role=='1' || $role=='6')
													{
                                                    	$payment_id=$invoices['id'];
														if($invoices['billing_id']>0) {
															$this->db->where('id',$payment_id);
                                                       		$appointments_res= $this->db->get('payments')->result_array();
														} else {
															$this->db->where('payment_id',$payment_id);
															$appointments_res= $this->db->get('appointments')->result_array();
														}

                                                       	$sno=1;
                                                       	foreach ($appointments_res as $key => $value)
                                                       	{
                                                       		if($value['type']=="online" || $value['type']=="Online"){
                                                       			$mode=$language['lg_video_call_book'];
                                                       		}else{
                                                       			$mode=$language['lg_clinic_booking'];
                                                       		}

															if($invoices['billing_id']>0) {
																$bill_no = $this->db->select('bill_no')->get_where('billing',array('id'=>$invoices['billing_id']))->row()->bill_no;
																$mode = $language['lg_billing'].' ('.$bill_no.')';
															}
													?>
                                                       
														<tr>
															<td><?php echo $sno; ?></td>
															<td><?php echo $mode; ?></td>
															<td class="text-right"><?php echo $amount; ?></td>
														</tr>
													<?php  $sno++; } ?>
													<?php
													}
													else
													{
														$sno=1;
														$test_ids = $this->db->get_where('lab_payments',array('order_id'=>$invoices['order_id']))->row()->booking_ids;
														$test_name="";
											            $array_ids=explode(',', $test_ids);
														$booking_ids_price = $this->db->get_where('lab_payments',array('order_id'=>$invoices['order_id']))->row()->booking_ids_price;
														if(!empty($booking_ids_price)) {
															$price_ids=explode(',', $booking_ids_price);
														} else {
															$price_ids=array();
														} 
											            foreach ($array_ids as $keys =>$value) {
											                $this->db->select('*');
											                $this->db->where('id',$value);
											                $query = $this->db->get('lab_tests');
											                $result = $query->row_array();
											                $mode=$result['lab_test_name'];											            	
															if(!empty($price_ids)) {
																$price=get_doccure_currency($price_ids[$keys],$result['currency_code'],$user_currency_code);
															} else {
											            		$price=get_doccure_currency($result['amount'],$result['currency_code'],$user_currency_code);
															}
											        ?>
											            <tr>
															<td><?php echo $sno; ?></td>
															<td><?php echo $mode; ?></td>
															<td class="text-right"><?php echo $rate_symbol.$price; ?></td>
														</tr>
											        <?php
													$sno++;	}	}
													?>
													</tbody>
												</table>
												</div>
										</div>
										<div class="col-md-6 col-xl-4 ml-auto">
											<div class="table-responsive">
												<table class="invoice-table-two table ">
													<tbody>
													<tr>
														<th><?php echo $language['lg_transaction_cha']; ?> (<?php echo $transaction_charge_percentage ?>%):</th>
														<td><span><?php echo $rate_symbol.number_format($transcation_charge,2, '.', ''); ?></span></td>
													</tr>
													<tr>
														<th><?php echo $language['lg_tax']; ?> (<?php echo $invoices['tax'] ?>%):</th>
														<td><span><?php echo $rate_symbol.number_format($tax_amount,2, '.', ''); ?></span></td>
													</tr>
													<tr>
														<th><?php echo $language['lg_total_amount']; ?>:</th>
														<td><span><?php echo $rate_symbol.number_format($total_amount,2, '.', ''); ?></span></td>
													</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<!-- /Invoice Item -->
								
							</div>
						</div>
					</div>

				</div>

			</div>		
			<!-- /Page Content-->

			 <script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
    <script type="text/javascript">
    	window.print();
    </script>