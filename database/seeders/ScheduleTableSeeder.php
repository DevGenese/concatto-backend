<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule; // Certifique-se de importar o modelo Schedule

class ScheduleSeeder extends Seeder
{
    public function run()
    {
        // Dados de exemplo para as agendas
        $schedules = [
            [
                'start_date' => '2023-10-15 09:00:00',
                'end_date' => '2023-10-15 12:00:00',
                'cooperative_id' => rand(1, 10), // Número aleatório entre 1 e 10
                'locality_id' => rand(1, 5), // Número aleatório entre 1 e 5
                'advice_type' => 'Agricultura',
                'observations' => 'Consulta sobre técnicas de plantio.',
                'location_id' => rand(1, 3), // Número aleatório entre 1 e 3
                'finished' => false,
            ],
            [
                'start_date' => '2023-10-16 14:00:00',
                'end_date' => '2023-10-16 16:00:00',
                'cooperative_id' => rand(1, 10),
                'locality_id' => rand(1, 5),
                'advice_type' => 'Pecuária',
                'observations' => 'Consulta sobre manejo de gado.',
                'location_id' => rand(1, 3),
                'finished' => false,
            ],
            [
                'start_date' => '2023-10-17 10:00:00',
                'end_date' => '2023-10-17 11:30:00',
                'cooperative_id' => rand(1, 10),
                'locality_id' => rand(1, 5),
                'advice_type' => 'Sustentabilidade',
                'observations' => 'Consulta sobre práticas sustentáveis.',
                'location_id' => rand(1, 3),
                'finished' => false,
            ],
            [
                'start_date' => '2023-10-18 08:00:00',
                'end_date' => '2023-10-18 10:00:00',
                'cooperative_id' => rand(1, 10),
                'locality_id' => rand(1, 5),
                'advice_type' => 'Agricultura',
                'observations' => 'Consulta sobre irrigação.',
                'location_id' => rand(1, 3),
                'finished' => false,
            ],
            [
                'start_date' => '2023-10-19 13:00:00',
                'end_date' => '2023-10-19 15:00:00',
                'cooperative_id' => rand(1, 10),
                'locality_id' => rand(1, 5),
                'advice_type' => 'Pecuária',
                'observations' => 'Consulta sobre alimentação de gado.',
                'location_id' => rand(1, 3),
                'finished' => false,
            ],
        ];

        // Itera sobre os agendamentos e os insere no banco de dados
        foreach ($schedules as $schedule) {
            Schedule::create($schedule);
        }
    }
}
