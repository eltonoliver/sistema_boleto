

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
					<?php if(count($listaData )>0){ ?>
					<div class="alert alert-warning" role="alert">Você tem boleto em aberto.</div>
					<?php }else{ ?>
					<div class="alert alert-success" role="alert">Neste momento não existem boletos cadastrados.</div>
					<?php } ?>
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
					<?php foreach ($listaData as $value):?>
						<?php $dataV =  substr($value->dataVencimento,8,2)."/".substr($value->dataVencimento,5,2)."/".substr($value->dataVencimento,0,4); ?>
						<tr>
							<td><?php echo $value->numeroDocumento; ?></td>
							<td><?php echo $value->msg2; ?></td>
							<td><?php echo $value->nomeSacado; ?></td>
							<td><?php echo $value->nossoNumero; ?></td>
							<td><?php echo substr($value->dataVencimento,8,2)."/".substr($value->dataVencimento,5,2)."/".substr($value->dataVencimento,0,4) ?></td>
							<td><?php echo number_format((intval($value->valorTitulo)/100), 2, ",", "."); ?></td>
							
							<td>
							<?php if($value->bancoId == 104){ ?>		
								<a target="_blank" class="glyphicon glyphicon-print"
								href="<?php echo base_url(); ?>cliente/PainelCli/processaBoleto/caixa/<?php echo $value->boletoId; ?>/<?php echo $value->nossoNumero?>"></a>
								<?php if($dataV == date('d/m/Y')){ ?> 
									<a href="" class="btn btn-warning">Renegociar</a>
								   <?php } ?>
							<?php }elseif($value->bancoId == 237){ ?>
								<a target="_blank" class="glyphicon glyphicon-print" href="<?php echo base_url(); ?>cliente/PainelCli/processaBoleto/bradesco/<?php echo $value->boletoId; ?>/<?php echo $value->nossoNumero?>"></a>
								<?php if($dataV == date('d/m/Y')){ ?> 
									<a href="" class="btn btn-warning">Renegociar</a>
								   <?php } ?>
							<?php } ?>	
							</td>

							<!-- <td><a href="" class="btn btn-warning">Renegociar</a></td>-->
						</tr>
					<?php endforeach; ?>	
					</table>
				</div>
			</div>
		</div>
