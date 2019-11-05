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
use Illuminate\Filesystem\Filesystem;


class AdminController extends Controller
{
    public function index(){
        $albums_names = PhotoAlbum::select("name", "id")->get();

        $view = view('new_admin')->with([
            "albums_names" => $albums_names
        ]);
        return $view;
    }


    public function showPreview($id){
        $al_info = DB::select("
            SELECT
                main.name as al_name,
                main.description as al_desc,
                main.id as al_id,
                main.slug as slug,
                GROUP_CONCAT(DISTINCT tg.tag) as tags
            FROM
                photo_albums main
            LEFT JOIN tags tg ON (tg.album_id = main.id)
            WHERE main.id = ".$id);

        $photos = DB::select("
            SELECT
                ph.name as name,
                ph.id as id,
                ph.pos as slug
            FROM
                photos ph
            LEFT JOIN albums_photos con1 ON (ph.id = con1.id_photo)
            LEFT JOIN photo_albums con2 ON (con1.id_album = con2.id)
            WHERE
                con2.id = ".$id."
            ORDER BY ph.pos
        ");
        $str = "";
        foreach($photos as $ph){
            $str = $str . implode(",", array($ph->name, $ph->id, $ph->slug)) . ";";
        }
        $str = substr($str, 0, -1);
        $al_info[0]->photos = $str;

        $all_tags = DB::table("tags")
                    ->select("tag")
                    ->distinct("tag")
                    ->get();
        return array("all_tags" => $all_tags, "al_info" => $al_info[0]);
    }

    public function delete_album($id){
        $alName = PhotoAlbum::where("id", $id)->select("slug")->first();
        PhotoAlbum::where("id", $id)->delete();
        DB::delete("delete from photos where id in (select id_photo from albums_photos where id_album = ".$id.")");
        Albums_photos::where("id_album", $id)->delete();
        DB::delete("delete from tags where album_id = ".$id);
        File::deleteDirectory(public_path('/img/'.$alName->slug));
        return array("result" => true);
    }

    public function saveNewCover(Request $request){
        DB::delete("delete from albums_photos where id_album = ".$request->al_id." and id_photo = (select title_photo_id from photo_albums where id = ".$request->al_id.")");
        DB::delete("delete from photos where id = (select title_photo_id from photo_albums where id = ".$request->al_id.")");
        $image = $request->file('edit_cover');
        $image = Image::make($image);
        $image->save(public_path('img/'.$request->slug.'/'.$request->slug."_cover.jpg"), 60);

        $photo_cover = new Photo();
        $photo_cover->name = $request->slug."_cover.jpg";
        $photo_cover->pos = 0;
        $photo_cover->save();

        $connection_cover = new Albums_photos();
        $connection_cover->id_album = $request->al_id;
        $connection_cover->id_photo = $photo_cover->id;
        $connection_cover->save();

        PhotoAlbum::where("id", $request->al_id)
                ->update(['title_photo_id' => $photo_cover->id]);

    }

    public function getAlbumsNames(){
        $albums = PhotoAlbum::select("name", "id", "slug")->orderBy("name", "ASC")->get();
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
        $image = $request->file('file');
        $originalName = $image->getClientOriginalName();
        if(!is_null($request->order)){
            $pos = array_search($originalName, $request->order);
        }
        else{
            $pos = 98;
        }

        if($pos == 0 || $pos != false){
            $allowExt = ["jpeg", "jpg", "png"];
            if(in_array(strtolower($image->getClientOriginalExtension()), $allowExt)){
                $filename = md5($originalName). "." . strtolower($image->getClientOriginalExtension());
                try{
                    $image = Image::make($image);
                    $image->save(public_path('img/'.$request->albumName.'/'.$filename), 60);
                    $photo = new Photo();
                    $photo->name = $filename;
                    $photo->pos = $pos+1;
                    $photo->save();

                    $connection = new Albums_photos();
                    $connection->id_album = $request->id;
                    $connection->id_photo = $photo->id;
                    $connection->save();
                    return array("result" => true);
                }
                catch(exc $e){
                    return array(
                        "result" => "false",
                        "errors" => $e
                    );
                }
            }
        }
        else{
            return array(
                "result" => "false",
                "errors" => "Такой файл не отправлялся"
            );
        }
    }

    public function createAlbum(Request $request){
        $slug = str_slug($request->nameOfAlbum);
        $album = new PhotoAlbum();
        $album->name = $request->nameOfAlbum;
        $album->description = $request->albumDesc;
        $album->slug = $slug;
        $album->save();

        File::makeDirectory(public_path('img/'.$slug));
        $image = Image::make(base64_decode($request["cover"]));
        $image->save(public_path('img/'.$slug.'/'.$slug."_cover.jpg"));

        $photo_cover = new Photo();
        $photo_cover->name = $slug."_cover.jpg";
        $photo_cover->pos = 0;
        $photo_cover->save();

        $connection_cover = new Albums_photos();
        $connection_cover->id_album = $album->id;
        $connection_cover->id_photo = $photo_cover->id;
        $connection_cover->save();

        PhotoAlbum::where('id', $album->id)
            ->update(['title_photo_id' => $photo_cover->id]);

        $tagsdb = array();
        if($request->tags != null){
            foreach ($request->tags as $key => $tag) {
                $tagsdb[$key] = ["album_id" => $album->id, "tag" => $tag];
            }
        }
        DB::table('tags')->insert($tagsdb);

        return array("result"=>true, "content" => array("id" => $album->id, "albumName" => $slug));
    }

    public function saveAfterEdit(Request $request){
        //Название, описание
        PhotoAlbum::where("id", $request->id)
                ->update(["name" => $request->nameOfAlbum,
                    "description" => $request->albumDesc]);

        DB::delete("delete from tags where album_id = ".$request->id);
        //Теги
        $tagsdb = array();
        if($request->edit_alInside != null){
            foreach ($request->edit_alInside as $key => $tag) {
                $tagsdb[$key] = ["album_id" => $request->id, "tag" => $tag];
            }
        }
        DB::table('tags')->insert($tagsdb);

        //Порядок
        $ids = explode(",", $request->pos);
        try{
            foreach($ids as $key => $id){
                Photo::where("id", $id)
                        ->update(["pos" => $key+1]);
            }
            return array("result" => true);
        }
        catch(exc $e){
            return array(
                "result" => false,
                "errors" => $e
            );
        }
    }

    public function deleteOnePhoto(Request $request){
        $slug = PhotoAlbum::select("slug")->where("id", $request->alId)->first();
        $ph_name = Photo::select("name")->where("id", $request->phId)->first();
        File::delete(public_path('/img/'.$slug["slug"]."/".$ph_name["name"]));
        Albums_photos::where("id_album", $request->alId)
                ->where("id_photo", $request->phId)
                ->delete();
        Photo::where("id", $request->phId)->delete();
        return array("result" => true, "content" => array("alId" => $request->alId, "phId" => $request->phId));
    }
}
