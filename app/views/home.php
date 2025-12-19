<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}


$userName = $_SESSION['user_name'] 
            ?? $_SESSION['customer_name'] 
            ?? null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- CSS -->
  <link rel="stylesheet" href="/css/stylee.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <title>Loyalty Program</title>
</head>

<body>

  <header>
    <div class="header-container">

      <!-- Logo -->
      <div class="header-col">
        <div class="logo-container">
          <a href="/">
            <img src="/images/logoo2.jpeg" alt="logo" class="logo">
          </a>
        </div>
      </div>

      <!-- Navigation -->
      <div class="header-col">
        <nav class="nav-container">
          <ul class="nav-list-container">
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="/customer/points">EarnPoints</a></li>
            <li><a href="/customer/redeemed-offers">Redeem</a></li>
            <li><a href="/offers">Offers</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </nav>
      </div>


      <div class="header-col">
        <ul class="button">
          <?php if (!isset($_SESSION['user_id']) && !isset($_SESSION['customer_id'])): ?>

            <li><a href="/login" class="btn">Login</a></li>
            <li><a href="/select-user-type" class="btn">Register</a></li>
          <?php else: ?>
            <!-- User logged in -->
            <li class="user-name">
              <i class="fa fa-user"></i>
              <?= htmlspecialchars($userName) ?>
            </li>
            <li>
              <a href="/logout" class="btn">Logout</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>

    </div>
  </header>


  <section id="home" class="hero">
    <div class="hero-content">
      <h1>Earn Rewards Every Time You Shop</h1>
      <p>
        Join our loyalty program and earn points with every purchase.
        Redeem your points for exclusive rewards and special offers.
      </p>
    </div>
  </section>

  <section id="about" class="about-section">
    <div class="container">
      <h2 class="section-title">About Loyalty</h2>
      <p class="section-desc">
        Loyalty is a platform for managing loyalty programs that serve both users and merchants.
        It offers a variety of programs to fit different needs.
      </p>
    </div>
  </section>

  <section class="categories">
    <div class="container">
      <h2 class="section-title">Supported Categories</h2>

      <div class="category-grid">
        <div class="category-card">
          <img src="/images/mall.jpeg" alt="Shopping Malls">
          <h3>Shopping Malls</h3>
        </div>

        <div class="category-card">
          <img src="/images/restaurant.jpeg" alt="Restaurants">
          <h3>Restaurants</h3>
        </div>

        <div class="category-card">
          <img src="/images/cinema.jpeg" alt="Cinemas">
          <h3>Cinemas</h3>
        </div>

        <div class="category-card">
          <img src="/images/Entertainment.jpeg" alt="Entertainment">
          <h3>Entertainment</h3>
        </div>

        <div class="category-card">
          <img src="/images/hotel.jpeg" alt="Hotels">
          <h3>Hotels</h3>
        </div>
      </div>
    </div>
  </section>

  <footer class="footer" id="contact">
    <div class="footer-container">
      <div class="footer-section">
        <h3>Loyalty Web App</h3>
        <p>Connecting customers with rewards and offers.</p>
      </div>

      <div class="footer-section">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="/">Home</a></li>
          <li><a href="/offers">Offers</a></li>
        </ul>
      </div>
    </div>

    <p class="footer-bottom">
      Loyalty Web App. All Rights Reserved.
    </p>
  </footer>

</body>
</html>
