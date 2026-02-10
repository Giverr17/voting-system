<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Election Results</title>
</head>
<body style="background-color:#f3f4f6; padding:40px; font-family:Arial, sans-serif;">

    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center">

                <table width="100%" max-width="600px"
                       style="background:#ffffff; border-radius:12px; padding:32px; box-shadow:0 10px 25px rgba(0,0,0,0.1);">

                    <tr>
                        <td style="text-align:center;">
                            <img src="{{ asset('images/Aces.png') }}" width="80" alt="Election Logo"
                                 style="margin-bottom:20px;">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <h1 style="font-size:22px; color:#111827; margin-bottom:12px;">
                                Hello {{ $user->username }},
                            </h1>

                            <p style="color:#4b5563; font-size:15px; line-height:1.6;">
                                Thank you for registrating in the election.
                               Please use this OTP to login and participate in the election.
                            </p>

                            <div style="text-align:center; margin:30px 0;">
                              {{ $user->code }}
                            </div>

                           

                            <hr style="border:none; border-top:1px solid #e5e7eb; margin:30px 0;">

                            <p style="color:#9ca3af; font-size:12px; text-align:center;">
                                © {{ date('Y') }} Class Election Committee
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
