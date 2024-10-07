<?php 
	class DbConnect {
		private $host = 'mxtaxdmy_WPG8B';
		private $dbName = '162.214.80.124';
		private $user = 'mxtaxdmy_WPG8B';
		private $pass = 'WQlwCEu6[D%h';

		public function connect() {
			try {
				$conn = new PDO('mysql:host=' . $this->host . '; dbname=' . $this->dbName, $this->user, $this->pass);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $conn;
			} catch( PDOException $e) {
				echo 'Database Error: ' . $e->getMessage();
			}
		}
	}
 ?>