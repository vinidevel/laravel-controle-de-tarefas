@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Agora falta pouco ! Precisamos apenas que você valide seu e-mail.</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                           Reenviamos um e-mail pra você com o link de validação.
                        </div>
                    @endif

                    Antes de utlizar os recursos desse sistema, por favor valide seu registro por meio do link de verificação que encaminhamos para o seu e-mail. 
                    <br>
                    Caso você não tenha recebido um e-mail de verificação, por favor clique no link abaixo para receber um novo e-mail.
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">Clique aqui</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
