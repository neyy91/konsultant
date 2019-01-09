{{-- 
    Таблица с ответами на определенные типы данных, доступная для админов.
 --}}
 @extends('layouts.admin')
 @section('breadcrumb')
     @parent
     <li class="active">{{ trans('answer.title') }}</li>
 @stop
 @section('content')
     <h1>{{ trans('answer.title') }}</h1>
     @include('answer.admin.filter')
     <table class="table table-striped table-bordered table-hover table-admin table-type-questions-answers">
         <thead>
             <tr>
                 <th>ID</th>
                 <th>{{ trans('answer.field.text') }}</th>
                 <th>{{ trans("answer.field.is.{$type}") }}</th>
                 <th>{{ trans('answer.field.created_at') }}</th>
                 <th class="action"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></th>
                 <th class="action"><span class="glyphicon glyphicon-trash text-danger" aria-hidden="true"></span></th>
             </tr>
         </thead>
         <tbody>
         @foreach ($answers as $answer)
         <tr>
             <td>{{ $answer->id }}</td>
             <td class="normal">{{ str_limit($answer->text, 150) }}</td>
             <td class="text-center">{{ trans('app.' . ($answer->is ? 'yes' : 'no')) }}</td>
             <td>{{ $answer->created_at->format(config('site.date.middle', 'd.m.Y H:i')) }}</td>
             <td class="action"><a href="{{ route('answer', ['answer' => $answer]) }}" class="btn btn-default btn-xs" target="_blank"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a></td>
             <td class="action"><a href="{{ route('answer.delete.form.admin', $answer->id) }}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
         </tr>
         @endforeach
         </tbody>
     </table>
     {{ $answers->links() }}
 @stop
