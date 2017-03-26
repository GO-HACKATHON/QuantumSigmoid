<?php

class dwsm extends CI_Model
{
	
	function variant($sn)
	{
		$id = $this->db->get_where('devices', array('serial_number' => $sn))->first_row();
		
		$this->db->select('variant_id');
		$this->db->from('pv_position');
		$this->db->where(array('store_id' => $id->store_id, 'shelf_id' => $id->shelf_id));
		$query = $this->db->get();
		$data = $query->result();
		$newData = array();
		$this->load->model('read');
		for($i=0;$i<count($data);$i++)
		{
			$newData[$i] = $data[$i]->variant_id;
		}
		//print_r($newData);die();
		return $newData;
	}
	
	
    function content($id = null, $type = "ByTarget")
    {
        if($type=="ByTarget")
        {
			$this->db->select('id');
			$this->db->from('variant');
			$this->db->where('target_id', $id['target_id']);
			$data = $this->db->get()->first_row();
			$variant_id = $data->id;
			
			$this->db->select('*');
			$this->db->from('pv_position');
		
			$this->db->where(array('store_id' => $id['store_id'], 'variant_id' => $variant_id, 'shelf_id' => $id['shelf_id']));
			//$this->db->where(array('store_location_id' => $id['store_location_id'], 'rack_id' => $id['rack_id']));
			if($this->db->get()->num_rows()>0)
			{
				$this->db->select('b.id as ContentID, b.name as Name, c.name as TargetName, b.px, b.py, b.pz, b.rx, b.ry, b.rz, b.sx, b.sy, b.sz');
				$this->db->from('target_content a');
				$this->db->join('content b', 'a.content_id = b.id');
				if(isset($id))
				{
					$this->db->where('a.target_id', $id['target_id']);
				}
				return $this->db->get()->result();
			}
			else
				return null;
        }
        else if($type=="ByStoreAndRack")
        {
            if(is_array($id))
            {
				//$this->db->distinct();
                $this->db->select('c.id as ContentID, c.name as Name, t.name as TargetName, c.scene_name as SceneName, CONCAT(tc.px,";",tc.py,";",tc.pz) as pos, CONCAT(tc.rx,";",tc.ry,";",tc.rz) as rot, CONCAT(tc.sx,";",tc.sy,";",tc.sz) as scale', FALSE);
                $this->db->from('pv_position pv');
				$this->db->join('variant v', 'pv.variant_id = v.id');
                $this->db->join('target t', 'v.target_id = t.target_id');
                $this->db->join('target_content tc', 't.target_id = tc.target_id');
                $this->db->join('content c', 'c.id = tc.content_id');
                $this->db->where($id);
                return $this->db->get()->result();
            }
        }
    }
}

?>