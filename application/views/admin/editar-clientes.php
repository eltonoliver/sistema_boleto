
		<div class="container-fluid" id="cadastro">
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
					<h2>Editar Cliente</h2>
					<!--<div class="alert alert-warning" role="alert">Para sua segurança, é necessário o preenchimento dos campos abaixo.<br>A criação de um usuário e senha são exclusivos para acesso ao sistema de boleto 2º via.</div>
					174862
					-->
					<div class="alert alert-success" role="alert">Dados alterados com sucesso!</div>
					<form action="<?php echo base_url(); ?>admin/PainelAdm/editar/<?php echo $listaDados[0]->usuarioId  ?>" class="boxfor" method="post">
						<input required="required" type="text" class="form-control text-uppercase" id="" name="nome" pattern="[Aa-Zz\s]+$" placeholder="Nome completo" value="<?php echo $listaDados[0]->nome; ?>">

						<input required="required" type="text" class="form-control" id="" name="cpf_cnpj" placeholder="CPF" value="<?php echo $listaDados[0]->cpf_cnpj; ?>">

						<input required="required" type="email" class="form-control" id="" name="email" placeholder="E-mail" value="<?php echo $listaDados[0]->email; ?>">

						<input required="required" type="tel" class="form-control" id="" name="contato" placeholder="Telefone" pattern="[0-9\s]+$" value="<?php echo $listaDados[0]->contato; ?>">
						<input required="required" type="text" class="form-control" id="" name="endereco" placeholder="Endereço"
						value="<?php echo $listaDados[0]->endereco; ?>"	
						>

						<input required="required" type="text" class="form-control" id="" name="cep" placeholder="CEP" pattern= "\d{5}-?\d{3}" value="<?php echo $listaDados[0]->cep; ?>">

						<input required type="password" class="form-control" id="" name="senha" value="<?php echo $listaDados[0]->senha; ?>" >

						

						<button type="submit" class="btn btn-default btnlog">Editar</button>
					</form>
				</div>
			</div>
		</div>


