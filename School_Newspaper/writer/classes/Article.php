<?php  
require_once 'Database.php';
require_once 'User.php';

/**
 * Class for handling Article-related operations.
 * Inherits CRUD methods from the Database class.
 */
class Article extends Database {

    public function createArticle($title, $content, $author_id, $image_path = null, $category_id = null) {
    $sql = "INSERT INTO articles 
            (title, content, author_id, image_path, category_id, is_active, created_at) 
            VALUES (:title, :content, :author_id, :image_path, :category_id, 0, NOW())";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ':title'       => $title,
        ':content'     => $content,
        ':author_id'   => $author_id,
        ':image_path'  => $image_path,
        ':category_id' => $category_id
    ]);
    return $this->pdo->lastInsertId();}


//HAVE CHANGES 
public function getArticlesByUserID($user_id) {
    $sql = "SELECT a.*, u.username, c.category_name
            FROM articles a
            JOIN school_publication_users u ON a.author_id = u.user_id
            LEFT JOIN categories c ON a.category_id = c.category_id
            WHERE a.author_id = :user_id
            ORDER BY a.created_at DESC";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public function getArticles() {
        $sql = "SELECT a.*, u.username 
                FROM articles a
                JOIN school_publication_users u 
                  ON a.author_id = u.user_id 
                ORDER BY a.created_at DESC";
        return $this->executeQuery($sql);
    }

    public function getActiveArticles() {
        $sql = "SELECT a.*, u.username 
                FROM articles a
                JOIN school_publication_users u 
                  ON a.author_id = u.user_id 
                WHERE a.is_active = 1
                ORDER BY a.created_at DESC";
        return $this->executeQuery($sql);
    }


public function updateArticle($article_id, $title, $content, $image_path = null) {
    if ($image_path) {
        // If new image is uploaded
        $sql = "UPDATE articles 
                SET title = :title, content = :content, image_path = :image_path 
                WHERE article_id = :article_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':image_path', $image_path);
    } else {
        // If no new image, keep old one
        $sql = "UPDATE articles 
                SET title = :title, content = :content 
                WHERE article_id = :article_id";
        $stmt = $this->pdo->prepare($sql);
    }

    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':article_id', $article_id);

    return $stmt->execute();
}


    public function updateArticleVisibility($id, $is_active) {
        $userModel = new User();
        if (!$userModel->isAdmin()) {
            return 0;
        }
        $sql = "UPDATE articles SET is_active = :active WHERE article_id = :id";
        return $this->executeNonQuery($sql, [
            ':active' => (int)$is_active,
            ':id' => $id
        ]);
    }

    public function deleteArticle($id) {
        $sql = "DELETE FROM articles WHERE article_id = :id";
        return $this->executeNonQuery($sql, [':id' => $id]);
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
        SELECT a.*, u.username AS author_name 
        FROM article_edit_requests r
        JOIN articles a ON r.article_id = a.article_id
        JOIN school_publication_users u ON a.author_id = u.user_id
        WHERE r.requester_id = ? AND r.status = 'accepted'
        ORDER BY a.created_at DESC
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getEditRequestById($request_id) {
        $sql = "SELECT * FROM article_edit_requests WHERE request_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$request_id]);
        return $stmt->fetch();
    }

    public function shareArticleWithWriter($article_id, $writer_id) {
        $sql = "INSERT INTO shared_articles (article_id, writer_id) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$article_id, $writer_id]);
    }

    public function writerHasAccess($article_id, $writer_id) {
    $sql = "SELECT * FROM shared_articles WHERE article_id = ? AND writer_id = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$article_id, $writer_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
}

public function getActiveArticlesWithUsers() {
    $stmt = $this->pdo->prepare("
        SELECT a.*, u.username, u.is_admin
        FROM articles a
        JOIN school_publication_users u ON a.author_id = u.user_id
        WHERE a.is_active = 1
        ORDER BY a.created_at DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getArticleById($article_id) {
    $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE article_id = ?");
    $stmt->execute([$article_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function updateArticleContent($article_id, $title, $content) {
    $stmt = $this->pdo->prepare("UPDATE articles SET title = ?, content = ? WHERE article_id = ?");
    return $stmt->execute([$title, $content, $article_id]);
}




}


?>
