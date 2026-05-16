<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id', 'reviewer_id',
        'score_character', 'score_capacity', 'score_capital',
        'score_collateral', 'score_condition',
        'score_people', 'score_planet', 'score_profit',
        'notes_5c', 'notes_3p', 'decision', 'decision_notes',
    ];

    // ── Computed Scores ──────────────────────────────────────────

    public function getTotal5cScoreAttribute(): int
    {
        return $this->score_character + $this->score_capacity +
               $this->score_capital + $this->score_collateral + $this->score_condition;
    }

    public function getTotal3pScoreAttribute(): int
    {
        return $this->score_people + $this->score_planet + $this->score_profit;
    }

    public function getTotalScoreAttribute(): int
    {
        return $this->total_5c_score + $this->total_3p_score;
    }

    public function getMaxScoreAttribute(): int
    {
        return 40; // 5C (5x5=25) + 3P (3x5=15) = 40
    }

    public function getScorePercentageAttribute(): float
    {
        return round(($this->total_score / $this->max_score) * 100, 1);
    }

    // ── Relationships ────────────────────────────────────────────

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
