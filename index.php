<?php
    include_once("_php/acesso.php");
    include_once("_php/classes.php");
    
    if (isset($_POST['enviar'])) {
        try{
                //Aqui definimos as variáveis com os valores que desejamos enviar
                $file = "@/json/answer.json";

                //URL para onde vai ser enviado nosso POST
                $url = $url_enviar;

                // Aqui inicio a função CURL
                $curl = curl_init();
                //aqu eu pego a URL para onde será enviado o POST
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl,CURLOPT_HTTPHEADER,array("Content-Type:multipart/form-data"));
                curl_setopt($curl, CURLOPT_POST, 1);
                //aqui eu pego os dados para enviar via POST
                curl_setopt($curl, CURLOPT_POSTFIELDS, $file);
                curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1); // call return content
                curl_setopt ($curl, CURLOPT_FOLLOWLOCATION, 1); // navigate the endpoint
                curl_setopt ($curl, CURLOPT_POST, true); // set as post
                $response = curl_exec($curl);
                echo ($response);    
                curl_close($curl);
                
                    
            } catch(Exception $e){
                 return $e->getMessage();
            }
    }

    
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
                    $enviaDadosAPI = $COMUNICACAO->enviaDadosAPI($url_receber);
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
                <form action="index.php" enctype="multipart/form-data" method="post">
                    <h1 style="text-align:center;">Desafio CodeNation</h1>    
                    <!--<input type="submit" name="receber" value="Receber Dados">*/-->
                    <input type="submit" name="salvar" value="Receber/Salvar Arquivo answer.json">
                    <input type="submit" name="descriptografar" value="Descriptografar">
                    <input type="submit" name="sha1" value="Converter em SHA1">
                    
                    
                    <form action="index.php" method="POST" enctype="multipart/form-data">
                          <input type="file" name="answer" value="answer">
                          <input type="submit" name="enviar" value="enviar">
                   </form>
                </form>
            </div>
        </main>

        <?php include_once("_incluir/rodape.php"); ?>  
    </body>
</html>