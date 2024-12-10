<?php

	// echo '<pre>';
	// print_r($this->data);	
	// echo '</pre>';
	// die("dsdsdsdsdsdsdsdd");

?>

<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<!-- Page Header -->
			<div class="page-header mb-3" hidden>
				<div class="row-header">
					<div class="col-sm-12">
						<h3 class="page-title">Chat</h3>
						<ul class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>admin/dashboard">Dashboard</a></li>
							<li class="breadcrumb-item active">Chat</li>
						</ul>
					</div>
				</div>
			</div>
			<!-- /Page Header -->
			<div class="col-xl-12">
				<div class="chat-window">

					<!-- Chat Left -->
					<div class="chat-cont-left">
						<div class="chat-header">
							<span>Chats</span>	
							<a hidden href="javascript:void(0)" class="chat-compose">
                                <i class="material-icons"></i>back
                            </a>						
						</div>
						<form class="chat-search">
							<div class="input-group">
								<div class="input-group-prepend">
									<i class="fas fa-search"></i>
								</div>
								<input type="text" onkeyup="search_user()" id="search_users" class="form-control rounded-pill" placeholder="Search">
							</div>
						</form>
						<input type="hidden" name="" id="user_selected_id" value="">
						<div class="chat-users-list">
							<div class="chat-scroll chat_users">
								
							</div>
						</div>
					</div>
					<!-- /Chat Left -->

					<!-- Chat Right -->
					<div class="chat-cont-right">
						<div class="chat-header">
							<a id="back_user_list" href="javascript:void(0)" class="back-user-list">
								<i class="material-icons">chevron_left</i>
							</a>
							<div class="media d-flex">
								<div class="media-img-wrap flex-shrink-0 chat-img">
									<div class="avatar ">
										
									</div>
								</div>
								<div class="media-body flex-grow-1">
									<div class="user-name user-info openchat"></div>
									<div class="user-status"></div>
								</div>
							</div>
							
						</div>
						<div class="chat-body">
							<div class="chat-scroll slimscrollleft">
								<ul class="list-unstyled chats">
								</ul>
							</div>
						</div>
						<div class="chat-footer" id="chat" onsubmit="return false">
										<form class="input-group" name="chat_form" id="chat_form" enctype="multipart/form-data" method="post" action="<?php echo base_url(); ?>messages/upload_files">
											<div class="input-group-prepend">
												<a class="link attach-icon btn-file btn" href="javascript:void(0)" onclick="$('#user_file').click();">
													<i class="fa fa-paperclip"></i>
												</a>
											</div>
											<input type="text" name="input_message" id="input_message" placeholder="Type Something" class="input-msg-send form-control chat-input" autocomplete="off">
											<input type="hidden" id="recipients" value="username" >
											<input type="hidden" name="receiver_id" id="receiver_id">
											<input type="hidden" name="to_user_id" id="to_user_id">
											<input type="hidden" name="time" id="time" > 
											<input type="hidden" name="admin_role" id="admin_role" > 
											<input type="file" name="userfile" id="user_file" class="d-none" accept="application/pdf, image/*">
											<div class="input-group-append">
												<a class="link btn msg-send-btn chat-send-btn" href="javascript:void(0)" id="chat-send-btn"><i class="fab fa-telegram-plane"></i></a> 
											</div>
										</form>
									</div>
					</div>
					<!-- /Chat Right -->

				</div>
			</div>
		</div>
	</div>
</div>


<?php /* ?>
<!--Page Content -->
			<div class="content">
				<div class="container-fluid">					
					<div class="row">
						<div class="col-xl-12">
							<div class="chat-window">
							
								<!-- Chat Left -->
								<div class="chat-cont-left">
									<div class="chat-header">
										<span>Chats</span>
									</div>
									<form class="chat-search">
										<div class="input-group">
											<div class="input-group-prepend">
												<i class="fas fa-search"></i>
											</div>
											<input type="text" onkeyup="search_user()" id="search_users" class="form-control" placeholder="Search">
										</div>
									</form>
									<input type="hidden" name="" id="user_selected_id" value="">
									<div class="chat-users-list">
										<div class="chat-scroll chat_users">
										
										</div>
									</div>
								</div>
								<!-- /Chat Left -->
							
								<!-- Chat Right -->
								<div class="chat-cont-right">
									<div class="chat-header">
										<a id="back_user_list" href="javascript:void(0)" class="back-user-list">
											<i class="material-icons">chevron_left</i>
										</a>
										<div class="media">
											<div class="media-img-wrap chat-img">
												<div class="avatar  ">
													
												</div>
											</div>
											<div class="media-body">
												<div class="user-name user-info pull-left openchat"></div>
												<div class="user-status"></div>
											</div>
										</div>
										<!-- <div class="chat-options chattrash">
											<a href="javascript:void(0)" onclick="delete_conversation();" title="Delete Chat History">
												<i class="far fa-trash-alt"></i>
											</a>
										</div>  -->
										<div class="progress upload-progress d-none">
											<div class="progress-bar progress-bar-success active progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="100" aria-valuemax="100" style="width: 100%;">
											 Uploading 
											</div>
										</div> 
									</div>
									<div id="chat-box" class="chatbox-message chat-body">
										<div class="chat-scroll slimscrollleft">
											<ul class="list-unstyled chats">

											</ul>
										</div>
									</div>
							
									<div class="chat-footer" id="chat" onsubmit="return false">
										<form class="input-group" name="chat_form" id="chat_form" enctype="multipart/form-data" method="post" action="<?php echo base_url(); ?>messages/upload_files">
											<div class="input-group-prepend">
												<a class="link attach-icon btn-file btn" href="javascript:void(0)" onclick="$('#user_file').click();">
													<i class="fa fa-paperclip"></i>
												</a>
											</div>
											<input type="text" name="input_message" id="input_message" placeholder="Type Something" class="input-msg-send form-control chat-input" autocomplete="off">
											<input type="hidden" id="recipients" value="username" >
											<input type="hidden" name="receiver_id" id="receiver_id">
											<input type="hidden" name="to_user_id" id="to_user_id">
											<input type="hidden" name="time" id="time" > 
											<input type="hidden" name="admin_role" id="admin_role" > 
											<input type="file" name="userfile" id="user_file" class="d-none">
											<div class="input-group-append">
												<a class="link btn msg-send-btn chat-send-btn" href="javascript:void(0)" id="chat-send-btn"><i class="fab fa-telegram-plane"></i></a> 
											</div>
										</form>
									</div>
								</div>
								<!-- /Chat Right -->
								
							</div>
						</div>
					</div>
					<!-- /Row -->

				</div>

			</div>		
			<!-- /Page Content-->
			<?php
			$user_detail=user_detail($this->session->userdata('user_id'));
            $user_profile_image=(!empty($user_detail['profileimage']))?base_url().$user_detail['profileimage']:base_url().'assets/img/user.png';
			?>
			<input type="hidden" name="img" id="img" value="<?php echo $user_profile_image; ?>">

			<?php   */   ?>