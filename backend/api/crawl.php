<?php
header('Content-Type: application/json');

// fnkt. lehe laadimiseks 
function fetchPageContent($url) {
    $ch = curl_init();

    // cURL seaded
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; PHP crawler)");

    $content = curl_exec($ch);

    if (curl_errno($ch)) {
        echo json_encode(["error" => curl_error($ch)]);
        curl_close($ch);
        exit;
    }

    curl_close($ch);
    return $content;
}

$url = "https://matkasport.ee";
$htmlContent = fetchPageContent($url);

$dom = new DOMDocument();
@$dom->loadHTML($htmlContent);
$xpath = new DOMXPath($dom);

// kontrollib, millist tüüpi andmeid küsitakse (hetkel kõik/top) ja väljastab lehel json formaadis
if (isset($_GET['type']) && $_GET['type'] === 'categories') {
    $containerQuery = $dom->getElementById('st_mega_menu_wrap');
    $elements = $xpath->query(".//li", $containerQuery);
    $data = ["categories" => []];
    foreach ($elements as $element) {
        $data["categories"][] = ["category" => trim($element->nodeValue)];
    }
} else {
    $containerQuery = $dom->getElementById('featured_categories_container_d40e96ddab');
    $elements = $xpath->query(".//h3/a", $containerQuery);
    $data = ["products" => []];
    foreach ($elements as $element) {
        $data["products"][] = ["name" => trim($element->nodeValue)];
    }
}

echo json_encode($data, JSON_PRETTY_PRINT);
?>
