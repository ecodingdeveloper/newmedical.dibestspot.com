
            
            <!-- Page Wrapper -->
            <div class="page-wrapper">
                <div class="content container-fluid">
                
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row">
                            <div class="col-sm-7 col-auto">
                                <h3 class="page-title">Packages</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Packages</a></li>
                                    <li class="breadcrumb-item active">Packages</li>
                                </ul>
                            </div>
                            <div class="col-sm-5 col">
                                <?php 
                                $get_pharmacy_details = $this->db->select('*')->from('users')->where('pharmacy_user_type',1)->get()->result_array();/** @phpstan-ignore-line */
                                ?>
          
              
              
                 <a href="javascript:void(0);" onclick="add_package_admin()"  class="btn btn-primary float-right mt-2">Add</a> 
              
            

                                
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="packagestable" class="table table-hover table-center mb-0 w-100">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Package Name</th>
                                                    <th>Description</th>
                                                    <th>Destination</th>
                                                    <th>Itinerary</th>
                                                    <th>Accomodation</th>
                                                    <th>Cost</th>
                                                    <th></th>
                                                    
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
                     <form action="#" enctype="multipart/form-data" autocomplete="off" id="admin_package_form" method="post"> 


                    <div class="modal-header">
                            <h5 class="modal-title">Add Package</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>

                    <div class="modal-body">
                        
                       <input type="hidden" value="" name="id"/> 
                       <input type="hidden" value="" name="method"/>
                       <div class="form-group">
                            <label for="package_name" class="control-label mb-10">Package Name <span class="text-danger">*</span></label>
                            <input type="text" parsley-trigger="change" id="package_name" name="package_name"  class="form-control" >
                        </div>
                        <div class="form-group">
                            <label for="package_destination" class="control-label mb-10">Destination <span class="text-danger">*</span></label>
                            <input type="text" parsley-trigger="change" id="package_destination" name="package_destination"  class="form-control" >
                        </div>

                         <!-- <div class="form-group">
                            
                            <label for="upload-video">Upload Image (jpeg, png, jpg) Recomended size (680X454) <span class="text-danger">*</span></label>

                          <input id="upload_image_url" class="form-control bg-input" name="upload_image_url" type="hidden" >
                          <input id="upload_preview_image_url" class="form-control bg-input" name="upload_preview_image_url" type="hidden" >
                          <button type="button" class="button blog-img-upload" id="upload_image_btn">Upload</button>
                          <div class="uploaded-section upload-wrap"> </div>
                          <label id="image-error" class="error" for="upload_image_url"></label>
                          <input type="hidden" name="row_id" id="row_id" value="1" />
                        </div>

                        <div id="imdDsiplay"></div> -->
                                                        
                        <div class="form-group">
                            <label for="package_description" class="control-label mb-10">Package Description <span class="text-danger">*</span></label>
                            <textarea type="number" parsley-trigger="change" id="package_description" name="package_description"  class="form-control" ></textarea>
                        </div>

                        <div class="form-group">
                            <label for="package_accomodation" class="control-label mb-10">Accomodation <span class="text-danger">*</span></label>
                            <select id="package_accomodation" name="package_accomodation"  class="form-control" >
                                <option value="">--Select Accomodation--</option>
                                <option value="threestar">3-Star Hotel</option>
                                <option value="fivestar">5-Star Hotel</option>         
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="package_itinerary" class="control-label mb-10">Itinerary <span class="text-danger">*</span></label>
                            <textarea type="number" parsley-trigger="change" id="package_itinerary" name="package_itinerary"  class="form-control" ></textarea>
                        </div>

                        <div class="form-group">
                            <label for="package_price" class="control-label mb-10">Package Price <span class="text-danger">*</span></label>
                            <input type="text" onkeypress="return isNumberKey(event)" parsley-trigger="change" id="package_price" name="package_price"  class="form-control" >
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
        
