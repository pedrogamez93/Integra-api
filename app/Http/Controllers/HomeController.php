<?php

namespace App\Http\Controllers;

use App\Constribution;
use App\Helpers\UtilHelper;
use App\Post;
use App\Release;
use App\Survey;
use App\Termn;
use App\User;
use App\welcomePage;
use Carbon\Carbon;
use Spatie\Once\Cache;
use App\Http\Controllers\LogUserController;

class HomeController extends Controller
{
    

    public function termn()
    {
        return response()->json(
            [
                'is_logged' => auth('api')->user() ? 1 : 0,
                'data' => Termn::where('is_home', '!=', 0)->first(),
            ],
            200
        );
    }

    public function registerFull()
    {
        $user = auth()->user();
        $getUser = User::find($user->id);
        $getUser->full_register = 1;
        $getUser->save();
        return response()->json([
            'is_logged' => auth('api')->user() ? 1 : 0,
            'data' => $getUser,
        ], 200);
    }

    public function updateUserTermCondition()
    {
        date_default_timezone_set('America/Santiago');
        $user = auth()->user();
        $getUser = User::find($user->id);
        $getUser->is_termn_home = 1;
        $getUser->updated_at_termn_service_home = date("Y-m-d H:i:s");
        $getUser->save();
        return response()->json(
            [
                'data' => $getUser,
                'is_logged' => auth('api')->user() ? 1 : 0,
            ],
            200
        );
    }

    public function isCheckTermn()
    {
        $user = auth()->user();

        if ($termn) {
            return response()->json(
                [
                    'is_termn' => 1,
                    'is_logged' => auth('api')->user() ? 1 : 0,
                ],
                200
            );
        }
        return response()->json(['is_termn' => 0], 200);
    }

    public function index()
    {
        $utilHelper = new UtilHelper();
        $user = auth('api')->user();
        $pageArray = [];
        $news = [];
        $releaseArray = [];
        $newArray = [];

        $now = Carbon::now();

        $util = new LogUserController;
        $util->store('home');

        $welcomePage = welcomePage::
        where('start_date', '<=', $now)
        ->where('end_date', '>=', $now)
        ->get();

        $certificate = welcomePage::
        where('type_certificate', 1)
        ->orderBy('created_at', 'asc')
        ->get();

        if (!$welcomePage) {
            return Response::json(
                array(
                    'is_logged' => auth('api')->user() ? 1 : 0,
                    'statusCode' => 404,
                    'message' => 'Página no encontrada',
                ),
                404
            );
        }
        if ($user == null or (isset($user->is_public)) && $user->is_public) {
            $news = Post::select('posts.*')
                ->where('datetime', '<=', $utilHelper->getDateCurrent())
                ->where('is_public', 1)
                ->orderBy('datetime', 'desc')
                ->limit(2)
                ->get();
            $releases = Release::select('releases.*')
                ->where('datetime', '<=', $utilHelper->getDateCurrent())
                ->where('is_public', 1)
                ->limit(2)
                ->orderBy('datetime', 'desc')
                ->distinct()
                ->get();
        } else {
            $news = \Cache::remember($user->persk . $user->werks . $user->tipest . 'post_home', 60 * 60, function () use ($utilHelper, $user) {
                $region = UtilHelper::QueryRegion($user);
                return Post::select('posts.*')
                    ->join('post_districts', 'post_districts.post_id', 'posts.id')
                    ->join('districts', 'post_districts.district_id', 'districts.id')
                    ->join('post_positions', 'post_positions.post_id', 'posts.id')
                    ->join('positions', 'post_positions.position_id', 'positions.id')
                    ->join('post_regions', 'post_regions.post_id', 'posts.id')
                    ->join('regions', 'post_regions.region_id', 'regions.id')
                    ->where('datetime', '<=', $utilHelper->getDateCurrent())
                    ->where('datetime', '<=', $utilHelper->getDateCurrent())
                    ->where('positions.code', $user->persk)
                    ->whereRaw($region)
                    ->where('districts.code', $user->tipest)
                    ->where('posts.is_private', 1)
                    ->limit(2)
                    ->orderBy('datetime', 'desc')
                    ->distinct()
                    ->get();
            });
            $releases = \Cache::remember($user->persk . $user->werks . $user->tipest . 'release_home', 1, function () use ($utilHelper, $user) {
                $region = UtilHelper::QueryRegion($user);
                return Release::select('releases.*')
                    ->join('release_districts', 'release_districts.release_id', 'releases.id')
                    ->join('districts', 'release_districts.district_id', 'districts.id')
                    ->join('release_positions', 'release_positions.release_id', 'releases.id')
                    ->join('positions', 'release_positions.position_id', 'positions.id')
                    ->join('release_regions', 'release_regions.release_id', 'releases.id')
                    ->join('regions', 'release_regions.region_id', 'regions.id')
                    ->where('datetime', '<=', $utilHelper->getDateCurrent())
                    ->where('datetime', '<=', $utilHelper->getDateCurrent())
                    ->where('positions.code', $user->persk)
                    ->whereRaw($region)
                    ->where('districts.code', $user->tipest)
                    ->where('releases.is_private', 1)
                    ->limit(2)
                    ->orderBy('datetime', 'desc')
                    ->distinct()
                    ->get();
            });
        }

        if (!$news) {
            return Response::json(
                array(
                    'is_logged' => auth('api')->user() ? 1 : 0,
                    'statusCode' => 404,
                    'message' => 'Página no encontrada',
                ),
                404
            );
        }

        if (!$releases) {
            return Response::json(
                array(
                    'is_logged' => auth('api')->user() ? 1 : 0,
                    'statusCode' => 404,
                    'message' => 'Página no encontrada',
                ),
                404
            );
        }

        foreach ($news as $value) {
            $dataImage = [
                'src' => $value->getFirstMedia('image_new') ? env('APP_URL') . $value->getFirstMediaUrl('image_new') : '',
                'alt' => $value->getFirstMedia('image_new')->name ? $value->getFirstMedia('image_new')->name : '',
            ];
            $newArray[] = [
                'id' => $value->id,
                'slug' => $value->slug,
                'img' => $dataImage,
                'title' => $value->title,
                'date' => date("d · m · Y", strtotime($value->datetime)),
            ];
        }
        $surveys = [];
        $date = Carbon::now();
        $results = [];
        if ($user) {
            $surveys = \Cache::remember($user->persk . $user->werks . $user->tipest . 'survey_index', 60 * 60, function () use ($utilHelper, $user, $date) {
                $region = UtilHelper::QueryRegion($user);
                return Survey::select('surveys.*')
                    ->orderBy('date', 'desc')->join('survey_districts', 'survey_districts.survey_id', 'surveys.id')
                    ->join('districts', 'survey_districts.district_id', 'districts.id')
                    ->join('survey_positions', 'survey_positions.survey_id', 'surveys.id')
                    ->join('positions', 'survey_positions.position_id', 'positions.id')
                    ->join('survey_regions', 'survey_regions.survey_id', 'surveys.id')
                    ->join('regions', 'survey_regions.region_id', 'regions.id')
                    ->where('positions.code', $user->persk)
                    ->whereRaw($region)
                    ->where('districts.code', $user->tipest)
                    ->where('date', '<=', $date->toDateTimeString())
                    ->where('end_date', '>=', $date->toDateTimeString())
                    ->orderBy('date', 'desc')
                    ->distinct()
                    ->get();
            });
            $surveysUser = Survey::select('surveys.*')
                ->orderBy('date', 'desc')
                ->join('user_rut_lists', 'user_rut_lists.id', 'surveys.user_rut_list_id')
                ->join('survey_users', 'survey_users.user_rut_list_id', 'user_rut_lists.id')
                ->join('users', 'survey_users.user_id', 'users.id')
                ->where('users.rut', $user->rut)
                ->where('date', '<=', $date->toDateTimeString())
                ->where('end_date', '>=', $date->toDateTimeString())
                ->orderBy('date', 'desc')
                ->distinct()
                ->get();
            if (count($surveys) > 0 && count($surveysUser) > 0) {
                $results = array_merge($surveysUser->toArray(), $surveys->toArray());
                $results = array_map("unserialize", array_unique(array_map("serialize", $results)));
            } elseif (count($surveys) > 0) {
                $results = $surveys;
            } else {
                $results = $surveysUser;
            }
        }
        
        foreach ($welcomePage as $value) {
            if($value->view_home) {
                $pageArray[] = [
                    'title' => $value->title,
                    'icon' => $value->icon,
                    'slug' => $value->slug,
                    'class' => $value->class,
                    'view_home'=> $value->view_home,
                    'type_certificate'=> $value->type_certificate,
                    'is_public' => $value->is_public
                ];
            }
            if($value->type_certificate) {
                $buttonArray[] = [
                    'title' => $value->title,
                    'icon' => $value->icon,
                    'slug' => $value->slug,
                    'class' => $value->class,
                    'view_home'=> $value->view_home,
                    'type_certificate'=> $value->type_certificate,
                    'is_public' => $value->is_public
                ];
            }
        }
        $buttonArray = [];
        foreach ($certificate as $value) {
            if($value->type_certificate) {
                $buttonArray[] = [
                    'title' => $value->title,
                    'icon' => $value->icon,
                    'slug' => $value->slug,
                    'class' => $value->class,
                    'view_home'=> $value->view_home,
                    'type_certificate'=> $value->type_certificate,
                    'is_public' => $value->is_public
                ];
            }
        }

        foreach ($releases as $value) {
            $releaseArray[] = [
                'id' => $value->id,
                'slug' => $value->slug,
                'title' => $value->title,
                'icon' => $value->icon,
                'date' => date("d · m · Y", strtotime($value->datetime)),
            ];
        }

        $date = Carbon::now();

        $alert = Constribution::where('init_date', '<=', $date->toDateTimeString())
            ->where('end_date', '>=', $date->toDateTimeString())
            ->first();

        return response()->json([
            'is_logged' => auth('api')->user() ? 1 : 0,
            'alert' => $alert ? true : false,
            'alert_message' => $alert ? $alert->subtitle : '',
            'data' => [
                'button' => array(
                    'data' => $buttonArray,
                ),
                'today' => array(
                    'data' => $pageArray,
                ),
                'releases' => array(
                    'data' => $releaseArray,
                ),
                'posts' => array(
                    'data' => $newArray,
                ),
                'survey' => array(
                    'data' => $results,
                ),
                'status' => true,
            ],
        ]);
    }
}
