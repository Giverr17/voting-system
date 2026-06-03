<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>OTP</title>
</head>
<body style="background-color:#f3f4f6; padding:40px; font-family:Arial, sans-serif;">

    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center">
                <table width="600"
                       style="background:#ffffff; border-radius:12px; padding:32px; box-shadow:0 10px 25px rgba(0,0,0,0.1);">

                    {{-- Logo --}}
                    <tr>
                        <td style="text-align:center; padding-bottom:24px;">
                            <img src="{{ asset('images/Aces.png') }}" width="80" alt="Aces Portal Logo">
                        </td>
                    </tr>

                    {{-- Greeting --}}
                    <tr>
                        <td>
                            <h1 style="font-size:22px; color:#111827; margin-bottom:12px;">
                                Hello {{ $user->username }},
                            </h1>

                            <p style="color:#4b5563; font-size:15px; line-height:1.6;">
                                Thank you for registering in the election.
                                Please use the OTP below to login and cast your vote.
                            </p>
                        </td>
                    </tr>

                    {{-- OTP Code --}}
                    <tr>
                        <td style="text-align:center; padding:30px 0;">
                            <p style="color:#6b7280; font-size:14px; margin-bottom:12px;">
                                Your One-Time Password
                            </p>
                            <div style="
                                display:inline-block;
                                background:#f0fdf4;
                                border:2px dashed #16a34a;
                                border-radius:12px;
                                padding:16px 40px;
                            ">
                                <span style="
                                    font-size:36px;
                                    font-weight:900;
                                    letter-spacing:10px;
                                    color:#15803d;
                                    font-family:monospace;
                                ">
                                    {{ $user->code }}
                                </span>
                            </div>
                            <p style="color:#9ca3af; font-size:12px; margin-top:12px;">
                                Do not share this code with anyone
                            </p>
                        </td>
                    </tr>

                    {{-- Warning --}}
                    <tr>
                        <td style="
                            background:#fef9c3;
                            border-left:4px solid #ca8a04;
                            padding:12px 16px;
                            border-radius:4px;
                            margin-bottom:20px;
                        ">
                            <p style="color:#713f12; font-size:13px; margin:0;">
                                ⚠️ This code is unique to you. Keep it safe and do not share it.
                            </p>
                        </td>
                    </tr>

                    {{-- Divider --}}
                    <tr>
                        <td>
                            <hr style="border:none; border-top:1px solid #e5e7eb; margin:30px 0;">
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="text-align:center;">
                            <p style="color:#9ca3af; font-size:12px;">
                                © {{ date('Y') }} {{ config('app.name') }} — Class Election Committee
                            </p>
                            <p style="color:#d1d5db; font-size:11px; margin-top:4px;">
                                If you did not register for this election, please ignore this email.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
