<?php     
    require_once("_php/acesso.php");
    class COMUNICACAO {
 
        public $filename = "answer.json";
        public $result_rec = null;
        public $decifrado = "";
        
        //FUNCAO INCLUIR DADOS 
        public function incluirDados($dados_rec) {
            $dados_json = json_encode($dados_rec);
            //VERIFICA A EXISTENCIA DO ANSWER E CRIA/APAGA O MESMO PARA RECRIAR
            if (file_exists($this->filename)) {
                    //APAGA O ARQUIVO EXISTENTE
                    unlink($this->filename);
                }
            // Cria o arquivo cadastro.json, o parâmetro "a" indica que o arquivo será aberto para escrita
            $fp = fopen($this->filename, "a");
            // Escreve o conteúdo JSON no arquivo - PARA PULAR UMA LINHA ADC:.chr(13).chr(10)
            $escreve = fwrite($fp, $dados_json);
            // Fecha o arquivo
            fclose($fp);
        }    
        
        
        //FUNÇÃO PARA LER OS DADOS DA API
         public function recebeDadosAPI($url_r) {
             try{
                //Inicializa cURL para uma URL.
                $ch = curl_init();
                //CONFIGURA PARAMETROS DO RECEBIMENTO
                curl_setopt($ch,CURLOPT_URL, $url_r);
                //MARCA QUE VAI RECEBER A STRING
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                //INICIA A CONEXÃO
                $result_rec = curl_exec($ch);
                //Fecha a conexão
                curl_close($ch);
             } catch(Exception $e){
                 return $e->getMessage();
             }
         return $result_rec;
         }
        
        
        //LER DADOS DO ARQUIVO
        public function lerDados() {
            try{
                $filename = "answer.json";
                $json_file = "";
                $dados_json ="";
                $json_file = file_get_contents($filename);   
                $dados_json = json_decode($json_file, true);
            } catch(Exception $e){
                 return $e->getMessage();
             }    
                return $dados_json;
        }
        
        
        //FUNÇÃO PARA DESCRIPTOGRAFAR OS DADOS DA API
        public function descriptografaDadosAPI($url_r) {
            //EXECUTA FUNÇÃO QUE RECEBE O JSON E TRANSFORMA EM ARRAY
            $dados_json = $this->lerDados();
            
            //CRIA UMA VARIAVEL COM O ALFABETO PARA DESCRIPTOGRAFAR
            $alfabeto = range('a','z');
            
            //RECEBE E TRANSFORMA A CHAVE EM NUMEROS E TRANSFORMA EM LOWCASE
            $cifrado = array_map('strtolower',str_split($dados_json["cifrado"]));
            
            //TRATAMENTO DA DESCRIPTOGRAFIA
            for ($i=0;$i<strlen($dados_json["cifrado"]);$i++)
            {
                if ($cifrado[$i] != "." && $cifrado[$i] != " " && !ctype_digit($cifrado[$i])){
                    $cifrado[$i] = (array_search($cifrado[$i],$alfabeto)-$dados_json["numero_casas"]);
                    if ($cifrado[$i] <0)
                    $cifrado[$i] = $cifrado[$i] + 26;
                    $cifrado[$i] = $alfabeto[$cifrado[$i]];
                } else if (ctype_digit($cifrado[$i])) {
                    $cifrado[$i] = cifrado[$i];
                }
                //VERIFICA INSERE OS PONTOS E ESPACOS
                if ($cifrado[$i] === " "){
                    $this->decifrado = $this->decifrado." ";
                } else if ($cifrado[$i] == "."){
                    $this->decifrado = $this->decifrado.".";
                } else {
                    $this->decifrado = $this->decifrado.$cifrado[$i];
                }
            }
            //INSERE A INFORMAÇÃO NA VARIAVEL $DADOS_JSON
            $dados_json["decifrado"] = $this->decifrado;
            
            //FUNCAO PARA INCLUIR DADOS
            $this->incluirDados($dados_json);
            return ("Código decifrado e gravado no arquivo: answer.json com sucesso!<BR>");
        }
        
        
        
        //FUNÇÃO PARA GERAR O SHA1
        public function SHA1($url_r) {
            $dados_json = $this->lerDados();
            if (isset($dados_json["decifrado"]) && $dados_json["decifrado"] !== "") {
                $dados_json["resumo_criptografico"] = sha1($dados_json["decifrado"]);
                $this->incluirDados($dados_json);
                return "Criptografia SHA1 gerada e adicionada com SUCESSO!";
            } else
                return "O Código ainda não foi Decifrado, decifre-o antes!";
        } 
        
        
        
        //FUNÇÃO PARA ENVIAR OS DADOS PARA A API
        public function enviaDadosAPI($url_r) {
            
            if (isset($_POST['enviar'])) {
                
                if (isset($_FILES['answer']['tmp_name'])) {
                    $ch = curl_init();
                    $cfile = new CURLFile($_FILES['answer']['tmp_name'], $_FILES['answer']['type'], $_FILES['answer']['name']);
                    $data = array("answer"=>$cfile);
                    
                    curl_setopt_array(
                        $ch,
                        array(
                            CURLOPT_URL         => $url_r,
                            CURLOPT_POST        => true,
                            CURLOPT_POSTFIELDS  => $data));
                    $response = curl_exec($ch);
                    
                    if ($response == true) {
                        echo "Filed Posted!";
                    } else
                        echo "Error: ".curl_error($ch);
                }

            }
        }    
        
        
        public function salvarDadosAPI($url_r) { 
            $this->result_rec = $this->recebeDadosAPI($url_r);
            if (isset($this->result_rec)) {
                // Tranforma o array $dados em JSON
                $dados_json = json_encode($this->result_rec);
                
                if (file_exists($this->filename)) {
                    //APAGA O ARQUIVO EXISTENTE
                    unlink($this->filename);
                }
                
                // Cria o arquivo cadastro.json, o parâmetro "a" indica que o arquivo será aberto para escrita
                $fp = fopen($this->filename, "a");
                // Escreve o conteúdo JSON no arquivo - PARA PULAR UMA LINHA ADC:.chr(13).chr(10)
                $escreve = fwrite($fp, $this->result_rec);
                // Fecha o arquivo
                fclose($fp);
                return ("Arquivo answer.json Gravado com sucesso!<BR>");    
            } else {
                return("Erro ao Buscar dados na API!<BR>");
                }
        }
        
    }
//$arquivo = file($this->filename);
//print_r($arquivo);
?>                    