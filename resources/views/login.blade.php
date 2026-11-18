<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        <h1>Login</h1>
         @if (session('error'))
        <div role="alert">
            {{ session('error') }}
        </div>
        @endif
                
        <form action="" method="POST">
        @csrf
            <div class="username">
                <label for="username">Username:</label>
                <input type="text" name="name" required>
            </div>

            <div class="password">
                <label for="password">Password:</label>
                <input type="password" name="password" required>
            </div>

            <div>
                <input type="submit" class="btn">   
            </div>

            <div>
                <p>Register here:<span><a href="{{route('register')}}">Register</a></span></p>
            </div>
        </form>
    </div>

</body>
</html>