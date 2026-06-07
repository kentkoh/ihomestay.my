<?php

class AdminArticleController {

    public function index(): void {
        Auth::requireAdmin();
        $articles = Article::all();
        $title    = 'Articles';
        ob_start();
        require APP_PATH . '/Views/admin/articles/index.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/admin.php';
    }

    public function create(): void {
        Auth::requireAdmin();
        $old   = $_SESSION['form_old'] ?? [];
        unset($_SESSION['form_old']);
        $title = 'New Article';
        ob_start();
        require APP_PATH . '/Views/admin/articles/create.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/admin.php';
    }

    public function store(): void {
        Auth::requireAdmin();
        CSRF::verify();

        $errors = $this->validate($_POST);
        if (!empty($errors)) {
            $_SESSION['flash']['danger'] = implode('<br>', $errors);
            $_SESSION['form_old']        = $_POST;
            header('Location: /admin/articles/create');
            exit;
        }

        $isPublished = isset($_POST['is_published']) ? 1 : 0;

        $id = Article::create([
            'title'        => trim($_POST['title']),
            'slug'         => 'temp',
            'excerpt'      => trim($_POST['excerpt'] ?? ''),
            'body'         => trim($_POST['body']),
            'cover_image'  => null,
            'is_published' => $isPublished,
            'published_at' => $isPublished ? date('Y-m-d H:i:s') : null,
        ]);

        $slug        = Article::makeSlug(trim($_POST['title']), $id);
        $coverImage  = $this->handleCoverUpload($id);

        Article::update($id, [
            'title'        => trim($_POST['title']),
            'slug'         => $slug,
            'excerpt'      => trim($_POST['excerpt'] ?? ''),
            'body'         => trim($_POST['body']),
            'cover_image'  => $coverImage,
            'is_published' => $isPublished,
            'published_at' => $isPublished ? date('Y-m-d H:i:s') : null,
        ]);

        $_SESSION['flash']['success'] = 'Article created.';
        header('Location: /admin/articles');
        exit;
    }

    public function edit(string $id): void {
        Auth::requireAdmin();
        $article = $this->findOrFail((int) $id);
        $title   = 'Edit Article';
        ob_start();
        require APP_PATH . '/Views/admin/articles/edit.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/admin.php';
    }

    public function update(string $id): void {
        Auth::requireAdmin();
        CSRF::verify();
        $article = $this->findOrFail((int) $id);

        $errors = $this->validate($_POST);
        if (!empty($errors)) {
            $_SESSION['flash']['danger'] = implode('<br>', $errors);
            header("Location: /admin/articles/$id/edit");
            exit;
        }

        $isPublished = isset($_POST['is_published']) ? 1 : 0;
        $slug        = Article::makeSlug(trim($_POST['title']), (int) $id);
        $coverImage  = $this->handleCoverUpload((int) $id) ?? $article['cover_image'];

        $publishedAt = $article['published_at'];
        if ($isPublished && !$publishedAt) {
            $publishedAt = date('Y-m-d H:i:s');
        }

        Article::update((int) $id, [
            'title'        => trim($_POST['title']),
            'slug'         => $slug,
            'excerpt'      => trim($_POST['excerpt'] ?? ''),
            'body'         => trim($_POST['body']),
            'cover_image'  => $coverImage,
            'is_published' => $isPublished,
            'published_at' => $isPublished ? $publishedAt : null,
        ]);

        $_SESSION['flash']['success'] = 'Article updated.';
        header('Location: /admin/articles');
        exit;
    }

    public function delete(string $id): void {
        Auth::requireAdmin();
        CSRF::verify();
        $article = Article::findById((int) $id);
        if ($article) {
            if ($article['cover_image']) {
                @unlink(UPLOAD_PATH . '/articles/' . $article['cover_image']);
            }
            Article::delete((int) $id);
            $_SESSION['flash']['success'] = 'Article deleted.';
        }
        header('Location: /admin/articles');
        exit;
    }

    public function toggle(string $id): void {
        Auth::requireAdmin();
        CSRF::verify();
        Article::togglePublish((int) $id);
        header('Location: /admin/articles');
        exit;
    }

    public function uploadImage(): void {
        ob_start();
        try {
            Auth::requireAdmin();
            CSRF::verify();

            if (empty($_FILES['image']['tmp_name'])) {
                ob_clean();
                header('Content-Type: application/json');
                echo json_encode(['error' => 'No file received.']);
                exit;
            }

            $file    = $_FILES['image'];
            $allowed = ['image/jpeg', 'image/png', 'image/webp'];
            $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $extMap  = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];
            $mime    = $extMap[$ext] ?? (@mime_content_type($file['tmp_name']) ?: '');

            if ($file['size'] > 5 * 1024 * 1024 || !in_array($mime, $allowed, true)) {
                ob_clean();
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Invalid file. JPG/PNG/WebP, max 5 MB.']);
                exit;
            }

            $dir = UPLOAD_PATH . '/articles/content';
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            $filename = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $filename)) {
                ob_clean();
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Failed to save file.']);
                exit;
            }

            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['url' => '/uploads/articles/content/' . $filename]);
            exit;

        } catch (Throwable $e) {
            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function findOrFail(int $id): array {
        $article = Article::findById($id);
        if (!$article) {
            http_response_code(404);
            echo '<h1>Article not found</h1>';
            exit;
        }
        return $article;
    }

    private function validate(array $post): array {
        $errors = [];
        if (empty(trim($post['title'] ?? '')))  $errors[] = 'Title is required.';
        if (empty(trim($post['body']  ?? '')))  $errors[] = 'Body is required.';
        return $errors;
    }

    private function handleCoverUpload(int $articleId): ?string {
        if (empty($_FILES['cover_image']['tmp_name'])) return null;

        $file    = $_FILES['cover_image'];
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $extMap  = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];
        $mime    = $extMap[$ext] ?? (@mime_content_type($file['tmp_name']) ?: '');

        if ($file['size'] > 5 * 1024 * 1024 || !in_array($mime, $allowed, true)) return null;

        $dir = UPLOAD_PATH . '/articles';
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $filename = $articleId . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        if (move_uploaded_file($file['tmp_name'], $dir . '/' . $filename)) {
            return $filename;
        }
        return null;
    }
}
