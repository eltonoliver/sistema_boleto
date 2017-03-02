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
                    <div class="alert alert-warning" role="alert">Todos os administradores deveram acessar sistema com seu e-mail cadastrado e senha.</div>
					<a href="add-admin.php"  class="btn btn-success">Cadastrar novo funcionário</a>
					<table class="table table-hover">
						<thead>
							<th>Nome do administrador</th>
							<th>contato@gmail.com</th>
							<th>MENU</th>
						</thead>
						<tr>
							<td>Nome do administrador</td>
							<td>contato@gmail.com</td>
							<td>
								<a class="glyphicon glyphicon-edit" title="Editar" href="add-admin.php"></a>
								<a class="glyphicon glyphicon-remove-sign" title="Excluir" href=""></a>
							</td>
						</tr>
						<tr>
							<td>Nome do administrador</td>
							<td>contato@gmail.com</td>
							<td>
								<a class="glyphicon glyphicon-edit" title="Editar" href="add-admin.php"></a>
								<a class="glyphicon glyphicon-remove-sign" title="Excluir" href=""></a>
							</td>
						</tr>
						<tr>
							<td>Nome do administrador</td>
							<td>contato@gmail.com</td>
							<td>
								<a class="glyphicon glyphicon-edit" title="Editar" href="add-admin.php"></a>
								<a class="glyphicon glyphicon-remove-sign" title="Excluir" href=""></a>
							</td>
						</tr>
						
						
					</table>
					
				</div>
			</div>
		</div>

<?php include "include-footer.php" ?>