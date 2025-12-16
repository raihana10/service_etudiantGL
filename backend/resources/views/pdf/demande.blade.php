<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document - Demande #{{ $demande->idDemande }}</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, DejaVu Sans Condensed, Arial, Helvetica, sans-serif; background: #fff; padding: 24px; color: #111; }
    .document { max-width: 800px; margin: 0 auto; padding: 36px; border: 2px solid #333; background: #fff; }
    
    .header { text-align: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 2px solid #0056a6; }
    .logo-container { display: flex; align-items: center; justify-content: center; gap: 25px; margin-bottom: 15px; }
    .logo-img { height: 90px; width: auto; max-width: 120px; }
    .university-info { text-align: center; }
    .university-name { font-size: 18px; font-weight: bold; color: #0056a6; margin-bottom: 5px; line-height: 1.2; }
    .university-subtitle { font-size: 14px; color: #333; margin-bottom: 8px; font-weight: 600; }
    .university-address { font-size: 11px; color: #555; }

    .doc-title { text-align: center; font-size: 22px; font-weight: bold; margin: 24px 0; text-decoration: underline; color: #333; }
    .content { line-height: 1.6; font-size: 13px; color: #333; }
    .content p { margin-bottom: 12px; }
    .field { display: inline-block; border-bottom: 1px solid #333; min-width: 160px; padding: 0 4px; }

    .signature-section { margin-top: 40px; display: table; width: 100%; }
    .signature-block { display: table-cell; text-align: center; vertical-align: top; width: 33.33%; }

    .footer { margin-top: 30px; padding-top: 12px; border-top: 1px solid #ddd; text-align: center; font-size: 10px; color: #666; }

    .table { width: 100%; border-collapse: collapse; margin: 16px 0; font-size: 12px; }
    .table th, .table td { border: 1px solid #333; padding: 8px; text-align: left; }
    .table th { background: #0056a6; color: #fff; font-weight: bold; }
    .table-total { background: #f0f8ff; font-weight: bold; }
  </style>
</head>
<body>
  <div class="document">
    <div class="header">
      <div class="logo-container">
    
        
        <div class="university-info">
          <div class="university-name">ÉCOLE NATIONALE DES SCIENCES APPLIQUÉES DE TÉTOUAN</div>
          <div class="university-subtitle">Université Abdelmalek Essaâdi</div>
          <div class="university-address">B.P. 2222 - M'Hannech II - 93030 Tétouan - Maroc</div>
        </div>
      </div>
    </div>

    @php
      $type = $demande->typeDoc;
      $titleMap = [
        'AttestationScolarite' => 'ATTESTATION DE SCOLARITÉ',
        'AttestationReussite'  => 'ATTESTATION DE RÉUSSITE',
        'ReleveNote'           => 'RELEVÉ DE NOTES',
        'ConventionStage'      => 'CONVENTION DE STAGE',
      ];
      $title = $titleMap[$type] ?? 'DOCUMENT';

      $nom = optional($etudiant)->nom ?? 'N/A';
      $prenom = optional($etudiant)->prenom ?? 'N/A';
      $apogee = optional($etudiant)->numApogee ?? 'N/A';
      $cin = optional($etudiant)->CIN ?? 'N/A';
      $dateNaissance = optional($etudiant)->dateNaissance ? optional($etudiant)->dateNaissance->format('d/m/Y') : 'N/A';
      $lieuNaissance = optional($etudiant)->lieuNaissance ?? 'N/A';
      
      // Calcul de la moyenne générale pour le relevé de notes
      $moyenneGenerale = 0;
      $totalNotes = 0;
      $countNotes = 0;
      if (!empty($notes)) {
        foreach ($notes as $n) {
          if (!is_null($n['note'])) {
            $totalNotes += $n['note'];
            $countNotes++;
          }
        }
        if ($countNotes > 0) {
          $moyenneGenerale = $totalNotes / $countNotes;
        }
      }
    @endphp

    <div class="doc-title">{{ $title }}</div>

    <div class="content">
      @if ($type === 'AttestationScolarite')
        @php
          $annee = date('Y') . '-' . (date('Y')+1);
          $filiere = optional(optional($etudiant)->filiere)->nomF ?? 'N/A';
          $niveau = optional($etudiant)->niveau ?? 'N/A';
          $nbr = optional($scolarite)->nbrExemplaire ?? 1;
        @endphp
        <p><strong>Année universitaire :</strong> <span class="field">{{ $annee }}</span></p>
        <p><strong>Filière :</strong> <span class="field">{{ $filiere }}</span></p>
        <p><strong>Niveau :</strong> <span class="field">{{ $niveau }}</span></p>
        

        <p style="margin-top: 18px;">L'<strong>École Nationale des Sciences Appliquées de Tétouan</strong> certifie que :</p>

        <p style="margin-left: 18px; margin-top: 12px;">
          <strong>Nom :</strong> <span class="field">{{ $nom }}</span><br>
          <strong>Prénom :</strong> <span class="field">{{ $prenom }}</span><br>
          <strong>Date de naissance :</strong> <span class="field">{{ $dateNaissance }}</span><br>
          <strong>Lieu de naissance :</strong> <span class="field">{{ $lieuNaissance }}</span><br>
          <strong>N° Apogée :</strong> <span class="field">{{ $apogee }}</span><br>
          <strong>CIN :</strong> <span class="field">{{ $cin }}</span>
        </p>

        <p style="margin-top: 12px;">
          Est régulièrement inscrit(e) dans notre établissement pour l'année universitaire 
          <strong>{{ $annee }}</strong> en <span class="field">{{ $filiere }}</span> (niveau {{ $niveau }}).
        </p>

        <p style="margin-top: 18px;">
          La présente attestation est délivrée à l'intéressé(e) pour servir et valoir ce que de droit.
        </p>

      @elseif ($type === 'AttestationReussite')
        @php
          $annee = optional($reussite)->anneeObtention ?? (date('Y')-1);
          $diplome = optional($reussite)->diplomeConcernee ?? 'N/A';
        @endphp
        <p><strong>Année d'obtention :</strong> <span class="field">{{ $annee }}</span></p>

        <p style="margin-top: 18px;">L'<strong>École Nationale des Sciences Appliquées de Tétouan</strong> atteste que :</p>

        <p style="margin-left: 18px; margin-top: 12px;">
          <strong>Nom :</strong> <span class="field">{{ $nom }}</span><br>
          <strong>Prénom :</strong> <span class="field">{{ $prenom }}</span><br>
          <strong>Date de naissance :</strong> <span class="field">{{ $dateNaissance }}</span><br>
          <strong>Lieu de naissance :</strong> <span class="field">{{ $lieuNaissance }}</span><br>
          <strong>N° Apogée :</strong> <span class="field">{{ $apogee }}</span><br>
          <strong>CIN :</strong> <span class="field">{{ $cin }}</span>
        </p>

        <p style="margin-top: 12px;">
          A obtenu avec succès son diplôme de <strong><span class="field">{{ $diplome }}</span></strong> 
          au titre de l'année universitaire <span class="field">{{ $annee }}</span>.
        </p>

        <p style="margin-top: 12px;"><strong>Note générale :</strong> <span class="field">{{ number_format($moyenneGenerale, 2, ',', ' ') }} / 20</span></p>

        <p style="margin-top: 18px;">
          La présente attestation est délivrée à l'intéressé(e) pour servir et valoir ce que de droit, 
          en attendant la délivrance du diplôme définitif.
        </p>

      @elseif ($type === 'ConventionStage')
        @php
          $entreprise = optional($convention)->raisonSocialeEntreprise ?? 'N/A';
          $ville = optional($convention)->villeEntreprise ?? 'N/A';
          $secteur = optional($convention)->secteurEntreprise ?? 'N/A';
          $adresse = optional($convention)->adresseEntreprise ?? 'N/A';
          $typeStage = optional($convention)->typeStage ?? 'N/A';
          $rep = optional($convention)->representantEntreprise ?? 'N/A';
          $fctRep = optional($convention)->fctRepresentant ?? 'N/A';
          $encAcad = optional($convention)->encadrantAcademique ?? 'N/A';
          $encEnt = optional($convention)->encadrantEntreprise ?? 'N/A';
          $fctEnc = optional($convention)->fctEncadrant ?? 'N/A';
          $telEnc = optional($convention)->TLEncadrant ?? 'N/A';
          $emailEnc = optional($convention)->emailEncadrant ?? 'N/A';
          $emailEnt = optional($convention)->emailEntreprise ?? 'N/A';
          $telEnt = optional($convention)->TLEntreprise ?? 'N/A';
          $sujet = optional($convention)->sujetStage ?? 'N/A';
          $dateDebut = optional($convention)->dateDebut ? optional($convention)->dateDebut->format('d/m/Y') : 'N/A';
          $dateFin = optional($convention)->dateFin ? optional($convention)->dateFin->format('d/m/Y') : 'N/A';
        @endphp
        <p><strong>Entre les soussignés :</strong></p>

        <p style="margin-top: 12px;"><strong>L'ÉCOLE NATIONALE DES SCIENCES APPLIQUÉES DE TÉTOUAN :</strong></p>
        <p style="margin-left: 18px;">
          B.P. 2222 - M'Hannech II - 93030 Tétouan
        </p>

        <p style="margin-top: 12px;"><strong>ET L'ENTREPRISE :</strong></p>
        <p style="margin-left: 18px;">
          <strong>Raison sociale :</strong> <span class="field">{{ $entreprise }}</span><br>
          <strong>Secteur d'activité :</strong> <span class="field">{{ $secteur }}</span><br>
          <strong>Adresse :</strong> <span class="field">{{ $adresse }}</span><br>
          <strong>Ville :</strong> <span class="field">{{ $ville }}</span><br>
          <strong>Téléphone :</strong> <span class="field">{{ $telEnt }}</span><br>
          <strong>Email :</strong> <span class="field">{{ $emailEnt }}</span>
        </p>

        <p style="margin-top: 12px;"><strong>CONCERNANT LE STAGIAIRE :</strong></p>
        <p style="margin-left: 18px;">
          <strong>Nom et Prénom :</strong> <span class="field">{{ $nom }} {{ $prenom }}</span><br>
          <strong>N° Apogée :</strong> <span class="field">{{ $apogee }}</span><br>
          <strong>Filière :</strong> <span class="field">{{ optional(optional($etudiant)->filiere)->nomF ?? 'N/A' }}</span><br>
          <strong>Niveau :</strong> <span class="field">{{ optional($etudiant)->niveau ?? 'N/A' }}</span>
        </p>

        <p style="margin-top: 12px;"><strong>OBJET DU STAGE :</strong></p>
        <p style="margin-left: 18px;">
          <strong>Type de stage :</strong> <span class="field">{{ $typeStage }}</span><br>
          <strong>Sujet :</strong> <span class="field">{{ $sujet }}</span><br>
          <strong>Période :</strong> Du <span class="field">{{ $dateDebut }}</span> au <span class="field">{{ $dateFin }}</span>
        </p>
        <p style="margin-left: 18px;">
          <strong>Encadrant académique :</strong> <span class="field">{{ $encAcad }}</span><br>
          <strong>Encadrant entreprise :</strong> <span class="field">{{ $encEnt }}</span> (<span class="field">{{ $fctEnc }}</span>)<br>
          <strong>Contact encadrant :</strong> <span class="field">{{ $telEnc }}</span> — <span class="field">{{ $emailEnc }}</span>
        </p>

        <p style="margin-top: 18px;">
          <strong>Article 1 :</strong> Le stagiaire s'engage à respecter le règlement intérieur de l'entreprise 
          et à se conformer aux directives qui lui seront données.
        </p>

        <p style="margin-top: 10px;">
          <strong>Article 2 :</strong> L'entreprise s'engage à encadrer le stagiaire et à lui fournir 
          les moyens nécessaires à l'accomplissement de sa mission.
        </p>

        <div class="signature-section">
          <div class="signature-block">
            <p><strong>Pour l'École</strong></p>
            <p><strong>ENSA de Tétouan</strong></p>
            <p style="margin-top: 60px;">Signature et Cachet</p>
          </div>
          <div class="signature-block">
            <p><strong>Pour l'Entreprise</strong></p>
            <p><strong>{{ $entreprise }}</strong></p>
            <p style="margin-top: 60px;">Signature et Cachet</p>
          </div>
          <div class="signature-block">
            <p><strong>Le Stagiaire</strong></p>
            <p style="margin-top: 60px;">Signature</p>
          </div>
        </div>

      @elseif ($type === 'ReleveNote')
        @php
          $annee = optional($releve)->annee ?? 'N/A';
          $semestre = optional($releve)->semestre ?? 'N/A';
          $filiere = optional(optional($etudiant)->filiere)->nomF ?? 'N/A';
          $niveau = optional($etudiant)->niveau ?? 'N/A';
        @endphp
        <p><strong>Année universitaire :</strong> <span class="field">{{ $annee }}</span></p>
        <p><strong>Semestre :</strong> <span class="field">{{ $semestre }}</span></p>
        <p><strong>Filière :</strong> <span class="field">{{ $filiere }}</span></p>
        <p><strong>Niveau :</strong> <span class="field">{{ $niveau }}</span></p>

        <p style="margin-top: 12px;">
          <strong>Nom :</strong> <span class="field">{{ $nom }}</span><br>
          <strong>Prénom :</strong> <span class="field">{{ $prenom }}</span><br>
          <strong>N° Apogée :</strong> <span class="field">{{ $apogee }}</span><br>
          <strong>CIN :</strong> <span class="field">{{ $cin }}</span>
        </p>

        <table class="table" style="margin-top: 20px;">
          <thead>
            <tr>
              <th>Code</th>
              <th>Module</th>
              <th>Note</th>
              <th>Résultat</th>
            </tr>
          </thead>
          <tbody>
            @if (!empty($notes))
              @foreach ($notes as $n)
                <tr>
                  <td>{{ $n['code'] }}</td>
                  <td>{{ $n['module'] }}</td>
                  <td>{{ is_null($n['note']) ? '—' : number_format($n['note'], 2, ',', ' ') }}</td>
                  <td>{{ $n['resultat'] }}</td>
                </tr>
              @endforeach
              <tr class="table-total">
                <td colspan="2" style="text-align: right;"><strong>Note générale :</strong></td>
                <td colspan="2"><strong>{{ number_format($moyenneGenerale, 2, ',', ' ') }} / 20</strong></td>
              </tr>
              <tr class="table-total">
                <td colspan="2" style="text-align: right;"><strong>Résultat :</strong></td>
                <td colspan="2">
                  <strong>
                    @if ($countNotes > 0)
                      @if ($moyenneGenerale >= 10)
                        Admis(e)
                      @else
                        Non admis(e)
                      @endif
                    @else
                      En attente
                    @endif
                  </strong>
                </td>
              </tr>
            @else
              <tr><td colspan="4" style="text-align:center;">Aucune note trouvée pour l'année sélectionnée</td></tr>
            @endif
          </tbody>
        </table>

      @endif
    </div>

    <div class="footer">
      <p>Document généré par le Service de Scolarité</p>
      <p>Date de génération : {{ $now->format('d/m/Y à H:i') }}</p>
    </div>
  </div>
</body>
</html>