@inject('request', 'Illuminate\Http\Request')
@extends('app')

@section('pageTitle', trans('message.subscription_list'))
@section('contentTitle', trans('message.subscription_list'))

@section('customcss')
<style>
    .StripeElement {
        background-color: white;
        padding: 8px 12px;
        border-radius: 4px;
        border: 1px solid transparent;
        box-shadow: 0 1px 3px 0 #e6ebf1;
        -webkit-transition: box-shadow 150ms ease;
        transition: box-shadow 150ms ease;
    }
    .StripeElement--focus {
        box-shadow: 0 1px 3px 0 #cfd7df;
    }
    .StripeElement--invalid {
        border-color: #fa755a;
    }
    .StripeElement--webkit-autofill {
        background-color: #fefde5 !important;
    }
</style>
@endsection
@section('content')
@php
    if($type == 'create')
        $mytitle = trans('message.purchase_plan');
    else 
        $mytitle = trans('message.update_plan');
@endphp
<section class="content">
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{$mytitle}}</h3>
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="card-body">
                    <!-- /.card-header -->
                    @if($type == 'create')
                        <form action="{{url('/subscribe')}}" method="POST" id="subscribe-form">
                    @else
                        <form action="{{url('/update-subscription')}}" method="POST" id="subscribe-form">
                        @method('put')
                    @endif
                        <div class="form-group row">
                            <label for="plan" class="col-sm-2 col-form-label">{{trans('message.plan')}}</label>
                            <div class="col-sm-10">
                                <select id="plan" class="form-control"  name="plan">
                                    <option value="">{{trans('message.select_plan')}}</option>
                                    @foreach($plans as $plan)
                                        @php
                                        $selected = '';
                                        if($type == 'update' && $plan->id == $user->subscriptions->first()->stripe_price)
                                            $selected = 'selected';   
                                        @endphp

                                        <option value='{{$plan->id}}' {{$selected}}>${{$plan->amount/100}}/per {{$plan->interval_count}} {{$plan->interval}}</option>
                                    @endforeach
                                </select>
                                <div id="plan_validate"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="card_holder_name" class="col-sm-2 col-form-label">{{trans('message.card_holder_name')}}</label>
                            <div class="col-sm-10">
                                <input type="text" value="{{old('card_holder_name', @$postBackData->card_holder_name) }}" class="form-control" id="card_holder_name" name="card_holder_name" placeholder="{{trans('message.card_holder_name')}}">
                                <div id="card_holder_name_validate"></div>
                            </div>
                        </div>
                        @csrf

                        <div class="form-group row">
                            <label for="card_holder_name" class="col-sm-2 col-form-label">{{trans('message.credit_or_debit_card')}}</label>
                            <div class="col-sm-10">
                                <div id="card-element" class="form-control"></div>
                                <!-- Used to display form errors. -->
                                <div id="card-errors" class="error" role="alert"></div>
                            </div>
                        </div>
                        <div class="stripe-errors"></div>
                        @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                            @endforeach
                        </div>
                        @endif
                        <div class="form-group text-center">
                            <button type="button" id="card-button" data-secret="{{ $intent->client_secret }}" class="btn btn-lg btn-success btn-block">SUBMIT</button>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
        <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div>


@stop

@section('javascript') 
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript" src="{!! url('resource', ['js','subscribe']); !!}"></script>
@endsection