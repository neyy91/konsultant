{{-- 
    Корзина с вопросами.
--}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('trash') }}">{{ trans('trash.title') }}</a></li>
    <li class="active">{{ trans("{$type}.title") }}</li>
@stop
 @section('content')
     <h1>{{ trans('trash.title') }} <span class="small">{{ trans("{$type}.title") }}</span> <div class="pull-right">@include('admin.trash.clean', ['route' => "trash.{$type}.clean", 'btn' => 'sm', 'text' => true])</div></h1>
     <table class="table table-striped table-bordered table-hover table-admin table-type-trash-{{ $type }}">
         <thead>
             <tr>
                 <th>ID</th>
                 <th class="normal">{{ trans("{$type}.field.title") }}</th>
                 <th>{{ trans("{$type}.field.created_at") }}</th>
                 <th>{{ trans("{$type}.field.deleted_at") }}</th>
                 <th class="action">{{ trans('trash.restore') }}</th>
             </tr>
         </thead>
         <tbody>
         @foreach ($items as $item)
         <tr>
             <td>{{ $item->id }}</td>
             <td class="normal">{{ $item->title }}</td>
             <td>{{ $item->created }}</td>
             <td>{{ $item->deleted }}</td>
             <td class="action"><form action="{{ route("trash.{$type}.restore", ['id' => $item->id]) }}" method="POST" role="form">{{ csrf_field() }}{{ method_field('PUT') }}<button type="submit" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-circle-arrow-up" aria-hidden="true"></span></button></form></td>
         </tr>
         @endforeach
         </tbody>
     </table>
     {{ $items->links() }}
 @stop
