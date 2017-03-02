<?php include "include-header.php" ?>

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
                            
                            <form action="">
                                <div class="form-group">
                                    <label for="nome">Seu nome completo:</label>
                                    <input required type="text" id="nome" class="form-control text-uppercase" value="Helder Pinto da Silva">
                                </div>
                                <div class="form-group">
                                    <label for="cpf">CPF:</label>
                                    <input required type="text" id="cpf" class="form-control" value="36925814722" required  pattern="^(\d{3}\.\d{3}\.\d{3}-\d{2})|(\d{11})$">
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
                                    <label for="endereco">Endereço:</label>
                                    <input required type="text" id="endereco" class="form-control" value="Centro">
                                </div>
                                <div class="form-group">
                                    <label for="cep">CEP:</label>
                                    <input required type="text" id="cep" class="form-control" value="69042000" pattern= "\d{5}-?\d{3}">
                                </div>
                                <button type="submit" class="btn btn-default ">Alterar</button>
                            </form>
                        </fieldset>
                        
                        <fieldset>
                            <legend>Alteração de senha</legend>
                            
                            <form action="">
                                <div class="form-group">
                                    <label for="cpf">Digite a nova senha:</label>
                                    <input required type="password" id="cpf" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Digite novamente a nova senha:</label>
                                    <input required type="password" id="email" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-default ">Alterar</button>
                            </form>
                        </fieldset>
                    </div>
				</div>
			</div>
		</div>

<?php include "include-footer.php" ?>