@if($className == 'profile-page')
	<div class="section_tag_line">
		{{auth::user()->tag_line}}
		<a href="javascript:void(0);" class="edit-tag-line"><i class="fas fa-pencil-alt"></i></a>
	</div>
	<form method="POST" action="{{url('/edit_profile_tag')}}" id="edit_profile_tag">
		@csrf
		<textarea id="tag_line" name="tag_line" maxlength="180">{{trim(auth::user()->tag_line)}}</textarea>
		<input type="hidden" value="{{$className}}" name="className"> 
		<a href="javascript:void(0)" class="save_tag_line" id="save_tag_line"><i class="fa fa-spinner fa-spin loader_tagline" style="display:none"></i> Save </a>
	</form>
@elseif($className == 'coache-page')
	@if(isset($coache))
		{{$coache->tag_line}}
	@endif
@else
	{{auth::user()->tag_line}}
@endif