<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('head');
	header("Cache-Control: no-cache, must-revalidate");
?>
<body>
	<input type="hidden" id="base_url" value="<?php echo base_url(); ?>" />
	<div id="top-nav" class="navbar navbar-inverse navbar-static-top">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="icon-toggle"></span>
			</button>
		</div>
		<div class="navbar-collapse collapse">
			<img style="margin-left: 20px; margin-top: 7px;" data-greta="<?php echo $this->config->item('assets')."/images/dcontrol_center.png"?>" />
			<?php if($this->session_data) { ?>
			<ul class="nav navbar-nav pull-right">
				<li class="dropdown">
					<a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#">
						<strong><?php echo ucfirst($this->session_data->username);?></strong>
							<span class="caret"></span>
					</a>
					<?php if($this->session_data->status != 0) { ?>
						<ul id="g-account-menu" class="dropdown-menu" role="menu">
							<li><a href="#">Edit Account</a></li>
							<li><a href="#">Change Password</a></li>
						</ul>
					<?php }?>
				</li>
				<li><a href="<?php echo base_url('auth/logout')?>">Log out</a></li>
			</ul>
			<?php } ?>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="panel panel-info">
				<div class="panel-heading"><center><strong>DAV</strong> Control Center</center></div>
				<div class="panel-body">
					<div class="col-md-3">
						<strong>Tools</strong>
						<hr />
						<?php include 'menu.php';?>
					</div>
					<div class="col-md-9">
						<?php $crumb = $this->uri->segment(1);?>
						<a href="#"><strong><span class="glyphicon glyphicon-dashboard"></span> <?php echo ucfirst($crumb);?></strong> </a> 
						<hr />
						<?php $this->load->view('message'); ?>
						<?php $this->load->view($page); ?>
					</div>
				</div>
				<div class="panel-footer"><center>&copy; DAV Global Pte Ltd. All right reserved.</center></div>
			</div>
		</div>
	</div>
</body>
</html>
