@extends('layouts.app')

@section('content')
    <approve-status v-if="{{$user->role}} == 0 && {{$user->approved}} == 0"></approve-status>
    <div v-if="{{$user->role}} == 1" class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Status</div>

                    <div class="card-body">
                        <status ck="{{$setting->ck}}"></status>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-2">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Register API Urls</div>

                    <div class="card-body">
                        <api-form
                            ck="{{$setting->ck}}"
                            cs="{{$setting->cs}}"
                            sc="{{$setting->shortcode}}"
                            vurl="{{$setting->vURL}}"
                            aurl="{{$setting->aURL}}"
                        ></api-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
