<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 08.12.2018
 * Time: 12:47
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Photo;
use App\PhotoAlbum;
use App\Albums_photos;
use Image;
use File;
use Illuminate\Support\Facades\Input;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Exception\NotReadableException as exc;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    public function index(){
        $albums_names = PhotoAlbum::select("name", "id")->get();
        $view = view('new_admin')->with([
            "albums_names" => $albums_names
        ]);
        return $view;
    }

    public function save_photo(Request $request){
        $all_images = $request->allFiles();
        usort($all_images, function($a, $b){
            return ($a->getClientOriginalName() < $b->getClientOriginalName()) ? -1 : 1;
        });
        dd($all_images);
        $album = new PhotoAlbum();
        $album->name = $request->name_of_album;
        $album->description = $request->album_desc;
        $album->save();

        File::makeDirectory(public_path('img/').$request->name_of_album);
        $image = Image::make(base64_decode($request["cover"]));
        $image->save(public_path('img/'.$request->name_of_album.'/'.$request->name_of_album."_cover.jpg"));

        $photo_cover = new Photo();
        $photo_cover->name = $request->name_of_album."_cover.jpg";
        $photo_cover->save();

        $connection_cover = new Albums_photos();
        $connection_cover->id_album = $album->id;
        $connection_cover->id_photo = $photo_cover->id;
        $connection_cover->save();

        PhotoAlbum::where('id', $album->id)
            ->update(['title_photo_id' => $photo_cover->id]);

        $count = 0;
        foreach($all_images as $image){
            if($image->getClientOriginalExtension() == "jpeg" ||
                    $image->getClientOriginalExtension() == "jpg" ||
                    $image->getClientOriginalExtension() == "png"){
                    $filename = $request->name_of_album . '_photo_'. $count . '.' . $image->getClientOriginalExtension();

                try{
                    $image = Image::make($image);
                    $image->save(public_path('img/'.$request->name_of_album.'/'.$filename));
                    $photo = new Photo();
                    $photo->name = $filename;
                    $photo->save();

                    $connection = new Albums_photos();
                    $connection->id_album = $album->id;
                    $connection->id_photo = $photo->id;
                    $connection->save();
                }
                catch(exc $e){

                }
                $count = $count+1;
            }
        }

        $tags = explode("/", $request->tags);
        $tagsdb = array();
        foreach ($tags as $key => $tag) {
           $tagsdb[$key] = ["album_id" => 2, "tag" => $tag];
        }
        DB::table('tags')->insert($tagsdb);

        return array(0 , "Загузка прошла успешно!");
    }

    public function show_preview($id){
        $photos = DB::table("photo_albums")
                    ->leftjoin("albums_photos", "photo_albums.id", "=", "albums_photos.id_album")
                    ->leftjoin("photos", "albums_photos.id_photo", "=", "photos.id")
                    ->where("photo_albums.id", $id)
                    ->select(
                            "photo_albums.name as al_name",
                            "photos.name as  ph_name",
                            "photo_albums.id as al_id",
                            "photos.id as  ph_id"
                            )
                    ->get();
        $tags = DB::table("photo_albums")
                    ->leftjoin("tags", "photo_albums.id", "=", "tags.album_id")
                    ->select("tags.tag")
                    ->where("tags.album_id", $id)
                    ->get();

        $all_tags = DB::table("tags")
                    ->select("tag")
                    ->distinct("tag")
                    ->get();
        return array("photos" => $photos, "tags" => $tags, "all_tags" => $all_tags);
    }

    public function delete_album($id){
        PhotoAlbum::where("id", $id)->delete();
        DB::delete("delete from photos where id in (select id_photo from albums_photos where id_album = ".$id.")");
        Albums_photos::where("id_album", $id)->delete();
        DB::delete("delete from tags where album_id = ".$id);
    }

    public function delete_one_photo($al_id, $ph_id){

        Albums_photos::where("id_album", $al_id)
                ->where("id_photo", $ph_id)
                ->delete();
        Photo::where("id", $ph_id)->delete();
        return array($al_id, $ph_id);
    }

    public function save_new_cover(Request $request){
        DB::delete("delete from albums_photos where id_album = ".$request->al_id." and id_photo = (select title_photo_id from photo_albums where id = ".$request->al_id.")");
        DB::delete("delete from photos where id = (select title_photo_id from photo_albums where id = ".$request->al_id.")");

        $image = Image::make(base64_decode($request["edit_cover"]));
        $image->save(public_path('img/'.$request->al_name.'/'.$request->al_name."_cover.jpg"));

        $photo_cover = new Photo();
        $photo_cover->name = $request->al_name."_cover.jpg";
        $photo_cover->save();

        $connection_cover = new Albums_photos();
        $connection_cover->id_album = $request->al_id;
        $connection_cover->id_photo = $photo_cover->id;
        $connection_cover->save();

        PhotoAlbum::where("id", $request->al_id)
                ->update(['title_photo_id' => $photo_cover->id]);

        return array("Обложка успешно обновлена!", '/img/'.$request->al_name.'/'.$request->al_name."_cover.jpg");
    }



    public function getAlbumsNames(){
        $albums = PhotoAlbum::select("name", "id")->get();
        return array("result" => true, "content" => $albums);
    }

    public function getAlbumsPhotos(Request $request){
        $photos = DB::select("SELECT id, name FROM photos WHERE id IN (SELECT id_photo FROM albums_photos WHERE id_album = ".$request->id.")");
        return array("result" => true, "content" => $photos);
    }

    public function getTags(Request $request){
        $tags = DB::table("tags")
                    ->select("tag")
                    ->where("tag", "like", '%'.$request->tag."%")
                    ->distinct("tag")
                ->get();

        return $tags;
    }

    public function saveNewTags(Request $request){
        DB::table("tags")
            ->where("album_id", $request->album_id)
            ->delete();

        $newtags = array();

        foreach ($request->tags_edit as $key => $tag) {
           $newtags[$key] = ["album_id" => $request->album_id, "tag" => $tag];
        }
        DB::table('tags')->insert($newtags);

        return array(0 , "Теги успешно обновлены.");
    }


    public function savePhoto(Request $request){
        dd($request);
        /*$image = $request->file('file');
        $allowExt = ["jpeg", "jpg", "png"];
        if(in_array(strtolower($image->getClientOriginalExtension()), $allowExt)){
                $filename = md5($image->getClientOriginalName()). "." . strtolower($image->getClientOriginalExtension());
            try{
                $image = Image::make($image);
                $image->save(public_path('img/'.$request->albumName.'/'.$filename));
                $photo = new Photo();
                $photo->name = $filename;
                $photo->save();

                $connection = new Albums_photos();
                $connection->id_album = $request->id;
                $connection->id_photo = $photo->id;
                $connection->save();
            }
            catch(exc $e){

            }
        }*/
    }

    public function createAlbum(Request $request){
        $album = new PhotoAlbum();
        $album->name = $request->nameOfAlbum;
        $album->description = $request->albumDesc;
        $album->save();

        File::makeDirectory(public_path('img/').$request->nameOfAlbum);
        $image = Image::make(base64_decode($request["cover"]));
        $image->save(public_path('img/'.$request->nameOfAlbum.'/'.$request->nameOfAlbum."_cover.jpg"));

        $photo_cover = new Photo();
        $photo_cover->name = $request->nameOfAlbum."_cover.jpg";
        $photo_cover->save();

        $connection_cover = new Albums_photos();
        $connection_cover->id_album = $album->id;
        $connection_cover->id_photo = $photo_cover->id;
        $connection_cover->save();

        PhotoAlbum::where('id', $album->id)
            ->update(['title_photo_id' => $photo_cover->id]);

        $tagsdb = array();
        foreach ($request->tags as $key => $tag) {
           $tagsdb[$key] = ["album_id" => $album->id, "tag" => $tag];
        }

        DB::table('tags')->insert($tagsdb);

        return array("result"=>true, "id" => $album->id, "albumName" => $request->nameOfAlbum);
    }
}