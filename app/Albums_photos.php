<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12.12.2018
 * Time: 12:42
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Albums_photos extends Model
{
    public $table='albums_photos';
    public $timestamp = true;

    public function photo(){
        return $this->belongsTo('App\Photo', 'id_photo', 'id');
    }

}