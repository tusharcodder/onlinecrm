@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-8">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Create New Box Isbn*') }}
					</div>					
					<div class="clearfix"></div>
				</div>
				
                <div class="card-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
							</ul>
						</div>
					@endif
                    @if ($message = Session::get('success'))
						<div class="alert alert-success">
							<p>{{ $message }}</p>
						</div>
					@endif
					<form method="POST" action="{{ route('boxisbns.store') }}">
					@csrf
					
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="name" class="col-form-label text-md-right">{{ __('Box isbn13*') }}</label>
									<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}"  autocomplete="name" required>
                                   
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									
									<label for="symbol" class="col-form-label text-md-right">{{ __('Book Isbn from*') }}</label>
									<span style="color:red;font-size:smaller">Double click in Isbn to add</span>
									<input type="text" class="form-control" id="isbnsearch" placeholder="Search Isbn" />
									<select class="form-control" name="book_isbns[]" id="box_isbns" multiple style="height:200px;">                                        
                                        @foreach($isbn13 as $key => $val)
                                            <option value="{{$val->isbn13}}">{{$val->isbn13}}</option>
                                        @endforeach
                                    </select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="symbol" class="col-form-label text-md-right">{{ __('Book Isbn To') }}</label>
									<span style="color:red;font-size:smaller">Double click in Isbn to remove</span>
									<select class="form-control" name="bookisbnsto[]" id="book_isbns_to" multiple style="height:238px;">                                        
                                      
                                    </select>
								</div>
							</div>
						</div>

						<div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Submit') }}
                                </button>
                            </div>
                        </div>
					</form>
				</div>
			</div>
		</div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Details') }}
					</div>					
					<div class="clearfix"></div>
				</div>
                <div class="card-body">
				
					<div class="row mb-1">
						<div class="col-sm-7">	
							Showing {{($boxisbns->currentPage()-1)* $boxisbns->perPage()+($boxisbns->total() ? 1:0)}} to {{($boxisbns->currentPage()-1)*$boxisbns->perPage()+count($boxisbns)}}  of  {{$boxisbns->total()}}  Results
						</div>
						<div class="col-sm-5">
							<form method="GET" action="{{ route('boxisbns.index') }}" role="search">
								<div class="input-group">
									<input type="text" class="form-control" name="search"
										placeholder="Search" value="{{ $search }}"> <span class="input-group-btn">
										<button type="submit" class="btn btn-primary">
											<i class="fa fa-search"></i>
										</button>
									</span>
								</div>
							</form>
						</div>
					</div>
					 <div class="table-responsive">
						<table class="table table-bordered">
							<tr>
								<th>#ID</th>
								<th>Box Isbn13</th>								
								<th width="227px">Action</th>
							</tr>
							@if($boxisbns->total() > 0)
								@foreach ($boxisbns as $key => $val)
								<tr>
									<!--<td>{{ ($boxisbns->currentPage()-1) * $boxisbns->perPage() + $loop->index + 1 }}</td>-->
									<td>{{ $val->id }}</td>
									<td>{{ $val->box_isbn13 }}</td>								
									<td>										
										@can('val-edit')
											<a class="btn btn-primary btn-sm" href="{{ route('boxisbns.edit',$val->id) }}">Edit</a>
										@endcan
										@can('val-delete')
											<form method="POST" action="{{ route('boxisbns.destroy',$val->id) }}" style="display:inline">
												@csrf
												@method('DELETE')
												<button type="submit" class="btn btn-danger btn-sm">
													{{ __('Delete') }}
												</button>
											</form>
										@endcan
									</td>
								</tr>
								@endforeach
							@else
								<tr><td colspan="4">No records found.</td></tr>
							@endif
						</table>
						{{ $boxisbns->links() }}
					</div>
				</div>
			</div>
	</div>
</div>
@endsection
