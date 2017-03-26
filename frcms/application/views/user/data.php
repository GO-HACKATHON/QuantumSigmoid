<div class="col-md-12">				
	<div style="margin-left: -15px;">
		<a title="Add Widget" data-toggle="modal" href="#addWidgetModal" class="btn btn-primary">Register New User</a>
	</div>
	<br />

	<table id="tbl" class="table table-striped table-bordered" border="0">
		<thead>
			<tr>
				<th style="text-align: center;">No</th>
				<th style="text-align: center;">User Name</th>
				<th style="text-align: center;">Product</th>
				<th style="text-align: center;">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php $no=1;?>
			<?php foreach($user as $val) {?>
			<tr>
				<td style="text-align: center;"> <?php echo $no;?></td>
				<td style="text-align: center;"> <?php echo $val->username;?></td>
				<td style="text-align: center;"> <?php echo $val->product_name;?></td>
				
				<td style="vertical-align: middle; text-align: center;">
					<p>
						<a class="edit" href="<?php echo $this->config->item('base_url')."/user/edit/".$val->id;?>">
							<button class="btn btn-primary btn-xs edit" data-title="Edit" rel="tooltip">
								<span class="glyphicon glyphicon-pencil"></span>
							</button>
						</a>
						<a class="trash" href="#" onclick="$.fn.delete('<?php echo base_url().'user/delete/'. $val->id; ?>');">
						<button class="btn btn-danger btn-xs" data-title="Delete" rel="tooltip">
							<span class="glyphicon glyphicon-trash"></span>
						</button>
						</a>
					</p>
				</td>
			</tr>
				<?php $no++;?>
			<?php }?>
		</tbody>
	</table>
	<?php echo $this->pagination->create_links(); ?>
	<?php $this->load->view('ping_footer');	?>
</div>
<!--/col-span-6-->