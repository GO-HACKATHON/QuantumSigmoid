<div class="modal-header">
	<h4 class="modal-title">USER <i><?php echo $user->username; ?></i></h4>
</div>
<?php echo form_open('user/update');?>
<?php $this->load->view('user/form'); ?>
<?php echo form_close();?>