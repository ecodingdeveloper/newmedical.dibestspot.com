<!-- Breadcrumb -->
			<div class="breadcrumb-bar">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-md-8 col-12">
							<nav aria-label="breadcrumb" class="page-breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php 
									/** @var array $language  */
									echo base_url();?>"><?php echo $language['lg_home'];?></a></li>
									<li class="breadcrumb-item active" aria-current="page">Pharmacy Search<?php //echo $language['lg_pharmacies_search'];?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title search-results"></h2>
						</div>
						<div class="col-md-4 col-12 d-md-block d-none">
							<div class="sort-by">
								<span class="sort-title"><?php echo $language['lg_sort_by'];?></span>
								<span class="sortby-fliter">
									<select class="form-control" id="orderby" onchange="search_pharmacy(0)">
										<option value=""><?php echo $language['lg_select'];?></option>
										<option class="sorting" value="Latest"><?php echo $language['lg_latest'];?></option>
									</select>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /Breadcrumb -->
			
			<!-- Page Content -->
			<div class="content">
				<div class="container-fluid">  

					<div class="row">
						<div class="col-md-12 col-lg-4 col-xl-3 theiaStickySidebar">
						
							<!-- Search Filter -->
							<div class="card search-filter">
								<div class="card-header">
                                                                        <h4 class="card-title mb-0"><?php echo $language['lg_search_filter'];?> 

                                                                        <a href="javascript:void(0);" onclick="reset_pharmacy()" class="text-danger text-sm float-right"><?php echo $language['lg_reset'];?></a></h4>
									
								</div>
								<div class="card-body">
                                                                    <form id="search_pharmacy_form">
                                                                        
                                                                    
								<div class="filter-widget">
                                                                    <select name="country" class="form-control" id="country">
                                                                        <option value=""><?php echo $language['lg_select_country'];?></option>
                                                                    </select>
                                                                </div>
                                                                <div class="filter-widget">
                                                                    <select name="state" class="form-control" id="state">
                                                                        <option value=""><?php echo $language['lg_select_state'];?></option>
                                                                    </select>
                                                                </div>
                                                                <div class="filter-widget">
                                                                    <select name="city" class="form-control" id="city">
                                                                        <option value=""><?php echo $language['lg_select_city'];?></option>
                                                                    </select>
                                                                </div>
								<div class="filter-widget">
									<h4>Service</h4>
									<div>
										<label class="custom_check">
											<input type="checkbox" name="24hrsopen" id="24hrsopen">
											<span class="checkmark"></span> Open 24 hours <?php //echo $language['lg_home_delivery'] ?>
										</label>
									</div>
									<div>
										<label class="custom_check">
											<input type="checkbox" name="home_delivery" id="home_delivery">
											<span class="checkmark"></span> Home Delivery<?php //echo $language['lg_open_24_hrs']; ?>
										</label>
									</div>
								</div>
								
									<div class="btn-search"> 
                                  <button type="button" onclick="search_pharmacy()" class="btn btn-block"><?php echo $language['lg_search3'];?></button>
                                                                                
									</div>
                                                                        </form>
								</div>
							</div>
							<!-- /Search Filter -->
							
						</div>

						<!-- load more -->
						<div class="col-md-12 col-lg-8 col-xl-9">
							<input type="hidden" name="page" id="page_no_hidden" value="1" >
							<div class="spinner-border text-success text-center" role="status" id="loading"></div>
							<div id="pharmacy-list"></div>

							<div class="load-more text-center d-none" id="load_more_btn">
								<a class="btn btn-primary btn-sm" href="javascript:void(0);"><?php echo $language['lg_load_more'];?></a>	
							</div>								
						</div>
						<!-- load more -->

					</div>

				</div>

			</div>		
			<!-- /Page Content -->

			<script type="text/javascript">
				var country='';
			    var state='';
			    var city='';
			    var specialization='';
			    var country_code='';
			</script>
                        
                        
                        
    <!-- View Pharmacy Profile Modal -->
<div class="modal fade" id="view_pharmacy_profile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Pharmacy Profile<?php //echo $language['lg_pharmacy_profile']; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body view_pharmacy_details">
          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $language['lg_close1']; ?></button>
      </div>
    </div>
  </div>
</div>                    