<?php

namespace App\Observers;

use App\Release;

class ReleaseObserver
{
    /**
     * Handle the release "created" event.
     *
     * @param  \App\Release  $release
     * @return void
     */
    public function created(Release $release)
    {
        $releaseQuery = Release::where('slug', $release->slug)->count();
        if ($releaseQuery > 1) {
            $renameSlug = $release->slug . $release->id;
            Release::where('id', $release->id)->update(['slug' => $renameSlug]);
        }
        \Cache::flush();
    }

    public function updating(Release $release)
    {
        \Cache::flush();
    }

    /**
     * Handle the release "updated" event.
     *
     * @param  \App\Release  $release
     * @return void
     */
    public function updated(Release $release)
    {
        //
    }

    /**
     * Handle the release "deleted" event.
     *
     * @param  \App\Release  $release
     * @return void
     */
    public function deleted(Release $release)
    {
        \Cache::flush();
    }

    /**
     * Handle the release "restored" event.
     *
     * @param  \App\Release  $release
     * @return void
     */
    public function restored(Release $release)
    {
        //
    }

    /**
     * Handle the release "force deleted" event.
     *
     * @param  \App\Release  $release
     * @return void
     */
    public function forceDeleted(Release $release)
    {
        //
    }
}