@extends('admin.layouts.app')

@section('title', 'New Event')
@section('heading', 'New Event')

@section('breadcrumbs')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Events',    'url' => route('admin.events.index')],
        ['label' => 'New Event'],
    ]"/>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data">
    @csrf
    @include('admin.events._form', ['event' => new \App\Models\Event()])
</form>
@endsection
