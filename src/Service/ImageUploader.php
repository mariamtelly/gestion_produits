<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function uploadProduct(UploadedFile $image)
    {
        $fichier = md5(uniqid(rand(), true)) . '.webp';

        $imageInfos = getImageSize($image);
        if(!$imageInfos)
        {
            throw new FileException("Format d'image incorrect!");
        }

        switch($imageInfos["mime"])
        {
            case "image/png":
                $imageSource = imagecreatefrompng($image);
                break;
            case "image/jpeg":
                $imageSource = imagecreatefromjpeg($image);
                break;
            case "image/jpg":
                $imageSource = imagecreatefromjpg($image);
                break;
            case "image/webp":
                $imageSource = imagecreatefromwebp($image);
                break;
            default:
                throw new FileException("Format d'image incorrect!");
        }

        // On récupère les dimensions
        $imageWidth = $imageInfos[0];
        $imageHeight = $imageInfos[1];

        //$resizedImage = imagecreatetruecolor($width, $height);

        $thumbnail = imagecreatetruecolor(100, 100);
        //$thumbnail_2 = imagecreatetruecolor(115, 100);
        $normal = imagecreatetruecolor(550, 750);

        //imagecopyresampled($resizedImage, $imageSource, 0, 0, 0, 0, $width, $height, $imageWidth, $imageHeight);

        imagecopyresampled($thumbnail, $imageSource, 0, 0, 0, 0, 100, 100, $imageWidth, $imageHeight);
        //imagecopyresampled($thumbnail_2, $imageSource, 0, 0, 0, 0, 115, 100, $imageWidth, $imageHeight);
        imagecopyresampled($normal, $imageSource, 0, 0, 0, 0, 550, 750, $imageWidth, $imageHeight);

        $path = $this->params->get('products_uploads_directory');
        //imagewebp($resizedImage, $path . '/' . $folder . '/' . $width . 'x' . $height . '-' . $fichier);

        imagewebp($thumbnail, $path . '/thumbnails/' . $fichier);
        //imagewebp($thumbnail_2, $path . '/' . $folder . '/' . $width . 'x' . $height . '-' . $fichier);
        imagewebp($normal, $path . '/normals/' . $fichier);

        $image->move($path . '/', $fichier);

        return $fichier;
    }

    public function deleteProduct(?string $fichier, ?int $width, ?int $height, ?string $folder = "")
    {
        if($fichier !== 'default.webp')
        {
            $success = false;
            $path = $this->params->get('products_uploads_directory');

            $thumbnail = $path . '/thumbnails/' . $fichier;
            if(file_exists($thumbnail)){
                unlink($thumbnail);
                $success = true;
            }

            $normal = $path . '/normals/' . $fichier;
            if(file_exists($normal)){
                unlink($normal);
                $success = true;
            }

            $original = $path . '/' . $fichier;

            if(file_exists($original)){
                unlink($original);
                $success = true;
            }
            return $success;
        }
        return false;
    }
}