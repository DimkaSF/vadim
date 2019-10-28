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



    public function index_album_desk($al_id){
        $allAbout = $this->getAllAboutAlbum($al_id);
        $view = view('al_index_desk')->with([
            'al_info' => $allAbout["info"][0],
            'photos_of_album' => $allAbout["photos"],
            'tags' => $allAbout["tags"]
        ]);
        return $view;
    }

    public function index_album_mob($al_id){
        $allAbout = $this->getAllAboutAlbum($al_id);
        $view = view('al_index_mob')->with([
            'al_info' => $allAbout["info"][0],
            'photos_of_album' => $allAbout["photos"],
            'tags' => $allAbout["tags"]
        ]);
        return $view;
    }

    private function getAllAboutAlbum($al_id){
        $al_info = PhotoAlbum::where("id", $al_id)
            ->get();

        $photos_of_album = Albums_photos::join("photos", "albums_photos.id_photo", "=", "photos.id")
            ->where('id_album', $al_id)
            ->where("name", "NOT LIKE", "%cover%")
            ->orderBy('name', 'ASC')
            ->get();

        $tags = DB::table("tags")->where("album_id", $al_id)->select("tag")->get();
        return array("info" => $al_info, "photos" => $photos_of_album, "tags" => $tags);
    }

    public function WhoAmI(){
        return view("/home/me");
    }

    public function getGenresIndex(){
        $tags = $this->getTags();
        return view("/home/genres", ["tags" => $tags]);
    }

    public function getAlbumsWithTag($tag){
        $tags = $this->getTags();
        $albums = DB::select("
            SELECT
                al.id,
                al.name as al_name,
                al.title_photo_id,
                ph.name as ph_name
            FROM
                photo_albums al
            LEFT JOIN photos ph ON (al.title_photo_id = ph.id)
            WHERE al.id IN (SELECT album_id FROM tags WHERE tag = '".$tag."')
            ");
        return view("/home/genres", ["tags" => $tags, "albums" => $albums]);

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
}