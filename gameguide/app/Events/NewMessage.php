<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $channels = [];

        if(!empty($this->message->group_id) && $this->message->group_id != null){
            $group_id = $this->message->group_id;
            $contacts = User::where('id', '!=', $this->message->from)->whereHas('group', function (Builder $query) use ($group_id) {
                    $query->where('group_id',$group_id);
                })->pluck('id');
            if($contacts && count($contacts)>0){
                foreach ($contacts as $key => $user) {
                    array_push($channels, new PrivateChannel('messages.' . $user));
                }
                return $channels;
            }else{
                return new PrivateChannel('messages.' . $this->message->to);
            }
        }else{
            return new PrivateChannel('messages.' . $this->message->to);
        }
        /*foreach ($this->group->users as $user) {
            array_push($channels, new PrivateChannel('users.' . $user->id));
        }

        return $channels;*/
        //return new PrivateChannel('groups.' . $this->message->group->id);
    }

    public function broadcastWith()
    {

        $this->message->load('fromContact');
        $this->message->sent_recieved = "received";
        $user_data = user_data_by_id($this->message->from);
        $this->message->first_name = $user_data->first_name;
        $this->message->last_name = $user_data->last_name;
        $this->message->group_id = $this->message->group_id;
        $this->message->messages_count = 1;
        if($user_data->profile_photo==NULL ||$user_data->profile_photo==''){
             $this->message->profile_photo    ='';
         }else{      
              $profile_photo =  profile_photo(auth()->id());
              $this->message->profile_photo = timthumb($profile_photo,50,50); 
            }
        //dd($this->message);
        return ["message" => $this->message];
    }
}
