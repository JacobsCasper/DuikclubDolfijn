<?php


namespace App\Services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileService
{

    public function uploadFile($file, SluggerInterface $slugger, $fileDirectory){
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move(
                $fileDirectory,
                $newFilename
            );
        } catch (FileException $e) {

        }

        return $newFilename;
    }

    public static function getBanner(){
        $dir = 'app/public/defaultBanners';

        $files = glob($dir . '/*.*');
        $file = array_rand($files);
        return $files[$file];
    }

}