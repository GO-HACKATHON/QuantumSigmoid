<?php $popup_message = $this->session->userdata('popup_message'); ?>
<?php if($popup_message) { ?>
	<?php foreach($popup_message as $m) { ?>
		noty({
			text: '<?php echo $m; ?>',
			layout: 'center',
			type: 'information',
			timeout: 2000
		});
	<?php } ?>
	
<?php } ?>
<?php // $this->session->set_userdata('message', null); ?>