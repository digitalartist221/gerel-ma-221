<?php
namespace App\Controllers;

use App\Models\Waitlist;
use App\Models\User;
use Packages\View\MadelineView;
use Packages\Http\Request;

/**
 * Controller: Super-Admin & Waitlist
 */
class AdminController {
    public function waitlist() {
        $list = Waitlist::fari();
        return MadelineView::render('admin/waitlist', ['list' => $list]);
    }

    public function joinWaitlist() {
        $req = new Request();
        $email = $req->input('email');
        
        if ($email) {
            Waitlist::bindu([
                'email' => $email,
                'name' => $req->input('name', ''),
                'entreprise' => $req->input('entreprise', ''),
                'created_at' => date('Y-m-d H:i:s'),
                'statut' => 'en_attente'
            ]);
            return json_encode(['success' => true, 'message' => 'Diadieuf! Inscription réussie.']);
        }
        return json_encode(['success' => false, 'message' => 'Email requis.']);
    }

    public function users() {
        $users = User::fari();
        return MadelineView::render('admin/users', ['users' => $users]);
    }
}
