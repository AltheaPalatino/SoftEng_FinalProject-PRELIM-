<?php require_once 'writer/classloader.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" 
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" 
        crossorigin="anonymous">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

  <style>
    body {
      font-family: "Comic Sans MS", "Arial Rounded MT Bold", Arial, sans-serif;
      background: linear-gradient(to right, #ff99cc, #ff4d6d, #4da6ff);
      background-attachment: fixed;
      min-height: 100vh;
    }

    .navbar {
      background: linear-gradient(90deg, #4da6ff, #ff4d6d, #ff99cc);
      border-bottom: 4px solid #fff;
    }

    .navbar-brand {
      font-weight: bold;
      font-size: 1.4rem;
    }

    .display-4 {
      color: #333;
      font-weight: bold;
      margin-top: 20px;
    }

    .card {
      border-radius: 20px;
      overflow: hidden;
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: scale(1.03);
    }

    .card-body h1 {
      font-size: 1.6rem;
      color: #4da6ff;
      font-weight: bold;
    }

    .card-body p {
      color: #444;
    }

    .badge-admin {
      background: #ff4d6d;
      color: white;
      border-radius: 8px;
      padding: 4px 8px;
      font-size: 0.8rem;
    }

    /* Mascot in corner */
    .mascot {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 90px;
      opacity: 0.9;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark p-3">
    <a class="navbar-brand" href="#"><i class="bi bi-journal-richtext"></i> School Publication</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </nav>

  <div class="container-fluid">
    <div class="display-4 text-center"><i class="bi bi-stars"></i> Welcome to the Homepage!</div>

    <div class="row mt-4">
      <div class="col-md-6 mb-4">
        <div class="card shadow">
          <div class="card-body text-center">
            <h1><i class="bi bi-pencil"></i> Writer</h1>
            <img src="https://i.postimg.cc/Kjdb14Wk/pexels-fotios-photos-851213.jpg" 
                 class="img-fluid rounded mb-3">
            <p>
              Content writers create clear, engaging, and informative content that helps communicate 
              effectively, build brand authority, and inspire readers.
            </p>
          </div>
        </div>
      </div>

      <div class="col-md-6 mb-4">
        <div class="card shadow">
          <div class="card-body text-center">
            <h1><i class="bi bi-people-fill"></i> Admin</h1>
            <img src="https://i.postimg.cc/jjZrzM9r/pexels-tima-miroshnichenko-9572504.jpg" 
                 class="img-fluid rounded mb-3">
            <p>
              Admins manage the editorial process, ensuring alignment with the publication’s vision 
              while guiding the whole content team.
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="display-4 text-center mt-4"><i class="bi bi-book-half"></i> Articles</div>

    <div class="row justify-content-center">
      <div class="col-md-6">
        <?php $articles = $articleObj->getActiveArticles(); ?>
        <?php foreach ($articles as $article) { ?>
          <div class="card mt-4 shadow">
            <div class="card-body">
              <h1><i class="bi bi-newspaper"></i> <?php echo htmlspecialchars($article['title']); ?></h1> 

              <?php if (!empty($article['is_admin']) && $article['is_admin'] == 1) { ?>
                <p><span class="badge-admin"><i class="bi bi-shield-lock"></i> Message From Admin</span></p>
              <?php } ?>

              <small>
                <strong><i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($article['username']); ?></strong> - 
                <i class="bi bi-clock"></i> <?php echo htmlspecialchars($article['created_at']); ?>
              </small>

              <?php if (!empty($article['image_path'])): ?>
                <div class="text-center w-100">
                  <img src="uploads/<?php echo htmlspecialchars($article['image_path']); ?>" class="img-fluid mt-2 mb-2 rounded" alt="Article Image">
                </div>
              <?php endif; ?>

              <p><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
            </div>
          </div>  
        <?php } ?>   
      </div>
    </div>
  </div>

</body>
</html>
