
      
      <!-- Page Wrapper -->
            <div class="page-wrapper">
      
                <div class="content container-fluid">
          
          <!-- Page Header -->
          <div class="page-header">
            <div class="row">
              <div class="col-sm-12">
                <h3 class="page-title">Welcome Admin!</h3>
                <ul class="breadcrumb">
                  <li class="breadcrumb-item active">Dashboard</li>
                </ul>
              </div>
            </div>
          </div>
          <!-- /Page Header -->

          <div class="row">
            <div class="col-xl-3 col-sm-6 col-12">
              <div class="card">
                <div class="card-body">
                  <div class="dash-widget-header">
                    <span class="dash-widget-icon text-primary border-primary">
                      <i class="fe fe-users"></i>
                    </span>
                    <div class="dash-count">
                      <h3><?php
                      /** @var int $doctors_count  */
                      echo $doctors_count;?></h3>
                    </div>
                  </div>
                  <div class="dash-widget-info">
                    <h6 class="text-muted">Doctors</h6>
                    <div class="progress progress-sm">
                      <div class="progress-bar bg-primary w-<?php echo $doctors_count;?>"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
              <div class="card">
                <div class="card-body">
                  <div class="dash-widget-header">
                    <span class="dash-widget-icon text-success">
                      <i class="fe fe-credit-card"></i>
                    </span>
                    <div class="dash-count">
                      <h3><?php
                      /** @var int $patients_count  */ 
                      echo $patients_count;?></h3>
                    </div>
                  </div>
                  <div class="dash-widget-info">
                    
                    <h6 class="text-muted">Patients</h6>
                    <div class="progress progress-sm">
                      <div class="progress-bar bg-success w-<?php echo $patients_count;?>"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
              <div class="card">
                <div class="card-body">
                  <div class="dash-widget-header">
                    <span class="dash-widget-icon text-danger border-danger">
                      <i class="fe fe-money"></i>
                    </span>
                    <div class="dash-count">
                      <h3><?php
                      /** @var int $appointments_count  */  
                      echo $appointments_count;?></h3>
                    </div>
                  </div>
                  <div class="dash-widget-info">
                    
                    <h6 class="text-muted">Appointment</h6>
                    <div class="progress progress-sm">
                      <div class="progress-bar bg-danger w-<?php echo $appointments_count;?>"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
              <div class="card">
                <div class="card-body">
                  <div class="dash-widget-header">
                    <span class="dash-widget-icon text-warning border-warning">
                      <i class="fe fe-folder"></i>
                    </span>
                    <div class="dash-count">
                      <h3><?php
                       /** @var float $revenue  */ 
                      echo default_currency_symbol().number_format($revenue,2);?></h3>
                    </div>
                  </div>
                  <div class="dash-widget-info">
                    
                    <h6 class="text-muted">Revenue</h6>
                    <div class="progress progress-sm">
                      <div class="progress-bar bg-warning w-0"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
		  

					<div class="row">
						<div class="col-md-12 col-lg-6">
						
							<!-- Sales Chart -->
							<div class="card card-chart">
								<div class="card-header">
									<h4 class="card-title">Revenue</h4>
								</div>
								<div class="card-body">
									<div id="morrisArea"></div>
								</div>
							</div>
							<!-- /Sales Chart -->
							
						</div>
						<div class="col-md-12 col-lg-6">
						
							<!-- Invoice Chart -->
							<div class="card card-chart">
								<div class="card-header">
									<h4 class="card-title">Status</h4>
								</div>
								<div class="card-body">
									<div id="morrisLine"></div>
								</div>
							</div>
							<!-- /Invoice Chart -->
							
						</div>	
					</div>
          
          <div class="row">
            <div class="col-md-6 d-flex">
            
              <!-- Recent Orders -->
              <div class="card card-table flex-fill">
                <div class="card-header">
                  <h4 class="card-title">Doctors List</h4>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-hover table-center mb-0 w-100">
                      <thead>
                        <tr>
                          <th>Doctor Name</th>
                          <th>Speciality</th>
                          <th>Earned</th>
                          <th>Reviews</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(!empty($doctors)){ 
                            foreach ($doctors as $drows) { 

                              $doctor_profileimage=(!empty($drows['profileimage']))?base_url().$drows['profileimage']:base_url().'assets/img/user.png';


                              ?>
                        <tr>
                          <td>
                            <h2 class="table-avatar">
                              <a target="_blank" href="<?php echo base_url().'doctor-preview/'.$drows['username'];?>" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="<?php echo $doctor_profileimage;?>" alt="User Image"></a>
                              <a target="_blank" href="<?php echo base_url().'doctor-preview/'.$drows['username'];?>">Dr. <?php echo ucfirst($drows['first_name'].' '.$drows['last_name']);?></a>
                            </h2>
                          </td>
                          <td><?php echo ucfirst($drows['specialization']);?></td>
                          <td><?php echo (get_earned($drows['id']));?></td>
                          <td>
                            <?php
                            $rating_value=$drows['rating_value'];
                            for( $i=1; $i<=5 ; $i++) {
                              if($i <= $rating_value){                                        
                              echo'<i class="fe fe-star text-warning"></i>';
                              }else { 
                              echo'<i class="fe fe-star-o text-secondary"></i>';
                              } 
                            } 
                          ?>
                           
                          </td>
                        </tr>
                        <?php } } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <!-- /Recent Orders -->
              
            </div>
            <div class="col-md-6 d-flex">
            
              <!-- Feed Activity -->
              <div class="card  card-table flex-fill">
                <div class="card-header">
                  <h4 class="card-title">Patients List</h4>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-hover table-center mb-0 w-100">
                      <thead>
                        <tr>                          
                          <th>Patient Name</th>
                          <th>Mobile No</th>
                          <th>Last Visit</th>
                          <th>Paid</th>                         
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(!empty($patients)){ 
                            foreach ($patients as $prows) { 

                              $patient_profileimage=(!empty($prows['profileimage']))?base_url().$prows['profileimage']:base_url().'assets/img/user.png';

                              ?>
                        <tr>
                          <td>
                            <h2 class="table-avatar">
                              <a target="_blank" href="<?php echo base_url().'patient-preview/'.base64_encode($prows['id']);?>" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="<?php echo $patient_profileimage;?>" alt="User Image"></a>
                              <a target="_blank" href="<?php echo base_url().'patient-preview/'.base64_encode($prows['id']);?>"><?php echo ucfirst($prows['first_name'].' '.$prows['last_name']);?> </a>
                            </h2>
                          </td>
                          <td><?php echo $prows['mobileno'];?></td>
                          <td><?php  if(isset($prows['last_vist'])){ echo date('d M Y',strtotime($prows['last_vist'])); }?></td>
                          <td><?php 
                          $org_amount=0;
                          if($prows['last_paid']){
                          $org_amount=get_doccure_currency($prows['last_paid'],$prows['currency_code'],default_currency_code());
                        }
                          echo default_currency_symbol()." ".number_format($org_amount,2,'.',',');?></td>
                          
                        </tr>
                      <?php } } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <!-- /Feed Activity -->
              
            </div>
            <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                  <h4 class="card-title">Appoinment List</h4>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="appoinment_table" class="table table-hover table-center w-100 mb-0">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Doctor/Clinic Name</th>
                          <th>Patient Name</th>
                          <th>Appointment Date</th>
                          <th>Booking Date</th>
                          <th>Status</th>
                          <th>Type</th>
                          <th>Amount</th>
                          
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        if(!empty($appointments))
                        {
                          $i=1;
                          foreach ($appointments as $rows) {

                            $doctor_profileimage=(!empty($rows['doctor_profileimage']))?base_url().$rows['doctor_profileimage']:base_url().'assets/img/user.png';
                            $patient_profileimage=(!empty($rows['patient_profileimage']))?base_url().$rows['patient_profileimage']:base_url().'assets/img/user.png';

                              $from_timezone=$rows['time_zone'];
                              $to_timezone = date_default_timezone_get();
                              $from_date_time = $rows['from_date_time'];
                              $from_date_time = converToTz($from_date_time,$to_timezone,$from_timezone);
                        ?>
                        <tr>
                          <td><?php echo $i++;?></td>

                          <?php if($rows['hospital_id']!=""){?>
                          <td>
                            <h2 class="table-avatar">
                            <a href="<?=base_url()?>/doctor-preview/<?=$rows['clinic_username']?>"><?=$rows['clinic_first_name']." ".$rows['clinic_last_name']?>
                            </a>
                            </h2>
                          </td>
                          <?php }else{ 

                            if($rows['role']==1)
                            {
                              $value=$this->language['lg_dr'];
                              $img='<a href="'.base_url().'doctor-preview/'.$rows['doctor_username'].'" class="avatar avatar-sm mr-2">
                                        <img class="avatar-img rounded-circle" src="'.$profile_image.'" alt="User Image">
                                      </a>';
                              $specialization=ucfirst($rows['doctor_specialization']);
                            }
                            else
                            {
                              $value="";
                              $img="";
                              $specialization="";
                            }

                           echo '<td><h2 class="table-avatar">
                                      '.$img.'
                                      <a href="'.base_url().'doctor-preview/'.$rows['doctor_username'].'">'.$value.' '.ucfirst($rows['doctor_name'].' ').' <span>'.$specialization.'</span></a>
                                    </h2></td>
                                      ';


                           } ?>
                          

                          
                           <td>
                            <h2 class="table-avatar">
                              <a target="_blank" href="<?php echo base_url().'patient-preview/'.base64_encode($rows['appointment_from']);?>" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="<?php echo $patient_profileimage;?>" alt="User Image"></a>
                              <a target="_blank" href="<?php echo base_url().'patient-preview/'.base64_encode($rows['appointment_from']);?>"><?php echo ucfirst($rows['patient_name']);?> </a>
                            </h2>
                          </td>
                          <td><?php echo date('d M Y',strtotime($from_date_time)).' <span class="d-block text-info">'.date('h:i A',strtotime($from_date_time)).'</span>';?></td>
                          <td><?php echo date('d M Y',strtotime($rows['created_date']));?></td>
                          <td><?php

                               $val='';

                          if($rows['appointment_status'] == '1')
                            {
                              $val = 'checked';
                            }

                           ?><div class="status-toggle">
                      <input type="checkbox"  id="status_<?php echo $rows['id']; ?>" class="check "<?php echo $val; ?> disabled>
                      <label for="status_<?php echo $rows['id']; ?>" class="checktoggle">checkbox</label>
                    </div></td>
                          <td><?php echo ucfirst($rows['type']);?></td>

                           <td><?php 
                          $org_amount=0;
                          if($rows['total_amount']){
                          $org_amount=get_doccure_currency($rows['total_amount'],$rows['currency_code'],default_currency_code());
                        }
                          echo default_currency_symbol()." ".number_format($org_amount,2,'.',',');?></td>
                        </tr>
                      <?php } } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
         
        </div>      
      </div>
      <!-- /Page Wrapper -->
    
        </div>
    <!-- /Main Wrapper -->
    
   