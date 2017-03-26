<?php
clearstatcache();
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
class MY_Controller extends CI_Controller
{
	var $db;
	var $session_data;
	var $uuid;
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('common');
		//$this->load->model('update');
		//$this->load->model('delete');
		$this->load->library('pagination');
		$this->load->library('message');
		$this->db = $this->common->db();
		$this->session_data = $this->session->userdata('is_logged_in');
		//$this->message('BROADCAST MESSAGE TEST', 'info');
		//$this->message('test constructor 2', 'info');
		//$this->message('test constructor 3');
		//$this->flash_message('flash message');
	}
	
	function authCheck($freeaccess = false)
	{
		if($freeaccess) return;
		
		if($this->session->userdata('is_logged_in'))
		{
			$this->authData = $this->session->userdata('is_logged_in');
			return;
		}
		else
			redirect('auth', 'refresh');
	}
	
	function saveAuth($data)
	{
		$this->session->set_userdata('is_logged_in', $data);
	}
	
	function statusCheck($type, $freeaccess = false)
	{
		if($freeaccess) return;
		
		$auth = $this->session->userdata('is_logged_in');
		if($type<=$auth->status)
			return;
		else
		{
			$this->message('You don\'t have access for previous page that you have been accessed');
			redirect('/');
		}
	}
	
	function _flashMsg($error)
	{
		$this->session->set_flashdata($error);
	}

	function _print_data($data)
	{
		print '<div style="background:#262725; color:#11ff65; padding:20px; border-top:3px solid #11ff65; border-bottom:3px solid #11ff65;">';
		print '<pre>';
		print_r($data);
		print '<pre>';
		print '</div>';
		//die();
	}
	
	function GetPageName($modul, $method)
	{
		$arr = explode('::', $method);
		return $modul.'/'.$arr[1];
	}
	
	function rc4($key, $str)
	{
		$s = array();
		for ($i = 0; $i < 256; $i++) {
			$s[$i] = $i;
		}
		
		$j = 0;
		for ($i = 0; $i < 256; $i++) {
			$j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
			$x = $s[$i];
			$s[$i] = $s[$j];
			$s[$j] = $x;
		}
		$i = 0;
		$j = 0;
		$res = '';
		for ($y = 0; $y < strlen($str); $y++) {
			$i = ($i + 1) % 256;
			$j = ($j + $s[$i]) % 256;
			$x = $s[$i];
			$s[$i] = $s[$j];
			$s[$j] = $x;
			$res .= $str[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
		}
		return $res;
	}
	
	function ListOfDay($month, $year)
	{
		//$month = "05";
		//$year = "2014";

		$start_date = "01-".$month."-".$year;
		$start_time = strtotime($start_date);

		$end_time = strtotime("+1 month", $start_time);

		for($i=$start_time; $i<$end_time; $i+=86400)
		{
		   $list[] = date('Y-m-d', $i);
		}
		//print_r($list);
		return $list;
	}
	
	function ListOfDayRangeDate($start_date, $end_date)
	{
		//$month = "05";
		//$year = "2014";

		//$start_date = "01-".$month."-".$year;
		$start_time = strtotime($start_date);

		$end_time = strtotime($end_date);

		for($i=$start_time; $i<$end_time; $i+=86400)
		{
		   $list[] = date('Y-m-d', $i);
		}
		//print_r($list);
		return $list;
	}
	
	function DateNow($format = 'Y-m-d H:i:s')
	{
		date_default_timezone_set('Asia/Jakarta');
		$dt = new DateTime();
		return $dt->format($format);
	}
}
?>