
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
                    <form action="<?php echo base_url(); ?>admin/PainelAdm/editarInforAdm/<?php echo $listaAdmin[0]->adminId; ?>" method="post">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nome">Seu nome completo:</label>
                                <input required type="text" id="nome" class="form-control text-uppercase" value="<?php echo $listaAdmin[0]->nome; ?>" name="nome">
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail:</label>
                                <input required type="e-mail" id="email" class="form-control" value="<?php echo $listaAdmin[0]->email; ?>" name="email">
                            </div>
                            <div class="form-group">
                                <label for="numero">Número de contato:</label>
                                <input required type="tel" id="numero" class="form-control" value="<?php echo $listaAdmin[0]->contato; ?>" name="contato">
                            </div>
                            <div class="form-group">
                                <label for="senha">Senha:</label>
                                <input required type="password" id="senha" class="form-control" value="<?php echo $listaAdmin[0]->senha; ?>" name="senha">
                            </div>
                            <div class="form-group">
                            <label for="dsenha">Digite novamente a senha:</label>
                            <?php if(empty($listaAdmin[0]->email)){ ?>
                                
                                <input required type="password" id="dsenha" class="form-control" name="confSenha">
                            <?php }else{
                                ?>
                                <input type="password" id="dsenha" class="form-control" name="confSenha">
                                <?php

                                } ?>    
                            </div>
                        </div>
                        <?php 
                                 $this->db->where('id_usuadmin',$_SESSION['idAdmin']);
                        $query = $this->db->get('permissao_admin')->result();


                        $qtdPermissao = count($query);

                        for ($i=0; $i < $qtdPermissao ; $i++ ) {

                            if($query[$i]->id_permissao == 1){

                                $liberado = true;

                            }
                        }
                    ?>
                    <?php if($liberado){ ?>
                        <div class="col-md-6">
                            <div class="panel panel-default paneldefaul">
                                <!-- Default panel contents -->
                                
                                <div class="panel-body">
                                    <p>Seleciona as opções desejada para administrador</p>
                                </div>
                                <!--
                                <label class="checkbox">
                                    <input type="checkbox" id="inlineCheckbox1" value="option1" checked> Adicionar cliente
                                </label>-->
                              
                                <label class="checkbox">
                                    <input type="checkbox" id="inlineCheckbox2" name="adm[]" value="1"
                                    <?php 

                                        foreach ($listaPermissao as $value) {
                                            if($value->id_permissao == 1){
                                                echo "checked";
                                            }
                                        }

                                    ?>
                                    > Adicionar administrador 
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" id="inlineCheckbox3" name="adm[]" value="2"
                                     <?php 

                                        foreach ($listaPermissao as $value) {
                                            if($value->id_permissao == 2){
                                                echo "checked";
                                            }
                                        }

                                    ?>
                                    > Arquivamento de cliente
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" id="inlineCheckbox4" name="adm[]" value="3"
                                    <?php 

                                        foreach ($listaPermissao as $value) {
                                            if($value->id_permissao == 3){
                                                echo "checked";
                                            }
                                        }

                                    ?>
                                   

                                    > Envio de arquivo/boleto
                                </label>
  
                            </div>
                        </div>
                        <?php } ?>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-default">Salvar</button>
                        </div>
                     
                
                    </form>
					
				</div>
			</div>
		</div>

