<?php 
class common extends CI_Model
{
	function db()
	{
		return $this->db;
	}
	
	function GetTableProcedure($select = null, $table, $id = null)
	{
		if(isset($select))
		{
			if(is_array($select))
			{
				$select_text = '';
				for($i=0;$i<count($select);$i++)
				{
					if($i==count($select)-1)
						$select_text .= $select[$i];
					else
						$select_text .= $select[$i].', ';
				}
				$this->db->select($select_text);
			}
		}
		else
		{
			$this->db->select('*');
		}
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
	
	function get($table_name, $id = null, $select = null)
	{
		return $this->GetTableProcedure($select, $table_name, $id);
	}
	
}