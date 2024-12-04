<?php

namespace ManageItWA\PhpAddressr;

class Addressr
{
    protected ApiClient $client;

    public function __construct(ApiClient $client)
    {
        $this->client = $client;
    }

    public function search(string $query): array
    {
        $results = $this->client->query($query);

        return $results;
    }
}