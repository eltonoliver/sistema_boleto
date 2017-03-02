

		<div class="container">
			<div class="col-md-4"></div>
			<div class="col-md-4 lo-gin">
				<img src="<?php echo base_url(); ?>assets/imagens/logo-civilcorp.png" alt="Civil Corp" width="" height="">
				
				<h4>Recuperar senha</h4>
				<p>Forneça informações adicionais para auxiliar no processo de recuperação. Digite e-mail de recuperação associado à sua conta</p>
								
				<form action="<?php echo base_url(); ?>Login/enviaEmailSenha" class="forlogn" method="post">
					<input type="email" class="form-control" id="exampleInputEmail1" name="email" placeholder="E-mail" required="required">
					<button type="submit" class="btn btn-default btnlog">Enviar</button>
				</form>
				
			</div>
			<div class="col-md-4"></div>
		</div>

