<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function pendingCustomers(Request $request)
    {
        $items = Customers::menunggu()
            ->latest()
            ->take(6)
            ->get(['id','nama_lengkap','email','created_at']);

        return response()->json([
            'count' => Customers::menunggu()->count(),
            'items' => $items->map(fn($c) => [
                'id'    => $c->id,
                'nama'  => $c->nama_lengkap,
                'email' => $c->email,
                'since' => $c->created_at->diffForHumans(),
                'url'   => route('admin.customers.show', $c),
            ]),
        ]);
    }
    
}
