<?php

namespace App\Observers;

use App\Survey;
use Illuminate\Http\Request;

class SurveyObserver
{
    /**
     * Handle the survey "created" event.
     *
     * @param  \App\Survey  $survey
     * @return void
     */
    public function created(Survey $survey)
    {
        \Cache::flush();
    }

    public function creating(Survey $survey)
    {
        \Log::info("111");
        \Cache::flush();
    }


    public function updated(Survey $survey)
    {
        \Log::info("111");
    }
    /**
     * Handle the survey "updated" event.
     *
     * @param  \App\Survey  $survey
     * @return void
     */
    public function updating(Survey $survey)
    {
        \Log::info("###");
        \Cache::flush();
    }
    /**
     * Handle the survey "deleted" event.
     *
     * @param  \App\Survey  $survey
     * @return void
     */
    public function deleted(Survey $survey)
    {
        \Log::info("###");
        \Cache::flush();
    }

    /**
     * Handle the survey "restored" event.
     *
     * @param  \App\Survey  $survey
     * @return void
     */
    public function restored(Survey $survey)
    {
        \Log::info("###222");
        \Cache::flush();
    }

    /**
     * Handle the survey "force deleted" event.
     *
     * @param  \App\Survey  $survey
     * @return void
     */
    public function forceDeleted(Survey $survey)
    {
        \Log::info("###");
        \Cache::flush();
    }
}