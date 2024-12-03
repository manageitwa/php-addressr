<?php

namespace ManageItWA\PhpAddressr;

class Addressr
{
    protected ApiClient $client;

    public function __construct(ApiClient $client)
    {
        $this->client = $client;
    }
}