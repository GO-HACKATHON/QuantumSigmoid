<script type="text/javascript">
    $(document).ready(function() {
		$.fn.domain = function()
		{
			return '<?php echo base_url(); ?>';
		};
		
		$.fn.delete = function(object) {
			swal({
				title: "Are you sure you want to delete this item?",
				text: "You will not be able to recover this file!",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes, Delete It!",
				cancelButtonText: "No, Cancel",
				closeOnConfirm: false,
				closeOnCancel: false
				},
				function(isConfirm) {
					if (isConfirm) {
						swal({
							title: "Deleted!",
							text: "Your file has been deleted.",
							type: "success"
							},
							function() { 
								$(location).attr('href', object);
							}
						);
					} else {
						swal("Cancelled", "Your file is safe :)", "error");
					}
			});
		};
        $('table.table-striped.table-bordered').not('.table-ajax').dataTable();
		<?php $this->load->view('popup_message'); ?>
    });
</script>
<?php // $this->session->set_userdata('message', null); ?>