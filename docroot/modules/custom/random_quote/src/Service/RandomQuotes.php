<?php

namespace Drupal\random_quote\Service;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class RandomQuotes {

  /**
   * The HTTP client to fetch the feed data with.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  public function __construct(ClientInterface $http_client) {
    $this->httpClient = $http_client;
  }

  public function randomQuote(): string {
    try {
      $response = $this->httpClient->request(
        'GET',
        'https://api.quotable.io/random',
      );
    }
    catch (GuzzleException $e) {
      watchdog_exception('random_quote', $e);

      return '';
    }

    $json_response = $response->getBody()->getContents();
    $reponse = \GuzzleHttp\json_decode($json_response, TRUE);

    return $reponse['content'] ?? '';
  }

}
