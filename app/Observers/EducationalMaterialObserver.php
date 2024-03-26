<?php

namespace App\Observers;

use App\EducationalMaterial;

class EducationalMaterialObserver
{
    /**
     * Handle the educational material "created" event.
     *
     * @param  \App\EducationalMaterial  $educationalMaterial
     * @return void
     */
    public function created(EducationalMaterial $educationalMaterial)
    {
    }

    public function creating(EducationalMaterial $educationalMaterial)
    {
        if (!$educationalMaterial->is_activity) {
            \Log::info("Hola");

            $educationalMaterial->is_activity = 0;
        }
        \Log::info("Hola2");

        \Log::info($educationalMaterial);

    }

    /**
     * Handle the educational material "updated" event.
     *
     * @param  \App\EducationalMaterial  $educationalMaterial
     * @return void
     */
    public function updated(EducationalMaterial $educationalMaterial)
    {
        //
    }

    /**
     * Handle the educational material "deleted" event.
     *
     * @param  \App\EducationalMaterial  $educationalMaterial
     * @return void
     */
    public function deleted(EducationalMaterial $educationalMaterial)
    {
        //
    }

    /**
     * Handle the educational material "restored" event.
     *
     * @param  \App\EducationalMaterial  $educationalMaterial
     * @return void
     */
    public function restored(EducationalMaterial $educationalMaterial)
    {
        //
    }

    /**
     * Handle the educational material "force deleted" event.
     *
     * @param  \App\EducationalMaterial  $educationalMaterial
     * @return void
     */
    public function forceDeleted(EducationalMaterial $educationalMaterial)
    {
        //
    }
}
