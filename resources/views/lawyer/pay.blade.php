{{-- Форма оплаты юристу --}}

@extends('layouts.app')
@extends('layouts.page.one')

@php
    $title = trans('pay.services.lawyer') . '. ' . trans('pay.invoice_number', ['number' => $pay->id]);
@endphp

@section('breadcrumb')
    @parent
    <li class="active">{{ $title }}</li>
@stop

@include('form.fields')

@section('content')
    <div class="row" id="payNow">
        <div class="col-xs-12 col-sm-8 col-sm-offset-2">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h1 class="panel-title">{{ $title }}</h1>
                </div>
                <div class="panel-body">
                    @include('pay.form', [
                        'user' => $pay->user,
                        'order' => $pay->id,
                        'url' => [
                            'default' => route('lawyer', ['lawyer' => $pay->service]),
                            'success' => route('lawyer', ['lawyer' => $pay->service, 'payed' => 1]),
                            'fail' => route('lawyer', ['lawyer' => $pay->service, 'failed' => 1]),
                        ],
                        'type' => 'lawyer',
                        'pay' => [
                            'readonly' => true,
                            'value' => $pay->cost,
                        ],
                    ])
                </div>
            </div>
        </div>
    </div>
@stop