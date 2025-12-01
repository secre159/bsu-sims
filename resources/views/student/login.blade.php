<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal Login - BSU-Bokod SIMS</title>
    @vite(['resources/css/app.css'])
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            width: 100%;
            max-width: 420px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .login-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .login-header p {
            margin: 8px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .login-body {
            padding: 40px;
        }
        .form-group {
            margin-bottom: 24px;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            font-size: 15px;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .form-group input.is-invalid {
            border-color: #dc2626;
        }
        .invalid-feedback {
            color: #dc2626;
            font-size: 13px;
            margin-top: 4px;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .checkbox-group input {
            width: auto;
            margin-right: 8px;
        }
        .checkbox-group label {
            margin: 0;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
        }
        .login-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        .login-btn:active {
            transform: translateY(0);
        }
        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-danger {
            background: #fee;
            color: #991b1b;
            border: 1px solid #fca;
        }
        .help-text {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        .help-text a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
        }
        .help-text a:hover {
            text-decoration: underline;
        }
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>🎓 SIMS Portal</h1>
            <p>Student Information Management System</p>
        </div>

        <div class="login-body">
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger">
                        {{ $error }}
                    </div>
                @endforeach
            @endif

            <form method="POST" action="{{ route('student.login') }}">
                @csrf

                <div class="form-group">
                    <label for="student_id">Student ID</label>
                    <input 
                        type="text" 
                        id="student_id" 
                        name="student_id" 
                        value="{{ old('student_id') }}"
                        class="@error('student_id') is-invalid @enderror"
                        placeholder="e.g., 2024-0001"
                        autocomplete="off"
                        required
                        autofocus
                    >
                    @error('student_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password"
                        class="@error('password') is-invalid @enderror"
                        placeholder="Enter your password"
                        required
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="remember" name="remember" value="1">
                    <label for="remember">Remember me</label>
                </div>

                <button type="submit" class="login-btn">Login</button>

                <div class="help-text">
                    <p style="font-size: 13px; color: #6b7280; margin: 0 0 12px 0;">
                        💡 Check your email for your temporary password
                    </p>
                    <a href="{{ route('dashboard') }}">Back to Admin Portal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
