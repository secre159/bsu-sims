<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student ID Card</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            width: 85.6mm;
            height: 53.98mm;
            padding: 0;
            margin: 0;
        }
        .id-card {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }
        .header {
            background: white;
            text-align: center;
            padding: 8px 10px;
        }
        .header h1 {
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
            margin: 0;
            line-height: 1.2;
        }
        .header p {
            font-size: 8px;
            color: #6b7280;
            margin: 2px 0 0 0;
        }
        .content {
            padding: 10px 15px;
            color: white;
        }
        .photo-section {
            text-align: center;
            margin-bottom: 8px;
        }
        .photo {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            border: 3px solid white;
            object-fit: cover;
            background: white;
            display: inline-block;
        }
        .no-photo {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            border: 3px solid white;
            background: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #667eea;
            font-weight: bold;
        }
        .info {
            text-align: center;
        }
        .student-id {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .name {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .program {
            font-size: 10px;
            margin-bottom: 2px;
        }
        .year-level {
            font-size: 9px;
            opacity: 0.9;
        }
        .footer {
            position: absolute;
            bottom: 5px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7px;
            opacity: 0.8;
            color: white;
        }
    </style>
</head>
<body>
    <div class="id-card">
        <div class="header">
            <h1>BSU-BOKOD CAMPUS</h1>
            <p>Benguet State University</p>
        </div>
        
        <div class="content">
            <div class="photo-section">
                @if($student->photo_path)
                    <img src="{{ public_path('storage/' . $student->photo_path) }}" alt="Student Photo" class="photo">
                @else
                    <div class="no-photo">
                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                    </div>
                @endif
            </div>
            
            <div class="info">
                <div class="student-id">{{ $student->student_id }}</div>
                <div class="name">{{ strtoupper($student->first_name) }} {{ strtoupper($student->last_name) }}</div>
                <div class="program">{{ $student->program->name }}</div>
                <div class="year-level">{{ $student->year_level }}</div>
            </div>
        </div>
        
        <div class="footer">
            Valid for Academic Year {{ date('Y') }}-{{ date('Y') + 1 }} · {{ $student->status }}
        </div>
    </div>
</body>
</html>
