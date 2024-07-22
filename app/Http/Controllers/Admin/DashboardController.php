<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\BannerRepository;
use App\Repositories\Admin\CategoryRepository;
use App\Repositories\Admin\PageRepository;
use App\Repositories\Admin\UserRepository;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
        private CategoryRepository $categoryRepository,
        private BannerRepository $bannerRepository,
        private PageRepository $pageRepository,
    ){}

    public function index(): View
    {
        return view('admin.dashboard', [
            'totalActiveUsers' => $this->userRepository->getTotalCount(['status' => 1]),
            'totalInActiveUsers' => $this->userRepository->getTotalCount(['status' => 0]),
        ]);
    }
}
