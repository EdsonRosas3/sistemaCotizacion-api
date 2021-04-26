<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body{
            font-family:"Gill Sans Extrabold", Helvetica, sans-serif
        }

        h1{
            color:#ffff;
            font-size: 30px;
        }
        .contenido{
            text-align: center;
        }
        .encabezado{
            background-color:DodgerBlue;
            width: 100%;
            height: 60px;
            text-align: center;
            line-height: 60px;
        }
        p{
            padding:30px;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="contenido">
        <div class="encabezado"><h1>Solicitud de cotización</h1></div>
        <p> {{$content->description}}</p>
    </div>
</body>
</html>