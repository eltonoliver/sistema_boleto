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
                    
                    <form action="">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nome">Seu nome completo:</label>
                                <input required type="text" id="nome" class="form-control text-uppercase" value="Helder Pinto da Silva">
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail:</label>
                                <input required type="e-mail" id="email" class="form-control" value="helder@gmail.com">
                            </div>
                            <div class="form-group">
                                <label for="numero">Número de contato:</label>
                                <input required type="tel" id="numero" class="form-control" value="92369546915">
                            </div>
                            <div class="form-group">
                                <label for="senha">Senha:</label>
                                <input required type="password" id="senha" class="form-control" value="Centro">
                            </div>
                            <div class="form-group">
                                <label for="dsenha">Digite novamente a senha:</label>
                                <input required type="password" id="dsenha" class="form-control" value="69042000">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-default paneldefaul">
                                <!-- Default panel contents -->
                                
                                <div class="panel-body">
                                    <p>Seleciona as opções desejada para administrador</p>
                                </div>

                                <label class="checkbox">
                                    <input type="checkbox" id="inlineCheckbox1" value="option1"> Adicionar cliente
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" id="inlineCheckbox2" value="option2"> Adicionar administrador 
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" id="inlineCheckbox3" value="option3"> Arquivamento de cliente
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" id="inlineCheckbox4" value="option4"> Envio de arquivo/boleto
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