@extends('layouts.app')

@section('htmlheader_title')
    Home
@endsection


@section('main-content')
    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Infusionsoft Settings:
                    </div>
                    <div class="panel-body">
                        @if( ! $connected  )
                            <a class="btn  btn-primary"
                               href="{{ $infusion_auth_url }}">{{ trans('Connect to Infusionsoft') }}</a>
                        @else
                            <a class="btn  btn-primary"
                               href="{{ $infusion_auth_url }}">{{ trans('Reconnect to infusionsoft') }}</a>
                        @endif
                    </div>
                    <div class="panel-body">
                        {{ trans('adminlte_lang::message.logged') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
