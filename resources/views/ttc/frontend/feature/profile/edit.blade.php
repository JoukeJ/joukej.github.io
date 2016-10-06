@extends('ttc.frontend.feature.layout.master')

@section('content')
    @errors

    <form method="post" action="{{ URL::route('profile.update',$identifier) }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        <input type="hidden" name="_method" value="put"/>

        <p>{{ trans('frontend/profile/edit.title') }}</p>

        <div class="form-row">
            <strong>{{ trans('frontend/profile/edit.labels.name') }}</strong>:<br/>
            <input type="text" name="name" value="{{ Input::old('name',$profile->name) }}"/>
        </div>

        <div class="form-row">
            <strong>{{ trans('frontend/profile/edit.labels.birthday') }}</strong><br/>

            {{ trans('frontend/profile/edit.labels.birthday_year') }}:<br/>
            <select name="year" id="year">
                <option value="-">{{ trans('frontend/profile/edit.labels.birthday_year') }}</option>
                @foreach(range(2015, 1900) as $year)
                    <option value="{{ $year }}" @if($profile->birthday != null && \Carbon\Carbon::parse($profile->birthday)->year == $year) selected @endif>{{$year}}</option>
                @endforeach
            </select>

            {{ trans('frontend/profile/edit.labels.birthday_month') }}
            <select name="month" id="month">
                <option value="-">{{ trans('frontend/profile/edit.labels.birthday_month') }}</option>
                @foreach(range(1,12) as $month)
                    <option value="{{ $month }}" @if($profile->birthday != null && \Carbon\Carbon::parse($profile->birthday)->month == $month) selected @endif>{{$month}}</option>
                @endforeach
            </select>

            {{ trans('frontend/profile/edit.labels.birthday_day') }}
            <select name="day" id="day">
                <option value="-">{{ trans('frontend/profile/edit.labels.birthday_day') }}</option>
                @foreach(range(1, 31) as $day)
                    <option value="{{ $day }}" @if($profile->birthday != null && \Carbon\Carbon::parse($profile->birthday)->day == $day) selected @endif>{{$day}}</option>
                @endforeach
            </select>

        </div>

        <div class="form-row">
            <strong>{{ trans('frontend/profile/edit.labels.gender') }}</strong>:<br/>
            <select name="gender">
                @foreach(config('ttc.profile.genders') as $gender)
                    <option @if($gender == Input::old('gender',$profile->gender)) selected @endif value="{{ $gender }}">
                        {{ $gender }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-row">
            <strong>{{ trans('frontend/profile/edit.labels.country') }}</strong>:<br/>
            <select name="geo_country_id">
                @foreach($countries as $cid => $c)
                    <option value="{{ $cid }}"
                            @if($cid == Input::old('geo_country_id',$profile->geo_country_id)) selected @endif>
                        {{ $c }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-row">
            <strong>{{ trans('frontend/profile/edit.labels.city') }}</strong>:<br/>
            <input type="text" name="geo_city" value="{{ Input::old('geo_city',$profile->geo_city) }}"/>
        </div>

        <input type="hidden" name="geo_lat" id="geo_lat"/>
        <input type="hidden" name="geo_lng" id="geo_lng"/>

        <br/>
        <input type="submit" name="submit" value="{{ trans('frontend/profile/edit.buttons.ok') }}"/>
    </form>

@endsection
