<?php include 'layout/header/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-6 left" data-aos="fade-right" data-aos-duration="1000">
            <img class="bg-img2" src="img/bubble.png" alt="logo">
            <img class="bg-img1" src="img/bubble.png" alt="logo">
            <img class="bg-img3" src="img/logo 2.png" alt="logo">
        </div>

        <div class="col-md-6 right" data-aos="fade-left" data-aos-duration="1000">
            <h2 data-aos="fade-up" data-aos-delay="200">Login</h2>

            <form method="POST">
                <div class="form-group" data-aos="fade-up" data-aos-delay="300">
                    <input type="email" name="email" placeholder="Email" class="form-control" required>
                </div>

                <div class="form-group" data-aos="fade-up" data-aos-delay="400">
                    <input type="password" name="password" placeholder="Password" class="form-control" required>
                </div>

                <button type="submit" name="login" class="btn submit-btn"  data-aos-delay="500">submit</button>
            </form>

            <a href="register.php">
                <div class="create-account" data-aos="fade-up" data-aos-delay="600">Daftar sekarang</div>
            </a>
        </div>
    </div>
</div>

<?php include 'layout/footer/footer.php'; ?>