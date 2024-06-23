<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        if(!empty($user)){
            $user_id = $user->id;
            if(empty($user->hash)){
                //create hash and update
                $this->updateHash($user_id);
            }
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        if(!empty($user)){
            $user_id = $user->id;
            if(empty($user->hash)){
                //create hash and update
                $this->updateHash($user_id);
            }
        }
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }

    /*Update unique hash to user table*/
    public function updateHash($user_id){
        $unique_hash = getToken();
        if(!empty($unique_hash)){
            $user = User::find($user_id);
            $user->hash = $unique_hash;
            $user->save();
        }
    }
}
