<?php
namespace Core;

interface Middleware {
    /**
     * Traite la requête.
     * Doit retourner vrai (true) pour continuer l'exécution, 
     * ou gérer une redirection/exception et retourner faux (false).
     */
    public function handle(): bool;
}
