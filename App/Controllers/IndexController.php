<?php

namespace App\Controllers;

# Importando as classes

//Recursos do mini
use  MF\Controller\Action;
use MF\Model\Container;

//Os models


class IndexController extends Action {

	public function index() {

		$this->view->login = isset($_GET['login']) ? $_GET['login']: '';
		$this->render('index');
	}

	public function inscreverse() {

		$this->view->usuario = array(
				'nome' => '', 
				'email' => '',
				'senha' => ''
			);
		$this->view->erroCadastro = false;

		$this->render('inscreverse');
	}

	public function registrar(){
		//Receber dados do form,fazer a conexão com o BD, mandar os dados para o BD
		
		//print_r($_POST);
		$usuario = Container::getModel('Usuario');
		$usuario->__set('nome', $_POST['nome']);
		$usuario->__set('email', $_POST['email']);
		$usuario->__set('senha', md5($_POST['senha']));
		//Debug
		# print_r($usuario);

		//Sucesso
		if($usuario->validaCadastro() && count($usuario->getUserPorEmail()) == 0){
				
				$usuario->salvar();
				$this->render('cadastro');	
		}else {
			
			$this->view->usuario = array(
				'nome' => $_POST['nome'], 
				'email' => $_POST['email'],
				'senha' => $_POST['senha']
			);

			$this->view->erroCadastro = true;

			$this->render('inscreverse');
		}
		
		
	}
}


?>