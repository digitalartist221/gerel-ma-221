<?php
namespace Packages\View;

class Component {
    public static function renderComponent($name, $attributes = []) {
        // Résolution de la classe PHP du composant
        $componentClassName = str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));
        $className = 'App\\Views\\Components\\' . $componentClassName;
        
        if (class_exists($className)) {
            // Instancie la classe avec les arguments
            $componentInstance = new $className($attributes);
            return $componentInstance->render();
        }

        // Rendu direct sans classe PHP : vue component simple
        return MadelineView::render('Components/' . $name, $attributes);
    }
}
