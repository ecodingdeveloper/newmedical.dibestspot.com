<!-- Search -->
							<div class="card search-widget">
								<div class="card-body">
									<form class="search-form" method="post" action="<?php echo base_url();?>blog">
										<div class="input-group">
											<input type="text" placeholder="<?php 
                                            /** @var array $language */
											echo $language['lg_search6'];?>" name="keywords" class="form-control" required >
											<div class="input-group-append">
												<button type="submit" id="search_blog" class="btn btn-primary"><i class="fa fa-search"></i></button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<!-- /Search -->

							<!-- Latest Posts -->
							<div class="card post-widget">
								<div class="card-header">
									<h4 class="card-title"><?php echo $language['lg_latest_posts'];?></h4>
								</div>
								<div class="card-body">
									<ul class="latest-posts">
										<?php if(!empty(latest_posts())){
											foreach (latest_posts() as $lrows) {
												$image_url=explode(',', $lrows['upload_image_url']);
												echo'<li>
													<div class="post-thumb">
														<a href="'.base_url().'blog/blog-details/'.$lrows['slug'].'">
															<img class="img-fluid" src="'.base_url().$image_url[0].'" alt="">
														</a>
													</div>
													<div class="post-info">
														<h4>
															<a href="'.base_url().'blog/blog-details/'.$lrows['slug'].'">'.$lrows['title'].'</a>
														</h4>
														<p>'.date('d M Y',strtotime($lrows['created_date'])).'</p>
													</div>
												</li>';
											}
										}
										
										?>
									</ul>
								</div>
							</div>
							<!-- /Latest Posts -->

							<!-- Categories -->
							<div class="card category-widget">
								<div class="card-header">
									<h4 class="card-title"><?php echo $language['lg_blog_categories'];?></h4>
								</div>
								<div class="card-body">
									<ul class="categories">
										<?php if(!empty(categories())){
											foreach (categories() as $crows) {
												echo'<li><a href="'.base_url().'blog?category='.$crows['slug'].'">'.$crows['category_name'].' <span>('.$crows['count'].')</span></a></li>';
										     } 

										   } ?>		
									</ul>
								</div>
							</div>
							<!-- /Categories -->

							<!-- Tags -->
							<div class="card tags-widget">
								<div class="card-header">
									<h4 class="card-title"><?php echo $language['lg_tags'];?></h4>
								</div>
								<div class="card-body">
									<ul class="tags">
										<?php if(!empty(tags())){
											foreach (tags() as $trows) {
												if(!empty($trows['tag']))
												echo'<li><a href="'.base_url().'blog?tags='.$trows['slug'].'" class="tag">'.$trows['tag'].'</a></li>';
										     } 

										   } ?>		
									</ul>
								</div>
							</div>
							<!-- /Tags -->