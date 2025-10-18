<!-- components/navbar.php -->
<nav >
    <ul>
        <li>Ecommerce Website</li>

        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="../auth/logout.php">Logout</a></li>
            <li><a href="profile.php">Profile</a></li>

        <?php elseif (isset($_SESSION['admin_id'])): ?>
            <li><a href="product.php">Add Product</a></li>
            <li><a href="../auth/admin_logout.php">Logout</a></li>

        <?php else: ?>
            <li><a href="../auth/login.php">Login</a></li>
            <li><a href="../auth/register.php">Register</a></li>
            <li><a href="../auth/admin_login.php">Admin Login</a></li>
        <?php endif; ?>
    </ul>
</nav>
