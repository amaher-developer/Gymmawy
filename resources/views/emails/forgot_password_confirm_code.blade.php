<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <style type="text/css">
        /* CLIENT-SPECIFIC STYLES */
        body, table, td, a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        /* Prevent WebKit and Windows mobile changing default text sizes */
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        /* Remove spacing between tables in Outlook 2007 and up */
        img {
            -ms-interpolation-mode: bicubic;
        }

        /* Allow smoother rendering of resized image in Internet Explorer */

        /* RESET STYLES */
        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        /* iOS BLUE LINKS */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* MOBILE STYLES */
        @media screen and (max-width: 525px) {

            /* ALLOWS FOR FLUID TABLES */
            .wrapper {
                width: 100% !important;
                max-width: 100% !important;
            }

            /* ADJUSTS LAYOUT OF LOGO IMAGE */
            .logo img {
                margin: 0 auto !important;
            }

            /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
            .mobile-hide {
                display: none !important;
            }

            .img-max {
                max-width: 100% !important;
                width: 100% !important;
                height: auto !important;
            }

            /* FULL-WIDTH TABLES */
            .responsive-table {
                width: 100% !important;
            }

            /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
            .padding {
                padding: 10px 5% 15px 5% !important;
            }

            .padding-meta {
                padding: 30px 5% 0px 5% !important;
                text-align: center;
            }

            .no-padding {
                padding: 0 !important;
            }

            .section-padding {
                padding: 50px 15px 50px 15px !important;
            }

            /* ADJUST BUTTONS ON MOBILE */
            .mobile-button-container {
                margin: 0 auto;
                width: 100% !important;
            }

            .mobile-button {
                padding: 15px !important;
                border: 0 !important;
                font-size: 16px !important;
                display: block !important;
            }

        }

        /* ANDROID CENTER FIX */
        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }
    </style>
</head>
<body style="margin: 0 !important; padding: 0 !important;">

<!-- HEADER -->
<table border="0" cellpadding="0" cellspacing="0" width="100%">

    <tr>
        <td bgcolor="#E6E9ED" align="center" style="padding: 10px 15px 10px 15px; " class="section-padding">
            <!--[if (gte mso 9)|(IE)]>
            <table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
                <tr>
                    <td align="center" valign="top" width="500">
            <![endif]-->
            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                   style="padding-bottom: 20px; max-width: 500px;" class="responsive-table">
                <!-- TITLE -->
                <tr>
                    <td align="center"
                        style="padding: 0 0 10px 0; font-size: 25px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #333333;border-bottom:1px dashed #000"
                        class="padding" colspan="2">
                        <p>لاعادة تعيين كلمة المرور برجاء الضغط علي الرابط التالي:</p>
                        <br/>
                        <p><a href="{{route('passwordResetForm',$confirm_code)}}">{{route('passwordResetForm',$confirm_code)}}</a></p>
                    </td>
                </tr>
            </table>
            <!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </td>
    </tr>

</table>
</body>
</html>