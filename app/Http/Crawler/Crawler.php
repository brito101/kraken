<?php

namespace App\Http\Crawler;

use App\Helpers\Command;
use DOMDocument;
use GuzzleHttp\Client;

class Crawler
{

    private static $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:125.0) Gecko/20100101 Firefox/125.0';

    /**
     * @param string $url
     * @return 
     */

    public static function crawler(string $url)
    {
        $list = [];
        $headers = null;

        $httpClient = new Client(
            [
                'headers' => [
                    'User-Agent' => self::$userAgent,
                ],
                'allow_redirects' => true,
                'connect_timeout' => 5
            ]
        );

        try {
            $response = $httpClient->get($url);

            $headers =  $response->getHeaders();

            $htmlString = (string) $response->getBody();
            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->loadHTML('<?xml encoding="utf-8" ?>' . $htmlString, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);
            $links = $dom->getElementsByTagName('a');
            $title = $dom->getElementsByTagName('title');
            $title = ($title[0]->nodeValue);
            foreach ($links as $k => $v) {
                $list[$k]['page'] = $title;
                $list[$k]['url'] = $v->getAttribute('href');
                $list[$k]['title'] = $v->getAttribute('title');
            }

            return ['links' => $list, 'headers' => $headers];
        } catch (\Exception $e) {
            return ['links' => [], 'headers' => []];
        }
    }
}
