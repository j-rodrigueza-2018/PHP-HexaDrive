<?php

declare(strict_types=1);

namespace JRA\HexaDrive\Domain;

/**
 * Defines the contract for any cloud service factory (Google Drive, S3, Dropbox, etc.)
 */
interface CloudServiceFactoryInterface
{
    /**
     * Creates and returns an instance of the cloud service.
     *
     * @return object The cloud service instance (e.g., Google Drive, S3, Dropbox)
     */
    public function create(): object;
}
