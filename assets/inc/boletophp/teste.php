<?php

require('funcoes_cef_sigcb.php');

$numero = strip_tags($_GET['numero']);

echo "<br>Modulo 10 de $numero � ".modulo_10($numero);
echo "<br>Modulo 11 de $numero � ".modulo_11($numero);


?>