{{--
    Список вопросов постранично.
--}}
@extends('layouts.app')
@extends('layouts.page.lawyer')

@section('breadcrumb')
    @parent
    <li><a href="{{ route('user.dashboard') }}">{{ trans('user.dashboard') }}</a></li>
    <li class="active">@lang('bookmark.title')</li>
@stop

@section('end')
    @parent
    <script src="{{ asset2('assets/scripts/list-general.js') }}"></script>
    <script>
        $(function() {
            $('#manageCategories').on('hide.bs.modal', function() {
                var $this = $(this), reload = $this.data('reload-hide'), href = $this.data('href-hide');
                if (href) {
                    document.location.href = href;
                    return;
                }
                if (reload) {
                    document.location.reload(false);
                    return;
                }
            });
        });
    </script>
@stop

@section('content')
    <h1>@lang('bookmark.title')@if ($category) <span class="small">{{ $category->name }}</span> @endif</h1>
    <div class="bookmarks-filter">
        <div class="row">
            <div class="col-xs-10 col-sm-4">
                <div class="dropdown dropdown-style filter-dropdown-bookmarks">
                    <a href="#" id="bookmarkSelect" class="btn btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@if ($category) {{ $category->name }} @else @lang('bookmark.all_bookmarks') @endif <span class="glyphicon glyphicon-chevron-down icon-down" aria-hidden="true"></span> <span class="glyphicon glyphicon-chevron-up icon-up" aria-hidden="true"></span></a>
                    <ul class="dropdown-menu dropdown-categories" aria-labelledby="bookmarkSelect">
                        @include('bookmark.dropdown_category_links', ['category' => $category, 'categories' => $categories])
                    </ul>
                </div>
            </div>
            <div class="col-xs-2 col-sm-6">
                @if ($management)
                    @section('end')
                        @parent
                        <script>
                            $(function() {
                                $('#manageCategories').modal('show').data('href-hide', '{{ route('user.bookmarks') }}');
                            });
                        </script>
                    @stop
                @endif
                <a class="script-action management-action" data-toggle="modal" href='#manageCategories'><span class="hidden-xs">@lang('bookmark.management_bookmark_categories')</span><span class="visible-xs"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></span></a>
                <div class="modal fade categories-management-parent" id="manageCategories" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">@lang('bookmark.management_bookmark_categories')</h4>
                            </div>
                            <div class="modal-body categories-content">
                                @include('bookmark.category_list')
                            </div>
                            <div class="modal-footer">
                            @include('bookmark.form', ['type' => 'modal'])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('question.items', ['questions' => $questions, 'statusShow' => true, 'categoryLawShow' => true, 'bookmarkShow' => true, 'from' => 'bookmarks', 'empty' => trans('bookmark.not_found')])
@stop