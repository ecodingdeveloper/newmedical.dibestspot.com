

<div class="content">
				<div class="container-fluid">


	            <div class="row">

	            		<div class="col-xl-6 col-lg-12 order-md-last order-sm-last order-last map-left">

						<div class="row align-items-center mb-4">
							<div class="col-md-6 col">
								<h4>2245 Doctors found</h4>
							</div>

							<div class="col-md-6 col-auto">
								<div class="view-icons ">
									<a href="#" class="grid-view active"><i class="fas fa-th-large"></i></a>
									<a href="#" class="list-view"><i class="fas fa-bars"></i></a>
								</div>
								<div class="sort-by d-sm-block d-none">
									<span class="sortby-fliter">
										<select class="select form-control" id="orderby" onchange="search_doctor(0)">
											<option>Sort by</option>
											<option value=""><?php 
									/** @var array $language  */
											echo $language['lg_select'];?></option>
											<option class="sorting" value="Rating"><?php echo $language['lg_rating'];?></option>
											<option class="sorting" value="Popular"><?php echo $language['lg_popular'];?></option>
											<option class="sorting" value="Latest"><?php echo $language['lg_latest'];?></option>
											<option class="sorting" value="Free"><?php echo $language['lg_free'];?></option>
										</select>
									</span>
								</div>
							</div>
						</div>	
						
				
						
							<input type="hidden" name="page" id="page_no_hidden" value="1" >

							<div id="doctor-list"></div>

							<div class="load-more text-center d-none" id="load_more_btn">
								<a class="btn btn-primary btn-sm" href="javascript:void(0);"><?php echo $language['lg_load_more'];?></a>	
							</div>
						<!-- Doctor Widget -->
						
	            </div>
	            <!-- /content-left-->
	           <div class="col-xl-6 col-lg-12 map-right">
	                <div id="map" class="map-listing"></div>
	                <!-- map-->
	            </div>
	            <!-- /map-right-->
	        </div>
	        <!-- /row-->
	   
				</div>

			</div>		

			<script type="text/javascript">
				var country='';
			    var state='<?php
				/** @var string $state  */
				 echo $state;?>';
			    var city='<?php 
			    /** @var string $city  */
			    echo $city;?>';
			    var specialization='';
			</script>
			<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo !empty(settings("google_map_api"))?settings("google_map_api"):''; ?>"></script>