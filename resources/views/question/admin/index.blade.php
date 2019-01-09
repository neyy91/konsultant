{{-- 
    Список вопросов для админов.
 --}}
 @extends('layouts.admin')
 @section('title', trans('question.title'))
 @section('breadcrumb')
     @parent
     <li class="active">{{ trans('question.title') }}</li>
 @stop
 @section('content')
     <h1>{{ trans('question.title') }}</h1>
     @include('question.admin.filter')
     <table class="table table-striped table-bordered table-hover table-admin table-type-questions">
         <thead>
             <tr>
                 <th>ID</th>
                 <th class="normal">{{ trans('question.field.title') }}</th>
                 <th>{{ trans('question.field.status') }}</th>
                 <th>{{ trans('question.field.sticky') }}</th>
                 <th>{{ trans('question.field.created_at') }}</th>
                 <th>{{ trans('question.field.category_law_id') }}</th>
                 <th>{{ trans('question.field.city_id') }}</th>
                 <th class="action"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></th>
                 <th class="action"><span class="glyphicon glyphicon-trash text-danger" aria-hidden="true"></span></th>
             </tr>
         </thead>
         <tbody>
         @foreach ($questions as $question)
         <tr>
             <td>{{ $question->id }}</td>
             <td class="normal"><a href="{{ route('question.update.form.admin', $question->id) }}">{{ $question->title }}</a></td>
             <td>{{ $question->statusLabel }}</td>
            <td align="center">{{ trans('app.' . ($question->sticky ? 'yes' : 'no')) }}</td>
             <td>{{ $question->created_at->format('d.m.Y H:i') }}</td>
             <td>{{ $question->categoryLaw->name }}</td>
             <td>{{ $question->city->name }}</td>
             <td class="action"><a href="{{ route('question.view', ['question' => $question]) }}" class="btn btn-default btn-xs" target="_blank"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a></td>
             <td class="action"><a href="{{ route('question.delete.form.admin', ['id' => $question->id]) }}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
         </tr>
         @endforeach
         </tbody>
     </table>
     {{ $questions->links() }}
 @stop
