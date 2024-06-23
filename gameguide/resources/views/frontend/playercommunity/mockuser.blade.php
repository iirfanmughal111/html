
        
                <div class="dis pb-4 user-{{$user->id}}">
                        <ul >
                        @php
                    
                        $requestUser=$user->friendRequestUserStatus($user->id);

                        $acceptUser=$user->firendAcceptUserStatus($user->id);

                        @endphp
                    
                    
                        @if($requestUser=='1' || $acceptUser=='1')
                        
                    <li> <a  href="{{url('manage-user')}}/{{$user->id}}" class="btn btn-default friend"><i class='fas fa-comments fa-lg'></i>  Chat</a></li>
                    
                    <li> <a  href="javascript:void(0)" onclick="unfriend('{{ $user->id}}');" class="btn btn-default friend"><i class='fas fa-user-times fa-lg'></i>  Unfriend</a></li>
                        
                        @elseif($requestUser=='0')	

                        <li> <a  href="#" class="btn btn-default friend"><i class='fas fa-user-friends fa-lg'></i>  Requested</a></li>
                    
                        <li> <a   class="btn btn-default friend"  href="javascript:void(0)" onclick="cancelfriend('{{ $user->id}}');"><i class='fas fa-user-times fa-lg'></i>  Cancel</a></li>
                    
                        
                        @elseif($acceptUser=='0')	
                    
                        <li>  <a  href="javascript:void(0)" onclick="confirmfriend('{{ $user->id}}');" class="btn btn-default friend"><i class="fas fa-user-friends fa-lg"></i>  Confirm</a></li>
                    
                        <li>  <a  href="javascript:void(0)" onclick="rejectfriend('{{ $user->id}}');" class="btn btn-default friend"> <i class='fas fa-user-slash fa-lg'></i>  Delete</a></li>
                    
                        
                
                        @else
                    
                        <li>  <a  class="btn btn-default friend" href="javascript:void(0)" onclick="addfriend('{{ $user->id}}');" ><i class="fas fa-user-plus fa-lg"></i>  Add Friend</a></li>
                    
                        @endif	
                    

                    </ul>
               </div>
        
    

         
