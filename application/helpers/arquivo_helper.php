<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
* Este arquivo contém funçoes do ARQUIVO.
*/


/**
 * Recebe arquivos apenas REM, OLD e RET e faz a inserção de seus dados na base de dados
 * @param string $banco String contendo o nome do banco que o arquivo pertence
 * @return string $html
 */
function ca_arquivoInsert()
{
	global $nome_arquivo;
	global $data_envio;
	global $status_envio;

	//INFO ARQUIVO
		$file 		= $_FILES['arquivo'];
		$numFile	= count(array_filter($file['name']));
		
		//PASTA
		$folder		= 'temp/';
		
		//REQUISITOS
		$permite 	= array( 'REM', 'RET', 'OLD', 'old', 'rem' , 'ret');
		$maxSize	= 1024 * 1024 * 10; // Max 10MB
	$msg = array();
	$errorMsg = array (
		0 => 'Não houve erro',
		1 => 'O arquivo no upload é maior do que o limite do PHP',
		2 => 'O arquivo ultrapassa o limite de tamanho especifiado no HTML',
		3 => 'O upload do arquivo foi feito parcialmente',
		4 => 'Não foi feito o upload do arquivo',

		);


	
	$file = $_FILES['arquivo'];
	$numFile = count(array_filter($file['name']));


	if ($numFile <= 0){
		$html .= "<script> alert('Selecione pelo menos um arquivo')</script>";
		$html .= ca_arquivoFormularioInsert();
	} else {
				
		$cont = 0;
		for($i =0 ; $i < $numFile ; $i++){
				$name 	= $file['name'][$i];
				$type	= $file['type'][$i];
				$size	= $file['size'][$i];
				$error	= $file['error'][$i];
				$tmp	= $file['tmp_name'][$i];
				
				$extensao = @end(explode('.', $name));
				$novoNome = rand().".$extensao";
				
				if($error != 0)
					$html .= "<b>$name :</b> ".$errorMsg[$error];
				else if(!in_array($extensao, $permite)){
					$html .=  "<b>$name :</b> Erro arquivo $extensao não suportado! <br>";
					$html .= ca_arquivoFormularioInsert();
				} else if($size > $maxSize){
					$html .= "<b>$name :</b> Erro imagem ultrapassa o limite de 10MB <br>";
					$html .= ca_arquivoFormularioInsert();
				} else {
					
					if(move_uploaded_file($tmp, $folder.'/'.$novoNome))
					{
						$arquivosEnviados .= "<b>$name : Upload Realizado com Sucesso! <br>";
						$cont++;
						$status_envio = 1;
						$data_envio = date("Y-m-d");
						$data_envio .= " ".date("H:i");
						$nome_arquivo = $novoNome;
						
						$text = file("temp/$nome_arquivo");
						if(substr($text[0], 76, 3) == "237"){
								$nomeBanco = "BRADESCO";
								$html .= cb_boletoInsertBradesco();
								ch_historicoInsertArquivo($nomeBanco);
								// $html .= cb_boletoSelect();
						} elseif (substr($text[0], 0, 3) == "104"){								
								$nomeBanco = "CAIXA ECONOMICA";
								cb_boletoInsertCaixaEconomica();
								ch_historicoInsertArquivo($nomeBanco);								
								// $html .= cb_boletoSelect();
						}			
						 
						if (!unlink("temp/".$novoNome)){
							$html .= " Erro ao deletar $novoNome ";
						}

					} else {
						$msg[] = "<b>$novoNome :</b> Desculpe! Ocorreu um erro...";
						$html .= " Não foi possível enviar o arquivo, tente novamente";
					}
			}//fim do else			
		} // fim do for
		if($cont != 0){
			$html .= "<center> $arquivosEnviados <br> <a href='?p=boleto'> <button> Visualizar Boletos</button></a></center>";			
		}
	} //fim do primeiro else 
	return $html;
}


/**
 * Faz a listagem de funcionarios.
 * @return string $html
 */
function ca_arquivoFormularioInsert(){
	$html = "
		<form method='POST' action='?p=arquivo&q=enviar' enctype='multipart/form-data'>
			<table border='0' cellspacing='10' cellpadding='10'>
				<tr>
					<td><i>Dica: Você pode selecionar vários arquivos para que sejam enviados de uma única só vez.</i></td>		
				</tr>
				<tr>							
					<td>
						<input type='file' name='arquivo[]' multiple />
						<input type='submit' value='Enviar' />
					</td>
				</tr>
			</table>
		</form>	
	";
	return $html;
}

?>