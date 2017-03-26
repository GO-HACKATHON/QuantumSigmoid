<?php $this->load->view('generaljs'); ?>
<div class="row">
	<?php $this->load->view('user/data'); ?>
</div>
	
<div class="modal fade" id="addWidgetModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<a href="#" class="pull-right" data-dismiss="modal"><span class="glyphicon glyphicon-remove-circle pull-right"></span></a>
				<h4 class="modal-title">Create User</h4>
			</div>
			<?php echo form_open('user/add');?>
			<?php $this->load->view('user/form'); ?>
			<?php echo form_close();?>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dalog -->
</div>
<!-- /.modal -->


