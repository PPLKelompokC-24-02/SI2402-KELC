<?php

namespace App\Services;

use App\Models\CurriculumVitae;

class GenerateCvPdf
{
    protected float $pageWidth = 595.28; // A4 width in pt
    protected float $pageHeight = 841.89; // A4 height in pt
    protected float $marginLeft = 42.0;
    protected float $marginRight = 42.0;
    protected float $marginTop = 45.0;
    protected float $marginBottom = 45.0;

    protected array $pages = [];
    protected int $currentPage = -1;
    protected float $currentY = 0;
    protected array $objects = [];
    protected int $objectCount = 0;

    public function generate(CurriculumVitae $cv, ?string $template = null): string
    {
        $template = $template ?: ($cv->template ?: 'ats-classic');
        $this->pages = [];
        $this->currentPage = -1;

        $this->addPage();

        if ($template === 'modern') {
            $this->renderModernHeader($cv);
        } else {
            $this->renderClassicHeader($cv);
        }

        // 1. Ringkasan Profil
        if (!empty($cv->summary)) {
            $this->renderSectionHeading('RINGKASAN PROFESIONAL', $template);
            $this->renderParagraph($cv->summary, 10, false, '#333333');
            $this->currentY -= 10;
        }

        // 2. Pendidikan
        $educations = $cv->getEducationsList();
        if (!empty($educations)) {
            $this->renderSectionHeading('PENDIDIKAN', $template);
            foreach ($educations as $edu) {
                $this->checkPageBreak(50);
                
                $inst = $edu['institution'] ?? '-';
                $degree = trim(($edu['degree'] ?? '') . ' ' . (!empty($edu['field_of_study']) ? '— ' . $edu['field_of_study'] : ''));
                $period = trim(($edu['start_year'] ?? '') . ' - ' . ($edu['end_year'] ?? 'Sekarang'));
                
                $this->renderTwoColumnHeader($inst, $period, true);
                if (!empty($degree)) {
                    $this->renderText($degree, 10, 'italic', '#444444');
                    $this->currentY -= 13;
                }
                if (!empty($edu['gpa'])) {
                    $this->renderText('Indeks Prestasi Kumulatif (IPK): ' . $edu['gpa'], 9.5, 'regular', '#333333');
                    $this->currentY -= 13;
                }
                if (!empty($edu['description'])) {
                    $this->renderParagraph($edu['description'], 9.5, false, '#555555');
                }
                $this->currentY -= 6;
            }
            $this->currentY -= 6;
        }

        // 3. Pengalaman Kerja & Organisasi
        $experiences = $cv->getExperiencesList();
        if (!empty($experiences)) {
            $this->renderSectionHeading('PENGALAMAN KERJA & ORGANISASI', $template);
            foreach ($experiences as $exp) {
                $this->checkPageBreak(55);

                $title = $exp['position'] ?? '-';
                $company = $exp['company'] ?? '-';
                if (!empty($exp['location'])) {
                    $company .= ' (' . $exp['location'] . ')';
                }
                $period = ($exp['start_date'] ?? '') . ' - ' . (!empty($exp['is_current']) ? 'Sekarang' : ($exp['end_date'] ?? ''));

                $this->renderTwoColumnHeader($title, $period, true);
                $this->renderText($company, 10, 'italic', '#444444');
                $this->currentY -= 13;

                if (!empty($exp['description'])) {
                    $this->renderParagraph($exp['description'], 9.5, false, '#555555');
                }
                $this->currentY -= 6;
            }
            $this->currentY -= 6;
        }

        // 4. Proyek & Portofolio
        $projects = $cv->getProjectsList();
        if (!empty($projects)) {
            $this->renderSectionHeading('PROYEK & PORTOFOLIO', $template);
            foreach ($projects as $proj) {
                $this->checkPageBreak(55);

                $title = $proj['title'] ?? '-';
                $role = !empty($proj['role']) ? 'Peran: ' . $proj['role'] : '';
                $period = $proj['period'] ?? '';

                $this->renderTwoColumnHeader($title, $period, true);
                if (!empty($role)) {
                    $this->renderText($role, 9.5, 'italic', '#444444');
                    $this->currentY -= 13;
                }
                if (!empty($proj['technologies'])) {
                    $this->renderText('Teknologi: ' . $proj['technologies'], 9.5, 'regular', '#073763');
                    $this->currentY -= 13;
                }
                if (!empty($proj['description'])) {
                    $this->renderParagraph($proj['description'], 9.5, false, '#555555');
                }
                if (!empty($proj['url'])) {
                    $this->renderText('Tautan: ' . $proj['url'], 9, 'regular', '#1b4d3e');
                    $this->currentY -= 12;
                }
                $this->currentY -= 6;
            }
            $this->currentY -= 6;
        }

        // 5. Keterampilan
        $skills = $cv->getSkillsList();
        if (!empty($skills)) {
            $this->renderSectionHeading('KETERAMPILAN', $template);
            foreach ($skills as $skill) {
                $this->checkPageBreak(25);
                $cat = $skill['category'] ?? 'Keahlian';
                $items = $skill['items'] ?? '';
                $line = $cat . ': ' . $items;
                $this->renderParagraph($line, 9.5, false, '#333333');
            }
            $this->currentY -= 8;
        }

        // 6. Sertifikasi & Lisensi
        $certifications = $cv->getCertificationsList();
        if (!empty($certifications)) {
            $this->renderSectionHeading('SERTIFIKASI & LISENSI', $template);
            foreach ($certifications as $cert) {
                $this->checkPageBreak(35);
                $name = $cert['name'] ?? '-';
                $issuer = $cert['issuer'] ?? '';
                $date = $cert['issue_date'] ?? '';

                $this->renderTwoColumnHeader($name, $date, true);
                if (!empty($issuer)) {
                    $this->renderText($issuer, 9.5, 'italic', '#444444');
                    $this->currentY -= 13;
                }
                if (!empty($cert['credential_id'])) {
                    $this->renderText('ID Kredensial: ' . $cert['credential_id'], 9, 'regular', '#555555');
                    $this->currentY -= 12;
                }
                $this->currentY -= 6;
            }
        }

        return $this->buildPdfString();
    }

    protected function addPage(): void
    {
        $this->currentPage++;
        $this->pages[$this->currentPage] = '';
        $this->currentY = $this->pageHeight - $this->marginTop;
    }

    protected function checkPageBreak(float $requiredHeight): void
    {
        if ($this->currentY - $requiredHeight < $this->marginBottom) {
            $this->addPage();
        }
    }

    protected function renderClassicHeader(CurriculumVitae $cv): void
    {
        // ATS Classic Header: centered black uppercase
        $name = mb_strtoupper($cv->full_name);
        $this->renderCenteredText($name, 18, 'bold', '#111111');
        $this->currentY -= 20;

        if (!empty($cv->headline)) {
            $this->renderCenteredText($cv->headline, 11, 'italic', '#444444');
            $this->currentY -= 15;
        }

        $contacts = $this->buildContactsArray($cv);
        if (!empty($contacts)) {
            $contactLine = implode('  |  ', $contacts);
            $this->renderCenteredText($contactLine, 9, 'regular', '#333333');
            $this->currentY -= 14;
        }

        // Underline divider
        $this->drawHorizontalLine($this->marginLeft, $this->pageWidth - $this->marginRight, $this->currentY, 1.0, '#333333');
        $this->currentY -= 18;
    }

    protected function renderModernHeader(CurriculumVitae $cv): void
    {
        // Modern Header: Top colored band
        $bannerHeight = 70.0;
        $bannerY = $this->pageHeight - $bannerHeight;
        
        // Navy box
        $this->drawFilledRect(0, $bannerY, $this->pageWidth, $bannerHeight, '#073763');

        // White Name inside banner
        $nameY = $bannerY + 38.0;
        $this->drawTextAbsolute($cv->full_name, $this->marginLeft, $nameY, 18, 'bold', '#ffffff');

        if (!empty($cv->headline)) {
            $headlineY = $bannerY + 18.0;
            $this->drawTextAbsolute($cv->headline, $this->marginLeft, $headlineY, 11, 'regular', '#cfe1f0');
        }

        $this->currentY = $bannerY - 14.0;

        // Contacts row below banner
        $contacts = $this->buildContactsArray($cv);
        if (!empty($contacts)) {
            $contactLine = implode('  *  ', $contacts);
            $this->renderText($contactLine, 9, 'regular', '#40544c');
            $this->currentY -= 12;
        }

        $this->drawHorizontalLine($this->marginLeft, $this->pageWidth - $this->marginRight, $this->currentY, 0.8, '#dce4de');
        $this->currentY -= 16;
    }

    protected function buildContactsArray(CurriculumVitae $cv): array
    {
        $contacts = [];
        if (!empty($cv->phone)) $contacts[] = $cv->phone;
        if (!empty($cv->email)) $contacts[] = $cv->email;
        if (!empty($cv->address)) $contacts[] = $cv->address;
        if (!empty($cv->linkedin_url)) $contacts[] = 'LinkedIn: ' . preg_replace('#^https?://(www\.)?linkedin\.com/in/#', '', $cv->linkedin_url);
        if (!empty($cv->github_url)) $contacts[] = 'GitHub: ' . preg_replace('#^https?://(www\.)?github\.com/#', '', $cv->github_url);
        if (!empty($cv->portfolio_url)) $contacts[] = 'Portofolio: ' . preg_replace('#^https?://#', '', $cv->portfolio_url);
        return $contacts;
    }

    protected function renderSectionHeading(string $title, string $template): void
    {
        $this->checkPageBreak(38);
        $color = ($template === 'modern') ? '#073763' : '#111111';

        $this->renderText($title, 11, 'bold', $color);
        $this->currentY -= 5;
        
        $lineColor = ($template === 'modern') ? '#073763' : '#333333';
        $lineWidth = ($template === 'modern') ? 1.2 : 0.8;
        $this->drawHorizontalLine($this->marginLeft, $this->pageWidth - $this->marginRight, $this->currentY, $lineWidth, $lineColor);
        $this->currentY -= 12;
    }

    protected function renderTwoColumnHeader(string $leftText, string $rightText, bool $isBold = false): void
    {
        $font = $isBold ? 'bold' : 'regular';
        $this->renderText($leftText, 10.5, $font, '#111111');
        
        if (!empty($rightText)) {
            $rightWidth = strlen($rightText) * 5.2;
            $xRight = $this->pageWidth - $this->marginRight - $rightWidth;
            $this->drawTextAbsolute($rightText, $xRight, $this->currentY, 9.5, 'regular', '#555555');
        }

        $this->currentY -= 14;
    }

    protected function renderText(string $text, float $size = 10, string $style = 'regular', string $color = '#000000'): void
    {
        $this->drawTextAbsolute($text, $this->marginLeft, $this->currentY, $size, $style, $color);
    }

    protected function renderCenteredText(string $text, float $size = 10, string $style = 'regular', string $color = '#000000'): void
    {
        $approxWidth = strlen($text) * ($size * 0.52);
        $x = max($this->marginLeft, ($this->pageWidth - $approxWidth) / 2);
        $this->drawTextAbsolute($text, $x, $this->currentY, $size, $style, $color);
    }

    protected function renderParagraph(string $text, float $size = 9.5, bool $isBullet = false, string $color = '#333333'): void
    {
        $maxWidth = $this->pageWidth - $this->marginLeft - $this->marginRight;
        $maxCharsPerLine = (int) ($maxWidth / ($size * 0.52));
        $lines = $this->wordWrapLines($text, $maxCharsPerLine);

        foreach ($lines as $i => $line) {
            $this->checkPageBreak(15);
            $x = $this->marginLeft;
            $content = $line;
            if ($isBullet && $i === 0) {
                $content = '*  ' . $content;
            } elseif ($isBullet) {
                $x += 10;
            }
            $this->drawTextAbsolute($content, $x, $this->currentY, $size, 'regular', $color);
            $this->currentY -= ($size * 1.35);
        }
    }

    protected function wordWrapLines(string $text, int $maxChars): array
    {
        $words = preg_split('/\s+/', trim($text));
        $lines = [];
        $currentLine = '';

        foreach ($words as $word) {
            if ($word === '') continue;

            if ($currentLine === '') {
                $currentLine = $word;
            } elseif (strlen($currentLine . ' ' . $word) <= $maxChars) {
                $currentLine .= ' ' . $word;
            } else {
                $lines[] = $currentLine;
                $currentLine = $word;
            }
        }

        if ($currentLine !== '') {
            $lines[] = $currentLine;
        }

        return !empty($lines) ? $lines : [''];
    }

    protected function drawTextAbsolute(string $text, float $x, float $y, float $size, string $style, string $hexColor): void
    {
        $fontCode = match ($style) {
            'bold' => '/F2',
            'italic' => '/F3',
            default => '/F1',
        };

        [$r, $g, $b] = $this->hexToRgb($hexColor);
        $escaped = $this->escapePdfString($text);

        $stream = sprintf(
            "BT\n%s %.2f Tf\n%.3f %.3f %.3f rg\n1 0 0 1 %.2f %.2f Tm\n(%s) Tj\nET\n",
            $fontCode,
            $size,
            $r,
            $g,
            $b,
            $x,
            $y,
            $escaped
        );

        $this->pages[$this->currentPage] .= $stream;
    }

    protected function drawHorizontalLine(float $x1, float $x2, float $y, float $width, string $hexColor): void
    {
        [$r, $g, $b] = $this->hexToRgb($hexColor);
        $stream = sprintf(
            "%.3f %.3f %.3f RG\n%.2f w\n%.2f %.2f m\n%.2f %.2f l\nS\n",
            $r,
            $g,
            $b,
            $width,
            $x1,
            $y,
            $x2,
            $y
        );
        $this->pages[$this->currentPage] .= $stream;
    }

    protected function drawFilledRect(float $x, float $y, float $w, float $h, string $hexColor): void
    {
        [$r, $g, $b] = $this->hexToRgb($hexColor);
        $stream = sprintf(
            "%.3f %.3f %.3f rg\n%.2f %.2f %.2f %.2f re\nf\n",
            $r,
            $g,
            $b,
            $x,
            $y,
            $w,
            $h
        );
        $this->pages[$this->currentPage] .= $stream;
    }

    protected function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;
        return [$r, $g, $b];
    }

    protected function escapePdfString(string $str): string
    {
        // Transliterate non-ASCII characters to standard Latin equivalents
        $str = iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $str) ?: $str;
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $str);
    }

    protected function buildPdfString(): string
    {
        $numPages = count($this->pages);
        $pageObjIds = [];
        $contentObjIds = [];

        // Fonts
        // obj 1: Catalog
        // obj 2: Pages
        // obj 3: /F1 (Helvetica)
        // obj 4: /F2 (Helvetica-Bold)
        // obj 5: /F3 (Helvetica-Oblique)
        // obj 6..(5 + numPages): Page objects
        // obj (6 + numPages)..: Content stream objects

        $nextObjId = 6;
        for ($i = 0; $i < $numPages; $i++) {
            $pageObjIds[$i] = $nextObjId++;
        }
        for ($i = 0; $i < $numPages; $i++) {
            $contentObjIds[$i] = $nextObjId++;
        }

        $objects = [];

        // 1. Catalog
        $objects[1] = "<<\n  /Type /Catalog\n  /Pages 2 0 R\n>>";

        // 2. Pages
        $kidsStr = implode(' ', array_map(fn($id) => "$id 0 R", $pageObjIds));
        $objects[2] = sprintf("<<\n  /Type /Pages\n  /Kids [%s]\n  /Count %d\n>>", $kidsStr, $numPages);

        // 3. Fonts
        $objects[3] = "<<\n  /Type /Font\n  /Subtype /Type1\n  /BaseFont /Helvetica\n  /Encoding /WinAnsiEncoding\n>>";
        $objects[4] = "<<\n  /Type /Font\n  /Subtype /Type1\n  /BaseFont /Helvetica-Bold\n  /Encoding /WinAnsiEncoding\n>>";
        $objects[5] = "<<\n  /Type /Font\n  /Subtype /Type1\n  /BaseFont /Helvetica-Oblique\n  /Encoding /WinAnsiEncoding\n>>";

        // Page objects
        for ($i = 0; $i < $numPages; $i++) {
            $pageId = $pageObjIds[$i];
            $contentId = $contentObjIds[$i];
            $objects[$pageId] = sprintf(
                "<<\n  /Type /Page\n  /Parent 2 0 R\n  /MediaBox [0 0 %.2f %.2f]\n  /Contents %d 0 R\n  /Resources <<\n    /Font <<\n      /F1 3 0 R\n      /F2 4 0 R\n      /F3 5 0 R\n    >>\n  >>\n>>",
                $this->pageWidth,
                $this->pageHeight,
                $contentId
            );
        }

        // Content streams
        for ($i = 0; $i < $numPages; $i++) {
            $contentId = $contentObjIds[$i];
            $stream = $this->pages[$i];
            $len = strlen($stream);
            $objects[$contentId] = sprintf("<< /Length %d >>\nstream\n%sendstream", $len, $stream);
        }

        // Build entire file
        $out = "%PDF-1.4\n%\xe2\xe3\xcf\xd3\n";
        $xrefOffsets = [];

        for ($id = 1; $id < $nextObjId; $id++) {
            $xrefOffsets[$id] = strlen($out);
            $out .= sprintf("%d 0 obj\n%s\nendobj\n", $id, $objects[$id]);
        }

        $startXref = strlen($out);
        $out .= sprintf("xref\n0 %d\n0000000000 65535 f \n", $nextObjId);
        for ($id = 1; $id < $nextObjId; $id++) {
            $out .= sprintf("%010d 00000 n \n", $xrefOffsets[$id]);
        }

        $out .= sprintf("trailer\n<<\n  /Size %d\n  /Root 1 0 R\n>>\nstartxref\n%d\n%%%%EOF\n", $nextObjId, $startXref);

        return $out;
    }
}

