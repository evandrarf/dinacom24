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
                'text' => 'Warga',
                'url'  => route('admin.resident.index'),
                'icon' => 'UsersIcon',
                'can'  => 'view_general_dashboard'
            ],
            [
                'text' => 'Kelurahan',
                'url'  => route('admin.village.index'),
                'icon' => 'CityIcon',
                'can'  => 'view_general_dashboard'
            ],
        ];
    }
}
