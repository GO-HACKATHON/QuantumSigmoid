<?php $message = $this->session->userdata('message'); ?>
	<?php if(isset($message)) { ?>
		<?php foreach($message as $m) { ?>
			<div class="alert alert-<?php echo $m->type; ?>" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<?php echo $m->message; ?>
			</div>
		<?php } ?>
	<?php } ?>
<?php $this->message->flush(); ?>