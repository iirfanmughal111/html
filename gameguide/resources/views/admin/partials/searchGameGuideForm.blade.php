 <form action="{{ url('admin/game-guides') }}" method="POST" id="searchForm" >
	@csrf
	<div class="row">
		<div class="col-md-12 mb-4">
			<div class="card h-100">
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="form-group col-lg-4">
									<input type="text" name="title" id="title" class="form-control" placeholder="Search By Title">
								</div>

								@if(isset($games) && count($games) > 0)
									<div class="form-group col-lg-4">
										<select id="game_id" class="form-control select2-single" name="game_id" data-width="100%">		
											<option value=" ">Search By Game</option>
											@foreach($games as $key=>$game)
												<option value="{{$game->id}}">{{$game->title}}</option>
											@endforeach
										</select>
									</div>
								@endif

								@if(isset($guideTypes) && count($guideTypes) > 0)
									<div class="form-group col-lg-4">
										<select id="guide_type_id" class="form-control select2-single" name="guide_type_id" data-width="100%">		
											<option value=" ">Search By Guide Type</option>
											@foreach($guideTypes as $key=>$guide)
												<option value="{{$guide->id}}">{{$guide->name}}</option>
											@endforeach
										</select>
									</div>
								@endif
								
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-lg-4">
							<button type="submit" class="btn btn-primary default  btn-lg mb-2 mb-lg-0 col-12 col-lg-auto">{{trans('global.submit')}}</button>
							<div class="spinner-border text-primary search_spinloder" style="display:none"></div>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>	
</form>