<?php

/**
 * This classes generates blocks of HTML
 * that may be needed on multiple pages
 */
class HTMLGen {
    
    /**
     * This generates HTML for the rating of an app
     * @param int $app_id the id of the app to generate the stars for
     * @param boolean $include_form whether or not to include the HTML form for submitting a rating
     */
    public static function ratings($app_id, $include_form) {
        $rating = Database::ratingGet($app_id);
        echo "<p>";
            for($i = 0; $i < 5; $i++) {
                if($i <= $rating && $rating > 0) {
                    echo '<img src="assets/img/star_full.png" alt="Star">';
                }
                else if($i - .5 === $rating) {
                    echo '<img src="assets/img/star_half.png" alt="Half Star">';
                }
                else {
                    echo '<img src="assets/img/star_none.png" alt="Empty Star">';
                }
            }
            echo "<br> Ratings: " . Database::ratingGetCount($app_id);
        echo "</p>";
        
        if($include_form) {
            echo '
            <div id="rate-form">
                <form action="/" method="post" class="inline-form">
                    <label><input type="radio" name="rating" value="1">1</label>
                    <label><input type="radio" name="rating" value="2">2</label>
                    <label><input type="radio" name="rating" value="3">3</label>
                    <label><input type="radio" name="rating" value="4">4</label>
                    <label><input type="radio" name="rating" value="5">5</label>
                    <input type="hidden" name="id" value="'. $app_id .'">
                    <input type="submit" class="btn btn-default" name="action" value="Rate">
                </form>
            </div>';
        }
    }
}