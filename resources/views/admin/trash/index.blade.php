{{-- 
    Корзина.
 --}}
 @extends('layouts.admin')
 @section('breadcrumb')
     @parent
     <li class="active">{{ trans('trash.cleaning_baskets') }}</li>
 @stop
 @section('content')
     <h1>{{ trans('trash.cleaning_baskets') }}</h1>
     <div class="row">
         <div class="col-xs-12 col-sm-4">
             <h2>{{ trans('question.title') }}</h2>
             <div class="row">
                 <div class="col-xs-6">
                     <a href="{{ route('trash.question') }}" class="btn btn-default">{{ trans('trash.view_baskets') }}</a>
                 </div>
                 <div class="col-xs-6">
                      @include('admin.trash.clean', ['route' => 'trash.question.clean', 'btn' => 'md', 'text' => true])
                 </div>
             </div>
         </div>
         <div class="col-xs-12 col-sm-4">
            <h2>{{ trans('document.title') }}</h2>
            <div class="row">
                <div class="col-xs-6">
                    <a href="{{ route('trash.document') }}" class="btn btn-default">{{ trans('trash.view_baskets') }}</a>
                </div>
                <div class="col-xs-6">
                     @include('admin.trash.clean', ['route' => 'trash.document.clean', 'btn' => 'md', 'text' => true])
                </div>
            </div>
         </div>
         <div class="col-xs-12 col-sm-4">
            {{-- TODO: звонки --}}
         </div>
     </div>
 @stop