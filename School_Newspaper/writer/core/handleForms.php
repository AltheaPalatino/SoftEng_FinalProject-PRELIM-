<?php  
require_once '../classloader.php';

if (isset($_POST['insertNewUserBtn'])) {
	$username = htmlspecialchars(trim($_POST['username']));
	$email = htmlspecialchars(trim($_POST['email']));
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);

	if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			if (!$userObj->usernameExists($username)) {

				if ($userObj->registerUser($username, $email, $password)) {
					header("Location: ../login.php");
				}

				else {
					$_SESSION['message'] = "An error occured with the query!";
					$_SESSION['status'] = '400';
					header("Location: ../register.php");
				}
			}

			else {
				$_SESSION['message'] = $username . " as username is already taken";
				$_SESSION['status'] = '400';
				header("Location: ../register.php");
			}
		}
		else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register.php");
		}
	}
	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}
}

if (isset($_POST['loginUserBtn'])) {
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);

	if (!empty($email) && !empty($password)) {

		if ($userObj->loginUser($email, $password)) {
			header("Location: ../index.php");
		}
		else {
			$_SESSION['message'] = "Username/password invalid";
			$_SESSION['status'] = "400";
			header("Location: ../login.php");
		}
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../login.php");
	}

}

if (isset($_GET['logoutUserBtn'])) {
	$userObj->logout();
	header("Location: ../login.php");
}


if (isset($_POST['editArticleBtn'])) {
    $title = $_POST['title'];
    $content = $_POST['content']; // FIX: was description
    $article_id = $_POST['article_id'];
    if ($articleObj->updateArticle($article_id, $title, $content)) {
        header("Location: ../articles_submitted.php");
    }
}

if (isset($_POST['deleteArticleBtn'])) {
	$article_id = $_POST['article_id'];
	echo $articleObj->deleteArticle($article_id);
}



/* ============================
   WRITER SIDE
=============================== */

// Writer requests to edit an article
if (isset($_POST['request_edit'])) {
    $article_id = $_POST['article_id'];
    $articleObj->requestEdit($article_id, $_SESSION['user_id']);
    header("Location: ../articles_submitted.php");
    exit;
}

// Writer submits edited article (optional if editing directly from shared_articles)
if (isset($_POST['editArticleBtn'])) {
    $article_id = $_POST['article_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Check access
    if ($articleObj->writerHasAccess($article_id, $_SESSION['user_id'])) {
        $articleObj->updateArticleContent($article_id, $title, $content);
        header("Location: ../shared_articles.php?success=updated");
        exit;
    } else {
        die("Unauthorized: You cannot edit this article.");
    }
}

/* ============================
   MARK NOTIFICATION AS READ (AJAX)
=============================== */
if (isset($_POST['action']) && $_POST['action'] === 'markAsRead') {
    if (session_status() === PHP_SESSION_NONE) session_start();
    header('Content-Type: application/json; charset=utf-8');

    $user_id = $_SESSION['user_id'] ?? null;
    $notif_id = isset($_POST['notif_id']) ? intval($_POST['notif_id']) : 0;

    if (!$user_id || $notif_id <= 0) {
        echo json_encode(['success' => false, 'error' => 'invalid_request']);
        exit;
    }

    // get PDO
    $db = new Database();
    $pdo = $db->getConnection();

    try {
        $sql = "UPDATE notifications SET is_read = 1 WHERE notification_id = :id AND user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $ok = $stmt->execute(['id' => $notif_id, 'user_id' => $user_id]);
        echo json_encode(['success' => (bool)$ok]);
        exit;
    } catch (Exception $e) {
        // do not leak sensitive info to client
        echo json_encode(['success' => false, 'error' => 'server_error']);
        exit;
    }
}

//CATEGORY
if (isset($_POST['insertArticleBtn'])) {
    $title       = $_POST['title'] ?? '';
    $content     = $_POST['content'] ?? '';
    $author_id   = $_SESSION['user_id'];
    $category_id = $_POST['category_id'] ?? null;

    // Handle image upload
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileName = time() . '_' . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image_path = $fileName; // only store filename
        }
    }

    if (!empty($title) && !empty($content)) {
        if ($articleObj->createArticle($title, $content, $author_id, $image_path, $category_id)) {
            header("Location: ../index.php");
            exit;
        } else {
            $_SESSION['message'] = "Error saving article.";
            $_SESSION['status'] = '400';
            header("Location: ../index.php");
            exit;
        }
    } else {
        $_SESSION['message'] = "Title and Content cannot be empty!";
        $_SESSION['status'] = '400';
        header("Location: ../index.php");
        exit;
    }
}

