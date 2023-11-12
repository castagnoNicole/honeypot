@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if(Auth()->user()->is_admin)
                    <div class="card-body text-center alert-success mb-2">
                        <h5 class="card-subtitle">You have administrator privileges</h5>
                        <p class="card-subtitle">{{ session('message') }}</p>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">Profile</div>
                    <div class="card-body border-bottom">
                        <div class="text-center">
                            <img class="image rounded-circle" src="{{ asset(Auth::user()->profile_pic)}}" alt="profile picture" style="width: 80px;height: 80px; padding: 10px; margin: 0px; ">
                        </div>

                        <form class="m-2" action="{{route('update')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input autofocus
                                           id="name"
                                           type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           name="name"
                                           placeholder="{{ Auth()->user()->name }}">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary mt-3 col-2">Update</button>
                            </div>
                        </form>
                        <form class="m-2" action="{{route('upload')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label for="profile_pic" class="col-md-4 col-form-label text-md-right">Profile
                                    image</label>

                                <div class="col-md-6">
                                    <input id="profile_pic" type="file"
                                           accept="image/png, image/jpeg"
                                           class="form-control @error('profile_pic') is-invalid @enderror"
                                           name="profile_pic">
                                    @error('profile_pic')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary mt-3 col-6">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
