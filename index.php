<?php
include 'includes/dbcon.php';

// Fetch only 4 books
$books = [];
$sql = "SELECT b.id, b.title, b.description, b.status, b.genre_id, g.genre_name 
        FROM tbl_books b
        LEFT JOIN tbl_genre g ON b.genre_id = g.id
        ORDER BY b.id DESC
        LIMIT 4";
$result = mysqli_query($conn, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $desc = strip_tags($row['description']); 
        $words = explode(" ", $desc);
        if (count($words) > 20) {
            $desc = implode(" ", array_slice($words, 0, 20)) . "...";
        }
        $row['short_description'] = $desc;
        $books[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>SRC Library</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/spinner.php'; ?>
    <?php include 'includes/topbar.php'; ?>
    <?php include 'includes/navbar.php'; ?>

    <!-- Library Books Collection -->
    <div class="container-fluid service pt-6 pb-6">
        <div class="container">
            <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h1 class="display-6 text-uppercase mb-5">Library Books Collection</h1>
            </div>

            <!-- Books Grid (Only 4 Books) -->
            <div class="row g-4" id="booksContainer">
                <?php if (!empty($books)): ?>
                    <?php foreach ($books as $index => $book): ?>
                        <div class="col-lg-3 col-md-6 wow fadeInUp"
                             data-wow-delay="<?php echo (0.1 * ($index % 4 + 1)); ?>s">
                            <div class="service-item h-100">
                                <div class="service-inner pb-5">
                                    <div class="service-text px-5 pt-4">
                                        <h5 class="text-uppercase"><?php echo htmlspecialchars($book['title']); ?></h5>
                                        <p><?php echo htmlspecialchars($book['short_description']); ?></p>
                                        <p>
                                            <strong>Status: </strong>
                                            <?php if ($book['status'] === 'available'): ?>
                                                <span class="badge bg-success">Available</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Borrowed</span>
                                            <?php endif; ?>
                                        </p>
                                        <?php if (!empty($book['genre_name'])): ?>
                                            <p><strong>Genre:</strong> <?php echo htmlspecialchars($book['genre_name']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <button 
                                        class="btn btn-light px-3 read-more-btn align-self-start" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#bookModal"
                                        data-title="<?php echo htmlspecialchars($book['title']); ?>"
                                        data-description="<?php echo htmlspecialchars($book['description']); ?>"
                                        data-status="<?php echo htmlspecialchars($book['status']); ?>"
                                        data-genre="<?php echo htmlspecialchars($book['genre_name']); ?>">
                                        Read More<i class="bi bi-chevron-double-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p class="text-muted">No books available at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Book Modal -->
    <div class="modal fade" id="bookModal" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="bookModalLabel">Book Details</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <h3 id="modalTitle"></h3>
            <p id="modalDescription"></p>
            <p><strong>Status: </strong><span id="modalStatus"></span></p>
            <p><strong>Genre: </strong><span id="modalGenre"></span></p>
            <div class="library-note">
                📚 Note: To read the full content of this book, please visit the <strong>Santa Rita College of Pampanga Library</strong>.
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <?php include 'includes/footer.php'; ?>   

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
