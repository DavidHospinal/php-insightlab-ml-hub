<?php
declare(strict_types=1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
        require_once __DIR__ . '/../../vendor/autoload.php';
    } else {
        throw new Exception('Composer autoloader not found');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Failed to load dependencies: ' . $e->getMessage()]);
    exit;
}

use Phpml\Clustering\KMeans;

function performKMeansClustering($points, $k) {
    try {
        // Validar datos
        if (empty($points) || $k < 1 || $k > count($points)) {
            throw new Exception('Invalid clustering parameters');
        }
        
        // Convertir puntos a formato esperado por PHP-ML
        $samples = [];
        foreach ($points as $point) {
            if (!isset($point['x']) || !isset($point['y'])) {
                throw new Exception('Each point must have x and y coordinates');
            }
            $samples[] = [(float)$point['x'], (float)$point['y']];
        }
        
        // Realizar clustering K-Means
        $kmeans = new KMeans($k);
        $clusters = $kmeans->cluster($samples);
        
        // Formatear resultado
        $result = [];
        foreach ($clusters as $clusterIndex => $cluster) {
            $clusterPoints = [];
            foreach ($cluster as $pointIndex => $point) {
                $clusterPoints[] = [
                    'x' => $point[0],
                    'y' => $point[1],
                    'original_index' => array_search($point, $samples)
                ];
            }
            $result[] = [
                'cluster_id' => $clusterIndex,
                'points' => $clusterPoints,
                'size' => count($clusterPoints)
            ];
        }
        
        return [
            'success' => true,
            'clusters' => $result,
            'total_clusters' => count($result),
            'total_points' => count($samples),
            'k' => $k
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => 'Error performing clustering: ' . $e->getMessage()
        ];
    }
}

function generateSyntheticData($count = 50) {
    try {
        // Generar datos sintéticos en diferentes regiones
        $points = [];
        
        // Cluster 1: Región superior izquierda
        for ($i = 0; $i < $count / 3; $i++) {
            $points[] = [
                'x' => rand(100, 300) + (rand(-50, 50) / 10),
                'y' => rand(100, 300) + (rand(-50, 50) / 10)
            ];
        }
        
        // Cluster 2: Región central derecha
        for ($i = 0; $i < $count / 3; $i++) {
            $points[] = [
                'x' => rand(500, 700) + (rand(-50, 50) / 10),
                'y' => rand(250, 450) + (rand(-50, 50) / 10)
            ];
        }
        
        // Cluster 3: Región inferior
        for ($i = 0; $i < $count / 3; $i++) {
            $points[] = [
                'x' => rand(200, 600) + (rand(-50, 50) / 10),
                'y' => rand(500, 700) + (rand(-50, 50) / 10)
            ];
        }
        
        return [
            'success' => true,
            'points' => $points,
            'count' => count($points)
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => 'Error generating synthetic data: ' . $e->getMessage()
        ];
    }
}

// Manejar requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['action'])) {
        echo json_encode(['success' => false, 'error' => 'Invalid input']);
        exit;
    }
    
    switch ($input['action']) {
        case 'cluster':
            if (!isset($input['points']) || !isset($input['k'])) {
                echo json_encode(['success' => false, 'error' => 'Required: points array and k value']);
                exit;
            }
            
            $k = intval($input['k']);
            if ($k < 1 || $k > 10) {
                echo json_encode(['success' => false, 'error' => 'K must be between 1-10']);
                exit;
            }
            
            echo json_encode(performKMeansClustering($input['points'], $k));
            break;
            
        case 'generate_data':
            $count = isset($input['count']) ? intval($input['count']) : 50;
            if ($count < 10 || $count > 200) {
                echo json_encode(['success' => false, 'error' => 'Count must be between 10-200']);
                exit;
            }
            
            echo json_encode(generateSyntheticData($count));
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Unknown action']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Only POST method allowed']);
}
?>