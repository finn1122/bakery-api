<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Features\Ftp\Domain\Repositories\FtpRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\BakeryResource;
use App\Models\Bakery;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

}
