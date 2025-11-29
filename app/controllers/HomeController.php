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
    
    /**
     * Página de términos y condiciones
     */
    public function terms() {
        $settingModel = new SettingModel();
        $settings = $settingModel->getAllAsArray();
        
        $this->render('client/terms', [
            'terms' => $settings['terms_and_conditions'] ?? '',
            'privacy' => $settings['privacy_policy'] ?? '',
            'cancellation' => $settings['cancellation_policy'] ?? ''
        ], 'client');
    }
}
