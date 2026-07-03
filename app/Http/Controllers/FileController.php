<?php

namespace App\Http\Controllers;

use App\Custom\Cast;
use App\Custom\FileHelper;
use App\Enums\YiiEnum;
use App\Http\Resources\JsonResponseResource;
use App\Models\File;
use App\Services\CloudinaryService;
use Illuminate\Http\JsonResponse;

class FileController
{
    public function apiCloudinaryPresign(CloudinaryService $cloudinaryService): JsonResponse {
        request()->validate([
            'cloudinaryFolder' => 'required|string',
            'fileBytes' => 'required|int',
            'fileMimeType' => 'required|string',
            'fileName' => 'required|string',
            'imageHeight' => 'int',
            'imageWidth' => 'int',
            'storageDeliveryId' => 'required|int',
        ]);

        $fileName = FileHelper::resolveUploadName(request('fileName'));
        $storageServiceFolder = request('cloudinaryFolder');
        $storageServicePublicId = FileHelper::resolveFileName(
            value: $fileName,
            maxLength: 100,
            hash: '-' . time(),
            stripExtension: true, // Quando salva com a extensão da xabu no thumbnail, que passa a precisar de uma extensão adicional .jpg.jpg
        );
        $filePath = $storageServiceFolder . '/' . $storageServicePublicId;
        $storageDeliveryId = (int) request('storageDeliveryId');
        $model = $this->fileRecordSave(
            filePath: $filePath,
            storageDeliveryId: $storageDeliveryId,
        );
        $cloudinaryDeliveryType = match ($storageDeliveryId) {
            YiiEnum::STORAGE_DELIVERY_AUTHENTICATED->value => CloudinaryService::DELIVERY_AUTHENTICATED,
            YiiEnum::STORAGE_DELIVERY_PRIVATE->value => CloudinaryService::DELIVERY_PRIVATE,
            default => CloudinaryService::DELIVERY_PUBLIC,
        };
        return response()->json(new JsonResponseResource([
            'presignData' => $cloudinaryService->getPresignData(
                folder: $storageServiceFolder,
                publicId: $storageServicePublicId,
                deliveryType: $cloudinaryDeliveryType,
            ),
            'fileData' => $model,
        ]));
    }

    private function fileRecordSave(string $filePath, int $storageDeliveryId): File {
        $model = new File;
        $model->fill([
            'file_byte' => request('fileBytes'),
            'file_conf' => false,
            'file_daho' => Cast::nowTimestamp(),
            'file_heig' => request('imageHeight'),
            'file_mity' => request('fileMimeType'),
            'file_path' => $filePath,
            'file_stde' => $storageDeliveryId,
            'file_widt' => request('imageWidth'),
        ]);
        $model->save();
        return $model;
    }
}
