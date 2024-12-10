<!-- Breadcrumb -->
			<div class="breadcrumb-bar">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-md-12 col-12">
							<nav aria-label="breadcrumb" class="page-breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard"><?php 
									/** @var array $language */
									echo $language['lg_dashboard'];?></a></li>
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_reviews'];?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_reviews'];?></h2>
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
						
							<!-- Profile Sidebar -->
							<?php $this->load->view('web/includes/patient_sidebar');?>
							<!-- /Profile Sidebar -->
							
						</div>
						<div class="col-md-7 col-lg-8 col-xl-9">
							<div class="doc-review review-listing">
							
								<!-- Review Listing -->
								<ul class="comments-list">

									<?php 

									if(!empty($reviews)){
									foreach ($reviews as $rrows) { 
  										$rimg=(!empty($rrows['profileimage']))?base_url().$rrows['profileimage']:base_url().'assets/img/user.png';

  										$drimg=(!empty($rrows['doctor_image']))?base_url().$rrows['doctor_image']:base_url().'assets/img/user.png';
                                     ?>
								
									<!-- Comment List -->
									<li>
										<div class="comment">
											<img class="avatar rounded-circle" alt="User Image" src="<?php echo $rimg; ?>">
											<div class="comment-body">
												<div class="meta-data">
													<span class="comment-author"><?php echo $rrows['first_name'].' '.$rrows['last_name'];?></span>
													<span class="comment-date"><?php echo $language['lg_reviewed'];?> <?php echo time_elapsed_string($rrows['created_date']);?></span>
													<div class="review-count rating">
														<?php for($i=1; $i<=5 ;$i++) {
														if($i <= $rrows['rating']){
															?>
															<i class="fas fa-star filled"></i>
														<?php }else{ ?>
															<i class="fas fa-star"></i>
														<?php } 
													} ?>
													</div>
												</div>
												
												<p class="comment-content">
													<?php echo $rrows['review'];?>
												</p>
												
												
											</div>
										

										</div>
										
										<ul class="comments-reply">
										
											<!-- Comment Reply List -->
											<?php if ($rrows['reply']) { ?>
											<li>
												<div class="comment">
													<img class="avatar rounded-circle" alt="User Image" src="<?php echo $drimg; ?>">
													<div class="comment-body">
														<div class="meta-data">
															<span class="comment-author"><?php echo $language['lg_dr']; ?> <?php echo $rrows['doctor_firstname'].' '.$rrows['doctor_lastname'];?></span>
															<span class="comment-date"><?php echo $language['lg_replied']; ?> <?php echo time_elapsed_string($rrows['reply_date']);?> </span>
														</div>
														<p class="comment-content">
															<?php echo $rrows['reply'];?>
														</p>
														 
													</div>
													
												</div>

											</li>
											<?php } ?>
											<!-- /Comment Reply List -->
											
										</ul>
										
															
									</li>
									
									<!-- /Comment List -->
									<?php } } else {

										echo'<li>
										<div class="comment">
										<p> '.$language['lg_no_reviews_foun'].'</p>
										</div>
										</li>';
									} ?>
	
									
								</ul>
								<!-- /Comment List -->
								
							</div>
						</div>
					</div>
				</div>

			</div>		
			<!-- /Page Content -->