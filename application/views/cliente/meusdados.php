
<?php 
    
    foreach ($listaDados as $value) {
        
        $nome    = $value->nome;
        $cpfCnpj = $value->cpf_cnpj;
        $email   = $value->email;
        $contato = $value->contato;
        $endereco= $value->endereco;
        $cep     = $value->cep; 


    }

?>
		<div class="container-fluid" id="meusdados">
			<div class="row">
				<div class="col-sm-3 col-md-2 sidebar">
					<?php include "include-menu.php" ?>
				</div>
			</div>

			<div class="row">
				<?php include "include-top.php" ?>
			</div>

			<div class="row">
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <div class="col-md-6 col-md-offset-3">
                         <?php if($email == "" || empty($email)){ ?> 
                        <div class="alert alert-danger" role="alert">
                       
                            <p>Suas informações estão incompletas.<br>Você não poderá realizar seus serviços por falta de informação.</p>
                           
                        </div>
                         <?php } ?>

                         <?php if(!empty($this->session->flashdata('mensagemSucesso'))){ ?>
                         <div class="alert alert-success" role="alert"><?php echo $this->session->flashdata('mensagemSucesso'); ?></div>
                         <?php } ?>
                     <!--MENSAGEM SENHA -->

                          <?php if(!empty($this->session->flashdata('msgSenhaAntigaErrada'))){ ?>
                            <div class="alert alert-danger" role="alert">
                            
                                <p><?php echo $this->session->flashdata('msgSenhaAntigaErrada');  ?></p>
                               
                            </div>
                           <?php } ?>

                           
                        
                            

                        <!-- MENSAGEM SENHA -->
                        <fieldset>
                            <legend>Tenha seus dados atualizado para facilitar nos serviços de boleto</legend>
                            
                            <form action="<?php echo base_url(); ?>cliente/PainelCli/cadastraDadosCliente/<?php echo $this->session->userdata('userId'); ?>" method="post">
                                <div class="form-group">
                                    <label for="nome">Seu nome completo:</label>
                                    <input required type="text" id="nome" name="nome" class="form-control text-uppercase" value="<?php echo $nome; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="cpf">CPF/CNPJ:</label>
                                    <input required type="text" id="cpf" name="cpf" class="form-control" value="<?php echo ($cpfCnpj); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">E-mail:</label>
                                    <input required type="e-mail" id="email" name="email" class="form-control" value="<?php echo $email; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="numero">Número de contato:</label>
                                    <input required type="tel" id="numero" name="numero" class="form-control" value="<?php echo $contato; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="endereco">Endereço:</label>
                                    <input required type="text" id="endereco" name="endereco" class="form-control" value="<?php echo $endereco; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="cep">CEP:</label>
                                    <input required type="text" id="cep" name="cep" class="form-control" value="<?php echo $cep; ?>" pattern= "\d{5}-?\d{3}">
                                </div>
                                <button type="submit" class="btn btn-default ">Alterar</button>
                            </form>
                        </fieldset>
                        
                        <fieldset>
                     
                            <legend>Alteração de senha</legend>
                            
                            <form action="<?php echo base_url(); ?>cliente/PainelCli/alteraSenha/<?php echo $this->session->userdata('userId'); ?>" method="post">
                                <div class="form-group">
                                    <label for="nome">Digite sua antiga senha:</label>
                                    <input required type="password" id="nome" name="senhaAntiga" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="cpf">Digite sua nova senha:</label>
                                    <input required type="password" id="cpf" name="novasenha1" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Digite novamente sua nova senha:</label>
                                    <input required type="password" id="email" name="novasenha2" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-default ">Alterar</button>
                            </form>
                        </fieldset>
                    </div>
				</div>
			</div>
		</div>

