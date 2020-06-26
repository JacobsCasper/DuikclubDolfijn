<?php

namespace App\Services;

abstract class CalenderTypes
{
const Duiken = 0;
const LessenOfTrainingen = 1;
const Activiteiten = 2;

public static function getById($id){
    switch ($id){
        case 0:
            return "Duiken";
        case 1:
            return "Lessen/Trainingen";
        case 2:
            return "Activiteiten";
    }
    return null;
}

public static function getAllTypes(){
 return ["Duiken", "Lessen/Trainingen", "Activiteiten"];
}
}