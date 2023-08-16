<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ImagePath
{
    /**
     * アップロードされた画像のパスを取得
     *
     * @param UploadedFile|null $imageFile
     * @param string $directoryName
     * @return string
     */
    public function getImagePath(?UploadedFile $imageFile, string $directoryName): string
    {
        $filePath = $imageFile->store("public/{$directoryName}");
        $imageFilePath = str_replace("public/{$directoryName}", "storage/{$directoryName}", $filePath);

        return $imageFilePath;
    }
}
