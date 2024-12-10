<?php

//	$obj = json_decode(decrypt($list['shipping']));
//	$order = (array) json_decode(decrypt($list['order_desc']));
	$user_currency=get_user_currency();
    $user_currency_code=$user_currency['user_currency_code'];
    $user_currency_rate=$user_currency['user_currency_rate'];

    $currency_option = (!empty($user_currency_code))?$user_currency_code:'$';
    $rate_symbol = currency_code_sign($currency_option);

?>
<style>
    .info{
        padding: 0% 8% !important;
    }
</style>
<!-- Page Content -->
<div class="page-wrapper">
	<div class="content container-fluid">
		<!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-7 col-auto">
                    <h3 class="page-title">Order Details</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Order Details</li>
                    </ul>
                </div>
                <div class="col-sm-5 col">
                </div>
            </div>
        </div>
        <!-- /Page Header -->
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
				    <div class="title">Order Details</div>
				    <div class="info">
				        <div class="row">
				            <div class="col-7"> <span id="heading">Date</span><br> <span id="details"><?php 
                            if(!empty($list[0]['created_at'])){
                                echo $list[0]['created_at'];
                            }else{

                            }

                            ?></span> </div>
				            <div class="col-5 pull-right"> <span id="heading">Order No.</span><br> <span id="details"><?php 
                            if(!empty($list[0]['order_id'])){
                              echo $list[0]['order_id'];

                            }else{}
                    ?></span> </div>
				        </div>
				    </div>
				    <div class="info">
				        <div class="row">
				            <div class="col-7"> <span id="heading">Email</span><br> <span id="details"><?php
                            if(!empty($list[0]['email'])){
                              echo $list[0]['email'];

                            }else{}
                        ?></span> </div>
				            <div class="col-5 pull-right"> <span id="heading">Mobile</span><br> <span id="details"><?php 
                             if(!empty($list[0]['phoneno'])){
                              echo $list[0]['phoneno'];

                            }else{}?></span> </div>
				        </div>
				    </div>
                    <?php
                    $total= 0;
                    if(!empty($list)){
                     foreach ($list as $key ): 
                        # code...
                     ?>
				    <div class="pricing">
				        <div class="row">
				            <div class="col-6">
				            	<div class="doctor-img1">
									<a href="javascript:void(0);">
										<img src="<?php echo base_url().$key['product_image'];?>" style="max-width: 50%;" class="img-fluid" alt="<?php echo $key['product_name'];?>">
									</a>
								</div>
				            </div>
				            <div class="col-6"><span id="heading">Shipping:</span><br> 
				            	<span id="details">
                                <?php echo $key['full_name'].',';?> 
				            		<?php echo $key['address1'].',';?> <br>
				            		<?php echo $key['address2'].',';?> <br>
				            		<?php if($key['cityname'] !='') echo $key['cityname'].' - ';?> <?php echo $key['postal_code'].',';?> <br>
				            		<?php if($key['statename'] !='') echo $key['statename'].' - ';?> <?php if($key['countryname'] !='') echo $key['countryname'];?>.<br>
				            	</span>
				            </div>
				        </div>
				    </div>
                   
				    <div class="pricing">
				        <div class="row">
				        	<div class="col-6"><h3><?php echo $key['product_name'];?></h3></div>
				        	<div class="col-3">Sub Total - <big><?php echo $key['qty'].' * '.$rate_symbol.$key['subtotal'];?></big></div>
				            <div class="col-3"><big><?php echo $rate_symbol.$key['subtotal'];?></big></div>
				        </div>
				    </div>
                    <?php
                $total += $key['subtotal'];
                 endforeach; }else{}?>
				    <div class="total">
				        <div class="row">
				        	<div class="col-6"></div>
				        	<div class="col-3"><b>Total</b></div>
				            <div class="col-3"><big><?php echo $rate_symbol.$total;?></big></div>
				        </div>
				    </div>
				    <div class="info">
				        <div class="row">
				            <div class="col-7"> <span id="heading">Payment Type</span><br> <span id="details"><?php 
                            if(!empty($list[0]['payment_type'])){
                              echo $list[0]['payment_type'];

                            }else{} ?></span> </div>
				            <div class="col-5 pull-right"> <span id="heading">Payment Status</span><br> <span id="details"><?php 
                            if(!empty($list[0]['order_status'])){
                              echo $list[0]['order_status'];

                            }else{}
                            ?></span> </div>
				        </div>
				    </div>
				    <div class="tracking">
				        <div class="title">Tracking Order</div>
				    </div>
				    <div class="progress-track">
				        <ul id="progressbar">
				            <li class="step0 active" id="step1">Order Placed <br><p>(Your order has been placed successfully.)</p></li>
				            <?php $acc = ''; if(!empty($order['accepted'])) $acc = 'active'; ?>
				            <li class="step0 <?php echo $acc; ?> text-center" id="step2">Accepted 
				            	<?php if(!empty($order['accepted'])) {?>
				             		<br><p>(<?php echo $order['accepted'];?>. )</p>
				             	<?php }?>
				         	</li>
				         	<?php $ship = ''; if(!empty($order['shipped'])) $ship = 'active'; ?>
				            <li class="step0 <?php echo $ship; ?> text-right" id="step3">On the way
				            	<?php if(!empty($order['shipped'])) {?>
				             		<br><p>(<?php echo $order['shipped'];?>. )</p>
				             	<?php }?>
				            </li>
				            <?php $finish = ''; if(!empty($order['completed'])) $finish = 'active'; ?>
				            <li class="step0 text-right" id="step4">Delivered
				            	<?php if(!empty($order['completed'])) {?>
				             		<br><p>(<?php echo $order['completed'];?>. )</p>
				             	<?php }?>
				            </li>
				        </ul>
				    </div>
				</div>
			</div>
		</div>
	</div>
</div>

<style type="text/css">

.title {
    color: rgb(252, 103, 49);
    font-weight: 600;
    margin-bottom: 2vh;
    padding: 0 8%;
    font-size: initial
}

#details {
    font-weight: 400
}

.info {
    padding: 5% 8%
}

.info .col-5 {
    padding: 0
}

#heading {
    color: grey;
    line-height: 6vh
}

.pricing {
    background-color: #ddd3;
    padding: 2vh 8%;
    font-weight: 400;
    line-height: 2.5
}

.pricing .col-3 {
    padding: 0
}

.total {
    padding: 2vh 8%;
    color: rgb(252, 103, 49);
    font-weight: bold
}

.total .col-3 {
    padding: 0
}

.footer {
    padding: 0 8%;
    font-size: x-small;
    color: black
}

.footer img {
    height: 5vh;
    opacity: 0.2
}

.footer a {
    color: rgb(252, 103, 49)
}

.footer .col-10,
.col-2 {
    display: flex;
    padding: 3vh 0 0;
    align-items: center
}

.footer .row {
    margin: 0
}

#progressbar {
    margin-bottom: 3vh;
    overflow: hidden;
    color: rgb(252, 103, 49);
    padding-left: 0px;
    margin-top: 3vh
}

#progressbar li {
    list-style-type: none;
    font-size: x-small;
    width: 25%;
    float: left;
    position: relative;
    font-weight: 400;
    color: rgb(160, 159, 159)
}

#progressbar #step1:before {
    content: "";
    color: rgb(252, 103, 49);
    width: 5px;
    height: 5px;
    margin-left: 0px !important
}

#progressbar #step2:before {
    content: "";
    color: #fff;
    width: 5px;
    height: 5px;
    margin-left: 32%
}

#progressbar #step3:before {
    content: "";
    color: #fff;
    width: 5px;
    height: 5px;
    margin-right: 32%
}

#progressbar #step4:before {
    content: "";
    color: #fff;
    width: 5px;
    height: 5px;
    margin-right: 0px !important
}

#progressbar li:before {
    line-height: 29px;
    display: block;
    font-size: 12px;
    background: #ddd;
    border-radius: 50%;
    margin: auto;
    z-index: -1;
    margin-bottom: 1vh
}

#progressbar li:after {
    content: '';
    height: 2px;
    background: #ddd;
    position: absolute;
    left: 0%;
    right: 0%;
    margin-bottom: 2vh;
    top: 1px;
    z-index: 1
}

.progress-track {
    padding: 0 8%
}

#progressbar li:nth-child(2):after {
    margin-right: auto
}

#progressbar li:nth-child(1):after {
    margin: auto
}

#progressbar li:nth-child(3):after {
    float: left;
    width: 68%
}

#progressbar li:nth-child(4):after {
    margin-left: auto;
    width: 132%
}

#progressbar li.active {
    color: black
}

#progressbar li.active:before,
#progressbar li.active:after {
    background: rgb(252, 103, 49)
}
</style>