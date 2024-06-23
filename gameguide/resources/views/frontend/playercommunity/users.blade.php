@extends('frontend.layouts.master')
@section('headtitle')
| Chat
@endsection

@section('content')
	@include('frontend.common.header')

<style>

#userimage{

    height: 150px;
    width: 150px;
    border: 8px solid #eee;
    position: absolute;
    left: 50%;
    top: 0;
    transform: translate(-50%,-50%);
}

.card{
    position:relative;
    width: 100%;
    border-radius: 5px;
    border: none;
    background: #F5F5F5;
}
.name{
    font-weight: 600;
    color: red;
    font-size: 14px;
    margin-bottom: 6px;
    padding-top: 82px;
}

.container .card .icons .icon {
    font-size: 14px;
    width: 30px;
    height: 30px;
    color: #8B53FF;
    background-color: white;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
}
.container .card .icons .icon a{
    color: #8B53FF;

}
.container .card .icons .icon :hover{

    color:white;
    background-color: #8B53FF;

}
.container .card .icons .icon:hover{

    color:white; background-color: #8B53FF;
    background-color: #8B53FF;
}
.dis{
    color: #7e7c7c;
    line-height: 2;
}

.mt-80{
    margin-top: 80px;
}
  
.friend{
    border: 1px solid #8B53FF;
    background-color: #fff;
    color:#8B53FF;
    font-size:10px;
}
.friend:hover{

    background-color: #8B53FF;
    color:white;
     
}
.dis ul{

    display: inline-flex;
    text-decoration: none;
    list-style: none;
    padding:0px;
}
.dis ul li{

    margin-left:2px;
}

.page-item.active .page-link {

    z-index: 1;
    color: #fff;
    background-color: #8B53FF;
    border-color: #8B53FF;
}

.page-link {

    color:#8B53FF;

}
.page-link:hover{

    background-color: #8B53FF;
    color:white;
}
.form-inline {  
  display: flex;
  flex-flow: row wrap;
  justify-content: center;
}
	
.description {
    height: 60px;
    padding: 0px 4px;
    font-size: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: 600;
}



@media (max-width: 800px) {
  .form-inline input {
    margin: 10px 0;
  }
  
  .form-inline {
    flex-direction: column;
    align-items: stretch;
  }
}
	
@media (max-width:160px) {
		
	.card{
	
		 width: 150%;
	}
	
	.description {
    
		height: 90px;
		
	}
	
}

.form-control{
    border-radius: 0.1rem;
    outline: initial!important;
    box-shadow: initial!important;
    font-size: .8rem;
    padding: 0.5rem 0.75rem;
    line-height: 1;
    border: 1px solid #8B53FF;
    background: #fff;
    color: #8B53FF;
    border-color: #8B53FF;
    height: calc(2em + 0.8rem);
    margin-left:2px;
    margin-right:4px;

}
</style>

	<div class="wrapper">
    	@include('frontend.partials.account_top')
    	@include('frontend.partials.guide_bar')

	<div class="container pt-3">
   

    <!-- <div class="row">
    <div class="col-md-6">
     </div>
        <div class="col-md-6">
        <div class="card">
            <div class="card-body">
            <form  class="form-inline" action="{{ url('player-search') }}" method="GET" >
            @csrf
               <div class="form-group">
                    <input type="name" name ="name" placeholder="Serach by name...." class="form-control" id="name">
                </div>

                <div class="form-group">
                    <input type="last"name="last" placeholder="Serach by last...."  class="form-control" id="last">
                </div>
               
                <button type="submit" class="btn btn-default friend">Submit</button>
            </form>
            </div>
    </div>
    </div>
    </div> -->

    <div class="row mt-3">

	 @if(is_object($users) && !empty($users) && $users->count())
		
	  @foreach($users as $key => $user)

        
        <div class="col-lg-3 col-md-6 col-sm-6 mt-80">
                <div class="card d-flex align-items-center justify-content-center ">
                    <div class="w-100">
						
                        <img id="userimage" src="{{$user->getProfilePhotoUrlAttribute()}}" alt="" class="rounded-circle">
                                            
                    </div>
                    <div class="text-center ">
                        
						<p class="name">{{$user->getFullNameAttribute()}}</p>
						
						<div class="description">
                        <p >{{isset($user->tag_line) ? $user->tagLine() : 'Not Bio Added'}}</p>
                       </div>
                        
                        <ul class="d-flex align-items-center justify-content-center list-unstyled icons">
                           
                        
     <a href="{{isset($user->userProfile['facebook_link']) ? $user->userProfile['facebook_link'] : 'javascript:void(0)'}}"> <li class="icon "><span class="fab fa-facebook"></span></li></a>
                        <a href="{{isset($user->userProfile['instagram_link']) ? $user->userProfile['instagram_link'] : 'javascript:void(0)'}}"><li class="icon mx-2"><span class="fab fa-instagram"></span></li></a>
                        <a href="{{isset($user->userProfile['instagram_link']) ? $user->userProfile['instagram_link'] : 'javascript:void(0)'}}" ><li class="icon "><span class="fab fa-twitter"></span></li></a>

                        </ul>
                        <span id="user-{{$user->id}}"></span>
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
                            
                                <li> <a class="btn btn-default friend"  href="javascript:void(0)" onclick="cancelfriend('{{ $user->id}}');" ><i class='fas fa-user-times fa-lg'></i>  Cancel</a></li>
                            
                                
                                @elseif($acceptUser=='0')	
                            
                                <li>  <a  href="javascript:void(0)" onclick="confirmfriend('{{ $user->id}}');" class="btn btn-default friend"><i class="fas fa-user-friends fa-lg"></i>  Confirm</a></li>
                            
                                <li>  <a   href="javascript:void(0)" onclick="rejectfriend('{{ $user->id}}');"  class="btn btn-default friend"> <i class='fas fa-user-slash fa-lg'></i>  Delete</a></li>
                            
                                
                        
                                @else
                            
                                <li>  <a  class="btn btn-default friend" href="javascript:void(0)" onclick="addfriend('{{ $user->id}}');" ><i class="fas fa-user-plus fa-lg"></i>  Add Friend</a></li>
                            
                                @endif	
                            
        
                            </ul>
                       </div>
                    </div>
                </div>
            </div>


            
       
                 
       

    
	 @endforeach
     <div class="container">
        <div class="row justify-content-center mt-3">
 
     {{ $users->links() }}

     </div>
     </div>
 @else
 
 No Data Found.
 @endif	

            </div>

	</div>
	
	@include('frontend.common.footer')
@stop

<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="script.js"></script>
      </script>
      
      
      <script >
        
      
function addfriend(id) 
         { 
            $.ajax({
            type: 'GET',
            url:  'add-frnd/'+ id,

            success: function (data) {
                
                $('.user-'+id).remove();

                $('#user-'+id).append(data.html);
                
            }
        });
      } 

function cancelfriend(id) 
         { 
            $.ajax({
            type: 'GET',
            url:  'cancel-frnd/'+ id,

         
            success: function (data) {
                
                $('.user-'+id).remove();

                $('#user-'+id).append(data.html);
                
            }
        });
} 

function confirmfriend(id) 
         { 
            $.ajax({
            type: 'GET',
            url:  'accept-frnd/'+ id,

         
            success: function (data) {
                
                $('.user-'+id).remove();

                $('#user-'+id).append(data.html);
                
            }
        });
    }

function rejectfriend(id) 
         { 
            $.ajax({
            type: 'GET',
            url:  'reject-frnd/'+ id,

         
            success: function (data) {
                
                $('.user-'+id).remove();

                $('#user-'+id).append(data.html);
                
            }
        });
} 
function unfriend(id) 
         { 
            $.ajax({
            type: 'GET',
            url:  'un-frnd/'+ id,

         
            success: function (data) {
                
                $('.user-'+id).remove();

                $('#user-'+id).append(data.html);
                
            }
        });
} 

        
     </script>

      

	  