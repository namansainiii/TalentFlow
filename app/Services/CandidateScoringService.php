<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Job;
use App\Models\Candidate;

class CandidateScoringService
{
    /**
     * Calculate score for an application based on skills, experience, education, and bonus skills.
     */
    public function calculateScore(Application $application): float
    {
        $job = $application->job()->with(['skills'])->first();
        $candidate = $application->candidate;

        if (!$job || !$candidate) {
            return 0.0;
        }

        // Candidate skills list (lowercase for comparison)
        $candidateSkills = $this->getCandidateSkills($candidate, $application);

        // 1. Mandatory Skills Score (Max 50 points)
        $mandatorySkills = $job->skills->where('pivot.is_mandatory', true)->pluck('name')->toArray();
        $mandatoryScore = 50.0;
        if (count($mandatorySkills) > 0) {
            $matchedMandatory = 0;
            foreach ($mandatorySkills as $skill) {
                if (in_array(strtolower(trim($skill)), $candidateSkills)) {
                    $matchedMandatory++;
                }
            }
            $mandatoryScore = ($matchedMandatory / count($mandatorySkills)) * 50.0;
        }

        // 2. Bonus Skills Score (Max 10 points)
        $bonusSkills = $job->skills->where('pivot.is_mandatory', false)->pluck('name')->toArray();
        $bonusScore = 0.0;
        if (count($bonusSkills) > 0) {
            $matchedBonus = 0;
            foreach ($bonusSkills as $skill) {
                if (in_array(strtolower(trim($skill)), $candidateSkills)) {
                    $matchedBonus++;
                }
            }
            $bonusScore = ($matchedBonus / count($bonusSkills)) * 10.0;
        } elseif (count($candidateSkills) > count($mandatorySkills)) {
            // Optional bonus points if candidate has extra skills
            $bonusScore = min(10.0, (count($candidateSkills) - count($mandatorySkills)) * 2.5);
        }

        // 3. Experience Score (Max 25 points)
        $requiredYears = $this->parseRequiredExperienceYears($job->experience);
        $candidateYears = (float) $candidate->experience_years;
        if ($requiredYears <= 0) {
            $experienceScore = 25.0;
        } else {
            $ratio = $candidateYears / $requiredYears;
            $experienceScore = min(25.0, $ratio * 25.0);
        }

        // 4. Education Score (Max 15 points)
        $educationScore = $this->calculateEducationScore($candidate->education);

        $totalScore = round($mandatoryScore + $bonusScore + $experienceScore + $educationScore, 2);
        $totalScore = min(100.0, max(0.0, $totalScore));

        // Update application
        $application->update([
            'skill_score' => $totalScore,
        ]);

        return $totalScore;
    }

    /**
     * Get unique candidate skills as lowercased array.
     */
    protected function getCandidateSkills(Candidate $candidate, Application $application): array
    {
        $skills = [];

        if (!empty($candidate->skills_summary)) {
            $parts = explode(',', $candidate->skills_summary);
            foreach ($parts as $part) {
                $trimmed = strtolower(trim($part));
                if ($trimmed !== '') {
                    $skills[] = $trimmed;
                }
            }
        }

        // Also check attached resume parsed data if available
        $resume = $application->resume ?: $candidate->latestResume;
        if ($resume && !empty($resume->parsed_data['skills'])) {
            foreach ($resume->parsed_data['skills'] as $skillName) {
                $trimmed = strtolower(trim($skillName));
                if ($trimmed !== '') {
                    $skills[] = $trimmed;
                }
            }
        }

        return array_values(array_unique($skills));
    }

    /**
     * Parse minimum required experience years from job experience string.
     */
    protected function parseRequiredExperienceYears(string $expString): float
    {
        if (preg_match('/(\d+(?:\.\d+)?)/', $expString, $matches)) {
            return (float) $matches[1];
        }
        return 0.0;
    }

    /**
     * Calculate score based on education level.
     */
    protected function calculateEducationScore(?string $education): float
    {
        if (!$education) {
            return 5.0;
        }

        $edu = strtolower($education);
        if (str_contains($edu, 'phd') || str_contains($edu, 'doctorate')) {
            return 15.0;
        }
        if (str_contains($edu, 'master') || str_contains($edu, 'm.tech') || str_contains($edu, 'm.sc')) {
            return 14.0;
        }
        if (str_contains($edu, 'bachelor') || str_contains($edu, 'b.tech') || str_contains($edu, 'b.sc') || str_contains($edu, 'degree')) {
            return 12.0;
        }
        if (str_contains($edu, 'diploma') || str_contains($edu, 'associate')) {
            return 9.0;
        }

        return 7.0;
    }
}
