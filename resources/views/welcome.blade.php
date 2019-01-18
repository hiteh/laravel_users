<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }
            .subtitle {
                font-weight: bold;
            }
            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            .btn-register {
                margin: 1.5rem;
                padding: 0.8rem;
                background-color: #636b6f;
                color: #ffffff;
                border: 0;
                border-radius: 0.2rem;
                font-weight: bold;
                cursor: pointer;
            }
            input {
                padding: 0.3rem;
                border-radius: 0.2rem;
                border: 1px solid #d9d9d9;
            }
            label {
                font-weight: bold;
            }
            select {
                background-color: #636b6f;
                color: #ffffff;
                border-radius: 0.2rem;
                padding: 0.1rem;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="top-right links">
                <form method="POST" action="{{ route('lang.store') }}">
                    @csrf
                    <label for="lang">{{ __('welcome.language') }}</label>
                    <select required name="lang" id="lang" onchange="event.preventDefault(); this.form.submit();">
                        <option value="en" {{ 'en' === session('lang') ? 'selected' : '' }}>{{'EN'}}</option>
                        <option value="pl" {{ 'pl' === session('lang') ? 'selected' : '' }}>{{'PL'}}</option>
                    </select>
                </form>
            </div>
            <div class="content">
                <div class="title m-b-md">
                    Laravel Users
                </div>
                <div class="subtitle m-b-md">
                    {{ __('welcome.welcome_msg') }}
                </div>
                <div>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div>
                            <label for="name">{{ __('welcome.name') }}</label>
                            <div>
                                <input id="name" type="text" name="name" value="" required autofocus>
                            </div>
                        </div>

                        <div>
                            <label for="email">{{ __('welcome.mail') }}</label>
                            <div>
                                <input id="email" type="email" name="email" value="" required>
                            </div>
                        </div>

                        <div>
                            <label for="password">{{ __('welcome.password') }}</label>
                            <div>
                                <input id="password" type="password" name="password" value="" required>
                            </div>
                        </div>

                        <div>
                            <label for="password-confirm">{{ __('welcome.confirm_password') }}</label>
                            <div>
                                <input id="password-confirm" type="password" name="password_confirmation" value="" required>
                            </div>
                        </div>

                        <div>
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn-register">
                                    {{ __('welcome.register') }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
