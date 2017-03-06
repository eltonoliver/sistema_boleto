

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
					<h2 class="txtalg">Histórico</h2>
					<table class="table table-striped" id="form-cli">
						<thead>
							<th>Nº Doc</th>
							<th>Descrição</th>
							<th>Nome Sacado</th>
							<th>Nosso Número</th>
							<th>Vencimento</th>
							<th>Valor</th>
							<th>Situação</th>
						</thead>
					
							<?php foreach ($listaData as $value):?>
							<tr>
								<td><?php echo $value->numeroDocumento; ?></td>
								<td><?php echo $value->msg2; ?></td>
								<td><?php echo $value->nomeSacado; ?></td>
								<td><?php echo $value->nossoNumero; ?></td>
								<td><?php echo substr($value->dataVencimento,8,2)."/".substr($value->dataVencimento,5,2)."/".substr($value->dataVencimento,0,4) ?></td>
								<td><?php echo number_format((intval($value->valorTitulo)/100), 2, ",", "."); ?></td>
								
								<td>
								<?php if($value->bancoId == 104){ ?>		
									<a target="_blank" class="glyphicon glyphicon-search"
									href="<?php echo base_url(); ?>cliente/PainelCli/processaBoletoHistorico/caixa/<?php echo $value->boletoId; ?>/<?php echo $value->nossoNumero?>"></a><span class="pgo">Pago</span>
								<?php }elseif($value->bancoId == 237){ ?>
									<a target="_blank" class="glyphicon glyphicon-search" href="<?php echo base_url(); ?>cliente/PainelCli/processaBoletoHistorico/bradesco/<?php echo $value->boletoId; ?>/<?php echo $value->nossoNumero?>"></a><span class="pgo">Pago</span>
								<?php } ?>	
								</td>
							
							</tr>
						<?php endforeach; ?>
					
					</table>
				</div>
			</div>
		</div>

