<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Posts') - Laravel CRUD</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: system-ui, sans-serif; background: #f4f6f8; margin: 0; color: #222; }
        .container { max-width: 860px; margin: 40px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
        .header { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 20px; }
        h1 { margin: 0 0 12px; font-size: 24px; }
        .header h1 { margin: 0; }
        .btn { display: inline-block; padding: 8px 14px; border-radius: 6px; border: 0; cursor: pointer;
               text-decoration: none; font-size: 14px; color: #fff; background: #2563eb; }
        .btn-gray { background: #6b7280; }
        .btn-yellow { background: #d97706; }
        .btn-red { background: #dc2626; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 10px; border-bottom: 1px solid #e5e7eb; vertical-align: middle; }
        .actions { display: flex; gap: 6px; flex-wrap: wrap; }
        .actions form { margin: 0; }
        label { display: block; font-weight: 600; margin: 14px 0 6px; }
        input[type=text], textarea { width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font: inherit; }
        textarea { min-height: 160px; }
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 16px; background: #dcfce7; color: #166534; }
        .error { color: #dc2626; font-size: 13px; margin-top: 4px; }
        .muted { color: #6b7280; font-size: 13px; }
        .pager { margin-top: 16px; }
    </style>
</head>
<body>
<div class="container">
    @if (session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif
    <div class="card">
        @yield('content')
    </div>
</div>
</body>
</html>
