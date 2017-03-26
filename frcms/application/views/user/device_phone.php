<div class="col-md-13">				
	<div>
		<a title="Add Widget" data-toggle="modal" href="#addWidgetModal" class="btn btn-primary ">Assign Device</a>
	</div>
	<br />

	<div class="panel panel-default">
		<table class="table table-striped">
			<thead>
				<tr>
					
					<th>Brand Name</th>
					<th>Serial Number</th>
					
					<th style="text-align: center;">Phone Number (+62)</th>
					<th style="text-align: center;">Condition</th>
					<th style="text-align: center;">Action</th>
				</tr>
			</thead>
			
				<?php if (isset($location_phone)) {?>
				<?php foreach($location_phone as $val) {?>
				<tr>
					
					<td> <?php echo $val->brand_name;?></td>
					<td> <?php echo $val->serial_number;?></td>
					
					<td style="text-align: center;"> <?php echo $val->phone_number;?></td>
					<td style="text-align: center;"> 
						<?php if ($val->status == "good") { ?>
							<span class="label label-success">Good</span>
						<?php } else { ?>
							<span class="label label-danger">Bad</span>
						<?php }?>
					</td>
					<td style="vertical-align: middle; text-align: center;">
						<div id = "test">
						<p>
							<a class="edit" href="<?php echo $this->config->item('base_url')."/location/delete_phone/".$val->id;?>" onclick="javascript: return confirm('Are you SURE you want to delete these item?')">
								<button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" data-placement="top" rel="tooltip">
									<span class="glyphicon glyphicon-trash"></span>
								</button>
							</a>
						</p>
						</div>
					</td>
				</tr>
					
				<?php } } else { ?>
				<tr>
					<td colspan="7" style="text-align: center;"> <?php echo "NO DEVICE ADDED";?></td>
				</tr>
				<?php }?>
		</table>
	</div>
	
</div>
<!--/col-span-6-->

<div class="modal fade" id="addWidgetModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<a href="#" class="pull-right" data-dismiss="modal"><span class="glyphicon glyphicon-remove-circle pull-right"></span></a>
					<h4 class="modal-title">Add Device</h4>
				</div>
				<div class="modal-body">
					<table class="table table-striped" border="0">
						<thead>
							<tr>
								<th style="text-align: center;">OS Platform</th>
								<th>Brand Name</th>
								<th>Serial Number</th>
								<th>OS Version</th>
								<th style="text-align: center;">Condition</th>
							</tr>
							<?php $uri = $this->uri->segment(3);?>
							<?php if (isset($devices)) { ?>
							<?php foreach($devices as $val) {?>
								<tr>
									<td style="text-align: center;"><a href="<?php echo $this->config->item('base_url')."/location/phone_add/".$val->device_id."_".$uri;?>"><?php echo $val->os_name;?></a></td>
									<td> <?php echo $val->brand_name;?></td>
									<td> <a href="<?php echo $this->config->item('base_url')."/location/phone_add/".$val->device_id."_".$uri;?>"><?php echo $val->serial_number;?></a></td>
									<td> <?php echo $val->os_version;?></td>
									<td style="text-align: center;">
										<?php if ($val->status == "good") { ?>
											<span class="label label-success">Good</span>
										<?php } else { ?>
											<span class="label label-danger">Bad</span>
										<?php }?>
									</td>
									
								</tr>
							<?php } } else { ?>
							<tr>
								<td colspan="5" style="text-align: center;"> <?php echo "NO DEVICE ADDED";?></td>
							</tr>
							<?php }?>
						</thead>
					</table>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dalog -->
	</div>