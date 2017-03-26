<?php
class Message
{
	protected $_ci;
	var $total_message;
	function __construct()
	{
		$this->_ci = &get_instance();
		$this->_ci->load->library('session');
		
	}
	
	function flush()
	{
		$this->_ci->session->set_userdata('message', null);
		$this->_ci->session->set_userdata('popup_message', null);
	}
	
	function show($msg, $type = 'success')
	{
		$current_msg = $this->_ci->session->userdata('message');
		$new_msg = new stdClass();
		$new_msg->message = $msg;
		$new_msg->type = $type;
		if($current_msg==null)
			$current_msg = array();
		array_push($current_msg, $new_msg);
		
		$this->_ci->session->set_userdata('message', $current_msg);
	}
	
	function show_popup($msg)
	{
		$current_msg = $this->_ci->session->userdata('popup_message');
		if($current_msg==null)
			$current_msg = array();
		array_push($current_msg, $msg);
		$this->_ci->session->set_userdata('popup_message', $current_msg);
		//$this->session->set_flashdata('message', $this->session->userdata('message'));
	}
	
	function total_message()
	{
		return count($this->_ci->session->userdata('message'));
	}
}
?>