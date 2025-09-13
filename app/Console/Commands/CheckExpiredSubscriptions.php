<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckExpiredSubscriptions extends Command
{
    protected $signature = 'subscriptions:check-expired {--reactivate : Également réactiver les abonnements valides}';
    protected $description = 'Vérifie et suspend les comptes avec abonnements expirés, et optionnellement réactive les comptes avec abonnements valides';

    public function handle()
    {
        $this->info('Vérification des abonnements...');
        
        $today = Carbon::now()->format('Y-m-d');
        $this->info("Date actuelle: {$today}");

        // 1. Suspendre les abonnements expirés
        $totalSuspended = $this->checkExpiredSubscriptions();

        // 2. Réactiver les abonnements valides (si option activée ou par défaut)
        $totalReactivated = $this->checkValidSubscriptions();

        $this->info("✅ Terminé! {$totalSuspended} utilisateurs suspendus, {$totalReactivated} utilisateurs réactivés.");
    }

    private function checkExpiredSubscriptions()
    {
        $this->info("\n=== VÉRIFICATION DES ABONNEMENTS EXPIRÉS ===");
        
        $today = Carbon::today();
        
        // Récupérer tous les abonnements expirés
        $abonnementsExpires = DB::table('abonnements')
            ->whereDate('date_fin', '<', $today)
            ->where('statut', 'actif') // Seulement ceux encore marqués actifs
            ->get();

        $this->info("Abonnements expirés trouvés: " . $abonnementsExpires->count());

        $totalSuspended = 0;

        foreach ($abonnementsExpires as $abonnement) {
            $this->info("Traitement abonnement expiré ID: {$abonnement->id}");
            
            $suspended = $this->suspendUsersForSubscription($abonnement->promoteur_id, $abonnement->id);
            $totalSuspended += $suspended;

            // Marquer l'abonnement comme expiré
            DB::table('abonnements')
                ->where('id', $abonnement->id)
                ->update(['statut' => 'expire']);

            Log::info("Abonnement {$abonnement->id} expiré - {$suspended} utilisateurs suspendus");
        }

        return $totalSuspended;
    }

    private function checkValidSubscriptions()
    {
        $this->info("\n=== VÉRIFICATION DES ABONNEMENTS VALIDES ===");
        
        $today = Carbon::today();
        
        // Récupérer tous les abonnements actifs et valides
        $abonnementsValides = DB::table('abonnements')
            ->where('statut', 'actif')
            ->whereDate('date_debut', '<=', $today)
            ->whereDate('date_fin', '>=', $today)
            ->get();

        $this->info("Abonnements valides trouvés: " . $abonnementsValides->count());

        $totalReactivated = 0;

        foreach ($abonnementsValides as $abonnement) {
            $this->info("Vérification abonnement valide ID: {$abonnement->id}");
            
            $reactivated = $this->reactivateUsersForSubscription($abonnement->promoteur_id, $abonnement->id);
            $totalReactivated += $reactivated;

            if ($reactivated > 0) {
                Log::info("Abonnement {$abonnement->id} valide - {$reactivated} utilisateurs réactivés");
            }
        }

        return $totalReactivated;
    }

    private function suspendUsersForSubscription($promoteurId, $abonnementId)
    {
        $suspended = 0;
        $this->info("  Suspension pour promoteur ID: {$promoteurId}");

        // 1. Suspendre le promoteur
        $promoteurCount = DB::table('promoteurs')
            ->where('id', $promoteurId)
            ->where('is_suspended', 0)
            ->update(['is_suspended' => 1]);
        
        if ($promoteurCount > 0) {
            $this->info("  ✓ Promoteur suspendu: {$promoteurCount}");
            $suspended += $promoteurCount;
        }

        // 2-6. Suspendre tous les utilisateurs des immeubles
        $suspended += $this->updateUsersForSubscription($abonnementId, 1, 'Suspension');

        return $suspended;
    }

    private function reactivateUsersForSubscription($promoteurId, $abonnementId)
    {
        $reactivated = 0;

        // 1. Réactiver le promoteur
        $promoteurCount = DB::table('promoteurs')
            ->where('id', $promoteurId)
            ->where('is_suspended', 1)
            ->update(['is_suspended' => 0]);
        
        if ($promoteurCount > 0) {
            $this->info("  ✓ Promoteur réactivé: {$promoteurCount}");
            $reactivated += $promoteurCount;
        }

        // 2-6. Réactiver tous les utilisateurs des immeubles
        $reactivated += $this->updateUsersForSubscription($abonnementId, 0, 'Réactivation');

        return $reactivated;
    }

    private function updateUsersForSubscription($abonnementId, $suspendValue, $action)
    {
        $updated = 0;
        $oppositeValue = $suspendValue === 1 ? 0 : 1;

        // Trouver tous les immeubles liés à cet abonnement
        $immeubles = DB::table('immeubles')
            ->where('abonnement_id', $abonnementId)
            ->get();

        if ($immeubles->isEmpty()) {
            $this->info("  ⚠ Aucun immeuble trouvé pour cet abonnement");
            return 0;
        }

        foreach ($immeubles as $immeuble) {
            $this->info("  Traitement immeuble ID: {$immeuble->id}");

            // Syndic
            if (isset($immeuble->syndicat_id) && $immeuble->syndicat_id) {
                $syndicCount = DB::table('syndicats')
                    ->where('id', $immeuble->syndicat_id)
                    ->where('is_suspended', $oppositeValue)
                    ->update(['is_suspended' => $suspendValue]);
                
                if ($syndicCount > 0) {
                    $this->info("    ✓ Syndic {$action}: {$syndicCount}");
                    $updated += $syndicCount;
                }
            }

            // Propriétaires
            $proprietaireCount = DB::table('proprietaires as p')
                ->join('appartements as a', 'p.appartement_id', '=', 'a.id')
                ->join('blocs as b', 'a.bloc_id', '=', 'b.id')
                ->where('b.immeuble_id', $immeuble->id)
                ->where('p.is_suspended', $oppositeValue)
                ->update(['p.is_suspended' => $suspendValue]);
            
            if ($proprietaireCount > 0) {
                $this->info("    ✓ Propriétaires {$action}: {$proprietaireCount}");
                $updated += $proprietaireCount;
            }

            // Locataires
            $locataireCount = DB::table('locataires as l')
                ->join('appartements as a', 'l.appartement_id', '=', 'a.id')
                ->join('blocs as b', 'a.bloc_id', '=', 'b.id')
                ->where('b.immeuble_id', $immeuble->id)
                ->where('l.is_suspended', $oppositeValue)
                ->update(['l.is_suspended' => $suspendValue]);
            
            if ($locataireCount > 0) {
                $this->info("    ✓ Locataires {$action}: {$locataireCount}");
                $updated += $locataireCount;
            }

            // Techniciens
            $technicienCount = DB::table('techniciens')
                ->where('immeuble_id', $immeuble->id)
                ->where('is_suspended', $oppositeValue)
                ->update(['is_suspended' => $suspendValue]);
            
            if ($technicienCount > 0) {
                $this->info("    ✓ Techniciens {$action}: {$technicienCount}");
                $updated += $technicienCount;
            }
        }

        return $updated;
    }
}