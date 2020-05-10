<?php

//Arquivo que vai fazer a conexão com o banco de dados
namespace App;

class Connection {


	public static function getDb(){

		try{

			$conn = new \PDO(
				"mysql:host=localhost;dbname=twitter_clone;charset=utf8",
				"root",
				""
			);

			return $conn;

		} catch(\PDOException $e){
			//
		}
	}
}


?>