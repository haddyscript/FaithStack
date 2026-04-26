@extends('admin.layouts.app')

@section('title', 'Edit Event')
@section('heading', 'Edit Event')

@section('breadcrumbs')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Events',    'url' => route('admin.events.index')],
        ['label' => $event->title, 'url' => route('admin.events.show', $event)],
        ['label' => 'Edit'],
    ]"/>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.events.update', $event) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('admin.events._form')
</form>
@endsection
