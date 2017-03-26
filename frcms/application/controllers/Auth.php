<?php
class auth extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		
	}

	function index()
	{
		$this->message->show('Welcome to DAV Control Center');
		if($this->session_data)
		{
			redirect('/');
		}
		else
		{
			redirect('auth/page');
		}
	}
		
	function page()
	{
		//$this->init_message();
		$this->load->view('login');
	}

	function login()
	{
		$data['username'] =$this->input->post('username');
		$data['password'] = md5($this->input->post('password'));
		$user = $this->db->get_where('users', $data)->first_row();
		$qc_user = $this->db->select("*,'0' as status")->get_where('qc_users', $data)->first_row();

		if(count($user)>0) // if the user's credentials validated...
		{
			unset($user->password);
			$this->saveAuth($user);
			redirect('/');
		}
		else if(count($qc_user)>0) // if the user's credentials validated...
		{
			unset($user->password);
			$this->saveAuth($qc_user);
			redirect('/');
		}
		else // incorrect username or password //
		{
			redirect('auth/page');
		}
	}

	function logout()
	{
		$this->session->unset_userdata('is_logged_in');
		$this->message->show('Logout Success');
		redirect('auth');
	}
}
?>