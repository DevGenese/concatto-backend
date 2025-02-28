<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Locality;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class SchedulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schedules = [
            [
                'start_date' => '2023-10-15 09:00:00',
                'end_date' => '2023-10-15 12:00:00',
                'cooperative_id' => FLOOR(RAND() * 10) + 1,
                'locality_id' => FLOOR(RAND() * 5) + 1,
                'advice_type' => 'Agricultura',
                'observations' => 'Consulta sobre técnicas de plantio.',
                'location_id' => FLOOR(RAND() * 3) + 1,
                'finished' => false,
            ],
            [
                'start_date' => '2023-10-16 14:00:00',
                'end_date' => '2023-10-16 16:00:00',
                'cooperative_id' => FLOOR(RAND() * 10) + 1,
                'locality_id' => FLOOR(RAND() * 5) + 1,
                'advice_type' => 'Pecuária',
                'observations' => 'Consulta sobre manejo de gado.',
                'location_id' => FLOOR(RAND() * 3) + 1,
                'finished' => false,
            ],
            [
                'start_date' => '2023-10-17 10:00:00',
                'end_date' => '2023-10-17 11:30:00',
                'cooperative_id' => FLOOR(RAND() * 10) + 1,
                'locality_id' => FLOOR(RAND() * 5) + 1,
                'advice_type' => 'Sustentabilidade',
                'observations' => 'Consulta sobre práticas sustentáveis.',
                'location_id' => FLOOR(RAND() * 3) + 1,
                'finished' => false,
            ],
            [
                'start_date' => '2023-10-18 08:00:00',
                'end_date' => '2023-10-18 10:00:00',
                'cooperative_id' => FLOOR(RAND() * 10) + 1,
                'locality_id' => FLOOR(RAND() * 5) + 1,
                'advice_type' => 'Agricultura',
                'observations' => 'Consulta sobre irrigação.',
                'location_id' => FLOOR(RAND() * 3) + 1,
                'finished' => false,
            ],
            [
                'start_date' => '2023-10-19 13:00:00',
                'end_date' => '2023-10-19 15:00:00',
                'cooperative_id' => FLOOR(RAND() * 10) + 1,
                'locality_id' => FLOOR(RAND() * 5) + 1,
                'advice_type' => 'Pecuária',
                'observations' => 'Consulta sobre alimentação de gado.',
                'location_id' => FLOOR(RAND() * 3) + 1,
                'finished' => false,
            ]
        ];
        // Itera sobre os municípios
        foreach ($schedules as $schedule) {
            Locality::create($schedule);
        }
    }
}
