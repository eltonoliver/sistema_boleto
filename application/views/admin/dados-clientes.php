
        
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
                        
                        <fieldset>
                            <legend>Informação do cliente</legend>
                            
                         
                                <div class="form-group">
                                    <label for="nome">Seu nome completo: <?php echo $listaDados[0]->nome; ?></label>
                                  
                                </div>
                                <div class="form-group">
                                    <label for="cpf">CPF: <?php echo $listaDados[0]->cpf_cnpj; ?></label>
                                   
                                </div>
                                <div class="form-group">
                                    <label for="email">E-mail: <?php echo $listaDados[0]->email; ?></label>
                                    
                                </div>
                                <div class="form-group">
                                    <label for="numero">Número de contato: <?php echo $listaDados[0]->contato; ?></label>
                                   
                                </div>
                                <div class="form-group">
                                    <label for="endereco">Endereço: <?php echo $listaDados[0]->endereco; ?></label>
                                    
                                </div>
                                <div class="form-group">
                                    <label for="cep">CEP: <?php echo $listaDados[0]->cep; ?></label>
                                   
                                </div>
                               <a href="<?php echo base_url(); ?>admin/painelAdm/clientes"> <button type="submit" class="btn btn-default ">Voltar</button></a>
                          
                        </fieldset>
                 
                    </div>
				</div>
			</div>
		</div>

