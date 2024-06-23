<?php

namespace App\Observers;

use App\Models\CoacheRating;
use App\Models\UserProfile;

class CoacheRatingObserver
{
    /**
     * Handle the coache rating "created" event.
     *
     * @param  \App\Models\CoacheRating  $coacheRating
     * @return void
     */
    public function created(CoacheRating $coacheRating)
    {
        if(!empty($coacheRating->id) && !empty($coacheRating->coache_id)){
            $data = array();
            $data['new_rating'] = $coacheRating->rating;
            $data['coache_id'] = $coacheRating->coache_id;
            $this->updateCoachProfile($data);
        }
    }

    /**
     * Handle the coache rating "updated" event.
     *
     * @param  \App\Models\CoacheRating  $coacheRating
     * @return void
     */
    public function updated(CoacheRating $coacheRating)
    {
        //
    }

    /**
     * Handle the coache rating "deleted" event.
     *
     * @param  \App\Models\CoacheRating  $coacheRating
     * @return void
     */
    public function deleted(CoacheRating $coacheRating)
    {
        //
    }

    /**
     * Handle the coache rating "restored" event.
     *
     * @param  \App\Models\CoacheRating  $coacheRating
     * @return void
     */
    public function restored(CoacheRating $coacheRating)
    {
        //
    }

    /**
     * Handle the coache rating "force deleted" event.
     *
     * @param  \App\Models\CoacheRating  $coacheRating
     * @return void
     */
    public function forceDeleted(CoacheRating $coacheRating)
    {
        //
    }

    /*Get Particular coach rating, and update it*/
    public function updateCoachProfile($data){
        /*Check particular coach have entry*/
        if(!empty($data['coache_id']) && !empty($data['new_rating'])){
            $given_rating = floatval($data['new_rating']);
            $userProfile = UserProfile::where('user_id',$data['coache_id'])->first();
            $rating = 0;
            $total_review = 0;
            $total_rating = 0;
            if($userProfile){
                /*Retreive Old Data*/
                $rating = $userProfile->rating;
                $total_review = $userProfile->total_review;
                $total_rating = floatval($userProfile->total_rating);

            }
            /*Calculate New parameter*/
            $total_review = $total_review + 1;
            $total_rating += $given_rating;
            $total_rating = number_format($total_rating,1);
            /*Calculate average Rating*/
            if($total_review > 0){
                $average_rating = number_format($total_rating/$total_review,1);
                //modify field
                $profileData = array();
                $profileData['rating'] = trim($average_rating);
                $profileData['total_review'] = trim($total_review);
                $profileData['total_rating'] = trim($total_rating);
                
                if($userProfile){
                    $profileDataMain = UserProfile::where('id',$userProfile->id);
                    //$profileDataMain->update($profileData);
                    $result = $profileDataMain->update([
                            'rating' => trim($average_rating),
                            'total_review' => trim($total_review),
                            'total_rating' => trim($total_rating)
                        ]
                    );
                    
                }else{
                    $user_id = trim($data['coache_id']);
                    if(!empty($user_id))
                        $profileData['user_id'] = $user_id;

                    $profileDataMain = UserProfile::create($profileData);
                }
            }
        }
        

    }
}
