<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $adminModel;

    public function __construct()
    {
        $this->adminModel = new Admin();
    }

    public function index()
    {
        $totalUsers = $this->adminModel->getTotalUsers();
        $totalPets = $this->adminModel->getTotalPets();
        $totalMedicalRecords = $this->adminModel->getTotalRekamMedis();
        $totalRecentMedicalRecords = $this->adminModel->getTotalRecentRekamMedis();

        $petSpeciesRawData = $this->adminModel->getPetSpeciesData();
        $rmMonthlyData = $this->adminModel->getRekamMedisMonthly();

        $monthlyLabels = [];
        $monthlyData = [];

        if (!empty($rmMonthlyData)) {
            foreach ($rmMonthlyData as $data) {

                $bulan = $data->bulan ?? 'Unknown';

                if ($bulan !== 'Unknown' && strtotime($bulan . '-01')) {
                    $bulan = date('M Y', strtotime($bulan . '-01'));
                }

                $monthlyLabels[] = $bulan;
                $monthlyData[] = $data->total ?? 0;
            }
        }

        $petSpeciesLabels = [];
        $petSpeciesData = [];

        if (!empty($petSpeciesRawData)) {
            foreach ($petSpeciesRawData as $data) {
                $petSpeciesLabels[] = $data->nama_jenis_hewan ?? 'Unknown';
                $petSpeciesData[]  = $data->total_hewan ?? 0;
            }
        }

        $recentActivities = $this->adminModel->getRecentRekamMedisActivities(10);

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalPets' => $totalPets,
            'totalMedicalRecords' => $totalMedicalRecords,
            'totalRecentMedicalRecords' => $totalRecentMedicalRecords,

            'monthlyLabels' => $monthlyLabels,
            'monthlyData' => $monthlyData,

            'petSpeciesLabels' => $petSpeciesLabels,
            'petSpeciesData' => $petSpeciesData,

            'recentActivities' => $recentActivities,
        ]);
    }
}
