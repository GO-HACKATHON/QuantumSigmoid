<?php
class User extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->authCheck();
		$this->statusCheck(3);
	}

	function index()
	{
		$data['page'] = 'user/index';
		$user = $this->db->get_where('users', array('status !=' => 3))->result();
		$data['product'] = $this->db->get('product')->result();
		$data['os_devices'] = $this->db->get('os_devices')->result();
		for($i=0;$i<count($user);$i++)
		{
			if($user[$i]->product_id!=0)
				$user[$i]->product_name = $this->db->get_where('product', array('id' =>$user[$i]->product_id))->first_row()->name;
			else if(($user[$i]->product_id==0) && ($user[$i]->status==4))
				$user[$i]->product_name = '<i>HR</i>';
			else
				$user[$i]->product_name = '<i>Administrator</i>';
		}
		$data['user'] = $user;
		//$this->_print_data($data);die();
		$this->load->view('home', $data);
	}


	function add()
	{
		$text = 0;
		$data = $this->input->post();
		if($data['repassword'] != '' || $data['password'] != '')
		{
			if($data['repassword']!=$data['password'])
			{
				$this->message->show('Password not same on Re-Enter Password', 'warning'); 
				$text++;
			}
		}
		else
		{
			$this->message->show('Password cant be blank'); 
			$text++;
		}
		
		$product_id = $data['product_id'];
		$username = $data['username'];
		$check = array();
		
		$check_username = $this->db->get_where('users', array('username' => $username))->result();
		if(count($check_username) == 0) {
			if(($product_id!=0)&& ($product_id!='hr'))
			{
				$check = $this->db->get_where('users', array('product_id' => $product_id))->first_row();
			}
			else
			{
				$data['status'] = 2;
				$text = 0;
			}
			if((count($check)>0) && ($product_id!='hr'))
			{
				$product = $this->db->get_where('product', array('id' => $product_id))->first_row();
				$this->message->show('Brand ' . $product->name . ' already taken', 'warning');
				$text++;
			}
			else if($product_id=='hr')
			{
				$data['status'] = 4;
				$text = 0;
			}
			else if($product_id > 0)
			{
				$data['status'] = 1;
				$text = 0;
			}
			if($text==0)
			{
				unset($data['repassword']);
				$data['password'] = md5($data['password']);
				if($this->db->insert('users', $data))
				{
					$this->message->show('User with username \''. $data['username'] .'\' has been added'); 
				}
			}
			
		}
		else{
			$this->message->show('Username ' . $data['username']. ' already taken', 'warning');
		}
		//print_r($data);die();
		redirect('user');
	}

	function edit($id)
	{
		$data['page'] = "user/user_edit";
		$data['user'] = $this->db->get_where('users', array('id' => $id))->first_row();
		$data['product'] = $this->db->get('product')->result();
		$this->load->view('home', $data);
	}

	function update()
	{
		$data = $this->input->post();
		$id = $data['id'];
		//unset($data['id']);
		$text = 0;
		
		if($data['repassword'] != '' || $data['password'] != '')
		{
			if($data['repassword']!=$data['password'])
			{
				$this->message->show('Password not same on Re-Enter Password', 'warning'); 
				$text =1;
			}
		}
		else
		{
			$text = 0;
		}
		
		$product_id = $data['product_id'];
		$check = array();
		if($product_id!=0)
		{
			$check = $this->db->get_where('users', array('product_id' => $product_id))->first_row();
		}
		else
		{
			$data['status'] = 2;
		}
		if((count($check)>0) && ($product_id!='hr'))
		{
			$product = $this->db->get_where('product', array('id' => $product_id))->first_row();
			$this->message->show('Brand ' . $product->name . ' already taken', 'warning'); 
			$text = 1;
		}
		else if($product_id=='hr')
		{
			$data['status'] = 4;
			$text = 0;
		}
		
		//echo $this->total_message->show();
		
		if($text == 0)
		{
			unset($data['repassword']);
			$user = $this->db->get_where('users', array('id' => $id))->first_row();
			if($user->password ==$data['password']){
				$data['password'] = $data['password'];
			}
			else{
				$data['password'] = md5($data['password']);
			}
			$this->db->where('id', $id);
			if($this->db->update('users', $data))
			{
				$this->message->show('User with username \''. $user->username .'\' has been updated'); 
				
			}
		}
		redirect('user');
		
	}

	function delete($id)
	{
		$this->db->where('id', $id);
		if($this->db->delete('user'))
		{
			$this->message->show('User with username \''. $data['username'] .'\' has been deleted'); 
		}
		redirect('user');
	}

}