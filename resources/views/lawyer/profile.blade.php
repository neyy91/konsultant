{{-- 
    Личная страница юриста.
 --}}
@php
    $user = $lawyer->user;
    $education = $lawyer->education;
@endphp

@extends('layouts.app')
@extends('layouts.page.one')

@section('breadcrumb')
    @parent
    <li><a href="{{ route('lawyers') }}">{{ trans('lawyer.lawyers') }}</a></li>
    <li class="active">{{ $user->display_name }}</li>
@stop

@section('end')
    @parent
    <script src="{{ asset2('assets/scripts/user.js') }}"></script>
    <script>
        $(function() {
            @if ($guest = Auth::guest())
                $('#setUrlUser').html('<input type="text" name="url" value="url" class="form-control form-field-url" id="registrationUrl">');
            @endif
            @if (request()->input('thanking'))
                $('#thanking').modal('show');
            @endif
        });
    </script>
@stop

@unlessmacros('input')
    @include('form.fields')
@endif

@section('content')
    <div class="profile-lawyer">
        @include('lawyer.info', ['lawyer' => $lawyer, 'liked' => $liked])

        {{-- О юристе и стоимость --}}
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <div class="panel panel-default profile-lawyer-about-panel">
                    <div class="panel-heading">
                        <h3 class="panel-title text-primary"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> @lang('user.about_myself')</h3>
                    </div>
                    <div class="panel-body">
                        {{ $lawyer->aboutmyself }}
                        <div class="registration">
                            <span class="text-muted registration-label">@lang('user.on_project_with')</span> <span class="registration-date">{{ $user->created_at->format('j F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4">
                <div class="panel panel-default profile-lawyer-cost-panel">
                    <div class="panel-heading">
                        <h3 class="panel-title text-primary"><span class="glyphicon glyphicon-rub" aria-hidden="true"></span> @lang('lawyer.cost_of_services')</h3>
                    </div>
                    <div class="panel-body">
                        {{ $lawyer->cost }}
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4">
                <div class="panel panel-primary profile-lawyer-pay-panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">@lang('lawyer.lawyer_help?')</h3>
                    </div>
                    <div class="panel-body">
                        @lang('lawyer.lawyer_help_description')
                    </div>
                    <div class="panel-footer">
                        <a href='#thanking' class="btn btn-primary btn-block" data-toggle="modal"><span class="glyphicon glyphicon-rub" aria-hidden="true"></span> @lang('user.thanking_lawyer')</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xs-12 col-sm-8">
                {{-- Специализация --}}
                <div class="panel panel-default profile-lawyer-specialization-panel" id="specializations">
                    <div class="panel-heading">
                        <h3 class="panel-title">@lang('lawyer.specialization')</h3>
                    </div>
                    <div class="panel-body">
                        @if ($questions['count'] > 0)
                            <div class="specializations-digrams">
                                <div class="answers-count">{{ trans_choice('answer.total_answers', $questions['count'], ['count' => $questions['count']]) }}</div>
                                <div class="row">
                                @foreach ($lawyer->specializations as $num => $spec)
                                    @php
                                        $specCount = $answerRepository->countAnsweredSpecializations($lawyer, $spec->id);
                                    @endphp
                                    @if ($num%4 == 0)
                                        <div class="clearfix"></div>
                                    @endif
                                    <div class="col-xs-6 col-sm-3">
                                        <div class="specialization">
                                            <div class="specialization-diagram">
                                                <div class="specialization-procent">{{ round($specCount*100/$questions['count']) }}%</div>
                                            </div>
                                            <div class="specializations-name">{{ $spec->name }}</div>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                            </div>
                            <hr>
                        @endif
                        <div class="specializations">
                            @php
                                $count = $lawyer->specializations->count();
                            @endphp
                            <h4 class="specializations-title">{{ trans_choice('user.specializations_count_categories', $count, ['count' => $count]) }}</h4>
                            <div class="row">
                                @foreach ($lawyer->specializations as $spec)
                                    @php
                                        if ($spec->parent) {
                                            $parent = $spec->parent;
                                            $childs = collect([$spec]);
                                        }
                                        else {
                                            $parent = $spec;
                                            $childs = $parent->childs;
                                        }
                                    @endphp
                                    <div class="col-xs-6">
                                        <h5 class="specialization"><a href="{{ route('questions.category', ['category' => $parent]) }}" class="specialization-link">{{ $parent->name }}</a></h5>
                                        @if ($childs->count() > 0)
                                            <ul class="specialization-childs">
                                            @foreach ($childs as $child)
                                                <li class="specialization-child"><a href="{{ route('questions.category', ['category' => $child]) }}" class="specialization-child-link">{{ $child->name }}</a></li>
                                            @endforeach
                                            </ul>
                                        @else
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Последние отзывы --}}
                @if ($liked['count']['all'] > 0)
                    <div class="panel panel-default profile-lawyer-likes-panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">@lang('like.last_likes_total_count', ['total' => $liked['count']['all']])</h3>
                        </div>
                        <div class="panel-body">
                            @include('like.items', ['likes' => $liked['latest']])
                            <div class="clearfix"></div>
                            @if ($liked['count']['all'] > 4)
                                <a href="{{ route('lawyer.liked', ['lawyer' => $lawyer]) }}" class="pull-right likes">@lang('like.view_all_liked_lawyer')</a>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="likes-empty">@lang('like.not_found')</div>
                @endif

                {{-- Последние вопросы с ответами юриста --}}
                @if ($questions['count'] > 0)
                    <div class="profile-lawyer-last-questions">
                        <h3 class="title">@lang('question.last_cunsultations_total_count', ['total' => $questions['count']])</h3>
                        <div class="questions">
                            @include('question.items', ['questions' => $questions['take'], 'categoryLawShow' => true])
                        </div>
                        <a class="questions-more" href="{{ route('lawyer.questions', ['lawyer' => $lawyer]) }}">@lang('question.view_all_counsultations')</a>
                    </div>
                @endif

            </div>
            {{-- Образование --}}
            @if ($education)
            <div class="col-xs-12 col-sm-4">
                <div class="panel @if ($education && $education->checked) panel-success @else panel-warning @endif profile-lawyer-education-panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">@if ($education && $education->checked) <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span> @lang('user.education_checked') @else @lang('user.education_no_checked') @endif</h3>
                    </div>
                    <div class="panel-body">
                        @if ($education)
                            <dl class="dl-horizontal ">
                                <dt>@lang('user.form.education.country')</dt>
                                <dd>{{ $education->country }}</dd>
                                <dt>@lang('user.form.education.city')</dt>
                                <dd>{{ $education->city }}</dd>
                                <dt>@lang('user.form.education.university')</dt>
                                <dd>{{ $education->university }}</dd>
                                <dt>@lang('user.form.education.faculty')</dt>
                                <dd>{{ $education->faculty }}</dd>
                                <dt>@lang('user.form.education.year')</dt>
                                <dd>{{ $education->year }}</dd>
                            </dl>
                        @else
                            <div class="text-muted">@lang('app.no_data')</div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="thanking">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">@lang('user.thanking_lawyer')</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ route('lawyers.thanking') }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="lawyer" value="{{ $lawyer->id }}">
                        @if ($guest)
                            <div id="setUrlUser" class="hidden"></div>
                            <p><b>@lang('pay.contacts_pay')</b></p>
                            @macros(input, 'firstname', null, ['form' => 'user', 'required' => true, 'label' => false, 'placeholder' => trans('user.form.firstname'), 'class' => 'input-lg'])
                            @macros(input, 'email', null, ['form' => 'user', 'required' => true, 'label' => false, 'placeholder' => trans('user.form.email'), 'class' => 'input-lg'])
                        @endif
                        <div class="form-group">
                            <div class="input-group">
                                @php
                                    $pays = config('site.lawyer.pay', [100, 200, 400, 900]);
                                @endphp
                                @macros(select, 'sum', null, ['form' => 'user', 'required' => true, 'label' => false, 'items' => array_combine($pays, $pays), 'label_first' => trans('pay.select_sum'), 'group' => false, 'class' => 'input-lg'])
                                <div class="input-group-addon"><span class="glyphicon glyphicon-rub toggle-tooltip" title="@lang('app.ruble')" aria-hidden="true"></span></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-block btn-lg"><span class="glyphicon glyphicon-rub" aria-hidden="true"></span> @lang('user.thanking')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
