<?php
class user extends model
{
	private $db;

	function __construct()
	{
		$this->db = $this->connect();
	}

	//Check the user name and password
	public function Checkuser($data)
	{
		$stmt = $this->db->prepare("SELECT * FROM users WHERE username=:username AND password=:password LIMIT 1");
		$stmt->execute(array(':username'=>$data['username'],':password'=>$data['password']));
		if ($stmt->rowCount() > 0) {
			$user_id=$stmt->fetch(PDO::FETCH_ASSOC);

			$profile = $this->db->prepare("INSERT INTO shop_employee(user_id, today_date)VALUES(:userid, :today_date)");
			$times= date('Y-m-d');
			$profile->bindparam(":userid", $user_id["id"]);
			$profile->bindparam(":today_date",$times);
			$profile->execute();
			$returndata['status']='success';
			$returndata['response']=$user_id;
			return $returndata;
		}else{
			$returndata['status']='error';
			$returndata['response']='No record found';
			return $returndata;
		}
	}

	public function getLastLogin($data)
	{
		$stmt = $this->db->prepare("SELECT * FROM users WHERE username=:username LIMIT 1");
		$stmt->execute(array(':username'=>$data['username']));
		if ($stmt->rowCount() > 0) {
			$user_id=$stmt->fetch(PDO::FETCH_ASSOC);
		$stmt = $this->db->prepare("SELECT user_id,today_date,logged_in,name FROM shop_employee
				JOIN users ON shop_employee.user_id = users.id
				WHERE users.id=:username");
		$stmt->execute(array(':username'=>$user_id["id"]));
		if ($stmt->rowCount() > 0) {
			$user_details=$stmt->fetchAll(PDO::FETCH_ASSOC);
			$returndata['status']='success';
			$returndata['response']=$user_details;
			return $returndata;
		}else{
			$returndata['status']='error';
			$returndata['response']='No record found';
			return $returndata;
		}
	}else{
			$returndata['status']='error';
			$returndata['response']='No record found';
			return $returndata;
		}
	}



	public function GetTables($data)
	{
		$stmt = $this->db->prepare("SELECT * FROM shop_tables");
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			$tables=$stmt->fetchAll(PDO::FETCH_ASSOC);
			$returndata['status']='success';
			$returndata['response']=$tables;
			return $returndata;
		}else{
			$returndata['status']='error';
			$returndata['response']='No record found';
			return $returndata;
		}
	}

		public function GetOrders($data)
		{
			$stmt = $this->db->prepare("SELECT * FROM shop_orders WHERE  oder_table=:oder_table");
			$stmt->execute(array(':oder_table'=>$data['oder_table']));
			if ($stmt->rowCount() > 0) {
				$tables=$stmt->fetchAll(PDO::FETCH_ASSOC);
				$returndata['status']='success';
				$returndata['response']=$tables;
				return $returndata;
			}else{
				$returndata['status']='error';
				$returndata['response']='No record found';
				return $returndata;
			}
		}


	public function GetMenus($data)
		{
			$stmt = $this->db->prepare("SELECT * FROM shop_menu");
			$stmt->execute();
			$menustmt = $this->db->prepare("SELECT DISTINCT menu_category FROM shop_menu");
			$menustmt->execute();
			if ($stmt->rowCount() > 0) {
				$tables=$stmt->fetchAll(PDO::FETCH_ASSOC);
				$menus=$menustmt->fetchAll(PDO::FETCH_ASSOC);
				$returndata['status']='success';
				$returndata['response']=$tables;
				$returndata['categories']=$menus;
				return $returndata;
			}else{
				$returndata['status']='error';
				$returndata['response']='No record found';
				return $returndata;
			}
		}

	public function addorless($data)
			{
					$stmt = $this->db->prepare("UPDATE shop_orders SET order_quanitiy=:order_quanitiy, order_amount=:order_amount WHERE order_id=:order_id");
					$stmt->bindparam(":order_quanitiy", $data['order_quanitiy']);
					$stmt->bindparam(":order_amount",$data["order_amount"]);
					$stmt->bindparam(":order_id",$data["order_id"]);
					if ($stmt->execute()) {
						$returndata['status']='success';
						return $returndata;
					}else{
						$returndata['status']='error';
						return $returndata;
					}
			}

	public function removeOrder($data)
			{
				for($i=0; $i<count($data['orders']); $i++) {
    			$stmt = $this->db->prepare("DELETE FROM shop_orders WHERE order_id=:order_id");
				$stmt->bindparam(":order_id",$data['orders'][$i]['order_id']);	
				if($stmt->execute()){

				}else{
					$returndata['status']='error';
					return $returndata;
				}
				}
				$returndata['status']='success';
				return $returndata;
			}

	public function payOrder($data)
		{
    		$stmt = $this->db->prepare("DELETE FROM shop_orders WHERE oder_table=:oder_table");
			$stmt->bindparam(":oder_table",$data['oder_table']);	
			if($stmt->execute()){
				$profile = $this->db->prepare("INSERT INTO shop_pay(order_table,order_amount,order_type)VALUES(:oder_table, :order_amount,:order_type)");
				$profile->bindparam(":oder_table", $data["oder_table"]);
				$profile->bindparam(":order_amount",$data["order_amount"]);
				$profile->bindparam(":order_type", $data["order_type"]);
				if($profile->execute()){

					$stmt_set = $this->db->prepare("UPDATE shop_tables SET status=:status WHERE table_no=:order_id");
					$status='NONE';
					$stmt_set->bindparam(":status",$status);
					$stmt_set->bindparam(":order_id",$data["oder_table"]);
					if($stmt_set->execute()){
					$returndata['status']='success';
					return $returndata;
					}else{
						$returndata['status']='error';
				return $returndata;	
					}
				}else{
				$returndata['status']='error';
				return $returndata;
				}
			}else{
				$returndata['status']='error';
				return $returndata;
			}
			
		}

	public function NewOrder($data)
		{

			$stmt = $this->db->prepare("UPDATE shop_tables SET status=:status WHERE table_no=:table_no");
			$stmt->bindparam(":table_no", $data['oder_table']);
			$stmt->bindparam(":status",$data["order_status"]);
			if ($stmt->execute()) {
			$stmt_check = $this->db->prepare("SELECT * FROM shop_orders WHERE  oder_table=:oder_table AND order_name=:order_name");
			$stmt_check->execute(array(':oder_table'=>$data['oder_table'],
									   ':order_name'=>$data['order_name']));
			if ($stmt_check->rowCount() > 0) {
					$orders=$stmt_check->fetch(PDO::FETCH_ASSOC);
					$stmt_update = $this->db->prepare("UPDATE shop_orders SET order_quanitiy=:order_quanitiy, order_amount=:order_amount WHERE order_id=:order_id");
					$quantites= $orders['order_quanitiy']+$data['order_quanitiy'];
					$stmt_update->bindparam(":order_quanitiy",$quantites);
					$prices=$orders['order_amount']+$data["order_amount"];
					$stmt_update->bindparam(":order_amount",$prices);
					$stmt_update->bindparam(":order_id",$orders["order_id"]);
					if ($stmt_update->execute()) {

					$this->removeInventory($data["order_name"],$data["order_quanitiy"],0);
						
					$returndata['status']='success';
					return $returndata;
					}else{
					$returndata['status']='error';
					return $returndata;
					}
				}else{	

				$profile = $this->db->prepare("INSERT INTO shop_orders(order_name, order_quanitiy,order_amount,oder_table,order_status,order_type)VALUES(:order_name, :order_quanitiy,:order_amount,:oder_table,:order_status,:order_type)");
				$profile->bindparam(":order_name", $data["order_name"]);
				$profile->bindparam(":order_quanitiy",$data["order_quanitiy"]);
				$profile->bindparam(":order_amount", $data["order_amount"]);
				$profile->bindparam(":oder_table", $data["oder_table"]);
				$profile->bindparam(":order_status",$data["order_status"]);
				$profile->bindparam(":order_type", $data["order_type"]);
				if ($profile->execute()) {
					$this->removeInventory($data["order_name"],$data["order_quanitiy"],0);
					$returndata['status']='success';
				return $returndata;
			}else{
				$returndata['status']='error';
				return $returndata;
			}
		    }
		}else{
				$returndata['status']='error';
				return $returndata;
			}
		}


		public function removeInventory($inv_name,$inv_quantity,$operation)
		{
			$stmt_check = $this->db->prepare("SELECT * FROM shop_inventory WHERE inventory_name=:inventory_name");
			$stmt_check->execute(array(':inventory_name'=>$inv_name));
			if ($stmt_check->rowCount() > 0) {
				$orders=$stmt_check->fetch(PDO::FETCH_ASSOC);
				if ($operation==0)
				$quantites= $orders['inventory_quantity']-$inv_quantity;	
				else
				$quantites= $orders['inventory_quantity']+$inv_quantity;	

				$stmt_update = $this->db->prepare("UPDATE shop_inventory SET inventory_quantity=:inventory_quantity WHERE inventory_name=:inventory_name");
					$stmt_update->bindparam(":inventory_name",$inv_name);
					$stmt_update->bindparam(":inventory_quantity",$quantites);
					if ($stmt_update->execute()) {
					$returndata['status']='success';
					return $returndata;
					}else{
					$returndata['status']='error';
					return $returndata;
					}
			}
		}
}
?>