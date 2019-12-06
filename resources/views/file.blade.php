<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>VueJS</title>
    <link rel="stylesheet" href="/css/app.css">
    <style>
        #upload_files p {
            float: left;
            margin-right: 15px;
        }
        .input-group {
            overflow: hidden;
        }
    </style>
</head>
<body>
<div class="container">
    <div id="app">
        <files></files>
    </div>
</div>
<script src="/js/app.js"></script>
</body>
</html>
