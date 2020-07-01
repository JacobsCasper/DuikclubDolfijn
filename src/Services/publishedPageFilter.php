<?php


namespace App\Services;


use App\Entity\Page;

class publishedPageFilter
{

    public static function filter($pages){
        $filteredPages = [];
        foreach ($pages as $page){
            if($page->getPublished() && $page->getNavigationEnabled()){
                array_push($filteredPages, $page);
            }
        }

        return $filteredPages;
    }

    public static function filterHomePageEnabled($pages){
        $filteredPages = [];
        foreach ($pages as $page){
            if($page->getPublished() && $page->getHomepageEnabled()){
                array_push($filteredPages, $page);
            }
        }

        return $filteredPages;
    }

}