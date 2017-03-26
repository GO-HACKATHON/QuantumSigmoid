<?php


class ajax extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('analytics');
	}
	
	function get($table, $key = null, $id = null)
	{
		$res = null;
		if(!isset($key))
		{
			$res = $this->db->get($table)->result();
		}
		else
		{
			$res = $this->db->get_where($table, array($key => $id))->result();
		}
		header("Content-Type: application/json");
		print_r(json_encode($res));
	}
/* Added by Winni 17052016 */
	function store($province,$city,$subdistrict,$village) {			
		$query = "s.id as sid,store_id,store_name,pa.name as province, ca.name as city FROM store s
			JOIN village_area va ON va.id = s.village_area_id
			JOIN subdistrict_area sa ON sa.id = va.subdistrict_area_id 
			JOIN city_area ca ON ca.id = sa.city_area_id
			JOIN province_area pa ON pa.id = ca.province_area_id WHERE
			pa.id = '$province'";
		// update by winni 26092016
		if($city != 'all'){
			$query .= " AND ca.id='$city'";
		}
		if ($subdistrict != 'all'){
			$query .= " AND sa.id='$subdistrict'";
		}
		if ($village != 'all'){
			$query .= " AND va.id = '$village'";
		}
		$data = $this->db->select($query,false)->get()->result();
		header("Content-Type: application/json");
		print_r(json_encode($data));
	}
/* Added by Winni 17052016 */	

	function total_interaction()
	{
		$city_id = $this->input->post('city_id');
		$province_id = $this->input->post('province_id');
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		//$var_id = $this->input->post('variant_id');
		//$product_id = 4;$city_id = 0;$province_id = 0;$start_date = 0;$end_date = 0;
		//print_r($city_id); // update by winni 17 10 2016
		$product_id = $this->input->post('product_id'); // update by Gia 2015-09-04
		
		$var = $this->db->get_where('variant', array('product_id' => $product_id))->result();
		$this->db->select("COUNT(*) as total", FALSE);
		$this->analytics->total_interaction_raw($var, $province_id, $city_id, $start_date, $end_date);
		$res = $this->db->get()->first_row();
		
		header("Content-Type: application/json");
		print_r(json_encode(array('total' => $res->total)));
	}
	
	function monthly_average() {
		$arr = array (
			'curr_month' => date('m'),
			'curr_date' => date('d'),
			'curr_year' => date('Y')
		);
		//print_r($arr);
		
		$start_date = $arr['curr_year']."-".$arr['curr_month']."-01 00:00:00";
		$end_date = $arr['curr_year']."-".$arr['curr_month']."-".$arr['curr_date']." 23:59:00";
		//$var_id = $this->input->post('variant_id');
		//$product_id = 4;$city_id = 0;$province_id = 0;$start_date = 0;$end_date = 0;
		
		$product_id = 4; // update by Gia 2015-09-04
		$var = $this->db->get_where('variant', array('product_id' => $product_id))->row_array();
		
		$data =	$this->analytics->monthly_average($var['target_id'], $start_date, $end_date);
		//$this->_print_data($data['total']);
		$average = $data['total'] / $arr['curr_date'];
		header("Content-Type: application/json");
		print_r(json_encode(array('total' => (int)$average)));
	}
	
	function daily_average()
	{
		//$product_id = 4;
		$product_id = $this->input->post('product_id');
		$total_daily = 0;
		$var = $this->db->get_where('variant', array('product_id' => $product_id))->result();
		//$oldestDate = $this->db->distinct()->select('DATE_FORMAT(datetime, "%Y-%m-%d") as oldestDate', FALSE)->from('activity')->order_by('datetime', 'ASC')->get()->first_row();
		$city_id = $this->input->post('city_id');
		$province_id = $this->input->post('province_id');
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		//$start_date = $this->DateNow('Y-m-d').' 00:00:00';
		//$end_date = $this->DateNow();
		
		//for($i=0;$i<count($var);$i++) $this->db->where('target_id', $var[$i]->target_id);
		//$daily = $this->db->where('datetime BETWEEN "'. $start_date .'" AND "' . $end_date . '"')->group_by('DATE_FORMAT(datetime, "%Y-%m-%d"), store_id')->order_by('datetime')->get()->result();
		//$daily = $this->db->where('datetime >=', $this->DateNow('Y-m-d'))->where('store_id !=', 0)
		$this->db->select('count(datetime) as daily', FALSE);
		$this->analytics->total_interaction_raw($var, $province_id, $city_id, $start_date, $end_date);
		$daily = $this->db->group_by('s.store_id')->order_by('datetime')->get()->result();
		$average = 0;
		if(count($daily)>0)
		{
			foreach($daily as $val)$total_daily += $val->daily;
			$average = floor($total_daily/count($daily));
		}
		header("Content-Type: application/json");
		print_r(json_encode(array('total' => $average)));
	}
	
	
	function highest_interaction()
	{
		//$product_id = 4;
		$product_id = $this->input->post('product_id');
		$var = $this->db->get_where('variant', array('product_id' => $product_id))->result();
		$start_date = $this->DateNow('Y-m-d').' 00:00:00';
		$end_date = $this->DateNow();
		$this->db->select('s.store_id, s.store_name, COUNT( * ) AS total', FALSE);
		$this->analytics->total_interaction_raw($var, 0, 0, $start_date, $end_date);
		$res = $this->db->group_by('s.store_id')->order_by('total', 'desc')->limit(1)->get()->first_row();
		header("Content-Type: application/json");
		print_r(json_encode($res));
	}
	
	
	function graph_hourly_interaction()
	{
		//$product_id = 4;
		$product_id = $this->input->post('product_id');
		$date = $this->input->post('date');
		//$id = 5;
		//$date = '2015-08-11';
	
		$var = $this->db->get_where('variant', array('product_id' => $product_id))->result();
		$res = array();
		if($date=='')
		{
			$datetime = $this->DateNow();
			//$hour = 18;
			$hour = $this->DateNow('H');
			$date = $this->DateNow('Y-m-d');
			$j=0;
			for($i=0;$i<($hour+1);$i++)
			{
				$res[$j] = new stdClass();
				$res[$j]->label = $i . ':00:00';
				$this->db->select('a.datetime');
				$this->analytics->total_interaction_raw($var, 0, 0, $date .' '.$i.':00:00', $date .' '.$i.':59:59');
				$res[$j]->y = $this->db->get()->num_rows();
				$j++;
			}
		}
		else
		{
			$j=0;
			for($i=0;$i<24;$i++)
			{
				$res[$j] = new stdClass();
				$res[$j]->label = $i . ':00:00';
				$this->db->select('a.datetime');
				$this->analytics->total_interaction_raw($var, 0, 0, $date .' '.$i.':00:00', $date .' '.$i.':59:59');
				$res[$j]->y = $this->db->get()->num_rows();
				$j++;
			}
		}
	
		header("Content-Type: application/json");
		print_r(json_encode($res));
	}
	
	function hourly_interactions_detail()
	{
		//$product_id = 4;
		$product_id = $this->input->post('product_id');
		$var = $this->db->get_where('variant', array('product_id' => $product_id))->result();
		
		/*$hour_start = '11:00:00';
		$hour = explode(":", $hour_start);
		$hour_end = $hour[0] . ':59:59';
		$date = '2015-09-04';*/
		
		$hour_start = $this->input->post('hour');
		$hour = explode(":", $hour_start);
		$hour_end = $hour[0] . ':59:59';
		$date = $this->input->post('date');
		if($date == "") $date = $this->DateNow('Y-m-d');
				
		$this->db->select('s.store_id, s.store_name, COUNT(*) as total');
		$this->analytics->total_interaction_raw($var, 0, 0, $date.' '.$hour_start,  $date.' '.$hour_end);
		$this->db->group_by('s.store_id');
		$this->db->order_by('total', 'desc');
		$this->db->limit(10);
		$res = $this->db->get()->result();
		
		header("Content-Type: application/json");
		print_r(json_encode($res));
	}
	
	function graph_daily_interaction()
	{
		$province_id = $this->input->post('province_id');
		$city_id = $this->input->post('city_id');
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');$product_id = $this->input->post('product_id');
		$var = $this->db->get_where('variant', array('product_id' => $product_id))->result();
		$year = $this->DateNow('Y');
		//$year = 2014;
		
		$this->db->select('DATE_FORMAT(a.datetime, "%Y-%m-%d") as date, count(a.datetime) as total', FALSE);
		$this->analytics->total_interaction_raw($var, $province_id, $city_id, $start_date, $end_date);
		$this->db->group_by('DATE_FORMAT(a.datetime, "%Y-%m-%d")');
		
		$data = $this->db->get()->result();
		$res = array();
		$j=0;
		for($i=0;$i<count($data);$i++)
		{
			$res[$i] = new stdClass();
			$res[$i]->label = $data[$i]->date;
			$res[$i]->y = $data[$i]->total;
		}
		$z = 0;
		$newRes = array();
		if($start_date == 0 && $end_date == 0)
		{
			for($i=1;$i<=12;$i++)
			{
				$days = $this->ListOfDay($i, $year);
				for($j=0;$j<count($days);$j++)
				{
					//	echo $days[$j].'<br />';
					
					for($k=0;$k<count($res);$k++)
					{
						if($data[$k]->date==$days[$j])
						{
							$newRes[$z] = new stdClass();
							$newRes[$z]->label = $days[$j];
							$newRes[$z]->y = $data[$k]->total;
							break;
						}
						else
						{
							$newRes[$z] = new stdClass();
							$newRes[$z]->label = $days[$j];
							$newRes[$z]->y = 0;
						}
					}
					//unset($res[$any]);
					$z++;
				}
			}
		}
		else
		{
			$days = $this->ListOfDayRangeDate($start_date, $end_date);
			for($j=0;$j<count($days);$j++)
			{
				for($k=0;$k<count($res);$k++)
				{
					if($data[$k]->date==$days[$j])
					{
						$newRes[$z] = new stdClass();
						$newRes[$z]->label = $days[$j];
						$newRes[$z]->y = $data[$k]->total;
						break;
					}
					else
					{
						$newRes[$z] = new stdClass();
						$newRes[$z]->label = $days[$j];
						$newRes[$z]->y = 0;
					}
				}
				//unset($res[$any]);
				$z++;
			}
		}
		
		header("Content-Type: application/json");
		print_r(json_encode($newRes));
	}
	
	function graph_weekly_interaction()
	{
		$product_id = $this->input->post('product_id');
		$var = $this->db->get_where('variant', array('product_id' => $product_id))->result();
		//$year = '2014';
		//$month = '12';
		$year = $this->DateNow('Y');
		$month = $this->DateNow('m');
		$this->db->select('CONCAT (" Week ", WEEK(datetime, 6) - WEEK(DATE_SUB(datetime, INTERVAL DAYOFMONTH(datetime)-1 DAY), 6)+1) as label, count(*) as y', FALSE);
		$this->analytics->total_interaction_raw($var, 0, 0, 0, 0);
		$res = $this->db->where(array('YEAR(datetime)' => $year, 'MONTH(datetime)' => $month))->group_by('WEEK(datetime)')->get();
		header("Content-Type: application/json");
		print_r(json_encode($res->result()));
	}
	
	function graph_monthly_interaction()
	{
		$product_id = $this->input->post('product_id');
		$var = $this->db->get_where('variant', array('product_id' => $product_id))->result();
		//$year = '2014';
		$year = $this->DateNow('Y');
		$this->db->select('MONTHNAME(a.datetime) as label, count(*) as y', FALSE);
		$this->analytics->total_interaction_raw($var, 0, 0, 0, 0);
		$res = $this->db->where('YEAR(a.datetime)', $year)->group_by('MONTH(a.datetime)')->get();
		header("Content-Type: application/json");
		print_r(json_encode($res->result()));
	}
	
	
	function topten_by_store()
	{
		$province_id = $this->input->post('province_id');
		$city_id = $this->input->post('city_id');
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		$product_id = $this->input->post('product_id');
		$var = $this->db->get_where('variant', array('product_id' => $product_id))->result();
		
		$this->db->select('s.store_id, s.store_name, COUNT(*) as total');
		$this->analytics->total_interaction_raw($var, $province_id, $city_id, $start_date, $end_date);
		$this->db->group_by('s.store_id');
		$this->db->order_by('total', 'desc');
		$this->db->limit(10);
		$res = $this->db->get()->result();
		
		header("Content-Type: application/json");
		print_r(json_encode($res));
	}
	
	function topten_by_region()
	{
		$province_id = $this->input->post('province_id');
		$city_id = $this->input->post('city_id');
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		$product_id = $this->input->post('product_id');
		$var = $this->db->get_where('variant', array('product_id' => $product_id))->result();
		
		$this->db->select('s.store_id, s.store_name, COUNT(*) as total');
		$this->db->select('SUBSTRING(s.village_area_id, 1, 7) as sda_id', FALSE);
		$this->analytics->total_interaction_raw($var, $province_id, $city_id, $start_date, $end_date);
		$this->db->group_by('sda_id');
		$this->db->order_by('total', 'desc');
		$this->db->limit(10);
		$res = $this->db->get()->result();
		$i=0;
		foreach($res as $val)
		{
			$res[$i]->subdistrict_name = $this->db->get_where('subdistrict_area', array('id' => $val->sda_id))->first_row()->name;
			$i++;
			//$data['top10byregion'][]-> = $this->db->get_where('area_kecamatan', array('id_kabupaten' => $val->store_district_id))->first_row()-
		}
		
		header("Content-Type: application/json");
		print_r(json_encode($res));
	}
	
	function total_error_rate()
	{
		$devices = $this->db->get('devices')->result();
		$total_row = 0;
		$totalday_in_days = 0;
		for($i=0;$i<count($devices);$i++)
		{
			$act_in_days = $this->db->select('COUNT(DISTINCT HOUR(datetime))/14 AS total', FALSE)
				->from('device_activity')
				->where(array('HOUR(datetime) >=' => 8, 'HOUR(datetime) <=' => 21, 'serial_number' => $devices[$i]->serial_number))
				->group_by('DATE(datetime), serial_number')
				->get()
				->result();
			for($j=0;$j<count($act_in_days);$j++)
			{
				$totalday_in_days += $act_in_days[$j]->total;
				$total_row++;
			}
		}
		$this->db->insert('device_activity_cache', array('total' => $totalday_in_days, 'row' => $total_row, 'type' =>'DAYS', 'datetime' => $this->DateNow()));
		//echo $totalday_in_days. ' ' . $total_row . ' ' . $totalday_in_days/$total_row;
	}
	
	function total_error_rate24()
	{
		$devices = $this->db->get('devices')->result();
		$total_row = 0;
		$totalday_in_days = 0;
		for($i=0;$i<count($devices);$i++)
		{
			$act_in_days = $this->db->select('COUNT(DISTINCT HOUR(datetime))/22 AS total', FALSE)
				->from('device_activity')
				->where(array('serial_number' => $devices[$i]->serial_number))
				->group_by('DATE(datetime), serial_number')
				->get()
				->result();
				
			for($j=0;$j<count($act_in_days);$j++)
			{
				$totalday_in_days += $act_in_days[$j]->total;
				$total_row++;
			}
			
		}
		$this->db->insert('device_activity_cache', array('total' => $totalday_in_days, 'row' => $total_row, 'type' => '24', 'datetime' => $this->DateNow()));
		//echo $totalday_in_days. ' ' . $total_row . ' ' . $totalday_in_days/$total_row;
	}
	
	public function test()
	{
		for($i=1837;$i>=1687;$i--)
		{
			$this->db->where('id', $i);
			$this->db->update('store', array('id' => ($i+104)));
			echo $i.'<br />';
		}
	}
	/*
	function graph_pressconference()
	{
		$id = $this->input->post('variant_id');
		$date = $this->input->post('date');
		//$id = 5;
		//$date = '2015-08-11';
	
		$var = $this->db->get_where('variant', array('id' => $id))->first_row();
		$res = array();
		if($date=='')
		{
			date_default_timezone_set('Asia/Jakarta');
			$dt = new DateTime();
			$datetime = $dt->format('Y-m-d H:i:s');
			//$time = explode(" ", $datetime)[1];
			$hour = $dt->format('H');
			//$hour = 18;
			//$date = '2014-12-05';
			$date = $dt->format('Y-m-d');
			//echo $hour;
				
			$j=0;
			for($i=6;$i<($hour+1);$i++)
			{
			$res[$j] = new stdClass();
			$res[$j]->label = $i . ':00:00';
					$res[$j]->y = $this->db->select('datetime')->from('activity')->where('target_id', $var->target_id)->where('DATE_FORMAT(datetime, "%Y-%m-%d %H:%m:%s") BETWEEN "' . $date .' '.$i.':00:00" AND "' . $date . ' ' . $i . ':59:59"')->get()->num_rows();
					$j++;
			}
		}
		else
		{
			$j=0;
			for($i=6;$i<24;$i++)
			{
				$res[$j] = new stdClass();
				$res[$j]->label = $i . ':00:00';
				$res[$j]->y = $this->db->select('datetime')->from('activity')->where('target_id', $var->target_id)->where('DATE_FORMAT(datetime, "%Y-%m-%d %H:%m:%s") BETWEEN "' . $date .' '.$i.':00:00" AND "' . $date . ' ' . $i . ':59:59"')->get()->num_rows();
				$j++;
			}
		}
	
		header("Content-Type: application/json");
		print_r(json_encode($res));
	}*/

}
