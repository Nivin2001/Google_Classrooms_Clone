<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width ,initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <x-main-layout title="2FA">
        @if(!$user->two_factor_secret && $user->two_factor_confirmed_at )
        <h3>Recovery Codes </h3>
        <ul>
            @foreach($user->recoveryCodes() as $code)
        <li> {{$code}}</li>
        @endforeach

        </ul>
        <form action="{{route('tow-factor.disable')}}" method="post">
            @csrf
            @method('delete')
            <button class="btn btn-primary">Disable 2FA</button>
        </form>
        <!-- <form action="{{route('tow-factor.enable')}}" method="post">
            @csrf
            <button class="btn btn-primary">Enable 2FA</button>
        </form> -->

        @else
        @if(session('status')=='two-factor-authentication-enabled')
        <div class="mb-4 font-medium text-sm">
            Please finish configuring two factor authentication below.
            <div>
                {!! $user()->twoFactorQrCodeSvg() !!}
                <form action="{{route('tow-factor.two-factor.confirm')}}" method="post">
                    @csrf
                    <p>
                        Enter code to confirm enable 2FA
                    </p>
                    <input type="text" name="code">
                    <button class="btn btn-primary">Confirm</button>
                </form>
                @else
                <form action="{{route('tow-factor.two-factor.confirm')}}" method="post">
                    @csrf

                    <button class="btn btn-primary">Enable 2FA</button>
                </form>
                @endif
                @endif

    </x-main-layout>
</body>