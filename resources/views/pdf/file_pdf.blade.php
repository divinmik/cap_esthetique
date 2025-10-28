<!DOCTYPE html>
<html lang="fr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>

  <!-- Favicons -->{{--
  <link href=" {{asset('asset/img/favicon.png')}}" rel="icon"> --}}

  <style>
      body{
        font-family: "arial",'sans-serif';
      }
      .container {
    max-width: 960px;
    width: 100%;
  padding-right: 15px;
  padding-left: 15px;
  margin-right: auto;
  margin-left: auto;
  }

.row {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}
.text-center {
    text-align: center !important;
}
.text-center {
    text-align: center !important;
}
.col-1 {
  -ms-flex: 0 0 8.333333%;
  flex: 0 0 8.333333%;
  max-width: 8.333333%;
}

.col-2 {
  -ms-flex: 0 0 16.666667%;
  flex: 0 0 16.666667%;
  max-width: 16.666667%;
}

.col-3 {
  -ms-flex: 0 0 25%;
  flex: 0 0 25%;
  max-width: 25%;
}

.col-4 {
  -ms-flex: 0 0 33.333333%;
  flex: 0 0 33.333333%;
  max-width: 33.333333%;
}

.col-5 {
  -ms-flex: 0 0 41.666667%;
  flex: 0 0 41.666667%;
  max-width: 41.666667%;
}

.col-6 {
  -ms-flex: 0 0 50%;
  flex: 0 0 50%;
  max-width: 50%;
}

.col-7 {
  -ms-flex: 0 0 58.333333%;
  flex: 0 0 58.333333%;
  max-width: 58.333333%;
}

.col-8 {
  -ms-flex: 0 0 66.666667%;
  flex: 0 0 66.666667%;
  max-width: 66.666667%;
}

.col-9 {
  -ms-flex: 0 0 75%;
  flex: 0 0 75%;
  max-width: 75%;
}

.col-10 {
  -ms-flex: 0 0 83.333333%;
  flex: 0 0 83.333333%;
  max-width: 83.333333%;
}

.col-11 {
  -ms-flex: 0 0 91.666667%;
  flex: 0 0 91.666667%;
  max-width: 91.666667%;
}

.col-12 {
  -ms-flex: 0 0 100%;
  flex: 0 0 100%;
  max-width: 100%;
}

.uppercase{
    text-transform: uppercase;
    font-weight: bold;
}

  </style>
</head>

<body>
    <div class="container" >
      <div class="row">
        <div class="col-6 text-center" style="position:fixe;s" >
            <span style="font-weight: bold;">
                MINISTERE DE L’ENSEIGNEMENT  <br>
                TECHNIQUE ET PROFESSIONNEL<br>
                <span style="letter-spacing: -1px;">----------------</span> <br>
                CABINET<br>
            </span>
            <br><br>
            <span style="">
                CODE: <span class="uppercase" style=";border-bottom: 1px solid #000;
            line-height: 39px;"># {{ $payload['code'] }}</span>
            </span>
            <br>

        </div>
        <div class="col-1"></div>
        <div class="col-4 text-center" style="position:fixed;right: 0;top: 0px;">
           <strong>REPUBLIQUE DU CONGO</strong><br>
                     Unité*Travail*Progrès
        </div>
      </div>
      <div class="row" style="justify-content: center;">
        <center>
            <div class="col-12 text-center" style="">
                
                <img src="{{ $logo }}" alt="CAP Esthétique"
                            width="190" height="200">

                <h3 style="font-size: 23px;">ATTESTATION DE PRÉINSCRIPTION EN LIGNE <br>
                </h3>
            </div>
        </center>
      </div>
      <div class="row" style="justify-content: ;">
        <div class="col-10 text-left" style="line-height: 33px;font-size:16px;">
            
            Nom(s) : <span class="uppercase"> {{ $payload['lastname']}} </span> <br>
            Prénom(s) : <span class=""> {{ ucwords(strtolower($payload['firstname']))}} </span> <br>
            Date et lieu de naissance : <span class="uppercase"> {{ $payload['birthdate']}} </span> <span class="uppercase"> {{ $payload['birthplace']}} </span> <br>
            Téléphone : <span class="uppercase"> {{ $payload['phone']}} </span> <br>
            E-mail : <span class="uppercase"> {{ $payload['email']}} </span> <br>
            Adresse : <span class="uppercase"> {{ $payload['address']}} </span> <br>
            Niveau scolaire atteint : <span class="uppercase"> {{ $payload['level']}} </span> <br>
            Ville choisie : <span class="uppercase"> {{ $payload['city']}} </span> <br>
            Etre mis(e) en contact avec un salon partenaire : <span class="uppercase"> {{ $payload['contact_salon']}} </span> <br>
            Type de formation : <span class="uppercase"> {{ $payload['type_formation']}} </span> <br>
        </div>
        <div class="row" style="justify-content:flex-start;">
            <div class="col-7 text-right" style="line-height: 33px;font-size:16px;position: fixed;
            bottom: 10em;right:0;">
                Émis, le : <span class="uppercase">{{ $payload['date']}}</span>
                <br>
                <br>
               {{--  <strong>
                    Le Directeur des Examens et Concours
                </strong>
                <br>
                <img src="$signature}}" style="" width="100" height="100" alt="">
                <br>
                <strong>
                    Jean Pierre MBENGA

                </strong> --}}

            </div>
      </div>
        <div class="row" style="justify-content:space-between;">
            <div class="col-12 text-left" style="font-size:9px;position: fixed;
            bottom: 1em;left:0;" >
                N.B : Cette attestation n’est délivrée qu’une seule fois et ne comporte ni rature ni surcharge.
            </div>
            <div class="col-12 text-right" style="font-size:9px;position: fixed;
            bottom: 0;right:0;text-align:right;"  >
                METP
            </div>
      </div>
      <img src="{{$path}}" style="position: fixed;bottom:2em;left:2em;" width="150" height="150" alt="">
      <img src="{{ $profile_photo_path }}" style="position: fixed;bottom:29em;right:1em;" width="150" height="150" alt="{{ $profile_photo_path }}">
  </div>





</body>

</html>
