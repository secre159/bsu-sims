<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border-radius: 0 0 8px 8px;
            border: 1px solid #e5e7eb;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #1f2937;
        }
        .credentials-box {
            background: white;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
        }
        .credential-item {
            margin: 12px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .credential-label {
            font-weight: 600;
            color: #667eea;
            min-width: 120px;
        }
        .credential-value {
            font-size: 14px;
            background: #f3f4f6;
            padding: 8px 12px;
            border-radius: 4px;
            color: #1f2937;
        }
        .warning {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            color: #92400e;
        }
        .warning strong {
            color: #d97706;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
            font-weight: 600;
        }
        .button:hover {
            opacity: 0.9;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }
        .info-box {
            background: #e0f2fe;
            border-left: 4px solid #0284c7;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            color: #0c4a6e;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎓 SIMS Portal</h1>
            <p>Student Information Management System</p>
        </div>

        <div class="content">
            <div class="greeting">
                Hello {{ $studentName }},
            </div>

            <p>Welcome to the BSU-Bokod Student Information Management System (SIMS) Portal! Your account has been created and you can now access your academic information online.</p>

            <div class="credentials-box">
                <div class="credential-item">
                    <span class="credential-label">Student ID:</span>
                    <span class="credential-value">{{ $studentId }}</span>
                </div>
                <div class="credential-item">
                    <span class="credential-label">Email:</span>
                    <span class="credential-value">{{ $email }}</span>
                </div>
                <div class="credential-item">
                    <span class="credential-label">Password:</span>
                    <span class="credential-value">{{ $password }}</span>
                </div>
            </div>

            <div class="warning">
                <strong>⚠️ Important:</strong> This is a temporary password. Please change it immediately after your first login for security purposes.
            </div>

            <div class="info-box">
                <strong>What you can do in the portal:</strong><br>
                ✓ View your profile and personal information<br>
                ✓ Check your current enrollments<br>
                ✓ View your grades and transcript<br>
                ✓ Manage your account settings
            </div>

            <div style="text-align: center;">
                <a href="{{ $loginUrl }}" class="button">Login to Your Portal</a>
            </div>

            <p style="margin-top: 30px; font-size: 14px;">
                <strong>Login Instructions:</strong><br>
                1. Visit the portal using the button above<br>
                2. Enter your Student ID and temporary password<br>
                3. Change your password to something secure<br>
                4. Explore your academic dashboard
            </p>

            <p style="font-size: 13px; color: #6b7280;">
                If you didn't request this account or have any questions, please contact the Academic Affairs Office.
            </p>

            <div class="footer">
                <p>BSU-Bokod SIMS Portal | {{ config('app.name') }}</p>
                <p>This is an automated message. Please do not reply to this email.</p>
                <p>&copy; {{ date('Y') }} Benguet State University. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
