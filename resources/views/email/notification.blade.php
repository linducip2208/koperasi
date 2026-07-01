<!DOCTYPE html>
<html><head><meta charset="UTF-8"></head>
<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; color: #1f2937;">
    <div style="text-align: center; padding: 24px; background: linear-gradient(135deg, #059669, #0d9488); border-radius: 16px 16px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 20px;">{{ $appName }}</h1>
    </div>
    <div style="background: #fff; padding: 24px; border: 1px solid #e5e7eb; border-radius: 0 0 16px 16px;">
        <h2 style="color: #059669; margin-top: 0;">{{ $subject }}</h2>
        <p style="color: #374151; line-height: 1.7;">Halo <strong>{{ $nama }}</strong>,</p>
        <div style="color: #374151; line-height: 1.7;">{!! $body !!}</div>
        <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">
        <p style="font-size: 12px; color: #9ca3af; text-align: center;">© {{ $tahun }} {{ $appName }}. Email ini dikirim otomatis, mohon tidak membalas.</p>
    </div>
</body></html>
