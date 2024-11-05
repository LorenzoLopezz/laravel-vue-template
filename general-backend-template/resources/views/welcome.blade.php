<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Laravel Backend Template</title>

  <style>
    body {
      background-color: #1F2C45;
    }
    .bg-static {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 90vh;
    }
  </style>
</head>

<body>
  <div class="bg-static">
    <img src="{{ asset('images/square-glasses.png') }}" width="150" alt="TEMPLATE">
  </div>
</body>

</html>
