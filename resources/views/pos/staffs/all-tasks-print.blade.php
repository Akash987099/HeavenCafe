<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Staff Tasks</title>
    <style>
        body { margin: 32px; color: #1e293b; font-family: Arial, sans-serif; font-size: 12px; }
        .header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 24px; }
        h1 { margin: 0; font-size: 22px; }
        p { margin: 5px 0 0; color: #64748b; }
        button { border: 0; border-radius: 6px; background: #128C7E; color: #fff; cursor: pointer; padding: 10px 16px; font-weight: bold; }
        table { border-collapse: collapse; width: 100%; }
        th { background: #f1f5f9; color: #475569; font-size: 10px; text-align: left; text-transform: uppercase; }
        th, td { border: 1px solid #e2e8f0; padding: 10px; vertical-align: top; }
        .muted { color: #64748b; font-size: 10px; }
        .status { border-radius: 99px; display: inline-block; padding: 4px 8px; font-size: 10px; font-weight: bold; }
        .task-image { height: 42px; width: 42px; border-radius: 4px; object-fit: cover; }
        .pending { background: #fef3c7; color: #92400e; }
        .in_progress { background: #e0f2fe; color: #0369a1; }
        .completed { background: #d1fae5; color: #047857; }
        @media print { body { margin: 12mm; } .no-print { display: none !important; } }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1>All Staff Tasks</h1>
            <p>All employee tasks &middot; Printed {{ now()->format('d M Y, h:i A') }}</p>
        </div>
        <button type="button" class="no-print" onclick="window.print()">Print</button>
    </div>

    <table>
        <thead>
            <tr><th>Employee</th><th>Task</th><th>Image</th></tr>
        </thead>
        <tbody>
            @forelse ($tasks as $task)
                <tr>
                    <td><strong>{{ $task->staff?->name ?: '-' }}</strong><br><span class="muted">{{ $task->staff?->staff_id ?: '-' }}</span></td>
                    <td><strong>{{ $task->title }}</strong>@if ($task->description)<br><span class="muted">{{ $task->description }}</span>@endif</td>
                    <td>@if ($task->task_image)<img src="{{ asset($task->task_image) }}" alt="{{ $task->title }}" class="task-image">@else - @endif</td>
                </tr>
            @empty
                <tr><td colspan="3" style="text-align: center; color: #64748b; padding: 24px;">No staff tasks found.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
