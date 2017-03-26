<?php

class Api extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
    }
	
	function dr() // device register
	{
		$sn = $this->input->post('sn');
		$res = -1;
		if($this->db->get_where('device', array('serial_number' => $sn))->num_rows()<1)
		{
			if($this->db->insert('device', array('serial_number' => $sn)))
				$res = 1;
		}
		else
		{
			$res =  0;
		}
		$res = array('result' => $res);
		header("Content-Type: application/json");
		print_r(json_encode($res));
	}

	function aaa()
	{
		ini_set('max_execution_time', 1200);
		putenv('PYTHONPATH=/usr/lib/python2.7/');
		
		$command = '/usr/bin/python /Applications/XAMPP/htdocs/frcms/fr.py';
		//ob_start();
		//passthru($command);
		//$out = ob_get_clean(); 
		
		//$out = system($command);

		$out = shell_exec($command);
		
		
		//$aaa = exec($command, $out, $status);
		//$aaa = system($command);
		//$aaa = exec('sudo python32 /var/www/frcms/fr.py');
		//echo shell_exec($command);
		//echo $aaa .'<br />';
		print_r($out);
	}


	function put_image()
	{
		//$sn = '70214cebe91d0d768bf32bda30aa0f62';
		$sn = $this->input->post('sn');
		$dn = $this->DateNow();
		$str = str_replace('-', '', $dn);
		$str = str_replace(' ', '', $str);
		$str = str_replace(':', '', $str);
		$filename = substr($sn, 0, 5). '_' .$str;
		$config['upload_path'] = './food_images/';
		$config['allowed_types'] = '*';
		$config['max_size']	= '10000';
		$config['max_width']  = '1920';
		$config['max_height']  = '1080';
    	$config['file_name'] = $filename;
		$this->load->library('upload', $config);

		$upd_data = null;
		$res = false;
		$python = null;
		$md5csf = null;
		$id = null;
		if (!$this->upload->do_upload())
		{
			$upd_data = array('error' => $this->upload->display_errors());
			//print_r($error);
		}
		else
		{
			$upd_data = array('upload_data' => $this->upload->data());
			chmod('./food_images/' . $filename .'.jpg', 0777);
			//print_r($data);
			$md5csf = md5_file('./food_images/' . $filename . '.jpg');
			$dev = $this->db->get_where('device', array('serial_number' => $sn))->first_row();
			$data = array('device_id' => $dev->id, 'food_id' => -1, 'datetime' => $dn, 'md5' => $md5csf);
			if($this->db->insert('recognized', $data)) $res = true;
			else $res = false;
			exec('mv /Applications/XAMPP/htdocs/frcms/food_images/' . $filename . '.jpg /Applications/XAMPP/htdocs/frcms/food_images/' . $filename . '_' . substr($md5csf, 0, 5) . '.jpg'); 
			//copy(base_url() . "/food_images/" . $filename . ".jpg", "./food_images/" . $filename . "_" . substr($md5csf, 0, 5) . ".jpg");
			$id = $this->db->get_where('recognized', array('food_id' => -1, 'datetime' => $dn, 'md5' => $md5csf))->first_row()->id;
		}
		
		//print_r($data);die();
		header("Content-Type: application/json");
		$res = array('result' => $res, 'id' => $id, 'submd5' => substr($md5csf, 0, 5));
		print_r(json_encode($res));
	}

	function recognize()
	{
		$id = $this->input->post('id');
		$sn = $this->input->post('sn');
		$dev_id = $this->db->get_where('device', array('serial_number' => $sn))->first_row()->id;
		$res = $this->db->get_where('recognized', array('id' => $id))->first_row();
		$food = null;
		if($res->food_id != -1)
			$food = $this->db->get_where('food', array('id' => $res->food_id))->first_row()->name;
		else
			$food = null;

		$val = array('id' => $id, "name" => $food, 'food_id' => $res->food_id);
		header("Content-Type: application/json");
		//$res = array('id' => $id, 'name' => $food);
		print_r(json_encode($val));
	}

	function set_recognized_food($id, $food_id)
	{
		$this->db->where('id', $id);
		$this->db->update('recognized', array('food_id' => $food_id));
	}

	function list_unrecognize()
	{
		$data = array();
		$res = $this->db->get_where('recognized', array('food_id' => -1))->result();
		for($i=0;$i<count($res);$i++)
		{
			$sn = $this->db->get_where('device', array('id' => $res[$i]->device_id))->first_row()->serial_number;
			$sn_ = substr($sn, 0, 5);
			$md5_ = substr($res[$i]->md5, 0, 5);
			$str = $res[$i]->datetime;
			
			$str = str_replace('-', '', $str);
			$str = str_replace(' ', '', $str);
			$str = str_replace(':', '', $str);
			//print_r($res[$i]);die();
			$str = $sn_ . '_' . $str;
			$filename = $str . '_' . $md5_ . '.jpg';
			$data[$i] = new stdClass();
			$data[$i]->id = $res[$i]->id;
			//$data[$i]->sn = $sn;
			$data[$i]->filename = $filename;
		}
		header("Content-Type: application/json");
		print_r(json_encode($data));
	}

	function statistics()
	{
		$result = $this->db->select("CONCAT(DATE(datetime), ' H', HOUR(datetime)) as datetime, COUNT(*) as total", FALSE)->from("recognized")->group_by(array("DATE(datetime)", "HOUR(datetime)"))->get()->result();
		header("Content-Type: application/json");
		print_r(json_encode($result));
	}
}