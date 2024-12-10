<?php if(!empty($replies)){
	foreach ($replies as $rows) {
?>		
<li>
	<div class="comment">
		<div class="comment-author">
			<img class="avatar" alt="" src="<?php echo base_url().$rows['profileimage'];?>">
		</div>
		<div class="comment-block">
			<span class="comment-by">
				<span class="blog-author-name"><?php echo ucfirst($rows['name']);?></span>
			</span>
			<p><?php echo $rows['replies'];?></p>
			<p class="blog-date"><?php echo date('d M Y',strtotime($rows['created_date']));?></p>
			<?php if (auth_check()) {  if ($this->session->userdata('user_id')==$rows['user_id']) { /** @phpstan-ignore-line */ ?>
			<a class="comment-btn text-danger" onclick="return delete_comment_reply('<?php echo $rows['id'];?>','<?php echo $rows['comment_id'];?>','2');" href="javascript:void(0);">
				<i class="fas fa-trash"></i> <?php 
                  /** @var array $language */
				echo $language['lg_delete'];?>
			</a>
		<?php } } ?>
		</div>
	</div>
</li>
<?php  } } ?>