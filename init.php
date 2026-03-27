<?php

    class Dbh
    {

        private $servername;
        private $username;
        private $password;
        private $dbname;
        private $charset;

        public function connect()
        {
			$config = parse_ini_file("../db.ini");

            try
            {
                $pdo = new PDO($config['dsn'], $config['username'], $config['password']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //echo "Successfully connected!";
                return $pdo;
            }
            catch (Exception $e)
            {
                echo "Connection failed: ".$e->getMessage();
            }
        }

    }

?>
