

<div >
    <h1>لديك رسالة من اتصل بنا</h1>

    <p>
        <label>الموضوع </label>
        <br/>
        @if(isset($title) && !empty($title))
            {{$title}}

        @endif
    </p>

    <p>
        <label>الاسم </label>
        <br/>
        @if(isset($name) && !empty($name))
            {{$name}}
        @endif
    </p>

    <p>
        <label>الهاتف </label>
        <br/>
        @if(isset($phone) && !empty($phone))
            {{$phone}}
        @endif
    </p>

    <p>
        <label>البريد الاليكتروني </label>
        <br/>
        @if(isset($email) && !empty($email))
            {{$email}}
        @else
            Not Found
        @endif
    </p>


    <p>
        <label>المحتوي </label>
        <br/>
        @if(isset($msg) && !empty($msg))
           {!! $msg !!}
        @endif
    </p>
</div>