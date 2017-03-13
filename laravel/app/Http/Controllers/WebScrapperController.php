<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Session;

class WebScrapperController extends BaseController
{
    private $client;
    public $url;
    public $crawler;
    public $filters;
    public $content = array();

    public function __construct(Client $client){

      $this->client = $client;

    }

    public function getIndex(){

      $this->url = 'http://testing-ground.scraping.pro/';
      $this->setScrapeUrl( $this->url );

      $this->filters = [
        'title' => '.caseblock',
        'author' => '.casedescr'
      ];

      return view('scraper')
        ->with('contents', $this->getContents());
    }

    public function setScrapeUrl($url = NULL, $method = 'GET'){
      $this->crawler = $this->client->request($method, $url);
      return $this->crawler;
    }

    public function getContents(){
      return $this->content = $this->startScraper();
    }

    private function startScraper(){

      $countContent = $this->crawler->filter('.caseblock')->count();

      if ($countContent){

        $this->content = $this->crawler->filter('.posts--list-large li')->each(function (Crawler $node, $i){
          return [
            'title' =>  $node->filter($this->filters['title'])->text(),
            'url' =>  $this->url.$node->filter($this->filters['title'])->attr('href'),
            'author'  =>  $node->filter($this->filters['author'])->text()
          ];
        });
      }
      return $this->content;
    }



}
