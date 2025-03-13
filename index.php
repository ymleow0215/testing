<?php
session_start(); // Start session to store login data
require 'dataconnection.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) {
        $error = "All fields are required!";
    } else {
        $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $db_email, $db_password);
            $stmt->fetch();
            
            if (password_verify($password, $db_password)) {
                $_SESSION["user_id"] = $id;
                $_SESSION["email"] = $db_email;
                header("Location: dashboard.php"); // Redirect to the dashboard
                exit();
            } else {
                $error = "Invalid email or password!";
            }
        } else {
            $error = "User not found!";
        }
        $stmt->close();
    }
}


$sneakersQuery = "SELECT p.ProductID, p.Product_name, p.price, p.description, p.Product_img, pr.avg_rating 
                  FROM product p 
                  JOIN product_category pc ON p.CategoryID = pc.CategoryID 
                  LEFT JOIN product_rating pr ON p.ProductID = pr.ProductID 
                  WHERE pc.Category_name = 'Sneakers' 
                  LIMIT 3";
$sneakersResult = $connect->query($sneakersQuery);

$sneakers = [];
if ($sneakersResult->num_rows > 0) {
    while ($row = $sneakersResult->fetch_assoc()) {
        $sneakers[] = $row;
    }
}

// Fetch products for Sport Shoes category
$sportShoesQuery = "SELECT p.ProductID, p.Product_name, p.price, p.description, p.Product_img, pr.avg_rating 
                    FROM product p 
                    JOIN product_category pc ON p.CategoryID = pc.CategoryID 
                    LEFT JOIN product_rating pr ON p.ProductID = pr.ProductID 
                    WHERE pc.Category_name = 'Sport shoes' 
                    LIMIT 3";
$sportShoesResult = $connect->query($sportShoesQuery);

$sportShoes = [];
if ($sportShoesResult->num_rows > 0) {
    while ($row = $sportShoesResult->fetch_assoc()) {
        $sportShoes[] = $row;
    }
}

// Fetch products for Slides category
$slidesQuery = "SELECT p.ProductID, p.Product_name, p.price, p.description, p.Product_img, pr.avg_rating 
                FROM product p 
                JOIN product_category pc ON p.CategoryID = pc.CategoryID 
                LEFT JOIN product_rating pr ON p.ProductID = pr.ProductID 
                WHERE pc.Category_name = 'Slides' 
                LIMIT 3";
$slidesResult = $connect->query($slidesQuery);

$slides = [];
if ($slidesResult->num_rows > 0) {
    while ($row = $slidesResult->fetch_assoc()) {
        $slides[] = $row;
    }
}

$commentsQuery = "SELECT nickname, rating, comment FROM store_review";
$commentsResult = $connect->query($commentsQuery);

$comments = [];
if ($commentsResult->num_rows > 0) {
    while ($row = $commentsResult->fetch_assoc()) {
        $comments[] = [
            'user' => $row['nickname'],
            'rating' => str_repeat('★', $row['rating']) . str_repeat('☆', 5 - $row['rating']),
            'text' => $row['comment']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrueSole</title>
    <link rel="icon" href="./image/favicon.ico">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <script defer href="script.js"></script>
</head>


<body>
    <?php include '../header&footer/header.php'; ?>

    <!-- swiper container -->
    <div class="parallax-container">
        <div class="swiper">
            <div class="swiper-wrapper">
                <!-- Slide 1 -->
                <div class="swiper-slide"><div class="title"><h2>DISCOVER SHOES THAT LOOK</h2> <h2>GREAT AND FEEL EVEN BETTER</h2></div>
                    <img class="parallax-bg" src="./image/banner1.jpg" alt="Parallax Background">
                    <button onclick="location.href='../Product-list/product-list.php'" class="shop-now-button">SHOP NOW</button>
                </div>
                <!-- Slide 2 -->
                <div class="swiper-slide"><div class="title"><h2>FIND SHOES THAT LOOK</h2> <h2>GREAT AND FEEL EVEN BETTER</h2></div>
                <img class="parallax-bg" src="./image/banner2.jpg" alt="Parallax Background">
                <button onclick="location.href='../Product-list/product-list.php'" class="shop-now-button">SHOP NOW</button>
            </div>
                <!-- Slide 3 -->
                <div class="swiper-slide"><div class="title"><h2>DISCOVER SHOES THAT LOOK</h2> <h2>GREAT AND FEEL EVEN BETTER</h2></div>
                <img class="parallax-bg" src="./image/banner4.webp" alt="Parallax Background">
                <button onclick="location.href='../Product-list/product-list.php'" class="shop-now-button">SHOP NOW</button>
            </div>
            </div>
            <!-- pagination -->
            <div class="swiper-pagination"></div>
            <!-- nav button -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>


    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="./script.js"></script>

    <div class="Product-categories">
        <p>Satisfy the popular demands of population</p>
        <h1>LET'S GET THE AMAZING SHOP EXPERIENCE</h1>
        <div class="categories">
            <div class="category">
                <img src="./image/category1.jpg" alt="Kids Category">
                <p>Comfort</p>
                <h2>KIDS</h2>
                <a href="../Product-list/product-list.php">SEE ALL</a>
            </div>
            <div class="category">
                <img src="./image/category2.jpg" alt="Women Category">
                <p>Latest</p>
                <h2>WOMEN</h2>
                <a href="../Product-list/product-list.php">SEE ALL</a>
            </div>
            <div class="category">
                <img src="./image/category3.jpg" alt="Men Category">
                <p>Trending</p>
                <h2>MEN</h2>
                <a href="../Product-list/product-list.php">SEE ALL</a>
            </div>
        </div>
    </div>

    <div class="sale-container">
        <div class="image-container">
            <img src="./image/sales.png" alt="New Balance Shoes">
        </div>
        <div class="details-container">
            <h1>new balance</h1>
            <h2>Made in USA 993 Core</h2>
            <p>Some description about sale shoes. This is a placeholder text for the product description. Add your content here to describe the features and benefits of the shoes.</p>
            <div class="price-container">
                <span class="discounted-price">RM 239.00</span>
                <span class="original-price">RM 599.00</span>
            </div>
            <button id="middle1_myCartLink" href="#" class="add-to-cart">Add to Cart <i class="fa-solid fa-cart-shopping"></i></button>
            <a href="../Product-list/product-list.php" class="shop-more">Shop More ></a>
        </div>
    </div>

    <p style="margin-top: 200px;"></p>

    <div class="product">
        <header>
            <p>ALL PRODUCTS</p>
            <h1>CATEGORIES</h1>
        </header>
    
        <!-- Sneakers Section -->
<!-- Sneakers Section -->
<section class="category-shoes">
    <div class="title-show-more">
        <h2>Sneakers</h2>
        <div class="show-more">
            <button onclick="location.href='../Product-list/product-list.php'">Show More<span class="icon"><i class="fas fa-arrow-right"></i></span></button>
        </div>
    </div>
    <div class="products">
        <?php foreach ($sneakers as $product): ?>
            <div class="product-card">
                <img src="../Product-list/image/<?php echo $product['Product_img']; ?>" alt="Sneaker">
                <h3><?php echo $product['Product_name']; ?></h3>
                <p><?php echo $product['description']; ?></p>
                <div class="product-footer">
                    <div class="price">RM<?php echo number_format($product['price'], 2); ?></div>
                    <div class="stars"><?php echo str_repeat('★', round($product['avg_rating'])); ?><span><?php echo str_repeat('☆', 5 - round($product['avg_rating'])); ?></span></div>
                </div>
                <button class="addtocart" data-product-id="<?php echo $product['ProductID']; ?>">Add to Cart <i class="fa-solid fa-cart-shopping"></i></button>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<p style="margin-top: 200px;"></p>

<!-- Sports Shoes Section -->
<section class="category-shoes">
    <div class="title-show-more">
        <h2>Sport shoes</h2>
        <div class="show-more">
            <button onclick="location.href='../Product-list/product-list.php'">Show More<span class="icon"><i class="fas fa-arrow-right"></i></span></button>
        </div>
    </div>
    <div class="products">
        <?php foreach ($sportShoes as $product): ?>
            <div class="product-card">
                <img src="../Product-list/image/<?php echo $product['Product_img']; ?>" alt="Sport shoes">
                <h3><?php echo $product['Product_name']; ?></h3>
                <p><?php echo $product['description']; ?></p>
                <div class="product-footer">
                    <div class="price">RM<?php echo number_format($product['price'], 2); ?></div>
                    <div class="stars"><?php echo str_repeat('★', round($product['avg_rating'])); ?><span><?php echo str_repeat('☆', 5 - round($product['avg_rating'])); ?></span></div>
                </div>
                <button class="addtocart" data-product-id="<?php echo $product['ProductID']; ?>">Add to Cart <i class="fa-solid fa-cart-shopping"></i></button>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<p style="margin-top: 200px;"></p>

<!-- Slides Section -->
<section class="category-shoes">
    <div class="title-show-more">
        <h2>Slides</h2>
        <div class="show-more">
            <button onclick="location.href='../Product-list/product-list.php'">Show More<span class="icon"><i class="fas fa-arrow-right"></i></span></button>
        </div>
    </div>
    <div class="products">
        <?php foreach ($slides as $product): ?>
            <div class="product-card">
                <img src="../Product-list/image/<?php echo $product['Product_img']; ?>" alt="Slides">
                <h3><?php echo $product['Product_name']; ?></h3>
                <p><?php echo $product['description']; ?></p>
                <div class="product-footer">
                    <div class="price">RM<?php echo number_format($product['price'], 2); ?></div>
                    <div class="stars"><?php echo str_repeat('★', round($product['avg_rating'])); ?><span><?php echo str_repeat('☆', 5 - round($product['avg_rating'])); ?></span></div>
                </div>
                <button class="addtocart" data-product-id="<?php echo $product['ProductID']; ?>">Add to Cart <i class="fa-solid fa-cart-shopping"></i></button>
            </div>
        <?php endforeach; ?>
    </div>
</section>
    </div>

    <p style="margin-top: 300px;"></p>

    <!-- Comment Section -->
  <div class="comment-section">
    <h2>About our shoes</h2>
    <p class="anonymous">anonymous:</p>
    <div class="rating">★★★★★</div>
    <p class="comment">comment</p>
    <a href="../Review/Review.php" class="comment-link">comment here</a>
    <div class="nav-buttons">
        <button class="prev-button"><i class="fas fa-chevron-left"></i></button>
        <button class="next-button"><i class="fas fa-chevron-right"></i></button>
    </div>

    <script>
    let comments = <?php echo json_encode($comments); ?>;
    let currentCommentIndex = 0;

    function updateComment() {
        const commentElement = document.querySelector(".comment");
        const anonymousElement = document.querySelector(".anonymous");
        const ratingElement = document.querySelector(".rating");

        const comment = comments[currentCommentIndex];
        anonymousElement.textContent = comment.user + ":";
        ratingElement.textContent = comment.rating;
        commentElement.textContent = comment.text;
    }

    function showNextComment() {
        currentCommentIndex = (currentCommentIndex + 1) % comments.length;
        updateComment();
    }

    function showPreviousComment() {
        currentCommentIndex = (currentCommentIndex - 1 + comments.length) % comments.length;
        updateComment();
    }

    document.addEventListener("DOMContentLoaded", () => {
        updateComment();
        setInterval(showNextComment, 5000); // Change comment every 5 seconds

        document.querySelector(".next-button").addEventListener("click", showNextComment);
        document.querySelector(".prev-button").addEventListener("click", showPreviousComment);
    });
</script>
  </div>

     <!-- Partners Brands Section -->
    <div class="partners-section">
     <h3>ALL PRODUCTS</h3>
     <h2>PARTNERS BRANDS</h2>
     <div class="logos">
        <div class="logo-above">
            <a href="../Product-list/product-list.php"><img src="./image/adidas.png" alt="Adidas"></a>
            <a href="../Product-list/product-list.php"><img src="./image/nike.png" alt="Adidas"></a>
        </div>
        <div class="logo-below">
            <a href="../Product-list/product-list.php"><img src="./image/puma.png" alt="Adidas"></a>
            <a href="../Product-list/product-list.php"><img src="./image/new balance.png" alt="Adidas"></a>
            <a href="../Product-list/product-list.php"><img src="./image/fila.png" alt="Adidas"></a>
        </div>
     </div>
    </div>


    <!-- Footer Section -->
    <?php include '../header&footer/footer.php'; ?>
</body>
</html>
