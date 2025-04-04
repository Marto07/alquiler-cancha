<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="container">
        @if (isset($data) && !$data['success'])
            <div class="card border-danger mb-3" style="max-width: 600px; background-color: #f8d7da;">
                <div class="card-header text-white bg-danger">Error</div>
                <div class="card-body text-danger">
                    <h5 class="card-title">Ha ocurrido un error</h5>
                    <pre class="card-text bg-white p-3 rounded border">{{ json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
