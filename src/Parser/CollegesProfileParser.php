<?php

namespace App\Parser;

use Exception;
use Symfony\Component\DomCrawler\Crawler;

class CollegesProfileParser
{
    public function parse(string $url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $html = curl_exec($ch);
        while (!$html) {
            $html = curl_exec($ch);
        }
        return $this->extractFromHtml($html);
    }

    private function extractFromHtml($html)
    {
        $crawler = new Crawler($html);
        $name = $crawler->filter('h1 > span')->first()->text();
        $addressContainer = $websiteUrl = $crawler->filterXPath('//div[contains(@itemprop, "address")]');
        $websiteUrl = '';
        $address = '';
        if ($addressContainer->count() != 0) {
            $websiteUrlContainer = $addressContainer->filter('a');
            if ($websiteUrlContainer->count() != 0) {
                $websiteUrl = $websiteUrlContainer->first()->attr('href');
            }
            try {
                $street = trim($crawler->filterXPath('//span[contains(@itemprop, "streetAddress")]')->first()->text());
                $street = preg_replace('/\s+/', ' ', $street);
                $city = trim($crawler->filterXPath('//span[contains(@itemprop, "addressLocality")]')->first()->text());
                $city = preg_replace('/\s+/', ' ', $city);
                $state = trim($crawler->filterXPath('//span[contains(@itemprop, "addressRegion")]')->first()->text());
                $state = preg_replace('/\s+/', ' ', $state);
                $postalCode = trim($crawler->filterXPath('//span[contains(@itemprop, "postalCode")]')->first()->text());
                $address = "$street | $city, $state | $postalCode";
            } catch (Exception $e) {
            }
        }
        $phone = null;
        $collegeContacts = $crawler
            ->filterXPath('//div[contains(@class, "school-contacts")]')
            ->filterXPath('//div[contains(@class, "col-sm-9")]')
            ->filterXPath('//div[contains(@class, "row")]');
        for ($i = 0; $i < $collegeContacts->count(); $i++) {
            $title = $collegeContacts->eq($i)
                ->filterXPath('//div[contains(@class, "col-xs-6 bold")]')
                ->first()
                ->text();
            if (strcasecmp($title, 'phone') == 0) {
                $phone = $collegeContacts->eq($i)
                    ->filterXPath('//div[contains(@class, "col-xs-6")]')
                    ->last()
                    ->text();
            }
        }

        return [
            "name" => $name,
            "address" => $address,
            "website_url" => $websiteUrl,
            "phone" => $phone,
        ];
    }
}
