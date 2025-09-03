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

use Phpml\Dataset\CsvDataset;
use Phpml\Dataset\ArrayDataset;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WordTokenizer;
use Phpml\CrossValidation\StratifiedRandomSplit;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Classification\NaiveBayes;

function detectLanguage($text) {
    try {
        // Cargar dataset de idiomas
        $dataset = new CsvDataset(__DIR__ . '/../../data/languages.csv', 1);
        $vectorizer = new TokenCountVectorizer(new WordTokenizer());
        $tfIdfTransformer = new TfIdfTransformer();

        // Preparar samples
        $samples = [];
        foreach ($dataset->getSamples() as $sample) {
            $samples[] = $sample[0];
        }

        // Procesar features
        $vectorizer->fit($samples);
        $vectorizer->transform($samples);
        $tfIdfTransformer->fit($samples);
        $tfIdfTransformer->transform($samples);

        // Crear dataset y entrenar
        $trainDataset = new ArrayDataset($samples, $dataset->getTargets());
        $classifier = new NaiveBayes();
        $classifier->train($samples, $dataset->getTargets());

        // Predecir idioma del texto input
        $inputSample = [$text];
        $vectorizer->transform($inputSample);
        $tfIdfTransformer->transform($inputSample);
        
        $prediction = $classifier->predict($inputSample);
        
        return [
            'success' => true,
            'language' => $prediction[0],
            'text' => $text,
            'confidence' => rand(85, 95) / 100 // Simulado para demo
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => 'Error detecting language: ' . $e->getMessage()
        ];
    }
}

function filterSpam($text) {
    try {
        // Detectar spam usando palabras clave en inglés y español
        $spamKeywords = [
            // English keywords
            'free', 'win', 'prize', 'money', 'cash', 'offer', 'limited time',
            'click here', 'act now', 'urgent', 'congratulations', 'winner',
            'discount', 'sale', 'viagra', 'pills', 'weight loss', 'earn money',
            'work from home', 'guaranteed', 'risk free', 'no obligation',
            // Spanish keywords
            'gratis', 'ganar', 'premio', 'dinero', 'efectivo', 'oferta', 'tiempo limitado',
            'haz clic aquí', 'actúa ahora', 'urgente', 'felicitaciones', 'ganador',
            'descuento', 'venta', 'pastillas', 'pérdida de peso', 'ganar dinero',
            'trabajar desde casa', 'garantizado', 'sin riesgo', 'sin obligación'
        ];
        
        $text_lower = strtolower($text);
        $spamScore = 0;
        $totalKeywords = count($spamKeywords);
        
        foreach ($spamKeywords as $keyword) {
            if (strpos($text_lower, $keyword) !== false) {
                $spamScore++;
            }
        }
        
        $confidence = ($spamScore / $totalKeywords);
        $isSpam = $spamScore >= 2; // Si encuentra 2+ palabras spam
        
        return [
            'success' => true,
            'is_spam' => $isSpam,
            'classification' => $isSpam ? 'spam' : 'ham',
            'text' => $text,
            'confidence' => max(0.6, min(0.98, $confidence + 0.3)) // Entre 60-98%
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => 'Error filtering spam: ' . $e->getMessage()
        ];
    }
}

// Manejar requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['action']) || !isset($input['text'])) {
        echo json_encode(['success' => false, 'error' => 'Invalid input']);
        exit;
    }
    
    switch ($input['action']) {
        case 'detect_language':
            echo json_encode(detectLanguage($input['text']));
            break;
            
        case 'filter_spam':
            echo json_encode(filterSpam($input['text']));
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Unknown action']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Only POST method allowed']);
}
?>