<?php
session_start();
require 'vendor/autoload.php';
require 'config/database.php';

use Carbon\Carbon;

$post_id = $_GET['id'] ?? null;

if (!$post_id) {
    header('Location: index.php');
    exit;
}

$sql = "SELECT p.id, p.text_content, p.upload_time, p.user_id, u.username, p.photo FROM posts p INNER JOIN users u ON p.user_id = u.id WHERE p.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    header('Location: index.php');
    exit;
}
?>
<?php include 'includes/head.php'; ?>
</head>

<body>
    <!-- Include your navbar here -->
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <div class="post-details">
            <div class="post-header d-flex align-items-center mb-4">
                <div class="post-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                    <span class="fs-4"><?php echo strtoupper(substr($post['username'], 0, 1)); ?></span>
                </div>
                <h2 class="mb-0"><?php echo htmlspecialchars($post['username']); ?></h2>
            </div>
            <?php if ($post['photo']): ?>
                <div class="post-image mb-4">
                    <img src="<?php echo htmlspecialchars($post['photo']); ?>" class="img-fluid rounded" alt="Post Image">
                </div>
            <?php endif; ?>
            <div class="post-content mb-4">
                <p><?php echo nl2br(htmlspecialchars($post['text_content'])); ?></p>
            </div>
            <div class="post-footer text-muted">
                <small>Posted <?php echo Carbon::parse($post['upload_time'])->diffForHumans(); ?></small>
            </div>
        </div>
    </div>

    <!-- Include your footer here -->
    <?php include 'includes/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>