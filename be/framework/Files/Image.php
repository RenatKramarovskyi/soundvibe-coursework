<?php

namespace Framework\Files;

use Exception;
use Framework\Config\Config;
use Framework\ORM\JsonSerializable;

class Image implements JsonSerializable
{
    public string $fileName;
    public string $fileExt;

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;
        return $this;
    }

    public function getFileExt(): string
    {
        return $this->fileExt;
    }

    public function setFileExt(string $fileExt): self
    {
        $this->fileExt = $fileExt;
        return $this;
    }

    public static function upload(array $fileData, ?string $uploadName = null, ?array $newSize = null): self
    {
        $image = new Image();

        $imageDir = (Config::$config["files"]["img_location"] ?? "");
        if(!is_dir($_SERVER["DOCUMENT_ROOT"] . "/" . $imageDir)){
            mkdir($_SERVER["DOCUMENT_ROOT"] . "/" . $imageDir, 0777, true);
        }

        $split = explode(".", $fileData["name"]);
        $ext = end($split);
        array_pop($split);
        $image->setFileExt(".jpeg");
        $fileName = implode(".", $split) . ".jpeg";

        $image->setFileName($imageDir . $fileName);
        if($uploadName !== null){
            $image->setFileName($imageDir . $uploadName);
        }

        $srcFile = $fileData["tmp_name"];
        $srcImg = false;
        if($ext == "jpg" || $ext == "jpeg"){
            $srcImg = imagecreatefromjpeg($srcFile);
        } else if($ext == "png"){
            $srcImg = imagecreatefrompng($srcFile);
        }

        if($srcImg === false){
            throw new Exception("Error uploading image " . $image->getFileName());
        }

        if($newSize !== null) {
            $originalSize = getimagesize($srcFile);

            $resizedImg = imagecreatetruecolor($newSize[0], $newSize[1]);
            imagecopyresampled(
                $resizedImg, $srcImg,
                0, 0,
                0, 0,
                $newSize[0], $newSize[1],
                $originalSize[0], $originalSize[1]
            );

            $srcImg = $resizedImg;
            imagedestroy($resizedImg);
        }

        $targetFile = $_SERVER["DOCUMENT_ROOT"] . "/" . $image->getFileName();
        imagejpeg($srcImg, $targetFile);
        imagedestroy($srcImg);

        return $image;
    }

    public function jsonSerialize(): array
    {
        return [
            "url" => $this->getFileName(),
            "ext" => $this->getFileExt()
        ];
    }

}