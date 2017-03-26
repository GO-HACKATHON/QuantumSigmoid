<?php

class Mobile extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function check($sn)
	{
		$this->load->model("read");
		$result = $this->read->checkMobileVersion($sn);
		if($result!="0")
		{
			$result->md5 = md5_file('./apk/DAV_'.$result->version.'.apk');
			header("Content-Type: application/json");
			print_r(json_encode($result));
		}
		else
		{
			echo $result;
		}
	}
	

	
	
	function version_check($sn, $modul = 'DAV')
	{
		//$modul = $this->input->post('modul');
		//if($modul=='') $modul = 'DAV';
		
		$this->load->model("read");
		$this->db->where('modul', $modul);
		$this->db->order_by('number_ver', 'desc');
		$current_version = $this->db->get('mobile_version')->first_row();
		
		$dev = $this->db->get_where('devices', array('serial_number' => $sn))->first_row();
		//print_r($dev);
		$res = $this->db->get_where('mobile_update_log', array('device_id' => $dev->id, 'mobile_version_id' => $current_version->id));
		//print_r($res->first_row());
		
		if($res->num_rows()>0)
		{
			$result = $res->first_row();
			
			$version = $this->db->get_where('mobile_version', array('id' => $result->mobile_version_id))->first_row();
			
			
			unset($result->mobile_version_id);
			unset($result->device_id);
			unset($result->datetime);
			$result->version = $version->version;
			$result->md5 = md5_file('./apk/'.$modul.'_'.$version->version.'.apk');
			header("Content-Type: application/json");
			print_r(json_encode($result));
		}
		else
		{
			$result = new stdClass();
			
			$result->id = 0;
			$result->version = $current_version->version;
			$result->md5 = md5_file('./apk/'.$modul.'_'.$current_version->version.'.apk');
			header("Content-Type: application/json");
			print_r(json_encode($result));
		}
	}
	

	function update($sn, $modul = 'DAV')
	{
		// get last version at modul
		$this->db->select('id, version, number_ver');
		$this->db->from('mobile_version');
		$this->db->where('modul', $modul);
		$this->db->order_by('number_ver', 'desc');
		$res = $this->db->get()->first_row();
		$version = $res->version;
		$mobile_version_id = $res->id;
		
		// get mobile device id by serial number
		$this->db->select('id');
		$this->db->from('devices');
		$this->db->where('serial_number', $sn);
		$query = $this->db->get();
		$device_id = $query->first_row()->id;
		
		// get date and time now
		$datetime = $this->DateNow();
		
		$total_row = $this->db->get_where('mobile_update_log', array('mobile_version_id' => $mobile_version_id, 'device_id' => $device_id))->num_rows();
		if($total_row==0)
		{
			$data = array('mobile_version_id' => $mobile_version_id, 'device_id' => $device_id, 'datetime' => $datetime);
			$this->db->insert('mobile_update_log', $data);
		}
	}
	
	function register_device($sn)
	{
		if($this->db->get_where('devices', array('serial_number' => $sn))->num_rows()<1)
		{
			if($this->db->insert('devices', array('serial_number' => $sn)))
				echo 1;
		}
		else
		{
			echo 0;
		}
	}
	
	
	function reset_device($sn)
	{
		$dev = $this->db->get_where('devices', array('serial_number' => $sn))->first_row();
		
		$any = $this->db->get_where('mobile_update_log', array('device_id' => $dev->id))->first_row();
		if($any)
		{
			$this->db->where('device_id', $dev->id);
			$this->db->delete('mobile_update_log');
		}
		
		
	}
	
	function device_activity()
	{
		$sn = $this->input->post('serial_number');
		$datetime = $this->DateNow();
		$data = array('serial_number' => $sn, 'datetime' => $datetime);
		$isAny = $this->db->get_where('device_activity', array('serial_number' => $sn, 'DATE(datetime)' => $this->DateNow('Y-m-d'), 'HOUR(datetime)' => $this->DateNow('H')))->first_row();
		$res = '';
		if($isAny==0)
			$res = $this->db->insert('device_activity', $data);
		header("Content-Type: application/json");
		$res = array('result' => $res);
		print_r(json_encode($res));
	}
	
	function activity($type = null)
	{
		if(!isset($type))
			$type = 'INIT';
		
		//$target_name = 'djarum_black_2 ';
		$target_name = $this->input->post('target_name');
		$target = $this->db->get_where('target', array('name' => $target_name))->first_row();
		$sn = $this->input->post('serial_number'); // update 11052016 11:38 by winni
		$ctn_id = $this->input->post('content_id');
		$ctn_var = $this->input->post('content_variant');
		$tid = $target->target_id;
		
		/*$tid = 'a23d5b71c15a43beb4d3301eb4cdcbda';
		$sn = 'c1999e815ff89312a143a6c415ecf65f22620f5a';
		$ctn_id = 15;
		$ctn_var = 'Music';*/
		
		$datetime = $this->DateNow();
		
		
		$dev = $this->db->get_where('devices', array('serial_number' => $sn))->first_row();
		$store_id = $dev->store_id;
		$shelf_id = $dev->shelf_id;
		$variant_id = $this->db->get_where('variant', array('target_id' => $tid))->first_row()->id;
		$content_id = $this->db->get_where('target_content', array('target_id' => $tid))->first_row()->content_id;
		
		$content_variant_id = 0;
		if($ctn_id!='')
			$content_variant_id = $this->db->get_where('content_variant', array('content_id' => $ctn_id, 'name' => $ctn_var))->first_row()->id;
				
		$data = array('target_id' => $tid, 'serial_number' => $sn, 'store_id' => $store_id, 'shelf_id' => $shelf_id, 'variant_id' => $variant_id, 'content_id' => $content_id, 'content_variant_id' => $content_variant_id, 'interaction_type' => $type, 'datetime' => $datetime);
		
		//print_r($data);die();
		
		$res = false;
		
		if($this->db->insert('activity', $data)) $res = true;
		else $res = false;
		header("Content-Type: application/json");
		$res = array('result' => $res);
		print_r(json_encode($res));
	}

	/* added by Gia 2016-11-21 */ 
	function activity_fd()
	{
		//$sn = '70214cebe91d0d768bf32bda30aa0f62';
		$sn = $this->input->post('serial_number');
		
		$str = str_replace('-', '', $this->DateNow());
		$str = str_replace(' ', '', $str);
		$str = str_replace(':', '', $str);
		$config['upload_path'] = './uploads/face';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size']	= '1000';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';
    	$config['file_name'] = substr($sn, 0, 10). '_' .$str;
		$this->load->library('upload', $config);

		$upd_data = '';
		if (!$this->upload->do_upload())
		{
			$upd_data = array('error' => $this->upload->display_errors());
			//print_r($error);
		}
		else
		{
			$upd_data = array('upload_data' => $this->upload->data());
			//print_r($data);
		}
		
		$dev = $this->db->get_where('devices', array('serial_number' => $sn))->first_row();
		$store_id = $dev->store_id;
		$shelf_id = $dev->shelf_id;
		$datetime = $this->DateNow();
		$data = array('serial_number' => $sn, 'store_id' => $store_id, 'shelf_id' => $shelf_id, 'datetime' => $datetime, 'gender' => 'UNKNOWN');
		
		//print_r($data);die();
		
		$res = false;
		



		
		if($this->db->insert('activity_fd', $data)) $res = true;
		else $res = false;
		header("Content-Type: application/json");
		$res = array('result' => $res, 'upload' => $upd_data);
		print_r(json_encode($res));
	}

	/* added by Gia 2016-10-21 */ 
	function activity_cv($type = null)
	{
		$sn = $this->input->post('serial_number');
		$dev = $this->db->get_where('devices', array('serial_number' => $sn))->first_row();
		$store_id = $dev->store_id;
		$shelf_id = $dev->shelf_id;
		$datetime = $this->DateNow();
		$data = array('serial_number' => $sn, 'store_id' => $store_id, 'shelf_id' => $shelf_id, 'interaction_type' => $type, 'datetime' => $datetime);
		
		//print_r($data);die();
		
		$res = false;
		
		if($this->db->insert('activity_cv', $data)) $res = true;
		else $res = false;
		header("Content-Type: application/json");
		$res = array('result' => $res);
		print_r(json_encode($res));
	}
	
	/* added by Gia 2016-07-26 */ 
	function activity_st()
	{
		$type = 'INIT';
		
		$target_name = 'tp_energymilk';
		//$target_name = $this->input->post('target_name');
		$target = $this->db->get_where('target', array('name' => $target_name))->first_row();
		//$sn = $this->input->post('serial_number'); // update 11052016 11:38 by winni
		$sn = 'a49e8acdfec2b9b0a4d82daf497f0f02e9e48fc3';// update 11052016 11:38 by winni
		$ctn_id = $this->input->post('content_id');
		//$ctn_id = ''';
		$ctn_var = $this->input->post('content_variant');
		$tid = $target->target_id;
		
		/*$tid = 'a23d5b71c15a43beb4d3301eb4cdcbda';
		$sn = 'c1999e815ff89312a143a6c415ecf65f22620f5a';
		$ctn_id = 15;
		$ctn_var = 'Music';*/
		
		$datetime = $this->DateNow();
		
		
		$dev = $this->db->get_where('devices', array('serial_number' => $sn))->first_row();
		$store_id = $dev->store_id;
		$shelf_id = $dev->shelf_id;
		$variant_id = $this->db->get_where('variant', array('target_id' => $tid))->first_row()->id;
		$content_id = $this->db->get_where('target_content', array('target_id' => $tid))->first_row()->content_id;
		
		$content_variant_id = 0;
		if($ctn_id!='')
			$content_variant_id = $this->db->get_where('content_variant', array('content_id' => $ctn_id, 'name' => $ctn_var))->first_row()->id;
				
		$data = array('target_id' => $tid, 'serial_number' => $sn, 'store_id' => $store_id, 'shelf_id' => $shelf_id, 'variant_id' => $variant_id, 'content_id' => $content_id, 'content_variant_id' => $content_variant_id, 'interaction_type' => $type, 'datetime' => $datetime);
		
		//print_r($data);die();
		
		$res = false;
		
		if($this->db->insert('activity', $data)) $res = true;
		else $res = false;
		header("Content-Type: application/json");
		$res = array('result' => $res);
		print_r(json_encode($res));
	}
	
	function activity1($type = null)
	{
		if(!isset($type))
			$type = 'INIT';
		
		$target_name = 'SGM_Fruit_1plus';
		//$target_name = $this->input->post('target_name');
		
		$target = $this->db->get_where('target', array('name' => $target_name))->first_row();
		print_r($target);
		$tid = $target->target_id;
		//$tid = ' f1813a3c3c914259887f15e2e4900c0c';
		//$sn = $this->input->post('serial_number');
		$sn = '714a614a325bfeec5501f157aab86768';
		$datetime = $this->DateNow();
		
		
		$dev = $this->db->get_where('devices', array('serial_number' => $sn))->first_row();
		$store_id = $dev->store_id;
		$shelf_id = $dev->shelf_id;
		$variant_id = $this->db->get_where('variant', array('target_id' => $tid))->first_row()->id;
		$content_id = $this->db->get_where('target_content', array('target_id' => $tid))->first_row()->content_id;
		$data = array('target_id' => $tid, 'serial_number' => $sn, 'store_id' => $store_id, 'shelf_id' => $shelf_id, 'variant_id' => $variant_id, 'content_id' => $content_id, 'interaction_type' => $type, 'datetime' => $datetime);
		$res = $data;
		
		//if($this->db->insert('activity', $data)) $res = true;
		//else $res = false;
		header("Content-Type: application/json");
		$res = array('result' => $res);
		print_r(json_encode($res));
	}
	
	function info($sn)
	{
		$shelf = '';$store = '';
		$dev = $this->db->get_where('devices', array('serial_number' => $sn))->first_row();
		if($dev->shelf_id!=0)
			$shelf = $this->db->get_where('shelf', array('id' => $dev->shelf_id))->first_row()->name;
		else
			$shelf = 'No Assignment';
		if($dev->store_id!=0)
			$store = $this->db->get_where('store', array('id' => $dev->store_id))->first_row()->store_id;
		else
			$store = 'No Assignment';
		
		$data = array('StoreID' => $store, 'Shelf' => $shelf, 'DateTime' => $this->DateNow());
		header("Content-Type: application/json");
		print_r(json_encode($data));
	}
	
	
	
	function product()
	{
		$data = $this->input->post();
		$res = $this->db->get_where('product', array('project_type' => $data['project_type']))->result();
		header("Content-Type: application/json");
		print_r(json_encode($res));
	}
	
	/*
	function variant()
	{
		$data = $this->input->post();
		$res = $this->db->select('id, name')->get_where('product', array('product_id' => $data['product_id']))->result();
		header("Content-Type: application/json");
		print_r(json_encode($res));
	}*/
	
	function assign()
	{
		$data = $this->input->post();
		//print_r($data);die();
		$sn = $data['sn'];
		unset($data['sn']);
		$dev = $this->db->get_where('devices', array('serial_number' => $sn))->first_row();
		$res = new stdClass();
		$where = $this->db->get_where('devices', $data)->num_rows();
		if($where>0)
		{
			$a = $this->db->get_where('devices', $data)->first_row();
			
			
			$res->sn = $a->serial_number;
			$res->store = $this->db->get_where('store', array('id' => $data['store_id']))->first_row()->store_name;
			$res->shelf = $this->db->get_where('shelf', array('id' => $data['shelf_id']))->first_row()->name;
			if($sn==$a->serial_number)
				$res->result = 0; // device sudah terassign sesuai dengan store dan shelf
			else
				$res->result = 1; // store dan shelf sudah terassign dengan device lain
		}
		else
		{
			$res->sn = $sn;
			$res->store = $this->db->get_where('store', array('id' => $dev->store_id))->first_row()->store_name;
			$res->shelf = $this->db->get_where('shelf', array('id' => $dev->shelf_id))->first_row()->name;
			if($dev->store_id==0 || $dev->shelf_id==0)
			{
				$this->db->where('id', $dev->id);
				$this->db->update('devices', $data);
				$res->result = 3;
				// berhasil di assign
			}
			else if($dev->store_id!=0 || $dev->shelf_id!=0)
			{
				
				$res->result = 2; // device sudah terassign ke store dan shelf lain
				//$res->store_id = $dev->store_id;
				//$res->shelf_id = $dev->shelf_id;
			}
		}
		header("Content-Type: application/json");
		print_r(json_encode($res));
		/*$variant = $this->db->get_where('variant', array('product_id' => $data['product_id']))->result();
		$count = 0;
		foreach($variant as $val)
		{
			//$store_id = $this->db->get_where('store', array('store_id' => $data['store_id']))->first_row()->id;
			//$shelf_id = $this->db->get
			$param = array('store_id' => $data['store_id'], 'variant_id' => $val->id, 'shelf_id' => $data['shelf_id']);
			if($this->db->get_where('pv_position', $param)->num_rows()==0)
				$this->db->insert('pv_position', $param);
			else
				$count++;
		}
		if($count==count($variant))
			echo -1;
		else if($count<count($variant))
			echo 0;
		else
			echo 1;
			*/
		
	}
	
	/* added by Winni */
	function get_tbl_shelf()
	{
		$res = $this->db->select('id, name')->from('shelf')->get()->result();
		header("Content-Type: application/json");
		print_r(json_encode($res));
	}	

	function get_tbl_store($store_id)
	{
		$res = $this->db->select('id, store_id, store_name')->from('store')->where('store_id', $store_id)->get()->result();
		header("Content-Type: application/json");
		print_r(json_encode($res));
	}	
	/* added by Winni */
}

?>