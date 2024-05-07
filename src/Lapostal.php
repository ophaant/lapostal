<?php

namespace Ophaant\Lapostal;

use Illuminate\Support\Facades\File;

class Lapostal
{
    private $json;
    public function __construct()
    {
        $this->json = $this->json;
    }

    public function loadPostal()
    {
        $jsonData = File::get(__DIR__ .'/../storage/postal_code/json/postal-code.json');
        $jsonString = preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $jsonData);
        $json = json_decode($jsonString, true);
        return $json;
    }
}
