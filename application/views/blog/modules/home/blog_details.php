<!-- Breadcrumb -->
			<div class="breadcrumb-bar">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-md-12 col-12">
							<nav aria-label="breadcrumb" class="page-breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>"><?php 
                                     /** @var array $language */
									echo $language['lg_home'];?></a></li>
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_blog2'];?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_blog_details'];?></h2>
						</div>
					</div>
				</div>
			</div>
			<!-- /Breadcrumb -->
			
			<!-- Page Content -->
			<div class="content">
				<div class="container">
				
					<div class="row">
						<div class="col-lg-8 col-md-12">
							<div class="blog-view">
								<div class="blog blog-single-post">
									<div class="blog-image">
										<?php
										/** @var array $posts */
										$image_url=explode(',', $posts['upload_image_url']);
										if(file_exists(($image_url[0]))) {
											$postimage = base_url().$image_url[0];
										} else {
											$postimage = base_url().'assets/img/no_blog_image.png';
										}
										?>
										<a href="javascript:void(0);"><img alt="" src="<?php echo $postimage;?>" class="img-fluid"></a>
									</div>
									<h3 class="blog-title"><?php echo $posts['title'];?></h3>
									<div class="blog-info clearfix">
										<div class="post-left">
											<ul>
												<li>
													<div class="post-author">
													<?php
														if(file_exists(($posts['profileimage']))) {
															$post_image = $posts['profileimage'];
														} else {
															$post_image = 'assets/img/user.png';
														}
													?>
														<a href="<?php echo ($posts['post_by']=='Admin')?'javascript:void(0);':base_url().'doctor-preview/'.$posts['username'];?>"><img src="<?php echo base_url().$post_image;?>" alt="Post Author"> <span><?php echo ($posts['post_by']=='Admin')?ucfirst($posts['name']):$language['lg_dr'].' '.ucfirst($posts['name']);?></span></a>
													</div>
												</li>
												<li><i class="far fa-calendar"></i><?php echo date('d M Y',strtotime($posts['created_date']));?></li>
												<li><i class="far fa-comments"></i><span class="comments_count">0</span> <?php echo $language['lg_comments'];?></li>
												<li><i class="fa fa-tags"></i><?php echo $posts['subcategory_name'];?></li>
											</ul>
										</div>
									</div>
									<div class="blog-content" style="word-wrap: break-word">
										 <?php echo $posts['content']; ?>
									</div>
								</div>
								
								<div class="card blog-share clearfix">
									<div class="card-header">
										<h4 class="card-title"><?php echo $language['lg_share_the_post'];?></h4>
									</div>
									<div class="card-body">
										<ul class="social-share">
											<li><a href="javascript:void(0)" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=<?php echo base_url(); ?>blog-details/<?php echo html_escape($posts['slug']); ?>', 'Share This Post', 'width=640,height=450');return false" title="Facebook"><i class="fab fa-facebook"></i></a></li>
											<li><a href="javascript:void(0)" onclick="window.open('https://twitter.com/share?url=<?php echo urldecode(base_url().'blog-details/'.$posts['slug']); ?>&amp;text=<?php echo html_escape($posts['slug']); ?>', 'Share This Post', 'width=640,height=450');return false" title="Twitter"><i class="fab fa-twitter"></i></a></li>
											<li><a href="javascript:void(0)" onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo urldecode(base_url().'blog-details/'.$posts['slug']); ?>', 'Share This Post', 'width=640,height=450');return false" title="Linkedin"><i class="fab fa-linkedin"></i></a></li>
											<li><a href="javascript:void(0)" onclick="window.open('https://plus.google.com/share?url=<?php echo urldecode(base_url().'blog-details/'.$posts['slug']); ?>', 'Share This Post', 'width=640,height=450');return false" title="Google Plus"><i class="fab fa-google-plus"></i></a></li>
											<li><a href="javascript:void(0)" onclick="window.open('http://pinterest.com/pin/create/button/?url=<?php echo urldecode(base_url().'blog-details/'.$posts['slug']); ?>&amp;media=<?php echo base_url().html_escape($image_url[0]); ?>', 'Share This Post', 'width=640,height=450');return false" title="Pinterest"><i class="fab fa-pinterest"></i></a></li>
										</ul>
									</div>
								</div>
								<div class="card author-widget clearfix">
								<div class="card-header">
									<h4 class="card-title"><?php echo $language['lg_about_author'];?></h4>
									</div>
								<div class="card-body">
									<div class="about-author">
										<div class="about-author-img">
											<div class="author-img-wrap">
												<a href="<?php echo ($posts['post_by']=='Admin')?'javascript:void(0);':base_url().'doctor-preview/'.$posts['username'];?>"><img class="img-fluid rounded-circle" alt="" src="<?php echo base_url().$post_image;?>"></a>
											</div>
										</div>
										<div class="author-details">
											<a href="<?php echo ($posts['post_by']=='Admin')?'javascript:void(0);':base_url().'doctor-preview/'.$posts['username'];?>" class="blog-author-name"><?php echo ($posts['post_by']=='Admin')?ucfirst($posts['name']):$language['lg_dr'].' '.ucfirst($posts['name']);?></a>
											<p class="mb-0"><?php echo $posts['about_author'];?></p>
										</div>
									</div>
								</div>
								</div>
								<div class="card blog-comments clearfix">
									<div class="card-header">
										<h4 class="card-title"><?php echo $language['lg_comments'];?> (<span class="comments_count">0</span>)</h4>
									</div>
									<div class="card-body pb-0">
									<ul class="comments-list" id="comments_list">
									</ul>
									<div class="row">
								<div class="load-more text-center d-none" id="load_more_btn">
									<input type="hidden" name="page" id="page_no_hidden" value="1" >
								<a class="btn btn-primary btn-sm" href="javascript:void(0);"><?php echo $language['lg_load_more'];?></a>	
							   </div>
							</div>
								</div>
								</div>
								
								<div class="card new-comment clearfix">
									<div class="card-header">
										<h4 class="card-title"><?php echo $language['lg_leave_comment'];?></h4>
									</div>
									<div class="card-body">
										<form method="post" action="javascript:void(0);" id="add_comments">
											<div class="form-group">
												<label><?php echo $language['lg_comments'];?></label>
												<input type="hidden" id="post_id" name="post_id" value="<?php echo $posts['id'];?>">
												<textarea rows="4" name="comments" id="comments" class="form-control"></textarea>
											</div>
											<div class="submit-section">
												<button class="btn btn-primary submit-btn" id="comments_btn" type="submit"><?php echo $language['lg_submit'];?></button>
											</div>
										</form>
									</div>
								</div>
								
							</div>
						</div>
					
						<!-- Blog Sidebar -->
						<div class="col-lg-4 col-md-12 sidebar-right theiaStickySidebar">

						<?php $this->load->view('web/includes/blog_sidebar'); /** @phpstan-ignore-line */?>
							
						</div>
						<!-- /Blog Sidebar -->
						
                </div>
				</div>

			</div>		
			<!-- /Page Content -->