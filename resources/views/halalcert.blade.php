@extends('site.app')
@section('title', 'Supplier Halal Certification')
@section('content')


    <!--=============================================
    =            breadcrumb area         =
    =============================================-->

    <div class="breadcrumb-area mb-50">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-container">
                        <ul>
                            <li><a href="{{route('site.pages.homepage')}}"><i class="fa fa-home"></i> Home</a></li>
                            <li class="active">Halal Certification </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--=====  End of breadcrumb area  ======-->

    <div class="faq-area page-content mb-50">
        <div class="container">
            <h1 style="text-align: center; margin-top: 20px;"> Halal Certification </h1>
            <div class="card-body">
                @php
                    $text = App\Models\Page::where('name','halalcert')->first()->text;
                @endphp
                <p>{!! (new Parsedown)->text($text) !!}</p>
            </div>
        </div>
    </div>


@stop
