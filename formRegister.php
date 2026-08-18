<div class="form-container">
    <h1>สมัครสมาชิก</h1>

    <form action="InsertRegis.php" method="post">
        <div class="input-group">
            <input type="text" class="form-control" name="first_name" placeholder="ชื่อ" aria-label="ชื่อ" required>
            <input type="text" class="form-control" name="last_name" placeholder="สกุล" aria-label="สกุล" required>
        </div>
        <div class="form-floating mb-3">
            <input type="email" class="form-control" name="email" id="floatingInput" placeholder="Email" required>
            <label for="floatingInput">Email</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="username" id="floatingInput" placeholder="Username" required>
            <label for="floatingInput">Username</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" name="password" id="floatingPassword" placeholder="Password" required>
            <label for="floatingPassword">Password</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" name="confirm_password" id="floatingPassword" placeholder="Confirm Password" required>
            <label for="floatingPassword">Confirm Password</label>
        </div>
        <button type="submit" class="btn btn-outline-success">สมัครสมาชิก</button>
    </form>

    <div class="footer">
        <a href="index.php">มีบัญชีอยู่แล้ว?</a>
        <p>ต้องการติดต่อเรา? <a href="mailto:psnonamejr@gmail.com">psnonamejr@gmail.com</a></p>
    </div>

</div>