<?php

namespace App\Console\Commands;

use App\Helpers\UtilHelper;
use App\Notification;
use App\Post;
use App\Release;
use App\SegmentedNotification;
use App\User;
use Carbon\Carbon;
use DateTime;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Http\Client\Common\HttpMethodsClient;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use OneSignal\Config as OneSignalConfig;
use OneSignal\OneSignal as OneSignalApi;

class SendNotification extends Command
{

    const TYPE_SETTLEMENT = 1;
    const TYPE_POST = 2;
    const TYPE_RELEASE = 3;
    const TYPE_LABOR_OFERTA = 4;

    protected $signature = 'notification:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        date_default_timezone_set('America/Santiago');
        $usetHelper = new UtilHelper();
        $currentDate = date("Y-m-d H:i", strtotime(Carbon::now()));
        $segmentedNotification = SegmentedNotification::whereRaw("DATE_FORMAT(datetime,'%Y-%m-%d %H:%i') <= '$currentDate' and is_send_notification = 0")->get();
        $objectId = 0;
        if (count($segmentedNotification) > 0) {
            foreach ($segmentedNotification as $value) {
                $utiHelper = new UtilHelper();
                $date = date("Y-m-d H:i:s", strtotime($value->datetime));
                $dateFormat = DateTime::createFromFormat('Y-m-d H:i:s', $date);

                if ($value->type == self::TYPE_POST) {
                    $post = Post::where('id', $value->object_id)->first();
                    $objectId = $post['id'];
                    $templateArray = [
                        'template' => 'noticias',
                        'slug' => $post['slug'],
                    ];
                }

                if ($value->type == self::TYPE_SETTLEMENT) {
                    $templateArray = [
                        'type' => $value->type,
                        'period' => date("Ym", strtotime(date('Ym') . "-1 month")),
                        'template' => 'liquidaciones-de-sueldo',
                    ];
                }

                if ($value->type == self::TYPE_RELEASE) {
                    $releasejObOffer = Release::where('id', $value->object_id)->where('is_job_offer', 0)->first();
                    $objectId = $releasejObOffer['id'];

                    $templateArray = [
                        'template' => 'comunicados',
                        'slug' => $releasejObOffer['slug'],
                    ];
                }

                if ($value->type == self::TYPE_LABOR_OFERTA) {
                    $release = Release::where('id', $value->object_id)->where('is_job_offer', 1)->first();
                    $objectId = $release['id'];
                    $templateArray = [
                        'template' => 'ofertas',
                        'slug' => $release['slug'],
                    ];
                }
                Notification::whereRaw("DATE_FORMAT(datetime,'%Y-%m-%d %H:%i') <= '$currentDate' and is_send_notification = 0")
                    ->update(['is_send_notification' => 1]);

                SegmentedNotification::whereRaw("DATE_FORMAT(datetime,'%Y-%m-%d %H:%i') <= '$currentDate' and is_send_notification = 0")
                    ->update(['is_send_notification' => 1]);

                $filter = $this->filterNotification($value, $dateFormat, $templateArray);
                $this->saveUserNotification($value, $value->type, $objectId, $date);
            }
            return response()->json(['data' => 'Notificación enviada', 'status' => 1], 200);
        }
    }

    public function payloadNotification($segmentedNotification, $date, $filter, $templateArray)
    {
        $oneSignalApi = $this->connectOnesignal();
        $data = [
            'filters' => $filter,

            'contents' => [
                'en' => $segmentedNotification->text,
            ],
            'data' => $templateArray,
        ];

        if (!empty($segmentedNotification->heading)) {
            $data['headings'] = [
                'en' => $segmentedNotification->heading,
            ];
        }
        $response = $oneSignalApi->notifications->add($data);
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

    public function filterNotification($segmentedNotification, $date, $template)
    {
        $filterPosition = [];
        $filterRegion = [];
        $filterPosition = [];
        $results = [];
        $users = User::select(DB::raw($this->validSelect($segmentedNotification)));
        if ($segmentedNotification->code_dependence) {
            $segmentedNotification->code_dependence = explode(',', $segmentedNotification->code_dependence);
            $users->whereIn('tipest', $segmentedNotification->code_dependence);
        }
        if ($segmentedNotification->code_position) {
            $segmentedNotification->code_position = explode(',', $segmentedNotification->code_position);
            $users->whereIn('persk', $segmentedNotification->code_position);
        }
        if ($segmentedNotification->code_region) {
            $segmentedNotification->code_region = explode(',', $segmentedNotification->code_region);
            $users->whereIn('werks', $segmentedNotification->code_region);
        }
        $users = $users->distinct()->get();
        foreach ($users as $value) {
            if ($segmentedNotification->code_position) {
                $results[] = [
                    "field" => "tag", "key" => "position_code", "relation" => "=", "value" => $value->code_position,
                ];
            }

            if ($segmentedNotification->code_region) {
                $results[] = [
                    "field" => "tag", "key" => "region_code", "relation" => "=", "value" => $value->code_region,
                ];
            }

            if ($segmentedNotification->code_dependence) {
                $results[] = [
                    "field" => "tag", "key" => "code_district", "relation" => "=", "value" => $value->dependence_code,
                ];
            }

            if ($value->type == self::TYPE_SETTLEMENT) {
                $results[] = ["field" => "tag", "key" => "is_notification_settlement", "relation" => "=", "value" => true];
            }

            if ($value->type == self::TYPE_POST) {
                $results[] = ["field" => "tag", "key" => "is_notification_new", "relation" => "=", "value" => true];
            }
            $this->payloadNotification($segmentedNotification, $date, $results, $template);
            $results = [];
        }
    }

    public function validSelect($segmentedNotification)
    {
        $select = '';
        if ($segmentedNotification->code_dependence) {
            $select .= 'tipest as dependence_code';
        }
        if ($segmentedNotification->code_position) {
            $select .= ',persk as code_position';
        }
        if ($segmentedNotification->code_region) {
            $select .= ',werks as code_region';
        }
        return $select;
    }

    public function saveUserNotification($segmentedNotification, $type, $objectId = null, $date)
    {
        $users = User::select('tipest as dependence_code', 'id', 'persk as code_position', 'werks as code_region');
        if ($type == self::TYPE_SETTLEMENT) {
            $users->where('is_notification_settlement', 1);
        }

        if ($type == self::TYPE_POST) {
            $users->where('is_notification_new', 1);
        }
        if ($segmentedNotification->code_dependence) {
            $users->whereIn('tipest', $segmentedNotification->code_dependence);
        }
        if ($segmentedNotification->code_position) {
            $users->whereIn('persk', $segmentedNotification->code_position);
        }
        if ($segmentedNotification->code_region) {
            $users->whereIn('werks', $segmentedNotification->code_region);
        }
        $users = $users->distinct()->get();
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
}
