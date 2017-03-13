<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebScrapperController extends BaseController
{
    private $client;
    public $url;
    public $crawler;
    public $filters;
    public $content = array();

    public function __construct(Client $client){

      $this->cliente = $cliente;

    }

    public function getIndex(){

      $this->url = 'http://code.tutsplus.com';
      $this->setScrapeUrl( $this->url );

      $this->filters = [
        'title' => '.posts__post-title',
        'author' => '.posts__post-author-link'
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

    private fuction startScraper(){

      $countContent = $this->crawler->filter('.posts__post-title')->count();

      if ($countContent){

        $this->content = $this->crawler->filter('.posts--list-large li')->each(function Crawler $node, $i)
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
