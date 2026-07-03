<?php

namespace App\Services;

use Cloudinary\Api\Admin\AdminApi;
use Cloudinary\Api\ApiResponse;
use Cloudinary\Api\ApiUtils;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Illuminate\Support\Facades\Http;

class CloudinaryService
{
    const string DELIVERY_PUBLIC = 'public';
    const string DELIVERY_PRIVATE = 'private';
    const string DELIVERY_AUTHENTICATED = 'authenticated';

    private static function getParams(): array {
        return [
            'cloud_name' => config('filesystems.disks.cloudinary.cloud_name'),
            'api_key' => config('filesystems.disks.cloudinary.key'),
            'api_secret' => config('filesystems.disks.cloudinary.secret'),
            'secure' => true, // Ensures URLs are always HTTPS
        ];
    }

    private static function config(): void {
        if (Configuration::instance()->cloud->apiSecret) return;
        $params = self::getParams();
        Configuration::instance()->cloud->cloudName = $params['cloud_name'];
        Configuration::instance()->cloud->apiKey = $params['api_key'];
        Configuration::instance()->cloud->apiSecret = $params['api_secret'];
    }

    public function getDownloadPrivateUrl(string $publicId, $format = 'png', int $expiryInMinutes = 3): string {
        self::config();
        $expiresAt = now()->timestamp + ($expiryInMinutes * 60);
        return new UploadApi()->privateDownloadUrl($publicId, $format, ['expires_at' => $expiresAt]);
    }

    public function getDownloadPublicUrl(string $publicId): string {
        $cloudinary = new Cloudinary(self::getParams());
        return $cloudinary->image($publicId)->toUrl();
    }

    /**
     * @param  string  $folder  ''|'banana'|'abacate/banana'
     * @param  string  $publicId  nome do arquivo
     * @param  string  $deliveryType  private|authenticated
     */
    private function getSignatureData(string $folder, string $publicId, string $deliveryType): array {
        self::config();
        $params = [
            'folder' => $folder,
            'public_id' => $publicId,
            'timestamp' => now()->timestamp,
        ];
        if (in_array($deliveryType, ['authenticated', 'private'])) $params['type'] = $deliveryType;
        return array_merge([
            'signature' => ApiUtils::signParameters($params, Configuration::instance()->cloud->apiSecret),
        ], $params);
    }

    public function getPresignData(string $folder, string $publicId, string $deliveryType = self::DELIVERY_PUBLIC): array {
        $signatureData = $this->getSignatureData($folder, $publicId, $deliveryType);
        return [
            'cloudinaryData' => array_merge([
                'api_key' => Configuration::instance()->cloud->apiKey,
            ], $signatureData),
            'uploadUrl' => config('filesystems.disks.cloudinary.upload_url'),
        ];
    }

    public function signedUpload($filePath, $presignData): array {
        // $presignData = CloudinaryApi::getPresignData();
        // CloudinaryApi::uploadWithSignature('/var/www/aaa1.png', $presignData);
        $params = [
            'api_key' => $presignData['api_key'],
            'folder' => $presignData['folder'],
            'signature' => $presignData['signature'],
            'timestamp' => $presignData['timestamp'],
        ];

        $response = Http::post($presignData['uploadUrl'], array_merge(['file' => new \CURLFile($filePath)], $params));
        if (!$response->successful()) {
            // echo $response;
            throw new \Exception('Ocorreu um erro no serviço Cloudinary. Resposta inválida (' . $response ?: '[vazio]' . ')');
        }
        return $response->json();
    }

    public function delete(string $publicId): void {
        self::config();
        $result = new UploadApi()->destroy($publicId); // publicId = file_path
        unset($result);
    }

    public function bulkDelete(array $publicIds): void {
        self::config();
        $result = new AdminApi()->deleteAssets($publicIds);
        unset($result);
    }

    /*
    storage: array:20 [▼
        "asset_id" => "ff4bd25ff2a9bba724e7564a0450e3ab"
        "public_id" => "_detached/teste-1773278059"
        "version" => 1773278061
        "version_id" => "62d9ae606a49d8a6be3dbdde0c1b971d"
        "signature" => "d30a2ed8741985cdb0e5b3f630a5a4d2f70f3491"
        "width" => 272
        "height" => 264
        "format" => "jpg"
        "resource_type" => "image"
        "created_at" => "2026-03-12T01:14:21Z"
        "tags" => []
        "bytes" => 17570
        "type" => "upload"
        "etag" => "6d0b6d37017adaab208ef0ec3480cf6f"
        "placeholder" => false
        "url" => "http://res.cloudinary.com/dx3dzrb3b/image/upload/v1773278061/_detached/teste-1773278059.jpg"
        "secure_url" => "https://res.cloudinary.com/dx3dzrb3b/image/upload/v1773278061/_detached/teste-1773278059.jpg"
        "asset_folder" => "_detached"
        "display_name" => "teste-1773278059"
        "api_key" => "xpto"
      ]
     */
    public function base64StringUpload(string $capture, string $folder, string $publicId): ApiResponse {
        self::config();
        return new UploadApi()->upload($capture, [
            'public_id' => $publicId,
            'folder' => $folder,
        ]);
    }

    public function formatFromMimetype(string $value): string {
        return match ($value) {
            'image/png' => 'png',
            'image/jpg', 'image/jpeg' => 'jpg',
            'application/pdf' => 'pdf',
            default => 'unknown',
        };
    }
}
