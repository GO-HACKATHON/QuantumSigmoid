<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		//echo 'test';
		$this->load->view('dashboard');
		//$this->authCheck();
		//$user = $this->authData;
		//$this->_print_data($data);die();
		/*if (($user->status>=2) && ($user->status<4))
		{
			$data['page'] = 'dashboard';
			$data['total_store'] = $this->db->get('store')->num_rows();
			$data['total_device'] = $this->db->get('devices')->num_rows();
			$data['total_target'] = $this->db->get('target')->num_rows();
			$err_rate_days = $this->db->from('device_activity_cache')->where('type', 'DAYS')->order_by('datetime', 'DESC')->get()->first_row();
			$err_rate_24 = $this->db->from('device_activity_cache')->where('type', '24')->order_by('datetime', 'DESC')->get()->first_row();
			$data['error_rate_days'] = 100 - (($err_rate_days->total/$err_rate_days->row)*100);
			$data['error_rate_24'] = 100 - (($err_rate_24->total/$err_rate_24->row)*100);
			$data['geo'] = $this->db->select('store_id, store_name, latitude, longitude')->from('store')->where(array('latitude !=' => 0, 'longitude !=' => 0))->get()->result();
			//print_r($data);
			$this->load->view('home', $data);
		}
		else if ($user->status==4)
		{
			$data['page'] = "questioner/totalscan";
			$start = date('Y-m-d');
			$end = date('Y-m-d');
		
			$this->db->select("id,fullname",false)->where("fullname!=''")->where("trash='0'");
			$data['qc'] = $this->db->get('qc_users')->result();
			$data['dt'] = $this->db->select("DATE(created_at) as dt FROM questioner_dav WHERE DATE(created_at) BETWEEN  '$start' and '$end' AND DATE_FORMAT(created_at,'%Y%m%d') = substring(scan_result,6,8)GROUP BY DATE(created_at) ",false)->get()->result();
			$newData = array();
			$qc = $data['qc'];
			$days = (strtotime($end) - strtotime($start)) / (60 * 60 * 24);
			
			for($i=0;$i<count($qc);$i++)
			{
				$newData[$i] = new stdClass;
				$newData[$i]->id = $qc[$i]->id;
				$newData[$i]->fullname = $qc[$i]->fullname;
				$year = substr($start,0,4);
				$month = substr($start,5,2);
				$date = substr($start,8,2);
				$new_date = $year.$month.$date;
				$tgl = 1;
				$totalperqc = $this->db->select("sum(total) as total FROM (select count(*) as total from questioner_dav a where date(a.created_at)  BETWEEN '$start' AND '$end' and a.qc_user_id = '".$newData[$i]->id ."' AND kondisi_device = 'Mati' UNION  select  count(*) as total FROM (SELECT a.kondisi_device,a.id FROM questioner_dav a JOIN devices dev ON SUBSTRING(dev.serial_number,1,5) = SUBSTRING(a.SCAN_RESULT,1,5) JOIN store s ON s.id = dev.store_id  JOIN shelf sh ON sh.id = dev.shelf_id JOIN qc_users qc ON qc.id = a.qc_user_id where DATE(a.created_at) BETWEEN '$start' AND '$end' AND DATE_FORMAT(a.created_at,'%Y%m%d') = substring(scan_result,6,8) and a.qc_user_id = '".$newData[$i]->id ."' group by DATE(a.created_at),s.id,sh.id,a.qc_user_id,date(a.created_at) order by a.id desc) as a where a.kondisi_device != 'Mati')a",false)->get()->first_row();
				
				$newData[$i]->totalperqc =  $totalperqc->total;
					
				for($j=0;$j<=$days;$j++)
				{
					$date = date('Y-m-d', strtotime($new_date));
					$res = $this->db->select("sum(total) as total FROM (select count(*) as total from questioner_dav a where date(a.created_at) = '".$date."' and a.qc_user_id = '" . $newData[$i]->id . "' AND kondisi_device = 'Mati' UNION select count(*) as `total` FROM (SELECT a.id,a.kondisi_device FROM questioner_dav a JOIN devices dev ON SUBSTRING(dev.serial_number,1,5) = SUBSTRING(a.SCAN_RESULT,1,5) JOIN store s ON s.id = dev.store_id JOIN shelf sh ON sh.id = dev.shelf_id JOIN qc_users qc ON qc.id = a.qc_user_id where DATE(a.created_at) = '".$date."' and a.qc_user_id = '" . $newData[$i]->id . "' AND DATE_FORMAT(a.created_at,'%Y%m%d') = substring(scan_result,6,8)
					 GROUP BY s.id,sh.id,a.qc_user_id order by a.id desc) as a where a.kondisi_device != 'Mati') a",false)->get()->first_row();
					$total = 0;
					if(count($res)>0) $total = $res->total ;					
					$newData[$i]->{$new_date} = $total;
					$newData[$i]->{$tgl} = $new_date ;
					$tgl++;
					$new_date = date('Ymd', strtotime($new_date. ' + 1 days'));
				}
			}
			$data['newData'] = $newData;
			$this->load->view('home', $data);
		}
		else if($user->status==1)
		{
			//if ($user->product_id >= '18'){
				$data['page'] = 'dashboard';
				$startDate = time();
				$data['start'] = date('Y-m-d', strtotime('-1 day', $startDate));
				$data['end'] =  date('Y-m-d', strtotime('-1 day', $startDate));
				$data['product'] = $this->db->get_where('product', array('id' => $user->product_id))->first_row();
				$data['province'] = $this->db->select("pa.id as id, pa.name as name from pv_position pv join store s on pv.store_id = s.id join variant v ON v.id = pv.variant_id join province_area pa ON pa.id = substring(s.village_area_id,1,2) where product_id = '".$user->product_id."'group by pa.id")->get()->result();
				$this->db->select("ca.id as id, ca.name as name");
				$this->db->join("store s","pv.store_id = s.id");
				$this->db->join("variant v","v.id = pv.variant_id");
				$this->db->join("city_area ca","ca.id =substring(s.village_area_id,1,4)");
				$this->db->where("product_id = '".$user->product_id."'");
				$this->db->group_by("ca.id");
				$data['city'] = $this->db->get("pv_position pv")->result();
				
				$this->db->select("sa.id as id, sa.name as name");
				$this->db->join("store s","pv.store_id = s.id");
				$this->db->join("variant v","v.id = pv.variant_id");
				$this->db->join("subdistrict_area sa","sa.id =substring(s.village_area_id,1,7)");
				$this->db->where("product_id =  '".$user->product_id."'");
				$this->db->group_by("sa.id");
				$data['subdistrict'] = $this->db->get("pv_position pv")->result(); 
				
				$product = $this->input->post('product');
				$this->db->select("va.id as id, va.name as name");
				$this->db->join("store s","pv.store_id = s.id");
				$this->db->join("variant v","v.id = pv.variant_id");
				$this->db->join("village_area va","va.id =s.village_area_id");
				$this->db->where("product_id = '".$user->product_id."'");
				$this->db->group_by("va.id");
				$data['village'] = $this->db->get("pv_position pv")->result(); 
				$data['store_type_list'] = $this->db->select("st.id as id,st.name as name from variant v join pv_position pv on v.id = pv.variant_id join store s ON s.id = pv.store_id join store_type st on st.id = s.store_type_id  where product_id = '".$user->product_id."' and s.store_type_id != '4' group by s.store_type_id",false)->get()->result();
				$data['shelf_list'] = $this->db->select("pv.shelf_id as id,sh.name as name from variant v join pv_position pv on v.id = pv.variant_id join shelf sh ON sh.id = pv.shelf_id join store s ON s.id = pv.store_id where product_id = '".$user->product_id."' AND s.store_type_id != 4 group by shelf_id",false)->get()->result();
				$this->load->view('reporting/index', $data);				
			}

		}
		else if($user->status==0)
		{
			$data['page'] = "monitoring/index";
			$id = $this->session_data->id;
			$this->db->select("d.id, d.store_id, max(datetime) as latest_ping, b.serial_number, c.name as shelf_name,e.fullname as qc");
			$this->db->join('devices b', 'a.serial_number = b.serial_number');
			$this->db->join('shelf c', 'c.id = b.shelf_id');
			$this->db->join('store d', 'd.id = b.store_id');
			$this->db->join('qc_users e','d.qc_id = e.id','left');
			$this->db->where('d.store_type_id != "4"');		
			$this->db->where('e.id = "'.$id.'"');				
			$this->db->group_by('a.serial_number');
			$this->db->order_by('datediff(max(datetime),current_date())');
			$data['res'] = $this->db->get('device_activity a')->result();			
			$this->load->view('home', $data);
		}
		*/
	}
	
	function product_report()
	{
		$this->load->model('read');
		$data['page'] = "product_report";
		$this->load->view('analytics/index', $data);
	}

	function sendStatistic()
	{
		$tid = $this->input->post('target_id');
		$sn = $this->input->post('serial_number');
		$datetime = $this->DateNow();
		$this->load->model('insert');
		$data = array('target_id' => $tid, 'serial_number' => $sn, 'datetime' => $datetime);
		$res = $this->insert->addIntoTable('activity', $data);
		header("Content-Type: application/json");
		$res = array('result' => $res);
		print_r(json_encode($res));
	}
	
	function totalActivity() {
		$total = $this->db->count_all_results('activity');
		print_r  ($total);
	}

	function ping()
	{
		$sn = $this->input->post('serial_number');
		$datetime = $this->DateNow();
		$this->load->model('insert');
		$data = array('serial_number' => $sn, 'datetime' => $datetime);
		$this->insert->addIntoTable('device_activity', $data);
	}

	function deviceRegister($sn)
	{
		//$tid = $this->input->post('brand');
		//$sn = $this->input->post('serial_number');
		$this->load->model('read');
		if($this->read->checkAvailableSerial($sn)==1)
		{
			$this->load->model('insert');
			$data = array('serial_number' => $sn, 'status' => 'good');
			$res = $this->insert->addIntoTable('devices', $data);
			echo 1;
		} else if($this->read->checkAvailableSerial($sn)==0){
			echo 0;
		}
	}

	function getLastMD5APK()
	{
		echo md5_file(base_url().'mobile_apk/DAV.apk');
	}

	function head2head()
	{
		if($this->session->userdata('is_logged_in'))
		{
			$this->load->model('read');
			//$data['geo'] = $this->read->readGeolocation();
			$data['page'] = "home/head2head";
			$this->load->view('home', $data);
		} else {
			redirect('auth', 'refresh');
		}
	}

	function submit_compare()
	{
		if($this->session->userdata('is_logged_in'))
		{
			$this->load->model('read');
			$data = $this->_get_post_data();
			$this->_print_data($data);
			die();
		} else {
			redirect('auth', 'refresh');
		}
	}
	
	function db_growth_size_daily()
	{
		$insert_db = "INSERT INTO stats.tables SELECT DATE(NOW()),TABLE_SCHEMA,TABLE_NAME,ENGINE,TABLE_ROWS,DATA_LENGTH,INDEX_LENGTH,DATA_FREE,AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES";
		$this->db->query($insert_db);
	}
	
	function insert_healthiness()
	{
		$insert_dav = "INSERT INTO device_healthiness(store_code,shelfing,serial_number,last_ping,type,status,date,time) 
				select	d.store_id,c.name, b.serial_number, max(datetime), 'DAV',
				(select CASE  WHEN datediff(max(datetime),current_date())  
				 >= 0 THEN 'Online' ELSE 'Offline' END),current_date(),DATE_FORMAT(NOW(),'%H')
				from device_activity a 
				join devices b on a.serial_number = b.serial_number join
				shelf c on c.id = b.shelf_id
				join store d on d.id = b.store_id
				WHERE c.name != 'DULT' AND 
				d.store_type_id != '4' AND
				(DATE_FORMAT(datetime, '%Y-%m-%d')  BETWEEN '2015-01-01' AND current_date())
				group by a.serial_number
				order by d.store_id";
		$insert_dult = "INSERT INTO device_healthiness(store_code,shelfing,serial_number,last_ping,type,status,date,time) 
				select	d.store_id,c.name, b.serial_number, max(datetime), 'DULT',
				(select CASE  WHEN datediff(max(datetime),current_date())  
				 >= 0 THEN 'Online' ELSE 'Offline' END),current_date(),DATE_FORMAT(NOW(),'%H')
				from device_activity a 
				join devices b on a.serial_number = b.serial_number join
				shelf c on c.id = b.shelf_id
				join store d on d.id = b.store_id
				WHERE c.name = 'DULT' AND 
				d.store_type_id != '4' AND
				(DATE_FORMAT(datetime, '%Y-%m-%d')  BETWEEN '2015-01-01' AND current_date())
				group by a.serial_number
				order by d.store_id";
		$this->db->query($insert_dav);
		$this->db->query($insert_dult);
	}
}