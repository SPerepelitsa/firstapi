<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<h1>Firstpage</h1>
    @foreach($userData as $key => $userParam)
        <p>{{$key.' : '.$userParam}}</p>
    @endforeach
</body>
</html>