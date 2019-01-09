{{-- 
Category confirm delete form
--}}
@extends('layouts.admin')
@section('content')
    @include('form.confirm.delete', ['route' => ['theme.delete.admin', ['id' => $theme->id]], 'name' => $theme->name])
@stop
