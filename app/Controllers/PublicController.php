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

        $pageTitle = 'Malaysia Homestay Directory — Book Direct from Owners';
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
        $images   = Listing::getImages((int) $listing['id']);
        $facilities = Listing::getFacilitiesForListing((int) $listing['id']);
        $similar  = Listing::similarListings((int) $listing['id'], (int) $listing['city_id'], (int) $listing['state_id'], 6);
        $pageTitle  = $listing['title'] . ' — ihomestay.my';

        ob_start();
        require APP_PATH . '/Views/public/listing.php';
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
        if (!empty($_GET['q']))      $filters['q']      = trim($_GET['q']);
        if (!empty($_GET['guests'])) $filters['guests'] = (int) $_GET['guests'];

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

        if ($contextCity) {
            $pageTitle = $contextCity['name'] . ' Homestays — ihomestay.my';
        } elseif ($contextState) {
            $pageTitle = $contextState['name'] . ' Homestays — ihomestay.my';
        } else {
            $pageTitle = 'Search Homestays — ihomestay.my';
        }

        ob_start();
        require APP_PATH . '/Views/public/search.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/main.php';
    }
}
