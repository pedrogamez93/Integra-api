<?php

namespace Meat\NovaPushNotification\Http\Controllers;

use App\District;
use App\Position;
use App\Post;
use App\Region;
use App\Release;
use App\SegmentedNotification;
use App\User;
use DateTime;
use DB;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Http\Client\Common\HttpMethodsClient;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OneSignal\Config as OneSignalConfig;
use OneSignal\OneSignal as OneSignalApi;

class PushNotificationController
{
    const TYPE_SETTLEMENT = 1;
    const TYPE_POST = 2;
    const TYPE_RELEASE = 3;
    const TYPE_LABOR_OFERTA = 4;

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function send(Request $request)
    {
        $objectId = null;
        $response = [];

        $rules = [
            'text' => 'required',
            'type' => 'required',
            'heading' => 'required',
            'datetime' => 'required'
        ];

        $customMessages = [
            'text.required' => 'El campo descripción es requerido.',
            'type.required' => 'El campo tipo es requerido.',
            'heading.required' => 'El campo nombre es requerido.',
            'datetime.required' => 'El campo fecha es requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if ($request->positions == null && $request->regions == null && $request->districts == null) {
            return response()->json(['massage' => 'Debe selecionar alguna segmentación'], 422);
        }

        date_default_timezone_set('America/Santiago');
        $date = date("Y-m-d H:i:s", strtotime($request->datetime));
        $dateFormat = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        $segmentedNotification = new SegmentedNotification();
        $segmentedNotification->datetime = $date;
        $segmentedNotification->type = $request->type;
        if (isset($request->text)) {
            $segmentedNotification->text = $request->text;
        }
        $segmentedNotification->headings = $request->heading;
        $districts = [];
        if ($request->districts) {
            $districts = District::whereIn('name', $request->districts)->pluck('code')->toArray();
            $districts = implode(",", $districts);
            $segmentedNotification->code_dependence = $districts;
        }
        $positions = [];
        if ($request->positions != null) {
            $positions = Position::whereIn('name', $request->positions)->pluck('code')->toArray();
            $positions = implode(",", $positions);
            $segmentedNotification->code_position = $positions;
        }
        if ($request->regions != null) {
            $regions = Region::whereIn('name', $request->regions)->pluck('code')->toArray();
            $regions = implode(",", $regions);
            $segmentedNotification->code_region = $regions;
        }

        if ($request->type == self::TYPE_POST) {
            $post = Post::where('id', $request->post_id)->first();
            $segmentedNotification->object_id = $post['id'];
        }

        if ($request->type == self::TYPE_SETTLEMENT) {
            $segmentedNotification->object_id = 0;
        }

        if ($request->type == self::TYPE_RELEASE) {
            $releasejObOffer = Release::where('id', $request->release_id)->where('is_job_offer', 0)->first();
            $segmentedNotification->object_id = $releasejObOffer['id'];
        }

        if ($request->type == self::TYPE_LABOR_OFERTA) {
            $release = Release::where('id', $request->jobOffer_id)->where('is_job_offer', 1)->first();
            $segmentedNotification->object_id = $release['id'];
        }
        $segmentedNotification->save();
        return response()->json(['data' => 'Notificación Programada, si desea eliminarla diríjase al módulo de segmentación de notificación', 'status' => 1], 200);
    }

    public function connectOnesignal()
    {
        $oneSignalConfig = new OneSignalConfig();
        $oneSignalConfig->setApplicationId(config('push_notifications.app_id'));
        $oneSignalConfig->setApplicationAuthKey(config('push_notifications.api_key'));

        $oneSignalClient = new HttpMethodsClient(
            new GuzzleAdapter(new GuzzleClient()),
            new GuzzleMessageFactory()
        );

        $oneSignalApi = new OneSignalApi(
            $oneSignalConfig,
            $oneSignalClient
        );
        return $oneSignalApi;
    }

    public function saveUserNotification($district, $positions, $region, $type, $objectId = null, $date)
    {
        $users = User::select('tipest as dependence_code', 'id', 'persk as code_position', 'werks as code_region');
        if ($type == self::TYPE_LABOR_OFERTA) {
            $users->where('is_notification_settlement', 1);
        }

        if ($type == self::TYPE_POST) {
            $users->where('is_notification_new', 1);
        }
        if ($district) {
            $users->where('tipest', $district->code);
        }
        if ($positions) {
            $users->where('persk', $positions->code);
        }
        if ($region) {
            $users->where('werks', $region->code);
        }
        $users = $users->groupBy('dependence_code', 'code_position', 'code_region', 'id')->get();
        $userArray = [];
        $i = 0;
        foreach ($users as $key => $user) {
            $i++;
            if ($i == 2000) {
                DB::table('notifications')->insert($userArray);
                $i = 0;
                $userArray = [];
            }
            $userArray[] = [
                'user_id' => $user->id,
                'object_id' => $objectId,
                'type' => $type,
                'datetime' => $date,
                'is_send_notification' => 0,
            ];
        }
        if (count($userArray) > 0) {
            DB::table('notifications')->insert($userArray);
        }
    }

    public function payloadNotification($request, $date, $filter, $templateArray)
    {
        $oneSignalApi = $this->connectOnesignal();
        $data = [
            'filters' => $filter,

            'contents' => [
                'en' => $request->text,
            ],
            'data' => $templateArray,
        ];

        //$data['send_after'] = $date;

        if (!empty($request->heading)) {
            $data['headings'] = [
                'en' => $request->heading,
            ];
        }
        $response = $oneSignalApi->notifications->add($data);
    }

    public function comparDistrict($districts, $positions, $regions, $type, $request, $date, $template)
    {
        $filterDistrict = [];
        $filterFisrst = [];
        $filterSecond = [];
        $results = [];
        $objectPriority = $this->orderPrioryty($positions, $regions, 'position_code', 'region_code');
        foreach ($districts as $district) {
            $filterDistrict = [
                "field" => "tag",
                "key" => "code_district",
                "relation" => "=",
                "value" => $district->code,
            ];
            $results[] = $filterDistrict;
            foreach ($objectPriority['objectFirst'] as $i => $dataFirst) {
                $filterFisrst = [
                    "field" => "tag",
                    "key" => $objectPriority['codeFirst'],
                    "relation" => "=", "value" => $dataFirst->code,
                ];
                $results[] = $filterFisrst;

                if ($i != 0) {
                    $filterFisrst = [
                        "operator" => "OR",
                        "field" => "tag", "key" => $objectPriority['codeFirst'], "relation" => "=", "value" => $dataFirst->code,
                    ];

                }

                foreach ($objectPriority['objectSecond'] as $j => $dataSecond) {
                    $filterSecond = [
                        "field" => "tag",
                        "key" => $objectPriority['codeSecond'],
                        "relation" => "=", "value" => $dataSecond->code,
                    ];

                    if ($j != 0) {
                        $filterSecond = [
                            "operator" => "OR",
                            $results,
                            "field" => "tag",
                            "key" => $objectPriority['codeSecond'],
                            "relation" => "=", "value" => $dataSecond->code,
                        ];
                        $results[] = $filterSecond;
                    } else {
                        $results[] = $filterSecond;
                    }
                }
                if ($type == self::TYPE_SETTLEMENT) {
                    $results[] = ["field" => "tag", "key" => "is_notification_settlement", "relation" => "=", "value" => true];
                }

                if ($type == self::TYPE_POST) {
                    $results[] = ["field" => "tag", "key" => "is_notification_new", "relation" => "=", "value" => true];
                }
                $this->payloadNotification($request, $date, $results, $template);
                $results = [];
            }
        }
    }

    public function filterNotification($regions, $district, $positions, $request, $date, $template)
    {
        $filterPosition = [];
        $filterRegion = [];
        $filterPosition = [];
        $results = [];
        if ($request->positions) {
            $results[] = [
                "field" => "tag", "key" => "position_code", "relation" => "=", "value" => $positions->code,
            ];
        }

        if ($request->regions) {
            $results[] = [
                "field" => "tag", "key" => "region_code", "relation" => "=", "value" => $regions->code,
            ];
        }

        if ($request->districts) {
            $results[] = [
                "field" => "tag", "key" => "code_district", "relation" => "=", "value" => $district->code,
            ];
        }

        if ($request->type == self::TYPE_SETTLEMENT) {
            $results[] = ["field" => "tag", "key" => "is_notification_settlement", "relation" => "=", "value" => true];
        }

        if ($request->type == self::TYPE_POST) {
            $results[] = ["field" => "tag", "key" => "is_notification_new", "relation" => "=", "value" => true];
        }
        //dd($results);
        $this->payloadNotification($request, $date, $results, $template);
    }

    public function comparRegion($districts, $positions, $regions, $type, $request, $date, $template)
    {
        $filterRegion = [];
        $filterFisrst = [];
        $filterPosition = [];
        $results = [];
        $objectPriority = $this->orderPrioryty($districts, $positions, 'code_district', 'position_code');
        foreach ($regions as $region) {
            $filterRegion = [
                "field" => "tag", "key" => "region_code", "relation" => "=", "value" => $region->code,
            ];
            $results[] = $filterRegion;

            foreach ($objectPriority['objectFirst'] as $i => $dataFirst) {
                $filterFisrst = [
                    "field" => "tag", "key" => $objectPriority['codeFirst'], "relation" => "=", "value" => $dataFirst->code,
                ];

                if ($i != 0) {
                    $filterFisrst = [
                        ["operator" => "OR"],
                        $filterRegion,
                        "field" => "tag", "key" => $objectPriority['codeFirst'], "relation" => "=", "value" => $dataFirst->code,
                    ];
                }

                $results[] = $filterFisrst;

                foreach ($objectPriority['objectSecond'] as $j => $dataSecond) {
                    $filterSecond = [
                        "field" => "tag", "key" => $objectPriority['codeSecond'], "relation" => "=", "value" => $dataSecond->code,
                    ];

                    if ($j != 0) {
                        $filterSecond = [
                            ["operator" => "OR"],
                            $filterFisrst,
                            "field" => "tag", "key" => $objectPriority['codeSecond'], "relation" => "=", "value" => $dataSecond->code,
                        ];
                    }
                    $results[] = $filterSecond;
                }
                if ($type == self::TYPE_SETTLEMENT) {
                    $results[] = ["field" => "tag", "key" => "is_notification_settlement", "relation" => "=", "value" => true];
                }

                if ($type == self::TYPE_POST) {
                    $results[] = ["field" => "tag", "key" => "is_notification_new", "relation" => "=", "value" => true];
                }
                dd($results);
            }
            dd($results);
            $results = [];
            //$this->payloadNotification($request, $date, $results, $template);
        }
    }

    public function orderPrioryty($objectFirst, $objectSecond, $codeFirst, $codeSecond)
    {
        if ($objectFirst >= $objectSecond) {
            return [
                'objectFirst' => $objectFirst,
                'objectSecond' => $objectSecond,
                'codeFirst' => $codeFirst,
                'codeSecond' => $codeSecond,
            ];
        }
        return [
            'objectFirst' => $objectSecond,
            'objectSecond' => $objectFirst,
            'codeFirst' => $codeSecond,
            'codeSecond' => $codeFirst,
        ];
    }

    public function getDistrict()
    {
        return response()->json(['data' => District::pluck('name')]);
    }

    public function getPosition()
    {
        return response()->json(['data' => Position::pluck('name')]);
    }
    public function getRegions()
    {
        return response()->json(['data' => Region::pluck('name')]);
    }

    public function getPost()
    {
        return response()->json(['data' => Post::get()]);
    }

    public function getRelease()
    {
        return response()->json(['data' => Release::where('is_job_offer', 0)->get()]);
    }
    public function getJobOffers()
    {
        return response()->json(['data' => Release::where('is_job_offer', 1)->get()]);
    }
}
