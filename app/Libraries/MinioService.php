<?php

namespace App\Libraries;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Config\Minio;

class MinioService
{
    protected $client;
    protected $config;
    protected $bucket;

    public function __construct()
    {
        $this->config = new Minio();
        $this->bucket = $this->config->defaultBucket;

        $this->client = new S3Client([
            'version' => $this->config->version,
            'region' => $this->config->region,
            'endpoint' => 'http://' . $this->config->endpoint . ':' . $this->config->port,
            'use_path_style_endpoint' => true,
            'credentials' => [
                'key' => $this->config->accessKey,
                'secret' => $this->config->secretKey,
            ],
        ]);

        // Ensure bucket exists
        $this->ensureBucketExists();
    }

    /**
     * Ensure the bucket exists, create if it doesn't
     */
    protected function ensureBucketExists(): void
    {
        try {
            if (!$this->client->doesBucketExist($this->bucket)) {
                $this->client->createBucket([
                    'Bucket' => $this->bucket,
                ]);
                log_message('info', "MinIO bucket '{$this->bucket}' created successfully");
            }
        } catch (AwsException $e) {
            log_message('error', 'MinIO bucket check/create failed: ' . $e->getMessage());
        }
    }

    /**
     * Upload a file to MinIO
     *
     * @param string $filePath Local file path or file content
     * @param string $objectKey The key/path in MinIO (e.g., 'schools/logos/uuid.jpg')
     * @param string|null $contentType MIME type of the file
     * @param bool $isFileContent Whether $filePath is file content or path
     * @return array ['success' => bool, 'url' => string|null, 'error' => string|null]
     */
    public function uploadFile(string $filePath, string $objectKey, ?string $contentType = null, bool $isFileContent = false): array
    {
        try {
            $params = [
                'Bucket' => $this->bucket,
                'Key' => $objectKey,
                'Body' => $isFileContent ? $filePath : fopen($filePath, 'rb'),
            ];

            if ($contentType) {
                $params['ContentType'] = $contentType;
            }

            $result = $this->client->putObject($params);

            // Get the object URL
            $url = $this->getObjectUrl($objectKey);

            log_message('info', "File uploaded to MinIO: {$objectKey}");

            return [
                'success' => true,
                'url' => $url,
                'key' => $objectKey,
                'error' => null,
            ];
        } catch (AwsException $e) {
            log_message('error', 'MinIO upload failed: ' . $e->getMessage());
            return [
                'success' => false,
                'url' => null,
                'key' => null,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Download a file from MinIO
     *
     * @param string $objectKey The key/path in MinIO
     * @return array ['success' => bool, 'content' => string|null, 'error' => string|null]
     */
    public function downloadFile(string $objectKey): array
    {
        try {
            $result = $this->client->getObject([
                'Bucket' => $this->bucket,
                'Key' => $objectKey,
            ]);

            $content = (string) $result['Body'];

            return [
                'success' => true,
                'content' => $content,
                'contentType' => $result['ContentType'] ?? null,
                'error' => null,
            ];
        } catch (AwsException $e) {
            log_message('error', 'MinIO download failed: ' . $e->getMessage());
            return [
                'success' => false,
                'content' => null,
                'contentType' => null,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Delete a file from MinIO
     *
     * @param string $objectKey The key/path in MinIO
     * @return array ['success' => bool, 'error' => string|null]
     */
    public function deleteFile(string $objectKey): array
    {
        try {
            $this->client->deleteObject([
                'Bucket' => $this->bucket,
                'Key' => $objectKey,
            ]);

            log_message('info', "File deleted from MinIO: {$objectKey}");

            return [
                'success' => true,
                'error' => null,
            ];
        } catch (AwsException $e) {
            log_message('error', 'MinIO delete failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check if a file exists in MinIO
     *
     * @param string $objectKey The key/path in MinIO
     * @return bool
     */
    public function fileExists(string $objectKey): bool
    {
        try {
            return $this->client->doesObjectExist($this->bucket, $objectKey);
        } catch (AwsException $e) {
            log_message('error', 'MinIO file existence check failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get the URL of an object in MinIO
     *
     * @param string $objectKey The key/path in MinIO
     * @return string
     */
    public function getObjectUrl(string $objectKey): string
    {
        return sprintf(
            '%s://%s:%d/%s/%s',
            $this->config->useSSL ? 'https' : 'http',
            $this->config->endpoint,
            $this->config->port,
            $this->bucket,
            $objectKey
        );
    }

    /**
     * Generate a presigned URL for temporary access
     *
     * @param string $objectKey The key/path in MinIO
     * @param int $expirationMinutes Expiration time in minutes (default: 60)
     * @return array ['success' => bool, 'url' => string|null, 'error' => string|null]
     */
    public function getPresignedUrl(string $objectKey, int $expirationMinutes = 60): array
    {
        try {
            $cmd = $this->client->getCommand('GetObject', [
                'Bucket' => $this->bucket,
                'Key' => $objectKey,
            ]);

            $request = $this->client->createPresignedRequest($cmd, "+{$expirationMinutes} minutes");
            $url = (string) $request->getUri();

            return [
                'success' => true,
                'url' => $url,
                'error' => null,
            ];
        } catch (AwsException $e) {
            log_message('error', 'MinIO presigned URL generation failed: ' . $e->getMessage());
            return [
                'success' => false,
                'url' => null,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * List all objects in a specific path/prefix
     *
     * @param string $prefix Path prefix (e.g., 'schools/logos/')
     * @return array ['success' => bool, 'objects' => array|null, 'error' => string|null]
     */
    public function listObjects(string $prefix = ''): array
    {
        try {
            $result = $this->client->listObjects([
                'Bucket' => $this->bucket,
                'Prefix' => $prefix,
            ]);

            $objects = [];
            if (isset($result['Contents'])) {
                foreach ($result['Contents'] as $object) {
                    $objects[] = [
                        'key' => $object['Key'],
                        'size' => $object['Size'],
                        'lastModified' => $object['LastModified'],
                    ];
                }
            }

            return [
                'success' => true,
                'objects' => $objects,
                'error' => null,
            ];
        } catch (AwsException $e) {
            log_message('error', 'MinIO list objects failed: ' . $e->getMessage());
            return [
                'success' => false,
                'objects' => null,
                'error' => $e->getMessage(),
            ];
        }
    }
}
