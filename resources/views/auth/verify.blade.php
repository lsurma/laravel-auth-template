@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify your e-mail address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    <p>
                        {{ __('Before proceeding, please check your email for a verification link.') }}
                        {{ __('If you did not receive the e-mail, fill form to request another.') }}
                    </p>

                    <form method="POST" action="{{ route('user-auth.verification.resend') }}">
                        @csrf
                        <div class="form-group">
                            <input class="form-control" placeholder="E-mail address" name="email" type="email" required /> 
                        </div>

                        <button type="submit" class="btn btn-info ">{{ __('Resend') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
