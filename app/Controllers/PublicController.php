<?php

class PublicController {

    public function home(): void {
        $listings      = Listing::publishedRecent(6);
        $statesWithCnt = Listing::countPublishedByState();
        $articles      = Article::latestPublished(3);
        $cities        = City::all();

        $citiesByState = [];
        foreach ($cities as $city) {
            $citiesByState[$city['state_id']][] = $city;
        }

        $pageTitle    = 'Malaysia Homestay Directory — Book Direct from Owners';
        $metaDesc     = 'Browse and book Malaysian homestays direct from owners — no platform fees, no middleman. Find family homestays across Selangor, Johor, Kedah, Kelantan and all states in Malaysia.';
        $canonicalUrl = rtrim(env('APP_URL', 'https://ihomestay.my'), '/') . '/';
        ob_start();
        require APP_PATH . '/Views/public/home.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/main.php';
    }

    public function listingDetail(string $slug): void {
        $listing = Listing::findBySlugPublic($slug);
        if (!$listing) {
            http_response_code(404);
            echo '<h1>404 — Listing not found</h1>';
            return;
        }
        $images       = Listing::getImages((int) $listing['id']);
        $facilities   = Listing::getFacilitiesForListing((int) $listing['id']);
        $similar      = Listing::similarListings((int) $listing['id'], (int) $listing['city_id'], (int) $listing['state_id'], 6);
        $blockedDates = (bool)($listing['owner_is_verified'] ?? false)
            ? ListingBlockedDate::blockedDatesArray((int) $listing['id'])
            : [];
        $pageTitle    = $listing['title'] . ' — ihomestay.my';

        $rawDesc  = strip_tags($listing['description'] ?? '');
        $metaDesc = mb_strlen($rawDesc) > 155 ? mb_substr($rawDesc, 0, 152) . '…' : $rawDesc;

        $primaryImg = null;
        foreach ($images as $img) {
            if ($img['is_primary']) { $primaryImg = $img['filename']; break; }
        }
        if (!$primaryImg && !empty($images)) $primaryImg = $images[0]['filename'];
        $metaImage    = $primaryImg ? (rtrim(env('APP_URL', 'https://ihomestay.my'), '/') . '/uploads/listings/' . $listing['id'] . '/' . $primaryImg) : null;
        $canonicalUrl = rtrim(env('APP_URL', 'https://ihomestay.my'), '/') . '/listing/' . $listing['slug'];

        ob_start();
        require APP_PATH . '/Views/public/listing.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/main.php';
    }

    public function articles(): void {
        $perPage    = 9;
        $page       = max(1, (int) ($_GET['page'] ?? 1));
        $total      = Article::countPublished();
        $totalPages = (int) ceil($total / $perPage);
        $articles   = Article::published($perPage, ($page - 1) * $perPage);
        $pageTitle  = 'Articles & Tips — ihomestay.my';
        ob_start();
        require APP_PATH . '/Views/public/articles.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/main.php';
    }

    public function articleDetail(string $slug): void {
        $article = Article::findBySlug($slug);
        if (!$article) {
            http_response_code(404);
            echo '<h1>404 — Article not found</h1>';
            return;
        }
        $related   = array_filter(
            Article::latestPublished(4),
            fn($a) => (int) $a['id'] !== (int) $article['id']
        );
        $related   = array_slice(array_values($related), 0, 3);
        $pageTitle = $article['title'] . ' — ihomestay.my';

        $rawExcerpt = $article['excerpt'] ?? strip_tags($article['body'] ?? '');
        $metaDesc   = mb_strlen($rawExcerpt) > 155 ? mb_substr($rawExcerpt, 0, 152) . '…' : $rawExcerpt;
        $metaImage  = !empty($article['cover_image'])
            ? rtrim(env('APP_URL', 'https://ihomestay.my'), '/') . '/uploads/articles/' . $article['cover_image']
            : null;
        $canonicalUrl = rtrim(env('APP_URL', 'https://ihomestay.my'), '/') . '/articles/' . $article['slug'];

        ob_start();
        require APP_PATH . '/Views/public/article.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/main.php';
    }

    public function search(): void {
        $this->renderSearch();
    }

    public function stateListings(string $stateSlug): void {
        $state = State::findBySlug($stateSlug);
        if (!$state) { http_response_code(404); echo '404 Not Found'; return; }
        $this->renderSearch(['state_id' => $state['id']]);
    }

    public function cityListings(string $stateSlug, string $citySlug): void {
        $state = State::findBySlug($stateSlug);
        if (!$state) { http_response_code(404); echo '404 Not Found'; return; }
        $city = City::findBySlug($state['id'], $citySlug);
        if (!$city) { http_response_code(404); echo '404 Not Found'; return; }
        $this->renderSearch(['state_id' => $state['id'], 'city_id' => $city['id']]);
    }

    private function renderSearch(array $baseFilters = []): void {
        $filters = $baseFilters;
        if (!isset($filters['state_id']) && !empty($_GET['state_id'])) {
            $filters['state_id'] = (int) $_GET['state_id'];
        }
        if (!isset($filters['city_id']) && !empty($_GET['city_id'])) {
            $filters['city_id'] = (int) $_GET['city_id'];
        }
        if (!empty($_GET['q']))        $filters['q']        = trim($_GET['q']);
        if (!empty($_GET['guests']))   $filters['guests']   = (int) $_GET['guests'];
        if (!empty($_GET['has_pool'])) $filters['has_pool'] = 1;
        if (!empty($_GET['has_bbq']))  $filters['has_bbq']  = 1;

        $page       = max(1, (int) ($_GET['page'] ?? 1));
        $perPage    = 12;
        $total      = Listing::countSearch($filters);
        $listings   = Listing::search($filters, $page, $perPage);
        $totalPages = (int) ceil($total / $perPage);

        $states       = State::all();
        $allCities    = City::all();
        $cities       = !empty($filters['state_id']) ? City::byState($filters['state_id']) : [];
        $contextState = !empty($filters['state_id']) ? State::findById($filters['state_id']) : null;
        $contextCity  = !empty($filters['city_id'])  ? City::findById($filters['city_id'])  : null;

        $base = rtrim(env('APP_URL', 'https://ihomestay.my'), '/');

        if ($contextCity) {
            $pageTitle    = $contextCity['name'] . ' Homestays — ihomestay.my';
            $metaDesc     = 'Find and book homestays in ' . $contextCity['name'] . ', ' . ($contextState['name'] ?? '') . '. Direct from owners, no platform fees.';
            $canonicalUrl = $base . '/' . ($contextState['slug'] ?? '') . '/' . $contextCity['slug'];
        } elseif ($contextState) {
            $pageTitle    = $contextState['name'] . ' Homestays — ihomestay.my';
            $metaDesc     = 'Browse homestays in ' . $contextState['name'] . ', Malaysia. Book direct from owners — no middleman, no platform fees. Family-friendly options across ' . $contextState['name'] . '.';
            $canonicalUrl = $base . '/' . $contextState['slug'];
        } else {
            $pageTitle    = 'Search Homestays — ihomestay.my';
            $metaDesc     = 'Search Malaysian homestays by state, city, guests and facilities. Book direct from owners across all states in Malaysia.';
            $canonicalUrl = $base . '/search';
        }

        ob_start();
        require APP_PATH . '/Views/public/search.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/main.php';
    }

    public function sitemap(): void {
        $base     = rtrim(env('APP_URL', 'https://ihomestay.my'), '/');
        $listings = Listing::allPublishedForSitemap();
        $articles = Article::allPublishedForSitemap();
        $states   = State::all();
        $cities   = City::allWithStateSlugs();

        $today = date('Y-m-d');

        header('Content-Type: application/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        $staticPages = [
            ['loc' => '/',         'priority' => '1.0', 'freq' => 'daily'],
            ['loc' => '/search',   'priority' => '0.8', 'freq' => 'daily'],
            ['loc' => '/articles', 'priority' => '0.7', 'freq' => 'weekly'],
            ['loc' => '/about',    'priority' => '0.4', 'freq' => 'monthly'],
            ['loc' => '/contact',  'priority' => '0.4', 'freq' => 'monthly'],
        ];
        foreach ($staticPages as $p) {
            echo "  <url>\n";
            echo '    <loc>' . htmlspecialchars($base . $p['loc']) . "</loc>\n";
            echo '    <lastmod>' . $today . "</lastmod>\n";
            echo '    <changefreq>' . $p['freq'] . "</changefreq>\n";
            echo '    <priority>' . $p['priority'] . "</priority>\n";
            echo "  </url>\n";
        }

        foreach ($states as $state) {
            echo "  <url>\n";
            echo '    <loc>' . htmlspecialchars($base . '/' . $state['slug']) . "</loc>\n";
            echo '    <lastmod>' . $today . "</lastmod>\n";
            echo "    <changefreq>weekly</changefreq>\n";
            echo "    <priority>0.8</priority>\n";
            echo "  </url>\n";
        }

        foreach ($cities as $city) {
            echo "  <url>\n";
            echo '    <loc>' . htmlspecialchars($base . '/' . $city['state_slug'] . '/' . $city['city_slug']) . "</loc>\n";
            echo '    <lastmod>' . $today . "</lastmod>\n";
            echo "    <changefreq>weekly</changefreq>\n";
            echo "    <priority>0.7</priority>\n";
            echo "  </url>\n";
        }

        foreach ($listings as $l) {
            $mod = substr($l['updated_at'] ?? $today, 0, 10);
            echo "  <url>\n";
            echo '    <loc>' . htmlspecialchars($base . '/listing/' . $l['slug']) . "</loc>\n";
            echo '    <lastmod>' . $mod . "</lastmod>\n";
            echo "    <changefreq>weekly</changefreq>\n";
            echo "    <priority>0.9</priority>\n";
            echo "  </url>\n";
        }

        foreach ($articles as $a) {
            $mod = substr($a['published_at'] ?? $today, 0, 10);
            echo "  <url>\n";
            echo '    <loc>' . htmlspecialchars($base . '/articles/' . $a['slug']) . "</loc>\n";
            echo '    <lastmod>' . $mod . "</lastmod>\n";
            echo "    <changefreq>monthly</changefreq>\n";
            echo "    <priority>0.6</priority>\n";
            echo "  </url>\n";
        }

        echo '</urlset>';
        exit;
    }

    public function about(): void {
        $pageTitle = 'About Us';
        ob_start();
        require APP_PATH . '/Views/public/about.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/main.php';
    }

    public function contact(): void {
        $pageTitle = 'Contact Us';
        ob_start();
        require APP_PATH . '/Views/public/contact.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/main.php';
    }

    public function terms(): void {
        $pageTitle = 'Terms of Use';
        ob_start();
        require APP_PATH . '/Views/public/terms.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/main.php';
    }
}
