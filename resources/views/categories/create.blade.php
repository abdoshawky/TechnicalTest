@extends('layouts.app')
@section('content')

    <div class="container">
        <form action="{!! url('categories') !!}" method="post">
            @csrf
            <div class="form-group row">
                <label for="name_ar" class="col-md-4 col-form-label text-md-right">{{ __('Name ( Arabic )') }}</label>

                <div class="col-md-6">
                    <input id="name_ar" type="text" class="form-control{{ $errors->has('name_ar') ? ' is-invalid' : '' }}" name="name_ar" value="{{ old('name_ar') }}">

                    @if ($errors->has('name_ar'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name_ar') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <label for="name_en" class="col-md-4 col-form-label text-md-right">{{ __('Name ( English )') }}</label>

                <div class="col-md-6">
                    <input id="name_en" type="text" class="form-control{{ $errors->has('name_en') ? ' is-invalid' : '' }}" name="name_en" value="{{ old('name_en') }}">

                    @if ($errors->has('name_en'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name_en') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ __('Add') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection