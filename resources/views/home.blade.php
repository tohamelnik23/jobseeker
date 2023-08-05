@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
					<b>
					@if(Auth::user()->role == 1)
						Employee
					@else
						Employer
						<br>
						<a href="{{ route('employer.job.add') }}">
							Post Gig
						</a>
						<br>
						<a href="{{ route('employer.jobs') }}">
							My Gigs
						</a>
					@endif
					</b></br>
                     {{ Auth::user()->accounts->name }} You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
