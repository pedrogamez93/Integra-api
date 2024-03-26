<?php

namespace App\Http\Controllers;

use App\Helpers\UtilHelper;
use App\Benefit;
// use Illuminate\Support\Facades\Input;
use Symfony\Component\Console\Input;
use Illuminate\Http\Request;
//use Illuminate\Http\Input;
use App\Http\Controllers\LogUserController;

class BenefitController extends Controller
{
    public function index()
    {
        extract($_GET);
        $other = isset($other) ? $other : 0;

        $user = auth('api')->user();

        $util = new LogUserController;
        $util->store('Beneficios');

        $utilHelper = new UtilHelper();

        if ($user == null) {
            return response()->json([
                'is_logged' => auth('api')->user() ? 1 : 0,
                'data' => [],
            ]);
        }

        $region = UtilHelper::QueryRegion($user);
        $wherePositions = UtilHelper::QueryPosition($user);

        $news = Benefit::select('benefits.*')
            ->join('benefit_regions', 'benefit_regions.benefit_id', 'benefits.id')
            ->join('regions', 'benefit_regions.region_id', 'regions.id')
            ->join('benefit_positions', 'benefit_positions.benefit_id', 'benefits.id')
            ->join('positions', 'benefit_positions.position_id', 'positions.id')
            
            ->join('benefit_districts', 'benefit_districts.benefit_id', 'benefits.id')
            ->join('districts', 'benefit_districts.district_id', 'districts.id')
            ->where('datetime', '<=', $utilHelper->getDateCurrent())
            ->whereRaw($region)
            ->whereRaw($wherePositions)
            ->where('districts.code', $user->tipest)
            ->orderBy('datetime', 'desc');
        $news = $news->where('benefits.is_other', $other);
        $news = $news->groupBy('benefits.id');
        $news = $news->paginate(5);



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
                "src" => $value->getFirstMedia('image_new') ? $value->getFirstMediaUrl('image_new', 'thumb') : '',
                "alt" => $value->getFirstMedia('image_new') ? $value->getFirstMedia('image_new')->name : '',
            ];
            $date = \Carbon\Carbon::parse($value->datetime)->format('d · m · Y');
            unset($value->datetime);
            $value->date = $date;
            unset($value->mediaRelation);
            unset($value->src);
            $value->slug = $value->slug;
           
            $filesData = json_decode($value->file);
            $files = [];
            foreach ($filesData as $key => $f) {
                if(!empty($f->attributes)) {
                    array_push($files, [
                        'alt' => $f->attributes->file,
                        'src' => env('APP_URL').'/storage/'. $f->attributes->file,
                    ]);
                }
            }
            $value->files = $files; //? env('APP_URL').'/storage/'. $value->file : ''
            unset($value->file);
        }

        return response()->json([
            'is_logged' => auth('api')->user() ? 1 : 0,
            'data' => [
                'benefits' => $news,
                'status' => true,
                'user' => $user,
            ],
        ]);
    }


    public function formatData($data)
    {
        extract($_GET);
        $other = isset($other) ? $other : 0;

        $user = auth('api')->user();
        $utilHelper = new UtilHelper();

        if ($user == null) {
            return response()->json([
                'is_logged' => auth('api')->user() ? 1 : 0,
                'data' => [],
            ]);
        }

        $region = UtilHelper::QueryRegion($user);
        $wherePositions = UtilHelper::QueryPosition($user);

        $news = Benefit::select('benefits.*')
            ->join('benefit_regions', 'benefit_regions.benefit_id', 'benefits.id')
            ->join('regions', 'benefit_regions.region_id', 'regions.id')
            ->join('benefit_positions', 'benefit_positions.benefit_id', 'benefits.id')
            ->join('positions', 'benefit_positions.position_id', 'positions.id')
            
            ->join('benefit_districts', 'benefit_districts.benefit_id', 'benefits.id')
            ->join('districts', 'benefit_districts.district_id', 'districts.id')
            ->where('datetime', '<=', $utilHelper->getDateCurrent())
            ->whereRaw($region)
            ->whereRaw($wherePositions)
            ->where('districts.code', $user->tipest)
            ->orderBy('datetime', 'desc');
        $news = $news->where('benefits.is_other', $other);
        $news = $news->groupBy('benefits.id');
        $news = $news->paginate(5);



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
                "src" => $value->getFirstMedia('image_new') ? $value->getFirstMediaUrl('image_new', 'thumb') : '',
                "alt" => $value->getFirstMedia('image_new') ? $value->getFirstMedia('image_new')->name : '',
            ];
            $date = \Carbon\Carbon::parse($value->datetime)->format('d · m · Y');
            unset($value->datetime);
            $value->date = $date;
            unset($value->mediaRelation);
            unset($value->src);
            $value->slug = $value->slug;
           
            $filesData = json_decode($value->file);
            $files = [];
            foreach ($filesData as $key => $f) {
                if(!empty($f->attributes)) {
                    array_push($files, [
                        'alt' => $f->attributes->file,
                        'src' => env('APP_URL').'/storage/'. $f->attributes->file,
                    ]);
                }
            }
            $value->files = $files; //? env('APP_URL').'/storage/'. $value->file : ''
            unset($value->file);
        }

        return response()->json([
            'is_logged' => auth('api')->user() ? 1 : 0,
            'data' => [
                'benefits' => $news,
                'status' => true,
                'user' => $user,
            ],
        ]);
    }

    public function show($slug)
    {
        $utilHelper = new UtilHelper();
        $user = auth('api')->user();
        $lastNewArray = [];
        $linkArray = [];
        $news = Benefit::with(['linkBenefit'])->where('slug', $slug)->first();

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
            $latNews = Benefit::select('benefits.*')
                ->where('datetime', '<=', $utilHelper->getDateCurrent())
                ->where('benefits.is_public', 1)
                ->inRandomOrder()
                ->limit(2)
                ->get();
        } else {
            $latNews = \Cache::remember($user->persk . $user->werks . $user->tipest . 'benefit_show' . $slug, 60 * 60, function () use ($utilHelper, $news, $user) {
                $region = UtilHelper::QueryRegion($user);
                return Benefit::select('benefits.*')
                    ->join('benefit_districts', 'benefit_districts.benefit_id', 'benefits.id')
                    ->join('districts', 'benefit_districts.district_id', 'districts.id')
                    ->join('benefit_positions', 'benefit_positions.benefit_id', 'benefits.id')
                    ->join('positions', 'benefit_positions.position_id', 'positions.id')
                    ->join('benefit_regions', 'benefit_regions.benefit_id', 'benefits.id')
                    ->join('regions', 'benefit_regions.region_id', 'regions.id')
                    ->where('benefits.id', '!=', $news->id)
                    ->where('benefits.is_private', 1)
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

        foreach ($news->linkBenefit as $link) {
            $linkArray[] = [
                'label' => $link->name,
                'icon' => $link->icon,
                'url' => $link->url,
                'target' => $link->target,
            ];
        }
        unset($news->linkBenefit);
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
        $news->benefit_gallery = [];
        $news->featured_img = [];

        $news->featured_img = [
            "src" => $news->getFirstMedia('image_new') ? env('APP_URL') . $news->getFirstMediaUrl('image_new') : '',
            "alt" => $news->getFirstMedia('image_new') ? $news->getFirstMedia('image_new')->name : '',
        ];

        $filesData = json_decode($news->file);
        $files = [];
        foreach ($filesData as $key => $f) {
            if(!empty($f->attributes)) {
                array_push($files, [
                    'alt' => $f->attributes->file,
                    'src' => env('APP_URL').'/storage/'. $f->attributes->file,
                ]);
            }
        }
        $news->files = $files; //? env('APP_URL').'/storage/'. $value->file : ''
        unset($news->file);

        foreach ($news->getMedia('image_new') as $key => $media) {
            $news->benefit_gallery = [
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
