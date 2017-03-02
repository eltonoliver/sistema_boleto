<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Este contém as funçoes usadas no HISTÓRICO.
*/


/**
 * exibe o historico de arquivos.
 * @return string $html
 */
function ch_historicoSelectArquivos()
{
	global $config;

	$qtdRegistros = mysql_result(mysql_query("SELECT COUNT(historicoId) AS qtd FROM historicoRemRet"),0,'qtd');

	// define parâmetros de paginacao para esta lista
	$paginacao = cb_Paginacao('?p=historico&q=arquivos',$qtdRegistros);//$paginacao = ['inicio','pagina','listaLinksPaginacao']
	
	if($qtdRegistros == 0){
		$html.= "<center>Não há historicos cadastrados</center>";
	}
	else{
		
		
	$html ="
		<table border='0' cellspacing='10' cellpadding='10'>
			<tr>
				<th>Data de Envio</th>
				<th width='200'>Enviado Por</th>
				<th>Status</th>
				<th>Nome do arquivo</th>
				<th>Banco</th>
			</tr>
		";

	$query = "SELECT dataEnvio,nomeFuncionario,statusEnvio,nomeArquivo,bancoBoleto FROM historicoRemRet ORDER BY dataEnvio DESC LIMIT {$paginacao['inicio']},{$config['qtdPaginacao']}";
	$res = mysql_query($query);	
	while($escrever= mysql_fetch_array($res)){
		if($escrever['statusEnvio'] == 1){
			$status = "Enviado";

		} else {
			$status = "Falha";
		}
		$html .= "
			<tr>
				<td>".substr($escrever['dataEnvio'],8,2)."/".substr($escrever['dataEnvio'],5,2)."/".substr($escrever['dataEnvio'],0,4)."</td>
				<td>".$escrever['nomeFuncionario']."</td>
				<td>$status</td>
				<td>".$escrever['nomeArquivo']."</td>
				<td>".$escrever['bancoBoleto']."</td>
			</tr>
			";
	}
	$html .= "
		</table>
		{$paginacao['listaLinksPaginacao']}
		";
	}
	
	return $html;
}

/**
 * insere o histrico de arquivos no banco de dados.
 * @return string $html
 */
function ch_historicoInsertArquivo($nomeBanco)
{	
	$CI =& get_instance();	
	global $nome_arquivo;
	global $data_envio;
	global $status_envio;
	
	$nomeArquivo = $nome_arquivo;
	$dataEnvio = $data_envio;
	$statusEnvio = $status_envio;
	$nomeFuncionario = $_SESSION['nome'];

	 if(!$CI->db->query("INSERT INTO historicoRemRet (dataEnvio, nomeFuncionario, statusEnvio, nomeArquivo, bancoBoleto)
		VALUES ('$dataEnvio', '$_SESSION[nome]', '$statusEnvio', '$nomeArquivo', '$nomeBanco')
		")){

	 	echo "Error";
	 }	
	
}

/**
 * menu do historico.
 * @return string $html
 */
function ch_historicoMenu()
{
	$html = "
		<!-- busca histórico -->
		<div class='container busca-historico text-center'>
			<a href='?p=historico&amp;q=usuarios' class='bt'>Acesso de Usuarios</a>				
			<a href='?p=historico&amp;q=arquivos' class='bt'>Envio de Arquivos</a>
		</div>					
		<!-- busca histórico fim -->
	";
	return $html;
}

/**
 * lista os dados de um usuario especifico
 * @return string $html
 */
function ch_historicoUsuarioView()
{
	$id = $_GET['id'];

	$sql = "SELECT usuarioId, nome, email, cpf_cnpj, senha, cep FROM usuario WHERE usuarioId = '$id'";

	if($result = mysql_query($sql)){
		if(mysql_num_rows($result) > 0){
		$html .= "
			<table>
				<tr>
					<td>Usuario ID:</td>
					<td>".mysql_result($result, 0, 'usuarioId')."</td>
				</tr>
				<tr>
					<td>Nome:</td>
					<td>".mysql_result($result, 0, 'nome')."</td>
				</tr>
				<tr>
					<td>Email:</td>
					<td>".mysql_result($result, 0, 'email')."</td>
				</tr>
				<tr>
					<td>CPF/CNPJ:</td>
					<td>".mysql_result($result, 0, 'cpf_cnpj')."</td>
				</tr>
				<tr>
					<td>Senha:</td>
					<td>".mysql_result($result, 0, 'senha')."</td>
				</tr>
				<tr>
					<td>CEP</td>
					<td>".mysql_result($result, 0, 'cep')."</td>
				</tr>

			</table>

		";

		} else {
			$html = "Nenhum usuário encontrado!";
		}
	
	} else {
		$html .= "Erro!";
	}
	return $html;
}

/**
 * Lista o historico de acesso dos usuarios
 * @return string $html
 */
function ch_historicoSelectUsuarios()
{
	global $config;

	$qtdRegistros = mysql_result(mysql_query("SELECT COUNT(historicoId) AS qtd FROM historicoUsuario "),0,'qtd');

	if ($qtdRegistros > 0) {
		// define parâmetros de paginacao para esta lista
		$paginacao = cb_Paginacao('?p=historico&q=usuarios',$qtdRegistros);//$paginacao = ['inicio','pagina','listaLinksPaginacao']

		$query = "SELECT historicoId,nomeUsuario,data,tipo,usuarioId FROM historicoUsuario ORDER BY data DESC LIMIT {$paginacao['inicio']},{$config['qtdPaginacao']}";

		$html.= "
			<table border='0' cellspacing='10' cellpadding='10'>
				<tr>
					<th>ID</th>
					<th>Nome do Usu&aacute;rio</th>
					<th>Data</th>
					<th>Tipo do Usu&aacute;rio</th>
				</tr>
			";
		$res = mysql_query($query);
		while($escrever = mysql_fetch_array($res)){
			$html .= "
				<tr>
					<td>".$escrever['historicoId']."</td>
					<td>".strtoupper($escrever['nomeUsuario'])."</td>
					<td>".substr($escrever['data'],8,2)."/".substr($escrever['data'],5,2)."/".substr($escrever['data'],0,4)."</td>
					<td>".$escrever['tipo']."</td>				
				</tr>";
		}

		$html .= "
			</table>
			{$paginacao['listaLinksPaginacao']}
		";
	} else {
		$html .= "<center>Nenhum historico de acesso de usuarios cadastrado.</center>";
	}

	return $html;
}

?>