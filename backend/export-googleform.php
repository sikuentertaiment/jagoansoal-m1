<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

require_once __DIR__ . '/config.php';

function absUrl($url) {
  if (empty($url)) return '';
  if (strpos($url, '://') !== false) return $url;
  $scheme = (!empty($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http');
  $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
  return $scheme . '://' . $host . '/' . ltrim($url, '/');
}

$body = json_decode(file_get_contents('php://input'), true);

$title = trim($body['title'] ?? 'Soal');
$questions = $body['questions'] ?? [];
$accessToken = trim($body['access_token'] ?? '');
$identityFields = $body['identity_fields'] ?? [];
$showHeader = !empty($body['show_header']);
$headerText = trim($body['header_text'] ?? '');
$showInfo = !empty($body['show_info']);
$infoFields = $body['info_fields'] ?? [];

if (empty($accessToken)) {
    echo json_encode(['error' => 'Access token is required']);
    exit();
}

if (empty($questions) || !is_array($questions)) {
    echo json_encode(['error' => 'Questions data is required']);
    exit();
}

try {
    $formData = createGoogleForm($title, $accessToken);
    $formId = $formData['formId'];
    $formUrl = $formData['responderUri'];

    $description = '';
    if ($showHeader && !empty($headerText)) {
        $description = $headerText;
    }
    if ($showInfo && !empty($infoFields)) {
        $infoLines = [];
        foreach ($infoFields as $info) {
            $label = stripNewlines($info['label'] ?? '');
            $value = stripNewlines($info['value'] ?? '');
            if (empty($label)) continue;
            $infoLines[] = $value ? $label . ': ' . $value : $label;
        }
        if (!empty($infoLines)) {
            if (!empty($description)) $description .= "\n\n";
            $description .= implode("\n", $infoLines);
        }
    }
    if (!empty($description)) {
        setFormDescription($formId, $description, $accessToken);
    }

    convertToQuiz($formId, $accessToken);
    addQuestionsToForm($formId, $questions, $accessToken, $identityFields);

    echo json_encode([
        'success' => true,
        'form_url' => $formUrl,
        'form_id' => $formId,
        'message' => 'Google Form berhasil dibuat'
    ]);
} catch (Exception $e) {
    error_log('Google Form export error: ' . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
}

function createGoogleForm($title, $accessToken) {
    $payload = json_encode([
        'info' => ['title' => stripNewlines($title)]
    ]);

    $ch = curl_init('https://forms.googleapis.com/v1/forms');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ],
        CURLOPT_TIMEOUT => 30,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        $data = json_decode($response, true);
        $errorMsg = $data['error']['message'] ?? 'Failed to create form (HTTP ' . $httpCode . ')';
        throw new Exception($errorMsg);
    }

    $data = json_decode($response, true);
    return [
        'formId' => $data['formId'],
        'responderUri' => $data['responderUri'] ?? 'https://docs.google.com/forms/d/e/' . $data['formId'] . '/viewform'
    ];
}

function stripNewlines($str) {
    return preg_replace('/\s+/', ' ', trim($str));
}

function setFormDescription($formId, $description, $accessToken) {
    $payload = json_encode([
        'requests' => [
            [
                'updateFormInfo' => [
                    'info' => ['description' => stripNewlines($description)],
                    'updateMask' => 'description'
                ]
            ]
        ]
    ]);

    $ch = curl_init('https://forms.googleapis.com/v1/forms/' . urlencode($formId) . ':batchUpdate');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ],
        CURLOPT_TIMEOUT => 15,
    ]);

    curl_exec($ch);
    curl_close($ch);
}

function addQuestionsToForm($formId, $questions, $accessToken, $identityFields = []) {
    $requests = [];
    $currentIndex = 0;

    foreach ($identityFields as $field) {
        $requests[] = [
            'createItem' => [
                'item' => [
                    'title' => stripNewlines($field),
                    'questionItem' => [
                        'question' => [
                            'textQuestion' => ['paragraph' => false],
                            'required' => true
                        ]
                    ]
                ],
                'location' => ['index' => $currentIndex]
            ]
        ];
        $currentIndex++;
    }

    foreach ($questions as $i => $q) {
        // add image item first if question has image
        if (!empty($q['image_url'])) {
            $imageUrl = absUrl($q['image_url']);
            $imageRequest = [
                'createItem' => [
                    'item' => [
                        'imageItem' => [
                            'image' => [
                                'sourceUri' => $imageUrl
                            ]
                        ]
                    ],
                    'location' => ['index' => $currentIndex]
                ]
            ];
            $requests[] = $imageRequest;
            $currentIndex++;
        }

        $questionText = stripNewlines($q['question'] ?? 'Soal ' . ($i + 1));
        $hasOptions = isset($q['options']) && is_array($q['options']) && count($q['options']) > 0;

        $questionItem = [];

        if ($hasOptions) {
            $options = array_map(function($opt) {
                return ['value' => stripNewlines($opt)];
            }, $q['options']);

            $questionItem['choiceQuestion'] = [
                'type' => 'RADIO',
                'options' => $options
            ];

            if (!empty($q['answer'])) {
                $questionItem['grading'] = [
                    'pointValue' => 1,
                    'correctAnswers' => [
                        'answers' => [['value' => stripNewlines($q['answer'])]]
                    ]
                ];
            }
        } else {
            $questionItem['textQuestion'] = [
                'paragraph' => true
            ];
        }

        $item = [
            'title' => $questionText,
            'questionItem' => [
                'question' => $questionItem
            ]
        ];

        if (!empty($q['explanation']) && !$hasOptions) {
            $item['questionItem']['question']['grading']['generalFeedback'] = [
                'text' => stripNewlines($q['explanation'])
            ];
        }

        $requests[] = [
            'createItem' => [
                'item' => $item,
                'location' => ['index' => $currentIndex]
            ]
        ];
        $currentIndex++;
    }

    $payload = json_encode(['requests' => $requests]);

    $ch = curl_init('https://forms.googleapis.com/v1/forms/' . urlencode($formId) . ':batchUpdate');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ],
        CURLOPT_TIMEOUT => 30,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        $data = json_decode($response, true);
        $errorMsg = $data['error']['message'] ?? 'Failed to add questions (HTTP ' . $httpCode . ')';
        throw new Exception($errorMsg);
    }
}

function convertToQuiz($formId, $accessToken) {
    $payload = json_encode([
        'requests' => [
            [
                'updateSettings' => [
                    'settings' => [
                        'quizSettings' => ['isQuiz' => true]
                    ],
                    'updateMask' => 'quizSettings.isQuiz'
                ]
            ]
        ]
    ]);

    $ch = curl_init('https://forms.googleapis.com/v1/forms/' . urlencode($formId) . ':batchUpdate');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ],
        CURLOPT_TIMEOUT => 15,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        $data = json_decode($response, true);
        $errorMsg = $data['error']['message'] ?? 'Failed to convert to quiz (HTTP ' . $httpCode . ')';
        throw new Exception($errorMsg);
    }

    try {
        $payload2 = json_encode([
            'publishSettings' => [
                'isPublished' => true,
                'isAcceptingResponses' => true
            ]
        ]);

        $ch2 = curl_init('https://forms.googleapis.com/v1/forms/' . urlencode($formId) . ':setPublishSettings');
        curl_setopt_array($ch2, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload2,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken
            ],
            CURLOPT_TIMEOUT => 15,
        ]);

        curl_exec($ch2);
        curl_close($ch2);
    } catch (Exception $e) {
    }
}
