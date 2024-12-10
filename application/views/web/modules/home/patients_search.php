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
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_patients_search'];?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title search-results"></h2>
						</div>
						<div class="col-md-4 col-12 d-md-block d-none">
							<div class="sort-by">
								<span class="sort-title"><?php echo $language['lg_sort_by'];?></span>
								<span class="sortby-fliter">
									<select class="select form-control" id="orderby" onchange="search_patient(0)">
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
						<div class="col-md-5 col-lg-4 col-xl-3 theiaStickySidebar">
						
							<!-- Search Filter -->
							<div class="card search-filter">
								<div class="card-header">
									<h4 class="card-title mb-0"><?php echo $language['lg_search_filter'];?> <a href="javascript:void(0);" onclick="reset_patient()" class="text-danger text-sm float-right"><?php echo $language['lg_reset'];?></a></h4>
								</div>
								<div class="card-body">
									<form id="search_patient_form">
								<!-- <div class="filter-widget">
									<div class="cal-icon">
										<input type="text" class="form-control filter-form ft-search"  name="right_top_search"  type="text" placeholder="Search by patients name" autocomplete="off" id="search_user">
									</div>			
								</div> -->
								
								
								<div class="filter-widget">
									<select class="form-control" name="gender" id="gender" >
										<option value=""> <?php echo $language['lg_select_gender'];?></option>
										<option value="Male"><?php echo $language['lg_male'];?></option>
										<option value="Female"><?php echo $language['lg_female'];?></option>
									</select>							
								</div>

								<div class="filter-widget">
									<select class="form-control" name="blood_group" id="blood_group" >
										<option value=""> <?php echo $language['lg_select_blood'];?></option>
										<option value="A-"><?php echo $language['lg_a2'];?></option>
										<option value="A+"><?php echo $language['lg_a3'];?></option>
										<option value="B-"><?php echo $language['lg_b6'];?></option>
										<option value="B+"><?php echo $language['lg_b7'];?></option>
										<option value="AB-"><?php echo $language['lg_ab1'];?></option>
										<option value="AB+"><?php echo $language['lg_ab2'];?></option>
										<option value="O-"><?php echo $language['lg_o4'];?></option>
										<option value="O+"><?php echo $language['lg_o5'];?></option>
									</select>							
								</div>
								
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
									<div class="btn-search">
										<button type="button" onclick="search_patient(0)" class="btn btn-block"><?php echo $language['lg_search3'];?></button>
									</div>
									</form>		
								</div>
							</div>
							<!-- /Search Filter -->
							
						</div>
						<div class="col-md-7 col-lg-8 col-xl-9">
						<input type="hidden" name="page" id="page_no_hidden" value="1" >
							<div class="row row-grid" id="patients-list">
							</div>

							<div class="load-more text-center d-none" id="load_more_btn">
									<a class="btn btn-primary btn-sm" href="javascript:void(0);"><?php echo $language['lg_load_more'];?></a>	
								</div>

						</div>
					</div>

				</div>

			</div>		
			<!-- /Page Content -->

			<script type="text/javascript">
				var country='';
			    var state='';
			    var city='';
			    var specialization='';
			</script>