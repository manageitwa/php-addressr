<?php

namespace ManageItWA\PhpAddressr\ApiClient;

use Illuminate\Support\Facades\Http;
use ManageItWA\PhpAddressr\ApiClient;
use ManageItWA\PhpAddressr\Exception\AddressException;
use ManageItWA\PhpAddressr\Exception\ConnectionException;
use ManageItWA\PhpAddressr\Exception\NotFoundException;
use ManageItWA\PhpAddressr\Exception\QueryException;

class LaravelHttp implements ApiClient
{
    protected bool $isConnected = false;

    protected string $endpoint = '';

    public function __construct(string $endpoint = '')
    {
        $this->endpoint = $endpoint;
    }

    public function query(string $query): array
    {
        $this->checkConnection();

        $response = Http::get($this->uri('addresses'), [
            'q' => $query,
        ]);

        if (!$response->ok()) {
            /** @var string */
            $message = $response->json('message', 'An error occurred while querying Addressr.');
            throw new QueryException((string) $message);
        }

        /** @var array<int, mixed> */
        $data = $response->json();

        if (!count($data)) {
            throw new NotFoundException('No addresses found for the query.');
        }

        return $data;
    }

    public function address(string $pId): array
    {
        $this->checkConnection();

        $response = Http::get($this->uri("addresses/{$pId}"));

        if ($response->getStatusCode() === 404) {
            throw new NotFoundException('No address found with the given ID.');
        }

        if (!$response->ok()) {
            /** @var string */
            $message = $response->json('message', 'An error occurred while fetching the address.');
            throw new AddressException((string) $message);
        }

        /** @var array<string, mixed> */
        return $response->json();
    }

    protected function checkConnection(): void
    {
        if (empty($this->endpoint)) {
            throw new ConnectionException('Addressr endpoint is not set. Please set "services.addressr.endpoint" in your "services.php" config file.');
        }

        if ($this->isConnected) {
            return;
        }

        $response = Http::head($this->uri());

        if (!$response->ok()) {
            throw new ConnectionException('Addressr endpoint is not reachable.');
        }

        if ($response->header('Content-Type') !== 'application/json') {
            throw new ConnectionException('Addressr endpoint is not returning JSON.');
        }

        if (!$response->hasHeader('link')) {
            throw new ConnectionException('Addressr endpoint does not appear to be an Addressr instance.');
        }

        $this->isConnected = true;
    }

    protected function uri(string $request = ''): string
    {
        return rtrim($this->endpoint, '/') . '/' . ltrim($request, '/');
    }
}