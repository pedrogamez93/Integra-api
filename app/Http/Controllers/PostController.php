<?php

namespace App\Http\Controllers;

use App\Helpers\UtilHelper;
use App\Post;
use App\Http\Controllers\LogUserController;
class PostController extends Controller
{
    public function index()
    {
        $user = auth('api')->user();

        $util = new LogUserController;
        $util->store('Noticias');

        $utilHelper = new UtilHelper();

        if ($user == null or (isset($user->is_public)) && $user->is_public) {

            $news = Post::select('posts.*')
                ->where('datetime', '<=', $utilHelper->getDateCurrent())
                ->where('posts.is_public', 1)
                ->orderBy('datetime', 'desc')
                ->paginate(5);
        } else {
            $news = \Cache::remember($user->persk . $user->werks . $user->tipest . $_GET['page'] . 'posts_index', 60 * 60, function () use ($utilHelper, $user) {
                $region = UtilHelper::QueryRegion($user);
                return Post::select('posts.*')
                    ->orderBy('datetime', 'desc')->join('post_districts', 'post_districts.post_id', 'posts.id')
                    ->join('districts', 'post_districts.district_id', 'districts.id')
                    ->join('post_positions', 'post_positions.post_id', 'posts.id')
                    ->join('positions', 'post_positions.position_id', 'positions.id')
                    ->join('post_regions', 'post_regions.post_id', 'posts.id')
                    ->join('regions', 'post_regions.region_id', 'regions.id')
                    ->where('posts.is_private', 1)
                    ->where('positions.code', $user->persk)
                    ->whereRaw($region)
                    ->where('districts.code', $user->tipest)
                    ->where('datetime', '<=', $utilHelper->getDateCurrent())
                    ->orderBy('datetime', 'desc')
                    ->distinct()
                    ->paginate(5);
            });
        }

        if (!$news) {
            return response()->json(
                array(
                    'is_logged' => auth('api')->user() ? 1 : 0,
                    'statusCode' => 404,
                    'message' => 'Página no encontrada',
                ),
                404
            );
        }
        foreach ($news as $key => $value) {
            $value->img = [
                "src" => $value->getFirstMedia('image_new') ? env('APP_URL') . $value->getFirstMediaUrl('image_new', 'thumb') : '',
                "alt" => $value->getFirstMedia('image_new') ? $value->getFirstMedia('image_new')->name : '',
            ];
            $date = \Carbon\Carbon::parse($value->datetime)->format('d · m · Y');
            unset($value->datetime);
            $value->date = $date;
            unset($value->mediaRelation);
            unset($value->src);
            $value->slug = $value->slug;
        }

        return response()->json([
            'is_logged' => auth('api')->user() ? 1 : 0,
            'data' => [
                'posts' => $news,
                'status' => true,
            ],
        ]);
    }

    public function show($slug)
    {
        $utilHelper = new UtilHelper();
        $user = auth('api')->user();
        $lastNewArray = [];
        $linkArray = [];
        $news = Post::with(['linkPost','benefitPost'])->where('slug', $slug)->first();

        if (!$news) {
            return response()->json(
                array(
                    'is_logged' => auth('api')->user() ? 1 : 0,
                    'statusCode' => 404,
                    'message' => 'Página no encontrada',
                ),
                404
            );
        }

        if ($user == null or (isset($user->is_public)) && $user->is_public) {
            $latNews = Post::select('posts.*')
                ->where('datetime', '<=', $utilHelper->getDateCurrent())
                ->where('posts.is_public', 1)
                ->inRandomOrder()
                ->limit(2)
                ->get();
        } else {
            $latNews = \Cache::remember($user->persk . $user->werks . $user->tipest . 'post_show' . $slug, 60 * 60, function () use ($utilHelper, $news, $user) {
                $region = UtilHelper::QueryRegion($user);
                return Post::select('posts.*')
                    ->join('post_districts', 'post_districts.post_id', 'posts.id')
                    ->join('districts', 'post_districts.district_id', 'districts.id')
                    ->join('post_positions', 'post_positions.post_id', 'posts.id')
                    ->join('positions', 'post_positions.position_id', 'positions.id')
                    ->join('post_regions', 'post_regions.post_id', 'posts.id')
                    ->join('regions', 'post_regions.region_id', 'regions.id')
                    ->where('posts.id', '!=', $news->id)
                    ->where('posts.is_private', 1)
                    ->where('positions.code', $user->persk)
                    ->whereRaw($region)
                    ->where('districts.code', $user->tipest)
                    ->where('datetime', '<=', $utilHelper->getDateCurrent())
                    ->inRandomOrder()
                    ->distinct()
                    ->limit(2)
                    ->get();
            });
        }

        foreach ($news->linkPost as $link) {
            $linkArray[] = [
                'label' => $link->name,
                'icon' => $link->icon,
                'url' => $link->url,
                'target' => $link->target,
            ];
        }
        unset($news->linkPost);
        $news->links = $linkArray;

        foreach ($latNews as $lastNew) {
            $dataImage = [
                "src" => $lastNew->getFirstMedia('image_new') ? env('APP_URL') . $lastNew->getFirstMediaUrl('image_new') : '',
                "alt" => $lastNew->getFirstMedia('image_new') ? $lastNew->getFirstMedia('image_new')->name : '',
            ];
            $lastNewArray[] = [
                'id' => $lastNew->id,
                'slug' => $lastNew->slug,
                'img' => $dataImage,
                'title' => $lastNew->title,
                'date' => date("d · m · Y", strtotime($lastNew->datetime)),
            ];
        }
        $news->post_gallery = [];
        $news->featured_img = [];

        $news->featured_img = [
            "src" => $news->getFirstMedia('image_new') ? env('APP_URL') . $news->getFirstMediaUrl('image_new') : '',
            "alt" => $news->getFirstMedia('image_new') ? $news->getFirstMedia('image_new')->name : '',
        ];

        foreach ($news->getMedia('image_new') as $key => $media) {
            $news->post_gallery = [
                'id' => $media->id,
                'alt' => $media->name,
                'src' => $media->getUrl(),
            ];
        }

        $news->date = \Carbon\Carbon::parse($news->datetime)->format('d · m · Y');
        unset($news->datetime);

        return response()->json([
            'is_logged' => auth('api')->user() ? 1 : 0,
            'data' => $news,
            'related' => array(
                'data' => $lastNewArray,
            ),
            'status' => true,
        ]);
    }
}