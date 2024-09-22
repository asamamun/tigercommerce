<?php
session_start();
require 'config/database.php';

if ($_SESSION['logged_in'] != true) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'];
$reaction = $_POST['reaction'];

// Check if the user has already reacted to the post
$sql = "SELECT reaction FROM user_reactions WHERE user_id = ? AND post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $post_id);
$stmt->execute();
$result = $stmt->get_result();
$user_reaction = $result->fetch_assoc();

if ($user_reaction) {
    // User has already reacted
    if ($user_reaction['reaction'] == $reaction) {
        // Remove the reaction
        $sql = "DELETE FROM user_reactions WHERE user_id = ? AND post_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $post_id);
        $stmt->execute();

        // Update the posts table
        if ($reaction == 'up') {
            $sql = "UPDATE posts SET up_reactions = up_reactions - 1 WHERE id = ?";
        } elseif ($reaction == 'down') {
            $sql = "UPDATE posts SET down_reactions = down_reactions - 1 WHERE id = ?";
        }
    } else {
        // Update the reaction
        $sql = "UPDATE user_reactions SET reaction = ? WHERE user_id = ? AND post_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $reaction, $user_id, $post_id);
        $stmt->execute();

        // Update the posts table
        if ($reaction == 'up') {
            $sql = "UPDATE posts SET up_reactions = up_reactions + 1, down_reactions = down_reactions - 1 WHERE id = ?";
        } elseif ($reaction == 'down') {
            $sql = "UPDATE posts SET down_reactions = down_reactions + 1, up_reactions = up_reactions - 1 WHERE id = ?";
        }
    }
} else {
    // User has not reacted, insert new reaction
    $sql = "INSERT INTO user_reactions (user_id, post_id, reaction) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $user_id, $post_id, $reaction);
    $stmt->execute();

    // Update the posts table
    if ($reaction == 'up') {
        $sql = "UPDATE posts SET up_reactions = up_reactions + 1 WHERE id = ?";
    } elseif ($reaction == 'down') {
        $sql = "UPDATE posts SET down_reactions = down_reactions + 1 WHERE id = ?";
    }
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();

header('Location: profile.php');
exit();
?>