<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Minio extends BaseConfig
{
    /**
     * MinIO Server URL
     */
    public string $url = 'http://20.163.1.176:9001';

    /**
     * MinIO API Endpoint (without /api/v1/...)
     */
    public string $endpoint = '20.163.1.176';

    /**
     * MinIO Port
     */
    public int $port = 9000;

    /**
     * Use SSL
     */
    public bool $useSSL = false;

    /**
     * Access Key
     */
    public string $accessKey = '91Z059h0qV3GV3LNN2E3';

    /**
     * Secret Key
     */
    public string $secretKey = 'JcEXRmlb4IgObq6RZL6mJfZTBfwrcg4bzfAbSOP4';

    /**
     * Region
     */
    public string $region = 'us-east-1';

    /**
     * API Version
     */
    public string $version = 'latest';

    /**
     * Default bucket name for school data
     */
    public string $defaultBucket = 'data';
}
