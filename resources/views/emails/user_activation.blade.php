<!DOCTYPE html>
<html lang=" @if($lang=='ar') 'ar' @else 'en' @endif ">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$mainSettings->name}}</title>
    <style>

        body { margin:0;background-color: rgba(243, 243, 243, 1);}
        .container { width:90%; margin:0 auto}
        .mail-main {padding-top:50px; text-align:center}

        .mail-main .logo { margin-bottom:50px; margin:0 auto; display:block;
            width:200px !important; height:100px !important;object-fit: contain}
        .mail-main .container .mail-description { color:#6a7078; margin-bottom:50px;    line-height: 25px; padding:10px; border:3px solid #ff602d; text-align:left;}

        .grid-mian img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 8px 8px 0px 0px;
            -moz-border-radius: 8px 8px 0px 0px;
            -webkit-border-radius: 8px 8px 0px 0px;
        }

        .mail-description a {
            text-decoration: none;
            font-size: 20px;
            font-weight: bold;
            color: #ff602d !important;
            margin-bottom: 20px;
            text-align: center
        }
        .mail-links a {
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            color: rgba(106, 112, 120, 1);
            margin-bottom: 20px;
            text-align: center
        }

        .mail-links {
            padding-bottom: 100px;
            text-align: center
        }


    </style>
</head>

<body>

<div class="mail-main">
    <div class="container">
        <div class="mail-description" @if($lang=='ar') style="direction: rtl; text-align: right" @endif>
            <h2>{{trans('global.user_activate')}}‎</h2>
            <label>{{trans('global.hello')}}@if(isset($data['name']) && !empty($data['name']))
                    {{$data['name']}}
                @endif,</label>

<br/><br/>
            <div class="mail-links">
                <label>{{trans('global.activate_your_account')}}</label>
                <br/><br/>
                <a href ="{{route('emailActivate').'?code='.$data['token']}}">{{route('emailActivate').'?code='.$data['token']}}</a>
            </div>
        </div>

    </div>
</div>
</body>
</html>