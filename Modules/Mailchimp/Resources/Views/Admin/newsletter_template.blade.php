<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$mainSettings->name}}</title>
    <style>


        body {
            margin: 0
        }

        .container {
            width: 90%;
            margin: 0 auto
        }

        .mail-main {
            background-color: rgba(243, 243, 243, 1);
            padding-top: 50px;
            text-align: center
        }

        .mail-main .logo {
            margin-bottom: 50px;
            margin: 0 auto;
            display: block;
            width:200px !important; height:200px !important;
        }

        .mail-main .container .mail-description {
            color: #6a7078;
            margin-bottom: 50px;
            line-height: 25px;
        }

        .remove-padding {
            padding: 0
        }

        .grid-mian img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 8px 8px 0px 0px;
            -moz-border-radius: 8px 8px 0px 0px;
            -webkit-border-radius: 8px 8px 0px 0px;
        }

        .grid-mian .price-info {
            background: #fff;
        }

        .room-type-mial {
            padding: 5px 10px;
            color: #ffbe52;
            background: #ff602d;
            display: inline-block;
            margin-bottom: 10px;
            border-radius: 25px;
        }

        .grid-mail-des {
            color: rgba(106, 112, 120, 1);
        }

        .price-info {
            padding: 10px;
        }

        .price-info .room-options {
            font-size: 20px;
            color: rgba(106, 112, 120, 1);
            margin: 0;
            margin-bottom: 5px;
        }

        .price-info .room-info {
            font-size: 16px;
            color: rgba(106, 112, 120, 1);
            margin: 0;
            margin-bottom: 10px;
        }

        .price-info .room-price {
            font-size: 25px;
            color: rgba(106, 112, 120, 1);
            margin-top: 0;
            margin-bottom: 10px;
        }

        .mail-main .grid-mian {
            margin-bottom: 30px;
            text-decoration: none;
            cursor: pointer;
            padding: 15px;
            width: 80%;
            display: inline-block;
        }

        .mail-links a {
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            color: rgba(106, 112, 120, 1);
            margin-bottom: 20px;
            text-align: center;
            margin-right: 5%;
        }

        .mail-links {
            padding-bottom: 100px;
            text-align: center
        }

    </style>
</head>

<body>

<div class="mail-main">
    <div class="container"><img src="{{$mainSettings->logo}}" class="logo">
        <p class="mail-description">{{$msg}}</p>

        @foreach($items as $item)
            <a href="{{route('showItem',$item->id)}}" target="_blank" class="grid-mian">
                <div class="mail-room-img-mian"><img src="{{$item->main_image}}"></div>
                <div class="price-info">
                    @if($item->installment)<span class="room-type-mial">Installments</span>
                    @endif

                    <p class="room-options">{{$item->item_type->parent->name_en}}, {{$item->item_type->name_en}}</p>
                    <p class="room-info">{{$item->address}}</p>
                    <h2 class="room-price">{{number_format($item->price)}} {{trans('global.le')}}</h2>
                    <span class="grid-mail-des">{{$item->district->city->name.", ".$item->district->name}}</span>

                </div>
            </a>

        @endforeach


        <div class="mail-links"><a
                    href="{{route('unSubscribeListForm',['list_id'=>$unsubscribe_list,'email'=>'*|EMAIL|*'])}}"
                    target="_blank">Unsubscribe
                from this list</a> <a
                    href="{{route('unSubscribeListForm', ['list_id'=>'all','email'=>'*|EMAIL|*'])}}"
                    target="_blank">Unsubscribe from all lists</a>
            <a
                    href="{{route('home')}}" target="_blank">Go to the website</a></div>
    </div>
</div>
</body>
</html>