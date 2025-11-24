<style>

    /* Button used to open the contact form - fixed at the bottom of the page */
    .open-button-ads {
        background-color: #555;
        color: white;
        padding: 16px 20px;
        border: none;
        cursor: pointer;
        opacity: 0.8;
        position: fixed;
        bottom: 23px;
        right: 28px;
        width: 280px;
    }

    /* The popup form - hidden by default */
    .form-popup-ads {
        display: none;
        position: fixed;
        bottom: 0;
        right: 15px;
        border: 0px solid #f1f1f1;
        z-index: 9;
    }

    /* Add styles to the form container */
    .form-container-ads {
        max-width: 300px;
        padding: 10px;
        background-color: white;
    }

    /* Full-width input fields */
    .form-container-ads input[type=text], .form-container input[type=password] {
        width: 100%;
        padding: 15px;
        margin: 5px 0 22px 0;
        border: none;
        background: #f1f1f1;
    }

    /* When the inputs get focus, do something */
    .form-container-ads input[type=text]:focus, .form-container input[type=password]:focus {
        background-color: #ddd;
        outline: none;
    }

    /* Set a style for the submit/login button */
    .form-container-ads .btn {
        background-color: #04AA6D;
        color: white;
        padding: 10px 10px;
        border: none;
        cursor: pointer;
        width: 100%;
        margin-bottom:10px;
        opacity: 0.8;
    }

    /* Add a red background color to the cancel button */
    .form-container-ads .cancel {
        background-color: #fa7e06;
    }

    /* Add some hover effects to buttons */
    .form-container-ads .btn:hover, .open-button:hover {
        opacity: 1;
    }
    .form-popup-div-ads {
        background-color: white;border-bottom: 1px solid #f1f1f1;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }
    .form-popup-img-ads {
        width: 100%;height: 200px;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }
    .AClass{
        right:10px;
        position: absolute;
    }
</style>

<div class="form-popup-ads" id="myFormAds">
    <div class="form-popup-div-ads">
        <div style="position:relative;">
            <button onclick="closeForm()" class="close AClass">
                <span>&times;</span>
            </button>
            <img src="{{asset('resources/assets/front/img/ads.png')}}" class="form-popup-img-ads" />
        </div>
    </div>
    <div class="form-container-ads">
        <h4 style="text-align: center;font-weight: bolder;margin-bottom: 15px">{{trans('global.need_coach')}}</h4>
        <p style="text-align: center">{{trans('global.ads_msg')}}</p>

        {{--        <button type="submit" class="btn">Login</button>--}}
        <a class="btn cancel" href="https://fit.gymmawy.com">{{trans('global.subscribe_now')}}</a>
    </div>
</div>
<script>
    document.getElementById("myFormAds").style.display = "block";

    function closeForm() {
        document.getElementById("myFormAds").style.display = "none";
    }
</script>
