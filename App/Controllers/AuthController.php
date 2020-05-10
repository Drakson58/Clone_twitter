<?php

namespace App\Controllers;

# Importando as classes

//Recursos do mini
use  MF\Controller\Action;
use MF\Model\Container;

//Os models


class AuthController extends Action {

	public function autenticar(){
		echo "opaopa";
		$usuario = Container::getModel('usuario');
		$usuario->__set('email', $_POST['email']);
		$usuario->__set('senha', md5($_POST['senha']));

		$usuario->autenticar();
		
		if($usuario->__get('id') != '' && $usuario->__get('nome')){
			
			session_start();

			$_SESSION['id'] = $usuario->__get('id');
			$_SESSION['nome'] = $usuario->__get('nome');

			header('Location: /timeline');
		}else {
			header('Location: /?login=erro');
		}
	}

	public function sair(){
		
		session_start();
		session_destroy();
		header('Location: /');
	}
}


?>