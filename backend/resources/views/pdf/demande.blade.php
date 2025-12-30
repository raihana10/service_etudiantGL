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
    .document--no-border { border: none !important; }
    
    /* Styles pour l'attestation d'admission (image.png) */
    .attestation-admission { 
      font-family: "Times New Roman", Times, serif;
      line-height: 1.5;
      padding: 20px;
    }
    
    .header-arabic {
      text-align: right;
      direction: rtl;
      font-family: "Traditional Arabic", Arial, sans-serif;
      margin-bottom: 30px;
    }
    
    .university-title {
      text-align: center;
      font-weight: bold;
      font-size: 14px;
      margin-bottom: 10px;
      text-transform: uppercase;
    }
    
    .attestation-title {
      text-align: center;
      font-weight: bold;
      font-size: 16px;
      text-decoration: underline;
      margin: 25px 0;
      text-transform: uppercase;
    }
    
    .student-info {
      margin-left: 40px;
      margin-bottom: 20px;
    }
    
    .info-line {
      margin-bottom: 8px;
    }
    
    .info-label {
      display: inline-block;
      min-width: 200px;
      font-weight: bold;
    }
    
    .underline-field {
      border-bottom: 1px solid #000;
      padding: 0 10px;
      min-width: 250px;
      display: inline-block;
      text-align: center;
    }
    
    .date-place {
      margin-top: 40px;
      text-align: right;
      font-weight: bold;
    }
    
    .director-signature {
      margin-top: 60px;
      text-align: right;
    }
    
    .student-number {
      margin-top: 10px;
      font-size: 12px;
      text-align: center;
    }
    
    /* Styles pour l'attestation de scolarité et relevé de notes */
    .attestation-scolarite {
      font-family: Arial, sans-serif;
      font-size: 12px;
    }
    
    .header-royaume {
      text-align: center;
      font-weight: bold;
      font-size: 14px;
      margin-bottom: 5px;
    }
    
    .header-university {
      text-align: center;
      font-weight: bold;
      font-size: 12px;
      margin-bottom: 5px;
    }
    
    .header-school {
      text-align: center;
      font-weight: bold;
      font-size: 11px;
      margin-bottom: 10px;
      text-decoration: underline;
    }
    
    .document-title {
      text-align: center;
      font-weight: bold;
      font-size: 14px;
      text-decoration: underline;
      margin: 15px 0;
      text-transform: uppercase;
    }
    
    .student-details {
      margin: 15px 0;
      line-height: 1.8;
    }
    
    .field-space {
      border-bottom: 1px solid #000;
      padding: 0 5px;
      min-width: 200px;
      display: inline-block;
    }
    
    .signature-date {
      margin-top: 40px;
      text-align: right;
      font-size: 11px;
    }
    
    .footer-info {
      margin-top: 30px;
      font-size: 10px;
      text-align: center;
      border-top: 1px solid #ccc;
      padding-top: 5px;
    }
    
    /* New Design for Relevé de Notes */
    .releve-new-container {
        font-family: Arial, sans-serif;
        font-size: 11px;
        color: #000;
    }
    .releve-header-table { width: 100%; border-bottom: 2px solid #000; margin-bottom: 10px; padding-bottom: 5px; }
    .releve-header-univ { font-weight: bold; font-size: 12px; }
    .releve-header-arabic { direction: rtl; font-family: 'DejaVu Sans', sans-serif; font-size: 13px; font-weight: bold; }
    
    .releve-title-shaded {
        background-color: #f2f2f2;
        border: 1px solid #000;
        padding: 5px 20px;
        text-align: center;
        font-weight: bold;
        font-size: 15px;
        margin: 10px auto;
        width: 60%;
    }
    .releve-session-box {
        border: 1px solid #000;
        padding: 3px 15px;
        display: inline-block;
        font-weight: bold;
        margin-bottom: 20px;
    }
    
    .releve-student-info { margin-bottom: 20px; line-height: 1.6; }
    .releve-student-name { font-weight: bold; font-size: 13px; margin-bottom: 8px; }
    
    .releve-grades-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
    .releve-grades-table th { border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 5px; text-align: left; }
    .releve-grades-table td { padding: 4px 5px; border-bottom: 1px dotted #ccc; }
    
    .releve-result-line {
        border-top: 2px solid #000;
        padding-top: 10px;
        font-weight: bold;
        font-size: 12px;
        margin-top: 10px;
    }
    
    .releve-sig-area { margin-top: 150px; text-align: center; } /* Increased margin to push signature down */
    .releve-fait-a { margin-bottom: 50px; font-weight: bold; }
    .releve-footer-msg { font-size: 9px; font-style: italic; border-top: 1px solid #ccc; padding-top: 5px; margin-top: 30px; }

    /* New Design for Convention de Stage */
    .convention-new { 
        font-family: 'Times New Roman', Times, serif; 
        font-size: 13px; 
        line-height: 1.4; 
        color: #000;
    }
    .conv-header-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
    .conv-header-left { text-align: left; width: 40%; vertical-align: top; }
    .conv-header-center { text-align: center; width: 20%; vertical-align: top; }
    .conv-header-right { text-align: right; width: 40%; vertical-align: top; direction: rtl; font-family: 'DejaVu Sans', sans-serif; }
    .conv-logo { width: 80px; height: 80px; border: 1px dashed #ccc; margin: 0 auto; line-height: 80px; color: #999; text-align: center; font-size: 10px; }
    
    .conv-title-doc { text-align: center; font-weight: bold; font-size: 16px; margin: 20px 0 5px 0; text-decoration: underline; }
    .conv-subtitle-doc { text-align: center; font-style: italic; margin-bottom: 20px; font-size: 11px; }
    .conv-sect-title { font-weight: bold; text-align: center; margin: 25px 0 15px 0; text-transform: uppercase; }
    .conv-party-block { margin-bottom: 25px; }
    .conv-party-head { font-weight: bold; margin-bottom: 5px; }
    .conv-denom { text-align: right; font-style: italic; margin-top: 8px; font-size: 11px; }
    
    .conv-art { margin-bottom: 20px; text-align: justify; }
    .conv-art-title { font-weight: bold; margin-bottom: 5px; }
    
    .conv-sig-section { margin-top: 40px; }
    .conv-sig-table { width: 100%; border-collapse: collapse; }
    .conv-sig-table td { width: 50%; vertical-align: top; padding-bottom: 30px; }
    .conv-sig-label-text { font-weight: bold; margin-bottom: 10px; display: block; }
    .conv-sig-box-draw { border-bottom: 1px solid #000; height: 60px; width: 90%; margin-bottom: 5px; }
    .conv-page-break { page-break-after: always; }
    .conv-footer-note-text { font-style: italic; font-size: 11px; margin-top: 5px; }

    /* Littéral design for Attestation de Réussite */
    .reussite-container {
      font-family: Arial, sans-serif;
      text-align: center;
      position: relative;
      min-height: 900px;
    }
    .reussite-container .univ-header {
      font-size: 16px;
      font-weight: bold;
      margin-bottom: 5px;
      text-transform: uppercase;
    }
    .reussite-container .univ-arabic {
      font-family: 'DejaVu Sans', serif;
      font-size: 15px;
      font-weight: bold;
      margin-bottom: 20px;
      direction: rtl;
    }
    .reussite-container .title-box {
      border: 1px solid #000;
      display: inline-block;
      padding: 4px 40px;
      font-weight: bold;
      font-size: 16px;
      margin-bottom: 40px;
    }
    .reussite-container .intro-text {
      font-size: 13px;
      margin-bottom: 25px;
    }
    .reussite-container .bold-data {
      font-weight: bold;
      font-size: 15px;
      margin-bottom: 8px;
    }
    .reussite-container .phrase {
      font-size: 14px;
      margin: 15px 0 10px 0;
    }
    .reussite-container .diagonal-box {
      margin: 60px 0;
      height: 350px;
      position: relative;
    }
    .reussite-container .diagonal-line {
      position: absolute;
      top: 0;
      left: 300px;
      width: 1px;
      height: 450px;
      background-color: #000;
      transform: rotate(35deg);
      transform-origin: top left;
    }
    .reussite-container .signature-area {
      text-align: right;
      margin-right: 40px;
    }
    .reussite-container .fait-a {
      font-size: 13px;
      margin-bottom: 100px;
    }
    .reussite-container .director-title {
      font-size: 13px;
      margin-bottom: 50px;
      margin-right: 20px;
    }
    .reussite-container .director-name {
      font-weight: bold;
      font-size: 14px;
      margin-right: 10px;
    }
    .reussite-container .footer-literal {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      text-align: left;
    }
    .reussite-container .student-id {
      position: absolute;
      bottom: 40px;
      left: 0;
      font-size: 13px;
    }
    .reussite-container .bottom-dir {
      position: absolute;
      bottom: 40px;
      right: 40px;
      font-size: 13px;
    }
    .reussite-container .avis-footer {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      text-align: center;
      font-size: 10px;
      border-top: none;
    }

    /* Styles for new Attestation de Scolarité */
    .scolarite-new {
        font-family: 'Times New Roman', serif;
        font-size: 14px;
        line-height: 1.5;
        direction: ltr;
        color: #000;
    }
    .scolarite-new .header-table {
        width: 100%;
        margin-bottom: 30px;
        border-collapse: collapse;
    }
    .scolarite-new .header-left-col {
        text-align: left;
        font-weight: bold;
        line-height: 1.5;
        width: 40%;
        vertical-align: top;
    }
    .scolarite-new .header-center-col {
        text-align: center;
        width: 20%;
        vertical-align: top;
    }
    .scolarite-new .header-right-col {
        text-align: right;
        direction: rtl;
        font-family: 'DejaVu Sans', serif;
        font-size: 14px;
        line-height: 1.5;
        width: 40%;
        vertical-align: top;
    }
    .scolarite-new .logo-box {
        width: 80px;
        height: 80px;
        border: 1px dashed #ccc;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #999;
        font-size: 10px;
    }
    .scolarite-new .title-underlined {
        text-align: center;
        font-weight: bold;
        text-decoration: underline;
        font-size: 16px;
        margin: 30px 0;
        text-transform: uppercase;
    }
    .scolarite-new .intro-phrase {
        margin: 30px 0 15px 0;
        line-height: 1.8;
    }
    .scolarite-new .info-list {
        margin-left: 40px;
        line-height: 1.8;
        margin-bottom: 30px;
    }
    .scolarite-new .info-item {
        margin-bottom: 5px;
    }
    .scolarite-new .signature-block {
        text-align: right;
        margin-top: 50px;
        font-weight: bold;
    }
    .scolarite-new .underline-num {
        margin-top: 10px;
        text-decoration: underline;
    }
    .scolarite-new .footer-table {
        width: 100%;
        margin-top: 60px;
        border-top: 1px solid #000;
        padding-top: 15px;
        border-collapse: collapse;
    }
    .scolarite-new .footer-left-col {
        text-align: left;
        font-size: 11px;
        width: 50%;
    }
    .scolarite-new .footer-right-col {
        text-align: right;
        direction: rtl;
        font-family: 'DejaVu Sans', serif;
        font-size: 11px;
        width: 50%;
    }
    .scolarite-new .warning-text {
        text-align: center;
        margin-top: 40px;
        font-style: italic;
        border-top: 1px solid #000;
        padding-top: 10px;
        font-size: 11px;
    }
  </style>
</head>
<body>
  <div class="document {{ $demande->typeDoc === 'AttestationReussite' ? 'document--no-border' : '' }}">
    @php
      $type = $demande->typeDoc;
      $nom = optional($etudiant)->nom ?? 'N/A';
      $prenom = optional($etudiant)->prenom ?? 'N/A';
      $apogee = optional($etudiant)->numApogee ?? 'N/A';
      $cin = optional($etudiant)->CIN ?? optional($etudiant)->cin ?? 'N/A';
      $dateNaissance = optional($etudiant)->dateNaissance ? optional($etudiant)->dateNaissance->format('d/m/Y') : 'N/A';
      $lieuNaissance = optional($etudiant)->lieuNaissance ?? 'N/A';
      $cne = optional($etudiant)->numApogee ?? 'N/A';
      
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

    @if ($type === 'AttestationReussite')
      <div class="reussite-container">
        <div class="univ-header">UNIVERSITÉ ABDELMALEK ESSAÂDI</div>
        <div class="univ-arabic">ﻱﺪﻌﺴﻟا ﻚﻟﺎﻤﻟا ﺪﺒﻋ ﺔﻌﻣﺎﺟ</div>
        
        <div class="title-box">
          ATTESTATION DE REUSSITE
        </div>
        
        <div class="intro-text">
          Le Directeur de l'Ecole Nationale des Sciences Appliquées de Tétouan atteste que
        </div>
        
        <div class="bold-data">
          @php
            $gender = (strpos(strtolower($nom), 'kaoutar') !== false || strpos(strtolower($prenom), 'kaoutar') !== false) ? 'Mademoiselle' : 'Monsieur';
            // Note: Simple heuristic for gender, ideally this comes from DB
          @endphp
          {{ $gender }} {{ strtoupper($nom) }} {{ strtoupper($prenom) }}
        </div>
        
        <div class="bold-data">
          née le {{ $dateNaissance }} à {{ strtoupper($lieuNaissance) }}
        </div>
        
        <div class="phrase">
          a été déclarée admise au niveau
        </div>
        
        <div class="bold-data">
          @php
            $filiere = optional(optional($etudiant)->filiere)->nomF ?? 'Génie Informatique';
            $niveau = optional($etudiant)->niveau ?? "2ème Année";
          @endphp
          {{ $niveau }} du Cycle Ingénieur: {{ $filiere }}
        </div>
        
        <div class="bold-data">
          au titre de l'année universitaire {{ date('Y') }}/{{ date('Y')+1 }}
        </div>
        
        <div class="diagonal-box">
           <!-- SVG for the diagonal line to ensure compatibility -->
           <svg width="100%" height="100%" style="position: absolute; top: 0; left: 0;">
             <line x1="80%" y1="0" x2="30%" y2="100%" stroke="black" stroke-width="1" />
           </svg>
        </div>
        
        <div class="signature-area">
          <div class="fait-a">
            Fait à TETOUAN, le {{ date('d') }} {{ strftime('%B', time()) }} {{ date('Y') }}
          </div>
          
          <div class="director-title">Le Directeur</div>
          <div class="director-name">Kamal REKLAOUI</div>
        </div>
        
        <div class="student-id">
          N° étudiant : &nbsp; {{ $apogee }}
        </div>
        
        <div class="bottom-dir">
          Le Directeur
        </div>
        
        <div class="avis-footer">
          Avis important: Il ne peut être délivré qu'un seul exemplaire de cette attestation. Aucun duplicata ne sera fourni.
        </div>
      </div>

    @elseif ($type === 'AttestationScolarite')
      <div class="scolarite-new">
        <table class="header-table">
            <tr>
                <td class="header-left-col">
                    ROYAUME DU MAROC<br>
                    Université Abdelmalek Essaadi<br>
                    Ecole Nationale des Sciences<br>
                    Appliquées<br>
                    Tétouan<br>
                    Service des Affaires Estudiantines
                </td>
                <td class="header-center-col">
                    <div class="logo-box">LOGO</div>
                </td>
                <td class="header-right-col">
                    ﺔـﻴـﺑﺮـﻐـﻤـﻟا ﺔـﻜـﻠـﻤـﻤـﻟا<br>
                    ﻱﺪﻌﺴﻟا ﻚﻟﺎﻤﻟا ﺪﺒﻋ ﺔﻌﻣﺎﺟ<br>
                    ﺔﻴﻘﻴﺒﻄﺘﻟا مﻮﻠﻌﻠﻟ ﺔﻴﻨﻄﻮﻟا ﺔﺳﺮﺪﻤﻟا<br>
                    ناوﻄﺗ<br>
                    ﺔﻴﺑﻼﻄﻟا نوﺆﺸﻟا ﺔﺤﻠﺼﻣ
                </td>
            </tr>
        </table>

        <div class="title-underlined">
            ATTESTATION DE SCOLARITE
        </div>

        <div class="intro-phrase">
            Le Directeur de l'Ecole Nationale des Sciences Appliquées de Tétouan atteste que l'étudiant :
        </div>

        <div class="info-list">
            @php
              $gender = (strpos(strtolower($nom), 'kaoutar') !== false || strpos(strtolower($prenom), 'kaoutar') !== false) ? 'Mademoiselle' : 'Monsieur';
            @endphp
            <div class="info-item">{{ $gender }} <strong>{{ strtoupper($nom) }} {{ strtoupper($prenom) }}</strong></div>
            <div class="info-item">Numéro de la carte d'identité nationale : <strong>{{ $cin }}</strong></div>

            <div class="info-item">Code national de l'étudiant : <strong>{{ $cne }}</strong></div>
            <div class="info-item">né le <strong>{{ $dateNaissance }}</strong> à <strong>{{ strtoupper($lieuNaissance) }}</strong></div>
            <div class="info-item">Poursuit ses études à l' Ecole Nationale des Sciences Appliquées Tétouan pour l'année universitaire <strong>{{ date('Y') }}/{{ date('Y')+1 }}</strong>.</div>
            <br>
            <div class="info-item">Diplôme : <strong>Cycle Ingénieur</strong></div>
            <div class="info-item">Filière : <strong>{{ optional(optional($etudiant)->filiere)->nomF ?? 'Génie Informatique' }}</strong></div>
            <div class="info-item">Année : <strong>{{ optional($etudiant)->niveau ?? '1ère' }} année</strong></div>
        </div>

        <div class="signature-block">
            Fait à TETOUAN, le {{ date('d/m/Y') }}<br>
            <br>
            Le Directeur<br>
            Service des Affaires Estudiantines<br>
            <div class="underline-num">
                N°étudiant: {{ $apogee }}
            </div>
        </div>

        <table class="footer-table">
            <tr>
                <td class="footer-left-col">
                    Adresse : M'Hannech II<br>
                    B.P. 2222 Tétouan<br>
                    Tél: 0539968802 FAX : 0539994624
                </td>
                <td class="footer-right-col">
                    ﻲﻧﺎﺜﻟا ﺶﻨﺤﻤﻟا :ناﻮﻨﻌﻟا<br>
                    ص.ب 2222 ناوﻄﺗ<br>
                    0539994624 • 0539968802 :ﺲﻛﺎﻔﻠﺗ
                </td>
            </tr>
        </table>

        <div class="warning-text">
            Le présent document n'est délivré qu'en un seul exemplaire.<br>
            Il appartient à l'étudiant d'en faire des photocopies certifiées conformes.
        </div>
      </div>

    @elseif ($type === 'ReleveNote')
      <div class="releve-new-container">
        <table class="releve-header-table" style="border-bottom: none;">
          <tr>
            <td class="releve-header-univ" style="text-align: left; width: 50%;">Université Abdelmalek Essaâdi</td>
            <td style="text-align: right; width: 50%;" class="releve-header-arabic">
                ﻱﺪﻌﺴﻟا ﻚﻟﺎﻤﻟا ﺪﺒﻋ ﺔﻌﻣﺎﺟ
            </td>
          </tr>
        </table>
        
        <table style="width: 100%; margin-bottom: 10px;">
          <tr>
            <td style="text-align: center; font-size: 11px;">
              Année universitaire &nbsp; <strong>{{ optional($releve)->annee ?? date('Y').'/'.(date('Y')+1) }}</strong> &nbsp; <span class="releve-header-arabic">ﺔـﻴـﻌـﻣﺎـﺠـﻟا ﺔـﻨـﺴـﻟا</span>
            </td>
          </tr>
        </table>

        <table style="width: 100%; border-top: 1px solid #000; padding-top: 5px;">
          <tr>
            <td style="font-weight: bold; text-align: left; width: 50%;">Ecole Nationale des Sciences Appliquées Tétouan</td>
            <td style="text-align: right; width: 50%; font-weight: bold;" class="releve-header-arabic">
                ﺔﻴﻘﻴﺒﻄﺘﻟا مﻮﻠﻌﻠﻟ ﺔﻴﻨﻄﻮﻟا ﺔﺳﺮﺪﻤﻟا ناوﻄﺗ
            </td>
          </tr>
        </table>

        <div style="text-align: center; position: relative; margin-top: 20px;">
          <div class="releve-title-shaded">RELEVE DE NOTES ET RESULTATS</div>
          <div style="position: absolute; top: 10px; right: 0; font-weight: bold;">Page : 1 / 1</div>
        </div>

        <div style="text-align: center;">
          <div class="releve-session-box">Session 1</div>
        </div>

        <div class="releve-student-info">
          <div class="releve-student-name">{{ strtoupper($nom) }} {{ $prenom }}</div>
          <table style="width: 100%;">
            <tr>
              <td style="width: 40%;">N° Etudiant : &nbsp; <strong>{{ $apogee }}</strong></td>
              <td style="width: 60%;">CNE : &nbsp; <strong>{{ $cne }}</strong></td>
            </tr>
            <tr>
              <td>Né le : &nbsp; <strong>{{ $dateNaissance }}</strong></td>
              <td>à : &nbsp; <strong>{{ strtoupper($lieuNaissance) }}</strong></td>
            </tr>
          </table>
          <div style="margin-top: 5px;">
            inscrit en &nbsp; <strong style="font-size: 12px;">{{ optional($etudiant)->niveau ?? 'N/A' }}</strong>
          </div>
          <div style="margin-top: 8px;">
            a obtenu les notes suivantes :
          </div>
        </div>

        <table class="releve-grades-table">
          <thead>
            <tr>
              <th style="width: 50%;">Modules</th>
              <th style="width: 20%; text-align: center;">Note/Barème</th>
              <th style="width: 15%; text-align: center;">Résultat</th>
              <th style="width: 15%; text-align: center;">Session</th>
            </tr>
          </thead>
          <tbody>
            @if (!empty($notes))
              @foreach ($notes as $n)
                <tr>
                  <td>{{ $n['module'] }}</td>
                  <td style="text-align: center;">{{ is_null($n['note']) ? '—' : number_format($n['note'], 2) }} / 20</td>
                  <td style="text-align: center;">{{ $n['resultat'] ?? 'Validé' }}</td>
                  <td style="text-align: center;">S1 {{ substr(optional($releve)->annee, 2, 2) }}/{{ substr(optional($releve)->annee, 7, 2) }}</td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="4" style="text-align: center; padding: 20px;">Aucune note disponible</td>
              </tr>
            @endif
          </tbody>
        </table>

        @if (!empty($notes))
          <div class="releve-result-line">
            <table style="width: 100%;">
              <tr>
                <td style="width: 40%;">Résultat d'admission session 1 :</td>
                <td style="width: 15%;">{{ number_format($moyenneGenerale, 3, '.', '') }} / 20</td>
                <td style="width: 15%;">{{ $moyenneGenerale >= 10 ? 'Admis' : 'Non Admis' }}</td>
                <td style="width: 15%;">
                  @if ($moyenneGenerale >= 16) Très Bien
                  @elseif ($moyenneGenerale >= 14) Bien
                  @elseif ($moyenneGenerale >= 12) Assez Bien
                  @elseif ($moyenneGenerale >= 10) Passable
                  @endif
                </td>
                <td style="text-align: right;">{{ count($notes) }}/{{ count($notes) * 30 }}</td>
              </tr>
            </table>
          </div>
        @endif

        <div class="releve-sig-area">
          <div class="releve-fait-a">Fait à TETOUAN, le {{ date('d/m/Y') }}</div>
          
          <div style="text-align: left; margin-left: 50px;">
            <div style="font-weight: bold;">Le Directeur de l'Ecole Nationale des Sciences Appliquées de Tétouan</div>
            <div style="margin-top: 20px; font-weight: bold;">Le Directeur</div>
          </div>
        </div>

        <div class="releve-footer-msg">
          Avis important : Il ne peut être délivré qu'un seul exemplaire du présent relevé de note. Aucun duplicata ne sera fourni.
        </div>
      </div>

    @elseif ($type === 'ConventionStage')
      <div class="convention-new">
        <table class="conv-header-table">
          <tr>
            <td class="conv-header-left">
              Université Abdelmalek Essaâdi<br>
              Ecole Nationale des Sciences Appliquées<br>
              Tétouan
            </td>
            <td class="conv-header-center">
              <div class="conv-logo">LOGO</div>
            </td>
            <td class="conv-header-right">
              ﻱﺪﻌﺴﻟا ﻚﻟﺎﻤﻟا ﺪﺒﻋ ﺔﻌﻣﺎﺟ<br>
              ﺔﻴﻘﻴﺒﻄﺘﻟا مﻮﻠﻌﻠﻟ ﺔﻴﻨﻄﻮﻟا ﺔﺳﺮﺪﻤﻟا<br>
              ناوﻄﺗ
            </td>
          </tr>
        </table>

        <div class="conv-title-doc">CONVENTION DE STAGE</div>
        <div class="conv-subtitle-doc">(2 exemplaires imprimés en recto-verso)</div>

        <div class="conv-sect-title">ENTRE</div>

        <div class="conv-party-block">
          <div class="conv-party-head">L'Ecole Nationale des Sciences Appliquées, Université Abdelmalek Essaâdi - Tétouan</div>
          B.P. 2222, Mhamech II, Tétouan , Maroc<br>
          Tél. +212 5 39 68 80 27 ; Fax. +212 39 99 46 24. Web: https://ensa-tetouan.ac.ma<br>
          Représenté par le Professeur Kamal REKLAOUI en qualité de Directeur.
          <div class="conv-denom">Ci-après, dénommé l'Etablissement</div>
        </div>

        <div class="conv-sect-title">ET</div>

        <div class="conv-party-block">
          <div class="conv-party-head">La Société : {{ optional($convention)->raisonSocialeEntreprise ?? 'N/A' }}</div>
          Adresse : {{ optional($convention)->adresseEntreprise ?? 'N/A' }}<br>
          Tél : {{ optional($convention)->TLEntreprise ?? 'N/A' }}<br>
          Email : {{ optional($convention)->emailEntreprise ?? 'N/A' }}<br>
          Représentée par Monsieur {{ optional($convention)->representantEntreprise ?? 'N/A' }} en qualité de {{ optional($convention)->fctRepresentant ?? 'N/A' }}
          <div class="conv-denom">Ci-après dénommée L'ENTREPRISE</div>
        </div>

        <div class="conv-art">
          <div class="conv-art-title">Article 1 : Engagement</div>
          L'ENTREPRISE accepte de recevoir à titre de stagiaire <strong>{{ strtoupper($nom) }} {{ strtoupper($prenom) }}</strong> étudiant de la filière du Cycle Ingénieur « {{ optional(optional($etudiant)->filiere)->nomF ?? 'N/A' }} » de l'ENSA de Tétouan, Université Abdelmalek Essaâdi (Tétouan), pour une période allant du <strong>{{ optional($convention)->dateDebut ? \Carbon\Carbon::parse($convention->dateDebut)->format('d/m/Y') : 'N/A' }}</strong> au <strong>{{ optional($convention)->dateFin ? \Carbon\Carbon::parse($convention->dateFin)->format('d/m/Y') : 'N/A' }}</strong><br>
          En aucun cas, cette convention ne pourra autoriser les étudiants à s'absenter durant la période des contrôles ou des enseignements.
        </div>

        <div class="conv-art">
          <div class="conv-art-title">Article 2 : Objet</div>
          Le stage aura pour objet essentiel d'assurer l'application pratique de l'enseignement donné par l'Etablissement, et ce, en organisant des visites sur les installations et en réalisant des études proposées par L'ENTREPRISE.
        </div>

        <div class="conv-art">
          <div class="conv-art-title">Article 3 : Encadrement et suivi</div>
          Pour accompagner le Stagiaire durant son stage, et ainsi instaurer une véritable collaboration L'ENTREPRISE/Stagiaire/Etablissement, L'ENTREPRISE désigne Mme/Mr <strong>{{ optional($convention)->encadrantEntreprise ?? 'N/A' }}</strong> encadrant(e) et parrain(e), pour superviser et assurer la qualité du travail fourni par le Stagiaire.<br>
          L'Etablissement désigne <strong>{{ optional($convention)->encadrantAcademique ?? 'N/A' }}</strong> en tant que tuteur qui procurera une assistance pédagogique.
        </div>

        <div class="conv-art">
          <div class="conv-art-title">Article 4 : Programme</div>
          Le thème du stage est: « <strong>{{ optional($convention)->sujetStage ?? 'N/A' }}</strong> »<br>
          Ce programme a été défini conjointement par l'Etablissement, L'ENTREPRISE et le Stagiaire.<br>
          Le contenu de ce programme doit permettre au Stagiaire une réflexion en relation avec les enseignements ou le projet de fin d'études qui s'inscrit dans le programme de formation de l'Etablissement.
        </div>

        <div class="conv-page-break"></div>

        <!-- Deuxième page -->
        <div class="conv-art">
          <div class="conv-art-title">Article 5 : Indemnité de stage</div>
          Au cours du stage, l'étudiant ne pourra prétendre à aucun salaire de la part de L'ENTREPRISE.<br>
          Cependant, si l'ENTREPRISE et l'étudiant le conviennent, ce dernier pourra recevoir une indemnité forfaitaire de la part de l'ENTREPRISE des frais occasionnés par la mission confiée à l'étudiant.
        </div>

        <div class="conv-art">
          <div class="conv-art-title">Article 6 : Règlement</div>
          Pendant la durée du stage, le Stagiaire reste placé sous la responsabilité de l'Etablissement.<br>
          <strong>Cependant, l'étudiant est tenu d'informer l'école dans un délai de 24h sur toute modification portant sur la convention déjà signée, sinon il en assumera toute sa responsabilité sur son non-respect de la convention signée par l'école.</strong><br><br>
          Toutefois, le Stagiaire est soumis à la discipline et au règlement intérieur de L'ENTREPRISE.<br>
          En cas de manquement, L'ENTREPRISE se réserve le droit de mettre fin au stage après en avoir convenu avec le Directeur de l'Etablissement.
        </div>

        <div class="conv-art">
            <div class="conv-art-title">Article 7 : Confidentialité</div>
            Le Stagiaire et l'ensemble des acteurs liés à son travail (l'administration de l'Etablissement, le parrain
            pédagogique ...) sont tenus au secret professionnel. Ils s'engagent à ne pas diffuser les informations
            recueillies à des fins de publications, conférences, communications, sans raccord préalable de
            L'ENTREPRISE. Cette obligation demeure valable après l'expiration du stage.
        </div>

        <div class="conv-art">
            <div class="conv-art-title">Article 8 : Assurance accident de travail</div>
            Le stagiaire devra obligatoirement souscrire une assurance couvrant la Responsabilité Civile et
            Accident de Travail, durant les stages et trajets effectués.<br>
            En cas d'accident de travail survenant durant la période du stage, L'ENTREPRISE s'engage à faire
            parvenir immédiatement à l'Etablissement toutes les informations indispensables à la déclaration dudit
            accident.
        </div>

        <div class="conv-art">
            <div class="conv-art-title">Article 9 : Evaluation de L'ENTREPRISE</div>
            Le stage accompli, le parrain établira un rapport d'appréciations générales sur le travail effectué et le
            comportement du Stagiaire durant son séjour chez L'ENTREPRISE.<br>
            L'ENTREPRISE remettra au Stagiaire une attestation indiquant la nature et la durée des travaux
            effectués.
        </div>

        <div class="conv-art">
            <div class="conv-art-title">Article 10 : Rapport de stage</div>
            A l'issue de chaque stage, le Stagiaire rédigera un rapport de stage faisant état de ses travaux et de son
            vécu au sein de L'ENTREPRISE. Ce rapport sera communiqué à L'ENTREPRISE et restera strictement
            confidentiel.
        </div>

        <div class="conv-sig-section">
          <div style="margin-bottom: 20px;">Fait à Tétouan en deux exemplaires, le {{ date('d/m/Y') }}</div>
          
          <table class="conv-sig-table">
            <tr>
              <td>
                <span class="conv-sig-label-text">Nom et signature du Stagiaire</span>
                <div class="conv-sig-box-draw"></div>
                <span class="conv-footer-note-text">Le Coordonnateur de la filière</span>
                <div class="conv-sig-box-draw"></div>
              </td>
              <td>
                <span class="conv-sig-label-text">Signature et cachet de L'Etablissement</span>
                <div class="conv-sig-box-draw"></div>
              </td>
            </tr>
            <tr>
              <td>
                <span class="conv-sig-label-text">Signature et cachet de L'ENTREPRISE</span>
                <div class="conv-sig-box-draw"></div>
              </td>
              <td></td>
            </tr>
          </table>
        </div>
      </div>
    @endif
  </div>
</body>
</html>