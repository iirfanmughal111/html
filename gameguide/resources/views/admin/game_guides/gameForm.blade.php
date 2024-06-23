@extends('admin.layouts.admin')
@section('content')
@section('ckeditor')
<script src="//cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
<script>
CKEDITOR.replace('description', {
    allowedContent: true
});
</script>
@stop
{{-- Check if New Game or Edit game, if $newGame set 1 then new game else Edit Game --}}
@php
$newGame = 1;
$gameTitle = 'Add';
$action = 'Add';
$transcriptFound = 0;
$keynoteFound = 0;
@endphp
@if(isset($guide))
@php
$newGame = 2;
$gameTitle = 'Edit';
$action = 'Update'
@endphp
@endif

@section('headtitle')
| {{$gameTitle}} Game Guides
@endsection


<div class="row">
    <div class="col-12">
        <h1>{{$gameTitle}} Game Guide</h1>
        <div class="separator mb-5"></div>
    </div>
</div>
<!-- Main content -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive" id="tag_container">
            <div class="col-lg-12">
                <div class="box box-primary">
                    <div class="box-body">
                        @include('flash-message')

                        @if($newGame == 2)
                        @if($errors->first('guide_id'))
                        <span class="error"> {{ $errors->first('guide_id')  }} </span>
                        @endif
                        @endif

                        @if($newGame == 1)
                        {{ Form::open(array('url' => 'admin/game-guides/create', 'method' => 'post','class'=>'profile form-horizontal','enctype'=>'multipart/form-data')) }}
                        @else
                        {{ Form::open(array('url' => 'admin/game-guides/update/', 'method' => 'post','class'=>'profile form-horizontal','enctype'=>'multipart/form-data')) }}
                        @endif


                        <div class="form-group col-md-12">
                            <div class="row">
                                <div class="col-md-8 row col-xs-12">
                                    <div class="col-md-12 col-xs-12 field mb-4">
                                        {{ Form::label('title') }}
                                        @if($newGame == 1)
                                        {{ Form::text('title',old('title'),array('class'=>'form-control','placeholder'=>'Title')) }}
                                        @else
                                        {{ Form::text('title',old('title', $guide->title),array('class'=>'form-control','placeholder'=>'Title')) }}
                                        @endif
                                        <span class="error"> {{ $errors->first('title')  }} </span>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="col-md-12 col-xs-12 field mb-4">
                                        {{ Form::label('Game') }}
                                        @if($newGame == 1)
                                        <select class="form-control input-sm" name="game_id" id="pro_category">
                                            <option value="">--Select Game--</option>
                                            @foreach($games as $game)
                                            <option value="{{ $game->id ?? ''}}"
                                                {{ old('game_id') == $game->id ? 'selected' : '' }}>
                                                {{ $game->title ?? ''}}</option>
                                            @endforeach
                                        </select>
                                        @else
                                        <select class="form-control input-sm" name="game_id" id="pro_category">
                                            <option value="">--Select Game--</option>
                                            @foreach($games as $game)
                                            <option value="{{ $game->id ?? ''}}"
                                                {{ old('game_id', $guide->game_id) == $game->id ? 'selected' : '' }}>
                                                {{ $game->title ?? ''}}</option>
                                            @endforeach
                                        </select>
                                        @endif
                                        <span class="error"> {{ $errors->first('game_id')  }} </span>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="col-md-12 col-xs-12 field mb-4">
                                        {{ Form::label('Guide Type') }}
                                        @if($newGame == 1)
                                        <select class="form-control input-sm" name="guide_type_id" id="pro_category">
                                            <option value="">--Select Guide Type--</option>
                                            @foreach($guideTypes as $guideType)
                                            <option value="{{ $guideType->id ?? ''}}"
                                                {{ old('guide_type_id') == $guideType->id ? 'selected' : '' }}>
                                                {{ $guideType->name ?? ''}}</option>
                                            @endforeach
                                        </select>
                                        @else
                                        <select class="form-control input-sm" name="guide_type_id" id="pro_category">
                                            <option value="">--Select Guide Type--</option>
                                            @foreach($guideTypes as $guideType)
                                            <option value="{{ $guideType->id ?? ''}}"
                                                {{ old('guide_type_id', $guide->guide_type_id) == $guideType->id ? 'selected' : '' }}>
                                                {{ $guideType->name ?? ''}}</option>
                                            @endforeach
                                        </select>
                                        @endif
                                        <span class="error"> {{ $errors->first('guide_type_id')  }} </span>

                                    </div>

                                    <div class="clearfix"></div>


                                    <div class="col-md-12 col-xs-12 field mb-4 embed_video_outer">
                                        {{ Form::label('Guide Tag') }}
                                        @if($newGame == 1)
                                        {{ Form::text('guide_tag',old('guide_tag'),array('class'=>'form-control','placeholder'=>'Guide Tag')) }}
                                        @else
                                        {{ Form::text('guide_tag',old('guide_tag', $guide->guide_tag),array('class'=>'form-control','placeholder'=>'Guide Tag')) }}
                                        @endif
                                        <span class="error"> {{ $errors->first('guide_tag')  }} </span>
                                        <i style="font-size:10px;">Hint : Video Text|Audio Text </i>
                                    </div>
                                    <div class="clearfix"></div>


                                    <div class="col-md-12 col-xs-12 field mb-4">
                                        {{ Form::label('Image') }}

                                        <div class="clearfix"></div>
                                        <input id="fileupload" class="inputfile" type="file" name="image"
                                            accept="image/*">
                                        <label class="mt-2 mb-3" for="fileupload"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="20" height="17"
                                                viewBox="0 0 20 17">
                                                <path
                                                    d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z">
                                                </path>
                                            </svg> <span>Choose a file</span></label>

                                        @if($newGame == 2)
                                        @if($guide->image != NULL)

                                        <div class="inputfile-preview old-file">
                                            <img src="{{$guide->image_url}}" class="module_image game_guide_new_image">
                                            <a href="{{url('/admin/game-guide/image_downlad')}}/{{$guide->game_id}}"
                                                class="filedownlad filedownlad-category">
                                                <i class="glyph-icon simple-icon-cloud-download"></i>
                                            </a>

                                            <a title="Delete Image" data-id="{{ $guide->id }}"
                                                data-confirm_type="complete"
                                                data-confirm_message="Are you sure you want to delete the Image?"
                                                data-left_button_name="Yes"
                                                data-left_button_id="delete_game_guide_image"
                                                data-left_button_cls="btn-primary"
                                                class="open_confirmBox action deleteImage" href="javascript:void(0)"
                                                data-game_id="{{ $guide->id }}">
                                                <i class="glyph-icon simple-icon-trash"></i>
                                            </a>
                                        </div>
                                        @endif
                                        @endif
                                        <div class="inputfile-preview new-file d-none">
                                            <img src="" class="module_image">
                                            <i class="glyph-icon simple-icon-trash new-module-file-trash"></i>
                                        </div>
                                        <span class="error"> {{ $errors->first('image')  }} </span>

                                        {{--@if($newGame == 2)
													@if($guide->image != NULL)
														<div class="clearfix"></div>
														<img src="{{$guide->image_url}}" class="game_image" style="width:100px;">
                                        <div class="clearfix mb-4"></div>
                                        @endif
                                        @endif
                                        <input type="file" name="image" accept="image/*">--}}
                                        <span class="error"> {{ $errors->first('image')  }} </span>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="col-md-12 col-xs-12 field mb-4">
                                        {{ Form::label('Short Description') }}
                                        @if($newGame == 1)
                                        {{ Form::textarea('short_description',old('short_description'),array('class'=>'form-control','placeholder'=>'Short Description','rows' => 3)) }}
                                        @else
                                        {{ Form::textarea('short_description',old('short_description', $guide->short_description),array('class'=>'form-control','placeholder'=>'Short Description','rows' => 3)) }}
                                        @endif
                                        <span class="error"> {{ $errors->first('short_description')  }} </span>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="col-md-12 col-xs-12 field mb-4 embed_video_outer">
                                        {{ Form::label('Embed Video') }}
                                        @if($newGame == 1)
                                        {{ Form::text('embed_video',old('embed_video'),array('class'=>'form-control','placeholder'=>'Embed Video')) }}
                                        @else
                                        {{ Form::text('embed_video',old('embed_video', $guide->embed_video),array('class'=>'form-control','placeholder'=>'Embed Video')) }}
                                        @endif
                                        <span class="error"> {{ $errors->first('embed_video')  }} </span>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="col-md-12 col-xs-12 field mb-4 description_guides">
                                        {{ Form::label('Description') }}
                                        @if($newGame == 1)
                                        {{ Form::textarea('description',old('description'),array('class'=>'form-control','placeholder'=>'')) }}
                                        @else
                                        {{ Form::textarea('description',old('description', $guide->description),array('class'=>'form-control','placeholder'=>'')) }}
                                        @endif
                                        <span class="error"> {{ $errors->first('description')  }} </span>
                                    </div>
                                    <div class="clearfix description_guides"></div>

                                    <div class="col-md-12 col-xs-12 field mb-4">
                                        {{ Form::label('Transcript') }}
                                        <div class="input_fields_trnscript_wrap">
                                            <span class="error"> {{ $errors->first('transcript.duration')  }} </span>
                                            <span class="error"> {{ $errors->first('transcript.content')  }} </span>
                                            <a href="javascript:void(0);"
                                                class="add_field_transcript btn btn-primary mb-4">Add More
                                                Transcript</a>
                                            @if($newGame == 2)
                                            @if(isset($guide->gameGuidetranscript) &&
                                            !empty($guide->gameGuidetranscript))
                                            @php
                                            $transcripts = $guide->gameGuidetranscript;
                                            @endphp
                                            @if(count($transcripts) > 0)
                                            @php
                                            $transcriptFound = 1;
                                            @endphp
                                            @foreach($transcripts as $tkey=>$transcript)
                                            <div class="row new_transcript">
                                                <div class="col-md-5 col-xs-5 field mb-4">
                                                    <input type="text" name="transcript[duration][]"
                                                        class="transcript_input form-control" placeholder="Duration"
                                                        value="{{$transcript->duration }}">
                                                </div>
                                                <div class="col-md-5 col-xs-5 field mb-4">
                                                    <input type="text" name="transcript[content][]"
                                                        class="transcript_input form-control" placeholder="Transcript"
                                                        value="{{$transcript->content}}">
                                                </div>
                                                @if($tkey != 0)
                                                <div class="col-md-2 col-xs-2 field mb-4"><a href="#"
                                                        class="transcript_remove_field">Remove</a></div>
                                                @endif
                                            </div>
                                            @endforeach
                                            @endif
                                            @endif
                                            @endif
                                            @if(old('transcript'))
                                            @foreach(old('transcript.duration') as $oldt=>$transcp)
                                            <div class="row new_transcript">
                                                <div class="col-md-5 col-xs-5 field mb-4">
                                                    <input type="text" name="transcript[duration][]"
                                                        class="transcript_input form-control" placeholder="Duration"
                                                        value="{{old('transcript.duration')[$loop->index]}}">
                                                </div>
                                                <div class="col-md-5 col-xs-5 field mb-4">
                                                    <input type="text" name="transcript[content][]"
                                                        class="transcript_input form-control" placeholder="Transcript"
                                                        value="{{old('transcript.content')[$loop->index]}}">
                                                </div>
                                                @if($transcriptFound == 0)
                                                @if($oldt != 0)
                                                <div class="col-md-2 col-xs-2 field mb-4"><a href="#"
                                                        class="transcript_remove_field">Remove</a></div>
                                                @endif
                                                @endif
                                            </div>
                                            @endforeach

                                            @if(count(old('transcript.duration')))
                                            @php
                                            $transcriptFound = 1;
                                            @endphp
                                            @endif
                                            @endif
                                            @if($transcriptFound == 0)
                                            <div class="row new_transcript">
                                                <div class="col-md-5 col-xs-5 field mb-4">
                                                    <input type="text" name="transcript[duration][]"
                                                        class="transcript_input form-control" placeholder="Duration">
                                                </div>
                                                <div class="col-md-5 col-xs-5 field mb-4">
                                                    <input type="text" name="transcript[content][]"
                                                        class="transcript_input form-control" placeholder="Transcript">
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="col-md-12 col-xs-12 field mb-4">
                                        {{ Form::label('Key') }}
                                        <div class="input_fields_wrap">
                                            <a href="javascript:void(0);" class="add_field_key btn btn-primary mb-4">Add
                                                More Key</a>
                                            @if($newGame == 2)
                                            @if(isset($guide->gameGuideKey) && !empty($guide->gameGuideKey))
                                            @php
                                            $keyData = $guide->gameGuideKey;
                                            @endphp
                                            @if(count($keyData) > 0)
                                            @php
                                            $keynoteFound = 1;
                                            @endphp
                                            @foreach($keyData as $kkey=>$data)
                                            <div class="row new_keynote">
                                                <div class="col-md-8 col-xs-8 field mb-4">
                                                    <input type="text" name="mykey[]" class="key_input form-control"
                                                        value="{{$data->content}}">
                                                </div>

                                                @if($kkey != 0)
                                                <div class="col-md-4 col-xs-4 field mb-4"><a href="#"
                                                        class="remove_field">Remove</a></div>
                                                @endif
                                            </div>
                                            @endforeach
                                            @endif
                                            @endif
                                            @endif
                                            @if(old('mykey'))
                                            @foreach(old('mykey') as $oldk=>$keynote)
                                            <div class="row new_keynote">
                                                <div class="col-md-8 col-xs-8 field mb-4">
                                                    <input type="text" name="mykey[]" class="key_input form-control"
                                                        value="{{ old('mykey')[$loop->index] }}">
                                                </div>
                                                @if($keynoteFound == 0)
                                                @if($oldk != 0)
                                                <div class="col-md-4 col-xs-4 field mb-4"><a href="#"
                                                        class="remove_field">Remove</a></div>
                                                @endif
                                                @endif
                                            </div>
                                            @endforeach
                                            @if(count(old('mykey')))
                                            @php
                                            $keynoteFound = 1;
                                            @endphp
                                            @endif
                                            @endif

                                            @if($keynoteFound == 0)
                                            <div class="row new_keynote">
                                                <div class="col-md-8 col-xs-8 field mb-4">
                                                    <input type="text" name="mykey[]" class="key_input form-control">
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>


                                </div>

                            </div>
                        </div>


                        <div class="form-group col-md-12">
                            <div class="sign-up-btn ">
                                @if($newGame == 2)
                                <input type="hidden" value="{{$guide->id}}" name="guide_id" id="guide_id">
                                @endif
                                <input name="submit" class="loginmodal-submit btn btn-primary" id="game_update"
                                    value="{{$action}}" type="submit">
                                <a href="{{url('admin/game-guides')}}" name="back"
                                    class="loginmodal-submit btn btn-primary" id="profile_back" value="Back"
                                    type="submit">Back</a>
                            </div>
                        </div>

                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-top confirmBoxCompleteModal" tabindex="-1" role="dialog" aria-hidden="true"></div>
@stop

@section('additionJs')
<script src="{{ url('js/module/guides.js')}}"></script>
@stop