<?php
	$json = file_get_contents('php://input');
	$obj = json_decode($json,true);
	require_once('models/model.class.php');
	require_once('models/user.class.php');
	require_once('controllers/basecontroller.class.php');
	require_once('controllers/users.class.php');

	$register_model = new user();
	$register_controller = new users($register_model);


	if (isset($obj)) {
		$obj = $register_model->sanatize_post($obj);
		if (!isset($obj['user_id']) || $obj['user_id']=='') {
			$returndata['status']='error';
			$returndata['response']='user_id is required !';
		}
		else{
			$returndata=$register_controller->confirmPay($obj);
		}


	}else{

		$returndata['status']='error';
		$returndata['response']='Access Denied with empty request !';
	}

	echo json_encode($returndata);

 ?>