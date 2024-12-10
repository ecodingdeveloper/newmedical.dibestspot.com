
            
            <!-- Page Wrapper -->
            <div class="page-wrapper">
                <div class="content container-fluid">
                
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row">
                            <div class="col-sm-7 col-auto">
                                <h3 class="page-title">Packages</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Packages</li>
                                </ul>
                            </div>
                            <div class="col-sm-5 col">
                                <?php 
                                $get_package_details = $this->db->select('*')->from('packages')->where('status',1)->get()->result_array();/** @phpstan-ignore-line */
                                // echo '<pre>';
                                // print_r($get_package_details);
                                // echo '</pre>';
                                // die("dsfad");
                                ?>
          
              
              
                 <a href="javascript:void(0);" onclick="add_package_admin()"  class="btn btn-primary float-right mt-2">Add a package</a> 
              
            

                                
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
                                                    <th>Id</th>
                                                    <th>Package Name</th>
                                                    <th>Description</th>
                                                    <th>Destination</th>
                                                    <th>Currency</th>
                                                    <th>Duration</th> 
                                                    <th>Not Included</th>                    
                                                    <th>Add-Ons</th>                    
                                                    
                                                    <th>Speciality</th>
                                                    <th>Cost</th>
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
                            <label for="package_name" class="control-label mb-10">Package Name <span class="text-danger"></span></label>
                            <input type="text" parsley-trigger="change" id="package_name" name="package_name"  class="form-control" >
                        </div>
                        <div class="form-group">
                            <label for="package_destination" class="control-label mb-10">Destination <span class="text-danger"></span></label>
                            <input type="text" parsley-trigger="change" id="package_destination" name="package_destination"  class="form-control" >
                        </div>

                         <div class="form-group">
                            
                            <label for="upload-video">Upload Image (jpeg, png, jpg) <span class="text-danger"></span></label>
                            <img id="imagePreview" src="" alt="Image Preview" style="max-width: 50%; margin-bottom: 10px; display: none;" />
                            <input
                                    id="package_img"
                                    name="package_img"
                                    type="file"
                                    class="block w-full rounded-md border-0 px-1.5 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    
                                />
                                <!-- <div class="form-group">
                            <label for="package_description" class="control-label mb-10">Package Image <span class="text-danger">*</span></label>
                            <img src="<?php echo base_url() . $get_package_details['package_image'];
                            
                            ?>" alt="Package Image">

                        </div> -->
                                <!-- <?php if(isset($get_package_details['package_image'])){ ?> -->
                                
                                     <!-- <?php } ?>                    -->
                        <div class="form-group">
                            <label for="package_description" class="control-label mb-10">Package Description <span class="text-danger"></span></label>
                            <textarea type="text" parsley-trigger="change" id="package_description" name="package_description"  class="form-control" ></textarea>
                        </div>

                        <div class="form-group">
                            <label for="package_speciality" class="control-label mb-10">Speciality <span class="text-danger"></span></label>
                            <input type="text" parsley-trigger="change" id="package_speciality" name="package_speciality"  class="form-control" >
                        </div>
                        
                        <div class="form-group">
                            <label for="package_currency" class="control-label mb-10">Currency <span class="text-danger"></span></label>
                            <input type="text" parsley-trigger="change" id="package_currency" name="package_currency"  class="form-control" >
                        </div>

                        

                <fieldset>
                        <legend>Duration</legend>

                        <div class="form-group">
                            <label for="package_days" class="control-label mb-10">Days</label>
                            <input type="text" parsley-trigger="change" id="package_days" name="package_days"  class="form-control" >
                            
                        </div>

                        <div class="form-group">
                            <label for="package_weeks" class="control-label mb-10">Weeks</label>
                            <input type="text" parsley-trigger="change" id="package_weeks" name="package_weeks"  class="form-control" >
                            
                        </div>

                        <div class="form-group">
                            <label for="package_months" class="control-label mb-10">Months</label>
                            <select id="package_months" name="package_months" class="form-control" parsley-trigger="change">
                                <option value="">Select Months</option>
                                <option value="1">1 Month</option>
                                <option value="2">2 Months</option>
                                <option value="3">3 Months</option>
                                <option value="4">4 Months</option>
                                <option value="5">5 Months</option>
                                <option value="6">6 Months</option>
                                <option value="7">7 Months</option>
                                <option value="8">8 Months</option>
                                <option value="9">9 Months</option>
                                <option value="10">10 Months</option>
                                <option value="11">11 Months</option>
                                <option value="12">12 Months</option>
                                </select>
                                </div>
                                </fieldset>


                        <div class="form-group">
                            <label for="not_included" class="control-label mb-10">What's Not Included <span class="text-danger"></span></label><br>
                            <textarea type="text" parsley-trigger="change" id="not_included" name="not_included"  class="form-control" ></textarea>
                        </div>    

                        <div class="form-group">
                        <label for="add_on" class="control-label mb-10">Add-Ons <span class="text-danger"></span></label><br>
                        <textarea type="text" parsley-trigger="change" id="add_on" name="add_on"  class="form-control" ></textarea>
                             
                           
                        </div>


                        <div class="form-group">
                            <label for="package_price" class="control-label mb-10">Package Price <span class="text-danger"></span></label>
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
  
        
