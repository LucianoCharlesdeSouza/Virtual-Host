<?php 
if(!isset($_SESSION)):
  session_start();
endif;

define("PASTA_SERVIDOR", 'H:/xampp/htdocs/');
define("PASTA_APACHE", 'H:\xampp\apache\conf\extra\httpd-vhosts.conf');
define("PASTA_WINDOWS", 'C:\Windows\System32\drivers\etc\hosts');

$dados_form = filter_input_array(INPUT_POST,FILTER_SANITIZE_MAGIC_QUOTES);
if(isset($dados_form['virtual_host']) && !empty($dados_form['virtual_host'])):

    $dados_form['virtual_host'] = trim(strtolower(strip_tags(str_replace(' ','_',$dados_form['virtual_host']))));

    $host_criado = str_replace('www.','',$dados_form['virtual_host']);
    $host_criado = str_replace('.com.br','',$host_criado);
    $host_criado = str_replace('.com','',$host_criado);
    $host_criado = str_replace('.br','',$host_criado);
    $host_criado = str_replace('.com.net','',$host_criado);
    $host_criado = str_replace('.net','',$host_criado);
    $host_criado = str_replace('.edu','',$host_criado);
    $host_criado = str_replace('.gov','',$host_criado);

    $virtual_host = '
    <VirtualHost *:80>
      ServerName '.$host_criado.'.com.br
      ServerAlias www.'.$host_criado.'.com.br
      DocumentRoot "'.PASTA_SERVIDOR.$host_criado.'.com.br"
      ErrorLog "logs/'.$host_criado.'-error.log"
      CustomLog "logs/'.$host_criado.'-access.log" common
      <Directory "'.PASTA_SERVIDOR.$host_criado.'.com.br">
        DirectoryIndex index.php index.html index.htm
        AllowOverride All
        Order allow,deny
        Allow from all
      </Directory>
    </VirtualHost>';

    $pula_linha ="\r\n";

    $caminho_vhosts = PASTA_APACHE;

    try {
      // PULA UMA LINHA NO ARQUIVO VHOSTS DO APACHE
      $file = fopen($caminho_vhosts,'a');
      fwrite($file, $pula_linha);

      // CRIA O VIRTUAL HOST NO ARQUIVO VHOST DO APACHE
      $file = fopen($caminho_vhosts,'a');
      fwrite($file, $virtual_host);
      fclose($file);  
    } catch (Exception $e) {
      $_SESSION['msg'] = "Erro ao Ler/Criar arqivo Virtual Host";
}

    // CRIA O ENDEREÇO SEM O WWW NO ARQUIVO HOSTS DO WINDOWS
    $caminho_localhosts = PASTA_WINDOWS;
    try {
      $file = fopen($caminho_localhosts,'a');
      fwrite($file, $pula_linha);

      $local_host_of_www = "127.0.0.1   ".$host_criado.".com.br";

      $file = fopen($caminho_localhosts,'a');
      fwrite($file, $local_host_of_www);
      fclose($file); 

      // CRIA O ENDEREÇO COM O WWW NO ARQUIVO HOSTS DO WINDOWS
      $file = fopen($caminho_localhosts,'a');
      fwrite($file, $pula_linha);

      $local_host_on_www = "127.0.0.1   www.".$host_criado.".com.br";

      $file = fopen($caminho_localhosts,'a');
      fwrite($file, $local_host_on_www);
      fclose($file);
    } catch (Exception $e) {
      $_SESSION['msg'] = "Erro ao Ler/Criar arqivo Hosts";
}

    try {
      // CRIA A PASTA DO PROJETO NO DIRETORIO HTDOCS DO XAMPP
      mkdir(PASTA_SERVIDOR.$host_criado.".com.br", 0700);
    } catch (Exception $e) {
      $_SESSION['msg'] = "Erro ao criar a pasta do projeto";
}

    try {
      $index = '
      <!DOCTYPE html>
      <html lang="pt-br">
      <head>
        <meta charset="utf-8">
        <title> Página gerada pelo gerador de Host Virtual.</title>
        <style>
          h1{
            font-size:1.5em;
            text-align:center;
            color: #ccc;
          }
        </style>
      </head>
      <body>
        <h1>Ola, seja bem vindo(a) a sua página gerada de forma dinâmica pelo gerador de Hosts Virtuais!</h1>
      </body>  
      </html>';

      $dir_projeto = PASTA_SERVIDOR.$host_criado.".com.br/index.php";
      $file = fopen($dir_projeto,'a');
      fwrite($file, $index);
      fclose($file);

    } catch (Exception $e) {
      $_SESSION['msg'] = "Erro ao criar index.php na pasta do projeto";
}
      
    $_SESSION['msg'] = "<p>Virtual Host criado com sucesso!</p>
    <p>Por favor reinicie seu servidor apache e depois acesse o link.</p>
    <p><a href='http://".$host_criado.".com.br' title='Meu novo host!' target='_blank'>".$host_criado.".com.br</a></p>";
    header("Location: index.php");
  else:
    $_SESSION['msg'] = "É necessário preencher o campo acima!";
    header("Location: index.php");
  endif;  
