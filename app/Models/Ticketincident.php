<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketIncident extends Model
{
    protected $table = 'ticketincidents';

    protected $fillable = [
        'numero_ticket',
        'titre',
        'description',
        'immeuble_id',
        'appartement_id',
        'bloc_id',
        'created_by',
        'assignee_id',
        'type_incident',
        'priorite',
        'statut',
        'date_incident',
        'date_resolution',
        'cout_estime',
        'cout_reel',
        'photos',
        'notes_resolution',
        'satisfaction_client'
    ];

    protected $casts = [
        'date_incident' => 'date',
        'date_resolution' => 'date',
        'cout_estime' => 'decimal:2',
        'cout_reel' => 'decimal:2',
        'photos' => 'array',
        'satisfaction_client' => 'integer'
    ];

    // Générer automatiquement le numéro de ticket
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (!$ticket->numero_ticket) {
                $ticket->numero_ticket = 'TIC-' . date('Y') . '-' . str_pad(
                    static::whereYear('created_at', date('Y'))->count() + 1,
                    4,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }

    // Relations
    public function appartement()
    {
        return $this->belongsTo(Appartement::class);
    }

    public function bloc()
    {
        return $this->belongsTo(Bloc::class);
    }

    public function immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    // Relation avec technicien via assignee_id
    public function technicien()
    {
        return $this->belongsTo(Technicien::class, 'assignee_id', 'user_id');
    }

    // Accessors pour l'affichage
    public function getPrioriteColorAttribute()
    {
        return match ($this->priorite) {
            'faible' => 'success',
            'normale' => 'warning',
            'haute' => 'danger',
            'urgente' => 'dark',
            default => 'secondary'
        };
    }

    public function getStatutColorAttribute()
    {
        return match ($this->statut) {
            'ouvert' => 'danger',
            'en_cours' => 'warning',
            'resolu' => 'success',
            'ferme' => 'secondary',
            default => 'secondary'
        };
    }

    public function getStatutDisplayAttribute()
    {
        return match ($this->statut) {
            'ouvert' => 'Ouvert',
            'en_cours' => 'En cours',
            'resolu' => 'Résolu',
            'ferme' => 'Fermé',
            default => 'Inconnu'
        };
    }

    // Scopes
    public function scopeOuverts($query)
    {
        return $query->where('statut', 'ouvert');
    }

    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopeResolus($query)
    {
        return $query->where('statut', 'resolu');
    }

    public function scopeParImmeuble($query, $immeubleId)
    {
        return $query->where('immeuble_id', $immeubleId);
    }

    public function scopeParPriorite($query, $priorite)
    {
        return $query->where('priorite', $priorite);
    }
}