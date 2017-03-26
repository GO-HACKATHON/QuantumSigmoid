<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('head'); ?>
<style>
body {
	background: url("<?php echo base_url()."/assets/img/DAV-Control-Center.png";?>") no-repeat center center fixed;
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;

}

.vertical-offset-100{
    padding-top:100px;
}
#loginBox {
    border-radius: 25px;
    border: 2px solid #929191;
    padding: 20px; 
    width: 400px;
    height: 150px;    
}
.col-md-7, .panel-body{
    height:500px;
}
@font-face{
  font-family: 'Gotham';
  src: url('<?php echo base_url() ?>assets/fonts/ufonts.com_gotham-book.ttf') format("truetype");
  
}
#welcome {
	font-family: 'Gotham';
	color: #929191;	
}
#welcome-text{
	font-size: 25pt;
	text-align: center;	
}
</style>
<body>
	<div id="top-nav" >
		<div class="col-md-12"style="background-color: #00B8F1" >		
			<img src="<?php echo $this->config->item('assets')."/img/DAV-control-center-toolbar.png"?>" />
		</div>
		<!-- /Header -->
		
		<div class="col-md-6"></div>
		<div class="container col-md-6"  id="welcome">
			<div class="row vertical-offset-100" >
				<div class="col-md-7 col-md-offset-4">			
					<div class="panel panel-default" >
						<div class="panel-body" >
							<br><br>
							<div id="welcome-text"></div>
							<div id="login-box" hidden>
								<p class="panel-title"><FONT size="6">Please Sign In</font></p><br>
								<?php $attr = array('id'=>'MyForm');?>
								<?php echo form_open('auth/login', $attr)?>
									<fieldset>
										<div class="form-group">
											<input class="form-control" placeholder="Username" name="username" type="text">
										</div>
										<div class="form-group">
											<input class="form-control" placeholder="Password" name="password" type="password" value="">
										</div>
										<br><br><br><br><br><br><br><br>
										<center><input type="image" value="submit" src="<?php echo $this->config->item('assets').'/img/Button-Login.png'; ?>" width="150px" alt="submit Button" ></center>
									</fieldset>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
<script>
setTimeout(function(){ 
		$('#login-box').show();
		$('#welcome-text').hide();
	}, 1000);
	var showText = function (target, message, index, interval) {   
  if (index < message.length) {
    $(target).append(message[index++]);
    setTimeout(function () { showText(target, message, index, interval); }, interval);
  }
}

$(function () {
  showText("#welcome-text", "WELCOME", 0, 100); 
});

</script>