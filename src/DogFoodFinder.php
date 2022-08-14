<?php

namespace DanielMoraes\DogFoodFinder;

use GuzzleHttp\ClientInterface;
use Symfony\Component\DomCrawler\Crawler;

class DogFoodFinder
{
    private $httpClient;
    private $domCrawler;

    public function __construct(ClientInterface $httpClient, Crawler $domCrawler)
    {
        $this->httpClient = $httpClient;
        $this->domCrawler = $domCrawler;
    }

    public function execute(string $url, string $cssSelector): array
    {
        $response = $this->httpClient->request('GET', $url);
        $responseBody = $response->getBody();

        $this->domCrawler = new Crawler();
        $this->domCrawler->addHtmlContent($responseBody);

        $dogFoodsElements = $this->domCrawler->filter($cssSelector);

        $return = [];
        foreach ($dogFoodsElements as $dogFood) {
            $return[] = $dogFood->textContent;
        }

        return $return;
    }
}
