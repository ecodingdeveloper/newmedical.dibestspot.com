<!-- Breadcrumb -->
			<div class="breadcrumb-bar">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-md-8 col-12">
							<nav aria-label="breadcrumb" class="page-breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>"><?php 
									 /** @var array $language  */
									echo $language['lg_home'];?></a></li>
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_lab_search'];?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title search-results"></h2>
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
									<h4 class="card-title mb-0"><?php echo $language['lg_search_filter'];?> <a href="javascript:void(0);" onclick="reset_lab()" class="text-danger text-sm float-right"><?php echo $language['lg_reset'];?></a></h4>
								</div>
								<div class="card-body">
									<form id="search_doctor_form">

								<div class="filter-widget">
									<input type="text" class="form-control filter-form ft-search"  name="keywords"  type="text" placeholder="<?php echo $language['lg_keywords'];?>" value="<?php echo !empty($keywords)?$keywords:'';?>" autocomplete="off" id="keywords">
								</div>
								<div class="filter-widget">
									<select name="country" class="form-control " id="country">
				                        <option value=""><?php echo $language['lg_select_country'];?></option>
				                     </select>
		                       </div>
								<div class="filter-widget">
									<select name="state" class="form-control " id="state">
			                        <option value=""><?php echo $language['lg_select_state'];?></option>
			                        </select>
		                       </div>
		                       <div class="filter-widget">
									<select name="city" class="form-control " id="city">
			                        <option value=""><?php echo $language['lg_select_city'];?></option>
			                        </select>
		                       </div>
									<div class="btn-search">
										<button type="button" onclick="search_lab(0)" class="btn btn-block"><?php echo $language['lg_search3'];?></button>
									</div>
									</form>	
								</div>
							</div>
							<!-- /Search Filter -->
							
						</div>
						
						<div class="col-md-12 col-lg-8 col-xl-9">
							<input type="hidden" name="page" id="page_no_hidden" value="1" >
							<div id="doctor-list"></div>

							<div class="load-more text-center d-none" id="load_more_btn">
								<a class="btn btn-primary btn-sm" href="javascript:void(0);"><?php echo $language['lg_load_more'];?></a>	
							</div>

							<!-- Doctor Widget -->
							
							<!-- /Doctor Widget -->

								
						</div>
					</div>

				</div>

			</div>		
			<!-- /Page Content -->

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