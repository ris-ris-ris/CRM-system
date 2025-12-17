<?php
/**
 * Convert a white-background garland sprite JPG into a transparent PNG.
 *
 * Usage (inside container/project root):
 *   php scripts/make_gir_png.php public/gir.jpg public/gir.png
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

$in = $argv[1] ?? 'public/gir.jpg';
$out = $argv[2] ?? 'public/gir.png';

if (!extension_loaded('gd')) {
    fwrite(STDERR, "GD extension is required.\n");
    exit(2);
}
if (!is_file($in)) {
    fwrite(STDERR, "Input not found: {$in}\n");
    exit(1);
}

$im = @imagecreatefromjpeg($in);
if (!$im) {
    fwrite(STDERR, "Can't read input (expected JPG): {$in}\n");
    exit(3);
}

$w = imagesx($im);
$h = imagesy($im);

$dst = imagecreatetruecolor($w, $h);
imagealphablending($dst, false);
imagesavealpha($dst, true);

$transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
imagefilledrectangle($dst, 0, 0, $w, $h, $transparent);

for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $rgb = imagecolorat($im, $x, $y);
        $r = ($rgb >> 16) & 255;
        $g = ($rgb >> 8) & 255;
        $b = $rgb & 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $sat = $max - $min; // simple saturation proxy

        // Distance-to-white (0 = pure white)
        $dr = 255 - $r;
        $dg = 255 - $g;
        $db = 255 - $b;
        $dist = sqrt($dr * $dr + $dg * $dg + $db * $db);

        // Only key out low-saturation pixels (the JPG background/halos)
        $alpha = 0; // 0 = opaque, 127 = fully transparent
        if ($sat < 50) {
            // Smoothly fade from white to content (removes white halo)
            $t = ($dist - 18) / (120 - 18);
            if ($t <= 0) {
                $alpha = 127;
            } elseif ($t >= 1) {
                $alpha = 0;
            } else {
                // smoothstep
                $t = $t * $t * (3 - 2 * $t);
                $alpha = (int)round(127 * (1 - $t));
            }
        }

        // De-white halos: slightly darken semi-transparent pixels
        if ($alpha > 0 && $alpha < 127) {
            $k = 1 - ($alpha / 127) * 0.35;
            $r = (int)round($r * $k);
            $g = (int)round($g * $k);
            $b = (int)round($b * $k);
        }

        if ($alpha >= 127) {
            continue;
        }

        $col = imagecolorallocatealpha($dst, $r, $g, $b, $alpha);
        imagesetpixel($dst, $x, $y, $col);
    }
}

if (!@imagepng($dst, $out, 6)) {
    fwrite(STDERR, "Failed to write: {$out}\n");
    exit(4);
}

imagedestroy($im);
imagedestroy($dst);

fwrite(STDOUT, "Wrote: {$out}\n");


