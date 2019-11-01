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
        $name_with_title = PhotoAlbum::with('get_title')->orderBy("created_at", "DESC")->get();
        $view = view('home.index')->with([
            'name_with_title' => $name_with_title,
        ]);
        return $view;
    }

    public function index_album_desk($slug){
        $allAbout = $this->getAllAboutAlbum($slug);
        if(config("mobile")){
            $view = view('al_index_mob')->with([
                'al_info' => $allAbout["info"][0],
                'photos_of_album' => $allAbout["photos"],
                'tags' => $allAbout["tags"]
            ]);
        }
        else{
            $view = view('al_index_desk')->with([
                'al_info' => $allAbout["info"][0],
                'photos_of_album' => $allAbout["photos"],
                'tags' => $allAbout["tags"]
            ]);
        }


        return $view;
    }


    private function getAllAboutAlbum($slug){
        $al_info = PhotoAlbum::where("slug", $slug)
            ->get();
        $photos_of_album = Albums_photos::join("photos", "albums_photos.id_photo", "=", "photos.id")
            ->where('id_album', $al_info[0]->id)
            ->where("name", "NOT LIKE", "%cover%")
            ->orderBy('pos')
            ->get();

        $tags = DB::table("tags")->where("album_id", $al_info[0]->id)->select("tag")->get();
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
                al.slug,
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