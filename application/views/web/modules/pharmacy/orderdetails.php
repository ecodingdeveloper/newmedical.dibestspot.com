<!-- Breadcrumb -->
<div class="breadcrumb-bar">
   <div class="container-fluid">
      <?php 
       /** @var array $language */
      if($this->session->userdata('role')=='5'){ ?>
      <div class="row align-items-center">
         <div class="col-md-12 col-12">
            <nav aria-label="breadcrumb" class="page-breadcrumb">
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard"><?php 
                   echo $language['lg_dashboard']; ?></a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_order_list']; ?></li>
               </ol>
            </nav>
            <h2 class="breadcrumb-title"><?php echo $language['lg_order_list']; ?></h2>
         </div>
      </div>
      <?php }else{ ?>
      <div class="row align-items-center">
         <div class="col-md-12 col-12">
            <nav aria-label="breadcrumb" class="page-breadcrumb">
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard"><?php echo $language['lg_dashboard'];?></a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_orders']; ?></li>
               </ol>
            </nav>
            <h2 class="breadcrumb-title"><?php echo $language['lg_orders']; ?></h2>
         </div>
      </div>
      <?php }?>
   </div>
</div>
<!-- /Breadcrumb -->
<!-- Page Content -->
<div class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-5 col-lg-4 col-xl-3 theiaStickySidebar">
            <!-- Profile Sidebar -->
            <?php if($this->session->userdata('role')=='5'){ ?>
            <?php $this->load->view('web/includes/pharmacy_sidebar.php');?>
            <?php }else{ ?>
            <?php $this->load->view('web/includes/patient_sidebar.php');?>
            <?php }?>
            <!-- /Profile Sidebar -->
         </div>
         <div class="col-md-7 col-lg-8 col-xl-9">
            <div class="card card-table">
               <div class="card-body ">
                  <div class=" d-grid float-md-right px-2 gap-3">
                     <a href="<?php 
                       /** @var array $product_details */
                     echo base_url().'invoice-products-view/'.base64_encode($product_details[0]['order_id'])?>"  class="btn btn-lg bg-info-light ">
                     View Order
                     </a>
                     <a href="<?php echo base_url().'invoice-products-print/'.base64_encode($product_details[0]['order_id'])?>" class="btn btn-lg bg-primary-light " target="blank">
                     Print Order
                     </a>
                  </div>
                  <!-- Invoice Table -->
                  <div class="table-responsive">
                     <table id="orders_table" class="table table-hover table-center mb-0 w-100">
                        <thead>
                           <tr>
                              <th><?php echo $language['lg_sno']; ?></th>
                              <th><?php echo $language['lg_order_id']; ?></th>
                              <?php if($this->session->userdata('role')=='5'){ ?>
                              <th><?php echo $language['lg_customer_name']; ?></th>
                              <?php }else{ ?>
                              <th><?php echo $language['lg_pharmacy_name']; ?></th>
                              <?php }?>
                              <th><?php echo $language['lg_quantity']; ?></th>
                              <th><?php echo 'Product Name';//$language['lg_quantity']; ?></th>
                              <th><?php echo $language['lg_amount']; ?></th>
                              <th><?php echo $language['lg_payment_gateway']; ?></th>
                              <th><?php echo $language['lg_status']; ?></th>
                              <th><?php echo $language['lg_order_date']; ?></th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php $i=1; foreach($product_details as $products){
                              if($this->session->userdata('role')=='5'){
                              $name = $products['full_name'];
                              }else{
                              	$name = $products['pharmacy_first_name'];
                              } if($products['order_status']== 'pending'){
                              	$status = '<span class="badge badge-primary">'.$this->language['lg_order_placed'].'</span>';
                                }
                                if($products['order_status']== 'shipped'){
                              	$status = '<span class="badge badge-warning">'.$this->language['lg_shipped'].'</span>';
                                }
                                if($products['order_status']== 'completed'){
                              	$status = '<span class="badge badge-success">'.$this->language['lg_delivered'].'</span>';
                                }                                
                              ?>
                           <tr>
                              <th><?=$i?></th>
                              <td><?php echo $products['order_id']; ?></th>
                              <td><?php echo $name; ?></td>
                              <td><?php echo $products['qty']; ?></td>
                              <td><?php echo $products['product_name']; ?></td>
                              <!-- <td><?php  // echo currency_code_sign($products['product_currency']).$products['subtotal']; ?></td> -->
                              <td><?php  echo convert_to_user_currency($products['subtotal'],$products['order_currency']); ?></td>
                              <td><?php  echo $products['payment_type']; ?></td>
                              <td><?php 
                               /** @var string $status */
                               echo $status; ?></td>
                              <td><?php  echo $products['created_at_formatted']; ?></td>
                           </tr>
                           <?php $i++; } ?>
                        </tbody>
                     </table>
                  </div>
                  <!-- /Invoice Table -->
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- /Page Content -->