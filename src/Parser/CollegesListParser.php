<?php

namespace App\Parser;

use Symfony\Component\DomCrawler\Crawler;

class CollegesListParser
{
    public function parse(array $urls = [])
    {
        $result = [];
        foreach ($urls as $url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $html = curl_exec($ch);
            while (!$html) {
                $html = curl_exec($ch);
            }
            $result = array_merge($result, $this->extractFromHtml($html));
        }
        return $result;
    }

    private function extractFromHtml($html)
    {
        $crawler = new Crawler($html);

        return $crawler->filterXPath('//div[contains(@class, "row vertical-padding")]')
            ->each(
                function (Crawler $node, $i) {
                    $name = $node->filter('h2')->first()->text();
                    $profileRelativeLink = $node->filter('h2')->filter('a')->first()->attr('href');
                    $locationContainer = $node->filterXPath('//div[contains(@class, "location")]');
                    $city = '';
                    $state = '';
                    if ($locationContainer->count() != 0) {
                        $location = $locationContainer->first()->text();
                        [$city, $state] = explode(', ', $location);
                    }
                    $imgNodes = $node->filter('img');
                    $imgUrl = null;
                    if ($imgNodes->count() > 0) {
                        $imgUrl = $imgNodes->first()->attr('src');
                    }
                    return [
                        'name' => $name,
                        'city' => $city,
                        'state' => $state,
                        'img_url' => $imgUrl,
                        'profile_relative_link' => $profileRelativeLink
                    ];
                }
            );
    }
}
