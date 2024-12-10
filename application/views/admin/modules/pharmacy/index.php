            
            <!-- Page Wrapper -->
            <div class="page-wrapper">
                <div class="content container-fluid">
                
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row">
                            <div class="col-sm-9 col-auto">
                                <h3 class="page-title">Products</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Products</li>
                                </ul>
                            </div>
                            <div class="col-sm-2 col">
                                <a href="javascript:void(0);" data-toggle="modal" data-target="#upload_form"  class="btn btn-primary float-right mt-2">Bulk Upload</a>
                            </div>
                            <div class="col-sm-1 col">
                                <a href="javascript:void(0);" onclick="add_product()"  class="btn btn-primary float-right mt-2">Add</a>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="pharmacyproductstable1" class="table table-hover table-center mb-0 w-100">
                                            <thead>
                                                <tr>
                                                    <th>S.no</th>
                                                    <th>Products</th>
                                                    <th>Stock</th>
                                                    <th>Price</th>
                                                    <th>Status</th>
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
                     <form action="#" enctype="multipart/form-data" autocomplete="off" id="product_form" method="post"> 
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
                            <label for="product_name" class="control-label mb-10">Product Name sdsdsdf <span class="text-danger">*</span></label>
                            <input type="text" parsley-trigger="change" id="product_name" name="product_name"  class="form-control" >
                        </div>
                        <div class="form-group">
                            <label for="category_image" class="control-label mb-10">Product Image <span class="text-danger">*</span></label>
                            <input type="file"  id="product_image" name="product_image"  class="form-control" >
                            <input type="hidden" name="product_img" id="product_img">
                            <div id="product_images"></div>
                        </div>
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
                        <div class="form-group">
                            <label for="product_stock" class="control-label mb-10">Stock<span class="text-danger">*</span></label>
                            <input type="number" parsley-trigger="change" id="product_stock" name="product_stock"  class="form-control" >
                        </div>
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
                                    <select id="unit_id" name="unit_id"  class="form-control" >
                                        <option value="">--Select Unit--</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="product_price" class="control-label mb-10">Product Price <span class="text-danger">*</span></label>
                            <input type="number" parsley-trigger="change" id="product_price" name="product_price"  class="form-control" >
                        </div>
                        <div class="row">
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
                        </div>
                        <div class="form-group">
                            <label for="product_description" class="control-label mb-10">Product Description <span class="text-danger">*</span></label>
                            <textarea type="number" parsley-trigger="change" id="product_description" name="product_description"  class="form-control" ></textarea>
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

        <div id="upload_form" class="modal fade" role="dialog" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                     <form action="<?php echo base_url('admin/pharmacy/bulk_upload'); ?>" enctype="multipart/form-data" autocomplete="off" id="bulk_upload_formss" method="post"> 
                    <div class="modal-header">
                            <h5 class="modal-title">Bulk upload of products</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>

                    <div class="modal-body">
                    
                    <div class="form-group">
                        <label for="products" class="control-label mb-10">Upload File<span class="text-danger">*</span></label>
                        <input type="file" id="products" name="products" class="form-control">
                    </div>
                       
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline btn-default btn-sm btn-rounded" data-dismiss="modal">Close</button>
                        <button type="submit" id="btnprodsave" class="btn btn-outline btn-success ">Upload</button>
                    </div>
                     </form>
                </div>
            </div>
        </div>
            
        </div>
        <!-- /Main Wrapper -->
        
        