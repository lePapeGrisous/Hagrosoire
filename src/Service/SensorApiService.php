<?php

namespace App\Service;

use App\Entity\Sensor;
use App\Repository\SensorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SensorApiService
{
    private string $apiBaseUrl;

    public function __construct(
        private HttpClientInterface $httpClient,
        private EntityManagerInterface $entityManager,
        private SensorRepository $sensorRepository,
        string $sensorApiUrl = 'http://localhost:3000'
    ) {
        $this->apiBaseUrl = rtrim($sensorApiUrl, '/');
    }

    /**
     * Synchronise tous les capteurs depuis l'API
     * @return array Resultats de la synchronisation
     */
    public function syncAllSensors(): array
    {
        $results = [
            'created' => 0,
            'updated' => 0,
            'errors' => [],
        ];

        try {
            // Recuperer les dernieres mesures de chaque capteur
            $response = $this->httpClient->request('GET', $this->apiBaseUrl . '/api/latest');
            $data = $response->toArray();

            if (!isset($data['success']) || !$data['success'] || !isset($data['data'])) {
                $results['errors'][] = 'Reponse API invalide';
                return $results;
            }

            foreach ($data['data'] as $sensorData) {
                try {
                    $result = $this->syncSensor($sensorData);
                    if ($result === 'created') {
                        $results['created']++;
                    } elseif ($result === 'updated') {
                        $results['updated']++;
                    }
                } catch (\Exception $e) {
                    $results['errors'][] = sprintf(
                        'Erreur pour capteur %s: %s',
                        $sensorData['device_name'] ?? 'inconnu',
                        $e->getMessage()
                    );
                }
            }

            $this->entityManager->flush();

        } catch (\Exception $e) {
            $results['errors'][] = 'Erreur API: ' . $e->getMessage();
        }

        return $results;
    }

    /**
     * Synchronise un capteur specifique
     */
    private function syncSensor(array $data): string
    {
        $deviceName = $data['device_name'] ?? null;
        if (!$deviceName) {
            throw new \InvalidArgumentException('device_name manquant');
        }

        // Chercher le capteur existant par son nom
        $sensor = $this->sensorRepository->findOneBy(['name' => $deviceName]);

        if ($sensor === null) {
            // Creer un nouveau capteur
            $sensor = new Sensor();
            $sensor->setName($deviceName);
            $this->entityManager->persist($sensor);
            $result = 'created';
        } else {
            $result = 'updated';
        }

        // Mettre a jour les donnees
        $this->updateSensorFromData($sensor, $data);

        return $result;
    }

    /**
     * Met a jour les proprietes du capteur depuis les donnees API
     */
    private function updateSensorFromData(Sensor $sensor, array $data): void
    {
        // Date/timestamp
        if (isset($data['timestamp'])) {
            $date = new \DateTimeImmutable($data['timestamp']);
            $sensor->setDate($date);
        } else {
            $sensor->setDate(new \DateTimeImmutable());
        }

        // Humidite
        if (isset($data['humidity'])) {
            $sensor->setHumidity((int) round($data['humidity']));
        }

        // Temperature
        if (isset($data['temperature'])) {
            $sensor->setTemperature((int) round($data['temperature']));
        }

        // Batterie
        if (isset($data['battery'])) {
            $sensor->setBatterie((float) $data['battery']);
        }

        // Latitude
        if (isset($data['latitude'])) {
            $sensor->setLatitude((float) $data['latitude']);
        }

        // Longitude
        if (isset($data['longitude'])) {
            $sensor->setLongitude((float) $data['longitude']);
        }
    }

    /**
     * Recupere l'historique d'un capteur specifique
     */
    public function getSensorHistory(string $deviceName, int $limit = 100): array
    {
        try {
            $response = $this->httpClient->request(
                'GET',
                $this->apiBaseUrl . '/api/device/' . urlencode($deviceName),
                ['query' => ['limit' => $limit]]
            );

            $data = $response->toArray();

            if (isset($data['success']) && $data['success'] && isset($data['data'])) {
                return $data['data'];
            }

            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Liste tous les capteurs connus de l'API
     */
    public function getApiDevices(): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->apiBaseUrl . '/api/devices');
            $data = $response->toArray();

            if (isset($data['success']) && $data['success'] && isset($data['data'])) {
                return $data['data'];
            }

            return [];
        } catch (\Exception $e) {
            return [];
        }
    }
}
