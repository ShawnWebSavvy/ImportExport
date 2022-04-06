<?php
namespace App\Traits;
use Illuminate\Http\Request;

trait Guid
{
    public function get_guid(){
        return uniqid();
    }
}
