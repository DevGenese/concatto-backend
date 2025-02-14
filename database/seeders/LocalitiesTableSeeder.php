<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Locality;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class LocalitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // URL da API
          $url = 'https://servicodados.ibge.gov.br/api/v1/localidades/municipios';

          // Faz a requisição à API
          $response = Http::get($url);
       
          // Verifica se a requisição foi bem-sucedida
          if ($response->successful()) {
              $municipios = $response->json();
  
              // Itera sobre os municípios
              foreach ($municipios as $municipio) {
                  Locality::create([
                      'city' => $municipio['nome'],
                      'uf'   => $municipio['microrregiao']['mesorregiao']['UF']['sigla'],
                  ]);
              }
  
              $this->command->info('Municípios e UFs importados com sucesso!');
          } else {
              $this->command->error('Erro ao acessar a API do IBGE');
          }
    }
}
