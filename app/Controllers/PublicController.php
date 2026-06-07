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
}
