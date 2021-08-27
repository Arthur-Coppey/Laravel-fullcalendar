@extends('layouts.base')

@section('title', 'Laravel-FullCalendar')

@section('head')
    @include('fullcalendar.deps')
    @include('fullcalendar.script')
@endsection

@section('content')
    <div id="calendar"></div>
    @include('fullcalendar.footer')
@endsection
