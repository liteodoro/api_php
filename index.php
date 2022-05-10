<?php

use LDAP\Result;

include_once "./config/config.php";
include_once "./config/jwt.php";

include_once "./app/router/router.php";
require_once "./app/services/DAO.php";
require_once "./app/models/usuario.php";
require_once "./app/controller/usuarioController.php";
require_once "./app/models/produto.php";
require_once "./app/controller/produtoController.php";

//phpinfo();

try {
    //Variavel para os resultados
    $result = null;
    $httpCod = null;
    $auth = null;

    $method = isset($_SERVER["REQUEST_METHOD"]) ? $_SERVER["REQUEST_METHOD"] : null;

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    if ($method == "OPTIONS") {
        //Cabeçalho comum da aplicação    
        header("Access-Control-Allow-Methods: POST, GET, PUT, OPTIONS");
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Max-Age: 3600"); //1hora == 3600 seg;
        header("Access-Control-Allow-Credentials: true");
    }

    if ($method != null && $method != "OPTIONS") {

        $authRouterFree = guardian($_SERVER["REQUEST_URI"]);

        //Validação de rotas
        $url = explode("/", $_SERVER["REQUEST_URI"]);
        array_shift($url);
        array_shift($url);

        $auth = authentic($method, $url);
        //$auth = "userAll";

        //Verifica validação do usuario
        if ($auth != null && $url[count($url) - 1] == "logon") {
            $httpCod = 200;
            $result = $auth;
        } else if ($auth != null || $authRouterFree) {
            $httpCod = 200;
            $result = routes($method, $url, $auth);
        } else {
            $httpCod = 401;
            $result = "Usuario sem autorização";
        }

        //A resposta se não existir errose se existirem dados
        //header('Content-Type: application/json;charset=utf-8');
        http_response_code($httpCod);
        echo json_encode(array("result" => $result));
    } else {
        //throw new Exception();
    };
} catch (Exception $e) {
    $code = 404;
    $erro = "Pagina não encontrada!";

    if ($e->getMessage() != null) {
        $code = $httpCod;
        $erro = $e->getMessage();
    }
    http_response_code($code);
    echo json_encode(array("result" => $erro));
}


function guardian($urlPadrao)
{
    $urlPadrao = $_SERVER["REQUEST_URI"];
    $routesFree = [
        "/api/usuario/logon",
        "/api/usuario/add",
        "/api/produto/list"
    ];
    //verificar se a rota é uma daquelas do array. Se for retorna o numero. Ele retorna o numero do index do array senão retorna false FALSE;
    return array_search($urlPadrao, $routesFree);
}


function routes($method, $url, $auth)
{
    $result = routeUser($method, $url, $auth);
    if ($result) return $result;
    $result = routeProduto($method, $url, $auth);
    if ($result) return $result;
    throw new Exception();
}


function authentic($method, $url)
{
    $result = null;

    //Cria um session
    if (!session_start()) {
        session_start();
    };

    //Autenticação
    $token = isset($_SERVER["HTTP_AUTHORIZATION"]) ? $_SERVER["HTTP_AUTHORIZATION"] : null;
    $auth = isset($_SESSION[$token]) ? $_SESSION[$token] : null;

    //Rotas Não Autenticadas
    if ($method == "POST" && $auth == null) {
        switch ($url[0]) {
            case "usuario":
                switch ($url[1]) {
                    case 'logon':
                        $dadosUser = json_decode(file_get_contents('php://input')); //tranformar JSON do body em Objetos
                        $userController = new usuarioController;
                        $result = $userController->logon($dadosUser->usuario, $dadosUser->senha);
                        $token = isset($result->token) ? $result->token : $token;
                        break;
                    default:
                        $result = null;
                        break;
                }
                break;

            default:
                $result = null;
                break;
        }
    }
    $auth = $token != null ? validJWT($token) : null;

    if ($token != null && $auth != null) {
        $_SESSION[$token] = json_decode($auth);
        $result = isset($result) ? $result : json_decode($auth);
    }

    return $result;
}


function uploadfotos($local, $nameFiles = null)
{
    $files = $_FILES;
    if ($files) {
        $local = new DirectoryIterator("./") . $local;
        if (!is_dir($local)) {
            mkdir($local, 0755, true);
        }
        $resultName = null;
        foreach ($files as $file) {
            $timeStamp =  (new DateTime("now"))->getTimestamp(); //use o timestamp: é o tempo em segundos
            //$nameFileMD5 = md5($files['file']['name'] . $files['file']['size'] . $files['file']['type']);
            $nameFileMD5 = md5($file['name'] . $timeStamp);

            $nameFiles = isset($nameFiles) ? $nameFiles : $nameFileMD5;
            $ext = explode('.', $file['name']);

            $newNameFile = $nameFiles . "." . $ext[count($ext) - 1];

            $destino = $local . '/' .  $newNameFile;

            $arquivo_tmp = $file['tmp_name'];

            if (move_uploaded_file($arquivo_tmp, $destino)) {
                $resultName .= $newNameFile;
            };
        }
    }
    return $resultName;
}