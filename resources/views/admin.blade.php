@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Admin Dashboard </div>
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                    <div class="card-body">
                        Welcome to admin dashboard



                        @include('user-list')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
