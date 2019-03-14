<?php
/**
* User Controller
*/
class users extends BaseController
{
	private $user;
	function __construct($user)
	{
		$this->user = $user;
	}

	public function userLogin($data)
	{
		try
		{
			$newuser = $this->user->Checkuser($data);
			return $newuser;

		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function lastLogin($data)
	{
		try
		{
			$updateduser = $this->user->getLastLogin($data);
			return $updateduser;

		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function measureOrder($data)
	{
		try
		{
			$updateduser = $this->user->addorless($data);
			return $updateduser;

		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function deleteOrder($data)
		{
			try
			{
				$updateduser = $this->user->removeOrder($data);
				return $updateduser;

			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		}

	public function confirmPay($data)
		{
			try
			{
				$updateduser = $this->user->payOrder($data);
				return $updateduser;

			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		}

	public function getOrders($data)
		{
			try
			{
				$updateduser = $this->user->GetOrders($data);
				return $updateduser;

			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		}

	public function getTables($data)
		{
			try
			{
				$updateduser = $this->user->GetTables($data);
				return $updateduser;

			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		}

	public function getMenu($data)
			{
				try
				{
					$updateduser = $this->user->GetMenus($data);
					return $updateduser;

				}
				catch(PDOException $e)
				{
					echo $e->getMessage();
				}
			}

	    public function addOrder($data)
		{
		try
			{
			$updateduser = $this->user->NewOrder($data);
			return $updateduser;
			}
			catch(PDOException $e)
			{
			echo $e->getMessage();
			}
		}

}