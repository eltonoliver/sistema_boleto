
		<div class="container-fluid" id="clientes">
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
					<h2>Clientes</h2>
					<?php if(count($listaUsuarioCli) <=0){ ?>
					<div class="alert alert-success" role="alert">Neste momento não existem clientes cadastrados.</div>
					<?php } ?>

					<!--<a href="cadastro.php"  class="btn btn-success" style="margin-bottom: 10px;">Cadastrar</a>-->

					<table class="table table-striped" id="form-admin" >
						<thead>
							<th>NOME CLIENTE / CPF/CNPJ</th>
							<th>E-MAIL</th>
							<th>MENU</th>
						</thead>
						<?php foreach ($listaUsuarioCli as $value) { ?>
						<tr>
							<td class="text-uppercase"><?php echo $value->nome ?><br><?php echo $value->cpf_cnpj; ?></td>
							<td><?php echo $value->email ?></td>
							<td>
								<a target="_blank" class="glyphicon glyphicon-search" href="<?php echo base_url(); ?>admin/PainelAdm/boletoPorUsuario/<?php echo $value->cpf_cnpj; ?>"></a>
								<a class="glyphicon glyphicon-edit" title="Editar" href="<?php echo base_url(); ?>admin/PainelAdm/editarClientes/<?php echo $value->usuarioId; ?>"></a>
								<a class="glyphicon glyphicon-folder-open arquivar"  title="Arquivar" href="<?php echo base_url(); ?>admin/PainelAdm/arquivarCliente/<?php echo $value->usuarioId; ?>"></a>
							</td>
						</tr>
						<?php } ?>
						
					</table>
					<!--
					<div class="btn-group" role="group" aria-label="First group">
						<button type="button" class="btn btn-default">1</button>
						<button type="button" class="btn btn-default">2</button>
						<button type="button" class="btn btn-default">3</button>
						<button type="button" class="btn btn-default">4</button>
					</div>
					-->
				</div>
			</div>
		</div>
