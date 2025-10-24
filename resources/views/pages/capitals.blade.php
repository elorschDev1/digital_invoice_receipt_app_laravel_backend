<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capital Cities</title>
</head>
<body>
    <h3>Capital Cities:</h3>
    <ul>
    @foreach ($capitals as $country => $capital)
    <li>The capital city of {{$country}} is {{$capital}}</li>
    @endforeach
    </ul>
    
</body>
</html>