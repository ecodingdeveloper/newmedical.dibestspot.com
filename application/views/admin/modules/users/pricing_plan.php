
            <!-- Page Wrapper -->
            <div class="page-wrapper">
                <div class="content container-fluid">
                
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row">
                            <div class="col-sm-7 col-auto">
                                <h3 class="page-title">Pricing Plan</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Pricing Plan</li>
                                </ul>
                                <!-- <h1>Select Profile and Time Period</h1> -->
                                <?php 
                               $get_doctor_plan = $this->db->select('plan')->from('plan')->where('profile', 'doctor') ->get()->result_array();
                                $get_patient_plan = $this->db->select('plan')->from('plan')->where('profile', 'patient')->get()->result_array();
                                $get_gtc_plan = $this->db->select('plan')->from('plan')->where('profile', 'gtc')->get()->result_array();
                                $get_pharmacy_plan = $this->db->select('plan')->from('plan')->where('profile', 'pharmacy')->get()->result_array();
                                $get_lab_plan = $this->db->select('plan')->from('plan')->where('profile', 'lab')->get()->result_array();
                                $get_clinic_plan = $this->db->select('plan')->from('plan')->where('profile', 'clinic')->get()->result_array();

                                // echo '<pre>';
                                // print_r($get_doctor_plan[0]['plan']);
                                // print_r($get_patient_plan[0]['plan']);
                                // print_r($get_gtc_plan[0]['plan']);
                                // print_r($get_pharmacy_plan[0]['plan']);
                                // print_r($get_lab_plan[0]['plan']);
                                // print_r($get_clinic_plan[0]['plan']);

                                // echo '</pre>';
                                // die("dsfad");
                                ?>
<div class="dropdown-container" style="padding-top: 20px;>
    <label for="profile">Select Profile:</label>
    <select id="profile" class="form-control">
        <option value="doctor">Doctor</option>
        <option value="clinic">Clinic</option>
        <option value="gtc">GTC</option>
        <option value="patient">Patient</option>
        <option value="lab">Lab</option>
        <option value="pharmacy">Pharmacy</option>
    </select>
</div>

<div class="dropdown-container" style="padding-top: 10px;">
    <label for="time-period">Choose Plan:</label>
    <select id="time-period" class="form-control">
        <option value="monthly">Monthly</option>
        <option value="yearly">Yearly</option>
    </select>
</div>
<div class="dropdown-container">
        <button class="btn btn-primary mt-3" onclick="sendDataToDatabase()">Update Plan</button>
    </div>

    <div id="output"></div>
                            </div>
                            
                            <div class="col-sm-5 col">
                                <?php 
                                $get_package_details = $this->db->select('*')->from('packages')->where('status',1)->get()->result_array();/** @phpstan-ignore-line */
                                // echo '<pre>';
                                // print_r($get_package_details);
                                // echo '</pre>';
                                // die("dsfad");
                                ?>
          
              
              
                 <a href="javascript:void(0);" onclick="add_pricing_admin()"  class="btn btn-primary float-right mt-2">Add a feature Plan</a> 
              
            

                                
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->
                    <!-- <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                <div class="choose-profile" style="    border: 3px;
                              border-style: solid;
                              border-color: #e4e3eb;
                              padding: 10px 10px 0px 10px;
                              border-radius: 4px;
                              margin-bottom:10px;
                              padding-bottom:-4px;" >
                      <div class="form-group">
                          <label><b>Choose the profile:</b></label>
                          <select style="background-color: #fafafa;
    color: #141313;" class="form-control" id="profile" name="profile" onchange="showProfileFields()">
                              <option value="">select</option>
                              <option value="1" <?php echo (isset($profile) && $profile === '1') ? 'selected' : ''; ?>>Doctor</option>
                              <option value="2" <?php echo (isset($profile) && $profile === '2') ? 'selected' : ''; ?>>Patient</option>
                              <option value="4" <?php echo (isset($profile) && $profile === '4') ? 'selected' : ''; ?>>Lab</option>
                              <option value="6" <?php echo (isset($profile) && $profile === '6') ? 'selected' : ''; ?>>Clinic</option>
                              <option value="5" <?php echo (isset($profile) && $profile === '5') ? 'selected' : ''; ?>>Pharmacy</option>
                          </select>
                      </div> -->
                                    <div class="table-responsive">
                                        <table id="pricingtable" class="table table-hover table-center mb-0 w-100">
                                            <thead>
                                                <tr>
                                                    <!-- <th>S. No.</th> -->
                                                    <!-- <th>ID</th> -->
                                                    <th>Plan Features</th>
                                                    <th>Basic (Doctors)</th>
                                                    <th>Enterprise (Clinics)</th>
                                                    <th>DIBEST Medical: GTC</th>
                                                    <th>Patient</th>
                                                    <th>Pharmacy</th>
                                                    <th>Lab</th>  
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
                     <form action="#" enctype="multipart/form-data" autocomplete="off" id="admin_pricing_form" method="post"> 


                    <div class="modal-header">
                            <h5 class="modal-title">Add Pricing Plan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>

                    <div class="modal-body">
                        
                       <input type="hidden" value="" name="id"/> 
                       <input type="hidden" value="" name="method"/>
                       <div class="form-group">
                            <label for="plan_features" class="control-label mb-10">Plan Features <span class="text-danger">*</span></label>
                            <input type="text" parsley-trigger="change" id="plan_features" name="plan_features"  class="form-control" >
                        </div>
                        <div class="form-group">
                            <label for="doctor_plan" class="control-label mb-10">Basic (Doctors) <span class="text-danger">*</span></label>
                            <input type="text" parsley-trigger="change" id="doctor_plan" name="doctor_plan"  class="form-control" >
                        </div>

                        <div class="form-group">
                            <label for="clinic_plan" class="control-label mb-10">Enterprise (Clinics) <span class="text-danger">*</span></label>
                            <input type="text" parsley-trigger="change" id="clinic_plan" name="clinic_plan"  class="form-control" ></input>
                        </div>

                        <div class="form-group">
                            <label for="gtc_plan" class="control-label mb-10">DIBEST Medical: GTC <span class="text-danger">*</span></label>
                            <input type="text" parsley-trigger="change" id="gtc_plan" name="gtc_plan"  class="form-control" >
                        </div>
                        <div class="form-group">
                            <label for="patient_plan" class="control-label mb-10">Patient<span class="text-danger">*</span></label>
                            <input type="text" parsley-trigger="change" id="patient_plan" name="patient_plan"  class="form-control" >
                        </div>
                        <div class="form-group">
                            <label for="lab_plan" class="control-label mb-10">Lab <span class="text-danger">*</span></label>
                            <input type="text" parsley-trigger="change" id="lab_plan" name="lab_plan"  class="form-control" >
                        </div>
                        <div class="form-group">
                            <label for="pharmacy_plan" class="control-label mb-10">Pharmacy <span class="text-danger">*</span></label>
                            <input type="text" parsley-trigger="change" id="pharmacy_plan" name="pharmacy_plan"  class="form-control" >
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


    <script>
        
        // Function to handle form submission via AJAX
function sendDataToDatabase() {
    const profile = document.getElementById('profile').value;
    const timePeriod = document.getElementById('time-period').value;
    // console.log(profile,timePeriod);
    // AJAX call to send data to the server
    $.ajax({
        url: '<?php echo base_url(); ?>mywarmembrace/insertPlan', // Server URL
        type: 'POST',
        data: {
            profile: profile,         // Sending selected profile
            time_period: timePeriod   // Sending selected time period
        },
        dataType: 'json',
        success: function(response) {
            // On successful response
            if (response.status === 'success') {
                alert(response.message);
                
            } else {
                alert(response.message); // Show error message from server
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error); // Log error to console
        }
    });
}

function changePlan(){
    const profile = document.getElementById('profile').value;
    const timePeriod = document.getElementById('time-period');

   if(profile=='doctor') {
    let a = <?php echo json_encode($get_doctor_plan); ?>;
    console.log(a[0].plan);
    const dropdown = document.getElementById('time-period');
    dropdown.value = a[0].plan;
   }
   if(profile=='patient') {
    let a = <?php echo json_encode($get_patient_plan); ?>;
    console.log(a[0].plan);
    const dropdown = document.getElementById('time-period');
    dropdown.value = a[0].plan;
   }
   if(profile=='gtc') {
    let a = <?php echo json_encode($get_gtc_plan); ?>;
    console.log(a[0].plan);
    const dropdown = document.getElementById('time-period');
    dropdown.value = a[0].plan;
   }
   if(profile=='pharmacy') {
    let a = <?php echo json_encode($get_pharmacy_plan); ?>;
    console.log(a[0].plan);
    const dropdown = document.getElementById('time-period');
    dropdown.value = a[0].plan;
   }
   if(profile=='lab') {
    let a = <?php echo json_encode($get_lab_plan); ?>;
    console.log(a[0].plan);
    const dropdown = document.getElementById('time-period');
    dropdown.value = a[0].plan;
   }
   if(profile=='clinic') {
    let a = <?php echo json_encode($get_clinic_plan); ?>;
    console.log(a[0].plan);
    const dropdown = document.getElementById('time-period');
    dropdown.value = a[0].plan;
   }
}

profile.addEventListener('change',changePlan);

changePlan();

    </script>
  <!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addAddon').addEventListener('click', function() {
        const addonNameInput = document.getElementById('addonName');
        const addonName = addonNameInput.value.trim(); // Get and trim the input value

        if (addonName) { // Check if the input is not empty
            // Create a new div for the addon
            const addonDiv = document.createElement('div');
            addonDiv.classList.add('addon-item');

            // Create a new checkbox
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.id = 'not_included'; // Use timestamp for unique ID
            checkbox.name = 'not_included[]';
            checkbox.value = addonName;

            // Create a label for the checkbox
            const label = document.createElement('label');
            label.htmlFor = checkbox.id; // Set label for the checkbox
            label.textContent = addonName;

            // Append the checkbox and label to the addon div
            addonDiv.appendChild(checkbox);
            addonDiv.appendChild(label);

            // Append the new addon div to the addons list
            document.getElementById('addonsList').appendChild(addonDiv);

            // Clear the input field after adding
            addonNameInput.value = '';
        } else {
            alert('Please enter a valid add-on name.'); // Alert for empty input
        }
    });
});

</script> -->

