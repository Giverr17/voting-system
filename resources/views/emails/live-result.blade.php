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
                                Hello {{ $user->username ?? $user->name }},
                            </h1>

                            <p style="color:#4b5563; font-size:15px; line-height:1.6;">
                                Thank you for participating in the election.
                                The live results are now available.
                            </p>

                            <div style="text-align:center; margin:30px 0;">
                                <a href="{{ $link }}"
                                   style="
                                       background:#2563eb;
                                       color:#ffffff;
                                       padding:14px 28px;
                                       text-decoration:none;
                                       border-radius:8px;
                                       font-weight:bold;
                                       display:inline-block;
                                   ">
                                    View Live Results
                                </a>
                            </div>

                            <p style="color:#6b7280; font-size:14px;">
                                You can bookmark this page to monitor the election progress in real time.
                            </p>

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
