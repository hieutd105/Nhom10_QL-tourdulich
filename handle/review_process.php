<?php
// handle/review_process.php
require_once __DIR__ . '/../functions/auth.php';
require_once __DIR__ . '/../functions/review_functions.php';

checkLogin(__DIR__ . '/../index.php');

$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action === 'create') {
    $tour_id = (int)($_POST['tour_id'] ?? 0);
    $user_id = (int)($_POST['user_id'] ?? 0);
    $rating  = (int)($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    if ($tour_id && $user_id && $rating >= 1 && $rating <= 5) {
        if (addReview($tour_id, $user_id, $rating, $comment)) {
            header('Location: ../views/review.php?msg=created');
            exit;
        }
        header('Location: ../views/review.php?msg=error');
        exit;
    }
    header('Location: ../views/review.php?msg=invalid');
    exit;
}

if ($action === 'update') {
    $id      = (int)($_POST['id'] ?? 0);
    $tour_id = (int)($_POST['tour_id'] ?? 0);
    $user_id = (int)($_POST['user_id'] ?? 0);
    $rating  = (int)($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    if ($id && $tour_id && $user_id && $rating >= 1 && $rating <= 5) {
        if (updateReview($id, $tour_id, $user_id, $rating, $comment)) {
            header('Location: ../views/review.php?msg=updated');
            exit;
        }
        header('Location: ../views/review.php?msg=error');
        exit;
    }
    header('Location: ../views/review.php?msg=invalid');
    exit;
}

if ($action === 'delete') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id && deleteReview($id)) {
        header('Location: ../views/review.php?msg=deleted');
        exit;
    }
    header('Location: ../views/review.php?msg=error');
    exit;
}

// Nếu không có action
header('Location: ../views/review.php');
