<nav class="navbar justify-content-center" data-bs-theme="dark">
  <!-- Navbar content -->
    <ul class="nav mb-5">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <li class="nav-item">
                <a class="nav-link text-sm-left text-success" aria-current="page" href="../user/welcome.php">Ecommerce Website</a>
            </li>
            <li class="nav-item">
                <a href="../auth/login.php" class="nav-link text-secondary">Login</a>
            </li>
            <li class="nav-item">
                <a href="../auth/register.php" class="nav-link text-secondary">Register</a>
            </li>
            <li class="nav-item">
                <a href="../auth/admin_login.php" class="nav-link text-secondary">Admin Login</a>
            </li>
        
        <?php elseif (isset($_SESSION['admin_id'])): ?>
            <li clas="nav-item"><a href="admin_page.php" class="nav-link text-secondary">Admin Home</a></li>
            <li class="nav-item"><a class="nav-link text-secondary" href="product.php">Add Product</a></li>
            <li class="nav-item"><a class="nav-link text-secondary" href="../auth/admin_registration.php">Admin Registration</a></li>
            <li class="nav-item"><a class="nav-link text-secondary" href="../auth/admin_logout.php">Logout</a></li>
 
        <?php else: ?>
            <li class="nav-item"><a class="nav-link text-secondary" href="../auth/logout.php">Logout</a></li>
            <li class="nav-item"><a class="nav-link text-secondary" href="profile.php">Profile</a></li>
        <?php endif; ?>
    </ul>
</nav>