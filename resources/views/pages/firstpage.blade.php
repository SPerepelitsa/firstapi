<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<h1>Firstpage</h1>
    @foreach($userData as $key => $userParam)
        @if($key == 'visited_at')
            @php
            $userParam = date('Y-m-d H:i:s', $userParam);
            @endphp
        @endif
        <p>{{$key.' : '.$userParam}}</p>
    @endforeach
</body>
</html>