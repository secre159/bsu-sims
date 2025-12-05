<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Students Report</title>
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
        .filter-info {
            background-color: #f3f4f6;
            padding: 8px;
            margin-bottom: 15px;
            border-left: 3px solid #3b82f6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #3b82f6;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
        }
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
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
        .footer-left {
            float: left;
        }
        .footer-right {
            float: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BENGUET STATE UNIVERSITY - BOKOD CAMPUS</h1>
        <p>Student Master List Report</p>
    </div>

    <div class="filter-info">
        <strong>Filter:</strong> {{ $filterDesc }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 12%">Student ID</th>
                <th style="width: 30%">Name</th>
                <th style="width: 12%">Program</th>
                <th style="width: 12%">Year</th>
                <th style="width: 12%">Type</th>
                <th style="width: 17%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student->student_id }}</td>
                    <td>{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}</td>
                    <td>{{ $student->program->code ?? 'N/A' }}</td>
                    <td>{{ $student->year_level }}</td>
                    <td>{{ $student->attendance_type ?: ($student->is_irregular ? 'Irregular' : 'Regular') }}</td>
                    <td>{{ $student->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">No students found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-left">
            <strong>Total Students:</strong> {{ $students->count() }}
        </div>
        <div class="footer-right">
            <strong>Generated:</strong> {{ now()->format('F d, Y h:i A') }}
        </div>
    </div>
</body>
</html>
