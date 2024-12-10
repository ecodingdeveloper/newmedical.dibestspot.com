
            
            <!-- Page Wrapper -->
            <div class="page-wrapper">
                <div class="content container-fluid">
                
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row">
                            <div class="col-sm-7 col-auto">
                                <h3 class="page-title">Services</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Services</li>
                                </ul>
                            </div>
                            <div class="col-sm-5 col">
                                <?php 
                                $get_service_details = $this->db->select('*')->from('services')->where('status',1)->get()->result_array();/** @phpstan-ignore-line */
                                // echo '<pre>';
                                // print_r($get_service_details);
                                // echo '</pre>';
                                // die("dsfad");
                                ?>
          
              
              
                 <a href="javascript:void(0);" onclick="add_service_admin()"  class="btn btn-primary float-right mt-2">Add a service</a> 
              
            

                                
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="servicestable" class="table table-hover table-center mb-0 w-100">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Id</th>
                                                    <th>Specialization</th>
                                                    <th>Operation</th>
                                                    
                                                    <th>Doctor</th>
                                                    <th>Clinic</th> 
                                                    <th>Location</th>                    
                                                    <th>City</th>
                                                    <th>Cost</th>
                                                    <!-- <th>Insurance</th> -->
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
                     <form action="#" enctype="multipart/form-data" autocomplete="off" id="admin_service_form" method="post"> 


                    <div class="modal-header">
                            <h5 class="modal-title">Add Service</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>

                    <div class="modal-body">
                        
                       <input type="hidden" value="" name="id"/> 
                       <input type="hidden" value="" name="method"/>
                       <div class="form-group">
                            <label for="specialization_name" class="control-label mb-10">Specialization <span class="text-danger"></span></label>
                            <select class="form-control" name="specialization_list" id="specialization_list">
                                    <option value="">Select Specialization</option>
                                </select>
                        </div>
                        <div class="form-group">
                            <label for="operation" class="control-label mb-10">Operation <span class="text-danger"></span></label>
                            <input type="text" parsley-trigger="change" id="operation" name="operation"  class="form-control" >
                        </div>

                        <div class="form-group">
                            <label for="doctor_list" class="control-label mb-10">Doctor<span class="text-danger"></span></label>
                            <select class="form-control" name="doctor_list" id="doctor_list">
                                    <option value="">Select Doctor</option>
                                </select>
                        </div>

                        <div class="form-group">
                            <label for="service_clinic" class="control-label mb-10">Clinic <span class="text-danger"></span></label>
                            <input type="text" parsley-trigger="change" id="service_clinic" name="service_clinic"  class="form-control" >
                        </div>

                      <div class="form-group">
                        <label>Country</label>
                          <?php $get_country=get_country();
                          //echo $get_country; ?>
                          <select class="form-control" id="country" name="country">
                              <?php foreach($get_country as $row){?>
                                <option value="<?=$row['country'];?>" <?=(!empty($profile['country']) && ($row['country']==$profile['country']))?'selected':'';?>><?=$row['country'];?></option>
                              <?php }?> 
                          </select>
                      </div>

                        <div class="form-group">
                          <label>City</label>
                          
                            <select class="form-control" id="city" name="city">
                          <!-- <?php 
                          
                          foreach($city_of_country as $row){?> -->

                                    <option value=""></option>
                                  <!-- <?php }?> -->
                              </select>
                          </div>

                        <div class="form-group">
                            <label for="service_price" class="control-label mb-10">Cost<span class="text-danger"></span></label>
                            <input type="text" onkeypress="return isNumberKey(event)" parsley-trigger="change" id="service_price" name="service_price"  class="form-control" >
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
        
