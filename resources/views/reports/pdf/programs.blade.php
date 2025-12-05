<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Programs Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            font-size: 11px;
        }
        .program-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .program-header {
            background-color: #3b82f6;
            color: white;
            padding: 10px;
            margin-bottom: 5px;
            border-left: 4px solid #1e40af;
        }
        .program-header h3 {
            margin: 0;
            font-size: 12px;
            font-weight: bold;
        }
        .program-header p {
            margin: 3px 0 0 0;
            font-size: 9px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: #e5e7eb;
            padding: 6px 8px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
        }
        td {
            padding: 5px 8px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 9px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            font-size: 9px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BENGUET STATE UNIVERSITY - BOKOD CAMPUS</h1>
        <p>Students by Program Report</p>
    </div>

    @foreach($programs as $program)
        <div class="program-section">
            <div class="program-header">
                <h3>{{ $program->code }} - {{ $program->name }}</h3>
                <p>Total Students: {{ $program->students_count }}</p>
            </div>

            @if($program->students->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th style="width: 18%">Student ID</th>
                            <th style="width: 37%">Name</th>
                            <th style="width: 15%">Year Level</th>
                            <th style="width: 15%">Type</th>
                            <th style="width: 15%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($program->students as $student)
                            <tr>
                                <td>{{ $student->student_id }}</td>
                                <td>{{ $student->last_name }}, {{ $student->first_name }}</td>
                                <td>{{ $student->year_level }}</td>
                                <td>{{ $student->attendance_type ?: ($student->is_irregular ? 'Irregular' : 'Regular') }}</td>
                                <td>{{ $student->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="font-style: italic; color: #6b7280; margin-left: 10px;">No students enrolled in this program.</p>
            @endif
        </div>
    @endforeach

    <div class="footer">
        <strong>Total Programs:</strong> {{ $programs->count() }} | 
        <strong>Total Students:</strong> {{ $programs->sum('students_count') }} | 
        <strong>Generated:</strong> {{ now()->format('F d, Y h:i A') }}
    </div>
</body>
</html>
