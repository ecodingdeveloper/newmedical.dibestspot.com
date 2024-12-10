		
<!-- Breadcrumb -->
			<div class="breadcrumb-bar">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-md-12 col-12">
							<nav aria-label="breadcrumb" class="page-breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
									<li class="breadcrumb-item active" aria-current="page">Lab tests</li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title">Lab tests</h2>
						</div>
					</div>
				</div>
			</div>
			<!-- /Breadcrumb -->

		<!-- Page Wrapper -->
            <div class="content">
                <div class=" container-fluid">
					<div class="container">
					<div id="group1">
                        <div class="row">
                            <div class="col-md-4">
                                 <div class="form-group">
                                    <label for="slug" class="control-label mb-10">Lab test booking date <span class="text-danger">*</span> </label>
                                    <input type="text" parsley-trigger="change" id="lab_test_book_date" min="<?php echo date('Y-m-d'); ?>" name="lab_test_book_date"  class="form-control" >
                                </div>
                            </div>
                        </div>
						<div class="row plan-card" id="div1">
							<!--Card1 -->
                                                    <input type="hidden" value="<?php echo date('Y-m-d', strtotime('+2 months')); ?>" id="maxDate">   
                                                    <input type="hidden" id="lab_id" value="<?php 
                                                    /** @var array $lab_details  */
                                                    echo $lab_details['user_id']; ?>">
                                                    <input type="hidden" name="lab_username" id="lab_username" value="<?php echo $lab_details['username']; ?>">
                                                     <?php
                                                        if(isset($lab_tests) && !empty($lab_tests)){
                                                            foreach ($lab_tests as $lab_tests_key => $lab_tests_val) {
                                                                $id = $lab_tests_val['id'];
                                                                $lab_id = $lab_tests_val['lab_id'];
                                                                $lab_test_name = (!empty($lab_tests_val['lab_test_name'])) ? ucfirst($lab_tests_val['lab_test_name']) : '';
                                                                $amount = (!empty($lab_tests_val['amount'])) ? $lab_tests_val['amount'] : '0';


                                                                
                                                                ?>
                                                                <?php 

             

            $user_currency=get_user_currency();
            $user_currency_code=$user_currency['user_currency_code'];
            $user_currency_rate=$user_currency['user_currency_rate'];
            /** @var array $rows  */
            $currency_option = (!empty($user_currency_code))?$user_currency_code:$lab_tests_val['currency_code'];
            $rate_symbol = currency_code_sign($currency_option);

                      if(!empty($this->session->userdata('user_id'))){
                        $rate=get_doccure_currency($lab_tests_val['amount'],$lab_tests_val['currency_code'],$user_currency_code);
                      }else{
                           $rate= $lab_tests_val['amount'];
                        }
            $amount=$rate_symbol.''.$rate;

            

                        ?>

                                    <div class="col-md-4">

                                        <div class="card">
                                            <div  class="card-body img-bg-card">
                                                    <h2 class="card-title text-center"><?php echo $lab_test_name; ?></h2>
                                                    <hr class="hr-text" data-content="&">
                                                    
                                                      <h2 class="text-center"><span class="plan-amount"><?php echo $amount; ?></span></h2>
                                                      <hr class="hr-text" data-content="&">

                                                      <h4 class="text-center">Duration : <span class="plan-amount"><?php echo $lab_tests_val['duration']; ?></span></h2>
                                                     <h4 class="text-center"><span class="plan-amount"><?php echo $lab_tests_val['description'];; ?></span></h2>
                                                    <div class="sub-btn text-center">
                                                        <center><input type="checkbox" id="lab_test_chk<?php echo $id ?>" name="lab_test_chk" class="lab_test_chk" value="<?php echo $id; ?>" data-price="<?php echo $rate; ?>"></center>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                        <?php
                                }
                            }
                        ?>
                                                        
                                                        
						</div>
                                            
                                            <div class="col-md-2">
                                                <div class="submit-section submit-btn-bottom">
                                                <a href="javascript:void(0);" class="btn btn-primary btn-block lab_test_book_btn"><?php
                                                /** @var array $language  */
                                                 echo $language['lg_proceed_to_pay'];?> </a>
                                            </div>
                                            </div>
                                            
                                                        <br/><br/>
					</div>
				</div>			
			</div>
			<!-- /Page Wrapper -->
			<script>
			var min_slider = document.getElementById("min-slider");
			var min_output = document.getElementById("min-output");
		</script>
		<script>
			function show2(){
			  document.getElementById('div2').style.display ='flex';
			   document.getElementById('div1').style.display ='none';
			}
			function show1(){
			  document.getElementById('div1').style.display = 'flex';
			  document.getElementById('div2').style.display = 'none';
			}
		</script>