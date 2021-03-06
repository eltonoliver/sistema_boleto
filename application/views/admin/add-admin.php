<?php include "include-header.php" ?>

		<div class="container-fluid" id="administrador">
			<div class="row">
				<div class="col-sm-3 col-md-2 sidebar">
					<?php include 'include-menu.php' ?>
				</div>
			</div>

			<div class="row">
				<?php include "include-top.php" ?>
			</div>

			<div class="row">
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
					<h2>Lista de administadores</h2>
                    <?php if(!empty($this->session->flashdata('msg'))){ ?>
                         <div class="alert alert-success" role="alert"><?php echo $this->session->flashdata('msg'); ?></div>
                         <?php } ?>
                           <?php if(!empty($this->session->flashdata('erro'))){ ?>
                         <div class="alert alert-warning" role="alert"><?php echo $this->session->flashdata('erro'); ?></div>
                         <?php } ?>
                    <form action="<?php echo base_url(); ?>admin/PainelAdm/cadastraAdmin" method="post">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nome">Seu nome completo: </label>
                                <input required type="text" id="nome" class="form-control text-uppercase" name="nome" value="<?php echo $this->session->flashdata('nome'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail:</label>
                                <input required type="e-mail" id="email" class="form-control" name="email" value="<?php echo $this->session->flashdata('email'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="numero">Número de contato:</label>
                                <input required type="tel" id="numero" class="form-control" name="contato" value="<?php echo $this->session->flashdata('contato'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="senha">Senha:</label>
                                <input required type="password" id="senha" class="form-control" name="senha">
                            </div>
                            <div class="form-group">
                                <label for="dsenha">Digite novamente a senha:</label>
                                <input required type="password" id="dsenha" class="form-control" name="confSenha">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-default paneldefaul">
                                <!-- Default panel contents -->
                                
                                <div class="panel-body">
                                    <p>Seleciona as opções desejada para administrador</p>
                                </div>
                                <!--
                                <label class="checkbox">
                                    <input type="checkbox" id="inlineCheckbox1" value="option1"> Adicionar cliente
                                </label>-->
                                <label class="checkbox">
                                    <input type="checkbox" id="inlineCheckbox2" value="1" name="adm[]"> Adicionar administrador 
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" id="inlineCheckbox3" value="2" name="adm[]"> Arquivamento de cliente
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" id="inlineCheckbox4" value="3" name="adm[]"> Envio de arquivo/boleto
                                </label>
  
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-default">Salvar</button>
                        </div>
                    </form>
					
				</div>
			</div>
		</div>

<?php include "include-footer.php" ?>