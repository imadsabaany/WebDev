<?php

class Session{
	
	private $logged_in=false;
	public $username;
	
	
	function __construct() {
		session_start();
		$this->check_login();
	}
	
	public function is_logged_in() {
		return $this->logged_in;
  	}
	public function login($user) {
    // database should find user based on username/password
    	if($user){
      	$this->username = $_SESSION['username'] = $user->username;
      	$this->logged_in = true;
    	}
  	}
	
	public function logout() {
    	unset($_SESSION['username']);
    	unset($this->username);
    	$this->logged_in = false;
  	}
	
	private function check_login() {
    if(isset($_SESSION['username'])) {
      $this->username = $_SESSION['username'];
      $this->logged_in = true;
    } else {
      unset($this->username);
      $this->logged_in = false;
    }
  }

}

$session = new Session();

?>
