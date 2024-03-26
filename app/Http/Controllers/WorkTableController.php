<?php

namespace App\Http\Controllers;

use App\Faq;
use App\Helpdesk;
use App\TutorialItem;

class WorkTableController extends Controller
{
    public function tutorial()
    {
        return response()->json(
            [
                'is_logged' => auth('api')->user() ? 1 : 0,
                'tutorial' => TutorialItem::get(),
            ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function faq()
    {
        return response()->json(
            [
                'is_logged' => auth('api')->user() ? 1 : 0,
                'questions' => Faq::get(),
            ]
        );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function contact()
    {
        //'is_logged' => auth('api')->user() ? 1 : 0,
        $helpDesks = Helpdesk::get();
        $contacts = $helpDesks->map(function ($helpDesk) {
            $contact = [];
            foreach ($helpDesk->content as $value) {
                $contact[] = [
                    'icon' => $value['attributes']['icon'],
                    'title' => $value['attributes']['title'],
                    'label' => $value['attributes']['label'],
                    'value' => $value['attributes']['value'],
                ];
            }
            return [
                'is_logged' => auth('api')->user() ? 1 : 0,
                'intro' => $helpDesk->title, 'contact' => $contact];
        })[0];

        return response()->json($contacts);
    }
}
