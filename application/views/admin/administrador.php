
		<div class="container-fluid" id="administrador">
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
					<h2>Lista de administadores</h2>
                    <div class="alert alert-warning" role="alert">Todos os administradores deveram acessar sistema com seu e-mail cadastrado e senha.</div>

                    <?php 
                    			 $this->db->where('id_usuadmin',$_SESSION['idAdmin']);
                    	$query = $this->db->get('permissao_admin')->result();


                    	$qtdPermissao = count($query);

                    	for ($i=0; $i < $qtdPermissao ; $i++ ) {

                    		if($query[$i]->id_permissao == 1){

                    			$liberado = true;

                    		}
                    	}
                    ?>

					<?php if($liberado){ ?>	
                    <a href="<?php echo base_url(); ?>admin/PainelAdm/addAdmin"  class="btn btn-success" style="margin-bottom: 10px;">Cadastrar novo funcionário</a>
                    <?php } ?>
					<table class="table table-hover" id="form-admin">
						<thead>
							<th>Nome do administrador</th>
							<th>E-mail</th>
							<th>MENU</th>
						</thead>
					<?php if($liberado){ ?>	
						<?php foreach ($listaAdmin as $value) { ?>
							
					
							<tr>
								<td><?php echo $value->nome ?></td>
								<td><?php echo $value->email ?></td>
								<td>
									<a class="glyphicon glyphicon-edit" title="Editar" href="<?php echo base_url(); ?>admin/PainelAdm/editarAdmin/<?php echo $value->adminId; ?>"></a>
									<a class="glyphicon glyphicon-remove-sign deletarAdmin" title="Excluir" href="<?php echo base_url(); ?>admin/PainelAdm/deleteAdmin/<?php echo $value->adminId; ?>"></a>
								</td>
							</tr>
							
						<?php } ?>	
					 <?php }else{

					 			$this->db->where('adminId',$_SESSION['idAdmin']);
					 			$query = $this->db->get('usuarioadmin')->result();

					 		 ?>	
							<?php foreach ($query as $value) { ?>
							
					
							<tr>
								<td><?php echo $value->nome ?></td>
								<td><?php echo $value->email ?></td>
								<td>
									<a class="glyphicon glyphicon-edit" title="Editar" href="<?php echo base_url(); ?>admin/PainelAdm/editarAdmin/<?php echo $value->adminId; ?>"></a>
								
								</td>
							</tr>
							
						<?php } ?>	



					<?php } ?>	
					</table>
					
				</div>
			</div>
		</div>

