<div class="modal-body">
	<?php if($page=="user/user_edit") { ?>
	<input type="hidden" name="id" value="<?php echo $user->id; ?>" />
	<?php } ?>
	<div class="form-group">
		<div class="row">    	
			<div class="col-md-11">
				<label for="username">User Name</label>
				<input type="text" class="form-control" name="username" placeholder="User Name" <?php if($page=="user/user_edit") {  ?> disabled="disabled" value="<?php echo $user->username; ?>" <?php  } ?> /> 
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="row">    	
			<div class="col-md-11">
				<label for="password">Password</label>
				<input type="password" class="form-control" name="password" placeholder="Enter Password" <?php if($page=="user/user_edit") {  ?> value="<?php echo $user->password; ?>" <?php  } ?> /> 
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="row">    	
			<div class="col-md-11">
				<label for="repassword">Re-Enter Password</label>
				<input type="password" class="form-control" name="repassword" placeholder="Re-Enter Password" <?php if($page=="user/user_edit") {  ?> value="<?php echo $user->password; ?>" <?php  } ?>  /> 
			</div>
		</div>
	</div>
	<div class="form-group">
		<label for="targetName">Product <i>(For DAV Analytics)</i></label>
		<div class="row">    	
			<div class="col-md-11">
				<select name="product_id" class="form-control">          
					<?php if(isset($product)) { ?>
						<option <?php if($page=="user/user_edit") { if($user->status=='3') { ?> selected="selected" <?php } } ?> value="0">[Administrator]</option>
						<option <?php if($page=="user/user_edit") { if($user->status=='4') { ?> selected="selected" <?php } } ?> value="hr">[HR]</option>
						<?php foreach($product as $val) { ?>
							<option <?php if($page=="user/user_edit") { if($user->product_id==$val->id) { ?> selected="selected" <?php } } ?> value="<?php echo $val->id;?>"><?php echo $val->name;?></option>
						<?php } ?>
					<?php }?>
				</select> 
			</div>        
		</div>
	</div>
	<br />
	<input class="btn btn-primary" type="submit" value="<?php if($page=="user/user_edit") { ?>Update<?php } else { ?>Submit<?php } ?>" />
	<a href="<?php echo base_url().'user'; ?>"><input class="btn btn-primary" data-dismiss="modal" id="cancel" type="button" value="Cancel" /></a>
</div>