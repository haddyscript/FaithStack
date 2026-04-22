@extends('admin.layouts.app')

@section('title', 'Edit ' . $member->full_name)
@section('heading', 'Edit Member')

@section('breadcrumbs')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Members',   'url' => route('admin.members.index')],
        ['label' => $member->full_name, 'url' => route('admin.members.show', $member)],
        ['label' => 'Edit'],
    ]"/>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.members.update', $member) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.members._form')
</form>
@endsection
