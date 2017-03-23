			 <?php 
                    			 $this->db->where('id_usuadmin',$_SESSION['idAdmin']);
                    	$query = $this->db->get('permissao_admin')->result();


                    	$qtdPermissao = count($query);

                    	for ($i=0; $i < $qtdPermissao ; $i++ ) {

                    		if($query[$i]->id_permissao == 2){

                    			$liberadoArqui = true;

                    		}
                    		if($query[$i]->id_permissao == 3){

                    			$liberadoEnv = true;

                    		}
                    	}
                    ?>
			<div class="boxlogo"><img src="<?php echo base_url(); ?>assets/imagens/logo-civilcorp.png" alt="Civil Corp" width="" height=""></div>


			<div class="row">
				<nav>
					<ul id="menu">
						<li><a class="menu-bt" href="<?php echo base_url(); ?>admin/PainelAdm" id="home">Home</a></li>
						<li><a class="menu-bt" href="<?php echo base_url(); ?>admin/PainelAdm/clientes" id="clientes">Clientes</a></li>
						<!--<li><a class="menu-bt" href="<?php echo base_url(); ?>admin/PainelAdm/cadastroClientes" id="cadastro">Cadastro de Clientes</a></li>-->
						<?php if($liberadoArqui){ ?>
                        <li><a class="menu-bt" href="<?php echo base_url(); ?>admin/PainelAdm/arquivamentoCli" id="arquivado">Arquivamento de Cliente</a></li>
                        <?php } ?>
                        <?php if($liberadoEnv){ ?>
						<li><a class="menu-bt" href="<?php echo base_url(); ?>admin/PainelAdm/envioArquivo" id="remessa">Envio de Arquivo</a></li>
						<?php } ?>

						<li><a class="menu-bt" href="<?php echo base_url(); ?>admin/PainelAdm/historico" id="historico">Histórico</a></li>
						<li><a class="menu-bt" href="<?php echo base_url(); ?>admin/PainelAdm/relatorio" id="relatorio">Relatório</a></li>
						<li><a class="menu-bt" href="<?php echo base_url(); ?>admin/PainelAdm/adm" id="administrador">Administradores</a></li>
						<li><a class="menu-bt" href="<?php echo base_url(); ?>admin/PainelAdm/sair">Sair</a></li>
				    </ul>
                </nav>
            </div>