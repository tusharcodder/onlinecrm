@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Edit Box Isbn') }}
					</div>					
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('boxisbns.index') }}"> Back to list</a>
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
					<form method="POST" action="{{ route('boxisbns.update',$boxparentisbn->id) }}">
					@csrf
					@method('PATCH')
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="name" class="col-form-label text-md-right">{{ __('Box isbn13*') }}</label>
									<input id="name" type="text" class="form-control" name="name" value="{{ old('name',$boxparentisbn->box_isbn13) }}"  autocomplete="name" required>
                                   
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="symbol" class="col-form-label text-md-right">{{ __('Book Isbn from*') }}</label>
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
									<select class="form-control" name="book_isbn_to[]" id="book_isbns_to" multiple style="height:238px;">                                        
										@foreach($boxchildisbn as $key => $val)
                                            <option value="{{$val->book_isbn13}}" selected>{{$val->book_isbn13}}</option>
                                        @endforeach 
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
	</div>
</div>
@endsection
