<?php

namespace App\Parser;

use Symfony\Component\DomCrawler\Crawler;

class CollegesListPageCountParser
{
    public function parse($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        $html = curl_exec($ch);
        return $this->extractFromHtml($html);
    }

    private function extractFromHtml($html)
    {
        $crawler = new Crawler($html);
        $pageText = $crawler->filterXPath('//div[contains(@class,"col-sm-9 desktop-74p-width")]')
                            ->filterXPath('//div[contains(@style, "text-align: center;")]')
                            ->filter('div')->first()->text();
        return (int) explode(' ', $pageText)[3];
    }
}
