<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    use HasFactory;

    protected $fillable = [
        /*
        'policy_number',
        'deduction_row',
        'active',
        'batch'
        */
        'PolicyNumber',
        'dummy_data_Capitec_active',
        'row'
    ];
    public $timestamps = false;

    public static function addNew($data){
        //dd($data);
        
        self::insert($data);
        //dd($data);
        //self::updateOrInsert($data);
    }
}