<?php

namespace App\Http\Controllers;

use App\Helpers\UtilHelper;
use App\Release;
use App\Http\Controllers\LogUserController;

class ReleaseController extends Controller
{
    public function index()
    {
        $user = auth('api')->user();
        
        $util = new LogUserController;
        $util->store('Comunicados');

        $utilHelper = new UtilHelper();
        if ($user == null or (isset($user->is_public) && $user->is_public)) {
            $releases = Release::select('releases.*')
                ->where('is_job_offer', 0)
                ->orderBy('datetime', 'desc')
                ->where('releases.is_public', 1)
                ->distinct()
                ->paginate(5);
        } else {
            $releases = \Cache::remember($user->persk . $user->werks . $user->tipest . $_GET['page'] . 'releases_index', 60 * 60, function () use ($utilHelper, $user) {
                $region = UtilHelper::QueryRegion($user);
                return Release::select('releases.*')
                    ->join('release_districts', 'release_districts.release_id', 'releases.id')
                    ->join('districts', 'release_districts.district_id', 'districts.id')
                    ->join('release_positions', 'release_positions.release_id', 'releases.id')
                    ->join('positions', 'release_positions.position_id', 'positions.id')
                    ->join('release_regions', 'release_regions.release_id', 'releases.id')
                    ->join('regions', 'release_regions.region_id', 'regions.id')
                    ->where('datetime', '<=', $utilHelper->getDateCurrent())
                    ->where('releases.is_private', 1)
                    ->where('positions.code', $user->persk)
                    ->whereRaw($region)
                    ->where('districts.code', $user->tipest)
                    ->where('is_job_offer', 0)
                    ->orderBy('datetime', 'desc')
                    ->distinct()
                    ->paginate(5);
            });
        }

        if (!$releases) {
            return response()->json(
                [
                    'statusCode' => 404, 'message' => 'Página no encontrada',
                    'is_logged' => auth('api')->user() ? 1 : 0,
                ],
                404
            );
        }

        foreach ($releases as $value) {
            $value->img = null;
            $date = \Carbon\Carbon::parse($value->datetime)->format('d · m · Y');
            $value->date = $date;
            $value->slug = $value->slug;
        }

        return response()->json([
            'is_logged' => auth('api')->user() ? 1 : 0,
            'data' => [
                'posts' => $releases,
                'status' => true,
            ],
        ]);
    }

    public function jobOffers()
    {
        $user = auth('api')->user();
        $utilHelper = new UtilHelper();

        if ($user == null or (isset($user->is_public)) && $user->is_public) {
            $releases = Release::select('releases.*')
                ->where('datetime', '<=', $utilHelper->getDateCurrent())
                ->where('is_job_offer', 1)
                ->where('releases.is_public', 1)
                ->orderBy('datetime', 'desc')->paginate(5);
        } else {
            $releases = \Cache::remember($user->persk . $user->werks . $user->tipest . $_GET['page'] . 'release_offers', 60 * 60, function () use ($utilHelper, $user) {
                $region = UtilHelper::QueryRegion($user);
                return Release::select('releases.*')
                    ->join('release_districts', 'release_districts.release_id', 'releases.id')
                    ->join('districts', 'release_districts.district_id', 'districts.id')
                    ->join('release_positions', 'release_positions.release_id', 'releases.id')
                    ->join('positions', 'release_positions.position_id', 'positions.id')
                    ->join('release_regions', 'release_regions.release_id', 'releases.id')
                    ->join('regions', 'release_regions.region_id', 'regions.id')
                    ->where('datetime', '<=', $utilHelper->getDateCurrent())
                    ->where('releases.is_private', 1)
                    ->where('positions.code', $user->persk)
                    ->whereRaw($region)
                    ->where('districts.code', $user->tipest)
                    ->where('datetime', '<=', $utilHelper->getDateCurrent())
                    ->where('is_job_offer', 1)
                    ->orderBy('datetime', 'desc')
                    ->distinct()
                    ->paginate(5);
            });
        }

        if (!$releases) {
            return response()->json(['statusCode' => 404, 'message' => 'Página no encontrada'], 404);
        }

        foreach ($releases as $value) {
            $value->img = null;
            $date = \Carbon\Carbon::parse($value->datetime)->format('d · m · Y');
            $value->date = $date;
            $value->slug = $value->slug;
        }

        return response()->json([
            'is_logged' => auth('api')->user() ? 1 : 0,
            'data' => [
                'posts' => $releases,
                'status' => true,
            ],
        ]);
    }

    public function show($slug)
    {
        $user = auth('api')->user();
        $utilHelper = new UtilHelper();
        $linkArray = [];
        $lastReleaseArray = [];

        if ($user == null or (isset($user->is_public)) && $user->is_public) {
            $release = Release::with(['benefitPost'])->select('releases.*')
                ->where('datetime', '<=', $utilHelper->getDateCurrent())
                ->where('releases.is_public', 1)
                ->where('slug', $slug)
                ->first();

            $latRelease = Release::select('releases.*')
                ->where('datetime', '<=', $utilHelper->getDateCurrent())
                ->where('releases.is_public', 1)
                ->limit(2)->get();
        } else {
            $region = UtilHelper::QueryRegion($user);
            $latRelease = \Cache::remember($user->persk . $user->werks . $user->tipest . 'release_show_limit', 60 * 60, function () use ($utilHelper, $user, $region) {
                return Release::select('releases.*')
                    ->join('release_districts', 'release_districts.release_id', 'releases.id')
                    ->join('districts', 'release_districts.district_id', 'districts.id')
                    ->join('release_positions', 'release_positions.release_id', 'releases.id')
                    ->join('positions', 'release_positions.position_id', 'positions.id')
                    ->join('release_regions', 'release_regions.release_id', 'releases.id')
                    ->join('regions', 'release_regions.region_id', 'regions.id')
                    ->where('datetime', '<=', $utilHelper->getDateCurrent())
                    ->where('releases.is_private', 1)
                    ->where('positions.code', $user->persk)
                    ->whereRaw($region)
                    ->where('districts.code', $user->tipest)
                    ->limit(2)
                    ->distinct()
                    ->get();
            });

            $release = \Cache::remember($user->persk . $user->werks . $user->tipest . 'release_show' . $slug, 60 * 60, function () use ($utilHelper, $user, $slug, $region) {
                return Release::with(['benefitPost'])->select('releases.*')
                    ->join('release_districts', 'release_districts.release_id', 'releases.id')
                    ->join('districts', 'release_districts.district_id', 'districts.id')
                    ->join('release_positions', 'release_positions.release_id', 'releases.id')
                    ->join('positions', 'release_positions.position_id', 'positions.id')
                    ->join('release_regions', 'release_regions.release_id', 'releases.id')
                    ->join('regions', 'release_regions.region_id', 'regions.id')
                    ->where('datetime', '<=', $utilHelper->getDateCurrent())
                    ->where('releases.is_private', 1)
                    ->where('positions.code', $user->persk)
                    ->whereRaw($region)
                    ->where('districts.code', $user->tipest)
                    ->where('releases.slug', $slug)
                    ->first();
            });
        }

        if (!$release) {
            return response()->json(
                array(
                    'is_logged' => auth('api')->user() ? 1 : 0,
                    'statusCode' => 404,
                    'message' => 'Página no encontrada',
                ),
                404
            );
        }

        foreach ($release->linkRelease as $link) {
            $linkArray[] = [
                'label' => $link->name,
                'icon' => $link->icon,
                'url' => $link->url,
                'target' => $link->target,
            ];
        }
        unset($release->linkRelease);

        $release->links = $linkArray;

        foreach ($latRelease as $lastNew) {
            $dataImage = null;
            $lastReleaseArray[] = [
                'id' => $lastNew->id,
                'slug' => $lastNew->slug,
                'title' => $lastNew->title,
                'date' => date("d · m · Y", strtotime($lastNew->datetime)),
                'icon' => $lastNew->icon,

            ];
        }

        $release->post_gallery = [];
        $release->featured_img = [];
        $release->featured_img = [
            'alt' => $release->getFirstMediaUrl('image_release') ? $release->getFirstMedia('image_release')->name : '',
            'src' => $release->getFirstMediaUrl('image_release') ? env('APP_URL') . $release->getFirstMediaUrl('image_release') : '',
        ];

        foreach ($release->getMedia('image_release') as $media) {
            $release->post_gallery = [
                'id' => $media->id,
                'alt' => $media->name,
                'src' => $media->getUrl(),
            ];
        }

        $date = date("d · m · Y", strtotime($release->datetime));
        $release->date = $date;
        unset($release->datetime);

     //   $benefitsArray = BenefitController::index();

        return response()->json([
            'is_logged' => auth('api')->user() ? 1 : 0,
            'data' => $release,
            'releases' => array(
                'data' => $lastReleaseArray,
            ),
            'status' => true,

        ]);
    }
}