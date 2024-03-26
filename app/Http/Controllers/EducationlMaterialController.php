<?php

namespace App\Http\Controllers;

use App\EducationalMaterial;
use App\Helpers\UtilHelper;
use App\VideoEducational;
use Illuminate\Support\Facades\Cache;

class EducationlMaterialController extends Controller
{
    public function __construct()
    {
        $this->utilHelper = new UtilHelper();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexActivity($category)
    {
        $page = request()->query('page', 1);
        $educationalMaterial = Cache::remember('em_index_activity_' . $page . $category, 60 * 60, function () {
            $educationalMaterial = EducationalMaterial::where('is_activity', 1)
                ->orderBy('datetime', 'desc')
                ->where('datetime', '<=', $this->utilHelper->getDateCurrent())
                ->where('category_id', $category)
                ->paginate(5);

            foreach ($educationalMaterial as $key => $value) {
                $value->img = [
                    "src" => $value->getFirstMedia('image_educational_material') ? env('APP_URL') . $value->getFirstMediaUrl('image_educational_material', 'thumb') : '',
                    "alt" => $value->getFirstMedia('image_educational_material') ? $value->getFirstMedia('image_educational_material')->name : '',
                ];

                $date = \Carbon\Carbon::parse($value->datetime)->format('d · m · Y');
                unset($value->datetime);
                $value->date = $date;
                unset($value->media);
                unset($value->src);
            }

            return $educationalMaterial;
        });

        return response()->json([
            'is_logged' => auth('api')->user() ? 1 : 0,
            'data' => [
                'educational_material' => $educationalMaterial,
                'status' => true,
            ],
        ]);
    }

    public function showActivity($slug)
    {
        $educationalMaterial = EducationalMaterial::whereIn('is_activity', [1, 0])
            ->where('slug', $slug)
            ->firstOrFail();
        $documents = [];
        $educationalMaterial->featured_img = [
            "src" => $educationalMaterial->getFirstMedia('image_educational_material') ? env('APP_URL') . $educationalMaterial->getFirstMediaUrl('image_educational_material') : '',
            "alt" => $educationalMaterial->getFirstMedia('image_educational_material') ? $educationalMaterial->getFirstMedia('image_educational_material')->name : '',
        ];
        foreach (json_decode($educationalMaterial->documents) as $key => $document) {
            if ($document->attributes->is_video) {
                $keyDocument = 'video';
                $video = isset($document->attributes->video) ? $document->attributes->video : '';
                $value = $video;
            } else {
                $keyDocument = 'url';
                $docuemnt = isset($document->attributes->document) ? $document->attributes->document : '';
                $value = env('APP_URL') . "/storage/{$docuemnt}";
            }
            $documents[] = [
                'id' => $key,
                'icon' => $document->attributes->icon,
                'title' => $document->attributes->title,
                $keyDocument => $value,
            ];
        }
        $educationalMaterial->documents = $documents;
        $date = \Carbon\Carbon::parse($educationalMaterial->datetime)->format('d · m · Y');
        unset($educationalMaterial->datetime);
        $educationalMaterial->date = $date;
        unset($educationalMaterial->media);
        unset($educationalMaterial->src);

        return response()->json([
            'is_logged' => auth('api')->user() ? 1 : 0,
            'data' => $educationalMaterial,
        ]);
    }

    public function indexWorkshop()
    {
        $page = request()->query('page', 1);
        $educationalMaterial = Cache::remember('em_index_workshop_' . $page, 60 * 60, function () {
            $educationalMaterial = EducationalMaterial::where('is_activity', 0)
                ->orderBy('datetime', 'desc')
                ->where('datetime', '<=', $this->utilHelper->getDateCurrent())
                ->paginate(5);
            $documents = [];
            foreach ($educationalMaterial as $key => $value) {
                $value->img = [
                    "src" => $value->getFirstMedia('image_educational_material') ? env('APP_URL') . $value->getFirstMediaUrl('image_educational_material') : '',
                    "alt" => $value->getFirstMedia('image_educational_material') ? $value->getFirstMedia('image_educational_material')->name : '',
                ];

                $date = \Carbon\Carbon::parse($value->datetime)->format('d · m · Y');
                unset($value->datetime);
                $value->date = $date;
                unset($value->media);
                unset($value->src);
            }

            return $educationalMaterial;
        });

        return response()->json([
            'is_logged' => auth('api')->user() ? 1 : 0,
            'data' => [
                'educational_material' => $educationalMaterial,
                'status' => true,
            ],
        ]);
    }

    public function showWorkshop($slug)
    {
        $educationalMaterial = EducationalMaterial::where('is_activity', 0)
            ->where('slug', $slug)
            ->firstOrFail();
        $documents = [];
        $educationalMaterial->featured_img = [
            "src" => $educationalMaterial->getFirstMedia('image_educational_material') ? env('APP_URL') . $educationalMaterial->getFirstMediaUrl('image_educational_material') : '',
            "alt" => $educationalMaterial->getFirstMedia('image_educational_material') ? $educationalMaterial->getFirstMedia('image_educational_material')->name : '',
        ];
        foreach (json_decode($educationalMaterial->documents) as $key => $document) {
            if ($document->attributes->is_video) {
                $keyDocument = 'video';
                $video = isset($document->attributes->video) ? $document->attributes->video : '';
                $value = $video;
            } else {
                $keyDocument = 'url';
                $docuemnt = isset($document->attributes->document) ? $document->attributes->document : '';
                $value = env('APP_URL') . "/storage/{$docuemnt}";
            }
            $documents[] = [
                'id' => $key,
                'icon' => $document->attributes->icon,
                'title' => $document->attributes->title,
                $keyDocument => $value,
            ];
        }
        $educationalMaterial->documents = $documents;
        $date = \Carbon\Carbon::parse($educationalMaterial->datetime)->format('d · m · Y');
        unset($educationalMaterial->datetime);
        $educationalMaterial->date = $date;
        unset($educationalMaterial->media);
        unset($educationalMaterial->src);

        return response()->json([
            'is_logged' => auth('api')->user() ? 1 : 0,
            'data' => $educationalMaterial,
        ]);
    }

    public function video()
    {
        $page = request()->query('page', 1);
        $videoResponse = Cache::remember('em_index_videos_' . $page, 60 * 60, function () {
            return VideoEducational::where('datetime', '<=', $this->utilHelper->getDateCurrent())
                ->orderBy('datetime', 'desc')
                ->paginate(5);
        });

        return response()->json([
            'is_logged' => auth('api')->user() ? 1 : 0,
            'data' => [
                'videos' => $videoResponse,
                'status' => true,
            ],
        ]);
    }
}
