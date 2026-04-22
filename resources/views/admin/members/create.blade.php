@extends('admin.layouts.app')

@section('title', 'Add Member')
@section('heading', 'Add Member')

@section('breadcrumbs')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Members',   'url' => route('admin.members.index')],
        ['label' => 'Add Member'],
    ]"/>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.members.store') }}" enctype="multipart/form-data">
    @csrf
    @include('admin.members._form', ['member' => new \App\Models\Member()])
</form>
@endsection
