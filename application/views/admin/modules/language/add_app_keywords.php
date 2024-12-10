<!-- Page Wrapper -->
            <div class="page-wrapper">
                <div class="content container-fluid">
				
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col-md-8">
								<h3 class="page-title">Add Keywords</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
									<li class="breadcrumb-item active">Add Keywords</li>
								</ul>
							</div>
						
						</div>
					</div>
					<!-- /Page Header -->
					
					<div class="row">
                <div class="col-md-12">
                    <div class="card">
                    <div class="card-body">                       
                    	<p class="text-center text-danger"><b><em>Note: *   Please enter words only in English ..........</em></b></p>

                    	
                        <form class="form-horizontal" onsubmit="return keyword_validation();" action="<?php echo base_url('admin/language/add_app_keywords'); ?>" method="POST" enctype="multipart/form-data" >

                        	<input  type="hidden" id="page_key" name="page_key" value="<?php echo $this->uri->segment(4);?>" class="form-control" > <?php /** @phpstan-ignore-line */ ?>
							<input  type="hidden" id="type" name="type" value="App" class="form-control" >

							<div class="form-group">
								<label class="col-sm-3 control-label">Field Name</label>
								<div class="col-sm-9">
									<input name="field_name" id="field_name" class="form-control" type="text">
									<small class="error_msg help-block field_name_error error" style="display: none;">Please enter a field</small>                             
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Name</label>
								<div class="col-sm-9">
									<input name="name" id="name" class="form-control" type="text">
									<small class="error_msg help-block name_error error" style="display: none;">Please enter a name</small>                         
								</div>
							</div>

							
							
							<div class="m-t-30 text-center">
								<button name="form_submit" type="submit" class="btn btn-primary" value="true">Save</button>
								<a href="<?php echo base_url().'admin/language/app_keywords/'.$this->uri->segment(4) ?>" class="btn btn-default m-l-5">Cancel</a><?php /** @phpstan-ignore-line */ ?>
							</div>
						</form>                          
                    </div>
                </div>
                </div>
			</div>
					
				</div>			
			</div>
			<!-- /Page Wrapper -->
		
        </div>
		<!-- /Main Wrapper -->