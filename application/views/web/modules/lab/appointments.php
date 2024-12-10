<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-12 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Appointments List</li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title">Appointments List</h2>
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
                    <?php $this->load->view('web/includes/lab_sidebar'); ?>
                    <!-- /Profile Sidebar -->
            </div>
            <div class="col-md-7 col-lg-8 col-xl-9">
                <div class="card card-table">
                    <div class="card-body">
                        <!-- Lab Table -->
                        <div class="table-responsive">
                            <table class="table table-hover table-center mb-0" id="lab_appointment_table">
                                <thead>
                                    <tr>
                                        <th>S.No </th>
                                        <th><?php echo $language['lg_patient_name'];?></th>
                                        <th><?php echo $language['lg_test_name'];?> </th>
                                        <th><?php echo $language['lg_lab_test_date'];?> </th>
                                        <th><?php echo $language['lg_amount'];?></th>
                                        <th><?php echo $language['lg_booked_date'];?></th>
                                        <th><?php echo $language['lg_booking_status'];?></th>
                                        <th>Status</th>
                                        <th><?php echo $language['lg_action'];?></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <!-- /Lab Table -->

                    </div>
                </div>
            </div>
        </div>

    </div>

</div>		
<!-- /Page Content -->

