{{-- 
    Список категории для админов.
 --}}
 @extends('layouts.admin')
 @section('breadcrumb')
     @parent
     <li class="active">{{ trans('category.title') }}</li>
 @stop
 @section('content')
     <h1>{{ trans('category.title') }} <a href="{{ route('category.create.form.admin') }}" class="btn btn-sm btn-primary pull-right"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> {{ trans('category.add_category') }}</a></h1>
     @include('category.admin.filter')
     <table class="table table-striped table-bordered table-hover table-admin table-type-categories">
         <thead>
             <tr>
                 <th>ID</th>
                 <th>{{ trans('category.field.name') }}</th>
                 <th>{{ trans('category.field.status') }}</th>
                 <th>{{ trans('category.field.parent_id') }}</th>
                 <th>{{ trans('category.field.sort') }}</th>
                 <th><span class="glyphicon glyphicon-trash text-danger" aria-hidden="true"></span></th>
             </tr>
         </thead>
         <tbody>
         @foreach ($categoriesLaw as $categoryLaw)
         <tr>
             <td>{{ $categoryLaw->id }}</td>
             <td class="normal"><a href="{{ route('category.update.form.admin', $categoryLaw->id) }}">{{ $categoryLaw->name }}</a></td>
             <td>{{ $categoryLaw->statusLabel }}</td>
             <td>
                @if ($categoryLaw->parent_id)
                    <a href="{{ route('category.update.form.admin', $categoryLaw->id) }}">{{ $categoryLaw->parent->name }}</a>
                @else
                    &mdash;
                @endif
             </td>
             <td>{{ $categoryLaw->sort }}</td>
             <td><a href="{{ route('category.delete.form.admin', $categoryLaw->id) }}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
         </tr>
         @endforeach
         </tbody>
     </table>
     {{ $categoriesLaw->links() }}
 @stop
