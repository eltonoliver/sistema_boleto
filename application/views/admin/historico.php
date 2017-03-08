
		<div class="container-fluid" id="historico">
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
					<h2>Histórico de usuário</h2>
					
					<table class="table table-hover" id="form-admin">
						<thead>
							<th>ID</th>
							<th>NOME DO USUÁRIO</th>
							<th>DATA</th>
							<th>TIPO DO USUÁRIO</th>
						</thead>
					<?php foreach ($listaHistoricoAcesso as $value) { ?>
						<tr>
							<td><?php echo $value->historicoId; ?></td>
							<td class="text-uppercase"><?php echo $value->nomeUsuario; ?></td>
							<td> <?php echo substr($value->data,8,2)."/".substr($value->data,5,2)."/".substr($value->data,0,4) ?> </td>
							<td><?php echo $value->tipo; ?></td>
						</tr>
					<?php } ?>		
		
					</table>
					
					
				</div>
			</div>
		</div>
