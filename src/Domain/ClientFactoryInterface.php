<?php

declare(strict_types=1);

namespace JRA\HexaDrive\Domain;

/**
 * Defines the contract for any service client factory (Google Drive, S3, etc.)
*/
interface ClientFactoryInterface
{
    /**
     * Creates and returns the client instance.
     *
     * @return object The client instance (Google Drive, S3, etc.)
    */
    public static function createClient(): object;
}