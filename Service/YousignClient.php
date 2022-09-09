<?php

namespace YousignBundle\Service;

use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\AddPathPlugin;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Http\Message\Authentication\Bearer;
use IIYousign\Client;
use IIYousign\Normalizer\JaneObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Serializer;

class YousignClient extends Client
{

    public function __construct(string $baseUrl, string $apiKey, $httpClient = null, array $additionalPlugins = array(), array $additionalNormalizers = array())
    {
        if (null === $httpClient) {
            $httpClient = Psr18ClientDiscovery::find();
            $plugins = [];
            $uri = Psr17FactoryDiscovery::findUrlFactory()->createUri($baseUrl);
            $plugins[] = new AddHostPlugin($uri);
            $plugins[] = new AddPathPlugin($uri);
            $plugins[] =  new AuthenticationPlugin(new Bearer($apiKey));

            if (count($additionalPlugins) > 0) {
                $plugins = array_merge($plugins, $additionalPlugins);
            }
            $httpClient = new PluginClient($httpClient, $plugins);
        }
        $requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        $normalizers = array(new ArrayDenormalizer(), new JaneObjectNormalizer());
        if (count($additionalNormalizers) > 0) {
            $normalizers = array_merge($normalizers, $additionalNormalizers);
        }
        $serializer = new Serializer($normalizers, array(new JsonEncoder(new JsonEncode(), new JsonDecode(array('json_decode_associative' => true)))));
        parent::__construct($httpClient, $requestFactory, $serializer, $streamFactory);
    }
}