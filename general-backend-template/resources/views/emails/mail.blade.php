<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
  <style>
    .btn {
      display: block;
      width: 200px;
      margin: 20px auto;
      padding: 10px;
      text-align: center;
      background: linear-gradient(90deg, #4CAF50, #8BC34A);
      color: white !important;
      text-decoration: none;
      border-radius: 5px;
      font-family: Arial, sans-serif;
      font-size: 16px;
      transition: background 0.3s ease;
    }

    .btn:hover {
      background: linear-gradient(90deg, #45A049, #7CB342);
    }
  </style>
</head>

<body>
<h1>{{ $data['title'] }}</h1>
<p>{{ $data['body'] }}</p>

<a href="{{ $data['actionUrl'] }}" class="btn">{{ $data['actionText']  }}</a>

</body>

</html>
