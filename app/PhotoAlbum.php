<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12.12.2018
 * Time: 12:11
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class PhotoAlbum extends Model
{
    public $table='photo_albums';
    public $timestamp = true;

    public function get_title(){
        return $this->belongsTo('App\Photo', 'title_photo_id', 'id');
    }

    public function setIsBigAttribute($bool){
        $this->attributes['is_big'] = $bool;
    }

}