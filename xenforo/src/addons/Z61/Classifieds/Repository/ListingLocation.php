<?php

namespace Z61\Classifieds\Repository;

use XF\Mvc\Entity\Repository;

class ListingLocation extends Repository
{
    public function getLocationDataForAddress($address, $apikey = '')
    {
        if (empty($apiKey))
        {
            $apiKey = \XF::options()->z61ClassifiedsGoogleApi;
        }
        $client = $this->app()->http()->createClient();
        $urlEncodedAddr = urlencode($address);
        $apiUrl = 'https://maps.google.com/maps/api/geocode/json?address='.$urlEncodedAddr.'&key='.$apiKey;

        $response = $client->get($apiUrl, [
           'headers' => ['Accept' => 'application/json']
        ]);

        $response = $response->getBody()->getContents();

        if ($response)
        {
            $response = json_decode($response);
            $status = $response->status;

            return [$response, $status];
        }

        return null;
    }
}