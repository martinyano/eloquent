<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: lightblue;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }
        .container {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .container h1 {
            text-align: center;
            margin-top: 0;
        }
        form div {
            margin-bottom: 15px;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }
        .btn{
            background-color: #04AA6D;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        a{
            text-align: center;
            padding: 15px;
        }
    </style>
</head>
<body>
   <div class="container">
        <h1>Register</h1>
        <form action="{{route('register')}}" method="POST" onsubmit="this.querySelector('button').disabled = true; this.querySelector('button').innerText='Registering...';">
            @csrf
            <div>
                <label for="username">Username:</label>
                <input type="text" name="name" required>
            </div>

            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" required>
            </div>

            <div>
                <button type="submit" class="btn">Register</button>
            </div>

            <div>
                <a href="{{route('login')}}">Back</a>
            </div>
        </form>
    </div>
</body>
</html>