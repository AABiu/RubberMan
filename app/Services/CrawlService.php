<?php

namespace App\Services;

use GuzzleHttp\Client;

class CrawlService
{

    public function get($url, $options = [])
    {
        return $this->crawl($url, 'GET', $options);
    }

    public function post($url, $options = [])
    {
        $options = ['form_params' => $options];
        return $this->crawl($url, 'POST', $options);
    }

    public function crawl($url, $method = 'GET', $options = [])
    {
        try {
            $client = new Client();
            $res = $client->request($method, $url, $options);
            $body = (string)$res->getBody();
            if (!isset($options['qxc'])){
                $data = json_decode($body, true);
            }else{
                $data = $body;
            }
            return $data;
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
    }
}