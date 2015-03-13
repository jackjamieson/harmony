<?php
	class chatuser
	{
		public $username;
		public $roomid;
		
		public function __construct($username, $roomid)
		{
			$this->username = $username;
			$this->roomid = $roomid;
		}
	}
	
?>
