<?php

namespace App\Console\Commands;

use App\Post;
use App\User;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Http\Client\Common\HttpMethodsClient;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use OneSignal\Config as OneSignalConfig;
use OneSignal\OneSignal as OneSignalApi;

class Notify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:notification';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dateCurrent = date("Y-m-d H:i");
        Validator::validate([
            'text' => $request->text,
        ], [
            'text' => 'required',
        ]);

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

        $response = [];

        $post = Post::select('posts.id as id_post', 'posts.slug', 'districts.code as dependence_code', DB::raw('"post"'), 'regions.code as code_region', 'positions.code as code_position')
            ->join('post_districts', 'post_districts.post_id', 'posts.id')
            ->join('districts', 'post_districts.district_id', 'districts.id')
            ->join('post_positions', 'post_positions.post_id', 'posts.id')
            ->join('positions', 'post_positions.position_id', 'positions.id')
            ->join('post_regions', 'post_regions.post_id', 'posts.id')
            ->join('regions', 'post_regions.region_id', 'regions.id')
            ->where('is_send_notification', 0)
            ->groupBy('dependence_code', 'code_region', 'code_position', 'id_post')
            ->get()->toArray();

        $release = Release::select('releases.id as id_release', 'releases.slug', 'districts.code as dependence_code', DB::raw('"release"'), 'regions.code as code_region', 'positions.code as code_position')
            ->join('release_districts', 'release_districts.release_id', 'releases.id')
            ->join('districts', 'release_districts.district_id', 'districts.id')
            ->join('release_positions', 'release_positions.release_id', 'releases.id')
            ->join('positions', 'release_positions.position_id', 'positions.id')
            ->join('release_regions', 'release_regions.release_id', 'releases.id')
            ->join('regions', 'release_regions.region_id', 'regions.id')
            ->where('is_send_notification', 0)
            ->groupBy('dependence_code', 'code_region', 'code_position', 'id_release')
            ->get()->toArray();

        $publication = [];

        $publication = array_merge($release, $post);

        foreach ($publication as $value) {
            $users = User::select('tipest as dependence_code', 'id', 'persk as code_position', 'werks as code_region')
                ->where('tipest', $value['dependence_code'])
                ->where('persk', $value['code_position'])
                ->where('werks', $value['code_region'])
                ->where('is_notification_settlement', 0)
                ->groupBy('dependence_code', 'code_position', 'code_region', 'id')
                ->get();

            $data = [
                'filters' => [
                    ["field" => "tag", "key" => "code_district", "relation" => "=", "value" => $value['dependence_code']],
                    ["field" => "tag", "key" => "region_code", "relation" => "=", "value" => $value['code_region']],
                    ["field" => "tag", "key" => "position_code", "relation" => "=", "value" => $value['code_position']],
                ],
                'contents' => [
                    'en' => $request->text,
                ],
            ];

            if (isset($value['post']) && $value['post'] == 'post') {
                $new = Post::where('id', $value['id_post'])->update(['is_send_notification' => 1]);
                $this->saveNotification('post', $users, $value);
                //$data['url'] = env('APP_URL') . '/api/post/' . $value['slug'];
            }

            if (isset($value['release']) && $value['release'] == 'release') {
                $this->saveNotification('release', $users, $value);
                $new = Release::where('id', $value['id_release'])->update(['is_send_notification' => 1]);
                //$data['url'] = env('APP_URL') . '/api/release/' . $value['slug'];

            }

            if (!empty($request->heading)) {
                $data['headings'] = [
                    'en' => $request->heading,
                ];
            }
            $response = $oneSignalApi->notifications->add($data);
        }
        return response()->json(['data' => 'Notificación enviada con exito'], 200);
    }

    public function saveNotification($type, $user, $object)
    {
        if (count($user) > 0) {
            foreach ($user as $value) {
                $notification = new Notification();
                $notification->object_id = $value['id'];
                $notification->type = $type;
                $notification->save();
            }
        }
    }
}
