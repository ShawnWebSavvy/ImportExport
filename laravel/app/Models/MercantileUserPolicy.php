<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\CssSelector\Parser\Handler\WhitespaceHandler;

class MercantileUserPolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'PolicyNumber',
        'dummy_data_Capitec_active',
        'active',
        'deductionRow',
        'batch'
    ];

    public static function addNew($data){
        //self::insert($data);
        self::upsert(
            $data, ['PolicyNumber'], ['PolicyNumber', 'dummy_data_Capitec_active']
        );
    }

    public static function add($data){
        self::upsert(
            $data, ['PolicyNumber'], ['PolicyNumber', 'dummy_data_Capitec_active']
        );
    }
    public static function addNew_($data){
        //dd($data);
        self::upsert(
            $data, ['PolicyNumber'], ['PolicyNumber', 'dummy_data_Capitec_active']
        );

        //self::insert($data);
        /*
        self::updateOrInsert(
            ["PolicyNumber" => $data[0]['PolicyNumber']],
        );
        */
        //dd($data);
        //self::updateOrInsert($data);
    }
}

/*
public static function addNew($data){
        //dd($data);
        self::upsert(
            
            $data, ['PolicyNumber'], ['PolicyNumber']


        );

        //dd($data);
        //self::updateOrInsert($data);
    }
*/