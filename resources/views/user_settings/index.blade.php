@extends('app')

@section('pageTitle', trans('message.user_setting'))
@section('contentTitle', trans('message.user_setting'))

@section('content')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">{{trans('message.user_details')}}</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ route('userDetailsUpdate') }}" method="POST" id="user_details_form" name="user_details_form" autocomplete="off" enctype="multipart/form-data" class="form-horizontal" >
                        @csrf
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="email" class="col-sm-2 col-form-label">{{trans('message.email')}}</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{old('email', @$postBackData->email) }}" class="form-control" readonly id="email" name="email" placeholder="{{trans('message.email')}}">
                                    <div id="email_validate"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">{{trans('message.name')}}</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{old('name', @$postBackData->name) }}" class="form-control" id="name" name="name" placeholder="{{trans('message.name')}}">
                                    <div id="name_validate"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="user_type" class="col-sm-2 col-form-label">{{trans('message.user_type')}}</label>
                                <div class="col-sm-10">
                                    <select id="user_type" class="form-control"  name="user_type">
                                        <option value="">{{trans('message.select_user_type')}}</option>
                                        @php
                                            $userType = old('user_type', @$postBackData->user_type);
                                            for($i = 1; $i < 4; $i++){
                                        @endphp
                                            <option value="{{$i}}" <?php echo $userType == $i ? "selected" : ""; ?>>User Type {{$i}}</option>
                                        @php
                                            }
                                        @endphp
                                    </select>
                                    <div id="user_type_validate"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="gender" class="col-sm-2 col-form-label">{{trans('message.gender')}}</label>
                                <div class="col-sm-10">
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" name="gender" id="male" value="male" {{@$postBackData->gender == 'male' ? 'checked' :''}}>
                                            <label for="male">
                                            Male
                                            </label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" name="gender" id="female" value="female" {{@$postBackData->gender == 'female' ? 'checked' :''}}>
                                            <label for="female">
                                            Female
                                            </label>
                                        </div>
                                        <div id="gender_validate"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="profile" class="col-sm-2 col-form-label">{{trans('message.profile_photo')}}</label>
                                <div class="col-sm-10">
                                    <div class="custom-file">
                                        <input type="file" class="" name="profile" id="profile">
                                        <div id="profile_validate"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="aboutme" class="col-sm-2 col-form-label">{{trans('message.about_me')}}</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="aboutme" name="aboutme" placeholder="{{trans('message.about_me')}}">{{old('aboutme', @$postBackData->aboutme) }}</textarea>
                                    <div id="aboutme_validate"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="language" class="col-sm-2 col-form-label changeLang">{{trans('message.language')}}</label>
                                <div class="col-sm-10">
                                    <select id="language" class="form-control"  name="language">
                                        <option value="en" {{ session()->get('locale') == 'en' ? 'selected' : '' }}>English</option>
                                        <option value="fr" {{ session()->get('locale') == 'fr' ? 'selected' : '' }}>France</option>
                                        <option value="sp" {{ session()->get('locale') == 'sp' ? 'selected' : '' }}>Spanish</option>
                                    </select>
                                    <div id="language_validate"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="timezone" class="col-sm-2 col-form-label">{{trans('message.timezone')}}<br><span id="livetimer">{{ \Carbon\Carbon::now()->tz(Auth::user()->timezone)->format('Y-m-d H:i:s')}}</span></label>
                                <div class="col-sm-10">
                                    <select id="timezone" class="form-control"  name="timezone">
                                        <option value="">{{trans('message.select_timezone')}}</option>
                                        @php
                                            $timezone = old('timezone', @$postBackData->timezone);
                                            foreach($timezonelist AS $tlKey => $tlValue){
                                        @endphp
                                            <option value="{{$tlKey}}" <?php echo $timezone == $tlKey ? "selected" : ""; ?>>{{$tlValue}}</option>
                                        @php
                                            }
                                        @endphp
                                    </select>
                                    <div id="timezone_validate"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="profile" class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="is_offer_news" name="is_offer_news" {{@$postBackData->is_offer_news == '1' ? 'checked' : ''}} value="1">
                                            <label for="is_offer_news">
                                            {{trans('message.keep_me_up_to_date_on_news_and_offers')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-info float-right">{{trans('message.submit')}}</button>
                        </div>
                        <!-- /.card-footer -->
                    </form>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">{{trans('message.change_password')}}</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ route('userPasswordChange') }}" method="POST" id="change_password_form" name="change_password_form" autocomplete="off" enctype="multipart/form-data" class="form-horizontal" >
                        @csrf
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="email" class="col-sm-2 col-form-label">{{trans('message.old_password') }}</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="old_password" id="old_password" placeholder="{{trans('message.old_password') }}">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="nav-icon fas fa-lock"></i></span>
                                        </div>
                                    </div>
                                    <div id="old_password_validate"></div>
                                    @if($errors->has('old_password'))
                                        <div class="error">{{ $errors->first('old_password') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-sm-2 col-form-label">{{trans('message.new_password') }}</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="new_password" id="new_password" placeholder="{{trans('message.new_password') }}">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="nav-icon fas fa-lock"></i></span>
                                        </div>
                                    </div>
                                    <div id="new_password_validate"></div>
                                    @if($errors->has('new_password'))
                                    <div class="error">{{ $errors->first('new_password') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-sm-2 col-form-label">{{trans('message.confirm_password') }}</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="cnew_password" id="cnew_password" placeholder="{{trans('message.confirm_password') }}">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="nav-icon fas fa-lock"></i></span>
                                        </div>
                                    </div>
                                    <div id="cnew_password_validate"></div>
                                    @if($errors->has('cnew_password'))
                                    <div class="error">{{ $errors->first('cnew_password') }}</div>
                                    @endif

                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-info float-right">{{trans('message.submit')}}</button>
                        </div>
                        <!-- /.card-footer -->
                    </form>
                </div>
            </div>
            @if($postBackData->subscriptions && $postBackData->subscriptions->count() && $postBackData->subscriptions)
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">{{trans('message.last_subscription_detail')}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <div class="text-right mb-10 mt-10">
                                <form action="{{route('cancel-subscription')}}" class="" method="post">
                                    @csrf
                                    <a href="{{route('update-subscription')}}" class="btn btn-success text-right">{{trans('message.update_subscription')}}</a>
                                    <input type="hidden" value="{{Utility::encode($postBackData->id)}}" name="userId">
                                    @if($postBackData->subscriptions->first() && $postBackData->subscriptions->first()->ends_at == NULL)
                                        <button type="submit" class="btn btn-danger ml-10">{{trans('message.cancel_subscription')}}</button>
                                    @endif
                                </form>
                            </div>
                            <!-- form start -->
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                    <th>{{trans('message.strip_id')}}</th>
                                    <th>{{trans('message.price')}}</th>
                                    <th>{{trans('message.duration')}}</th>
                                    <th>{{trans('message.created_date')}}</th>
                                    <th>{{trans('message.currency')}}</th>
                                    <th>{{trans('message.status')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    <td>{{$currentPlanDetails->id}}</td>
                                    <td>${{$currentPlanDetails->amount/100}}</td>
                                    <td>Per {{$currentPlanDetails->interval_count}} {{$currentPlanDetails->interval}}</td>
                                    <td>{{\Carbon\Carbon::parse($currentPlanDetails->created)->tz(Auth::user()->timezone)->format('Y-m-d H:i:s')}}</td>
                                    <td>{{strtoupper($currentPlanDetails->currency)}}</td>
                                    <td>{{($postBackData->subscriptions->first()->ends_at  != NULL ? 'Canclled' : 'Active')}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@stop

@section('javascript')
<script type="text/javascript" src="{!! url('resource', ['js','user_settings']); !!}"></script>
@endsection