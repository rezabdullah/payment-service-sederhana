<?php

namespace Rezabdullah\Helper;

class Http
{
    public static function getBasicToken(): array
    {
        $authorization = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '';
        $pattern = '/(?<=^Basic\s).*/';
        
        preg_match($pattern, $authorization, $getAuthorization, PREG_OFFSET_CAPTURE, 0);

        if(isset($getAuthorization[0][0])) {
            $getBasicToken = $getAuthorization[0][0];
            
            if(strpos(base64_decode($getBasicToken), ':') !== false) {
                $getBasicTokenArray = explode(':', base64_decode($getBasicToken));
            }
        }

        return [
            'username' => isset($getBasicTokenArray[0]) ? $getBasicTokenArray[0] : null,
            'password' => isset($getBasicTokenArray[1]) ? $getBasicTokenArray[1] : null
        ];
    }

    public static function getBearerToken(): string
    {
        $authorization = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '';
        $pattern = '/(?<=^Bearer\s).*/';
        
        preg_match($pattern, $authorization, $getAuthorization, PREG_OFFSET_CAPTURE, 0);

        return isset($getAuthorization[0][0]) ? $getAuthorization[0][0] : $authorization;
    }

    public static function filterRequestBody(): void
    {
        $get_php_input = json_decode(file_get_contents("php://input"), true);

        if(!empty($_POST)) {
            $_SERVER['X_HTTP_REQUEST_BODY'] = $_POST;
        }
        else {
            $_SERVER['X_HTTP_REQUEST_BODY'] = is_array($get_php_input) ? $get_php_input : [];
        }
    }
    
    public static function responseOk(?array $data = []): string
    {
        return self::responseJson($data, 200);
    }

    public static function responseError($errors = null, int $httpCode = 500): string
    {
        return self::responseJson([
            'errors' => is_array($errors) ? $errors : ($errors ? [$errors] : []),
        ], $httpCode);
    }

    public static function responseJson(array $params = [], int $httpCode = 200): string
    {
        header("Content-Type: application/json");
        http_response_code($httpCode);
        return json_encode($params);
    }
}