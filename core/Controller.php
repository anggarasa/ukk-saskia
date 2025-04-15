<?php

class Controller {
    public function model($model) {
        require_once '../app/models/' . $model . '.php';

        // Contoh: jika $model=="PelangganModel" dan kelasnya ada di namespace "app\models"
        $modelClass = 'app\\models\\' . $model;
        return new $modelClass;
    }

    public function view($view, $data = []) {
        require_once '../app/views/' . $view . '.php';
    }
}
