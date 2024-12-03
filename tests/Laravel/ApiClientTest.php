<?php

use Illuminate\Support\Facades\Http;
use ManageItWA\PhpAddressr\ApiClient\LaravelHttp;
use ManageItWA\PhpAddressr\Exception\ConnectionException;
use ManageItWA\PhpAddressr\Exception\NotFoundException;
use ManageItWA\PhpAddressr\Exception\QueryException;

test('can query for addresses', function () {
    Http::fake([
        'https://test.local/' => Http::response('[]', 200, ['Content-Type' => 'application/json', 'Link' => '</>; rel=self; title="API Root", </docs/>; rel=describedby; title="API Docs"; type=text/html, </api-docs>; rel=describedby; title="API Docs"; type=application/json']),
        'https://test.local/addresses?q=*' => Http::response('[{"sla":"UNIT 1, 140 ABERNETHY RD, BELMONT WA 6104","score":71.174286,"links":{"self":{"href":"/addresses/GAWA_161898132"}}},{"sla":"UNIT 2, 140 ABERNETHY RD, BELMONT WA 6104","score":71.174286,"links":{"self":{"href":"/addresses/GAWA_161898133"}}},{"sla":"UNIT 3, 140 ABERNETHY RD, BELMONT WA 6104","score":71.174286,"links":{"self":{"href":"/addresses/GAWA_161898135"}}},{"sla":"UNIT 4, 140 ABERNETHY RD, BELMONT WA 6104","score":71.174286,"links":{"self":{"href":"/addresses/GAWA_161898137"}}},{"sla":"140 ABERNETHY RD, BELMONT WA 6104","score":59.30484,"links":{"self":{"href":"/addresses/GAWA_162396399"}}}]', 200, ['Content-Type' => 'application/json']),
    ]);

    $apiClient = new LaravelHttp('https://test.local');

    $addresses = $apiClient->query('140 Abernethy Road Belmont');

    expect($addresses)->toBeArray();
    expect($addresses)->toHaveCount(5);
    expect($addresses[0]['sla'])->toBe('UNIT 1, 140 ABERNETHY RD, BELMONT WA 6104');
    expect($addresses[1]['sla'])->toBe('UNIT 2, 140 ABERNETHY RD, BELMONT WA 6104');
});

test('will throw connection exception if head check fails', function () {
    Http::fake([
        'https://test.local/' => Http::response('Internal server error', 500),
    ]);

    $apiClient = new LaravelHttp('https://test.local');

    $apiClient->query('140 Abernethy Road Belmont');
})->throws(ConnectionException::class);

test('will throw query exception if adresses endpoint fails', function () {
    Http::fake([
        'https://test.local/' => Http::response('[]', 200, ['Content-Type' => 'application/json', 'Link' => '</>; rel=self; title="API Root", </docs/>; rel=describedby; title="API Docs"; type=text/html, </api-docs>; rel=describedby; title="API Docs"; type=application/json']),
        'https://test.local/addresses?q=*' => Http::response('Internal server error', 500),
    ]);

    $apiClient = new LaravelHttp('https://test.local');

    $apiClient->query('140 Abernethy Road Belmont');
})->throws(QueryException::class);

test('will throw not found exception if adresses endpoint returns no results', function () {
    Http::fake([
        'https://test.local/' => Http::response('[]', 200, ['Content-Type' => 'application/json', 'Link' => '</>; rel=self; title="API Root", </docs/>; rel=describedby; title="API Docs"; type=text/html, </api-docs>; rel=describedby; title="API Docs"; type=application/json']),
        'https://test.local/addresses?q=*' => Http::response('[]', 200, ['Content-Type' => 'application/json']),
    ]);

    $apiClient = new LaravelHttp('https://test.local');

    $apiClient->query('140 Abernethy Road Belmont');
})->throws(NotFoundException::class);

test('can get a single address', )