<!-- Breadcrumb -->
			<div class="breadcrumb-bar">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-md-12 col-12">
							<nav aria-label="breadcrumb" class="page-breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard"><?php 
									 /** @var array $language */
									echo $language['lg_dashboard']; ?></a></li>
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_add_product']; ?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_add_product']; ?></h2>
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
						<?php $this->load->view('web/includes/pharmacy_sidebar.php');?>
						<!-- /Profile Sidebar -->
					    </div>
						
						<div class="col-md-7 col-lg-8 col-xl-9">
							<div class="card">
								<div class="card-body">
									
									<!-- Profile Settings Form -->
									<form method="post" id="add_product" autocomplete="off" action="#" >
										<div class="form-group">
											<label><?php echo $language['lg_product_name']; ?> <span class="text-danger">*</span></label>
											<input type="text" class="form-control pharmacy_products" placeholder="Enter Product Name" id="name" name="name">
										</div>
														
														
										<div class="form-group">
											<label><?php echo $language['lg_category']; ?> <span class="text-danger">*</span></label>
											<select class="form-control" name="category" id="category">
												<option value=""><?php echo $language['lg_select_category']; ?></option>
											</select>
										</div>
									<div class="form-group">
										<label><?php echo $language['lg_subcategory']; ?> <span class="text-danger">*</span></label>
										<select class="form-control" name="subcategory" id="subcategory">
											<option value=""><?php echo $language['lg_select_subcateg']; ?></option>
										</select>
									</div>
									<div class="form-group">
										<label><?php echo $language['lg_units_val']; ?> <span class="text-danger">*</span></label>
										<div class="row">
											<div class="col-md-6">
											<input type="text" class="form-control" name="unit_value" onkeypress="return isNumber(event)" id="unit_value">
										    </div>
										    <div class="col-md-6">
											<select class="form-control" name="unit" id="unit">
												<option value=""><?php echo $language['lg_select_unit']; ?></option>
											</select>
										   </div>
									   </div>
									</div>
									<div class="form-group">
										<label><?php echo $language['lg_price']; ?> <span class="text-danger">*</span></label>
									<input type="text"  class="form-control" id="price" name="price" maxlength="8" 
									onkeyup="number(this)" >
									</div>
									<div class="form-group">
										<label><?php echo $language['lg_sale_price']; ?> <span class="text-danger">*</span></label>
										<input type="text"  onkeyup="number(this)" class="form-control" id="sale_price" name="sale_price" onchange="if($('#price').val()>this.value) { $('#price_error').css('display','block'); $('#price').val(''); } else { $('#price_error').css('display','none'); } ">
					<span id="price_error" class="error" style="display:none;"> Sales price should be greater than price value</span>	
									</div>
								
									<!-- <div class="form-group">
										<label>Discount in percent<?php //echo $language['lg_discount_in_percent']; ?> </label>
										<input type="text"  onkeyup="number(this)" class="form-control" id="discount" name="discount">
									</div> -->
									
									<div class="form-group">
										<label><?php echo $language['lg_manufactured_by']; ?> </label>
										<input type="text" class="form-control" id="manufactured_by" name="manufactured_by" required="required">
									</div>

									<div class="form-group">
										<label> <?php echo $language['lg_short_descripti']; ?> <span class="text-danger">*</span></label>
										<textarea class="form-control" rows="6" id="short_description" name="short_description" required="required"></textarea>
									</div>
									
									<div class="form-group">
										<label><?php echo $language['lg_description']; ?> <span class="text-danger">*</span></label>
										<textarea class="form-control" rows="6" id="description" name="description"></textarea>
									</div>
									
			                        <div class="form-group">
			                          <label for="upload-video"><?php echo $language['lg_upload_product_']; ?> <span class="text-danger">*</span></label>
			                          <input id="upload_image_url" class="form-control bg-input" name="upload_image_url" type="hidden" >
			                          <input id="upload_preview_image_url" class="form-control bg-input" name="upload_preview_image_url" type="hidden" >
			                          <button type="button" class="button blog-img-upload" id="upload_image_btn"><?php echo $language['lg_upload3'] ?></button>
			                          <div class="uploaded-section upload-wrap"> </div>
									  <input type="hidden" name="row_id" id="row_id" value="1" />
			                         

									  <label id="image-error" class="error" for="upload_image_url"></label>
<label id="image-errors" style="display:none;color:red;font-size:13px;">Please Upload image</label>

								

			                        </div>
									<label id="image-error" class="error" for="upload_image_url"></label>
									 
									<div class="form-group">
									 
									</div>

									<button id="product_btn" class="btn btn-primary" type="submit"><?php echo $language['lg_add_product']; ?></button>
								<button id="" class="btn btn-secondary" type="button" 
								onclick="window.location.href='<?php echo base_url();?>';"><?php echo $language['lg_cancel']; ?></button>
								</form>
									<!-- /Profile Settings Form -->
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>		
			<!-- /Page Content -->

			<script type="text/javascript">
	var category='';
  	var subcategory='';
  	var unit='';
</script>



			
