<?php

namespace App\Services;

use App\Models\Resume;
use App\Models\Skill;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;

class ResumeParserService
{
    /**
     * Parse text and extract details from a PDF resume.
     */
    public function parseResume(Resume $resume): array
    {
        $filePath = Storage::disk('local')->path($resume->file_path);

        $text = '';
        if (file_exists($filePath)) {
            $content = @file_get_contents($filePath) ?: '';

            // Fast PDF text stream extraction (< 2ms)
            if (preg_match_all('/(?:\(|\<)[^\)\>]{3,}(?:\)|\>)/', $content, $matches)) {
                $text = implode(' ', $matches[0]);
            }

            // Fallback to Smalot parser if stream extraction yielded insufficient text
            if (mb_strlen($text) < 50) {
                try {
                    $parser = new Parser;
                    $pdf = $parser->parseFile($filePath);
                    $extractedText = $pdf->getText();
                    if (! empty($extractedText)) {
                        $text = $extractedText;
                    }
                } catch (\Throwable $e) {
                    $text = $content;
                }
            }
        }

        $extractedData = $this->extractDataFromText($text);

        // Update resume with parsed details
        $resume->update([
            'parsed_text' => mb_substr($text, 0, 50000),
            'parsed_data' => $extractedData,
            'status' => 'completed',
        ]);

        // If candidate details are missing or empty, populate from parsed data
        $candidate = $resume->candidate;
        if ($candidate) {
            $updates = [];
            if (empty($candidate->phone) && ! empty($extractedData['phone'])) {
                $updates['phone'] = $extractedData['phone'];
            }
            if ($candidate->experience_years == 0 && ! empty($extractedData['experience_years'])) {
                $updates['experience_years'] = $extractedData['experience_years'];
            }
            if (empty($candidate->education) && ! empty($extractedData['education'])) {
                $updates['education'] = $extractedData['education'];
            }
            if (! empty($extractedData['skills'])) {
                $updates['skills_summary'] = implode(', ', $extractedData['skills']);
            }
            if (! empty($updates)) {
                $candidate->update($updates);
            }
        }

        return $extractedData;
    }

    /**
     * Simple regex and keyword extractor for resume text.
     */
    public function extractDataFromText(string $text): array
    {
        // 1. Email extraction
        $email = null;
        if (preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/i', $text, $matches)) {
            $email = $matches[0];
        }

        // 2. Phone extraction
        $phone = null;
        if (preg_match('/(?:\+?\d{1,3}[-.\s]?)?\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}/', $text, $matches)) {
            $phone = trim($matches[0]);
        }

        // 3. Experience years extraction
        $experienceYears = 0;
        if (preg_match('/(\d+(?:\.\d+)?)\+?\s*(?:years|yrs)\s*(?:of)?\s*(?:experience|exp)?/i', $text, $matches)) {
            $experienceYears = (float) $matches[1];
        }

        // 4. Education extraction
        $education = null;
        if (preg_match('/(ph\.?d|master(?:[\'’]s)?(?:\s+degree)?|bachelor(?:[\'’]s)?(?:\s+degree)?|b\.?tech|m\.?tech|b\.?sc|m\.?sc|diploma|associate(?:\s+degree)?)/i', $text, $matches)) {
            $education = ucwords(strtolower($matches[0]));
        }

        // 5. Skills extraction from skills database
        $foundSkills = [];
        $skills = Skill::pluck('name')->toArray();
        foreach ($skills as $skill) {
            // Case-insensitive word boundary match
            $escaped = preg_quote($skill, '/');
            if (preg_match('/\b'.$escaped.'\b/i', $text)) {
                $foundSkills[] = $skill;
            }
        }

        return [
            'email' => $email,
            'phone' => $phone,
            'experience_years' => $experienceYears,
            'education' => $education,
            'skills' => array_values(array_unique($foundSkills)),
        ];
    }
}
