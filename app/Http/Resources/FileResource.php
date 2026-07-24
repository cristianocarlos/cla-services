<?php

namespace App\Http\Resources;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin File
 */
class FileResource extends JsonResource
{
    public function toArray(Request $request): array {
        return [
            'bytes' => $this->file_byte,
            'height' => $this->whenNotNull($this->file_heig),
            'id' => $this->file_code,
            'mime_type' => $this->file_mity,
            'name' => $this->file_name,
            'width' => $this->whenNotNull($this->file_widt),
            'path' => $this->file_path,
        ];
    }
}
