<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 20.11.2018
 * Time: 17:47
 */

namespace App\Http\Controllers;

use App\Albums_photos;
use App\Photo;
use App\PhotoAlbum;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class MyController extends Controller
{
    public function home(){
        $name_with_title = PhotoAlbum::with('get_title')->get()->shuffle();
        $new_order = new Collection();
        $buffer = new Collection();

        for ($i = 0; $i < count($name_with_title); $i++){
            if(count($buffer) == 2){
                $pos = rand(0, 2);
                $buffer->push($name_with_title[$i]);
                //dd(count($buffer));
                for ($j = 0; $j < count($buffer); $j++){
                    //print_r($buffer[$j]->name);
                    if($j == $pos)
                        $buffer[$j]->setIsBigAttribute(true);
                    else
                        $buffer[$j]->setIsBigAttribute(false);
                }
                $new_order = $new_order->merge($buffer);
                $buffer = new Collection();
            }
            else{
                $buffer->push($name_with_title[$i]);
                //print_r($buffer);
            }
        }

        $pos = rand(0, count($buffer));
        for ($j = 0; $j < count($buffer); $j++){
            //print_r($buffer[$j]->name);
            if($j == $pos)
                $buffer[$j]->setIsBigAttribute(true);
            else
                $buffer[$j]->setIsBigAttribute(false);
        }
        $new_order = $new_order->merge($buffer);


        $view = view('home.index')->with([
            'name_with_title' => $name_with_title,
        ]);
        return $view;
    }



    public function index_album($al_id){
        $al = PhotoAlbum::get();
        $position = array_search($al_id, $al->map->id->toArray());
        $al_info = $al->get($position);
        if(!is_null($al->get($position-1)))
            $prev_id = $al->get($position-1);
        else
            $prev_id = $al->get($position);
        if(!is_null($al->get($position+1)))
            $next_id = $al->get($position+1);
        else
            $next_id = $al->get($position);

        $photos_of_album = Albums_photos::join("photos", "albums_photos.id_photo", "=", "photos.id")
            ->where('id_album', $al_id)
            ->where("name", "NOT LIKE", "%cover%")
            ->get();

        $tags = DB::table("tags")->where("album_id", $al_id)->select("tag")->get();

        $is_last = false;
        $max_id = PhotoAlbum::find(DB::table('photo_albums')->max('id'));

        //dd($al_info, $max_id);
        if($al_info->id == $max_id->id) $is_last = true;

        $view = view('album_index')->with([
            'al_info' => $al_info,
            'photos_of_album' => $photos_of_album,
            'is_last'=>$is_last,
            'next_id'=>$next_id->id,
            'prev_id'=>$prev_id->id,
            'tags' => $tags
        ]);
        return $view;
    }

    public function WhoAmI(){
        return view("/home/me");
    }

    public function getGenresIndex(){
        $tags = $this->getTags();
        return view("/home/genres", ["tags" => $tags]);
    }

    public function getAlbums(Request $request){
        return DB::select("
            SELECT
                al.id,
                al.name as al_name,
                al.title_photo_id,
                ph.name as ph_name
            FROM
                photo_albums al
            LEFT JOIN photos ph ON (al.title_photo_id = ph.id)
            WHERE al.id IN (SELECT album_id FROM tags WHERE tag = '".$request->tag."')
            ");
    }

    public function getTags(){
        return DB::select("SELECT DISTINCT tag as text, COUNT(1) as weight FROM tags GROUP BY tag ORDER BY weight DESC");
    }

    public function getInst(){

    }
}