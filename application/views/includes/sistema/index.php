<?php include "include-header.php" ?>

		<div class="container-fluid" id="home">
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
					<h2 class="txtalg">Seja bem vindo!</h2>
					
					<div class="alert alert-warning" role="alert">Você tem boleto em aberto.</div>
					
					<div class="alert alert-success" role="alert">Neste momento não existem boletos cadastrados.</div>
					
					
					<table class="table table-striped">
						<thead>
							<th>Nº Doc</th>
							<th>Descrição</th>
							<th>Nome Sacado</th>
							<th>Nosso Número</th>
							<th>Vencimento</th>
							<th>Valor</th>
							<th>Imprimir</th>
						</thead>
						<tr>
							<td>ENT 003/005</td>
							<td></td>
							<td>Helder Pinto da Silva</td>
							<td>000000087156392</td>
							<td>28/02/2017</td>
							<td>2.600,00</td>
							<td><a target="_blank" class="glyphicon glyphicon-print" href=""></a></td>
						</tr>
						<tr>
							<td>ENT 003/005</td>
							<td></td>
							<td>Helder Pinto da Silva</td>
							<td>000000087156392</td>
							<td>28/02/2017</td>
							<td>2.600,00</td>
							<td><a target="_blank" class="glyphicon glyphicon-print" href=""></a></td>
						</tr>
						<tr>
							<td>ENT 003/005</td>
							<td></td>
							<td>Helder Pinto da Silva</td>
							<td>000000087156392</td>
							<td>28/02/2017</td>
							<td>2.600,00</td>
							<td><a target="_blank" class="glyphicon glyphicon-print" href=""></a></td>
						</tr>
					</table>
				</div>
			</div>
		</div>

<?php include "include-footer.php" ?>