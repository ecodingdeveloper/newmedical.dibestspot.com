
<style>



.bg {
  /* The image used */
  background-image: url("assets/img/login-banner-005-a.jpg");

  /* Full height */
  height: 100%;

  /* Center and scale the image nicely */
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;

}
.background-multiply .content {
  background-color: #636363 !important;
  background-blend-mode: multiply;
    }
</style>

<script
    src="https://maps.googleapis.com/maps/api/js?key=<?php echo !empty(settings("google_map_api"))?settings("google_map_api"):''; ?>&callback=locate&libraries=places&v=weekly"
    defer>
</script>

<script>

    let placeSearch;
    let autocomplete;
    const componentForm = {
        //street_number: "short_name",
        //route: "long_name",
        locality: "long_name",
        administrative_area_level_1: "long_name",
        country: "long_name",
        postal_code: "short_name",
    };

    function initAutocomplete() {
        // Create the autocomplete object, restricting the search predictions to
        // geographical location types.
        autocomplete = new google.maps.places.Autocomplete(
                document.getElementById("search_location"),
                {types: ["geocode"]}
        );

         // Set initial restrict to the greater list of countries.
  autocomplete.setComponentRestrictions({
    //country: ["ind"],
  });
        // Avoid paying for data that you don't need by restricting the set of
        // place fields that are returned to just the address components.
        autocomplete.setFields(["address_component"]);
        // When the user selects an address from the drop-down, populate the
        // address fields in the form.
        autocomplete.addListener("place_changed", fillInAddress);
    }

    function fillInAddress() {
        // Get the place details from the autocomplete object.
        const place = autocomplete.getPlace();

        for (const component in componentForm) {
            //console.log(component);
            document.getElementById(component).value = "";
            document.getElementById(component).disabled = false;
        }

        // Get each component of the address from the place details,
        // and then fill-in the corresponding field on the form.
        //console.log(place.address_components);
        for (const component of place.address_components) {
            const addressType = component.types[0];
            //console.log(componentForm[addressType]);
            if (componentForm[addressType]) {
                const val = component[componentForm[addressType]];
                document.getElementById(addressType).value = val;
            }
        }
        var address = $.trim($('#search_location').val());
        get_lat_long(address);
    }

    // Bias the autocomplete object to the user's geographical location,
    // as supplied by the browser's 'navigator.geolocation' object.
    function geolocate() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                const geolocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };
                const circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy,
                });
                autocomplete.setBounds(circle.getBounds());
            });
        }
    }
    function locate() {
        geocoder = new google.maps.Geocoder();
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var currentLatitude = position.coords.latitude;
                var currentLongitude = position.coords.longitude;

                var infoWindowHTML = "Latitude: " + currentLatitude + "<br>Longitude: " + currentLongitude;
                var infoWindow = new google.maps.InfoWindow({map: map, content: infoWindowHTML});
                var currentLocation = {lat: currentLatitude, lng: currentLongitude};
                infoWindow.setPosition(currentLocation);
                var lat = currentLocation.lat;
                var lng = currentLocation.lng;
                //lat = '34.052235';
                //lng = '-118.243683';
                $('#s_lat').val(lat);
                $('#s_long').val(lng);
                var latlng = {lat: parseFloat(lat), lng: parseFloat(lng)};
                //alert(lat + '---' + lng);
                geocoder.geocode({location: latlng}, function (results, status) {
                    var res = (results[0].formatted_address);
                    //console.log(res);
                    var arr = res.split(",");
                    
                    var city =$.trim(arr.slice(-4,-2).pop().toLowerCase());
                    
                    $('#search_location').val(city);
                });

            });
        }
        initAutocomplete();
    }
    function get_lat_long(address)
    {
        //alert(address);
        geocoder = new google.maps.Geocoder();

        geocoder.geocode({'address': address}, function (results, status) {

            if (status == google.maps.GeocoderStatus.OK) {
                var latitude = results[0].geometry.location.lat();
                var longitude = results[0].geometry.location.lng();
                $('#s_lat').val(latitude);
                $('#s_long').val(longitude);
                //alert(latitude);
            }
        });
    }
</script>
       <!-- Home Banner -->
      <section class="section section-search" style="background: #f9f9f9 url('<?php echo !empty(base_url().settings("banner_image"))?base_url().settings("banner_image"):base_url()."assets/img/search-bg.png";?>') no-repeat bottom center; background-size: 100% auto;">
        <div class="container-fluid">
          <div class="banner-wrapper">
            <div class="banner-header text-center">
              <?php  /* ?><h1 hidden><?php echo settings('banner_title');?></h1>
              <p hidden><?php echo settings('banner_sub_title');?></p> <?php */ 
            //
           // echo "sdgadrfhearha";
              ?>
              <h1><?php 
                  /** @var array $language  */
                    echo $language['lg_search_doctor_m'];?></h1>
                    <p><?php echo $language['lg_discover_the_be'];?></p>
            </div>
                         
            <!-- Search -->
            <div class="search-box">
              <form method="post" action="#">
                <div class="form-group search-location">
                  <input type="text" class="form-control" autocomplete="off"  id="search_location" name="location" placeholder="<?php 
                  /** @var array $language  */
                    echo $language['lg_search_location'];?>">
                  <span class="form-text"><?php echo $language['lg_based_on_your_l'];?></span>
                   <div class="location_result"></div>

                </div>
                <div class="form-group search-info">
                  <input type="text" class="form-control" autocomplete="off" onkeyup="search_keyword()" id="search_keywords" name="keywords" placeholder="<?php echo $language['lg_search_doctorsd'];?>">
                  <span class="form-text"><?php echo $language['lg_ex__dental_or_s'];?></span>
                  <div class="keywords_result"></div>
                </div>
                <button type="button" class="btn btn-primary search-btn mt-0" id="search_button"><i class="fas fa-search"></i> <span><?php echo $language['lg_search3'];?></span></button>
              </form>
            </div>
            <!-- /Search -->
           
          </div>
        </div>
      </section>
      <!-- /Home Banner -->
	  
			<section class="section home-tile-section">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-9 m-auto">
							<div class="section-header text-center">
								<h2><?php echo $language['lg_what_are_you_lo'];?>  </h2>
							</div>
							<div class="row">
								<div class="col-lg-4 mb-3">
									<div class="card text-center doctor-book-card">
										<img src="<?php echo base_url();?>assets/img/doctors/doctor-07.jpg" alt="" class="img-fluid">
										<div class="doctor-book-card-content tile-card-content-1">
											<div>
												<h3 class="card-title mb-0"><?php echo $language['lg_visit_a_doctor']; ?></h3>
												<a href="<?php echo base_url();?>doctors-search" class="btn book-btn1 px-3 py-2 mt-3" tabindex="0"><?php echo $language['lg_book_now']; ?></a>
											</div>
										</div>
									</div>
								</div>
                <div class="col-lg-4 mb-3">
                  <div class="card text-center doctor-book-card">
                    <img src="<?php echo base_url();?>assets/img/img-pharmacy1.jpg" alt="" class="img-fluid">
                    <div class="doctor-book-card-content tile-card-content-1">
                      <div>
                        <h3 class="card-title mb-0"><?php echo $language['lg_find_a_pharmacy']; ?></h3>
                         <?php if($this->session->userdata('user_id')) { if(is_doctor() || is_patient()){ ?>
                        <a href="<?php echo base_url();?>doctor-pharmacy-search" class="btn book-btn1 px-3 py-2 mt-3" tabindex="0"><?php echo $language['lg_find_now']; ?></a>
                      <?php } }else {?>
                          <a href="<?php echo base_url();?>signin" class="btn book-btn1 px-3 py-2 mt-3" tabindex="0"><?php echo $language['lg_find_now']; ?></a>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </div>
								<div class="col-lg-4 mb-3">
									<div class="card text-center doctor-book-card">
										<img src="<?php echo base_url();?>assets/img/lab-image.jpg" alt="" class="img-fluid">
										<div class="doctor-book-card-content tile-card-content-1">
											<div>
											<h3 class="card-title mb-0"><?php echo $language['lg_find_a_lab']; ?></h3>
												<a href="<?php echo base_url();?>labs-search" class="btn book-btn1 px-3 py-2 mt-3" tabindex="0"><?php echo $language['lg_find_now']; ?></a>
											</div>
										</div>
									</div>
								</div>

                <!-- <div class="col-lg-4 mb-3">
									<div class="card text-center doctor-book-card">
										<img src="<?php echo base_url();?>assets/img/features/features.png" alt="" class="img-fluid">
										<div class="doctor-book-card-content tile-card-content-1">
											<div>
												<h3 class="card-title mb-0">GTC</h3>
												<a href="#" class="btn book-btn1 px-3 py-2 mt-3" tabindex="0"><?php echo $language['lg_book_now']; ?></a>
											</div>
										</div>
									</div>
								</div> -->

                <div class="col-lg-4 mb-3">
									<div class="card text-center doctor-book-card">
										<img src="<?php echo base_url();?>assets/img/img-01.jpg" width="340" height="227" alt="" class="img-fluid">
										<div class="doctor-book-card-content tile-card-content-1">
											<div>
												<h3 class="card-title mb-0">MyWarmEmbrace</h3>
												<a href="<?php echo base_url();?>mywarmembrace/home" class="btn book-btn1 px-3 py-2 mt-3" tabindex="0"><?php echo "JOIN NOW" ?></a>
											</div>
										</div>
									</div>
								</div>


                <div class="col-lg-4 mb-3">
									<div class="card text-center doctor-book-card">
										<img src="<?php echo base_url();?>assets/img/img-01.jpg" width="340" height="227" alt="" class="img-fluid">
										<div class="doctor-book-card-content tile-card-content-1">
											<div>
												<h3 class="card-title mb-0">Medical Tourism - International solutions</h3>
												<a href="<?php echo base_url();?>/home/package_search" class="btn book-btn1 px-3 py-2 mt-3" tabindex="0"><?php echo "VISIT NOW" ?></a>
											</div>
										</div>
									</div>
								</div>

                <div class="col-lg-4 mb-3">
									<div class="card text-center doctor-book-card">
										<img src="<?php echo base_url();?>assets/img/img-01.jpg" width="340" height="227" alt="" class="img-fluid">
										<div class="doctor-book-card-content tile-card-content-1">
											<div>
												<h3 class="card-title mb-0">GTC: Membership</h3>
												<a href="<?php echo base_url();?>subscription/plans" class="btn book-btn1 px-3 py-2 mt-3" tabindex="0"><?php echo "REGISTER NOW" ?></a>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
			</section>
        
        <?php if(!empty($specialization)) { ?>
      <!-- Clinic and Specialities -->
      <section class="section section-specialities">
        <div class="container-fluid">
          <div class="section-header text-center">
            <?php /* ?><h2 hidden><?php echo settings('specialities_title');?></h2>
            <p class="sub-title" hidden><?php echo settings('specialities_content');?></p> 
            <p class="sub-title"><?php echo $language['lg_specialities_co']; ?></h2><?php */ ?>
            <h2><?php echo $language['lg_specialty_title']; ?><br><?php echo $language['lg_specialty_title1']; ?><br><?php echo $language['lg_specialty_title3']; ?></h2>            
          </div>
          <div class="row justify-content-center">
              <div class="col-md-9">
                  <p><span class="font500"><?php echo $language['lg_specialities_ti']; ?>.</span>&nbsp;<?php echo $language['lg_specialty_conte']; ?></p>                  
              </div>
              <div class="col-md-9">
                  <p><span class="font500"><?php echo $language['lg_specialities_ti1']; ?>:</span>&nbsp;<?php echo $language['lg_specialty_conte1']; ?></p>                  
              </div>
              <div class="col-md-9">
                  <p><span class="font500"><?php echo $language['lg_specialities_ti2']; ?>:</span>&nbsp;<?php echo $language['lg_specialty_conte2']; ?></p>                  
              </div>
              <div class="col-md-9">
                  <p><span class="font500"><?php echo $language['lg_specialities_ti3']; ?>:</span>&nbsp;<?php echo $language['lg_specialty_conte3']; ?></p>                  
              </div>              
          </div>
          <div class="row justify-content-center">
            <div class="col-md-9">
              <!-- Slider -->
              <div class="specialities-slider slider">

                <?php foreach ($specialization as $srows) { ?>
                             
                <!-- Slider Item -->
                <div class="speicality-item text-center">
                  <div class="speicality-img">
                    <img src="<?php echo base_url().$srows['specialization_img'];?>" class="img-fluid" alt="Speciality">
                    <span><i class="fa fa-circle" aria-hidden="true"></i></span>
                  </div>
                  <p><?php echo $srows['specialization'];?></p>
                </div>  
                <!-- /Slider Item -->
                <?php } ?>               
                
              </div>
              <!-- /Slider -->
              
            </div>
          </div>
        </div>   
      </section>   
      <!-- Clinic and Specialities -->
    <?php } ?>
      
     <!-- Popular Section -->
      <section class="section section-doctor">
        <div class="container-fluid">
           <div class="row">
            <div class="col-lg-4">
              <div class="section-header ">
              <?php /* ?><h2 hidden><?php echo settings('doctor_title');?></h2><?php */ ?>
                <h2><?php echo $language['lg_book_our_doctor']; ?></h2>
              </div>
              <div class="about-content">
              <?php /* ?><p hidden><?php echo settings('doctor_content');?></p> <?php */ ?>
                <p><?php echo $language['lg_doctor_content']; ?></p>  
                <p><?php echo $language['lg_doctor_sub_cont']; ?></p>        
                <?php /* ?>  <a href="javascript:;"><?php echo $language['lg_read_more'];?></a> <?php */ ?>
              </div>
            </div>
            <div class="col-lg-8">
              <div class="doctor-slider slider">

                <?php if(!empty($doctors)) {
                  $count=0;
                     foreach ($doctors as $rows) {
                      $count++;
                      echo $count;

                      $profileimage=(!empty($rows['profileimage']))?base_url().$rows['profileimage']:base_url().'assets/img/user.png';

                      $where=array('patient_id' =>$this->session->userdata('user_id'),'doctor_id'=>$rows['user_id']);
                        $favourites='';
                        $is_favourite=$this->db->get_where('favourities',$where)->result_array();
                       if(count($is_favourite) > 0 )
                          {
                            $favourites='fav-btns';
                          }
                 ?>                    
                <!-- Doctor Widget -->
                <div class="profile-widget">
                  <div class="doc-img">
                    <a href="<?php echo base_url();?>doctor-preview/<?php echo $rows['username'];?>">
                      <img width="228" height="152" class="img-fluid" alt="User Image" src="<?php echo $profileimage;?>">
                    </a>
                    <a href="javascript:void(0)" id="favourities_<?php echo $rows['user_id'];?>" onclick="add_favourities('<?php echo $rows['user_id'];?>')" class="fav-btn <?php echo $favourites;?>">
                      <i class="fas fa-heart"></i>
                    </a>
                  </div>
                  <div class="pro-content">
                    <h3 class="title">
                      <a href="<?php echo base_url();?>doctor-preview/<?php echo $rows['username'];?>"><?php echo $language['lg_dr'];?> <?php echo ucfirst($rows['first_name'].' '.$rows['last_name']);?></a> 
                      <i class="fas fa-check-circle verified"></i>
                    </h3>
                    <p class="speciality"><?php echo ucfirst($rows['speciality']);?></p>
                    <div class="rating">
                      <?php
                        $rating_value=$rows['rating_value'];
                        for( $i=1; $i<=5 ; $i++) {
                          if($i <= $rating_value){                                        
                          echo'<i class="fas fa-star filled"></i>';
                          }else { 
                          echo'<i class="fas fa-star"></i>';
                          } 
                        } 
                      ?>
                      <span class="d-inline-block average-rating">(<?php echo $rows['rating_count'];?>)</span>
                    </div>
                    <ul class="available-info"> 

                      <li>
                        <i class="fas fa-map-marker-alt"></i> <?php echo $rows['statename'].' '.$rows['countryname'];?>
                      </li>
                      <li>

                        <?php 

             if($rows['price_type']=='Custom Price'){

            $user_currency=get_user_currency();
            $user_currency_code=$user_currency['user_currency_code'];
            $user_currency_rate=$user_currency['user_currency_rate'];

            $currency_option = (!empty($user_currency_code))?$user_currency_code:$rows['currency_code'];
            $rate_symbol = currency_code_sign($currency_option);

                      if(!empty($this->session->userdata('user_id'))){
                        $rate=get_doccure_currency($rows['amount'],$rows['currency_code'],$user_currency_code);
                      }else{
                           $rate= $rows['amount'];
                        }
            $amount=$rate_symbol.''.$rate;

            }else{

              $amount="Free";
            }

                        ?>

                        <i class="far fa-money-bill-alt"></i> <?php echo $amount;?> 
                       
                      </li>
                    </ul>
                    <div class="row row-sm">
                      <div class="col-6">
                        <a href="<?php echo base_url().'doctor-preview/'.$rows['username'];?>" class="btn view-btn"><?php echo $language['lg_view_profile'];?></a>
                      </div>
                      <div class="col-6">
                        <a href="<?php echo base_url().'book-appoinments/'.$rows['username'];?>" class="btn book-btn"><?php echo $language['lg_book_now'];?></a>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /Doctor Widget -->
              <?php } } ?>
            
              
              
                
              </div>
            </div>
           </div>
        </div>
      </section>
      <!-- /Popular Section -->

       
       <!-- Availabe Features -->
       <section class="section section-features">
        <div class="container-fluid">
           <div class="row">
            <div class="col-md-5 features-img">
              <img src="<?php echo !empty(base_url().settings("feature_image"))?base_url().settings("feature_image"):base_url()."assets/img/features/feature.png";?>" class="img-fluid" alt="Feature">
            </div>
            <div class="col-md-7">
              <div class="section-header">  
                <?php /* ?><h2 class="mt-2" hidden><?php echo settings('feature_title');?></h2>
                <p hidden><?php echo settings('feature_sub_title');?> </p><?php */ ?>
                <h2 class="mt-2"><?php echo $language['lg_available_featu']; ?></h2>                
                <p class="mb-3"><?php echo $language['lg_it_is_a_long_es']; ?></p>
                <ul>               
                   <li class="mb-2"><?php echo $language['lg_available_text1']; ?></li>
                   <li class="mb-2"><?php echo $language['lg_available_text2']; ?></li>
                   <li class="mb-2"><?php echo $language['lg_available_text3']; ?></li>
                   <li><?php echo $language['lg_available_text4']; ?></li>

                </ul>
              </div>  
              <div class="features-slider slider">
                <!-- Slider Item -->
                <div class="feature-item text-center">
                  <img src="<?php echo base_url();?>assets/img/features/feature-01.jpg" class="img-fluid" alt="Feature">
                  <p><?php echo $language['lg_patient_ward'];?></p>
                </div>
                <!-- /Slider Item -->
                
                <!-- Slider Item -->
                <div class="feature-item text-center">
                  <img src="<?php echo base_url();?>assets/img/features/feature-02.jpg" class="img-fluid" alt="Feature">
                  <p><?php echo $language['lg_test_room'];?></p>
                </div>
                <!-- /Slider Item -->
                
                <!-- Slider Item -->
                <div class="feature-item text-center">
                  <img src="<?php echo base_url();?>assets/img/features/feature-03.jpg" class="img-fluid" alt="Feature">
                  <p><?php echo $language['lg_icu'];?></p>
                </div>
                <!-- /Slider Item -->
                
                <!-- Slider Item -->
                <div class="feature-item text-center">
                  <img src="<?php echo base_url();?>assets/img/features/feature-04.jpg" class="img-fluid" alt="Feature">
                  <p><?php echo $language['lg_laboratory'];?></p>
                </div>
                <!-- /Slider Item -->
                
                <!-- Slider Item -->
                <div class="feature-item text-center">
                  <img src="<?php echo base_url();?>assets/img/features/feature-05.jpg" class="img-fluid" alt="Feature">
                  <p><?php echo $language['lg_operation'];?></p>
                </div>
                <!-- /Slider Item -->
                
                <!-- Slider Item -->
                <div class="feature-item text-center">
                  <img src="<?php echo base_url();?>assets/img/features/feature-06.jpg" class="img-fluid" alt="Feature">
                  <p><?php echo $language['lg_medical'];?></p>
                </div>
                <!-- /Slider Item -->
              </div>
            </div>
           </div>
        </div>
      </section>    
      <!-- /Availabe Features -->
      <?php if (!empty($blogs)) { ?>
      
      <!-- Blog Section -->
       <section class="section section-blogs">
        <div class="container-fluid">
        
          <!-- Section Header -->
          <div class="section-header text-center">
            <h2><?php echo $language['lg_blogs_and_news'];?></h2>
            <p class="sub-title"><?php echo $language['lg_blog_content_lo']; ?></p>
          </div>
          <!-- /Section Header -->
          
          <div class="row blog-grid-row">

            <?php foreach ($blogs as $brows) { 
               $image_url=explode(',', $brows['upload_image_url']);
              ?>

            <div class="col-md-6 col-lg-3 col-sm-12">
                          <!-- Blog Post -->
              <div class="blog grid-blog">
                <div class="blog-image">
                  <a href="<?php echo base_url().'blog/blog-details/'.$brows['slug'];?>"><img class="img-fluid" src="<?php echo $image_url[0];?>" alt="Post Image"></a>
                </div>
                <div class="blog-content">
                  <ul class="entry-meta meta-item">
                    <li>
                      <div class="post-author">
                        <a href="<?php echo ($brows['post_by']=='Admin')?'javascript:void(0);':base_url().'doctor-preview/'.$brows['username'] ;?>"><img src="<?php echo (!empty($brows['profileimage']))?base_url().$brows['profileimage']:base_url().'assets/img/user.png';?>" alt="Post Author"> <span><?php echo ($brows['post_by']=='Admin')?ucfirst($brows['name']):$language['lg_dr'].' '.ucfirst($brows['name']);?></span></a>
                      </div>
                    </li>
                    <li><i class="far fa-clock"></i> <?php echo date('d M Y',strtotime($brows['created_date']));?></li>
                  </ul>
                  <h3 class="blog-title"><a href="<?php echo base_url().'blog/blog-details/'.$brows['slug'];?>"><?php echo $brows['title'];?></a></h3>
                  <p class="mb-0"><?php echo character_limiter($brows['description'], 70, '...');?></p>
                </div>
              </div>
              <!-- /Blog Post -->
              
            </div>

            <?php } ?>
          
            
          </div>
          <div class="view-all text-center"> 
            <a href="<?php echo base_url();?>blog" class="btn btn-primary"><?php echo $language['lg_view_all'];?></a>
          </div>
        </div>
      </section>
      <!-- /Blog Section -->   

      <?php }
     