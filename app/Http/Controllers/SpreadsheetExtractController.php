<?php

namespace App\Http\Controllers;

use App\Custom\Cast;
use App\Custom\FileHelper;
use App\Enums\YiiEnum;
use App\Http\Resources\FileResource;
use App\Http\Resources\JsonResponseResource;
use App\Models\File;
use App\Services\CloudinaryService;
use App\Services\SpreadsheetExtractService;
use Illuminate\Http\JsonResponse;

class SpreadsheetExtractController
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
        $isRaw = FileHelper::isRaw(request('fileMimeType'));
        $fileName = FileHelper::resolveUploadName(request('fileName'));
        $storageServiceFolder = request('cloudinaryFolder');
        $storageServicePublicId = FileHelper::resolveFileName(
            value: $fileName,
            maxLength: 100,
            hash: '-' . time(),
            stripExtension: !$isRaw, // Quando salva com a extensão da xabu no thumbnail, que passa a precisar de uma extensão adicional .jpg.jpg
        );
        $filePath = $storageServiceFolder . '/' . $storageServicePublicId;
        $storageDeliveryId = (int) request('storageDeliveryId');
        $model = $this->fileRecordSave(
            fileName: $fileName,
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
                isRaw: $isRaw,
            ),
            'fileData' => new FileResource($model)->additional(['isRaw' => $isRaw]),
        ]));
    }

    private function fileRecordSave(string $fileName, string $filePath, int $storageDeliveryId): File {
        $model = new File;
        $model->fill([
            'file_byte' => request('fileBytes'),
            'file_conf' => false,
            'file_daho' => Cast::nowTimestamp(),
            'file_heig' => request('imageHeight'),
            'file_mity' => request('fileMimeType'),
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_stde' => $storageDeliveryId,
            'file_stse' => YiiEnum::STORAGE_SERVICE_CLOUDINARY->value,
            'file_type' => YiiEnum::FILE_SHEET_TO_EXTRACT->value,
            'file_widt' => request('imageWidth'),
        ]);
        $model->save();
        return $model;
    }

    public function apiConfirm(File $model, CloudinaryService $cloudinaryService, SpreadsheetExtractService $spreadsheetExtractService): JsonResponse {
        $model->update(['file_conf' => true]);
        $downloadUrl = $cloudinaryService->resolveDownloadUrl(FileHelper::isRaw($model->file_mity));
        $fileUrl = $downloadUrl . '/' . $model->file_path;
        $rows = $model->file_mity === 'text/csv'
            ? $spreadsheetExtractService->getCsvRows($fileUrl)
            : $spreadsheetExtractService->getSheetRows($fileUrl);
        return response()->json(new JsonResponseResource(['fileUrl' => $fileUrl, 'rows' => $rows]));
    }
}
