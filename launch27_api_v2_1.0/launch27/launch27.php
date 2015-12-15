<?php

namespace launch27;

class launch27
{

  // @var string The API key to be used for requests.
  public static $apiKey;

  // @var string The base URL for the API.
  public static $apiBase;
  
  // @var string|null The version of the API to use for requests. (null will use v1)
  public static $apiVersion=2;

  const VERSION = '0.2';


  /**
   * Sets the API key to be used for requests.
   *
   * @param string $apiKey
   */
  public static function setApiKey($apiKey)
  {
    self::$apiKey=$apiKey;
  }


  /**
   * Sets the API key to be used for requests.
   *
   * @param string $apiKey
   */
  public static function setApiBaseURL($apiBase)
  {
    self::$apiBase=$apiBase;
  }


  /**
   * @param string $apiVersion The API version to use for requests.
   */
  public static function setApiVersion($apiVersion)
  {
    self::$apiVersion=$apiVersion;
  }

}
