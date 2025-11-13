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
        // Get dashboard statistics using Admin model methods
        $totalUsers = $this->adminModel->getTotalUsers();
        $totalPets = $this->adminModel->getTotalPets();
        $totalMedicalRecords = $this->adminModel->getTotalRekamMedis();
        $totalRecentMedicalRecords = $this->adminModel->getTotalRecentRekamMedis();

        // Get chart data
        $petSpeciesRawData = $this->adminModel->getPetSpeciesData();
        $rmMonthlyData = $this->adminModel->getRekamMedisMonthly();

        // Get recent activities
        $recentActivities = $this->adminModel->getRecentRekamMedisActivities(10);

        // Process chart data for monthly rekam medis
        $monthlyLabels = [];
        $monthlyData = [];
        if (!empty($rmMonthlyData) && is_array($rmMonthlyData)) {
            foreach ($rmMonthlyData as $data) {
                $bulan = $data['bulan'] ?? $data['month'] ?? 'Unknown';
                // Format bulan seperti di PHP asli: "M Y"
                if ($bulan !== 'Unknown' && strtotime($bulan . '-01')) {
                    $bulan = date('M Y', strtotime($bulan . '-01'));
                }
                $monthlyLabels[] = $bulan;
                $monthlyData[] = $data['jumlah'] ?? $data['total'] ?? 0;
            }
        }

        // Process chart data for pet species
        $petSpeciesLabels = [];
        $petSpeciesData = [];
        if (!empty($petSpeciesRawData) && is_array($petSpeciesRawData)) {
            foreach ($petSpeciesRawData as $data) {
                $petSpeciesLabels[] = $data['nama_jenis_hewan'] ?? $data['jenis_hewan'] ?? 'Unknown';
                $petSpeciesData[] = $data['total_hewan'] ?? $data['total'] ?? 0;
            }
        }

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
