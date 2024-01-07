<?php

namespace App\Actions\Utility\Dashboard;

class GetSidebarMenuAction
{
    public function handle()
    {
        return [
            [
                'text' => 'Dashboard',
                'url'  => route('admin.dashboard.index'),
                'icon' => 'PieChartIcon',
                'can'  => 'view_general_dashboard'
            ],
            [
                'text' => 'Bantuan Sosial',
                'url'  => route('admin.social-assistance.index'),
                'icon' => 'HoldingHandIcon',
                'can'  => ['view_general_dashboard', 'view_social_assistance']
            ],
            [
                'text' => 'Warga',
                'url'  => route('admin.resident.index'),
                'icon' => 'UsersIcon',
                'can'  => ['view_general_dashboard', 'view_resident']
            ],
            [
                'text' => 'Petugas',
                'url'  => route('admin.user-management.index'),
                'icon' => 'OfficerIcon',
                'can'  => ['view_user_management']
            ],
            [
                'text' => 'Kelurahan',
                'url'  => route('admin.village.index'),
                'icon' => 'CityIcon',
                'can'  => ['view_village']
            ],
        ];
    }
}
