<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	/*Tela de Login

	Author: Elton Oliveira
	Date:21/02/2017
	E-mail: eltonoliveirape@gmail.com
	*/
	public function __construct(){
		parent::__construct();
		$this->load->helper("funcoes");
	}
	public function index()
	{			
		
		$this->load->view('cliente/include-header.php');
		
		$this->load->view('cliente/login.php');
		$this->load->view('cliente/include-footer.php');

	}

	public function recuperarSenha(){

		$this->load->view('cliente/include-header.php');
		$this->load->view('cliente/recuperarsenha.php');
		$this->load->view('cliente/include-footer.php');
	}

	public function enviaEmailSenha(){


		$email = $this->input->post('email');
		$senhaUsuario = "";
		if(!empty($email)){

			$this->db->where('email',$email);
			$query = $this->db->get('usuario')->result();

			if(!empty($query[0]->email)){

				for($i = 0; $i < 6; $i++){
					$senhaUsuario .= rand(1,9);
				}

				//Altera senha e deixa o status como null
				$data = array(
		        'senha' 			=>$senhaUsuario,
		        'status_password' 	=> NULL
				);


				$this->db->where('email',$email);
				$this->db->update('usuario',$data);
				$this->load->library('email');

				$config['charset'] = 'utf-8';
				$config['wordwrap'] = TRUE;
				$config['mailtype'] = 'html';
				$this->email->initialize($config);
				$this->email->from('sistema@clientecivilcorp.com.br', 'Sistema de Solicitação de Serviços');
				$this->email->to($email);				 
								
				$this->email->subject('Nova Senha de Acesso');
				$this->email->message('Esta é sua nova senha de acesso :'.$senhaUsuario.'<br><br>
					<a href="http://clientecivilcorp.com.br/novo_sistema/">Voltar Ao Sistema</a>');	
				if($this->email->send()){

					$this->session->set_flashdata('msg', 'Sua nova senha foi enviada para seu e-mail');
					redirect('Login/');
				}
			
			}else{
				$this->session->set_flashdata('msg', 'Você não possui e-mail cadastrado, contate o administrador do sistema!');
				redirect('Login/');
			}
		}else{

			$this->session->set_flashdata('msg', 'Digite um e-mail');
			redirect('Login/');
		}

		
		
	}

	
	public function validaLogin(){

		$login = strtolower(strip_tags($this->input->post('usuario')));
		$email = $login;
		$cpfCnpj = str_pad(cb_limparLogin($login), 14, "0", STR_PAD_LEFT);

		$senha = strip_tags($this->input->post('senha'));
		$datahora = date('Y-m-d h:i:s');


		$this->db->where('login',$login);
		$queryLogin = $this->db->get('usuarioAdmin')->result();

		if (!empty($queryLogin[0]->email)) {
			// é administrador

			//	$this->db->where('usuarioId',$_SESSION['userId']);
			//	$verificaStatusSenha = $this->db->get('usuario')->result();

			if ($resultado = $this->db->query("SELECT adminId, nome, login FROM usuarioAdmin WHERE login LIKE '$login' AND senha LIKE '$senha'")){
				if($resultado->result()){

					foreach ($resultado->result() as $value) {
							
							$adminId = (isset($value->adminID)?$value->adminID:"");
							$_SESSION['nome'] = $value->nome;
							$_SESSION['admin'] = $value->login;

						}	
					

						cb_boletoHistoricoInsert($login, "Admin: Acesso", $adminId);
						redirect('admin/PainelAdm');

						

				} else {	
					$this->session->set_flashdata('msg', 'Erro nos dados de entrada!');
					cb_boletoHistoricoInsert($login, "Admin: Identifição de admin falhou", '0');
				
					redirect('Login/');
				}
			} 
		} else {
			// é cliente
			
			$verificaLogin = strpos($cpfCnpj, '@');
			if($verificaLogin == false){
   				
   				$this->db->where('cpf_cnpj',$cpfCnpj);
				$verificaStatusSenha = $this->db->get('usuario')->result();
				if($verificaStatusSenha[0]->status_password == NULL || $verificaStatusSenha[0]->status_password == 0){

					    $sql = "SELECT usuarioId, nome, cpf_cnpj FROM usuario WHERE cpf_cnpj LIKE '$cpfCnpj' AND senha LIKE '$senha'";

				}else{

					 $senha = md5($senha);	
					 $sql = "SELECT usuarioId, nome, cpf_cnpj FROM usuario WHERE cpf_cnpj LIKE '$cpfCnpj' AND senha LIKE '$senha'";
				}


   			
				
			}else{

					$this->db->where('email',$email);
				$verificaStatusSenha = $this->db->get('usuario')->result();
				if($verificaStatusSenha[0]->status_password == NULL || $verificaStatusSenha[0]->status_password == 0){
					$sql = "SELECT usuarioId, nome, cpf_cnpj,email FROM usuario WHERE email LIKE '$email' AND senha LIKE '$senha'";
				}else{	

					$senha = md5($senha);
					$sql = "SELECT usuarioId, nome, cpf_cnpj,email FROM usuario WHERE email LIKE '$email' AND senha LIKE '$senha'";
				}	

				
			}

			
			if ($resultado = $this->db->query($sql)){

				if ($resultado->result()) {

						foreach ($resultado->result() as $value) {
							
							$_SESSION['nome'] = $value->nome;
							$_SESSION['userId'] = $value->usuarioId;
							$_SESSION['cliente'] = $value->cpf_cnpj;

						}	

			
					cb_boletoHistoricoInsert($_SESSION['nome'], "Cliente {$_SESSION['cliente']}: Acesso", $_SESSION['userId']);
					redirect('cliente/PainelCli');
				} else {

					cb_boletoHistoricoInsert($cpfCnpj, "Cliente: Acesso falhou", 0);

					$debugMsg2 = "CNPJ: $cpfCnpj\n\nCNPJ ou senha inválida";
					
					//cb_mail('erroboletos@civilcorp.com.br', 'Civilcorp: erro de acesso ao sistema de boletos', $debugMsg2);
					$this->session->set_flashdata('msg', 'Erro nos dados de entrada!');
					redirect('Login/');
				}
			}
			
		}

	}



}
