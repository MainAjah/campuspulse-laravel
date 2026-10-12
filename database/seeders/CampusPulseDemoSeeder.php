<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\Place;
use App\Models\Report;
use App\Models\ReportConfirmation;
use App\Models\ModerationAction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CampusPulseDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo campus
        $campus = Campus::firstOrCreate(
            ['name' => 'Kampus Demo (DEMO)'],
            [
                'city' => 'Jakarta',
                'is_demo' => true,
            ]
        );

        // Create demo users
        $user = User::firstOrCreate(
            ['email' => 'demo@campuspulse.id'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('demo1234'),
                'role' => 'USER',
            ]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@campuspulse.id'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin1234'),
                'role' => 'ADMIN',
            ]
        );

        // Create demo places
        $places = [
            [
                'name' => 'Perpustakaan Pusat (DEMO)',
                'category' => 'LIBRARY',
                'description' => 'Perpustakaan utama kampus dengan koleksi lengkap dan ruang baca nyaman.',
                'latitude' => -6.2088,
                'longitude' => 106.8456,
                'floor' => '1-3',
            ],
            [
                'name' => 'Ruang Belajar A (DEMO)',
                'category' => 'STUDY_ROOM',
                'description' => 'Ruang belajar individu dengan AC dan wifi cepat.',
                'latitude' => -6.2090,
                'longitude' => 106.8460,
                'floor' => '2',
            ],
            [
                'name' => 'Laboratorium Komputer (DEMO)',
                'category' => 'LABORATORY',
                'description' => 'Lab komputer dengan 50 unit PC dan software lengkap.',
                'latitude' => -6.2085,
                'longitude' => 106.8450,
                'floor' => '1',
            ],
            [
                'name' => 'Kantin Pusat (DEMO)',
                'category' => 'CANTEEN',
                'description' => 'Kantin utama dengan variasi makanan terjangkau.',
                'latitude' => -6.2095,
                'longitude' => 106.8465,
                'floor' => '1',
            ],
            [
                'name' => 'Zona Wi-Fi Kampus (DEMO)',
                'category' => 'WIFI',
                'description' => 'Area hotspot Wi-Fi gratis di sekitar taman kampus.',
                'latitude' => -6.2092,
                'longitude' => 106.8458,
                'floor' => null,
            ],
            [
                'name' => 'Ruang Rapat (DEMO)',
                'category' => 'OTHER',
                'description' => 'Ruang rapat dan diskusi untuk organisasi mahasiswa.',
                'latitude' => -6.2087,
                'longitude' => 106.8453,
                'floor' => '3',
            ],
        ];

        $placeModels = [];
        foreach ($places as $placeData) {
            $place = Place::firstOrCreate(
                ['name' => $placeData['name'], 'campus_id' => $campus->id],
                array_merge($placeData, ['campus_id' => $campus->id])
            );
            $placeModels[] = $place;
        }

        // Create demo reports
        $reportData = [
            [
                'place' => $placeModels[3], // Kantin
                'user' => $user,
                'category' => 'CROWD',
                'description' => 'Kantin sangat ramai saat jam makan siang, antrian panjang di setiap counter.',
                'status' => 'VERIFIED',
            ],
            [
                'place' => $placeModels[4], // Wi-Fi
                'user' => $user,
                'category' => 'WIFI',
                'description' => 'Sinyal Wi-Fi lemah di area taman belakang gedung A, sering putus-putus.',
                'status' => 'PENDING',
            ],
            [
                'place' => $placeModels[2], // Lab Komputer
                'user' => $user,
                'category' => 'DAMAGE',
                'description' => 'PC nomor 12 layarnya berkedip-kedip dan keyboard tidak responsif.',
                'status' => 'PENDING',
            ],
            [
                'place' => $placeModels[1], // Ruang Belajar
                'user' => $user,
                'category' => 'CLEANLINESS',
                'description' => 'Lantai ruang belajar kotor, ada sisa makanan dan minuman yang dibiarkan.',
                'status' => 'RESOLVED',
            ],
        ];

        $reportModels = [];
        foreach ($reportData as $rd) {
            $report = Report::firstOrCreate(
                [
                    'place_id' => $rd['place']->id,
                    'user_id' => $rd['user']->id,
                    'category' => $rd['category'],
                    'description' => $rd['description'],
                ],
                ['status' => $rd['status']]
            );
            $reportModels[] = $report;
        }

        // Create demo confirmations
        $confirmationData = [
            ['report' => $reportModels[0], 'user' => $admin, 'still_valid' => true, 'note' => 'Masih ramai saat dicek'],
            ['report' => $reportModels[0], 'user' => $user, 'still_valid' => true, 'note' => 'Benar, antrian panjang'],
            ['report' => $reportModels[1], 'user' => $admin, 'still_valid' => true, 'note' => 'Konfirmasi lemahnya sinyal'],
            ['report' => $reportModels[2], 'user' => $admin, 'still_valid' => true, 'note' => 'PC 12 memang bermasalah'],
        ];

        foreach ($confirmationData as $cd) {
            ReportConfirmation::firstOrCreate(
                [
                    'report_id' => $cd['report']->id,
                    'user_id' => $cd['user']->id,
                ],
                ['still_valid' => $cd['still_valid'], 'note' => $cd['note']]
            );
        }

        // Create demo moderation actions
        $moderationData = [
            ['report' => $reportModels[0], 'moderator' => $admin, 'action' => 'VERIFY', 'note' => 'Diverifikasi admin'],
            ['report' => $reportModels[3], 'moderator' => $admin, 'action' => 'RESOLVE', 'note' => 'Sudah dibersihkan'],
        ];

        foreach ($moderationData as $md) {
            ModerationAction::firstOrCreate(
                [
                    'report_id' => $md['report']->id,
                    'moderator_id' => $md['moderator']->id,
                    'action' => $md['action'],
                ],
                ['note' => $md['note']]
            );
        }

        $this->command->info('CampusPulse demo data seeded successfully.');
    }
}