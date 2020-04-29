<?php
    include_once("_php/acesso.php");
    include_once("_php/classes.php");
    
    foreach ($_POST as $key => $val){
    if (isset($key)){
            switch($key){
               case "descriptografar": {
                    $COMUNICACAO = new COMUNICACAO;
                    $descriptografa = $COMUNICACAO->descriptografaDadosAPI($url_receber);
                     //Exibe a resposta da API
                    echo $descriptografa;
                    break;
                }

               case "sha1": {
                    $COMUNICACAO = new COMUNICACAO;
                    $SHA1 = $COMUNICACAO->SHA1($url_receber);
                     //Exibe a resposta da API
                    echo $SHA1;
                    break;
                }
                    
                case "salvar": {
                    $COMUNICACAO = new COMUNICACAO;
                    $salvarDadosAPI = $COMUNICACAO->salvarDadosAPI($url_receber);
                     //Exibe a resposta da API
                    echo $salvarDadosAPI;
                    break;
                }    

               case "enviar": {
                    $COMUNICACAO = new COMUNICACAO;
                    $enviaDadosAPI = $COMUNICACAO->enviaDadosAPI($url_enviar);
                     //Exibe a resposta da API
                    echo $enviaDadosAPI;
                    break;
                } 
                   default:
                        echo("Deu erro!");       
            }
        } else
        echo "Não entrou no switch";
    }    
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Criptografia Json</title>
        
        <!-- estilo -->
        <link href="_css/estilo.css" rel="stylesheet">
        <link href="_css/contato.css" rel="stylesheet">
    </head>

    <body>
        <?php include_once("_incluir/topo.php"); ?>
        
        <main> 
            <div id="janela_formulario">
                <form action="index.php" enctype="multipart/form-data" method="POST">
                    <h1 style="text-align:center;">Desafio CodeNation</h1>    
                    <input type="submit" name="salvar"          value="Receber/Salvar Arquivo answer.json">
                    <input type="submit" name="descriptografar" value="Descriptografar">
                    <input type="submit" name="sha1"            value="Converter em SHA1">
                    <input type="file"   name="answer"          value="answer">
                    <input type="submit" name="enviar"          value="enviar">
                </form>
            </div>
        </main>

        <?php include_once("_incluir/rodape.php"); ?>  
    </body>
</html>