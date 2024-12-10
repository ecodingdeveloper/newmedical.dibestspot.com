<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-12 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Lab Test List</li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title">Lab Test List</h2>
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
                    <div class="card-header">
                        <a href="javascript:void(0);" onclick="add_lab_test()" class="btn btn-primary float-right mt-2">+ <?php echo $language['lg_lab_test_add_btn_label']; ?></a>
                    </div>
                    <div class="card-body">
                        <!-- Lab Table -->
                        <div class="table-responsive">
                            <table class="table table-hover table-center mb-0" id="lab_table">
                                <thead>
                                    <tr>
                                            <th>S.No</th>
                                            <th>Lab Name</th>
                                            <th>Lab Test Name</th>
                                            <th>Duration</th>
                                            <th>Currency Code</th>
                                            <th>Amount</th>
                                            <th>Description</th>
                                            <th>Created Date</th>
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

