<!DOCTYPE html>
<html lang="en">

<?php $this->load->view('head');?>

<body>
	<input type="hidden" id="base_url" value="<?php echo base_url(); ?>" />
	<div id="top-nav" class="navbar navbar-inverse navbar-static-top">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="icon-toggle"></span>
			</button>
		</div>
		<div class="navbar-collapse collapse">
			<img style="margin-left: 20px; margin-top: 7px;" src="<?php echo $this->config->item('assets')."/images/dcontrol_center.png"?>" />
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
						<?php $crumb = $this->uri->segment(1);
						if($this->session_data->status != 4 ){?>
							<a href="#"><strong><span class="glyphicon glyphicon-dashboard"></span> <?php echo ucfirst($crumb);?></strong> </a> 
						<?php }
						else{?>
							<a href="#"><strong><span class="glyphicon glyphicon-dashboard"></span> QC Performance</strong> </a> 
						
						<?php }?><hr />
						
						<?php $this->load->view('message'); ?>
						
						<?php $this->load->view($page); ?>
						<?php /* switch($page) {
							case "login" : include 'login.php'; break;
							case "home" : include 'dashboard.php'; break;
							case "home/head2head" : include 'head2head.php';break;
							case "user" : include 'user/user.php'; break;
							case "user/edit" : include 'user/user_edit.php'; break;
							case "target" : include 'target/target.php'; break;
							case "target/edit" : include 'target/target_edit.php'; break;
							case "target/detail" : include 'target/target_detail.php'; break;
							case "target/dbdetail" : include 'target/target_db_detail.php'; break;
							case "target/content" : include 'target/target_content.php'; break;
							case "target/tag": include 'target/target_tag.php'; break;
							case "speech" : include 'speech.php'; break;
							case "store" : include 'store/index.php'; break;
							case "store/detail" : include 'store/detail.php'; break;
							case "store/edit" : include 'store/edit.php'; break;
							case "store/geolocation" : include 'geolocation/store_map.php'; break;
							case "store/device" : include 'store/store_device.php'; break;
							case "store/modem" : include 'store/store_modem.php'; break;
							case "store/pv_position": include 'store/store_pv_position.php'; break;
							case "store_type/index": include $page.'.php'; break;
							case "store_type/edit": include $page.'.php'; break;
							case "store_type/news": include $page.'.php'; break;
							case "device" : include 'device/device.php'; break;
							case "device/edit" : include 'device/device_edit.php'; break;
							case "device/phone" : include 'device/device_phone.php'; break;
							case "modem" : include 'modem/modem.php'; break;
							case "modem/edit" : include 'modem/modem_edit.php'; break;
							case "content" : include 'content/content.php'; break;
							case "content/edit" : include 'content/content_edit.php'; break;
							case "content/detail" : include 'content/content_detail.php'; break;
							case "statistic" : include 'statistic/statistic.php'; break;
							case "statistic/device_activity" : include 'statistic/device_activity.php'; break;
							case "statistic/brand_activity" : include 'statistic/brand_activity.php'; break;
							case "statistic/brand_activity_detail" : include 'statistic/brand_activity_detail.php'; break;
							case "statistic/brand_activity_more_detail" : include 'statistic/brand_activity_more_detail.php'; break;
							case "map" : include 'fullmap.php'; break;
							case "tag" : include 'tag/tag.php'; break;
							case "tag/edit" : include 'tag/tag_edit.php'; break;
							case "shelf" : include 'shelf/shelf.php'; break;
							case "shelf/edit" : include 'shelf/shelf_edit.php'; break;
							case "brand" : include 'brand/brand.php'; break;
							case "brand/edit" : include 'brand/brand_edit.php'; break;
							case "product" : include 'product/product.php'; break;
							case "product/edit" : include 'product/product_edit.php'; break;
							case "product/variant" : include 'product/product_variant.php'; break;
							case "devicestatus": include 'devicestatus.php'; break;
							case "apps": include 'apps/index.php'; break;
							case "news": include 'news/index.php'; break;
							case "reporting": include 'company/reporting.php'; break;
							case "reporting_detail": include 'company/reporting_detail.php'; break;
							case "presscon/product": include 'presscon/product.php'; break;
							case "qc_report": include 'qc/index.php'; break;
							case "qc_questionaire": include 'qc/questionaire.php'; break;
							case "qc_map": include 'qc/map.php'; break;
							case "qc_form": include 'qc/qc_form.php'; break;
						} */?>
					</div>
				</div>
				<div class="panel-footer"><center>&copy; DAV Global Pte Ltd. All right reserved.</center></div>
			</div>
		</div>
	</div>
</body>
</html>
