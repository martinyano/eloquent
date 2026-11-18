<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
   <div class="container mt-5">
        <div class="row justify-content-center">
           <div class="col-md-5 col-lg-4">
                <h2 class="card-title text-center mb-4">register</h2>
                <form action="{{route('register')}}" method="POST" onsubmit="this.querySelector('button').disabled = true; this.querySelector('button').innerText='Registering...';">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">username:</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">password:</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary mt-4 mb-4 text-center">register</button>
                        
                    </div>

                    <div>
                        <a href="{{route('login')}}" class="text-center">back</a>
                    </div>
                </form>
           </div>
        </div>
    </div>
</body>
</html>