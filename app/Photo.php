<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12.12.2018
 * Time: 12:41
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    public $table='photos';
    public $timestamp = true;
}