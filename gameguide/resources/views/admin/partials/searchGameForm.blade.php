 <form action="{{ url('admin/games') }}" method="POST" id="searchForm" >
		@csrf
	<div class="row">
		<div class="col-md-12 mb-4">
			<div class="card h-100">
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<div class="form-group col-lg-8">
									<input type="text" name="title" id="title" class="form-control" placeholder="Search By Title">
								</div>
								
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-lg-6">
							<button type="submit" class="btn btn-primary default  btn-lg mb-2 mb-lg-0 col-12 col-lg-auto">{{trans('global.submit')}}</button>
							<div class="spinner-border text-primary search_spinloder" style="display:none"></div>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>	
</form>