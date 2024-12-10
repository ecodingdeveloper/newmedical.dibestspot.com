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
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_edit_product']; ?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_edit_product']; ?></h2>
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
									<form method="post" id="edit_product" autocomplete="off" action="#" >
                            <input type="hidden" name="product_id" value="<?php 
                             /** @var array $products */
                            echo $products['id'];?>">
                            <div class="form-group">
                              <label><?php echo $language['lg_name']; ?> <span class="text-danger">*</span></label>
                              <input type="text" value="<?php echo $products['name'];?>" class="form-control" id="name" name="name">
                            </div>
                            
                            <div class="form-group">
                              <label><?php echo $language['lg_category']; ?> <span class="text-danger">*</span></label>
                              <select class="form-control" name="category" id="category">
                                <option value="">Select Category<?php echo $language['lg_select_category']; ?></option>
                              </select>
                            </div>
                            <div class="form-group">
                              <label><?php echo $language['lg_subcategory']; ?> <span class="text-danger">*</span></label>
                              <select class="form-control" name="subcategory" id="subcategory" required="required">
                                <option value=""><?php echo $language['lg_select_subcateg']; ?></option>
                              </select>
                            </div>
                            <div class="form-group">
                              <label><?php echo $language['lg_units']; ?> <span class="text-danger">*</span></label>
                              <div class="row">
                                <div class="col-md-6">
                                <input type="text" class="form-control" name="unit_value" value="<?php echo $products['unit_value'];?>" onkeypress="return isNumber(event)" id="unit_value">
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
                              <input type="text" value="<?php echo $products['price'];?>" onkeyup="number(this)" class="form-control" id="price" name="price">
                            </div>
                            <div class="form-group">
                              <label><?php echo $language['lg_sale_price']; ?> <span class="text-danger">*</span></label>
                              <input type="text" value="<?php echo $products['sale_price'];?>"  onkeyup="number(this)" class="form-control" id="sale_price" name="sale_price">
                            </div>
                            <!-- <div class="form-group">
                              <label>Discount in percent<?php //echo $language['lg_discount_in_percent']; ?> </label>
                              <input type="text" value="<?php echo $products['discount'];?>"  onkeyup="number(this)" class="form-control" id="discount" name="discount">
                            </div> -->
                            <div class="form-group">
                              <label><?php echo $language['lg_description']; ?> <span class="text-danger">*</span></label>
                              <textarea class="form-control" rows="6" id="description" name="description"><?php echo $products['description'];?></textarea>
                            </div>

                              <div class="form-group">
                    <label><?php echo $language['lg_manufactured_by']; ?> </label>
                    <input type="text" class="form-control" id="manufactured_by" name="manufactured_by" value="<?php echo $products['manufactured_by'];?>" required="required">
                  </div>

                  <div class="form-group">
                    <label> <?php echo $language['lg_short_descripti']; ?> <span class="text-danger">*</span></label>
              <textarea class="form-control" rows="6" id="short_description" name="short_description" required="required"><?php echo $products['short_description'];?></textarea>
                  </div>
                  


                                        <div class="form-group">
                                          <label for="upload-video"><?php echo $language['lg_upload_product_']; ?> <span class="text-danger">*</span></label>

                                          <input id="upload_image_url" value="<?php echo $products['upload_image_url'];?>" class="form-control bg-input" name="upload_image_url" type="hidden" >
                                          <input id="upload_preview_image_url" value="<?php echo $products['upload_preview_image_url'];?>" class="form-control bg-input" name="upload_preview_image_url" type="hidden" >
                                          <button type="button" class="button blog-img-upload" id="upload_image_btn"><?php echo $language['lg_upload3'] ?></button>
                                          <div class="uploaded-section upload-wrap">

                                            <?php

                                            $image_url=explode(',', $products['upload_image_url']);
                                                            $preview_image_url=explode(',', $products['upload_preview_image_url']);

                                                            for ($i=0; $i <count($image_url) ; $i++) { 
                                                              if(file_exists($image_url[$i])) {
                                            echo'<div id="remove_image_div_'.$i.'" class="upload-images">
                                     <img src="'.base_url().$image_url[$i].'" alt="" height="42" width="42">
                                     <a href="javascript:;" onclick="remove_image(\''.$image_url[$i].'\',\''.$preview_image_url[$i].'\',\''.$i.'\')"  class="uploaded-remove btn btn-icon btn-danger btn-sm"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
                                     </div>';
}
                                    } ?>

                                           </div>
                                          <label id="image-error" class="error" for="upload_image_url"></label>
                                           <input type="hidden" name="row_id" id="row_id" value="<?php echo ($i+1);?>" />
                                        </div>
                                   

                            <button id="product_btn" class="btn btn-primary mr-1" type="submit" style="float: right"><?php echo $language['lg_save_changes']; ?></button>


                          </form>

                          <a href="<?=base_url()?>/product-list">   <button class="btn btn-danger mr-1" style="float: right;"><?php echo $language['lg_close1']; ?></button></a>
									<!-- /Profile Settings Form -->
									
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>		
			<!-- /Page Content -->

			<script type="text/javascript">
    var category='<?php echo $products['category'];?>';
    var subcategory='<?php echo $products['subcategory'];?>';
    var unit='<?php echo $products['unit'];?>';
  </script>


			