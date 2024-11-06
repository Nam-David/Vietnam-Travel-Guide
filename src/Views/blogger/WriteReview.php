<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write New Review</title>
    <link rel="stylesheet" href="/BE_LT_WEB/Vietnam-Travel-Guide/public/css/WriteReview.css">
</head>
<body>
    <?php   
        include_once "../../Controllers/bothController.php";
        include_once "../../Controllers/authController.php";

        $controller = new bothController();
        $provinces = $controller->getAllProvinces();
        
        $auth = new AuthController();
        $auth->checkAuth();
    ?>
    <!-- Header -->
    <header>
        <div class="logo">
            <img src="logo.png" alt="Vuon Tam The Gioi">
        </div>
        <nav>
            <ul>
                <li><a href="#">Destination</a></li>
                <li><a href="#">Stories</a></li>
                <li><a href="#">Reviews</a></li>
            </ul>
        </nav>
        <div class="auth">
            <a href="#" class="sign-up">Sign Up</a>
            <a href="#" class="login-btn">Login</a>
        </div>
    </header>

    <!-- Main Section -->
    <section class="main-section">
        <h1>Share your Travel Experience in form of a story</h1>
        <div class="review-form">
            <input type="text" placeholder="What would you like to review?" class="search-bar">
            
            <div class="flex-container">
                <div class="top-places">
                    <h2>Top places with reviews</h2>
                    <p>Travelers want to see more reviews of these places.</p>
                    
                    <div class="review-placeholder">
                        <img src="placeholder-image.png" alt="Upload your photo">
                    </div>
                </div>

                <form action="#" method="POST" enctype="multipart/form-data">
                    <label for="provinceID">Chọn Tỉnh/Thành Phố:</label>
                    <select id="provinceID" name="provinceID" required>
                        <option value="">Chọn tỉnh/thành</option>
                        <?php
                            foreach ($provinces as $province) {
                                echo '<option value="' . $province['provinceID'] . '">' . $province['provinceName'] . '</option>';
                            }
                        ?>
                    </select>
    
                    <label for="blogContent">Nội Dung Blog:</label>
                    <textarea id="blogContent" name="blogContent" placeholder="Nhập nội dung blog của bạn" required></textarea>
    
                    <label for="blogImages">Tải lên Hình Ảnh:</label>
                    <input type="file" id="blogImages" name="blogImages[]" multiple required accept="image/*">
    
                    <button type="submit" class="submit-btn">Thêm Blog</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2023 Travel Pulse. All rights reserved</p>
    </footer>
</body>
</html>
