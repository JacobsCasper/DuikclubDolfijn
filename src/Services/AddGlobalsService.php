<?php


namespace App\Services;

use Twig\Environment;

class AddGlobalsService
{

    public static function getImage(){
        $dirname = "backgroundImages/";
        $pngs = glob($dirname."*.png");
        $jpegs = glob($dirname."*.jpeg");
        $jpgs = glob($dirname."*.jpg");

        $images = array_merge($pngs, $jpegs, $jpgs);
        $index = random_int(1, count($images));
        $index--;
        return $images[$index];
    }

    public static function addGlobals(Environment $twig, Array $pages){
        //add background image

        $twig->addGlobal('backgroundImage', AddGlobalsService::getImage());

        //add nav pages
        $twig->addGlobal('pages', $pages);
    }
}