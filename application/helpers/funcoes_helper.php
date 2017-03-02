<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');




/**
* Funçoes de uso geral em todo o sistema
*/

/**
 * pega uma data MYSQL e converte para timestamp
 * @param datetime $data
 */
function ohs_dbdate2timestamp($data)
{
	$dia = substr($data,8,2);
	$mes = substr($data,5,2);
	$ano = substr($data,0,4);
	$hora = substr($data,11,2);
	$minuto = substr($data,14,2);
	$segundo = substr($data,17,2);
	$tempofinal = mktime($hora,$minuto,$segundo,$mes,$dia,$ano);
	return $tempofinal;
}

/**
 *  formata um CPF (xxx.xxx.xxx-xx) ou CNPJ (xx.xxx.xxx/xxxx-xx)
 *  @param   string $cpfCnpj
 *  @return  string $cpfCnpjFormatado
 */
function cb_cpfCnpjFormatar($cpfCnpj)
{
	if (strlen(intval($cpfCnpj)) > 11) {
		$cpfCnpj = str_pad($cpfCnpj, 14, "0", STR_PAD_LEFT);
		$cpfCnpjFormatado = substr($cpfCnpj,0,2).".";
		$cpfCnpjFormatado .= substr($cpfCnpj,2,3).".";
		$cpfCnpjFormatado .= substr($cpfCnpj,5,3)."/";
		$cpfCnpjFormatado .= substr($cpfCnpj,8,4)."-";
		$cpfCnpjFormatado .= substr($cpfCnpj,12,2);
	} else {
		$cpfCnpj = str_pad($cpfCnpj, 11, "0", STR_PAD_LEFT);
		$cpfCnpjFormatado = substr($cpfCnpj,3,3).".";
		$cpfCnpjFormatado .= substr($cpfCnpj,6,3).".";
		$cpfCnpjFormatado .= substr($cpfCnpj,9,3)."-";
		$cpfCnpjFormatado .= substr($cpfCnpj,12,2);
	}
	return $cpfCnpjFormatado;
}

/**
 *  Limpa os zeros a mais
 *  @param   string $cpfCnpj
 *  @return   string $cpfCnpj
 */
function cb_limparZeros($cpfCnpj)
{
	if (strlen($cpfCnpj) > 11) {
		if(substr($cpfCnpj, 0,3) == '0'){
			$cpfCnpj = substr($cpfCnpj, 3,11);		
		}	
	}

	return $cpfCnpj;
}

/**
 *  remove qualquer caracter especiais que contenha dentro de uma string
 *  @param   string $str
 *  @return  string $str
 */
function cb_limparCaracteresEspeciais($str)
{
	$transformArray = array(
		"ç" => "c",
		"á" => "a",
		"à" => "a",
		"ã" => "a",
		"â" => "a",
		"ä" => "a",
		"é" => "e",
		"è" => "e",
		"ê" => "e",
		"ë" => "e",
		"í" => "i",
		"ì" => "i",
		"î" => "i",
		"ï" => "i",
		"ó" => "o",
		"ò" => "o",
		"õ" => "o",
		"ô" => "o",
		"ö" => "o",
		"ú" => "u",
		"ù" => "u",
		"û" => "u",
		"ü" => "u",
		);
	$str = strtr($str, $transformArray);
    $str = preg_replace('/[^a-z0-9]/i', ' ', $str);
    return $str;
}

/**
 * Calcula X dias a partir da dataAtual 
 * antes a funcao chamava cc_calcularData21dias()
 * @param  int $xDias a quantidade de dias para trás
 * @return string $novaData
 */
function cc_calcularDataHojeMenosXisDias($xDias = 10)
{
	$dataAtual = gmdate('Y-m-d');

	$dia = substr($dataAtual, 8, 9);
	$mes = substr($dataAtual, 5, 2);
	$ano = substr($dataAtual, 0, 4);

	$timestamp = mktime(0, 0, 0, $mes, $dia, $ano);
	$qtdDiasSegundos = $xDias * 86400;
	$timestamp = $timestamp - $qtdDiasSegundos;
	$novaData = date('Y-m-d', $timestamp);

	return $novaData;
}

/**
 * Limpa CPF ou CNPJ, removendo - . /
 * @param string $login
 * @return string $login
 */
function cb_limparLogin($login)
{
	// substitui símbolos com funcao str_replace()
	$login = str_replace(".","",$login);
	$login = str_replace("-","",$login);
	$login = str_replace("/","",$login);
	$login = str_replace("-","",$login);
	$login = trim($login);
	return $login;
}

/**
 * Adiciona um dia a mais na data , caso a mesma passe de 20 e 45 - . /
 * @param string $row
 * @return string $row
 */
function cb_recalcularDia($row)
{
	$row .=" 20:45:00";
	if ($row < gmdate('Y-m-d H:i:s')) {
		// esta vencido: recalcular
		if (gmdate('H:i:s') > '20:45:00') {
			// para o dia seguinte
			$row = gmdate('Y-m-d', time() + 86400);
		} else {
			// para hoje
			$row = gmdate('Y-m-d');
		}
	}
	$row = substr($row, 0,10);
	return $row;
}

/**
 * formata um CEP
 * @param  string $cep 
 * @return  string $cepFormatado
 */
function cb_FormatCep($cep)
{
	$cep = cb_limparLogin($cep);

	return substr($cep,0,5)."-".substr($cep,5,3);
}
/*
 * Retorna o dia da semana de uma determinada data.
 */
function diasemana($data)
{  
	$dia =  substr($data,8,2);
	$mes =  substr($data,5,2);
	$ano =  substr($data,0,4);

	$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );
	// switch($diasemana){  
	// 				case"0": $diasemana = "Domingo";	   break;  
	// 				case"1": $diasemana = "Segunda-Feira"; break;  
	// 				case"2": $diasemana = "Terça-Feira";   break;  
	// 				case"3": $diasemana = "Quarta-Feira";  break;  
	// 				case"4": $diasemana = "Quinta-Feira";  break;  
	// 				case"5": $diasemana = "---ta-Feira";   break;  
	// 				case"6": $diasemana = "Sábado";		break;  
	// 			 }
	return $diasemana;
}

/** 
 * Transfere uma data que caia no sabado ou domingo para segunda-feira
 * @bug só está sendo usado no admin, e o mais importante era para o cliente
 * 20150621: nova funcao por Ricardo: cb_valorMulta($valorBoleto, $vencimento)
 */
function mudarDataParaSegunda($data)
{
	// BUG: boletos com vencimento para 01/fev/2015 estão sendo redefinidos para 01/ago/2016 por esta função
	// 
	// eliminando o processamento dela:
	return $data;// ou seja, retorna o mesmo que entrou (por Ricardo, em 2015-01-27)

	// RODRIGO: verificar e corrigir


	$diaSemana = diasemana($data);
	if ( $diaSemana == "0"){
		$timestampDataAntiga = ohs_dbdate2timestamp($data);
		$quantidadeDias = 1;
		$timestamp1Dia = 86400;
		$timeStampTotal = $timestamp1Dia * $quantidadeDias;
		$novoTimeStamp = $timestampDataAntiga + ($timeStampTotal);
		$novaData = date("Y-m-d", $novoTimeStamp);
		if (diasemana($novaData) == 1) {
			$html = $novaData;
		} else {
			$html = $data;
		}
	} else if ($diaSemana == "6") {
		$timestampDataAntiga = ohs_dbdate2timestamp($data);
		$quantidadeDias = 2;
		$timestamp1Dia = 86400;
		$timeStampTotal = $timestamp1Dia * $quantidadeDias;
		$novoTimeStamp = $timestampDataAntiga + ($timeStampTotal);
		$novaData = date("Y-m-d", $novoTimeStamp);
		if (diasemana($novaData) == 1) {
			$html = $novaData;
		} else {
			$html = $data;
		}
	} else {
		$html = $data;
	}
	return $html;
}

/**
 * cálculo de juros e multa para boleto vencidos
 * @param  float $valorBoleto o valor nominal do boleto
 * @param datetime $vencimento a data de vencimento do boleto em formato MySql
 * @return array um array associativo ['vencimento' => 'yyyy-mm-aa', 'acrescimo' => n.nn, 'valorFinal' => n.nn]
 */
function cb_valorMulta($valorBoleto, $vencimento)
{
	// feriados
	$feriados = array(
		'2015-11-02',
		'2015-11-20',
		'2015-12-24',
		'2015-12-25',
		'2015-12-30',
		'2015-12-31',
		'2016-01-01',
		'2016-02-09',
		);

	// se o dia de vencimento for feriado, não inclui multa
	if (in_array($vencimento, $feriados)) {
		return array(
			'vencimento' => $vencimento,
			//'multa' => '0',
			//'juros' => '0',
			'acrescimo' => 0,
			//'multaDias' => '0',
			'valorFinal' => $valorBoleto
			);
	}

	//global $timestampOriginal,$timestamp,$diaSemanaHoje,$diff,$diaSemanaHoje,$diaSemanaVenc;

	// transforma vencimento em timestamp e descobre o dia da semana
	// completa o vencimento como uma datetime mysql às 2 h para não haver problema de troca de dia na hora GMT2local
	$vencimento .= ' 12:00:00';
	$timestampOriginal = ohs_dbdate2timestamp($vencimento);
	$hojeStamp = ohs_dbdate2timestamp(date('Y-m-d 12:00:00'));
	$diaSemanaVenc = date('l', $timestampOriginal);
	$diaSemanaHoje = date('l', $hojeStamp);
	$diasDeMulta = 0;

	// vencimento é sábado? -> aumentar 2 dias
	if ($diaSemanaVenc == 'Saturday') {
		$timestamp = $timestampOriginal + (86400 * 2);
		$vencimentoAjustado = date('Y-m-d H:i:s', $timestamp);
	} elseif ($diaSemanaVenc == 'Sunday') {
		// vencimento é domingo? -> aumentar 1 dia
		$timestamp = $timestampOriginal + 86400;
		$vencimentoAjustado = date('Y-m-d H:i:s', $timestamp);
	} else {
		// se não for dia especial, o vencimento para calculo de juros é o vencimento nominal
		$vencimentoAjustado = $vencimento;
		$timestamp = $timestampOriginal;
	}

	// se boleto estiver vencido, calcular juros e multa
	if ($vencimentoAjustado < gmdate("Y-m-d 12:00:00")) {

		$vencimentoAjustado = gmdate("Y-m-d 12:00:00");

		$diff = $hojeStamp - $timestampOriginal;

		$diasDeMulta = $diff / 86400;

		// multa de 2%
		$multa = 0.02 * $valorBoleto;

		// juros de 0.033% ao dia //M = P . ( 1 + ( i . n ) ) OU Juros = P.i.n
		$juros = $valorBoleto * 0.00033 * $diasDeMulta;

		//multa e juros
		$acrescimo = $multa + $juros;			

		$valorFinal = $valorBoleto + $acrescimo;

		$retorno = array(
			'vencimento' => $vencimentoAjustado,
			//'multa' => $multa,
			//'juros' => $juros,
			'acrescimo' => $acrescimo,
			//'multaDias' => $diasDeMulta,
			'valorFinal' => round($valorFinal, 2)
			);
	} else {
		$retorno = array(
			'vencimento' => $vencimentoAjustado,
			//'multa' => '0',
			//'juros' => '0',
			'acrescimo' => 0,
			//'multaDias' => '0',
			'valorFinal' => $valorBoleto
			);
	}

	return $retorno;
}

/**
 * exibe o menu-principal para o cliente
 * @return string $html
 */
function cb_menuCliente()
{
	return"
		<li><b>{$_SESSION['nome']} &raquo; </b></li>
		<li><a href='?p=boletos'>Boletos</a></li>
		<li><a href='?p=editar'>Editar informações</a></li>
		<li><a href='?p=sair'>Sair</a></li>
		";
		//<p>Ol&aacute;, ".$_SESSION['nome']." &nbsp; ";return $html;
}

/**
 * menu para administradores
 * @return  string
 */
function cb_menuAdmin()
{
	return "
		<li><a href='?p=boleto'>Boletos</a></li>
		<li><a href='?p=cliente'>Clientes</a></li>
		<li><a href='?p=arquivo'>Arquivos Remessa/Retorno</a></li>
		<li><a href='?p=historico&amp;q=usuarios'>Histórico</a></li>
		<li><a href='?p=relatorio&amp;r=pagamento'>Relatórios</a></li>
		<li><a href='?p=admin'>Administradores</a></li>
		<li><a href='?p=sair'>Sair</a></li>
	";
}

/**
 * exibe os boletos para o cliente.
 * @return string $html
 */
function cb_boletosClientes()
{
	// apaga boletos com mais de 10 dias de atraso
	cb_boletoDeleteAntigos();

	$html = '';
	
	$tam = strlen($_SESSION['cliente']); 

	if($tam == 11) {
		$_SESSION['cliente'] = "000".$_SESSION['cliente'];
	}
	
	$qtdRegistros = mysql_result(mysql_query("SELECT COUNT(boletoId) AS qtd FROM boleto WHERE cpf_cnpj = {$_SESSION['cliente']}"), 0, 'qtd');
	
	if($qtdRegistros == 0){ 
		$html .= "<center>Neste momento não existem boletos cadastrados.</center>";	
	} else {
		$xDias = cc_calcularDataHojeMenosXisDias(10);//cc_calcularData21dias();

		// seleciona apenas boletos (a) do cliente logado, (b) pendentes e (c) com o máximo de 21 dias de atraso		
		$sql= "SELECT boletoId,bancoId,dataVencimento,dataPagamento,valorTitulo,cpf_cnpj,nomeSacado,nossoNumero,numeroDocumento,msg2 FROM boleto WHERE cpf_cnpj = {$_SESSION['cliente']}  AND dataVencimento > '$xDias'  AND dataPagamento = '0000-00-00' AND nossoNumero <> '000000000000000'";
		
		if ($result = mysql_query($sql)) {
			if(mysql_num_rows($result) > 0){
				$html .= "
					<table border='0' cellspacing='10' cellpadding='10'>
						<tr>
							<th>N&ordm; Doc</th>
							<th>Descrição</th>				
							<th>Nome Sacado</th>				
							<th>Nosso N&uacute;mero</th>
							<th>Vencimento</th>
							<th>Valor</th>
							<th>&nbsp;</th>	
						</tr>
				";
				while ($boleto = mysql_fetch_array($result)) {
					$cont++;
					$html .= "
						<tr>
							<td>".$boleto['numeroDocumento']."</td>
							<td style='width:250px;line-height:1.2em;'>".$boleto['msg2']."</td>
							<td>".$boleto['nomeSacado']."</td>
							<td>".$boleto['nossoNumero']."</td>
							<td>".substr($boleto['dataVencimento'],8,2)."/".substr($boleto['dataVencimento'],5,2)."/".substr($boleto['dataVencimento'],0,4)."</td>
							<td>".number_format((intval($boleto['valorTitulo'])/100), 2, ",", ".")."</td>
							<td>";
								if($boleto['bancoId'] == 104){
									$html .= "<a href='boleto_cef_sigcb.php?id=".$boleto['boletoId']."&nosso={$boleto['nossoNumero']}' target='boleto'><img src='http://civilcorp.com.br/ohs/pub/3rdparty/icons/printer.png' width='20' height='20'></a>";
								} elseif ($boleto['bancoId'] == 237){
									$html .= "<a href='boleto_bradesco.php?id=".$boleto['boletoId']."&nosso={$boleto['nossoNumero']}' target='boleto'><img src='http://civilcorp.com.br/ohs/pub/3rdparty/icons/printer.png' width='20' height='20'></a>";		
								}
								
							"</td>
						</tr>
						";
				}//fim while
			} else {
				$html .= "<center>Nenhum boleto pendente neste momento.</center>";
			}
		} else {
			$html .= "
				<tr>
					<td colspan='8' style='text-align:center;'>Nenhum boleto pendente neste momento.</td>
				</tr>
				";
		}

		$html .= "
			</table>
		";
	}
	return $html;
}

/**
* Validacao do Login
* @return string $html
*/
function cb_loginValidacao()
{
	require_once("../inc/funcoes.php");
	
	$login = strtolower(mysql_real_escape_string(strip_tags($_POST['usuario'])));

	$cpfCnpj = str_pad(cb_limparLogin($login), 14, "0", STR_PAD_LEFT);

	$senha = mysql_real_escape_string(strip_tags($_POST['senha']));
	$datahora = date('Y-m-d h:i:s');

	if (!is_numeric($cpfCnpj)) {
		// é administrador
		if ($resultado = mysql_query("SELECT adminId, nome, login FROM usuarioAdmin WHERE login LIKE '$login' AND senha LIKE '$senha'")){
			if($rows = mysql_num_rows($resultado)){
				$adminId = mysql_result($resultado,0,'adminId');
				$_SESSION['nome'] = mysql_result($resultado,0,'nome');
				$_SESSION['admin'] = mysql_result($resultado,0,'login');
				$html = "<script>document.location.href='../admin/index.php';</script>";

				cb_boletoHistoricoInsert($login, "Admin: Acesso", $adminId);
				//mysql_query("INSERT INTO historicoUsuario (nomeUsuario,data,tipo) VALUES ('$login','$datahora','Administrador')");
			} else {				
				cb_boletoHistoricoInsert($login, "Admin: Identifição de admin falhou", '0');
				$html .= "<script>document.location.href='../admin/index.php';</script>";
			}
		} 
	} else {
		// é cliente
		if ($resultado = mysql_query("SELECT usuarioId, nome, cpf_cnpj FROM usuario WHERE cpf_cnpj LIKE '$cpfCnpj' AND senha LIKE '$senha'")){
			if ($rows = mysql_num_rows($resultado)) {
				$_SESSION['nome'] = mysql_result($resultado,0,'nome');
				$_SESSION['userId'] = mysql_result($resultado,0,'usuarioId');
				$_SESSION['cliente'] = mysql_result($resultado,0,'cpf_cnpj');
				
				/*
				$datahora = gmdate('Y-m-d h:i:s');
				$sql = "INSERT INTO historicoUsuario (nomeUsuario,data,tipo,usuarioId) VALUES ('$_SESSION[nome]','$datahora','Cliente','$_SESSION[userId]')";
				mysql_query($sql);
				*/
				cb_boletoHistoricoInsert($_SESSION['nome'], "Cliente {$_SESSION['cliente']}: Acesso", $_SESSION['userId']);
				$html = "<script>document.location.href='../cliente/index.php';</script>";
			} else {
				
				cb_boletoHistoricoInsert($cpfCnpj, "Cliente: Acesso falhou", 0);

				$debugMsg2 = "CNPJ: $cpfCnpj\n\nCNPJ ou senha inválida";
				
				cb_mail('erroboletos@civilcorp.com.br', 'Civilcorp: erro de acesso ao sistema de boletos', $debugMsg2);
				$html = "<script>document.location.href='../cliente/index.php';</script>";	
			}
		}
		
	}
	return $html;
}

/**
 * tela de login
 * @return string $html
*/
function cb_loginTela()
{
	$html = "
		<div style='width:400px;text-align:center;padding:30px;margin:auto;'>
			<form method='post' action='?p=login' class='tela-login'>
				<div><input type='text' name='usuario' placeholder='Usuario' /></div>
				<div><input type='password' name='senha' placeholder='Senha' /></div>
				<div><input type='submit' name='enviarDados' value='Acessar o sistema' ></div>
				<div style='padding-top:10px;'><a href='?p=recuperar'>Recuperar ou cadastrar senha</a></div>
			</form>
		</div>
	";
	return $html;
}

function cb_Paginacao($parametros,$qtdRegistros)
{
	global $config;

	if(!$_GET['pagina']){
		$_GET['pagina'] = 1;
	}

	$paginacao['inicio'] = ($_GET['pagina'] * $config['qtdPaginacao']) - $config['qtdPaginacao'];
	$paginacao['pagina'] = $_GET['pagina'];
	$qtdPaginas = ceil($qtdRegistros / $config['qtdPaginacao']);
	$anteriorPagina = $paginacao['pagina'] - 1;
	$proximaPagina = $paginacao['pagina'] + 1;

	if($qtdPaginas > 1) {
		if($qtdPaginas <= 10) {
			$paginacao['listaLinksPaginacao'] = "
				<ul class = 'container paginacao'>
				";
			$paginaInicio = 0;
			if ($paginacao['pagina'] != 1) {
				$paginacao['listaLinksPaginacao'] .= "
				<li><a href='$parametros&pagina=1' title='Ir para primeira pagina'> &laquo;&laquo; </a></li>
					<li><a href='$parametros&pagina=$anteriorPagina' title='Página Anterior'><b> &laquo; </b></a></li>
				";
			}
			for ($i=1; $i <= ceil($qtdRegistros/$config['qtdPaginacao']); $i++) {
				if ($paginacao['pagina'] == $i) {
						$paginacao['listaLinksPaginacao'] .= "
							<li><a href='$parametros&pagina=$i'><b>$i</b></a></li>
						";
				} else {
					$paginacao['listaLinksPaginacao'] .= "
						<li><a href='$parametros&pagina=$i'>$i</a></li>
						";
				}
			}	
			if ($paginacao['pagina'] != $qtdPaginas) {
				$paginacao['listaLinksPaginacao'] .= "
					<li><a href='$parametros&pagina=$proximaPagina' title='Proxima Página'><b> &raquo; </b></a></li>
					<li><a href='$parametros&pagina=$qtdPaginas' title='Ir para ultima pagina'> &raquo;&raquo; </a></li>
				";
			}
			$paginacao['listaLinksPaginacao'] .= "
				</ul>
			";
		} else {
			$min = $paginacao['pagina'] - 2;
			$max = $paginacao['pagina'] + 2;
			if($min < 1){
				$min = 1;
			}
			if ($max > $qtdPaginas){
				$max = $qtdPaginas;
			}
			$paginacao['listaLinksPaginacao'] = "
				<ul class = 'container paginacao'>
				";
			if ($paginacao['pagina'] != 1) {
				$paginacao['listaLinksPaginacao'] .= "
					<li><a href='$parametros&pagina=1' title='Ir para primeira pagina'> &laquo;&laquo; </a></li>
					<li><a href='$parametros&pagina=$anteriorPagina' title='Página Anterior'> <b> &laquo; </b> </a></li>
				";
			}

			for ($i = $min; $i<= $max; $i++) {
				if ($paginacao['pagina'] == $i) {
					$paginacao['listaLinksPaginacao'] .= "
						<li><a href='$parametros&pagina=$i'> <b> $i </b></a></li>
					";
				} else {
					$paginacao['listaLinksPaginacao'] .= "
						<li><a href='$parametros&pagina=$i'> $i </a></li>
					";
				}
			}
			if($paginacao['pagina'] != $qtdPaginas) {
				$paginacao['listaLinksPaginacao'] .= "
					<li><a href='$parametros&pagina=$proximaPagina' title='Pr&oacute;xima P&aacute;gina'> <b> &raquo; </b> </a></li>
					<li><a href='$parametros&pagina=$qtdPaginas' title='Ir para &uacute;ltima P&aacute;gina'> &raquo;&raquo; </a></li>
				";
			
			}
			$paginacao['listaLinksPaginacao'] .= "
				
				</ul>
			";//<li style='display:block;'><i>$qtdPaginas P&aacute;ginas</i></li>
		}
	} else {
		$paginacao['listaLinksPaginacao'] = "";
	}

	return $paginacao;
}

/**
 * Deleta boletos antigos do sistema
 * @return void
 */
function cb_boletoDeleteAntigos()
{
	// permite visualizar no máximo com 10 dias de atraso
	$CI =& get_instance();
	$xDias = cc_calcularDataHojeMenosXisDias(9);

	$CI->db->query("DELETE FROM boleto WHERE dataVencimento < '$xDias'");
}

/**
 * Insere um registro de acesso no historico do sistema
 * @param  string  $nome      o nome do usuário, se existir
 * @param  string  $tipo      [Clinete, Admin e detalhes do acesso]
 * @param  int $usuarioId 
 * @return void
 */
function cb_boletoHistoricoInsert($nome, $tipo, $usuarioId = 0)
{	
	$CI =& get_instance();
	$datahora = gmdate('Y-m-d H:i:s');

	$CI->db->query("INSERT INTO historicoUsuario (nomeUsuario, data, tipo, usuarioId) VALUES ('$nome', '$datahora', '$tipo', '$usuarioId')");
}

/**
 * envia um e-mail com usuário autenticado
 * @return bool
 */
function cb_mail($para, $assunto, $msg, $de = 'sistema@clientecivilcorp.com.br')
{
	@date_default_timezone_set('Etc/UTC');

	$usuario =  'sistema@clientecivilcorp.com.br';
	$senha = ').R-pmP3tiKW';

	require_once($_SERVER['DOCUMENT_ROOT'].'/novo_sistema/assets/inc/PHPMailer/PHPMailerAutoload.php');

	$mail = new PHPMailer();

	if (!$de) {
		$emailSender = $usuario;
		$senderParam = '0';
	} else {
		$emailSender = $de;
		$senderParam = 'true';
	}
	
	$mail->setFrom($emailSender, '', $senderParam);
	$mail->addAddress($para);
	$mail->Subject = $assunto;
	$mail->Body = $mail->html2text($msg);

	$mail->isSMTP();
	$mail->Host = 'mail.clientecivilcorp.com.br';
	$mail->SMTPAuth = true;
	$mail->Username = $usuario;
	$mail->Password = $senha;
	$mail->Port = 25;

	if (!$mail->Send()) {
	   return false;
	} else {
		return true;
	}
}

function ohs_formataData($data){


	return $data;

}



?>