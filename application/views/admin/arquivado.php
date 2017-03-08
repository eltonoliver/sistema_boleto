

		<div class="container-fluid" id="arquivado">
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
					<h2>Clientes arquivados</h2>

					<table class="table table-striped" id="form-admin">
						<thead>
							<th>NOME CLIENTE / CPF/CNPJ</th>
							<th>E-MAIL</th>
							<th>MENU</th>
						</thead>
						<?php foreach ($listaUsuarioArq as $key => $value) { ?>
								<tr>
								<td class="text-uppercase"><?php echo $value->nome ?><br><?php echo $value->cpf_cnpj; ?></td>
								<td><?php echo $value->email ?></td>
								<td>
									<a class="glyphicon glyphicon-folder-open desarquivar" title="Arquivar" href="<?php echo base_url(); ?>admin/PainelAdm/desArquivaMentoCli/<?php echo $value->usuarioId; ?>"></a>
								</td>
						</tr>


					 	<?php	} ?>
						

						
						
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

