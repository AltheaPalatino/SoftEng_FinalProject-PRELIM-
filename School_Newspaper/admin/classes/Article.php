<?php  
require_once 'Database.php';
require_once 'User.php';

class Article extends Database {

    public function createArticle($title, $content, $author_id, $image_path = null) {
        $sql = "INSERT INTO articles (title, content, image_path, author_id) 
                VALUES (:title, :content, :image_path, :author_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':image_path', $image_path);
        $stmt->bindParam(':author_id', $author_id);
        return $stmt->execute();
    }

    public function getAllArticles() {
        $sql = "SELECT * FROM articles ORDER BY created_at DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

//HAVE CHANGES 
public function getArticles($id = null) {
    if ($id) {
        $sql = "SELECT a.*, u.username, u.is_admin, c.category_name
                FROM articles a
                JOIN school_publication_users u ON a.author_id = u.user_id
                LEFT JOIN categories c ON a.category_id = c.category_id
                WHERE a.article_id = ?";
        return $this->executeQuerySingle($sql, [$id]);
    }

    $sql = "SELECT a.*, u.username, u.is_admin, c.category_name
            FROM articles a
            JOIN school_publication_users u ON a.author_id = u.user_id
            LEFT JOIN categories c ON a.category_id = c.category_id
            ORDER BY a.created_at DESC";
    return $this->executeQuery($sql);
}

//HAVE CHANGES 
public function getActiveArticles($id = null) {
    if ($id) {
        $sql = "SELECT a.*, u.username, u.is_admin, c.category_name
                FROM articles a
                JOIN school_publication_users u 
                  ON a.author_id = u.user_id
                LEFT JOIN categories c 
                  ON a.category_id = c.category_id
                WHERE a.article_id = ? AND a.is_active = 1";
        return $this->executeQuerySingle($sql, [$id]);
    }

    $sql = "SELECT a.*, u.username, u.is_admin, c.category_name
            FROM articles a
            JOIN school_publication_users u 
              ON a.author_id = u.user_id
            LEFT JOIN categories c 
              ON a.category_id = c.category_id
            WHERE a.is_active = 1
            ORDER BY a.created_at DESC";
    return $this->executeQuery($sql);
}


    public function getArticlesByUserID($user_id) {
        $sql = "SELECT a.*, u.username, u.is_admin 
                FROM articles a
                JOIN school_publication_users u 
                ON a.author_id = u.user_id
                WHERE a.author_id = ?
                ORDER BY a.created_at DESC";
        return $this->executeQuery($sql, [$user_id]);
    }

    public function updateArticle($id, $title, $content) {
        $sql = "UPDATE articles SET title = ?, content = ? WHERE article_id = ?";
        return $this->executeNonQuery($sql, [$title, $content, $id]);
    }

    public function updateArticleVisibility($article_id, $status) {
        $sql = "UPDATE articles SET is_active = ? WHERE article_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$status, $article_id]);}


public function deleteArticle($article_id, $admin_id) {
    // First get article details
    $sql = "SELECT author_id, title FROM articles WHERE article_id = :article_id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['article_id' => $article_id]);
    $article = $stmt->fetch();

    if ($article) {
        // Delete the article
        $deleteSql = "DELETE FROM articles WHERE article_id = :article_id";
        $deleteStmt = $this->pdo->prepare($deleteSql);
        $deleteStmt->execute(['article_id' => $article_id]);

        // Insert notification for the writer
        $notifSql = "INSERT INTO notifications (user_id, message) 
                     VALUES (:user_id, :message)";
        $notifStmt = $this->pdo->prepare($notifSql);
        $notifStmt->execute([
            'user_id' => $article['author_id'],
            'message' => "Your article titled '{$article['title']}' was deleted by an admin."
        ]);

        return true;
    }
    return false;
}


public function getArticleByID($article_id) {
    $sql = "SELECT * FROM articles WHERE article_id = :article_id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['article_id' => $article_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


    public function requestEdit($article_id, $requester_id) {
    $stmt = $this->pdo->prepare("INSERT INTO article_edit_requests (article_id, requester_id) VALUES (?, ?)");
    return $stmt->execute([$article_id, $requester_id]);
}

public function getEditRequestsForAuthor($author_id) {
    $stmt = $this->pdo->prepare("
        SELECT r.request_id, r.article_id, r.status, r.requested_at, u.username AS requester_name, a.title
        FROM article_edit_requests r
        JOIN articles a ON r.article_id = a.article_id
        JOIN school_publication_users u ON r.requester_id = u.user_id
        WHERE a.author_id = ?
    ");
    $stmt->execute([$author_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function respondToEditRequest($request_id, $status) {
    $stmt = $this->pdo->prepare("UPDATE article_edit_requests SET status = ? WHERE request_id = ?");
    return $stmt->execute([$status, $request_id]);
}

public function getSharedArticles($user_id) {
    $stmt = $this->pdo->prepare("
        SELECT a.article_id, a.title, a.content, a.image_path, a.created_at, u.username AS author_name
        FROM article_edit_requests r
        JOIN articles a ON r.article_id = a.article_id
        JOIN school_publication_users u ON a.author_id = u.user_id
        WHERE r.requester_id = ? AND r.status = 'accepted'
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getEditRequestById($request_id) {
    $stmt = $this->pdo->prepare("SELECT * FROM article_edit_requests WHERE request_id = ?");
    $stmt->execute([$request_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function shareArticleWithWriter($article_id, $writer_id) {
    $sql = "INSERT INTO shared_articles (article_id, writer_id) VALUES (?, ?)";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([$article_id, $writer_id]);
}

}
?>
