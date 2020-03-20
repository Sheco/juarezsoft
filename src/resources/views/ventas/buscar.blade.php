@extends('layouts.app')
@section('content')
    <div class="container">
        <form method="post" action="{{ route('ventas.buscar') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Ticket#</label>
                    {{ Form::text('id', null, ['class'=>'form-control']) }}
                </div>

                <div class="col-md-6 form-group">
                    <label>Fecha</label>
                    {{ Form::date('fecha', null, ['class'=>'form-control']) }}
                </div>

                <div class="col-md-6 form-group">
                    <label>Total</label>
                    {{ Form::text('total', null, ['class'=>'form-control']) }}
                </div>

                <div class="col-md-6 form-group">
                    <label>&nbsp;</label>
                    <input type="submit" value="Buscar" class="btn btn-success form-control">
                </div>
            </div>
        </form>
    </div>
@endsection
