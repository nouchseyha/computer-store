<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset OTP</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:'Segoe UI',Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:40px 0;">
        <tr>
            <td align="center">
                <table width="480" cellpadding="0" cellspacing="0"
                       style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08);">

                    {{-- Header --}}
                    <tr>
                        <td style="background:#0f172a;padding:28px 40px;text-align:center;">
                            <span style="font-size:1.3rem;font-weight:800;color:#fff;letter-spacing:-.3px;">
                                💻 Computer <span style="color:#f97316;">TK</span> Store
                            </span>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:40px 40px 32px;">
                            <h2 style="margin:0 0 8px;font-size:1.4rem;font-weight:700;color:#0f172a;">
                                Password Reset Request
                            </h2>
                            <p style="margin:0 0 24px;color:#64748b;font-size:.95rem;line-height:1.6;">
                                We received a request to reset your password. Use the OTP code below to continue.
                                This code is valid for <strong>10 minutes</strong>.
                            </p>

                            {{-- OTP Box --}}
                            <div style="text-align:center;margin:32px 0;">
                                <div style="display:inline-block;background:#f1f5f9;border:2px dashed #cbd5e1;
                                            border-radius:14px;padding:20px 48px;">
                                    <div style="font-size:2.6rem;font-weight:800;letter-spacing:12px;
                                                color:#2563eb;font-family:'Courier New',monospace;">
                                        {{ $otp }}
                                    </div>
                                </div>
                            </div>

                            <p style="margin:0 0 8px;color:#64748b;font-size:.88rem;line-height:1.6;">
                                Enter this code on the password reset page. Do not share this code with anyone.
                            </p>
                            <p style="margin:0;color:#94a3b8;font-size:.82rem;">
                                If you didn't request a password reset, you can safely ignore this email.
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background:#f8fafc;padding:20px 40px;border-top:1px solid #e2e8f0;
                                   text-align:center;color:#94a3b8;font-size:.78rem;">
                            &copy; {{ date('Y') }} Computer TK Store. All rights reserved.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
