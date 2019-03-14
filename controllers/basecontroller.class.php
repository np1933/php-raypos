<?php
/**
* Base Controller
*/
class BaseController
{

	function __construct()
	{
		# code...
	}

	public function redirect($url)
	{
		header("Location: $url");
	}

}


?>