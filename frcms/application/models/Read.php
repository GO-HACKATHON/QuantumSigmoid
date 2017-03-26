<?php 
class Read extends CI_Model
{
	
	function getTotalDevicePerLocation($location_id)
	{
		$this->db->select("*");
		$this->db->from("store_locations a");
		$this->db->join("location_phone b", "a.id = b.location_id");
		$this->db->where("a.id", $location_id);
		$q = $this->db->get();
		return $q->num_rows();
	}
	
	function getTotalContentPerLocation($location_id)
	{
		//SELECT * FROM location_phone a join devices b on a.phone_id = b.id join activity c on c.serial_number = b.serial_number join marker_content d on d.target_id = c.target_id where a.id = 1
		$this->db->distinct();
		$this->db->select("d.content_id");
		$this->db->from("location_phone a");
		$this->db->join("devices b", "a.phone_id = b.id");
		$this->db->join("activity c", "c.serial_number = b.serial_number");
		$this->db->join("marker_content d", "d.target_id = c.target_id");
		$this->db->where("a.location_id", $location_id);
		$q = $this->db->get();
		return $q->num_rows();
	}
	
	function getTotalInteractionPerLocation($location_id)
	{
		$this->db->select("c.serial_number");
		$this->db->from("location_phone a");
		$this->db->join("devices b", "a.phone_id = b.id");
		$this->db->join("activity c", "c.serial_number = b.serial_number");
		$this->db->where("a.location_id", $location_id);
		$q = $this->db->get();
		return $q->num_rows();
	}
	
	
	function checkAvailableSerial($sn)
	{
		$SQL = "SELECT * FROM devices WHERE serial_number = '" . $sn . "'";
		$query = $this->db->query($SQL);
		if ($query->num_rows > 0)
			return 0;
		else
			return 1;
	}
	
	
			
	function checkMobileVersion($sn)
	{
		$modul = 'DAV';
		
		// get last version at modul
		$this->db->select('id, version');
		$this->db->from('mobile_version');
		$this->db->where('modul', $modul);
		$this->db->order_by('number_ver', 'desc');
		$query = $this->db->get();
		//$version = $query->first_row()->version;
		$version = $query->result();
		$mobile_version_id = $query->first_row()->id;
		
		// get mobile device id by serial number
		$this->db->select('id');
		$this->db->from('devices');
		$this->db->where('serial_number', $sn);
		$query = $this->db->get();
		$device_id = $query->first_row()->id;
		
		// get date and time now
		date_default_timezone_set('Asia/Jakarta');
		$dt = new DateTime();
		$datetime = $dt->format('Y-m-d H:i:s');
		
		// check last update of current device
		$this->db->select('*');
		$this->db->from('mobile_update_log');
		$this->db->where(array('device_id' => $device_id, 'mobile_version_id' => $mobile_version_id));
		$query = $this->db->get();
		if($query->num_rows()==0)
		{
			// if nothing insert first record
			return $version[0];
		}
		else
		{
			return 0;
			/*$this->db->select('*');
			$this->db->from('mobile_update_log');
			$this->db->where(array('device_id' => $device_id, 'mobile_version_id' => $mobile_version_id));
			$query = $this->db->get();
			if($query->num_rows()==0)
			{
				
			}*/
		}
	}
	
	function mobile_version($id = null)
	{
		return $this->GetTableProcedure('mobile_version', $id);
	}
	
	function GetTableProcedure($table, $id = null)
	{
		$this->db->select('*');
		$this->db->from($table);
		if(isset($id))
		{
			if(!is_array($id))
			{
				$this->db->where('id', $id);
				return $this->db->get()->first_row();
			}
			else
			{
				$this->db->where($id);
				return $this->db->get()->result();
			}
		}
		else
		{
			return $this->db->get()->result();
		}
	}
	
	
	function config($config_name)
	{
		$this->db->select('*');
		$this->db->from('config');
		$this->db->where('name', $config_name);
		return $this->db->get()->first_row();
	}

	function brand_activity(){
		$SQL = "SELECT a.target_id, a.serial_number, a.datetime, b.name as variant_name, c.name as product_name, d.name as brand_name 
				FROM `activity` a 
				JOIN `variant` b ON a.target_id = b.target_id 
				JOIN `product` c ON c.id = b.product_id
				INNER JOIN `brand` d ON d.id = c.brand_id
				ORDER BY a.serial_number";
		
		$SQL2 = "SELECT b.id as variant_id, b.name as variant_name, c.id as product_id, c.name as product_name, d.name as brand_name 
				FROM `activity` a 
				JOIN `variant` b ON a.target_id = b.target_id 
				JOIN `product` c ON c.id = b.product_id
				INNER JOIN `brand` d ON d.id = c.brand_id
				GROUP BY b.name, c.name, d.name ";
		
		$SQuery = $this->db->query($SQL2);
		$query = $this->db->query($SQL);
		if ($query->num_rows > 0) {
			foreach ($query->result_array() as $val) {
				$totalReco[] = $val['product_name'];
			}
			$counts = array_count_values($totalReco);
			
			if ($SQuery->num_rows > 0) {
				foreach ($SQuery->result_array() as $v) {
					$data['variant_id'] = $v['variant_id'];
					$data['product_id'] = $v['product_id'];
					$data['brand_name'] = $v['brand_name'];
					$data['product_name'] = $v['product_name'];
					$data['variant_name'] = $v['variant_name'];
					$data['total_reco'] = $counts[$v['product_name']];
					$post[] = $data;
				}
			}
			return $post;
		}
	}
	
	function brand_activity_detail($product_id, $variant_id) {
		$SQL = "SELECT a.serial_number, c.name as product_name, d.name as brand_name
				FROM `activity` a 
				JOIN `variant` b ON a.target_id = b.target_id 
				JOIN `product` c ON c.id = b.product_id
				INNER JOIN `brand` d ON d.id = c.brand_id
				INNER JOIN `devices` e ON a.serial_number = e.serial_number
				WHERE c.id = $product_id AND b.id = $variant_id";
		
		$SQL2 = "SELECT a.serial_number, a.target_id, b.id as variant_id, c.id as product_id, c.name as product_name, d.name as brand_name, f.id as store_id_int, f.store_id, f.store_name, g.name as shelf_name,
				h.nama as province
				FROM `activity` a
				JOIN `variant` b ON a.target_id = b.target_id
				JOIN `product` c ON c.id = b.product_id
				INNER JOIN `brand` d ON d.id = c.brand_id
				INNER JOIN `devices` e ON a.serial_number = e.serial_number
				INNER JOIN `store` f ON e.store_id = f.id
				INNER JOIN `shelf` g ON e.shelf_id = g.id
				INNER JOIN `area_provinsi` h ON f.store_province_id = h.id
				WHERE c.id = $product_id AND b.id = $variant_id
				GROUP BY f.store_id";
		
		$SQuery = $this->db->query($SQL2);
		$query = $this->db->query($SQL);
		
		if ($query->num_rows > 0) {
			//return $query->result();
			foreach ($query->result_array() as $val) {
				$totalReco[] = $val['product_name'];
			}
			//print_r (count($totalReco));
			$counts = array_count_values($totalReco);
			if ($SQuery->num_rows > 0) {
				foreach ($SQuery->result_array() as $v) {
					$sq = "SELECT COUNT( * ) as totalReco FROM  `activity` a
								INNER JOIN `devices` e ON a.serial_number = e.serial_number
								WHERE target_id =  '".$v['target_id']."' AND e.store_id ='".$v['store_id_int']."'";
					$q = $this->db->query($sq);
					
					$data['serial_number'] = $v['serial_number'];
					$data['product_name'] = $v['product_name'];
					$data['brand_name'] = $v['brand_name'];
					$data['store_id'] = $v['store_id'];
					$data['store_name'] = $v['store_name'];
					$data['variant_id'] = $v['variant_id'];
					$data['product_id'] = $v['product_id'];
					$data['province'] = $v['province'];
					$data['total_reco'] = $total;
					$post[] = $data;
				}
			}
			return $post;
		}
	}
	
	function brand_activity_more_detail($product_id, $variant_id, $store_id) {
		$SQL = "SELECT a.serial_number, c.name as product_name, d.name as brand_name
				FROM `activity` a 
				JOIN `variant` b ON a.target_id = b.target_id 
				JOIN `product` c ON c.id = b.product_id
				INNER JOIN `brand` d ON d.id = c.brand_id
				INNER JOIN `devices` e ON a.serial_number = e.serial_number
				WHERE c.id = $product_id AND b.id = $variant_id";
	
	
		$SQL2 = "SELECT a.serial_number, a.datetime, c.name as product_name, d.name as brand_name, f.store_id, f.store_name, g.name as shelf_name
		FROM `activity` a
		JOIN `variant` b ON a.target_id = b.target_id
		JOIN `product` c ON c.id = b.product_id
		INNER JOIN `brand` d ON d.id = c.brand_id
		INNER JOIN `devices` e ON a.serial_number = e.serial_number
		INNER JOIN `store` f ON e.store_id = f.id
		INNER JOIN `shelf` g ON e.shelf_id = g.id
		WHERE c.id = $product_id AND b.id = $variant_id AND f.store_id = '".$store_id."'";
	
		$SQuery = $this->db->query($SQL2);
		$query = $this->db->query($SQL);
	
		if ($query->num_rows > 0) {
			//return $query->result();
			foreach ($query->result_array() as $val) {
				$totalReco[] = $val['product_name'];
			}
			$counts = array_count_values($totalReco);
				if ($SQuery->num_rows > 0) {
				foreach ($SQuery->result_array() as $v) {
					$data['serial_number'] = $v['serial_number'];
					$data['product_name'] = $v['product_name'];
					$data['brand_name'] = $v['brand_name'];
					$data['store_id'] = $v['store_id'];
					$data['store_name'] = $v['store_name'];
					$data['datetime'] = $v['datetime'];
					$data['shelf_name'] = $v['shelf_name'];
					$post[] = $data;
				}
			}
			return $post;
		}
	}
	
	function highest_interaction() {
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('read');
		$today = date('Y-m-d');
		//echo $today;
		/*$SQL = 'SELECT c.id, c.store_id, c.store_name, b.serial_number, COUNT( * ) AS total
				FROM  `activity` a
				JOIN  `devices` b ON a.serial_number = b.serial_number
				JOIN  `store` c ON b.store_id = c.id
				WHERE  `datetime` LIKE "%2015-07-14%"
				GROUP BY  `store_id`
				ORDER BY  `total` DESC
				LIMIT 1';*/
		
		$result = $this->db->select('c.id, c.store_id, c.store_name, b.serial_number, COUNT( * ) AS total', FALSE)
			->from('activity a')
			->join('devices b', 'a.serial_number = b.serial_number')
			->join('store c', 'b.store_id = c.id')
			->like('datetime', $today, 'both')
			->group_by('c.store_id')
			->order_by('total', 'desc')
			->limit(1)
			->get()
			->row_array();
		
		return $result;
	}

	function isLoginExist($username, $password) {
		$SQL = "SELECT * FROM `qc_users` WHERE username = '".$username."' AND password = '".$password."'";
		$query = $this->db->query($SQL);
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	function getUserByName($username){
		$SQL = "SELECT * FROM `qc_users` WHERE `username` = '".$username."'";
		$query = $this->db->query($SQL);
		
		return $query->row();
	}

	function getStoreByName($store_name){
		$SQL = "SELECT * FROM `store` WHERE `store_name` = '".$store_name."'";
		$query = $this->db->query($SQL);
		
		return $query->row();
	}
	function getAllStore(){
		$sql = "SELECT `store_name`, `store_id` FROM `store` ";

		$query = $this->db->query($sql);
		//die($query->num_rows()) ;
		
		if($query->num_rows() > 0){
			return $query->result();
		}
	}
	function getAllShelf(){
		$sql = "SELECT `id`, `name` FROM `shelf` ";

		$query = $this->db->query($sql);
		//die($query->num_rows()) ;
		
		if($query->num_rows() > 0){
			return $query->result();
		}
	}
	function getShelfByName($name){
		$SQL = "SELECT * FROM `shelf` WHERE `name` = '".$name."'";
		$query = $this->db->query($SQL);
		
		return $query->row();
	}
	

	
}
?>