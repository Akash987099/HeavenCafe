<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isOwnTasks ? 'My Tasks' : 'All Staff Tasks' }}</title>
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
        .task-image { height: 42px; width: 42px; border-radius: 4px; object-fit: cover; }
        .employee { break-inside: avoid; margin-top: 20px; }
        .employee h2 { background: #f1f5f9; border: 1px solid #e2e8f0; font-size: 14px; margin: 0; padding: 10px; }
        @media print { body { margin: 12mm; } .no-print { display: none !important; } }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1>{{ $isOwnTasks ? 'My Tasks' : 'All Staff Tasks' }}</h1>
            <p>{{ $isOwnTasks ? 'Tasks assigned to you' : 'All employee tasks' }} &middot; Printed {{ now()->format('d M Y, h:i A') }}</p>
        </div>
        <button type="button" class="no-print" onclick="window.print()">Print</button>
    </div>

    @forelse ($tasks->groupBy('staff_id') as $staffTasks)
        @php($employee = $staffTasks->first()->staff)
        <section class="employee">
            <h2>{{ $employee?->name ?: '-' }} <span class="muted">&middot; {{ $employee?->staff_id ?: '-' }}</span></h2>
            <table>
                <thead><tr><th>Image</th><th>Task</th></tr></thead>
                <tbody>
                    @foreach ($staffTasks as $task)
                        <tr>
                            <td>@if ($task->task_image)<img src="{{ asset($task->task_image) }}" alt="{{ $task->title }}" class="task-image">@else - @endif</td>
                            <td><strong>{{ $task->title }}</strong>@if ($task->description)<br><span class="muted">{{ $task->description }}</span>@endif</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    @empty
        <p>No staff tasks found.</p>
    @endforelse
</body>
</html>
