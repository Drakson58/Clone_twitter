<?php

namespace App\Controllers;

# Importando as classes

//Recursos do mini
use  MF\Controller\Action;
use MF\Model\Container;

//Os models


class AppController extends Action {

	public function timeline(){

		if($this->validaAutenticacao()) {

			//Recuperar os tweets
			$tweet = Container::getModel('Tweet');
			$tweet->__set('id_usuario', $_SESSION['id']);
			$this->view->tweets = $tweet->getAll();

			$this->render('timeline');
		}else {
			header('Location: /?login=erro');
		}
	}

	
	public function tweet(){

		if($this->validaAutenticacao()) {

			$tweet = Container::getModel('Tweet');
			$tweet->__set('tweet', $_POST['tweet']);
			$tweet->__set('id_usuario', $_SESSION['id']);
			$tweet->salvar();

			header('Location: /timeline');
		}else {
			header('Location: /?login=erro');
		}
		
	}

	public function validaAutenticacao(){
		
		session_start();

		if($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
			return true;
		}else {
			header('Location: /?login=erro');
		}
	}

	public function quem_seguir(){

		if($this->validaAutenticacao()){

			$pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';
			$usuarios = array();
			if($pesquisarPor != ''){

				$usuario = Container::getModel('Usuario');
				$usuario->__set('nome', $pesquisarPor);
				$usuario->__set('id', $_SESSION['id']);
				$usuarios = $usuario->pesquisar();
			}
			$this->view->usuarios = $usuarios;
			$this->render('quemSeguir');
		}else {
			header('Location: /?login=erro');
		}
	}

	public function acao(){

		if($this->validaAutenticacao()) {

			$acao = isset($_GET['acao']) ? $_GET['acao'] : '';
			$id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

			$usuario = Container::getModel('Usuario');
			$usuario->__set('id', $_SESSION['id']);
			

			if($acao == 'seguir'){
				
				$usuario->seguir($id_usuario_seguindo);
			}else if($acao == 'deixar_de_seguir'){

				$usuario->deixar_de_seguir($id_usuario_seguindo);
			}
		}else {
			header('Location: /?login=erro');
		}
	}
}


?>