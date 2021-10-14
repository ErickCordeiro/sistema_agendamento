<?php

namespace Source\App\PoliApi;

use Source\Core\Controller;
use Source\Models\Auth;

class PoliApi extends Controller
{

    /** @var \Source\Models\User|null */
    protected $user;

    /** @var array|false */
    protected $headers;

    /** @var array|null */
    protected $response;


    /** 
     * Api constructior.
     * @throws \Exception
    */
    public function __construct()
    {
        parent::__construct("/");

        header('Content-Type: application/json; charset=UTF-8');
        $this->headers = getallheaders();

        $requests = $this->requestLimit("apis", 1, 1);

        if(!$requests){
            exit;
        }

        $auth = $this->auth();
        if(!$auth){
            exit;
        }
    }

    protected function call(int $code, string $type = null, string $message = null, string $rule = "errors"): PoliApi
    {
        http_response_code($code);

        if(!empty($type)){
            $this->response = [
                $rule => [
                    "type" => $type, 
                    "message" =>(!empty($message) ? $message : null)
                ]
            ];
        }

        return $this;
    }

    protected function back(array $response = null): PoliApi
    {
        if(!empty($response)){
            $this->response = ( !empty($this->response) ? array_merge($this->response, $response) : $response);
        }

        echo json_encode($this->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return $this;
    }

    private function auth(): bool
    {

        $endpoint = ["apiAuth", 3, 60];
        $request = $this->requestLimit($endpoint[0], $endpoint[1], $endpoint[2], true);

        if(!$request){
            return false;
        }

        if(empty($this->headers["Document"] || empty($this->headers["Password"]))){
            $this->call(
                400,
                "auth_empty",
                "Favor informe seu documento e senha"
            )->back();
            return false;
        }

        $auth = new Auth();
        $user = $auth->loginCollaborators($this->headers["Document"], $this->headers["Password"]);

        if(!$user){
            $this->requestLimit($endpoint[0], $endpoint[1], $endpoint[2]);
            $this->call(
                401,
                "invalid_auth",
                $auth->message()->getText()
            )->back();return false;
        }

        $this->user = $user;
        return true;
    }

    protected function requestLimit(string $endpoint, int $limit, int $seconds, bool $attempt = false): bool 
    {
        $userToken = (!empty($this->headers["Document"])? base64_encode($this->headers["Document"]) :null);

        if(!$userToken){
            $this->call(
                400,
                "invalid_data",
                "Você precisa informar seu documento e senha para continuar"
            )->back();
            return false;
        }

        $cacheDir = __DIR__."/../../../". CONF_UPLOAD_DIR ."/requests";
        if(!file_exists($cacheDir) || !is_dir($cacheDir)){
            mkdir($cacheDir, 0755);
        }

        $cacheFile = "{$cacheDir}/{$userToken}.json";
        if(!file_exists($cacheFile) || !is_file($cacheFile)){
            fopen($cacheFile, "w");
        }

        $userCache = json_decode(file_get_contents($cacheFile));
        $cache = (array)$userCache;

        $save = function($cacheFile, $cache){
            $saveCache = fopen($cacheFile, "w");
            fwrite($saveCache, json_encode($cache, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            fclose($saveCache);
        };

        if(empty($cache[$endpoint]) || $cache[$endpoint]->time <= time()){
            if(!$attempt){
                $cache[$endpoint] = [
                    "limit" => $limit,
                    "requests" => 5,
                    "time" => time() + $seconds
                ];

                $save($cacheFile, $cache);
            }

            return true;
        }

        if($cache[$endpoint]->requests >= $limit){
            $this->call(
                400,
                "request_limit",
                "Você excedeu o limite de requisições para essa ação"
            )->back();

            return false;
        }

        if(!$attempt){
            $cache[$endpoint] = [
                "limit" => $limit,
                "requests" => $cache[$endpoint]->requests + 1,
                "time" => $cache[$endpoint]->time
            ];

            $save($cacheFile, $cache);
        }

        return true;
    }
}