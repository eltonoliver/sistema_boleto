

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
					<h2>Boletos</h2>
					<?php if(count($listaBoletos) <=0){ ?>
					<div class="alert alert-success" role="alert">Neste momento não existem boletos cadastrados.</div>
					<?php } ?>
					<table class="table table-striped" id="form-admin">
						<thead>
							<th>DOCUMENTO</th>
							<th>NOME SACADO / CPF</th>
							<th>NOSSO NÚMERO</th>
							<th>VENC</th>
							<th>VALOR</th>
							<th>STATUS</th>
							<th>OPÇÕES</th>
						</thead>

						<?php foreach ($listaBoletos as $value) { ?>
							
					
						<tr>
							<td><?php echo $value->numeroDocumento; ?></td>
							<td class="text-uppercase"><?php echo $value->nomeSacado ?><br><?php echo $value->cpf_cnpj; ?></td>
							<td>
								<?php
								 echo ($value->nossoNumero =="00000000000")?"BOLETO INDISPONIVEL / REJEITADO":$value->nossoNumero;

								 ?>	

							</td>
							<td>
								<?php 

									echo substr($value->dataVencimento,8,2)."/".substr($value->dataVencimento,5,2)."/".substr($value->dataVencimento,0,4);
								 ?>

							</td>
							<td>
								<?php 

									echo number_format(((intval($value->valorTitulo))/100), 2, ",", ".");
								?>
							</td>

							<td><?php 
									
									if($value->dataVencimento < $dataXdias && $value->dataPagamento == '0000-00-00'){

										$style = 'style="color:red;"';
									}else{

										$style = "";
									}

									echo ($value->dataPagamento == '0000-00-00')?"<spam $style>Em aberto</span>":'<span class="pgo">Pago</span>';

							 	?></td><!--<span class="pgo">Pago</span> -->
								<td>
								  <?php if($value->nossoNumero != "00000000000"){ ?>	
									<?php if($value->bancoID == 104){ ?>		
										<a target="_blank" class="glyphicon glyphicon-print"
										href="<?php echo base_url(); ?>admin/PainelAdm/processaBoleto/caixa/<?php echo $value->boletoId; ?>/<?php echo $value->nossoNumero?>"></a>
									
										
									<?php }elseif($value->bancoID == 237){ ?>
										<a target="_blank" class="glyphicon glyphicon-print" href="<?php echo base_url(); ?>admin/PainelAdm/editarClientes/<?php echo $value->usuarioId; ?>"></a>
										
									<?php } ?>	
									<?php } ?>
							</td>
						</tr>
						<?php 	} ?>
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

