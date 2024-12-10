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
                              <strong><?php 
                              /** @var array $invoices  */
                              /** @var array $language  */
                              echo $language['lg_invoice_no']; ?>:</strong> <?php echo $invoices[0]['order_id'];?> <br>
                              <strong><?php echo $language['lg_issued']; ?>:</strong> <?php echo date('d M Y',strtotime($invoices[0]['created_at']));?>
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
                                 <?php echo ucfirst($invoices[0]['pharmacy_first_name']);?> <br>
                                 <?php echo $invoices[0]['doctoraddress1'].', '.$invoices[0]['doctoraddress2'];?>,<br>
                                 <?php echo $invoices[0]['patientcityname'].', '.$invoices[0]['patientstatename'];?> <br>
                              <?php echo $invoices[0]['patientcountryname']." - ".$invoices[0]['patient_postal_code'];?> <br>
                              <?php echo $invoices[0]['mob_no']; ?> <br>
                              </p>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="invoice-info invoice-info2">
                              <strong class="customer-text"><?php echo $language['lg_invoice_to']; ?></strong>
                              <p class="invoice-details">
                                 <?php echo ucfirst($invoices[0]['full_name']);?> <br>
                                 <?php echo $invoices[0]['address1'].', '.$invoices[0]['address2'];?>,<br>
                                 <?php echo $invoices[0]['doctorcityname'].', '.$invoices[0]['doctorstatename'].', ';?> <br>
								 <?php echo $invoices[0]['doctorcountryname']." - ".$invoices[0]['postal_code'];?> <br>
								 <?php echo $invoices[0]['phoneno']; ?> <br>
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
                                 <?php echo ucfirst($invoices[0]['payment_type']);?><br>
                              </p>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- /Invoice Item -->
                  <?php
                  // transaction charge percentage
                  $transaction_charge_percentage = $invoices[0]['transaction_charge_percentage'];

                   $cart_total_amount = 0;
                   foreach($invoices as $invoivce){
                   $cart_total_amount += $invoivce['subtotal'];
                   }
                   $tax = !empty(settings("tax"))?settings("tax"):"0";
				   // $transcation_charge_amt = !empty(settings("transaction_charge"))?settings("transaction_charge"):"0";
				$user_currency=get_user_currency();
				$user_currency_code=$user_currency['user_currency_code'];
				$user_currency_rate=$user_currency['user_currency_rate'];
				$currency_option = (!empty($user_currency_code))?$user_currency_code:$invoices[0]['product_currency'];
            $transcation_charges = ($cart_total_amount * ($transaction_charge_percentage/100));
				$rate_symbol = currency_code_sign($currency_option);
            $total_amounts = $cart_total_amount + $transcation_charges;
            $tax_amounts = ($total_amounts * $tax/100);
            $total_amounts = $total_amounts + $tax_amounts;
				// $transcation_charge=get_doccure_currency($transcation_charges,$invoices[0]['product_currency'],$user_currency_code); 
            $transcation_charge=convert_to_user_currency($transcation_charges,$invoices[0]['order_currency']);
            
   		 
            // $tax_amount=get_doccure_currency($tax_amounts,$invoices[0]['product_currency'],$user_currency_code); 
            $tax_amount=convert_to_user_currency($tax_amounts,$invoices[0]['order_currency']);                 
			
            // $total_amount=get_doccure_currency($total_amounts,$invoices[0]['product_currency'],$user_currency_code);
            $total_amount=convert_to_user_currency($total_amounts,$invoices[0]['order_currency']);
                     ?>
                  <!-- Invoice Item -->
                  <div class="invoice-item invoice-table-wrap">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="table-responsive">
                              <table class="invoice-table table table-bordered">
                                 <thead>
                                    <tr>
                                       <th><?php echo $language['lg_description']; ?></th>
                                       <th><?php echo 'Product name'; //$language['lg_description']; ?></th>
                                       <th><?php echo $language['lg_quantity']; ?></th>
                                       <th class="text-right"><?php echo $language['lg_total1']; ?></th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php
                                       $sno=1;
                                       foreach ($invoices as $key => $value) {
                                       /*$rate=get_doccure_currency($value['subtotal'],$value['product_currency'],$user_currency_code);
                                       $rate=number_format((float)$rate,2,'.',',');
                                       $amount=$rate_symbol.''.$rate;*/
                                       $amount=convert_to_user_currency($value['subtotal'],$invoices[0]['order_currency']);
                                       ?>
                                    <tr>
                                       <td><?php echo $sno; ?></td>
                                       <td><?php echo $value['product_name']; ?></td>
                                       <td><?php echo $value['qty']; ?></td>
                                       <td class="text-right"><?php echo $amount; ?></td>
                                    </tr>
                                    <?php  $sno++; } ?>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                        <div class="col-md-6 col-xl-5 ml-auto">
                           <div class="table-responsive">
                              <table class="invoice-table-two table">
                                 <tbody>
                                    <tr>
                                       <th><?php echo $language['lg_transaction_cha']; ?> (<?php echo $transaction_charge_percentage ?>%):</th>
                                       <!-- <td><span><?php // echo $rate_symbol.number_format((float)$transcation_charge,2, '.', ''); ?></span></td> -->
                                       <td><span><?php echo $transcation_charge; ?></span></td>
                                    </tr>
                                    <tr>
                                    <th><?php echo $language['lg_tax']; ?> (<?php echo $tax ?>%):</th>
                                    <!-- <td><span><?php // echo $rate_symbol.number_format((float)$tax_amount,2, '.', ''); ?></span></td> -->
                                    <td><span><?php echo $tax_amount; ?></span></td>
                                    </tr>
                                 <tr>
                                    <tr>
                                       <th><?php echo $language['lg_total_amount']; ?>:</th>
                                       <!-- <td><span><?php // echo $rate_symbol.number_format((float)$total_amount,2, '.', ''); ?></span></td> -->
                                       <td><span><?php echo $total_amount; ?></span></td>
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