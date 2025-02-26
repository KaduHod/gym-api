<?php
namespace Core\Auth;

use App\Models\User;
use Illuminate\Http\Request;

interface AuthServiceI {
    public function run(Request $request): ?User;
    public function getAuthType(Request $request):?string;
}
