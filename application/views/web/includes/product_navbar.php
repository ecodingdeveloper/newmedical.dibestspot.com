<!--product navbar-->
			<section class="section shadow-sm" style="background: #fff;margin-top:15px">
				<div class="container-fluid">
					<div class="row justify-content-center">
						<div class="col-12">
							<ul class="navbar-nav main-nav1" style="flex-direction: row;">
								<?php if(!empty(product_categories())) { foreach (product_categories() as $crows) { ?>
							<li class="nav-item dropdown mr-4">
								<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $crows['category_name'];?> </a>
								<div class="dropdown-menu">
									<?php if(!empty(product_subcategories($crows['id']))) { foreach (product_subcategories($crows['id']) as $srows) { ?>
									<a class="dropdown-item" href="<?php echo base_url().'products?subcategory='.$srows['slug'];?>"><?php echo $srows['subcategory_name'];?></a>
									
									<?php } } ?>
								</div>
							</li>
							<?php }  } ?>
						</ul>
						</div>

					</div>
				</div>
			</section>
		  <!--/product navbar-->