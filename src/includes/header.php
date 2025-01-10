<header>
    <nav>
        <ul>
            <li><a href="./index.php">Home</a></li>
            <?php if (isset($_SESSION['username'])): ?>
                <li>
                    <span class="welcome-message">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </li>
                <li>
                    <form action="../src/Handler/UserHandler.php" method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="logout">
                        <button type="submit" class="logout-button">Logout</button>
                    </form>
                </li>
            <?php else: ?>
                <li><a href="./login.php">Login</a></li>
                <li><a href="./register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
