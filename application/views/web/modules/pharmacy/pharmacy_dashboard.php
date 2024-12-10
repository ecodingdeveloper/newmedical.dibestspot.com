<!-- Breadcrumb -->
      <div class="breadcrumb-bar">
        <div class="container-fluid">
          <div class="row align-items-center">
            <div class="col-md-12 col-12">
              <nav aria-label="breadcrumb" class="page-breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard"><?php
                   /** @var array $language */
                   echo $language['lg_home']; ?></a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?php echo  $language['lg_dashboard']; ?></li>
                </ol>
              </nav>
              <h2 class="breadcrumb-title"><?php echo  $language['lg_dashboard']; ?></h2>
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
              <?php $this->load->view('web/includes/pharmacy_sidebar.php');
                    $user_detail=user_detail($this->session->userdata('user_id')); ?>
              <!-- /Profile Sidebar -->
              
            </div>
            
            <div class="col-md-7 col-lg-8 col-xl-9">

              <?php 
			  //print_r($user_detail);
              if($user_detail['is_updated']=='0') {
              echo'<div class="alert alert-warning" role="alert">
              <i class="fa fa-exclamation-circle" aria-hidden="true"></i>'.$language['lg_this_is_a_warni'].' <a href="'.base_url().'profile" class="alert-link">'.$language['lg_click_here1'].'</a>. '.$language['lg_give_it_a_click'].'
              </div>';
                }
                if($user_detail['is_verified']=='0') {
                echo'<div class="alert alert-warning" role="alert">
                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                '.$language['lg_this_is_a_warni1'].' <a onclick="email_verification()" href="javascript:void(0);" class="alert-link">'.$language['lg_click_here1'].'</a>. '.$language['lg_give_it_a_click1'].'
              </div>';
                }
              ?>
             <div class="row">
                <div class="col-md-12">
                  <div class="card dash-card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-12 col-lg-6">
                          <div class="dash-widget dct-border-rht">
                            <div class="circle-bar circle-bar1">
                              <div class="circle-graph1" data-percent="75">
                                <img src="assets/img/icon01.png" class="img-fluid" alt="patient">
                              </div>
                            </div>
                            

                            <div class="dash-widget-info">
                              <h6><?php echo $language['lg_today_order']; ?></h6>
                              <h3><?php 
                               /** @var array $today_order_list */
                              echo count($today_order_list); ?></h3>
                              <!-- <p class="text-muted"><?php echo $language['lg_till_today']; ?></p> -->
                            </div>
                          </div>
                        </div>
                        
                        <!-- <div class="col-md-12 col-lg-4">
                          <div class="dash-widget dct-border-rht">
                            <div class="circle-bar circle-bar2">
                              <div class="circle-graph2" data-percent="65">
                                <img src="assets/img/icon02.png" class="img-fluid" alt="Patient">
                              </div>
                            </div>
                            <div class="dash-widget-info"> -->
                              <h6><?php echo $language['lg_upcoming_order']; ?></h6>
                              <h3><?php
                                /** @var array $upcoming_order_list */
                              //  echo count($upcoming_order_list); ?></h3>
                              <!-- <p class="text-muted"><?php //echo date('d M Y',strtotime(date("y-m-d")));  ?></p> -->
                            <!-- </div>
                          </div>
                        </div> -->
                        
                        <div class="col-md-12 col-lg-6">
                          <div class="dash-widget">
                            <div class="circle-bar circle-bar3">
                              <div class="circle-graph3" data-percent="50">
                                <img src="assets/img/icon03.png" class="img-fluid" alt="Patient">
                              </div>
                            </div>
                            <div class="dash-widget-info">
                              <h6><?php echo $language['lg_total_order']; ?></h6>
                              <h3><?php 
                               /** @var array $total_order */
                              echo count($total_order);  ?></h3>
                              <!-- <p class="text-muted"><?php echo $language['lg_till_today']; ?></p> -->
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card card-table">
                <div class="card-body">
                
                  <!-- Invoice Table -->
                  <div class="table-responsive">
                    <table id="orders_table" class="table table-hover table-center mb-0 w-100">
                      <thead>
                        <tr>
                          
                          <th>S.NO</th>
                          <th><?php echo $language['lg_order_id']; ?></th>
                          <th><?php echo $language['lg_customer_name']; ?></th>
                          <th><?php echo $language['lg_quantity']; ?></th>
                          <th><?php echo $language['lg_amount']; ?></th>
                          <th><?php echo $language['lg_payment_gateway']; ?></th>
                          <th><?php echo $language['lg_status']; ?></th>
                          <th><?php echo $language['lg_order_status']; ?></th>
                          
                        </tr>
                      </thead>
                      <tbody>
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
