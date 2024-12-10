
            
            <!-- Page Wrapper -->
            <div class="page-wrapper">
                <div class="content container-fluid">
                
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row">
                            <div class="col-sm-7 col-auto">
                                <h3 class="page-title">Products</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Products</li>
                                </ul>
                            </div>
                            <div class="col-sm-5 col">
                                <?php 
                                $get_pharmacy_details = $this->db->select('*')->from('users')->where('pharmacy_user_type',1)->get()->result_array();/** @phpstan-ignore-line */
            
            if(isset($get_pharmacy_details) && !empty($get_pharmacy_details)){
              
              ?> 
                 <a href="javascript:void(0);" onclick="add_product_admin()"  class="btn btn-primary float-right mt-2">Add</a> <?php 
              
            }else{
                ?> 
                    <a href="#" onClick="alert('Please create pharmacy')"  class="btn btn-primary float-right mt-2">Add</a> <?php 

              
            }  ?>

                                
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="adminproductstable" class="table table-hover table-center mb-0 w-100">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Product</th>
                                                    <th>Actions</th>
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
                     <form action="#" enctype="multipart/form-data" autocomplete="off" id="admin_product_form" method="post"> 


                    <div class="modal-header">
                            <h5 class="modal-title">Add Product</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>

                    <div class="modal-body">
                        
                       <input type="hidden" value="" name="id"/> 
                       <input type="hidden" value="" name="method"/>
                       <div class="form-group">
                            <label for="product_name" class="control-label mb-10">Product Name <span class="text-danger">*</span></label>
                            <input type="text" parsley-trigger="change" id="product_name" name="product_name"  class="form-control" >
                        </div>

                         <div class="form-group">
                            
                            <label for="upload-video">Upload Image (jpeg, png, jpg) Recomended size (680X454) <span class="text-danger">*</span></label>

                          <input id="upload_image_url" class="form-control bg-input" name="upload_image_url" type="hidden" >
                          <input id="upload_preview_image_url" class="form-control bg-input" name="upload_preview_image_url" type="hidden" >
                          <button type="button" class="button blog-img-upload" id="upload_image_btn">Upload</button>
                          <div class="uploaded-section upload-wrap"> </div>
                          <label id="image-error" class="error" for="upload_image_url"></label>
                          <input type="hidden" name="row_id" id="row_id" value="1" />
                        </div>

                        <div id="imdDsiplay"></div>
                                                        

                        <div class="form-group">
                            <label for="category_id" class="control-label mb-10">Category Name <span class="text-danger">*</span></label>
                            <select id="category_id" name="category_id"  class="form-control" >
                                <option value="">--Select Category--</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sub_category_id" class="control-label mb-10">Sub Category Name <span class="text-danger">*</span></label>
                            <select id="sub_category_id" name="sub_category_id"  class="form-control" >
                                <option value="">--Select Sub Category--</option>
                            </select>
                        </div>

                       <!-- <div class="form-group">
                            <label for="product_stock" class="control-label mb-10">Stock<span class="text-danger">*</span></label>
                            <input type="number" parsley-trigger="change" id="product_stock" name="product_stock"  class="form-control" >
                        </div>  -->
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="unit_value" class="control-label mb-10">Unit<span class="text-danger">*</span></label>
                                    <input type="number" parsley-trigger="change" id="unit_value" name="unit_value"  class="form-control" >
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="unit_id" class="control-label mb-10">Unit Type<span class="text-danger">*</span></label>
                                    <select id="unit_id" name="unit_id"  class="form-control" required="" >
                                        <option value="">--Select Unit--</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="product_price" class="control-label mb-10">Product Price <span class="text-danger">*</span></label>
                            <input type="text" onkeypress="return isNumberKey(event)" parsley-trigger="change" id="product_price" name="product_price"  class="form-control" >
                            <script type="text/javascript">
                                function isNumberKey(evt)
                                {
                                    var charCode = (evt.which) ? evt.which : evt.keyCode;
                                    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
                                    return false;

                                  return true;
                                }
                            </script>
                        </div>

                         <div class="form-group">
                            <label for="product_price" class="control-label mb-10">Sale Price <span class="text-danger">*</span></label>
                            <input type="decimal" onkeypress="return isNumberKey(event)" parsley-trigger="change" id="sale_price" name="sale_price"  class="form-control" >
                           
                        </div>

                        <!-- <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="discount_value" class="control-label mb-10">Discount Value</label>
                                    <input type="number" parsley-trigger="change" id="discount_value" name="discount_value"  class="form-control" >
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="discount_type" class="control-label mb-10">Discount Type</label>
                                    <select id="discount_type" name="discount_type"  class="form-control" >
                                        <option value="flat">Flat</option>
                                        <option value="percent">Percent</option>
                                    </select>
                                </div>
                            </div>

                             <div class="col-6">
                                <div class="form-group">
                                    <label for="discount_type" class="control-label mb-10">Discount In Percent</label>
                                    <input type="number" parsley-trigger="change" id="discount_percent" name="discount_percent" class="form-control" >
                                </div>
                            </div>
                            
                            
                        </div> -->
                        
                        <div class="form-group">
                            <label for="product_description" class="control-label mb-10">Manufactured By <span class="text-danger">*</span></label>
                            <textarea type="number" parsley-trigger="change" id="manufatured_by" name="manufatured_by"  class="form-control" ></textarea>
                        </div>

                        <div class="form-group">
                            <label for="product_description" class="control-label mb-10">Product Description <span class="text-danger">*</span></label>
                            <textarea type="number" parsley-trigger="change" id="product_description" name="product_description"  class="form-control" ></textarea>
                        </div>
                        <div class="form-group">
                            <label for="product_description" class="control-label mb-10">Product Short Description <span class="text-danger">*</span></label>
                            <textarea type="number" parsley-trigger="change" id="short_description" name="short_description"  class="form-control" ></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline btn-default btn-sm btn-rounded" data-dismiss="modal">Close</button>
                        <button type="submit" id="btnproductsave" class="btn btn-outline btn-success ">Submit</button>
                    </div>
                     </form>
                </div>
            </div>
        </div>

    
        </div>
        <!-- /Main Wrapper -->
        
    <div class="modal fade" id="avatar-image-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">   
        <div class="modal-header d-block"> 
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Upload Image</h4>
                  <span id="image_size" > Please Upload a Image of size above 680x454 </span> 
        </div>

        <div class="modal-body">
          <div id="imageimg_loader" class="loader-wrap" style="display: none;">
            <div class="loader">Loading...</div>
          </div>

          <div class="image-editor">
            
            <input type="file" id="fileopen"  name="file" class="cropit-image-input">
            <span class="error_msg help-block" id="error_msg_model" ></span> 
            <div class="cropit-preview" style="width: 465px;"></div>
            <div class="row resize-bottom">
              <div class="col-md-4">
                <div class="image-size-label">Resize Image</div>
              </div>
              <div class="col-md-4"><input type="range" class="custom cropit-image-zoom-input"></div>
              <div class="col-md-4 text-right"><button class="btn btn-primary export">Done</button></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
        
