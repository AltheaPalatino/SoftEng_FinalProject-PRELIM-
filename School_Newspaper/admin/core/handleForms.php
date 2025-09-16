<?php  
require_once '../classloader.php';
require_once '../classes/Article.php';
require_once '../classes/User.php';

if (!isset($_SESSION)) session_start();

$articleObj = new Article();
$userObj = new User();

//user registration
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
					exit;
				} else {
					$_SESSION['message'] = "An error occured with the query!";
					$_SESSION['status'] = '400';
					header("Location: ../register.php");
					exit;
				}
			} else {
				$_SESSION['message'] = $username . " as username is already taken";
				$_SESSION['status'] = '400';
				header("Location: ../register.php");
				exit;
			}
		} else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register.php");
			exit;
		}
	} else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
		exit;
	}
}

//User login
if (isset($_POST['loginUserBtn'])) {
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);

	if (!empty($email) && !empty($password)) {
		if ($userObj->loginUser($email, $password)) {
			header("Location: ../index.php");
			exit;
		} else {
			$_SESSION['message'] = "Username/password invalid";
			$_SESSION['status'] = "400";
			header("Location: ../login.php");
			exit;
		}
	} else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../login.php");
		exit;
	}
}

//logout
if (isset($_GET['logoutUserBtn'])) {
	$userObj->logout();
	header("Location: ../index.php");
	exit;
}

// ARTICLE CRUD (with Image Upload)
if (isset($_POST['insertArticleBtn'])) {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $author_id = $_SESSION['user_id'];

    $image_path = null; 

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../uploads/'; 
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileName = time() . '_' . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image_path = $fileName; 
        }
    }

    if (!empty($title) && !empty($content)) {
        if ($articleObj->createArticle($title, $content, $author_id, $image_path)) {
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


if (isset($_POST['editArticleBtn'])) {
    $title = $_POST['title'];
    $content = $_POST['content']; 
    $article_id = $_POST['article_id'];
    if ($articleObj->updateArticle($article_id, $title, $content)) {
        header("Location: ../articles_submitted.php");
    }
}



// Admin accepts an edit request
if (isset($_POST['accept_edit'])) {
    $request_id = $_POST['request_id'];
    $request = $articleObj->getEditRequestById($request_id);

    if ($request) {
        $articleObj->respondToEditRequest($request_id, 'accepted'); 
        $articleObj->shareArticleWithWriter($request['article_id'], $request['requester_id']); 
    }

    header("Location: ../articles_from_students.php?success=accepted");
    exit;
}


if (isset($_POST['reject_edit'])) {
    $request_id = $_POST['request_id'];
    $articleObj->respondToEditRequest($request_id, 'rejected');
    header("Location: ../articles_from_students.php?success=rejected");
    exit;
}

if (isset($_POST['updateArticleVisibility'])) {
    $article_id = $_POST['article_id'];
    $status = $_POST['status'];

    if ($articleObj->updateArticleVisibility($article_id, $status)) {
        echo 1; 
    } else {
        echo 0; 
    }
    exit;
}

//Delete Article
if (isset($_POST['deleteArticleBtn'])) {
    $article_id = $_POST['article_id'] ?? null;

    if ($article_id && isset($_SESSION['user_id'])) {
        if ($userObj->isAdmin()) {
            if ($articleObj->deleteArticle($article_id, $_SESSION['user_id'])) {
                echo 1; 
            } else {
                echo 0; 
            }
        } else {
            $article = $articleObj->getArticleByID($article_id);
            if ($article && $article['author_id'] == $_SESSION['user_id']) {
                if ($articleObj->deleteArticle($article_id, $_SESSION['user_id'])) {
                    echo 1;
                } else {
                    echo 0;
                }
            } else {
                echo 0; 
            }
        }
    } else {
        echo 0; 
    }
    exit;
}

//Notifications
if (isset($_POST['action']) && $_POST['action'] === 'markAsRead') {
    if (session_status() === PHP_SESSION_NONE) session_start();
    header('Content-Type: application/json; charset=utf-8');

    $user_id = $_SESSION['user_id'] ?? null;
    $notif_id = isset($_POST['notif_id']) ? intval($_POST['notif_id']) : 0;

    if (!$user_id || $notif_id <= 0) {
        echo json_encode(['success' => false, 'error' => 'invalid_request']);
        exit;
    }


    $db = new Database();
    $pdo = $db->getConnection();

    try {
        $sql = "UPDATE notifications SET is_read = 1 WHERE notification_id = :id AND user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $ok = $stmt->execute(['id' => $notif_id, 'user_id' => $user_id]);
        echo json_encode(['success' => (bool)$ok]);
        exit;
    } catch (Exception $e) {

        echo json_encode(['success' => false, 'error' => 'server_error']);
        exit;
    }
}



