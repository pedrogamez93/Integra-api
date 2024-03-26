<?php

namespace App\Http\Controllers;

use App\Helpers\UtilHelper;
use App\Survey;

class SurveysController extends Controller
{

    public function index()
    {
        $user = auth('api')->user();
        $utilHelper = new UtilHelper();
        $surveys = \Cache::remember($user->persk . $user->werks . $user->tipest . $_GET['page'] . 'survey_index', 60 * 60, function () use ($utilHelper, $user) {
            return Survey::select('surveys.*')
                ->orderBy('date', 'desc')->join('survey_districts', 'survey_districts.survey_id', 'surveys.id')
                ->join('districts', 'survey_districts.district_id', 'districts.id')
                ->join('survey_positions', 'survey_positions.survey_id', 'surveys.id')
                ->join('positions', 'survey_positions.position_id', 'positions.id')
                ->join('survey_regions', 'survey_regions.survey_id', 'surveys.id')
                ->join('regions', 'survey_regions.region_id', 'regions.id')
                ->where('positions.code', $user->persk)
                ->where('regions.code', $user->werks)
                ->where('districts.code', $user->tipest)
                ->where('date', '<=', $utilHelper->getDateCurrent())
                ->orderBy('date', 'desc')->paginate(5);
        });

        return response()->json([
            'is_logged' => auth('api')->user() ? 1 : 0,
            'data' => [
                'survey' => $surveys,
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
        $survey = Survey::where('slug', $slug)->first();

        if (!$survey) {
            return response()->json(
                array(
                    'is_logged' => auth('api')->user() ? 1 : 0,
                    'statusCode' => 404,
                    'message' => 'Página no encontrada',
                ),
                404
            );
        }
        return response()->json([
            'is_logged' => auth('api')->user() ? 1 : 0,
            'data' => $survey,
            'status' => true,
        ]);
    }
}