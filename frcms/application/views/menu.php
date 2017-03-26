<?php 
//$this->session_data = $this->session->userdata('is_logged_in');
//$status = $this->session_data->status;
?>

<?php //if ($status==1) { ?>
<!-- ul class="nav nav-pills nav-stacked">
    <li class="nav-header"></li>
    <li><a href="<?php echo base_url()."reporting"; ?>"><span class="glyphicon glyphicon-list-alt" ></span> Reporting </a></li>
    <li style="padding-left: 25px; font-size: x-small; margin-top: -5px;"><a href="<?php echo base_url()."statistic/brand_activity"; ?>"><span class="glyphicon glyphicon-plus" ></span> &nbsp;Total Brand & Activity</a></li>  
</ul -->
<?php  //} else { ?>

<?php if(($this->session_data->status > 0) && ($this->session_data->status < 4)){ ?>
	<ul class="nav nav-pills nav-stacked">
		<li class="nav-header"></li>
		<li><a href="<?php echo base_url()."home"; ?>"><span class="glyphicon glyphicon-dashboard" ></span> Dashboard</a></li>
		<li><a href="<?php echo base_url()."store"; ?>"><span class="glyphicon glyphicon-shopping-cart" ></span> Store</a></li>
		<li><a href="<?php echo base_url()."targetassign"; ?>"><span class="glyphicon glyphicon-shopping-cart" ></span> Target/Marker Assignment</a></li>
		<li><a href="<?php echo base_url()."newsassign"; ?>"><span class="glyphicon glyphicon-shopping-cart" ></span> News Assignment</a></li>
		<li><a href="<?php echo base_url()."qc"; ?>"><span class="glyphicon glyphicon-check" ></span> QC</a></li>
		<li><a href="<?php echo base_url()."questioner"; ?>"><span class="glyphicon glyphicon-check" ></span> Questioner</a></li>
		<li><a href="<?php echo base_url()."monitoring"; ?>"><span class="glyphicon glyphicon-phone-alt" ></span> Device monitoring</a></li>
		<li><a href="<?php echo base_url()."risklog"; ?>"><span class="glyphicon glyphicon-list-alt" ></span> Risk Log</a></li>
		<!-- <li><a href="<?php echo base_url()."device"; ?>"><span class="glyphicon glyphicon-phone" ></span> Device</a></li>  -->

		<!-- <li><a href="#"><span class="glyphicon glyphicon-stats" ></span> &nbsp;Reporting</a></li> -->
		<!-- 
		<li style="padding-left: 25px; font-size: x-small;">
			<a href="<?php echo base_url()."statistic/device_activity"; ?>">
				<span class="glyphicon glyphicon-plus" ></span> &nbsp;Devices Activity
			</a></li>  
		<li style="padding-left: 25px; font-size: x-small; margin-top: -5px;"><a href="<?php echo base_url()."statistic/brand_activity"; ?>"><span class="glyphicon glyphicon-plus" ></span> &nbsp;Total Brand & Activity</a></li>
		-->  
	</ul>


	<hr>
	<ul class="nav nav-pills nav-stacked">
		<li class="nav-header" ></li>
		
		<li><a href="#"><span class="glyphicon glyphicon-th-list" ></span> Data Management</a></li>
		<li style="padding-left: 20px;"><a href="<?php echo base_url()."user"; ?>"><span class="glyphicon glyphicon-user" ></span> &nbsp;User</a></li>
		<li style="padding-left: 20px;"><a href="<?php echo base_url()."store_type"; ?>"><span class="glyphicon glyphicon-shopping-cart" ></span> &nbsp;Store Type</a></li>
		<li style="padding-left: 20px;"><a href="<?php echo base_url()."device"; ?>"><span class="glyphicon glyphicon-phone" ></span> &nbsp;Device</a></li>
		<!-- li style="padding-left: 20px;"><a href="<?php echo base_url()."modem"; ?>"><span class="glyphicon glyphicon-signal" ></span> &nbsp;Modem</a></li -->
		<li style="padding-left: 20px;"><a href="<?php echo base_url()."brand"; ?>"><span class="glyphicon glyphicon-leaf" ></span> &nbsp;Brand</a></li>
		<li style="padding-left: 20px;"><a href="<?php echo base_url()."product"; ?>"><span class="glyphicon glyphicon-heart-empty" ></span> &nbsp;Product</a></li>
		<li style="padding-left: 20px;"><a href="<?php echo base_url()."variant"; ?>"><span class="glyphicon glyphicon-heart-empty" ></span> &nbsp;Variant</a></li>
		<li style="padding-left: 20px;"><a href="<?php echo base_url()."target"; ?>"><span class="glyphicon glyphicon-picture" ></span> &nbsp;Target</a></li>
		<li style="padding-left: 20px;"><a href="<?php echo base_url()."content"; ?>"><span class="glyphicon glyphicon-folder-open" ></span> &nbsp;Content</a></li>
		<!-- li style="padding-left: 20px;"><a href="<?php echo base_url()."tag"; ?>"><span class="glyphicon glyphicon-tags" ></span> &nbsp;Tag</a></li -->
		<li style="padding-left: 20px;"><a href="<?php echo base_url()."shelf"; ?>"><span class="glyphicon glyphicon-inbox" ></span> &nbsp;Shelf</a></li>
		<li style="padding-left: 20px;"><a href="<?php echo base_url()."news"; ?>"><span class="glyphicon glyphicon-list-alt" ></span> &nbsp;News</a></li>
		<li style="padding-left: 20px;"><a href="<?php echo base_url()."apps"; ?>"><span class="glyphicon glyphicon-th-large" ></span> &nbsp;Apps</a></li>
	</ul>
<?php }
else if($this->session_data->status == 4 ){?> <!-- HR LOGIN -->
	<ul class="nav nav-pills nav-stacked">
		<li class="nav-header"></li> 
		<li><a href="<?php echo base_url()."questioner/scan/". date('Y-m-d').'/'. date('Y-m-d'); ?>">QC Performance</a></li>
		<li><a href="<?php echo base_url()."questioner/chart/"; ?>">QC Graph</a></li>
		<li><a href="<?php echo base_url()."holidays/"; ?>">Holidays</a></li>

  </ul>
<?php } 
else{?>
	<ul class="nav nav-pills nav-stacked">
		<li class="nav-header"></li> 
		<li><a href="<?php echo base_url()."monitoring"; ?>">Device monitoring</a></li>
		<li><a href="<?php echo base_url().'healthiness'?>">Device Healthiness</a></li>
		<li><a href="<?php echo base_url().'questioner/chart'?>">Performance</a></li>
		<li><a href="<?php echo base_url()."questioner/scan/". date('Y-m-d').'/'. date('Y-m-d'); ?>">Poin</a></li>
		<li><a href="<?php echo base_url().'questioner/'?>">Scan Device</a></li>
		<li><a href="<?php echo base_url().'risklog'?>">Risk Log</a></li>
  </ul>
<?php } ?>