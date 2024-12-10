<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-12 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Lab Appointments List</li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title">Lab Appointments List</h2>
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
                    <?php $this->load->view('web/includes/patient_sidebar'); ?>
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
                                            <th>S.NO </th>
                                            <th>Labs Name</th>
                                            <th>Tests Name</th>
                                            <th>Lab test date</th>
                                            <th>Amount</th>
                                            <th>Booked Date</th>
                                            <th>Booking Status</th>
                                            <th>Status</th>
                                            <th>Action</th>
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

