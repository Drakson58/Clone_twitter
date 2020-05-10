<?php


namespace App\Models;

//Importando o modelo de class
use MF\Model\Model;

class Usuario extends Model{

	private $id;
	private $email;
	private $nome;
	private $senha;

	public function __set($atributo, $valor){
		$this->$atributo = $valor;
	}

	public function __get($atributo){
		return $this->$atributo;
	}

	//Salvar
	public function salvar(){

		$query = "insert into usuarios(nome, email, senha) values (:nome, :email, :senha)";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':nome', $this->__get('nome'));
		$stmt->bindValue(':email', $this->__get('email'));
		$stmt->bindValue(':senha', $this->__get('senha'));
		$stmt->execute();

		return $this;
	}
	
	//Validar se pode ser feito o cadastro
	public function validaCadastro(){
		
		$validado = true;
		if(strlen($this->__get('nome')) < 3){
			$validado = false;
		}
		if(strlen($this->__get('email')) < 3){
			$validado = false;
		}
		if(strlen($this->__get('senha')) < 3){
			$validado = false;
		}

		return $validado;
	}

	//Recuperar um usuário por email
	public function getUserPorEmail(){

		$query = "select nome, email from usuarios where email = :email";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':email', $this->__get('email'));
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}


	//Autenticar cadastro
	public function autenticar(){

		$query = "select id, nome, email from usuarios where email = :email and senha = :senha";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':email', $this->__get('email'));
		$stmt->bindValue(':senha', $this->__get('senha'));
		$stmt->execute();

		$usuario = $stmt->fetch(\PDO::FETCH_ASSOC); //Pegando um unico registro do banco e colocando dentro de um array

		if($usuario['id'] != '' && $usuario['nome'] != ''){
			
			$this->__set('id', $usuario['id']);
			$this->__set('nome', $usuario['nome']);
		}

		return $this;
	}

	public function pesquisar(){

		$query = "select id, nome, email, () as seguindo_sn from usuarios where nome  like :nome and id != :id_usuario";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function seguir($id){

		$query = "insert into usuarios_seguidores(id_usuario, id_usuario_seguindo) values (:id_user, :id_usuario)";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_user', $this->__get('id'));
		$stmt->bindValue(':id_usuario', $id);
		$stmt->execute();

		return true;
	}

	public function deixar_de_seguir($id){
		
		$query = "delete from usuarios_seguidores where id_usuario = :id_user and id_usuario_seguindo = :id_usuario";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_user', $this->__get('id'));
		$stmt->bindValue(':id_usuario', $id);
		$stmt->execute();

		return true;
	}
}

?>