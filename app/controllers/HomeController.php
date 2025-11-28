<?php
/**
 * Controlador Principal (Home)
 */

class HomeController extends Controller {
    private $restaurantModel;
    
    public function __construct($params = []) {
        parent::__construct($params);
        $this->restaurantModel = new RestaurantModel();
    }
    
    /**
     * Página principal
     */
    public function index() {
        $restaurants = $this->restaurantModel->getActive();
        
        $this->render('client/home', [
            'restaurants' => $restaurants
        ], 'client');
    }
    
    /**
     * Página de restaurante
     */
    public function restaurant() {
        $slug = $this->getParam('slug');
        $restaurant = $this->restaurantModel->findBySlug($slug);
        
        if (!$restaurant) {
            $this->redirect('');
        }
        
        $areas = $this->restaurantModel->getAreas($restaurant['id']);
        $schedules = $this->restaurantModel->getSchedules($restaurant['id']);
        
        $this->render('client/restaurant', [
            'restaurant' => $restaurant,
            'areas' => $areas,
            'schedules' => $schedules
        ], 'client');
    }
}
