<?php


namespace App\utils;


use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class CustomUtils
{

    public static function parseDate($inputDate){
        $temp = Carbon::parse($inputDate);
        $m = $temp->month< 10 ? "0$temp->month": $temp->month;
        $d = $temp->day< 10 ? "0$temp->day": $temp->day;
        return "$m/$d/$temp->year";
    }

    public static function transFormDate($inputDate){
        if(strlen(trim($inputDate)) == 4){
            $inputDate = "01/01/$inputDate";
        }
        return Carbon::parse($inputDate)->toDateString();
    }
}
