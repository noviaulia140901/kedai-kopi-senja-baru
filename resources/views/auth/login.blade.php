<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
    </head>
    <body>
        <h1>Login</h1>

        <form action="#" method="POST">
            @csrf
            <label for="username">Username</label>
            <input
                 type="text"
                 id="username"
                 name="username"
                 placeholder="Masukan username"
            >

            <label for="password">Password</label>
            <input
                 type="password"
                 id="password"
                 name="password"
                 placeholder="Masukan password"
            >

            <button type="submit">Masuk</button>
        </form>
    </body>
</html>
