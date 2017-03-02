

		<div class="container-fluid" id="remessa">
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
					<h2>Envio de arquivos</h2>
					<div class="panel panel-default paneldefault">
						<div class="panel-heading">
							<h3 class="panel-title">Dica: Você pode selecionar vários arquivos para que sejam enviados de uma única só vez.</h3>
						</div>
						<div class="panel-body">
							<form action="<?php echo base_url();?>admin/PainelAdm/manipulaArquivo"  enctype='multipart/form-data' method="post">
								<input type="file" class="filestyle" data-buttonBefore="true" name="arquivo[]" multiple>
								<br>
								<input type="submit" class="btn btn-danger" value="Enviar" id="btn-remessa">
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

