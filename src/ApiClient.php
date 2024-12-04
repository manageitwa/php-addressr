<?php

namespace ManageItWA\PhpAddressr;

/**
 * API client interface.
 * 
 * The Addressr API has two main endpoints - one for querying addresses, providing a list of matches for a given
 * query, and one for fetching the details of a single address by an ID.
 * 
 * Thus, our API client must be able to execute both of these endpoints and return the results as a JSON-decoded
 * array.
 * 
 * @package ManageItWA\PhpAddressr
 */
interface ApiClient
{
    /**
     * Address query.
     * 
     * This method should accept a string-based query for an address, and return the JSON-decoded results array from
     * Addressr. Note that, by default, the Addressr API requires the query to be at least 3 characters in length.
     * 
     * @param string $query The query to search for.
     * @return array<int, mixed> The JSON-decoded results array from Addressr.
     * @throws \ManageItWA\PhpAddressr\Exception\QueryException If an error occurs while querying Addressr.
     * @throws \ManageItWA\PhpAddressr\Exception\ConnectionException If an error occurs while connecting to Addressr.
     * @throws \ManageItWA\PhpAddressr\Exception\NotFoundException If no addresses are found for the query.
     */
    public function query(string $query): array;

    /**
     * Gets details of a singular address, by ID.
     * 
     * This method should expect a property ID (usually in the format of GA{STATE_?}{ID[0-9]}) and return the JSON-decoded 
     * address array from Addressr.
     * 
     * @param string $pId Property ID
     * @return array<string, mixed> The JSON-decoded address array from Addressr.
     * @throws \ManageItWA\PhpAddressr\Exception\AddressException If an error occurs while fetching the address data or if the address data is invalid.
     * @throws \ManageItWA\PhpAddressr\Exception\ConnectionException If an error occurs while connecting to Addressr.
     * @throws \ManageItWA\PhpAddressr\Exception\NotFoundException If no address is found for the given property ID.
     */
    public function address(string $pId): array;
}