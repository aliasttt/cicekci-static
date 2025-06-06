<?php
// Create images directory if it doesn't exist
$imageDir = 'assets/images';
if (!file_exists($imageDir)) {
    mkdir($imageDir, 0777, true);
}

// Array of image URLs and their local filenames
$images = [
    'https://images.unsplash.com/photo-1582798358481-1993b5d537e8' => 'red-rose-bouquet.jpg',
    'https://images.unsplash.com/photo-1582798358481-1993b5d537e8' => 'mixed-bouquet.jpg',
    'https://images.unsplash.com/photo-1582798358481-1993b5d537e8' => 'white-orchid.jpg',
    'https://images.unsplash.com/photo-1582798358481-1993b5d537e8' => 'wedding-arrangement.jpg',
    'https://images.unsplash.com/photo-1582798358481-1993b5d537e8' => 'monstera.jpg',
    'https://images.unsplash.com/photo-1582798358481-1993b5d537e8' => 'lily-arrangement.jpg',
    'https://images.unsplash.com/photo-1582798358481-1993b5d537e8' => 'hero-bg.jpg',
    'https://images.unsplash.com/photo-1582798358481-1993b5d537e8' => 'buket.jpg',
    'https://images.unsplash.com/photo-1582798358481-1993b5d537e8' => 'aranjman.jpg',
    'https://images.unsplash.com/photo-1582798358481-1993b5d537e8' => 'orchid.jpg',
    'https://images.unsplash.com/photo-1582798358481-1993b5d537e8' => 'testimonial1.jpg',
    'https://images.unsplash.com/photo-1582798358481-1993b5d537e8' => 'testimonial2.jpg',
    'https://images.unsplash.com/photo-1582798358481-1993b5d537e8' => 'testimonial3.jpg',
    'https://images.unsplash.com/photo-1582798358481-1993b5d537e8' => 'pattern.png'
];

// Download each image
foreach ($images as $url => $filename) {
    $filepath = $imageDir . '/' . $filename;
    if (!file_exists($filepath)) {
        $imageData = file_get_contents($url);
        if ($imageData !== false) {
            file_put_contents($filepath, $imageData);
            echo "Downloaded: $filename\n";
        } else {
            echo "Failed to download: $filename\n";
        }
    } else {
        echo "File already exists: $filename\n";
    }
}

echo "Image download complete!\n";
?> 