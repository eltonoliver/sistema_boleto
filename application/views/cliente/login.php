

		<div class="container">
			<div class="col-md-4"></div>
			
			<div class="col-md-4 lo-gin">
				<img src="<?php echo base_url(); ?>assets/imagens/logo-civilcorp.png" alt="Civil Corp" width="" height=""><br>
			
				  <?php echo $this->session->flashdata('msg'); ?>
				<form action="<?php echo base_url(); ?>login/validaLogin/" class="boxfor" method="post">
					<input type="text" class="form-control" id="exampleInputEmail1" placeholder="Nome do usuário ou e-mail" required="required" name="usuario">
					<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Senha" required="required" name="senha">
					<button type="submit" class="btn btn-default btnlog">Entrar</button>
				</form>
				
				<p><a href="Login/recuperarSenha">Esqueci minha senha</a></p>
				<!--<p><a href="login/cadastro">Criar uma conta</a></p>-->
				
			</div>
			
			<div class="col-md-4"></div>
		</div>
