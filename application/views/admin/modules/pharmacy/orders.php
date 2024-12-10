			
			<!-- Page Wrapper -->
            <div class="page-wrapper">
                <div class="content container-fluid">
				
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col-sm-7 col-auto">
								<h3 class="page-title">Orders</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
									<li class="breadcrumb-item active">Orders</li>
								</ul>
							</div>
							<div class="col-sm-5 col">
							</div>
						</div>
					</div>
					<!-- /Page Header -->
					<div class="row">
						<div class="col-sm-12">
							<div class="card">
								<div class="card-body">
									<div class="table-responsive">
										<table id="orderstable" class="table table-hover table-center mb-0 w-100">
											<thead>
												<tr>
													<th>S.No</th>
													<th>Order ID</th>
													<th>Pharmacy</th>
													<th>Quantity</th>
													<th>Amount</th>
													<th>Payment</th>
													<th>Order Update</th>
													<th>Status</th>
													<th>Action</th>
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
			<!-- /Page Wrapper -->
			
			

			<div id="modal_form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                     <form action="#" enctype="multipart/form-data" autocomplete="off" id="order_form" method="post"> 
                    <div class="modal-header">
							<h5 class="modal-title">Order Update</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
					</div>

                    <div class="modal-body">
                        
                            <input type="hidden" value="" name="id"/>
                            <input type="hidden" value="" name="status"/>
                            
                            <div class="form-group">
                                <label for="unit" class="control-label mb-10">Order Description <span class="text-danger">*</span></label>
                                <input type="text" parsley-trigger="change" id="description" name="description"  class="form-control" >
                            </div>
                           
                       
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline btn-default btn-sm btn-rounded" data-dismiss="modal">Close</button>
                        <button type="submit" id="btnordersave" class="btn btn-outline btn-success ">Update</button>
                    </div>
                     </form>
                </div>
            </div>
        </div>
			
		<div id="modal_form_c" class="modal fade" role="dialog" tabindex="-1">
		    <div class="modal-dialog">
		        <div class="modal-content">
		             <form action="#" enctype="multipart/form-data" autocomplete="off" id="cancel_form" method="post"> 
		            <div class="modal-header">
							<h5 class="modal-title">Cancel Order</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
					</div>

		            <div class="modal-body">
		                
		            <input type="hidden" value="" name="id"/> 
		            <input type="hidden" value="" name="cancel_by"/>
		            
		            <div class="form-group">
		                <label for="reason" class="control-label mb-10">Cancel Reason<span class="text-danger">*</span></label>
		                <textarea id="reason" name="reason" class="form-control"></textarea>
		            </div>
		               
		            </div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-outline btn-default btn-sm btn-rounded" data-dismiss="modal">Close</button>
		                <button type="submit" id="btncancelsave" class="btn btn-outline btn-success ">Cancel</button>
		            </div>
		             </form>
		        </div>
		    </div>
		</div>
			
	
        </div>
		<!-- /Main Wrapper -->
		
		
