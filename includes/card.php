<?php

use Carbon\Carbon;
?>

<div class="col-md-4 mb-4">
    <div class="card h-100 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="<?= htmlspecialchars($user['profile_pic']) ?>" alt="Profile Picture" class="rounded-circle me-3" width="50" height="50">
                <h5 class="card-title mb-0"><?= htmlspecialchars($user['username']) ?></h5>

            </div>
            <div class="text-muted">
                <small>Posted <?php echo Carbon::parse($row['upload_time'])->diffForHumans(); ?></small>
            </div>
            <div class="dropdown">
                <button class="btn btn-link p-0 border-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="edit_user_post.php?id=<?= $row['id'] ?>">Edit</a></li>
                    <li><a class="dropdown-item" href="delete_user_post.php?id=<?= $row['id'] ?>">Delete</a></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <?php
            $currentFile = basename($_SERVER['PHP_SELF']);
            if ($currentFile == 'index.php') {
                echo '<p class="card-text">' . htmlspecialchars(substr($row['text_content'], 0, 100)) . '...</p>';
            } else {
                echo '<p class="card-text">' . htmlspecialchars($row['text_content']) . '</p>';
            }
            ?>
        </div>
        <div>
            <?php if ($row['photo']) { ?>
                <img src="<?= htmlspecialchars($row['photo']) ?>" class="card-img-top" alt="Post Image">
            <?php } ?>
        </div>
        <div class="post-footer">
            <div class="card-footer d-flex justify-content-start align-items-center">
                <form action="react.php" method="post" class="d-inline me-3">
                    <input type="hidden" name="post_id" value="<?= $row['id'] ?>">
                    <input type="hidden" name="reaction" value="up">
                    <button type="submit" class="btn btn-link p-0 border-0"><i class="bi bi-hand-thumbs-up-fill"></i> <?= $row['up_reactions'] ?></button>
                </form>
                <form action="react.php" method="post" class="d-inline">
                    <input type="hidden" name="post_id" value="<?= $row['id'] ?>">
                    <input type="hidden" name="reaction" value="down">
                    <button type="submit" class="btn btn-link p-0 border-0"><i class="bi bi-hand-thumbs-down-fill"></i> <?= $row['down_reactions'] ?></button>
                </form>
            </div>
        </div>
    </div>
    <?php  ?>
</div>