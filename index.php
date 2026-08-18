<?php
// include 'condb.php';

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบเพื่อใช้งาน</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f2f4f7;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .form-container {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            border-radius: 10px;
            width: 100%;
            max-width: 500px;
            margin: 0 20px;
            text-align: center;
        }

        .form-container label {
            font-weight: 500;
            color: #555;
            display: block;
            margin-bottom: 8px;
            text-align: left;
            margin-left: 10px;
        }

        .form-container input,
        .form-container textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-container input:focus,
        .form-container textarea:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .form-container textarea {
            resize: vertical;
            height: 150px;
        }

        .form-container input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            padding: 12px 20px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .form-container input[type="submit"]:hover {
            background-color: #45a049;
        }

        .form-container .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #888;
        }

        .form-container .footer a {
            color: #4CAF50;
            text-decoration: none;
        }

        .form-container .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div id="loginFormContainer" class="form-container">
        <h1>Login999999999999</h1>

        <form action="checkLogin.php" method="post">
            <div class="form-floating mb-3">
                <input name="username" type="text" class="form-control" id="floatingInput" placeholder="input username">
                <label for="floatingInput">Username</label>
            </div>
            <div class="form-floating">
                <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">Password</label>
            </div>
            <button type="submit" class="btn btn-outline-success">Login</button>
        </form>

        <div class="footer">
            <a id="RegisterButton" href="#" class="btn btn-link text-success link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">
                ยังไม่มีบัญชีใช่หรือไม่?
            </a>
            <p>ต้องการติดต่อเรา? <a href="mailto:psnonamejr@gmail.com">psnonamejr@gmail.com</a></p>
        </div>
    </div>

    <div id="formContainer">
        
    </div> <!-- ใช้สำหรับแสดงฟอร์มใหม่ -->

    <script>
        document.getElementById('RegisterButton').addEventListener('click', function(e) {
            e.preventDefault(); // ป้องกันการเปลี่ยนหน้า

            // ซ่อนฟอร์ม Login
            document.getElementById('loginFormContainer').style.display = 'none';

            // โหลดฟอร์มจาก formRegister.php และแสดงใน formContainer
            fetch('formRegister.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to load form.');
                    }
                    return response.text();
                })
                .then(html => {
                    document.getElementById('formContainer').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('ไม่สามารถโหลดฟอร์มได้');
                });
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>