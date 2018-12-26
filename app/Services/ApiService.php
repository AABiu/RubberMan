<?php

namespace App\Services;

use GuzzleHttp\Psr7\Uri;
use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Http\Request;
use Log;
use Mockery\Exception;


class ApiService
{

    private $crawlService;


    public function __construct()
    {
        $this->crawlService = new CrawlService();
    }

    public function getQXCData($url)
    {
        try {
            $data = $this->crawlService->get($url, ['qxc' => true]);
            return $data;
        } catch (\Exception $exception) {
            return [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
            ];
        }
    }

    public function getLngLatByAddress($address)
    {
        try {
            $url = 'http://api.map.baidu.com/geocoder/v2/?output=json&ak=' . $this->baidu_ak . '&address=' . $address;
            $data = $this->crawlService->get($url, []);
            return $data;
        } catch (ValidatorException $exception) {
            return [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
            ];
        }
    }

}