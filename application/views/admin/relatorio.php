

		<div class="container-fluid" id="relatorio">
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
					<h2>Resumo dos boletos deste mês e mês anterior</h2>
					
					<table class="table table-hover" id="form-admin">
						<thead>
							<th>BOLETOS/PERÍODO</th>
							<th>MÊS ATUAL</th>
							<th>MÊS PASSADO</th>
						</thead>
						<tr>
							<td>PAGOS</td>
							<td><?php echo $qtdPagosAtual; ?> <?php echo "(".substr($porcentagemPagosAtual ,0,4)." %)"; ?></td>
							<td><?php echo $qtdPagosPassado; ?>  <?php echo "(".substr($porcentagemPagosPassado ,0,4)." %)"; ?></td>
						</tr>
						<tr>
							<td>PENDENTES</td>
							<td><?php echo $qtdPendentesAtual; ?><?php echo "(".substr($porcentagemPendentesAtual ,0,4)." %)"; ?></td>
							<td><?php echo $qtdPendentesPassado; ?><?php echo "(".substr($porcentagemPendentesPassado ,0,4)." %)"; ?></td>
						</tr>
						<tr>
							<td>TOTAL</td>
							<td><?php echo $totalAtual; ?></td>
							<td><?php echo $totalPassado; ?></td>
						</tr>
						
						
					</table>
					
				</div>
			</div>
		</div>

