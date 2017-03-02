<?php
/**
* Este arquivo salva o arquivo enviado na pasta temp.
* @author Matheus Azevedo <matheus.azevedo@operahouse.com.br> & Rodrigo Carvalho <@operahouse.com.br>
* @todo 
* @see http:http://oph.com.br/estagio/civilcorp/
* @copyright Copyright (c) 2014, operahouse.com.br
*/
?>

<?php
// Pasta onde o arquivo vai ser salvo
$_UP['pasta'] = 'temp/';

// Tamanho máximo do arquivo (em Bytes)
$_UP['tamanho'] = 1024 * 1024 * 2; // 2Mb

// Array com as extensões permitidas
$_UP['extensoes'] = array('rem', 'ret');

// Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)
$_UP['renomeia'] = false;

// Array com os tipos de erros de upload do PHP
$_UP['erros'][0] = 'Não houve erro';
$_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
$_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
$_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
$_UP['erros'][4] = 'Não foi feito o upload do arquivo';

// Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
if ($_FILES['arquivo']['error'] != 0) {
	die ("Não foi possível fazer o upload, erro:<br />".$_UP['erros'][$_FILES['arquivo']['error']]);
exit; // Para a execução do script
}

// Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar

// Faz a verificação da extensão do arquivo
$extensao = strtolower(end(explode('.', $_FILES['arquivo']['name'])));
if (array_search($extensao, $_UP['extensoes']) === false) {
	echo "Por favor, envie arquivos com as seguintes extensões: REM e RET";
}

// Faz a verificação do tamanho do arquivo
else if ($_UP['tamanho'] < $_FILES['arquivo']['size']) {
	echo "O arquivo enviado é muito grande, envie arquivos de até 2Mb.";
}

// O arquivo passou em todas as verificações, hora de tentar movê-lo para a pasta
else {
// Primeiro verifica se deve trocar o nome do arquivo
if ($_UP['renomeia'] == true) {
// Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .jpg
	$nome_final = time().'.ret';
} else {
// Mantém o nome original do arquivo
	$nome_final = $_FILES['arquivo']['name'];
}

// Depois verifica se é possível mover o arquivo para a pasta escolhida
if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_final)) {
// Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
	echo "<script> alert('Upload efetuado com sucesso!'); </script>";
	$statusEnvio = 1;
	$data = date("Y-m-d");
	$dataEnvio = "20".substr($data,2,2);
	$dataEnvio .= substr($data,5,2);
	$dataEnvio .= substr($data,9,2);
	$dataEnvio .= " ".date("H:i");
	require("boleto-inserir.php");
	require("historico-arquivosInsere.php");
	require("boleto-listar.php");
	if (!unlink("temp/".$nome_final)){
		echo ("Erro ao deletar $nome_final");
	}
}else{
	// Não foi possível fazer o upload, provavelmente a pasta está incorreta
	echo "Não foi possível enviar o arquivo, tente novamente";
}

}
?>

