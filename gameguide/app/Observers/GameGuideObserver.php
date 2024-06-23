<?php

namespace App\Observers;

use App\Models\GameGuide;
use App\Models\GameGuideTranscript;
use App\Models\GuideType;

class GameGuideObserver
{
    /**
     * Handle the game guide "created" event.
     *
     * @param  \App\Models\GameGuide  $gameGuide
     * @return void
     */
    public function created(GameGuide $gameGuide)
    {
        //
    }

    /**
     * Handle the game guide "updated" event.
     *
     * @param  \App\Models\GameGuide  $gameGuide
     * @return void
     */
    public function updated(GameGuide $gameGuide)
    {
        //
    }

    /**
     * Handle the game guide "deleted" event.
     *
     * @param  \App\Models\GameGuide  $gameGuide
     * @return void
     */
    public function deleted(GameGuide $gameGuide)
    {
        /*If game Guide deleted then its Transcript and keynotes also deleted*/
        $id = $gameGuide->id;
        GameGuideTranscript::where('game_guide_id',$id)->delete();
        GuideType::where('game_guide_id',$id)->delete();
    }

    /**
     * Handle the game guide "restored" event.
     *
     * @param  \App\Models\GameGuide  $gameGuide
     * @return void
     */
    public function restored(GameGuide $gameGuide)
    {
        //
    }

    /**
     * Handle the game guide "force deleted" event.
     *
     * @param  \App\Models\GameGuide  $gameGuide
     * @return void
     */
    public function forceDeleted(GameGuide $gameGuide)
    {
        //
    }
}
